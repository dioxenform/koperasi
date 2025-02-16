<?php
include "service/database.php"; // Koneksi ke database

if (isset($_GET['nama'])) {
    $nama = mysqli_real_escape_string($db, $_GET['nama']);
    $query = "SELECT * FROM karyawan WHERE nama LIKE '%$nama%'";
    $result = mysqli_query($db, $query);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'id_karyawan' => $row['id_karyawan'],
            'nama' => $row['nama'],
            'simpanan' => $row['simpanan'], // Pastikan kolom simpanan ada di database
            'pinjaman' => $row['pinjaman'], // Pastikan kolom pinjaman ada di database
        ];
    }

    echo json_encode($data);
}
?>
