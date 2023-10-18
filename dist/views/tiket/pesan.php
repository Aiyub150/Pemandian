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
            <?php if(isset($_SESSION['nama'])){ echo "Hallo ".$_SESSION['nama']." !"; }?><br />
            <br />
            United States of America
          </address>
        </div>
      </div>

      <fieldset>
        <legend>Tiket</legend>
        <div class="form__radios">
          <div class="form__radio">
            <label for="jenis_tiket">Dewasa</label>
            <input name="jenis_tiket" class="form-control" min="0" value="0" type="number" style="width: 180px;"/>
          </div>

          <div class="form__radio">
            <label for="jenis_tiket">Remaja</label>
            <input name="jenis_tiket" class="form-control" min="0" value="0" type="number" style="width: 180px;"/>
          </div>

          <div class="form__radio">
            <label for="jenis_tiket">Anak-Anak</label>
            <input name="jenis_tiket" class="form-control" min="0" value="0" type="number" style="width: 180px;"/>
          </div>
        </div>
      </fieldset>

      <fieldset>
        <legend>Metode Pembayaran</legend>
        <div class="form__radios">
          <div class="form__radio">
            <img src="../../../public/img/gopay.png" alt="" style="width: 20px; height: 20px; margin-right: 10px;">
            <label for="visa">GOPAY</label>
            <input checked id="visa" name="payment-method" type="radio" />
          </div>
          
          <div class="form__radio">
            <img src="../../../public/img/dana.png" alt="" style="width: 20px; height: 20px; margin-right: 10px;">
            <label for="visa">Dana</label>
            <input id="visa" name="payment-method" type="radio" />
          </div>
          
          <div class="form__radio">
            <img src="../../../public/img/cod.png" alt="" style="width: 20px; height: 20px; margin-right: 10px;">
            <label for="mastercard">BAYAR DI LOKER</label>
            <input id="mastercard" name="payment-method" type="radio" />
          </div>
        </div>
      </fieldset>

      <div>
      <h2>Total.</h2>

      <table>
        <tbody>
          <tr>
            <td>Tiket Dewasa</td>
            <td align="right">Rp. 15.000</td>
          </tr>
          <tr>
            <td>Tiket Remaja</td>
            <td align="right">Rp. 10.000</td>
          </tr>
          <tr>
            <td>Tiket Anak</td>
            <td align="right">Rp. 5.000</td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td>Total</td>
            <td align="right">Rp. 20.000</td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div>
      <button class="button button--full g-recaptcha" data-sitekey="6LcUfDsoAAAAAKWDZQoulxVqCHCHc50yX1Akzij2" 
        data-callback='onSubmit' 
        data-action='submit' type="submit"><i class="fa fa-shopping-bag" aria-hidden="true"></i> Bayar Sekarang</button>
    </div>
    </form>
  </div>

  <script src='https://www.google.com/recaptcha/api.js'></script>
  <script>
       function onSubmit(token) {
        document.getElementById("token").value = token;
        document.getElementById("form").submit();
      }

      function tampilkanInput() {
      var pilihan = document.getElementById("pilihanTiket").value;
      var inputContainer = document.getElementById("inputContainer");

      if (pilihan === "dewasa") {
          tambahInput("Tiket Dewasa", "Dewasa", 15000);
      } else if (pilihan === "remaja") {
          tambahInput("Tiket Remaja", "Remaja", 10000);
      } else if (pilihan === "anak") {
          tambahInput("Tiket Anak", "Anak", 5000);
      }
  }

  function tambahInput(label, name, harga) {
      var inputElement = document.createElement("div");
      inputElement.classList.add("login-form-group");

      inputElement.innerHTML = `
          <label for="${name}">${label} <span class="required-star">*</span></label>
          <input class="hidden" name="jenis_tiket" value="${label}">
          <input autocomplete="off" type="number" min="0" value="0" id="${name}" name="quantity" onchange="hitungTotal()">
          <input autocomplete="off" type="number" value="${harga}" id="harga${name}" name="tiket" class="hidden">
          <input autocomplete="off" type="text" value="" id="subtotal${name}" name="sub_total" class="hidden" readonly>
      `;

      document.getElementById("inputContainer").appendChild(inputElement);
  }

  function tambahSelect() {
      var inputContainer = document.getElementById("inputContainer");

      // Membuat array dari semua opsi yang telah dipilih
      var selectedOptions = Array.from(document.querySelectorAll("select")).map(el => el.value);

      // Membuat array dari semua opsi yang tersedia
      var options = ["dewasa", "remaja", "anak"].filter(option => !selectedOptions.includes(option));

      if (options.length === 0) {
          // Semua opsi telah dipilih, menyembunyikan dropdown
          inputContainer.innerHTML = '';
      } else {
          var selectElement = document.createElement("select");
          selectElement.name = "jenisTiket";
          selectElement.onchange = function() { tampilkanInput(); };

          options.forEach(option => {
              var optionElement = document.createElement("option");
              optionElement.value = option;
              optionElement.textContent = option.charAt(0).toUpperCase() + option.slice(1);
              selectElement.appendChild(optionElement);
          });

          inputContainer.innerHTML = ''; // Menghapus elemen lama
          inputContainer.appendChild(selectElement);
      }
  }



      function onSubmit(token) {
      document.getElementById("form").submit();
    }
    function hitungTotal() {
      var d = document.getElementById("Dewasa");
      var dewasa = d ? parseInt(d.value) : 0;
      var hargaDewasa = document.getElementById("hargaDewasa").value;

      var r = document.getElementById("Remaja");
      var remaja = r ? parseInt(r.value) : 0;
      var hargaRemaja = document.getElementById("hargaRemaja").value;

      var a = document.getElementById("Anak");
      var anak = a ? parseInt(a.value) : 0;
      var hargaAnak = document.getElementById("hargaAnak").value;

      var totalDewasa = dewasa * hargaDewasa;
      var totalRemaja = remaja * hargaRemaja;
      var totalAnak = anak * hargaAnak;

      var total = totalDewasa + totalRemaja + totalAnak;

      document.getElementById("total").value = total;
      document.getElementById("subtotalDewasa").value = totalDewasa;
      document.getElementById("subtotalRemaja").value = totalRemaja;
      document.getElementById("subtotalAnak").value = totalAnak;
  }


  </script>
  <script src='https://www.google.com/recaptcha/api.js?render=6LcUfDsoAAAAAKWDZQoulxVqCHCHc50yX1Akzij2'></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>

  </html>


