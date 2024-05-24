<?php
session_start();
require_once "db_connect.php";

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

// Nama karyawan
$nama = isset($row['Nama']) ? $row['Nama'] : '';

// Role karyawan
$role = isset($row['role']) ? $row['role'] : '';

// NIP karyawan
$nip = isset($row['NIP']) ? $row['NIP'] : '';

// Agama karyawan
$agama = isset($row['agama']) ? $row['agama'] : '';

// Tempat dan tanggal lahir karyawan
$tmpt_tgl_lahir = isset($row['tmpt_tgl_lahir']) ? $row['tmpt_tgl_lahir'] : '';

// Nomor telepon karyawan
$no_tel = isset($row['no_tel']) ? $row['no_tel'] : '';

// Foto profil karyawan
$foto_profil = isset($row['foto_profil']) ? $row['foto_profil'] : 'default.jpg';
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
                                                <h4><?php echo $nama; ?></h4>
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
                                <h2 class="text-white pb-2 fw-bold">Hi, <?php echo $nama; ?></h2>
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

                                    <div class="avatar avatar-xl mx-auto text-center d-flex justify-content-center align-items-center">
                                        <img src="../uploads/<?php echo $foto_profil; ?>" alt="..." class="avatar-img rounded-circle">
                                    </div>
                                    <form action="update_profile.php" method="post" enctype="multipart/form-data">
                                        <div class="profile-form">
                                            <div class="form-group">
                                                <label for="nama">Nama:</label>
                                                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama; ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="role">Role:</label>
                                                <input type="text" class="form-control" id="role" name="role" value="<?php echo $role; ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="nip">NIP:</label>
                                                <input type="text" class="form-control" id="nip" name="nip" value="<?php echo $nip; ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Password:</label>
                                                <input type="text" class="form-control" name="password" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="agama">Agama:</label>
                                                <input type="text" class="form-control" id="agama" name="agama" value="<?php echo $agama; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="tmpt_tgl_lahir">Tempat & Tanggal Lahir:</label>
                                                <input type="text" class="form-control" id="tmpt_tgl_lahir" name="tmpt_tgl_lahir" value="<?php echo $tmpt_tgl_lahir; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="no_tel">Nomor Telepon:</label>
                                                <input type="text" class="form-control" id="no_tel" name="no_tel" value="<?php echo $no_tel; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="foto_profil">Foto Profil:</label>
                                                <input type="file" class="form-control-file" id="foto_profil" name="foto_profil">
                                            </div>

                                            <input type="submit" class="btn btn-primary" value="Simpan Perubahan">

                                        </div>
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

</body>

</html>