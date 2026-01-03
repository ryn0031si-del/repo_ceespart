<?php
session_start(); // Pastikan session dimulai
require_once 'config.php';

// 1. PROTEKSI LOGIN
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) { 
    header("location: login.php"); 
    exit; 
}

// =========================================================
// 🆕 LOGIKA UPDATE JUMLAH (TAMBAH/KURANG)
// =========================================================
if (isset($_GET['act']) && isset($_GET['id'])) {
    $id_prod = $_GET['id'];
    $act = $_GET['act'];

    if (isset($_SESSION['keranjang'][$id_prod])) {
        if ($act == 'plus') {
            $_SESSION['keranjang'][$id_prod]['jumlah'] += 1;
        } elseif ($act == 'min') {
            $_SESSION['keranjang'][$id_prod]['jumlah'] -= 1;
            // Jika jumlah 0, hapus dari keranjang
            if ($_SESSION['keranjang'][$id_prod]['jumlah'] <= 0) {
                unset($_SESSION['keranjang'][$id_prod]);
            }
        }
    }
    // Redirect kembali ke halaman ini agar URL bersih
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ---------------------------------------------------------
// 2. LOGIKA PROSES SIMPAN PESANAN
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi_checkout'])) {
    $id_user = $_SESSION['id_user'];
    
    // Hitung ulang total harga di backend untuk keamanan
    $total_harga_fix = 0;
    foreach ($_SESSION['keranjang'] as $itm) {
        $total_harga_fix += $itm['harga'] * $itm['jumlah'];
    }

    $metode_pembayaran = $_POST['metode_pembayaran'];
    $nama_penerima = $_POST['nama_penerima'];
    $no_hp_penerima = $_POST['no_hp_penerima'];
    $alamat_penerima = $_POST['alamat_penerima'];
    
    $nama_file_bukti = null;

    if ($metode_pembayaran !== 'COD' && isset($_FILES['bukti_transaksi'])) {
        $file = $_FILES['bukti_transaksi'];
        if ($file['error'] === 0) {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $nama_file_bukti = "bukti_" . time() . "_" . uniqid() . "." . $ext;
            // Pastikan folder assets/bukti_transaksi/ sudah dibuat
            move_uploaded_file($file['tmp_name'], "assets/bukti_transaksi/" . $nama_file_bukti);
        }
    }

    mysqli_begin_transaction($conn);
    try {
        $sql_p = "INSERT INTO pesanan (id_user, total_harga, status_pesanan, tanggal_pesanan, bukti_pembayaran, metode_pembayaran, nama_penerima, no_hp_penerima, alamat_penerima) 
                  VALUES (?, ?, 'Menunggu Pembayaran', NOW(), ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql_p);
        mysqli_stmt_bind_param($stmt, "idsssss", 
            $id_user, $total_harga_fix, $nama_file_bukti, $metode_pembayaran,
            $nama_penerima, $no_hp_penerima, $alamat_penerima
        );
        
        mysqli_stmt_execute($stmt);
        $id_pesanan = mysqli_insert_id($conn);

        $stmt_d = mysqli_prepare($conn, "INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah, harga_saat_pesan) VALUES (?, ?, ?, ?)");
        foreach ($_SESSION['keranjang'] as $id_produk => $item) {
            mysqli_stmt_bind_param($stmt_d, "iiid", $id_pesanan, $id_produk, $item['jumlah'], $item['harga']);
            mysqli_stmt_execute($stmt_d);
        }
        
        unset($_SESSION['keranjang']);
        mysqli_commit($conn);
        
        header("Location: pesanan.php?success=1");
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        die("❌ Terjadi kesalahan: " . $e->getMessage());
    }
}

// 3. LOGIKA TAMPILAN
$id_user = $_SESSION["id_user"];
$stmt = $conn->prepare("SELECT * FROM users WHERE id_user=?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (empty($_SESSION['keranjang'])) {
    echo "<script>alert('Keranjang anda kosong!');location='produk.php';</script>";
    exit;
}

