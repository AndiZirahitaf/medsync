-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2025 at 02:39 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projek`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `unique_id` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `unique_id`) VALUES
(1, 'admin1', 'admin1@gmail.com', '$2y$10$7KB1XIBGj0KVI2GQ2Ibo6uj3vkGPU5JzPhUVrJzgYXdyx/MJvKJXC', 123456);

-- --------------------------------------------------------

--
-- Table structure for table `consultations`
--

CREATE TABLE `consultations` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `nth_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jml_sesi` tinyint(4) NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `keluhan` varchar(255) NOT NULL,
  `kode_unik` varchar(6) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultations`
--

INSERT INTO `consultations` (`id`, `doctor_id`, `patient_id`, `nth_id`, `tanggal`, `jml_sesi`, `time_start`, `time_end`, `keluhan`, `kode_unik`, `status`) VALUES
(1, 1, 2, 1, '2024-05-15', 1, '12:00:00', '12:30:00', '', '79C1B9', 'archive'),
(3, 1, 1, 1, '2024-06-06', 3, '21:00:00', '22:30:00', '', '27EA83', 'archive'),
(4, 2, 2, 1, '2024-06-20', 4, '16:30:00', '18:30:00', '', 'BD5DB3', 'archive'),
(5, 1, 1, 2, '2024-06-15', 3, '17:30:00', '19:00:00', '', 'DS4K2B', 'archive'),
(9, 2, 2, 2, '2024-06-20', 3, '17:30:00', '19:00:00', '', 'C5A58A', 'archive'),
(10, 3, 2, 1, '2024-06-28', 4, '20:30:00', '22:30:00', '', 'C5A58B', 'active'),
(11, 1, 2, 2, '2024-06-19', 2, '20:06:00', '21:06:00', '', 'C5A58C', 'active'),
(12, 1, 2, 3, '2024-06-04', 3, '20:10:00', '21:40:00', 'Suka muntah muntah Suka muntah muntah Suka muntah muntahSuka muntah muntahSuka muntah muntahSuka muntah muntah', 'C5A58D', 'active'),
(13, 2, 2, 3, '2024-06-05', 1, '21:30:00', '22:00:00', 'keren banget guisss', 'B2EF0C', 'active'),
(14, 1, 1, 3, '2024-06-26', 3, '16:10:00', '17:40:00', 'Saya suka stress', '383125', 'active'),
(15, 1, 1, 4, '2024-06-03', 1, '16:48:00', '17:18:00', 'aed', '85EB37', 'active'),
(16, 37, 2, 1, '2024-06-05', 1, '20:15:00', '20:45:00', 'Saya suka buang air kecil, kenapa ya dok?', 'I9DJ5F', 'active'),
(17, 37, 6, 1, '2024-06-12', 3, '23:00:00', '00:30:00', 'Sakit Hidung', '58F75A', 'archive'),
(18, 37, 5, 1, '2024-06-20', 4, '20:00:00', '22:00:00', 'Suka muntah muntah dok', 'F2C9C2', 'active'),
(19, 37, 6, 2, '2024-06-14', 1, '23:00:00', '23:30:00', 'ewfsefe', '02B3E1', 'archive'),
(20, 37, 1, 1, '2024-06-06', 2, '03:24:00', '04:24:00', 'asdasdasd', '942451', 'archive'),
(21, 38, 7, 1, '2024-06-06', 2, '20:30:00', '21:30:00', 'Saya suka mual mual dok', 'EB222D', 'archive'),
(22, 38, 7, 2, '2024-06-06', 3, '20:00:00', '21:30:00', 'Saya suka mual mual dok', 'F10500', 'archive'),
(23, 39, 7, 1, '2024-06-12', 1, '20:08:00', '20:38:00', 'dfs', 'DF1CA2', 'active'),
(24, 3, 8, 1, '2025-06-10', 1, '00:03:00', '00:33:00', ',', '', 'active'),
(25, 1, 8, 1, '2025-06-09', 1, '21:02:00', '21:32:00', ',', '', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `str` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `profesi` int(11) NOT NULL,
  `pengalaman` int(11) NOT NULL,
  `rating` varchar(255) NOT NULL,
  `mahir` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `alumni` varchar(255) NOT NULL,
  `praktik` varchar(255) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `unique_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `str`, `nama`, `profesi`, `pengalaman`, `rating`, `mahir`, `harga`, `alumni`, `praktik`, `gambar`, `email`, `password`, `unique_id`) VALUES
(1, '354645634234', 'Dr. Mukesh Ambani', 2, 8, '4.9', 'Stress, Depresi', 100000, 'ITB', 'Jl. Kertayasa No.9', '66602f00989a9.jpg', 'mukesh@gmail.com', '$2y$10$0b49BJbL.lnO8PseT70xdOlY5sBVTWpllWoN1E4Tn1EVMkt2Ba5Aq', 345345),
(2, '4820193756128394', 'Drg. Gabriel Egay', 4, 5, '4.9', 'Orthodonti, Kecantikan Gigi', 150000, 'UPN \"Veteran\" Tambun', 'JI. Perjuangan II, Desa\r\nSukamakur Kec. Cibarusah Kota Karawang', 'doc-1.jpg', 'egi@gmail.com', '$2y$10$0b49BJbL.lnO8PseT70xdOlY5sBVTWpllWoN1E4Tn1EVMkt2Ba5Aq', 567567),
(3, '9302746510982345', 'Dr. Shinichi Kudo, Sp. PD', 3, 10, '2.9', 'Organ Pencernaan', 50000, 'STIK Pamulang, Kota Tangerang Selatan', 'Panggilan', 'conan.png', 'conan@gmail.com', '$2y$10$0b49BJbL.lnO8PseT70xdOlY5sBVTWpllWoN1E4Tn1EVMkt2Ba5Aq', 789789),
(5, '4961839572967103', 'St. Jaygarcia Saturn', 5, 500, '1.0', 'Tumbuh Kembang Anak', 1000, 'Marijoa, Red Line', 'Elbaf', 'saturn.jpg', 'saturn@gmail.com', '$2y$10$0b49BJbL.lnO8PseT70xdOlY5sBVTWpllWoN1E4Tn1EVMkt2Ba5Aq', 234234),
(37, '7866786856456', 'Sega Amadeus von Acedia', 1, 4, '4.5', 'Sumsum tulang belakang, saraf kejepit', 40000, 'Universitas Bala-bala', 'Jl. Kartosuwiryo No. 999', '665f0d75b45f6.png', 'sega@gmail.com', '$2y$10$JXjhxDbS6bgo87yFbfbU5.NRBUaDecLAhsax2ar8hdwzqG.a.jrQy', 363878),
(38, '3894728462789', 'Dr. Owen Al-Khawarizmi', 1, 9, '4.7', 'Pankreas, Hati', 60000, 'Universitas Tulung Agung Surakarta', 'PT Alkadera, Sumatera Selatan', '666038a6d3ab5.png', 'owen@gmail.com', '$2y$10$wRQit1dP9HJpn05alXzB/O29g2FdsIgwrLYxUdqWXn/N1c4WPYeeK', 281985),
(39, '435345345', 'I Made Yudha Widya Putra', 2, 1, '5.0', 'Broken Home', 99000, 'PT Mencari Cinta Sejati', 'Jl Hadikusumo No. 11', '666033136aa15.jpg', 'yudha@gmail.com', '$2y$10$PwcNR9YGacURqAmpsLqTtek3FdCYdY3IoQsBFI3mVmujMUFsj/Kfe', 778838);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` varchar(100) NOT NULL,
  `outgoing_msg_id` varchar(100) NOT NULL,
  `cons_code` varchar(6) NOT NULL,
  `msg` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `cons_code`, `msg`) VALUES
