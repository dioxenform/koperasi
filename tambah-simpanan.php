<?php
session_start();
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("location: index.php");
}
$daftar_message = "";
include "service/database.php";
date_default_timezone_set('Asia/Jakarta');
$tanggal_simpanan = date('Y-m-d H:i:s');

// Mengambil data nama karyawan
$sql_karyawan = "SELECT id_karyawan, nama FROM karyawan";
$result_karyawan = $db->query($sql_karyawan);

// Memproses form jika disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi apakah field telah diisi
    if (isset($_POST['id_karyawan']) && isset($_POST['pokok']) && isset($_POST['sukarela']) && isset($_POST['wajib'])) {
        $karyawan = $_POST['id_karyawan'];
        $pokok = $_POST['pokok'];
        $sukarela = $_POST['sukarela'];
        $wajib = $_POST['wajib'];

        // Array untuk menyimpan jenis simpanan dan jumlahnya
        $jenis_simpanan = [
            1 => $pokok,  // ID jenis simpanan untuk Pokok
            2 => $sukarela, // ID jenis simpanan untuk Sukarela
            3 => $wajib    // ID jenis simpanan untuk Wajib
        ];

        // Loop untuk menyimpan setiap jenis simpanan
        foreach ($jenis_simpanan as $id_jenis => $jumlah) {
            if ($jumlah > 0) { // Hanya simpan jika jumlah lebih dari 0
                $sql = "INSERT INTO simpanan (id_karyawan, id_jenis, tanggal_simpanan, jumlah_simpan) 
                        VALUES ('$karyawan', '$id_jenis', '$tanggal_simpanan', '$jumlah')";
                if ($db->query($sql) !== TRUE) {
                    $daftar_message = "Error: " . $sql . "<br>" . $db->error;
                    break;
                }
            }
        }

        if (empty($daftar_message)) {
            $daftar_message = "Data berhasil disimpan!";
        }
    } else {
        $daftar_message = "Mohon isi semua field.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Penambahan Simpanan Karyawan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/daisyui@1.7.0/dist/full.js"></script>
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
</head>
<body>
  <?php include "layout/header-login.php"; ?>
  <main data-theme="dark">
    <div class="flex justify-center items-center min-h-screen">
      <form class="max-w-md w-full border-2 border-gray-500 rounded-lg px-8 py-10 shadow-2xl" method="POST" action="" data-theme="dark">
        <i class="text-white"><?= $daftar_message ?></i>
        
        <!-- Nama Karyawan -->
        <div class="mb-4">
          <label for="karyawan" class="block text-sm font-medium mb-2">Nama Karyawan</label>
          <select name="id_karyawan" id="karyawan" class="w-full p-2 bg-gray-700 border border-gray-600 rounded-md text-white">
            <option value="" disabled selected>Pilih Karyawan</option>
            <?php
            while ($row = $result_karyawan->fetch_assoc()) {
              echo "<option value='{$row['id_karyawan']}'>{$row['nama']}</option>";
            }
            ?>
          </select>
        </div>

        <!-- Jumlah Simpanan Pokok -->
        <div class="mb-4 relative">
          <label for="pokok" class="block text-sm font-medium text-gray-300 mb-2">Jumlah Simpanan Pokok:</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">Rp.</span>
            <input 
              type="text" 
              id="formatted_pokok" 
              class="w-full pl-10 pr-3 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
              oninput="formatCurrency(this)" 
              placeholder="0" 
            />
            <input type="hidden" id="pokok" name="pokok">
          </div>
        </div>

        <!-- Jumlah Simpanan Sukarela -->
        <div class="mb-4 relative">
          <label for="sukarela" class="block text-sm font-medium text-gray-300 mb-2">Jumlah Simpanan Sukarela:</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">Rp.</span>
            <input 
              type="text" 
              id="formatted_sukarela" 
              class="w-full pl-10 pr-3 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
              oninput="formatCurrency(this)" 
              placeholder="0" 
            />
            <input type="hidden" id="sukarela" name="sukarela">
          </div>
        </div>

        <!-- Jumlah Simpanan Wajib -->
        <div class="mb-4 relative">
          <label for="wajib" class="block text-sm font-medium text-gray-300 mb-2">Jumlah Simpanan Wajib:</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">Rp.</span>
            <input 
              type="text" 
              id="formatted_wajib" 
              class="w-full pl-10 pr-3 py-2 bg-gray-800 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
              oninput="formatCurrency(this)" 
              placeholder="0" 
            />
            <input type="hidden" id="wajib" name="wajib">
          </div>
        </div>

        <!-- Tanggal Pendaftaran -->
        <div class="mb-4">
          <label for="tanggal_simpanan" class="block text-sm font-medium mb-2">Tanggal Pendaftaran</label>
          <input 
            type="text" 
            name="tanggal_simpanan" 
            id="tanggal_simpanan" 
            class="w-full p-2 bg-gray-700 border border-gray-600 rounded-md text-white" 
            value="<?= date('Y-m-d H:i:s') ?>" 
            readonly 
          />
        </div>

        <!-- Submit Button -->
        <button type="button" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-semibold" onclick="showConfirmationModal()">Tambahkan Simpanan</button>

        <!-- Modal Konfirmasi -->
        <div id="confirmation-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center hidden">
          <div class="bg-gray-900 p-6 rounded-lg shadow-xl w-96 text-center border border-gray-700">
            <h2 class="text-xl font-semibold text-white">Konfirmasi Simpanan</h2>
            <p class="text-gray-300 mt-2">Apakah Anda yakin sudah mengisi form dengan benar?</p>
            <div class="mt-5 flex justify-center space-x-4">
              <button class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition" onclick="event.preventDefault(); closeModal();">Batal</button>
              <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg transition">Ya, Lanjutkan</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </main>

  <script>
    // Fungsi untuk format mata uang
    function formatCurrency(input) {
      // Hapus semua karakter selain angka
      let value = input.value.replace(/\D/g, "");

      // Jika nilai kosong, atur ke 0
      if (!value) {
        input.value = "";
        document.getElementById(input.id.replace("formatted_", "")).value = "";
        return;
      }

      // Format angka ke bentuk ribuan (IDR)
      let formattedValue = new Intl.NumberFormat("id-ID").format(value);
      input.value = formattedValue;

      // Simpan angka asli tanpa format ke hidden input
      document.getElementById(input.id.replace("formatted_", "")).value = value;
    }

    // Fungsi untuk membuka modal konfirmasi
    function showConfirmationModal() {
      document.getElementById("confirmation-modal").style.display = "flex";
    }

    // Fungsi untuk menutup modal konfirmasi
    function closeModal() {
      document.getElementById("confirmation-modal").style.display = "none";
    }

    $(document).ready(function () {
      // Inisialisasi Select2 di dropdown "Nama Karyawan"
      $('#karyawan').select2({
        placeholder: 'Cari Karyawan', // Teks placeholder saat belum memilih
        allowClear: true // Memungkinkan untuk menghapus pilihan
      });
    });
  </script>
</body>
</html>