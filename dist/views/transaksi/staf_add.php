<?php

session_start(); // Pastikan Anda memulai sesi sebelum mengakses $_SESSION

if(isset($_SESSION['level']) && ($_SESSION['level'] == '1' || $_SESSION['level'] == '2')){

// Pengguna dengan level 1 atau 2 diizinkan mengakses dashboard.php

} else {

header('Location: ../index.php'); exit();

}

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
    $tgl_pemesanan = date('Y-m-d');
    $total_harga = $_POST['totaltiket'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $status = $_POST['status'];

    $transaksi = "INSERT INTO transaksi (id_transaksi, id_user, tgl_pemesanan, total_harga, metode_pembayaran, bukti_pembayaran, status) VALUES ('$id_transaksi', '$id_user', '$tgl_pemesanan', '$total_harga', '$metode_pembayaran',  'bayar di loket','$status')";
    if ($conn->query($transaksi) === true) {
      // Lakukan loop untuk memasukkan data-detail tiket sebanyak tiga kali
      for ($i = 1; $i <= 2; $i++) {
        $jenis_tiket = $_POST['jenis_tiket'.$i];
        $quantity = $_POST['quantity'.$i];
        $sub_total = $_POST['sub_total'.$i];
        $detail_transaksi = "INSERT INTO detail_transaksi (id_transaksi, jenis_tiket, quantity, sub_total) VALUES ('$id_transaksi', '$jenis_tiket', '$quantity', '$sub_total')";
        
        if ($conn->query($detail_transaksi) !== true) {
          echo "Error: " . $conn->error;
          break; // Keluar dari loop jika terjadi error
        }
      }
       header("location: staf.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error ;
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>transaksi - Pemandian</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../../public/assets/css/main/app.css">
    <link rel="stylesheet" href="../../../public/assets/css/main/app-dark.css">
    <link rel="shortcut icon" href="../../../public/assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="../../../public/assets/images/logo/favicon.png" type="image/png">
    
<link rel="stylesheet" href="../../../public/assets/css/shared/iconly.css">

</head>
<style>
    .hidden{
        display: none;
    }
</style>
<body>
            
<div class="page-heading">
    <h3>Tiket - Transaksi</h3>
</div>
<div class="page-content">
    <section class="row">
                <div class="card">
                    <div class="col-md-6 col-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Tambah Data Transaksi</h4>
                  </div>
                  <div class="card-content">
                    <div class="card-body">
                      <form class="form form-horizontal" method="post">
                        <div class="form-body">
                          <div class="row">
                            <div class="col-md-4">
                              <label for="email-horizontal">NAMA</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <input type="text" id="email-horizontal" class="form-control" name="nama" placeholder="nama">
                            </div>
                            <div class="col-md-4">
                              <label for="contact-info-horizontal">DEWASA</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <input type="number" id="dewasa" class="form-control" name="quantity1" min="0" value="0" onchange="hitungTotal()">
                              <input type="number" style="display: none;" id="hargaDewasa" class="form-control"  min="0" value="10000" onchange="hitungTotal()"> 
                              <input type="number" style="display: none;" id="subtotalDewasa" class="form-control"  min="0" value="0" name="sub_total1" onchange="hitungTotal()"> 
                              <input type="text" style="display: none;" class="form-control"  min="0" value="Dewasa" name="jenis_tiket1" onchange="hitungTotal()"> 
                            </div>
                            <div class="col-md-4">
                              <label for="contact-info-horizontal">ANAK</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <input type="number" id="anak" class="form-control" name="quantity2" min="0" value="0" onchange="hitungTotal()">
                              <input type="number" style="display: none;" id="hargaAnak" class="form-control"  min="0" value="5000" onchange="hitungTotal()">
                              <input type="number" style="display: none;" id="subtotalAnak" class="form-control"  min="0" value="0" name="sub_total2" onchange="hitungTotal()"> 
                              <input type="text" style="display: none;" class="form-control"  min="0" value="Anak-Anak" name="jenis_tiket2" onchange="hitungTotal()"> 
                            </div>
                            <div class="col-md-4">
                              <label for="contact-info-horizontal">TOTAL HARGA</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <input type="number" id="total" class="form-control" name="totaltiket" placeholder="total harga" onchange="hitungTotal()" readonly>
                            </div>
                            <div class="col-md-4">
                              <label for="contact-info-horizontal">LOKET</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <select name="metode_pembayaran" class="form-select" id="" required>
                                <option value="" readonly>Pilih Nomor Loket</option>
                                <option value="Loket 1">Loket 1</option>
                                <option value="Loket 2">Loket 2</option>
                              </select>
                              </div>                        

                            <div class="col-md-4">
                              <label for="contact-info-horizontal">STATUS PEMBAYARAN</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <select name="status" class="form-select" id="" required>
                                <option value="">Pilih Status Pembayaran</option>
                                <option value="done">Sudah Dibayar</option>
                                <option value="notyet">Belum Dibayar</option>
                              </select>
                            </div>
                            <div class="col-sm-12 d-flex justify-content-end">
                              <button type="submit" class="btn btn-primary me-1 mb-1">
                                Submit
                              </button>
                              <button type="reset" class="btn btn-light-secondary me-1 mb-1">
                                Reset
                              </button>
                              <button type="button" class="btn btn-danger me-1 mb-1" onclick="location.href='staf.php'">
                                    Kembali
                                </button>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
                </div>
        </div>
    </section>
</div>

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2023 &copy; Pemandian</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="../../../public/assets/js/bootstrap.js"></script>
    <script src="../../../public/assets/js/app.js"></script>
    
<!-- Need: Apexcharts -->
<script src="../../../public/assets/extensions/apexcharts/apexcharts.min.js"></script>
<script src="../../../public/assets/js/pages/dashboard.js"></script>
<script>

  function onSubmit(token) {
     document.getElementById("form").submit();
   }
  function hitungTotal() {
    // Calculate subtotal for each ticket type
    let dewasa = document.getElementById("dewasa").value;
    let hargadewasa = document.getElementById("hargaDewasa").value;
    let anak = document.getElementById("anak").value;
    let hargaanak = document.getElementById("hargaAnak").value;

    let sub_totalDewasa = dewasa * hargadewasa;
    let sub_totalAnak = anak * hargaanak;

    // Update the subtotals in the input fields
    document.getElementById("subtotalDewasa").value = sub_totalDewasa;
    document.getElementById("subtotalAnak").value = sub_totalAnak;

    // Calculate the total
    let total = sub_totalDewasa + sub_totalAnak;

    // Update the total in the input field
    document.getElementById("total").value = total;
}
 </script>
</body>

</html>
