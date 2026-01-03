<?php
// Selalu mulai session di awal
session_start();

// Hapus semua data variabel dari session
$_SESSION = array();

// Hancurkan session
session_destroy();

// Arahkan pengguna kembali ke halaman utama
header("location: index.php");
exit;
?>