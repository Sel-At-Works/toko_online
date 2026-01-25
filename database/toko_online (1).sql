-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 25, 2026 at 05:27 AM
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
-- Database: `toko_online`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `penjual_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `gambar`, `created_at`, `penjual_id`) VALUES
(21, 'Botol', 'kategori_1769050008.webp', '2026-01-22 02:46:48', 29),
(22, 'roni', 'kategori_1769050095.jpg', '2026-01-22 02:48:15', NULL),
(23, 'gacor', 'kategori_1769050715.jpg', '2026-01-22 02:58:35', 29);

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id` int(11) NOT NULL,
  `pembeli_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`id`, `pembeli_id`, `produk_id`, `qty`, `created_at`) VALUES
(34, 28, 29, 1, '2026-01-22 10:25:32'),
(35, 28, 28, 1, '2026-01-22 10:25:35'),
(36, 28, 27, 1, '2026-01-22 10:25:37'),
(37, 28, 26, 1, '2026-01-22 10:25:40'),
(38, 28, 25, 1, '2026-01-22 10:25:43'),
(39, 28, 30, 1, '2026-01-22 10:34:11');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `penjual_id` int(11) NOT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga_modal` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `margin` int(11) NOT NULL DEFAULT 0,
  `margin_persen` int(11) NOT NULL DEFAULT 0,
  `stok` int(11) NOT NULL DEFAULT 0,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `penjual_id`, `kategori_id`, `nama_produk`, `deskripsi`, `harga_modal`, `harga`, `margin`, `margin_persen`, `stok`, `gambar`, `created_at`, `updated_at`) VALUES
(25, 11, 23, 'Pelajaran Agama Islam', 'hhhhhhhhh', 2000, 3000, 1000, 50, 194, '1769054292_paramex.webp', '2026-01-22 03:55:10', '2026-01-24 05:15:40'),
(26, 11, 21, 'Pelajaran Agama Islam1', 'sssssssss', 2000, 3000, 1000, 50, 197, '1769054314_kokain.jpg', '2026-01-22 03:58:34', '2026-01-24 05:15:36'),
(27, 29, 21, 'Pelajaran Agama Islam', '1233', 2000, 3000, 1000, 50, 194, '1769054382_paramex.webp', '2026-01-22 03:59:42', '2026-01-24 05:24:27'),
(28, 11, 21, 'Pelajaran Agama Islam2', 'ssssssssssssssss', 2000, 3000, 1000, 50, 191, '1769074779_paramex.webp', '2026-01-22 09:39:39', '2026-01-24 07:08:39'),
(29, 11, 21, 'Pelajaran Agama Islam3', '33333333333333', 2000, 3000, 1000, 50, 189, '1769074854_paramex.webp', '2026-01-22 09:40:54', '2026-01-24 07:15:30'),
(30, 11, 21, 'Pelajaran Agama Islam4', 'ffffffffffff', 2000, 3000, 1000, 50, 195, '1769074875_barcode sn 1.jpg', '2026-01-22 09:41:15', '2026-01-24 06:36:35');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nama_role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `nama_role`) VALUES
(3, 'pembeli'),
(2, 'penjual'),
(1, 'super_admin');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `pembeli_id` int(11) NOT NULL,
  `bank` varchar(50) DEFAULT NULL,
  `no_rekening` varchar(50) DEFAULT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `total` int(11) NOT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `status` enum('pending','menunggu_verifikasi','selesai','ditolak') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `pembeli_id`, `bank`, `no_rekening`, `no_telepon`, `total`, `bukti_transfer`, `status`, `created_at`) VALUES
