<?php
require_once '../config.php';

// --- KEAMANAN HALAMAN ADMIN ---
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
if ($_SESSION["role"] !== 'admin') {
    header("location: ../index.php");
    exit;
}

// --- PENGAMBILAN DATA STATISTIK ---
$total_users = 0;
$total_produk = 0;
$total_pesanan = 0;
$pendapatan_total = 0;

$result_users = mysqli_query($conn, "SELECT COUNT(id_user) as total FROM users WHERE role = 'customer'");
if($result_users) $total_users = mysqli_fetch_assoc($result_users)['total'];

$result_produk = mysqli_query($conn, "SELECT COUNT(id_produk) as total FROM produk");
if($result_produk) $total_produk = mysqli_fetch_assoc($result_produk)['total'];

$result_pesanan = mysqli_query($conn, "SELECT COUNT(id_pesanan) as total FROM pesanan");
if($result_pesanan) $total_pesanan = mysqli_fetch_assoc($result_pesanan)['total'];

$result_pendapatan = mysqli_query($conn, "SELECT SUM(total_harga) as total FROM pesanan WHERE status_pesanan = 'Selesai'");
if($result_pendapatan) {
    $pendapatan_total = mysqli_fetch_assoc($result_pendapatan)['total'] ?? 0;
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Ce'3s Part</title>
    <!-- CSS Admin -->
    <link rel="stylesheet" href="style.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<div class="admin-wrapper">
    <aside class="sidebar">
        <div class="sidebar-header">
            <h3>Admin Panel</h3>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="index.php" class="active"><i class="fa-solid fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="manage_produk.php"><i class="fa-solid fa-box"></i> Kelola Produk</a></li>
                <li><a href="manage_pesanan.php"><i class="fa-solid fa-receipt"></i> Kelola Pesanan</a></li>
                <li><a href="manage_users.php"><i class="fa-solid fa-users"></i> Kelola Pengguna</a></li>
                <li><a href="admin_chat.php"><i class="fa-solid fa-comments"></i> Chat</a></li>
                <li><a href="../index.php" target="_blank"><i class="fa-solid fa-globe"></i> Lihat Website</a></li>
                <li><a href="../logout.php"><i class="fa-solid fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <header class="main-header">
            <h1>Dashboard</h1>
            <div class="admin-info">
                Selamat Datang, <strong><?php echo htmlspecialchars($_SESSION["nama_lengkap"]); ?></strong>
            </div>
        </header>

        <section class="dashboard-stats">
            <div class="stat-card">
                <div class="icon"><i class="fa-solid fa-users"></i></div>
                <div class="stat-info">
                    <h4>Total Pelanggan</h4>
                    <p><?php echo $total_users; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon"><i class="fa-solid fa-box"></i></div>
                <div class="stat-info">
                    <h4>Total Produk</h4>
                    <p><?php echo $total_produk; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon"><i class="fa-solid fa-receipt"></i></div>
                <div class="stat-info">
                    <h4>Total Pesanan</h4>
                    <p><?php echo $total_pesanan; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon"><i class="fa-solid fa-dollar-sign"></i></div>
                <div class="stat-info">
                    <h4>Total Pendapatan</h4>
                    <p>Rp. <?php echo number_format($pendapatan_total, 0, ',', '.'); ?></p>
                </div>
            </div>
        </section>

        <section class="recent-activities">
            <h2>Aktivitas Terbaru</h2>
            <p>Fitur ini bisa dikembangkan untuk menampilkan pesanan terbaru atau pendaftar baru.</p>
        </section>
    </main>
</div>

<!-- JS Admin -->
<script src="js/index_admin.js"></script>
</body>
</html>
