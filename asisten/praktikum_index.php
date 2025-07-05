<?php
// Session dan akses asisten
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = 'Manajemen Praktikum';
$activePage = 'praktikum';

$praktikum = mysqli_query($conn, "SELECT * FROM praktikum ORDER BY nama ASC");
require_once 'templates/header.php';
?>

<div class="bg-white p-6 rounded-2xl shadow-lg">
    <?php if (isset($_GET['tambah']) && $_GET['tambah'] === 'success'): ?>
    <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
        Praktikum berhasil ditambahkan.
    </div>
    <?php endif; ?>
    <?php if (isset($_GET['hapus']) && $_GET['hapus'] === 'success'): ?>
        <div class="bg-green-100 text-green-800 border border-green-300 px-4 py-2 rounded mb-4">
            ✅ Praktikum berhasil dihapus.
        </div>
    <?php elseif (isset($_GET['hapus']) && $_GET['hapus'] === 'failed'): ?>
        <div class="bg-red-100 text-red-700 border border-red-300 px-4 py-2 rounded mb-4">
            ❌ Gagal menghapus praktikum.
        </div>
    <?php endif; ?>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-[#2d6a6d]">🧪 Manajemen Praktikum</h1>
        <a href="praktikum_tambah.php" class="bg-[#5eaaa8] hover:bg-[#2d6a6d] text-white px-4 py-2 rounded-lg font-semibold transition">
            + Tambah Praktikum
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
            <thead class="bg-[#5eaaa8] text-white">
                <tr>
                    <th class="px-4 py-3 text-left">Nama Praktikum</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php while ($row = mysqli_fetch_assoc($praktikum)): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-800 font-medium"><?= htmlspecialchars($row['nama']) ?></td>
                        <td class="px-4 py-3 text-center space-x-2">
                            <a href="praktikum_edit.php?id=<?= $row['id'] ?>"
                               class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs font-semibold transition">
                                Edit
                            </a>
                            <a href="praktikum_hapus.php?id=<?= $row['id'] ?>"
                               onclick="return confirm('Yakin ingin menghapus praktikum ini?')"
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
