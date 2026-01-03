<?php
session_start();
require_once 'config.php';

// 🔒 1. Proteksi Login & Parameter URL
if (!isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]) { 
    header("location: login.php"); 
    exit; 
}

if (empty($_GET['id'])) { 
    header("location: pesanan.php"); 
    exit; 
}

$id_pesanan = (int)$_GET['id'];
$id_user = $_SESSION['id_user'];

// 🔎 2. Ambil data utama pesanan
$q = $conn->prepare("SELECT * FROM pesanan WHERE id_pesanan=? AND id_user=?");
$q->bind_param("ii", $id_pesanan, $id_user);
$q->execute();
$detail = $q->get_result()->fetch_assoc();
$q->close();

if (!$detail) { 
    header("location: pesanan.php"); 
    exit; 
}

// 🧾 3. Ambil item produk
$q = $conn->prepare("SELECT p.nama_produk, p.gambar_produk, dp.jumlah, dp.harga_saat_pesan 
                      FROM detail_pesanan dp 
                      JOIN produk p ON dp.id_produk=p.id_produk 
                      WHERE dp.id_pesanan=?");
$q->bind_param("i", $id_pesanan);
$q->execute();
$item_pesanan = $q->get_result()->fetch_all(MYSQLI_ASSOC);
$q->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi #<?= $id_pesanan ?> - Ce'3s Part</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* === DARK REFINED THEME === */
        :root {
            --bg-dark: #090a1a;
            --card-bg: #131629;
            --accent: #a0c4ff;
            --text-main: #cbd5e1;
            --border: rgba(160, 196, 255, 0.1);
            --success: #27ae60;
            --danger: #e74c3c;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background: var(--bg-dark);
            color: var(--text-main);
            margin: 0;
            padding: 20px;
        }

        .container { max-width: 850px; margin: 0 auto; }

        .page-header { margin-bottom: 30px; border-bottom: 1px solid var(--border); padding-bottom: 15px; }
        .page-header h2 { color: var(--accent); margin: 0; font-weight: 800; font-size: 1.5rem; }
        .page-header p { margin-top: 5px; font-size: 0.9rem; color: #64748b; }

        /* === PROGRESS TRACKER STYLING === */
        .order-tracking-wrapper {
            margin-bottom: 30px;
            padding: 30px 10px;
            background: var(--card-bg);
            border-radius: 12px;
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden; /* Mencegah scroll horizontal */
        }

        .track-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start; /* Ubah ke start agar label panjang tidak merusak align icon */
            position: relative;
            max-width: 750px;
            margin: 0 auto;
        }

        .track-line {
            position: absolute;
            top: 25px; /* Sesuaikan dengan setengah tinggi icon */
            left: 10%; /* Margin kiri kanan agar garis tidak keluar */
            width: 80%;
            height: 4px;
            background: rgba(160, 196, 255, 0.1);
            z-index: 1;
            border-radius: 4px;
        }

        .track-line-progress {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background: var(--success);
            box-shadow: 0 0 10px rgba(46, 204, 113, 0.5);
            transition: width 0.5s ease;
            border-radius: 4px;
        }

        .track-step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .track-icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #1a1d35;
            border: 4px solid var(--bg-dark); /* Border lebih tebal agar garis tertutup rapi */
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            color: #3a3f5c;
            transition: 0.3s;
            font-size: 1.2rem;
        }

        .track-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #5c6382;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.3;
        }

        .step-active .track-icon-circle {
            background: var(--success);
            color: #fff;
            box-shadow: 0 0 15px rgba(39, 174, 96, 0.4);
            border-color: var(--card-bg); /* Ubah border color agar menyatu */
        }

        .step-active .track-label { color: var(--success); }

        .status-cancelled-box {
            background: rgba(231, 76, 60, 0.1);
            border: 1px solid rgba(231, 76, 60, 0.3);
            color: var(--danger);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 800;
        }

        /* === GRID & CARDS === */
        .grid-detail {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .card {
            background: var(--card-bg);
            padding: 25px;
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        .card h3 { 
            font-size: 0.95rem; 
            color: var(--accent); 
            margin-top: 0; 
            border-bottom: 1px solid var(--border);
            padding-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .info-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.9rem; flex-wrap: wrap; gap: 5px; }
        .info-label { color: #94a3b8; }
        .info-value { color: #fff; font-weight: 600; text-align: right; word-break: break-word; }

        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            display: inline-block;
        }
        .menunggu-pembayaran { background: rgba(243, 156, 18, 0.2); color: #f39c12; }
        .diproses { background: rgba(52, 152, 219, 0.2); color: #3498db; }
        .dikirim { background: rgba(155, 89, 182, 0.2); color: #9b59b6; }
        .selesai { background: rgba(46, 204, 113, 0.2); color: #2ecc71; }
        .dibatalkan { background: rgba(231, 76, 60, 0.2); color: #e74c3c; }

        /* === TABEL PRODUK RESPONSIVE === */
        .product-list { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .product-list td { padding: 15px 0; border-bottom: 1px solid var(--border); vertical-align: middle; }
        .product-img { 
            width: 60px; height: 60px; 
            border-radius: 8px; object-fit: cover; 
            background: #1a1d35; margin-right: 15px; 
            flex-shrink: 0; /* Agar gambar tidak gepeng di HP */
        }
        
        .item-info-wrapper { display: flex; align-items: center; }

        .total-section {
            margin-top: 20px;
            padding: 20px;
            background: rgba(160, 196, 255, 0.05);
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px dashed var(--border);
        }
        .total-amount { color: var(--accent); font-size: 1.4rem; font-weight: 800; }

        .bukti-bayar-wrapper {
            width: 100%; height: 200px; /* Tinggi fix agar rapi */
            background: #090a1a;
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
            margin-top: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .bukti-bayar { width: 100%; height: 100%; object-fit: contain; cursor: pointer; }

        .btn-back {
            display: inline-flex; align-items: center; gap: 10px;
            margin-top: 30px; padding: 12px 25px;
            background: var(--accent); color: var(--bg-dark);
            text-decoration: none; border-radius: 8px;
            font-weight: 800; transition: 0.3s;
        }

        /* =========================================
           MOBILE RESPONSIVE FIX (AGAR TIDAK PECAH)
           ========================================= */
        @media (max-width: 768px) {
            .grid-detail { grid-template-columns: 1fr; } /* Stack vertikal */
            
            /* Perbaiki Tracking Bar di HP */
            .track-icon-circle { width: 40px; height: 40px; font-size: 1rem; border-width: 3px; }
            .track-line { top: 20px; } /* Sesuaikan tinggi garis */
            .track-label { font-size: 0.6rem; }
            .order-tracking-wrapper { padding: 25px 5px; } /* Kurangi padding */
        }

        @media (max-width: 480px) {
            body { padding: 10px; }
            
            /* Tracking Bar Lebih Kecil Lagi */
            .track-label { font-size: 0.55rem; } 
            
            /* Tabel Produk jadi Stack di HP Kecil */
            .product-list td { display: block; width: 100%; text-align: left; padding: 10px 0; }
            .product-list td:last-child { 
                text-align: left; 
                padding-left: 75px; /* Luruskan harga dengan teks nama produk */
                margin-top: -10px;
                color: var(--accent);
            }
            .total-section { flex-direction: column; align-items: flex-start; gap: 10px; }
            .total-amount { font-size: 1.2rem; }
            
            .btn-back { width: 100%; justify-content: center; box-sizing: border-box; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="page-header">
        <h2><i class="fas fa-file-invoice"></i> Transaksi #<?= $id_pesanan ?></h2>
        <p>Dipesan pada: <?= date("d M Y, H:i", strtotime($detail['tanggal_pesanan'])) ?></p>
    </div>

    <?php 
        $current_status = $detail['status_pesanan'];
        $progress_width = "0%";
        if($current_status == 'Diproses') $progress_width = "33%";
        if($current_status == 'Dikirim') $progress_width = "66%";
        if($current_status == 'Selesai') $progress_width = "100%";
    ?>

    <?php if($current_status == 'Dibatalkan'): ?>
        <div class="status-cancelled-box">
            <i class="fas fa-times-circle"></i> PESANAN DIBATALKAN
        </div>
    <?php else: ?>
        <div class="order-tracking-wrapper">
            <div class="track-container">
                <div class="track-line">
                    <div class="track-line-progress" style="width: <?= $progress_width ?>"></div>
                </div>

                <div class="track-step <?= (in_array($current_status, ['Menunggu Pembayaran', 'Diproses', 'Dikirim', 'Selesai'])) ? 'step-active' : '' ?>">
                    <div class="track-icon-circle"><i class="fas fa-clock"></i></div>
                    <div class="track-label">Menunggu<br>Bayar</div>
                </div>

                <div class="track-step <?= (in_array($current_status, ['Diproses', 'Dikirim', 'Selesai'])) ? 'step-active' : '' ?>">
                    <div class="track-icon-circle"><i class="fas fa-box-open"></i></div>
                    <div class="track-label">Sedang<br>Diproses</div>
                </div>

                <div class="track-step <?= (in_array($current_status, ['Dikirim', 'Selesai'])) ? 'step-active' : '' ?>">
                    <div class="track-icon-circle"><i class="fas fa-truck"></i></div>
                    <div class="track-label">Sedang<br>Dikirim</div>
                </div>

                <div class="track-step <?= ($current_status == 'Selesai') ? 'step-active' : '' ?>">
                    <div class="track-icon-circle"><i class="fas fa-check"></i></div>
                    <div class="track-label">Pesanan<br>Selesai</div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="grid-detail">
        <div class="card">
            <h3><i class="fas fa-shipping-fast"></i> Pengiriman</h3>
            <div class="info-row">
                <span class="info-label">Penerima</span>
                <span class="info-value"><?= htmlspecialchars($detail['nama_penerima']) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">No. HP</span>
                <span class="info-value"><?= htmlspecialchars($detail['no_hp_penerima']) ?></span>
            </div>
            <div style="margin-top: 10px;">
                <span class="info-label" style="display:block; margin-bottom:5px;">Alamat:</span>
                <div class="info-value" style="text-align: left; line-height: 1.5; background:rgba(255,255,255,0.05); padding:10px; border-radius:6px;">
                    <?= nl2br(htmlspecialchars($detail['alamat_penerima'])) ?>
                </div>
            </div>
            <div class="info-row" style="margin-top: 15px; align-items: center;">
                <span class="info-label">Status:</span>
                <span class="status-badge <?= strtolower(str_replace(' ', '-', $detail['status_pesanan'])) ?>">
                    <?= $detail['status_pesanan'] ?>
                </span>
            </div>
        </div>

        <div class="card">
            <h3><i class="fas fa-wallet"></i> Pembayaran</h3>
            <div class="info-row">
                <span class="info-label">Metode</span>
                <span class="info-value"><?= htmlspecialchars($detail['metode_pembayaran']) ?></span>
            </div>
            
            <div style="margin-top: 15px;">
                <span class="info-label" style="font-size: 0.8rem;">Bukti Transaksi:</span>
                <?php if (!empty($detail['bukti_pembayaran'])): ?>
                    <div class="bukti-bayar-wrapper">
                        <a href="assets/bukti_transaksi/<?= $detail['bukti_pembayaran'] ?>" target="_blank">
                            <img src="assets/bukti_transaksi/<?= $detail['bukti_pembayaran'] ?>" class="bukti-bayar">
                        </a>
                    </div>
                <?php else: ?>
                    <div style="padding: 15px; background: rgba(255,255,255,0.03); border-radius: 8px; text-align: center; margin-top: 5px; border: 1px dashed var(--border);">
                        <small style="color: #64748b;">Belum ada bukti / COD</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card">
        <h3><i class="fas fa-shopping-cart"></i> Produk</h3>
        <table class="product-list">
            <?php foreach ($item_pesanan as $item): ?>
            <tr>
                <td>
                    <div class="item-info-wrapper">
                        <img src="assets/images/<?= $item['gambar_produk'] ?>" class="product-img" onerror="this.src='assets/images/default.png'">
                        <div>
                            <div style="color: #fff; font-weight: 700; font-size: 0.95rem; line-height:1.3; margin-bottom:4px;">
                                <?= htmlspecialchars($item['nama_produk']) ?>
                            </div>
                            <div style="font-size: 0.8rem; color: #94a3b8;">
                                <?= $item['jumlah'] ?> x Rp <?= number_format($item['harga_saat_pesan'], 0, ',', '.') ?>
                            </div>
                        </div>
                    </div>
                </td>
                <td style="text-align: right; color: var(--accent); font-weight: 800; white-space: nowrap;">
                    Rp <?= number_format($item['harga_saat_pesan'] * $item['jumlah'], 0, ',', '.') ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div class="total-section">
            <div>
                <span style="font-size: 0.8rem; color: #94a3b8; text-transform: uppercase;">Total Bayar</span>
            </div>
            <span class="total-amount">Rp <?= number_format($detail['total_harga'], 0, ',', '.') ?></span>
        </div>
    </div>

    <div style="text-align: center;">
        <a href="pesanan.php" class="btn-back">
            <i class="fas fa-chevron-left"></i> Kembali
        </a>
    </div>
</div>

</body>
</html>