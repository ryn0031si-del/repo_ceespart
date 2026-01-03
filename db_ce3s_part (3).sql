-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2025 at 05:18 PM
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
-- Database: `db_ce3s_part`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_role` enum('admin','operator','owner') DEFAULT NULL,
  `activity_type` varchar(50) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `activity_type` varchar(50) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id_chat` int(11) NOT NULL,
  `id_pengirim` int(11) DEFAULT NULL,
  `id_penerima` int(11) DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `media_url` varchar(255) DEFAULT NULL,
  `media_type` varchar(50) DEFAULT NULL,
  `waktu_kirim` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id_chat`, `id_pengirim`, `id_penerima`, `pesan`, `media_url`, `media_type`, `waktu_kirim`) VALUES
(12, 3, 3, 'gabe', NULL, NULL, '2025-10-30 08:34:05'),
(14, 6, 3, 'wahyu', NULL, NULL, '2025-10-30 12:54:29'),
(18, 3, 3, 'gabe', NULL, NULL, '2025-11-02 07:37:35'),
(23, 4, 3, 'jawirrrr', NULL, NULL, '2025-12-01 13:08:13'),
(24, 4, 3, 'rudi', NULL, NULL, '2025-12-03 07:51:34'),
(25, 10, 3, 'dika', NULL, NULL, '2025-12-05 08:56:50'),
(26, 4, 3, 'agung', NULL, NULL, '2025-12-08 15:00:31'),
(27, 4, 3, 'dika', NULL, NULL, '2025-12-09 19:38:26'),
(28, 4, 3, 'santo', NULL, NULL, '2025-12-09 20:21:25'),
(29, 4, 3, 'hallo', NULL, NULL, '2025-12-10 04:48:02'),
(30, 3, 10, 'alll', NULL, NULL, '2025-12-10 06:02:10'),
(32, 3, 4, 'wkwkwkwkw', NULL, NULL, '2025-12-10 06:30:48'),
(34, 11, 3, 'Hallo riyan', NULL, NULL, '2025-12-10 08:13:15'),
(36, 11, 3, 'Pinjam dulu seratus', NULL, NULL, '2025-12-13 10:41:47'),
(37, 3, 11, 'SIAPPPP', NULL, NULL, '2025-12-13 10:42:46');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail` int(11) NOT NULL,
  `id_pesanan` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_saat_pesan` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id_detail`, `id_pesanan`, `id_produk`, `jumlah`, `harga_saat_pesan`) VALUES
(27, 27, NULL, 1, 60000.00),
(28, 28, NULL, 4, 60000.00),
(29, 29, NULL, 3, 60000.00),
(30, 30, NULL, 1, 60000.00),
(31, 31, NULL, 1, 60000.00),
(32, 32, NULL, 1, 60000.00),
(33, 33, NULL, 1, 60000.00),
(34, 34, NULL, 1, 60000.00),
(35, 35, NULL, 1, 60000.00),
(36, 36, NULL, 2, 60000.00),
(37, 37, NULL, 2, 60000.00),
(38, 38, NULL, 1, 60000.00),
(39, 39, NULL, 1, 60000.00),
(40, 40, NULL, 1, 222.00),
(41, 41, NULL, 1, 23233.00),
(42, 42, NULL, 1, 111112.00),
(43, 43, NULL, 1, 23233.00),
(44, 44, NULL, 1, 23233.00),
(45, 45, NULL, 1, 23233.00),
(46, 46, NULL, 1, 23233.00),
(47, 47, NULL, 1, 111112.00),
(48, 48, NULL, 2, 23233.00),
(49, 49, NULL, 4, 22222.00),
(50, 50, NULL, 3, 43333243.00),
(51, 51, 18, 1, 80000.00),
(52, 52, 20, 2, 500000.00),
(53, 53, 16, 1, 40000.00),
(54, 54, 16, 1, 40000.00);

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nama_penerima` varchar(255) NOT NULL,
  `no_hp_penerima` varchar(20) NOT NULL,
  `alamat_penerima` text NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `ongkir` int(11) NOT NULL DEFAULT 0,
  `status_pesanan` enum('Menunggu Pembayaran','Diproses','Dikirim','Selesai','Dibatalkan') NOT NULL DEFAULT 'Menunggu Pembayaran',
  `tanggal_pesanan` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_user`, `nama_penerima`, `no_hp_penerima`, `alamat_penerima`, `metode_pembayaran`, `total_harga`, `ongkir`, `status_pesanan`, `tanggal_pesanan`) VALUES
