<?php
require '../app/config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $secret_key = "6LcUfDsoAAAAAOuwYSk9i_ZoQwCgIexCeVMJ31Vb";
    $verify = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['token']);
    $response = json_decode($verify);
    if($response == true){
      $username = $_POST['username'];
      $password = $_POST['password'];

      // Membuat prepared statementecho 
      $query = "SELECT * FROM users WHERE (username='$username' OR email='$username') AND password='$password'";
      $result = mysqli_query($conn, $query);
      $d = mysqli_fetch_assoc($result);
      
      $cek = mysqli_num_rows($result);
      if ($cek > 0) {
          $_SESSION['id_user'] = $d['id_user'];
          $_SESSION['nama'] = $d['nama'];
          $_SESSION['username'] = $d['username'];
          $_SESSION['level'] = $d['level'];
          
          if($_SESSION['level'] == 1){
              header("location: dashboard/dashboard.php"); 
          } elseif($_SESSION['level'] == 2){
            header("location: transaksi/staf.php"); 
          } else {
              header("location: index.php");
          }
          exit();
      } else {
          $error = "<p style='color: red;'>Username atau password yang anda masukkan salah<p>";
      }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pemandian</title>
    <link rel="stylesheet" href="../../public/css/auth.css">
    <link rel="stylesheet" href="../../public/css/waves.css">
    <link rel="icon" type="image/x-icon" href="../../public/img/icon.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    
</head>

<body>
<!-- https://dribbble.com/shots/15392711-Dashboard-Login-Sign-Up/-->

<div class="login-container">
  <div class="login-form">
    <div class="login-form-inner">
      <div class="logo">
          <img src="../../public/img/logo_pemandian_transparant.png" alt="" style="width: 200px; height: 80px; margin-left: 70px;">
        </div>
      <h1>Login</h1>
      <p class="body-text">Silahkan login terlebih dahulu.</p>

      <form method="post" action="" id="form" style="margin-top: 20px;">
        <div class="login-form-group">
            <label for="email">Email / Username<span class="required-star">*</span></label>
            <input type="text" placeholder="Masukkan username atau email anda" id="email" name="username" required>
        </div>
        <div class="login-form-group">
            <label for="pwd">Password <span class="required-star">*</span></label>
            <input autocomplete="off" type="password" placeholder="Minimum 8 characters" id="pwd" name="password" required>
        </div>
        <?php if (isset($error)) { echo $error; } ?>
        <input type="hidden" name="token" id="token">
        <button class="rounded-button login-cta g-recaptcha" type="submit"
        data-sitekey="6LcUfDsoAAAAAKWDZQoulxVqCHCHc50yX1Akzij2" 
        data-callback='onSubmit' 
        data-action='submit'>Login</button>
      </form>

      <div class="login-form-group single-row">
            <div class="custom-check">
            <input autocomplete="off" type="checkbox" checked id="remember"><label for="remember">Remember me</label>
            </div>

            <a href="forgot_password.php" class="link forgot-link">Forgot Password ?</a>
        </div>        
      <div class="register-div">Belum mempunyai akun? <a href="register.php" class="link create-account" -link>Buat Akun Baru</a></div>
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
<script src='https://www.google.com/recaptcha/api.js?render=6LcUfDsoAAAAAKWDZQoulxVqCHCHc50yX1Akzij2'></script>
<script>
   function onSubmit(token) {
    document.getElementById("token").value = token;
    document.getElementById("form").submit();
   }
 </script>
 <script src="../../public/js/auth.js"></script>
</body>

</html>
