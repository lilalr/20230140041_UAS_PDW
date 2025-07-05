<?php
$pageTitle = 'Dashboard';
$activePage = 'dashboard';
require_once 'templates/header_mahasiswa.php'; 
?>

<!-- Greeting Section -->
<div class="bg-gradient-to-r from-[#5eaaa8] to-[#2d6a6d] text-white p-8 rounded-xl shadow-lg mb-8">
  <div class="flex flex-col md:flex-row md:items-center md:justify-between">
    <div>
      <h1 class="text-3xl font-bold">Selamat Siang, <?= htmlspecialchars($_SESSION['nama']); ?>! 🎓</h1>
      <p class="mt-2 text-white/90">Terus semangat dalam menyelesaikan semua modul praktikummu.</p>
      <p class="mt-2 text-sm opacity-80">1 dari 2 tugas telah diselesaikan</p>
    </div>
    <div class="mt-6 md:mt-0">
      <div class="bg-[#2d6a6d] px-4 py-2 rounded-full text-sm font-semibold shadow-md text-white text-center">
        Progress Keseluruhan: <strong>50%</strong>
      </div>
    </div>
  </div>

  <!-- Progress Bar -->
  <div class="w-full bg-white bg-opacity-20 h-2 mt-4 rounded-full">
    <div class="bg-white h-2 rounded-full w-1/2"></div>
  </div>
</div>

<!-- Statistik -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
  <div class="bg-white p-6 rounded-xl shadow flex items-center space-x-4">
    <div class="bg-[#def1ef] p-3 rounded-full text-[#5eaaa8] text-xl">
      🧪
    </div>
    <div>
      <div class="text-2xl font-extrabold text-[#5eaaa8]">1</div>
      <div class="text-sm text-gray-600">Praktikum Diikuti</div>
    </div>
  </div>

  <div class="bg-white p-6 rounded-xl shadow flex items-center space-x-4">
    <div class="bg-green-100 p-3 rounded-full text-green-600 text-xl">
      ✅
    </div>
    <div>
      <div class="text-2xl font-extrabold text-green-600">1</div>
      <div class="text-sm text-gray-600">Tugas Selesai</div>
    </div>
  </div>

  <div class="bg-white p-6 rounded-xl shadow flex items-center space-x-4">
    <div class="bg-yellow-100 p-3 rounded-full text-yellow-500 text-xl">
      ⏳
    </div>
    <div>
      <div class="text-2xl font-extrabold text-yellow-500">1</div>
      <div class="text-sm text-gray-600">Tugas Menunggu</div>
    </div>
  </div>
</div>

<!-- Notifikasi -->
<div class="bg-white p-6 rounded-xl shadow-md">
  <div class="flex items-center justify-between mb-4">
    <h3 class="text-2xl font-bold text-gray-800">Notifikasi Terbaru</h3>
    <span class="text-sm bg-blue-100 text-blue-600 px-3 py-1 rounded-full">3 Baru</span>
  </div>

  <ul class="space-y-4">
    <li class="flex items-start p-3 border-b border-gray-100 last:border-b-0">
      <span class="text-xl mr-4">⭐</span>
      <div>Selamat Datang di Semester Baru. Semoga sukses ya!</div>
    </li>
    <li class="flex items-start p-3 border-b border-gray-100 last:border-b-0">
      <span class="text-xl mr-4">📢</span>
      <div>Pendaftaran untuk praktikum semester baru dibuka sampai <strong>31 Juli 2025</strong>.</div>
    </li>
    <li class="flex items-start p-3">
      <span class="text-xl mr-4">📘</span>
      <div>Modul baru ditambahkan ke <a href="#" class="font-semibold text-blue-600 hover:underline">Katalog Praktikum</a>.</div>
    </li>
  </ul>
</div>

<?php require_once 'templates/footer_mahasiswa.php'; ?>