$total_harga = 0;
foreach ($_SESSION['keranjang'] as $i) $total_harga += $i['harga'] * $i['jumlah'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Checkout - Ce’3s Part</title>
    <style>
        :root {
            --primary-color: #090a1a;
            --secondary-color: #253560;
            --accent-color: #cbd5e1;
            --highlight-color: #a0c4ff;
            --card-bg: #131629;
            --border-color: #1a1d35;
            --border-radius: 12px;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background-color: var(--primary-color);
            color: var(--accent-color);
            padding: 40px 20px;
        }

        .checkout-form-container {
            background-color: var(--card-bg);
            padding: 40px;
            border-radius: var(--border-radius);
            max-width: 1100px;
            margin: 0 auto;
            border: 1px solid rgba(160, 196, 255, 0.1);
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 40px;
        }

        .checkout-section-title {
            font-size: 1.4em;
            font-weight: 800;
            color: var(--highlight-color);
            margin-bottom: 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 10px;
        }

        /* Penyeragaman Ukuran Input */
        .checkout-group { margin-bottom: 20px; }
        .checkout-group label {
            display: block;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .checkout-group input, 
        .checkout-group textarea, 
        .checkout-group select {
            width: 100%;
            height: 48px; /* Fixed height agar seragam */
            padding: 10px 15px;
            background-color: #090a1a;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            box-sizing: border-box;
        }

        textarea { height: auto !important; resize: none; }

        .checkout-summary {
            background: #0d0f1f;
            padding: 25px;
            border-radius: var(--border-radius);
            border: 1px solid var(--border-color);
        }

        .produk-item-checkout {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .produk-item-checkout img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            background: #1a1d35;
            border-radius: 8px;
        }

        /* CSS BARU UNTUK TOMBOL QTY */
        .qty-control {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 5px;
            background: #1a1d35;
            width: fit-content;
            padding: 2px;
            border-radius: 6px;
        }
        .btn-qty {
            display: inline-block;
            background: var(--secondary-color);
            color: white;
            width: 24px;
            height: 24px;
            line-height: 24px;
            text-align: center;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            transition: 0.2s;
        }
        .btn-qty:hover { background: var(--highlight-color); color: #090a1a; }
        .qty-val { font-weight: bold; min-width: 20px; text-align: center; color: white; }

        .total-payment {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px dashed var(--border-color);
            display: flex;
            justify-content: space-between;
            font-weight: 800;
        }

        .btn-checkout-submit {
            width: 100%;
            margin-top: 30px;
            background-color: var(--highlight-color);
            color: #090a1a;
            padding: 18px;
            border: none;
            border-radius: 10px;
            font-weight: 800;
            cursor: pointer;
        }

        .payment-guide-info {
            margin-top: 15px;
            padding: 15px;
            background: #1a1d35;
            border-radius: 8px;
            display: none;
        }

        @media (max-width: 992px) { .checkout-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<div class="checkout-form-container">
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="aksi_checkout" value="1">
        <div class="checkout-grid">
            <div class="checkout-left">
                <div class="checkout-section-title">Data Penerima</div>
                
                <div class="checkout-group">
                    <label>Nama Penerima</label>
                    <input type="text" name="nama_penerima" value="<?= htmlspecialchars($user['nama_lengkap'] ?? '') ?>" required>
                </div>

                <div class="checkout-group">
                    <label>Nomor HP / WhatsApp (Maks. 12 Angka)</label>
                    <input type="tel" 
                           name="no_hp_penerima" 
                           value="<?= htmlspecialchars($user['no_hp'] ?? '') ?>" 
                           required
                           maxlength="12"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           placeholder="08xxxxxxxxxx">
                </div>
                <div class="checkout-group">
                    <label>Alamat Lengkap</label>
                    <textarea name="alamat_penerima" rows="3" required><?= htmlspecialchars($user['alamat'] ?? '') ?></textarea>
                </div>

                <div class="checkout-group">
                    <label>Metode Pembayaran</label>
                    <select id="metode" name="metode_pembayaran" required onchange="togglePayment()">
                        <option value="">-- Pilih Pembayaran --</option>
                        <option value="COD">COD (Bayar di Tempat)</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="E-Wallet">E-Wallet (QRIS)</option>
                    </select>
                </div>

                <div id="payment-guide" class="payment-guide-info">
                    <div id="bank-list" style="display:none;">
                        <p><b>BCA:</b> 1234567890 (Ce3s Part)</p>
                        <p><b>BRI:</b> 9876543210 (Ce3s Part)</p>
                    </div>
                    <div id="ewallet-list" style="display:none;">
                        <p><b>DANA/GoPay:</b> 0812-3456-7890</p>
                    </div>
                    <div style="margin-top: 15px;">
                        <label style="display:block; margin-bottom:5px;">Unggah Bukti Transfer:</label>
                        <input type="file" name="bukti_transaksi" id="bukti_file">
                    </div>
                </div>
            </div>

            <div class="checkout-right">
                <div class="checkout-summary">
                    <div class="checkout-section-title">Ringkasan Belanja</div>
                    
                    <?php foreach ($_SESSION['keranjang'] as $id => $i): ?>
                        <div class="produk-item-checkout">
                            <img src="assets/images/<?= htmlspecialchars($i['gambar'] ?? 'default.png') ?>" alt="produk">

                            <div class="checkout-item-details" style="padding-left:15px; width: 100%;">
                                <h4 style="color:#fff; margin:0; font-size: 0.95rem;"><?= htmlspecialchars($i['nama']) ?></h4>
                                <div style="color: var(--highlight-color); font-size: 0.9rem; margin: 3px 0;">
                                    Rp <?= number_format($i['harga'], 0, ',', '.') ?>
                                </div>
                                
                                <div class="qty-control">
                                    <a href="?act=min&id=<?= $id ?>" class="btn-qty">-</a>
                                    <span class="qty-val"><?= $i['jumlah'] ?></span>
                                    <a href="?act=plus&id=<?= $id ?>" class="btn-qty">+</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="total-payment">
                        <span>Total Pembayaran</span>
                        <span style="color:var(--highlight-color);">Rp <?= number_format($total_harga, 0, ',', '.') ?></span>
                    </div>

                    <button type="submit" class="btn-checkout-submit">Konfirmasi Pesanan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function togglePayment() {
        const m = document.getElementById('metode').value;
        const guide = document.getElementById('payment-guide');
        const bank = document.getElementById('bank-list');
        const wallet = document.getElementById('ewallet-list');
        const file = document.getElementById('bukti_file');

        if(m === 'COD' || m === '') {
            guide.style.display = 'none';
            file.required = false;
        } else {
            guide.style.display = 'block';
            file.required = true;
            bank.style.display = (m === 'Transfer Bank') ? 'block' : 'none';
            wallet.style.display = (m === 'E-Wallet') ? 'block' : 'none';
        }
    }
</script>
</body>
</html> 