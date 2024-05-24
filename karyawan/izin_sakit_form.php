<?php
session_start();
require_once "db_connect.php";

// Memeriksa apakah karyawan sudah login
if (!isset($_SESSION['karyawan_logged_in']) || $_SESSION['karyawan_logged_in'] !== true) {
    header("location: login_karyawan.php");
    exit;
}

// Memeriksa apakah karyawan sudah login
if (!isset($_SESSION['karyawan_logged_in']) || $_SESSION['karyawan_logged_in'] !== true) {
    header("location: login_karyawan.php");
    exit;
}
// Ambil data karyawan dari database
$karyawan_id = $_SESSION['karyawan_id'];
$sql = "SELECT * FROM employees WHERE id = '$karyawan_id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Tentukan foto profil karyawan (jika ada) atau gunakan gambar default
$foto_profil = isset($row['foto_profil']) ? $row['foto_profil'] : '../assets/img/profile.jpg';

$Nama = isset($row['Nama']) ? $row['Nama'] : 'Nama tidak tersedia';
// Role karyawan
$role = isset($row['role']) ? $row['role'] : 'Role tidak tersedia';

// NIP karyawan
$nip = isset($row['NIP']) ? $row['NIP'] : 'NIP tidak tersedia';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_absen'])) {
    $employee_id = $_SESSION['karyawan_id'];

    // Mengubah format waktu menjadi tanggal bulan tahun
    $waktu = date("Y-m-d");

    // Memeriksa apakah karyawan telah melakukan absensi pada tanggal yang sama
    $sql_check_absensi = "SELECT * FROM absensi WHERE employee_id = '$employee_id' AND DATE(waktu) = '$waktu'";
    $result_check_absensi = mysqli_query($conn, $sql_check_absensi);

    if (mysqli_num_rows($result_check_absensi) > 0) {
        $error_message = "Anda telah melakukan absensi hari ini.";
    } else {
        $keterangan = "hadir"; // Keterangan default untuk absen hadir

        $sql_absen = "INSERT INTO absensi (employee_id, waktu, keterangan) VALUES ('$employee_id', '$waktu', '$keterangan')";
        $result_absen = mysqli_query($conn, $sql_absen);

        if ($result_absen) {
            $success_message = "Absensi berhasil direkam.";
        } else {
            $error_message = "Gagal merekam absensi.";
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_izin_sakit'])) {
    $employee_id = $_SESSION['karyawan_id'];
    $keterangan = $_POST['keterangan'];
    $alasan = $_POST['alasan'];

    // Mengubah format waktu menjadi tanggal bulan tahun
    $waktu = date("Y-m-d");

    // Simpan bukti foto ke direktori yang sesuai dan dapatkan path-nya untuk disimpan di database
    $bukti = $_FILES['bukti']['name'];
    $bukti_temp = $_FILES['bukti']['tmp_name'];

    // Upload foto ke direktori tertentu (misalnya: 'uploads/')
    $upload_dir = '../uploads/';
    move_uploaded_file($bukti_temp, $upload_dir . $bukti);

    $sql_absen = "INSERT INTO absensi (employee_id, waktu, keterangan, alasan, bukti) VALUES ('$employee_id', '$waktu', '$keterangan', '$alasan', '$bukti')";
    $result_absen = mysqli_query($conn, $sql_absen);

    if ($result_absen) {
        $success_message = "Absensi berhasil direkam.";
        echo "<script>
                setTimeout(function() {
                    alert('$success_message');
                    window.location.href = 'index.php';
                }, 3000); // mengalihkan setelah 3 detik
              </script>";
    } else {
        $error_message = "Gagal merekam absensi.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Absensi APP</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="../assets/img/icon.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="../assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Lato:300,400,700,900"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ['../assets/css/fonts.min.css']
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/atlantis.min.css">

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="../assets/css/demo.css">
</head>

<body>

    <div class="wrapper">
        <div class="main-header">
            <!-- Logo Header -->

            <!-- End Logo Header -->

            <!-- Navbar Header -->
            <nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">

                <div class="container-fluid">

                    <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                        <li class="nav-item toggle-nav-search hidden-caret">
                            <a class="nav-link" data-toggle="collapse" href="#search-nav" role="button" aria-expanded="false" aria-controls="search-nav">
                                <i class="fa fa-search"></i>
                            </a>
                        </li>

                        <li class="nav-item dropdown hidden-caret">
                            <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                                <div class="avatar-sm">
                                    <img src="../uploads/<?php echo $foto_profil; ?>" alt="..." class="avatar-img rounded-circle">
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li>
                                        <div class="user-box">
                                            <div class="avatar-lg"><img src="../uploads/<?php echo $foto_profil; ?>" alt="image profile" class="avatar-img rounded"></div>
                                            <div class="u-text">
                                                <h4><?php echo $Nama; ?></h4>
                                                <p class="text-muted"><?php echo $nip; ?></p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="profile.php">My Profile</a>
                                        <a class="dropdown-item" href="logout.php">Logout</a>
                                    </li>
                                </div>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>

        <!-- Sidebar -->

        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="content">
                <div class="panel-header bg-primary-gradient">
                    <div class="page-inner py-5">
                        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                            <div>
                                <h2 class="text-white pb-2 fw-bold">Hi, <?php echo $Nama; ?></h2>
                                <h5 class="text-white op-7 mb-2">Jangan Lupa Untuk Absen</h5>
                            </div>
                            <div class="ml-md-auto py-2 py-md-0">
								<a href="index.php" class="btn btn-white btn-border btn-round mr-2">Kembali Ke Beranda</a>
							</div>
                        </div>
                    </div>
                </div>
                <div class="page-inner mt--5">
                    <div class="row mt--2">

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title">Absensi APP</div>
                                    <div class="card-category">Ayo, jangan lupa untuk mengisi absensi kamu hari ini. Kehadiranmu sangatlah penting!</div>
                                    <div class="row pb-2 pt-4">
                                        <div class="col-3">
                                            <h6>Lokasi</h6>
                                        </div>
                                        <div class="col-9">
                                            <h6>PT Karya Anak Bangsa</h6>
                                            <h6>Gedung Pasaraya Blok M, lantai 6-7 Jl. Iskandarsyah II No. 2, Jakarta 12160</h6>
                                            <a href="">Lihat Lokasi</a>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="card-title">Keterangan Tidak Masuk</div>
                                    <?php if (isset($error_message)) echo "<p>$error_message</p>"; ?>
                                    <?php if (isset($success_message)) echo "<p>$success_message</p>"; ?>

                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1">Keterangan</label>
                                            <select name="keterangan" required class="form-control" id="exampleFormControlSelect1">
                                                <option value="izin">Izin</option>
                                                <option value="sakit">Sakit</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="alasan">Alasan</label>
                                            <input type="text" name="alasan" class="form-control" id="alasan" placeholder="Alasan Kamu">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleFormControlFile1">Bukti</label>
                                            <input type="file" name="bukti" accept="images/*" class="form-control-file" id="exampleFormControlFile1">
                                        </div>
                                        <br>
                                        <input type="submit" class="btn btn-primary" name="submit_izin_sakit" value="Submit">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Custom template | don't include it in your project! -->
        <div class="custom-template">
            <div class="title">Settings</div>
            <div class="custom-content">
                <div class="switcher">
                    <div class="switch-block">
                        <h4>Logo Header</h4>
                        <div class="btnSwitch">
                            <button type="button" class="changeLogoHeaderColor" data-color="dark"></button>
                            <button type="button" class="selected changeLogoHeaderColor" data-color="blue"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="purple"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="light-blue"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="green"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="orange"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="red"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="white"></button>
                            <br />
                            <button type="button" class="changeLogoHeaderColor" data-color="dark2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="blue2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="purple2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="light-blue2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="green2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="orange2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="red2"></button>
                        </div>
                    </div>
                    <div class="switch-block">
                        <h4>Navbar Header</h4>
                        <div class="btnSwitch">
                            <button type="button" class="changeTopBarColor" data-color="dark"></button>
                            <button type="button" class="changeTopBarColor" data-color="blue"></button>
                            <button type="button" class="changeTopBarColor" data-color="purple"></button>
                            <button type="button" class="changeTopBarColor" data-color="light-blue"></button>
                            <button type="button" class="changeTopBarColor" data-color="green"></button>
                            <button type="button" class="changeTopBarColor" data-color="orange"></button>
                            <button type="button" class="changeTopBarColor" data-color="red"></button>
                            <button type="button" class="changeTopBarColor" data-color="white"></button>
                            <br />
                            <button type="button" class="changeTopBarColor" data-color="dark2"></button>
                            <button type="button" class="selected changeTopBarColor" data-color="blue2"></button>
                            <button type="button" class="changeTopBarColor" data-color="purple2"></button>
                            <button type="button" class="changeTopBarColor" data-color="light-blue2"></button>
                            <button type="button" class="changeTopBarColor" data-color="green2"></button>
                            <button type="button" class="changeTopBarColor" data-color="orange2"></button>
                            <button type="button" class="changeTopBarColor" data-color="red2"></button>
                        </div>
                    </div>
                    <div class="switch-block">
                        <h4>Sidebar</h4>
                        <div class="btnSwitch">
                            <button type="button" class="selected changeSideBarColor" data-color="white"></button>
                            <button type="button" class="changeSideBarColor" data-color="dark"></button>
                            <button type="button" class="changeSideBarColor" data-color="dark2"></button>
                        </div>
                    </div>
                    <div class="switch-block">
                        <h4>Background</h4>
                        <div class="btnSwitch">
                            <button type="button" class="changeBackgroundColor" data-color="bg2"></button>
                            <button type="button" class="changeBackgroundColor selected" data-color="bg1"></button>
                            <button type="button" class="changeBackgroundColor" data-color="bg3"></button>
                            <button type="button" class="changeBackgroundColor" data-color="dark"></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="custom-toggle">
                <i class="flaticon-settings"></i>
            </div>
        </div>
        <!-- End Custom template -->
    </div>
    <style>
        .main-panel {
            width: 100% !important;
        }
    </style>
    <!--   Core JS Files   -->
    <script src="../assets/js/core/jquery.3.2.1.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery UI -->
    <script src="../assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="../assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>


    <!-- Chart JS -->
    <script src="../assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="../assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="../assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="../assets/js/plugin/jqvmap/jquery.vmap.min.js"></script>
    <script src="../assets/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

    <!-- Sweet Alert -->
    <script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Atlantis JS -->
    <script src="../assets/js/atlantis.min.js"></script>

    <!-- Atlantis DEMO methods, don't include it in your project! -->
    <script src="../assets/js/setting-demo.js"></script>
    <script src="../assets/js/demo.js"></script>
    <script>
        Circles.create({
            id: 'circles-1',
            radius: 45,
            value: 60,
            maxValue: 100,
            width: 7,
            text: 5,
            colors: ['#f1f1f1', '#FF9E27'],
            duration: 400,
            wrpClass: 'circles-wrp',
            textClass: 'circles-text',
            styleWrapper: true,
            styleText: true
        })

        Circles.create({
            id: 'circles-2',
            radius: 45,
            value: 70,
            maxValue: 100,
            width: 7,
            text: 36,
            colors: ['#f1f1f1', '#2BB930'],
            duration: 400,
            wrpClass: 'circles-wrp',
            textClass: 'circles-text',
            styleWrapper: true,
            styleText: true
        })

        Circles.create({
            id: 'circles-3',
            radius: 45,
            value: 40,
            maxValue: 100,
            width: 7,
            text: 12,
            colors: ['#f1f1f1', '#F25961'],
            duration: 400,
            wrpClass: 'circles-wrp',
            textClass: 'circles-text',
            styleWrapper: true,
            styleText: true
        })

        var totalIncomeChart = document.getElementById('totalIncomeChart').getContext('2d');

        var mytotalIncomeChart = new Chart(totalIncomeChart, {
            type: 'bar',
            data: {
                labels: ["S", "M", "T", "W", "T", "F", "S", "S", "M", "T"],
                datasets: [{
                    label: "Total Income",
                    backgroundColor: '#ff9e27',
                    borderColor: 'rgb(23, 125, 255)',
                    data: [6, 4, 9, 5, 4, 6, 4, 3, 8, 10],
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false,
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            display: false //this will remove only the label
                        },
                        gridLines: {
                            drawBorder: false,
                            display: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            drawBorder: false,
                            display: false
                        }
                    }]
                },
            }
        });

        $('#lineChart').sparkline([105, 103, 123, 100, 95, 105, 115], {
            type: 'line',
            height: '70',
            width: '100%',
            lineWidth: '2',
            lineColor: '#ffa534',
            fillColor: 'rgba(255, 165, 52, .14)'
        });
    </script>
    <script>
        function displayTime() {
            var date = new Date();
            var options = {
                timeZone: 'Asia/Jakarta'
            };
            var time = date.toLocaleTimeString('en-US', options);
            document.getElementById('time').textContent = time;
        }

        function displayDate() {
            var date = new Date();
            var options = {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                timeZone: 'Asia/Jakarta'
            };
            var formatter = new Intl.DateTimeFormat('id-ID', options);
            var dateStr = formatter.format(date);
            document.getElementById('date').textContent = dateStr;
        }

        setInterval(displayTime, 1000); // Refresh setiap detik
        displayTime(); // Menampilkan waktu saat pertama kali halaman dimuat
        displayDate(); // Menampilkan tanggal saat pertama kali halaman dimuat
    </script>
</body>

</html>