(27, NULL, '', '', '', '', 60000.00, 0, 'Diproses', '2025-10-30 04:34:11'),
(28, NULL, '', '', '', '', 240000.00, 0, 'Menunggu Pembayaran', '2025-10-30 05:19:12'),
(29, NULL, '', '', '', '', 180000.00, 0, 'Menunggu Pembayaran', '2025-10-30 05:21:51'),
(30, NULL, '', '', '', '', 60000.00, 0, 'Menunggu Pembayaran', '2025-10-30 05:22:33'),
(31, NULL, '', '', '', '', 60000.00, 0, 'Menunggu Pembayaran', '2025-10-30 05:23:27'),
(32, NULL, '', '', '', '', 60000.00, 0, 'Menunggu Pembayaran', '2025-10-30 05:23:52'),
(33, NULL, '', '', '', '', 60000.00, 0, 'Menunggu Pembayaran', '2025-10-30 05:26:45'),
(34, NULL, '', '', '', '', 60000.00, 0, 'Menunggu Pembayaran', '2025-10-30 05:27:28'),
(35, 3, '', '', '', '', 60000.00, 0, 'Dikirim', '2025-10-30 05:28:04'),
(36, NULL, '', '', '', '', 120000.00, 0, 'Menunggu Pembayaran', '2025-10-30 07:43:00'),
(37, NULL, '', '', '', '', 120000.00, 0, 'Selesai', '2025-10-30 07:45:57'),
(38, 6, '', '', '', '', 60000.00, 0, 'Menunggu Pembayaran', '2025-10-30 12:54:07'),
(39, NULL, '', '', '', '', 60000.00, 0, 'Diproses', '2025-10-31 17:01:09'),
(40, NULL, '', '', '', '', 222.00, 0, 'Menunggu Pembayaran', '2025-11-02 07:32:49'),
(41, NULL, '', '', '', '', 23233.00, 0, 'Diproses', '2025-11-03 13:01:44'),
(42, NULL, '', '', '', '', 111112.00, 0, 'Diproses', '2025-11-09 09:43:20'),
(43, 4, '', '', '', '', 23233.00, 0, 'Menunggu Pembayaran', '2025-11-27 09:39:15'),
(44, 4, '', '', '', '', 23233.00, 0, 'Menunggu Pembayaran', '2025-11-27 09:41:51'),
(45, 4, '', '', '', '', 23233.00, 0, 'Menunggu Pembayaran', '2025-11-27 09:43:52'),
(46, 4, '', '', '', '', 23233.00, 0, 'Menunggu Pembayaran', '2025-11-27 09:48:22'),
(47, 4, '', '', '', '', 111112.00, 0, 'Menunggu Pembayaran', '2025-11-27 09:48:53'),
(48, 4, '', '', '', '', 46466.00, 0, 'Menunggu Pembayaran', '2025-12-03 07:52:04'),
(49, 4, '', '', '', '', 88888.00, 0, 'Menunggu Pembayaran', '2025-12-05 08:52:05'),
(50, 4, '', '', '', '', 99999999.99, 0, 'Menunggu Pembayaran', '2025-12-10 04:40:30'),
(51, 11, '', '', '', '', 80000.00, 0, 'Menunggu Pembayaran', '2025-12-10 08:13:06'),
(52, 11, '', '', '', '', 1000000.00, 0, 'Menunggu Pembayaran', '2025-12-11 05:41:43'),
(53, 11, '', '', '', '', 40000.00, 0, 'Menunggu Pembayaran', '2025-12-11 05:42:52'),
(54, 11, '', '', '', '', 40000.00, 0, 'Menunggu Pembayaran', '2025-12-11 05:43:55');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL,
  `gambar_produk` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `kategori`, `deskripsi`, `harga`, `stok`, `gambar_produk`) VALUES
