<?php
require_once '../config.php';

// Keamanan: Hanya admin yang bisa mengakses
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    http_response_code(403); exit;
}

$id_admin = $_SESSION["id_user"];
$id_customer = intval($_GET['customer_id']);
$chat_history = [];

// Query diperbarui untuk mengambil file_lampiran dan tipe_lampiran
$sql = "SELECT id_pengirim, pesan, file_lampiran, tipe_lampiran, waktu_kirim 
        FROM chat 
        WHERE (id_pengirim = ? AND id_penerima = ?) OR (id_pengirim = ? AND id_penerima = ?)
        ORDER BY waktu_kirim ASC";

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "iiii", $id_admin, $id_customer, $id_customer, $id_admin);
    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        $chat_history = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    mysqli_stmt_close($stmt);
}

header('Content-Type: application/json');
echo json_encode($chat_history);
?>