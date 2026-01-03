<?php
require_once 'config.php';

// Pastikan user login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(403);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pengirim = $_SESSION["id_user"];
    $id_penerima = intval($_POST['id_penerima']);
    $pesan = trim($_POST['pesan']);

    if (!empty($pesan) && $id_penerima > 0) {
        $sql = "INSERT INTO chat (id_pengirim, id_penerima, pesan, waktu_kirim) VALUES (?, ?, ?, NOW())";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "iis", $id_pengirim, $id_penerima, $pesan);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo json_encode(['status' => 'success']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Pesan kosong']);
    }
}
?>
