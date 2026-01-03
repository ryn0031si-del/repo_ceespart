<?php
require_once 'config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(403);
    exit;
}

$id_customer = $_SESSION["id_user"];
$id_admin = intval($_GET['receiver_id']);
$chat_history = [];

// Perubahan: Menambahkan file_lampiran dan tipe_lampiran ke SELECT
$sql = "SELECT id_pengirim, pesan, file_lampiran, tipe_lampiran, waktu_kirim 
        FROM chat 
        WHERE (id_pengirim = ? AND id_penerima = ?) 
           OR (id_pengirim = ? AND id_penerima = ?)
        ORDER BY waktu_kirim ASC";

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "iiii", $id_customer, $id_admin, $id_admin, $id_customer);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $chat_history = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
}

header('Content-Type: application/json');
echo json_encode($chat_history);
?>