<?php
$pageTitle = 'Cari Praktikum';
$activePage = 'cari_praktikum';

require_once '../config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

require_once 'templates/header_mahasiswa.php';

$userId = $_SESSION['user_id'];

// Hitung statistik
$totalResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM praktikum");
$totalData = mysqli_fetch_assoc($totalResult);
$total = $totalData['total'] ?? 0;

$terdaftarResult = mysqli_query($conn, "SELECT COUNT(*) AS jumlah FROM pendaftaran_praktikum WHERE user_id = $userId");
$terdaftarData = mysqli_fetch_assoc($terdaftarResult);
$terdaftar = $terdaftarData['jumlah'] ?? 0;

$tersedia = $total - $terdaftar;
?>

<div class="bg-white p-6 rounded-xl shadow-md">
  <h2 class="text-2xl font-bold mb-2 text-[#2d6a6d]">Katalog Mata Praktikum</h2>

  <!-- Statistik -->
  <div class="flex flex-wrap gap-4 mb-6">
    <div class="bg-[#5eaaa8] text-white px-4 py-2 rounded shadow">
      Total Praktikum: <strong><?= $total; ?></strong>
    </div>
    <div class="bg-green-500 text-white px-4 py-2 rounded shadow">
      Sudah Terdaftar: <strong><?= $terdaftar; ?></strong>
    </div>
    <div class="bg-yellow-400 text-white px-4 py-2 rounded shadow">
      Tersedia: <strong><?= $tersedia; ?></strong>
    </div>
  </div>

  <!-- Daftar Praktikum -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php
    $result = mysqli_query($conn, "SELECT * FROM praktikum");

    while ($row = mysqli_fetch_assoc($result)):
      $praktikumId = $row['id'];
      $cek = mysqli_query($conn, "SELECT id FROM pendaftaran_praktikum WHERE user_id = $userId AND praktikum_id = $praktikumId");
      $sudahTerdaftar = ($cek && mysqli_num_rows($cek) > 0);
    ?>
    <div class="bg-gray-50 border border-gray-200 rounded-xl shadow p-5 flex flex-col justify-between">
      <div>
        <h3 class="text-xl font-semibold text-[#2d6a6d] mb-2"><?= htmlspecialchars($row['nama']); ?></h3>
        <p class="text-gray-700 text-sm mb-4"><?= htmlspecialchars($row['deskripsi']); ?></p>
      </div>

      <div class="mt-4">
        <?php if ($sudahTerdaftar): ?>
          <button disabled class="w-full bg-gray-300 text-gray-600 py-2 rounded-md cursor-not-allowed">
            ✅ Sudah Terdaftar
          </button>
        <?php else: ?>
          <form action="daftar_praktikum.php" method="POST">
            <input type="hidden" name="praktikum_id" value="<?= $row['id']; ?>">
            <button type="submit" class="w-full bg-[#5eaaa8] hover:bg-[#2d6a6d] text-white py-2 rounded-md transition">
              Daftar
            </button>
          </form>
        <?php endif; ?>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</div>

<?php require_once 'templates/footer_mahasiswa.php'; ?>
