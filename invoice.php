<?php
session_start();
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("location: index.php");
}
include 'service/database.php'; // Koneksi ke database

if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error);
}

// Ambil ID pinjaman dari URL
$id_pinjaman = isset($_GET['id_pinjaman']) ? intval($_GET['id_pinjaman']) : 0;

// Query data pinjaman berdasarkan id_pinjaman
$sql_pinjaman = "SELECT p.*, k.nama, k.nik, k.alamat, k.no_telepon, p.jumlah_pinjaman, p.cicilan, p.status_pinjaman FROM pinjaman p 
                 JOIN karyawan k ON p.id_karyawan = k.id_karyawan
                 WHERE p.id_pinjaman = $id_pinjaman";
$result_pinjaman = $db->query($sql_pinjaman);

if (!$result_pinjaman) {
    die("Error: " . $db->error);
}

$pinjaman = $result_pinjaman->fetch_assoc();

// Tutup koneksi
$db->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pinjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="text-center mb-4 no-print">
        <button onclick="printInvoice()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
            Cetak Invoice
        </button>
    </div>

    <div class="invoice-container max-w-4xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-md relative">
        <!-- Logo dan Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">INVOICE PINJAMAN</h1>
            <p class="text-sm text-gray-500">Nomor: INV-<?php echo date('Ymd') . "-" . $id_pinjaman; ?> | Tanggal: <?php echo date('d M Y'); ?></p>
        </div>

        <!-- Informasi Perusahaan -->
        <div class="mb-6">
            <p class="text-lg font-semibold text-gray-700">Dari:</p>
            <p class="text-gray-600">PT. EVERAGE VALVES METALS</p>
            <p class="text-gray-600">Jl. Wringinanom, Gresik, Jawa Timur</p>
            <p class="text-gray-600">0918-320-198 | email@example.com</p>
        </div>

        <!-- Informasi Karyawan -->
        <div class="mb-6">
            <p class="text-lg font-semibold text-gray-700">Kepada:</p>
            <p class="text-gray-600"><?php echo htmlspecialchars($pinjaman['nama']); ?></p>
            <p class="text-gray-600"><?php echo htmlspecialchars($pinjaman['alamat']); ?></p>
            <p class="text-gray-600"><?php echo htmlspecialchars($pinjaman['no_telepon']); ?></p>
        </div>

        <!-- Detail Pinjaman -->
        <table class="w-full border-collapse text-left mb-6">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-2 px-4 border-b">ID Pinjaman</th>
                    <th class="py-2 px-4 border-b">Jumlah Pinjaman (Rp)</th>
                    <th class="py-2 px-4 border-b">Cicilan (Rp)</th>
                    <th class="py-2 px-4 border-b">Status</th>
                </tr>
            </thead>
            <tbody>
    <?php if (!empty($pinjaman)): ?>
        <tr>
            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($pinjaman['id_pinjaman']); ?></td>
            <td class="py-2 px-4 border-b text-right">Rp <?php echo number_format($pinjaman['jumlah_pinjaman'], 0, ',', '.'); ?></td>
            <td class="py-2 px-4 border-b text-right">Rp <?php echo number_format($pinjaman['cicilan'], 0, ',', '.'); ?></td>
            <td class="py-2 px-4 border-b text-center"><?php echo htmlspecialchars($pinjaman['status_pinjaman']); ?></td>
        </tr>
    <?php else: ?>
        <tr>
            <td colspan="4" class="py-2 px-4 border-b text-center">Tidak ada data pinjaman.</td>
        </tr>
    <?php endif; ?>
</tbody>

        </table>

        <!-- Catatan -->
        <div class="mb-6">
            <p class="text-sm text-gray-600"><strong>Catatan:</strong></p>
            <ul class="list-disc list-inside text-sm text-gray-600">
                <li>Pembayaran cicilan dapat dilakukan melalui transfer ke rekening: <strong>Bank ABC - 1234567890</strong></li>
                <li>Konfirmasi pembayaran melalui email: email@example.com</li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm">
            <p>Terima kasih atas kepercayaan Anda kepada kami!</p>
        </div>
    </div>

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
