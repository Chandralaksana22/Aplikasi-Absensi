<?php
session_start();
require_once "db_connect.php";

// Memeriksa apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("location: ../login_admin.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $NIP = $_POST['NIP'];
    $Nama = $_POST['Nama'];
    $tmpt_tgl_lahir = $_POST['tmpt_tgl_lahir'];
    $jenkel = $_POST['jenkel'];
    $agama = $_POST['agama'];
    $no_tel = $_POST['no_tel'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Enkripsi password sebelum disimpan


    // Memasukkan data karyawan ke dalam tabel employees
    $sql = "INSERT INTO employees (NIP, Nama, tmpt_tgl_lahir, jenkel, agama, no_tel, role, password) 
            VALUES ('$NIP', '$Nama', '$tmpt_tgl_lahir', '$jenkel', '$agama', '$no_tel', '$role', '$password')";

    if (mysqli_query($conn, $sql)) {
        header("location: index.php");
    } else {
        $error = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Blank</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include 'navbar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Tambah Karyawan</h1>
                    <div class="card shadow my-5">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Karyawan</h6>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($error)) echo "<p>$error</p>"; ?>
                            <form action="" method="post">
                                <div class="form-group">
                                    <input type="text" name="NIP" class="form-control form-control-user" placeholder="NIP">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="Nama" class="form-control form-control-user" placeholder="Nama">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="tmpt_tgl_lahir" class="form-control form-control-user" placeholder="Tempat, Tanggal Lahir">
                                </div>
                                <div class="form-group">
                                    <select name="jenkel" class="form-control form-control-user">
                                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                        <option value="laki-laki">Laki-laki</option>
                                        <option value="perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="agama" class="form-control form-control-user" placeholder="Agama">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="no_tel" class="form-control form-control-user" placeholder="Nomor Telephone">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="role" class="form-control form-control-user" placeholder="Jabatan">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="password" class="form-control form-control-user" placeholder="Kata Sandi">
                                </div>
                                <input type="submit" class="btn btn-primary" value="Tambah">
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout_admin.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>