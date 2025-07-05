<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    die("Akses ditolak!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    if (empty($nama) || empty($email) || empty($password) || empty($role)) {
        $error = "Semua field wajib diisi!";
    } else {
        $cekEmail = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
        if (mysqli_num_rows($cekEmail) > 0) {
            $error = "Email sudah digunakan!";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_query($conn, "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$hash', '$role')");
            if ($insert) {
                // Tambahkan notifikasi sukses di redirect
                header("Location: user_index.php?tambah=success");
                exit;
            } else {
                $error = "Gagal menambahkan pengguna.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Pengguna</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-[#e3f1ef] to-[#f0fdfb] min-h-screen flex items-center justify-center p-6">

<div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-lg">
  <h1 class="text-2xl font-bold text-[#2d6a6d] mb-4">+ Tambah Pengguna</h1>

  <?php if (isset($error)): ?>
    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST" class="space-y-4">
    <input type="text" name="nama" placeholder="Nama Lengkap"
           class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-[#5eaaa8] focus:outline-none" required>
    
    <input type="email" name="email" placeholder="Email"
           class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-[#5eaaa8] focus:outline-none" required>
    
    <input type="password" name="password" placeholder="Password"
           class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-[#5eaaa8] focus:outline-none" required>
    
    <select name="role" class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-[#5eaaa8] focus:outline-none" required>
      <option value="">-- Pilih Peran --</option>
      <option value="mahasiswa">Mahasiswa</option>
      <option value="asisten">Asisten</option>
    </select>

    <div class="flex justify-between items-center">
      <a href="user_index.php" class="text-gray-600 hover:underline">← Kembali</a>
      <button type="submit" class="bg-[#5eaaa8] hover:bg-[#2d6a6d] text-white px-5 py-2 rounded-md font-semibold transition">
        Simpan
      </button>
    </div>
  </form>
</div>
</body>
</html>
