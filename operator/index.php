<?php
require_once '../config.php';

// --- KEAMANAN HALAMAN OPERATOR ---
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'operator') {
    header("location: ../login.php");
    exit;
}

$nama_operator = $_SESSION["nama_lengkap"];

// --- AMBIL DATA REAL-TIME DARI DATABASE ---
$sql_pending = "SELECT COUNT(*) as total FROM pesanan WHERE status_pesanan = 'Menunggu Pembayaran'";
$res_pending = mysqli_query($conn, $sql_pending);
$pending_count = ($res_pending) ? mysqli_fetch_assoc($res_pending)['total'] : 0;

$sql_proses = "SELECT COUNT(*) as total FROM pesanan WHERE status_pesanan = 'Diproses'";
$res_proses = mysqli_query($conn, $sql_proses);
$proses_count = ($res_proses) ? mysqli_fetch_assoc($res_proses)['total'] : 0;

$sql_stok = "SELECT COUNT(*) as total FROM produk WHERE stok < 10";
$res_stok = mysqli_query($conn, $sql_stok);
$stok_limit = ($res_stok) ? mysqli_fetch_assoc($res_stok)['total'] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Dashboard - Ce'3s Part</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3>OPERATOR</h3>
            </div>
            <nav class="sidebar-nav">
                <ul class="nav-links">
                    <li><a href="index.php" class="active"><i class="fa-solid fa-gauge"></i> <span>Dashboard</span></a></li>
                    <li><a href="stok_barang.php"><i class="fa-solid fa-boxes-stacked"></i> <span>Stok Barang</span></a></li>
                    <li><a href="pesanan.php"><i class="fa-solid fa-cart-flatbed"></i> <span>Kelola Pesanan</span></a></li>
                    <li><a href="riwayat.php"><i class="fa-solid fa-clock-rotate-left"></i> <span>Riwayat Kerja</span></a></li>
                    <li class="logout-item"><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span></a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="content-header">
                <div class="header-title">
                    <h1>Dashboard Operator</h1>
                    <p>Selamat bekerja, <strong><?= htmlspecialchars($nama_operator) ?></strong>!</p>
                </div>
                <div class="current-date">
                    <i class="fa-regular fa-calendar-days"></i> <?= date('d M Y') ?>
                </div>
            </header>

            <section class="dashboard-stats">
                <div class="stat-card warning">
                    <div class="stat-icon"><i class="fa-solid fa-bell"></i></div>
                    <div class="stat-info">
                        <h4>Pesanan Baru</h4>
                        <p><?= number_format($pending_count, 0, ',', '.') ?></p>
                    </div>
                </div>

                <div class="stat-card primary">
                    <div class="stat-icon"><i class="fa-solid fa-truck-fast"></i></div>
                    <div class="stat-info">
                        <h4>Siap Dikirim</h4>
                        <p><?= number_format($proses_count, 0, ',', '.') ?></p>
                    </div>
                </div>

                <div class="stat-card danger">
                    <div class="stat-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <div class="stat-info">
                        <h4>Stok Menipis</h4>
                        <p><?= number_format($stok_limit, 0, ',', '.') ?></p>
                    </div>
                </div>
            </section>

            <div class="content-container">
                <h2><i class="fa-solid fa-clipboard-list"></i> Instruksi Tugas Hari Ini</h2>
                <div class="task-list">
                    <div class="task-item">
                        <input type="checkbox" id="t1">
                        <label for="t1">Cek pesanan status 'Menunggu Pembayaran'</label>
                    </div>
                    <div class="task-item">
                        <input type="checkbox" id="t2">
                        <label for="t2">Update stok produk di bawah 10 pcs</label>
                    </div>
                    <div class="task-item">
                        <input type="checkbox" id="t3">
                        <label for="t3">Packing barang status 'Diproses'</label>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>