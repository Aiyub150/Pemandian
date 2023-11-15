<?php
session_start(); // Pastikan Anda memulai sesi sebelum mengakses $_SESSION

require '../../app/config.php';

if(isset($_SESSION['level']) && ($_SESSION['level'] == '1' || $_SESSION['level'] == '2')){

// Pengguna dengan level 1 atau 2 diizinkan mengakses dashboard.php

} else {

header('Location: ../index.php'); exit();

}

$labels = ['januari','februari','maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];

$totalQuery = "SELECT SUM(total_harga) as total_jumlah FROM transaksi";
$hasil = $conn->query($totalQuery);

if ($hasil && $hasil->num_rows > 0) {
    $row = $hasil->fetch_assoc();
    $p = $row['total_jumlah'];
} else {
    $p = 0; // Atur ke nol jika tidak ada hasil atau terjadi kesalahan
}

$user = "SELECT COUNT(*) as jumlah_user FROM users";
$users = $conn->query($user);

if ($users && $users->num_rows > 0) {
    $row = $users->fetch_assoc();
    $jumlah_user = $row['jumlah_user'];
} else {
    $jumlah_user = 0; // Atur ke nol jika tidak ada hasil atau terjadi kesalahan
}

for ($bulan = 1; $bulan <= 12; $bulan++) {
    $sql = "SELECT * FROM transaksi WHERE MONTH(tgl_pemesanan) = $bulan";
    $result = $conn->query($sql);
    $data[$bulan] = $result->num_rows; // Simpan jumlah transaksi untuk bulan ini
}

for ($jenis_tiket = 1; $jenis_tiket <= 2; $jenis_tiket++) {
    if ($jenis_tiket == 1) {
        $jenis_tiket_label = "Dewasa";
    } else {
        $jenis_tiket_label = "Anak-Anak";
    }

    $sql = "SELECT quantity FROM detail_transaksi WHERE jenis_tiket = '$jenis_tiket_label'";
    $result = $conn->query($sql);

    // Jika query berhasil dijalankan
    if ($result) {
        $total_quantity = 0;

        while ($row = $result->fetch_assoc()) {
            $total_quantity += $row['quantity'];
        }

        $tiket[$jenis_tiket] = $total_quantity;
    }
}

$dewasa = "SELECT SUM(sub_total) as total_sub_total FROM detail_transaksi WHERE jenis_tiket='Dewasa'";
$hasild = $conn->query($dewasa);

$total_sub_total_dewasa = 0;

if ($hasild && $hasild->num_rows > 0) {
    while ($row = $hasild->fetch_assoc()) {
        $total_sub_total_dewasa += $row['total_sub_total'];
    }
}

$anak = "SELECT SUM(sub_total) as total_sub_total FROM detail_transaksi WHERE jenis_tiket='Anak-Anak'";
$hasila = $conn->query($anak);

$total_sub_total_anak = 0;

if ($hasila && $hasila->num_rows > 0) {
    while ($row = $hasila->fetch_assoc()) {
        $total_sub_total_anak += $row['total_sub_total'];
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pemandian</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../../public/assets/css/main/app.css">
    <link rel="stylesheet" href="../../../public/assets/css/main/app-dark.css">
    <link rel="shortcut icon" href="../../../public/assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="../../../public/assets/images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="../../../public/css/loader.css">
    <link rel="stylesheet" href="../../../public/assets/css/shared/iconly.css">

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
                class="sidebar-item active ">
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
                <li class="submenu-item">
                    <a href="../transaksi/transaksi.php">Transaksi</a>
                </li>
                <li class="submenu-item">
                    <a href="../tiket/tiket.php">Tiket</a>
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
        <?php
        if(isset($_SESSION['level']) && $_SESSION['level'] == '1') {
            echo "
        <ul class='menu'>
            <li class='sidebar-title'>Manage User</li>
            <li
                class='sidebar-item'>
                <a href='../user/user.php' class='sidebar-link'>
                    <i class='fa fa-user'></i>
                    <span>user</span>
                </a>
            </li>
        </ul>";
        }
        ?>
        <ul class="menu">
            <li class="sidebar-title">Authentication</li>
            <li 
                class="sidebar-item">
                <a href="index.html" class='sidebar-link'>
                    <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                    <span><?= $_SESSION['username'] ?></span>
                </a>
            </li>
            <li 
                class="sidebar-item">
                <a href="index.html" class='sidebar-link'>
                    <i class="fa fa-cogs" aria-hidden="true"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
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
    <h3>Dashboard</h3>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-9">
            <div class="row">
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon purple mb-2">
                                    <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Total Pengguna</h6>
                                    <h6 class="font-extrabold mb-0"><?php echo $jumlah_user ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon blue mb-2">
                                    <i class="fa fa-ticket" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Penjualan Tiket Dewasa</h6>
                                    <h6 class="font-extrabold mb-0">Rp. <?php echo $total_sub_total_dewasa ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon red mb-2">
                                    <i class="fa fa-ticket" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Penjualan Tiket Anak</h6>
                                    <h6 class="font-extrabold mb-0">Rp. <?php echo $total_sub_total_anak ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon green mb-2">
                                        <i class="fa fa-money" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Total Penjualan</h6>
                                    <h6 class="font-extrabold mb-0">Rp. <?php echo $p ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Penjualan Tiket Perbulan</h4>
                        </div>
                        <div class="card-body">
                        <canvas id="Chart" width="400" height="140"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3">
            <div class="card">
                <div class="card-body py-4 px-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl">
                            <img src="../../../public/assets/images/faces/1.jpg" alt="Face 1">
                        </div>
                        <div class="ms-3 name">
                            <h5 class="font-bold"><?= $_SESSION['username'] ?></h5>
                            <h6 class="text-muted mb-0">Level <?= $_SESSION['level'] ?></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Data Jenis Tiket </h4>
                </div>
                <div class="card-body">
                <canvas id="pie" width="200" height="240"></canvas>
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
<script src="../../../public/js/loader.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>

<script>
// Ambil data dari PHP dan atur ke dalam array
let labels = <?php echo json_encode($labels); ?>;
let value = <?php echo json_encode($data); ?>;
let values = Object.values(value);

// Inisialisasi Chart.js
let ctx = document.getElementById('Chart').getContext('2d');
let myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Data Transaksi Perbulan',
            data: values,
            backgroundColor: 'rgba(64, 131, 201, 0.2)',
            borderColor: 'rgba(6, 76, 161, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});


let tikets = <?php echo json_encode($tiket); ?>;
let t = Object.values(tikets);

let ctk = document.getElementById('pie').getContext('2d');
let myPie = new Chart(ctk, {
    type: 'pie',
    data: {
        labels: ['Dewasa','Anak-Anak'],
        datasets: [{
            label: 'Rata-Rata Usia Pengunjung',
            data: t,
            backgroundColor: [
                'rgba(255, 99, 132)',
                'rgba(54, 162, 235)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)'
            ],  // Tambahkan koma di sini
            borderWidth: 1
        }]
    },
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
