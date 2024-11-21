-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2024 at 02:37 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_bahrululum`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun_admins`
--

CREATE TABLE `akun_admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `akun_admins`
--

INSERT INTO `akun_admins` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin12345');

-- --------------------------------------------------------

--
-- Table structure for table `akun_users`
--

CREATE TABLE `akun_users` (
  `user_nis` char(10) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `akun_users`
--

INSERT INTO `akun_users` (`user_nis`, `username`, `password`, `created_at`) VALUES
('199946', 'Ridho', 'ridho123', '2024-11-21 17:01:08'),
('XX', 'Fulan', '12345', '2024-11-16 19:20:11');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id_question` int(10) UNSIGNED NOT NULL,
  `id_quiz` int(10) UNSIGNED NOT NULL,
  `question_text` longtext NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`options`)),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id_question`, `id_quiz`, `question_text`, `options`, `created_at`) VALUES
(1, 5, 'Hewan mana yang bisa terbang tinggi?', '{\"options\":{\"a\":\"burung\",\"b\":\"ayam\",\"c\":\"bebek\",\"d\":\"ular\"},\"answer\":\"a\"}', '2024-11-21 20:26:52'),
(2, 5, 'Makan pakai yang benar menggunakan apa?', '{\"options\":{\"a\":\"sendok\",\"b\":\"nasi\",\"c\":\"tangan\",\"d\":\"kaki\"},\"answer\":\"c\"}', '2024-11-21 20:59:30');

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `id_quiz` int(10) UNSIGNED NOT NULL,
  `subject_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`id_quiz`, `subject_id`, `title`, `created_at`) VALUES
(1, 1, 'Latihan-1 Tajwid', '2024-11-17 13:04:47'),
(2, 2, 'Latihan-1 Aljabar', '2024-11-17 13:04:47'),
(3, 3, 'Latihan-1 Puisi', '2024-11-17 13:04:47'),
(4, 4, 'Latihan-1 Past Tense', '2024-11-17 13:04:47'),
(5, 5, 'Latihan-1 Organ Manusia', '2024-11-17 13:04:47'),
(6, 6, 'Latihan-1 G30S PKI', '2024-11-17 13:04:47'),
(7, 3, 'Latihan-2 Teks Biografi', '2024-11-20 18:59:38'),
(12, 3, 'Latihan-3 Teks Prosedur', '2024-11-21 12:43:39');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_score`
--

CREATE TABLE `quiz_score` (
  `id_score` int(10) UNSIGNED NOT NULL,
  `user_nis` char(10) NOT NULL,
  `id_quiz` int(10) UNSIGNED NOT NULL,
  `score` int(10) UNSIGNED NOT NULL,
  `completed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ranking`
--

CREATE TABLE `ranking` (
  `id_ranking` int(10) UNSIGNED NOT NULL,
  `id_quiz` int(10) UNSIGNED NOT NULL,
  `user_nis` char(10) NOT NULL,
  `rank` int(10) UNSIGNED NOT NULL,
  `id_score` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id_session` int(10) UNSIGNED NOT NULL,
  `user_nis` char(10) NOT NULL,
  `is_active` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject_id` int(10) UNSIGNED NOT NULL,
  `thumbnail` varchar(100) NOT NULL,
  `subject_name` varchar(45) NOT NULL,
  `subject_desc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject_id`, `thumbnail`, `subject_name`, `subject_desc`) VALUES
(1, '487524825-Pendidikan Agama Islam.png', 'Pendidikan Agama Islam', 'Mata pelajaran ini membimbing siswa memahami ajaran Islam, meliputi akidah, ibadah, akhlak, dan sejarah Islam. Siswa diajarkan nilai-nilai keislaman untuk diterapkan dalam kehidupan sehari-hari sesuai dengan tuntunan Al-Qur\'an dan Hadis.'),
(2, '647129532-Matematika.png', 'Matematika', 'Matematika mengembangkan kemampuan logika, analisis, dan pemecahan masalah. Siswa belajar konsep bilangan, aljabar, geometri, statistik, serta penerapannya dalam kehidupan sehari-hari dan bidang sains.'),
(3, 'Bahasa Indonesia.png', 'Bahasa Indonesia', 'Mata pelajaran ini bertujuan untuk mengembangkan kemampuan berbahasa yang meliputi keterampilan membaca, menulis, berbicara, dan mendengarkan. Siswa diajarkan memahami, menganalisis, serta menghasilkan teks dengan ragam genre dan konteks budaya Indonesia.'),
(4, 'Bahasa Inggris.png', 'Bahasa Inggris', 'Bahasa Inggris berfokus pada penguasaan kemampuan berkomunikasi dalam bahasa internasional ini, mencakup keterampilan berbicara, mendengarkan, membaca, dan menulis. Pembelajaran juga meliputi pemahaman budaya negara-negara berbahasa Inggris.'),
(5, 'IPA.png', 'IPA', 'Mata pelajaran ini mencakup pembelajaran tentang fenomena alam, meliputi biologi, fisika, dan kimia. IPA bertujuan mengasah kemampuan observasi, eksperimen, dan analisis ilmiah terhadap lingkungan sekitar.'),
(6, 'IPS.png', 'IPS', 'IPS mempelajari interaksi manusia dengan lingkungan sosial, sejarah, ekonomi, dan geografi. Mata pelajaran ini membantu siswa memahami dinamika masyarakat, hubungan antarindividu, serta pengelolaan sumber daya.');

-- --------------------------------------------------------

--
-- Table structure for table `user_answer`
--

CREATE TABLE `user_answer` (
  `id_answer` int(10) UNSIGNED NOT NULL,
  `user_nis` char(10) NOT NULL,
  `id_quiz` int(10) UNSIGNED NOT NULL,
  `id_question` int(10) UNSIGNED NOT NULL,
  `answered_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun_admins`
--
ALTER TABLE `akun_admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `akun_users`
--
ALTER TABLE `akun_users`
  ADD PRIMARY KEY (`user_nis`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id_question`),
  ADD KEY `id_quiz_idx` (`id_quiz`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id_quiz`),
  ADD KEY `subject_id_idx` (`subject_id`);

