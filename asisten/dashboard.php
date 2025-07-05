<?php
$pageTitle = 'Dashboard';
$activePage = 'dashboard';
require_once '../config.php';
require_once 'templates/header.php';

$totalModul = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM modul"))['total'];
$totalLaporan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM laporan"))['total'];
$laporanBelumDinilai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM laporan WHERE nilai IS NULL"))['total'];

$laporan = mysqli_query($conn, "
    SELECT u.nama AS mahasiswa, m.judul AS modul, l.tanggal_upload
    FROM laporan l
    JOIN users u ON l.user_id = u.id
    JOIN modul m ON l.modul_id = m.id
    ORDER BY l.tanggal_upload DESC
    LIMIT 5
");
?>

<!-- Statistik Kartu -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-blue-50 p-6 rounded-2xl shadow-lg flex items-center justify-between">
        <div>
            <p class="text-blue-700 text-sm font-semibold mb-1">Total Modul</p>
            <p class="text-3xl font-extrabold text-blue-900"><?= $totalModul ?></p>
        </div>
        <div class="bg-blue-200 text-blue-700 p-3 rounded-full">
            📘
        </div>
    </div>

    <div class="bg-green-50 p-6 rounded-2xl shadow-lg flex items-center justify-between">
        <div>
            <p class="text-green-700 text-sm font-semibold mb-1">Laporan Masuk</p>
            <p class="text-3xl font-extrabold text-green-900"><?= $totalLaporan ?></p>
        </div>
        <div class="bg-green-200 text-green-700 p-3 rounded-full">
            📂
        </div>
    </div>

    <div class="bg-yellow-50 p-6 rounded-2xl shadow-lg flex items-center justify-between">
        <div>
            <p class="text-yellow-600 text-sm font-semibold mb-1">Belum Dinilai</p>
            <p class="text-3xl font-extrabold text-yellow-800"><?= $laporanBelumDinilai ?></p>
        </div>
        <div class="bg-yellow-200 text-yellow-600 p-3 rounded-full">
            ⏳
        </div>
    </div>
</div>

<!-- Aktivitas -->
<div class="bg-white p-6 rounded-2xl shadow-lg">
    <h3 class="text-xl font-bold text-[#2d6a6d] mb-5">Aktivitas Terbaru</h3>
    <div class="divide-y divide-gray-200">
        <?php while ($row = mysqli_fetch_assoc($laporan)): ?>
        <div class="flex items-start py-4">
            <div class="w-10 h-10 bg-[#5eaaa8] text-white flex items-center justify-center rounded-full font-bold mr-4">
                <?= strtoupper(substr($row['mahasiswa'], 0, 2)) ?>
            </div>
            <div>
                <p class="text-gray-800">
                    <strong><?= htmlspecialchars($row['mahasiswa']) ?></strong> mengumpulkan laporan untuk
                    <strong><?= htmlspecialchars($row['modul']) ?></strong>
                </p>
                <p class="text-sm text-gray-500"><?= date("d M Y, H:i", strtotime($row['tanggal_upload'])) ?></p>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>
