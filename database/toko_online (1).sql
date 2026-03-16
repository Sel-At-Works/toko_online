-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 16, 2026 at 05:56 AM
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
(307, NULL, 36, 38, 'mass barang nya', 1, 1, '2026-03-08 13:49:29'),
(308, NULL, 38, 36, 'ok', 1, 1, '2026-03-08 13:49:39'),
(309, NULL, 36, 38, 'test', 1, 1, '2026-03-08 13:49:58'),
(310, NULL, 36, 38, 'test mas', 1, 1, '2026-03-08 13:50:06'),
(311, NULL, 38, 36, 'ok', 1, 1, '2026-03-08 13:50:19'),
(312, NULL, 36, 38, 'test', 1, 1, '2026-03-10 13:36:40'),
(313, NULL, 36, 38, 'test cuy', 1, 1, '2026-03-10 20:38:12'),
(314, NULL, 38, 36, 'ok', 1, 0, '2026-03-10 20:38:47');

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
(41, 'Buku Anak', 'kategori_1772723576.webp', '2026-03-05 15:12:56', 36),
(42, 'Fiksi', 'kategori_1773556072.jpg', '2026-03-15 06:27:52', 36),
(43, 'Non Fiksi', 'kategori_1773556083.jpg', '2026-03-15 06:28:03', 36),
(49, 'fiksi', 'kategori_1773560448.jpg', '2026-03-15 07:40:48', 35),
(51, 'agama', 'kategori_1773638764.jpg', '2026-03-16 05:26:04', 36),
(52, 'Agama', 'kategori_1773638822.jpg', '2026-03-16 05:27:02', 35);

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
(19, 65, '0805905857857', '2026-02-23 09:27:58'),
(20, 67, '0823456745612', '2026-03-08 05:53:29'),
(21, 34, '0856473526525', '2026-03-08 06:14:07');

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
(2, 35, 'MANDIRI', '2365366764774', 'uploads/qris/1772949495_gambar qris.jpg', '2026-01-26 17:22:52'),
(3, 36, 'BCA', '6376677836', 'uploads/qris/1772949462_gambar qris.jpg', '2026-01-27 01:07:25'),
(7, 42, 'BCA', '8595898598', 'uploads/qris/1771483146_barcode sn 1.jpg', '2026-02-19 06:38:43'),
(11, 66, 'BRI', '637667657588955', NULL, '2026-03-02 00:40:46'),
(12, 61, 'BCA', '2365366356', 'uploads/qris/1772950292_gambar qris.jpg', '2026-03-08 06:11:10');

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
(42, 36, 43, '25 Nabi & Rasul', 'menceritakan tentang 25 nabi dan rasul', 20000, 55000, 35000, 175, 91, '1771803788_ilustrasi-buku-non-fiksi-untuk-anak-1.jpeg', '2026-02-22 23:43:08', '2026-03-15 07:38:57', 1),
(44, 36, 41, 'Kopi cafe', 'fhjfjhfhfhf', 3000, 5000, 2000, 67, 0, '1771822754_barcode sn 1.jpg', '2026-02-23 04:59:14', '2026-03-15 07:17:00', 0),
(45, 36, 41, 'Kancil Si Nakal', 'menceritakan si kancil', 2000, 5000, 3000, 150, 0, '1771822946_buku non fiksi.jpg', '2026-02-23 05:02:26', '2026-03-15 07:17:18', 0),
(46, 36, 42, 'Kancil Dan Buaya', 'menceritakan tentang si kancil dan buaya\r\n', 20000, 60000, 40000, 200, 94, '1772951071_OIP.webp', '2026-02-23 06:09:26', '2026-03-16 05:34:42', 1),
(47, 35, 42, 'Kancil Si Gemoy', 'menceritakan kancil yang gemoy', 2000, 5000, 3000, 150, 0, '1771827017_Orang_Biasa_Baru.jpg', '2026-02-23 06:10:17', '2026-03-15 07:43:11', 0),
(48, 35, 49, 'Kancil Mencuri Timun', 'menceritakan si kancil nakal yang mencuri timun', 20000, 30000, 10000, 50, 94, '1772951173_kancil mencuri timun.jpg', '2026-02-23 06:17:24', '2026-03-15 08:00:13', 1),
(50, 36, 42, 'Orang-orang biasa', 'punya orang biasa', 2000, 5000, 3000, 150, 0, '1771839013_buku non fiksi.jpg', '2026-02-23 09:30:13', '2026-03-15 07:43:18', 0),
(51, 36, 41, 'Dari Mana Saya Datang', 'buku anak dari mana saya datang ', 20000, 50000, 30000, 150, 83, '1772723714_buku anak produk.webp', '2026-03-05 15:15:14', '2026-03-16 05:38:09', 1),
(52, 35, 49, 'laskar Pelangi', 'Laskar Pelangi adalah kisah tentang semangat belajar, persahabatan, dan perjuangan sepuluh anak dari keluarga sederhana yang tetap berusaha meraih cita-cita meskipun memiliki banyak keterbatasan.', 30000, 55000, 25000, 83, 98, '1772951308_buku laskar pelangi.jpg', '2026-03-08 06:28:28', '2026-03-16 05:35:30', 1),
(54, 35, 52, 'Agama-Agama Dunia', 'menceritakan semua agama yang ada di dunia ', 25000, 40000, 15000, 60, 97, '1773638961_kategori agama.jpg', '2026-03-16 05:29:21', '2026-03-16 05:43:50', 1),
(55, 36, 51, 'Agama Islam Menjawab Pertanyaan Kita ?', 'Buku Agama Islam Menjawab Pertanyaan Kita membahas berbagai pertanyaan yang sering muncul dalam kehidupan sehari-hari tentang ajaran Islam. Dengan bahasa yang mudah dipahami, buku ini menjelaskan dasar-dasar keimanan, ibadah, akhlak, serta berbagai masalah yang sering ditanyakan oleh umat Islam. Buku ini membantu pembaca memahami ajaran Islam secara lebih jelas sehingga dapat diterapkan dalam kehidupan sehari-hari.', 30000, 50000, 20000, 67, 96, '1773639145_agama islam menjawab pertanyaan kita.jpg', '2026-03-16 05:32:25', '2026-03-16 05:48:42', 1);

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
(468, 38, NULL, NULL, '0867267357765', 90000, NULL, 'selesai', '2026-03-15 07:56:46', NULL, 1),
(469, 38, NULL, NULL, '0867267357765', 80000, NULL, 'refund', '2026-03-15 07:58:41', 'Produk rusak', 1),
(470, 38, NULL, NULL, '0867267357765', 30000, NULL, 'selesai', '2026-03-15 08:00:02', NULL, 1),
(471, 38, NULL, NULL, '0867267357765', 115000, NULL, 'selesai', '2026-03-16 05:34:09', NULL, 1),
(472, 38, NULL, NULL, '0867267357765', 90000, NULL, 'dikirim', '2026-03-16 05:37:20', 'Produk rusak', 1),
(473, 38, NULL, NULL, '0867267357765', 90000, NULL, 'selesai', '2026-03-16 05:39:47', NULL, 1),
(474, 38, NULL, NULL, '0867267357765', 40000, NULL, 'selesai', '2026-03-16 05:42:21', NULL, 1),
(475, 38, NULL, NULL, '0867267357765', 90000, NULL, 'selesai', '2026-03-16 05:43:44', NULL, 1),
(476, 38, NULL, NULL, '0867267357765', 50000, NULL, 'selesai', '2026-03-16 05:45:32', NULL, 1),
(477, 38, NULL, NULL, '0867267357765', 50000, NULL, 'selesai', '2026-03-16 05:48:16', NULL, 1);

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
(542, 468, 46, 1, 60000),
(543, 468, 48, 1, 30000),
(544, 469, 48, 1, 30000),
(545, 469, 51, 1, 50000),
(546, 470, 48, 1, 30000),
(547, 471, 46, 1, 60000),
(548, 471, 52, 1, 55000),
(549, 472, 51, 1, 50000),
(550, 472, 54, 1, 40000),
(551, 473, 54, 1, 40000),
(552, 473, 55, 1, 50000),
(553, 474, 54, 1, 40000),
(554, 475, 54, 1, 40000),
(555, 475, 55, 1, 50000),
(556, 476, 55, 1, 50000),
(557, 477, 55, 1, 50000);

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
(492, 468, 36, 'transfer', 60000, 'selesai', 'bukti_468_36_1773561406.jpg', 'setuju', '	12335367619', 'https://www.jne.co.id/en/tracking-package', '2026-03-15 14:57:26', '2026-03-15 14:57:46', 0, 0, NULL, NULL),
(493, 468, 35, 'qris', 30000, 'selesai', 'bukti_468_35_1773561406.png', 'setuju', '12345678910', 'https://www.jne.co.id/en/tracking-package', '2026-03-15 14:57:00', '2026-03-15 14:57:48', 0, 0, NULL, NULL),
(494, 469, 35, 'transfer', 30000, 'refund', 'bukti_469_35_1773561521.jpg', 'ditolak', NULL, NULL, NULL, '2026-03-15 14:59:25', 0, 0, 'Produk rusak', '2026-03-15 14:59:25'),
(495, 469, 36, 'qris', 50000, 'selesai', 'bukti_469_36_1773561521.png', 'setuju', '12335467517', 'https://www.jne.co.id/en/tracking-package', '2026-03-15 14:58:52', '2026-03-15 14:59:32', 0, 0, NULL, NULL),
(496, 470, 35, 'transfer', 30000, 'selesai', 'bukti_470_35_1773561602.png', 'setuju', '11335677910', 'https://www.jne.co.id/en/tracking-package', '2026-03-15 15:00:13', '2026-03-15 15:00:35', 0, 0, NULL, NULL),
(497, 471, 36, 'transfer', 60000, 'selesai', 'bukti_471_36_1773639249.jpg', 'setuju', '12235437617', 'https://www.jne.co.id/en/tracking-package', '2026-03-16 12:34:42', '2026-03-16 12:36:25', 0, 0, NULL, NULL),
(498, 471, 35, 'qris', 55000, 'selesai', 'bukti_471_35_1773639249.png', 'setuju', '12335678950', 'https://www.jne.co.id/en/tracking-package', '2026-03-16 12:35:30', '2026-03-16 12:36:28', 0, 0, NULL, NULL),
(499, 472, 36, 'qris', 50000, 'selesai', 'bukti_472_36_1773639440.jpg', 'setuju', '22335667517	', 'https://www.jne.co.id/en/tracking-package', '2026-03-16 12:38:09', '2026-03-16 12:38:59', 0, 0, NULL, NULL),
(500, 472, 35, 'transfer', 40000, 'refund', 'bukti_472_35_1773639440.png', 'ditolak', NULL, NULL, NULL, '2026-03-16 12:37:28', 0, 0, 'Produk rusak', '2026-03-16 12:37:28'),
(501, 473, 35, 'transfer', 40000, 'selesai', 'bukti_473_35_1773639587.jpg', 'setuju', '	12235668958', 'https://www.jne.co.id/en/tracking-package', '2026-03-16 12:40:59', '2026-03-16 12:41:46', 0, 0, NULL, NULL),
(502, 473, 36, 'qris', 50000, 'selesai', 'bukti_473_36_1773639587.png', 'setuju', '22345666518', 'https://www.jne.co.id/en/tracking-package', '2026-03-16 12:40:00', '2026-03-16 12:41:48', 0, 0, NULL, NULL),
(503, 474, 35, 'transfer', 40000, 'selesai', 'bukti_474_35_1773639741.jpg', 'setuju', '12135658959', 'https://jet.co.id/track', '2026-03-16 12:42:29', '2026-03-16 12:43:14', 0, 0, NULL, NULL),
(504, 475, 35, 'transfer', 40000, 'selesai', 'bukti_475_35_1773639824.jpg', 'setuju', '12137653959', 'https://jet.co.id/track', '2026-03-16 12:43:50', '2026-03-16 12:45:08', 0, 0, NULL, NULL),
(505, 475, 36, 'qris', 50000, 'selesai', 'bukti_475_36_1773639824.png', 'setuju', '22345666719', 'https://www.jne.co.id/en/tracking-package', '2026-03-16 12:44:16', '2026-03-16 12:45:10', 0, 0, NULL, NULL),
(506, 476, 36, 'transfer', 50000, 'selesai', 'bukti_476_36_1773639932.jpg', 'setuju', '	22345666710', 'https://www.jne.co.id/en/tracking-package', '2026-03-16 12:45:39', '2026-03-16 12:46:19', 0, 0, NULL, NULL),
(507, 477, 36, 'qris', 50000, 'selesai', 'bukti_477_36_1773640096.png', 'setuju', '22345666689', 'https://www.jne.co.id/en/tracking-package', '2026-03-16 12:48:42', '2026-03-16 12:49:03', 0, 0, NULL, NULL);

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
(34, 3, '4563673678826647', 'fauzan', 'fauzan@gmail.com', '$2y$10$jUzW4ggpR5cNMqAxOsSWfeTnsZOiTVgrsEMDd61KFKaLx7cZuy1nm', '2026-01-26 09:10:56', 'Kp.Nanas Rt005/001', 'uploads/profile/user_34_1772950447.jpg', NULL, NULL, 'offline', 1),
(35, 2, '3453453434353334', 'dika', 'dika@gmail.com', '$2y$10$OsbhbvhHO9Wb1Y0OQ99MXe3sw8mw4ECEWfAVY8oONKq8ScvDXtC7.', '2026-01-26 09:11:43', 'Kp.Rambutan Rt005/001', 'uploads/profile/user_35_1772949986.jpg', NULL, NULL, 'offline', 1),
(36, 2, '1342425425435534', 'danish', 'danish@gmail.com', '$2y$10$mExDAnIE2pGY.cqIBRKE7eM1GeLw.vrl6hnLUQ3jwqSpWPUNQ9sXS', '2026-01-26 10:36:27', 'Kp.Jembatan Rt 007/00\r\n', 'uploads/profile/user_36_1772950012.jpg', NULL, NULL, 'offline', 1),
(38, 3, '4784787887228738', 'alatas', 'alatas@gmail.com', '$2y$10$jHpUcYEYd8mLffgIP0kMmu2JethQj6ir7uzq00uNjkFmICiy6esBO', '2026-01-26 17:22:17', 'Kp.Tas rt006/001', 'uploads/profile/user_38_1772951688.webp', NULL, NULL, 'offline', 1),
(42, 2, '7587875875878758', 'mario', 'mario@gmail.com', '$2y$10$2rVm.ilpNuqxWcV3eZSUGOhN0MKVtPxu.CTqasCzJnRJCNWTBlt8K', '2026-02-19 06:38:36', 'Kp.pik No.12B', 'uploads/profile/user_42_1772950048.jpg', NULL, NULL, 'offline', 1),
(61, 2, '6904960906904996', 'romlih', 'romlih@gmail.com', '$2y$10$kpGlb/v/sgBra.L2pACsN.Mc8K0TSydoSgt9rB0vmyqBcsuNDJkc.', '2026-02-23 00:41:55', 'kp.romlih', 'uploads/profile/user_61_1772950308.jpg', NULL, NULL, 'offline', 1),
(62, 3, '8988484494044094', 'fahri', 'fahri@gmail.com', '$2y$10$Gh3Jy47X8CEZTxti97Y1hOKGPl/uOA3wGsiTnB7KUs9ZrqTyVqpWS', '2026-02-23 05:13:52', 'kp.atahir', 'uploads/profile/default.png', NULL, NULL, 'offline', 0),
(63, 3, '8988484494035635', 'yoga', 'yoga@gmail.com', '$2y$10$nC8vUY4aJfBvVu5pZ1lbyuwIoJQHzr00etjMZKRRdzM2wPjtm2YQG', '2026-02-23 06:12:58', 'kp.atahir', 'uploads/profile/user_63_1771827200.jpg', NULL, NULL, 'offline', 0),
(64, 3, '9090599588746647', 'riyan', 'riyan@gmail.com', '$2y$10$IHFBEdZfUV3ZKHg/VZ3bKu8x/LlWWsaTckVIfoQ3X4MMj6lklbgTG', '2026-02-23 09:12:34', 'kp.bona rt006/001', 'uploads/profile/user_64_1771837981.jpg', NULL, NULL, 'offline', 0),
(65, 3, '6960069099698698', 'ridho', 'ridho@gmail.com', '$2y$10$81Alkwt/R6zYaTkrMjmTaenv/ApsfTyhdtuYcnL9I/Y6ljc.OJOAq', '2026-02-23 09:27:36', 'kp.bona rt006/001', 'uploads/profile/user_65_1772611904.jpg', NULL, NULL, 'offline', 0),
(66, 2, '5858989839839849', 'memey', 'memey@gmail.com', '$2y$10$XX1Q/UN3Fv1X6HM2A9eqauPxDToLE7FxPdgMRmDYxFFIi7uVUH.te', '2026-03-02 00:40:40', 'kp.pisangan', 'uploads/profile/user_66_1772950341.jpg', NULL, NULL, 'offline', 1),
(67, 3, '6738982374757857', 'Rifqi', 'Rifqi@gmail.com', '$2y$10$Y.wf71UwW7WP6ZuTwEd5ZuljBxW8h1XpoHiYNxQCGxsrPy2ik6JeK', '2026-03-08 05:50:54', 'kp.Dewata Rt 05/011', 'uploads/profile/user_67_1772949224.jpg', NULL, NULL, 'offline', 0);

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
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_id` (`transaksi_id`);

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
  ADD KEY `penjual_id` (`penjual_id`),
  ADD KEY `kategori_id` (`kategori_id`);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=315;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=644;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pembeli_profile`
--
ALTER TABLE `pembeli_profile`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `penjual_profile`
--
ALTER TABLE `penjual_profile`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=478;

--
-- AUTO_INCREMENT for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=558;

--
-- AUTO_INCREMENT for table `transaksi_penjual`
--
ALTER TABLE `transaksi_penjual`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=508;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

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
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `fk_notif_transaksi` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi_penjual` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`penjual_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `produk_ibfk_2` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

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
