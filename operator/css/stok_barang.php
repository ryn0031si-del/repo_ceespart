<?php
require_once '../config.php';

// --- KEAMANAN HALAMAN OPERATOR ---
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'operator') {
    header("location: ../login.php");
    exit;
}

// Ambil data produk (Fokus pada stok)
// Mengurutkan stok terkecil di atas agar operator segera mengetahui barang yang hampir habis
$query = "SELECT id_produk, nama_produk, stok, harga FROM produk ORDER BY stok ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Barang - Ce'3s Part</title>
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
                    <li><a href="stok_barang.php" class="active"><i class="fa-solid fa-boxes-stacked"></i> <span>Stok Barang</span></a></li>
                    <li><a href="pesanan.php"><i class="fa-solid fa-cart-flatbed"></i> <span>Kelola Pesanan</span></a></li>
                    <li><a href="riwayat.php"><i class="fa-solid fa-clock-rotate-left"></i> <span>Riwayat Kerja</span></a></li>
                    <li class="logout-item"><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span></a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <div class="header-title">
                    <h1>Manajemen Stok</h1>
                    <p style="color: var(--text-light);">Pantau ketersediaan suku cadang dan lakukan restock jika diperlukan.</p>
                </div>
                <div class="current-date">
                    <i class="fa-regular fa-calendar-days"></i> <?= date('d M Y') ?>
                </div>
            </header>

            <div class="content-container" style="width: 100%; max-width: none;">
                <div class="section-title">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Daftar Inventaris Produk</span>
                </div>

                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Harga Satuan</th>
                                <th>Status Stok</th>
                                <th>Sisa Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (mysqli_num_rows($result) > 0):
                                $no = 1;
                                while($row = mysqli_fetch_assoc($result)): 
                                    $stok = $row['stok'];
                                    
                                    // Logika klasifikasi status stok sesuai standar operasional
                                    if ($stok <= 0) {
                                        $status_class = 'status-danger';
                                        $status_text = 'Habis';
                                    } elseif ($stok < 10) {
                                        $status_class = 'status-warning';
                                        $status_text = 'Menipis';
                                    } else {
                                        $status_class = 'status-safe';
                                        $status_text = 'Tersedia';
                                    }
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="font-bold"><?= htmlspecialchars($row['nama_produk']) ?></td>
                                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge <?= $status_class ?>"><?= $status_text ?></span>
                                </td>
                                <td class="font-bold <?= ($stok < 10) ? 'text-danger' : '' ?>">
                                    <?= number_format($stok, 0, ',', '.') ?> Pcs
                                </td>
                            </tr>
                            <?php 
                                endwhile; 
                            else:
                            ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-light);">
                                    Tidak ada data produk ditemukan di database.
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