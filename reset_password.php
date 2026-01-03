<?php
// PERBAIKAN 1: Path disesuaikan (tanpa ../)
require_once "config.php";

if (!isset($_GET['token'])) {
    die("Error: Token tidak ditemukan!");
}

$token = $_GET['token'];

// PERBAIKAN 2: Menggunakan Prepared Statement (Lebih Aman)
$stmt = $conn->prepare("SELECT * FROM users WHERE reset_token=? LIMIT 1");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: Token reset tidak valid atau sudah kedaluwarsa!");
}

// Ambil data user (opsional, jika ingin menampilkan nama user)
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Ce'3s Part</title>
    <style>
        /* --- CSS UTAMA (Dark Mode Adjusted) --- */
        *{box-sizing:border-box;margin:0;padding:0;font-family:"Segoe UI",sans-serif}
        
        body{
          /* Background Gradasi: Deep Dark ke Primary Blue */
          background:linear-gradient(135deg,#090a1a,#344e89);
          height:100vh;display:flex;align-items:center;justify-content:center;
          backdrop-filter:blur(8px)
        }

        .wrapper{
          position:relative;
          /* Background Card: Navy Gelap */
          background:#131629;
          border-radius:20px;
          /* Shadow sedikit lebih gelap & border halus */
          box-shadow:0 15px 40px rgba(0,0,0,.5);
          border: 1px solid rgba(160, 196, 255, 0.1);
          width:800px;
          max-width:100%;
          /* Menetapkan tinggi tetap agar konsisten */
          height: 500px; 
          display:flex;
          overflow:hidden;
        }

        /* --- Bagian Kiri (Form) --- */
        .form-container{
            width:50%;
            padding:40px;
            z-index:1; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; /* Konten selalu di tengah vertikal */
        }
        
        /* Judul Highlight */
        .form-container h2{margin-bottom:10px; color:#a0c4ff; text-align: center;}
        
        /* Teks Deskripsi Abu Terang */
        .form-container p {text-align:center; color:#cbd5e1; font-size: 0.9rem; margin-bottom: 25px;}
        
        .form-container form{display:flex;flex-direction:column}
        
        .form-container input{
          padding:12px 15px;margin:10px 0;
          /* Input Field Gelap */
          background: #090a1a;
          color: #fff;
          border:1px solid rgba(160, 196, 255, 0.2);
          border-radius:10px;
          transition:.3s
        }
        .form-container input:focus{
            /* Border Fokus Highlight */
            border:2px solid #a0c4ff;
            outline:none
        }
        
        .form-container button.submit-btn{
          margin-top:20px;padding:12px;border:none;border-radius:10px;
          /* Tombol Gradasi: Primary ke Gelap */
          background:linear-gradient(to right,#344e89,#253560);
          color:#fff;font-weight:bold;cursor:pointer;transition:.3s;
          font-size: 1rem;
        }
        .form-container button.submit-btn:hover{
          /* Hover Effect: Gradasi ke Highlight */
          background:linear-gradient(to left,#344e89,#a0c4ff);
          color: #090a1a;
        }

        /* --- Bagian Kanan (Gambar Part) --- */
        .toggle-container{
          width:50%;
          height:100%;
          /* Gradasi Panel Kanan */
          background:linear-gradient(to bottom right,#344e89,#090a1a);
          color:#fff;
          position:absolute;
          top:0;
          left: 50%; /* Posisi Kanan */
          border-radius: 0 20px 20px 0;
          /* Lengkungan (Curve) estetik */
          clip-path: ellipse(100% 120% at right center);
          z-index:2;
          overflow: hidden;
          padding: 0;
          display: flex;
          align-items: center;
          justify-content: center;
          border-left: 1px solid rgba(160, 196, 255, 0.1);
        }
        
        /* Gambar Ilustrasi */
        .toggle-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            mix-blend-mode: overlay; 
            opacity: 0.7; 
        }

        /* --- Responsive Mobile --- */
        @media(max-width:768px){
          .wrapper{flex-direction:column; height: auto;}
          .form-container{width:100%; padding: 30px;}
          .toggle-container{
            position:relative; width:100%; height: 200px;
            clip-path:none; left: 0; border-radius: 0 0 20px 20px;
            border-left: none; border-top: 1px solid rgba(160, 196, 255, 0.1);
          }
        }
    </style>
</head>
<body>

<div class="wrapper">
    
    <div class="form-container">
        <h2>Reset Password</h2>
        <p>Silakan buat password baru yang aman untuk akun Anda.</p>

        <form action="reset_process.php" method="POST">
            
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <input type="password" name="password" placeholder="Password Baru" required minlength="6">
            <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required minlength="6">

            <button type="submit" name="reset_password_btn" class="submit-btn">Ubah Password</button>
        </form>
    </div>

    <div class="toggle-container">
        <img src="images/pass.jpg" alt="Sparepart Illustration">
    </div>

</div>

</body>
</html>