(11, 'MASTER REM RCB', '', 'Master Rem RCB dirancang untuk memberikan performa pengereman yang lebih responsif dan stabil. Dibuat dengan material berkualitas, sehingga lebih awet, ringan, dan nyaman digunakan. Cocok untuk harian maupun balap, serta kompatibel dengan berbagai jenis motor.', 2000000.00, 100, '693919b056921.jpg'),
(12, 'HANDGRIP RCB', '', 'CB Original Handgrip menawarkan kenyamanan genggaman yang lebih stabil dan anti-selip. Terbuat dari bahan rubber berkualitas yang lembut namun kuat, memberikan kontrol lebih baik saat berkendara. Desain sporty dan elegan cocok untuk berbagai jenis motor.', 100000.00, 100, '69391991979cd.png'),
(13, 'HANDGRIP RCB HG55', '', 'RCB Original Handgrip menawarkan kenyamanan genggaman yang lebih stabil dan anti-selip. Terbuat dari bahan rubber berkualitas yang lembut namun kuat, memberikan kontrol lebih baik saat berkendara. Desain sporty dan elegan cocok untuk berbagai jenis motor.', 97000.00, 100, '69391affd369e.png'),
(14, 'Paket Handle Rem & Handgrip Brembo', '', 'Paket handle rem dan handgrip Brembo ini dirancang untuk meningkatkan kontrol dan kenyamanan saat berkendara. Set ini mencakup handle rem depan, handle kopling, handgrip ergonomis, serta selang rem berkualitas tinggi. Material kuat dan desain sporty memberikan respons pengereman lebih stabil serta tampilan motor yang lebih premium.', 2500000.00, 100, '69391bd701780.png'),
(15, 'Oli MPX 1&2', '', 'Oli mesin MPX dirancang untuk motor matic dengan formulasi yang mampu memberikan perlindungan optimal pada mesin. Memiliki kekentalan stabil, membantu menjaga suhu mesin tetap rendah, mengurangi gesekan, serta meningkatkan efisiensi bahan bakar. Cocok untuk penggunaan harian dengan performa mesin yang lebih halus dan responsif.', 40000.00, 200, '69391ca8883aa.png'),
(16, 'Busi NGK C7HSA', '', 'Busi NGK C7HSA dirancang untuk memberikan pembakaran yang lebih stabil dan optimal pada mesin motor. Menggunakan elektroda berkualitas tinggi untuk memastikan percikan api kuat, mudah starter, serta efisiensi bahan bakar yang lebih baik. Cocok untuk penggunaan harian dan mampu menjaga performa mesin tetap responsif dalam berbagai kondisi.', 40000.00, 100000, '69391d2d92e9b.png'),
(17, 'Disc Brake / Piringan Cakram Brembo', '', 'Piringan cakram Brembo dirancang untuk memberikan performa pengereman maksimal dengan daya cengkeram yang lebih kuat dan stabil. Terbuat dari material baja berkualitas tinggi yang tahan panas, sehingga menjaga performa tetap konsisten saat pengereman keras. Desain wave modern membantu pendinginan lebih cepat dan meningkatkan kontrol saat berkendara.', 150000.00, 100, '69391d8e5230b.png'),
(18, 'Rantai motor TK Racing gold', '', 'Rantai motor gold ini dibuat dari material baja berkualitas dengan lapisan anti-karat yang meningkatkan ketahanan dan umur pakai. Desain presisi memberikan kinerja yang halus, kuat, dan minim gesekan sehingga tenaga mesin tersalurkan lebih optimal. Cocok untuk pemakaian harian maupun jarak jauh, serta memberikan tampilan motor yang lebih premium.', 80000.00, 100, '69391e6d844a7.png'),
(19, 'RKN Piston kin', '', 'Piston motor RKN pilihan ideal untuk menjaga agar mesin tetap prima. Terbuat dari aluminium alloy berkualitas tinggi, piston ini dirancang presisi agar cocok digunakan sebagai pengganti piston standar maupun upgrade performa. Memberikan kompresi optimal, memperlancar pembakaran, serta meningkatkan respons mesin dan daya tahan. Cocok untuk motor harian maupun modifikasi.', 250000.00, 100, '69391fbf93414.png'),
(20, 'BAN Luar IRC', '', 'Ban IRC dirancang dengan compound karet berkualitas tinggi yang memberikan daya cengkeram kuat di berbagai kondisi jalan. Pola tapaknya dibuat presisi untuk meningkatkan stabilitas, kenyamanan, dan keamanan saat berkendara. Tahan lama, tidak mudah aus, serta cocok digunakan untuk motor harian maupun perjalanan jarak jauh.', 500000.00, 500000, '693920a6459b6.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `role` enum('admin','customer','operator','owner') NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  `terakhir_diubah` timestamp NULL DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama_lengkap`, `email`, `no_hp`, `password`, `alamat`, `role`, `image`, `dibuat_pada`, `terakhir_diubah`, `reset_token`) VALUES
(1, 'Administrator', 'admin@ce3s.com', '081234567890', '$2y$10$E.LgQ5/1k.z9.3Xy.uN4P.P7bYgH2yI8bK9rN3mG1fX8uJ6oB5c2', 'Kantor Pusat Ce3s Part', 'admin', NULL, '2025-10-18 02:00:00', NULL, NULL),
(3, 'admin', 'admin@gmail.com', '081215864207', '$2y$10$rs2RN/H8cYPf8TEuHh..Eeh8JhiB5XdAgNUfOrt5KUkrpnBQvm9uS', 'Kantor Admin', 'admin', NULL, '2025-10-18 19:52:46', NULL, NULL),
(4, 'rudi', 'rudi@gmail.com', '081548267462', '$2y$10$Xrqk6u03MvJYBrJYaRrhKurfPXWgSxXmOt9rnWEyjLue2R2NUjefm', 'purworejo', 'customer', NULL, '2025-10-30 12:42:04', NULL, 'dd41219b3345baac577b23dc6f1765b1483217f3d64fd74f0fae72c3e87f918a'),
(5, 'ujang', 'ujang@gmail.com', '081548267465', '$2y$10$uK1XSPbtLw2Y7y8gVRo3/usBfXeS76mvOUbfmtr0ScQVIb7n8ZLnO', 'purworejo', 'customer', NULL, '2025-10-30 12:44:27', NULL, NULL),
(6, 'wahyu', 'wahyu@gmail.com', '081548267467', '$2y$10$UBQUZ3fIBze0MQHmKQLaWerk1RRPeKTcoscfs/XrQ2vLjkcySq8hK', 'purworejo', 'operator', NULL, '2025-10-30 12:53:01', NULL, NULL),
(7, 'Staff Gudang Operator', 'operator@ce3spart.com', '081234567899', '$2y$10$0k81CaQeG.TWm4PdrwGZxeVd3cI8VAViFdhP5pYlSAoQ2wCPuZ5yW', 'Gudang Ce3s Part', 'operator', NULL, '2025-11-28 06:45:11', NULL, NULL),
(9, 'Bapak Owner', 'owner@ce3spart.com', '081200000000', '$2y$10$6dgQzyq3R/PbWcCr2jcugOHLGJTs.LwiosLVEkrTQZr3SVyxTgjoK', 'Kantor Owner', 'owner', NULL, '2025-11-28 07:45:25', NULL, NULL),
(10, 'dika', 'dika@gmail.com', '081548267461', '$2y$10$JecINSYiLRHHTONDzaiwPe6OU.mVs8trFuIXRtAVDGot77htVszpu', 'purworejo', 'customer', NULL, '2025-12-05 08:55:41', NULL, NULL),
(11, 'Iyan', 'riyans@gmail.com', '081215864218', '$2y$10$z5F8Mpe5nUhQyLNQz3UKz.zyIv98/UI2xyHqyV2QFU2oTdQOro/Ta', 'Pekalongan', 'customer', NULL, '2025-12-10 07:37:18', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id_chat`),
  ADD KEY `id_pengirim` (`id_pengirim`),
  ADD KEY `id_penerima` (`id_penerima`);

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `no_hp` (`no_hp`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id_chat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`id_pengirim`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`id_penerima`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
