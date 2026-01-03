<?php
// PERBAIKAN 1: Hapus "../" karena config.php ada di satu folder yang sama
require_once "config.php"; 

if(isset($_POST['email'])) {

    $email = $_POST['email'];

    // Cek apakah email ada (gunakan prepared statement agar lebih aman)
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        header("Location: forgot_password.php?error=Email tidak ditemukan!");
        exit;
    }

    // Buat token
    $token = bin2hex(random_bytes(32));

    // Simpan token di database
    $stmt_update = $conn->prepare("UPDATE users SET reset_token=? WHERE email=?");
    $stmt_update->bind_param("ss", $token, $email);
    $stmt_update->execute();

    // PERBAIKAN 2: Sesuaikan nama folder di URL
    // Dari 'ce3s' menjadi 'ce3s_part' sesuai screenshot kamu
    // Pastikan apakah file reset_password.php ada di dalam folder 'login' atau di folder utama?
    // Jika di folder utama, hapus '/login'
    $resetLink = "http://localhost/ce3s_part/reset_password.php?token=".$token;

    // Redirect
    header("Location: forgot_password.php?success=Link reset: " . $resetLink);
    exit;
}

header("Location: forgot_password.php?error=Terjadi kesalahan!");
exit;
?>