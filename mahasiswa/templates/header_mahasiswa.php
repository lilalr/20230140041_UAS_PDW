<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title><?= $pageTitle ?? 'Dashboard - SIMPRAK'; ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f1f5f4] font-sans min-h-screen">

<!-- NAVBAR -->
<nav class="bg-[#5eaaa8] text-white shadow-md">
  <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
    <div class="text-xl font-bold">SIMPRAK</div>
    <div class="hidden md:flex space-x-6 items-center text-sm font-medium">
      <?php 
        $activeClass = 'border-b-2 border-white';
        $inactiveClass = 'hover:text-gray-200 transition';
      ?>
      <a href="dashboard.php" class="<?= ($activePage == 'dashboard') ? $activeClass : $inactiveClass ?>">Dashboard</a>
      <a href="cari_praktikum.php" class="<?= ($activePage == 'cari_praktikum') ? $activeClass : $inactiveClass ?>">Cari Praktikum</a>
      <a href="praktikum_saya.php" class="<?= ($activePage == 'praktikum_saya') ? $activeClass : $inactiveClass ?>">Praktikum Saya</a>
      <span class="ml-4">Halo, <?= htmlspecialchars($_SESSION['nama']); ?>!</span>
      <a href="../logout.php" class="bg-white text-[#5eaaa8] px-3 py-1 rounded hover:bg-gray-100">Logout</a>
    </div>
  </div>
</nav>

<!-- CONTENT WRAPPER -->
<main class="p-6">
