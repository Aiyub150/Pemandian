<?php
require '../app/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama = $_POST['nama'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];
  $no_telepon = $_POST['no_telepon'];

  $secret_key = "6LcUfDsoAAAAAOuwYSk9i_ZoQwCgIexCeVMJ31Vb";
  // Membuat prepared statement
  $verify = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['token']);
  $response = json_decode($verify);
  if($response == true){
      // Pengecekan apakah username sudah digunakan sebelumnya
      $check_username_query = "SELECT * FROM users WHERE username=?";
      $check_username_stmt = $conn->prepare($check_username_query);
      $check_username_stmt->bind_param("s", $username);
      $check_username_stmt->execute();
      $check_username_result = $check_username_stmt->get_result();

      if ($check_username_result->num_rows > 0) {
        echo "<div id='error-message'>Username ini sudah pernah dipakai.</div>";
      }else {
          // Jika username belum pernah digunakan, lakukan proses insert
          $stmt = $conn->prepare("INSERT INTO users (nama, username, password, email, no_telepon) VALUES (?, ?, ?, ?, ?)");
          $stmt->bind_param("sssss",  $nama, $username, $password, $email, $no_telepon);

          if ($stmt->execute()) {
              header("location: login.php");
          } else {
              echo "Error: " . $stmt->error;
          }
      }

      // Tutup statement setelah digunakan
      $check_username_stmt->close();
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Pemandian</title>
    <link rel="stylesheet" href="../../public/css/auth.css">
    <link rel="stylesheet" href="../../public/css/waves2.css">
    <link rel="icon" type="image/x-icon" href="../../public/img/icon.png" />
</head>

<body>
<!-- https://dribbble.com/shots/15392711-Dashboard-Login-Sign-Up/-->

<div class="login-container">
  <div class="login-form">
    <div class="login-form-inner">
      <div class="logo">
          <img src="../../public/img/logo_pemandian_transparant.png" alt="" style="width: 200px; height: 80px; margin-left: 70px;">
        </div>
      <h1>Daftar</h1>
      <p class="body-text">Silahkan daftar akun anda terlebih dahulu.</p>

      <form method="post" action="" id="form" style="margin-top: 20px;">
      <div class="login-form-group">
            <label for="email">Nama<span class="required-star">*</span></label>
            <input type="text" placeholder="Masukkan nama anda" id="email" name="nama" required>
        </div>
        <div class="login-form-group">
            <label for="email">Username<span class="required-star">*</span></label>
            <input type="text" placeholder="Masukkan username anda" id="email" name="username" required>
            <div id="error-message" style="color: red; font-weight: bold;"></div>
        </div>
        <div class="login-form-group">
            <label for="email">Email<span class="required-star">*</span></label>
            <input type="text" placeholder="Masukkan email anda" id="email" name="email" required>
        </div>
        <div class="login-form-group">
            <label for="no_tlp">No Telepon<span class="required-star">*</span></label>
            <input type="text" placeholder="Masukkan nomor telepon anda" id="email" name="no_telepon" required>
        </div>
        <div class="login-form-group">
            <label for="pwd">Password <span class="required-star">*</span></label>
            <input autocomplete="off" type="password" placeholder="Minimum 8 characters" id="pwd" name="password" required>
        </div>
        <div class="login-form-group">
            <label for="pwd">Confirm Password <span class="required-star">*</span></label>
            <input autocomplete="off" type="password" placeholder="Minimum 8 characters" id="pwd" name="password" required>
        </div>
        <input type="hidden" name="token" id="token">
        <button class="rounded-button login-cta g-recaptcha" type="submit"
        data-sitekey="6LcUfDsoAAAAAKWDZQoulxVqCHCHc50yX1Akzij2" 
        data-callback='onSubmit' 
        data-action='submit'>Daftar Sekarang</button>
      </form>
      <?php if (isset($error)) { echo $error; } ?>
      <div class="register-div">Sudah mempunyai akun? <a href="login.php" class="link create-account" -link>Login</a></div>
    </div>

  </div>
  <div class="onboarding">
      <!--Hey! This is the original version
of Simple CSS Waves-->

<div class="header">

<!--Content before waves-->
<div class="inner-header flex">
<!--Just the logo.. Don't mind this-->

</div>

<!--Waves Container-->
<div>
<svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
<defs>
<path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
</defs>
<g class="parallax">
<use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7" />
<use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
<use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
<use xlink:href="#gentle-wave" x="48" y="7" fill="#fff" />
</g>
</svg>
</div>
<!--Waves end-->

</div>
<!--Header ends-->

<!--Content starts-->
<div class="content flex">
</div>
<!--Content ends-->
  </div>
  </div>
</div>
<script src='https://www.google.com/recaptcha/api.js?render=6LcUfDsoAAAAAKWDZQoulxVqCHCHc50yX1Akzij2'></script>
<script>
   function onSubmit(token) {
    document.getElementById("token").value = token;
    document.getElementById("form").submit();
   }
  document.getElementById('error-message').style.color = 'red';
  document.getElementById('error-message').style.fontWeight = 'bold'; 

 </script>
</body>

</html>