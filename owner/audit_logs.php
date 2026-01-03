<?php
require_once '../config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'owner') {
    header("location: login.php");
    exit;
}

// Ambil data log aktivitas
$query_logs = mysqli_query($conn, "SELECT a.*, u.nama_lengkap 
                                   FROM audit_logs a 
                                   JOIN users u ON a.id_user = u.id_user 
                                   ORDER BY a.created_at DESC");

// Mengelompokkan log berdasarkan role dalam PHP
$grouped_logs = [];
while ($row = mysqli_fetch_assoc($query_logs)) {
    $grouped_logs[$row['role']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Audit Logs - Ce'3s Part</title>
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
                <li><a href="audit_logs.php" class="active"><i class="fa-solid fa-list-check"></i> <span>Audit Logs</span></a></li>
                <li><a href="manage_admin.php"><i class="fa-solid fa-user-shield"></i> <span>Kelola Admin</span></a></li>
                <li><a href="profile.php"><i class="fa-solid fa-user-gear"></i> <span>Pengaturan Profil</span></a></
                <li><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span></a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="content-header">
                <h1>System Audit Logs</h1>
                <p style="color: var(--text-gray);">Pantau aktivitas staff Admin dan Operator secara berkelompok</p>
            </div>

            <div class="data-container">
                <table class="owner-table">
                    <thead>
                        <tr>
                            <th>Grup Role / Nama Staff</th>
                            <th style="text-align: right;">Jumlah Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($grouped_logs)): ?>
                            <?php foreach ($grouped_logs as $role => $logs): ?>
                                <tr class="role-group-header" onclick="toggleDetails('<?= $role ?>', this)">
                                    <td class="role-title">
                                        <i class="fa-solid fa-chevron-down chevron-icon"></i>
                                        Role: <?= strtoupper($role) ?>
                                    </td>
                                    <td style="text-align: right; font-weight: bold; color: var(--owner-gold);">
                                        <?= count($logs) ?> Aktivitas
                                    </td>
                                </tr>

                                <tr class="log-details-row" id="details-<?= $role ?>">
                                    <td colspan="2">
                                        <div class="activity-list-container">
                                            <table class="inner-log-table">
                                                <thead style="border-bottom: 1px solid var(--owner-gold);">
                                                    <tr style="color: var(--text-gray); font-size: 0.8rem;">
                                                        <td width="20%">WAKTU</td>
                                                        <td width="25%">STAFF</td>
                                                        <td>AKTIVITAS & DETAIL</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($logs as $log): ?>
                                                        <tr>
                                                            <td class="log-timestamp" style="font-size: 0.8rem;">
                                                                <?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?>
                                                            </td>
                                                            <td style="color: #fff; font-weight: bold;">
                                                                <?= htmlspecialchars($log['nama_lengkap']) ?>
                                                            </td>
                                                            <td>
                                                                <span class="activity-type"><?= htmlspecialchars($log['activity_type']) ?></span><br>
                                                                <small class="activity-detail"><?= htmlspecialchars($log['details']) ?></small>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="2" style="text-align: center; padding: 50px;">Belum ada log aktivitas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        function toggleDetails(role, element) {
            // Toggle baris detail
            const detailRow = document.getElementById('details-' + role);
            detailRow.classList.toggle('active');

            // Rotate Icon
            const icon = element.querySelector('.chevron-icon');
            icon.classList.toggle('rotate');
        }
    </script>

</body>
</html>