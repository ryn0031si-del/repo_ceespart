<?php
require_once 'config.php';
// Pastikan session dimulai (jika belum ada di config.php)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION["loggedin"])) { header("location: login.php"); exit; }
$id_customer = $_SESSION["id_user"];
$id_admin = 3; // ID Admin Default

// --- LOGIKA INTERNAL: PROSES PENGIRIMAN CHAT ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proses_chat'])) {
    $id_pengirim = $_SESSION["id_user"];
    $id_penerima = intval($_POST['id_penerima']);
    $pesan = trim($_POST['pesan']);
    $nama_file = null;
    $tipe_file = null;

    // Proses Upload File jika ada
    if (isset($_FILES['chat_file']) && $_FILES['chat_file']['error'] === 0) {
        $target_dir = "assets/chat/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $ext = strtolower(pathinfo($_FILES['chat_file']['name'], PATHINFO_EXTENSION));
        $nama_file = "chat_" . time() . "_" . uniqid() . "." . $ext;
        
        if (move_uploaded_file($_FILES['chat_file']['tmp_name'], $target_dir . $nama_file)) {
            $tipe_file = (in_array($ext, ['mp4', 'webm'])) ? 'video' : 'gambar';
        }
    }

    if (!empty($pesan) || $nama_file !== null) {
        $sql = "INSERT INTO chat (id_pengirim, id_penerima, pesan, file_lampiran, tipe_lampiran, waktu_kirim) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iisss", $id_pengirim, $id_penerima, $pesan, $nama_file, $tipe_file);
        mysqli_stmt_execute($stmt);
        echo json_encode(['status' => 'success']);
    }
    exit; // Menghentikan rendering HTML saat request AJAX
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Live Chat - Ce'3s Part</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/index.css"> <style>
    /* Styling Chat Container (Inline sementara atau bisa dipindah ke style.css) */
    .chat-attachment { max-width: 250px; border-radius: 8px; margin-bottom: 8px; display: block; border: 1px solid #333; cursor: pointer; }
    .btn-attach { background: none; border: none; color: #a0c4ff; font-size: 1.2rem; cursor: pointer; padding: 0 10px; transition: 0.3s; }
    .btn-attach:hover { color: #fff; }
    #chat-file { display: none; }
    .file-preview { display: none; padding: 10px; background: #1a1d35; border-top: 1px solid #333; font-size: 0.8rem; color: #a0c4ff; }
    
    /* Tambahan agar footer tidak naik jika chat sedikit */
    body { display: flex; flex-direction: column; min-height: 100vh; }
    main { flex: 1; }
</style>
</head>
<body>

<header>
    <nav class="container">
        <a href="index.php" class="logo">Ce'3s Part</a>

        <div class="hamburger" id="hamburger">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>

        <div class="nav-menu" id="nav-menu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="produk.php">Produk</a></li>
                <li><a href="pesanan.php">Pesanan Saya</a></li>
                <li><a href="chat.php" class="active">Chat</a></li>
                <li><a href="about.php">About</a></li>
            </ul>
            <div class="auth-links">
                <a href="profil.php" class="profile-link">
                    <i class="fa-regular fa-user"></i> Halo, <?= htmlspecialchars($_SESSION["nama_lengkap"]) ?>
                </a>
                <a href="logout.php" class="btn-nav-logout">Logout</a>
            </div>
        </div>
    </nav>
</header>

<main class="container">
    <div class="page-header">
        <h2>Live Chat</h2>
        <p>Ada pertanyaan? Hubungi admin kami secara langsung.</p>
    </div>

    <div class="chat-container">
        <div class="chat-header"><i class="fa-solid fa-headset"></i> Chat dengan Admin</div>
        
        <div class="chat-messages" id="chat-messages">
            </div>

        <div id="file-info" class="file-preview"></div>

        <form id="chat-form" class="chat-input-form" enctype="multipart/form-data">
            <input type="hidden" id="receiver-id" value="<?= $id_admin ?>">

            <button type="button" class="btn-attach" onclick="document.getElementById('chat-file').click()">
                <i class="fa-solid fa-paperclip"></i>
            </button>
            <input type="file" id="chat-file" accept="image/*,video/*">

            <input type="text" id="message-input" placeholder="Ketik pesan Anda...">
            <button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
        </form>
    </div>
</main>

<footer class="footer">
    <div class="container footer-container">
        <div class="footer-column">
            <h3>Ce'3s Part</h3>
            <p>Toko spare part motor terpercaya yang menyediakan produk original & berkualitas.</p>
        </div>
        <div class="footer-column">
            <h4>Kontak Kami</h4>
            <ul>
                <li><i class="fa-solid fa-location-dot"></i> Jl. Contoh No.123, Karawang</li>
                <li><i class="fa-solid fa-phone"></i> 0812-3456-7890</li>
                <li><i class="fa-solid fa-envelope"></i> support@ce3spart.com</li>
            </ul>
        </div>
        <div class="footer-column">
            <h4>Ikuti Kami</h4>
            <div class="social-links">
                <a href="https://www.facebook.com/share/1Bu5u31u82/"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="https://www.instagram.com/gk_pnyanama007" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                <a href="https://wa.me/qr/5JO24PB5ZBI7E1" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> Ce'3s Part. All Rights Reserved.</p>
    </div>
</footer>

<script src="js/script.js"></script>

<script>
const form = document.getElementById('chat-form'),
      input = document.getElementById('message-input'),
      fileInput = document.getElementById('chat-file'),
      fileInfo = document.getElementById('file-info'),
      receiver = document.getElementById('receiver-id').value,
      box = document.getElementById('chat-messages'),
      user = <?= $id_customer ?>;

// Preview Nama File
fileInput.addEventListener('change', () => {
    if (fileInput.files.length > 0) {
        fileInfo.style.display = 'block';
        fileInfo.innerHTML = `<i class="fa-solid fa-file"></i> Terpilih: ${fileInput.files[0].name} <span style="color:red; cursor:pointer; margin-left:10px;" onclick="cancelFile()">[Batal]</span>`;
    }
});

function cancelFile() {
    fileInput.value = '';
    fileInfo.style.display = 'none';
}

async function fetchMessages(){
  try{
    const res = await fetch(`get_chat.php?receiver_id=${receiver}`),
          msgs = await res.json();
    
    // Simpan posisi scroll saat ini
    const isScrolledToBottom = box.scrollHeight - box.clientHeight <= box.scrollTop + 10;

    box.innerHTML = msgs.map(m=>{
      const cls = m.id_pengirim == user ? 'sent' : 'received',
            time = new Date(m.waktu_kirim).toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
      
      let attachment = '';
      if (m.file_lampiran) {
          if (m.tipe_lampiran === 'video') {
              attachment = `<video src="assets/chat/${m.file_lampiran}" controls class="chat-attachment"></video>`;
          } else {
              attachment = `<img src="assets/chat/${m.file_lampiran}" class="chat-attachment" onclick="window.open(this.src)">`;
          }
      }

      return `<div class="message-bubble ${cls}">
                ${attachment}
                ${m.pesan ? `<p>${m.pesan}</p>` : ''}
                <span class="timestamp">${time}</span>
              </div>`;
    }).join('');

    // Hanya auto-scroll jika user sebelumnya berada di paling bawah atau pesan baru pertama kali dimuat
    if (isScrolledToBottom || box.scrollTop === 0) {
        box.scrollTop = box.scrollHeight;
    }
    
  }catch(e){ console.error('Gagal memuat pesan:',e); }
}

form.addEventListener('submit',async e=>{
  e.preventDefault();
  const msg=input.value.trim();
  const file=fileInput.files[0];
  
  if(!msg && !file) return;

  const data=new FormData();
  data.append('proses_chat', '1');
  data.append('pesan', msg);
  data.append('id_penerima', receiver);
  if(file) data.append('chat_file', file);

  // Kirim data
  await fetch('chat.php', {method:'POST', body:data});
  
  // Reset Form
  input.value='';
  cancelFile();
  
  // Refresh Pesan Langsung
  fetchMessages();
  // Scroll paksa ke bawah setelah kirim pesan
  setTimeout(() => box.scrollTop = box.scrollHeight, 100);
});

// Load pesan pertama kali
fetchMessages(); 
// Auto refresh setiap 3 detik
setInterval(fetchMessages, 3000);
</script>
</body>
</html>