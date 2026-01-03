<?php
require_once '../config.php';
// Keamanan halaman
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    header("location: login.php"); exit;
}

// Ambil data produk berdasarkan ID dari URL
$id_produk = intval($_GET['id']);
$produk = null;
$sql = "SELECT * FROM produk WHERE id_produk = ?";
if($stmt = mysqli_prepare($conn, $sql)){
    mysqli_stmt_bind_param($stmt, "i", $id_produk);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $produk = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);

// Jika produk tidak ditemukan, hentikan eksekusi
if(!$produk) { 
    die("Produk tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Admin Panel</title>
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
            <h1>Edit Produk: <?php echo htmlspecialchars($produk['nama_produk']); ?></h1>
        </header>

        <div class="content-container">
            <form action="proses_produk.php" method="post" enctype="multipart/form-data" class="data-form product-form-grid">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_produk" value="<?php echo $produk['id_produk']; ?>">
                <input type="hidden" name="gambar_lama" value="<?php echo $produk['gambar_produk']; ?>">

                <div class="form-column-left">
                    <div class="form-group">
                        <label for="nama_produk">Nama Produk</label>
                        <input type="text" id="nama_produk" name="nama_produk" value="<?php echo htmlspecialchars($produk['nama_produk']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga (Rp)</label>
                        <input type="number" id="harga" name="harga" value="<?php echo $produk['harga']; ?>" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input type="number" id="stok" name="stok" value="<?php echo $produk['stok']; ?>" min="0" required>
                    </div>
                </div>

                <div class="form-column-right">
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Produk</label>
                        <textarea id="deskripsi" name="deskripsi" rows="7"><?php echo htmlspecialchars($produk['deskripsi']); ?></textarea>
                    </div>
                    <div class="form-group form-group-upload">
                        <label for="gambar_produk" style="background-image: url('../assets/images/<?php echo htmlspecialchars($produk['gambar_produk']); ?>'); background-size: cover; background-position: center;">
                            <i class="fa-solid fa-cloud-arrow-up" style="display: none;"></i>
                            <span>Klik untuk ganti gambar</span>
                            <span class="filename" id="filename"></span>
                        </label>
                        <input type="file" id="gambar_produk" name="gambar_produk" accept="image/*">
                    </div>
                </div>

                <div class="form-span-2 form-actions">
                    <a href="manage_produk.php" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Update Produk</button>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
    // Script untuk menu toggle
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('show');
    });

    // Script untuk preview gambar
    const fileInput = document.getElementById('gambar_produk');
    const filenameDisplay = document.getElementById('filename');
    const uploadLabel = document.querySelector('.form-group-upload label');

    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            filenameDisplay.textContent = file.name;
            const reader = new FileReader();
            reader.onload = function(e) {
                uploadLabel.style.backgroundImage = `url('${e.target.result}')`;
            }
            reader.readAsDataURL(file);
        } else {
            filenameDisplay.textContent = '';
            uploadLabel.style.backgroundImage = `url('../assets/images/<?php echo htmlspecialchars($produk['gambar_produk']); ?>')`;
        }
    });
</script>

</body>
</html>