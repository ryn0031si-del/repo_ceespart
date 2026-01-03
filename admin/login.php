<?php 
// Mulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config.php';

// Jika sudah login, arahkan ke dashboard sesuai role
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if ($_SESSION["role"] === 'admin') {
        header("location: index.php");
        exit;
    } elseif ($_SESSION["role"] === 'operator') {
        header("location: ../operator/index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panel Login - Ce'3s Part</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="wrapper" id="formWrapper">

  <!-- FORM LOGIN ADMIN -->
  <div class="form-container login admin">
    <h2>Login Admin</h2>
    <p>Masuk ke panel administrator Ce'3s Part</p>

    <?php if(isset($_GET['error'])): ?>
      <div class="message error"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <form action="login_process.php" method="POST">
      <input type="hidden" name="role" value="admin">
      <input type="email" name="email" placeholder="Masukkan email admin..." required />
      <input type="password" name="password" placeholder="Masukkan password..." required />
      <button type="submit" class="submit-btn">Masuk</button>
    </form>
  </div>

  <!-- FORM LOGIN OPERATOR -->
  <div class="form-container login operator">
    <h2>Login Operator</h2>
    <p>Masuk ke panel operator untuk mengelola data.</p>

    <form action="login_process.php" method="POST">
      <input type="hidden" name="role" value="operator">
      <input type="email" name="email" placeholder="Masukkan email operator..." required />
      <input type="password" name="password" placeholder="Masukkan password..." required />
      <button type="submit" class="submit-btn">Masuk</button>
    </form>
  </div>

  <!-- PANEL TOGGLE -->
  <div class="toggle-container" id="togglePanel">
    <h2 id="toggleTitle">Login sebagai Operator?</h2>
    <p id="toggleText">Klik tombol di bawah untuk masuk sebagai Operator.</p>
    <button class="switch-btn" id="toggleBtn">Login Operator</button>
  </div>

</div>

<script>
  const wrapper = document.getElementById('formWrapper');
  const toggleBtn = document.getElementById('toggleBtn');
  const toggleTitle = document.getElementById('toggleTitle');
  const toggleText = document.getElementById('toggleText');

  toggleBtn.addEventListener('click', () => {
    wrapper.classList.toggle('active');

    if (wrapper.classList.contains('active')) {
      toggleTitle.textContent = "Login sebagai Admin?";
      toggleText.textContent = "Klik tombol di bawah untuk masuk sebagai Admin.";
      toggleBtn.textContent = "Login Admin";
    } else {
      toggleTitle.textContent = "Login sebagai Operator?";
      toggleText.textContent = "Klik tombol di bawah untuk masuk sebagai Operator.";
      toggleBtn.textContent = "Login Operator";
    }
  });
</script>

</body>
</html>
