<?php
session_start();
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("location: index.php");
}
include "service/database.php"; // Menghubungkan file db.php

// Ambil ID Pinjaman dari URL
if (!isset($_GET['id'])) {
    header("location: pinjaman.php"); // Redirect jika ID tidak ada
    exit;
}

$id_pinjaman = $_GET['id'];

// Query untuk mendapatkan data pinjaman berdasarkan ID
$query = "SELECT 
              pinjaman.id_pinjaman,
              pinjaman.id_karyawan,
              pinjaman.jumlah_pinjaman,
              pinjaman.tenor,
              pinjaman.tanggal_pinjaman,
              pinjaman.cicilan,
              pinjaman.total_pinjaman,
              pinjaman.status_pinjaman,
              pinjaman.deadline,
              karyawan.nama AS nama_karyawan,
              karyawan.nik,
              karyawan.alamat,
              karyawan.no_telepon
          FROM 
              pinjaman
          INNER JOIN 
              karyawan ON pinjaman.id_karyawan = karyawan.id_karyawan
          WHERE 
              pinjaman.id_pinjaman = '$id_pinjaman'";
$result = $db->query($query);

if ($result->num_rows === 0) {
    header("location: pinjaman.php"); 
    exit;
}

$data = $result->fetch_assoc();

// Hitung jumlah cicilan yang sudah dibayar
$query_cicilan = "SELECT * FROM transaksi WHERE id_pinjaman = '$id_pinjaman' AND jenis_transaksi = 'cicilan' ORDER BY tanggal_transaksi ASC";
$result_cicilan = $db->query($query_cicilan);
$cicilan_terbayar = [];
while ($row = $result_cicilan->fetch_assoc()) {
    $cicilan_terbayar[$row['bulan']] = $row['status_transaksi'];
}

// Proses update status pembayaran cicilan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $bulan = $_POST['bulan'];
    $status_pembayaran = $_POST['status_pembayaran'];

    // Pastikan hanya bisa membayar bulan berikutnya jika bulan sebelumnya sudah lunas
    if ($bulan > 1 && (!isset($cicilan_terbayar[$bulan - 1]) || $cicilan_terbayar[$bulan - 1] !== 'Lunas')) {
        echo "<script>alert('Bulan sebelumnya belum lunas!');</script>";
    } else {
        // Simpan status pembayaran baru
        $query_update = "INSERT INTO transaksi (id_pinjaman, id_karyawan, jenis_transaksi, tanggal_transaksi, jumlah_transaksi, bulan, status_transaksi)
                         VALUES ('$id_pinjaman', '{$data['id_karyawan']}', 'cicilan', NOW(), '{$data['cicilan']}', '$bulan', '$status_pembayaran')
                         ON DUPLICATE KEY UPDATE status_transaksi = '$status_pembayaran'";
        if ($db->query($query_update)) {
            echo "<script>alert('Status pembayaran berhasil diperbarui!'); window.location.href='rincian-pinjaman.php?id=$id_pinjaman';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui status pembayaran!');</script>";
        }
    }
}

