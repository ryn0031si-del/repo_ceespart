<?php
require_once 'config.php';
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) { header("location: produk.php"); exit; }
$id = (int)$_GET['id'];

// 🔎 Ambil detail produk
$q = $conn->prepare("SELECT * FROM produk WHERE id_produk=?");
$q->bind_param("i", $id);
$q->execute();
$produk = $q->get_result()->fetch_assoc();
$q->close();

if (!$produk) die("❌ Produk tidak ditemukan.");

// 🔗 Produk terkait
$q = $conn->prepare("SELECT * FROM produk WHERE id_produk != ? ORDER BY RAND() LIMIT 4");
$q->bind_param("i", $id);
$q->execute();
$terkait = $q->get_result()->fetch_all(MYSQLI_ASSOC);
$q->close();
// mysqli_close($conn); // Opsional: Dihapus jika config.php digunakan berulang

if (session_status() == PHP_SESSION_NONE) session_start();

// Helper function jika belum ada di config.php
if (!function_exists('safe')) {
    function safe($str) { return htmlspecialchars($str, ENT_QUOTES, 'UTF-8'); }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= safe($produk['nama_produk']) ?> - Ce'3s Part</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/index.css"> <link rel="stylesheet" href="css/detail_produk.css">

</head>
<body>

<header>
    <nav class="container">
        <a href="index.php" class="logo">Ce'3s Part</a>

        <div class="hamburger" id="hamburger">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>

        <div class="nav-menu" id="nav-menu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="produk.php" class="active">Produk</a></li>
                <li><a href="pesanan.php">Pesanan Saya</a></li>
                <li><a href="chat.php">Chat</a></li>
                <li><a href="about.php">About</a></li>
            </ul>
            <div class="auth-links">
                <?php if (!empty($_SESSION["loggedin"])): ?>
                    <a href="profil.php" class="profile-link">
                        <i class="fa-regular fa-user"></i> Halo, <?= safe($_SESSION["nama_lengkap"] ?? $_SESSION["nama_user"] ?? "User") ?>
                    </a>
                    <a href="logout.php" class="btn-nav-logout">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="profile-link">Login</a>
                    <a href="register.php" class="btn-nav-register">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>

<main class="container">
    <div class="product-detail">
        <div class="product-detail-image">
            <img src="assets/images/<?= safe($produk['gambar_produk'] ?: 'placeholder.jpg') ?>" alt="<?= safe($produk['nama_produk']) ?>">
        </div>

        <div class="product-detail-info">
            <h1><?= safe($produk['nama_produk']) ?></h1>
            <span class="price">Rp <?= number_format($produk['harga'],0,',','.') ?></span>

            <div class="stock-status">
                <?php if ($produk['stok'] > 5): ?>
                    <span class="in-stock"><i class="fa fa-check-circle"></i> Stok Tersedia (<?= $produk['stok'] ?>)</span>
                <?php elseif ($produk['stok'] > 0): ?>
                    <span class="low-stock"><i class="fa fa-exclamation-triangle"></i> Stok Terbatas (<?= $produk['stok'] ?>)</span>
                <?php else: ?>
                    <span class="out-of-stock"><i class="fa fa-times-circle"></i> Stok Habis</span>
                <?php endif; ?>
            </div>

            <div class="description">
                <h4>Deskripsi Produk</h4>
                <p><?= $produk['deskripsi'] ? nl2br(safe($produk['deskripsi'])) : '<i>Tidak ada deskripsi.</i>' ?></p>
            </div>

            <form id="cart-form" action="keranjang/keranjang.php" method="POST">
                <input type="hidden" name="id_produk" value="<?= $produk['id_produk'] ?>">
                
                <div class="product-actions">
                    <div class="quantity-selector">
                        <button type="button" class="quantity-btn minus"><i class="fa-solid fa-minus"></i></button>
                        <input type="number" name="jumlah" value="1" min="1" max="<?= $produk['stok'] ?>" id="quantity-input" class="quantity-input" <?= $produk['stok']<=0?'disabled':'' ?>>
                        <button type="button" class="quantity-btn plus"><i class="fa-solid fa-plus"></i></button>
                    </div>
                    
                    <button type="submit" name="tambah_ke_keranjang" class="btn-primary" <?= $produk['stok']<=0?'disabled':'' ?>>
                        <i class="fa fa-cart-plus"></i> + Keranjang
                    </button>
                    
                    <button type="button" id="btn-pesan-sekarang" class="btn-order" <?= $produk['stok']<=0?'disabled':'' ?>>
                        <i class="fa fa-bag-shopping"></i> Beli Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($terkait): ?>
    <section class="related-products">
        <h2>Produk Terkait</h2>
        <div class="product-grid">
            <?php foreach ($terkait as $t): ?>
                <div class="product-card">
                    <img src="assets/images/<?= safe($t['gambar_produk'] ?: 'placeholder.jpg') ?>" alt="<?= safe($t['nama_produk']) ?>">
                    <h3><?= safe($t['nama_produk']) ?></h3>
                    <p>Rp <?= number_format($t['harga'],0,',','.') ?></p>
                    <a href="detail_produk.php?id=<?= $t['id_produk'] ?>" class="btn-card">Lihat Detail</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</main>

<footer class="footer">
    <div class="container footer-container">
        <div class="footer-column">
            <h3>Ce'3s Part</h3>
            <p>Toko spare part motor terpercaya dengan produk original & berkualitas.</p>
        </div>
        <div class="footer-column">
            <h4>Kontak Kami</h4>
            <ul>
                <li><i class="fa fa-location-dot"></i> Jl. Contoh No.123, Karawang</li>
                <li><i class="fa fa-phone"></i> 0812-3456-7890</li>
                <li><i class="fa fa-envelope"></i> support@ce3spart.com</li>
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
    <div class="footer-bottom"><p>&copy; <?= date('Y') ?> Ce'3s Part. All Rights Reserved.</p></div>
</footer>

<script src="js/script.js"></script>
<script>
// Script Khusus Halaman Detail (Logika Jumlah & Tombol Pesan)
document.addEventListener('DOMContentLoaded', () => {
    const f = document.getElementById('cart-form');
    if (!f) return;

    const q = document.getElementById('quantity-input'),
          minus = f.querySelector('.minus'),
          plus = f.querySelector('.plus'),
          pesan = document.getElementById('btn-pesan-sekarang'),
          max = parseInt(q.max) || 100;

    // Logika Tombol Plus/Minus
    minus.onclick = () => { if (q.value > 1) q.value--; };
    plus.onclick = () => { if (parseInt(q.value) < max) q.value++; };

    // Logika Tombol "Beli Sekarang" (Langsung ke Checkout)
    pesan.onclick = () => {
        const data = new FormData(f); 
        data.append('tambah_ke_keranjang', 'true');
        
        fetch('keranjang/keranjang.php', { method: 'POST', body: data })
        .then(r => {
            if (r.ok || r.redirected) {
                window.location.href = 'checkout.php'; // Arahkan langsung ke checkout
            } else {
                alert('Gagal memproses pesanan!');
            }
        })
        .catch(() => alert('Terjadi kesalahan koneksi.'));
    };
});
</script>
</body>
</html>