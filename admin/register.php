<?php
require_once '../config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Admin - Ce'3s Part</title>
    <link rel="stylesheet" href="auth_style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h2>Buat Akun Admin Baru</h2>
            <p>Formulir ini hanya untuk mendaftarkan administrator baru.</p>
            
            <?php 
            if(isset($_GET['error'])) {
                echo '<p class="error-message">' . htmlspecialchars($_GET['error']) . '</p>';
            }
            if(isset($_GET['success'])) {
                echo '<p class="success-message">' . htmlspecialchars($_GET['success']) . '</p>';
            }
            ?>

            <form action="register_process.php" method="post">
                <div class="input-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" required>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="no_hp">Nomor HP</label>
                    <input type="tel" id="no_hp" name="no_hp" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn-primary">Daftarkan Admin</button>
            </form>
            
            <p class="bottom-text">Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>
</body>
</html>