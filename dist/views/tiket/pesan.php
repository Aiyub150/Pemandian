<?php
session_start();
if (!isset($_SESSION['id_user']) || !isset($_SESSION['level']) != '1') {
    header("location: ../login.php"); // Arahkan ke halaman login jika tidak ada sesi id_user
    exit();
}

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
    <link rel="icon" type="image/x-icon" href="../../../public/img/icon.png" />
    <link rel="stylesheet" href="../../../public/assets/css/main/app.css">
    <link rel="stylesheet" href="../../../public/assets/css/pages/auth.css">
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
                <h4 style="text-align: center;">Silahkan Pilih Jumlah Tiket</h4>
                <h5>Dewasa</h5>
                <div class="form-group position-relative has-icon-left mb-4">                    
                    <input type="number" class="form-control form-control-xl" id="dewasa" value="0" name="no_tlp" min="0" onchange="hitungTotal()" required>
                    <input type="number" class="form-control form-control-xl" id="hargaDewasa" value="15000" min="0" style="display: none;" onchange="hitungTotal()">      
                    <div class="form-control-icon">
                        <i class="fa fa-ticket"></i>
                    </div>         
                </div>
                <h5>Remaja</h5>
                <div class="form-group position-relative has-icon-left mb-4">                    
                    <input type="number" class="form-control form-control-xl" id="remaja" value="0" name="no_tlp" min="0" onchange="hitungTotal()" required>   
                    <input type="number" class="form-control form-control-xl" id="hargaRemaja" value="10000" min="0" style="display: none;" onchange="hitungTotal()">
                    <div class="form-control-icon">
                        <i class="fa fa-ticket"></i>
                    </div>         
                </div>
                <h5>Anak-Anak</h5>
                <div class="form-group position-relative has-icon-left mb-4">                    
                    <input type="number" class="form-control form-control-xl" id="anak" value="0" name="no_tlp" min="0" onchange="hitungTotal()" required> 
                    <input type="number" class="form-control form-control-xl" id="hargaAnak" value="5000" min="0" style="display: none;" onchange="hitungTotal()">  
                    <div class="form-control-icon">
                        <i class="fa fa-ticket"></i>
                    </div>         
                </div>
                <h5>Total</h5>
                <div class="form-group position-relative has-icon-left mb-4">   
                <input class="form-control form-control-xl" type="text" id="total" readonly>
                <div class="form-control-icon">
                    <p style="font-size: 25px;">Rp.  </P> 
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
        </div>
    </div>
    <div class="col-lg-7 d-none d-lg-block">
    <img style="height: 100%;" src="../../../public/img/background2.png" alt="">
    </div>
</div>

    </div>
    <script>
function hitungTotal() {
    var dewasa = document.getElementById("dewasa").value;
    var hargaDewasa = document.getElementById("hargaDewasa").value;
    var remaja = document.getElementById("remaja").value;
    var hargaRemaja = document.getElementById("hargaRemaja").value;
    var anak = document.getElementById("anak").value;
    var hargaAnak = document.getElementById("hargaAnak").value;
    var totalDewasa = dewasa * hargaDewasa;
    var totalRemaja = remaja * hargaRemaja;
    var totalAnak = anak * hargaAnak;
    var total = totalDewasa + totalRemaja + totalAnak;

    document.getElementById("total").value = total;
}
</script>
</body>

</html>
