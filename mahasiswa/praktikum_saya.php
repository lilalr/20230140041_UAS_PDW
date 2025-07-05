<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$pageTitle = 'Praktikum Saya';
$activePage = 'praktikum_saya';

// Pastikan file header_mahasiswa.php ada
require_once 'templates/header_mahasiswa.php';

// Ambil praktikum yang diikuti mahasiswa
$query = "
    SELECT p.id AS praktikum_id, p.nama, p.deskripsi
    FROM pendaftaran_praktikum pp
    JOIN praktikum p ON pp.praktikum_id = p.id
    WHERE pp.user_id = $user_id
";
$praktikumResult = mysqli_query($conn, $query);
$jumlahPraktikum = mysqli_num_rows($praktikumResult);
?>

<div class="bg-white p-6 rounded-xl shadow-md">
    <h1 class="text-2xl font-bold text-[#2d6a6d] mb-2">📘 Praktikum Saya</h1>
    <p class="text-gray-600 mb-6">
        Kamu telah mengambil <span class="font-semibold"><?= $jumlahPraktikum ?></span> praktikum.
    </p>

    <?php if ($jumlahPraktikum > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($praktikum = mysqli_fetch_assoc($praktikumResult)): ?>
                <div class="bg-[#f5fdfd] border border-[#cce5e2] rounded-xl p-4 shadow hover:shadow-md transition">
                    <h3 class="text-lg font-bold text-[#2d6a6d] mb-1"><?= htmlspecialchars($praktikum['nama']) ?></h3>
                    <p class="text-sm text-gray-600 mb-3"><?= nl2br(htmlspecialchars($praktikum['deskripsi'])) ?></p>

                    <?php
                    // Ambil modul & laporan terkait praktikum ini
                    $modulQ = mysqli_query($conn, "SELECT id FROM modul WHERE praktikum_id = " . $praktikum['praktikum_id']);
                    $modul_ids = [];
                    while ($modul = mysqli_fetch_assoc($modulQ)) {
                        $modul_ids[] = $modul['id'];
                    }

                    $totalModul = count($modul_ids);
                    $uploaded = 0;
                    $dinilai = 0;

                    if ($totalModul > 0) {
                        $modulIdList = implode(",", $modul_ids);
                        $laporanQ = mysqli_query($conn, "SELECT * FROM laporan WHERE user_id = $user_id AND modul_id IN ($modulIdList)");
                        $uploaded = mysqli_num_rows($laporanQ);

                        while ($laporan = mysqli_fetch_assoc($laporanQ)) {
                            if (!is_null($laporan['nilai'])) {
                                $dinilai++;
                            }
                        }
                    }

                    $progress = $totalModul > 0 ? round(($uploaded / $totalModul) * 100) : 0;
                    ?>

                    <div class="mb-2 text-sm text-gray-600">
                        Modul dikumpulkan: <span class="font-semibold"><?= $uploaded ?></span> / <?= $totalModul ?><br>
                        Dinilai oleh asisten: <span class="font-semibold"><?= $dinilai ?></span> modul
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-[#5eaaa8] h-2.5 rounded-full transition-all duration-500" style="width: <?= $progress ?>%;"></div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded">
            Kamu belum mendaftar ke praktikum manapun.
        </div>
    <?php endif; ?>
</div>
