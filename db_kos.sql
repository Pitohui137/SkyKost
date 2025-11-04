-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 03, 2025 at 03:46 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kos`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`nama`, `username`, `password`) VALUES
('Administrator', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997'),
('Doni Tampan', 'doncu', '5cec175b165e3d5e62c9e13ce848ef6feac81bff');

-- --------------------------------------------------------

--
-- Stand-in structure for view `detail_kamar`
-- (See below for the actual view)
--
CREATE TABLE `detail_kamar` (
`lantai` varchar(5)
,`no_kamar` varchar(5)
,`harga` int(11)
,`total_harga_per_bulan` decimal(51,0)
,`total_bayar` decimal(63,0)
,`piutang` decimal(64,0)
,`jml_penghuni` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `detail_pembayaran`
-- (See below for the actual view)
--
CREATE TABLE `detail_pembayaran` (
`id_pembayaran` int(10)
,`id_penghuni` int(5)
,`no_kamar` varchar(10)
,`nama` varchar(200)
,`no_ktp` varchar(20)
,`tgl_bayar` varchar(10)
,`harga_per_bulan` int(30)
,`bayar` bigint(20)
,`ket` varchar(200)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `detail_penghuni`
-- (See below for the actual view)
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
,`harga_per_bulan` int(30)
,`bayar` decimal(41,0)
,`piutang` decimal(42,0)
);

-- --------------------------------------------------------

--
-- Table structure for table `kamar`
--

CREATE TABLE `kamar` (
  `lantai` varchar(5) NOT NULL,
  `no_kamar` varchar(5) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `kamar`
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
-- Table structure for table `keuangan`
--

CREATE TABLE `keuangan` (
  `id_pembayaran` int(10) NOT NULL,
  `id_penghuni` int(5) NOT NULL,
  `tgl_bayar` varchar(10) NOT NULL,
  `bayar` bigint(20) NOT NULL,
  `ket` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `keuangan`
--

INSERT INTO `keuangan` (`id_pembayaran`, `id_penghuni`, `tgl_bayar`, `bayar`, `ket`) VALUES
(1, 1, '01-02-2020', 5100000, 'bayar'),
(2, 48, '07-05-2020', 40000, ''),
(3, 49, '07-05-2020', 5000000, ''),
(5, 1, '02-11-2025', 300000, 'Pembayaran via GoPay - november'),
(6, 60, '02-11-2025', 500000, 'Pembayaran via Transfer BCA - Januari');

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_pembayaran`
--

