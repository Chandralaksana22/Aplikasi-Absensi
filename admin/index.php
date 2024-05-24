<?php
session_start();
require_once "db_connect.php";

// Memeriksa apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
   header("location: ../login_admin.php");
    exit;
}
// Array untuk menyimpan data hadir, izin, dan sakit untuk setiap bulan
$data = array(
    'bulan' => array(),
    'hadir' => array(),
    'izin' => array(),
    'sakit' => array()
);

// Query untuk mengambil jumlah hadir, izin, dan sakit untuk setiap bulan dalam 6 bulan terakhir
$current_date = date('Y-m-d');
$six_months_ago = date('Y-m-d', strtotime('-6 months', strtotime($current_date)));

$sql = "SELECT DATE_FORMAT(waktu, '%Y-%m') AS bulan, 
               SUM(CASE WHEN keterangan = 'hadir' THEN 1 ELSE 0 END) AS hadir,
               SUM(CASE WHEN keterangan = 'izin' THEN 1 ELSE 0 END) AS izin,
               SUM(CASE WHEN keterangan = 'sakit' THEN 1 ELSE 0 END) AS sakit
        FROM absensi
        WHERE waktu >= '$six_months_ago'
        GROUP BY DATE_FORMAT(waktu, '%Y-%m')
        ORDER BY DATE_FORMAT(waktu, '%Y-%m') ASC";

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $data['bulan'][] = $row['bulan'];
    $data['hadir'][] = $row['hadir'];
    $data['izin'][] = $row['izin'];
    $data['sakit'][] = $row['sakit'];
}

// Konversi data ke format JSON untuk digunakan dalam chart
$json_data = json_encode($data);
// Query untuk mendapatkan jumlah karyawan
$sql_karyawan = "SELECT COUNT(*) AS total_karyawan FROM employees";
$result_karyawan = mysqli_query($conn, $sql_karyawan);
$row_karyawan = mysqli_fetch_assoc($result_karyawan);
$total_karyawan = $row_karyawan['total_karyawan'];

// Query untuk mendapatkan absensi karyawan hari ini
$date_today = date('Y-m-d');
$sql_absensi_hari_ini = "SELECT absensi.*, employees.NIP, employees.Nama FROM absensi JOIN employees ON absensi.employee_id = employees.id WHERE DATE(absensi.waktu) = '$date_today'";
$result_absensi_hari_ini = mysqli_query($conn, $sql_absensi_hari_ini);

// Menghitung jumlah karyawan yang hadir, sakit, dan izin hari ini
$total_hadir_hari_ini = 0;
$total_sakit_hari_ini = 0;
$total_izin_hari_ini = 0;

while ($row = mysqli_fetch_assoc($result_absensi_hari_ini)) {
    if ($row['keterangan'] == 'hadir') {
        $total_hadir_hari_ini++;
    } elseif ($row['keterangan'] == 'sakit') {
        $total_sakit_hari_ini++;
    } elseif ($row['keterangan'] == 'izin') {
        $total_izin_hari_ini++;
    }
}

// Query untuk mendapatkan absensi karyawan bulan ini
$date_month = date('Y-m-01');
$sql_absensi_bulan_ini = "SELECT absensi.*, employees.NIP, employees.Nama FROM absensi JOIN employees ON absensi.employee_id = employees.id WHERE DATE(absensi.waktu) >= '$date_month'";
$result_absensi_bulan_ini = mysqli_query($conn, $sql_absensi_bulan_ini);

// Menghitung jumlah karyawan yang hadir, sakit, dan izin bulan ini
$total_hadir_bulan_ini = 0;
$total_sakit_bulan_ini = 0;
$total_izin_bulan_ini = 0;

while ($row = mysqli_fetch_assoc($result_absensi_bulan_ini)) {
    if ($row['keterangan'] == 'hadir') {
        $total_hadir_bulan_ini++;
    } elseif ($row['keterangan'] == 'sakit') {
        $total_sakit_bulan_ini++;
    } elseif ($row['keterangan'] == 'izin') {
        $total_izin_bulan_ini++;
    }
}
?>


</html>
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Jumlah Masuk</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"> <?php echo $total_hadir_hari_ini; ?> Orang</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Jumlah Izin</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_izin_hari_ini; ?> Orang</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-info fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah Sakit
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $total_sakit_hari_ini; ?> Orang</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-briefcase-medical fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Total Karyawan</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_karyawan; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Statistik Karyawan</h6>
                                </div>
                                <!-- Card Body -->

                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="multipleBarChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Diagram Kehadiran</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary"></i> hadir
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Izin
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Sakit
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                   

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
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <!-- <script src="js/demo/chart-pie-demo.js"></script> -->
    <script>
        // Set new default font family and font color to mimic Bootstrap's default styling
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        // Pie Chart Example
        var ctx = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["Hadir", "Izin", "Sakit"],
                datasets: [{
                    data: [<?php echo $total_hadir_hari_ini; ?>, <?php echo $total_izin_hari_ini; ?>, <?php echo $total_sakit_hari_ini; ?>],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });
    </script>
   <script>
        // Data yang diperoleh dari PHP
        var absensiData = <?php echo $json_data; ?>;
        
        // Inisialisasi data untuk chart
        var chartData = {
            labels: absensiData.bulan,
            datasets: [{
                label: 'Hadir',
                backgroundColor: '#59d05d',
                borderColor: '#59d05d',
                data: absensiData.hadir
            }, {
                label: 'Izin',
                backgroundColor: '#fdaf4b',
                borderColor: '#fdaf4b',
                data: absensiData.izin
            }, {
                label: 'Sakit',
                backgroundColor: '#177dff',
                borderColor: '#177dff',
                data: absensiData.sakit
            }]
        };

        // Konfigurasi options untuk chart
        var chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            },
            title: {
                display: true,
                text: 'Statistik Karyawan'
            },
            tooltips: {
                mode: 'index',
                intersect: false
            },
            responsive: true,
            scales: {
                xAxes: [{
                    stacked: true,
                }],
                yAxes: [{
                    stacked: true
                }]
            }
        };

        // Membuat chart menggunakan Chart.js
        var multipleBarChart = document.getElementById('multipleBarChart').getContext('2d');
        var myMultipleBarChart = new Chart(multipleBarChart, {
            type: 'bar',
            data: chartData,
            options: chartOptions
        });
    </script>
</body>

</html>