<?php
require_once '../config.php';

// Proteksi Akses Owner: Pastikan hanya role owner yang bisa masuk
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'owner') {
    header("location: login.php");
    exit;
}

// 1. Ambil Statistik Utama
$query_stats = mysqli_query($conn, "SELECT 
    (SELECT SUM(total_harga) FROM pesanan WHERE status_pesanan = 'Selesai') as omzet,
    (SELECT COUNT(*) FROM pesanan WHERE status_pesanan = 'Menunggu Pembayaran') as pending,
    (SELECT COUNT(*) FROM users WHERE role = 'admin') as total_admin,
    (SELECT COUNT(*) FROM produk) as total_produk");
$stats = mysqli_fetch_assoc($query_stats);

// 2. Ambil 5 Transaksi Terakhir
$recent_orders = mysqli_query($conn, "SELECT id_pesanan, nama_penerima, total_harga, status_pesanan FROM pesanan ORDER BY tanggal_pesanan DESC LIMIT 5");

// 3. Ambil Aktivitas Terbaru (Audit Logs)
$activities = mysqli_query($conn, "SELECT a.*, u.nama_lengkap FROM audit_logs a 
              JOIN users u ON a.id_user = u.id_user ORDER BY a.created_at DESC LIMIT 15");

// 4. Kelompokkan Aktivitas berdasarkan ROLE untuk tampilan ringkas
$grouped_acts = [];
while($act = mysqli_fetch_assoc($activities)) {
    $grouped_acts[$act['role']][] = $act;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard - Ce'3s Part</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Tambahan CSS untuk Accordion Ringkas */
        .role-group {
            margin-bottom: 10px;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            overflow: hidden;
        }
        summary {
            padding: 12px;
            background: rgba(255, 215, 0, 0.05);
            cursor: pointer;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
            list-style: none;
            color: var(--owner-gold);
            text-transform: uppercase;
            font-size: 0.85rem;
        }
        summary::-webkit-details-marker { display: none; }
        summary::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            transition: 0.3s;
        }
        details[open] summary::after { transform: rotate(180deg); }
        .log-content {
            padding: 10px;
            background: var(--bg-deep);
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
</head>
<body>

    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3>OWNER PANEL</h3>
            </div>
            <ul class="nav-links">
                <li><a href="dashboard.php" class="active"><i class="fa-solid fa-gauge"></i> <span>Dashboard</span></a></li>
                <li><a href="laporan.php"><i class="fa-solid fa-chart-pie"></i> <span>Laporan Keuangan</span></a></li>
                <li><a href="audit_logs.php"><i class="fa-solid fa-list-check"></i> <span>Audit Logs</span></a></li>
                <li><a href="manage_admin.php"><i class="fa-solid fa-user-shield"></i> <span>Kelola Admin</span></a></li>
                <li><a href="profile.php"><i class="fa-solid fa-user-gear"></i> <span>Pengaturan Profil</span></a></
                <li><a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span></a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="content-header">
                <div>
                    <h1>Business Overview</h1>
                    <p style="color: var(--text-gray);">Selamat datang kembali, <strong><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></strong></p>
                </div>
                <div class="time-info">
                    <i class="fa-regular fa-calendar-check" style="color: var(--owner-gold);"></i>
                    <span style="font-size: 0.9rem; margin-left: 5px;"><?= date('d M Y') ?></span>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fa-solid fa-wallet"></i>
                    <h3>Total Omzet</h3>
                    <div class="value"><?= rupiah($stats['omzet'] ?? 0) ?></div>
                </div>
                <div class="stat-card">
                    <i class="fa-solid fa-hourglass-half"></i>
                    <h3>Pesanan Pending</h3>
                    <div class="value"><?= $stats['pending'] ?></div>
                </div>
                <div class="stat-card">
                    <i class="fa-solid fa-users-gear"></i>
                    <h3>Staff Admin</h3>
                    <div class="value"><?= $stats['total_admin'] ?></div>
                </div>
                <div class="stat-card">
                    <i class="fa-solid fa-box"></i>
                    <h3>Total Produk</h3>
                    <div class="value"><?= $stats['total_produk'] ?></div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 30px;">
                
                <div class="data-container">
                    <div class="section-title">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Recent Transactions</span>
                    </div>
                    <table class="owner-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Penerima</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($recent_orders) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($recent_orders)): ?>
                                    <?php 
                                        $badge_class = 'pending';
                                        if($row['status_pesanan'] == 'Selesai') $badge_class = 'success';
                                        if($row['status_pesanan'] == 'Dibatalkan') $badge_class = 'danger';
                                    ?>
                                    <tr>
                                        <td style="font-weight: 800; color: var(--owner-gold);">#<?= $row['id_pesanan'] ?></td>
                                        <td><?= htmlspecialchars($row['nama_penerima']) ?></td>
                                        <td><?= rupiah($row['total_harga']) ?></td>
                                        <td><span class="status-badge <?= $badge_class ?>"><?= $row['status_pesanan'] ?></span></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" style="text-align:center;">Belum ada pesanan masuk.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="data-container">
                    <div class="section-title">
                        <i class="fa-solid fa-fingerprint"></i>
                        <span>Staff Activities</span>
                    </div>
                    
                    <?php if (!empty($grouped_acts)): ?>
                        <?php foreach($grouped_acts as $role => $logs): ?>
                            <details class="role-group">
                                <summary>
                                    <span><i class="fa-solid fa-user-tag"></i> Role: <?= strtoupper($role) ?></span>
                                    <small style="color: var(--text-gray); font-size: 0.7rem;"><?= count($logs) ?> Aktivitas</small>
                                </summary>
                                <div class="log-content">
                                    <?php foreach($logs as $act): ?>
                                        <div class="log-item" style="border-bottom: 1px solid rgba(255,255,255,0.05); padding: 8px 0;">
                                            <div class="log-time" style="font-size: 0.7rem; color: var(--owner-gold);">
                                                <?= date('H:i', strtotime($act['created_at'])) ?> - <?= date('d M', strtotime($act['created_at'])) ?>
                                            </div>
                                            <div class="log-desc" style="font-size: 0.8rem;">
                                                <strong><?= htmlspecialchars($act['nama_lengkap']) ?></strong>: 
                                                <span style="color: #eee;"><?= $act['activity_type'] ?></span>
                                            </div>
                                            <div style="font-size: 0.7rem; color: var(--text-gray); font-style: italic;">
                                                <?= htmlspecialchars($act['details']) ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </details>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="text-align: center; color: var(--text-gray); font-size: 0.8rem; padding: 20px;">Tidak ada aktivitas tercatat.</p>
                    <?php endif; ?>
                </div>

            </div>
        </main>
    </div>

</body>
</html>