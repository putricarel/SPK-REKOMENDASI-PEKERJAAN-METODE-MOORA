<?php
session_start();
require 'functions.php';

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (login($_POST) > 0) {
    $login = mysqli_query($con, "SELECT * FROM login WHERE username = '$username' AND password = '$password' ");
    $user = mysqli_fetch_assoc($login);

    if ($user['level'] == 'admin') {
      $_SESSION['status'] = "log_in";
      echo "<script>
      alert('Selamat Datang Admin');
      document.location.href='admin/index.php';
      </script>";
    } else if ($user['level'] == 'user') {
      $_SESSION['status'] = "log_in";
      echo "<script>
      alert('Selamat Datang User');
      document.location.href='konsumen/index.php';
      </script>";
    }
  } else {
    echo "<script>
    document.location.href='login.php?pesan=username/passwordsalah';
    </script>";
  }
}
?>

<!doctype html>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
      body {
        background-color: #3E2723; /* Cokelat tua */
        background-image: url('img/c5.png');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        font-family: Arial, sans-serif;
        color: #FFF;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .login-container {
        background: #3F281A; 
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        height: 370px;
        padding: 30px;
      }

      .login-container h1 {
        color: #FFD54F; 
        margin-bottom: 35px;
        text-align: center;
      }

      .form-control {
        background-color: #8D6E63; /* Cokelat muda */
        border: none;
        color: #FFF;
      }

      .form-control:focus {
        background-color: #A1887F; /* Cokelat yang lebih terang */
        border-color: #FFD54F; /* Fokus kuning keemasan */
        box-shadow: none;
      }

      .btn-custom {
        background-color: #6D4C41; /* Cokelat medium */
        border: none;
        color: white;
        width: 100%;
      }

      .btn-custom:hover {
        background-color: #5D4037; /* Cokelat lebih gelap */
        color: #FFD54F; /* Kuning keemasan */
      }

      .alert {
        background-color: #D84315; 
        border: none;
        color: white;
      }

      .footer {
        margin-top: 20px;
        color: #FFD54F;
        text-align: center;
      }
    </style>
  </head>

  <body>
    <div class="login-container col-md-3">
      <?php if (isset($_GET['pesan'])): ?>
        <?php if ($_GET['pesan'] == "username/passwordsalah"): ?>
          <div class="alert">Gagal! Username atau Password salah.</div>
        <?php elseif ($_GET['pesan'] == "logoutberhasil"): ?>
          <div class="alert alert-success">Logout berhasil.</div>
        <?php elseif ($_GET['pesan'] == "logindahulu"): ?>
          <div class="alert alert-warning">Anda harus login terlebih dahulu.</div>
        <?php endif; ?>
      <?php endif; ?>

      <h1>Sign In</h1>
      <form method="post">
        <div class="form-group">
          <input type="text" name="username" class="form-control" style="background-color: #FFF; color: #000;" placeholder="Username" required autofocus>
        </div>
        <div class="form-group">
          <input type="password" name="password" class="form-control" style="background-color: #FFF; color: #000;" placeholder="Password" required>
        </div>
        <button type="submit" name="login" class="btn btn-custom">Log In</button>
      </form>
      <div class="footer mt-3">
        &copy; Putri Carellilas Fony
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>

  </html>
