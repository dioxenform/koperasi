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

  // Fetch daftar karyawan
  $sql_karyawan = "SELECT id_karyawan, nama FROM karyawan";
  $result_karyawan = $db->query($sql_karyawan);
  if (!$result_karyawan) {
      die("Gagal mengambil data karyawan: " . $db->error);
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id_karyawan = $_POST['id_karyawan'];
      $jumlah_pinjaman = str_replace('.', '', $_POST['jumlah_pinjaman']); // Hilangkan format Rp
      $bunga = $_POST['bunga'];
      $tenor = $_POST['tenor'];

      $tanggal_pinjaman = date('Y-m-d');
      $status_pinjaman = 'Pending';
      $total_pinjaman = $jumlah_pinjaman + ($jumlah_pinjaman * ($bunga / 100));
      $cicilan = $total_pinjaman / $tenor; // Perhitungan cicilan bulanan
      $deadline = date('Y-m-d', strtotime("+$tenor months"));

      $sql_insert = "INSERT INTO pinjaman (id_karyawan, tanggal_pinjaman, jumlah_pinjaman, tenor, bunga, total_pinjaman, cicilan, deadline, status_pinjaman)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

      if ($stmt = $db->prepare($sql_insert)) {
          $stmt->bind_param("isdiiddss", $id_karyawan, $tanggal_pinjaman, $jumlah_pinjaman, $tenor, $bunga, $total_pinjaman, $cicilan, $deadline, $status_pinjaman);
          if ($stmt->execute()) {
              $daftar_message = "Data berhasil disimpan!";
          } else {
              $daftar_message = "Terjadi kesalahan: " . $stmt->error;
          }
          $stmt->close();
      } else {
          $daftar_message = "Gagal menyiapkan statement: " . $db->error;
      }
  }
  ?>


  <!DOCTYPE html>
  <html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Pinjaman</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
              <select name="id_karyawan" id="id_karyawan" class="w-full p-2 bg-gray-700 border border-gray-600 rounded-md text-white">
                <option value="" disabled selected>Pilih Karyawan</option>
                  <?php
                  while ($row = $result_karyawan->fetch_assoc()) {
                  echo "<option value='{$row['id_karyawan']}'>{$row['nama']}</option>";
                  }
                  ?>
              </select>
            </div>

          <!-- Jumlah Pinjaman -->
          <div class="mb-4 relative">
    <label for="jumlah_pinjaman" class="block text-sm font-medium text-gray-700 mb-2">
      Jumlah Pinjaman:
    </label>
    <div class="relative">
      <!-- Label Rp -->
      <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">Rp.</span>
      <!-- Input -->
      <input 
        type="text" 
        id="jumlah_pinjaman" 
        name="jumlah_pinjaman" 
        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
        oninput="formatCurrency(this)" 
        placeholder="0" 
      />
    </div>
  </div>

          <!-- Bunga -->
          <div class="relative z-0 w-full mb-5 group">
            <input type="number" name="bunga" id="bunga" class="block py-2 px-3 w-full text-sm text-gray-200 bg-transparent border-0 border-b-2 border-gray-500 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 focus:text-white peer rounded-md" placeholder=" " required />
            <label for="bunga" class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:text-white peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
              Bunga (%)
            </label>
          </div>

          <!-- Tenor -->
          <div class="relative z-0 w-full mb-5 group">
            <input type="number" name="tenor" id="tenor" class="block py-2 px-3 w-full text-sm text-gray-200 bg-transparent border-0 border-b-2 border-gray-500 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 focus:text-white peer rounded-md" placeholder=" " required />
            <label for="tenor" class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:text-white peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
              Tenor (bulan)
            </label>
          </div>

          <!-- Tanggal Pengajuan -->
          <div class="mb-4">
              <label for="tanggal_pinjaman" class="block text-sm font-medium mb-2">Tanggal Pengajuan</label>
              <input 
              type="text" 
              name="tanggal_pinjaman" 
              id="tanggal_pinjaman" 
              class="w-full p-2 bg-gray-700 border border-gray-600 rounded-md text-white" 
              value="<?= date('Y-m-d H:i:s') ?>" 
              readonly 
              />
          </div>

          <!-- Deadline -->
          <div class="mb-4">
              <label for="deadline" class="block text-sm font-medium mb-2">Deadline</label>
              <input 
              type="text" 
              name="deadline" 
              id="deadline" 
              class="w-full p-2 bg-gray-700 border border-gray-600 rounded-md text-white" 
              value="-" 
              readonly 
              />
          </div>

          <!-- Total Pinjaman -->
          <div class="mb-4 relative">
      <label for="total_pinjaman" class="block text-sm font-medium mb-2">Total Pinjaman</label>
      <div class="relative">
          <!-- Label Rp -->
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">Rp.</span>
          <!-- Input -->
          <input 
          type="text" 
          name="total_pinjaman" 
          id="total_pinjaman" 
          class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-700 text-white" 
          value="-" 
          readonly 
          />
      </div>
  </div>

  <!-- cicilan -->
  <div class="mb-4">
      <label for="cicilan" class="block text-sm font-medium mb-2">Cicilan per Bulan</label>
      <div class="relative">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">Rp.</span>
          <input 
              type="text" 
              name="cicilan" 
              id="cicilan" 
              class="w-full pl-10 pr-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white" 
              value="-" 
              readonly
          />
      </div>
  </div>

             <!-- Submit Button -->
             <button type="button" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-semibold" onclick="showConfirmationModal()">Ajukan Pinjaman</button>
             <!-- Modal Konfirmasi -->
