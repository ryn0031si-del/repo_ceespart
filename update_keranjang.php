<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $delta = (int)$_POST['delta'];

    if (isset($_SESSION['keranjang'][$id])) {
        $_SESSION['keranjang'][$id]['jumlah'] += $delta;
        $new_qty = $_SESSION['keranjang'][$id]['jumlah'];

        if ($new_qty <= 0) {
            unset($_SESSION['keranjang'][$id]);
        }

        $total = 0;
        foreach ($_SESSION['keranjang'] as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }

        echo json_encode([
            'status' => 'success',
            'new_qty' => $new_qty,
            'total_raw' => $total,
            'total_formatted' => number_format($total, 0, ',', '.')
        ]);
    }
}
