<?php
require_once 'config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama     = trim($_POST['nama']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role     = trim($_POST['role']);

    // Validasi
    if (empty($nama) || empty($email) || empty($password) || empty($role)) {
        $message = "Semua field harus diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Format email tidak valid!";
    } elseif (!in_array($role, ['mahasiswa', 'asisten'])) {
        $message = "Peran tidak valid!";
    } else {
        $cek = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $cek->bind_param("s", $email);
        $cek->execute();
        $cek->store_result();

        if ($cek->num_rows > 0) {
            $message = "Email sudah terdaftar. Gunakan email lain.";
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);

            $simpan = $conn->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
            $simpan->bind_param("ssss", $nama, $email, $hashed, $role);

            if ($simpan->execute()) {
                header("Location: login.php?status=registered");
                exit();
            } else {
                $message = "Terjadi kesalahan saat menyimpan data.";
            }
            $simpan->close();
        }
        $cek->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Registrasi Akun - SIMPRAK</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: linear-gradient(to bottom right, #5eaaa8, #2d6a6d);
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center font-sans">

  <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-md">
    <div class="text-center mb-6">
      <div class="inline-flex items-center justify-center w-14 h-14 bg-[#5eaaa8] rounded-full mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.75 3h4.5a.75.75 0 01.75.75V5h3.25A1.75 1.75 0 0120 6.75v9.5A1.75 1.75 0 0118.25 18H5.75A1.75 1.75 0 014 16.25v-9.5A1.75 1.75 0 015.75 5H9V3.75A.75.75 0 019.75 3z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 21h6" />
        </svg>
      </div>
      <h1 class="text-2xl font-bold text-[#2d6a6d]">Registrasi SIMPRAK</h1>
      <p class="text-sm text-gray-600">Buat akun baru untuk mulai praktikum</p>
    </div>

    <?php if (!empty($message)): ?>
      <div class="bg-red-100 border border-red-300 text-red-700 p-3 mb-4 rounded-md text-sm">
        <?= $message; ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
        <input type="text" name="nama" required
          class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-[#5eaaa8]">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" required
          class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-[#5eaaa8]">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" name="password" required
          class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-[#5eaaa8]">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
        <select name="role" required
          class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-[#5eaaa8]">
          <option value="">-- Pilih Peran --</option>
          <option value="mahasiswa">Mahasiswa</option>
          <option value="asisten">Asisten</option>
        </select>
      </div>
      <button type="submit"
        class="w-full bg-[#5eaaa8] text-white font-semibold py-2 rounded-md hover:bg-[#2d6a6d] transition duration-200">
        DAFTAR
      </button>
    </form>

    <p class="text-sm text-center mt-4 text-gray-600">
      Sudah punya akun? <a href="login.php" class="text-[#2d6a6d] font-medium hover:underline">Login di sini</a>
    </p>
  </div>

</body>
</html>
