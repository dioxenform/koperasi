<?php
    session_start();
    if(isset($_POST["logout"])){
        session_unset();
        session_destroy();
        header("location: index.php");
    }

    include "service/database.php"; // Menghubungkan file db.php


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Karyawan</title>
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
<main class=" min-h-screen py-10" data-theme="dark">
  <div class="max-w-5xl mx-auto p-4 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-3xl font-semibold text-gray-800 dark:text-white">Daftar Karyawan</h2>
      <div class="flex items-center space-x-4">
        <!-- Tambah Karyawan -->
        <a href="tambah-karyawan.php" class="relative group">
          <button class="relative px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-purple-500 rounded-full shadow-md hover:from-purple-500 hover:to-blue-500 hover:shadow-lg transform hover:scale-105 transition-transform duration-300">
            <span class="flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current text-white group-hover:rotate-90 transition-transform duration-300" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
              Tambah Karyawan
            </span>
          </button>
        </a>
      </div>
    </div>

    <!-- Tabel -->
    <table id="example" class="table table=strped  min-w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg overflow-hidden shadow-lg">
      <thead class="bg-gray-50 dark:bg-gray-800">
        <tr>
          <th scope="col" class="">
            ID Karyawan
          </th>
          <th scope="col" class="">
            Nama
          </th>
          <th scope="col" class="">
            NIK
          </th>
          <th scope="col" class="">
            Alamat
          </th>
          <th scope="col" class="">
            No. Telepon
          </th>
          <th scope="col" class="">
            Tanggal Bergabung
          </th>
          <th scope="col" class="">
            Status
          </th>
          <th scope="col" class="text-right">
            Action
          </th>
        </tr>
      </thead>
        <tbody id="karyawanList" class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
          <?php
          // Query untuk mengambil data karyawan
          $query = "SELECT * FROM karyawan"; 
          $result = mysqli_query($db, $query);

          // Cek apakah ada data karyawan
          if (mysqli_num_rows($result) > 0) {
          // Menampilkan data karyawan
          while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">';
            echo '<td style="text-align: center">' . $row['id_karyawan'] . '</td>';
            echo '<td>' . $row['nama'] . '</td>';
            echo '<td>' . $row['nik'] . '</td>';
            echo '<td class="max-w-[200px] break-words overflow-hidden text-ellipsis">' . $row['alamat'] . '</td>';
            echo '<td>' . $row['no_telepon'] . '</td>';
            echo '<td>' . date('d F Y', strtotime($row['tanggal_pendaftaran'])) . '</td>';
            echo '<td>' . $row['status_aktif'] . '</td>';
            echo '<td class="flex justify-between">';
            echo '<a href="edit-karyawan.php?id=' . $row['id_karyawan'] . '" class="btn btn-sm mr-2 btn-warning">Edit</a>';
            echo '<a href="rincian-karyawan.php?id=' . $row['id_karyawan'] . '" class="btn btn-sm mr-2 btn-warning">Rincian</a>';
            echo '</td>';
            echo '</tr>';
        
        

              // Baris informasi tambahan (misalnya simpanan dan pinjaman)
              // echo '<tr id="info-' . $row['id'] . '" class="hidden">';
              // echo '<td colspan="5" class="py-2 px-4 border-b bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">';
              // echo '<p>Simpanan: ' . number_format($row['simpanan'], 0, ',', '.') . '</p>';
              // echo '<p>Pinjaman: ' . number_format($row['pinjaman'], 0, ',', '.') . '</p>';
              // echo '</td>';
              // echo '</tr>';
              }
            } else {
              echo '<tr><td colspan="7" class="py-2 px-4 text-center">Tidak ada data karyawan</td></tr>';
              }
              ?>
        </tbody>
    </table>
  </div>
</main>
</body>
</html>
