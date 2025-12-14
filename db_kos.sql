-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2025 at 08:08 AM
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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_tagihan_bulanan` ()   BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_id INT;
    DECLARE v_no_kamar VARCHAR(10);
    DECLARE v_tgl_masuk VARCHAR(10);
    DECLARE v_harga_kamar INT;
    DECLARE v_bulan_berlalu INT;
    DECLARE v_tagihan_baru INT;
    DECLARE v_updated INT DEFAULT 0;
    DECLARE v_total INT DEFAULT 0;
    DECLARE v_errors TEXT DEFAULT '';
    
    DECLARE cur CURSOR FOR 
        SELECT p.id, p.no_kamar, p.tgl_masuk
        FROM penghuni p
        WHERE p.status = 'Penghuni';
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE, 
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET v_errors = CONCAT(v_errors, 'Error: ', @text, '; ');
    END;
    
    SELECT COUNT(*) INTO v_total FROM penghuni WHERE status = 'Penghuni';
    
    OPEN cur;
    
    read_loop: LOOP
        FETCH cur INTO v_id, v_no_kamar, v_tgl_masuk;
        
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        SELECT harga INTO v_harga_kamar 
        FROM kamar 
        WHERE no_kamar = v_no_kamar;
        
        SET v_bulan_berlalu = TIMESTAMPDIFF(MONTH, 
            STR_TO_DATE(v_tgl_masuk, '%d-%m-%Y'), 
            NOW()
        );
        
        SET v_tagihan_baru = v_harga_kamar * (v_bulan_berlalu + 1);
        
        -- UPDATE: harga_per_bulan -> tagihan
        UPDATE penghuni 
        SET tagihan = v_tagihan_baru
        WHERE id = v_id;
        
        SET v_updated = v_updated + 1;
    END LOOP;
    
    CLOSE cur;
    
    INSERT INTO log_auto_update (tanggal_update, jumlah_penghuni, jumlah_updated, status, detail)
    VALUES (
        NOW(), 
        v_total,
        v_updated,
        IF(v_updated = v_total, 'success', IF(v_updated > 0, 'partial', 'failed')),
        IF(v_errors = '', 
            CONCAT('Successfully updated ', v_updated, ' out of ', v_total, ' penghuni'), 
            v_errors)
    );
    
    SELECT v_updated AS updated, v_total AS total, 
           IF(v_errors = '', 'success', 'partial') AS status;
    
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_hitung_piutang` (`p_id` INT) RETURNS DECIMAL(15,2) DETERMINISTIC BEGIN
    DECLARE v_tagihan DECIMAL(15,2);
    DECLARE v_total_bayar DECIMAL(15,2);
    DECLARE v_piutang DECIMAL(15,2);
    
    -- Ambil tagihan
    SELECT COALESCE(tagihan, 0) INTO v_tagihan
    FROM penghuni
    WHERE id = p_id;
    
    -- Ambil total pembayaran
    SELECT COALESCE(SUM(bayar), 0) INTO v_total_bayar
    FROM keuangan
    WHERE id_penghuni = p_id;
    
    -- Hitung piutang
    SET v_piutang = v_tagihan - v_total_bayar;
    
    -- Pastikan tidak negatif
    IF v_piutang < 0 THEN
        SET v_piutang = 0;
    END IF;
    
    RETURN v_piutang;
END$$

