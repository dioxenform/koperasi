<?php
    session_start();
    if(isset($_POST["logout"])){
        session_unset();
        session_destroy();
        header("location: index.php");
    }

    $daftar_message = "";

include "service/database.php"; // Menghubungkan file db.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $nama = $_POST['nama'];
    $nik = $_POST['nik'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];
    $tanggal_pendaftaran = $_POST['tanggal_pendaftaran'];

    // Query untuk menyimpan data ke database
    $sql = "INSERT INTO karyawan (nama, nik, alamat, no_telepon, tanggal_pendaftaran) 
            VALUES ('$nama', '$nik', '$alamat', '$no_telepon', '$tanggal_pendaftaran')";

    if ($db->query($sql) === TRUE) {
        $daftar_message = "Data berhasil disimpan!";
    } else {
        $daftar_message = "Error: " . $sql . "<br>" . $db->error;
    }
}


?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Penambahan Anggota Baru</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/daisyui@1.7.0/dist/full.js"></script>
</head>
  <body>
      <?php include "layout/header-login.php"?>
      <main data-theme="dark">
        <div class="flex justify-center items-center min-h-screen ">
          <form class="max-w-md w-full border-2 border-gray-500 rounded-lg px-8 py-10  shadow-2xl" method="POST" action="" data-theme="dark">
            <i class="text-white"><?= $daftar_message ?></i>
            <div class="relative z-0 w-full mb-5 group">
              <input 
                type="text" 
                name="nama" 
                id="nama" 
                class="block py-2 px-3 w-full text-sm text-gray-200 bg-transparent border-0 border-b-2 border-gray-500 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 focus:text-white peer rounded-md" 
                placeholder=" " 
                required 
                autocomplete="off"
              />
              <label 
                for="nama" 
                class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:text-white-200 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                >
                Nama Lengkap
              </label>
            </div>

            <div class="relative z-0 w-full mb-5 group">
              <input 
              type="text" 
              name="nik" 
              id="nik" 
              class="block py-2 px-3 w-full text-sm text-gray-200 bg-transparent border-0 border-b-2 border-gray-500 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 focus:text-white peer rounded-md" 
              placeholder=" " 
              required 
              autocomplete="off"
              />
              <label 
                for="nik" 
                class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:text-white-200 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
              >
                NIK
              </label>
            </div>

            <div class="relative z-0 w-full mb-5 group">
              <input 
                type="text" 
                name="alamat" 
                id="alamat" 
                class="block py-2 px-3 w-full text-sm text-gray-200 bg-transparent border-0 border-b-2 border-gray-500 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 focus:text-white peer rounded-md" 
                placeholder=" " 
                required 
                autocomplete="off"
              />
              <label 
                for="alamat" 
                class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:text-white-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
              >
                Alamat
              </label>
            </div>

            <div class="relative z-0 w-full mb-5 group">
              <input 
                type="text" 
                name="no_telepon" 
                id="no_telepon" 
                class="block py-2 px-3 w-full text-sm text-gray-200 bg-transparent border-0 border-b-2 border-gray-500 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 focus:text-white peer rounded-md" 
                placeholder=" " 
                required 
                autocomplete="off"
              />
              <label 
                for="no_telepon" 
                class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:text-white peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
              >
                No. Telepon
              </label>
            </div>


            <div class="relative z-0 w-full mb-5 group">
              <input type="date" name="tanggal_pendaftaran" id="tanggal_pendaftaran" class="block py-2 px-3 w-full text-sm text-white bg-transparent border-0 border-b-2 border-gray-500 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer rounded-md" placeholder=" " required />
              <label for="tanggal_pendaftaran" class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Tanggal Pendaftaran</label>
            </div>

            <!-- Submit Button -->
            <button type="button" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-semibold" onclick="showConfirmationModal()">Submit</button>
             <!-- Modal Konfirmasi -->
             <div id="confirmation-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center hidden">
    <div class="bg-gray-900 p-6 rounded-lg shadow-xl w-96 text-center border border-gray-700">
        <h2 class="text-xl font-semibold text-white">Konfirmasi Karyawan</h2>
        <p class="text-gray-300 mt-2">Apakah Anda yakin sudah mengisi form dengan benar ?</p>
        <div class="mt-5 flex justify-center space-x-4">
            <button class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition" onclick="event.preventDefault() ; closeModal() ;">Batal</button>
            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg transition">Ya, Sudah</button>
        </div>
    </div>
</div>
          </form>
        </div>
      </main>
      <script>
        
// Fungsi untuk membuka modal konfirmasi
function showConfirmationModal() {
            document.getElementById("confirmation-modal").style.display = "flex";
        }

        // Fungsi untuk menutup modal konfirmasi
        function closeModal() {
            document.getElementById("confirmation-modal").style.display = "none";
        }
      </script>
  </body>
</html>