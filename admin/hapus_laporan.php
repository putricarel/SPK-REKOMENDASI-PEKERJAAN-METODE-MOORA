<?php
require '../functions.php';

// Ambil id_laporan dari URL
$id_laporan = $_GET['id_laporan'];

// Pastikan koneksi ke database
global $con;

// Query untuk menghapus laporan
$query = "DELETE FROM laporan WHERE id_laporan = '$id_laporan'";

// Eksekusi query dan cek hasil
if (mysqli_query($con, $query)) {
  echo "<script>
  alert('Laporan berhasil dihapus!');
  window.location.href = 'laporan.php';
  </script>";
} else {
    // Tangkap error dari database
  $error = mysqli_error($con);
  echo "<script>
  alert('Gagal menghapus laporan! Error: $error');
  window.location.href = 'laporan.php';
  </script>";
}
?>
