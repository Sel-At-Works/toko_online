-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 09, 2026 at 04:25 AM
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
(315, NULL, 36, 38, 'sudah di kirim', 1, 1, '2026-03-30 23:02:50'),
(316, NULL, 38, 36, 'terima kasih', 1, 1, '2026-03-30 23:06:03'),
(317, NULL, 36, 38, 'sama sama', 1, 1, '2026-03-30 23:06:22'),
(318, NULL, 38, 36, 'test', 1, 1, '2026-04-09 08:41:30'),
(319, NULL, 36, 38, 'oke', 1, 1, '2026-04-09 08:41:41'),
(320, NULL, 38, 36, 'test', 1, 1, '2026-04-09 08:41:52'),
(321, NULL, 36, 38, 'ok', 1, 0, '2026-04-09 08:42:06');

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
(53, 'Fiksi', 'kategori_1774832123.jpg', '2026-03-30 00:55:23', NULL),
(54, 'Agama', 'kategori_1774832579.jpg', '2026-03-30 01:02:59', 35),
(56, 'buku anak', 'kategori_1774835556.webp', '2026-03-30 01:52:36', 35),
(61, 'Buku Agama', 'kategori_1775700835.jpg', '2026-04-09 02:13:55', 36),
(62, 'Non Fiksi', 'kategori_1775704325.jpg', '2026-04-09 03:12:05', 35);

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

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`id`, `pembeli_id`, `produk_id`, `qty`, `created_at`) VALUES
(708, 38, 57, 1, '2026-04-09 04:12:09');

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
(2, 35, 'MANDIRI', '2365366764774', 'uploads/qris/1775666102_gambar qris.jpg', '2026-01-26 17:22:52'),
(3, 36, 'BCA', '6376677836', 'uploads/qris/1774831873_gambar qris.jpg', '2026-01-27 01:07:25'),
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
(42, 36, 61, '25 Nabi & Rasul', 'menceritakan tentang 25 nabi dan rasul', 20000, 55000, 35000, 175, 90, '1771803788_ilustrasi-buku-non-fiksi-untuk-anak-1.jpeg', '2026-02-22 23:43:08', '2026-04-09 02:27:37', 1),
(44, 36, 41, 'Kopi cafe', 'fhjfjhfhfhf', 3000, 5000, 2000, 67, 0, '1771822754_barcode sn 1.jpg', '2026-02-23 04:59:14', '2026-03-15 07:17:00', 0),
(45, 36, 41, 'Kancil Si Nakal', 'menceritakan si kancil', 2000, 5000, 3000, 150, 0, '1771822946_buku non fiksi.jpg', '2026-02-23 05:02:26', '2026-03-15 07:17:18', 0),
(46, 36, 41, 'Kancil Dan Buaya', 'menceritakan tentang si kancil dan buaya\r\n', 20000, 60000, 40000, 200, 94, '1774831906_OIP.webp', '2026-02-23 06:09:26', '2026-04-09 02:14:43', 1),
(47, 35, NULL, 'Kancil Si Gemoy', 'menceritakan kancil yang gemoy', 2000, 5000, 3000, 150, 0, '1771827017_Orang_Biasa_Baru.jpg', '2026-02-23 06:10:17', '2026-03-15 07:43:11', 0),
(48, 35, 53, 'Kancil Mencuri Timun', 'menceritakan si kancil nakal yang mencuri timun', 20000, 30000, 10000, 50, 92, '1774832279_OIP.webp', '2026-02-23 06:17:24', '2026-04-08 01:08:30', 1),
(50, 36, NULL, 'Orang-orang biasa', 'punya orang biasa', 2000, 5000, 3000, 150, 0, '1771839013_buku non fiksi.jpg', '2026-02-23 09:30:13', '2026-03-15 07:43:18', 0),
(51, 36, 41, 'Dari Mana Saya Datang', 'buku anak dari mana saya datang ', 20000, 50000, 30000, 150, 73, '1772723714_buku anak produk.webp', '2026-03-05 15:15:14', '2026-04-09 02:27:37', 1),
(52, 35, 53, 'laskar Pelangi', 'Laskar Pelangi adalah kisah tentang semangat belajar, persahabatan, dan perjuangan sepuluh anak dari keluarga sederhana yang tetap berusaha meraih cita-cita meskipun memiliki banyak keterbatasan.', 30000, 55000, 25000, 83, 92, '1774832251_buku laskar pelangi.jpg', '2026-03-08 06:28:28', '2026-04-09 01:50:39', 1),
(54, 35, NULL, 'Agama-Agama Dunia', 'menceritakan semua agama yang ada di dunia ', 25000, 40000, 15000, 60, 0, '1774832241_kategori agama.jpg', '2026-03-16 05:29:21', '2026-03-30 00:59:57', 0),
(55, 36, 61, 'Agama Islam Menjawab Pertanyaan Kita ?', 'Buku Agama Islam Menjawab Pertanyaan Kita membahas berbagai pertanyaan yang sering muncul dalam kehidupan sehari-hari tentang ajaran Islam. Dengan bahasa yang mudah dipahami, buku ini menjelaskan dasar-dasar keimanan, ibadah, akhlak, serta berbagai masalah yang sering ditanyakan oleh umat Islam. Buku ini membantu pembaca memahami ajaran Islam secara lebih jelas sehingga dapat diterapkan dalam kehidupan sehari-hari.', 30000, 50000, 20000, 67, 95, '1774831890_agama islam menjawab pertanyaan kita.jpg', '2026-03-16 05:32:25', '2026-04-09 02:53:19', 1),
(56, 35, 56, '25 Nabi dan Rasul', 'menceritakan tentang sejarah nabi dan rasull serta doa harian yang dapat di gunakan untuk sehari-hari', 30000, 40000, 10000, 33, 75, '1774836590_ilustrasi-buku-non-fiksi-untuk-anak-1.jpeg', '2026-03-30 02:09:50', '2026-04-09 02:53:55', 1),
(57, 36, 41, 'Malin Kundang', 'Menceritakan tentang anak durhaka kepada orangtuanya hanya karna sudah menjadi kaya dan berpura pura tidak kenal dengan orangtuanya.', 30000, 45000, 15000, 50, 100, '1775701145_malin kundang.jpg', '2026-04-09 02:19:05', '2026-04-09 02:19:05', 1);

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
(478, 38, NULL, NULL, '0867267357765', 40000, NULL, 'selesai', '2026-03-30 15:22:53', NULL, 1),
(479, 38, NULL, NULL, '0867267357765', 40000, NULL, 'selesai', '2026-03-30 15:24:16', NULL, 1),
(480, 38, NULL, NULL, '0867267357765', 40000, NULL, 'selesai', '2026-03-30 15:25:27', NULL, 1),
(481, 38, NULL, NULL, '0867267357765', 40000, NULL, 'selesai', '2026-03-30 15:27:09', NULL, 1),
(482, 38, NULL, NULL, '0867267357765', 40000, NULL, 'refund', '2026-03-30 15:51:19', 'Stok habis', 1),
(483, 38, NULL, NULL, '0867267357765', 40000, NULL, 'selesai', '2026-03-30 15:52:36', NULL, 1),
(484, 38, NULL, NULL, '0867267357765', 105000, NULL, 'selesai', '2026-03-30 15:55:13', NULL, 1),
(485, 38, NULL, NULL, '0867267357765', 90000, NULL, 'dikirim', '2026-03-30 15:58:28', 'Stok habis', 1),
(486, 38, NULL, NULL, '0867267357765', 90000, NULL, 'selesai', '2026-03-30 16:00:59', NULL, 1),
(487, 38, NULL, NULL, '0867267357765', 50000, NULL, 'selesai', '2026-03-30 16:04:21', NULL, 1),
(488, 38, NULL, NULL, '0867267357765', 30000, NULL, 'selesai', '2026-04-07 16:29:04', NULL, 1),
(489, 38, NULL, NULL, '0867267357765', 30000, NULL, 'selesai', '2026-04-08 01:07:51', NULL, 1),
(490, 38, NULL, NULL, '0867267357765', 105000, NULL, 'selesai', '2026-04-08 01:09:52', NULL, 1),
(491, 38, NULL, NULL, '0867267357765', 105000, NULL, 'dikirim', '2026-04-08 01:12:06', 'Stok habis', 1),
(492, 38, NULL, NULL, '0867267357765', 90000, NULL, 'selesai', '2026-04-08 01:14:08', NULL, 1),
(493, 38, NULL, NULL, '0867267357765', 40000, NULL, 'selesai', '2026-04-08 01:16:08', NULL, 1),
(494, 38, NULL, NULL, '0867267357765', 40000, NULL, 'selesai', '2026-04-08 01:18:13', NULL, 1),
(495, 38, NULL, NULL, '0867267357765', 40000, NULL, 'selesai', '2026-04-08 09:47:27', NULL, 1),
(496, 38, NULL, NULL, '0867267357765', 80000, NULL, 'selesai', '2026-04-08 10:01:02', NULL, 1),
(497, 38, NULL, NULL, '0867267357765', 40000, NULL, 'selesai', '2026-04-08 10:06:26', NULL, 1),
(498, 38, NULL, NULL, '0867267357765', 40000, NULL, 'selesai', '2026-04-08 10:10:17', NULL, 1),
(499, 38, NULL, NULL, '0867267357765', 40000, NULL, 'selesai', '2026-04-08 10:15:23', NULL, 1),
(500, 38, NULL, NULL, '0867267357765', 40000, NULL, 'selesai', '2026-04-08 10:50:34', NULL, 1),
(501, 38, NULL, NULL, '0867267357765', 40000, NULL, 'selesai', '2026-04-08 10:57:52', NULL, 1),
(502, 38, NULL, NULL, '0867267357765', 40000, NULL, 'refund', '2026-04-08 10:59:11', 'Stok habis', 1),
(503, 38, NULL, NULL, '0867267357765', 40000, NULL, 'selesai', '2026-04-08 11:00:46', NULL, 1),
(504, 38, NULL, NULL, '0867267357765', 105000, NULL, 'refund', '2026-04-08 11:02:53', 'Produk rusak', 1),
(505, 38, NULL, NULL, '0867267357765', 90000, NULL, 'selesai', '2026-04-08 11:04:31', NULL, 1),
(506, 38, NULL, NULL, '0867267357765', 105000, NULL, 'selesai', '2026-04-08 16:13:01', NULL, 1),
(507, 38, NULL, NULL, '0867267357765', 90000, NULL, 'dikirim', '2026-04-08 16:16:35', 'Alamat tidak valid', 1),
(508, 38, NULL, NULL, '0867267357765', 90000, NULL, 'selesai', '2026-04-08 16:20:13', NULL, 1),
(509, 38, NULL, NULL, '0867267357765', 40000, NULL, 'selesai', '2026-04-08 16:22:45', NULL, 1),
(510, 38, NULL, NULL, '0867267357765', 40000, NULL, 'selesai', '2026-04-08 16:26:34', NULL, 1),
(511, 38, NULL, NULL, '0867267357765', 95000, NULL, 'selesai', '2026-04-09 01:50:02', NULL, 1),
(512, 38, NULL, NULL, '0867267357765', 105000, NULL, 'selesai', '2026-04-09 02:27:02', NULL, 1),
(513, 38, NULL, NULL, '0867267357765', 90000, NULL, 'selesai', '2026-04-09 02:53:02', NULL, 1);

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
(558, 478, 56, 1, 40000),
(559, 479, 56, 1, 40000),
(560, 480, 56, 1, 40000),
(561, 481, 56, 1, 40000),
(562, 482, 56, 1, 40000),
(563, 483, 56, 1, 40000),
(564, 484, 51, 1, 50000),
(565, 484, 52, 1, 55000),
(566, 485, 51, 1, 50000),
(567, 485, 56, 1, 40000),
(568, 486, 51, 1, 50000),
(569, 486, 56, 1, 40000),
(570, 487, 51, 1, 50000),
(571, 488, 48, 1, 30000),
(572, 489, 48, 1, 30000),
(573, 490, 51, 1, 50000),
(574, 490, 52, 1, 55000),
(575, 491, 51, 1, 50000),
(576, 491, 52, 1, 55000),
(577, 492, 51, 1, 50000),
(578, 492, 56, 1, 40000),
(579, 493, 56, 1, 40000),
(580, 494, 56, 1, 40000),
(581, 495, 56, 1, 40000),
(582, 496, 56, 2, 40000),
(583, 497, 56, 1, 40000),
(584, 498, 56, 1, 40000),
(585, 499, 56, 1, 40000),
(586, 500, 56, 1, 40000),
(587, 501, 56, 1, 40000),
(588, 502, 56, 1, 40000),
(589, 503, 56, 1, 40000),
(590, 504, 51, 1, 50000),
(591, 504, 52, 1, 55000),
(592, 505, 51, 1, 50000),
(593, 505, 56, 1, 40000),
(594, 506, 51, 1, 50000),
(595, 506, 52, 1, 55000),
(596, 507, 51, 1, 50000),
(597, 507, 56, 1, 40000),
(598, 508, 51, 1, 50000),
(599, 508, 56, 1, 40000),
(600, 509, 56, 1, 40000),
(601, 510, 56, 1, 40000),
(602, 511, 52, 1, 55000),
(603, 511, 56, 1, 40000),
(604, 512, 42, 1, 55000),
(605, 512, 51, 1, 50000),
(606, 513, 55, 1, 50000),
(607, 513, 56, 1, 40000);

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
  `refunded_at` datetime DEFAULT NULL,
  `notif_dibaca_penjual` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi_penjual`
--

INSERT INTO `transaksi_penjual` (`id`, `transaksi_id`, `penjual_id`, `metode_pembayaran`, `total`, `status`, `bukti_transfer`, `approve`, `resi`, `link_lacak`, `approved_at`, `updated_at`, `is_hidden`, `notif_dibaca_pembeli`, `alasan_tolak`, `refunded_at`, `notif_dibaca_penjual`) VALUES
(508, 478, 35, 'transfer', 40000, 'selesai', 'bukti_478_35_1774884173.jpg', 'setuju', '23453665367', 'https://www.jne.co.id/en/tracking-package', '2026-03-30 22:23:21', '2026-03-30 22:23:41', 0, 0, NULL, NULL, 0),
(509, 479, 35, 'qris', 40000, 'selesai', 'bukti_479_35_1774884256.jpg', 'setuju', '23453665779', 'https://jet.co.id/track', '2026-03-30 22:24:29', '2026-03-30 22:24:57', 0, 0, NULL, NULL, 0),
(510, 480, 35, 'transfer', 40000, 'selesai', 'bukti_480_35_1774884327.jpg', 'setuju', '23453665779', 'https://jet.co.id/track', '2026-03-30 22:25:54', '2026-03-30 22:26:46', 0, 0, NULL, NULL, 0),
(511, 481, 35, 'qris', 40000, 'selesai', 'bukti_481_35_1774884429.jpg', 'setuju', '23453665889', 'https://jet.co.id/track', '2026-03-30 22:28:18', '2026-03-30 22:28:58', 0, 0, NULL, NULL, 0),
(512, 482, 35, 'transfer', 40000, 'refund', 'bukti_482_35_1774885879.jpg', 'ditolak', NULL, NULL, NULL, '2026-04-08 17:58:56', 1, 0, 'Stok habis', '2026-03-30 22:51:36', 0),
(513, 483, 35, 'qris', 40000, 'selesai', 'bukti_483_35_1774885956.jpg', 'setuju', '23453665889', 'https://jet.co.id/track', '2026-03-30 22:52:52', '2026-03-30 22:53:18', 0, 0, NULL, NULL, 0),
(514, 484, 36, 'qris', 50000, 'selesai', 'bukti_484_36_1774886113.jpg', 'setuju', '23457665609', 'https://jet.co.id/track', '2026-03-30 22:56:47', '2026-03-30 22:57:52', 0, 0, NULL, NULL, 0),
(515, 484, 35, 'transfer', 55000, 'selesai', 'bukti_484_35_1774886113.png', 'setuju', '23453665810', 'https://jet.co.id/track', '2026-03-30 22:55:50', '2026-03-30 22:57:54', 0, 0, NULL, NULL, 0),
(516, 485, 36, 'qris', 50000, 'refund', 'bukti_485_36_1774886308.jpg', 'ditolak', NULL, NULL, NULL, '2026-03-30 22:58:44', 0, 0, 'Stok habis', '2026-03-30 22:58:44', 0),
(517, 485, 35, 'qris', 40000, 'selesai', 'bukti_485_35_1774886308.png', 'setuju', '24453665779', 'https://jet.co.id/track', '2026-03-30 22:59:21', '2026-03-30 23:00:16', 0, 0, NULL, NULL, 0),
(518, 486, 36, 'transfer', 50000, 'selesai', 'bukti_486_36_1774886459.jpg', 'setuju', '24355666780', 'https://jet.co.id/track', '2026-03-30 23:01:56', '2026-03-30 23:03:29', 0, 0, NULL, NULL, 0),
(519, 486, 35, 'qris', 40000, 'selesai', 'bukti_486_35_1774886459.png', 'setuju', '24457666789', 'https://jet.co.id/track', '2026-03-30 23:01:16', '2026-03-30 23:03:32', 0, 0, NULL, NULL, 0),
(520, 487, 36, 'transfer', 50000, 'selesai', 'bukti_487_36_1774886661.png', 'setuju', '24255665789', 'https://jet.co.id/track', '2026-03-30 23:04:42', '2026-03-30 23:05:24', 0, 0, NULL, NULL, 0),
(521, 488, 35, 'transfer', 30000, 'selesai', 'bukti_488_35_1775579344.jpg', 'setuju', '24457767710', 'https://jet.co.id/track', '2026-04-07 23:29:14', '2026-04-07 23:29:42', 0, 0, NULL, NULL, 0),
(522, 489, 35, 'qris', 30000, 'selesai', 'bukti_489_35_1775610471.jpg', 'setuju', '24457769999', 'https://jet.co.id/track', '2026-04-08 08:08:30', '2026-04-08 08:08:51', 0, 0, NULL, NULL, 0),
(523, 490, 36, 'transfer', 50000, 'selesai', 'bukti_490_36_1775610592.jpg', 'setuju', '24278665789', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 08:10:38', '2026-04-08 08:11:02', 0, 0, NULL, NULL, 0),
(524, 490, 35, 'qris', 55000, 'selesai', 'bukti_490_35_1775610592.png', 'setuju', '00057769969', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 08:10:02', '2026-04-08 08:11:04', 0, 0, NULL, NULL, 0),
(525, 491, 36, 'qris', 50000, 'refund', 'bukti_491_36_1775610726.jpg', 'ditolak', NULL, NULL, NULL, '2026-04-08 08:12:15', 0, 0, 'Stok habis', '2026-04-08 08:12:15', 0),
(526, 491, 35, 'transfer', 55000, 'selesai', 'bukti_491_35_1775610726.png', 'setuju', '00057769900', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 08:12:34', '2026-04-08 08:13:02', 0, 0, NULL, NULL, 0),
(527, 492, 36, 'qris', 50000, 'selesai', 'bukti_492_36_1775610848.jpg', 'setuju', '24278867700', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 08:14:59', '2026-04-08 08:15:47', 0, 0, NULL, NULL, 0),
(528, 492, 35, 'transfer', 40000, 'selesai', 'bukti_492_35_1775610848.png', 'setuju', '	00057769988', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 08:14:31', '2026-04-08 08:15:46', 0, 0, NULL, NULL, 0),
(529, 493, 35, 'transfer', 40000, 'selesai', 'bukti_493_35_1775610968.jpg', 'setuju', '00057769944', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 08:16:26', '2026-04-08 08:17:18', 0, 0, NULL, NULL, 0),
(530, 494, 35, 'transfer', 40000, 'selesai', 'bukti_494_35_1775611093.jpg', 'setuju', '00057769955', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 08:18:20', '2026-04-08 08:18:36', 0, 0, NULL, NULL, 0),
(531, 495, 35, 'qris', 40000, 'selesai', 'bukti_495_35_1775641647.jpg', 'setuju', '11057769922', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 16:47:51', '2026-04-08 16:48:11', 0, 0, NULL, NULL, 0),
(532, 496, 35, 'transfer', 80000, 'selesai', 'bukti_496_35_1775642462.jpg', 'setuju', '11057559933', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 17:01:15', '2026-04-08 17:01:31', 0, 0, NULL, NULL, 0),
(533, 497, 35, 'qris', 40000, 'selesai', 'bukti_497_35_1775642786.jpg', 'setuju', '11057559976', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 17:06:35', '2026-04-08 17:06:51', 0, 0, NULL, NULL, 0),
(534, 498, 35, 'transfer', 40000, 'selesai', 'bukti_498_35_1775643017.jpg', 'setuju', '11057587923', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 17:10:28', '2026-04-08 17:10:46', 0, 0, NULL, NULL, 0),
(535, 499, 35, 'transfer', 40000, 'selesai', 'bukti_499_35_1775643323.jpg', 'setuju', '21357787723', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 17:15:30', '2026-04-08 17:50:04', 0, 0, NULL, NULL, 0),
(536, 500, 35, 'transfer', 40000, 'selesai', 'bukti_500_35_1775645434.jpg', 'setuju', '11057587333', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 17:50:42', '2026-04-08 17:51:11', 0, 0, NULL, NULL, 0),
(537, 501, 35, 'qris', 40000, 'selesai', 'bukti_501_35_1775645872.jpg', 'setuju', '31457587337', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 17:58:06', '2026-04-08 17:58:39', 0, 0, NULL, NULL, 0),
(538, 502, 35, 'transfer', 40000, 'refund', 'bukti_502_35_1775645951.jpg', 'ditolak', NULL, NULL, NULL, '2026-04-08 17:59:22', 0, 0, 'Stok habis', '2026-04-08 17:59:22', 0),
(539, 503, 35, 'qris', 40000, 'selesai', 'bukti_503_35_1775646046.jpg', 'setuju', '31457787340', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 18:00:54', '2026-04-08 18:01:44', 0, 0, NULL, NULL, 0),
(540, 504, 36, 'transfer', 50000, 'refund', 'bukti_504_36_1775646173.jpg', 'ditolak', NULL, NULL, NULL, '2026-04-08 18:03:30', 0, 0, 'Produk rusak', '2026-04-08 18:03:30', 0),
(541, 504, 35, 'qris', 55000, 'selesai', 'bukti_504_35_1775646173.png', 'setuju', '31457587390', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 18:03:01', '2026-04-08 18:03:42', 0, 0, NULL, NULL, 0),
(542, 505, 36, 'qris', 50000, 'selesai', 'bukti_505_36_1775646271.png', 'setuju', '24278867800', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 18:04:45', '2026-04-08 18:06:20', 0, 0, NULL, NULL, 0),
(543, 505, 35, 'qris', 40000, 'selesai', 'bukti_505_35_1775646271.jpg', 'setuju', '2488868900', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 18:05:49', '2026-04-08 18:06:18', 0, 0, NULL, NULL, 0),
(544, 506, 36, 'qris', 50000, 'selesai', 'bukti_506_36_1775664781.jpg', 'setuju', '24278867777', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 23:13:23', '2026-04-08 23:15:21', 0, 0, NULL, NULL, 0),
(545, 506, 35, 'transfer', 55000, 'selesai', 'bukti_506_35_1775664781.png', 'setuju', '	11057588222', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 23:14:10', '2026-04-08 23:15:19', 0, 0, NULL, NULL, 0),
(546, 507, 36, 'transfer', 50000, 'selesai', 'bukti_507_36_1775664995.jpg', 'setuju', '24278867675', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 23:17:59', '2026-04-08 23:18:57', 0, 0, NULL, NULL, 0),
(547, 507, 35, 'qris', 40000, 'refund', 'bukti_507_35_1775664995.png', 'ditolak', NULL, NULL, NULL, '2026-04-08 23:31:47', 1, 0, 'Alamat tidak valid', '2026-04-08 23:17:02', 0),
(548, 508, 36, 'qris', 50000, 'selesai', 'bukti_508_36_1775665213.jpg', 'setuju', '24278867707', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 23:20:31', '2026-04-08 23:21:56', 0, 0, NULL, NULL, 0),
(549, 508, 35, 'transfer', 40000, 'selesai', 'bukti_508_35_1775665213.png', 'setuju', '2158587391', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 23:21:08', '2026-04-08 23:21:58', 0, 0, NULL, NULL, 0),
(550, 509, 35, 'transfer', 40000, 'selesai', 'bukti_509_35_1775665365.jpg', 'setuju', '11007557933', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 23:22:59', '2026-04-08 23:23:52', 0, 0, NULL, NULL, 0),
(551, 510, 35, 'transfer', 40000, 'selesai', 'bukti_510_35_1775665594.jpg', 'setuju', '11057587212', 'https://www.jne.co.id/en/tracking-package', '2026-04-08 23:26:52', '2026-04-08 23:27:29', 0, 0, NULL, NULL, 0),
(552, 511, 35, 'qris', 95000, 'selesai', 'bukti_511_35_1775699402.jpg', 'setuju', '11053577213', 'https://www.jne.co.id/en/tracking-package', '2026-04-09 08:50:39', '2026-04-09 08:51:00', 0, 0, NULL, NULL, 0),
(553, 512, 36, 'transfer', 105000, 'selesai', 'bukti_512_36_1775701622.jpg', 'setuju', '24228767705', 'https://www.jne.co.id/en/tracking-package', '2026-04-09 09:27:37', '2026-04-09 09:28:05', 0, 0, NULL, NULL, 0),
(554, 513, 36, 'qris', 50000, 'selesai', 'bukti_513_36_1775703182.png', 'setuju', '23228767805', 'https://www.jne.co.id/en/tracking-package', '2026-04-09 09:53:19', '2026-04-09 09:54:32', 0, 0, NULL, NULL, 0),
(555, 513, 35, 'transfer', 40000, 'selesai', 'bukti_513_35_1775703182.jpg', 'setuju', '11253777214', 'https://www.jne.co.id/en/tracking-package', '2026-04-09 09:53:55', '2026-04-09 09:54:37', 0, 0, NULL, NULL, 0);

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
(35, 2, '3453453434353334', 'dika', 'dika@gmail.com', '$2y$10$OsbhbvhHO9Wb1Y0OQ99MXe3sw8mw4ECEWfAVY8oONKq8ScvDXtC7.', '2026-01-26 09:11:43', 'Kp.Rambutan Rt005/001', 'uploads/profile/user_35_1775666073.jpg', NULL, NULL, 'offline', 1),
(36, 2, '1342425425435534', 'danish', 'danish@gmail.com', '$2y$10$mExDAnIE2pGY.cqIBRKE7eM1GeLw.vrl6hnLUQ3jwqSpWPUNQ9sXS', '2026-01-26 10:36:27', 'Kp.Jembatan Rt 07/08', 'uploads/profile/user_36_1775641356.jpg', NULL, NULL, 'offline', 1),
(38, 3, '4784787887228738', 'alatas', 'alatas@gmail.com', '$2y$10$jHpUcYEYd8mLffgIP0kMmu2JethQj6ir7uzq00uNjkFmICiy6esBO', '2026-01-26 17:22:17', 'Kp.Tas rt006/001', NULL, '67e767a21e12250016b2a0741cd62d0036cafd3c87d241f7eac170861867e1d7', '2026-04-09 10:47:42', 'offline', 1),
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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=322;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=709;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=514;

--
-- AUTO_INCREMENT for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=608;

--
-- AUTO_INCREMENT for table `transaksi_penjual`
--
ALTER TABLE `transaksi_penjual`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=556;

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
