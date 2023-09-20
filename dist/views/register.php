<?php
require '../app/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $no_telepon = $_POST['no_telepon'];

    // Membuat prepared statement
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, no_telepon) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss",  $username, $password, $email, $no_telepon);

    if ($stmt->execute()) {
        header("location: login.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pemandian</title>
    <link rel="stylesheet" href="../../public/assets/css/main/app.css">
    <link rel="stylesheet" href="../../public/assets/css/pages/auth.css">
    <link rel="icon" type="image/x-icon" href="../../public/img/icon.png" />
</head>

<body>
    
    <div id="auth">
        
<div class="row h-100">
    
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <div class="auth-logo">
                <a href="index.php"><img src="../../public/img/logo_pemandian_transparant.png" alt="Logo" style="width: 200px; height: 50px;"></a>
            </div>
            <h1 class="auth-title">Daftar</h1>
            <p class="auth-subtitle mb-5">Silahkan untuk mendaftarkan data anda dan pastikan data yang anda masukkan sudah benar.</p>
            
            <form method="post" action="">
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" name="username" class="form-control form-control-xl" placeholder="Username"  required>
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" name="email" class="form-control form-control-xl" placeholder="Email"  required>
                    <div class="form-control-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" name="no_telepon" class="form-control form-control-xl" placeholder="No Telepon"  required>
                    <div class="form-control-icon">
                        <i class="bi bi-phone"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" name="password" class="form-control form-control-xl" placeholder="Password"  required>
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" class="form-control form-control-xl" placeholder="Confirm Password" required>
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>
                <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Daftar</button>
            </form>        
            <?php if (isset($error)) { echo $error; } ?>            
            <div class="text-center mt-5 text-lg fs-4">
                <p class='text-gray-600'>Sudah mempunyai akun? <a href="login.php" class="font-bold">Log
                        in</a>.</p>
            </div>
        </div>
    </div>
    <div class="col-lg-7 d-none d-lg-block">

        <img style="height: 100%;" src="../../public/img/background2.png" alt="">
    </div>
</div>

    </div>
</body>

</html>
