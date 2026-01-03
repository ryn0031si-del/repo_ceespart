<?php
require_once '../config.php';

// Proteksi Akses Owner: Pastikan hanya role owner yang bisa masuk
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'owner') {
    header("location: login.php");
    exit;
}

$error = "";
$success = "";

// Proses Form saat Tombol Daftar diklik
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = $_POST['password'];

    // 1. Validasi: Cek apakah email sudah terdaftar
    $check_email = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
    
    if (mysqli_num_rows($check_email) > 0) {
        $error = "Email ini sudah terdaftar. Gunakan email lain.";
    } else {
        // 2. Hash Password untuk keamanan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 3. Simpan ke database
        $sql = "INSERT INTO users (nama_lengkap, email, password, role) VALUES ('$nama', '$email', '$hashed_password', '$role')";
        
        if (mysqli_query($conn, $sql)) {
            // 4. Catat aktivitas ke Audit Logs
            catatLog($_SESSION['id_user'], 'owner', 'Tambah Staff', "Mendaftarkan staff baru: $nama ($role)");
            
            $success = "Staff baru berhasil didaftarkan!";
        } else {
            $error = "Gagal mendaftarkan staff. Terjadi kesalahan sistem.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Staff - Ce'3s Part</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3>OWNER PANEL</h3>
            </div>
            <ul class="nav-links">
                <li><a href="dashboard.php"><i class="fa-solid fa-gauge"></i> <span>Dashboard</span></a></li>
                <li><a href="laporan.php"><i class="fa-solid fa-chart-pie"></i> <span>Laporan Keuangan</span></a></li>
                <li><a href="audit_logs.php"><i class="fa-solid fa-list-check"></i> <span>Audit Logs</span></a></li>
                <li><a href="manage_admin.php" class="active"><i class="fa-solid fa-user-shield"></i> <span>Kelola Admin</span></a></li>
                <li><a href="profile.php"><i class="fa-solid fa-user-gear"></i> <span>Pengaturan Profil</span></a></li>
                <li><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span></a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="content-header">
                <div>
                    <h1>Registrasi Staff</h1>
                    <p style="color: var(--text-gray);">Menambahkan akses Admin atau Operator baru</p>
                </div>
                <a href="manage_admin.php" class="btn-add-user" style="background: transparent; border: 1px solid var(--owner-gold); color: var(--owner-gold);">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div style="max-width: 600px; margin: 0 auto; animation: fadeIn 0.8s ease-out;">
                <div class="data-container">
                    <div class="section-title">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Detail Akun Staff Baru</span>
                    </div>

                    <?php if ($error): ?>
                        <div class="error-msg" style="margin-bottom: 20px;"><?= $error ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div style="background: rgba(46, 204, 113, 0.1); color: #2ecc71; padding: 15px; border-radius: 12px; border: 1px solid rgba(46, 204, 113, 0.2); margin-bottom: 20px;">
                            <i class="fa-solid fa-circle-check"></i> <?= $success ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" autocomplete="off">
                        <div class="form-group">
                            <label>Nama Lengkap Staff</label>
                            <input type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap" required 
                                   style="width: 100%; padding: 12px 15px; background: var(--bg-deep); border: 1px solid var(--border-subtle); color: white; border-radius: 10px;">
                        </div>

                        <div class="form-group">
                            <label>Alamat Email Resmi</label>
                            <input type="email" name="email" placeholder="contoh@ce3spart.com" required 
                                   style="width: 100%; padding: 12px 15px; background: var(--bg-deep); border: 1px solid var(--border-subtle); color: white; border-radius: 10px;">
                        </div>

                        <div class="form-group">
                            <label>Hak Akses (Role)</label>
                            <select name="role" required 
                                    style="width: 100%; padding: 12px 15px; background: var(--bg-deep); border: 1px solid var(--border-subtle); color: white; border-radius: 10px; cursor: pointer;">
                                <option value="" disabled selected>Pilih Role...</option>
                                <option value="admin">Admin (Akses Penuh)</option>
                                <option value="operator">Operator (Akses Transaksi)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Kata Sandi Default</label>
                            <div class="form-group">
    <input type="password" name="password" placeholder="Minimal 6 karakter" required 
           style="width: 100%; padding: 12px 15px; background: var(--bg-deep); border: 1px solid var(--border-subtle); color: white; border-radius: 10px;">
</div>

                        <button type="submit" class="btn-add-user" 
                                style="width: 100%; justify-content: center; margin-top: 25px; padding: 15px; font-size: 1rem; border: none; cursor: pointer;">
                            Daftarkan Staff Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="css/script.js"></script>
</body>
</html>