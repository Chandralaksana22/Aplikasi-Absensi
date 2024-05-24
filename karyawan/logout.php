<?php
session_start();

// Hapus semua data sesi admin
$_SESSION = array();
session_destroy();

// Redirect ke halaman login admin
header("location: ../index.php");
exit;
?>
