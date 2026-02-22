-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 22, 2026 at 10:14 AM
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
(302, NULL, 35, 38, 'down', 1, 0, '2026-02-22 17:10:20');

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
(22, 'roni', 'kategori_1769050095.jpg', '2026-01-22 02:48:15', NULL),
(25, 'permen', 'kategori_1771481162.jpg', '2026-02-19 06:06:02', NULL);

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
(12, 38, '0867267357765', '2026-01-27 04:22:37');

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
(4, 39, 'BCA', '6376676635', 'uploads/qris/1770699578_login.png', '2026-02-10 04:59:15'),
(5, 40, 'BCA', '6376676644', 'uploads/qris/1770699741_smkbisa-2-removebg-preview.png', '2026-02-10 05:02:03'),
(6, 41, 'BRI', '785758589558758', 'uploads/qris/1770700002_obt keras.jpg', '2026-02-10 05:06:06'),
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
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `penjual_id`, `kategori_id`, `nama_produk`, `deskripsi`, `harga_modal`, `harga`, `margin`, `margin_persen`, `stok`, `gambar`, `created_at`, `updated_at`) VALUES
(31, 35, 22, 'Kopi Kapal Api', 'Nikmat Di Mulut ', 2000, 3000, 1000, 50, 24, '1769420007_obt keras.jpg', '2026-01-26 09:33:27', '2026-02-22 10:07:25'),
(33, 36, 25, 'Kopi Liong', 'Nikmat Di minum ', 3000, 5000, 2000, 67, 98, '1769654313_membersihkan ont.jpg', '2026-01-29 02:38:33', '2026-02-20 16:32:58'),
(38, 36, 25, 'Kopi cafe', 'fhjjfjhffjhjfhfjfjhhf', 3000, 5000, 2000, 67, 61, '1771599571_barcode sn 2.jpg', '2026-02-20 14:59:31', '2026-02-22 10:07:06');

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
(326, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-20 15:52:20', NULL, 1),
(327, 38, NULL, NULL, '0867267357765', 8000, NULL, 'selesai', '2026-02-20 15:53:22', NULL, 1),
(328, 38, NULL, NULL, '0867267357765', 8000, NULL, 'refund', '2026-02-20 15:55:11', 'Alamat tidak valid', 1),
(329, 38, NULL, NULL, '0867267357765', 8000, NULL, 'refund', '2026-02-20 15:58:22', 'Produk rusak', 1),
(330, 38, NULL, NULL, '0867267357765', 8000, NULL, 'refund', '2026-02-20 16:19:05', 'Produk tidak tersedia', 1),
(331, 38, NULL, NULL, '0867267357765', 8000, NULL, 'refund', '2026-02-20 16:21:46', 'Produk rusak', 1),
(332, 38, NULL, NULL, '0867267357765', 8000, NULL, 'selesai', '2026-02-20 16:23:55', NULL, 1),
(333, 38, NULL, NULL, '0867267357765', 8000, NULL, 'selesai', '2026-02-20 16:26:39', NULL, 1),
(334, 38, NULL, NULL, '0867267357765', 8000, NULL, 'selesai', '2026-02-20 16:29:59', NULL, 1),
(335, 38, NULL, NULL, '0867267357765', 5000, NULL, 'refund', '2026-02-20 16:31:25', 'Produk rusak', 1),
(336, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-20 16:32:00', NULL, 1),
(337, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-20 16:32:53', NULL, 1),
(338, 38, NULL, NULL, '0867267357765', 3000, NULL, 'refund', '2026-02-20 16:35:40', 'Stok habis', 1),
(339, 38, NULL, NULL, '0867267357765', 3000, NULL, 'selesai', '2026-02-20 16:41:56', NULL, 1),
(340, 38, NULL, NULL, '0867267357765', 3000, NULL, 'selesai', '2026-02-20 16:42:43', NULL, 1),
(341, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-21 06:53:03', NULL, 1),
(342, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-21 06:57:37', NULL, 1),
(343, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-21 07:25:41', NULL, 1),
(344, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-21 07:27:34', NULL, 1),
(345, 38, NULL, NULL, '0867267357765', 5000, NULL, 'refund', '2026-02-21 07:29:09', 'Stok habis', 1),
(346, 38, NULL, NULL, '0867267357765', 5000, NULL, 'refund', '2026-02-21 07:31:26', 'Stok habis', 1),
(347, 38, NULL, NULL, '0867267357765', 5000, NULL, 'refund', '2026-02-21 07:34:34', 'Produk rusak', 1),
(348, 38, NULL, NULL, '0867267357765', 5000, NULL, 'refund', '2026-02-21 07:35:38', 'Stok habis', 1),
(349, 38, NULL, NULL, '0867267357765', 5000, NULL, 'refund', '2026-02-21 07:37:57', 'Stok habis', 1),
(350, 38, NULL, NULL, '0867267357765', 5000, NULL, 'refund', '2026-02-21 07:43:12', 'Pembayaran tidak valid', 1),
(351, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-21 07:43:50', NULL, 1),
(352, 38, NULL, NULL, '0867267357765', 5000, NULL, 'refund', '2026-02-21 07:45:51', 'Stok habis', 1),
(353, 38, NULL, NULL, '0867267357765', 5000, NULL, 'menunggu_verifikasi', '2026-02-21 07:48:12', NULL, 1),
(354, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-21 07:48:16', NULL, 1),
(355, 38, NULL, NULL, '0867267357765', 5000, NULL, 'refund', '2026-02-21 07:49:22', 'Stok habis', 1),
(356, 38, NULL, NULL, '0867267357765', 5000, NULL, 'refund', '2026-02-21 07:50:40', 'Stok habis', 1),
(357, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-21 07:52:37', NULL, 1),
(358, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-21 08:00:54', NULL, 1),
(359, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-21 08:17:18', NULL, 1),
(360, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-21 08:19:13', NULL, 1),
(361, 38, NULL, NULL, '0867267357765', 5000, NULL, 'refund', '2026-02-21 08:19:59', 'Stok habis', 1),
(362, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-21 08:20:45', NULL, 1),
(363, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-21 08:21:31', NULL, 1),
(364, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-21 08:23:15', NULL, 1),
(365, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-22 06:26:04', NULL, 1),
(366, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-22 07:04:57', NULL, 1),
(367, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-22 07:10:12', NULL, 1),
(368, 38, NULL, NULL, '0867267357765', 8000, NULL, 'selesai', '2026-02-22 07:51:21', NULL, 1),
(369, 38, NULL, NULL, '0867267357765', 8000, NULL, 'refund', '2026-02-22 08:15:12', 'Stok habis', 1),
(370, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-22 08:23:20', NULL, 1),
(371, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-22 08:32:00', NULL, 1),
(372, 38, NULL, NULL, '0867267357765', 8000, NULL, 'selesai', '2026-02-22 08:33:11', NULL, 1),
(373, 38, NULL, NULL, '0867267357765', 8000, NULL, 'refund', '2026-02-22 08:35:55', 'Stok habis', 1),
(374, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-22 08:37:24', NULL, 1),
(375, 38, NULL, NULL, '0867267357765', 5000, NULL, 'refund', '2026-02-22 08:38:09', 'Produk rusak', 1),
(376, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-22 08:40:19', NULL, 1),
(377, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-22 08:41:19', NULL, 1),
(378, 38, NULL, NULL, '0867267357765', 8000, NULL, 'refund', '2026-02-22 08:44:04', 'Stok habis', 1),
(379, 38, NULL, NULL, '0867267357765', 8000, NULL, 'refund', '2026-02-22 08:44:15', 'Stok habis', 1),
(380, 38, NULL, NULL, '0867267357765', 8000, NULL, 'dikirim', '2026-02-22 08:45:58', 'Stok habis', 1),
(381, 38, NULL, NULL, '0867267357765', 8000, NULL, 'dikirim', '2026-02-22 08:47:31', 'Stok habis', 1),
(382, 38, NULL, NULL, '0867267357765', 3000, NULL, 'selesai', '2026-02-22 08:49:25', NULL, 1),
(383, 38, NULL, NULL, '0867267357765', 8000, NULL, 'refund', '2026-02-22 08:50:51', 'Produk rusak', 1),
(384, 38, NULL, NULL, '0867267357765', 8000, NULL, 'refund', '2026-02-22 08:52:36', 'Stok habis', 1),
(385, 38, NULL, NULL, '0867267357765', 8000, NULL, 'refund', '2026-02-22 08:55:38', 'Produk rusak', 1),
(386, 38, NULL, NULL, '0867267357765', 8000, NULL, 'selesai', '2026-02-22 08:57:19', NULL, 1),
(387, 38, NULL, NULL, '0867267357765', 8000, NULL, 'selesai', '2026-02-22 08:58:45', NULL, 1),
(388, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-22 09:42:28', NULL, 1),
(389, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-22 09:46:19', NULL, 1),
(390, 38, NULL, NULL, '0867267357765', 5000, NULL, 'menunggu_verifikasi', '2026-02-22 10:01:32', NULL, 1),
(391, 38, NULL, NULL, '0867267357765', 5000, NULL, 'menunggu_verifikasi', '2026-02-22 10:01:32', NULL, 1),
(392, 38, NULL, NULL, '0867267357765', 5000, NULL, 'selesai', '2026-02-22 10:05:51', NULL, 1),
(393, 38, NULL, NULL, '0867267357765', 8000, NULL, 'selesai', '2026-02-22 10:06:56', NULL, 1);

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
(367, 326, 38, 1, 5000),
(368, 327, 31, 1, 3000),
(369, 327, 38, 1, 5000),
(370, 328, 31, 1, 3000),
(371, 328, 38, 1, 5000),
(372, 329, 31, 1, 3000),
(373, 329, 38, 1, 5000),
(374, 330, 31, 1, 3000),
(375, 330, 38, 1, 5000),
(376, 331, 31, 1, 3000),
(377, 331, 38, 1, 5000),
(378, 332, 31, 1, 3000),
(379, 332, 38, 1, 5000),
(380, 333, 31, 1, 3000),
(381, 333, 38, 1, 5000),
(382, 334, 31, 1, 3000),
(383, 334, 33, 1, 5000),
(384, 335, 38, 1, 5000),
(385, 336, 38, 1, 5000),
(386, 337, 33, 1, 5000),
(387, 338, 31, 1, 3000),
(388, 339, 31, 1, 3000),
(389, 340, 31, 1, 3000),
(390, 341, 38, 1, 5000),
(391, 342, 38, 1, 5000),
(392, 343, 38, 1, 5000),
(393, 344, 38, 1, 5000),
(394, 345, 38, 1, 5000),
(395, 346, 38, 1, 5000),
(396, 347, 38, 1, 5000),
(397, 348, 38, 1, 5000),
(398, 349, 38, 1, 5000),
(399, 350, 38, 1, 5000),
(400, 351, 38, 1, 5000),
(401, 352, 38, 1, 5000),
(402, 354, 38, 1, 5000),
(403, 355, 38, 1, 5000),
(404, 356, 38, 1, 5000),
(405, 357, 38, 1, 5000),
(406, 358, 38, 1, 5000),
(407, 359, 38, 1, 5000),
(408, 360, 38, 1, 5000),
(409, 361, 38, 1, 5000),
(410, 362, 38, 1, 5000),
(411, 363, 38, 1, 5000),
(412, 364, 38, 1, 5000),
(413, 365, 38, 1, 5000),
(414, 366, 38, 1, 5000),
(415, 367, 38, 1, 5000),
(416, 368, 31, 1, 3000),
(417, 368, 38, 1, 5000),
(418, 369, 31, 1, 3000),
(419, 369, 38, 1, 5000),
(420, 370, 38, 1, 5000),
(421, 371, 38, 1, 5000),
(422, 372, 31, 1, 3000),
(423, 372, 38, 1, 5000),
(424, 373, 31, 1, 3000),
(425, 373, 38, 1, 5000),
(426, 374, 38, 1, 5000),
(427, 375, 38, 1, 5000),
(428, 376, 38, 1, 5000),
(429, 377, 38, 1, 5000),
(430, 379, 31, 1, 3000),
(431, 379, 38, 1, 5000),
(432, 380, 31, 1, 3000),
(433, 380, 38, 1, 5000),
(434, 381, 31, 1, 3000),
(435, 381, 38, 1, 5000),
(436, 382, 31, 1, 3000),
(437, 383, 31, 1, 3000),
(438, 383, 38, 1, 5000),
(439, 384, 31, 1, 3000),
(440, 384, 38, 1, 5000),
(441, 385, 31, 1, 3000),
(442, 385, 38, 1, 5000),
(443, 386, 31, 1, 3000),
(444, 386, 38, 1, 5000),
(445, 387, 31, 1, 3000),
(446, 387, 38, 1, 5000),
(447, 388, 38, 1, 5000),
(448, 389, 38, 1, 5000),
(449, 392, 38, 1, 5000),
(450, 393, 31, 1, 3000),
(451, 393, 38, 1, 5000);

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
(318, 326, 36, 'transfer', 5000, 'selesai', 'bukti_326_36_1771602740.jpg', 'setuju', '6867867867', 'https://www.jne.co.id/en/tracking-package', '2026-02-20 22:52:27', '2026-02-20 22:52:50', 0, 0, NULL, NULL),
(319, 327, 35, 'transfer', 3000, 'selesai', 'bukti_327_35_1771602802.jpg', 'setuju', '57577565675', 'https://www.jne.co.id/en/tracking-package', '2026-02-20 22:54:00', '2026-02-20 22:54:24', 0, 0, NULL, NULL),
(320, 327, 36, 'qris', 5000, 'selesai', 'bukti_327_36_1771602802.jpg', 'setuju', '7577757758', 'https://jet.co.id/track', '2026-02-20 22:53:28', '2026-02-20 22:54:27', 0, 0, NULL, NULL),
(321, 328, 35, 'transfer', 3000, 'selesai', 'bukti_328_35_1771602911.jpg', 'setuju', '75857875875', 'https://www.jne.co.id/en/tracking-package', '2026-02-20 22:55:43', '2026-02-20 22:57:14', 0, 0, NULL, NULL),
(322, 328, 36, 'qris', 5000, 'refund', 'bukti_328_36_1771602911.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-20 22:57:34', 1, 0, 'Alamat tidak valid', '2026-02-20 22:56:24'),
(323, 329, 35, 'transfer', 3000, 'refund', 'bukti_329_35_1771603102.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-20 23:20:59', 1, 0, 'Produk rusak', '2026-02-20 22:58:52'),
(324, 329, 36, 'qris', 5000, 'refund', 'bukti_329_36_1771603102.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-20 23:21:19', 1, 0, 'Stok habis', '2026-02-20 22:58:30'),
(325, 330, 35, 'transfer', 3000, 'refund', 'bukti_330_35_1771604345.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-20 23:20:57', 1, 0, 'Produk tidak tersedia', '2026-02-20 23:19:31'),
(326, 330, 36, 'qris', 5000, 'refund', 'bukti_330_36_1771604345.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-20 23:21:21', 1, 0, 'Stok habis', '2026-02-20 23:19:12'),
(327, 331, 35, 'transfer', 3000, 'refund', 'bukti_331_35_1771604506.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-20 23:43:34', 1, 0, 'Produk rusak', '2026-02-20 23:23:57'),
(328, 331, 36, 'qris', 5000, 'selesai', 'bukti_331_36_1771604506.jpg', 'setuju', '686986986', 'https://www.jne.co.id/en/tracking-package', '2026-02-20 23:21:51', '2026-02-20 23:22:42', 0, 0, NULL, NULL),
(329, 332, 35, 'transfer', 3000, 'selesai', 'bukti_332_35_1771604635.jpg', 'setuju', '869896896', 'https://www.jne.co.id/en/tracking-package', '2026-02-20 23:23:58', '2026-02-20 23:25:36', 0, 0, NULL, NULL),
(330, 332, 36, 'qris', 5000, 'selesai', 'bukti_332_36_1771604635.jpg', 'setuju', '6786787686', 'https://jet.co.id/track', '2026-02-20 23:24:28', '2026-02-20 23:25:39', 0, 0, NULL, NULL),
(331, 333, 35, 'transfer', 3000, 'selesai', 'bukti_333_35_1771604799.jpg', 'setuju', '75588783367637', 'https://www.jne.co.id/en/tracking-package', '2026-02-20 23:27:35', '2026-02-20 23:28:30', 0, 0, NULL, NULL),
(332, 333, 36, 'qris', 5000, 'selesai', 'bukti_333_36_1771604799.jpg', 'setuju', '755887837782', 'https://jet.co.id/track', '2026-02-20 23:26:51', '2026-02-20 23:28:37', 0, 0, NULL, NULL),
(333, 334, 35, 'transfer', 3000, 'selesai', 'bukti_334_35_1771604999.jpg', 'setuju', '55775873873', 'https://www.jne.co.id/en/tracking-package', '2026-02-20 23:30:07', '2026-02-20 23:30:52', 0, 0, NULL, NULL),
(334, 334, 36, 'qris', 5000, 'selesai', 'bukti_334_36_1771604999.jpg', 'setuju', '57873728783', 'https://www.jne.co.id/en/tracking-package', '2026-02-20 23:30:34', '2026-02-20 23:30:55', 0, 0, NULL, NULL),
(335, 335, 36, 'transfer', 5000, 'refund', 'bukti_335_36_1771605085.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-20 23:32:02', 0, 0, 'Produk rusak', '2026-02-20 23:32:02'),
(336, 336, 36, 'transfer', 5000, 'selesai', 'bukti_336_36_1771605120.jpg', 'setuju', '5982089390', 'https://www.jne.co.id/en/tracking-package', '2026-02-20 23:32:03', '2026-02-20 23:32:30', 0, 0, NULL, NULL),
(337, 337, 36, 'transfer', 5000, 'selesai', 'bukti_337_36_1771605173.jpg', 'setuju', '5783784406960', 'https://www.jne.co.id/en/tracking-package', '2026-02-20 23:32:58', '2026-02-20 23:33:28', 0, 0, NULL, NULL),
(338, 338, 35, 'transfer', 3000, 'refund', 'bukti_338_35_1771605340.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-20 23:41:59', 0, 0, 'Stok habis', '2026-02-20 23:41:59'),
(339, 339, 35, 'transfer', 3000, 'selesai', 'bukti_339_35_1771605716.jpg', 'setuju', '48948948904', 'https://www.jne.co.id/en/tracking-package', '2026-02-20 23:42:02', '2026-02-20 23:42:18', 0, 0, NULL, NULL),
(340, 340, 35, 'transfer', 3000, 'selesai', 'bukti_340_35_1771605763.jpg', 'setuju', '598585859805', 'https://www.jne.co.id/en/tracking-package', '2026-02-20 23:42:49', '2026-02-20 23:43:19', 0, 0, NULL, NULL),
(341, 341, 36, 'transfer', 5000, 'selesai', 'bukti_341_36_1771656783.jpg', 'setuju', '609490970997', 'https://www.jne.co.id/en/tracking-package', '2026-02-21 13:53:11', '2026-02-21 13:53:36', 0, 0, NULL, NULL),
(342, 342, 36, 'transfer', 5000, 'selesai', 'bukti_342_36_1771657057.jpg', 'setuju', '6096490969', 'https://www.jne.co.id/en/tracking-package', '2026-02-21 13:57:48', '2026-02-21 13:58:00', 0, 0, NULL, NULL),
(343, 343, 36, 'qris', 5000, 'selesai', 'bukti_343_36_1771658741.jpg', 'setuju', '68096909604', 'https://www.jne.co.id/en/tracking-package', '2026-02-21 14:25:48', '2026-02-21 14:26:03', 0, 0, NULL, NULL),
(344, 344, 36, 'transfer', 5000, 'selesai', 'bukti_344_36_1771658854.jpg', 'setuju', '7909050970', 'https://www.jne.co.id/en/tracking-package', '2026-02-21 14:28:03', '2026-02-21 14:28:46', 0, 0, NULL, NULL),
(345, 345, 36, 'transfer', 5000, 'refund', 'bukti_345_36_1771658949.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-21 14:30:23', 1, 0, 'Stok habis', '2026-02-21 14:29:20'),
(346, 346, 36, 'transfer', 5000, 'refund', 'bukti_346_36_1771659086.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-21 14:31:35', 0, 0, 'Stok habis', '2026-02-21 14:31:35'),
(347, 347, 36, 'transfer', 5000, 'refund', 'bukti_347_36_1771659274.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-21 14:34:46', 0, 0, 'Produk rusak', '2026-02-21 14:34:46'),
(348, 348, 36, 'transfer', 5000, 'refund', 'bukti_348_36_1771659338.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-21 14:36:26', 0, 0, 'Stok habis', '2026-02-21 14:36:26'),
(349, 349, 36, 'transfer', 5000, 'refund', 'bukti_349_36_1771659477.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-21 14:38:08', 0, 0, 'Stok habis', '2026-02-21 14:38:08'),
(350, 350, 36, 'transfer', 5000, 'refund', 'bukti_350_36_1771659792.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-21 14:43:21', 0, 0, 'Pembayaran tidak valid', '2026-02-21 14:43:21'),
(351, 351, 36, 'transfer', 5000, 'selesai', 'bukti_351_36_1771659830.jpg', 'setuju', '6904790965087', 'https://www.jne.co.id/en/tracking-package', '2026-02-21 14:43:57', '2026-02-21 14:44:51', 0, 0, NULL, NULL),
(352, 352, 36, 'transfer', 5000, 'refund', 'bukti_352_36_1771659951.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-21 14:48:19', 0, 0, 'Stok habis', '2026-02-21 14:48:19'),
(353, 354, 36, 'transfer', 5000, 'selesai', 'bukti_354_36_1771660096.jpg', 'setuju', '6069640960904', 'https://www.jne.co.id/en/tracking-package', '2026-02-21 14:48:33', '2026-02-21 14:48:58', 0, 0, NULL, NULL),
(354, 355, 36, 'transfer', 5000, 'refund', 'bukti_355_36_1771660162.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-21 14:50:41', 0, 0, 'Stok habis', '2026-02-21 14:50:41'),
(355, 356, 36, 'transfer', 5000, 'refund', 'bukti_356_36_1771660240.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-21 14:51:50', 0, 0, 'Stok habis', '2026-02-21 14:51:50'),
(356, 357, 36, 'transfer', 5000, 'selesai', 'bukti_357_36_1771660357.jpg', 'setuju', '689846094889', 'https://jet.co.id/track', '2026-02-21 14:52:46', '2026-02-21 14:53:18', 0, 0, NULL, NULL),
(357, 358, 36, 'transfer', 5000, 'selesai', 'bukti_358_36_1771660854.jpg', 'setuju', '68960488694', 'https://jet.co.id/track', '2026-02-21 15:02:46', '2026-02-21 15:03:00', 0, 0, NULL, NULL),
(358, 359, 36, 'transfer', 5000, 'selesai', 'bukti_359_36_1771661838.jpg', 'setuju', '686908609', 'https://jet.co.id/track', '2026-02-21 15:17:28', '2026-02-21 15:17:47', 0, 0, NULL, NULL),
(359, 360, 36, 'transfer', 5000, 'selesai', 'bukti_360_36_1771661953.jpg', 'setuju', '68696069068', 'https://www.jne.co.id/en/tracking-package', '2026-02-21 15:19:22', '2026-02-21 15:19:41', 0, 0, NULL, NULL),
(360, 361, 36, 'transfer', 5000, 'refund', 'bukti_361_36_1771661999.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-21 15:20:48', 0, 0, 'Stok habis', '2026-02-21 15:20:48'),
(361, 362, 36, 'transfer', 5000, 'selesai', 'bukti_362_36_1771662045.jpg', 'setuju', '68906868', 'https://www.jne.co.id/en/tracking-package', '2026-02-21 15:20:50', '2026-02-21 15:21:01', 0, 0, NULL, NULL),
(362, 363, 36, 'transfer', 5000, 'selesai', 'bukti_363_36_1771662091.jpg', 'setuju', '68960896', 'https://jet.co.id/track', '2026-02-21 15:21:35', '2026-02-21 15:21:53', 0, 0, NULL, NULL),
(363, 364, 36, 'transfer', 5000, 'selesai', 'bukti_364_36_1771662195.jpg', 'setuju', '689608696', 'https://jet.co.id/track', '2026-02-21 15:23:18', '2026-02-21 15:23:31', 0, 0, NULL, NULL),
(364, 365, 36, 'transfer', 5000, 'selesai', 'bukti_365_36_1771741564.jpg', 'setuju', '5859895509555', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 13:27:20', '2026-02-22 13:27:43', 0, 0, NULL, NULL),
(365, 366, 36, 'transfer', 5000, 'selesai', 'bukti_366_36_1771743897.jpg', 'setuju', '5895058985855', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 14:05:03', '2026-02-22 14:09:52', 0, 0, NULL, NULL),
(366, 367, 36, 'transfer', 5000, 'selesai', 'bukti_367_36_1771744212.jpg', 'setuju', '689680860986', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 14:10:58', '2026-02-22 14:11:16', 0, 0, NULL, NULL),
(367, 368, 35, 'transfer', 3000, 'selesai', 'bukti_368_35_1771746681.jpg', 'setuju', '590590059059', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 14:53:05', '2026-02-22 14:54:30', 0, 0, NULL, NULL),
(368, 368, 36, 'qris', 5000, 'selesai', 'bukti_368_36_1771746681.jpg', 'setuju', '696806869886', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 14:51:45', '2026-02-22 14:54:33', 0, 0, NULL, NULL),
(369, 369, 35, 'qris', 3000, 'selesai', 'bukti_369_35_1771748112.jpg', 'setuju', '69069069-96', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 15:15:26', '2026-02-22 15:22:44', 0, 0, NULL, NULL),
(370, 369, 36, 'transfer', 5000, 'refund', 'bukti_369_36_1771748112.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-22 15:23:22', 0, 0, 'Stok habis', '2026-02-22 15:23:22'),
(371, 370, 36, 'transfer', 5000, 'selesai', 'bukti_370_36_1771748600.jpg', 'setuju', '12345678', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 15:23:42', '2026-02-22 15:24:13', 0, 0, NULL, NULL),
(372, 371, 36, 'transfer', 5000, 'selesai', 'bukti_371_36_1771749120.jpg', 'setuju', '12345678', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 15:32:12', '2026-02-22 15:32:29', 0, 0, NULL, NULL),
(373, 372, 35, 'qris', 3000, 'selesai', 'bukti_372_35_1771749191.jpg', 'setuju', '589589085', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 15:34:01', '2026-02-22 15:35:10', 0, 0, NULL, NULL),
(374, 372, 36, 'qris', 5000, 'selesai', 'bukti_372_36_1771749191.jpg', 'setuju', '12345678', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 15:33:30', '2026-02-22 15:35:12', 0, 0, NULL, NULL),
(375, 373, 35, 'qris', 3000, 'selesai', 'bukti_373_35_1771749355.jpg', 'setuju', '12345678', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 15:36:04', '2026-02-22 15:37:02', 0, 0, NULL, NULL),
(376, 373, 36, 'qris', 5000, 'refund', 'bukti_373_36_1771749355.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-22 15:36:26', 0, 0, 'Stok habis', '2026-02-22 15:36:26'),
(377, 374, 36, 'transfer', 5000, 'selesai', 'bukti_374_36_1771749444.jpg', 'setuju', '4564566736736727722', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 15:37:32', '2026-02-22 15:37:49', 0, 0, NULL, NULL),
(378, 375, 36, 'transfer', 5000, 'refund', 'bukti_375_36_1771749489.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-22 15:38:20', 0, 0, 'Produk rusak', '2026-02-22 15:38:20'),
(379, 376, 36, 'transfer', 5000, 'selesai', 'bukti_376_36_1771749619.jpg', 'setuju', '12345678910', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 15:40:27', '2026-02-22 15:40:59', 0, 0, NULL, NULL),
(380, 377, 36, 'transfer', 5000, 'selesai', 'bukti_377_36_1771749679.jpg', 'setuju', '12345678910', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 15:41:27', '2026-02-22 15:41:45', 0, 0, NULL, NULL),
(381, 378, 35, 'transfer', 3000, 'refund', 'bukti_378_35_1771749844.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-22 15:44:54', 0, 0, 'Stok habis', '2026-02-22 15:44:54'),
(382, 379, 35, 'transfer', 3000, 'refund', 'bukti_379_35_1771749855.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-22 15:46:04', 0, 0, 'Stok habis', '2026-02-22 15:46:04'),
(383, 379, 36, 'qris', 5000, 'refund', 'bukti_379_36_1771749855.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-22 15:44:32', 0, 0, 'Produk rusak', '2026-02-22 15:44:32'),
(384, 380, 35, 'transfer', 3000, 'refund', 'bukti_380_35_1771749958.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-22 15:46:11', 0, 0, 'Stok habis', '2026-02-22 15:46:11'),
(385, 380, 36, 'qris', 5000, 'selesai', 'bukti_380_36_1771749958.jpg', 'setuju', '12345678910', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 15:46:27', '2026-02-22 15:46:45', 0, 0, NULL, NULL),
(386, 381, 35, 'transfer', 3000, 'selesai', 'bukti_381_35_1771750051.jpg', 'setuju', '12345678', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 15:48:03', '2026-02-22 15:48:55', 0, 0, NULL, NULL),
(387, 381, 36, 'qris', 5000, 'refund', 'bukti_381_36_1771750051.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-22 15:47:47', 0, 0, 'Stok habis', '2026-02-22 15:47:47'),
(388, 382, 35, 'transfer', 3000, 'selesai', 'bukti_382_35_1771750165.jpg', 'setuju', '4564566736736727722', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 15:49:35', '2026-02-22 15:50:08', 0, 0, NULL, NULL),
(389, 383, 35, 'transfer', 3000, 'selesai', 'bukti_383_35_1771750251.jpg', 'setuju', '12345678', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 15:51:19', '2026-02-22 15:52:03', 0, 0, NULL, NULL),
(390, 383, 36, 'qris', 5000, 'refund', 'bukti_383_36_1771750251.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-22 15:52:49', 0, 0, 'Produk rusak', '2026-02-22 15:52:49'),
(391, 384, 35, 'transfer', 3000, 'refund', 'bukti_384_35_1771750356.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-22 15:54:05', 0, 0, 'Stok habis', '2026-02-22 15:54:05'),
(392, 384, 36, 'qris', 5000, 'selesai', 'bukti_384_36_1771750356.jpg', 'setuju', '4564566736736727722', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 15:53:25', '2026-02-22 15:54:16', 0, 0, NULL, NULL),
(393, 385, 35, 'transfer', 3000, 'refund', 'bukti_385_35_1771750538.jpg', 'ditolak', NULL, NULL, NULL, '2026-02-22 15:57:28', 0, 0, 'Produk rusak', '2026-02-22 15:57:28'),
(394, 385, 36, 'qris', 5000, 'selesai', 'bukti_385_36_1771750538.jpg', 'setuju', '12345678910', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 15:55:50', '2026-02-22 15:56:38', 0, 0, NULL, NULL),
(395, 386, 35, 'transfer', 3000, 'selesai', 'bukti_386_35_1771750639.jpg', 'setuju', '12345678910', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 15:57:30', '2026-02-22 15:58:18', 0, 0, NULL, NULL),
(396, 386, 36, 'qris', 5000, 'selesai', 'bukti_386_36_1771750639.jpg', 'setuju', '12345678910', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 15:57:52', '2026-02-22 15:58:17', 0, 0, NULL, NULL),
(397, 387, 35, 'transfer', 3000, 'selesai', 'bukti_387_35_1771750725.jpg', 'setuju', '12345678910', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 15:59:28', '2026-02-22 15:59:47', 0, 0, NULL, NULL),
(398, 387, 36, 'qris', 5000, 'selesai', 'bukti_387_36_1771750725.jpg', 'setuju', '12345678910', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 15:58:55', '2026-02-22 15:59:46', 0, 0, NULL, NULL),
(399, 388, 36, 'transfer', 5000, 'selesai', 'bukti_388_36_1771753348.jpg', 'setuju', '689680860986', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 16:42:40', '2026-02-22 16:42:50', 0, 0, NULL, NULL),
(400, 389, 36, 'transfer', 5000, 'selesai', 'bukti_389_36_1771753579.jpg', 'setuju', '689680860986', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 16:46:30', '2026-02-22 16:46:38', 0, 0, NULL, NULL),
(401, 392, 36, 'transfer', 5000, 'selesai', 'bukti_392_36_1771754751.jpg', 'setuju', '12345678910', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 17:06:06', '2026-02-22 17:06:15', 0, 0, NULL, NULL),
(402, 393, 35, 'transfer', 3000, 'selesai', 'bukti_393_35_1771754816.jpg', 'setuju', '12345678', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 17:07:25', '2026-02-22 17:07:37', 0, 0, NULL, NULL),
(403, 393, 36, 'transfer', 5000, 'selesai', 'bukti_393_36_1771754816.jpg', 'setuju', '12345678', 'https://www.jne.co.id/en/tracking-package', '2026-02-22 17:07:06', '2026-02-22 17:07:39', 0, 0, NULL, NULL);

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
  `status_login` enum('online','offline') COLLATE utf8mb4_general_ci DEFAULT 'offline'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `nik`, `nama`, `email`, `password`, `created_at`, `alamat`, `foto`, `reset_token`, `reset_expired`, `status_login`) VALUES
(33, 1, '1234567891011123', 'marsel', 'marsel@gmail.com', '$2y$10$nu3M/VQf3Or6LUjXnNN6luVbkeKQH3eJSzTTyjD.B8HmdxyICn9vC', '2026-01-26 09:09:55', 'kp.pisangan Rt 006/011', 'uploads/profile/user_33_1769419418.jpg', NULL, NULL, 'offline'),
(34, 3, '4563673678826647', 'fauzan', 'fauzan@gmail.com', '$2y$10$kx8FSrWbt9tKi5UxLuFv5./8wndIbNAtlHz.md9zoy3l9CrpB1tGy', '2026-01-26 09:10:56', 'Kp.Nanas Rt005/001', 'uploads/profile/user_34_1769419793.webp', NULL, NULL, 'offline'),
(35, 2, '3453453434353334', 'dika', 'dika@gmail.com', '$2y$10$OsbhbvhHO9Wb1Y0OQ99MXe3sw8mw4ECEWfAVY8oONKq8ScvDXtC7.', '2026-01-26 09:11:43', 'Kp.Rambutan Rt005/001', 'uploads/profile/user_35_1769419720.webp', NULL, NULL, 'offline'),
(36, 2, '1342425425435534', 'danish', 'danish@gmail.com', '$2y$10$mExDAnIE2pGY.cqIBRKE7eM1GeLw.vrl6hnLUQ3jwqSpWPUNQ9sXS', '2026-01-26 10:36:27', 'Kp.Jembatan Rt 007/00\r\n', 'uploads/profile/user_36_1769423895.webp', NULL, NULL, 'offline'),
(38, 3, '4784787887228738', 'tasaja', 'tas@gmail.com', '$2y$10$jHpUcYEYd8mLffgIP0kMmu2JethQj6ir7uzq00uNjkFmICiy6esBO', '2026-01-26 17:22:17', 'Kp.Tas rt006/001', 'uploads/profile/user_38_1770604018.webp', NULL, NULL, 'offline'),
(39, 2, '6738982374744444', 'yanto', 'yanto@gmail.com', '$2y$10$nEm4oBSdfaXzKatDB02IuOL.6xEgMJApPBiBFEzwYqDtoG7AS/N4u', '2026-02-10 04:23:21', 'kp.Pisangan Rt 05/011', 'uploads/profile/user_39_1770697439.jpg', NULL, NULL, 'offline'),
(40, 2, '7585785785857875', 'ronai', 'ronai@gmail.com', '$2y$10$mov.RSJYs.MT5481XfEqJuRSbTuMDSxBA7tMCueMYvB7QF6HfSzkG', '2026-02-10 05:01:48', 'kp.ronai rt009/001', 'uploads/profile/user_40_1770699759.png', NULL, NULL, 'offline'),
(41, 2, '1234567891011121', 'roni', 'roni@gmail.com', '$2y$10$FnPMY6gwHDCpz6/BTMwGt.eTnyB/E7VIneHIHvESeFMG1fBy.JVZO', '2026-02-10 05:05:56', 'kp.roni rt008/011', 'uploads/profile/user_41_1770700014.png', NULL, NULL, 'offline'),
(42, 2, '7587875875878758', 'mario', 'mario@gmail.com', '$2y$10$2rVm.ilpNuqxWcV3eZSUGOhN0MKVtPxu.CTqasCzJnRJCNWTBlt8K', '2026-02-19 06:38:36', 'pkp.pik12', 'uploads/profile/user_42_1771483163.jpg', NULL, NULL, 'offline');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=303;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=519;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pembeli_profile`
--
ALTER TABLE `pembeli_profile`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `penjual_profile`
--
ALTER TABLE `penjual_profile`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=394;

--
-- AUTO_INCREMENT for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=452;

--
-- AUTO_INCREMENT for table `transaksi_penjual`
--
ALTER TABLE `transaksi_penjual`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=404;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

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
