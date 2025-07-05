<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    die("Akses ditolak!");
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$praktikum_id = isset($_GET['praktikum_id']) ? (int)$_GET['praktikum_id'] : 0;

if ($id <= 0 || $praktikum_id <= 0) {
    die("ID tidak valid.");
}

// Ambil data modul
$modul = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM modul WHERE id = $id"));
if (!$modul) {
    die("Data tidak ditemukan.");
}

// Hapus laporan yang terkait dengan modul ini
mysqli_query($conn, "DELETE FROM laporan WHERE modul_id = $id");

// Hapus file materi jika ada
if ($modul['file_materi'] && file_exists("../uploads/materi/" . $modul['file_materi'])) {
    unlink("../uploads/materi/" . $modul['file_materi']);
}

// Hapus modul
$hapus = mysqli_query($conn, "DELETE FROM modul WHERE id = $id");

if ($hapus) {
    header("Location: modul_index.php?praktikum_id=$praktikum_id&hapus=success");
    exit;
} else {
    // Debug tampilkan error MySQL
    die("Gagal menghapus modul: " . mysqli_error($conn));
}
?>
