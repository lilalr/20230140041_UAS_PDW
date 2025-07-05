<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = 'Manajemen Modul';
$activePage = 'modul';

$praktikum_id = isset($_GET['praktikum_id']) ? (int)$_GET['praktikum_id'] : 0;
if ($praktikum_id <= 0) {
    die("ID praktikum tidak valid.");
}

$query = "SELECT * FROM modul WHERE praktikum_id = $praktikum_id ORDER BY id ASC";
$modul = mysqli_query($conn, $query);
if (!$modul) {
    die("Query gagal: " . mysqli_error($conn) . "<br>SQL: $query");
}

require_once 'templates/header.php';
?>

<div class="bg-white p-6 rounded-2xl shadow-lg">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-[#2d6a6d]">📘 Manajemen Modul</h1>
        <a href="modul_tambah.php?praktikum_id=<?= $praktikum_id ?>" class="bg-[#5eaaa8] hover:bg-[#2d6a6d] text-white px-4 py-2 rounded-lg font-semibold transition">
            + Tambah Modul
        </a>
    </div>
    <?php if (isset($_GET['tambah']) && $_GET['tambah'] === 'success'): ?>
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
        Modul berhasil ditambahkan.
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['update']) && $_GET['update'] === 'success'): ?>
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
        Modul berhasil diperbarui.
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['hapus']) && $_GET['hapus'] === 'success'): ?>
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            Modul berhasil dihapus.
        </div>
    <?php elseif (isset($_GET['hapus']) && $_GET['hapus'] === 'failed'): ?>
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
            Gagal menghapus modul.
        </div>
    <?php elseif (isset($_GET['tambah']) && $_GET['tambah'] === 'success'): ?>
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            Modul berhasil ditambahkan.
        </div>
    <?php endif; ?>

    <div class="overflow-x-auto">
        <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
            <thead class="bg-[#5eaaa8] text-white">
                <tr>
                    <th class="px-4 py-3 text-left">#</th>
                    <th class="px-4 py-3 text-left">Judul</th>
                    <th class="px-4 py-3 text-center">Materi</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php $no = 1; while ($row = mysqli_fetch_assoc($modul)): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-semibold text-gray-800"><?= $no++ ?></td>
                    <td class="px-4 py-3 text-gray-700"><?= htmlspecialchars($row['judul']) ?></td>
                    <td class="px-4 py-3 text-center">
                        <?php if (!empty($row['file_materi'])): ?>
                            <a href="../uploads/materi/<?= $row['file_materi'] ?>" target="_blank"
                               class="inline-block bg-blue-100 text-blue-700 px-3 py-1 text-xs font-semibold rounded-full hover:bg-blue-200 transition">
                                📄 Download
                            </a>
                        <?php else: ?>
                            <span class="text-gray-400 italic">Tidak ada file</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-center space-x-2">
                        <a href="modul_edit.php?id=<?= $row['id'] ?>&praktikum_id=<?= $praktikum_id ?>"
                           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-xs font-semibold transition">
                            Edit
                        </a>
                        <a href="modul_hapus.php?id=<?= $row['id'] ?>&praktikum_id=<?= $praktikum_id ?>"
                           onclick="return confirm('Yakin ingin menghapus modul ini?')"
                           class="inline-block bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold transition">
                            Hapus
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>
