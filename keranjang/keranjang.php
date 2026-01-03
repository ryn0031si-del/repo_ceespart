<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once '../config.php';

// Inisialisasi keranjang jika belum ada
$_SESSION['keranjang'] ??= [];

// Fungsi helper untuk set pesan flash
function setFlash($type, $msg) {
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

/* === LOGIKA PEMROSESAN (POST) === */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. TAMBAH KE KERANJANG
    if (isset($_POST['tambah_ke_keranjang'])) {
        $id = intval($_POST['id_produk'] ?? 0);
        $jumlah = intval($_POST['jumlah'] ?? 0);

        if ($id && $jumlah > 0) {
            $stmt = $conn->prepare("SELECT nama_produk, harga, stok, gambar_produk FROM produk WHERE id_produk=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $p = $result->fetch_assoc();

            if ($p) {
                $stok_db = $p['stok'];
                $jumlah_di_keranjang = $_SESSION['keranjang'][$id]['jumlah'] ?? 0;
                $total_rencana = $jumlah_di_keranjang + $jumlah;

                if ($total_rencana > $stok_db) {
                    setFlash('error', "Stok tidak mencukupi! Maksimal tersedia: $stok_db");
                } else {
                    $_SESSION['keranjang'][$id] = [
                        'nama' => $p['nama_produk'],
                        'harga' => $p['harga'],
                        'jumlah' => $total_rencana,
                        'gambar' => $p['gambar_produk']
                    ];
                    setFlash('success', "{$p['nama_produk']} berhasil ditambahkan.");
                }
            } else {
                setFlash('error', "Produk tidak ditemukan.");
            }
        }
    }

    // 2. HAPUS ITEM
    if (isset($_POST['hapus_item'])) {
        $id = intval($_POST['id_produk_hapus']);
        if (isset($_SESSION['keranjang'][$id])) {
            unset($_SESSION['keranjang'][$id]);
            setFlash('success', "Produk dihapus dari keranjang.");
        }
    }

    // 3. UPDATE JUMLAH (Di Keranjang)
    if (isset($_POST['update_jumlah'])) {
        $id = intval($_POST['id_produk_update']);
        $baru = intval($_POST['jumlah_baru']);

        if ($baru <= 0) {
            unset($_SESSION['keranjang'][$id]);
            setFlash('success', "Produk dihapus karena jumlah 0.");
        } else {
            $stmt = $conn->prepare("SELECT stok FROM produk WHERE id_produk=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            
            if ($res) {
                if ($baru <= $res['stok']) {
                    $_SESSION['keranjang'][$id]['jumlah'] = $baru;
                    setFlash('success', "Jumlah diperbarui.");
                } else {
                    setFlash('error', "Stok kurang! Sisa: {$res['stok']}");
                }
            }
        }
    }

    // REDIRECT (Agar tidak resubmit saat refresh)
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$conn->close();

// Mengambil Pesan Flash
$pesan_sukses = '';
$pesan_error = '';
if (isset($_SESSION['flash'])) {
    if ($_SESSION['flash']['type'] == 'success') $pesan_sukses = $_SESSION['flash']['msg'];
    if ($_SESSION['flash']['type'] == 'error') $pesan_error = $_SESSION['flash']['msg'];
    unset($_SESSION['flash']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang Belanja - Ce'3s Part</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/index.css"> <link rel="stylesheet" href="../css/keranjang.css">
</head>
<body>

    <header>
        <nav class="container">
            <a href="../index.php" class="logo">Ce'3s Part</a>

            <div class="hamburger" id="hamburger">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>

            <div class="nav-menu" id="nav-menu">
                <ul>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../produk.php">Produk</a></li>
                    <li><a href="../pesanan.php">Pesanan Saya</a></li>
                    <li><a href="../chat.php">Chat</a></li>
                    <li><a href="../about.php">About</a></li>
                </ul>
                <div class="auth-links">
                    <?php if (!empty($_SESSION["loggedin"])): ?>
                        <a href="../profil.php" class="profile-link">
                            <i class="fa-regular fa-user"></i> Halo, <?= htmlspecialchars($_SESSION["nama_lengkap"] ?? "User") ?>
                        </a>
                        <a href="../logout.php" class="btn-nav-logout">Logout</a>
                    <?php else: ?>
                        <a href="../login.php" class="profile-link">Login</a>
                        <a href="../register.php" class="btn-nav-register">Daftar</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="cart-page">
            <div class="cart-header-title">
                <h1><i class="fa-solid fa-cart-shopping"></i> Keranjang Belanja</h1>
                <p>Periksa kembali daftar belanjaan Anda sebelum checkout.</p>
            </div>
            
            <?php if ($pesan_sukses): ?>
                <div class='alert alert-success'><i class="fa-solid fa-check-circle"></i> <?= $pesan_sukses ?></div>
            <?php endif; ?>
            
            <?php if ($pesan_error): ?>
                <div class='alert alert-error'><i class="fa-solid fa-circle-exclamation"></i> <?= $pesan_error ?></div>
            <?php endif; ?>

            <?php if (empty($_SESSION['keranjang'])): ?>
                <div class="cart-empty">
                    <i class="fa-solid fa-basket-shopping"></i>
                    <p>Keranjang Anda masih kosong.</p>
                    <a href="../produk.php" class="btn-primary">Mulai Belanja</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th colspan="2">Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0; 
                            foreach ($_SESSION['keranjang'] as $id => $i):
                                $sub = $i['harga'] * $i['jumlah']; 
                                $total += $sub; 
                            ?>
                            <tr>
                                <td class="cart-img">
                                    <img src="../assets/images/<?= htmlspecialchars($i['gambar'] ?: 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($i['nama']) ?>">
                                </td>
                                <td class="cart-nama">
                                    <span class="product-name"><?= htmlspecialchars($i['nama']) ?></span>
                                </td>
                                <td class="cart-harga">Rp <?= number_format($i['harga'], 0, ',', '.') ?></td>
                                <td>
                                    <form method="POST" class="update-form">
                                        <input type="hidden" name="id_produk_update" value="<?= $id ?>">
                                        <div class="qty-control">
                                            <input type="number" name="jumlah_baru" value="<?= $i['jumlah'] ?>" min="1" class="quantity-input-cart">
                                            <button type="submit" name="update_jumlah" class="btn-icon-update" title="Update">
                                                <i class="fa-solid fa-rotate"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                                <td class="cart-subtotal">Rp <?= number_format($sub, 0, ',', '.') ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="id_produk_hapus" value="<?= $id ?>">
                                        <button type="submit" name="hapus_item" class="btn-icon-hapus" onclick="return confirm('Hapus produk ini?')" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="total-label">Total Belanja</td>
                                <td colspan="2" class="total-value">Rp <?= number_format($total, 0, ',', '.') ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="cart-checkout">
                    <a href="../produk.php" class="btn-secondary"><i class="fa-solid fa-arrow-left"></i> Lanjut Belanja</a>
                    <a href="../checkout.php" class="btn-primary">Checkout <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer">
        <div class="container footer-container">
            <div class="footer-column">
                <h3>Ce'3s Part</h3>
                <p>Toko spare part motor terpercaya yang menyediakan produk original & berkualitas.</p>
            </div>
            <div class="footer-column">
                <h4>Kontak Kami</h4>
                <ul>
                    <li><i class="fa-solid fa-location-dot"></i> Jl. Contoh No. 123, Karawang</li>
                    <li><i class="fa-solid fa-phone"></i> 0812-3456-7890</li>
                    <li><i class="fa-solid fa-envelope"></i> support@ce3spart.com</li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Ikuti Kami</h4>
                <div class="social-links">
                    <a href="https://www.facebook.com/share/1Bu5u31u82/"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/gk_pnyanama007" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="https://wa.me/qr/5JO24PB5ZBI7E1" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Ce'3s Part. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="../js/script.js"></script>
</body>
</html>
