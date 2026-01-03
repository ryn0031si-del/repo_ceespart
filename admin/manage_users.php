<?php
require_once '../config.php';

// Admin Page Security
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    header("location: login.php");
    exit;
}

// Fetch all customer data from the database
$user_list = [];
$sql = "SELECT id_user, nama_lengkap, email, no_hp, alamat, dibuat_pada 
        FROM users 
        WHERE role = 'customer' 
        ORDER BY dibuat_pada DESC";
$result = mysqli_query($conn, $sql);
if ($result) {
    $user_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Admin Panel</title>
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
                <li><a href="manage_pesanan.php"><i class="fa-solid fa-receipt"></i> Kelola Pesanan</a></li>
                <li><a href="manage_users.php" class="active"><i class="fa-solid fa-users"></i> Kelola Pengguna</a></li>
                <li><a href="admin_chat.php"><i class="fa-solid fa-comments"></i> Chat</a></li>
                <li><a href="../index.php" target="_blank"><i class="fa-solid fa-globe"></i> Lihat Website</a></li>
                <li><a href="../logout.php"><i class="fa-solid fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <header class="main-header">
            <button class="menu-toggle" id="menu-toggle"><i class="fa-solid fa-bars"></i></button>
            <h1>Kelola Pengguna</h1>
        </header>

        <div class="content-container">
            <?php 
            if(isset($_GET['status'])) {
                if ($_GET['status'] == 'sukses') echo '<div class="alert alert-success">Pengguna berhasil dihapus!</div>';
                if ($_GET['status'] == 'gagal') echo '<div class="alert alert-danger">Gagal menghapus pengguna!</div>';
            }
            ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Alamat</th>
                            <th>Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($user_list)): ?>
                            <tr><td colspan="7" style="text-align: center;">Tidak ada pengguna terdaftar.</td></tr>
                        <?php else: ?>
                            <?php foreach ($user_list as $user): ?>
                            <tr>
                                <td><?php echo $user['id_user']; ?></td>
                                <td><?php echo htmlspecialchars($user['nama_lengkap']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['no_hp']); ?></td>
                                <td><?php echo htmlspecialchars($user['alamat']); ?></td>
                                <td><?php echo date("d M Y", strtotime($user['dibuat_pada'])); ?></td>
                                <td class="action-links">
                                    <a href="proses_pengguna.php?action=delete&id=<?php echo $user['id_user']; ?>" class="btn-delete" onclick="return confirm('PERINGATAN: Menghapus pengguna juga akan menghapus semua data terkait seperti pesanan dan chat. Apakah Anda yakin?');">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
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