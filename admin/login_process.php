<?php
require_once '../config.php';

// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil data dari form
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];
$role_form = isset($_POST['role']) ? $_POST['role'] : ''; // Ambil input hidden role dari form

if (empty($email) || empty($password)) {
    header("location: login.php?error=Email dan password wajib diisi!");
    exit();
}

// Ambil data user berdasarkan email
$sql = "SELECT id_user, nama_lengkap, password, role FROM users WHERE email = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $email);
    
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) == 1) {
            mysqli_stmt_bind_result($stmt, $id_user, $nama_lengkap, $hashed_password, $role_db);
            
            if (mysqli_stmt_fetch($stmt)) {
                // 1. Verifikasi Password
                if (password_verify($password, $hashed_password)) {
                    
                    // 2. Verifikasi kesesuaian Role (Admin vs Operator)
                    // Mencegah operator login di form admin dan sebaliknya
                    if ($role_db === $role_form) {
                        
                        // Set Session
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id_user"] = $id_user;
                        $_SESSION["nama_lengkap"] = $nama_lengkap;
                        $_SESSION["role"] = $role_db;
                        
                        // ✨ FITUR: Catat aktivitas login ke Audit Logs
                        // Fungsi catatLog() diambil dari config.php Anda
                        catatLog($id_user, $role_db, 'Login', 'Berhasil login ke panel ' . $role_db);
                        
                        // 3. Alur Redirect berdasarkan Role
                        if ($role_db === 'admin') {
                            header("location: index.php"); // Ubah ke dashboard.php agar tidak ke index customer
                        } elseif ($role_db === 'operator') {
                            header("location: ../operator/index.php");
                        }
                        exit();
                        
                    } else {
                        header("location: login.php?error=Akses ditolak. Peran akun tidak sesuai.");
                    }
                } else {
                    header("location: login.php?error=Password salah.");
                }
            }
        } else {
            header("location: login.php?error=Akun tidak ditemukan.");
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
?>