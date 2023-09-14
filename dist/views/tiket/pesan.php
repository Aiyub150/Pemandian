<?php
session_start();

require '../../app/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Membuat prepared statement
    $stmt = $conn->prepare("SELECT id_user FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $_SESSION['username'] = $username;
        header("location: ../index.php"); 
    } else {
        $error = "Username atau password salah.";
    }

    $stmt->close();
}
?>
<style>
  .hidden {
    display: none;
  }
</style>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pemandian</title>
    <link rel="stylesheet" href="../../../public/assets/css/main/app.css">
    <link rel="stylesheet" href="../../../public/assets/css/pages/auth.css">
    <link rel="shortcut icon" href="../../../public/assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="../../../public/assets/images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div id="auth">
        
<div class="row h-100">
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <div class="auth-logo">
                <a href="../index.php"><img src="../../../public/img/logo_pemandian_transparant.png" alt="Logo" style="width: 200px; height: 50px;"></a>
            </div>
            <h1 class="auth-title">Pesan Tiket.</h1>
            <p class="auth-subtitle mb-5">Silahkan masukkan data anda dan tiket yang ingin anda pesan.</p>

            <form method="post" action="">
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" class="form-control form-control-xl" placeholder="Nama Anda" name="nama" required> 
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" class="form-control form-control-xl" placeholder="Nomor Telepon Anda" name="no_tlp" required>
                    <div class="form-control-icon">
                        <i class="fa fa-phone"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <select class="form-select form-control-xl" id="ageSelector" onchange="showForm()">
                    <option value="none">Pilih Usia</option>
                    <option value="dewasa">Dewasa</option>
                    <option value="remaja">Remaja</option>
                    <option value="anak">Anak-Anak</option>
                    </select>
                    
                    <div id="formDewasa" class="hidden">
                    <input type="text" class="form-control form-control-xl" placeholder="Nomor Telepon Anda" name="no_tlp" required>
                    <div class="form-control-icon">
                        <i class="fa fa-phone"></i>
                    </div>
                    </div>

                    <div id="formRemaja" class="hidden">
                    <input type="text" class="form-control form-control-xl" placeholder="Nomor Telepon Anda" name="no_tlp" required>
                    <div class="form-control-icon">
                        <i class="fa fa-phone"></i>
                    </div>
                    </div>

                    <div id="formAnak" class="hidden">
                    <input type="text" class="form-control form-control-xl" placeholder="Nomor Telepon Anda" name="no_tlp" required>
                    <div class="form-control-icon">
                        <i class="fa fa-phone"></i>
                    </div>
                    </div>
                </div>
                <div class="form-check form-check-lg d-flex align-items-end">
                    <!-- <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label text-gray-600" for="flexCheckDefault">
                        Keep me logged in
                    </label> -->
                </div>
                <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Pesan Sekarang</button>
            </form>
            <?php if (isset($error)) { echo $error; } ?> 
            <div class="text-center mt-5 text-lg fs-4">
                <p class="text-gray-600">Don't have an account? <a href="register.php" class="font-bold">Sign
                        up</a>.</p>
                <p><a class="font-bold" href="forgot_password.php">Forgot password?</a>.</p>
            </div>
        </div>
    </div>
    <div class="col-lg-7 d-none d-lg-block">
    <img style="height: 100%;" src="../../../public/img/background2.png" alt="">
    </div>
</div>

    </div>
    <script>
function showForm() {
  var ageSelector = document.getElementById("ageSelector");
  var selectedValue = ageSelector.value;
  var forms = document.querySelectorAll("[id^='form']");

  forms.forEach(function(form) {
    if (form.id === "form" + selectedValue) {
      form.classList.remove("hidden");
    } else {
      form.classList.add("hidden");
    }
  });

  var options = ageSelector.querySelectorAll("option");
  options.forEach(function(option) {
    if (option.value !== selectedValue && option.value !== "none") {
      option.disabled = true;
    } else {
      option.disabled = false;
    }
  });
}
</script>
</body>

</html>
