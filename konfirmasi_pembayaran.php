<?php
require_once 'config.php';
$id_pesanan = $_GET['id'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Konfirmasi Pembayaran</title>
    <link rel="stylesheet" href="css/checkout.css">
</head>
<body>
<div class="checkout-container">
    <h2>Unggah Bukti Transaksi</h2>
    <p>Pesanan ID: #<?= htmlspecialchars($id_pesanan) ?></p>
    <form action="proses_upload_bukti.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_pesanan" value="<?= htmlspecialchars($id_pesanan) ?>">
        <label>Upload Foto Struk/Bukti Transfer:</label>
        <input type="file" name="bukti_foto" accept="image/*" required>
        <button type="submit" class="order">Kirim Bukti</button>
    </form>
</div>
</body>
</html>