<?php
require_once '../config.php';

// Keamanan Halaman Admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    header("location: login.php");
    exit;
}

$id_admin = $_SESSION["id_user"];
$conversations = [];

// --- QUERY DIPERBARUI: Mengambil pesan terakhir DAN jumlah pesan belum dibaca ---
// Bagian Query SQL di admin_chat.php
$sql = "SELECT 
            u.id_user, 
            u.nama_lengkap, 
            c_last.pesan, 
            c_last.waktu_kirim,
            (SELECT COUNT(*) FROM chat 
             WHERE id_pengirim = u.id_user 
             AND id_penerima = $id_admin 
             AND is_read = 0) AS unread_count
        FROM users u
        INNER JOIN (
            SELECT customer_id, MAX(id_chat) AS max_id_chat
            FROM (
                SELECT 
                    CASE 
                        WHEN id_pengirim IN (SELECT id_user FROM users WHERE role = 'customer') THEN id_pengirim
                        ELSE id_penerima
                    END AS customer_id,
                    id_chat
                FROM chat
                WHERE (id_pengirim IN (SELECT id_user FROM users WHERE role = 'customer') AND id_penerima IN (SELECT id_user FROM users WHERE role = 'admin'))
                   OR (id_pengirim IN (SELECT id_user FROM users WHERE role = 'admin') AND id_penerima IN (SELECT id_user FROM users WHERE role = 'customer'))
            ) AS chats_with_roles
            GROUP BY customer_id
        ) AS last_per_customer ON u.id_user = last_per_customer.customer_id
        JOIN chat c_last ON c_last.id_chat = last_per_customer.max_id_chat
        WHERE u.role = 'customer'
        ORDER BY c_last.waktu_kirim DESC";

$result = mysqli_query($conn, $sql);
if ($result) {
    $conversations = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Chat - Admin Panel</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* CSS Tambahan untuk Badge Unread */
        .convo-item {
            display: flex;
            align-items: center;
            padding: 15px;
            text-decoration: none;
            color: inherit;
            border-bottom: 1px solid #eee;
            transition: background 0.3s;
        }
        .convo-item:hover { background: #f9f9f9; }
        .convo-details { flex: 1; margin-left: 15px; }
        .convo-name { font-weight: bold; display: block; }
        .convo-last-message { font-size: 0.9em; color: #666; }
        
        .convo-meta {
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 5px;
        }
        .convo-time { font-size: 0.8em; color: #999; }
        .unread-badge {
            background-color: #e74c3c;
            color: white;
            font-size: 0.75em;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 12px;
            min-width: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <aside class="sidebar">
        <div class="sidebar-header"><h3>Admin Panel</h3></div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="index.php"><i class="fa-solid fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="manage_produk.php"><i class="fa-solid fa-box"></i> Kelola Produk</a></li>
                <li><a href="manage_pesanan.php"><i class="fa-solid fa-receipt"></i> Kelola Pesanan</a></li>
                <li><a href="manage_users.php"><i class="fa-solid fa-users"></i> Kelola Pengguna</a></li>
                <li><a href="admin_chat.php" class="active"><i class="fa-solid fa-comments"></i> Chat</a></li>
                <li><a href="../index.php" target="_blank"><i class="fa-solid fa-globe"></i> Lihat Website</a></li>
                <li><a href="../logout.php"><i class="fa-solid fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <header class="main-header">
            <button class="menu-toggle" id="menu-toggle"><i class="fa-solid fa-bars"></i></button>
            <h1>Kotak Masuk Chat</h1>
        </header>

        <div class="content-container">
            <div class="conversation-list">
                <?php if (empty($conversations)): ?>
                    <p class="empty-message">Tidak ada percakapan yang tersedia.</p>
                <?php else: ?>
                    <?php foreach ($conversations as $convo): ?>
                    <a href="chat_box.php?customer_id=<?php echo $convo['id_user']; ?>" class="convo-item">
                        <div class="convo-avatar">
                            <i class="fa-solid fa-user-circle" style="font-size: 40px; color: #ccc;"></i>
                        </div>
                        <div class="convo-details">
                            <span class="convo-name"><?php echo htmlspecialchars($convo['nama_lengkap']); ?></span>
                            <span class="convo-last-message">
                                <?php echo htmlspecialchars(substr($convo['pesan'], 0, 50)) . (strlen($convo['pesan']) > 50 ? '...' : ''); ?>
                            </span>
                        </div>
                        <div class="convo-meta">
                            <span class="convo-time"><?php echo date("H:i", strtotime($convo['waktu_kirim'])); ?></span>
                            <?php if ($convo['unread_count'] > 0): ?>
                                <span class="unread-badge"><?php echo $convo['unread_count']; ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<script>
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('show');
    });
</script>
</body>
</html>
