<?php
require_once '../config.php';

// Keamanan Halaman Admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    header("location: login.php");
    exit;
}

// Ambil semua data pesanan, gabungkan dengan nama user
$pesanan_list = [];
$sql = "SELECT p.id_pesanan, u.nama_lengkap, p.tanggal_pesanan, p.total_harga, p.status_pesanan 
        FROM pesanan p
        JOIN users u ON p.id_user = u.id_user
        ORDER BY p.tanggal_pesanan DESC";
$result = mysqli_query($conn, $sql);
if ($result) {
    $pesanan_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - Admin Panel</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<div class="admin-wrapper">
    <aside class="sidebar">
        <div class="sidebar-header"><h3>Admin Panel</h3></div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="index.php"><i class="fa-solid fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="manage_produk.php"><i class="fa-solid fa-box"></i> Kelola Produk</a></li>
                <li><a href="manage_pesanan.php" class="active"><i class="fa-solid fa-receipt"></i> Kelola Pesanan</a></li>
                <li><a href="manage_users.php"><i class="fa-solid fa-users"></i> Kelola Pengguna</a></li>
                <li><a href="admin_chat.php"><i class="fa-solid fa-comments"></i> Chat</a></li>
                <li><a href="../index.php" target="_blank"><i class="fa-solid fa-globe"></i> Lihat Website</a></li>
                <li><a href="../logout.php"><i class="fa-solid fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <header class="main-header">
            <button class="menu-toggle" id="menu-toggle"><i class="fa-solid fa-bars"></i></button>
            <h1>Kelola Pesanan</h1>
        </header>

        <div class="content-container">
            <?php 
            if(isset($_GET['status']) && $_GET['status'] == 'sukses') {
                echo '<div class="alert alert-success">Status pesanan berhasil diperbarui!</div>';
            }
            ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Nama Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pesanan_list)): ?>
                            <tr><td colspan="6" style="text-align: center;">Belum ada pesanan.</td></tr>
                        <?php else: ?>
                            <?php foreach ($pesanan_list as $pesanan): ?>
                            <tr>
                                <td>#<?php echo $pesanan['id_pesanan']; ?></td>
                                <td><?php echo htmlspecialchars($pesanan['nama_lengkap']); ?></td>
                                <td><?php echo date("d M Y", strtotime($pesanan['tanggal_pesanan'])); ?></td>
                                <td>Rp. <?php echo number_format($pesanan['total_harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <form action="proses_pesanan.php" method="post" class="status-form">
                                        <input type="hidden" name="id_pesanan" value="<?php echo $pesanan['id_pesanan']; ?>">
                                        <select name="status_pesanan" class="status-select <?php echo strtolower(str_replace(' ', '-', $pesanan['status_pesanan'])); ?>">
                                            <option value="Menunggu Pembayaran" <?php if($pesanan['status_pesanan'] == 'Menunggu Pembayaran') echo 'selected'; ?>>Menunggu Pembayaran</option>
                                            <option value="Diproses" <?php if($pesanan['status_pesanan'] == 'Diproses') echo 'selected'; ?>>Diproses</option>
                                            <option value="Dikirim" <?php if($pesanan['status_pesanan'] == 'Dikirim') echo 'selected'; ?>>Dikirim</option>
                                            <option value="Selesai" <?php if($pesanan['status_pesanan'] == 'Selesai') echo 'selected'; ?>>Selesai</option>
                                            <option value="Dibatalkan" <?php if($pesanan['status_pesanan'] == 'Dibatalkan') echo 'selected'; ?>>Dibatalkan</option>
                                        </select>
                                </td>
                                <td>
                                        <button type="submit" class="btn-update">Update</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('show');
    });
</script>
</div> </main>
</div> <script src="../js/script.js"></script>

<script>
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('show');
    });
</script>
</body>
</html>
</body>
</html>