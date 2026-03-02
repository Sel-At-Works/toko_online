-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 02, 2026 at 12:20 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

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
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` int NOT NULL,
  `transaksi_id` int DEFAULT NULL,
  `pengirim_id` int NOT NULL,
  `penerima_id` int NOT NULL,
  `pesan` text NOT NULL,
  `dibaca` tinyint(1) NOT NULL DEFAULT '0',
  `dibalas` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`, `transaksi_id`, `pengirim_id`, `penerima_id`, `pesan`, `dibaca`, `dibalas`, `created_at`) VALUES
(274, NULL, 36, 38, 'allo', 1, 1, '2026-02-18 17:40:46'),
(275, NULL, 38, 36, 'okee', 1, 1, '2026-02-18 17:41:44'),
(276, NULL, 38, 36, 'tes', 1, 1, '2026-02-18 23:45:56'),
(277, NULL, 36, 38, 'apa tuh', 1, 1, '2026-02-18 23:46:44'),
(278, NULL, 38, 36, 'ok', 1, 1, '2026-02-18 23:46:56'),
(279, NULL, 36, 38, 'chat', 1, 1, '2026-02-19 13:24:25'),
(280, NULL, 38, 36, 'p', 1, 1, '2026-02-19 13:35:48'),
(281, NULL, 36, 38, 'tesst', 1, 1, '2026-02-20 15:16:37'),
(282, NULL, 38, 36, 'yaaa', 1, 1, '2026-02-20 15:16:41'),
(283, NULL, 38, 36, 'oyy', 1, 1, '2026-02-20 15:16:50'),
(284, NULL, 38, 36, 'oyyy', 1, 1, '2026-02-20 15:17:03'),
(285, NULL, 36, 38, 'otw kirim', 1, 1, '2026-02-20 15:17:54'),
(289, NULL, 35, 38, 'acc', 1, 1, '2026-02-20 15:20:02'),
(290, NULL, 35, 38, 'alamat lu kurang jelas', 1, 1, '2026-02-20 15:20:17'),
(291, NULL, 38, 35, 'ok', 1, 1, '2026-02-20 15:20:59'),
(292, NULL, 38, 36, 'ok', 1, 1, '2026-02-20 15:21:02'),
(293, NULL, 36, 38, 'udah ya', 1, 1, '2026-02-20 23:26:08'),
(294, NULL, 38, 36, 'oke', 1, 0, '2026-02-20 23:26:17'),
(295, NULL, 35, 38, 'p', 1, 1, '2026-02-20 23:28:01'),
(296, NULL, 38, 35, 'dik main yuk', 1, 1, '2026-02-22 17:08:44'),
(297, NULL, 35, 38, 'gass', 1, 1, '2026-02-22 17:08:56'),
(298, NULL, 35, 38, 'mau jam berapa?', 1, 1, '2026-02-22 17:09:18'),
(299, NULL, 35, 38, 'p pesanan lu nih', 1, 1, '2026-02-22 17:09:41'),
(300, NULL, 35, 38, 'pesnan lu', 1, 1, '2026-02-22 17:09:53'),
(301, NULL, 38, 35, 'okee', 1, 1, '2026-02-22 17:10:07'),
(302, NULL, 35, 38, 'down', 1, 0, '2026-02-22 17:10:20'),
(303, NULL, 38, 36, 'test aja', 1, 0, '2026-02-23 11:50:02'),
(304, NULL, 64, 35, 'masih ada barang bang', 1, 0, '2026-02-23 16:14:38'),
(305, NULL, 64, 36, 'barang masih ada ?', 1, 1, '2026-02-23 16:15:14'),
(306, NULL, 36, 64, 'ada', 1, 0, '2026-02-23 16:15:23');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int NOT NULL,
  `nama_kategori` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `penjual_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `gambar`, `created_at`, `penjual_id`) VALUES
(22, 'Non Fiksi', 'kategori_1771803543.jpg', '2026-01-22 02:48:15', NULL),
(25, 'Fiksi', 'kategori_1771803492.jpg', '2026-02-19 06:06:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id` int NOT NULL,
  `pembeli_id` int NOT NULL,
  `produk_id` int NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `transaksi_id` int DEFAULT NULL,
  `tipe` varchar(50) DEFAULT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `pesan` text,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `user_id`, `transaksi_id`, `tipe`, `judul`, `pesan`, `is_read`, `created_at`) VALUES
