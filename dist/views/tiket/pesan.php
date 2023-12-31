<?php 
    session_start(); // Pastikan Anda memulai sesi sebelum mengakses $_SESSION
    if(!isset($_SESSION['username'])){
        header('Location: ../login.php');
        exit();
    } else {
        require '../../app/config.php';
        $sql_max_id = $conn->query("SELECT MAX(id_transaksi) as max_id FROM transaksi;");
        $result_max_id = $sql_max_id->fetch_assoc();
        $maksimalid = $result_max_id["max_id"];
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $secret_key = "6LcUfDsoAAAAAOuwYSk9i_ZoQwCgIexCeVMJ31Vb";
            // Disini kita akan melakukan komunikasi dengan google recaptcha
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
                $status = "";

                if($status == null){
                    $status = "notyet";
                }

                // Set nilai default untuk nama gambar
                $nama_gambar = "Bayar Di Loket";

                // Mengelola unggahan gambar
                if(isset($_FILES["bukti_pembayaran"]) && $_FILES["bukti_pembayaran"]["error"] == 0){
                    $target_dir = "../../app/payment/";  // Sesuaikan dengan path folder Anda
                    $target_file = $target_dir . basename($_FILES["bukti_pembayaran"]["name"]);
                    $uploadOk = 1;
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                    // Validasi gambar
                    $check = getimagesize($_FILES["bukti_pembayaran"]["tmp_name"]);
                    if($check !== false) {
                        echo "File adalah gambar - " . $check["mime"] . ".";
                        $uploadOk = 1;
                    } else {
                        echo "File bukan gambar.";
                        $uploadOk = 0;
                    }

                    // Periksa ukuran file
                    if ($_FILES["bukti_pembayaran"]["size"] > 500000) {
                        echo "Maaf, ukuran file terlalu besar.";
                        $uploadOk = 0;
                    }

                    // Izinkan beberapa tipe file
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                        && $imageFileType != "gif" ) {
                        $uploadOk = 0;
                    }

                    // Periksa jika $uploadOk tidak berubah menjadi 0 oleh kesalahan
                    if ($uploadOk == 0) {
                        echo "Maaf, file tidak terunggah.";
                    } else {
                        if (move_uploaded_file($_FILES["bukti_pembayaran"]["tmp_name"], $target_file)) {
                            echo "File ". htmlspecialchars( basename( $_FILES["bukti_pembayaran"]["name"])). " sudah terunggah.";
                            $nama_gambar = basename($_FILES["bukti_pembayaran"]["name"]);
                        } else {
                            echo "Maaf, terjadi kesalahan saat mengunggah file.";
                        }
                    }
                }

                // Setelah gambar berhasil diunggah atau tidak, Anda dapat menyimpan data ke dalam database.
                $transaksi = "INSERT INTO transaksi (id_transaksi, id_user, tgl_pemesanan, total_harga, metode_pembayaran, bukti_pembayaran, status) VALUES ('$id_transaksi', '$id_user', '$tgl_pemesanan', '$total_harga', '$metode_pembayaran', '$nama_gambar','$status')";

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
                    $_SESSION['id_transaksi'] = $id_transaksi;
                    header("location: nota.php");
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error ;
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
<link rel="stylesheet" href="../../../public/css/checkout.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>Pesan Tiket - Pemandian</title>
</head>
<body>
<div class="mainscreen">
    <!-- <img src="https://image.freepik.com/free-vector/purple-background-with-neon-frame_52683-34124.jpg"  class="bgimg " alt="">--> 
      <div class="card">
        <div class="leftside">
          <img
            src="../../../public/img/icon.png"
            class="product"
            alt="Shoes"
          />
        </div>
        <div class="rightside">
          <form id="form" method="POST" enctype="multipart/form-data">
            <h1>Pesan Tiket</h1>
            <h2>Tiket</h2>
            <label for="dewasa">Dewasa</label>
            <input type="number" id="dewasa" class="inputbox" min="0" value="0" name="quantity1" onchange="hitungTotal()" required/>
            <input type="text" class="hidden" name="name" id="hargaDewasa" value="10000" onchange="hitungTotal()" />
            <input type="text" class="hidden" name="jenis_tiket1" value="Dewasa"  />
            <label for="anak">Anak-Anak</label>
            <input type="number" id="anak" class="inputbox" min="0" value="0" name="quantity2" onchange="hitungTotal()" required />
            <input type="text" class="hidden" name="name" id="hargaAnak" value="5000"onchange="hitungTotal()"  />
            <input type="text" class="hidden" name="jenis_tiket2" value="Anak-Anak"  />
            <h2>Pembayaran</h2>
            <p>Metode Pembayaran</p>
            <select class="inputbox" name="metode_pembayaran" id="card_type" required>
              <option value="">--Pilih Metode Pembayaran--</option>
              <option value="Qris">Scan Code Qris</option>
              <option value="Bayar Di Loket">Bayar Di Loket</option>
            </select>
            <p>Bukti Pembayaran</p>
            <input type="file" id="gambar" class="inputbox" name="bukti_pembayaran" />
            <p style="color: #4f4d4d; font-size: 15px;"><i class="fa fa-info-circle" aria-hidden="true"></i> Jika bayar di loket, anda tidak perlu mengupload bukti pembayaran</p>
          <h2>Total</h2>
          <table>
            <tbody>
              <tr>
                <td>Dewasa</td>
                <td align="right">Rp. <input type="text" name="sub_total1" id="subtotalDewasa" style="border: none; width: 100px; color: #b4b4b4;" value="0" onchange="hitungTotal()" readonly></td>
              </tr>
              </tr>
              <tr>
                <td>Anak-Anak</td>
                <td align="right">Rp. <input type="text" name="sub_total2" id="subtotalAnak" style="border: none; width: 100px; color: #b4b4b4;" value="0" onchange="hitungTotal()" readonly></td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <td>Total</td>
                <td align="right">Rp. <input type="text" name="totaltiket" id="total" style="border: none; width: 100px; color: #b4b4b4;" value="0" onchange="hitungTotal()" readonly></td>
              </tr>
            </tfoot>
          </table>
            <p></p>
            <input type="text" id="token" class="hidden g-recaptcha" type="submit"
        data-sitekey="6LcUfDsoAAAAAKWDZQoulxVqCHCHc50yX1Akzij2" 
        data-callback='onSubmit' 
        data-action='submit' value="6LcUfDsoAAAAAKWDZQoulxVqCHCHc50yX1Akzij2">
        <div id="konfirmasi" class="popup">
          <div class="popup-content">
              <span class="close" onclick="tutupKonfirmasi()">&times;</span>
              <h2>Apakah anda yakin ingin memesan tiket sesuai dengan yang anda pesan?</h2>
               <p style="color: #4f4d4d; font-size: 15px;"><i class="fa fa-info-circle" aria-hidden="true"></i> jika anda tidak mengupload bukti pembayaran menggunakan Qris, maka sistem kami mendeteksi sebagai bayar di loket</p>
              <button type="submit" class="button">Iya</button>
              <button onclick="tutupKonfirmasi()" class="button">Tidak</button>
              </div>
          </div>
          </form>
          <button onclick="tampilkanPopup()" class="button"><i class="fa fa-ticket" aria-hidden="true"></i> Buat Nota Pembayaran</button>
          <div id="popup" class="popup">
          <div class="popup-content">
            <span class="close" onclick="tutupPopup()">&times;</span>
            <img src="../../../public/img/logo_pemandian_transparant.png" alt="..."
            style="width: 300px; height: 100px; display: block; margin-left: auto; margin-right: auto;"/>
            <h2 style="text-align: center;">Nota Pembayaran</h2>
            <p style="text-align: center;">Atas Nama <?php echo $_SESSION['nama'] ?></p>
            <p style="margin-top: 20px;">No. Nota Pembayaran: <?php echo $maksimalid ?></p>
            <p>Dewasa : <span id="notaDewasa"></span></p>
            <p>Anak-Anak : <span id="notaAnak"></span></p>
            <p>Total : <span id="notaTotal"></span></p>
            <p>Metode Pembayaran : <span id="metodePembayaran"></span></p>
            <div id="qrcode-container" class="hidden" style="text-align: center;">
              <p style="color: #4f4d4d; font-size: 15px;"><i class="fa fa-info-circle" aria-hidden="true"></i> Scan code QR dibawah ini untuk melakukan pembayaran lalu unggah bukti pembayaran</p>
              <a href="id.qr-code-generator.com/" border="0" style="cursor:default" rel="nofollow"></a><img src="https://chart.googleapis.com/chart?cht=qr&chl=Anda%20sudah%20melakukan%20pembayaran!&chs=180x180&choe=UTF-8&chld=L|2">
            </div>
            <!-- <p style="color: #4f4d4d; font-size: 15px;"><i class="fa fa-info-circle" aria-hidden="true"></i> Harap untuk melakukan Screenshot pada nota pembayaran sebelum memesan tiket</p> -->
            <!-- Tambahan informasi atau elemen nota pembayaran lainnya bisa ditambahkan di sini -->
            <button onclick="tampilkanKonfirmasi()" class="button">Pesan Sekarang</button>
          </div>
        </div>
        </div>
      </div>
    </div>

  <script src='https://www.google.com/recaptcha/api.js?render=6LcUfDsoAAAAAKWDZQoulxVqCHCHc50yX1Akzij2'></script>
  <!-- <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script> -->

  <script>
  function onSubmit(token) {
        console.log('onSubmit called'); 
        document.getElementById("token").value = token;
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
//function showSweetAlert() {
//var jenis_tiket = document.getElementById("jenis_tiket");
//   var anak = document.getElementById("anak");
//   var subtotalAnak = document.getElementById("subtotalAnak");
//   var remaja = document.getElementById("remaja");
//   var subtotalRemaja = document.getElementById("subtotalRemaja");
//   var dewasa = document.getElementById("dewasa");
//   var subtotalDewasa = document.getElementById("subtotalDewasa");
//   var metode_pembayaran = document.getElementById("metode_pembayaran");
//     Swal.fire({
//               title: Apakah kamu yakin?,
//               text: "silahkan untuk cek pesanan anda apakah sudah benar atau tidak.",
//               icon: 'warning',
//               showCancelButton: true,
//               confirmButtonColor: '#3085d6',
//               cancelButtonColor: '#d33',
//               cancelButtonText: 'Batal',
//               confirmButtonText: 'Yakin'
//             }).then((result) => {
//               if (result.isConfirmed) {
//                 $.ajax({
//                 url: pesan.php,
//                 method: "POST",
//                 data: { jenis_tiket: jenis_tiket,
//                         quantity: [anak, remaja, dewasa],
//                         sub_total: [subtotalAnak, subtotalRemaja, subtotalDewasa],
//                         metode_pembayaran: metode_pembayaran
//                       },
//                 success: function(response) {
//                     console.log("Data berhasil dikirim.");
//                     // link.closest("tr").remove();

//                     location.href = "index.php?success=1&message=Pesanan Anda Berhasil Dikirim";
//                 },
//                 error: function(error) {
//                     console.error("Terjadi kesalahan: " + error);
//                     location.href = "pelanggan.php?error=1&message=Terjadi Kesalahan S  istem";
//                 }
//              });
                
//               }
//             });
//   }
function tampilkanPopup() {
    let notaDewasa = document.getElementById("subtotalDewasa").value;
    let notaAnak = document.getElementById("subtotalAnak").value;
    let total = document.getElementById("total").value;
    let metodePembayaran = document.getElementById("card_type").value;

    document.getElementById("notaDewasa").textContent = "Rp. " + notaDewasa;
    document.getElementById("notaAnak").textContent = "Rp. " + notaAnak;
    document.getElementById("notaTotal").textContent = "Rp. " + total;
    document.getElementById("metodePembayaran").textContent = metodePembayaran;

    // Check if the selected payment method is "Qris"
    if (metodePembayaran === "Qris") {

      // Show QR code container
      document.getElementById("qrcode-container").classList.remove("hidden");
    } else {
      // Hide QR code container
      document.getElementById("qrcode-container").classList.add("hidden");
    }

    // Show the existing popup
    document.getElementById("popup").style.display = "block";
  }

  // // Function to generate QR code
  // function generateQRCode() {
  //   let qrcode = new QRCode(document.getElementById("qrcode-container"), {
  //     text: "Your_QR_Code_Data_Here", // Replace with your actual QR code data
  //     width: 128,
  //     height: 128,
  //   });
  // }

  function tutupPopup() {
    // Hide the QR code container
    document.getElementById("qrcode-container").classList.add("hidden");

    // Hide the existing popup
    document.getElementById("popup").style.display = "none";
  }

  function tampilkanKonfirmasi() {
    document.getElementById("popup").style.display = "none";
    document.getElementById("konfirmasi").style.display = "block";
  }
  function tutupKonfirmasi() {
    document.getElementById("konfirmasi").style.display = "none";
  }

  </script>  
  <script src='https://www.google.com/recaptcha/api.js?render=6LcUfDsoAAAAAKWDZQoulxVqCHCHc50yX1Akzij2'></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../../../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  </body>

  </html>