(72, '345345', '568858', 'C5A58C', 'halo gais'),
(73, '568858', '345345', 'C5A58C', 'halo fera'),
(74, '568858', '345345', 'C5A58C', 'selamat pagi'),
(75, '345345', '568858', 'C5A58C', 'pagi pak'),
(76, '345345', '568858', 'C5A58D', 'halo mas'),
(77, '568858', '345345', 'C5A58D', 'saya ferawati '),
(78, '715339', '363878', '58F75A', 'halo ubay'),
(79, '363878', '715339', '58F75A', 'halo dok'),
(80, '363878', '715339', '02B3E1', 'permisi dok'),
(81, '715339', '363878', '02B3E1', 'iyah ubay'),
(82, '363878', '715339', '02B3E1', 'saya ingin menceritakan masalah saya sebagai anak buangan dari desa dok'),
(83, '715339', '363878', '02B3E1', 'boleh silakan'),
(84, '715339', '363878', '58F75A', 'halo'),
(85, '363878', '715339', '58F75A', 'apa kabar'),
(86, '715339', '363878', '58F75A', 'terima kasih'),
(87, '363878', '135346', '942451', 'halo dokter'),
(88, '135346', '363878', '942451', 'halo, coba ceritakan dengan detail keluhan anda'),
(89, '363878', '135346', '942451', 'saya suka muntah munta dan mual'),
(90, '135346', '363878', '942451', 'oh begitu, setiap hari makannya apa?'),
(91, '200998', '281985', 'EB222D', 'halo mas'),
(92, '200998', '281985', 'EB222D', 'tolong ceritakan mengenai riwayat kesehatannya'),
(93, '281985', '200998', 'EB222D', 'saya suka makan pedes dok'),
(94, '281985', '200998', 'EB222D', 'trus makan cuma sekali sehari'),
(95, '200998', '281985', 'EB222D', 'sebaiknya makan jangan ditinggal ya dik'),
(96, '281985', '200998', 'F10500', 'halo dok'),
(97, '200998', '281985', 'F10500', 'ceritakan mengenai penyakit anda'),
(98, '281985', '200998', 'F10500', 'saya jarang makan dok'),
(99, '281985', '200998', 'F10500', 'saya sekali makan langsung mual'),
(100, '200998', '281985', 'F10500', 'coba puasa'),
(101, '135346', '345345', '85EB37', 'halo pak'),
(102, '345345', '135346', '85EB37', 'hlao'),
(103, '345345', '135346', '85EB37', 'gimana kabarnya pak'),
(104, '135346', '345345', '85EB37', 'iya, sehat '),
(105, '135346', '345345', '85EB37', 'alhamdulilah');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kelamin` char(1) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `bb` int(3) NOT NULL,
  `tb` int(3) NOT NULL,
  `no_tlp` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `unique_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `email`, `nama`, `kelamin`, `tgl_lahir`, `bb`, `tb`, `no_tlp`, `password`, `unique_id`) VALUES
