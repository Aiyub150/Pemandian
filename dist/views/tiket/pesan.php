<?php 
    session_start(); // Pastikan Anda memulai sesi sebelum mengakses $_SESSION
    if(!isset($_SESSION['username'])){
        header('Location: ../login.php');
        exit();
    } else {
      require '../../app/config.php';

      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $secret_key = "6LcUfDsoAAAAAOuwYSk9i_ZoQwCgIexCeVMJ31Vb";
        // Disini kita akan melakukan komunkasi dengan google recpatcha
        // dengan mengirimkan scret key dan hasil dari response recaptcha nya
        $verify = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['token']);
        $response = json_decode($verify);
        if($response == true){
          $sql = $conn->query("SELECT MAX(id_transaksi) as max_id FROM transaksi;");
          $result = $sql->fetch_assoc();
          $id_transaksi =  $result["max_id"] + 1;
          $id_user = $_SESSION['id_user'];
          $jenis_tiket = $_POST['jenis_tiket'];
          $quantity = $_POST['quantity'];
          $sub_total = $_POST['sub_total'];
          $tgl_pemesanan = date('Y-m-d');
          $total_harga = $_POST['totaltiket'];
          $metode_pembayaran = $_POST['metode_pembayaran'];
          $status = $_POST['status'];

          $transaksi = "INSERT INTO transaksi (id_transaksi, id_user, tgl_pemesanan, total_harga, metode_pembayaran, status) VALUES ('$id_transaksi', '$id_user', '$tgl_pemesanan', '$total_harga', '$metode_pembayaran', '$status')";
          $detail_transaksi = "INSERT INTO detail_transaksi (id_transaksi, jenis_tiket, quantity, sub_total) VALUES ('$id_transaksi', '$jenis_tiket', '$quantity', '$sub_total')";

          if ($conn->query($transaksi) === true) {
            if ($conn->query($detail_transaksi) === true) {
              header("location: ../transaksi/transaksi.php");            
            } else {
              echo "Error: " . $sql . "<br>" . $conn->error;
            }
          } else {
              echo "Error: " . $sql . "<br>" . $conn->error;
          }
        }
      }
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
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/x-icon" href="../../../public/img/icon.png" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../../../public/css/checkout.css">
<title>Pesan Tiket - Pemandian</title>
</head>
<body>
  <div class="iphone">
    <header class="header">
      <legend style="text-align: center;">Pesan Tiket</legend>
    </header>

    <form action="https://httpbin.org/post" class="form" method="POST">
      <div>
        <div class="card">
          <address>
            <?php if(isset($_SESSION['nama'])){ echo "Hallo kak ".$_SESSION['nama']." !"; }?><br />
            Silahkan pesan tiket dan berikut adalah harga-harganya : <br />
            Dewasa : Rp. 15.000<br />
            Remaja : Rp. 10.000<br />
            Anak   : Rp. 5.000<br />
          </address>
        </div>
      </div>

      <fieldset>
        <legend>Tiket</legend>
        <div class="form__radios">
          <div class="form__radio">
            <label for="jenis_tiket">Dewasa</label>
            <input name="jenis_tiket" id="dewasa" class="form-control" min="0" value="0" type="number" style="width: 180px;" onchange="hitungTotal()"/>
            <input type="number" class="hidden" value="15000" id="hargaDewasa" onchange="hitungTotal()">
          </div>

          <div class="form__radio">
            <label for="jenis_tiket">Remaja</label>
            <input name="jenis_tiket" id="remaja" class="form-control" min="0" value="0" type="number" style="width: 180px;" onchange="hitungTotal()"/>
            <input type="number" class="hidden" value="10000" id="hargaRemaja" onchange="hitungTotal()">
          </div>

          <div class="form__radio">
            <label for="jenis_tiket">Anak-Anak</label>
            <input name="jenis_tiket" id="anak" class="form-control" min="0" value="0" type="number" style="width: 180px;" onchange="hitungTotal()"/>
            <input type="number" class="hidden" value="5000" id="hargaAnak" onchange="hitungTotal()">
          </div>
        </div>
        <div class="login-form-group">
            <label for="remaja">Tiket Remaja <span class="required-star">*</span></label>
            <input autocomplete="off" type="number" min="0" value="0" id="remaja" name="tiket" onchange="hitungTotal()">
            <input autocomplete="off" type="number" value="10000" id="hargaRemaja" name="tiket" class="hidden">
        </div>
        <div class="login-form-group">
            <label for="anak">Tiket Anak <span class="required-star">*</span></label>
            <input autocomplete="off" type="number" min="0" value="0" id="anak" name="tiket" onchange="hitungTotal()">
            <input autocomplete="off" type="number" value="5000" id="hargaAnak" name="tiket" class="hidden">
        </div>
        <div class="login-form-group">
            <label for="anak">Total harga tiket <span class="required-star">*</span></label>
            <div class="input-group">
                <span class="input-group-addon">Rp.</span>
                <input autocomplete="off" type="text" value="" id="total" name="totaltiket" readonly>
                
            </div>
        </div>
      </fieldset>

      <div>
      <h2>Total.</h2>

      <table>
        <tbody>
          <tr>
            <td>Tiket Dewasa</td>
            <td align="right">Rp. <input type="number" value="0" id="subtotalDewasa" style="width: 70px;" onchange="hitungTotal()" readonly></td>
          </tr>
          <tr>
            <td>Tiket Remaja</td>
            <td align="right">Rp. <input type="number" value="0" id="subtotalRemaja" style="width: 70px;" onchange="hitungTotal()" readonly></td>
          </tr>
          <tr>
            <td>Tiket Anak</td>
            <td align="right">Rp. <input type="number" value="0" id="subtotalAnak" style="width: 70px;" onchange="hitungTotal()" readonly></td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td>Total</td>
            <td align="right">Rp. <input type="number" value="0" id="total" style="width: 70px;" onchange="hitungTotal()" readonly></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div>
      <button class="button button--full g-recaptcha" data-sitekey="6LcUfDsoAAAAAKWDZQoulxVqCHCHc50yX1Akzij2" 
        data-callback='onSubmit' 
        data-action='submit' type="submit" onclick="tampilkanPopup()"><i class="fa fa-ticket" aria-hidden="true"></i> Pesan Sekarang</button>
    </div>
    </form>
  </div>
  
  <div id="popup" class="popup">
    <div class="popup-content">
        <!-- Isi dari nota pembayaran -->
        <!-- Anda dapat menambahkan elemen-elemen HTML sesuai dengan kebutuhan Anda -->
        <h2>Nota Pembayaran</h2>
        <p>Tiket Dewasa: <span id="notaDewasa"></span></p>
        <p>Tiket Remaja: <span id="notaRemaja"></span></p>
        <p>Tiket Anak-Anak: <span id="notaAnak"></span></p>
        <p>Total: <span id="notaTotal"></span></p>
        <!-- Akhir dari isi nota pembayaran -->
        <button onclick="tutupPopup()">Tutup</button>
    </div>
