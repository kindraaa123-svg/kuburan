-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2026 at 06:04 PM
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
(19, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Tambah Plot', 'Menambah plot #32 (blok #23, nomor plot \"C-01\").', '2026-04-14 09:29:30', '2026-04-14 09:29:30'),
(20, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Login', 'User login ke sistem.', '2026-04-15 07:30:47', '2026-04-15 07:30:47'),
(21, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Logout', 'User logout dari sistem.', '2026-04-15 07:31:21', '2026-04-15 07:31:21'),
(22, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Login', 'User login ke sistem.', '2026-04-17 22:49:53', '2026-04-17 22:49:53'),
(23, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Blok A (X:-365->116, Y:-115->86); Blok B (X:336->250, Y:-95->86); Blok C (X:810->248, Y:-100->206).', '2026-04-17 23:36:59', '2026-04-17 23:36:59'),
(24, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Blok A (X:116->121, Y:86->108); Blok B (X:250->283, Y:86->99); Blok C (X:248->287, Y:206->263).', '2026-04-17 23:52:44', '2026-04-17 23:52:44'),
(25, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Tidak ada perubahan nilai.', '2026-04-17 23:56:28', '2026-04-17 23:56:28'),
(26, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Tidak ada perubahan nilai.', '2026-04-18 00:07:32', '2026-04-18 00:07:32'),
(27, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Blok A (X:121->116, Y:108->107); Blok B (X:283->130, Y:99->359); Blok C (X:287->433, Y:263->364).', '2026-04-18 00:07:52', '2026-04-18 00:07:52'),
(28, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Item fasilitas #1 (facility 2, X:538, Y:669).', '2026-04-18 01:25:51', '2026-04-18 01:25:51'),
(29, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Item fasilitas #2 (facility 2, X:537, Y:695).', '2026-04-18 01:39:44', '2026-04-18 01:39:44'),
(30, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Login', 'User login ke sistem.', '2026-04-18 07:24:13', '2026-04-18 07:24:13'),
(31, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Item fasilitas #3 (facility 3, X:42, Y:22); Item fasilitas #4 (facility 3, X:24, Y:88); Item fasilitas #5 (facility 3, X:1088, Y:88); Item fasilitas #6 (facility 1, X:20, Y:6); Item fasilitas #7 (facility 1, X:53, Y:6); Item fasilitas #8 (facility 1, X:86, Y:6); Item fasilitas #9 (facility 1, X:119, Y:6); Item fasilitas #10 (facility 1, X:152, Y:6); Item fasilitas #11 (facility 1, X:185, Y:6); Item fasilitas #12 (facility 1, X:218, Y:6); Item fasilitas #13 (facility 1, X:251, Y:6); Item fasilitas #14 (facility 1, X:284, Y:6); Item fasilitas #15 (facility 1, X:317, Y:6); Item fasilitas #16 (facility 1, X:350, Y:6); Item fasilitas #17 (facility 1, X:383, Y:6); Item fasilitas #18 (facility 1, X:416, Y:6); Item fasilitas #19 (facility 1, X:449, Y:6); Item fasilitas #20 (facility 1, X:482, Y:6); Item fasilitas #21 (facility 1, X:515, Y:6); Item fasilitas #22 (facility 1, X:548, Y:6); Item fasilitas #23 (facility 1, X:581, Y:6); Item fasilitas #24 (facility 1, X:614, Y:6); Item fasilitas #25 (facility 1, X:647, Y:6); Item fasilitas #26 (facility 1, X:680, Y:6); Item fasilitas #27 (facility 1, X:713, Y:6); Item fasilitas #28 (facility 1, X:746, Y:6); Item fasilitas #29 (facility 1, X:779, Y:6); Item fasilitas #30 (facility 1, X:812, Y:6); Item fasilitas #31 (facility 1, X:845, Y:6); Item fasilitas #32 (facility 1, X:878, Y:6); Item fasilitas #33 (facility 1, X:911, Y:6); Item fasilitas #34 (facility 1, X:944, Y:6); Item fasilitas #35 (facility 1, X:977, Y:6); Item fasilitas #36 (facility 1, X:1010, Y:6); Item fasilitas #37 (facility 1, X:1043, Y:6); Item fasilitas #38 (facility 1, X:1076, Y:6); Item fasilitas #39 (facility 1, X:1109, Y:6); Item fasilitas #40 (facility 1, X:20, Y:738); Item fasilitas #41 (facility 1, X:53, Y:738); Item fasilitas #42 (facility 1, X:86, Y:738); Item fasilitas #43 (facility 1, X:119, Y:738); Item fasilitas #44 (facility 1, X:152, Y:738); Item fasilitas #45 (facility 1, X:185, Y:738); Item fasilitas #46 (facility 1, X:218, Y:738); Item fasilitas #47 (facility 1, X:251, Y:738); Item fasilitas #48 (facility 1, X:284, Y:738); Item fasilitas #49 (facility 1, X:317, Y:738); Item fasilitas #50 (facility 1, X:350, Y:738); Item fasilitas #51 (facility 1, X:383, Y:738); Item fasilitas #52 (facility 1, X:416, Y:738); Item fasilitas #53 (facility 1, X:449, Y:738); Item fasilitas #54 (facility 1, X:482, Y:738); Item fasilitas #55 (facility 1, X:515, Y:738); Item fasilitas #56 (facility 1, X:647, Y:738); Item fasilitas #57 (facility 1, X:680, Y:738); Item fasilitas #58 (facility 1, X:713, Y:738); Item fasilitas #59 (facility 1, X:746, Y:738); Item fasilitas #60 (facility 1, X:779, Y:738); Item fasilitas #61 (facility 1, X:812, Y:738); Item fasilitas #62 (facility 1, X:845, Y:738); Item fasilitas #63 (facility 1, X:878, Y:738); Item fasilitas #64 (facility 1, X:911, Y:738); Item fasilitas #65 (facility 1, X:944, Y:738); Item fasilitas #66 (facility 1, X:977, Y:738); Item fasilitas #67 (facility 1, X:1010, Y:738); Item fasilitas #68 (facility 1, X:1043, Y:738); Item fasilitas #69 (facility 1, X:1076, Y:738); Item fasilitas #70 (facility 1, X:1109, Y:738); Item fasilitas #71 (facility 1, X:6, Y:42); Item fasilitas #72 (facility 1, X:1158, Y:42); Item fasilitas #73 (facility 1, X:6, Y:76); Item fasilitas #74 (facility 1, X:1158, Y:76); Item fasilitas #75 (facility 1, X:6, Y:110); Item fasilitas #76 (facility 1, X:1158, Y:110); Item fasilitas #77 (facility 1, X:6, Y:144); Item fasilitas #78 (facility 1, X:1158, Y:144); Item fasilitas #79 (facility 1, X:6, Y:178); Item fasilitas #80 (facility 1, X:1158, Y:178); Item fasilitas #81 (facility 1, X:6, Y:212); Item fasilitas #82 (facility 1, X:1158, Y:212); Item fasilitas #83 (facility 1, X:6, Y:246); Item fasilitas #84 (facility 1, X:1158, Y:246); Item fasilitas #85 (facility 1, X:6, Y:280); Item fasilitas #86 (facility 1, X:1158, Y:280); Item fasilitas #87 (facility 1, X:6, Y:314); Item fasilitas #88 (facility 1, X:1158, Y:314); Item fasilitas #89 (facility 1, X:6, Y:348); Item fasilitas #90 (facility 1, X:1158, Y:348); Item fasilitas #91 (facility 1, X:6, Y:382); Item fasilitas #92 (facility 1, X:1158, Y:382); Item fasilitas #93 (facility 1, X:6, Y:416); Item fasilitas #94 (facility 1, X:1158, Y:416); Item fasilitas #95 (facility 1, X:6, Y:450); Item fasilitas #96 (facility 1, X:1158, Y:450); Item fasilitas #97 (facility 1, X:6, Y:484); Item fasilitas #98 (facility 1, X:1158, Y:484); Item fasilitas #99 (facility 1, X:6, Y:518); Item fasilitas #100 (facility 1, X:1158, Y:518); Item fasilitas #101 (facility 1, X:6, Y:552); Item fasilitas #102 (facility 1, X:1158, Y:552); Item fasilitas #103 (facility 1, X:6, Y:586); Item fasilitas #104 (facility 1, X:1158, Y:586); Item fasilitas #105 (facility 1, X:6, Y:620); Item fasilitas #106 (facility 1, X:1158, Y:620); Item fasilitas #107 (facility 1, X:6, Y:654); Item fasilitas #108 (facility 1, X:1158, Y:654); Item fasilitas #109 (facility 1, X:6, Y:688); Item fasilitas #110 (facility 1, X:1158, Y:688).', '2026-04-18 07:33:05', '2026-04-18 07:33:05'),
(32, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Item fasilitas #111 (facility 3, X:42, Y:22); Item fasilitas #112 (facility 3, X:36, Y:19); Item fasilitas #113 (facility 3, X:1088, Y:88); Item fasilitas #114 (facility 3, X:118, Y:248); Item fasilitas #115 (facility 3, X:136, Y:422); Item fasilitas #116 (facility 3, X:118, Y:614); Item fasilitas #117 (facility 1, X:20, Y:6); Item fasilitas #118 (facility 1, X:53, Y:6); Item fasilitas #119 (facility 1, X:86, Y:6); Item fasilitas #120 (facility 1, X:119, Y:6); Item fasilitas #121 (facility 1, X:152, Y:6); Item fasilitas #122 (facility 1, X:185, Y:6); Item fasilitas #123 (facility 1, X:218, Y:6); Item fasilitas #124 (facility 1, X:251, Y:6); Item fasilitas #125 (facility 1, X:284, Y:6); Item fasilitas #126 (facility 1, X:317, Y:6); Item fasilitas #127 (facility 1, X:350, Y:6); Item fasilitas #128 (facility 1, X:383, Y:6); Item fasilitas #129 (facility 1, X:416, Y:6); Item fasilitas #130 (facility 1, X:449, Y:6); Item fasilitas #131 (facility 1, X:482, Y:6); Item fasilitas #132 (facility 1, X:515, Y:6); Item fasilitas #133 (facility 1, X:548, Y:6); Item fasilitas #134 (facility 1, X:581, Y:6); Item fasilitas #135 (facility 1, X:614, Y:6); Item fasilitas #136 (facility 1, X:647, Y:6); Item fasilitas #137 (facility 1, X:680, Y:6); Item fasilitas #138 (facility 1, X:713, Y:6); Item fasilitas #139 (facility 1, X:746, Y:6); Item fasilitas #140 (facility 1, X:779, Y:6); Item fasilitas #141 (facility 1, X:812, Y:6); Item fasilitas #142 (facility 1, X:845, Y:6); Item fasilitas #143 (facility 1, X:878, Y:6); Item fasilitas #144 (facility 1, X:911, Y:6); Item fasilitas #145 (facility 1, X:944, Y:6); Item fasilitas #146 (facility 1, X:977, Y:6); Item fasilitas #147 (facility 1, X:1010, Y:6); Item fasilitas #148 (facility 1, X:1043, Y:6); Item fasilitas #149 (facility 1, X:1076, Y:6); Item fasilitas #150 (facility 1, X:1109, Y:6); Item fasilitas #151 (facility 1, X:20, Y:738); Item fasilitas #152 (facility 1, X:53, Y:738); Item fasilitas #153 (facility 1, X:86, Y:738); Item fasilitas #154 (facility 1, X:119, Y:738); Item fasilitas #155 (facility 1, X:152, Y:738); Item fasilitas #156 (facility 1, X:185, Y:738); Item fasilitas #157 (facility 1, X:218, Y:738); Item fasilitas #158 (facility 1, X:251, Y:738); Item fasilitas #159 (facility 1, X:284, Y:738); Item fasilitas #160 (facility 1, X:317, Y:738); Item fasilitas #161 (facility 1, X:350, Y:738); Item fasilitas #162 (facility 1, X:383, Y:738); Item fasilitas #163 (facility 1, X:416, Y:738); Item fasilitas #164 (facility 1, X:449, Y:738); Item fasilitas #165 (facility 1, X:482, Y:738); Item fasilitas #166 (facility 1, X:515, Y:738); Item fasilitas #167 (facility 1, X:548, Y:1278); Item fasilitas #168 (facility 1, X:581, Y:1278); Item fasilitas #169 (facility 1, X:614, Y:1278); Item fasilitas #170 (facility 1, X:647, Y:738); Item fasilitas #171 (facility 1, X:680, Y:738); Item fasilitas #172 (facility 1, X:713, Y:738); Item fasilitas #173 (facility 1, X:746, Y:738); Item fasilitas #174 (facility 1, X:779, Y:738); Item fasilitas #175 (facility 1, X:812, Y:738); Item fasilitas #176 (facility 1, X:845, Y:738); Item fasilitas #177 (facility 1, X:878, Y:738); Item fasilitas #178 (facility 1, X:911, Y:738); Item fasilitas #179 (facility 1, X:944, Y:738); Item fasilitas #180 (facility 1, X:977, Y:738); Item fasilitas #181 (facility 1, X:1010, Y:738); Item fasilitas #182 (facility 1, X:1043, Y:738); Item fasilitas #183 (facility 1, X:1076, Y:738); Item fasilitas #184 (facility 1, X:1109, Y:738); Item fasilitas #185 (facility 1, X:6, Y:42); Item fasilitas #186 (facility 1, X:1158, Y:42); Item fasilitas #187 (facility 1, X:6, Y:76); Item fasilitas #188 (facility 1, X:1158, Y:76); Item fasilitas #189 (facility 1, X:6, Y:110); Item fasilitas #190 (facility 1, X:1158, Y:110); Item fasilitas #191 (facility 1, X:6, Y:144); Item fasilitas #192 (facility 1, X:1158, Y:144); Item fasilitas #193 (facility 1, X:6, Y:178); Item fasilitas #194 (facility 1, X:1158, Y:178); Item fasilitas #195 (facility 1, X:6, Y:212); Item fasilitas #196 (facility 1, X:1158, Y:212); Item fasilitas #197 (facility 1, X:6, Y:246); Item fasilitas #198 (facility 1, X:1158, Y:246); Item fasilitas #199 (facility 1, X:6, Y:280); Item fasilitas #200 (facility 1, X:1158, Y:280); Item fasilitas #201 (facility 1, X:6, Y:314); Item fasilitas #202 (facility 1, X:1158, Y:314); Item fasilitas #203 (facility 1, X:6, Y:348); Item fasilitas #204 (facility 1, X:1158, Y:348); Item fasilitas #205 (facility 1, X:6, Y:382); Item fasilitas #206 (facility 1, X:1158, Y:382); Item fasilitas #207 (facility 1, X:6, Y:416); Item fasilitas #208 (facility 1, X:1158, Y:416); Item fasilitas #209 (facility 1, X:6, Y:450); Item fasilitas #210 (facility 1, X:1158, Y:450); Item fasilitas #211 (facility 1, X:6, Y:484); Item fasilitas #212 (facility 1, X:1158, Y:484); Item fasilitas #213 (facility 1, X:6, Y:518); Item fasilitas #214 (facility 1, X:1158, Y:518); Item fasilitas #215 (facility 1, X:6, Y:552); Item fasilitas #216 (facility 1, X:1158, Y:552); Item fasilitas #217 (facility 1, X:6, Y:586); Item fasilitas #218 (facility 1, X:1158, Y:586); Item fasilitas #219 (facility 1, X:6, Y:620); Item fasilitas #220 (facility 1, X:1158, Y:620); Item fasilitas #221 (facility 1, X:6, Y:654); Item fasilitas #222 (facility 1, X:1158, Y:654); Item fasilitas #223 (facility 1, X:6, Y:688); Item fasilitas #224 (facility 1, X:1158, Y:688); Item fasilitas #225 (facility 2, X:880, Y:1254).', '2026-04-18 08:23:34', '2026-04-18 08:23:34'),
(33, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan blok (2): Blok B (X:130->105, Y:359->398); Blok C (X:433->413, Y:364->394) | Perubahan fasilitas (115): #226 f3 (X:42, Y:22); #227 f3 (X:29, Y:0); #228 f3 (X:1084, Y:58); #229 f3 (X:118, Y:248); #230 f3 (X:136, Y:422); #231 f3 (X:118, Y:614); #232 f1 (X:20, Y:6); #233 f1 (X:53, Y:6); +107 item lain.', '2026-04-18 08:58:11', '2026-04-18 08:58:11'),
(34, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan blok (2): Blok B (X:105->108, Y:398->410); Blok C (X:413->417, Y:394->406) | Perubahan fasilitas (116): #341 f3 (X:48, Y:324); #342 f3 (X:42, Y:17); #343 f3 (X:29, Y:0); #344 f3 (X:1084, Y:58); #345 f3 (X:118, Y:248); #346 f3 (X:136, Y:422); #347 f3 (X:118, Y:614); #348 f1 (X:20, Y:6); +108 item lain.', '2026-04-18 09:08:56', '2026-04-18 09:08:56'),
(35, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan fasilitas (116): #341 f3 (X:48->32, Y:324->324); #457 f3 (X:42, Y:17); #458 f3 (X:29, Y:0); #459 f3 (X:1084, Y:58); #460 f3 (X:118, Y:248); #461 f3 (X:136, Y:422); #462 f3 (X:118, Y:614); #463 f1 (X:20, Y:6); +108 item lain.', '2026-04-18 09:41:32', '2026-04-18 09:41:32'),
(36, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan fasilitas (115): #572 f3 (X:42, Y:17); #573 f3 (X:29, Y:0); #574 f3 (X:1084, Y:58); #575 f3 (X:118, Y:248); #576 f3 (X:136, Y:422); #577 f3 (X:118, Y:614); #578 f1 (X:20, Y:6); #579 f1 (X:53, Y:6); +107 item lain.', '2026-04-18 09:50:42', '2026-04-18 09:50:42'),
(37, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Login', 'User login ke sistem.', '2026-04-18 22:02:15', '2026-04-18 22:02:15'),
(38, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan blok (3): Blok A (X:116->106, Y:107->103); Blok B (X:108->111, Y:410->545); Blok C (X:417->761, Y:406->110) | Perubahan fasilitas (118): #2 f2 (X:537->548, Y:695->716); #687 f3 (X:428, Y:511); #688 f3 (X:504, Y:183); #689 f3 (X:19, Y:24); #690 f3 (X:30, Y:20); #691 f3 (X:1084, Y:58); #692 f3 (X:118, Y:248); #693 f3 (X:136, Y:422); +110 item lain.', '2026-04-18 22:14:07', '2026-04-18 22:14:07'),
(39, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan fasilitas (115): #804 f3 (X:19, Y:24); #805 f3 (X:30, Y:20); #806 f3 (X:1076, Y:26); #807 f3 (X:118, Y:248); #808 f3 (X:136, Y:422); #809 f3 (X:118, Y:614); #810 f1 (X:20, Y:6); #811 f1 (X:53, Y:6); +107 item lain.', '2026-04-18 22:25:13', '2026-04-18 22:25:13'),
(40, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan fasilitas (115): #919 f3 (X:45, Y:22); #920 f3 (X:30, Y:20); #921 f3 (X:1075, Y:24); #922 f3 (X:118, Y:248); #923 f3 (X:136, Y:422); #924 f3 (X:118, Y:614); #925 f1 (X:20, Y:6); #926 f1 (X:53, Y:6); +107 item lain.', '2026-04-18 22:34:46', '2026-04-18 22:34:46'),
(41, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan fasilitas (116): #687 f3 (X:428->405, Y:511->483); #1034 f3 (X:45, Y:22); #1035 f3 (X:30, Y:20); #1036 f3 (X:1075, Y:24); #1037 f3 (X:118, Y:248); #1038 f3 (X:136, Y:422); #1039 f3 (X:118, Y:614); #1040 f1 (X:20, Y:6); +108 item lain.', '2026-04-18 22:55:23', '2026-04-18 22:55:23'),
(42, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Login', 'User login ke sistem.', '2026-04-19 01:37:36', '2026-04-19 01:37:36'),
(43, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan fasilitas (118): #341 f3 (X:32->30, Y:324->325); #687 f3 (X:405->417, Y:483->494); #688 f3 (X:504->539, Y:183->186); #1149 f3 (X:45, Y:22); #1150 f3 (X:30, Y:20); #1151 f3 (X:1076, Y:29); #1152 f3 (X:118, Y:248); #1153 f3 (X:136, Y:422); +110 item lain.', '2026-04-19 01:38:22', '2026-04-19 01:38:22'),
(44, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan fasilitas (118): #341 f3 (X:30->31, Y:325->388); #687 f3 (X:417->406, Y:494->528); #688 f3 (X:539->481, Y:186->188); #1264 f3 (X:26, Y:30); #1265 f3 (X:30, Y:20); #1266 f3 (X:1076, Y:29); #1267 f3 (X:118, Y:248); #1268 f3 (X:136, Y:422); +110 item lain.', '2026-04-19 01:49:26', '2026-04-19 01:49:26'),
(45, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan fasilitas (117): #341 f3 (X:31->15, Y:388->365); #687 f3 (X:406->399, Y:528->497); #1379 f3 (X:26, Y:30); #1380 f3 (X:30, Y:20); #1381 f3 (X:1061, Y:29); #1382 f3 (X:118, Y:248); #1383 f3 (X:136, Y:422); #1384 f3 (X:118, Y:614); +109 item lain.', '2026-04-19 01:50:17', '2026-04-19 01:50:17'),
(46, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan blok (1): Blok C (X:761->792, Y:110->147) | Perubahan fasilitas (118): #341 f3 (X:15->29, Y:365->348); #687 f3 (X:399->423, Y:497->499); #688 f3 (X:481->511, Y:188->185); #1494 f3 (X:26, Y:30); #1495 f3 (X:30, Y:20); #1496 f3 (X:1058, Y:32); #1497 f3 (X:118, Y:248); #1498 f3 (X:136, Y:422); +110 item lain.', '2026-04-19 02:00:33', '2026-04-19 02:00:33'),
(47, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan fasilitas (116): #687 f3 (X:423->423, Y:499->478); #1609 f3 (X:26, Y:30); #1610 f3 (X:30, Y:7); #1611 f3 (X:1058, Y:32); #1612 f3 (X:118, Y:248); #1613 f3 (X:136, Y:422); #1614 f3 (X:118, Y:614); #1615 f1 (X:20, Y:6); +108 item lain.', '2026-04-19 02:33:21', '2026-04-19 02:33:21'),
(48, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan blok (1): Blok B (X:111->145, Y:545->468) | Perubahan fasilitas (115): #1724 f3 (X:26, Y:30); #1725 f3 (X:30, Y:7); #1726 f3 (X:1058, Y:32); #1727 f3 (X:118, Y:248); #1728 f3 (X:136, Y:422); #1729 f3 (X:118, Y:614); #1730 f1 (X:20, Y:6); #1731 f1 (X:53, Y:6); +107 item lain.', '2026-04-19 02:36:28', '2026-04-19 02:36:28'),
(49, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Tambah Blok', 'Menambah data blok #24 \"Colton Vargas\".', '2026-04-19 02:37:14', '2026-04-19 02:37:14'),
(50, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan blok (1): Colton Vargas (X:(kosong)->704, Y:(kosong)->463) | Perubahan fasilitas (115): #1839 f3 (X:26, Y:30); #1840 f3 (X:30, Y:7); #1841 f3 (X:1058, Y:32); #1842 f3 (X:118, Y:248); #1843 f3 (X:136, Y:422); #1844 f3 (X:118, Y:614); #1845 f1 (X:20, Y:6); #1846 f1 (X:53, Y:6); +107 item lain.', '2026-04-19 02:39:48', '2026-04-19 02:39:48'),
(51, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan blok (1): Blok C (X:792->785, Y:147->125) | Perubahan fasilitas (115): #1954 f3 (X:26, Y:30); #1955 f3 (X:30, Y:7); #1956 f3 (X:1058, Y:32); #1957 f3 (X:118, Y:248); #1958 f3 (X:136, Y:422); #1959 f3 (X:118, Y:614); #1960 f1 (X:20, Y:6); #1961 f1 (X:53, Y:6); +107 item lain.', '2026-04-19 02:39:54', '2026-04-19 02:39:54'),
(52, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan blok (1): Blok A (X:106->129, Y:103->117) | Perubahan fasilitas (116): #688 f3 (X:511->510, Y:185->182); #2069 f3 (X:26, Y:30); #2070 f3 (X:30, Y:7); #2071 f3 (X:1055, Y:32); #2072 f3 (X:118, Y:248); #2073 f3 (X:136, Y:422); #2074 f3 (X:118, Y:614); #2075 f1 (X:20, Y:6); +108 item lain.', '2026-04-19 03:08:35', '2026-04-19 03:08:35'),
(53, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan fasilitas (115): #2184 f3 (X:26, Y:30); #2185 f3 (X:35, Y:24); #2186 f3 (X:1055, Y:32); #2187 f3 (X:118, Y:248); #2188 f3 (X:136, Y:422); #2189 f3 (X:118, Y:614); #2190 f1 (X:20, Y:6); #2191 f1 (X:53, Y:6); +107 item lain.', '2026-04-19 03:19:39', '2026-04-19 03:19:39'),
(54, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Reset Password User', 'Reset password user #2 username \"admin\" ke password default.', '2026-04-19 03:20:38', '2026-04-19 03:20:38'),
(55, 2, 'Admin', 'admin', '127.0.0.1', NULL, NULL, 'Login', 'User login ke sistem.', '2026-04-19 03:20:48', '2026-04-19 03:20:48'),
(56, 2, 'Admin', 'admin', '127.0.0.1', NULL, NULL, 'Logout', 'User logout dari sistem.', '2026-04-19 03:35:55', '2026-04-19 03:35:55'),
(57, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Login', 'User login ke sistem.', '2026-04-19 07:22:15', '2026-04-19 07:22:15'),
(58, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan fasilitas (3): #1 f2 (X:538, Y:709); #2 f3 (X:33, Y:351); #3 f3 (X:409, Y:493).', '2026-04-19 07:36:49', '2026-04-19 07:36:49'),
(59, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan blok (1): Blok B (X:145->145, Y:468->456).', '2026-04-19 07:41:04', '2026-04-19 07:41:04'),
(60, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan fasilitas (1): #2 f3 (X:33->229, Y:351->374).', '2026-04-19 07:41:23', '2026-04-19 07:41:23'),
(61, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan fasilitas (1): #2 f3 (X:229->39, Y:374->360).', '2026-04-19 07:53:59', '2026-04-19 07:53:59'),
(62, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Tidak ada perubahan nilai.', '2026-04-19 07:54:09', '2026-04-19 07:54:09'),
(63, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Tidak ada perubahan nilai.', '2026-04-19 08:00:18', '2026-04-19 08:00:18'),
(64, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan fasilitas (2): #1 f2 (X:538->540, Y:709->694); #3 f3 (X:409->427, Y:493->490).', '2026-04-19 08:01:47', '2026-04-19 08:01:47'),
(65, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Tidak ada perubahan nilai.', '2026-04-19 08:02:20', '2026-04-19 08:02:20'),
(66, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Edit Posisi Blok', 'Mengatur posisi denah blok dari dashboard. Perubahan fasilitas (116): scene:road_top_main (X:26, Y:30); scene:road_left_side (X:35, Y:24); scene:road_right_side (X:1076, Y:27); scene:road_mid_top (X:118, Y:248); scene:road_mid_center (X:136, Y:422); scene:road_bottom (X:118, Y:614); scene:landmark_gatehouse (X:246, Y:318); scene:tree_top_0 (X:20, Y:6); +108 item lain.', '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(67, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Logout', 'User logout dari sistem.', '2026-04-19 08:49:36', '2026-04-19 08:49:36'),
(68, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Login', 'User login ke sistem.', '2026-04-19 09:01:08', '2026-04-19 09:01:08'),
(69, 1, 'Superadmin\r\n', 'superadmin', '127.0.0.1', NULL, NULL, 'Logout', 'User logout dari sistem.', '2026-04-19 09:01:21', '2026-04-19 09:01:21');

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
(11, 'Blok A', 'Blok utama dengan 10 plot', '#10B981', 129, 117, 15, '2026-04-11 10:32:08', '2026-04-19 03:08:35'),
(22, 'Blok B', 'Kuburan Premium', '#C12F2F', 145, 456, 15, '2026-04-12 16:11:01', '2026-04-19 07:41:04'),
(23, 'Blok C', NULL, '#A42382', 785, 125, 15, '2026-04-12 10:00:30', '2026-04-19 02:39:54'),
(24, 'Colton Vargas', 'Enim in ut duis quid', '#B53AF8', 704, 463, 8, '2026-04-19 02:37:14', '2026-04-19 02:39:48');

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
-- Table structure for table `facility`
--

CREATE TABLE `facility` (
  `facilityid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `facility_name` varchar(120) DEFAULT NULL,
  `facility_key` varchar(40) DEFAULT NULL,
  `icon_emoji` varchar(16) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facility`
--

INSERT INTO `facility` (`facilityid`, `name`, `picture`, `facility_name`, `facility_key`, `icon_emoji`, `created_at`, `updated_at`) VALUES
(1, 'Pohon', '', 'Pohon', 'pohon', 'T', '2026-04-17 23:13:53', '2026-04-17 23:13:53'),
(2, 'Pintu Masuk', '', 'Pintu Masuk', 'pintu_masuk', 'G', '2026-04-17 23:13:53', '2026-04-17 23:13:53'),
(3, 'Jalan', '', 'Jalan', 'jalan', 'R', '2026-04-17 23:13:53', '2026-04-17 23:13:53');

-- --------------------------------------------------------

--
-- Table structure for table `facility_map_items`
--

CREATE TABLE `facility_map_items` (
  `facility_map_itemid` bigint(20) UNSIGNED NOT NULL,
  `facility_id` int(10) UNSIGNED NOT NULL,
  `item_type` varchar(20) DEFAULT NULL,
  `scene_object_key` varchar(120) DEFAULT NULL,
  `map_x` int(11) NOT NULL,
  `map_y` int(11) NOT NULL,
  `map_width` int(11) DEFAULT NULL,
  `map_height` int(11) DEFAULT NULL,
  `map_rotation` decimal(8,2) DEFAULT NULL,
  `is_fixed` tinyint(1) NOT NULL DEFAULT 0,
  `is_removed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facility_map_items`
--

INSERT INTO `facility_map_items` (`facility_map_itemid`, `facility_id`, `item_type`, `scene_object_key`, `map_x`, `map_y`, `map_width`, `map_height`, `map_rotation`, `is_fixed`, `is_removed`, `created_at`, `updated_at`) VALUES
(1, 2, 'icon', NULL, 540, 694, 103, 93, 0.00, 1, 0, '2026-04-19 07:36:49', '2026-04-19 08:31:14'),
(2, 3, 'icon', NULL, 39, 360, 1105, 73, 0.00, 1, 0, '2026-04-19 07:36:49', '2026-04-19 08:31:14'),
(3, 3, 'icon', NULL, 427, 490, 335, 77, 90.31, 1, 0, '2026-04-19 07:36:49', '2026-04-19 08:31:14'),
(4, 3, 'scene', 'road_top_main', 26, 30, 1113, 61, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(5, 3, 'scene', 'road_left_side', 35, 24, 70, 695, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(6, 3, 'scene', 'road_right_side', 1076, 27, 68, 702, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(7, 3, 'scene', 'road_mid_top', 118, 248, NULL, NULL, 0.00, 1, 1, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(8, 3, 'scene', 'road_mid_center', 136, 422, NULL, NULL, 0.00, 1, 1, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(9, 3, 'scene', 'road_bottom', 118, 614, NULL, NULL, 0.00, 1, 1, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(10, 0, 'scene', 'landmark_gatehouse', 246, 318, NULL, NULL, 0.00, 1, 1, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(11, 1, 'scene', 'tree_top_0', 20, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(12, 1, 'scene', 'tree_top_1', 53, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(13, 1, 'scene', 'tree_top_2', 86, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(14, 1, 'scene', 'tree_top_3', 119, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(15, 1, 'scene', 'tree_top_4', 152, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(16, 1, 'scene', 'tree_top_5', 185, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(17, 1, 'scene', 'tree_top_6', 218, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(18, 1, 'scene', 'tree_top_7', 251, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(19, 1, 'scene', 'tree_top_8', 284, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(20, 1, 'scene', 'tree_top_9', 317, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(21, 1, 'scene', 'tree_top_10', 350, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(22, 1, 'scene', 'tree_top_11', 383, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(23, 1, 'scene', 'tree_top_12', 416, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(24, 1, 'scene', 'tree_top_13', 449, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(25, 1, 'scene', 'tree_top_14', 482, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(26, 1, 'scene', 'tree_top_15', 515, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(27, 1, 'scene', 'tree_top_16', 548, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(28, 1, 'scene', 'tree_top_17', 581, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(29, 1, 'scene', 'tree_top_18', 614, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(30, 1, 'scene', 'tree_top_19', 647, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(31, 1, 'scene', 'tree_top_20', 680, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(32, 1, 'scene', 'tree_top_21', 713, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(33, 1, 'scene', 'tree_top_22', 746, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(34, 1, 'scene', 'tree_top_23', 779, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(35, 1, 'scene', 'tree_top_24', 812, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(36, 1, 'scene', 'tree_top_25', 845, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(37, 1, 'scene', 'tree_top_26', 878, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(38, 1, 'scene', 'tree_top_27', 911, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(39, 1, 'scene', 'tree_top_28', 944, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(40, 1, 'scene', 'tree_top_29', 977, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(41, 1, 'scene', 'tree_top_30', 1010, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(42, 1, 'scene', 'tree_top_31', 1043, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(43, 1, 'scene', 'tree_top_32', 1076, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(44, 1, 'scene', 'tree_top_33', 1109, 6, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(45, 1, 'scene', 'tree_bottom_0', 20, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(46, 1, 'scene', 'tree_bottom_1', 53, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(47, 1, 'scene', 'tree_bottom_2', 86, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(48, 1, 'scene', 'tree_bottom_3', 119, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(49, 1, 'scene', 'tree_bottom_4', 152, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(50, 1, 'scene', 'tree_bottom_5', 185, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(51, 1, 'scene', 'tree_bottom_6', 218, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(52, 1, 'scene', 'tree_bottom_7', 251, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(53, 1, 'scene', 'tree_bottom_8', 284, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(54, 1, 'scene', 'tree_bottom_9', 317, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(55, 1, 'scene', 'tree_bottom_10', 350, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(56, 1, 'scene', 'tree_bottom_11', 383, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(57, 1, 'scene', 'tree_bottom_12', 416, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(58, 1, 'scene', 'tree_bottom_13', 449, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(59, 1, 'scene', 'tree_bottom_14', 482, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(60, 1, 'scene', 'tree_bottom_15', 515, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(61, 1, 'scene', 'tree_bottom_16', 548, 1278, NULL, NULL, 0.00, 1, 1, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(62, 1, 'scene', 'tree_bottom_17', 581, 1278, NULL, NULL, 0.00, 1, 1, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(63, 1, 'scene', 'tree_bottom_18', 614, 1278, NULL, NULL, 0.00, 1, 1, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(64, 1, 'scene', 'tree_bottom_19', 647, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(65, 1, 'scene', 'tree_bottom_20', 680, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(66, 1, 'scene', 'tree_bottom_21', 713, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(67, 1, 'scene', 'tree_bottom_22', 746, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(68, 1, 'scene', 'tree_bottom_23', 779, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(69, 1, 'scene', 'tree_bottom_24', 812, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(70, 1, 'scene', 'tree_bottom_25', 845, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(71, 1, 'scene', 'tree_bottom_26', 878, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(72, 1, 'scene', 'tree_bottom_27', 911, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(73, 1, 'scene', 'tree_bottom_28', 944, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(74, 1, 'scene', 'tree_bottom_29', 977, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(75, 1, 'scene', 'tree_bottom_30', 1010, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(76, 1, 'scene', 'tree_bottom_31', 1043, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(77, 1, 'scene', 'tree_bottom_32', 1076, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(78, 1, 'scene', 'tree_bottom_33', 1109, 738, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(79, 1, 'scene', 'tree_left_0', 6, 42, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(80, 1, 'scene', 'tree_right_0', 1158, 42, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(81, 1, 'scene', 'tree_left_1', 6, 76, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(82, 1, 'scene', 'tree_right_1', 1158, 76, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(83, 1, 'scene', 'tree_left_2', 6, 110, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(84, 1, 'scene', 'tree_right_2', 1158, 110, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(85, 1, 'scene', 'tree_left_3', 6, 144, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(86, 1, 'scene', 'tree_right_3', 1158, 144, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(87, 1, 'scene', 'tree_left_4', 6, 178, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(88, 1, 'scene', 'tree_right_4', 1158, 178, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(89, 1, 'scene', 'tree_left_5', 6, 212, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(90, 1, 'scene', 'tree_right_5', 1158, 212, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(91, 1, 'scene', 'tree_left_6', 6, 246, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(92, 1, 'scene', 'tree_right_6', 1158, 246, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(93, 1, 'scene', 'tree_left_7', 6, 280, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(94, 1, 'scene', 'tree_right_7', 1158, 280, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(95, 1, 'scene', 'tree_left_8', 6, 314, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(96, 1, 'scene', 'tree_right_8', 1158, 314, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(97, 1, 'scene', 'tree_left_9', 6, 348, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(98, 1, 'scene', 'tree_right_9', 1158, 348, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(99, 1, 'scene', 'tree_left_10', 6, 382, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(100, 1, 'scene', 'tree_right_10', 1158, 382, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(101, 1, 'scene', 'tree_left_11', 6, 416, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(102, 1, 'scene', 'tree_right_11', 1158, 416, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(103, 1, 'scene', 'tree_left_12', 6, 450, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(104, 1, 'scene', 'tree_right_12', 1158, 450, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(105, 1, 'scene', 'tree_left_13', 6, 484, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(106, 1, 'scene', 'tree_right_13', 1158, 484, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(107, 1, 'scene', 'tree_left_14', 6, 518, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(108, 1, 'scene', 'tree_right_14', 1158, 518, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(109, 1, 'scene', 'tree_left_15', 6, 552, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(110, 1, 'scene', 'tree_right_15', 1158, 552, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(111, 1, 'scene', 'tree_left_16', 6, 586, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(112, 1, 'scene', 'tree_right_16', 1158, 586, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(113, 1, 'scene', 'tree_left_17', 6, 620, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(114, 1, 'scene', 'tree_right_17', 1158, 620, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(115, 1, 'scene', 'tree_left_18', 6, 654, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(116, 1, 'scene', 'tree_right_18', 1158, 654, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(117, 1, 'scene', 'tree_left_19', 6, 688, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(118, 1, 'scene', 'tree_right_19', 1158, 688, 16, 16, 0.00, 1, 0, '2026-04-19 08:31:14', '2026-04-19 08:31:14'),
(119, 2, 'scene', 'entrance_main_gate', 880, 1254, NULL, NULL, 0.00, 1, 1, '2026-04-19 08:31:14', '2026-04-19 08:31:14');

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
(15, '2026_04_14_000004_add_map_coordinates_to_blocks_table', 8),
(16, '2026_04_18_000005_create_facility_table', 9),
(17, '2026_04_18_000006_create_facility_map_items_table', 10),
(18, '2026_04_18_000007_add_size_rotation_to_place_table', 11),
(19, '2026_04_18_000008_add_item_type_and_scene_key_to_place_table', 12),
(20, '2026_04_18_000009_add_is_removed_to_place_table', 13),
(21, '2026_04_18_000010_create_restore_data_table', 14),
(22, '2026_04_19_000011_add_size_rotation_to_facility_map_items_table', 15),
(23, '2026_04_19_000012_add_scene_columns_to_facility_map_items_table', 16);

-- --------------------------------------------------------

--
-- Table structure for table `place`
--
-- Error reading structure for table cemetry.place: #1932 - Table &#039;cemetry.place&#039; doesn&#039;t exist in engine
-- Error reading data for table cemetry.place: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `cemetry`.`place`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `restore_data`
--
-- Error reading structure for table cemetry.restore_data: #1932 - Table &#039;cemetry.restore_data&#039; doesn&#039;t exist in engine
-- Error reading data for table cemetry.restore_data: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `cemetry`.`restore_data`&#039; at line 1

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
('6QTfYuxqVWjg5T3u0hyELG91Onl3Q6ieushxuoWU', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZlduNmM4ZFFqY2ttc0xiNTlNak5DRTVxUFBXZ2J2V0swdDdtTFFIUSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1776614457),
('f3nLRXcMQ22ViYvQysLrn6mg8bTHPwOTGIhAeJHv', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMDBVZGY0bjlKWE1jOXFnbjcwb0xPNWdjQU1UekpHaUZvNVZqc0lzciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6OTM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tZWRpYS9kZWNlYXNlZC1waG90bz9wYXRoPXN5c3RlbS1sb2dvcyUyRmxvZ28tMjAyNjA0MTkxMDM0MDctTTg3aGpPLnBuZyI7czo1OiJyb3V0ZSI7czoyMDoibWVkaWEuZGVjZWFzZWQtcGhvdG8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776613764),
('og8yY30YmVjhNPFF7QYD7DBsVQl9bmnhvnxNEPOt', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiamt3bjZJdUhNT2d6cHF2ZDJBckw4STN3UHpESlAzYVVPWUs2a0Q1byI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1776614599),
('tsrjk2RAByJD0KWuSrSRWWIqhaPlLZQoQ1ITzkdh', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSzRoSFI3UDBKMU9CWFViek40MFFVSFlsdjU2NFJ2ZzNORlQ1SUVGTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776608527);

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
(1, 'Kuburan', 'storage/system-logos/logo-20260419103407-M87hjO.png', '021-555-0001', 'Manager', 'Alamat Sistem');

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
(2, 'admin', '$2y$12$eCLMVwnNcYwDgH3JMz5wvO63xGkUpxiDLHYPhnusB9NRJ272.MSHG', 2, NULL, NULL);

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
-- Indexes for table `facility`
--
ALTER TABLE `facility`
  ADD PRIMARY KEY (`facilityid`),
  ADD UNIQUE KEY `facility_facility_key_unique` (`facility_key`);

--
-- Indexes for table `facility_map_items`
--
ALTER TABLE `facility_map_items`
  ADD PRIMARY KEY (`facility_map_itemid`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `blocks`
--
ALTER TABLE `blocks`
  MODIFY `blockid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
-- AUTO_INCREMENT for table `facility`
--
ALTER TABLE `facility`
  MODIFY `facilityid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `facility_map_items`
--
ALTER TABLE `facility_map_items`
  MODIFY `facility_map_itemid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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
