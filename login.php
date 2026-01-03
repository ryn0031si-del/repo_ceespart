<?php
// Memulai session untuk halaman ini
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login & Daftar Akun</title>
  <link rel="stylesheet" href="css/login.css">
  
  <style>
    /* Tambahan style agar pesan error/sukses terlihat rapi (jika belum ada di css) */
    .message {
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 5px;
      text-align: center;
      font-weight: bold;
      font-size: 0.85rem; 
      width: 100%;
    }
    .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    
    a.forgot-password {
      color: #a0c4ff;
      margin-top: 8px;
      display: block;
      font-size: .9rem;
      cursor: pointer;
      text-decoration: underline;
      text-align: right;
    }
  </style>
</head>

<body>

<div class="wrapper" id="formWrapper">

  <div class="form-container login">
    <h2>Masuk</h2>

    <?php if(isset($_GET['error'])): ?>
      <div class="message error"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <?php if(isset($_GET['success'])): ?>
      <div class="message success"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>

    <form action="login_process.php" method="POST">
      <input type="email" name="email" placeholder="Masukkan Email..." required />
      <input type="password" name="password" placeholder="Masukkan Password..." required />

      <a href="forgot_password.php" class="forgot-password">Lupa kata sandi?</a>

      <button type="submit" class="submit-btn">Masuk</button>
      
      <div class="social-login"></div>
    </form>
  </div>

  <div class="form-container signin">
    <h2>Daftar Akun</h2>
    
    <form action="register_process.php" method="POST">
      <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required />
      <input type="email" name="email" placeholder="Email" required />
      
      <input 
        type="tel" 
        name="no_hp" 
        placeholder="Nomor HP (08xxx)" 
        required 
        maxlength="13"
        inputmode="numeric"
        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
      />

      <input type="text" name="alamat" placeholder="Alamat" required />
      <input type="password" name="password" placeholder="Password" required />

      <label class="accept-terms">
        <input type="checkbox" required /> Saya menyetujui syarat & ketentuan
      </label>

      <button type="submit" class="submit-btn">Daftar</button>
      <div class="social-login"></div>
    </form>
  </div>

  <div class="toggle-container">
    <div class="toggle">
        <h2 id="toggleHeading">Halo, Kawan!</h2>
        <p id="toggleText">Belum punya akun? Daftarkan diri Anda untuk memulai perjalanan bersama kami.</p>
        <button class="switch-btn" id="toggleBtn">Daftar</button>
    </div>
  </div>

</div>

<script>
  const wrapper = document.getElementById('formWrapper');
  const toggleBtn = document.getElementById('toggleBtn');
  const toggleHeading = document.getElementById('toggleHeading');
  const toggleText = document.getElementById('toggleText');

  toggleBtn.addEventListener('click', () => {
    wrapper.classList.toggle('active');

    if (wrapper.classList.contains('active')) {
      // Jika mode ACTIVE (sedang menampilkan form Daftar), panel menawarkan Masuk
      toggleHeading.textContent = "Sudah punya akun?";
      toggleText.textContent = "Masuk kembali untuk melanjutkan aktivitas Anda!";
      toggleBtn.textContent = "Masuk";
    } else {
      // Jika mode BIASA (sedang menampilkan form Masuk), panel menawarkan Daftar
      toggleHeading.textContent = "Halo, Kawan!";
      toggleText.textContent = "Belum punya akun? Daftarkan diri Anda sekarang.";
      toggleBtn.textContent = "Daftar";
    }
  });

  // Jika ada pesan sukses (misal habis register), otomatis tetap di login (tidak perlu class active)
  <?php if(isset($_GET['success'])): ?>
    wrapper.classList.remove('active'); 
  <?php endif; ?>
</script>

</body>
</html>