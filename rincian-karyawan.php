<?php
session_start();
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("location: index.php");
}
include "service/database.php"; // Menghubungkan file db.php

// Ambil ID karyawan dari parameter GET
$id_karyawan = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$id_karyawan || $id_karyawan <= 0) {
    die("ID karyawan tidak valid.");
}

// Query untuk mendapatkan data karyawan
$sql_karyawan = "SELECT * FROM karyawan WHERE id_karyawan = $id_karyawan";
$result_karyawan = $db->query($sql_karyawan);

if ($result_karyawan->num_rows == 0) {
    die("Data karyawan tidak ditemukan.");
}

$karyawan_data = $result_karyawan->fetch_assoc();

// Query untuk mendapatkan total simpanan per jenis
$sql_simpanan = "SELECT 
                    jenis_simpanan.nama_jenis,
                    SUM(simpanan.jumlah_simpan) AS total_simpanan
                 FROM 
                    simpanan
                 INNER JOIN 
                    jenis_simpanan ON simpanan.id_jenis = jenis_simpanan.id_jenis
                 WHERE 
                    simpanan.id_karyawan = $id_karyawan
                 GROUP BY 
                    jenis_simpanan.nama_jenis";
$result_simpanan = $db->query($sql_simpanan);

// Query untuk mendapatkan data pinjaman dari tabel pinjaman
$sql_pinjaman = "SELECT 
                    id_pinjaman,
                    jumlah_pinjaman,
                    status_pinjaman
                 FROM 
                    pinjaman
                 WHERE 
                    id_karyawan = $id_karyawan";
$result_pinjaman = $db->query($sql_pinjaman);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rincian Anggota</title>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
  <script defer src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script defer src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
  <script defer src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.js"></script>
</head>
<body>
<?php include "layout/header-login.php" ?>
<main class="min-h-screen py-10" data-theme="dark">
  <div class="max-w-5xl mx-auto p-4 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-3xl font-semibold text-gray-800 dark:text-white">Rincian Anggota</h2>
      <a href="karyawan.php" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-md">
        Kembali
      </a>
    </div>

    <!-- Data Pribadi Anggota -->
    <div class="mb-6">
      <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Data Pribadi</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <p class="text-gray-600 dark:text-gray-400"><strong>Nama:</strong> <?php echo htmlspecialchars($karyawan_data['nama']); ?></p>
          <p class="text-gray-600 dark:text-gray-400"><strong>Alamat:</strong> <?php echo htmlspecialchars($karyawan_data['alamat']); ?></p>
          <p class="text-gray-600 dark:text-gray-400"><strong>No. Telepon:</strong> <?php echo htmlspecialchars($karyawan_data['no_telepon']); ?></p>
        </div>
        <div>
          <p class="text-gray-600 dark:text-gray-400"><strong>Status Aktif:</strong> <?php echo $karyawan_data['status_aktif'] == 'aktif' ? 'Aktif' : 'Nonaktif'; ?></p>
          <p class="text-gray-600 dark:text-gray-400"><strong>Tanggal Pendaftaran:</strong> <?php echo date('d F Y', strtotime($karyawan_data['tanggal_pendaftaran'])); ?></p>
        </div>
      </div>
    </div>

    <!-- Rincian Simpanan -->
    <div class="mb-6">
      <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Rincian Simpanan</h3>
      <table class="table table-striped min-w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg overflow-hidden shadow-lg">
        <thead class="bg-gray-50 dark:bg-gray-800">
          <tr>
            <th scope="col" class="px-4 py-2">Jenis Simpanan</th>
            <th scope="col" class="px-4 py-2">Total (Rp)</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result_simpanan->num_rows > 0): ?>
            <?php while ($row = $result_simpanan->fetch_assoc()): ?>
              <tr>
                <td class="px-4 py-2"><?php echo htmlspecialchars($row['nama_jenis']); ?></td>
                <td class="px-4 py-2 text-right">
                  Rp <?php echo number_format($row['total_simpanan'], 0, ',', '.'); ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="2" class="px-4 py-2 text-center">Tidak ada data simpanan</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Rincian Pinjaman -->
    <div class="mb-6">
      <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Rincian Pinjaman</h3>
      <table class="table table-striped min-w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg overflow-hidden shadow-lg">
        <thead class="bg-gray-50 dark:bg-gray-800">
          <tr>
            <th scope="col" class="px-4 py-2">ID Pinjaman</th>
            <th scope="col" class="px-4 py-2">Jumlah Pinjaman (Rp)</th>
            <th scope="col" class="px-4 py-2">Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result_pinjaman->num_rows > 0): ?>
            <?php while ($row = $result_pinjaman->fetch_assoc()): ?>
              <tr>
                <td class="px-4 py-2"><?php echo htmlspecialchars($row['id_pinjaman']); ?></td>
                <td class="px-4 py-2 text-left">
                  Rp <?php echo number_format($row['jumlah_pinjaman'], 0, ',', '.'); ?>
                </td>
                <td class="px-4 py-2">
                  <?php echo $row['status_pinjaman'] == 'lunas' ? '<span class="text-green-500">Lunas</span>' : '<span class="text-red-500">Belum Lunas</span>'; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="3" class="px-4 py-2 text-center">Tidak ada data pinjaman</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
</body>
</html>