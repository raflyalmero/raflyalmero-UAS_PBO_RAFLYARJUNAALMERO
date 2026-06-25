-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 25, 2026 at 07:59 
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_uas_pbo_ti1d_raflyarjunaalmero`
--

-- -------------------------------------------------------

--
-- Table structure for table `tabel_mahasiswa`
--

CREATE TABLE `tabel_mahasiswa` (
  `id_mahasiswa` int NOT NULL,
  `nama_mahasiswa` varchar(100) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `semester` int NOT NULL,
  `tarif_ukt_nominal` decimal(15,2) NOT NULL,
  `jenis_pembiayaan` enum('Mandiri','Bidikmisi','Prestasi') NOT NULL,
  `golongan_ukt` varchar(10) DEFAULT NULL,
  `nama_wali` varchar(100) DEFAULT NULL,
  `nomor_kip_kuliah` varchar(50) DEFAULT NULL,
  `dana_saku_subsidi` decimal(15,2) DEFAULT NULL,
  `nama_instansi_beasiswa` varchar(100) DEFAULT NULL,
  `minimal_ipk_bersyarat` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_mahasiswa`
--

INSERT INTO `tabel_mahasiswa` (`id_mahasiswa`, `nama_mahasiswa`, `nim`, `semester`, `tarif_ukt_nominal`, `jenis_pembiayaan`, `golongan_ukt`, `nama_wali`, `nomor_kip_kuliah`, `dana_saku_subsidi`, `nama_instansi_beasiswa`, `minimal_ipk_bersyarat`) VALUES
(1, 'Andi Pratama', '202101001', 6, '8500000.00', 'Mandiri', 'A', NULL, NULL, NULL, NULL, NULL),
(2, 'Siti Rahayu', '202101002', 4, '7500000.00', 'Mandiri', 'B', NULL, NULL, NULL, NULL, NULL),
(3, 'Budi Santoso', '202101003', 2, '9500000.00', 'Mandiri', 'A', NULL, NULL, NULL, NULL, NULL),
(4, 'Dewi Lestari', '202101004', 8, '6500000.00', 'Mandiri', 'C', NULL, NULL, NULL, NULL, NULL),
(5, 'Ahmad Fauzi', '202101005', 6, '8500000.00', 'Mandiri', 'A', NULL, NULL, NULL, NULL, NULL),
(6, 'Rina Marlina', '202101006', 4, '7500000.00', 'Mandiri', 'B', NULL, NULL, NULL, NULL, NULL),
(7, 'Dedi Kurniawan', '202101007', 2, '7000000.00', 'Mandiri', 'B', NULL, NULL, NULL, NULL, NULL),
(8, 'Putri Wulandari', '202102001', 6, '500000.00', 'Bidikmisi', 'D', 'Slamet Riyadi', 'KIP202102001', '1200000.00', NULL, NULL),
(9, 'Joko Susilo', '202102002', 4, '500000.00', 'Bidikmisi', 'D', 'Sri Mulyani', 'KIP202102002', '1200000.00', NULL, NULL),
(10, 'Nina Herawati', '202102003', 2, '500000.00', 'Bidikmisi', 'D', 'Agus Salim', 'KIP202102003', '1200000.00', NULL, NULL),
(11, 'Rudi Hartono', '202102004', 8, '500000.00', 'Bidikmisi', 'D', 'Siti Fatimah', 'KIP202102004', '1200000.00', NULL, NULL),
(12, 'Mega Susanti', '202102005', 6, '500000.00', 'Bidikmisi', 'D', 'Bambang Pamungkas', 'KIP202102005', '1200000.00', NULL, NULL),
(13, 'Gilang Ramadhan', '202102006', 4, '500000.00', 'Bidikmisi', 'D', 'Eka Yuliana', 'KIP202102006', '1200000.00', NULL, NULL),
(14, 'Tari Mulyani', '202102007', 2, '500000.00', 'Bidikmisi', 'D', 'Hendra Wijaya', 'KIP202102007', '1200000.00', NULL, NULL),
(15, 'Rizky Febrian', '202103001', 6, '2500000.00', 'Prestasi', 'C', NULL, NULL, NULL, 'Beasiswa Unggulan Kemendikbud', '3.50'),
(16, 'Fajar Nugroho', '202103002', 4, '2500000.00', 'Prestasi', 'C', NULL, NULL, NULL, 'Beasiswa Unggulan Kemendikbud', '3.50'),
(17, 'Lina Indah Sari', '202103003', 2, '2500000.00', 'Prestasi', 'C', NULL, NULL, NULL, 'Beasiswa Djarum Plus', '3.25'),
(18, 'Bayu Aji Saputra', '202103004', 8, '2500000.00', 'Prestasi', 'C', NULL, NULL, NULL, 'Beasiswa Unggulan Kemendikbud', '3.50'),
(19, 'Anisa Rahmawati', '202103005', 6, '2500000.00', 'Prestasi', 'C', NULL, NULL, NULL, 'Beasiswa Djarum Plus', '3.25'),
(20, 'Yoga Aditya', '202103006', 4, '2500000.00', 'Prestasi', 'C', NULL, NULL, NULL, 'Beasiswa Bank Indonesia', '3.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_mahasiswa`
--
ALTER TABLE `tabel_mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD UNIQUE KEY `nim` (`nim`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tabel_mahasiswa`
--
ALTER TABLE `tabel_mahasiswa`
  MODIFY `id_mahasiswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
