<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'config.php';

// Jika sudah login, langsung redirect
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'asisten') {
        header("Location: asisten/dashboard.php");
    } elseif ($_SESSION['role'] === 'mahasiswa') {
        header("Location: mahasiswa/dashboard.php");
    }
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' LIMIT 1");
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        // Redirect sesuai role
        if ($user['role'] === 'asisten') {
            header("Location: asisten/dashboard.php");
        } elseif ($user['role'] === 'mahasiswa') {
            header("Location: mahasiswa/dashboard.php");
        }
        exit;
    } else {
        $error = "Email atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Login SIMPRAK</title>
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
      <h1 class="text-2xl font-bold text-[#2d6a6d]">SIMPRAK</h1>
      <p class="text-sm text-gray-600">Sistem Informasi Praktikum</p>
    </div>

    <?php if (!empty($error)): ?>
      <div class="bg-red-100 border border-red-300 text-red-700 p-3 mb-4 rounded-md text-sm">
        <?= $error; ?>
      </div>
    <?php elseif (isset($_GET['status']) && $_GET['status'] === 'registered'): ?>
      <div class="bg-green-100 border border-green-300 text-green-700 p-3 mb-4 rounded-md text-sm">
        Pendaftaran berhasil! Silakan login.
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" required
          class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5eaaa8]">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" name="password" required
          class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5eaaa8]">
      </div>
      <button type="submit"
        class="w-full bg-[#5eaaa8] text-white font-semibold py-2 rounded-md hover:bg-[#2d6a6d] transition duration-200">
        MASUK
      </button>
    </form>

    <p class="text-sm text-center mt-4 text-gray-600">
      Belum punya akun? <a href="register.php" class="text-[#2d6a6d] font-medium hover:underline">Daftar di sini</a>
    </p>
  </div>

</body>
</html>
