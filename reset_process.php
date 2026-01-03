<?php
// PERBAIKAN: Path config disesuaikan (tanpa ../)
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Ambil data dari form
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 2. Validasi input
    if (empty($token) || empty($password) || empty($confirm_password)) {
        die("Error: Data tidak lengkap!");
    }

    // 3. Cek apakah password dan konfirmasi sama
    if ($password !== $confirm_password) {
        die("Error: Konfirmasi password tidak cocok! <a href='javascript:history.back()'>Kembali</a>");
    }

    // 4. Cek apakah token valid di database
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token=? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Error: Token tidak valid atau kadaluarsa!");
    }

    // 5. Hash password baru (PENTING untuk keamanan)
    // Jangan simpan password mentah! Gunakan password_hash
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 6. Update password user & Hapus token agar tidak bisa dipakai lagi
    $stmt_update = $conn->prepare("UPDATE users SET password=?, reset_token=NULL WHERE reset_token=?");
    $stmt_update->bind_param("ss", $hashed_password, $token);
    
    if ($stmt_update->execute()) {
        // Sukses! Redirect ke halaman login
        // Pastikan path login.php sesuai dengan struktur foldermu
        // Jika login.php ada di dalam folder 'login', gunakan: header("Location: login/login.php?reset=success");
        // Jika login.php ada di folder utama (ce3s_part), gunakan ini:
        header("Location: login.php?reset=success"); 
        exit;
    } else {
        echo "Error saat mengupdate password: " . $conn->error;
    }

} else {
    // Jika user mencoba buka file ini langsung tanpa lewat form
    header("Location: login.php");
    exit;
}
?>