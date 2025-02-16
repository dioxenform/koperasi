-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Feb 2025 pada 08.56
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `koperasi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_simpanan`
--

CREATE TABLE `jenis_simpanan` (
  `id_jenis` int(11) NOT NULL,
  `nama_jenis` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jenis_simpanan`
--

INSERT INTO `jenis_simpanan` (`id_jenis`, `nama_jenis`) VALUES
(1, 'pokok'),
(3, 'sukarela'),
(2, 'wajib');

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `tanggal_pendaftaran` date DEFAULT NULL,
  `status_aktif` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `nama`, `nik`, `alamat`, `no_telepon`, `tanggal_pendaftaran`, `status_aktif`) VALUES
(1, 'Dio Aditya Saputra', '1814311038', 'Jl. Wiyung Pasar 151B', '0896-8782-2131', '2025-02-13', 'aktif'),
(2, 'Setptian haryo permana', '2839102932', 'Jl. Semolowaru ', '082344423122', '2025-02-13', 'aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pinjaman`
--

CREATE TABLE `pinjaman` (
  `id_pinjaman` int(11) NOT NULL,
  `id_karyawan` int(11) NOT NULL,
  `tanggal_pinjaman` date NOT NULL,
  `jumlah_pinjaman` decimal(15,2) NOT NULL,
  `tenor` int(11) NOT NULL,
  `bunga` decimal(5,2) NOT NULL,
  `total_pinjaman` decimal(12,2) NOT NULL,
  `cicilan` decimal(15,2) NOT NULL,
  `deadline` date NOT NULL,
  `status_pinjaman` enum('pending','aktif','ditolak','lunas') NOT NULL DEFAULT 'pending',
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pinjaman`
--

INSERT INTO `pinjaman` (`id_pinjaman`, `id_karyawan`, `tanggal_pinjaman`, `jumlah_pinjaman`, `tenor`, `bunga`, `total_pinjaman`, `cicilan`, `deadline`, `status_pinjaman`, `is_active`) VALUES
(1, 1, '2025-02-13', 3000000.00, 6, 1.00, 3030000.00, 505000.00, '2025-08-13', 'lunas', 0),
(2, 1, '2025-02-13', 2000000.00, 12, 1.00, 2020000.00, 168333.33, '2026-02-13', 'lunas', 1),
(3, 1, '2025-02-13', 2000000.00, 10, 1.00, 2020000.00, 202000.00, '2025-12-13', 'pending', 1),
(4, 2, '2025-02-13', 3000000.00, 10, 5.00, 3150000.00, 315000.00, '2025-12-13', 'pending', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `simpanan`
--

CREATE TABLE `simpanan` (
  `id_simpanan` int(11) NOT NULL,
  `id_karyawan` int(11) NOT NULL,
  `tanggal_simpanan` datetime DEFAULT NULL,
  `id_jenis` int(11) NOT NULL,
  `jumlah_simpan` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `simpanan`
--

INSERT INTO `simpanan` (`id_simpanan`, `id_karyawan`, `tanggal_simpanan`, `id_jenis`, `jumlah_simpan`) VALUES
(1, 1, '2025-02-13 09:52:16', 1, 50000.00),
(2, 1, '2025-02-13 09:52:16', 2, 1000000.00),
(3, 1, '2025-02-13 09:52:16', 3, 50000.00),
(4, 1, '2025-02-13 09:53:43', 1, 20000.00),
(5, 1, '2025-02-13 09:53:43', 2, 100000.00),
(6, 1, '2025-02-13 09:53:43', 3, 20000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_karyawan` int(11) NOT NULL,
  `jenis_transaksi` enum('simpanan','cicilan') NOT NULL,
  `id_simpanan` int(11) DEFAULT NULL,
  `id_pinjaman` int(11) DEFAULT NULL,
  `tanggal_transaksi` timestamp NOT NULL DEFAULT current_timestamp(),
  `jumlah_transaksi` decimal(15,2) NOT NULL,
  `bulan` int(11) NOT NULL,
  `status_transaksi` enum('Belum Lunas','Lunas') DEFAULT 'Belum Lunas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_karyawan`, `jenis_transaksi`, `id_simpanan`, `id_pinjaman`, `tanggal_transaksi`, `jumlah_transaksi`, `bulan`, `status_transaksi`) VALUES
(19, 1, 'cicilan', NULL, 1, '2025-02-13 02:51:01', 505000.00, 1, 'Lunas'),
(20, 1, 'cicilan', NULL, 1, '2025-02-13 02:51:06', 505000.00, 2, 'Lunas'),
(21, 1, 'cicilan', NULL, 1, '2025-02-13 02:51:12', 505000.00, 3, 'Lunas'),
(22, 1, 'cicilan', NULL, 1, '2025-02-13 02:51:17', 505000.00, 4, 'Lunas'),
(23, 1, 'cicilan', NULL, 1, '2025-02-13 02:51:24', 505000.00, 5, 'Lunas'),
(24, 1, 'cicilan', NULL, 1, '2025-02-13 02:51:30', 505000.00, 6, 'Lunas'),
(25, 1, 'cicilan', NULL, 2, '2025-02-13 06:09:00', 168333.33, 1, 'Lunas'),
(26, 1, 'cicilan', NULL, 2, '2025-02-13 06:09:05', 168333.33, 2, 'Lunas'),
(27, 1, 'cicilan', NULL, 2, '2025-02-13 06:09:11', 168333.33, 3, 'Lunas'),
(28, 1, 'cicilan', NULL, 2, '2025-02-13 06:09:16', 168333.33, 4, 'Lunas'),
(29, 1, 'cicilan', NULL, 2, '2025-02-13 06:09:22', 168333.33, 5, 'Lunas'),
(30, 1, 'cicilan', NULL, 2, '2025-02-13 06:09:34', 168333.33, 6, 'Lunas'),
(31, 1, 'cicilan', NULL, 2, '2025-02-13 06:09:39', 168333.33, 7, 'Lunas'),
(32, 1, 'cicilan', NULL, 2, '2025-02-13 06:09:47', 168333.33, 8, 'Lunas'),
(33, 1, 'cicilan', NULL, 2, '2025-02-13 06:09:53', 168333.33, 9, 'Lunas'),
(34, 1, 'cicilan', NULL, 2, '2025-02-13 06:09:57', 168333.33, 10, 'Lunas'),
(35, 1, 'cicilan', NULL, 2, '2025-02-13 06:10:03', 168333.33, 11, 'Lunas'),
(36, 1, 'cicilan', NULL, 2, '2025-02-13 06:10:07', 168333.33, 12, 'Lunas');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(9, 'dio', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '2024-12-08 16:34:31'),
(11, 'mab4456', '6510caae69c71906509a116f17a7f5d17444f9b45a811e426a371ca4d64c86aa', '2025-01-09 05:31:32'),
(12, 'dioaditya', '6510caae69c71906509a116f17a7f5d17444f9b45a811e426a371ca4d64c86aa', '2025-01-09 10:33:25'),
(14, 'dio aditya', '6510caae69c71906509a116f17a7f5d17444f9b45a811e426a371ca4d64c86aa', '2025-01-09 12:10:44'),
(15, 'dwi sukarsih', '985187c41e70d8c829417e0affc0698d630767ff3ef74120494a4084538ec044', '2025-01-15 10:11:07'),
(17, 'eka', '29f9c5480b48b280db95b4fd92b131ca488b015e8e6a31560c6bc4219a40d804', '2025-02-13 14:31:00');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `jenis_simpanan`
--
ALTER TABLE `jenis_simpanan`
  ADD PRIMARY KEY (`id_jenis`),
  ADD UNIQUE KEY `nama_jenis` (`nama_jenis`);

--
-- Indeks untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- Indeks untuk tabel `pinjaman`
--
ALTER TABLE `pinjaman`
  ADD PRIMARY KEY (`id_pinjaman`),
  ADD KEY `id_karyawan` (`id_karyawan`);

--
-- Indeks untuk tabel `simpanan`
--
ALTER TABLE `simpanan`
  ADD PRIMARY KEY (`id_simpanan`),
  ADD KEY `id_karyawan` (`id_karyawan`),
  ADD KEY `id_jenis` (`id_jenis`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_karyawan` (`id_karyawan`),
  ADD KEY `id_simpanan` (`id_simpanan`),
  ADD KEY `id_pinjaman` (`id_pinjaman`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `jenis_simpanan`
--
ALTER TABLE `jenis_simpanan`
  MODIFY `id_jenis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_karyawan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pinjaman`
--
ALTER TABLE `pinjaman`
  MODIFY `id_pinjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `simpanan`
--
ALTER TABLE `simpanan`
  MODIFY `id_simpanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pinjaman`
--
ALTER TABLE `pinjaman`
  ADD CONSTRAINT `pinjaman_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `simpanan`
--
ALTER TABLE `simpanan`
  ADD CONSTRAINT `simpanan_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`) ON DELETE CASCADE,
  ADD CONSTRAINT `simpanan_ibfk_2` FOREIGN KEY (`id_jenis`) REFERENCES `jenis_simpanan` (`id_jenis`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_simpanan`) REFERENCES `simpanan` (`id_simpanan`) ON DELETE SET NULL,
  ADD CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`id_pinjaman`) REFERENCES `pinjaman` (`id_pinjaman`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
