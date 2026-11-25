-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 25, 2026 at 12:44 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `profile_personenl`
--

-- --------------------------------------------------------

--
-- Table structure for table `documentaries`
--

CREATE TABLE `documentaries` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `youtube_url` varchar(255) NOT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `documentaries`
--

INSERT INTO `documentaries` (`id`, `title`, `description`, `youtube_url`, `sort_order`, `created_at`, `updated_at`) VALUES
(2, 'বালি থেকে সিলিকন', 'The Government of Bangladesh has already declared the semiconductor industry a national priority sector. In January 2025, the National Semiconductor Taskforce, established under the Office of the Chief Adviser, identified three key priorities: skills development, policy support, and global connectivity.  Import duties, VAT, and advance taxes on semiconductor-related raw materials have been withdrawn until 2031. Additionally, businesses operating within high-tech parks are eligible for a 100% tax holiday for the first seven years.  However, Bangladesh\'s current semiconductor exports stand at only USD 8 million, compared to the target of USD 1 billion by 2030. Therefore, significant further initiatives are required to bridge this gap and achieve the targeted growth.', 'https://www.youtube.com/watch?v=NYjnVvdIH_0', 1, '2026-11-09 12:53:25', '2026-11-09 12:53:25'),
(3, '3D Animated House Design – Two Rooms (12 ft × 12 ft Each). (Claude.ai)', 'An AI-powered engineering solution platform designed to solve real-world engineering challenges using ChatGPT, Claude AI, Grok, Gemini, and other advanced AI tools. Supports civil, electrical, mechanical, and computer engineering through 3D design, architectural visualization, structural calculations, coding, technical analysis, project planning, and innovative problem-solving.', 'https://youtu.be/44_r6tsyvRQ', 2, '2026-11-09 13:05:30', '2026-11-09 13:05:30');

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

CREATE TABLE `education` (
  `id` bigint UNSIGNED NOT NULL,
  `institution` varchar(255) NOT NULL,
  `degree` varchar(255) DEFAULT NULL,
  `field` varchar(255) DEFAULT NULL,
  `start_year` varchar(10) DEFAULT NULL,
  `end_year` varchar(10) DEFAULT NULL,
  `description` text,
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `education`
--

INSERT INTO `education` (`id`, `institution`, `degree`, `field`, `start_year`, `end_year`, `description`, `sort_order`, `created_at`, `updated_at`) VALUES
(2, 'Dinajpur Polytechnic Institute', 'Diploma-in- Computer', 'Computer Technology', '2008', '2012', NULL, 1, '2026-11-09 12:36:58', '2026-11-09 12:36:58'),
(3, 'Dhaka University of Engineering & Technology, Gazipur.', 'BSc. in CSE', 'Computer Science and Engineering.', '2012-13', '2015-16', NULL, 2, '2026-11-09 12:43:55', '2026-11-09 12:43:55');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_items`
--

CREATE TABLE `gallery_items` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `gallery_items`
--

INSERT INTO `gallery_items` (`id`, `title`, `image`, `sort_order`, `created_at`, `updated_at`) VALUES
(5, NULL, 'media/12', 1, '2026-11-13 11:16:36', '2026-11-13 11:16:36');

-- --------------------------------------------------------

--
-- Table structure for table `media_files`
--

CREATE TABLE `media_files` (
  `id` bigint UNSIGNED NOT NULL,
  `mime` varchar(100) NOT NULL,
  `data` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `headline` varchar(255) DEFAULT NULL,
  `bio` text,
  `photo` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `youtube_channel_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `name`, `headline`, `bio`, `photo`, `email`, `phone`, `location`, `youtube_channel_url`, `created_at`, `updated_at`) VALUES
(1, 'Md. Julfikar Ali Saju Sarker', 'B.Sc. in CSE, DUET | Laravel & PHP Web Developer | MySQL | AI & Technology Enthusiast', 'Laravel Web Developer specializing in Laravel, and MySQL. Experienced in developing responsive, secure, and database-driven web application.', 'media/5', 'esaju.cse@gmail.com', '01746973136, 01799549504', 'Aftabnagar, Badda, Dhaka-1212', 'https://www.youtube.com/channel/UCqrhh1w24VdYO7VczUCPauw', '2026-11-09 17:24:19', '2026-11-13 09:57:21');

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

CREATE TABLE `slides` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `slides`
--

INSERT INTO `slides` (`id`, `title`, `caption`, `image`, `sort_order`, `created_at`, `updated_at`) VALUES
(12, NULL, NULL, 'media/9', 1, '2026-11-13 11:13:33', '2026-11-13 11:13:33'),
(13, NULL, NULL, 'media/10', 2, '2026-11-13 11:13:56', '2026-11-13 11:13:56'),
(14, NULL, NULL, 'media/11', 3, '2026-11-13 11:14:50', '2026-11-13 11:14:50');

-- --------------------------------------------------------

--
-- Table structure for table `social_links`
--

CREATE TABLE `social_links` (
  `id` bigint UNSIGNED NOT NULL,
  `platform` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `social_links`
--

INSERT INTO `social_links` (`id`, `platform`, `url`, `sort_order`, `created_at`, `updated_at`) VALUES
(3, 'Facebook', 'https://www.facebook.com/ja.saju.56', 1, '2026-11-09 12:17:16', '2026-11-09 12:17:16'),
(4, 'Facebook page', 'https://www.facebook.com/profile.php?id=61596189968851', 2, '2026-11-09 12:17:59', '2026-11-09 12:17:59'),
(5, 'Departmental work', 'https://grid-library.wasmer.app/', 3, '2026-11-09 12:34:11', '2026-11-09 12:34:11'),
(6, 'Business works', 'https://digitalaisolutions.wasmer.app', 4, '2026-11-09 12:35:15', '2026-11-09 12:35:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@example.com', NULL, '$2y$10$RazE0tggGGc0lg4Iw3.To.wVd/yh4gWTv9ghPeG4bgEjl/cYNrH9u', NULL, '2026-11-09 17:24:19', '2026-11-09 17:24:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `documentaries`
--
ALTER TABLE `documentaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery_items`
--
ALTER TABLE `gallery_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media_files`
--
ALTER TABLE `media_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slides`
--
ALTER TABLE `slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_links`
--
ALTER TABLE `social_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `documentaries`
--
ALTER TABLE `documentaries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `education`
--
ALTER TABLE `education`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gallery_items`
--
ALTER TABLE `gallery_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `media_files`
--
ALTER TABLE `media_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `slides`
--
ALTER TABLE `slides`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `social_links`
--
ALTER TABLE `social_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
