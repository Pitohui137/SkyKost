-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Bulan Mei 2020 pada 11.29
-- Versi server: 10.4.10-MariaDB
-- Versi PHP: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kos`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`nama`, `username`, `password`) VALUES
('Administrator', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997'),
('Doni Tampan', 'doncu', '5cec175b165e3d5e62c9e13ce848ef6feac81bff');

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `detail_kamar`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `detail_kamar` (
`lantai` varchar(5)
,`no_kamar` varchar(5)
,`harga` int(11)
,`total_biaya` decimal(51,0)
,`total_bayar` decimal(63,0)
,`piutang` decimal(64,0)
,`jml_penghuni` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `detail_pembayaran`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `detail_pembayaran` (
`id_pembayaran` int(10)
,`id_penghuni` int(5)
,`no_kamar` varchar(10)
,`nama` varchar(200)
,`no_ktp` varchar(20)
,`tgl_bayar` varchar(10)
,`biaya` int(30)
,`bayar` bigint(20)
,`ket` varchar(200)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `detail_penghuni`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `detail_penghuni` (
`id` int(10)
,`no_kamar` varchar(10)
,`nama` varchar(200)
,`no_ktp` varchar(20)
,`alamat` varchar(200)
,`no` varchar(30)
,`tgl_masuk` varchar(10)
,`tgl_keluar` varchar(10)
,`status` varchar(20)
,`biaya` int(30)
,`bayar` decimal(41,0)
,`piutang` decimal(42,0)
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kamar`
--

