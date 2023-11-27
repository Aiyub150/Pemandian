<?php

session_start(); // Pastikan Anda memulai sesi sebelum mengakses $_SESSION

if(isset($_SESSION['level']) && ($_SESSION['level'] == '1' || $_SESSION['level'] == '2')){

// Pengguna dengan level 1 atau 2 diizinkan mengakses dashboard.php

} else {

header('Location: ../index.php'); exit();

}

require '../../app/config.php';

$searchInput = isset($_GET['id_transaksi']) ? $_GET['id_transaksi'] : '';

// Ubah query untuk mencari data berdasarkan id_transaksi
if(!empty($searchInput)) {
    $sql = "SELECT * FROM transaksi INNER JOIN users ON transaksi.id_user=users.id_user WHERE transaksi.id_transaksi = $searchInput ORDER BY tgl_pemesanan DESC";
} else {
    $sql = "SELECT * FROM transaksi INNER JOIN users ON transaksi.id_user=users.id_user ORDER BY tgl_pemesanan DESC";
}

$result = $conn->query($sql);
?>
<style>
    .hidden{
        display: none;
    }
</style>
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
    <link rel="stylesheet" href="../../../public/css/loader.css">
    
<link rel="stylesheet" href="../../../public/assets/css/shared/iconly.css">
<link rel="stylesheet" href="../../../public/assets/extensions/sweetalert2/sweetalert2.min.css">
</head>

<body>
    
            
<div class="page-heading">
    <h3 style="text-align: center; margin: 20px;">Halo Staff <?php echo $_SESSION['nama'] ?>, Selamat Datang Di Transaksi Tiket</h3>
</div>
    <section class="row">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tabel Transaksi</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            
                            <a href="staf_add.php" class="btn icon icon-left btn-primary">+ tambah data</a>
                            <button onclick="printTable('dataTable')" class="btn btn-primary"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                            <a href="../logout.php" class="btn icon icon-left btn-danger" style="float: right;"><i class="bi bi-door-open"></i> Logout</a>
                            <input class="form-control" type="number" style="margin-top: 10px;" id="searchInput" placeholder="Cari Data Berdasarkan Nomor Transaksi" value="<?php echo $searchInput; ?>">
                            <button onclick="searchData()" class="btn btn-primary" style="margin: 10px;">Cari</button>
                            <div id="your-qr-result" class="hidden"></div>
                            <h1>Scan Dengan Barcode</h1>
                            <div style="display: flex; justify-content: center;">
                                <div id="my-qr-reader" style="width: 500px;">
                                
                                </div>
                            </div>
                        </div>

                        <!-- Table with no outer spacing -->
                        <div class="table-responsive">
                            <table class="table mb-0 table-lg" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>ID TRANSAKSI</th>
                                        <th>NAMA</th>
                                        <th>TGL PEMESANAN</th>
   										<th>TOTAL</th>
                                        <th>METODE PEMBAYARAN</th>
                                        <th>BUKTI PEMBAYARAN</th>
                                        <th>STATUS</th>
                                        <th colspan="3" style="text-align: center;">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row["id_transaksi"] . "</td>";
                                            echo "<td>" . $row["nama"] . "</td>";
                                            echo "<td>" . $row["tgl_pemesanan"] . "</td>";
                                            echo "<td>" . $row["total_harga"] . "</td>";
                                            echo "<td>" . $row["metode_pembayaran"] . "</td>";
                                            echo "<td><img style='width: 200px; height: 200px;' src='../../app/payment/" . $row["bukti_pembayaran"] . "' alt=' Bayar Di Loket    '></td>";
                                            echo "<td>"; 
                                            $status = $row["status"];
                                            if($status == 'done'){
                                                echo "<i class='fa fa-check-square' aria-hidden='true' style='color: green;'></i> Sudah Dibayar";
                                            } else {
                                                echo "<i class='fa fa-window-close' aria-hidden='true' style='color: red;'></i> Belum Dibayar";
                                            }
                                            echo "</td>";
                                            echo '<td><a class="btn icon btn-primary" href="staf_update.php?id=' . $row["id_transaksi"] . '"><i class="bi bi-pencil"></i></a></td>';
                                            echo '<td><a class="btn icon btn-danger" href="staf_delete.php?id=' . $row["id_transaksi"] . '"><i class="fa fa-trash"></i></a></td>';
                                            echo '<td><a class="btn icon btn-warning" href="../detail_transaksi/detail_transaksi.php?id=' . $row["id_transaksi"] . '"><i class="fa fa-list"></i></a></td>';
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' style='text-align: center;'>Tidak ada data.</td></tr>";
                                    }
                                ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>        
    </section>
</div>
<div class="loader-container" id="loader-container">
                <div class="spinner-box">
                <div class="blue-orbit leo">
                </div>

                <div class="green-orbit leo">
                </div>
                
                <div class="red-orbit leo">
                </div>
                
                <div class="white-orbit w1 leo">
                </div><div class="white-orbit w2 leo">
                </div><div class="white-orbit w3 leo">
                </div>
                </div>
        </div>
            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2023 © Pemandian</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="../../../public/assets/js/bootstrap.js"></script>
    <script src="../../../public/assets/js/app.js"></script>
    <script src="../../../public/js/exportToExcel.js"></script>
    <script src="../../../public/j  s/exportToPDF.js"></script>
    <script src="../../../public/js/print.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>


<!-- Need: Apexcharts -->
<script src="../../../public/assets/extensions/apexcharts/apexcharts.min.js"></script>
<script src="../../../public/assets/js/pages/dashboard.js"></script>
<script src="../../../public/assets/extensions/sweetalert2/sweetalert2.min.js"></script>>
<script src="../../../public/assets/js/pages/sweetalert2.js"></script>>
<script src="../../../public/js/loader.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../../public/js/sweetalert.js"></script>
<script>
function applyFilter(filter) {
    window.location.href = 'laporan.php?filter=' + filter;
}
function searchData() {
    var searchInput = document.getElementById('searchInput').value;
    window.location.href = 'staf.php?id_transaksi=' + searchInput;
}
</script>
<script>
    function domReady(fn) {
        if (document.readyState === "complete" || document.readyState === "interactive") {
            setTimeout(fn, 1);
        } else {
            document.addEventListener("DOMContentLoaded", fn);
        }
    }

    domReady(function () {
        var myqr = document.getElementById('your-qr-result');
        var lastResult, countResults = 0;

        function onScanSuccess(decodeText, decodeResult) {
            if (decodeText !== lastResult) {
                ++countResults;
                lastResult = decodeText;

                // Set nilai hasil pemindaian ke dalam input pencarian
                document.getElementById('searchInput').value = decodeText;

                // Alert dapat dihilangkan jika tidak diperlukan
                alert("Scanner Barcode Berhasil, Nomor Transaksi : " + decodeText, decodeResult);

                myqr.innerHTML = `you scan ${countResults} : ${decodeText}`;
            }
        }

        var htmlscanner = new Html5QrcodeScanner(
            "my-qr-reader", { fps: 10, qrbox: 250 }
        );

        htmlscanner.render(onScanSuccess);
    });
</script>
</body>

</html>
