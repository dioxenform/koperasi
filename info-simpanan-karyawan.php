<?php
session_start();
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("location: index.php");
}
include 'service/database.php'; // Koneksi ke database

// Ambil ID karyawan dari parameter GET (atau bisa juga dari sesi)
$id_karyawan = isset($_GET['id_karyawan']) ? $_GET['id_karyawan'] : null;

// Query untuk mengambil data karyawan
$karyawan = null;
if ($id_karyawan) {
    $query = "SELECT * FROM karyawan WHERE id_karyawan = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id_karyawan);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $karyawan = $result->fetch_assoc();
    }
    $stmt->close();
}

// Jika tidak ada data karyawan, tampilkan pesan error
if (!$karyawan) {
    die("Data karyawan tidak ditemukan.");
}

// Query untuk mengambil data simpanan berdasarkan id_karyawan
$query_simpanan = "
    SELECT js.nama_jenis, SUM(s.jumlah_simpan) AS total_simpanan
    FROM simpanan s
    JOIN jenis_simpanan js ON s.id_jenis = js.id_jenis
    WHERE s.id_karyawan = ?
    GROUP BY js.nama_jenis
";
$stmt_simpanan = $db->prepare($query_simpanan);
$stmt_simpanan->bind_param("i", $id_karyawan);
$stmt_simpanan->execute();
$result_simpanan = $stmt_simpanan->get_result();

// Simpan hasil query dalam array
$simpanan = [];
while ($row = $result_simpanan->fetch_assoc()) {
    $simpanan[$row['nama_jenis']] = $row['total_simpanan'];
}
$stmt_simpanan->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Simpanan</title>
    <!-- Tambahkan Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <!-- Tombol Cetak -->
    <div class="text-center mb-4 no-print">
        <button onclick="printInvoice()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
            Cetak Invoice
        </button>
    </div>
    <!-- Invoice Container -->
    <div class="invoice-container max-w-4xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-md relative">
        <!-- Watermark -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 rotate-[-45deg] opacity-20 pointer-events-none">
            <img src="/img/logo-perusahaan.jpg" alt="Logo Perusahaan" class="w-64 h-auto">
        </div>
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center h-24 sm:h-32 md:h-40 ">
                <img src="/img/logo-perusahaan.jpg" alt="logo perusahaan" class="w-24 sm:w-32 md:w-48 h-auto">
            </div>
            <h1 class="text-3xl font-bold text-gray-800">INVOICE SIMPANAN</h1>
            <p class="text-sm text-gray-500">Nomor: INV-2023-12345 | Tanggal: 01 Oktober 2023</p>
        </div>
        <!-- Informasi Perusahaan -->
        <div class="mb-6">
            <p class="text-lg font-semibold text-gray-700">Dari:</p>
            <p class="text-gray-600">PT. EVERAGE VALVES METALS</p>
            <p class="text-gray-600">Jl. Wringinanom, Gresik, Jawa Timur, Indonesia</p>
            <p class="text-gray-600">0918-320-198 | halooooo@gmail.com</p>
        </div>
        <!-- Informasi Pelanggan -->
        <div class="mb-6">
            <p class="text-lg font-semibold text-gray-700">Kepada:</p>
            <p class="text-gray-600"><?php echo htmlspecialchars($karyawan['nama']); ?></p>
            <p class="text-gray-600"><?php echo htmlspecialchars($karyawan['alamat']); ?></p>
            <p class="text-gray-600"><?php echo htmlspecialchars($karyawan['no_telepon']); ?></p>
        </div>
        <!-- Detail Simpanan -->
        <table class="w-full border-collapse text-left mb-6">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-2 px-4 border-b">Jenis Simpanan</th>
                    <th class="py-2 px-4 border-b">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="py-2 px-4 border-b">Simpanan Pokok</td>
                    <td class="py-2 px-4 border-b text-right">
                        Rp <?php echo isset($simpanan['pokok']) ? number_format($simpanan['pokok'], 0, ',', '.') : '0'; ?>
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b">Simpanan Wajib</td>
                    <td class="py-2 px-4 border-b text-right">
                        Rp <?php echo isset($simpanan['wajib']) ? number_format($simpanan['wajib'], 0, ',', '.') : '0'; ?>
                    </td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b">Simpanan Sukarela</td>
                    <td class="py-2 px-4 border-b text-right">
                        Rp <?php echo isset($simpanan['sukarela']) ? number_format($simpanan['sukarela'], 0, ',', '.') : '0'; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Catatan Tambahan -->
        <div class="mb-6">
            <p class="text-sm text-gray-600"><strong>Catatan:</strong></p>
            <ul class="list-disc list-inside text-sm text-gray-600">
                <li>Pembayaran dapat dilakukan melalui transfer bank ke rekening berikut:</li>
                <li><strong>Bank ABC - 1234567890 a.n PT. Contoh Perusahaan</strong></li>
                <li><strong>Bank MANDI SENDIRI - 1234567890 a.n PT. Contoh Perusahaan</strong></li>
                <li><strong>Bank BNN - 1234567890 a.n PT. Contoh Perusahaan</strong></li>
                <li>Mohon konfirmasi pembayaran dengan mengirimkan bukti transfer ke email kami.</li>
            </ul>
        </div>
        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm">
            <p>Terima kasih atas kepercayaan Anda kepada kami!</p>
            <p>Untuk pertanyaan lebih lanjut, silakan hubungi kami di: Telepon: 0918-320-198 | Email: halooooo@gmail.com</p>
        </div>
    </div>
    <!-- JavaScript untuk Mencetak -->
    <script>
        function printInvoice() {
            const printContents = document.querySelector('.invoice-container').innerHTML;
            const originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>
</body>
</html>