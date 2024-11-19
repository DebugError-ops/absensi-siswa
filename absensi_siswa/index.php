<?php
include 'config.php';

// Menambah siswa
if (isset($_POST['add_siswa'])) {
    $nama = $_POST['nama'];
    $conn->query("INSERT INTO siswa (nama) VALUES ('$nama')");
}

// Menghapus siswa
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM siswa WHERE id=$id");
}

// Mencatat absensi
if (isset($_POST['absen'])) {
    $siswa_id = $_POST['siswa_id'];
    $status = $_POST['status'];
    $tanggal = date('Y-m-d');
    $conn->query("INSERT INTO absensi (siswa_id, tanggal, status) VALUES ('$siswa_id', '$tanggal', '$status')");
}

// Menampilkan siswa
$siswa_result = $conn->query("SELECT * FROM siswa");

// Menampilkan absensi hari ini
$today = date('Y-m-d');
$absensi_today = $conn->query("SELECT s.nama, a.status FROM absensi a JOIN siswa s ON a.siswa_id = s.id WHERE a.tanggal = '$today'");

// Menampilkan absensi per bulan
$absensi_per_bulan = $conn->query("SELECT s.nama, a.status, COUNT(*) as total FROM absensi a JOIN siswa s ON a.siswa_id = s.id WHERE MONTH(a.tanggal) = MONTH(CURRENT_DATE()) GROUP BY s.id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Sistem Absensi Siswa</title>
</head>
<body>
    <h1>Sistem Absensi Siswa</h1>

    <h2>Tambah Siswa</h2>
    <form method="POST">
        <input type="text" name="nama" required placeholder="Nama Siswa">
        <button type="submit" name="add_siswa">Tambah</button>
    </form>

    <h2>Daftar Siswa</h2>
    <ul>
        <?php while ($row = $siswa_result->fetch_assoc()): ?>
            <li>
                <?= $row['nama'] ?>
                <a href="?delete=<?= $row['id'] ?>">Hapus</a>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="siswa_id" value="<?= $row['id'] ?>">
                    <select name="status" required>
                        <option value="Hadir">Hadir</option>
                        <option value="Tidak Hadir">Tidak Hadir</option>
                    </select>
                    <button type="submit" name="absen">Absen</button>
                </form>
            </li>
        <?php endwhile; ?>
    </ul>

    <h2>Absensi Hari Ini</h2>
    <ul>
        <?php while ($row = $absensi_today->fetch_assoc()): ?>
            <li><?= $row['nama'] ?> - <?= $row['status'] ?></li>
        <?php endwhile; ?>
    </ul>

    <h2>Absensi Bulan Ini</h2>
<ul>
    <?php while ($row = $absensi_per_bulan->fetch_assoc()): ?>
        <li><?= $row['nama'] ?> - <?= $row['status'] ?> (Total: <?= $row['total'] ?>)</li>
    <?php endwhile; ?>
</ul>



