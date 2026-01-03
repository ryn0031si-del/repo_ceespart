<?php
require_once '../config.php';

// --- KEAMANAN HALAMAN OPERATOR ---
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'operator') {
    header("location: ../login.php");
    exit;
}

// Ambil data riwayat pesanan (Menyesuaikan ENUM database: Selesai dan Dibatalkan)
$query = "SELECT p.*, u.nama_lengkap 
          FROM pesanan p 
          LEFT JOIN users u ON p.id_user = u.id_user 
          WHERE p.status_pesanan IN ('Selesai', 'Dibatalkan') 
          ORDER BY p.tanggal_pesanan DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Kerja - Ce'3s Part</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="admin-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3>OPERATOR</h3>
            </div>
            <nav class="sidebar-nav">
                <ul class="nav-links">
                    <li><a href="index.php"><i class="fa-solid fa-gauge"></i> <span>Dashboard</span></a></li>
                    <li><a href="stok_barang.php"><i class="fa-solid fa-boxes-stacked"></i> <span>Stok Barang</span></a></li>
                    <li><a href="pesanan.php"><i class="fa-solid fa-cart-flatbed"></i> <span>Kelola Pesanan</span></a></li>
                    <li><a href="riwayat.php" class="active"><i class="fa-solid fa-clock-rotate-left"></i> <span>Riwayat Kerja</span></a></li>
                    <li class="logout-item"><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span></a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <div class="header-title">
                    <h1>Riwayat Operasional</h1>
                    <p style="color: var(--text-light);">Daftar pesanan yang telah selesai atau dibatalkan</p>
                </div>
                <button onclick="window.print()" class="btn-print">
                    <i class="fa-solid fa-print"></i> Cetak Laporan
                </button>
            </header>

            <div class="content-container" style="width: 100%; max-width: none;">
                <div class="section-title">
                    <i class="fa-solid fa-box-archive"></i>
                    <span>Arsip Pesanan Selesai & Batal</span>
                </div>

                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Nama Pelanggan</th>
                                <th>Tanggal Transaksi</th>
                                <th>Total Bayar</th>
                                <th>Status Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td>#<?= $row['id_pesanan'] ?></td>
                                    <td class="font-bold"><?= htmlspecialchars($row['nama_lengkap'] ?? 'Guest') ?></td>
                                    <td><?= date('d M Y, H:i', strtotime($row['tanggal_pesanan'])) ?></td>
                                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge <?= ($row['status_pesanan'] == 'Selesai') ? 'status-safe' : 'status-danger' ?>">
                                            <i class="<?= ($row['status_pesanan'] == 'Selesai') ? 'fa-solid fa-check-double' : 'fa-solid fa-xmark' ?>"></i>
                                            <?= $row['status_pesanan'] ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-light);">
                                        Belum ada riwayat transaksi yang terselesaikan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

</body>
</html>