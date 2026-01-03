<?php
require_once '../config.php';

// Keamanan Halaman Admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    header("location: login.php");
    exit;
}

// Pastikan request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi input
    $id_pesanan = intval($_POST['id_pesanan']);
    $status_baru = $_POST['status_pesanan'];
    
    // Daftar status yang valid untuk keamanan
    $status_valid = ['Menunggu Pembayaran', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'];

    if ($id_pesanan > 0 && in_array($status_baru, $status_valid)) {
        // Siapkan query UPDATE
        $sql = "UPDATE pesanan SET status_pesanan = ? WHERE id_pesanan = ?";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $status_baru, $id_pesanan);
            
            // Eksekusi dan redirect
            if (mysqli_stmt_execute($stmt)) {
                header("location: manage_pesanan.php?status=sukses");
            } else {
                header("location: manage_pesanan.php?status=gagal");
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        header("location: manage_pesanan.php?status=gagal");
    }
} else {
    // Jika bukan POST, kembalikan ke halaman kelola pesanan
    header("location: manage_pesanan.php");
}

mysqli_close($conn);
?>