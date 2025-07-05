<?php
// 1. Session dan akses asisten
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit();
}

// 2. Set Judul dan halaman aktif
$pageTitle = 'Kelola Pengguna';
$activePage = 'user';

// 3. Ambil data semua user
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY role ASC, nama ASC");

// 4. Panggil Header
require_once 'templates/header.php';
?>

<div class="bg-white p-6 rounded-2xl shadow-md">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-[#2d6a6d]">👥 Kelola Pengguna</h1>
        <a href="user_tambah.php" class="bg-[#5eaaa8] hover:bg-[#2d6a6d] text-white px-4 py-2 rounded-lg font-semibold transition">
            + Tambah Pengguna
        </a>
    </div>

    <!-- Notifikasi -->
    <?php if (isset($_GET['hapus']) && $_GET['hapus'] === 'success'): ?>
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">✅ Pengguna berhasil dihapus.</div>
    <?php elseif (isset($_GET['hapus']) && $_GET['hapus'] === 'failed'): ?>
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">❌ Gagal menghapus pengguna.</div>
    <?php elseif (isset($_GET['edit']) && $_GET['edit'] === 'success'): ?>
        <div class="bg-blue-100 text-blue-700 px-4 py-2 rounded mb-4">✏️ Pengguna berhasil diperbarui.</div>
    <?php elseif (isset($_GET['tambah']) && $_GET['tambah'] === 'success'): ?>
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">✅ Pengguna berhasil ditambahkan.</div>
    <?php endif; ?>

    <div class="overflow-x-auto">
        <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
            <thead class="bg-[#5eaaa8] text-white">
                <tr>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Role</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php while ($user = mysqli_fetch_assoc($users)): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-2 font-semibold text-gray-800"><?= htmlspecialchars($user['nama']) ?></td>
                        <td class="px-4 py-2 text-gray-700"><?= htmlspecialchars($user['email']) ?></td>
                        <td class="px-4 py-2 text-gray-700 capitalize"><?= htmlspecialchars($user['role']) ?></td>
                        <td class="px-4 py-2 text-center space-x-2">
                            <a href="user_edit.php?id=<?= $user['id'] ?>"
                               class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-xs font-semibold transition">
                               Edit
                            </a>
                            <a href="user_hapus.php?id=<?= $user['id'] ?>"
                               onclick="return confirm('Yakin ingin menghapus pengguna ini?')"
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
