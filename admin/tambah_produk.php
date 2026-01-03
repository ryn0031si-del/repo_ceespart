<?php
require_once '../config.php';

// ✅ Cek apakah session sudah aktif sebelum start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Validasi akses admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Baru - Admin</title>
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
            <h1>Tambah Produk Baru</h1>
        </header>

        <div class="content-container">
            <!-- ✅ Action langsung kirim ke proses_produk.php dengan parameter action=tambah -->
            <form action="proses_produk.php?action=tambah" method="post" enctype="multipart/form-data" class="data-form product-form-grid">
                
                <div class="form-column-left">
                    <div class="form-group">
                        <label for="nama_produk">Nama Produk</label>
                        <input type="text" id="nama_produk" name="nama_produk" required>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga (Rp)</label>
                        <input type="number" id="harga" name="harga" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input type="number" id="stok" name="stok" min="0" required>
                    </div>
                </div>

                <div class="form-column-right">
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Produk</label>
                        <textarea id="deskripsi" name="deskripsi" rows="7"></textarea>
                    </div>
                    <div class="form-group form-group-upload">
                        <label for="gambar_produk">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span>Pilih atau seret gambar ke sini</span>
                            <span class="filename" id="filename"></span>
                        </label>
                        <input type="file" id="gambar_produk" name="gambar_produk" accept="image/*" required>
                    </div>
                </div>

                <div class="form-span-2 form-actions">
                    <a href="manage_produk.php" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Produk</button>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
    // Menu toggle sidebar
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('show');
    });

    // Menampilkan nama file upload
    const fileInput = document.getElementById('gambar_produk');
    const filenameDisplay = document.getElementById('filename');
    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            filenameDisplay.textContent = fileInput.files[0].name;
        } else {
            filenameDisplay.textContent = '';
        }
    });
</script>

</body>
</html>
