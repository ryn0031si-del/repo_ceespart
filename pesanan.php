<?php
// Mulai sesi di baris paling atas
session_start();
require_once 'config.php';

// Security: Pastikan user login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$id_user = $_SESSION["id_user"];
$pesanan_list = [];

// Ambil semua pesanan user dari database
$sql = "SELECT id_pesanan, tanggal_pesanan, total_harga, ongkir, status_pesanan 
        FROM pesanan 
        WHERE id_user = ? 
        ORDER BY tanggal_pesanan DESC";

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id_user);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $pesanan_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - Ce'3s Part</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <link rel="stylesheet" href="css/style.css"> 
    <link rel="stylesheet" href="css/index.css"> <link rel="stylesheet" href="css/pesanan.css"> 
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
                <li><a href="produk.php">Produk</a></li>
                <li><a href="pesanan.php" class="active">Pesanan Saya</a></li>
                <li><a href="chat.php">Chat</a></li>
                <li><a href="about.php">About</a></li>
            </ul>
            <div class="auth-links">
                <a href="profil.php" class="profile-link">
                    <i class="fa-regular fa-user"></i> Halo, <?= htmlspecialchars($_SESSION["nama_lengkap"]) ?>
                </a>
                <a href="logout.php" class="btn-nav-logout">Logout</a>
            </div>
        </div>
    </nav>
</header>

<main class="container">
    <div class="page-header">
        <h2>Riwayat Pesanan Saya</h2>
        <p>Lacak dan lihat semua transaksi Anda di halaman ini.</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert-success">
            ✅ Pesanan Anda berhasil dibuat! Silakan cek detailnya di bawah.
        </div>
    <?php endif; ?>

    <div class="order-history-container">
        
        <?php if (empty($pesanan_list)): ?>
            <p class="empty-message">Anda belum memiliki riwayat pesanan.</p>
        
        <?php else: ?>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Tanggal</th>
                        <th>Total + Ongkir</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pesanan_list as $pesanan): ?>
                        <tr>
                            <td>#<?= $pesanan['id_pesanan']; ?></td>
                            <td><?= date("d M Y", strtotime($pesanan['tanggal_pesanan'])); ?></td>
                            <td>Rp <?= number_format($pesanan['total_harga'] + $pesanan['ongkir'], 0, ',', '.'); ?></td>
                            <td>
                                <span class="status <?= strtolower(str_replace(' ', '-', $pesanan['status_pesanan'])); ?>">
                                    <?= htmlspecialchars($pesanan['status_pesanan']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="detail_pesanan.php?id=<?= $pesanan['id_pesanan']; ?>" class="btn-card">
                                    <i class="fa-solid fa-eye"></i> Lihat Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
    </div>
</main>

<footer class="footer">
    <div class="container footer-container">
        <div class="footer-column">
            <h3>Ce'3s Part</h3>
            <p>
                Toko spare part motor terpercaya yang menyediakan produk original & berkualitas
                untuk menunjang performa kendaraan Anda.
            </p>
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
        <p>&copy; <?php echo date('Y'); ?> Ce'3s Part. All Rights Reserved.</p>
    </div>
</footer>

<script src="js/script.js"></script> 
</body>
</html>