CREATE TABLE `pengajuan_pembayaran` (
  `id_pengajuan` int(10) NOT NULL,
  `id_penghuni` int(10) NOT NULL,
  `nominal` bigint(20) NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `tgl_pengajuan` datetime NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `tgl_konfirmasi` datetime DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengajuan_pembayaran`
--

INSERT INTO `pengajuan_pembayaran` (`id_pengajuan`, `id_penghuni`, `nominal`, `metode_pembayaran`, `bukti_transfer`, `tgl_pengajuan`, `status`, `tgl_konfirmasi`, `keterangan`) VALUES
(1, 1, 300000, 'GoPay', '19cc82ae20c7d62c4db2b266a485e0b8.jpg', '2025-11-02 03:29:07', 'approved', '2025-11-02 03:31:28', 'november'),
(2, 60, 500000, 'Transfer BCA', '1914f488d8c8505ba623faca404d1c89.jpeg', '2025-11-02 04:03:47', 'approved', '2025-11-02 04:05:09', 'Januari');

-- --------------------------------------------------------

--
-- Table structure for table `penghuni`
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
  `harga_per_bulan` int(30) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `password` varchar(300) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `penghuni`
--

INSERT INTO `penghuni` (`id`, `no_kamar`, `no_ktp`, `nama`, `alamat`, `no`, `tgl_masuk`, `tgl_keluar`, `harga_per_bulan`, `status`, `password`, `foto`) VALUES
(1, '101', '2147483647', 'Nobi Kharisma', 'Ds Darmorejo RT 05/02 Mejayan Madiun', '081333896104', '01-08-2019', '31-07-2020', 5400000, 'Penghuni', '8cb2237d0679ca88db6464eac60da96345513964', 'default-avatar.jpg'),
(48, '102', '5557', 'Yoga Pratama wkwks', '423', '23424', '25-06-2020', '31-07-2020', 4800000, 'Penghuni', '8cb2237d0679ca88db6464eac60da96345513964', 'default-avatar.jpg'),
(49, '204', '4222222222', 'David', 'Jl. Prof. Soedarto S.H., Tembalang', '7888787878', '07-05-2020', '31-07-2020', 6000000, 'Penghuni', '8cb2237d0679ca88db6464eac60da96345513964', 'default-avatar.jpg'),
(60, '103', '4321', 'Aby', 'Serang', '082114952019', '02-11-2025', '03-12-2025', 6000000, 'Penghuni', '8cb2237d0679ca88db6464eac60da96345513964', 'f75a16a7ac6aa9e2bd330ff8220dfda6.jpg'),
(61, '104', '2354646', 'Frando', 'Bogor', '087253231', '02-11-2025', '12-12-2025', 7200000, 'Penghuni', 'c24d0a1968e339c3786751ab16411c2c24ce8a2e', 'default-avatar.jpg'),
(62, '105', '4321433', 'Tama', 'Senen', '0821326654382', '03-11-2025', '10-12-2025', 7200000, 'Penghuni', '93ba1608fc10b710894fb9f8c89724c6eeb44d11', 'f20bdddc20d5c252a6d7b284ecdd1932.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `penghuni_session`
--

CREATE TABLE `penghuni_session` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure for view `detail_kamar`
--
DROP TABLE IF EXISTS `detail_kamar`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detail_kamar`  AS SELECT `kamar`.`lantai` AS `lantai`, `kamar`.`no_kamar` AS `no_kamar`, `kamar`.`harga` AS `harga`, sum(`keuangan_penghuni`.`harga_per_bulan`) AS `total_harga_per_bulan`, sum(`keuangan_penghuni`.`bayar`) AS `total_bayar`, coalesce(sum(`keuangan_penghuni`.`harga_per_bulan`),0) - coalesce(sum(`keuangan_penghuni`.`bayar`),0) AS `piutang`, count(`keuangan_penghuni`.`id`) AS `jml_penghuni` FROM (`kamar` left join (select `penghuni`.`id` AS `id`,`penghuni`.`no_kamar` AS `no_kamar`,`penghuni`.`harga_per_bulan` AS `harga_per_bulan`,sum(`keuangan`.`bayar`) AS `bayar` from (`penghuni` left join `keuangan` on(`keuangan`.`id_penghuni` = `penghuni`.`id`)) where `penghuni`.`status` = 'Penghuni' group by `penghuni`.`id`) `keuangan_penghuni` on(`kamar`.`no_kamar` = `keuangan_penghuni`.`no_kamar`)) GROUP BY `kamar`.`no_kamar` ;

-- --------------------------------------------------------

--
-- Structure for view `detail_pembayaran`
--
DROP TABLE IF EXISTS `detail_pembayaran`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detail_pembayaran`  AS SELECT `keuangan`.`id_pembayaran` AS `id_pembayaran`, `keuangan`.`id_penghuni` AS `id_penghuni`, `penghuni`.`no_kamar` AS `no_kamar`, `penghuni`.`nama` AS `nama`, `penghuni`.`no_ktp` AS `no_ktp`, `keuangan`.`tgl_bayar` AS `tgl_bayar`, `penghuni`.`harga_per_bulan` AS `harga_per_bulan`, `keuangan`.`bayar` AS `bayar`, `keuangan`.`ket` AS `ket` FROM (`penghuni` join `keuangan` on(`penghuni`.`id` = `keuangan`.`id_penghuni`)) WHERE 1 = '1' ORDER BY str_to_date(`keuangan`.`tgl_bayar`,'%d-%m-%Y') DESC ;

-- --------------------------------------------------------

--
-- Structure for view `detail_penghuni`
--
DROP TABLE IF EXISTS `detail_penghuni`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detail_penghuni`  AS SELECT `penghuni`.`id` AS `id`, `penghuni`.`no_kamar` AS `no_kamar`, `penghuni`.`nama` AS `nama`, `penghuni`.`no_ktp` AS `no_ktp`, `penghuni`.`alamat` AS `alamat`, `penghuni`.`no` AS `no`, `penghuni`.`tgl_masuk` AS `tgl_masuk`, `penghuni`.`tgl_keluar` AS `tgl_keluar`, `penghuni`.`status` AS `status`, `penghuni`.`harga_per_bulan` AS `harga_per_bulan`, sum(`keuangan`.`bayar`) AS `bayar`, `penghuni`.`harga_per_bulan`- coalesce(sum(`keuangan`.`bayar`),0) AS `piutang` FROM (`penghuni` left join `keuangan` on(`penghuni`.`id` = `keuangan`.`id_penghuni`)) GROUP BY `penghuni`.`id` ORDER BY `penghuni`.`no_kamar` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`no_kamar`);

--
-- Indexes for table `keuangan`
--
ALTER TABLE `keuangan`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `fk_pembayaran` (`id_penghuni`);

--
-- Indexes for table `pengajuan_pembayaran`
--
ALTER TABLE `pengajuan_pembayaran`
  ADD PRIMARY KEY (`id_pengajuan`),
  ADD KEY `fk_pengajuan_penghuni` (`id_penghuni`);

--
-- Indexes for table `penghuni`
--
ALTER TABLE `penghuni`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kamar` (`no_kamar`),
  ADD KEY `idx_nama` (`nama`);

--
-- Indexes for table `penghuni_session`
--
ALTER TABLE `penghuni_session`
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `keuangan`
--
ALTER TABLE `keuangan`
  MODIFY `id_pembayaran` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pengajuan_pembayaran`
--
ALTER TABLE `pengajuan_pembayaran`
  MODIFY `id_pengajuan` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `penghuni`
--
ALTER TABLE `penghuni`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `keuangan`
--
ALTER TABLE `keuangan`
  ADD CONSTRAINT `fk_pembayaran` FOREIGN KEY (`id_penghuni`) REFERENCES `penghuni` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengajuan_pembayaran`
--
ALTER TABLE `pengajuan_pembayaran`
  ADD CONSTRAINT `fk_pengajuan_penghuni` FOREIGN KEY (`id_penghuni`) REFERENCES `penghuni` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penghuni`
--
ALTER TABLE `penghuni`
  ADD CONSTRAINT `fk_kamar` FOREIGN KEY (`no_kamar`) REFERENCES `kamar` (`no_kamar`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
