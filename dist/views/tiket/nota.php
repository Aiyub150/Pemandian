<?php
require '../../../vendor/autoload.php';
use Picqer\Barcode\BarcodeGeneratorHTML;

session_start(); // Pastikan Anda memulai sesi sebelum mengakses $_SESSION
if(!isset($_SESSION['username'])){
    header('Location: ../login.php');
    exit();
} else {
    require '../../app/config.php';
    $id_user = $_SESSION['id_user'];
    $nama = $_SESSION['nama'];
    $id_transaksi = $_SESSION['id_transaksi'];
    $sql = "SELECT * FROM transaksi WHERE id_user=? AND id_transaksi=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $id_user, $id_transaksi);
$stmt->execute();
$result = $stmt->get_result();

$dewasaQuery = "SELECT * FROM detail_transaksi INNER JOIN transaksi ON detail_transaksi.id_transaksi=transaksi.id_transaksi WHERE detail_transaksi.id_transaksi='$id_transaksi' AND jenis_tiket='Dewasa'";
$anakQuery = "SELECT * FROM detail_transaksi INNER JOIN transaksi ON detail_transaksi.id_transaksi=transaksi.id_transaksi WHERE detail_transaksi.id_transaksi='$id_transaksi' AND jenis_tiket='Anak-Anak'";

$dewasaResult = $conn->query($dewasaQuery);
$anakResult = $conn->query($anakQuery);

$d = $dewasaResult->fetch_assoc();
$a = $anakResult->fetch_assoc();

// Create a barcode generator instance
$generator = new BarcodeGeneratorHTML();

// Generate the barcode HTML with the transaction ID
$barcodeHTML = $generator->getBarcode($id_transaksi, $generator::TYPE_CODE_128);
}
?>
 <style>
        /* Add the styles for the barcode container */
        .barcode-container {
            width: 200px; /* Set your desired width */
            height: auto; /* Adjust the height as needed */
            margin: 0 auto; /* Center the barcode horizontally */
        }

        .barcode-container img {
            width: 100%; /* Make sure the barcode image fills the container */
            height: auto; /* Maintain aspect ratio */
        }
        @media print {
            body {
                font-size: 12pt; /* Set your desired font size for print */
            }

            .barcode-container {
                /* Adjust styles for printing, e.g., make it full-width */
                width: 100%;
                margin: 0; /* Remove margin for full-width */
            }
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
        <div class="rightside" >
            <div id="Nota">
            <img src="../../../public/img/logo_pemandian_transparant.png" alt="..." style="margin-left: auto; margin-right: auto;"/>

            <h2 style="margin-top: 20px; text-align: center;">Nota Pembayaran</h2>
            <div class="barcode-container"><?php echo $barcodeHTML; ?></div>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<p>Nama : " . $nama . "</p>";
                echo "<p>Tanggal Transaksi : " . $row['tgl_pemesanan'] . "</p>";
                echo "<p>No. Pembayaran : " . $row['id_transaksi'] . "</p>";
        
                // Display Dewasa quantity
                // $dewasaQuantity = isset($d['quantity']) ? $d['quantity'] : 0;
                echo "<p>Dewasa : " . $d['quantity'] . "</p>";
        
                // Display Anak quantity
                // $anakQuantity = isset($a['quantity']) ? $a['quantity'] : 0;
                echo "<p>Anak : " . $a['quantity'] . "</p>";
        
                echo "<p>Metode Pembayaran : " . $row['metode_pembayaran'] . "</p>";
                echo "<p>Total : " . $row['total_harga'] . "</p>";
            }
        }

        ?>
        
        </div>
        <p style="color: #4f4d4d; font-size: 15px;"><i class="fa fa-info-circle" aria-hidden="true"></i> Harap untuk melakukan Screenshot pada nota pembayaran sebelum memesan tiket atau memilih opsi cetak pembayaran</p>
        <button onclick="printNota('Nota')" class="button"><i class="fa fa-print" aria-hidden="true"></i> Cetak Nota Pembayaran</button>
        <a href="../index.php" style="margin-top: 50px; text-decoration: none;"> <button class="button">selesai</button></a>
      </div>
    </div>

  <script src='https://www.google.com/recaptcha/api.js?render=6LcUfDsoAAAAAKWDZQoulxVqCHCHc50yX1Akzij2'></script>
  <!-- <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script> -->

  <script>
    function printNota(Nota) {
        var notaElement = document.getElementById(Nota);
        var printContents = notaElement.outerHTML;
        var originalContents = document.body.innerHTML;

        // Menetapkan lebar menjadi 100% dari lebar window
        notaElement.style.width = "100%";

        document.body.innerHTML = printContents;

        // Menggunakan fungsi print
        window.print();

        // Mengembalikan konten asli
        document.body.innerHTML = originalContents;
    }
</script>    
  <script src='https://www.google.com/recaptcha/api.js?render=6LcUfDsoAAAAAKWDZQoulxVqCHCHc50yX1Akzij2'></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../../../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  </body>

  </html>