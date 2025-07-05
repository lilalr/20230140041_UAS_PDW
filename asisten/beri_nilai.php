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

$laporan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT l.*, u.nama AS mahasiswa, m.judul AS modul
    FROM laporan l
    JOIN users u ON l.user_id = u.id
    JOIN modul m ON l.modul_id = m.id
    WHERE l.id = $id
"));

if (!$laporan) {
    die("Laporan tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nilai = (int)$_POST['nilai'];
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);

    $update = mysqli_query($conn, "
        UPDATE laporan SET nilai = $nilai, feedback = '$feedback' WHERE id = $id
    ");

    if ($update) {
        header("Location: laporan_masuk.php");
        exit;
    } else {
        $error = "Gagal menyimpan nilai.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beri Nilai</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold mb-4">Beri Nilai Laporan</h1>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 text-red-700 px-4 py-2 mb-4 rounded"><?= $error ?></div>
    <?php endif; ?>

    <p class="mb-2"><strong>Mahasiswa:</strong> <?= htmlspecialchars($laporan['mahasiswa']) ?></p>
    <p class="mb-2"><strong>Modul:</strong> <?= htmlspecialchars($laporan['modul']) ?></p>
    <p class="mb-4"><strong>File:</strong>
        <a href="../uploads/laporan/<?= $laporan['file_laporan'] ?>" target="_blank" class="text-blue-600 underline">
            Download Laporan
        </a>
    </p>

    <form method="POST">
        <label class="block mb-2">Nilai (0–100):</label>
        <input type="number" name="nilai" min="0" max="100" required class="w-full border px-3 py-2 mb-4 rounded"
               value="<?= $laporan['nilai'] ?? '' ?>">

        <label class="block mb-2">Feedback:</label>
        <textarea name="feedback" rows="4" class="w-full border px-3 py-2 mb-4 rounded"><?= $laporan['feedback'] ?? '' ?></textarea>

        <div class="flex justify-between">
            <a href="laporan_masuk.php" class="bg-gray-400 text-white px-4 py-2 rounded">Kembali</a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Simpan Nilai</button>
        </div>
    </form>
</div>

</body>
</html>
