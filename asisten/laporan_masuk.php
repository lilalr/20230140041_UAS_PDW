<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = 'Laporan Masuk';
$activePage = 'laporan';

$laporan = mysqli_query($conn, "
    SELECT l.*, u.nama AS mahasiswa, m.judul AS modul
    FROM laporan l
    JOIN users u ON l.user_id = u.id
    JOIN modul m ON l.modul_id = m.id
    ORDER BY l.tanggal_upload DESC
");

require_once 'templates/header.php';
?>

<div class="bg-white p-6 rounded-2xl shadow-lg">
    <h1 class="text-2xl font-bold text-[#2d6a6d] mb-6">📥 Daftar Laporan Masuk</h1>

    <div class="overflow-x-auto">
        <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
            <thead class="bg-[#5eaaa8] text-white text-left">
                <tr>
                    <th class="px-4 py-3">Mahasiswa</th>
                    <th class="px-4 py-3">Modul</th>
                    <th class="px-4 py-3">Tanggal Upload</th>
                    <th class="px-4 py-3">File</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php while ($row = mysqli_fetch_assoc($laporan)): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-medium text-gray-800"><?= htmlspecialchars($row['mahasiswa']) ?></td>
                    <td class="px-4 py-3 text-gray-700"><?= htmlspecialchars($row['modul']) ?></td>
                    <td class="px-4 py-3 text-gray-600"><?= date("d M Y, H:i", strtotime($row['tanggal_upload'])) ?></td>
                    <td class="px-4 py-3 text-blue-600">
                        <a href="../uploads/laporan/<?= $row['file_laporan'] ?>" target="_blank" class="underline hover:text-blue-800 transition">Download</a>
                    </td>
                    <td class="px-4 py-3">
                        <?php if (!is_null($row['nilai'])): ?>
                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 text-xs font-semibold rounded-full">
                                ✔️ Dinilai (<?= $row['nilai'] ?>)
                            </span>
                        <?php else: ?>
                            <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 text-xs font-semibold rounded-full">
                                ⏳ Belum Dinilai
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <a href="beri_nilai.php?id=<?= $row['id'] ?>"
                           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-md text-xs font-semibold transition">
                            Beri Nilai
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>
