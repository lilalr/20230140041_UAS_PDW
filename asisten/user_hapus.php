<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    die("Akses ditolak!");
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $hapus = mysqli_query($conn, "DELETE FROM users WHERE id = $id");
    if ($hapus) {
        header("Location: user_index.php?hapus=success");
    } else {
        header("Location: user_index.php?hapus=failed");
    }
} else {
    header("Location: user_index.php?hapus=invalid");
}
exit;
