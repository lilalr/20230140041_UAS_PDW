<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    die("Akses ditolak!");
}

if (!isset($_GET['modul_id']) || !is_numeric($_GET['modul_id'])) {
    die("Modul tidak valid.");
}

$modul_id = (int)$_GET['modul_id'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Upload Laporan</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
  <div class="max-w-md mx-auto bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-xl font-bold text-[#2d6a6d] mb-4">Upload Laporan</h2>
    <form action="proses_upload_laporan.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="modul_id" value="<?= $modul_id ?>">

      <label class="block mb-2 text-sm font-medium text-gray-700">Pilih file laporan (.pdf / .doc / .docx)</label>
      <input type="file" name="laporan" required class="mb-4 w-full p-2 border border-gray-300 rounded">

      <button type="submit" class="bg-[#5eaaa8] text-white px-4 py-2 rounded hover:bg-[#4a908f] transition">
        Upload
      </button>
    </form>
  </div>
</body>
</html>
