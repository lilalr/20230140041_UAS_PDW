<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Pengguna</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-[#e3f1ef] to-[#f0fdfb] min-h-screen flex items-center justify-center p-6">

<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    die("Akses ditolak!");
}

$id = (int) $_GET['id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $id"));

if (!$user) {
    die("User tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    $update = mysqli_query($conn, "
        UPDATE users SET nama = '$nama', email = '$email', role = '$role' WHERE id = $id
    ");

    if ($update) {
        header("Location: user_edit.php?id=$id&status=success");
        exit;
    } else {
        $error = "Gagal memperbarui pengguna.";
    }
}
?>

<div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-lg">
  <h1 class="text-2xl font-bold text-[#2d6a6d] mb-4">✏️ Edit Pengguna</h1>

  <!-- Notifikasi sukses -->
  <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
    <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-2 rounded mb-4">
      ✅ Pengguna berhasil diperbarui.
    </div>
  <?php endif; ?>

  <!-- Notifikasi gagal -->
  <?php if (isset($error)): ?>
    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST" class="space-y-4">
    <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>"
           class="w-full border px-4 py-2 rounded-md focus:ring-[#5eaaa8] focus:outline-none" required>
    
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>"
           class="w-full border px-4 py-2 rounded-md focus:ring-[#5eaaa8] focus:outline-none" required>
    
    <select name="role" class="w-full border px-4 py-2 rounded-md focus:ring-[#5eaaa8] focus:outline-none" required>
      <option value="mahasiswa" <?= $user['role'] === 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
      <option value="asisten" <?= $user['role'] === 'asisten' ? 'selected' : '' ?>>Asisten</option>
    </select>

    <div class="flex justify-between items-center">
      <a href="user_index.php" class="text-gray-600 hover:underline">← Kembali</a>
      <button type="submit" class="bg-[#5eaaa8] hover:bg-[#2d6a6d] text-white px-5 py-2 rounded-md font-semibold transition">
        Simpan Perubahan
      </button>
    </div>
  </form>
</div>
</body>
</html>
