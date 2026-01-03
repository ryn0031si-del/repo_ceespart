<?php
// 🔧 Konfigurasi Database
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'db_ce3s_part');

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME)
    or die("❌ Gagal koneksi: " . mysqli_connect_error());

// 🧠 Konfigurasi Umum
if (session_status() === PHP_SESSION_NONE) session_start();
date_default_timezone_set('Asia/Jakarta');

define('SHOP_NAME', "Ce'3s Part");
define('SHOP_ADDRESS', "Jl. Raya Telukjambe Timur, Karawang, Jawa Barat");
define('SHOP_PHONE', "0812-3456-7890");

// 🗺️ Google Maps API
define('GOOGLE_API_KEY', 'ISI_API_KEY_KAMU_DI_SINI');

// 💰 Tarif Ongkir
define('TARIF_PER_KM', 2000);
define('TARIF_PER_KG', 5000);
define('FAKTOR_VOLUMETRIK', 6000);

// 🧮 Hitung Jarak (via Google Maps API)
function hitungJarak($alamat) {
    if (GOOGLE_API_KEY === 'ISI_API_KEY_KAMU_DI_SINI') return 0;
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . urlencode(SHOP_ADDRESS) .
           "&destinations=" . urlencode($alamat) . "&key=" . GOOGLE_API_KEY;
    $data = @json_decode(@file_get_contents($url), true);
    return $data['rows'][0]['elements'][0]['distance']['value'] / 1000 ?? 0;
}

// 🔒 Fungsi Keamanan & Format
function safe($str) { return htmlspecialchars($str, ENT_QUOTES, 'UTF-8'); }
function rupiah($n) { return "Rp " . number_format($n, 0, ',', '.'); }

/**
 * 🛠️ Fitur Baru: Fungsi untuk mencatat aktivitas ke tabel audit_logs
 * Fungsi ini akan mengisi data di halaman "Staff Activities" secara otomatis.
 */
function catatLog($id_user, $role, $aktivitas, $detail) {
    global $conn;
    $id_user = mysqli_real_escape_string($conn, $id_user);
    $role = mysqli_real_escape_string($conn, $role);
    $aktivitas = mysqli_real_escape_string($conn, $aktivitas);
    $detail = mysqli_real_escape_string($conn, $detail);

    $sql = "INSERT INTO audit_logs (id_user, role, activity_type, details, created_at) 
            VALUES ('$id_user', '$role', '$aktivitas', '$detail', NOW())";
    return mysqli_query($conn, $sql);
}
?>