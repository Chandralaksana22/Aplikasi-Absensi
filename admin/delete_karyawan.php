<?php
session_start();
require_once "db_connect.php";

// Memeriksa apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
   header("location: ../login_admin.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql_delete = "DELETE FROM employees WHERE id = '$id'";
    $result_delete = mysqli_query($conn, $sql_delete);

    if ($result_delete) {
        header("location: list_karyawan.php");
        exit;
    } else {
        $error = "Gagal menghapus data karyawan.";
    }
} else {
    header("location: list_karyawan.php");
    exit;
}
?>
