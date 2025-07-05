<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$praktikum_id = isset($_GET['praktikum_id']) ? (int)$_GET['praktikum_id'] : 0;

if ($id <= 0 || $praktikum_id <= 0) {
    die("ID tidak valid.");
}

$modul = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM modul WHERE id = $id"));
if (!$modul) die("Modul tidak ditemukan.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);
    $tanggal = $_POST['tanggal'];
    $fileName = $modul['file_materi'];

    if (isset($_FILES['file_materi']) && $_FILES['file_materi']['error'] === 0) {
        $ext = pathinfo($_FILES['file_materi']['name'], PATHINFO_EXTENSION);
        if (strtolower($ext) !== 'pdf') {
            $error = "File harus PDF.";
        } else {
            $newName = "materi_" . time() . "_" . rand(1000, 9999) . ".pdf";
            $uploadPath = "../uploads/materi/" . $newName;
            if (move_uploaded_file($_FILES['file_materi']['tmp_name'], $uploadPath)) {
                $fileName = $newName;
                if ($modul['file_materi'] && file_exists("../uploads/materi/" . $modul['file_materi'])) {
                    unlink("../uploads/materi/" . $modul['file_materi']);
                }
            } else {
                $error = "Gagal upload file.";
            }
        }
    }

    if (!isset($error)) {
        $update = mysqli_query($conn, "UPDATE modul SET judul='$judul', tanggal='$tanggal', file_materi='$fileName' WHERE id=$id");
        if ($update) {
            header("Location: modul_index.php?praktikum_id=$praktikum_id&update=success");
            exit;
        } else {
            $error = "Gagal memperbarui data.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Modul</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-[#e3f1ef] to-[#f0fdfb] min-h-screen flex items-center justify-center p-6">

<div class="bg-white w-full max-w-xl p-8 rounded-xl shadow-xl">
  <h1 class="text-2xl font-bold text-[#2d6a6d] mb-6">✏️ Edit Modul Praktikum</h1>

  <?php if (isset($error)): ?>
    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 border border-red-300"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data" class="space-y-4">
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Judul Modul</label>
      <input type="text" name="judul" required
             value="<?= htmlspecialchars($modul['judul']) ?>"
             class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-[#5eaaa8]">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
      <input type="date" name="tanggal" required
             value="<?= $modul['tanggal'] ?>"
             class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-[#5eaaa8]">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Materi (PDF)</label>
      <input type="file" name="file_materi" accept=".pdf"
             class="w-full border border-gray-300 p-2 rounded bg-gray-50">
    </div>

    <?php if ($modul['file_materi']): ?>
      <p class="text-sm text-gray-500">
        📄 File saat ini:
        <a href="../uploads/materi/<?= $modul['file_materi'] ?>" target="_blank" class="text-blue-600 underline">
          <?= $modul['file_materi'] ?>
        </a>
      </p>
    <?php endif; ?>

    <div class="flex justify-between items-center pt-4">
      <a href="modul_index.php?praktikum_id=<?= $praktikum_id ?>" class="text-gray-600 hover:underline text-sm">← Kembali</a>
      <button type="submit" class="bg-[#5eaaa8] hover:bg-[#2d6a6d] text-white font-semibold px-5 py-2 rounded-md transition">
        Simpan Perubahan
      </button>
    </div>
  </form>
</div>

</body>
</html>
