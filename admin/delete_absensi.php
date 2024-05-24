<?php
session_start();
require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['absensi_id'])) {
    $absensi_id = $_POST['absensi_id'];

    // Query untuk menghapus data absensi berdasarkan ID
    $sql = "DELETE FROM absensi WHERE id = '$absensi_id'";
    if (mysqli_query($conn, $sql)) {
        header("location: absensi.php"); // Redirect kembali ke dashboard admin setelah berhasil menghapus
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
} else {
    header("location: absensi.php"); // Redirect kembali ke dashboard admin jika tidak ada request yang valid
    exit;
}
?>