// Proses update status pinjaman
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_pinjaman_status'])) {
    $new_status_pinjaman = $_POST['status_pinjaman'];

    // Validasi status pinjaman
    $allowed_statuses = ['Pending', 'Aktif', 'Ditolak', 'Lunas'];
    if (!in_array($new_status_pinjaman, $allowed_statuses)) {
        echo "<script>alert('Status pinjaman tidak valid!');</script>";
    } else {
        // Update status pinjaman di database
        $query_update_status = "UPDATE pinjaman SET status_pinjaman = '$new_status_pinjaman' WHERE id_pinjaman = '$id_pinjaman'";
        if ($db->query($query_update_status)) {
            echo "<script>alert('Status pinjaman berhasil diperbarui!'); window.location.href='rincian-pinjaman.php?id=$id_pinjaman';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui status pinjaman!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rincian Pinjaman</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <script defer src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php include "layout/header-login.php" ?>
<main class="min-h-screen py-10 bg-gray-900 text-white">
  <div class="max-w-4xl mx-auto p-6 bg-gray-800 shadow-lg rounded-lg">
    <!-- Header -->
    <h2 class="text-2xl font-semibold mb-6 text-blue-400">Rincian Pinjaman</h2>

    <!-- Data Karyawan -->
    <div class="mb-6">
      <h3 class="text-xl font-medium text-gray-300 mb-4">Informasi Karyawan</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-400">Nama Karyawan:</label>
          <p class="text-lg text-white"><?= $data['nama_karyawan'] ?></p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-400">NIK:</label>
          <p class="text-lg text-white"><?= $data['nik'] ?></p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-400">Alamat:</label>
          <p class="text-lg text-white"><?= $data['alamat'] ?></p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-400">No Telepon:</label>
          <p class="text-lg text-white"><?= $data['no_telepon'] ?></p>
        </div>
      </div>
    </div>

    <!-- Data Pinjaman -->
    <div class="mb-6">
      <h3 class="text-xl font-medium text-gray-300 mb-4">Informasi Pinjaman</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-400">Jumlah Pinjaman:</label>
          <p class="text-lg text-white">Rp <?= number_format($data['jumlah_pinjaman'], 0, ',', '.') ?></p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-400">Tenor:</label>
          <p class="text-lg text-white"><?= $data['tenor'] ?> Bulan</p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-400">Cicilan/Bulan:</label>
          <p class="text-lg text-white">Rp <?= number_format($data['cicilan'], 0, ',', '.') ?></p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-400">Total Pinjaman:</label>
          <p class="text-lg text-white">Rp <?= number_format($data['total_pinjaman'], 0, ',', '.') ?></p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-400">Status Pinjaman:</label>
          <form method="POST" action="" class="inline-flex items-center space-x-2">
            <select name="status_pinjaman" class="px-2 py-1 border border-gray-500 rounded-md bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="Pending" <?= $data['status_pinjaman'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
              <option value="Aktif" <?= $data['status_pinjaman'] === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
              <option value="Ditolak" <?= $data['status_pinjaman'] === 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
              <option value="Lunas" <?= $data['status_pinjaman'] === 'Lunas' ? 'selected' : '' ?>>Lunas</option>
            </select>
            <button type="submit" name="update_pinjaman_status" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              Simpan
            </button>
          </form>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-400">Tanggal Pengajuan:</label>
          <p class="text-lg text-white"><?= $data['tanggal_pinjaman'] ?></p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-400">Deadline:</label>
          <p class="text-lg text-white"><?= $data['deadline'] ?></p>
        </div>
      </div>
    </div>

    <!-- Rincian Pembayaran Per Bulan -->
    <div class="mb-6">
      <h3 class="text-xl font-medium text-gray-300 mb-4">Rincian Pembayaran Per Bulan</h3>
      <table class="w-full table-auto divide-y divide-gray-700">
        <thead class="bg-gray-700">
          <tr>
            <th class="px-4 py-2 text-left text-sm font-medium text-white">Bulan</th>
            <th class="px-4 py-2 text-left text-sm font-medium text-white">Cicilan</th>
            <th class="px-4 py-2 text-left text-sm font-medium text-white">Status</th>
            <th class="px-4 py-2 text-left text-sm font-medium text-white">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-gray-800 divide-y divide-gray-700">
          <?php for ($bulan = 1; $bulan <= $data['tenor']; $bulan++): ?>
            <tr>
              <td class="px-4 py-2 whitespace-nowrap">Bulan <?= $bulan ?></td>
              <td class="px-4 py-2 whitespace-nowrap">Rp <?= number_format($data['cicilan'], 0, ',', '.') ?></td>
              <td class="px-4 py-2 whitespace-nowrap">
                <?php if (isset($cicilan_terbayar[$bulan]) && $cicilan_terbayar[$bulan] === 'Lunas'): ?>
                  <span class="text-green-500">Sudah Terbayar</span>
                <?php else: ?>
                  <span class="text-red-500">Belum Terbayar</span>
                <?php endif; ?>
              </td>
              <td class="px-4 py-2 whitespace-nowrap">
                <?php if (!isset($cicilan_terbayar[$bulan]) || $cicilan_terbayar[$bulan] !== 'Lunas'): ?>
                  <form method="POST" action="" class="inline-flex items-center space-x-2">
                    <input type="hidden" name="bulan" value="<?= $bulan ?>">
                    <select name="status_pembayaran" class="px-2 py-1 border border-gray-500 rounded-md bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                      <option value="Belum Lunas" <?= (!isset($cicilan_terbayar[$bulan]) || $cicilan_terbayar[$bulan] === 'Belum Lunas') ? 'selected' : '' ?>>Belum Terbayar</option>
                      <option value="Lunas" <?= (isset($cicilan_terbayar[$bulan]) && $cicilan_terbayar[$bulan] === 'Lunas') ? 'selected' : '' ?>>Sudah Terbayar</option>
                    </select>
                    <button type="submit" name="update_status" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                      Simpan
                    </button>
                  </form>
                <?php else: ?>
                  <span>-</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endfor; ?>
        </tbody>
      </table>
    </div>

    <!-- Tombol Kembali -->
    <div class="mt-6">
      <a href="pinjaman.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        Kembali ke Daftar Pinjaman
      </a>
    </div>
  </div>
</main>
</body>
</html>