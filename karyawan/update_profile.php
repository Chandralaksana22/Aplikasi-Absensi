<?php
session_start();
require_once "db_connect.php";

// Memeriksa apakah karyawan sudah login
if (!isset($_SESSION['karyawan_logged_in']) || $_SESSION['karyawan_logged_in'] !== true) {
    header("location: login_karyawan.php");
    exit;
}

// Memeriksa apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $karyawan_id = $_SESSION['karyawan_id'];
    $nama = trim($_POST["nama"]);
    $password = trim($_POST["password"]);
    $agama = trim($_POST["agama"]);
    $tmpt_tgl_lahir = trim($_POST["tmpt_tgl_lahir"]);
    $no_tel = trim($_POST["no_tel"]);

    // Memeriksa apakah foto profil baru diunggah
    if ($_FILES['foto_profil']['error'] == UPLOAD_ERR_OK) {
        $foto_profil_name = $_FILES['foto_profil']['name'];
        $foto_profil_tmp = $_FILES['foto_profil']['tmp_name'];
        $foto_profil_type = $_FILES['foto_profil']['type'];
        $foto_profil_size = $_FILES['foto_profil']['size'];

        // Memeriksa tipe file
        $allowed_types = array('image/jpeg', 'image/png');
        if (in_array($foto_profil_type, $allowed_types)) {
            // Simpan foto profil ke direktori upload
            $upload_path = "../uploads/";
            $foto_profil_destination = $upload_path . $foto_profil_name;
            move_uploaded_file($foto_profil_tmp, $foto_profil_destination);

            // Update data karyawan termasuk foto profil
            $sql = "UPDATE employees SET Nama='$nama', password='$password', agama='$agama', tmpt_tgl_lahir='$tmpt_tgl_lahir', no_tel='$no_tel', foto_profil='$foto_profil_name' WHERE id='$karyawan_id'";
        } else {
            // Jika tipe file tidak didukung, kembali dengan pesan error
            $_SESSION['message'] = "Tipe file tidak didukung.";
            header("location: profile.php");
            exit;
        }
    } else {
        // Update data karyawan tanpa foto profil
        $sql = "UPDATE employees SET Nama='$nama', password='$password', agama='$agama', tmpt_tgl_lahir='$tmpt_tgl_lahir', no_tel='$no_tel' WHERE id='$karyawan_id'";
    }

    // Eksekusi query update
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Profil berhasil diperbarui.";
    } else {
        $_SESSION['message'] = "Terjadi kesalahan. Silakan coba lagi.";
    }

    // Kembali ke halaman profil
    header("location: profile.php");
    exit;
} else {
    // Jika halaman diakses secara langsung, kembali ke halaman profil
    header("location: profile.php");
    exit;
}
?>
