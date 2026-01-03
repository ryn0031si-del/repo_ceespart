<?php
require_once '../config.php';

// Proteksi Akses Owner
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'owner') {
    header("location: login.php");
    exit;
}

// --- LOGIKA PROSES EDIT STAFF & PASSWORD ---
if (isset($_POST['update_staff'])) {
    $id = $_POST['id_user'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = $_POST['role'];
    $password_baru = $_POST['password_baru'];

    // Update data dasar
    $sql_update = "UPDATE users SET nama_lengkap='$nama', email='$email', role='$role' WHERE id_user='$id'";
    
    if (mysqli_query($conn, $sql_update)) {
        $detail_log = "Mengubah data staff: $nama ($role)";

        // Jika password baru diisi, maka update passwordnya
        if (!empty($password_baru)) {
            $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE users SET password='$hashed_password' WHERE id_user='$id'");
            $detail_log .= " serta memperbarui password.";
        }

        catatLog($_SESSION['id_user'], 'owner', 'Edit Staff', $detail_log);
        header("location: manage_admin.php?status=updated");
        exit;
    }
}

// Ambil data staff
$query_users = mysqli_query($conn, "SELECT id_user, nama_lengkap, email, role FROM users WHERE role IN ('admin', 'operator') ORDER BY role ASC");

$staff_by_role = ['admin' => [], 'operator' => []];
while($user = mysqli_fetch_assoc($query_users)) {
    $staff_by_role[$user['role']][] = $user;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Staff - Ce'3s Part</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header"><h3>OWNER PANEL</h3></div>
            <ul class="nav-links">
                <li><a href="dashboard.php"><i class="fa-solid fa-gauge"></i> <span>Dashboard</span></a></li>
                <li><a href="laporan.php"><i class="fa-solid fa-chart-pie"></i> <span>Laporan Keuangan</span></a></li>
                <li><a href="audit_logs.php"><i class="fa-solid fa-list-check"></i> <span>Audit Logs</span></a></li>
                <li><a href="manage_admin.php" class="active"><i class="fa-solid fa-user-shield"></i> <span>Kelola Admin</span></a></li>
                <li><a href="profile.php"><i class="fa-solid fa-user-gear"></i> <span>Pengaturan Profil</span></a></
                <li><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span></a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="user-controls">
                <div>
                    <h1>Staff Management</h1>
                    <p style="color: var(--text-gray);">Manajemen akun dan password staff</p>
                </div>
                <a href="add_user.php" class="btn-add-user"><i class="fa-solid fa-user-plus"></i> Tambah Staff</a>
            </div>

            <?php foreach ($staff_by_role as $role_name => $users): ?>
                <div class="section-title" style="margin: 20px 0;">
                    <i class="fa-solid <?= $role_name == 'admin' ? 'fa-user-shield' : 'fa-headset' ?>"></i> 
                    <span>Kategori: <?= strtoupper($role_name) ?></span>
                </div>

                <div class="user-card-grid">
                    <?php if(!empty($users)): foreach ($users as $user): ?>
                        <div class="user-card">
                            <div class="user-info">
                                <div class="user-avatar"><i class="fa-solid fa-user-tie"></i></div>
                                <div class="user-details">
                                    <h4><?= htmlspecialchars($user['nama_lengkap']) ?></h4>
                                    <p><?= htmlspecialchars($user['email']) ?></p>
                                </div>
                            </div>
                            <div class="user-actions">
                                <button class="btn-edit-user" 
                                        onclick="openEditModal('<?= $user['id_user'] ?>', '<?= $user['nama_lengkap'] ?>', '<?= $user['email'] ?>', '<?= $user['role'] ?>')">
                                    <i class="fa-solid fa-user-pen"></i> Edit Akun & Password
                                </button>
                                <a href="delete_user_process.php?id=<?= $user['id_user'] ?>" class="btn-delete-user" onclick="return confirm('Hapus staff ini?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; else: echo "<p style='color:gray; padding-left:10px;'>Kosong.</p>"; endif; ?>
                </div>
            <?php endforeach; ?>
        </main>
    </div>

    <div id="editModal" class="modal-edit">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Profil Staff</h3>
                <span class="close-modal" onclick="closeModal()">&times;</span>
            </div>
            <form method="POST">
                <input type="hidden" name="id_user" id="edit_id">
                
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="edit_nama" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="edit_email" required>
                </div>

                <div class="form-group">
                    <label>Role Hak Akses</label>
                    <select name="role" id="edit_role">
                        <option value="admin">Admin</option>
                        <option value="operator">Operator</option>
                    </select>
                </div>

                <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 20px 0;">

                <div class="form-group">
                    <label style="color: #f1c40f;">Password Baru (Opsional)</label>
                    <input type="password" name="password_baru" placeholder="Isi hanya jika ingin ganti password">
                    <span class="help-text">*Kosongkan jika tidak ingin mengubah password staff.</span>
                </div>

                <button type="submit" name="update_staff" class="btn-add-user" style="width: 100%; justify-content: center; margin-top: 10px;">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, nama, email, role) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('editModal')) {
                closeModal();
            }
        }
    </script>
</body>
</html>