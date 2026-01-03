<?php
require_once '../config.php'; 

// 1. EMERGENCY RESET (Hanya jika diperlukan, bisa dihapus jika sudah sinkron)
$reset_password = password_hash('admin123', PASSWORD_DEFAULT);
$update_sql = "UPDATE users SET password = '$reset_password' WHERE nama_lengkap = 'Bapak Owner' AND role = 'owner'";
mysqli_query($conn, $update_sql);

if (isset($_SESSION['role']) && $_SESSION['role'] === 'owner') {
    header("Location: dashboard.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['identitas'])) {
    $identitas = mysqli_real_escape_string($conn, $_POST['identitas']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE nama_lengkap = ? AND role = 'owner' LIMIT 1";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $identitas);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['id_user']  = $row['id_user'];
                $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
                $_SESSION['role']     = $row['role'];
                header("Location: dashboard.php"); 
                exit;
            } else {
                $error = "Kata sandi tidak valid.";
            }
        } else {
            $error = "Akun Owner tidak ditemukan.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Login - Exclusive Access</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <canvas id="rainCanvas"></canvas>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="card-header">
                <div class="icon-box">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h2>Owner Access</h2>
                <p>Silakan verifikasi identitas Anda untuk melanjutkan</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error-msg">
                    <i class="fa-solid fa-circle-exclamation"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" autocomplete="off">
                <div class="input-group">
                    <label>Nama Pengguna</label>
                    <input type="text" name="identitas" placeholder="Masukkan nama lengkap" required>
                </div>
                
                <div class="input-group">
                    <label>Kata Sandi</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" placeholder="Masukkan kata sandi" required>
                        <i class="fa-solid fa-eye" id="togglePassword"></i>
                    </div>
                </div>

                <button type="submit" class="btn-verify">
                    <span>Verifikasi & Masuk</span>
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </form>
            
            <div class="card-footer">
                <small>&copy; <?= date('Y') ?> Ce'3s Part - Secure System</small>
            </div>
        </div>
    </div>

    <script src="css/script.js"></script>
</body>
</html>