<?php
require_once '../config.php';

// 1. Keamanan Halaman Admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    header("location: login.php");
    exit;
}

if (!isset($_GET['customer_id']) || empty($_GET['customer_id'])) {
    header("location: admin_chat.php");
    exit;
}

$id_customer = intval($_GET['customer_id']);
$id_admin = $_SESSION["id_user"];

// --- 2. LOGIKA MATIKAN NOTIFIKASI ---
$sql_update_read = "UPDATE chat SET is_read = 1 
                    WHERE id_pengirim = ? AND id_penerima = ? AND is_read = 0";
if ($stmt_read = mysqli_prepare($conn, $sql_update_read)) {
    mysqli_stmt_bind_param($stmt_read, "ii", $id_customer, $id_admin);
    mysqli_stmt_execute($stmt_read);
    mysqli_stmt_close($stmt_read);
}

// 3. Ambil Nama Customer
$customer_name = "Customer";
$sql_user = "SELECT nama_lengkap FROM users WHERE id_user = ?";
if($stmt_user = mysqli_prepare($conn, $sql_user)){
    mysqli_stmt_bind_param($stmt_user, "i", $id_customer);
    mysqli_stmt_execute($stmt_user);
    $result_user = mysqli_stmt_get_result($stmt_user);
    if($user_data = mysqli_fetch_assoc($result_user)){
        $customer_name = $user_data['nama_lengkap'];
    }
    mysqli_stmt_close($stmt_user);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - <?php echo htmlspecialchars($customer_name); ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --bg-dark-deep: #05060f;
            --bg-card: #131629;
            --bg-hover: #1c2540;
            --accent-blue: #a0c4ff;
            --border-color: rgba(160, 196, 255, 0.1);
            --text-gray: #94a3b8;
            --text-light: #cbd5e1;
            --success-glow: rgba(46, 204, 113, 0.2);
        }

        body { background-color: var(--bg-dark-deep); color: var(--text-light); }

        .chat-container {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 180px);
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
            margin: 20px;
        }

        /* Header Chat */
        .chat-header-info {
            background: var(--bg-hover);
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Area Pesan */
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 25px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            background: radial-gradient(circle at center, #131629 0%, #05060f 100%);
        }

        .message-bubble {
            max-width: 75%;
            padding: 12px 16px;
            border-radius: 15px;
            font-size: 0.95rem;
            line-height: 1.5;
            position: relative;
        }

        .message-bubble.sent {
            align-self: flex-end;
            background: #344e89; /* Biru Navy sesuai tombol CSS Anda */
            color: #fff;
            border: 1px solid rgba(160, 196, 255, 0.2);
            border-bottom-right-radius: 2px;
        }

        .message-bubble.received {
            align-self: flex-start;
            background: var(--bg-hover);
            color: var(--text-light);
            border: 1px solid var(--border-color);
            border-bottom-left-radius: 2px;
        }

        .chat-attachment {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 8px;
            border: 1px solid var(--border-color);
            display: block;
        }

        .timestamp {
            display: block;
            font-size: 0.7rem;
            margin-top: 6px;
            color: var(--text-gray);
            text-align: right;
        }

        /* Input Area */
        .input-area {
            background: var(--bg-hover);
            padding: 15px 25px;
            border-top: 1px solid var(--border-color);
        }

        #file-preview-text {
            font-size: 0.8rem;
            color: #75b798; /* Hijau sesuai alert-success Anda */
            background: rgba(21, 87, 36, 0.2);
            padding: 8px 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            display: none;
            border: 1px solid rgba(21, 87, 36, 0.3);
        }

        .chat-input-form {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .file-label {
            cursor: pointer;
            color: var(--accent-blue);
            font-size: 1.4rem;
            transition: .3s;
        }

        .file-label:hover { transform: scale(1.1); color: #fff; }

        #message-input {
            flex: 1;
            background: var(--bg-dark-deep);
            border: 1px solid var(--border-color);
            color: #fff;
            padding: 12px 20px;
            border-radius: 10px;
            outline: none;
        }

        #message-input:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 10px rgba(160, 196, 255, 0.2);
        }

        .btn-send {
            background: transparent;
            border: 1px solid #344e89;
            color: var(--accent-blue);
            width: 45px;
            height: 45px;
            border-radius: 10px;
            cursor: pointer;
            transition: .3s;
        }

        .btn-send:hover {
            background: #344e89;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(52, 78, 137, 0.4);
        }

        #chat_file { display: none; }

        /* Custom Scrollbar */
        .chat-messages::-webkit-scrollbar { width: 6px; }
        .chat-messages::-webkit-scrollbar-track { background: var(--bg-dark-deep); }
        .chat-messages::-webkit-scrollbar-thumb { background: var(--bg-hover); border-radius: 10px; }
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
        <header class="main-header" style="background:var(--bg-card); border-bottom:1px solid var(--border-color);">
            <button class="menu-toggle" id="menu-toggle"><i class="fa-solid fa-bars"></i></button>
            <h1 style="color:var(--accent-blue); font-weight:800;"><?php echo htmlspecialchars($customer_name); ?></h1>
            <a href="admin_chat.php" class="back-link" style="color:var(--text-gray); text-decoration:none; font-size:0.9rem;">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </header>

        <div class="chat-container">
            <div class="chat-messages" id="chat-messages"></div>
            
            <div class="input-area">
                <div id="file-preview-text"></div>
                <form class="chat-input-form" id="chat-form">
                    <input type="hidden" id="receiver-id" value="<?php echo $id_customer; ?>">
                    
                    <label for="chat_file" class="file-label">
                        <i class="fa-solid fa-circle-plus"></i>
                    </label>
                    <input type="file" id="chat_file" name="chat_file" accept="image/*,video/*">
                    
                    <input type="text" id="message-input" placeholder="Tulis balasan..." autocomplete="off">
                    <button type="submit" class="btn-send"><i class="fa-solid fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const fileInput = document.getElementById('chat_file');
    const filePreviewText = document.getElementById('file-preview-text');
    const receiverId = document.getElementById('receiver-id').value;
    const messagesContainer = document.getElementById('chat-messages');
    const currentUserId = <?php echo $id_admin; ?>;

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            filePreviewText.style.display = 'block';
            filePreviewText.innerHTML = `<i class="fa-solid fa-file-arrow-up"></i> Lampiran siap: ${this.files[0].name}`;
        } else {
            filePreviewText.style.display = 'none';
        }
    });

    async function fetchMessages() {
        try {
            const response = await fetch(`get_chat.php?customer_id=${receiverId}`);
            const messages = await response.json();
            
            messagesContainer.innerHTML = '';
            messages.forEach(msg => {
                const bubble = document.createElement('div');
                bubble.className = `message-bubble ${msg.id_pengirim == currentUserId ? 'sent' : 'received'}`;
                
                let content = '';
                if (msg.file_lampiran) {
                    const url = `../assets/chat/${msg.file_lampiran}`;
                    content += msg.tipe_lampiran === 'gambar' 
                        ? `<img src="${url}" class="chat-attachment" onclick="window.open(this.src)">` 
                        : `<video controls class="chat-attachment"><source src="${url}" type="video/mp4"></video>`;
                }
                if (msg.pesan) content += `<p style="margin:0;">${msg.pesan}</p>`;

                const time = new Date(msg.waktu_kirim).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                bubble.innerHTML = `${content}<span class="timestamp">${time}</span>`;
                messagesContainer.appendChild(bubble);
            });
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        } catch (e) { console.error(e); }
    }

    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const text = messageInput.value.trim();
        if (!text && fileInput.files.length === 0) return;

        const formData = new FormData();
        formData.append('pesan', text);
        formData.append('id_penerima', receiverId);
        if (fileInput.files.length > 0) formData.append('chat_file', fileInput.files[0]);

        const res = await fetch('send_chat.php', { method: 'POST', body: formData });
        const result = await res.json();
        
        if (result.status === 'success') {
            messageInput.value = '';
            fileInput.value = '';
            filePreviewText.style.display = 'none';
            fetchMessages();
        }
    });

    fetchMessages();
    setInterval(fetchMessages, 3000);

    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('show');
    });
</script>
</body>
</html>