(1, 36, NULL, 'chat', 'Pesan Baru', 'Anda menerima pesan baru', 0, '2026-02-12 02:24:27'),
(2, 38, NULL, 'chat', 'Pesan Baru', 'Anda menerima pesan baru', 0, '2026-02-12 02:26:51'),
(3, 38, NULL, 'chat', 'Pesan Baru', 'Anda menerima pesan baru', 0, '2026-02-12 02:27:39'),
(4, 36, NULL, 'chat', 'Pesan Baru', 'Anda menerima pesan baru', 0, '2026-02-12 02:27:50');

-- --------------------------------------------------------

--
-- Table structure for table `pembeli_profile`
--

CREATE TABLE `pembeli_profile` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pembeli_profile`
--

INSERT INTO `pembeli_profile` (`id`, `user_id`, `no_telepon`, `created_at`) VALUES
(12, 38, '0867267357765', '2026-01-27 04:22:37'),
(16, 62, '0867267357765', '2026-02-23 05:15:08'),
(17, 63, '0867267357765', '2026-02-23 06:13:20'),
(18, 64, '0867455355355', '2026-02-23 09:13:01'),
(19, 65, '0805905857857', '2026-02-23 09:27:58');

-- --------------------------------------------------------

--
-- Table structure for table `penjual_profile`
--

CREATE TABLE `penjual_profile` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `bank` varchar(50) NOT NULL,
  `no_rekening` varchar(50) NOT NULL,
  `qris` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penjual_profile`
--

INSERT INTO `penjual_profile` (`id`, `user_id`, `bank`, `no_rekening`, `qris`, `created_at`) VALUES
(2, 35, 'MANDIRI', '111111111111111', 'uploads/qris/1769449067_obt keras.jpg', '2026-01-26 17:22:52'),
(3, 36, 'BCA', '6376677836', 'uploads/qris/1769488763_obt keras.jpg', '2026-01-27 01:07:25'),
(7, 42, 'BCA', '8595898598', 'uploads/qris/1771483146_barcode sn 1.jpg', '2026-02-19 06:38:43');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int NOT NULL,
  `penjual_id` int NOT NULL,
  `kategori_id` int DEFAULT NULL,
  `nama_produk` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `harga_modal` int NOT NULL,
  `harga` int NOT NULL,
  `margin` int NOT NULL DEFAULT '0',
  `margin_persen` int NOT NULL DEFAULT '0',
  `stok` int NOT NULL DEFAULT '0',
  `gambar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `penjual_id`, `kategori_id`, `nama_produk`, `deskripsi`, `harga_modal`, `harga`, `margin`, `margin_persen`, `stok`, `gambar`, `created_at`, `updated_at`, `is_active`) VALUES
(42, 36, 22, '25 Nabi & Rasul', 'menceritakan tentang 25 nabi dan rasul', 2000, 5000, 3000, 150, 94, '1771803788_ilustrasi-buku-non-fiksi-untuk-anak-1.jpeg', '2026-02-22 23:43:08', '2026-02-26 03:23:43', 1),
(44, 36, 25, 'Kopi cafe', 'fhjfjhfhfhf', 3000, 5000, 2000, 67, 0, '1771822754_barcode sn 1.jpg', '2026-02-23 04:59:14', '2026-02-23 05:00:37', 0),
(45, 36, 25, 'Kancil Si Nakal', 'menceritakan si kancil', 2000, 5000, 3000, 150, 0, '1771822946_buku non fiksi.jpg', '2026-02-23 05:02:26', '2026-02-23 05:04:31', 0),
(46, 36, 25, 'kancil nakal', 'menceritakan tentang si kancil ', 2000, 5000, 3000, 150, 96, '1771826966_buku non fiksi.jpg', '2026-02-23 06:09:26', '2026-02-26 03:30:15', 1),
(47, 35, 25, 'Kancil Si Gemoy', 'menceritakan kancil yang gemoy', 2000, 5000, 3000, 150, 0, '1771827017_Orang_Biasa_Baru.jpg', '2026-02-23 06:10:17', '2026-02-23 06:15:03', 0),
(48, 35, 25, 'Kancil Menangis', 'menceritakan si kancil yang menangis', 2000, 5000, 3000, 150, 97, '1771827444_buku non fiksi.jpg', '2026-02-23 06:17:24', '2026-02-25 03:05:42', 1),
(50, 36, 25, 'Orang-orang biasa', 'punya orang biasa', 2000, 5000, 3000, 150, 0, '1771839013_buku non fiksi.jpg', '2026-02-23 09:30:13', '2026-02-23 09:31:54', 0);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `nama_role` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
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
  `id` int NOT NULL,
  `pembeli_id` int NOT NULL,
  `bank` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_rekening` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_telepon` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total` int NOT NULL,
  `resi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('menunggu_verifikasi','diproses','dikirim','selesai','refund') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'menunggu_verifikasi',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pesan_refund` text COLLATE utf8mb4_general_ci,
  `notif_dibaca_pembeli` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `pembeli_id`, `bank`, `no_rekening`, `no_telepon`, `total`, `resi`, `status`, `created_at`, `pesan_refund`, `notif_dibaca_pembeli`) VALUES
