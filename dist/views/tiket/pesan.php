  <?php 
      session_start(); // Pastikan Anda memulai sesi sebelum mengakses $_SESSION
      if(!isset($_SESSION['username'])){
          header('Location: ../login.php');
          exit();
      } else {
        require '../../app/config.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
  ?>

  <style>
    .hidden {
      display: none;
    }

  .login-container .login-form .login-form-group select {
    padding: 13px 20px;
    box-sizing: border-box;
    border: 1px solid var(--grey);
    border-radius: 50px;
    font-family: "Raleway", sans-serif;
    font-weight: 600;
    font-size: 14px;
    color: var(--text);
    transition: linear 0.2s;
    appearance: none; /* Menghilangkan default styles dari elemen select */
  }

  .login-container .login-form .login-form-group select:focus {
    outline: none;
    border: 1px solid var(--primary-color);
  }

  .login-container .login-form .login-form-group select::-webkit-input-placeholder {
    color: var(--placeholder);
    font-weight: 300;
    font-size: 14px;
  }

  </style>
  <!DOCTYPE html>
  <html lang="en">

  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Pesan tiket - Pemandian</title>
      <link rel="stylesheet" href="../../../public/css/auth.css">
      <link rel="stylesheet" href="../../../public/css/waves3.css">
      <link rel="icon" type="image/x-icon" href="../../../public/img/icon.png" />
      
  </head>

  <body>
  <!-- https://dribbble.com/shots/15392711-Dashboard-Login-Sign-Up/-->

  <div class="login-container">
    <div class="login-form">
      <div class="login-form-inner">
        <div class="logo">
            <img src="../../../public/img/logo_pemandian_transparant.png" alt="" style="width: 200px; height: 50px; margin-left: 70px;">
          </div>
        <h1>Pesan Tiket</h1>
        <!-- <p class="body-text">Silahkan untuk memesan tiket dibawah ini.</p> -->

        <!-- <a href="#" class="rounded-button google-login-button">
          <span class="google-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
              <path d="M113.47 309.408L95.648 375.94l-65.139 1.378C11.042 341.211 0 299.9 0 256c0-42.451 10.324-82.483 28.624-117.732h.014L86.63 148.9l25.404 57.644c-5.317 15.501-8.215 32.141-8.215 49.456.002 18.792 3.406 36.797 9.651 53.408z" fill="#fbbb00" />
              <path d="M507.527 208.176C510.467 223.662 512 239.655 512 256c0 18.328-1.927 36.206-5.598 53.451-12.462 58.683-45.025 109.925-90.134 146.187l-.014-.014-73.044-3.727-10.338-64.535c29.932-17.554 53.324-45.025 65.646-77.911h-136.89V208.176h245.899z" fill="#518ef8" />
              <path d="M416.253 455.624l.014.014C372.396 490.901 316.666 512 256 512c-97.491 0-182.252-54.491-225.491-134.681l82.961-67.91c21.619 57.698 77.278 98.771 142.53 98.771 28.047 0 54.323-7.582 76.87-20.818l83.383 68.262z" fill="#28b446" />
              <path d="M419.404 58.936l-82.933 67.896C313.136 112.246 285.552 103.82 256 103.82c-66.729 0-123.429 42.957-143.965 102.724l-83.397-68.276h-.014C71.23 56.123 157.06 0 256 0c62.115 0 119.068 22.126 163.404 58.936z" fill="#f14336" />
            </svg></span>
          <span>Login dengan google</span>
        </a> -->

        <form method="post" action="" id="form">
          <div class="sign-in-seperator">
          <span>Silahkan masukkan jumlah tiket dibawah ini</span>
        </div>
        <div class="login-form-group" id="pilihanTiketContainer">
        <label for="pilihanTiket">Jenis Tiket <span class="required-star">*</span></label>
          <select id="pilihanTiket" name="jenisTiket" onchange="tampilkanInput()">
              <option value="">Pilih Jenis Tiket</option>
              <option value="dewasa">Dewasa</option>
              <option value="remaja">Remaja</option>
              <option value="anak">Anak</option>
          </select>
        </div>

        <div class="login-form-group" id="inputContainer"></div>

          <div class="login-form-group">
              <label for="anak">Total harga tiket <span class="required-star">*</span></label>
              <div class="input-group">
                  <span class="input-group-addon">Rp.</span>
                  <input autocomplete="off" type="text" value="" id="total" name="totaltiket" readonly>
              </div>
          </div>
          <div class="login-form-group">
              <label for="metode_pembayaran">Metode Pembayaran <span class="required-star">*</span></label>
              <select class="form-select" name="metode_pembayaran" id="">
                <option value="">Pilih Metode Pembayaran</option>
                <option value="gopay">gopay</option>
                <option value="dana">dana</option>
                <option value="cod">bayar di tempat</option>
              </select>
          </div>
          <div class="login-form-group">
              <label for="status">Status <span class="required-star">*</span></label>
              <select class="form-select" name="status" id="">
                <option value="">Pilih status Pembayaran</option>
                <option value="done">Sudah Dibayar</option>
                <option value="notyet">Belum Dibayar</option>
              </select>
          </div>
          <button class="rounded-button login-cta g-recaptcha" type="submit"
          data-sitekey="6LcUfDsoAAAAAKWDZQoulxVqCHCHc50yX1Akzij2" 
          data-callback='onSubmit' 
          data-action='submit'>Pesan Sekarang</button>
        </form>
        <?php if (isset($error)) { echo $error; } ?>
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
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <script>
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
  </body>

  </html>


