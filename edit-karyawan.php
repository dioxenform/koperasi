<?php
session_start();
if(isset($_POST["logout"])){
    session_unset();
    session_destroy();
    header("location: index.php");
}
include 'service/database.php'; // Koneksi ke database

// Ambil ID Karyawan dari URL
$id_karyawan = $_GET['id'];

// Ambil data karyawan
$query_karyawan = "SELECT * FROM karyawan WHERE id_karyawan = '$id_karyawan'";
$result_karyawan = mysqli_query($db, $query_karyawan);
$karyawan = mysqli_fetch_assoc($result_karyawan);

// Ambil daftar simpanan
$query_simpanan = "SELECT s.id_simpanan, s.id_jenis, js.nama_jenis, s.jumlah_simpan, s.tanggal_simpanan 
                    FROM simpanan s 
                    JOIN jenis_simpanan js ON s.id_jenis = js.id_jenis
                    WHERE s.id_karyawan = '$id_karyawan'";
$result_simpanan = mysqli_query($db, $query_simpanan);

// Ambil daftar pinjaman
$query_pinjaman = "SELECT * FROM pinjaman WHERE id_karyawan = '$id_karyawan'";
$result_pinjaman = mysqli_query($db, $query_pinjaman);

// Proses update data karyawan
if (isset($_POST['update_karyawan'])) {
    $nama = $_POST['nama'];
    $nik = $_POST['nik'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];
    $status_aktif = $_POST['status_aktif'];

    $query_update_karyawan = "UPDATE karyawan SET 
                                nama = '$nama',
                                nik = '$nik',
                                alamat = '$alamat',
                                no_telepon = '$no_telepon',
                                status_aktif = '$status_aktif'
                              WHERE id_karyawan = '$id_karyawan'";

    if (mysqli_query($db, $query_update_karyawan)) {
        echo "<script>alert('Data karyawan berhasil diperbarui!'); window.location.href='karyawan.php?id=$id_karyawan';</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Karyawan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
    <?php include "layout/header-login.php"; ?>
    <div class="max-w-4xl mx-auto p-6 bg-gray-800 shadow-md rounded-lg mt-10">
        <h2 class="text-2xl font-semibold mb-4 text-center text-blue-400">Edit Data Karyawan</h2>
        <!-- Form Edit Karyawan -->
        <form method="POST" action="">
            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-300 mb-2">Nama:</label>
                <input type="text" id="nama" name="nama" value="<?= $karyawan['nama'] ?>" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="nik" class="block text-sm font-medium text-gray-300 mb-2">NIK:</label>
                <input type="text" id="nik" name="nik" value="<?= $karyawan['nik'] ?>" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="alamat" class="block text-sm font-medium text-gray-300 mb-2">Alamat:</label>
                <textarea id="alamat" name="alamat" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?= $karyawan['alamat'] ?></textarea>
            </div>
            <div class="mb-4">
                <label for="no_telepon" class="block text-sm font-medium text-gray-300 mb-2">No Telepon:</label>
                <input type="text" id="no_telepon" name="no_telepon" value="<?= $karyawan['no_telepon'] ?>" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="status_aktif" class="block text-sm font-medium text-gray-300 mb-2">Status Aktif:</label>
                <select id="status_aktif" name="status_aktif" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Aktif" <?= ($karyawan['status_aktif'] == 'Aktif') ? 'selected' : '' ?>>Aktif</option>
                    <option value="nonaktif" <?= ($karyawan['status_aktif'] == 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
            <button type="submit" name="update_karyawan" class="w-full p-3 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 transition duration-300">
                Simpan Perubahan
            </button>
        </form>
    </div>
</body>
</html>
