<?php
// Panggil file konfigurasi untuk koneksi database
require_once 'config.php';

// Ambil data dari formulir dan bersihkan untuk keamanan
$nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
$password = $_POST['password'];
$role = 'customer'; // Atur role default untuk setiap pendaftar baru

// Validasi sederhana agar tidak ada data yang kosong
if (empty($nama_lengkap) || empty($email) || empty($no_hp) || empty($alamat) || empty($password)) {
    // Jika ada yang kosong, kembalikan ke halaman registrasi dengan pesan error
    header("location: login.php?error=Semua kolom wajib diisi!");
    exit();
}

// Cek apakah email atau no_hp sudah terdaftar
$sql_check = "SELECT id_user FROM users WHERE email = ? OR no_hp = ?";
if ($stmt_check = mysqli_prepare($conn, $sql_check)) {
    mysqli_stmt_bind_param($stmt_check, "ss", $email, $no_hp);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);
    
    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        // Jika sudah ada, kembalikan dengan pesan error
        header("location: login.php?error=Email atau Nomor HP sudah terdaftar.");
        exit();
    }
    mysqli_stmt_close($stmt_check);
}

// Enkripsi password menggunakan metode hashing yang aman
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Siapkan query SQL untuk menyimpan pengguna baru
$sql = "INSERT INTO users (nama_lengkap, email, no_hp, alamat, password, role) VALUES (?, ?, ?, ?, ?, ?)";

if ($stmt = mysqli_prepare($conn, $sql)) {
    // Ikat variabel ke statement sebagai parameter
    mysqli_stmt_bind_param($stmt, "ssssss", $nama_lengkap, $email, $no_hp, $alamat, $hashed_password, $role);
    
    // Coba eksekusi statement
    if (mysqli_stmt_execute($stmt)) {
        // Jika berhasil, arahkan ke halaman login dengan pesan sukses
        header("location: login.php?success=Registrasi berhasil! Silakan masuk.");
    } else {
        // Jika gagal, kembalikan dengan pesan error umum
        header("location: login.php?error=Terjadi kesalahan. Silakan coba lagi.");
    }
    mysqli_stmt_close($stmt);
}

// Tutup koneksi database
mysqli_close($conn);
?>