</div>

  <script src='https://www.google.com/recaptcha/api.js'></script>
  <script>
  function onSubmit(token) {
        document.getElementById("token").value = token;
        document.getElementById("form").submit();
  }

  function hitungTotal() {
    // Calculate subtotal for each ticket type
    let dewasa = document.getElementById("dewasa").value;
    let hargadewasa = document.getElementById("hargaDewasa").value;
    let remaja = document.getElementById("remaja").value;
    let hargaremaja = document.getElementById("hargaRemaja").value;
    let anak = document.getElementById("anak").value;
    let hargaanak = document.getElementById("hargaAnak").value;

    let sub_totalDewasa = dewasa * hargadewasa;
    let sub_totalRemaja = remaja * hargaremaja;
    let sub_totalAnak = anak * hargaanak;

    // Update the subtotals in the input fields
    document.getElementById("subtotalDewasa").value = sub_totalDewasa;
    document.getElementById("subtotalRemaja").value = sub_totalRemaja;
    document.getElementById("subtotalAnak").value = sub_totalAnak;

    // Calculate the total
    let total = sub_totalDewasa + sub_totalRemaja + sub_totalAnak;

    // Update the total in the input field
    document.getElementById("total").value = total;
}

function tampilkanPopup() {
    let notaDewasa = document.getElementById("subtotalDewasa").value;
    let notaRemaja = document.getElementById("subtotalRemaja").value;
    let notaAnak = document.getElementById("subtotalAnak").value;
    let total = document.getElementById("total").value;

    document.getElementById("notaDewasa").textContent = "Rp. " + notaDewasa;
    document.getElementById("notaRemaja").textContent = "Rp. " + notaRemaja;
    document.getElementById("notaAnak").textContent = "Rp. " + notaAnak;
    document.getElementById("notaTotal").textContent = "Rp. " + total;

    document.getElementById("popup").style.display = "flex";
}

function tutupPopup() {
    document.getElementById("popup").style.display = "none";
}

  </script>  
  <script src='https://www.google.com/recaptcha/api.js?render=6LcUfDsoAAAAAKWDZQoulxVqCHCHc50yX1Akzij2'></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>

  </html>


