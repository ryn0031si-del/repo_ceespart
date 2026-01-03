<?php
require_once '../config.php';

// Keamanan Halaman Admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    header("location: login.php");
    exit;
}

$produk_list = [];
$sql = "SELECT id_produk, nama_produk, harga, stok, gambar_produk FROM produk ORDER BY nama_produk ASC";
$result = mysqli_query($conn, $sql);
if ($result) {
    $produk_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Admin Panel</title>
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
                <li><a href="manage_produk.php" class="active"><i class="fa-solid fa-box"></i> Kelola Produk</a></li>
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
             <button class="menu-toggle" id="menu-toggle"><i class="fa-solid fa-bars"></i></button>
            <h1>Kelola Produk</h1>
            <a href="tambah_produk.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Produk Baru</a>
        </header>

        <div class="content-container">
            <?php 
            if(isset($_GET['status'])) {
                if ($_GET['status'] == 'sukses') echo '<div class="alert alert-success">Operasi berhasil!</div>';
                if ($_GET['status'] == 'gagal' || $_GET['status'] == 'gagal_upload') echo '<div class="alert alert-danger">Operasi gagal! Periksa kembali data atau file gambar.</div>';
            }
            ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($produk_list)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">Belum ada produk.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($produk_list as $produk): ?>
                        <tr>
                            <td><img src="../assets/images/<?php echo htmlspecialchars($produk['gambar_produk'] ? $produk['gambar_produk'] : 'placeholder.jpg'); ?>" alt="Gambar Produk" class="table-img"></td>
                            <td><?php echo htmlspecialchars($produk['nama_produk']); ?></td>
                            <td>Rp. <?php echo number_format($produk['harga'], 0, ',', '.'); ?></td>
                            <td><?php echo $produk['stok']; ?></td>
                            <td class="action-links">
                                <a href="edit_produk.php?id=<?php echo $produk['id_produk']; ?>" class="btn-edit"><i class="fa-solid fa-pencil"></i></a>
                                <a href="proses_produk.php?action=delete&id=<?php echo $produk['id_produk']; ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
<script>
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('show');
    });
</script>
<script src="../js/manage_produk.js"></script>

<script>
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('show');
    });
</script>
</body>
</html>
</body>
</html>