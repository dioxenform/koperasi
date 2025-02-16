<?php
    session_start();
    if (isset($_POST["logout"])) {
        session_unset();
        session_destroy();
        header("location: index.php");
    }

    include "service/database.php"; // Menghubungkan file db.php

    $jenis_simpanan = isset($_GET['jenis_simpanan']) ? $_GET['jenis_simpanan'] : '';

    // Query untuk mengambil data simpanan dan menghitung total berdasarkan jenis simpanan
    $sql = "SELECT simpanan.id_simpanan,
                    simpanan.id_karyawan, 
                    karyawan.nama AS nama_karyawan,
                    jenis_simpanan.nama_jenis,
                    simpanan.tanggal_simpanan,
                    simpanan.jumlah_simpan
              FROM 
                    simpanan
              INNER JOIN
                    karyawan ON simpanan.id_karyawan = karyawan.id_karyawan
              INNER JOIN 
                    jenis_simpanan ON simpanan.id_jenis = jenis_simpanan.id_jenis
              WHERE ('$jenis_simpanan' = '' OR jenis_simpanan.nama_jenis = '$jenis_simpanan')
              ORDER BY 
                      simpanan.id_karyawan, jenis_simpanan.nama_jenis";

    $result = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simpanan Anggota</title>
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
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-3xl font-semibold text-gray-800 dark:text-white">Riwayat Simpanan</h2>
      <div class="flex items-center space-x-4">
        <a href="simpanan.php" class="relative group">
          <button class="relative px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-500 to-teal-500 rounded-full shadow-md hover:from-teal-500 hover:to-green-500 hover:shadow-lg transform hover:scale-105 transition-transform duration-300">
            <span class="flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current text-white group-hover:rotate-90 transition-transform duration-300" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
              Riwayat Simpanan
            </span>
          </button>
        </a>
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

    <table id="example" class="table table-striped min-w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg overflow-hidden shadow-lg">
      <thead class="bg-gray-50 dark:bg-gray-800">
        <tr>
          <th scope="col">ID Karyawan</th>
          <th scope="col">Nama Karyawan</th>
          <th scope="col">Pokok</th>
          <th scope="col">Wajib</th>
          <th scope="col">Sukarela</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody id="karyawanList" class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
      <?php
        $karyawan_simpanan = [];
        while ($row = $result->fetch_assoc()) {
            $karyawan_id = $row['id_karyawan'];
            $jenis_simpanan = $row['nama_jenis'];
            $jumlah_simpan = $row['jumlah_simpan'];

            if (!isset($karyawan_simpanan[$karyawan_id])) {
                $karyawan_simpanan[$karyawan_id] = [
                    'pokok' => 0,
                    'wajib' => 0,
                    'sukarela' => 0
                ];
            }

            if ($jenis_simpanan == 'pokok') {
                $karyawan_simpanan[$karyawan_id]['pokok'] += $jumlah_simpan;
            } elseif ($jenis_simpanan == 'wajib') {
                $karyawan_simpanan[$karyawan_id]['wajib'] += $jumlah_simpan;
            } elseif ($jenis_simpanan == 'sukarela') {
                $karyawan_simpanan[$karyawan_id]['sukarela'] += $jumlah_simpan;
            }
        }

        foreach ($karyawan_simpanan as $karyawan_id => $simpanan) {
            // Ambil nama karyawan
            $sql_karyawan = "SELECT nama FROM karyawan WHERE id_karyawan = '$karyawan_id'";
            $result_karyawan = $db->query($sql_karyawan);
            if ($result_karyawan->num_rows > 0) {
                $row_karyawan = $result_karyawan->fetch_assoc();
                $nama_karyawan = $row_karyawan['nama'];
            }
      ?>
          <tr>
            <td><?php echo $karyawan_id; ?></td>
            <td><?php echo $nama_karyawan; ?></td>
            <td><?php echo number_format($simpanan['pokok'], 0, ',', '.'); ?></td>
            <td><?php echo number_format($simpanan['wajib'], 0, ',', '.'); ?></td>
            <td><?php echo number_format($simpanan['sukarela'], 0, ',', '.'); ?></td>
            <td>
            <?php echo '<a href="info-simpanan-karyawan.php?id_karyawan=' . $karyawan_id . '" class="btn btn-sm btn-warning" target="_blank">Cetak</a>'; ?>
            </td>
          </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
</main>

</body>
</html>
