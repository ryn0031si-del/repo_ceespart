<?php
// 1. Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config.php';

// 2. Perbaikan Session: Cek agar tidak terjadi "Session already active"
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. Security Check
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'admin') {
    header("location: login.php");
    exit;
}

$action = $_REQUEST['action'] ?? '';

// --- Fungsi upload gambar ---
function uploadGambar($file) {
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) return null;
    if ($file['error'] !== UPLOAD_ERR_OK) return false;

    $ekstensiValid = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ekstensiGambar = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($ekstensiGambar, $ekstensiValid)) return false;

    $namaFileBaru = uniqid() . '.' . $ekstensiGambar;
    $tujuan = '../assets/images/' . $namaFileBaru;

    if (move_uploaded_file($file['tmp_name'], $tujuan)) {
        return $namaFileBaru;
    }
    return false;
}

// 4. Logika Berdasarkan Aksi
switch ($action) {
    case 'tambah':
        $nama = $_POST['nama_produk'];
        $deskripsi = $_POST['deskripsi'];
        $harga = $_POST['harga'];
        $stok = $_POST['stok'];
        $gambar = uploadGambar($_FILES['gambar_produk']);

        if ($gambar === false) {
            header("location: manage_produk.php?status=gagal_upload");
            exit;
        }

        $sql = "INSERT INTO produk (nama_produk, deskripsi, harga, stok, gambar_produk) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssdis", $nama, $deskripsi, $harga, $stok, $gambar);
        
        // Eksekusi & Catat Log
        if (mysqli_stmt_execute($stmt)) {
            catatLog($_SESSION['id_user'], $_SESSION['role'], 'Tambah Produk', 'Menambahkan produk baru: ' . $nama);
            header("location: manage_produk.php?status=sukses");
        } else {
            header("location: manage_produk.php?status=gagal");
        }
        break;

    case 'edit':
        $id = $_POST['id_produk'];
        $nama = $_POST['nama_produk'];
        $deskripsi = $_POST['deskripsi'];
        $harga = $_POST['harga'];
        $stok = $_POST['stok'];
        $gambar_lama = $_POST['gambar_lama'];
        
        $gambar_baru = uploadGambar($_FILES['gambar_produk']);
        if ($gambar_baru === false) {
            header("location: manage_produk.php?status=gagal_upload");
            exit;
        }

        $gambar_final = $gambar_baru ?? $gambar_lama;

        $sql = "UPDATE produk SET nama_produk = ?, deskripsi = ?, harga = ?, stok = ?, gambar_produk = ? WHERE id_produk = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssdisi", $nama, $deskripsi, $harga, $stok, $gambar_final, $id);

        if (mysqli_stmt_execute($stmt)) {
            // Hapus gambar lama jika ganti gambar baru
            if ($gambar_baru && !empty($gambar_lama) && file_exists('../assets/images/' . $gambar_lama)) {
                unlink('../assets/images/' . $gambar_lama);
            }
            catatLog($_SESSION['id_user'], $_SESSION['role'], 'Edit Produk', 'Mengubah data produk: ' . $nama);
            header("location: manage_produk.php?status=sukses");
        } else {
            header("location: manage_produk.php?status=gagal");
        }
        break;

    case 'delete':
        $id = $_GET['id'];
        
        // Ambil info produk sebelum dihapus untuk detail log
        $res = mysqli_query($conn, "SELECT nama_produk, gambar_produk FROM produk WHERE id_produk = $id");
        $data_produk = mysqli_fetch_assoc($res);

        $sql = "DELETE FROM produk WHERE id_produk = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            // Hapus file fisik gambar
            if (!empty($data_produk['gambar_produk']) && file_exists('../assets/images/' . $data_produk['gambar_produk'])) {
                unlink('../assets/images/' . $data_produk['gambar_produk']);
            }
            catatLog($_SESSION['id_user'], $_SESSION['role'], 'Hapus Produk', 'Menghapus produk: ' . $data_produk['nama_produk']);
            header("location: manage_produk.php?status=sukses");
        } else {
            header("location: manage_produk.php?status=gagal");
        }
        break;

    default:
        header("location: manage_produk.php");
}

if (isset($stmt)) mysqli_stmt_close($stmt);
mysqli_close($conn);
?>