(1, 'umar@u.com', 'Umar Andika Fatihariz', 'l', '2006-05-24', 50, 170, '081280882405', '$2y$10$0b49BJbL.lnO8PseT70xdOlY5sBVTWpllWoN1E4Tn1EVMkt2Ba5Aq', 135346),
(2, 'aidil@gmail.com', 'Aidil Addzikra', 'l', '2005-11-17', 70, 165, '0812 2349 4355', '$2y$10$TJmSZfrpvB8PDK0SWHiJkuPSKsvVsyofUU0Hf.bNfbPCloio1gO9C', 568858),
(3, 'ano@gmail.com', 'Adriano Muhammad Rafi', 'p', '2024-05-09', 75, 190, '0812-4556-2958', '$2y$10$dBPk3k1npdMf/pQvpsg/rehBx9SC8d9JWWUOaX6x90LWAvvRhs36u', 347853),
(5, 'fatih@gmail.com', 'M. Fatih Al-Ghifary', 'l', '2005-08-10', 82, 172, '081280882405', '$2y$10$0rNokZ2Y9ZhfmzZi6qI8MO6XEHOTyuTSkWoNWLIb40wv47f.V0gai', 981072),
(6, 'ubay@gmail.com', 'Ubay Sawadikap Rottenmaro', 'l', '2005-03-31', 85, 171, '081280882405', '$2y$10$RAujBzJMRSSh9asro.6tfOXoF35/S48Qv2j8NX5/glLX6LXmc1BhS', 715339),
(7, 'edu@gmail.com', 'Eduardo Saverin', 'l', '1995-07-20', 70, 190, '081280882405', '$2y$10$X71it88inGP9ylFEVd0IluuASBsD1yqRzvX/lmCqCFgigwBmduRV6', 200998),
(8, 'r@u.com', 'u', 'p', '2025-06-17', 1, 1, '1', '$2y$10$U1ZtLS6spK4Z22sKkUK7jOhMiWYx3l0nasx/pfcb2i/ldz4uIxYJK', 430196);

-- --------------------------------------------------------

--
-- Table structure for table `profesi_spesialis`
--

CREATE TABLE `profesi_spesialis` (
  `id` int(11) NOT NULL,
  `profesi` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profesi_spesialis`
--

INSERT INTO `profesi_spesialis` (`id`, `profesi`) VALUES
(1, 'Dokter Umum'),
(2, 'Psikolog Klinis'),
(3, 'Spesialis Anak'),
(4, 'Dokter Gigi'),
(5, 'Sp. Penyakit Dalam');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profesi` (`profesi`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profesi_spesialis`
--
ALTER TABLE `profesi_spesialis`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `consultations`
--
ALTER TABLE `consultations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `profesi_spesialis`
--
ALTER TABLE `profesi_spesialis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `consultations`
--
ALTER TABLE `consultations`
  ADD CONSTRAINT `cons_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `cons_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `profesi_FK_1` FOREIGN KEY (`profesi`) REFERENCES `profesi_spesialis` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
