<style>
    header .masthead{
        background: url("../../public/img/background.png");
    }   
</style>
<?php
require '../app/config.php';
session_start();
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
// Ambil data dari formulir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST["username"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $no_telepon = $conn->real_escape_string($_POST["no_telepon"]);
    $ulasan = $conn->real_escape_string($_POST["ulasan"]);
    $tgl_ulasan = date("Y-m-d");

    // Periksa apakah data user sudah ada di tabel user
    $sql_check_user = "SELECT id_user FROM users WHERE username='$username' AND email='$email' AND no_telepon='$no_telepon'";
    $result = $conn->query($sql_check_user);

    if ($result) {
        if ($result->num_rows > 0) {
            // Jika data user sudah ada, ambil ID user
            $row = $result->fetch_assoc();
            $id_user = $row["id_user"];
        } else {
            // Jika data user belum ada, tambahkan ke tabel user
            $sql_insert_user = "INSERT INTO users (username, email, no_telepon) VALUES ('$username', '$email', '$no_telepon')";
            if ($conn->query($sql_insert_user)) {
                $id_user = $conn->insert_id;
            } else {
                echo "Error: " . $sql_insert_user . "<br>" . $conn->error;
            }
        }

        // Tambahkan ulasan ke tabel ulasan
        $sql_insert_ulasan = "INSERT INTO ulasan (id_user, ulasan, tgl_ulasan) VALUES ('$id_user', '$ulasan', '$tgl_ulasan')";
        if ($conn->query($sql_insert_ulasan)) {
            echo "Ulasan berhasil disimpan.";
        } else {
            echo "Error: " . $sql_insert_ulasan . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql_check_user . "<br>" . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Pemandian</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="../../public/img/icon.png" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="../../public/css/styles.css" rel="stylesheet" />
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand" href="#page-top"><img src="../../public/img/logo_pemandian_transparant.png" alt="..." /></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars ms-1"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                        <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                        <li class="nav-item"><a class="nav-link" href="#portfolio">Gallery</a></li>
                        <li class="nav-item"><a class="nav-link" href="#about">Tiket</a></li>
                        <li class="nav-item"><a class="nav-link" href="#team">Team</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                        <?php
                        if (isset($_SESSION['id_user'])){
                            echo "<li class='nav-item'><a class='nav-link' href='logout.php'>Logout</a></li>";
                        } else {
                            echo "<li class='nav-item'><a class='nav-link' href='login.php'>Login</a></li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Masthead-->
        <header class="masthead">
            <div class="container">
                <div class="masthead-heading text-uppercase">Selamat Datang Di Pemandian</div>
                <div class="masthead-subheading">Pengalaman Pemandian yang Tidak Terlupakan</div>
                <a class="btn btn-warning btn-xl text-uppercase" href="tiket/pesan.php">Pesan Sekarang</a>
            </div>
        </header>
        <!-- Services-->
        <section class="page-section" id="services">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">Services</h2>
                    <h3 class="section-subheading text-muted">Layanan Dan Fasilitas Yang Kami Sediakan.</h3>
                </div>
                <div class="row text-center">
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-bath fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3">Kolam Renang</h4>
                        <p class="text-muted">Pemandian kami menyediakan beberapa tingkatan kolam renang untuk kalangan Dewasa, Remaja, maupun Anak-anak dan sudah dipastikan kebersihan kolam renang kami demi kenyamanan anda.</p>
                    </div>
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fa fa-cutlery fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3">Warung</h4>
                        <p class="text-muted">Nikmati keindahan alam pemandian kami sambil menikmati hidangan lezat di warung kami yang menyajikan berbagai makanan lezat untuk memuaskan selera Anda.</p>
                    </div>
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-lock fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3">Keamanan</h4>
                        <p class="text-muted">Kami mengutamakan keamanan dan kenyamanan setiap pengunjung. Di pemandian kami, Anda dapat menikmati pemandangan yang indah dengan tenang dan nyaman.</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- Portfolio Grid-->
        <section class="page-section bg-light" id="portfolio">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">Gallery</h2>
                    <h3 class="section-subheading text-muted">Momen Momen Indah Di Pemandian Kami.</h3>
                </div>
                <div class="row">
                    <!-- <div class="col-lg-4 col-sm-6 mb-4">
                        <div class="portfolio-item">
                            <a class="portfolio-link" data-bs-toggle="modal" href="#portfolioModal1">
                                <div class="portfolio-hover">
                                    <div class="portfolio-hover-content"><i class="fa fa-search-plus fa-3x" aria-hidden="true"></i></div>
                                </div>
                                <img class="img-fluid" src="../../public/img/gambar.png" alt="..." />
                            </a>
                            <div class="portfolio-caption">
                                <div class="portfolio-caption-heading">Threads</div>
                                <div class="portfolio-caption-subheading text-muted">Illustration</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 mb-4">
                        <div class="portfolio-item">
                            <a class="portfolio-link" data-bs-toggle="modal" href="#portfolioModal2">
                                <div class="portfolio-hover">
                                    <div class="portfolio-hover-content"><i class="fa fa-search-plus fa-3x" aria-hidden="true"></i></div>
                                </div>
                                <img class="img-fluid" src="../../public/img/gambar2.png" alt="..." />
                            </a>
                            <div class="portfolio-caption">
                                <div class="portfolio-caption-heading">Explore</div>
                                <div class="portfolio-caption-subheading text-muted">Graphic Design</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 mb-4">
                        <div class="portfolio-item">
                            <a class="portfolio-link" data-bs-toggle="modal" href="#portfolioModal3">
                                <div class="portfolio-hover">
                                    <div class="portfolio-hover-content"><i class="fa fa-search-plus fa-3x" aria-hidden="true"></i></div>
                                </div>
                                <img class="img-fluid" src="../../public/img/gambar3.png" alt="..." />
                            </a>
                            <div class="portfolio-caption">
                                <div class="portfolio-caption-heading">Finish</div>
                                <div class="portfolio-caption-subheading text-muted">Identity</div>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-lg-4 col-sm-6 mb-4 mb-lg-0">
                        <!-- Portfolio item 4-->
                        <div class="portfolio-item">
                            <a class="portfolio-link" data-bs-toggle="modal" href="#portfolioModal4">
                                <div class="portfolio-hover">
                                    <div class="portfolio-hover-content"><i class="fa fa-search-plus fa-3x" aria-hidden="true"></i></div>
                                </div>
                                <img class="img-fluid" src="../../public/img/gambar6.png" alt="..." />
                            </a>
                            <div class="portfolio-caption">
                                <div class="portfolio-caption-heading">Yonif Raider 515</div>
                                <div class="portfolio-caption-subheading text-muted">meriahkan hut tni ke 72 yonif raider 515 kostrad karya bhakti di wisata pemandian patemon</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 mb-4 mb-sm-0">
                        <!-- Portfolio item 5-->
                        <div class="portfolio-item">
                            <a class="portfolio-link" data-bs-toggle="modal" href="#portfolioModal5">
                                <div class="portfolio-hover">
                                    <div class="portfolio-hover-content"><i class="fa fa-search-plus fa-3x" aria-hidden="true"></i></div>
                                </div>
                                <img class="img-fluid" src="../../public/img/gambar7.png" alt="..." />
                            </a>
                            <div class="portfolio-caption">
                                <div class="portfolio-caption-heading">Ir. H. Hendy Siswanto, ST. IPU</div>
                                <div class="portfolio-caption-subheading text-muted">Bupati Jember Ir. H. Hendy Siswanto, ST. IPU Mengunjungi Pemandian Patemon Di Tanggul Jember</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <!-- Portfolio item 6-->
                        <div class="portfolio-item">
                            <a class="portfolio-link" data-bs-toggle="modal" href="#portfolioModal6">
                                <div class="portfolio-hover">
                                    <div class="portfolio-hover-content"><i class="fa fa-search-plus fa-3x" aria-hidden="true"></i></div>
                                </div>
                                <img class="img-fluid" src="../../public/img/gambar9.png" alt="..." />
                            </a>
                            <div class="portfolio-caption">
                                <div class="portfolio-caption-heading">Momen Pemandian Terpadat</div>
                                <div class="portfolio-caption-subheading text-muted">Ribuan pengunjung padati pemandian</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- About-->
        <section class="page-section" id="about">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">Tiket</h2>
                    <h3 class="section-subheading text-muted">Harga tiket di Pemandian.</h3>
                </div>
                <ul class="timeline">
                    <li>
                        <div class="timeline-image"><i class="fa fa-male rounded-circle img-fluid" aria-hidden="true"></i></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4>Dewasa</h4>
                                <h4 class="subheading">Rp. 15.000</h4>
                            </div>
                            <!-- <div class="timeline-body"><p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p></div> -->
                        </div>
                    </li>
                    <li class="timeline-inverted">
                        <div class="timeline-image"><i class="fa fa-female rounded-circle img-fluid" aria-hidden="true"></i></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4>Remaja</h4>
                                <h4 class="subheading">Rp. 10.000</h4>
                            </div>
                            <!-- <div class="timeline-body"><p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p></div> -->
                        </div>
                    </li>
                    <li>
                        <div class="timeline-image"><i class="fa fa-male rounded-circle img-fluid" aria-hidden="true"></i></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4>Anak-anak</h4>
                                <h4 class="subheading">Rp. 5.000</h4>
                            </div>
                            <!-- <div class="timeline-body"><p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p></div> -->
                        </div>
                    </li>
                    <!-- <li class="timeline-inverted">
                        <div class="timeline-image"><img class="rounded-circle img-fluid" src="assets/img/about/4.jpg" alt="..." /></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4>July 2020</h4>
                                <h4 class="subheading">Phase Two Expansion</h4>
                            </div>
                            <div class="timeline-body"><p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p></div>
                        </div>
                    </li> -->
                    <li class="timeline-inverted">
                        <div class="timeline-image">
                            <a href="tiket/pesan.php" style="color: white; text-decoration: none;">
                            <h4>
                                Pesan
                                <br />
                                Sekarang Disini.
                                <br />
                            </h4>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </section>
        <!-- Team-->
        <section class="page-section bg-light" id="team">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">Team Kami</h2>
                    <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>      
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="team-member">
                            <img class="mx-auto rounded-circle" src="../../public/img/anonymous.png" alt="..." />
                            <h4>Parveen Anand</h4>
                            <p class="text-muted">Lead Designer</p>
                            <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Parveen Anand Twitter Profile"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Parveen Anand Facebook Profile"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Parveen Anand LinkedIn Profile"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="team-member">
                            <img class="mx-auto rounded-circle" src="../../public/img/anonymous.png" alt="..." />
                            <h4>Diana Petersen</h4>
                            <p class="text-muted">Lead Marketer</p>
                            <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Diana Petersen Twitter Profile"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Diana Petersen Facebook Profile"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Diana Petersen LinkedIn Profile"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="team-member">
                            <img class="mx-auto rounded-circle" src="../../public/img/anonymous.png" alt="..." />
                            <h4>Larry Parker</h4>
                            <p class="text-muted">Developer Website</p>
                            <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Larry Parker Twitter Profile"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Larry Parker Facebook Profile"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Larry Parker LinkedIn Profile"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 mx-auto text-center"><p class="large text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut eaque, laboriosam veritatis, quos non quis ad perspiciatis, totam corporis ea, alias ut unde.</p></div>
                </div>
            </div>
        </section>
        <!-- Clients-->
        <div class="py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-3 col-sm-6 my-3">
                        <a href="https://www.google.com/maps/place/Pemandian+Patemon/@-8.1486338,113.4590323,17z/data=!4m14!1m7!3m6!1s0x2dd68b71a152bb29:0xcdeff13125ebe860!2sPemandian+Patemon!8m2!3d-8.1486391!4d113.4616072!16s%2Fg%2F11ddx8nc38!3m5!1s0x2dd68b71a152bb29:0xcdeff13125ebe860!8m2!3d-8.1486391!4d113.4616072!16s%2Fg%2F11ddx8nc38?entry=ttu"><img class="img-fluid img-brand d-block mx-auto" src="../../public/img/google_maps.png" alt="..." style="width: 200px; height: 100px;" aria-label="Microsoft Logo" /></a>
                    </div>
                    <div class="col-md-3 col-sm-6 my-3">
                        <a href="https://web.facebook.com/pemandian.patemon/?locale=id_ID&_rdc=1&_rdr"><img class="img-fluid img-brand d-block mx-auto" src="../../public/img/facebook.png" alt="..." aria-label="Google Logo" /></a>
                    </div>
                    <div class="col-md-3 col-sm-6 my-3">
                        <a href="#!"><img class="img-fluid img-brand d-block mx-auto" src="../../public/img/instagram.png" alt="..." aria-label="Facebook Logo" /></a>
                    </div>
                    <div class="col-md-3 col-sm-6 my-3">
                        <a href="#!"><img class="img-fluid img-brand d-block mx-auto" src="../../public/img/youtube.png" alt="..." aria-label="IBM Logo" /></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contact-->
        <section class="page-section" id="contact" method="post">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">Kritik & Saran</h2>
                    <h3 class="section-subheading text-muted">Berikan kritik dan saran sesuai dengan pengalaman anda di pemandian kami.</h3>
                </div>
                <!-- * * * * * * * * * * * * * * *-->
                <!-- * * SB Forms Contact Form * *-->
                <!-- * * * * * * * * * * * * * * *-->
                <!-- This form is pre-integrated with SB Forms.-->
                <!-- To make this form functional, sign up at-->
                <!-- https://startbootstrap.com/solution/contact-forms-->
                <!-- to get an API token!-->
                <form id="contactForm" data-sb-form-api-token="API_TOKEN">
                    <div class="row align-items-stretch mb-5">
                        <div class="col-md-6">
                            <div class="form-group">
                                <!-- Name input-->
                                <input class="form-control" id="name" name="username" type="text" placeholder="Nama Anda *" data-sb-validations="required" />
                                <div class="invalid-feedback" data-sb-feedback="name:required">Nama diperlukan</div>
                            </div>
                            <div class="form-group">
                                <!-- Email address input-->
                                <input class="form-control" id="email" name="email" type="email" placeholder="Email Anda *" data-sb-validations="required,email" />
                                <div class="invalid-feedback" data-sb-feedback="email:required">alamat emaildiperlukan.</div>
                                <div class="invalid-feedback" data-sb-feedback="email:email">Email yang anda masukkan tidak valid.</div>
                            </div>
                            <div class="form-group mb-md-0">
                                <!-- Phone number input-->
                                <input class="form-control" name="no_telepon" id="phone" type="tel" placeholder="Nomor Telepon Anda *" data-sb-validations="required" />
                                <div class="invalid-feedback" data-sb-feedback="phone:required">nomor telepon diperlukan.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-textarea mb-md-0">
                                <!-- Message input-->
                                <textarea class="form-control" name="ulasan" id="message" placeholder="Kritik & Saran *" data-sb-validations="required"></textarea>
                                <div class="invalid-feedback" data-sb-feedback="message:required">Berikan kritik dan saran sesuai dengan pengalaman anda di pemandian kami.</div>
                            </div>
                        </div>
                    </div>
                        <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    $sql = "INSERT INTO ulasan (ulasan, tgl_ulasan) VALUES ('$ulasan', '$tgl_ulasan')";

    if ($conn->query($sql) === true) {
        header("location: ulasan.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    ?> 
                    <!-- Submit success message-->
                    <!---->
                    <!-- This is what your users will see when the form-->
                    <!-- has successfully submitted-->
                    <div class="d-none" id="submitSuccessMessage">
                        <div class="text-center text-white mb-3">
                            <div class="fw-bolder">Kritik Dan Saran Anda Berhasil Dikirim!</div>
                            Untuk informasi lebih lanjut anda bisa menghubungi kami melalui link dibawah ini
                            <br />
                            <a href="https://www.youtube.com/channel/UC0zCg-hXAFtmSsve6rw4GoA">Hubungi Kami Disini  </a>
                        </div>
                    </div>
                    <!-- Submit error message-->
                    <!---->
                    <!-- This is what your users will see when there is-->
                    <!-- an error submitting the form-->
                    <div class="d-none" id="submitErrorMessage"><div class="text-center text-danger mb-3">Error sending message!</div></div>
                    <!-- Submit Button-->
                    <div class="text-center"><button class="btn btn-warning btn-xl text-uppercase disabled" id="submitButton" type="submit">Kirim</button></div>
                </form>
            </div>
        </section>
        <!-- Footer-->
        <footer class="footer py-4">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-4 text-lg-start">Copyright &copy; Pemandian 2023</div>
                    <div class="col-lg-4 my-3 my-lg-0">
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a class="link-dark text-decoration-none me-3" href="#!">Privacy Policy</a>
                        <a class="link-dark text-decoration-none" href="#!">Terms of Use</a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Portfolio Modals-->
        <!-- Portfolio item 1 modal popup-->
        <div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="close-modal" data-bs-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true" style="width: 50px; height: 50px;"></i></div>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="modal-body">
                                    <!-- Project details-->
                                    <h2 class="text-uppercase">Project Name</h2>
                                    <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                                    <img class="img-fluid d-block mx-auto" src="../../public/img/gambar.png" alt="..." />
                                    <p>Use this area to describe your project. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est blanditiis dolorem culpa incidunt minus dignissimos deserunt repellat aperiam quasi sunt officia expedita beatae cupiditate, maiores repudiandae, nostrum, reiciendis facere nemo!</p>
                                    <ul class="list-inline">
                                        <li>
                                            <strong>Client:</strong>
                                            Threads
                                        </li>
                                        <li>
                                            <strong>Category:</strong>
                                            Illustration
                                        </li>
                                    </ul>
                                    <button class="btn btn-warning btn-xl text-uppercase" data-bs-dismiss="modal" type="button">
                                        <i class="fas fa-xmark me-1"></i>
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Portfolio item 2 modal popup-->
        <div class="portfolio-modal modal fade" id="portfolioModal2" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="close-modal" data-bs-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true" style="width: 50px; height: 50px;"></i></div>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="modal-body">
                                    <!-- Project details-->
                                    <h2 class="text-uppercase">Project Name</h2>
                                    <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                                    <img class="img-fluid d-block mx-auto" src="../../public/img/gambar2.png" alt="..." />
                                    <p>Use this area to describe your project. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est blanditiis dolorem culpa incidunt minus dignissimos deserunt repellat aperiam quasi sunt officia expedita beatae cupiditate, maiores repudiandae, nostrum, reiciendis facere nemo!</p>
                                    <ul class="list-inline">
                                        <li>
                                            <strong>Client:</strong>
                                            Explore
                                        </li>
                                        <li>
                                            <strong>Category:</strong>
                                            Graphic Design
                                        </li>
                                    </ul>
                                    <button class="btn btn-warning btn-xl text-uppercase" data-bs-dismiss="modal" type="button">
                                        <i class="fas fa-xmark me-1"></i>
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Portfolio item 3 modal popup-->
        <div class="portfolio-modal modal fade" id="portfolioModal3" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="close-modal" data-bs-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true" style="width: 50px; height: 50px;"></i></div>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="modal-body">
                                    <!-- Project details-->
                                    <h2 class="text-uppercase">Project Name</h2>
                                    <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                                    <img class="img-fluid d-block mx-auto" src="../../public/img/gambar3.png" alt="..." />
                                    <p>Use this area to describe your project. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est blanditiis dolorem culpa incidunt minus dignissimos deserunt repellat aperiam quasi sunt officia expedita beatae cupiditate, maiores repudiandae, nostrum, reiciendis facere nemo!</p>
                                    <ul class="list-inline">
                                        <li>
                                            <strong>Client:</strong>
                                            Finish
                                        </li>
                                        <li>
                                            <strong>Category:</strong>
                                            Identity
                                        </li>
                                    </ul>
                                    <button class="btn btn-warning btn-xl text-uppercase" data-bs-dismiss="modal" type="button">
                                        <i class="fas fa-xmark me-1"></i>
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Portfolio item 4 modal popup-->
        <div class="portfolio-modal modal fade" id="portfolioModal4" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="close-modal" data-bs-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true" style="width: 50px; height: 50px;"></i></div>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="modal-body">
                                    <!-- Project details-->
                                    <h2 class="text-uppercase">Yonif Raider 515</h2>
                                    <p class="item-intro text-muted">meriahkan hut tni ke 72 yonif raider 515 kostrad karya bhakti di wisata pemandian patemon.</p>
                                    <img class="img-fluid d-block mx-auto" src="../../public/img/gambar6.png" alt="..." />
                                    <p>Dalam rangka memperingati Hari Ulang Tahun   ke-72 TNI tahun 2017 banyak kegiatan yang dilaksanakan oleh satuan-satuan TNI seluruh Indonesia. Tidak mau ketinggalan Yonif  Raider 515/UTY ikut serta memeriahkan HUT TNI ke 72 kali ini dengan mengadakan Karya Bhakti di sekitar satuan. Tanggul – Jember, Rabu (27/09/2017).</p>
                                    <button class="btn btn-warning btn-xl text-uppercase" data-bs-dismiss="modal" type="button">
                                        <i class="fas fa-xmark me-1"></i>
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Portfolio item 5 modal popup-->
        <div class="portfolio-modal modal fade" id="portfolioModal5" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="close-modal" data-bs-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true" style="width: 50px; height: 50px;"></i></div>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="modal-body">
                                    <!-- Project details-->
                                    <h2 class="text-uppercase">Ir. H. Hendy Siswanto, ST. IPU</h2>
                                    <p class="item-intro text-muted">Bupati Jember Ir. H. Hendy Siswanto, ST. IPU Mengunjungi Pemandian Patemon Di Tanggul Jember.</p>
                                    <img class="img-fluid d-block mx-auto" src="../../public/img/gambar8.png" alt="..." />
                                    <p>Bupati Jember Ir. H. Hendy Siswanto, ST. IPU. mengunjungi Pemandian Patemon di Tanggul Jember, Senin (21/02/2022). Pemandian Patemon merupakan taman rekreasi keluarga milik Pemkab Jember. Di sana Bupati Hendy Siswanto didampingi Kepala Disparbud dan Kepala BPKAD Jember meninjau aset yang sudah lama tanpa pemeliharaan tersebut.</p>
                                    <button class="btn btn-warning btn-xl text-uppercase" data-bs-dismiss="modal" type="button">
                                        <i class="fas fa-xmark me-1"></i>
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Portfolio item 6 modal popup-->
        <div class="portfolio-modal modal fade" id="portfolioModal6" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="close-modal" data-bs-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true" style="width: 50px; height: 50px;"></i></div>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="modal-body">
                                    <!-- Project details-->
                                    <h2 class="text-uppercase">Momen Terpadat Di Pemandian</h2>
                                    <p class="item-intro text-muted">Ribuan pengunjung padati pemandian.</p>
                                    <img class="img-fluid d-block mx-auto" src="../../public/img/gambar9.png" alt="..." />
                                    <p>Ribuan pengunjung padati kolam pemandian Patemon, Kecamatan Tanggul, Kabupaten Jember, Minggu (11-8-2013). Libur lebaran dimanfaatkan warga untuk berlibur bersama keluarga.</p>
                                    <button class="btn btn-warning btn-xl text-uppercase" data-bs-dismiss="modal" type="button">
                                        <i class="fas fa-xmark me-1"></i>
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="../../public/js/scripts.js"></script>
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
    </body>
        </html>
        