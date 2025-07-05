<?php
// Pastikan session aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi: hanya asisten yang boleh masuk
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Asisten - <?= $pageTitle ?? 'Dashboard'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#2d6a6d] text-white flex flex-col">
        <div class="p-6 border-b border-[#285c5e] text-center">
            <h1 class="text-2xl font-bold">SIMPRAK</h1>
            <p class="text-sm text-gray-200 mt-1 italic"><?= htmlspecialchars($_SESSION['nama']); ?></p>
        </div>
        <nav class="flex-grow mt-4">
            <ul class="space-y-2 p-4">
                <?php 
                    $activeClass = 'bg-[#5eaaa8] text-white';
                    $inactiveClass = 'text-gray-300 hover:bg-[#4c918d] hover:text-white';
                ?>
                <li>
                    <a href="dashboard.php" class="flex items-center px-4 py-2 rounded-md transition <?= $activePage == 'dashboard' ? $activeClass : $inactiveClass; ?>">
                        🏠 <span class="ml-3">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="laporan_masuk.php" class="flex items-center px-4 py-2 rounded-md transition <?= $activePage == 'laporan' ? $activeClass : $inactiveClass; ?>">
                        📥 <span class="ml-3">Laporan Masuk</span>
                    </a>
                </li>
                <li>
                    <a href="modul_index.php?praktikum_id=1" class="flex items-center px-4 py-2 rounded-md transition <?= $activePage == 'modul' ? $activeClass : $inactiveClass; ?>">
                        📘 <span class="ml-3">Manajemen Modul</span>
                    </a>
                </li>
                <li>
                    <a href="praktikum_index.php" class="flex items-center px-4 py-2 rounded-md transition <?= $activePage == 'praktikum' ? $activeClass : $inactiveClass; ?>">
                        🧪 <span class="ml-3">Manajemen Praktikum</span>
                    </a>
                </li>
                <li>
                    <a href="user_index.php" class="flex items-center px-4 py-2 rounded-md transition <?= $activePage == 'user' ? $activeClass : $inactiveClass; ?>">
                        👥 <span class="ml-3">Kelola Pengguna</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="p-4 border-t border-[#285c5e]">
            <a href="../logout.php" class="block text-center bg-red-500 hover:bg-red-600 text-white font-bold py-2 rounded-md transition">
                Logout
            </a>
        </div>
    </aside>

    <!-- Konten utama -->
    <main class="flex-1 p-6 lg:p-10">
        <header class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800"><?= $pageTitle ?? 'Dashboard'; ?></h2>
        </header>
