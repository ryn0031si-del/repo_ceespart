<?php
// Panggil file konfigurasi untuk memulai session dan koneksi
require_once 'config.php';

// Ambil data email dan password dari formulir
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];

// Validasi agar tidak kosong
if (empty($email) || empty($password)) {
    header("location: login.php?error=Email dan password wajib diisi!");
    exit();
}

// Siapkan query untuk mencari pengguna berdasarkan email
$sql = "SELECT id_user, nama_lengkap, password, role FROM users WHERE email = ?";

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $email);
    
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        
        // Cek apakah pengguna dengan email tersebut ada (hasilnya harus 1)
        if (mysqli_stmt_num_rows($stmt) == 1) {
            // Ikat hasil query ke variabel
            mysqli_stmt_bind_result($stmt, $id_user, $nama_lengkap, $hashed_password, $role);
            
            if (mysqli_stmt_fetch($stmt)) {
                // Verifikasi password yang diinput dengan password hash di database
                if (password_verify($password, $hashed_password)) {
                    // Jika password cocok, simpan data ke session
                    session_start();
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id_user"] = $id_user;
                    $_SESSION["nama_lengkap"] = $nama_lengkap;
                    $_SESSION["role"] = $role;
                    
                    // Arahkan pengguna ke halaman utama
                    header("location: index.php");
                } else {
                    // Jika password tidak cocok
                    header("location: login.php?error=Email atau password yang Anda masukkan salah.");
                }
            }
        } else {
            // Jika email tidak ditemukan
            header("location: login.php?error=Email atau password yang Anda masukkan salah.");
        }
    } else {
        echo "Terjadi kesalahan. Silakan coba lagi nanti.";
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>