<?php
session_start();
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("location: index.php");
}
include "service/database.php"; // Menghubungkan file db.php

$sql = "SELECT 
            simpanan.id_simpanan,
            simpanan.id_karyawan, 
            jenis_simpanan.nama_jenis,
            karyawan.nama AS nama_karyawan,
            simpanan.tanggal_simpanan,
            simpanan.jumlah_simpan
        FROM 
            simpanan
        INNER JOIN 
            jenis_simpanan ON simpanan.id_jenis = jenis_simpanan.id_jenis
        INNER JOIN 
            karyawan ON simpanan.id_karyawan = karyawan.id_karyawan";
$result = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Simpanan Anggota</title>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
  <script defer src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script defer src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
  <script defer src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.js"></script>
  <script defer src="javascript/table.js"></script>
</head>
<body>
<?php include "layout/header-login.php" ?>
<main class="min-h-screen py-10" data-theme="dark">
  <div class="max-w-5xl mx-auto p-4 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-3xl font-semibold text-gray-800 dark:text-white">Riwayat Simpanan</h2>
      <div class="flex items-center space-x-4">
        <!-- Simpanan Anggota -->
        <a href="simpanan-anggota.php" class="relative group">
          <button class="relative px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-500 to-teal-500 rounded-full shadow-md hover:from-teal-500 hover:to-green-500 hover:shadow-lg transform hover:scale-105 transition-transform duration-300">
            <span class="flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current text-white group-hover:rotate-90 transition-transform duration-300" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
              Simpanan Anggota
            </span>
          </button>
        </a>
        <!-- Tambah Karyawan -->
        <a href="tambah-simpanan.php" class="relative group">
          <button class="relative px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-purple-500 rounded-full shadow-md hover:from-purple-500 hover:to-blue-500 hover:shadow-lg transform hover:scale-105 transition-transform duration-300">
            <span class="flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current text-white group-hover:rotate-90 transition-transform duration-300" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
              Tambah Simpanan
            </span>
          </button>
        </a>
      </div>
    </div>
    <!-- Tabel -->
    <table id="example" class="table table-striped min-w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg overflow-hidden shadow-lg">
      <thead class="bg-gray-50 dark:bg-gray-800">
        <tr>
          <th scope="col" class="">ID Simpanan</th>
          <th scope="col" class="">ID Karyawan</th>
          <th scope="col" class="">Nama Karyawan</th>
          <th scope="col" class="">Jumlah</th>
          <th scope="col" class="">Jenis Simpanan</th>
          <th scope="col" class="">Tanggal Simpan</th>
          <th scope="col" class="">Action</th>
        </tr>
      </thead>
      <tbody id="karyawanList" class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
        <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr class="border-b border-gray-200">
            <td class="px-4 py-2"><?php echo $row['id_simpanan']; ?></td>
            <td class="px-4 py-2"><?php echo $row['id_karyawan']; ?></td>
            <td class="px-4 py-2"><?php echo $row['nama_karyawan']; ?></td>
            <td class="px-4 py-2">
              <?php
              $jumlahSimpanan = $row['jumlah_simpan'];
              echo number_format($jumlahSimpanan, 0, ',', '.'); // Menampilkan jumlah dengan format 1.000.000
              ?>
            </td>
            <td class="px-4 py-2"><?php echo $row['nama_jenis']; ?></td>
            <td class="px-4 py-2"><?php echo $row['tanggal_simpanan']; ?></td>
            <td class="px-4 py-2 whitespace-nowrap text-center grid gap-2">
              <!-- Tombol Edit -->
              <a href="edit-simpanan.php?id=<?php echo $row['id_simpanan']; ?>" class="inline-flex items-center px-2 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                Edit
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="px-4 py-2 text-center">Tidak ada data</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>
</body>
</html>