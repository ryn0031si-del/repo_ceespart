<?php
session_start(); // WAJIB ADA DI BARIS PERTAMA
require_once 'config.php';

// 🔹 Ambil 8 produk terbaru saja
$produk_unggulan = [];
$sql = "SELECT id_produk, nama_produk, harga, gambar_produk 
        FROM produk 
        ORDER BY id_produk DESC 
        LIMIT 8";
$result = mysqli_query($conn, $sql);
if ($result) $produk_unggulan = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ce'3s Part - Solusi Spare Part Motor Terpercaya</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/index.css">
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
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="produk.php">Produk</a></li>
                <li><a href="pesanan.php">Pesanan Saya</a></li>
                <li><a href="chat.php">Chat</a></li>
                <li><a href="about.php">About</a></li>
            </ul>
            <div class="auth-links">
                <?php if (!empty($_SESSION["loggedin"])): ?>
                    <a href="profil.php" class="profile-link">
                        <i class="fa-regular fa-user"></i> Halo, <?= htmlspecialchars($_SESSION["nama_user"] ?? $_SESSION["nama_lengkap"] ?? "User"); ?>
                    </a>
                    <a href="logout.php" class="btn-nav-logout">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="profile-link">Login</a>
                    <a href="login.php" class="btn-nav-register">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>
<main>
    <section class="hero">
        <div class="container hero-content">
            <div class="hero-text">
                <h1>Temukan Kebutuhan Motormu, Mulai Petualanganmu</h1>
                <p>Produk Spare Part Original & Berkualitas untuk Performa Terbaik.</p>
                <a href="produk.php" class="btn-primary">Belanja Sekarang</a>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1558981403-c5f9899a28bc?q=80&w=2070" alt="Motor Kustom">
            </div>
        </div>
    </section>

    <section class="container features-section">
        <div class="feature-item">
            <i class="fa-solid fa-shield-halved"></i>
            <h4>Produk Original</h4>
            <p>Jaminan keaslian untuk setiap spare part.</p>
        </div>
        <div class="feature-item">
            <i class="fa-solid fa-truck-fast"></i>
            <h4>Pengiriman Cepat</h4>
            <p>Pesanan Anda diproses dan dikirim secepatnya.</p>
        </div>
        <div class="feature-item">
            <i class="fa-solid fa-headset"></i>
            <h4>Layanan Terbaik</h4>
            <p>Admin kami siap membantu setiap pertanyaan Anda.</p>
        </div>
    </section>

    <section class="container featured-products">
        <div class="section-header">
            <h2>Produk Terlaris</h2>
            <p>Spare part pilihan yang paling banyak dicari pelanggan kami.</p>
        </div>

        <?php if (!empty($produk_unggulan)): ?>
        <div class="product-grid">
            <?php foreach ($produk_unggulan as $p): ?>
            <div class="product-card">
                <img src="assets/images/<?= htmlspecialchars($p['gambar_produk'] ?: 'placeholder.jpg'); ?>" alt="<?= htmlspecialchars($p['nama_produk']); ?>">
                <h3><?= htmlspecialchars($p['nama_produk']); ?></h3>
                <p>Rp <?= number_format($p['harga'], 0, ',', '.'); ?></p>
                <a href="detail_produk.php?id=<?= $p['id_produk']; ?>" class="btn-card">Lihat Detail</a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p class="empty-message">Belum ada produk unggulan saat ini.</p>
        <?php endif; ?>

        <div class="section-cta">
            <a href="produk.php" class="btn-see-all">
                Lihat Semua Produk <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </section>
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
        <p>&copy; <?= date('Y'); ?> Ce'3s Part. All Rights Reserved.</p>
    </div>
</footer>
<script src="js/script.js"></script> 
</body>
</html>