DELIMITER ;

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
('Administrator', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997');

-- --------------------------------------------------------

--
-- Stand-in structure for view `detail_kamar`
-- (See below for the actual view)
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
,`tagihan` int(30)
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
,`status` varchar(20)
,`tagihan` int(30)
,`foto` varchar(255)
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
('1', '101', 700000),
('1', '102', 400000),
('1', '103', 600000),
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
(9, 64, '12-11-2025', 700000, 'Pembayaran via GoPay - November'),
(12, 64, '13-12-2025', 700000, 'Pembayaran via GoPay - Desember 2025'),
(13, 76, '13-12-2025', 900000, 'Pembayaran via Transfer BCA - Segini dulu ya Bu '),
(16, 77, '14-12-2025', 500000, 'Nyicil'),
(17, 76, '14-12-2025', 200000, 'Pembayaran via Transfer BCA');

-- --------------------------------------------------------

--
-- Table structure for table `log_auto_update`
--

CREATE TABLE `log_auto_update` (
  `id` int(10) NOT NULL,
  `tanggal_update` datetime NOT NULL,
  `jumlah_penghuni` int(5) NOT NULL DEFAULT 0,
  `jumlah_updated` int(5) NOT NULL DEFAULT 0,
  `status` enum('success','partial','failed') DEFAULT 'success',
  `detail` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_auto_update`
--

INSERT INTO `log_auto_update` (`id`, `tanggal_update`, `jumlah_penghuni`, `jumlah_updated`, `status`, `detail`, `created_at`) VALUES
(1, '2025-12-13 10:15:20', 6, 6, 'success', 'Initial setup - Database structure updated', '2025-12-13 10:15:20');

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
(5, 64, 700000, 'GoPay', '94ea6ff57d95f258b760ffe38b0bdccd.png', '2025-11-12 10:03:31', 'approved', '2025-11-12 10:04:28', 'November'),
(6, 64, 700000, 'GoPay', '948a88ac5571d72c4bc475c191a23de4.png', '2025-12-13 17:37:14', 'approved', '2025-12-13 17:38:22', 'Desember 2025'),
(7, 76, 900000, 'Transfer BCA', '3c41fcd0f04e28d2a121057f19bacb85.png', '2025-12-13 23:28:58', 'approved', '2025-12-13 23:30:02', 'Segini dulu ya Bu '),
(8, 76, 200000, 'Transfer BCA', '1b0f65f705fb6bab07f59ce896f8ab9b.jpg', '2025-12-14 03:47:24', 'approved', '2025-12-14 03:48:11', '');

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
  `tagihan` int(30) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `password` varchar(300) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `penghuni`
--

INSERT INTO `penghuni` (`id`, `no_kamar`, `no_ktp`, `nama`, `alamat`, `no`, `tgl_masuk`, `tagihan`, `status`, `password`, `foto`) VALUES
(64, '101', '43455', 'Aby', 'Serang', '082114952019', '03-11-2025', 1400000, 'Penghuni', '8cb2237d0679ca88db6464eac60da96345513964', '427940df744ed08316e0f16f58f7367f.jpg'),
(76, '102', '8273764', 'Tama', 'Senen', '0872175212', '08-08-2025', 2000000, 'Penghuni', '7c4a8d09ca3762af61e59520943dc26494f8941b', '935ec5691ccb64360c366adda8c5b383.jpg'),
(77, '104', '4321433', 'Frando', 'Jagakarsa', '09287238271', '09-09-2025', 2400000, 'Penghuni', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'default-avatar.png'),
(78, '204', '928397221', 'Yono', 'Pesing', '082653621121', '09-11-2025', 800000, 'Penghuni', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'default-avatar.png');

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
-- Stand-in structure for view `v_dashboard_stats`
-- (See below for the actual view)
--
CREATE TABLE `v_dashboard_stats` (
`total_kamar` bigint(21)
,`kamar_terisi` bigint(21)
,`total_penghuni` bigint(21)
,`pendapatan_bulan_ini` decimal(41,0)
,`pendapatan_tahun_ini` decimal(41,0)
,`total_piutang` decimal(64,0)
,`pengajuan_pending` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_laporan_keuangan`
-- (See below for the actual view)
--
CREATE TABLE `v_laporan_keuangan` (
`bulan` varchar(7)
,`tahun` varchar(4)
,`periode` varchar(69)
,`jumlah_transaksi` bigint(21)
,`total_pendapatan` decimal(41,0)
,`rata_rata_pembayaran` decimal(23,4)
);

-- --------------------------------------------------------

--
-- Structure for view `detail_kamar`
--
DROP TABLE IF EXISTS `detail_kamar`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detail_kamar`  AS SELECT `kamar`.`lantai` AS `lantai`, `kamar`.`no_kamar` AS `no_kamar`, `kamar`.`harga` AS `harga`, coalesce(sum(`keuangan_penghuni`.`tagihan`),0) AS `total_biaya`, coalesce(sum(`keuangan_penghuni`.`bayar`),0) AS `total_bayar`, coalesce(sum(`keuangan_penghuni`.`tagihan`),0) - coalesce(sum(`keuangan_penghuni`.`bayar`),0) AS `piutang`, count(`keuangan_penghuni`.`id`) AS `jml_penghuni` FROM (`kamar` left join (select `penghuni`.`id` AS `id`,`penghuni`.`no_kamar` AS `no_kamar`,`penghuni`.`tagihan` AS `tagihan`,coalesce(sum(`keuangan`.`bayar`),0) AS `bayar` from (`penghuni` left join `keuangan` on(`keuangan`.`id_penghuni` = `penghuni`.`id`)) where `penghuni`.`status` = 'Penghuni' group by `penghuni`.`id`) `keuangan_penghuni` on(`kamar`.`no_kamar` = `keuangan_penghuni`.`no_kamar`)) GROUP BY `kamar`.`no_kamar` ;

-- --------------------------------------------------------

--
-- Structure for view `detail_pembayaran`
--
DROP TABLE IF EXISTS `detail_pembayaran`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detail_pembayaran`  AS SELECT `keuangan`.`id_pembayaran` AS `id_pembayaran`, `keuangan`.`id_penghuni` AS `id_penghuni`, `penghuni`.`no_kamar` AS `no_kamar`, `penghuni`.`nama` AS `nama`, `penghuni`.`no_ktp` AS `no_ktp`, `keuangan`.`tgl_bayar` AS `tgl_bayar`, `penghuni`.`tagihan` AS `tagihan`, `keuangan`.`bayar` AS `bayar`, `keuangan`.`ket` AS `ket` FROM (`penghuni` join `keuangan` on(`penghuni`.`id` = `keuangan`.`id_penghuni`)) WHERE 1 = 1 ORDER BY str_to_date(`keuangan`.`tgl_bayar`,'%d-%m-%Y') DESC ;

-- --------------------------------------------------------

--
-- Structure for view `detail_penghuni`
--
DROP TABLE IF EXISTS `detail_penghuni`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detail_penghuni`  AS SELECT `penghuni`.`id` AS `id`, `penghuni`.`no_kamar` AS `no_kamar`, `penghuni`.`nama` AS `nama`, `penghuni`.`no_ktp` AS `no_ktp`, `penghuni`.`alamat` AS `alamat`, `penghuni`.`no` AS `no`, `penghuni`.`tgl_masuk` AS `tgl_masuk`, `penghuni`.`status` AS `status`, `penghuni`.`tagihan` AS `tagihan`, `penghuni`.`foto` AS `foto`, coalesce(sum(`keuangan`.`bayar`),0) AS `bayar`, `penghuni`.`tagihan`- coalesce(sum(`keuangan`.`bayar`),0) AS `piutang` FROM (`penghuni` left join `keuangan` on(`penghuni`.`id` = `keuangan`.`id_penghuni`)) GROUP BY `penghuni`.`id` ORDER BY `penghuni`.`no_kamar` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_dashboard_stats`
--
DROP TABLE IF EXISTS `v_dashboard_stats`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_dashboard_stats`  AS SELECT (select count(0) from `kamar`) AS `total_kamar`, (select count(distinct `penghuni`.`no_kamar`) from `penghuni` where `penghuni`.`status` = 'Penghuni') AS `kamar_terisi`, (select count(0) from `penghuni` where `penghuni`.`status` = 'Penghuni') AS `total_penghuni`, (select sum(`keuangan`.`bayar`) from `keuangan` where date_format(str_to_date(`keuangan`.`tgl_bayar`,'%d-%m-%Y'),'%Y-%m') = date_format(current_timestamp(),'%Y-%m')) AS `pendapatan_bulan_ini`, (select sum(`keuangan`.`bayar`) from `keuangan` where date_format(str_to_date(`keuangan`.`tgl_bayar`,'%d-%m-%Y'),'%Y') = date_format(current_timestamp(),'%Y')) AS `pendapatan_tahun_ini`, (select sum(`p`.`tagihan` - coalesce((select sum(`k`.`bayar`) from `keuangan` `k` where `k`.`id_penghuni` = `p`.`id`),0)) from `penghuni` `p` where `p`.`status` = 'Penghuni') AS `total_piutang`, (select count(0) from `pengajuan_pembayaran` where `pengajuan_pembayaran`.`status` = 'pending') AS `pengajuan_pending` ;

-- --------------------------------------------------------

--
-- Structure for view `v_laporan_keuangan`
--
DROP TABLE IF EXISTS `v_laporan_keuangan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_laporan_keuangan`  AS SELECT date_format(str_to_date(`k`.`tgl_bayar`,'%d-%m-%Y'),'%Y-%m') AS `bulan`, date_format(str_to_date(`k`.`tgl_bayar`,'%d-%m-%Y'),'%Y') AS `tahun`, date_format(str_to_date(`k`.`tgl_bayar`,'%d-%m-%Y'),'%M %Y') AS `periode`, count(`k`.`id_pembayaran`) AS `jumlah_transaksi`, sum(`k`.`bayar`) AS `total_pendapatan`, avg(`k`.`bayar`) AS `rata_rata_pembayaran` FROM `keuangan` AS `k` GROUP BY date_format(str_to_date(`k`.`tgl_bayar`,'%d-%m-%Y'),'%Y-%m') ORDER BY date_format(str_to_date(`k`.`tgl_bayar`,'%d-%m-%Y'),'%Y') DESC, date_format(str_to_date(`k`.`tgl_bayar`,'%d-%m-%Y'),'%Y-%m') DESC ;

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
  ADD KEY `fk_pembayaran` (`id_penghuni`),
  ADD KEY `idx_tgl_bayar` (`tgl_bayar`),
  ADD KEY `idx_id_penghuni_tgl` (`id_penghuni`,`tgl_bayar`);

--
-- Indexes for table `log_auto_update`
--
ALTER TABLE `log_auto_update`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tanggal` (`tanggal_update`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `pengajuan_pembayaran`
--
ALTER TABLE `pengajuan_pembayaran`
  ADD PRIMARY KEY (`id_pengajuan`),
  ADD KEY `fk_pengajuan_penghuni` (`id_penghuni`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_tgl_pengajuan` (`tgl_pengajuan`),
  ADD KEY `idx_id_penghuni_status` (`id_penghuni`,`status`);

--
-- Indexes for table `penghuni`
--
ALTER TABLE `penghuni`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kamar` (`no_kamar`),
  ADD KEY `idx_nama` (`nama`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_no_kamar_status` (`no_kamar`,`status`),
  ADD KEY `idx_tgl_masuk` (`tgl_masuk`);

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
  MODIFY `id_pembayaran` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `log_auto_update`
--
ALTER TABLE `log_auto_update`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pengajuan_pembayaran`
--
ALTER TABLE `pengajuan_pembayaran`
  MODIFY `id_pengajuan` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `penghuni`
--
ALTER TABLE `penghuni`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

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

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `event_update_tagihan` ON SCHEDULE EVERY 1 MONTH STARTS '2026-01-01 00:01:00' ON COMPLETION NOT PRESERVE ENABLE DO CALL sp_update_tagihan_bulanan()$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
