<?php require_once "config.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Ce'3s Part</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- RESET & BASIC --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Montserrat', sans-serif; }
        
        body {
            /* Gradasi tema Motor: Deep Dark ke Primary Blue */
            background: linear-gradient(135deg, #090a1a, #344e89);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px; /* Padding agar tidak mentok layar HP */
        }

        /* --- WRAPPER UTAMA --- */
        .wrapper {
            background: #131629;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.5);
            border: 1px solid rgba(160, 196, 255, 0.1);
            width: 850px;
            max-width: 100%;
            min-height: 500px;
            display: flex;
            overflow: hidden;
            position: relative;
        }

        /* --- BAGIAN KIRI (FORM) --- */
        .form-container {
            width: 50%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            z-index: 2;
            background: #131629; /* Pastikan background solid */
        }
        
        .form-container h2 {
            margin-bottom: 10px; 
            color: #a0c4ff; 
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }
        
        .form-container p.desc { 
            color: #cbd5e1; 
            text-align: center; 
            font-size: 0.9rem; 
            margin-bottom: 30px; 
            line-height: 1.5;
        }

        /* Input Styles */
        .form-container form { display: flex; flex-direction: column; }
        
        .form-container input {
            padding: 12px 15px;
            margin-bottom: 20px;
            background: #090a1a;
            color: #fff;
            border: 1px solid rgba(160, 196, 255, 0.2);
            border-radius: 8px;
            transition: .3s;
            font-size: 14px;
        }
        .form-container input:focus {
            border-color: #a0c4ff;
            outline: none;
            background-color: #05060f;
        }
        
        /* Button Styles */
        .form-container button.submit-btn {
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(to right, #344e89, #5c7cfa);
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: .3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .form-container button.submit-btn:hover {
            background: linear-gradient(to left, #344e89, #a0c4ff);
            color: #090a1a;
            box-shadow: 0 5px 15px rgba(52, 78, 137, 0.4);
        }

        /* Link Kembali */
        .back-link {
            text-align: center; 
            margin-top: 20px;
        }
        .back-link a {
            color: #a0c4ff; 
            text-decoration: none; 
            font-size: 0.85rem; 
            font-weight: 600;
            transition: .3s;
        }
        .back-link a:hover { color: #fff; text-decoration: underline; }

        /* --- BAGIAN KANAN (GAMBAR/ILLUSTRATION) --- */
        .image-container {
            width: 50%;
            position: relative;
            background: linear-gradient(to bottom right, #344e89, #090a1a);
            overflow: hidden;
            /* Membuat lengkungan unik di sisi kiri gambar (Desktop) */
            clip-path: ellipse(120% 100% at 90% 50%);
        }
        
        /* Logic: Jika di desktop, kita buat gambar full height */
        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.6;
            mix-blend-mode: overlay;
        }

        /* --- ALERT MESSAGES --- */
        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 0.85rem;
            text-align: center;
            font-weight: 600;
        }
        .alert-error { 
            background: rgba(220, 53, 69, 0.2); 
            color: #ff6b6b; 
            border: 1px solid rgba(220, 53, 69, 0.3); 
        }
        .alert-success { 
            background: rgba(40, 167, 69, 0.2); 
            color: #5ddc7e; 
            border: 1px solid rgba(40, 167, 69, 0.3); 
        }

        /* =========================================
           MODE MOBILE (RESPONSIVE FIX)
           ========================================= */
        @media (max-width: 768px) {
            .wrapper {
                flex-direction: column-reverse; /* Form di bawah, Gambar di atas */
                height: auto;
                min-height: auto;
            }

            /* Gambar jadi header kecil di atas */
            .image-container {
                width: 100%;
                height: 150px; /* Tinggi fix untuk header */
                clip-path: none; /* Hilangkan lengkungan samping */
                border-radius: 20px 20px 0 0; /* Lengkung atas wrapper */
            }

            .form-container {
                width: 100%;
                padding: 30px;
                border-radius: 0 0 20px 20px;
            }

            .form-container h2 { font-size: 1.5rem; }
        }
    </style>
</head>
<body>

<div class="wrapper">
    
    <div class="form-container">
        <h2>Lupa Password?</h2>
        <p class="desc">
            Jangan khawatir. Masukkan email yang terdaftar dan kami akan mengirimkan link untuk mereset password Anda.
        </p>

        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>

        <form action="forgot_process.php" method="POST">
            <input type="email" name="email" placeholder="Masukkan Email Anda..." required>
            <button type="submit" class="submit-btn">Kirim Link Reset</button>
        </form>
        
        <div class="back-link">
            <a href="login.php">&larr; Kembali ke Login</a>
        </div>
    </div>

    <div class="image-container">
        <img src="images/pass.jpg" alt="Illustration">
    </div>

</div>

</body>
</html>