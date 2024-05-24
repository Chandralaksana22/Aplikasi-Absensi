<?php
session_start();
require_once "db_connect.php";

// Memeriksa apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("location: ../login_admin.php");
    exit;
}

// Query untuk mendapatkan jumlah karyawan
$sql_karyawan = "SELECT COUNT(*) AS total_karyawan FROM employees";
$result_karyawan = mysqli_query($conn, $sql_karyawan);
$row_karyawan = mysqli_fetch_assoc($result_karyawan);
$total_karyawan = $row_karyawan['total_karyawan'];

// Query untuk mendapatkan absensi karyawan hari ini
$date_today = date('Y-m-d');
$sql_absensi_hari_ini = "SELECT absensi.*, employees.NIP, employees.Nama FROM absensi JOIN employees ON absensi.employee_id = employees.id WHERE DATE(absensi.waktu) = '$date_today'";
$result_absensi_hari_ini = mysqli_query($conn, $sql_absensi_hari_ini);


// Query untuk mendapatkan absensi karyawan bulan ini
$date_month = date('Y-m-01');
$sql_absensi_bulan_ini = "SELECT absensi.*, employees.NIP, employees.Nama FROM absensi JOIN employees ON absensi.employee_id = employees.id WHERE DATE(absensi.waktu) >= '$date_month'";
$result_absensi_bulan_ini = mysqli_query($conn, $sql_absensi_bulan_ini);

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Absensi - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Admin- Keterangan Absensi</h1>
                        <a href="printlaporan.php" target="_blank" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Absensi Hari Ini</h6>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>NIP</th>
                                                <th>Nama</th>
                                                <th>Waktu</th>
                                                <th>Keterangan</th>
                                                <th>Alasan</th>
                                                <th>Bukti</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>NIP</th>
                                                <th>Nama</th>
                                                <th>Waktu</th>
                                                <th>Keterangan</th>
                                                <th>Alasan</th>
                                                <th>Bukti</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($result_absensi_hari_ini)) : ?>
                                                <tr>
                                                    <td><?php echo $row['NIP']; ?></td>
                                                    <td><?php echo $row['Nama']; ?></td>
                                                    <td><?php echo $row['waktu']; ?></td>
                                                    <td><?php echo ucwords($row['keterangan']); ?></td>
                                                    <td><?php echo $row['alasan']; ?></td>
                                                    <td>
                                                        <?php if (!empty($row['bukti'])) : ?>
                                                            <a target="_blank" href="../uploads/<?php echo $row['bukti']; ?>" class="btn btn-primary">Lihat Bukti</a>
                                                        <?php else : ?>
                                                            Karyawan Hadir
                                                        <?php endif; ?>

                                                    </td>
                                                    <td>
                                                        <form method="post" action="delete_absensi.php">
                                                            <input type="hidden" name="absensi_id" value="<?php echo $row['id']; ?>">
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Absensi Bulan Ini</h6>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>NIP</th>
                                                <th>Nama</th>
                                                <th>Waktu</th>
                                                <th>Keterangan</th>
                                                <th>Alasan</th>
                                                <th>Bukti</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>NIP</th>
                                                <th>Nama</th>
                                                <th>Waktu</th>
                                                <th>Keterangan</th>
                                                <th>Alasan</th>
                                                <th>Bukti</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($result_absensi_bulan_ini)) : ?>
                                                <tr>
                                                    <td><?php echo $row['NIP']; ?></td>
                                                    <td><?php echo $row['Nama']; ?></td>
                                                    <td><?php echo $row['waktu']; ?></td>
                                                    <td><?php echo ucwords($row['keterangan']); ?></td>
                                                    <td><?php echo ucwords($row['alasan']);?></td>
                                                    <td>
                                                        <?php if (!empty($row['bukti'])) : ?>
                                                            <a target="_blank" href="../uploads/<?php echo $row['bukti']; ?>" class="btn btn-primary">Lihat Bukti</a>
                                                        <?php else : ?>
                                                            Karyawan Hadir
                                                        <?php endif; ?>

                                                    </td>

                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
                        <span>Copyright &copy; Absensi APP 2024</span>
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

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>