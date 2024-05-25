-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Bulan Mei 2024 pada 11.45
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
-- Database: `peminjaman_skuter`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `kecamatan`
--

CREATE TABLE `kecamatan` (
  `id_kecamatan` int(11) NOT NULL,
  `nama_kecamatan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kecamatan`
--

INSERT INTO `kecamatan` (`id_kecamatan`, `nama_kecamatan`) VALUES
(1, 'PURWAKARTA'),
(2, 'JATILUHUR'),
(3, 'WANAYASA');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Pimpinan','Operator') NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_telepon` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id_user`, `username`, `password`, `role`, `nama`, `alamat`, `no_telepon`) VALUES
(15, 'edgard', '$2y$10$VGaxNbwgriT/hVkm752eCeGPNNb2T1Kzf3KDZkFvRznCSxBORKmVq', 'Admin', '', NULL, NULL),
(16, 'runi', '$2y$10$MtMG2gJ0BRrnwJ84jqh6MOPIcIj/d8I1s82rYEEiTn0IVG9ZDc3ZG', 'Operator', 'runi', 'karawang', '08564'),
(18, 'ghazy', '$2y$10$HsqPUgF6SxgRbKpgfK3Q..CTqRFXoaBUkK0z8rdwmt3L5uLBrGVbO', 'Pimpinan', 'ghazy', 'munjul', '0821732137');

-- --------------------------------------------------------

--
-- Struktur dari tabel `scooter`
--

CREATE TABLE `scooter` (
  `id_scooter` int(11) NOT NULL,
  `warna` varchar(50) NOT NULL,
  `status` enum('Tersedia','Disewa','Perbaikan') NOT NULL DEFAULT 'Tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `scooter`
--

INSERT INTO `scooter` (`id_scooter`, `warna`, `status`) VALUES
(14, 'hijau', 'Tersedia'),
(15, 'biru', 'Tersedia');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tarif`
--

CREATE TABLE `tarif` (
  `id` int(11) NOT NULL,
  `value` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tarif`
--

INSERT INTO `tarif` (`id`, `value`) VALUES
(1, 50000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksipengembalian`
--

CREATE TABLE `transaksipengembalian` (
  `id` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_scooter` int(11) NOT NULL,
  `tanggal_pengembalian` datetime NOT NULL,
  `biaya_tambahan` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksipengembalian`
--

INSERT INTO `transaksipengembalian` (`id`, `id_transaksi`, `id_scooter`, `tanggal_pengembalian`, `biaya_tambahan`) VALUES
(14, 17, 14, '2024-05-24 21:32:28', 10000.00),
(15, 18, 15, '2024-05-24 21:33:39', 0.00),
(16, 19, 15, '2024-05-24 21:33:47', 0.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksipenyewaan`
--

CREATE TABLE `transaksipenyewaan` (
  `id_transaksi` int(11) NOT NULL,
  `id_scooter` int(11) NOT NULL,
  `no_ktp` varchar(20) NOT NULL,
  `nama_penyewa` varchar(100) NOT NULL,
  `alamat_penyewa` text NOT NULL,
  `tanggal_mulai` datetime NOT NULL,
  `tarif_per_jam` decimal(10,2) NOT NULL,
  `id_kecamatan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksipenyewaan`
--

INSERT INTO `transaksipenyewaan` (`id_transaksi`, `id_scooter`, `no_ktp`, `nama_penyewa`, `alamat_penyewa`, `tanggal_mulai`, `tarif_per_jam`, `id_kecamatan`) VALUES
(17, 14, '1000', 'Hafis', 'upi', '2024-05-24 21:26:27', 50000.00, 1),
(18, 15, '2000', 'ghazy', 'bojong', '2024-05-24 21:27:33', 50000.00, 2),
(19, 15, '2000', 'ghazy', 'bojong', '2024-05-24 21:27:58', 50000.00, 2);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kecamatan`
--
ALTER TABLE `kecamatan`
  ADD PRIMARY KEY (`id_kecamatan`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `scooter`
--
ALTER TABLE `scooter`
  ADD PRIMARY KEY (`id_scooter`);

--
-- Indeks untuk tabel `tarif`
--
ALTER TABLE `tarif`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksipengembalian`
--
ALTER TABLE `transaksipengembalian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_scooter` (`id_scooter`);

--
-- Indeks untuk tabel `transaksipenyewaan`
--
ALTER TABLE `transaksipenyewaan`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_scooter` (`id_scooter`),
  ADD KEY `fk_kecamatan` (`id_kecamatan`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kecamatan`
--
ALTER TABLE `kecamatan`
  MODIFY `id_kecamatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `scooter`
--
ALTER TABLE `scooter`
  MODIFY `id_scooter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `tarif`
--
ALTER TABLE `tarif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `transaksipengembalian`
--
ALTER TABLE `transaksipengembalian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `transaksipenyewaan`
--
ALTER TABLE `transaksipenyewaan`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `transaksipengembalian`
--
ALTER TABLE `transaksipengembalian`
  ADD CONSTRAINT `transaksipengembalian_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksipenyewaan` (`id_transaksi`),
  ADD CONSTRAINT `transaksipengembalian_ibfk_2` FOREIGN KEY (`id_scooter`) REFERENCES `scooter` (`id_scooter`);

--
-- Ketidakleluasaan untuk tabel `transaksipenyewaan`
--
ALTER TABLE `transaksipenyewaan`
  ADD CONSTRAINT `fk_kecamatan` FOREIGN KEY (`id_kecamatan`) REFERENCES `kecamatan` (`id_kecamatan`),
  ADD CONSTRAINT `transaksipenyewaan_ibfk_1` FOREIGN KEY (`id_scooter`) REFERENCES `scooter` (`id_scooter`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
