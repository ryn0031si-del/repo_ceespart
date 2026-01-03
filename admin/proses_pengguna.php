<?php
require_once '../config.php';

// Security Check: Ensure user is a logged-in admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    header("location: login.php");
    exit;
}

$action = $_GET['action'] ?? '';
$id_user = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($action === 'delete' && $id_user > 0) {
    // Prepare the DELETE statement to prevent SQL injection
    $sql = "DELETE FROM users WHERE id_user = ? AND role = 'customer'";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id_user);
        
        // Execute the statement and redirect based on the result
        if (mysqli_stmt_execute($stmt)) {
            // Check if any row was actually deleted
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                header("location: manage_users.php?status=sukses");
            } else {
                // This happens if the user ID doesn't exist or is an admin
                header("location: manage_users.php?status=gagal");
            }
        } else {
            header("location: manage_users.php?status=gagal");
        }
        mysqli_stmt_close($stmt);
    }
} else {
    // Redirect if the action or ID is invalid
    header("location: manage_users.php");
}

mysqli_close($conn);
?>