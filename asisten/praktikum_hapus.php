<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    die("Akses ditolak!");
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("ID tidak valid.");
}

// Cek apakah praktikum ada
$cek = mysqli_query($conn, "SELECT * FROM praktikum WHERE id = $id");
if (mysqli_num_rows($cek) == 0) {
    die("Data tidak ditemukan.");
}

// Hapus data
$hapus = mysqli_query($conn, "DELETE FROM praktikum WHERE id = $id");

if ($hapus) {
    header("Location: praktikum_index.php?hapus=success");
    exit;
} else {
    header("Location: praktikum_index.php?hapus=failed");
    exit;
}
?>
