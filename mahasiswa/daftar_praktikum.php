<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
  die("Akses ditolak!");
}

$user_id = $_SESSION['user_id'];
$praktikum_id = isset($_POST['praktikum_id']) ? (int) $_POST['praktikum_id'] : 0;

if ($praktikum_id <= 0) {
  echo "<script>alert('ID praktikum tidak valid.'); window.location='cari_praktikum.php';</script>";
  exit;
}

// Cek apakah sudah pernah daftar
$cek = mysqli_query($conn, "SELECT * FROM pendaftaran_praktikum WHERE user_id = $user_id AND praktikum_id = $praktikum_id");

if (!$cek) {
  die("Query error: " . mysqli_error($conn));
}

if (mysqli_num_rows($cek) > 0) {
  echo "<script>alert('Anda sudah mendaftar ke praktikum ini.'); window.location='cari_praktikum.php';</script>";
  exit;
}

// Insert data
$query = mysqli_query($conn, "INSERT INTO pendaftaran_praktikum (user_id, praktikum_id) VALUES ($user_id, $praktikum_id)");

if ($query) {
  echo "<script>alert('Berhasil mendaftar praktikum!'); window.location='cari_praktikum.php';</script>";
} else {
  echo "<script>alert('Gagal mendaftar!'); window.location='cari_praktikum.php';</script>";
}
?>
