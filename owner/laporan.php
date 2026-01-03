<?php
require_once '../config.php';

// Proteksi Akses Owner
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'owner') {
    header("location: login.php");
    exit;
}

// Inisialisasi Tanggal (Default: Hari ini)
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : date('Y-m-01'); // Awal bulan ini
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : date('Y-m-d'); // Hari ini

// 1. Ambil Ringkasan berdasarkan Filter Tanggal
$query_total = mysqli_query($conn, "SELECT SUM(total_harga) as total, COUNT(id_pesanan) as qty 
                                    FROM pesanan 
                                    WHERE status_pesanan = 'Selesai' 
                                    AND DATE(tanggal_pesanan) BETWEEN '$tgl_mulai' AND '$tgl_selesai'");
$data_total = mysqli_fetch_assoc($query_total);

// 2. Ambil Rincian Transaksi berdasarkan Filter
$query_report = mysqli_query($conn, "SELECT * FROM pesanan 
                                     WHERE status_pesanan = 'Selesai' 
                                     AND DATE(tanggal_pesanan) BETWEEN '$tgl_mulai' AND '$tgl_selesai' 
                                     ORDER BY tanggal_pesanan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Periode - Ce'3s Part</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3>OWNER PANEL</h3>
            </div>
            <ul class="nav-links">
                <li><a href="dashboard.php"><i class="fa-solid fa-gauge"></i> <span>Dashboard</span></a></li>
                <li><a href="laporan.php" class="active"><i class="fa-solid fa-chart-pie"></i> <span>Laporan Keuangan</span></a></li>
                <li><a href="audit_logs.php"><i class="fa-solid fa-list-check"></i> <span>Audit Logs</span></a></li>
                <li><a href="manage_admin.php"><i class="fa-solid fa-user-shield"></i> <span>Kelola Admin</span></a></li>
                <li><a href="profile.php"><i class="fa-solid fa-user-gear"></i> <span>Pengaturan Profil</span></a></
                <li><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span></a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="content-header">
                <h1>Financial Report</h1>
                <button onclick="window.print()" class="btn-export">
                    <i class="fa-solid fa-print"></i> Cetak Laporan
                </button>
            </div>

            <form method="GET" class="filter-wrapper">
                <div class="filter-group">
                    <label>Mulai Tanggal</label>
                    <input type="date" name="tgl_mulai" value="<?= $tgl_mulai ?>">
                </div>
                <div class="filter-group">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="tgl_selesai" value="<?= $tgl_selesai ?>">
                </div>
                <button type="submit" class="btn-filter">
                    <i class="fa-solid fa-magnifying-glass"></i> Filter
                </button>
                <a href="laporan.php" style="color: var(--text-gray); font-size: 0.8rem; text-decoration: none;">Reset</a>
            </form>

            <div class="report-summary-box">
                <div class="summary-item">
                    <label>Omzet Periode Ini</label>
                    <div class="amount"><?= rupiah($data_total['total'] ?? 0) ?></div>
                </div>
                <div class="summary-item">
                    <label>Jumlah Transaksi</label>
                    <div class="amount"><?= $data_total['qty'] ?> <span style="font-size:0.9rem; color:var(--text-gray);">Pesanan</span></div>
                </div>
                <div class="summary-item" style="border-left-color: #3498db;">
                    <label>Periode Terpilih</label>
                    <div class="amount" style="font-size: 1rem; margin-top: 10px;">
                        <?= date('d/m/Y', strtotime($tgl_mulai)) ?> - <?= date('d/m/Y', strtotime($tgl_selesai)) ?>
                    </div>
                </div>
            </div>

            <div class="data-container">
                <table class="owner-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>ID Pesanan</th>
                            <th>Penerima</th>
                            <th>Metode</th>
                            <th>Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($query_report) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($query_report)): ?>
                                <tr>
                                    <td><?= date('d M Y', strtotime($row['tanggal_pesanan'])) ?></td>
                                    <td style="color: var(--owner-gold); font-weight: 800;">#<?= $row['id_pesanan'] ?></td>
                                    <td><?= htmlspecialchars($row['nama_penerima']) ?></td>
                                    <td><?= $row['metode_pembayaran'] ?></td>
                                    <td style="font-weight: 700; color: #fff;"><?= rupiah($row['total_harga']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 30px; color: var(--text-gray);">
                                    Tidak ada data pesanan selesai pada rentang tanggal ini.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

</body>
</html>