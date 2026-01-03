<?php
require_once '../config.php';

// --- KEAMANAN HALAMAN OPERATOR ---
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'operator') {
    header("location: ../login.php");
    exit;
}

// Proses Update Status Pesanan jika ada kiriman POST
if (isset($_POST['update_status'])) {
    $id_pesanan = $_POST['id_pesanan'];
    $status_baru = $_POST['status_baru'];
    
    // Sinkronisasi dengan ENUM Database: Menunggu Pembayaran, Diproses, Dikirim, Selesai, Dibatalkan
    $update_sql = "UPDATE pesanan SET status_pesanan = '$status_baru' WHERE id_pesanan = '$id_pesanan'";
    if (mysqli_query($conn, $update_sql)) {
        $success_msg = "Status pesanan #$id_pesanan berhasil diperbarui!";
    }
}

// Ambil data pesanan operasional (Menunggu Pembayaran & Diproses)
$query = "SELECT p.*, u.nama_lengkap 
          FROM pesanan p 
          LEFT JOIN users u ON p.id_user = u.id_user 
          WHERE p.status_pesanan IN ('Menunggu Pembayaran', 'Diproses') 
          ORDER BY p.tanggal_pesanan DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - Ce'3s Part</title>
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
                    <li><a href="pesanan.php" class="active"><i class="fa-solid fa-cart-flatbed"></i> <span>Kelola Pesanan</span></a></li>
                    <li><a href="riwayat.php"><i class="fa-solid fa-clock-rotate-left"></i> <span>Riwayat Kerja</span></a></li>
                    <li class="logout-item"><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span></a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <div class="header-title">
                    <h1>Antrean Pesanan</h1>
                    <p style="color: var(--text-light);">Siapkan barang dan perbarui status operasional</p>
                </div>
                <div class="current-date">
                    <i class="fa-regular fa-calendar-days"></i> <?= date('d M Y') ?>
                </div>
            </header>

            <?php if (isset($success_msg)): ?>
                <div class="alert-success">
                    <i class="fa-solid fa-circle-check"></i> <?= $success_msg ?>
                </div>
            <?php endif; ?>

            <div class="content-container" style="width: 100%; max-width: none;">
                <div class="section-title">
                    <i class="fa-solid fa-spinner"></i>
                    <span>Pesanan Perlu Diproses</span>
                </div>

                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td>#<?= $row['id_pesanan'] ?></td>
                                    <td class="font-bold"><?= htmlspecialchars($row['nama_lengkap'] ?? 'Guest') ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($row['tanggal_pesanan'])) ?></td>
                                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge <?= ($row['status_pesanan'] == 'Menunggu Pembayaran') ? 'status-warning' : 'status-primary' ?>">
                                            <?= $row['status_pesanan'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" class="action-form">
                                            <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan'] ?>">
                                            <select name="status_baru" class="select-status">
                                                <option value="Menunggu Pembayaran" <?= $row['status_pesanan'] == 'Menunggu Pembayaran' ? 'selected' : '' ?>>Menunggu Pembayaran</option>
                                                <option value="Diproses" <?= $row['status_pesanan'] == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                                                <option value="Dikirim">Dikirim</option>
                                                <option value="Selesai">Selesai</option>
                                                <option value="Dibatalkan">Batalkan</option>
                                            </select>
                                            <button type="submit" name="update_status" class="btn-update">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; color: var(--text-light); padding: 40px;">
                                        Tidak ada pesanan aktif saat ini.
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