(1, 28, 'bni', '1234567890', NULL, 3000, NULL, 'pending', '2026-01-22 10:24:57'),
(2, 27, 'bri', '111111111111111', '2222222222222', 12000, 'bukti_1769231126.jpg', 'menunggu_verifikasi', '2026-01-24 05:05:26'),
(3, 27, 'bni', '1000000000', '0877777777777', 33000, 'bukti_1769232005.webp', 'menunggu_verifikasi', '2026-01-24 05:20:05'),
(4, 27, 'bni', '1111111111', '0000000000000', 12000, 'bukti_1769232295.webp', 'menunggu_verifikasi', '2026-01-24 05:24:55'),
(5, 27, 'bri', '111111111111111', '0000000000000', 6000, 'bukti_1769232563.webp', 'menunggu_verifikasi', '2026-01-24 05:29:23'),
(6, 27, 'bni', '1111111111', '9999999999999', 6000, 'bukti_1769236621.webp', 'menunggu_verifikasi', '2026-01-24 06:37:01'),
(7, 27, 'bni', '1111111111', '0000000000000', 3000, NULL, 'menunggu_verifikasi', '2026-01-24 06:43:50'),
(8, 27, 'bni', '1111111111', '0000000000000', 3000, NULL, 'menunggu_verifikasi', '2026-01-24 07:03:37'),
(9, 27, 'bni', '8977778867', '0812346466747', 6000, NULL, 'menunggu_verifikasi', '2026-01-24 07:09:09'),
(10, 27, 'bni', '8735377363', '0874648494940', 3000, NULL, 'selesai', '2026-01-24 07:16:09');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `id` int(11) NOT NULL,
  `transaksi_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`id`, `transaksi_id`, `produk_id`, `qty`, `harga`) VALUES
(1, 1, 28, 1, 3000),
(2, 2, 29, 1, 3000),
(3, 2, 28, 1, 3000),
(4, 2, 25, 1, 3000),
(5, 2, 26, 1, 3000),
(6, 3, 25, 1, 3000),
(7, 3, 26, 1, 3000),
(8, 3, 27, 2, 3000),
(9, 3, 28, 2, 3000),
(10, 3, 29, 3, 3000),
(11, 3, 30, 2, 3000),
(12, 4, 30, 1, 3000),
(13, 4, 29, 1, 3000),
(14, 4, 28, 1, 3000),
(15, 4, 27, 1, 3000),
(16, 5, 28, 1, 3000),
(17, 5, 29, 1, 3000),
(18, 6, 29, 1, 3000),
(19, 6, 30, 1, 3000),
(20, 7, 29, 1, 3000),
(21, 8, 29, 1, 3000),
(22, 9, 28, 2, 3000),
(23, 10, 29, 1, 3000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `nik` char(16) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `alamat` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expired` datetime DEFAULT NULL,
  `status_login` enum('online','offline') DEFAULT 'offline'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `nik`, `nama`, `email`, `password`, `created_at`, `alamat`, `foto`, `reset_token`, `reset_expired`, `status_login`) VALUES
(11, 2, '1234567891011121', 'Marsel', 'marsel@gmail.com', '$2y$10$qWZnaZFsIRoNB1r9jlt5o.Sjq93/ilL8kNUKoYe24eLwkTXt332Uy', '2026-01-15 04:26:12', 'kp clayaban 45 aja1\r\n\r\n', 'uploads/profile/user_11_1768640954.webp', NULL, NULL, 'online'),
(14, 1, '2345366252617182', 'herlino', 'herlino@gmail.com', '$2y$10$.xT6PODwbqxcoqDvqDv5ruZ3x0uo0uEsxuSg5f4eWtKJpuHr.ykC2', '2026-01-17 10:24:04', 'kampung pik aja ', 'uploads/profile/user_14_1768645465.webp', NULL, NULL, 'online'),
(27, 3, '8998888777664433', 'agoy', 'agoy@gmail.com', '$2y$10$SawlUlXy0vIeipAn9EZ/iOhhPscsJwb2Ns8aWUjGJomGEcpybnQZq', '2026-01-17 18:04:15', 'gdhhdhhduud', 'uploads/pembeli_1768673197.jpg', NULL, NULL, 'online'),
(28, 3, '4564667377883455', 'mantap', 'mantap@gmail.com', '$2y$10$y4/XuAYRG6YI.1q2Zc1a0euJVoPmk.v0yeaG/jpwNZT25gjfC3Peu', '2026-01-17 18:04:56', 'dhhdyeuue88eue', 'uploads/pembeli_1768673186.jpg', NULL, NULL, 'online'),
(29, 2, '1234567899303939', 'Danish', 'danish@gmail.com', '$2y$10$SmoVGwpkKApE9DFg3eJZD.vocvfPOkm2MLMdlyRTOCpjcnod0hMm2', '2026-01-19 04:46:06', 'Kp.Kamjet\r\n', 'uploads/profile/user_1768797966.png', NULL, NULL, 'offline'),
(30, 2, '5455445654654645', 'gaggagaa', 'gaggagagg@gmail.com', '$2y$10$Sg5v02flGrP/aWGE0AlYcOYGXuoE5UhQXuWHF6AfjcM4myNYrmeAS', '2026-01-20 07:13:20', 'gdgdggdgd', 'uploads/profile/user_1768893200.jpg', NULL, NULL, 'offline'),
(31, 2, '6474774872676764', 'ggdgd', 'ggdgd@gmail.com', '$2y$10$tPI6z09NK7AJBBlabam74uerZ9/wbw7Wx/tQy.1BRNTU1D9dj5IZK', '2026-01-20 07:13:51', 'eeeeeee', 'uploads/profile/user_1768893231.webp', NULL, NULL, 'offline');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pembeli_id` (`pembeli_id`,`produk_id`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penjual_id` (`penjual_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_role` (`nama_role`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembeli_id` (`pembeli_id`);

--
-- Indexes for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_id` (`transaksi_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`pembeli_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`penjual_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`pembeli_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD CONSTRAINT `transaksi_detail_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
