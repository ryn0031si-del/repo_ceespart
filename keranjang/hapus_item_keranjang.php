<?php
require_once 'config.php';

if (!empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    if (isset($_SESSION['keranjang'][$id])) unset($_SESSION['keranjang'][$id]);
}

header('Location: keranjang/keranjang.php');
exit;
?>
