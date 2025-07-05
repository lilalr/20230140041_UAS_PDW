<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Modul</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-[#e3f1ef] to-[#f0fdfb] min-h-screen flex items-center justify-center p-6">

<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    die("Akses ditolak!");
}

$praktikum_id = isset($_GET['praktikum_id']) ? (int)$_GET['praktikum_id'] : 0;
if ($praktikum_id <= 0) {
    die("ID praktikum tidak valid.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);
    $tanggal = $_POST['tanggal'];
    $fileName = null;

    if (isset($_FILES['file_materi']) && $_FILES['file_materi']['error'] === 0) {
        $ext = pathinfo($_FILES['file_materi']['name'], PATHINFO_EXTENSION);
        if (strtolower($ext) !== 'pdf') {
            $error = "File harus dalam format PDF.";
        } else {
            $newName = "materi_" . time() . "_" . rand(1000, 9999) . ".pdf";
            $uploadPath = "../uploads/materi/" . $newName;
            if (move_uploaded_file($_FILES['file_materi']['tmp_name'], $uploadPath)) {
                $fileName = $newName;
            } else {
                $error = "Gagal mengupload file.";
            }
        }
    }

    if (!isset($error)) {
        $insert = mysqli_query($conn, "INSERT INTO modul (praktikum_id, judul, file_materi, tanggal)
                     VALUES ($praktikum_id, '$judul', '$fileName', '$tanggal')");
        if ($insert) {
            header("Location: modul_index.php?praktikum_id=$praktikum_id&tambah=success");
            exit;
        } else {
            $error = "Gagal menyimpan data.";
        }
    }
}
?>

<div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-lg">
  <h1 class="text-2xl font-bold text-[#2d6a6d] mb-4">📚 Tambah Modul</h1>

  <?php if (isset($error)): ?>
    <div class="bg-red-100 text-red-600 px-4 py-2 rounded mb-4"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data" class="space-y-4">
    <input type="text" name="judul" placeholder="Judul Modul"
           class="w-full border px-4 py-2 rounded-md focus:ring-[#5eaaa8] focus:outline-none" required>

    <input type="date" name="tanggal"
           class="w-full border px-4 py-2 rounded-md focus:ring-[#5eaaa8] focus:outline-none" required>

    <input type="file" name="file_materi" accept=".pdf"
           class="w-full text-sm border p-2 rounded bg-gray-50">

    <div class="flex justify-between">
      <a href="modul_index.php?praktikum_id=<?= $praktikum_id ?>" class="text-gray-600 hover:underline">← Kembali</a>
      <button type="submit" class="bg-[#5eaaa8] hover:bg-[#2d6a6d] text-white px-5 py-2 rounded-md font-semibold transition">
        Simpan
      </button>
    </div>
  </form>
</div>
</body>
</html>
