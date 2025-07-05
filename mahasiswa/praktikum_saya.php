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

// === PROSES UPLOAD LAPORAN ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['laporan']) && isset($_POST['modul_id'])) {
    $modul_id = (int)$_POST['modul_id'];
    $folder = "../uploads/laporan/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $ext = strtolower(pathinfo($_FILES['laporan']['name'], PATHINFO_EXTENSION));
    $allowed = ['pdf', 'doc', 'docx'];
    if (!in_array($ext, $allowed)) {
        echo "<script>alert('Format file tidak diperbolehkan! Hanya PDF/DOC/DOCX.');</script>";
    } else {
        $newName = "laporan_{$user_id}_{$modul_id}_" . time() . "." . $ext;
        $destination = $folder . $newName;

        $cek = mysqli_query($conn, "SELECT * FROM laporan WHERE user_id=$user_id AND modul_id=$modul_id");
        if (mysqli_num_rows($cek) > 0) {
            echo "<script>alert('Kamu sudah mengupload laporan untuk modul ini!');</script>";
        } elseif (move_uploaded_file($_FILES['laporan']['tmp_name'], $destination)) {
            $insert = mysqli_query($conn, "INSERT INTO laporan (user_id, modul_id, file_laporan) VALUES ($user_id, $modul_id, '$newName')");
            if ($insert) {
                echo "<script>alert('Laporan berhasil diupload!'); window.location.href = 'praktikum_saya.php';</script>";
                exit;
            } else {
                echo "<script>alert('Gagal menyimpan laporan ke database!');</script>";
            }
        } else {
            echo "<script>alert('Gagal upload file!');</script>";
        }
    }
}

// Ambil praktikum yang diikuti mahasiswa
require_once 'templates/header_mahasiswa.php';

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

                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                        <div class="bg-[#5eaaa8] h-2.5 rounded-full transition-all duration-500" style="width: <?= $progress ?>%;"></div>
                    </div>

                    <?php if ($totalModul > 0): ?>
                        <div>
                            <h4 class="text-sm font-semibold text-[#2d6a6d] mb-2">📄 Daftar Modul & Upload:</h4>
                            <ul class="space-y-2 text-sm">
                                <?php
                                $modulQ2 = mysqli_query($conn, "SELECT * FROM modul WHERE praktikum_id = " . $praktikum['praktikum_id']);
                                while ($modul = mysqli_fetch_assoc($modulQ2)):
                                    $modul_id = $modul['id'];
                                    $laporanQ = mysqli_query($conn, "SELECT * FROM laporan WHERE user_id = $user_id AND modul_id = $modul_id");
                                    $laporan = mysqli_fetch_assoc($laporanQ);
                                    $sudahUpload = $laporan ? true : false;
                                ?>
                                    <li class="bg-white border border-gray-200 rounded p-3">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium"><?= htmlspecialchars($modul['judul']) ?></span>
                                            <?php if ($sudahUpload): ?>
                                                <span class="text-green-600 font-semibold">✔ Sudah Upload</span>
                                            <?php else: ?>
                                                <form action="praktikum_saya.php" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
                                                    <input type="hidden" name="modul_id" value="<?= $modul_id ?>">
                                                    <input type="file" name="laporan" required class="text-xs border p-1 rounded">
                                                    <button type="submit" class="bg-[#5eaaa8] text-white px-2 py-1 rounded text-xs hover:bg-[#4a908f]">
                                                        Upload
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded">
            Kamu belum mendaftar ke praktikum manapun.
        </div>
    <?php endif; ?>
</div>
