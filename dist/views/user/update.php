<?php

session_start(); // Pastikan Anda memulai sesi sebelum mengakses $_SESSION

if(isset($_SESSION['level']) && ($_SESSION['level'] == '1' || $_SESSION['level'] == '2')){

// Pengguna dengan level 1 atau 2 diizinkan mengakses dashboard.php

} else {

header('Location: ../index.php'); exit();

}

require '../../app/config.php';

$id_user = ""; // Inisialisasi nilai awal untuk id_user

if (isset($_GET["id"])) {
    $id_user = $_GET["id"];
    $sql = "SELECT * FROM users WHERE id_user='$id_user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $id_user = $data["id_user"];
        $username = $data["username"];
        $password = $data["password"];
        $email = $data["email"];
        $no_telepon = $data["no_telepon"];
        $level = $data["level"];        
    } else {
        echo "Data tidak ditemukan.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_user = $_POST["id_user"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $no_telepon = $_POST["no_telepon"];
    $level = $_POST["level"];

    // Cek apakah data diubah oleh user atau tidak
    $sql_select = "SELECT * FROM users WHERE id_user='$id_user'";
    $result_select = $conn->query($sql_select);
    
    if ($result_select->num_rows > 0) {
        $data = $result_select->fetch_assoc();
        
        // Mengambil data yang diubah oleh user atau menggunakan data sebelumnya
        $id_user = ($id_user !== '') ? $id_user : $data["id_user"];
        $username = ($username !== '') ? $username : $data["username"];
        $password = ($password !== '') ? $password : $data["password"];
        $email = ($email !== '') ? $email : $data["email"];
        $no_telepon = ($no_telepon !== '') ? $no_telepon : $data["no_telepon"];
        $level = ($level !== '') ? $level : $data["level"];
        
        // Update data di database
        $sql_update = "UPDATE users SET id_user='$id_user', username='$username', password='$password', email='$email', no_telepon='$no_telepon', level='$level' WHERE id_user='$id_user'";
        
        if ($conn->query($sql_update) === true) {
            header("Location: user.php"); // Ganti transaksi.php dengan halaman yang sesuai
            exit;
        } else {
            echo "Error: " . $sql_update . "<br>" . $conn->error;
        }
    } else {
        echo "Data tidak ditemukan.";
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
            <a href="../dashboard/dashboard.php" class='sidebar-link'>
                <i class="fa fa-ticket" aria-hidden="true"></i>
                <span>Tiket</span>
            </a>
            <ul class="submenu">
                <li class="submenu-item">
                    <a href="../transaksi/transaksi.php">Transaksi</a>
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
                class="sidebar-item active">
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
                              <label for="email-horizontal">ID USER</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <input type="text" id="email-horizontal" class="form-control" name="id_user" placeholder="id user" value="<?php echo $id_user; ?>">
                            </div>
                            <div class="col-md-4">
                              <label for="contact-info-horizontal">USERNAME</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <input type="text" id="contact-info-horizontal" class="form-control" name="username" placeholder="username" value="<?php echo $username; ?>">
                            </div>
                            <div class="col-md-4">
                              <label for="contact-info-horizontal">PASSWORD</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <input type="password" id="password" class="form-control" name="password" placeholder="password"  value="<?php echo $password; ?> "><a class='btn' id='toggleButton' title='Lihat Password' style="margin-top: 5px;"><i id='eyeIcon' class='fa fa-eye'></i> Lihat Password</a>
                            </div>
                            <div class="col-md-4">
                              <label for="contact-info-horizontal">EMAIL</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <input type="text" id="contact-info-horizontal" class="form-control" name="email" placeholder="email" value="<?php echo $email; ?>">
                            </div>
                            <div class="col-md-4">
                              <label for="contact-info-horizontal">NO TELEPON</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <input type="text" id="contact-info-horizontal" class="form-control" name="no_telepon" placeholder="no telepon" value="<?php echo $no_telepon; ?>">
                            </div>
                            <div class="col-md-4">
                              <label for="contact-info-horizontal">LEVEL</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select name="level" id="" value="<?php echo $level; ?>" class="form-select">
                                    <option value="0" <?php if ($level == '0') echo 'selected'; ?>>pengguna</option>
                                    <option value="1" <?php if ($level == '1') echo 'selected'; ?>>admin</option>
                                    <option value="2" <?php if ($level == '2') echo 'selected'; ?>>staff</option>

                                </select>
                            </div>
                            
                            <div class="col-sm-12 d-flex justify-content-end">
                              <button type="submit" class="btn btn-primary me-1 mb-1">
                                Update
                              </button>
                              <button type="reset" class="btn btn-light-secondary me-1 mb-1">
                                Reset
                              </button>
                              <button type="button" class="btn btn-danger me-1 mb-1" onclick="location.href='user.php'">
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
        document.getElementById('toggleButton').addEventListener('click', function() {
            var passwordField = document.getElementById('password');
            var eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    </script>
</body>

</html>
