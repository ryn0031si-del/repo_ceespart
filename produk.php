<?php
session_start(); // WAJIB ADA DI BARIS PERTAMA
require_once 'config.php';

$produk = [];
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Logika Pencarian Produk
if ($search !== '') {
    $sql = "SELECT id_produk, nama_produk, harga, gambar_produk 
            FROM produk 
            WHERE nama_produk LIKE ? 
            ORDER BY nama_produk ASC";
    $stmt = mysqli_prepare($conn, $sql);
    $param = "%$search%";
    mysqli_stmt_bind_param($stmt, "s", $param);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $sql = "SELECT id_produk, nama_produk, harga, gambar_produk 
            FROM produk 
            ORDER BY nama_produk ASC";
    $result = mysqli_query($conn, $sql);
}

if ($result) $produk = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Produk Kami - Ce'3s Part</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<link rel="stylesheet" href="css/style.css"> 
<link rel="stylesheet" href="css/produk.css">
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
                <li><a href="index.php">Home</a></li>
                <li><a href="produk.php" class="active">Produk</a></li>
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

<main class="container">
    <div class="page-header">
        <h2>Semua Produk</h2>
        <p>Temukan berbagai spare part pilihan terbaik untuk kendaraan Anda.</p>
    </div>

    <div class="search-container">
        <form action="produk.php" method="get">
            <input type="text" name="search" placeholder="Cari nama produk atau merek..." value="<?= htmlspecialchars($search); ?>">
            <button type="submit"><i class="fa fa-search"></i> Cari</button>
        </form>
    </div>

    <?php if (!empty($produk)): ?>
    <div class="product-grid">
        <?php foreach ($produk as $item): ?>
        <div class="product-card">
            <img src="assets/images/<?= htmlspecialchars($item['gambar_produk'] ?: 'placeholder.jpg'); ?>" alt="<?= htmlspecialchars($item['nama_produk']); ?>">
            <h3><?= htmlspecialchars($item['nama_produk']); ?></h3>
            <p>Rp <?= number_format($item['harga'], 0, ',', '.'); ?></p>
            <a href="detail_produk.php?id=<?= $item['id_produk']; ?>" class="btn-card">Lihat Detail</a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <p class="empty-message">
            Produk tidak ditemukan untuk pencarian "<strong><?= htmlspecialchars($search); ?></strong>"
        </p>
    <?php endif; ?>
</main>

<footer class="footer">
    <div class="container footer-container">
        <div class="footer-column">
            <h3>Ce'3s Part</h3>
            <p>Toko spare part motor terpercaya dengan produk original & berkualitas untuk menunjang performa kendaraan Anda.</p>
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