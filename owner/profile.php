<?php
require_once '../config.php';

// Proteksi Akses Owner
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'owner') {
    header("location: login.php");
    exit;
}

$owner_id = $_SESSION['id_user'];
$msg = "";

// Ambil data terbaru dari database
$query_user = mysqli_query($conn, "SELECT nama_lengkap, email FROM users WHERE id_user = '$owner_id'");
$data = mysqli_fetch_assoc($query_user);

// --- LOGIKA UPDATE PROFIL ---
if (isset($_POST['update_profile'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "UPDATE users SET nama_lengkap = '$nama', email = '$email' WHERE id_user = '$owner_id'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['nama_lengkap'] = $nama; // Update session agar nama di header berubah
        catatLog($owner_id, 'owner', 'Update Profil', 'Mengubah informasi profil mandiri');
        $msg = "success_profile";
    }
}

// --- LOGIKA UPDATE PASSWORD ---
if (isset($_POST['update_password'])) {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // Ambil password lama di DB
    $res = mysqli_query($conn, "SELECT password FROM users WHERE id_user = '$owner_id'");
    $pass_db = mysqli_fetch_assoc($res)['password'];

    if (password_verify($old_pass, $pass_db)) {
        if ($new_pass === $confirm_pass) {
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE users SET password = '$hashed' WHERE id_user = '$owner_id'");
            catatLog($owner_id, 'owner', 'Update Password', 'Berhasil mengganti password mandiri');
            $msg = "success_password";
        } else {
            $msg = "err_confirm";
        }
    } else {
        $msg = "err_old";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan Profil - Owner</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="dashboard-wrapper">
    <aside class="sidebar">
        <div class="sidebar-header"><h3>OWNER PANEL</h3></div>
        <ul class="nav-links">
            <li><a href="dashboard.php"><i class="fa-solid fa-gauge"></i> <span>Dashboard</span></a></li>
            <li><a href="laporan.php"><i class="fa-solid fa-chart-pie"></i> <span>Laporan Keuangan</span></a></li>
            <li><a href="audit_logs.php"><i class="fa-solid fa-list-check"></i> <span>Audit Logs</span></a></li>
            <li><a href="manage_admin.php"><i class="fa-solid fa-user-shield"></i> <span>Kelola Admin</span></a></li>
            <li><a href="profile.php" class="active"><i class="fa-solid fa-user-gear"></i> <span>Pengaturan Profil</span></a></li>
            <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 10px 0;">
            <li><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span></a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="content-header">
            <h1>Account Settings</h1>
            <p style="color: var(--text-gray);">Kelola informasi pribadi dan keamanan akun Anda</p>
        </div>

        <?php if($msg == "success_profile"): ?>
            <div style="background: rgba(46, 204, 113, 0.2); color: #2ecc71; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #2ecc71;">
                <i class="fa-solid fa-check-circle"></i> Profil berhasil diperbarui!
            </div>
        <?php elseif($msg == "success_password"): ?>
            <div style="background: rgba(46, 204, 113, 0.2); color: #2ecc71; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #2ecc71;">
                <i class="fa-solid fa-lock"></i> Password berhasil diganti!
            </div>
        <?php elseif($msg == "err_old"): ?>
            <div style="background: rgba(231, 76, 60, 0.2); color: #e74c3c; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e74c3c;">
                <i class="fa-solid fa-circle-xmark"></i> Password lama salah!
            </div>
        <?php elseif($msg == "err_confirm"): ?>
            <div style="background: rgba(231, 76, 60, 0.2); color: #e74c3c; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e74c3c;">
                <i class="fa-solid fa-circle-xmark"></i> Konfirmasi password tidak cocok!
            </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <div class="data-container">
                <div class="section-title">
                    <i class="fa-solid fa-id-card"></i>
                    <span>Informasi Dasar</span>
                </div>
                <form action="" method="POST" style="margin-top: 20px;">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="color: var(--owner-gold); font-size: 0.8rem; display: block; margin-bottom: 5px;">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($data['nama_lengkap']) ?>" style="width: 100%; padding: 10px; background: var(--bg-deep); border: 1px solid var(--border-subtle); color: #fff; border-radius: 8px;" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="color: var(--owner-gold); font-size: 0.8rem; display: block; margin-bottom: 5px;">Alamat Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" style="width: 100%; padding: 10px; background: var(--bg-deep); border: 1px solid var(--border-subtle); color: #fff; border-radius: 8px;" required>
                    </div>
                    <button type="submit" name="update_profile" style="background: var(--owner-gold); border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer;">Update Profil</button>
                </form>
            </div>

            <div class="data-container">
                <div class="section-title">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>Keamanan Akun</span>
                </div>
                <form action="" method="POST" style="margin-top: 20px;">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="color: var(--owner-gold); font-size: 0.8rem; display: block; margin-bottom: 5px;">Password Saat Ini</label>
                        <input type="password" name="old_password" placeholder="Masukkan password lama" style="width: 100%; padding: 10px; background: var(--bg-deep); border: 1px solid var(--border-subtle); color: #fff; border-radius: 8px;" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="color: var(--owner-gold); font-size: 0.8rem; display: block; margin-bottom: 5px;">Password Baru</label>
                        <input type="password" name="new_password" placeholder="Min. 6 karakter" style="width: 100%; padding: 10px; background: var(--bg-deep); border: 1px solid var(--border-subtle); color: #fff; border-radius: 8px;" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="color: var(--owner-gold); font-size: 0.8rem; display: block; margin-bottom: 5px;">Konfirmasi Password Baru</label>
                        <input type="password" name="confirm_password" placeholder="Ulangi password baru" style="width: 100%; padding: 10px; background: var(--bg-deep); border: 1px solid var(--border-subtle); color: #fff; border-radius: 8px;" required>
                    </div>
                    <button type="submit" name="update_password" style="background: transparent; border: 1px solid var(--owner-gold); color: var(--owner-gold); padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer;">Ganti Password</button>
                </form>
            </div>
        </div>
    </main>
</div>

</body>
</html>