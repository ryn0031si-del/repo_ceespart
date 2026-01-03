<?php
session_start(); // Pastikan session dimulai
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Tentang Kami - Ce'3s Part</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/index.css"> <link rel="stylesheet" href="css/about.css">

</head>
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
                <li><a href="about.php" class="active">About</a></li>
            </ul>
            <div class="auth-links">
                <?php if (!empty($_SESSION["loggedin"])): ?>
                    <a href="profil.php" class="profile-link">
                        <i class="fa-regular fa-user"></i> Halo, <?= htmlspecialchars($_SESSION["nama_lengkap"] ?? "User") ?>
                    </a>
                    <a href="logout.php" class="btn-nav-logout">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="profile-link">Login</a>
                    <a href="register.php" class="btn-nav-register">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>

<main class="container">
    <div class="page-header">
        <h2>Tentang Ce'3s Part</h2>
        <p>Mengenal lebih dekat siapa kami dan apa yang kami lakukan.</p>
    </div>

    <section class="about-section">
        <div class="about-image">
            <img src="https://images.unsplash.com/photo-1558981403-c5f9899a28bc?q=80&w=2070" alt="Workshop Ce'3s Part">
        </div>
        <div class="about-content">
            <h3>Partner Terpercaya Pengendara Motor</h3>
            <p>
                Ce'3s Part lahir dari kecintaan terhadap otomotif dan keinginan memberikan solusi spare part 
                berkualitas dan terpercaya bagi pengendara motor di Indonesia. Kami paham bahwa performa 
                dan keamanan adalah segalanya.
            </p>
            <p>
                Kami berkomitmen menyediakan produk original dari merek ternama. Kami bukan hanya penjual, 
                tapi partner Anda di setiap perjalanan.
            </p>
            
            <div class="stats-grid">
                <div class="stat-item">
                    <h4>100%</h4>
                    <span>Original</span>
                </div>
                <div class="stat-item">
                    <h4>24/7</h4>
                    <span>Support</span>
                </div>
            </div>
        </div>
    </section>

    <section class="vision-mission">
        <div class="vision-mission-card">
            <div class="icon-box"><i class="fa-solid fa-bullseye"></i></div>
            <h4>Misi Kami</h4>
            <p>Menyediakan platform e-commerce lengkap dan mudah digunakan untuk kebutuhan suku cadang motor dengan pelayanan cepat dan responsif.</p>
        </div>
        <div class="vision-mission-card">
            <div class="icon-box"><i class="fa-solid fa-eye"></i></div>
            <h4>Visi Kami</h4>
            <p>Menjadi destinasi utama dan terpercaya bagi pengendara motor di Indonesia dalam mencari dan membeli suku cadang berkualitas tinggi.</p>
        </div>
    </section>

    <section class="team-section">
        <h2>Tim Kami</h2>
        <div class="team-grid">
            <?php 
            $team = [
                ["Anomali", "CEO & Founder", "Anomali.jpg"],
                ["Anomali 1", "Head of Operations", "placeholder_user.jpg"],
                ["Anomali 2", "Marketing Specialist", "placeholder_user.jpg"],
                ["Anomali 3", "Customer Support", "placeholder_user.jpg"]
            ];
            foreach($team as $t): ?>
            <div class="team-member">
                <div class="member-img">
                    <img src="assets/images/<?= htmlspecialchars($t[2]) ?>" alt="<?= htmlspecialchars($t[0]) ?>" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($t[0]) ?>&background=random'">
                </div>
                <h4><?= htmlspecialchars($t[0]) ?></h4>
                <span><?= htmlspecialchars($t[1]) ?></span>
                
                <div class="member-social">
                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<footer class="footer">
    <div class="container footer-container">
        <div class="footer-column">
            <h3>Ce'3s Part</h3>
            <p>Toko spare part motor terpercaya yang menyediakan produk original & berkualitas untuk menunjang performa kendaraan Anda.</p>
        </div>
        <div class="footer-column">
            <h4>Kontak Kami</h4>
            <ul>
                <li><i class="fa-solid fa-location-dot"></i> Jl. Contoh No.123, Karawang</li>
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
        <p>&copy; <?= date('Y') ?> Ce'3s Part. All Rights Reserved.</p>
    </div>
</footer>

<script src="js/script.js"></script>
</body>
</html>