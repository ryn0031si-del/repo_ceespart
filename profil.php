<?php
// Memanggil file config untuk memulai session dan koneksi database
require_once 'config.php';

// --- BAGIAN KEAMANAN ---
// Cek apakah pengguna sudah login. Jika belum, tendang ke halaman login.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Ambil ID pengguna dari session yang sedang aktif
$id_user = $_SESSION["id_user"];
$user = null; // Siapkan variabel untuk menampung data user

// Siapkan query untuk mengambil semua data pengguna berdasarkan ID-nya
$sql = "SELECT nama_lengkap, email, no_hp, alamat, dibuat_pada FROM users WHERE id_user = ?";

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id_user);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        // Ambil hasil query sebagai sebuah array
        $user = mysqli_fetch_assoc($result);
    } else {
        echo "Terjadi kesalahan saat mengambil data.";
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);

// Jika karena suatu alasan data user tidak ditemukan, tampilkan pesan error
if ($user === null) {
    die("Data pengguna tidak dapat ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Ce'3s Part</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/index.css"> <link rel="stylesheet" href="css/profil.css"> </head>
<body>

    <header>
        <nav class="container">
            <a href="index.php" class="logo">Ce'3s Part</a>

            <div class="hamburger" id="hamburger">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>

            <div class="nav-menu" id="nav-menu">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="produk.php">Produk</a></li>
                    <li><a href="pesanan.php">Pesanan Saya</a></li>
                    <li><a href="chat.php">Chat</a></li>
                    <li><a href="about.php">About</a></li>
                </ul>
                <div class="auth-links">
                    <a href="profil.php" class="profile-link active-link">
                        <i class="fa-regular fa-user"></i> Halo, <?php echo htmlspecialchars($_SESSION["nama_lengkap"]); ?>
                    </a>
                    <a href="logout.php" class="btn-nav-logout">Logout</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="page-header">
            <h2>Profil Saya</h2>
            <p>Lihat informasi pribadi Anda di sini.</p>
        </div>

        <div class="profile-card">
            <div class="profile-header-card">
                <div class="avatar-circle">
                    <?php 
                        $initials = strtoupper(substr($user['nama_lengkap'], 0, 2));
                        echo $initials;
                    ?>
                </div>
                <h3><?php echo htmlspecialchars($user['nama_lengkap']); ?></h3>
                <span class="member-badge">Member</span>
            </div>

            <div class="profile-info">
                <div class="info-group">
                    <div class="info-item">
                        <span class="info-label"><i class="fa-regular fa-envelope"></i> Email</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="fa-solid fa-phone"></i> Nomor HP</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['no_hp']); ?></span>
                    </div>
                </div>
                
                <div class="info-group">
                    <div class="info-item full-width">
                        <span class="info-label"><i class="fa-solid fa-location-dot"></i> Alamat</span>
                        <span class="info-value"><?php echo nl2br(htmlspecialchars($user['alamat'])); ?></span>
                    </div>
                </div>

                <div class="info-group">
                     <div class="info-item">
                        <span class="info-label"><i class="fa-regular fa-calendar"></i> Bergabung Sejak</span>
                        <span class="info-value"><?php echo date("d F Y", strtotime($user['dibuat_pada'])); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="profile-actions">
                <a href="pesanan.php" class="btn-secondary"><i class="fa-solid fa-box-open"></i> Riwayat Pesanan</a>
                <a href="logout.php" class="btn-danger"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container footer-container">
            <div class="footer-column">
                <h3>Ce'3s Part</h3>
                <p>
                    Toko spare part motor terpercaya yang menyediakan produk original & berkualitas
                    untuk menunjang performa kendaraan Anda.
                </p>
            </div>
            <div class="footer-column">
                <h4>Kontak Kami</h4>
                <ul>
                    <li><i class="fa-solid fa-location-dot"></i> Jl. Contoh No. 123, Karawang</li>
                    <li><i class="fa-solid fa-phone"></i> 0812-3456-7890</li>
                    <li><i class="fa-solid fa-envelope"></i> support@ce3spart.com</li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Ikuti Kami</h4>
                <div class="social-links">
                    <a href="https://www.facebook.com/share/1Bu5u31u82/"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/gk_pnyanama007" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="https://wa.me/qr/5JO24PB5ZBI7E1" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Ce'3s Part. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>