<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
  die("Akses ditolak!");
}

$user_id = $_SESSION['user_id'];
$modul_id = isset($_POST['modul_id']) ? (int)$_POST['modul_id'] : 0;

if (!isset($_FILES['laporan']) || $modul_id <= 0) {
  die("Data tidak lengkap.");
}

$folder = "../uploads/laporan/";
if (!is_dir($folder)) {
  mkdir($folder, 0777, true);
}

$originalName = basename($_FILES['laporan']['name']);
$ext = pathinfo($originalName, PATHINFO_EXTENSION);
$newName = "laporan_{$user_id}_{$modul_id}_" . time() . "." . $ext;
$destination = $folder . $newName;

if (move_uploaded_file($_FILES['laporan']['tmp_name'], $destination)) {
  $cek = mysqli_query($conn, "SELECT * FROM laporan WHERE user_id=$user_id AND modul_id=$modul_id");

  if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Sudah mengupload sebelumnya!'); window.history.back();</script>";
    exit;
  }

  $insert = mysqli_query($conn, "INSERT INTO laporan (user_id, modul_id, file_laporan) VALUES ($user_id, $modul_id, '$newName')");
  if ($insert) {
    echo "<script>alert('Laporan berhasil diupload!'); window.history.back();</script>";
  } else {
    echo "<script>alert('Gagal menyimpan laporan!'); window.history.back();</script>";
  }
} else {
  echo "<script>alert('Gagal upload file!'); window.history.back();</script>";
}
?>
