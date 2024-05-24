<?php
session_start();
require_once "db_connect.php";

// Memeriksa apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("location: ../login_admin.php");
    exit;
}

// Query untuk mendapatkan jumlah karyawan
$sql_absensi_semua = "SELECT absensi.*, employees.NIP, employees.Nama FROM absensi JOIN employees ON absensi.employee_id = employees.id";
$result_absensi_semua = mysqli_query($conn, $sql_absensi_semua);

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
    <script>
        // Function to trigger printing
        function printPage() {
            window.print();
        }

        // Automatically trigger printing when page finishes loading
        window.onload = printPage;
    </script>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->

        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->

                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="card mb-4">
                        <div class="card-header py-3">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>NIP</th>
                                                <th>Nama</th>
                                                <th>Waktu</th>
                                                <th>Keterangan</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($result_absensi_semua)) : ?>
                                                <tr>
                                                    <td><?php echo $row['NIP']; ?></td>
                                                    <td><?php echo $row['Nama']; ?></td>
                                                    <td><?php echo $row['waktu']; ?></td>
                                                    <td><?php echo ucwords($row['keterangan']); ?></td>


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



        </div>
        <!-- End of Content Wrapper -->

    </div>


</body>

</html>