CREATE TABLE `kamar` (
  `lantai` varchar(5) NOT NULL,
  `no_kamar` varchar(5) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kamar`
--

INSERT INTO `kamar` (`lantai`, `no_kamar`, `harga`) VALUES
('1', '101', 500000),
('1', '102', 400000),
('1', '103', 500000),
('1', '104', 600000),
('1', '105', 600000),
('2', '201', 500000),
('2', '202', 500000),
('2', '203', 500000),
('2', '204', 400000),
('2', '205', 400000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan`
--

CREATE TABLE `keuangan` (
  `id_pembayaran` int(10) NOT NULL,
  `id_penghuni` int(5) NOT NULL,
  `tgl_bayar` varchar(10) NOT NULL,
  `bayar` bigint(20) NOT NULL,
  `ket` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `keuangan`
--

INSERT INTO `keuangan` (`id_pembayaran`, `id_penghuni`, `tgl_bayar`, `bayar`, `ket`) VALUES
(1, 1, '01-02-2020', 5100000, 'bayar'),
(2, 48, '07-05-2020', 40000, ''),
(3, 49, '07-05-2020', 5000000, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penghuni`
--

CREATE TABLE `penghuni` (
  `id` int(10) NOT NULL,
  `no_kamar` varchar(10) DEFAULT NULL,
  `no_ktp` varchar(20) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `alamat` varchar(200) NOT NULL,
  `no` varchar(30) NOT NULL,
  `tgl_masuk` varchar(10) NOT NULL,
  `tgl_keluar` varchar(10) NOT NULL,
  `biaya` int(30) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `penghuni`
--

INSERT INTO `penghuni` (`id`, `no_kamar`, `no_ktp`, `nama`, `alamat`, `no`, `tgl_masuk`, `tgl_keluar`, `biaya`, `status`) VALUES
(1, '101', '2147483647', 'Nobi Kharisma', 'Ds Darmorejo RT 05/02 Mejayan Madiun', '081333896104', '01-08-2019', '31-07-2020', 5400000, 'Penghuni'),
(48, '102', '5557', 'Yoga Pratama wkwks', '423', '23424', '25-06-2020', '31-07-2020', 4800000, 'Penghuni'),
(49, '204', '4222222222', 'David', 'Jl. Prof. Soedarto S.H., Tembalang', '7888787878', '07-05-2020', '31-07-2020', 6000000, 'Penghuni');

-- --------------------------------------------------------

--
-- Struktur untuk view `detail_kamar`
--
DROP TABLE IF EXISTS `detail_kamar`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detail_kamar`  AS  select `kamar`.`lantai` AS `lantai`,`kamar`.`no_kamar` AS `no_kamar`,`kamar`.`harga` AS `harga`,sum(`keuangan_penghuni`.`biaya`) AS `total_biaya`,sum(`keuangan_penghuni`.`bayar`) AS `total_bayar`,coalesce(sum(`keuangan_penghuni`.`biaya`),0) - coalesce(sum(`keuangan_penghuni`.`bayar`),0) AS `piutang`,count(`keuangan_penghuni`.`id`) AS `jml_penghuni` from (`kamar` left join (select `penghuni`.`id` AS `id`,`penghuni`.`no_kamar` AS `no_kamar`,`penghuni`.`biaya` AS `biaya`,sum(`keuangan`.`bayar`) AS `bayar` from (`penghuni` left join `keuangan` on(`keuangan`.`id_penghuni` = `penghuni`.`id`)) where `penghuni`.`status` = 'Penghuni' group by `penghuni`.`id`) `keuangan_penghuni` on(`kamar`.`no_kamar` = `keuangan_penghuni`.`no_kamar`)) group by `kamar`.`no_kamar` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `detail_pembayaran`
--
DROP TABLE IF EXISTS `detail_pembayaran`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detail_pembayaran`  AS  select `keuangan`.`id_pembayaran` AS `id_pembayaran`,`keuangan`.`id_penghuni` AS `id_penghuni`,`penghuni`.`no_kamar` AS `no_kamar`,`penghuni`.`nama` AS `nama`,`penghuni`.`no_ktp` AS `no_ktp`,`keuangan`.`tgl_bayar` AS `tgl_bayar`,`penghuni`.`biaya` AS `biaya`,`keuangan`.`bayar` AS `bayar`,`keuangan`.`ket` AS `ket` from (`penghuni` join `keuangan` on(`penghuni`.`id` = `keuangan`.`id_penghuni`)) where 1 = '1' order by str_to_date(`keuangan`.`tgl_bayar`,'%d-%m-%Y') desc ;

-- --------------------------------------------------------

--
-- Struktur untuk view `detail_penghuni`
--
DROP TABLE IF EXISTS `detail_penghuni`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detail_penghuni`  AS  select `penghuni`.`id` AS `id`,`penghuni`.`no_kamar` AS `no_kamar`,`penghuni`.`nama` AS `nama`,`penghuni`.`no_ktp` AS `no_ktp`,`penghuni`.`alamat` AS `alamat`,`penghuni`.`no` AS `no`,`penghuni`.`tgl_masuk` AS `tgl_masuk`,`penghuni`.`tgl_keluar` AS `tgl_keluar`,`penghuni`.`status` AS `status`,`penghuni`.`biaya` AS `biaya`,sum(`keuangan`.`bayar`) AS `bayar`,`penghuni`.`biaya` - coalesce(sum(`keuangan`.`bayar`),0) AS `piutang` from (`penghuni` left join `keuangan` on(`penghuni`.`id` = `keuangan`.`id_penghuni`)) group by `penghuni`.`id` order by `penghuni`.`no_kamar` ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`username`);

--
-- Indeks untuk tabel `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`no_kamar`);

--
-- Indeks untuk tabel `keuangan`
--
ALTER TABLE `keuangan`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `fk_pembayaran` (`id_penghuni`);

--
-- Indeks untuk tabel `penghuni`
--
ALTER TABLE `penghuni`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kamar` (`no_kamar`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `keuangan`
--
ALTER TABLE `keuangan`
  MODIFY `id_pembayaran` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `penghuni`
--
ALTER TABLE `penghuni`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `keuangan`
--
ALTER TABLE `keuangan`
  ADD CONSTRAINT `fk_pembayaran` FOREIGN KEY (`id_penghuni`) REFERENCES `penghuni` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penghuni`
--
ALTER TABLE `penghuni`
  ADD CONSTRAINT `fk_kamar` FOREIGN KEY (`no_kamar`) REFERENCES `kamar` (`no_kamar`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
