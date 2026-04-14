-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2026 at 06:48 PM
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
-- Database: `cemetry`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `action` varchar(120) NOT NULL,
  `detail` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `name`, `username`, `ip_address`, `longitude`, `latitude`, `action`, `detail`, `created_at`, `updated_at`) VALUES
(1, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Update Hak Akses', 'Memperbarui pengaturan hak akses menu sidebar per level.', '2026-04-13 07:42:23', '2026-04-13 07:42:23'),
(2, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Update Hak Akses', 'Memperbarui pengaturan hak akses menu sidebar per level.', '2026-04-13 07:42:26', '2026-04-13 07:42:26'),
(3, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Update Hak Akses', 'Memperbarui pengaturan hak akses menu sidebar per level.', '2026-04-13 07:42:31', '2026-04-13 07:42:31'),
(4, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Update Hak Akses', 'Memperbarui pengaturan hak akses menu sidebar per level.', '2026-04-13 07:44:34', '2026-04-13 07:44:34'),
(5, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Update Hak Akses', 'Memperbarui pengaturan hak akses menu sidebar per level.', '2026-04-13 07:44:38', '2026-04-13 07:44:38'),
(6, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Update Hak Akses', 'Memperbarui pengaturan hak akses menu sidebar per level.', '2026-04-13 07:53:54', '2026-04-13 07:53:54'),
(7, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Hapus Kontak Keluarga', 'Menghapus kontak keluarga #13 \"Keluarga 3\" untuk almarhum #13 \"Almarhum 3\" pada plot #13.', '2026-04-13 07:57:51', '2026-04-13 07:57:51'),
(8, 1, '-', 'superadmin', '127.0.0.1', NULL, NULL, 'Logout', 'User logout dari sistem.', '2026-04-13 08:10:32', '2026-04-13 08:10:32'),
(9, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Login', 'User login ke sistem.', '2026-04-14 07:43:22', '2026-04-14 07:43:22'),
(10, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Blok', 'Mengedit data blok #11. warna peta dari \"#10b981\" ke \"#10B981\", posisi X denah dari \"(kosong)\" ke \"134\", posisi Y denah dari \"(kosong)\" ke \"169\".', '2026-04-14 08:24:43', '2026-04-14 08:24:43'),
(11, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Blok', 'Mengedit data blok #11. posisi X denah dari \"134\" ke \"301\", posisi Y denah dari \"169\" ke \"187\".', '2026-04-14 08:29:12', '2026-04-14 08:29:12'),
(12, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Blok', 'Mengedit data blok #22. posisi X denah dari \"(kosong)\" ke \"518\", posisi Y denah dari \"(kosong)\" ke \"184\".', '2026-04-14 08:29:17', '2026-04-14 08:29:17'),
(13, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Blok', 'Mengedit data blok #23. posisi X denah dari \"(kosong)\" ke \"723\", posisi Y denah dari \"(kosong)\" ke \"170\".', '2026-04-14 08:29:22', '2026-04-14 08:29:22'),
(14, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Blok', 'Mengedit data blok #11. posisi X denah dari \"301\" ke \"115\", posisi Y denah dari \"187\" ke \"181\".', '2026-04-14 08:29:36', '2026-04-14 08:29:36'),
(15, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Blok', 'Mengedit data blok #11. posisi X denah dari \"115\" ke \"49\", posisi Y denah dari \"181\" ke \"209\".', '2026-04-14 08:33:28', '2026-04-14 08:33:28'),
(16, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Blok', 'Mengedit data blok #22. posisi X denah dari \"518\" ke \"409\", posisi Y denah dari \"184\" ke \"223\".', '2026-04-14 08:33:33', '2026-04-14 08:33:33'),
(17, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Blok', 'Mengedit data blok #11. posisi X denah dari \"49\" ke \"2\", posisi Y denah dari \"209\" ke \"213\".', '2026-04-14 08:39:16', '2026-04-14 08:39:16'),
(18, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Blok A (X:2->-365, Y:213->-115); Blok B (X:409->336, Y:223->-95); Blok C (X:723->810, Y:170->-100).', '2026-04-14 09:09:37', '2026-04-14 09:09:37'),
(19, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Tambah Plot', 'Menambah plot #32 (blok #23, nomor plot \"C-01\").', '2026-04-14 09:29:30', '2026-04-14 09:29:30');

-- --------------------------------------------------------

--
-- Table structure for table `blocks`
--

CREATE TABLE `blocks` (
  `blockid` int(11) NOT NULL,
  `block_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `map_color` varchar(20) DEFAULT NULL,
  `map_x` int(11) DEFAULT NULL,
  `map_y` int(11) DEFAULT NULL,
  `max_plots` int(10) UNSIGNED NOT NULL DEFAULT 15,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blocks`
--

INSERT INTO `blocks` (`blockid`, `block_name`, `description`, `map_color`, `map_x`, `map_y`, `max_plots`, `created_at`, `updated_at`) VALUES
(11, 'Blok A', 'Blok utama dengan 10 plot', '#10B981', -365, -115, 15, '2026-04-11 10:32:08', '2026-04-14 09:09:36'),
(22, 'Blok B', 'Kuburan Premium', '#C12F2F', 336, -95, 15, '2026-04-12 16:11:01', '2026-04-14 09:09:36'),
(23, 'Blok C', NULL, '#A42382', 810, -100, 15, '2026-04-12 10:00:30', '2026-04-14 09:09:36');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cemetery_blocks`
--

CREATE TABLE `cemetery_blocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(30) NOT NULL,
  `name` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `map_width` int(10) UNSIGNED NOT NULL DEFAULT 1200,
  `map_height` int(10) UNSIGNED NOT NULL DEFAULT 700,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deceased`
--

CREATE TABLE `deceased` (
  `deceasedid` int(11) NOT NULL,
  `plotid` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `death_date` date DEFAULT NULL,
  `burial_date` date DEFAULT NULL,
  `religion` varchar(50) DEFAULT NULL,
  `identity_number` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deceased`
--

INSERT INTO `deceased` (`deceasedid`, `plotid`, `full_name`, `gender`, `birth_date`, `death_date`, `burial_date`, `religion`, `identity_number`, `address`, `description`, `photo_url`, `created_at`, `updated_at`) VALUES
(11, 11, 'Almarhum 1', 'female', '1965-04-11', '2026-03-11', '2026-03-12', 'Kristen', 'ID-A-1', 'Alamat dummy 1', 'Deskripsi dummy 1', 'deceased-photos/BwOyROjmbEMp8xEoSfjtw6fsCdOFkpbcMfroANNX.png', '2026-04-11 10:32:08', '2026-04-12 16:46:28'),
(12, 12, 'Almarhum 2', 'male', '1964-04-11', '2026-03-10', '2026-03-11', 'Islam', 'ID-A-2', 'Alamat dummy 2', 'Deskripsi dummy 2', NULL, '2026-04-11 10:32:08', '2026-04-11 10:32:08'),
(13, 13, 'Almarhum 3', 'female', '1963-04-11', '2026-03-09', '2026-03-10', 'Kristen', 'ID-A-3', 'Alamat dummy 3', 'Deskripsi dummy 3', NULL, '2026-04-11 10:32:08', '2026-04-11 10:32:08'),
(14, 15, 'Almarhum 5', 'female', '1961-04-11', '2026-03-07', '2026-03-08', 'Kristen', 'ID-A-5', 'Alamat dummy 5', 'Deskripsi dummy 5', NULL, '2026-04-11 10:32:08', '2026-04-11 10:32:08'),
(15, 17, 'Almarhum 7', 'female', '1959-04-11', '2026-03-05', '2026-03-06', 'Kristen', 'ID-A-7', 'Alamat dummy 7', 'Deskripsi dummy 7', NULL, '2026-04-11 10:32:08', '2026-04-11 10:32:08'),
(16, 19, 'Almarhum 9', 'female', '1957-04-11', '2026-03-03', '2026-03-04', 'Kristen', 'ID-A-9', 'Alamat dummy 9', 'Deskripsi dummy 9', NULL, '2026-04-11 10:32:08', '2026-04-11 10:32:08'),
(17, 20, 'Almarhum 10', 'male', '1956-04-11', '2026-03-02', '2026-03-03', 'Islam', 'ID-A-10', 'Alamat dummy 10', 'Deskripsi dummy 10', NULL, '2026-04-11 10:32:08', '2026-04-11 10:32:08'),
(18, 21, 'Almarhum Dummy 1', 'female', '1965-04-11', '2026-03-01', '2026-03-02', 'Kristen', 'DUMMY-ID-0001', 'Jl. Dummy No. 1, Kota Contoh', 'Data dummy almarhum ke-1', NULL, '2026-04-11 08:21:44', '2026-04-11 08:21:44'),
(19, 22, 'Almarhum Dummy 2', 'male', '1964-04-11', '2026-02-28', '2026-03-01', 'Islam', 'DUMMY-ID-0002', 'Jl. Dummy No. 2, Kota Contoh', 'Data dummy almarhum ke-2', NULL, '2026-04-11 08:21:44', '2026-04-11 08:21:44'),
(20, 23, 'Almarhum Dummy 3', 'female', '1963-04-11', '2026-02-27', '2026-02-28', 'Kristen', 'DUMMY-ID-0003', 'Jl. Dummy No. 3, Kota Contoh', 'Data dummy almarhum ke-3', NULL, '2026-04-11 08:21:44', '2026-04-11 08:21:44'),
(21, 24, 'Almarhum Dummy 4', 'male', '1962-04-11', '2026-02-26', '2026-02-27', 'Islam', 'DUMMY-ID-0004', 'Jl. Dummy No. 4, Kota Contoh', 'Data dummy almarhum ke-4', NULL, '2026-04-11 08:21:44', '2026-04-11 08:21:44'),
(22, 25, 'Almarhum Dummy 5', 'female', '1961-04-11', '2026-02-25', '2026-02-26', 'Kristen', 'DUMMY-ID-0005', 'Jl. Dummy No. 5, Kota Contoh', 'Data dummy almarhum ke-5', NULL, '2026-04-11 08:21:44', '2026-04-11 08:21:44'),
(23, 26, 'Almarhum Dummy 6', 'male', '1960-04-11', '2026-02-24', '2026-02-25', 'Islam', 'DUMMY-ID-0006', 'Jl. Dummy No. 6, Kota Contoh', 'Data dummy almarhum ke-6', NULL, '2026-04-11 08:21:44', '2026-04-11 08:21:44'),
(24, 27, 'Almarhum Dummy 7', 'female', '1959-04-11', '2026-02-23', '2026-02-24', 'Kristen', 'DUMMY-ID-0007', 'Jl. Dummy No. 7, Kota Contoh', 'Data dummy almarhum ke-7', NULL, '2026-04-11 08:21:44', '2026-04-11 08:21:44'),
(25, 28, 'Almarhum Dummy 8', 'male', '1958-04-11', '2026-02-22', '2026-02-23', 'Islam', 'DUMMY-ID-0008', 'Jl. Dummy No. 8, Kota Contoh', 'Data dummy almarhum ke-8', NULL, '2026-04-11 08:21:44', '2026-04-11 08:21:44'),
(26, 29, 'Almarhum Dummy 9', 'female', '1957-04-11', '2026-02-21', '2026-02-22', 'Kristen', 'DUMMY-ID-0009', 'Jl. Dummy No. 9, Kota Contoh', 'Data dummy almarhum ke-9', NULL, '2026-04-11 08:21:44', '2026-04-11 08:21:44'),
(27, 30, 'Almarhum Dummy 10', 'male', '1956-04-11', '2026-02-20', '2026-02-21', 'Islam', 'DUMMY-ID-0010', 'Jl. Dummy No. 10, Kota Contoh', 'Data dummy almarhum ke-10', NULL, '2026-04-11 08:21:44', '2026-04-11 08:21:44'),
(28, 14, 'Holly Stephens', 'female', '1999-09-03', '1983-02-28', '2013-11-25', 'Non est iste impedit', '958', 'Aut ex ipsum optio', 'Blanditiis aut ex il', 'deceased-photos/pcqdW3JMsNBXqVInQacUscw6y2QKAKUMZGay9VP8.png', '2026-04-12 23:05:09', '2026-04-12 23:05:09');

-- --------------------------------------------------------

--
-- Table structure for table `deceased_records`
--

CREATE TABLE `deceased_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `plot_id` bigint(20) UNSIGNED DEFAULT NULL,
  `full_name` varchar(150) NOT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `death_date` date DEFAULT NULL,
  `burial_date` date DEFAULT NULL,
  `religion` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employer`
--

CREATE TABLE `employer` (
  `employerid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phonenumber` varchar(255) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employer`
--

INSERT INTO `employer` (`employerid`, `name`, `email`, `phonenumber`, `userid`) VALUES
(1, 'Superadmin\r\n', 'superadmin@gmail.com', '0350748', 1),
(2, 'Admin', 'admin@gmail.com', '0987654', 2),
(3, 'Eric Dean', 'vufojax@mailinator.com', '+1 (316) 817-5017', 3);

-- --------------------------------------------------------

--
-- Table structure for table `families`
--

CREATE TABLE `families` (
  `familyid` int(11) NOT NULL,
  `deceased_id` int(11) NOT NULL,
  `family_name` varchar(150) NOT NULL,
  `relationship_status` varchar(100) DEFAULT NULL,
  `phone_number` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `families`
--

INSERT INTO `families` (`familyid`, `deceased_id`, `family_name`, `relationship_status`, `phone_number`, `email`, `address`, `notes`, `created_at`, `updated_at`) VALUES
(11, 11, 'Keluarga 1', 'Suami/Istri', '08123000001', 'keluarga1@dummy.test', 'Alamat keluarga 1', 'Kontak dummy', '2026-04-11 10:32:08', '2026-04-11 10:32:08'),
(12, 12, 'Keluarga 2', 'Anak', '08123000002', 'keluarga2@dummy.test', 'Alamat keluarga 2', 'Kontak dummy', '2026-04-11 10:32:08', '2026-04-11 10:32:08'),
(14, 14, 'Keluarga 5', 'Suami/Istri', '08123000005', 'keluarga5@dummy.test', 'Alamat keluarga 5', 'Kontak dummy', '2026-04-11 10:32:08', '2026-04-11 10:32:08'),
(15, 15, 'Keluarga 7', 'Suami/Istri', '08123000007', 'keluarga7@dummy.test', 'Alamat keluarga 7', 'Kontak dummy', '2026-04-11 10:32:08', '2026-04-11 10:32:08'),
(16, 16, 'Keluarga 9', 'Suami/Istri', '08123000009', 'keluarga9@dummy.test', 'Alamat keluarga 9', 'Kontak dummy', '2026-04-11 10:32:08', '2026-04-11 10:32:08'),
(17, 17, 'Keluarga 10', 'Anak', '08123000010', 'keluarga10@dummy.test', 'Alamat keluarga 10', 'Kontak dummy', '2026-04-11 10:32:08', '2026-04-11 10:32:08'),
(18, 18, 'Keluarga Dummy 1', 'Suami/Istri', '08123000001', 'keluarga1@dummy.test', 'Alamat keluarga dummy ke-1', 'Kontak keluarga dummy', '2026-04-11 08:21:44', '2026-04-11 08:21:44'),
(19, 19, 'Keluarga Dummy 2', 'Anak', '08123000002', 'keluarga2@dummy.test', 'Alamat keluarga dummy ke-2', 'Kontak keluarga dummy', '2026-04-11 08:21:44', '2026-04-11 08:21:44'),
(20, 20, 'Keluarga Dummy 3', 'Suami/Istri', '08123000003', 'keluarga3@dummy.test', 'Alamat keluarga dummy ke-3', 'Kontak keluarga dummy', '2026-04-11 08:21:44', '2026-04-11 08:21:44'),
(21, 21, 'Keluarga Dummy 4', 'Anak', '08123000004', 'keluarga4@dummy.test', 'Alamat keluarga dummy ke-4', 'Kontak keluarga dummy', '2026-04-11 08:21:44', '2026-04-11 08:21:44'),
(22, 22, 'Keluarga Dummy 5', 'Suami/Istri', '08123000005', 'keluarga5@dummy.test', 'Alamat keluarga dummy ke-5', 'Kontak keluarga dummy', '2026-04-11 08:21:44', '2026-04-11 08:21:44'),
(23, 23, 'Keluarga Dummy 6', 'Anak', '08123000006', 'keluarga6@dummy.test', 'Alamat keluarga dummy ke-6', 'Kontak keluarga dummy', '2026-04-11 08:21:44', '2026-04-11 08:21:44'),
(24, 24, 'Keluarga Dummy 7', 'Suami/Istri', '08123000007', 'keluarga7@dummy.test', 'Alamat keluarga dummy ke-7', 'Kontak keluarga dummy', '2026-04-11 08:21:44', '2026-04-11 08:21:44'),
(25, 25, 'Keluarga Dummy 8', 'Anak', '08123000008', 'keluarga8@dummy.test', 'Alamat keluarga dummy ke-8', 'Kontak keluarga dummy', '2026-04-11 08:21:44', '2026-04-11 08:21:44'),
(26, 26, 'Keluarga Dummy 9', 'Suami/Istri', '08123000009', 'keluarga9@dummy.test', 'Alamat keluarga dummy ke-9', 'Kontak keluarga dummy', '2026-04-11 08:21:44', '2026-04-11 08:21:44'),
(27, 27, 'Keluarga Dummy 10', 'Anak', '08123000010', 'keluarga10@dummy.test', 'Alamat keluarga dummy ke-10', 'Kontak keluarga dummy', '2026-04-11 08:21:44', '2026-04-11 08:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `grave_plots`
--

CREATE TABLE `grave_plots` (
  `plotid` int(11) NOT NULL,
  `block_id` int(11) NOT NULL,
  `plot_number` varchar(50) NOT NULL,
  `row_number` varchar(20) DEFAULT NULL,
  `position_x` decimal(10,2) NOT NULL,
  `position_y` decimal(10,2) NOT NULL,
  `width` decimal(10,2) DEFAULT NULL,
  `height` decimal(10,2) DEFAULT NULL,
  `status` enum('empty','occupied') DEFAULT 'empty',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grave_plots`
--

INSERT INTO `grave_plots` (`plotid`, `block_id`, `plot_number`, `row_number`, `position_x`, `position_y`, `width`, `height`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(11, 11, 'A-01', '1', 40.00, 40.00, 60.00, 40.00, 'occupied', 'Plot dummy 1', '2026-04-11 10:32:08', '2026-04-12 16:46:28'),
(12, 11, 'A-02', '1', 130.00, 40.00, 60.00, 40.00, 'occupied', 'Plot dummy 2', '2026-04-11 10:32:08', '2026-04-11 08:21:44'),
(13, 11, 'A-03', '1', 220.00, 40.00, 60.00, 40.00, 'occupied', 'Plot dummy 3', '2026-04-11 10:32:08', '2026-04-11 08:21:44'),
(14, 11, 'A-04', '1', 310.00, 40.00, 60.00, 40.00, 'occupied', 'Plot dummy 4', '2026-04-11 10:32:08', '2026-04-12 23:05:09'),
(15, 11, 'A-05', '1', 400.00, 40.00, 60.00, 40.00, 'occupied', 'Plot dummy 5', '2026-04-11 10:32:08', '2026-04-11 08:21:44'),
(16, 11, 'A-06', '2', 40.00, 130.00, 60.00, 40.00, 'empty', 'Plot dummy 6', '2026-04-11 10:32:08', '2026-04-11 15:28:16'),
(17, 11, 'A-07', '2', 130.00, 130.00, 60.00, 40.00, 'occupied', 'Plot dummy 7', '2026-04-11 10:32:08', '2026-04-11 08:21:44'),
(18, 11, 'A-08', '2', 220.00, 130.00, 60.00, 40.00, 'empty', 'Plot dummy 8', '2026-04-11 10:32:08', '2026-04-11 10:32:08'),
(19, 11, 'A-09', '2', 310.00, 130.00, 60.00, 40.00, 'occupied', 'Plot dummy 9', '2026-04-11 10:32:08', '2026-04-11 08:21:44'),
(20, 11, 'A-10', '2', 400.00, 130.00, 60.00, 40.00, 'occupied', 'Plot dummy 10', '2026-04-11 10:32:08', '2026-04-11 08:21:44'),
(31, 22, 'B-01', NULL, 24.00, 24.00, 60.00, 40.00, 'empty', NULL, '2026-04-12 10:40:49', '2026-04-12 10:40:49'),
(32, 23, 'C-01', NULL, 24.00, 24.00, 60.00, 40.00, 'empty', NULL, '2026-04-14 09:29:30', '2026-04-14 09:29:30');

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `levelid` int(11) NOT NULL,
  `levelname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`levelid`, `levelname`) VALUES
(1, 'Superadmin'),
(2, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `level_sidebar_access`
--

CREATE TABLE `level_sidebar_access` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `levelid` int(10) UNSIGNED NOT NULL,
  `menu_key` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `level_sidebar_access`
--

INSERT INTO `level_sidebar_access` (`id`, `levelid`, `menu_key`, `created_at`, `updated_at`) VALUES
(64, 1, 'data-blok', '2026-04-13 07:53:54', '2026-04-13 07:53:54'),
(65, 1, 'data-plot', '2026-04-13 07:53:54', '2026-04-13 07:53:54'),
(66, 1, 'data-almarhum', '2026-04-13 07:53:54', '2026-04-13 07:53:54'),
(67, 1, 'data-kontak-keluarga', '2026-04-13 07:53:54', '2026-04-13 07:53:54'),
(68, 1, 'data-user', '2026-04-13 07:53:54', '2026-04-13 07:53:54'),
(69, 1, 'activity-log', '2026-04-13 07:53:54', '2026-04-13 07:53:54'),
(70, 1, 'restore-data', '2026-04-13 07:53:54', '2026-04-13 07:53:54'),
(71, 1, 'hak-akses', '2026-04-13 07:53:54', '2026-04-13 07:53:54'),
(72, 1, 'settings', '2026-04-13 07:53:54', '2026-04-13 07:53:54'),
(73, 2, 'data-blok', '2026-04-13 07:53:54', '2026-04-13 07:53:54'),
(74, 2, 'data-plot', '2026-04-13 07:53:54', '2026-04-13 07:53:54'),
(75, 2, 'data-almarhum', '2026-04-13 07:53:54', '2026-04-13 07:53:54'),
(76, 2, 'data-kontak-keluarga', '2026-04-13 07:53:54', '2026-04-13 07:53:54'),
(77, 2, 'data-user', '2026-04-13 07:53:54', '2026-04-13 07:53:54'),
(78, 2, 'activity-log', '2026-04-13 07:53:54', '2026-04-13 07:53:54');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_11_100000_add_admin_fields_to_users_table', 2),
(5, '2026_04_11_100100_create_cemetery_blocks_table', 2),
(6, '2026_04_11_100200_create_grave_plots_table', 3),
(7, '2026_04_11_100300_create_deceased_records_table', 4),
(8, '2026_04_11_100400_create_family_contacts_table', 4),
(9, '2026_04_11_100500_create_system_settings_table', 4),
(10, '2026_04_11_173500_normalize_grave_plot_statuses', 4),
(11, '2026_04_13_000001_add_profile_fields_to_legacy_user_table', 5),
(12, '2026_04_13_000002_add_max_plots_to_blocks_table', 6),
(13, '2026_04_13_000001_create_activity_logs_table', 7),
(14, '2026_04_13_000003_create_level_sidebar_access_table', 7),
(15, '2026_04_14_000004_add_map_coordinates_to_blocks_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `roleid` int(11) NOT NULL,
  `rolename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0MRvaLgop0qB1coKRYSBdaGRDuCOu5nnYYN8crfU', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidkROQnFXbDBxbDRZRDRVYWlVRWVBM2F2RTNBS1R4ZENIaktBUXNtdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTEwOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvbWVkaWEvZGVjZWFzZWQtcGhvdG8/cGF0aD1kZWNlYXNlZC1waG90b3MlMkZwY3FkVzNKTXNOQlhxVkluUWFjVXNjdzZ5MlFLQUtVTVpHYXk5VlA4LnBuZyI7czo1OiJyb3V0ZSI7czoyMDoibWVkaWEuZGVjZWFzZWQtcGhvdG8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjk6ImF1dGhfdXNlciI7YTo2OntzOjI6ImlkIjtpOjE7czo4OiJ1c2VybmFtZSI7czoxMDoic3VwZXJhZG1pbiI7czo3OiJsZXZlbGlkIjtpOjE7czo0OiJuYW1lIjtzOjEyOiJTdXBlcmFkbWluDQoiO3M6ODoibGF0aXR1ZGUiO047czo5OiJsb25naXR1ZGUiO047fX0=', 1776185209),
('4hd0PJa32ZDgFHKzy257j4tCDKZeT6wPwlDsTo03', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYW5rTUlrd1JETWw4UDFkaGlTRlB3dGZkVE45UWM5WE82YVU2N2NLeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776177788),
('Hh3Sm2CV1qvRxyMobx6KOGVRB34jv3IgNK4AbtSV', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidFdyNmVHRlFnN1JZc2hjMTA3V3RiWWt6TzhUWUJCbG54YkdPZTZuNCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jaGF0Ym90IjtzOjU6InJvdXRlIjtzOjc6ImNoYXRib3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776185253);

-- --------------------------------------------------------

--
-- Table structure for table `system`
--

CREATE TABLE `system` (
  `systemid` int(11) NOT NULL,
  `systemname` varchar(255) NOT NULL,
  `systemlogo` varchar(255) NOT NULL,
  `systemcontact` varchar(255) NOT NULL,
  `systemmanager` varchar(255) NOT NULL,
  `systemaddress` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system`
--

INSERT INTO `system` (`systemid`, `systemname`, `systemlogo`, `systemcontact`, `systemmanager`, `systemaddress`) VALUES
(1, 'Kuburan', 'storage/system-logos/logo-20260412170840-G14095.png', '021-555-0001', 'Manager', 'Alamat Sistem');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `levelid` int(11) NOT NULL,
  `reset_password_token` varchar(255) DEFAULT NULL,
  `reset_password_token_expired` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `username`, `password`, `levelid`, `reset_password_token`, `reset_password_token_expired`) VALUES
(1, 'superadmin', '$2y$12$43NfKBErNkIW5VYZ5WKuEeYFyba9ixlwvqJkHq2Ek4oHKyC8EcM6S', 1, '', '2026-04-11 15:21:44'),
(2, 'admin', '$2y$12$Xuou8Uhff2E/qVgIkwWFx.SkeE/c7MnaXew5HFPniv2SdApIxHJ5G', 2, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_index` (`user_id`);

--
-- Indexes for table `blocks`
--
ALTER TABLE `blocks`
  ADD PRIMARY KEY (`blockid`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cemetery_blocks`
--
ALTER TABLE `cemetery_blocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cemetery_blocks_code_unique` (`code`);

--
-- Indexes for table `deceased`
--
ALTER TABLE `deceased`
  ADD PRIMARY KEY (`deceasedid`);

--
-- Indexes for table `deceased_records`
--
ALTER TABLE `deceased_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employer`
--
ALTER TABLE `employer`
  ADD PRIMARY KEY (`employerid`);

--
-- Indexes for table `families`
--
ALTER TABLE `families`
  ADD PRIMARY KEY (`familyid`);

--
-- Indexes for table `grave_plots`
--
ALTER TABLE `grave_plots`
  ADD PRIMARY KEY (`plotid`);

--
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`levelid`);

--
-- Indexes for table `level_sidebar_access`
--
ALTER TABLE `level_sidebar_access`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `level_sidebar_access_unique` (`levelid`,`menu_key`),
  ADD KEY `level_sidebar_access_levelid_index` (`levelid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`roleid`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `system`
--
ALTER TABLE `system`
  ADD PRIMARY KEY (`systemid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `blocks`
--
ALTER TABLE `blocks`
  MODIFY `blockid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `cemetery_blocks`
--
ALTER TABLE `cemetery_blocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deceased`
--
ALTER TABLE `deceased`
  MODIFY `deceasedid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `deceased_records`
--
ALTER TABLE `deceased_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employer`
--
ALTER TABLE `employer`
  MODIFY `employerid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `families`
--
ALTER TABLE `families`
  MODIFY `familyid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `grave_plots`
--
ALTER TABLE `grave_plots`
  MODIFY `plotid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `level`
--
ALTER TABLE `level`
  MODIFY `levelid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `level_sidebar_access`
--
ALTER TABLE `level_sidebar_access`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `roleid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system`
--
ALTER TABLE `system`
  MODIFY `systemid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
