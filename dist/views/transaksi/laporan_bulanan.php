<?php
session_start();

if (isset($_SESSION['level']) && ($_SESSION['level'] == '1' || $_SESSION['level'] == '2')) {
    // Pengguna dengan level 1 atau 2 diizinkan mengakses dashboard.php
} else {
    header('Location: ../index.php');
    exit();
}

require '../../app/config.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'monthly';
$dateInput = isset($_GET['dateInput']) ? $_GET['dateInput'] : date('Y-m');

$startDate = $endDate = $dateInput;

// Query untuk mengambil data Dewasa
$sql = "SELECT jenis_tiket, SUM(quantity) AS total_quantity, DATE_FORMAT(transaksi.tgl_pemesanan, '%Y-%m') AS month_year
        FROM detail_transaksi 
        INNER JOIN transaksi ON detail_transaksi.id_transaksi = transaksi.id_transaksi
        WHERE DATE_FORMAT(transaksi.tgl_pemesanan, '%Y-%m') = '$startDate'
        GROUP BY jenis_tiket, month_year";

$result = $conn->query($sql);

$chartData = array();

// Inisialisasi data default
$labels = ['Dewasa', 'Anak-Anak'];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Create a new array for each jenis_tiket
        $chartData[] = [
            'jenis_tiket' => $row['jenis_tiket'],
            'total_quantity' => $row['total_quantity'],
            'month_year' => $row['month_year'],
        ];  
        $labels_json = json_encode($labels);
        $chartData_json = json_encode($chartData);      
    }
}
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
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="../../../public/assets/css/main/app.css">
    <link rel="stylesheet" href="../../../public/assets/css/main/app-dark.css">
    <link rel="shortcut icon" href="../../../public/assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="../../../public/assets/images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="../../../public/css/loader.css">
    
<link rel="stylesheet" href="../../../public/assets/css/shared/iconly.css">
<link rel="stylesheet" href="../../../public/assets/extensions/sweetalert2/sweetalert2.min.css">
</head>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
    <div class="sidebar-header position-relative">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo">
                <a href="index.html"><img src="../../../public/img/logo_pemandian_transparant.png" alt="Logo" srcset=""></a>
            </div>
            <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2" opacity=".3"></path><g transform="translate(-210 -1)"><path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path><circle cx="220.5" cy="11.5" r="4"></circle><path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path></g></g></svg>
                <div class="form-check form-switch fs-6">
                    <input class="form-check-input  me-0" type="checkbox" id="toggle-dark" >
                    <label class="form-check-label" ></label>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z"></path></svg>
            </div>
            <div class="sidebar-toggler  x">
                <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
            </div>
        </div>
    </div>
    <div class="sidebar-menu">
        <ul class="menu">
            <li
                class="sidebar-item">
                <a href="../index.php" class='sidebar-link'>
                    <i class="fa fa-desktop"></i>
                    <span>Halaman utama</span>
                </a>
            </li>
            <li class="sidebar-title">Menu</li>
            <li
                class="sidebar-item">
                <a href="../dashboard/dashboard.php" class='sidebar-link'>
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li
            class="sidebar-item has-sub">
            <a href="#" class='sidebar-link'>
                <i class="fa fa-ticket" aria-hidden="true"></i>
                <span>Tiket</span>
            </a>
            <ul class="submenu">
                <li class="submenu-item active">
                    <a href="transaksi.php">Transaksi</a>
                </li>
            </ul>
        </li>
        <li
            class="sidebar-item has-sub">
            <a href="#" class='sidebar-link'>
                <i class="fa fa-comment" aria-hidden="true"></i>
                <span>Ulasan</span>
            </a>
            <ul class="submenu">
                <li class="submenu-item">
                    <a href="../ulasan/ulasan.php">kritik & saran</a>
                </li>
            </ul>
        </ul>
        <ul class="menu">
            <li class="sidebar-title">Manage User</li>
            <li
                class="sidebar-item">
                <a href="../user/user.php" class='sidebar-link'>
                    <i class="fa fa-user"></i>
                    <span>user</span>
                </a>
            </li>
        </ul>
        <ul class="menu">
            <li class="sidebar-title">Authentication</li>
            <li 
                class="sidebar-item">
                <a href="index.html" class='sidebar-link'>
                    <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                    <span><?= $_SESSION['username'] ?></span>
                </a>
            </li>
            <!-- <li 
                class="sidebar-item">
                <a href="index.html" class='sidebar-link'>
                    <i class="fa fa-cogs" aria-hidden="true"></i>
                    <span>Pengaturan</span>
                </a>
            </li> -->
            <li 
                class="sidebar-item">
                <a href="../logout.php" class='sidebar-link'>
                    <i class="bi bi-door-open"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            
<div class="page-heading">
    <h3>Tiket - Laporan Bulanan</h3>
</div>
<div class="page-content">
    <section class="row">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tabel Laporan Bulanan</h4>
                    </div>
                    <div class="card-content">
                    <div class="card-body">
                        <a href="transaksi.php" class="btn btn-danger">Kembali</a>
                        <a href="tambah.php" class="btn icon icon-left btn-primary">+ tambah data</a>
                        <button onclick="printTable('dataTable')" class="btn btn-primary"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                        <form method="get" action="" style="margin-top: 20px;">
                            <label for="dateInput">Pilih Bulan:</label>
                            <input type="month" id="dateInput" name="dateInput" value="<?= $dateInput ?>" class="form-control">
                            <button type="submit" class="btn btn-primary mt-2">Filter</button>
                        </form>
                    </div>

                        <!-- Table with no outer spacing -->
                        <div class="table-responsive">
                            <canvas id="myChart" width="100px" height="100px"></canvas>
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
    <script src="../../../public/js/exportToPDF.js"></script>
    <script src="../../../public/js/print.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>


<!-- Need: Apexcharts -->
<script src="../../../public/assets/extensions/apexcharts/apexcharts.min.js"></script>
<script src="../../../public/assets/js/pages/dashboard.js"></script>
<script src="../../../public/assets/extensions/sweetalert2/sweetalert2.min.js"></script>>
<script src="../../../public/assets/js/pages/sweetalert2.js"></script>>
<script src="../../../public/js/loader.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.min.js"></script>
<script src="../../../public/js/sweetalert.js"></script>
<script>
// Convert PHP data to JavaScript
const labels = <?php echo json_encode($labels); ?>;
const chartData = <?php echo json_encode($chartData); ?>;

// Urutkan dataset dari kiri ke kanan
chartData.reverse();

// Filter data untuk memisahkan Dewasa dan Anak-Anak
const filteredData = chartData.reduce((acc, data) => {
  if (!acc[data.month_year]) {
    acc[data.month_year] = [];
  }
  acc[data.month_year].push(data);
  return acc;
}, {});

// Atur format dataset
const datasets = Object.values(filteredData).map((data, index) => {
  const dataset = {
    label: 'data bulan ' + data[0].month_year,
    data: data.map(item => item.total_quantity),
    borderColor: index % 2 === 0 ? 'red' : 'blue',
    backgroundColor: index % 2 === 0 ? 'rgba(255, 0, 0, 0.5)' : 'rgba(0, 0, 255, 0.5)',
  };
  return dataset;
});

const data = {
  labels: labels,
  datasets: datasets,
};

// Get the canvas element
const ctx = document.getElementById('myChart').getContext('2d');

// Create the chart using the canvas element
const myChart = new Chart(ctx, {
  type: 'bar',
  data: data,
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});
</script>




</body>

</html>