<div id="confirmation-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center hidden">
    <div class="bg-gray-900 p-6 rounded-lg shadow-xl w-96 text-center border border-gray-700">
        <h2 class="text-xl font-semibold text-white">Konfirmasi Pinjaman</h2>
        <p class="text-gray-300 mt-2">Apakah Anda yakin ingin mengajukan pinjaman ini?</p>
        <div class="mt-5 flex justify-center space-x-4">
            <button class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition"  onclick="event.preventDefault() ; closeModal()">Batal</button>
            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg transition">Ya, Ajukan</button>
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

        // Kirim formulir ke server
        function submitForm() {
        document.querySelector("form").submit();
    }

        // Fungsi format mata uang
        function formatCurrency(input) {
            let value = input.value.replace(/\D/g, "");
            input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

      // Menghitung total pinjaman dan deadline saat input berubah
      document.getElementById('jumlah_pinjaman').addEventListener('input', calculate);
      document.getElementById('bunga').addEventListener('input', calculate);
      document.getElementById('tenor').addEventListener('input', calculate);

      function formatCurrency(input) {
      // Format angka menjadi format Rp
      return input.toLocaleString("id-ID");
  }

      function calculate() {
      const jumlahPinjaman = parseFloat(document.getElementById('jumlah_pinjaman').value.replace(/\./g, '')) || 0;
      const bunga = parseFloat(document.getElementById('bunga').value) || 0;
      const tenor = parseInt(document.getElementById('tenor').value) || 0;
    
      
      
      
      
      // Hitung cicilan bulanan (asumsi bunga per bulan dihitung dari jumlah pinjaman awal)
      const bungaBulanan = (jumlahPinjaman * bunga) / 100 ;
      const totalBunga =  bungaBulanan * tenor
      const cicilanPerBulan =  jumlahPinjaman / tenor  ;
      document.getElementById('cicilan').value = cicilanPerBulan.toLocaleString('id-ID');
      
      // Hitung total pinjaman berdasarkan cicilan
      const totalPinjaman = ( cicilanPerBulan * tenor ) + totalBunga ;
      document.getElementById('total_pinjaman').value = formatCurrency(totalPinjaman);
      
      // Hitung deadline
      const deadline = new Date();
      deadline.setMonth(deadline.getMonth() + tenor);
      // Validasi input, jika salah satu kosong, set nilai output menjadi "-"
   if (!jumlahPinjaman || !bunga|| !tenor) {
       document.getElementById('total_pinjaman').value = "-";
       document.getElementById('cicilan').value = "-";
       document.getElementById('deadline').value = "-";
       return; // Keluar dari fungsi jika input tidak lengkap
   }
      document.getElementById('deadline').value = deadline.toISOString().split('T')[0];
  }
    </script>
  </body>
  </html>
