<?php
session_start();
require_once "db_connect.php";

// Memeriksa apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
   header("location: ../login_admin.php");
    exit;
}

// Query untuk mendapatkan data karyawan
$sql_karyawan = "SELECT * FROM employees";
$result_karyawan = mysqli_query($conn, $sql_karyawan);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan</title>
</head>

<body>
    <h2>Data Karyawan</h2>
    <table border="1">
        <thead>
            <tr>
                <th>NIP</th>
                <th>Nama</th>
                <th>Tempat/Tanggal Lahir</th>
                <th>Jenis Kelamin</th>
                <th>Agama</th>
                <th>No. Telepon</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result_karyawan)) : ?>
                <tr>
                    <td><?php echo $row['NIP']; ?></td>
                    <td><?php echo $row['Nama']; ?></td>
                    <td><?php echo $row['tmpt_tgl_lahir']; ?></td>
                    <td><?php echo $row['jenkel']; ?></td>
                    <td><?php echo $row['agama']; ?></td>
                    <td><?php echo $row['no_tel']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td>
                        <a href="edit_karyawan.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="delete_karyawan.php?id=<?php echo $row['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>
