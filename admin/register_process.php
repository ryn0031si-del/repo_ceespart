<?php
require_once '../config.php';

// Ambil data dari form
$nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
$password = $_POST['password'];
$alamat = 'Kantor Admin'; // Alamat default untuk admin
$role = 'admin'; // Peran diatur secara paksa menjadi 'admin'

if (empty($nama_lengkap) || empty($email) || empty($no_hp) || empty($password)) {
    header("location: register.php?error=Semua kolom wajib diisi!");
    exit();
}

// Cek duplikasi email atau no_hp
$sql_check = "SELECT id_user FROM users WHERE email = ? OR no_hp = ?";
if ($stmt_check = mysqli_prepare($conn, $sql_check)) {
    mysqli_stmt_bind_param($stmt_check, "ss", $email, $no_hp);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);
    
    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        header("location: register.php?error=Email atau Nomor HP sudah terdaftar.");
        exit();
    }
    mysqli_stmt_close($stmt_check);
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (nama_lengkap, email, no_hp, alamat, password, role) VALUES (?, ?, ?, ?, ?, ?)";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "ssssss", $nama_lengkap, $email, $no_hp, $alamat, $hashed_password, $role);
    
    if (mysqli_stmt_execute($stmt)) {
        header("location: register.php?success=Admin baru berhasil didaftarkan!");
    } else {
        header("location: register.php?error=Terjadi kesalahan. Coba lagi.");
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
?>