--
-- Indexes for table `quiz_score`
--
ALTER TABLE `quiz_score`
  ADD PRIMARY KEY (`id_score`),
  ADD UNIQUE KEY `user_nis_UNIQUE` (`user_nis`);

--
-- Indexes for table `ranking`
--
ALTER TABLE `ranking`
  ADD PRIMARY KEY (`id_ranking`),
  ADD UNIQUE KEY `user_nis_UNIQUE` (`user_nis`),
  ADD KEY `fk_ranking_id_score_idx` (`id_score`),
  ADD KEY `fk_ranking_id_quiz_idx` (`id_quiz`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id_session`),
  ADD UNIQUE KEY `user_nis_UNIQUE` (`user_nis`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subject_id`),
  ADD UNIQUE KEY `subject_name_UNIQUE` (`subject_name`);

--
-- Indexes for table `user_answer`
--
ALTER TABLE `user_answer`
  ADD PRIMARY KEY (`id_answer`),
  ADD UNIQUE KEY `user_nis_UNIQUE` (`user_nis`),
  ADD KEY `id_quiz_idx` (`id_quiz`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id_question` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id_quiz` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `subject_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `id_quiz` FOREIGN KEY (`id_quiz`) REFERENCES `quiz` (`id_quiz`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `subject_id` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quiz_score`
--
ALTER TABLE `quiz_score`
  ADD CONSTRAINT `fk_quiz_score_id_quiz` FOREIGN KEY (`id_quiz`) REFERENCES `quiz` (`id_quiz`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_quiz_score_user_nis` FOREIGN KEY (`user_nis`) REFERENCES `akun_users` (`user_nis`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ranking`
--
ALTER TABLE `ranking`
  ADD CONSTRAINT `fk_ranking_id_quiz` FOREIGN KEY (`id_quiz`) REFERENCES `quiz` (`id_quiz`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ranking_user_nis` FOREIGN KEY (`user_nis`) REFERENCES `akun_users` (`user_nis`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `user_nis` FOREIGN KEY (`user_nis`) REFERENCES `akun_users` (`user_nis`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_answer`
--
ALTER TABLE `user_answer`
  ADD CONSTRAINT `fk_user_answer_quiz` FOREIGN KEY (`id_quiz`) REFERENCES `quiz` (`id_quiz`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_answer_user_nis` FOREIGN KEY (`user_nis`) REFERENCES `akun_users` (`user_nis`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