(411, 65, NULL, NULL, '0805905857857', 5000, NULL, 'selesai', '2026-02-23 09:30:35', NULL, 1),
(412, 38, NULL, NULL, '0867267357765', 10000, NULL, 'refund', '2026-02-23 09:37:11', 'Produk rusak', 1),
(413, 38, NULL, NULL, '0867267357765', 10000, NULL, 'selesai', '2026-02-23 09:41:22', NULL, 1),
(414, 38, NULL, NULL, '0867267357765', 5000, NULL, 'menunggu_verifikasi', '2026-02-25 03:04:42', NULL, 1),
(415, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-25 03:04:50', NULL, 1),
(416, 38, NULL, NULL, '0867267357765', 10000, NULL, 'selesai', '2026-02-26 03:23:17', NULL, 1),
(417, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-26 03:25:47', NULL, 1),
(418, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-26 03:27:20', NULL, 1),
(419, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-26 03:29:56', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `id` int NOT NULL,
  `transaksi_id` int NOT NULL,
  `produk_id` int NOT NULL,
  `qty` int NOT NULL,
  `harga` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`id`, `transaksi_id`, `produk_id`, `qty`, `harga`) VALUES
(476, 411, 50, 1, 5000),
(477, 412, 42, 1, 5000),
(478, 412, 48, 1, 5000),
(479, 413, 42, 1, 5000),
(480, 413, 48, 1, 5000),
(481, 415, 48, 1, 5000),
(482, 416, 42, 1, 5000),
(483, 416, 46, 1, 5000),
(484, 417, 46, 1, 5000),
(485, 418, 46, 1, 5000),
(486, 419, 46, 1, 5000);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_penjual`
--

CREATE TABLE `transaksi_penjual` (
  `id` int NOT NULL,
  `transaksi_id` int DEFAULT NULL,
  `penjual_id` int DEFAULT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `total` int DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `approve` enum('menunggu','setuju','ditolak') DEFAULT 'menunggu',
  `resi` varchar(100) DEFAULT NULL,
  `link_lacak` text,
  `approved_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_hidden` tinyint(1) DEFAULT '0',
  `notif_dibaca_pembeli` tinyint(1) DEFAULT '0',
  `alasan_tolak` varchar(255) DEFAULT NULL,
  `refunded_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi_penjual`
--

INSERT INTO `transaksi_penjual` (`id`, `transaksi_id`, `penjual_id`, `metode_pembayaran`, `total`, `status`, `bukti_transfer`, `approve`, `resi`, `link_lacak`, `approved_at`, `updated_at`, `is_hidden`, `notif_dibaca_pembeli`, `alasan_tolak`, `refunded_at`) VALUES
(428, 411, 36, 'transfer', 5000, 'selesai', 'bukti_411_36_1771839035.jpg', 'setuju', '5895898598588', 'https://www.jne.co.id/en/tracking-package', '2026-02-23 16:30:44', '2026-02-23 16:30:58', 0, 0, NULL, NULL),
(429, 412, 36, 'qris', 5000, 'selesai', 'bukti_412_36_1771839431.jpg', 'setuju', '6896896896', 'https://www.jne.co.id/en/tracking-package', '2026-02-23 16:37:22', '2026-02-23 16:38:47', 0, 0, NULL, NULL),
(430, 412, 35, 'qris', 5000, 'refund', 'bukti_412_35_1771839431.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-23 16:37:57', 0, 0, 'Produk rusak', '2026-02-23 16:37:57'),
(431, 413, 36, 'qris', 5000, 'selesai', 'bukti_413_36_1771839682.jpg', 'setuju', '123456787585', 'https://www.jne.co.id/en/tracking-package', '2026-02-23 16:41:30', '2026-02-23 16:42:04', 0, 0, NULL, NULL),
(432, 413, 35, 'transfer', 5000, 'selesai', 'bukti_413_35_1771839682.jpg', 'setuju', '4564566736736727722', 'https://www.jne.co.id/en/tracking-package', '2026-02-23 16:41:53', '2026-02-23 16:42:05', 0, 0, NULL, NULL),
(433, 415, 35, 'transfer', 5000, 'selesai', 'bukti_415_35_1771988690.jpg', 'setuju', '6898896986', 'https://www.jne.co.id/en/tracking-package', '2026-02-25 10:05:42', '2026-02-25 10:05:53', 0, 0, NULL, NULL),
(434, 416, 36, 'transfer', 10000, 'selesai', 'bukti_416_36_1772076197.jpg', 'setuju', '68968986', 'https://www.jne.co.id/en/tracking-package', '2026-02-26 10:23:43', '2026-02-26 10:24:25', 0, 0, NULL, NULL),
(435, 417, 36, 'transfer', 5000, 'selesai', 'bukti_417_36_1772076347.jpg', 'setuju', '686860948884', 'https://www.jne.co.id/en/tracking-package', '2026-02-26 10:26:04', '2026-02-26 10:26:39', 0, 0, NULL, NULL),
(436, 418, 36, 'qris', 5000, 'selesai', 'bukti_418_36_1772076440.jpg', 'setuju', '58898489484', 'https://jet.co.id/track', '2026-02-26 10:27:30', '2026-02-26 10:27:47', 0, 0, NULL, NULL),
(437, 419, 36, 'qris', 5000, 'selesai', 'bukti_419_36_1772076596.jpg', 'setuju', '78578958958', 'https://jet.co.id/track', '2026-02-26 10:30:15', '2026-02-26 10:30:28', 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `role_id` int NOT NULL,
  `nik` char(16) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `alamat` text COLLATE utf8mb4_general_ci,
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_expired` datetime DEFAULT NULL,
  `status_login` enum('online','offline') COLLATE utf8mb4_general_ci DEFAULT 'offline',
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `nik`, `nama`, `email`, `password`, `created_at`, `alamat`, `foto`, `reset_token`, `reset_expired`, `status_login`, `is_active`) VALUES
(33, 1, '1234567891011123', 'marsel', 'marsel@gmail.com', '$2y$10$nu3M/VQf3Or6LUjXnNN6luVbkeKQH3eJSzTTyjD.B8HmdxyICn9vC', '2026-01-26 09:09:55', 'kp.pisangan Rt 006/011', 'uploads/profile/user_33_1769419418.jpg', NULL, NULL, 'offline', 1),
(34, 3, '4563673678826647', 'fauzan', 'fauzan@gmail.com', '$2y$10$kx8FSrWbt9tKi5UxLuFv5./8wndIbNAtlHz.md9zoy3l9CrpB1tGy', '2026-01-26 09:10:56', 'Kp.Nanas Rt005/001', 'uploads/profile/user_34_1769419793.webp', NULL, NULL, 'offline', 1),
(35, 2, '3453453434353334', 'dika', 'dika@gmail.com', '$2y$10$OsbhbvhHO9Wb1Y0OQ99MXe3sw8mw4ECEWfAVY8oONKq8ScvDXtC7.', '2026-01-26 09:11:43', 'Kp.Rambutan Rt005/001', 'uploads/profile/user_35_1769419720.webp', NULL, NULL, 'offline', 1),
(36, 2, '1342425425435534', 'danish', 'danish@gmail.com', '$2y$10$mExDAnIE2pGY.cqIBRKE7eM1GeLw.vrl6hnLUQ3jwqSpWPUNQ9sXS', '2026-01-26 10:36:27', 'Kp.Jembatan Rt 007/00\r\n', 'uploads/profile/user_36_1769423895.webp', NULL, NULL, 'offline', 1),
(38, 3, '4784787887228738', 'tasaja', 'tas@gmail.com', '$2y$10$jHpUcYEYd8mLffgIP0kMmu2JethQj6ir7uzq00uNjkFmICiy6esBO', '2026-01-26 17:22:17', 'Kp.Tas rt006/001', 'uploads/profile/user_38_1770604018.webp', NULL, NULL, 'offline', 1),
(42, 2, '7587875875878758', 'mario', 'mario@gmail.com', '$2y$10$2rVm.ilpNuqxWcV3eZSUGOhN0MKVtPxu.CTqasCzJnRJCNWTBlt8K', '2026-02-19 06:38:36', 'pkp.pik12', 'uploads/profile/user_42_1771483163.jpg', NULL, NULL, 'offline', 1),
(61, 2, '6904960906904996', 'romlih', 'romlih@gmail.com', '$2y$10$aJDwW7R8W0c8G6e4VpVbs.TmL/ZXkgx68lvd3TKWRBhgNgPxM/sBu', '2026-02-23 00:41:55', 'kp.romlih', 'uploads/profile/user_1771807315.webp', NULL, NULL, 'offline', 1),
(62, 3, '8988484494044094', 'fahri', 'fahri@gmail.com', '$2y$10$Gh3Jy47X8CEZTxti97Y1hOKGPl/uOA3wGsiTnB7KUs9ZrqTyVqpWS', '2026-02-23 05:13:52', 'kp.atahir', 'uploads/profile/default.png', NULL, NULL, 'offline', 0),
(63, 3, '8988484494035635', 'yoga', 'yoga@gmail.com', '$2y$10$nC8vUY4aJfBvVu5pZ1lbyuwIoJQHzr00etjMZKRRdzM2wPjtm2YQG', '2026-02-23 06:12:58', 'kp.atahir', 'uploads/profile/user_63_1771827200.jpg', NULL, NULL, 'offline', 0),
(64, 3, '9090599588746647', 'riyan', 'riyan@gmail.com', '$2y$10$IHFBEdZfUV3ZKHg/VZ3bKu8x/LlWWsaTckVIfoQ3X4MMj6lklbgTG', '2026-02-23 09:12:34', 'kp.bona rt006/001', 'uploads/profile/user_64_1771837981.jpg', NULL, NULL, 'offline', 0),
(65, 3, '6960069099698698', 'ridho', 'ridho@gmail.com', '$2y$10$81Alkwt/R6zYaTkrMjmTaenv/ApsfTyhdtuYcnL9I/Y6ljc.OJOAq', '2026-02-23 09:27:36', 'kp.bona rt006/001', 'uploads/profile/user_65_1771838885.jpg', NULL, NULL, 'offline', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_id` (`transaksi_id`),
  ADD KEY `pengirim_id` (`pengirim_id`),
  ADD KEY `penerima_id` (`penerima_id`);

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
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembeli_profile`
--
ALTER TABLE `pembeli_profile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `penjual_profile`
--
ALTER TABLE `penjual_profile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

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
-- Indexes for table `transaksi_penjual`
--
ALTER TABLE `transaksi_penjual`
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
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=307;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=560;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pembeli_profile`
--
ALTER TABLE `pembeli_profile`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `penjual_profile`
--
ALTER TABLE `penjual_profile`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=420;

--
-- AUTO_INCREMENT for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=487;

--
-- AUTO_INCREMENT for table `transaksi_penjual`
--
ALTER TABLE `transaksi_penjual`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=438;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`),
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`pengirim_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `chat_ibfk_3` FOREIGN KEY (`penerima_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`pembeli_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembeli_profile`
--
ALTER TABLE `pembeli_profile`
  ADD CONSTRAINT `pembeli_profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penjual_profile`
--
ALTER TABLE `penjual_profile`
  ADD CONSTRAINT `penjual_profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `transaksi_penjual`
--
ALTER TABLE `transaksi_penjual`
  ADD CONSTRAINT `transaksi_penjual_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`marsel`@`localhost` EVENT `hapus_chat_lama` ON SCHEDULE EVERY 1 DAY STARTS '2026-01-29 13:13:16' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM chat
  WHERE created_at < NOW() - INTERVAL 7 DAY$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
