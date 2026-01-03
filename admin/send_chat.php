<?php
require_once '../config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    http_response_code(403); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pengirim = $_SESSION["id_user"];
    $id_penerima = intval($_POST['id_penerima']);
    $pesan = trim($_POST['pesan']);
    $nama_file = null;
    $tipe_file = null;

    // Logika Upload File Multimedia
    if (isset($_FILES['chat_file']) && $_FILES['chat_file']['error'] === 0) {
        $target_dir = "../assets/chat/"; 
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $ext = strtolower(pathinfo($_FILES['chat_file']['name'], PATHINFO_EXTENSION));
        $nama_file = "chat_admin_" . time() . "_" . uniqid() . "." . $ext;
        
        if (move_uploaded_file($_FILES['chat_file']['tmp_name'], $target_dir . $nama_file)) {
            $tipe_file = (in_array($ext, ['mp4', 'webm'])) ? 'video' : 'gambar';
        }
    }

    if (!empty($pesan) || $nama_file !== null) {
        $sql = "INSERT INTO chat (id_pengirim, id_penerima, pesan, file_lampiran, tipe_lampiran, waktu_kirim) VALUES (?, ?, ?, ?, ?, NOW())";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "iisss", $id_pengirim, $id_penerima, $pesan, $nama_file, $tipe_file);
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['status' => 'success']);
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>