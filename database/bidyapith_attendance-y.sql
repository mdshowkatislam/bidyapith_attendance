-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 23, 2025 at 12:41 PM
-- Server version: 9.1.0
-- PHP Version: 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bidyapith_attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_statuses`
--

DROP TABLE IF EXISTS `attendance_statuses`;
CREATE TABLE IF NOT EXISTS `attendance_statuses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `short_form` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendance_statuses_name_unique` (`name`),
  UNIQUE KEY `attendance_statuses_short_form_unique` (`short_form`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `attendance_statuses`
--

INSERT INTO `attendance_statuses` (`id`, `name`, `short_form`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Present', 'P', 1, '2025-07-20 04:40:09', '2025-07-20 04:40:09'),
(3, 'Absent', 'A', 1, '2025-07-20 04:41:28', '2025-07-20 04:41:28'),
(4, 'Late In', 'LI', 1, '2025-07-20 04:41:48', '2025-07-20 04:41:48'),
(5, 'Early Out', 'EO', 1, '2025-07-20 04:42:06', '2025-07-20 04:42:06'),
(10, 'On Leave', 'OL', 0, '2025-07-20 05:42:00', '2025-07-20 05:42:00'),
(11, 'Over Time', 'OT', 0, '2025-07-20 05:42:20', '2025-07-20 05:42:20');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
CREATE TABLE IF NOT EXISTS `branches` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` bigint DEFAULT NULL,
  `branch_code` bigint NOT NULL,
  `branch_name_en` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `branch_name_bn` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `branch_location` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `head_of_branch_id` bigint DEFAULT NULL,
  `eiin` bigint DEFAULT NULL,
  `rec_status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branches_branch_code_unique` (`branch_code`),
  UNIQUE KEY `branches_uid_unique` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `uid`, `branch_code`, `branch_name_en`, `branch_name_bn`, `branch_location`, `head_of_branch_id`, `eiin`, `rec_status`, `created_at`, `updated_at`) VALUES
(1, 1842856565896840, 100, 'Badda branch', 'badda ব্রাঞ্চ', 'badda,dhaka', 1, 123, 1, '2025-09-10 00:16:15', '2025-09-10 00:16:15'),
(2, 1842857540409009, 101, 'uttara branch', 'uttara shakha', 'uttara,sector-1', 2, 123, 1, '2025-09-10 00:31:45', '2025-09-10 02:28:35'),
(7, 1843232861008931, 12, 'sa', 'asd', 'sd', 12, 123, 2, '2025-09-14 03:57:18', '2025-09-14 04:05:08'),
(10, 1843329368773023, 91, 'dda', 'sss', 'sss', 11, 111, 1, '2025-09-15 05:31:15', '2025-09-15 05:31:15');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `check_in_outs`
--

DROP TABLE IF EXISTS `check_in_outs`;
CREATE TABLE IF NOT EXISTS `check_in_outs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `log_id` int DEFAULT NULL,
  `machine_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `in_time` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `out_time` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `check_in_outs_user_id_date_unique` (`user_id`,`date`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `check_in_outs`
--

INSERT INTO `check_in_outs` (`id`, `user_id`, `log_id`, `machine_id`, `date`, `in_time`, `out_time`, `status`, `created_at`, `updated_at`) VALUES
(1, 58, NULL, NULL, '2025-07-22', '12:50', '16:00', 1, NULL, NULL),
(2, 55, NULL, NULL, '2025-07-22', '09:16', '16:00', 1, NULL, NULL),
(3, 57, NULL, NULL, '2025-07-22', '10:05', '16:00', 1, NULL, NULL),
(4, 62, NULL, NULL, '2025-07-22', '12:45', '16:30', 1, NULL, NULL),
(5, 64, NULL, NULL, NULL, '', NULL, 1, NULL, NULL),
(6, 51, NULL, NULL, '2025-07-22', '12:50', '16:30', 1, NULL, NULL),
(7, 52, NULL, 'mac2023', '0000-00-00', NULL, NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `db_location_settings`
--

DROP TABLE IF EXISTS `db_location_settings`;
CREATE TABLE IF NOT EXISTS `db_location_settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` tinyint NOT NULL,
  `db_location` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `db_location_settings_key_unique` (`key`),
  UNIQUE KEY `db_location_settings_value_unique` (`value`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `db_location_settings`
--

INSERT INTO `db_location_settings` (`id`, `key`, `value`, `db_location`, `created_at`, `updated_at`) VALUES
(1, 'sync_time', 4, 'D:\\xamp-8.2\\anonymous\\incoming', '2025-07-20 04:08:57', '2025-09-24 05:56:58');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

DROP TABLE IF EXISTS `designations`;
CREATE TABLE IF NOT EXISTS `designations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` bigint NOT NULL,
  `designation_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `designations_uid_unique` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `uid`, `designation_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Head Teacher nafi', NULL, '2025-09-29 02:33:19'),
(2, 2, 'Assistant Teacher onu', NULL, '2025-09-29 02:36:06'),
(3, 3, 'Trade Instructor 1', NULL, '2025-09-29 02:53:07'),
(4, 4, 'Senior Asst. Teacher1', NULL, '2025-09-29 02:53:18'),
(5, 5, 'Lab Assistant (Electronics)', NULL, NULL),
(11, 11, 'Principal', NULL, NULL),
(12, 12, 'Vice Principal', NULL, NULL),
(13, 13, 'Professor', NULL, NULL),
(14, 14, 'Associate Professor ', NULL, NULL),
(15, 15, 'Assistant Professor ', NULL, NULL),
(16, 16, 'Lecturer ', NULL, NULL),
(17, 17, 'Madrasha Super', NULL, NULL),
(18, 18, 'Computer Operator', NULL, NULL),
(19, 1844236228821457, 'abul-2', '2025-09-25 05:45:24', '2025-09-29 02:51:58'),
(20, 1844586501117341, 'nafi', '2025-09-29 02:32:50', '2025-09-29 02:32:50'),
(21, 1844586641274988, 'onu', '2025-09-29 02:35:04', '2025-09-29 02:35:04'),
(22, 1844587824323138, 'poty', '2025-09-29 02:53:52', '2025-09-29 02:53:52'),
(23, 1844589514254963, 'ops', '2025-09-29 03:20:44', '2025-09-29 03:20:44'),
(24, 1844591509526436, 'edewd', '2025-09-29 03:52:26', '2025-09-29 03:52:26');

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

DROP TABLE IF EXISTS `districts`;
CREATE TABLE IF NOT EXISTS `districts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` bigint NOT NULL,
  `division_id` bigint DEFAULT NULL,
  `district_name_en` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district_name_bn` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `districts_uid_unique` (`uid`),
  KEY `districts_division_id_foreign` (`division_id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `uid`, `division_id`, `district_name_en`, `district_name_bn`, `created_at`, `updated_at`) VALUES
(1, 1818754494274317, 3, 'Dhaka', 'ঢাকা', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(2, 1818754494301107, 3, 'Faridpur', 'ফরিদপুর', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(3, 1818754494307903, 3, 'Gazipur', 'গাজীপুর', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(4, 1818754494314948, 3, 'Gopalganj', 'গোপালগঞ্জ', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(5, 1818754494324313, 8, 'Jamalpur', 'জামালপুর', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(6, 1818754494336158, 3, 'Kishoreganj', 'কিশোরগঞ্জ', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(7, 1818754494345431, 3, 'Madaripur', 'মাদারীপুর', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(8, 1818754494355597, 3, 'Manikganj', 'মানিকগঞ্জ', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(9, 1818754494363594, 3, 'Munshiganj', 'মুন্সিগঞ্জ', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(10, 1818754494374466, 8, 'Mymensingh', 'ময়মনসিংহ', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(11, 1818754494384124, 3, 'Narayanganj', 'নারায়ণগঞ্জ', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(12, 1818754494392205, 3, 'Narsingdi', 'নরসিংদী', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(13, 1818754494401035, 8, 'Netrokona', 'নেত্রকোণা', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(14, 1818754494415610, 3, 'Rajbari', 'রাজবাড়ী', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(15, 1818754494421333, 3, 'Shariatpur', 'শরীয়তপুর', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(16, 1818754494429741, 8, 'Sherpur', 'শেরপুর', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(17, 1818754494436356, 3, 'Tangail', 'টাঙ্গাইল', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(18, 1818754494441154, 5, 'Bogura', 'বগুড়া', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(19, 1818754494448893, 5, 'Joypurhat', 'জয়পুরহাট', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(20, 1818754494455284, 5, 'Naogaon', 'নওগাঁ', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(21, 1818754494462404, 5, 'Natore', 'নাটোর', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(22, 1818754494467412, 5, 'Nawabganj', 'নবাবগঞ্জ', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(23, 1818754494473956, 5, 'Pabna', 'পাবনা', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(24, 1818754494479026, 5, 'Rajshahi', 'রাজশাহী', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(25, 1818754494487144, 5, 'Sirajgonj', 'সিরাজগঞ্জ', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(26, 1818754494492250, 6, 'Dinajpur', 'দিনাজপুর', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(27, 1818754494498847, 6, 'Gaibandha', 'গাইবান্ধা', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(28, 1818754494506332, 6, 'Kurigram', 'কুড়িগ্রাম', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(29, 1818754494512713, 6, 'Lalmonirhat', 'লালমনিরহাট', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(30, 1818754494520411, 6, 'Nilphamari', 'নীলফামারী', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(31, 1818754494527153, 6, 'Panchagarh', 'পঞ্চগড়', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(32, 1818754494536005, 6, 'Rangpur', 'রংপুর', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(33, 1818754494542190, 6, 'Thakurgaon', 'ঠাকুরগাঁও', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(34, 1818754494550715, 1, 'Barguna', 'বরগুনা', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(35, 1818754494556420, 1, 'Barishal', 'বরিশাল', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(36, 1818754494563790, 1, 'Bhola', 'ভোলা', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(37, 1818754494570992, 1, 'Jhalokati', 'ঝালকাঠি', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(38, 1818754494577361, 1, 'Patuakhali', 'পটুয়াখালী', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(39, 1818754494583646, 1, 'Pirojpur', 'পিরোজপুর', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(40, 1818754494593459, 2, 'Bandarban', 'বান্দরবান', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(41, 1818754494600666, 2, 'Brahmanbaria', 'ব্রাহ্মণবাড়িয়া', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(42, 1818754494606145, 2, 'Chandpur', 'চাঁদপুর', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(43, 1818754494615089, 2, 'Chattogram', 'চট্টগ্রাম', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(44, 1818754494620602, 2, 'Cumilla', 'কুমিল্লা', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(45, 1818754494625681, 2, 'Cox\'s Bazar', 'কক্স বাজার', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(46, 1818754494632288, 2, 'Feni', 'ফেনী', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(47, 1818754494640621, 2, 'Khagrachari', 'খাগড়াছড়ি', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(48, 1818754494647530, 2, 'Lakshmipur', 'লক্ষ্মীপুর', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(49, 1818754494655208, 2, 'Noakhali', 'নোয়াখালী', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(50, 1818754494662103, 2, 'Rangamati', 'রাঙ্গামাটি', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(51, 1818754494667372, 7, 'Habiganj', 'হবিগঞ্জ', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(52, 1818754494673570, 7, 'Maulvibazar', 'মৌলভীবাজার', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(53, 1818754494678765, 7, 'Sunamganj', 'সুনামগঞ্জ', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(54, 1818754494685258, 7, 'Sylhet', 'সিলেট', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(55, 1818754494690418, 4, 'Bagerhat', 'বাগেরহাট', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(56, 1818754494698281, 4, 'Chuadanga', 'চুয়াডাঙ্গা', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(57, 1818754494705680, 4, 'Jashore', 'যশোর', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(58, 1818754494717228, 4, 'Jhenaidah', 'ঝিনাইদহ', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(59, 1818754494723624, 4, 'Khulna', 'খulনা', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(60, 1818754494733217, 4, 'Kushtia', 'কুষ্টিয়া', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(61, 1818754494745385, 4, 'Magura', 'মাগুরা', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(62, 1818754494756334, 4, 'Meherpur', 'মেহেরপুর', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(63, 1818754494766236, 4, 'Narail', 'নড়াইল', '2024-12-17 23:24:09', '2025-09-25 09:24:58'),
(64, 1818754494777020, 4, 'Satkhira', 'সাতক্ষীরা', '2024-12-17 23:24:09', '2025-09-25 09:24:58');

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

DROP TABLE IF EXISTS `divisions`;
CREATE TABLE IF NOT EXISTS `divisions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` bigint NOT NULL,
  `division_name_en` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `division_name_bn` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `divisions_uid_unique` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`id`, `uid`, `division_name_en`, `division_name_bn`, `created_at`, `updated_at`) VALUES
(1, 1818754028556574, 'Barishal', 'বরিশাল', '2024-12-17 23:16:44', '2025-09-25 09:06:13'),
(2, 1818754028594249, 'Chattogram', 'চট্টগ্রাম', '2024-12-17 23:16:44', '2025-09-25 09:06:13'),
(3, 1818754028605202, 'Dhaka', 'ঢাকা', '2024-12-17 23:16:44', '2025-09-25 09:06:13'),
(4, 1818754028612320, 'Khulna', 'খুলনা', '2024-12-17 23:16:44', '2025-09-25 09:06:13'),
(5, 1818754028668735, 'Rajshahi', 'রাজশাহী', '2024-12-17 23:16:45', '2025-09-25 09:06:13'),
(6, 1818754028674153, 'Rangpur', 'রংপুর', '2024-12-17 23:16:45', '2025-09-25 09:06:13'),
(7, 1818754028683614, 'Sylhet', 'সিলেট', '2024-12-17 23:16:45', '2025-09-25 09:06:13'),
(8, 1818754028689730, 'Mymensingh', 'ময়মনসিংহ', '2024-12-17 23:16:45', '2025-09-25 09:06:13');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `eiin` bigint DEFAULT NULL,
  `caid` bigint DEFAULT NULL,
  `profile_id` int NOT NULL COMMENT 'emp_id',
  `person_type` tinyint NOT NULL COMMENT '1 = Teacher, 2 = Staff, 3 = Student',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `designation_id` int DEFAULT NULL,
  `designation_name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_profile_id_unique` (`profile_id`),
  UNIQUE KEY `employees_caid_unique` (`caid`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `eiin`, `caid`, `profile_id`, `person_type`, `created_at`, `updated_at`, `designation_id`, `designation_name`) VALUES
(1, 134172, 113417220240078, 64, 1, '2025-10-07 05:53:06', '2025-10-07 05:53:06', 10, 'Senior Teacher'),
(2, 134172, 113417220240079, 58, 1, '2025-10-07 06:25:42', '2025-10-07 06:25:42', 8, 'Senior Assistant Teacher'),
(3, 134172, 113417220240080, 55, 1, '2025-10-07 06:26:35', '2025-10-07 06:26:35', 7, 'Assistant Head Teacher'),
(4, 134172, 113417220240072, 62, 1, '2025-10-07 06:31:20', '2025-10-07 06:31:20', 3, 'Senior Assistant Teacher'),
(5, 134172, 6340020001, 59, 2, '2025-10-07 06:32:28', '2025-10-07 06:32:28', NULL, NULL),
(6, 134172, 6481220001, 57, 2, '2025-10-07 06:33:55', '2025-10-07 06:33:55', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_group`
--

DROP TABLE IF EXISTS `employee_group`;
CREATE TABLE IF NOT EXISTS `employee_group` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_id` bigint UNSIGNED NOT NULL,
  `employee_emp_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_group_employee_emp_id_unique` (`employee_emp_id`),
  KEY `employee_group_group_id_foreign` (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `employee_group`
--

INSERT INTO `employee_group` (`id`, `group_id`, `employee_emp_id`, `created_at`, `updated_at`) VALUES
(1, 1, 62, NULL, NULL),
(2, 1, 55, NULL, NULL),
(3, 1, 58, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci,
  `branch_uid` bigint UNSIGNED NOT NULL,
  `shift_uid` bigint UNSIGNED NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `flexible_in_time` int DEFAULT NULL,
  `flexible_out_time` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_group_name_unique` (`group_name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `group_name`, `description`, `branch_uid`, `shift_uid`, `status`, `flexible_in_time`, `flexible_out_time`, `created_at`, `updated_at`) VALUES
(1, 'Morning Shift Teachers', 'Teachers assigned to the early morning shift', 1845401593211652, 1835688947083507, 1, 10, 15, '2025-10-15 00:09:25', '2025-10-15 00:09:25');

-- --------------------------------------------------------

--
-- Table structure for table `group_special_workdays`
--

DROP TABLE IF EXISTS `group_special_workdays`;
CREATE TABLE IF NOT EXISTS `group_special_workdays` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_id` bigint UNSIGNED NOT NULL,
  `special_workingday_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_special_workdays_group_id_special_workingday_id_unique` (`group_id`,`special_workingday_id`),
  KEY `group_special_workdays_special_workingday_id_foreign` (`special_workingday_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `group_special_workdays`
--

INSERT INTO `group_special_workdays` (`id`, `group_id`, `special_workingday_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(5, 1, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

DROP TABLE IF EXISTS `holidays`;
CREATE TABLE IF NOT EXISTS `holidays` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `holiday_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `holidays_holiday_name_start_date_unique` (`holiday_name`,`start_date`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `holiday_name`, `start_date`, `end_date`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Janmashtami', '2025-08-16', NULL, '3rd half', 1, '2025-07-22 06:03:20', '2025-07-22 06:03:20'),
(13, 'ccc', '2025-09-24', '2025-09-25', 'ccc', 1, '2025-09-23 02:57:05', '2025-09-23 02:57:05'),
(14, 'ddd', '2025-09-24', '2025-09-26', 'ddd', 1, '2025-09-23 03:02:24', '2025-09-23 03:02:24'),
(15, 'eee', '2025-09-24', '2025-09-26', 'eee', 1, '2025-09-23 03:04:36', '2025-09-23 03:04:36'),
(16, 'ccccccccccc', '2025-09-24', NULL, 'cccccccccc', 0, '2025-09-23 03:21:21', '2025-09-23 04:37:21'),
(17, 'nafi', '2025-09-24', '2025-09-25', 'nafi', 0, '2025-09-23 03:24:32', '2025-09-23 04:36:57'),
(18, 'test', '2025-09-24', '2025-09-26', 'test', 0, '2025-09-23 03:29:14', '2025-09-23 04:34:54'),
(19, 'test 1', '2025-09-24', NULL, 'test 1', 0, '2025-09-23 03:33:50', '2025-09-23 04:34:20'),
(32, 'xxxx', '2025-09-22', NULL, 'xxxxx', 1, '2025-09-23 04:43:05', '2025-09-24 02:08:24'),
(34, 'erfrg', '2025-09-04', NULL, 'regreg', 1, '2025-09-29 03:57:20', '2025-09-29 03:57:20'),
(33, 'Boro din', '2025-12-25', NULL, 'Boro din', 0, '2025-09-27 23:03:25', '2025-09-27 23:03:46');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_07_10_044942_create_personal_access_tokens_table', 1),
(64, '2025_09_03_113209_create_branches_table', 30),
(42, '2025_07_09_193053_work_days', 23),
(47, '2025_07_10_110008_employeesOld', 25),
(76, '2025_07_09_195112_groups', 38),
(13, '2025_07_13_085205_work_day_group', 5),
(24, '2025_07_16_060939_special_working_days', 12),
(25, '2025_07_16_063955_create_group_special_workdays_table', 13),
(38, '2025_07_22_053601_create_check_in_outs_table', 21),
(32, '2025_07_20_042610_create_db_location_settings_table', 17),
(33, '2025_07_20_101539_create_attendance_statuses_table', 18),
(75, '2025_07_09_194434_employee_group', 37),
(41, '2025_07_22_112036_create_holidays_table', 22),
(67, '2023_09_21_142101_create_districts_table', 32),
(66, '2023_09_21_142029_create_divisions_table', 32),
(51, '2025_07_24_083657_create_employees_table', 26),
(52, '2025_08_06_131351_add_flexible_time_to_groups_table', 27),
(65, '2025_05_21_183235_create_shift_settings_table', 31),
(68, '2023_09_21_142126_create_upazilas_table', 32),
(69, '2023_09_21_142202_create_teachers_table', 32),
(70, '2023_09_23_073501_create_students_table', 33),
(71, '2023_11_04_062551_create_designations_table', 33),
(77, '2025_09_25_081010_add_designation_to_employee_table', 39),
(73, '2025_10_07_102426_create_employees_table', 35);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_token', 'c60a8bda72a928b59a10c6aadbb1535a916dddb5d319844c0d4d6e6e22290e0e', '[\"*\"]', '2025-07-14 04:04:32', NULL, '2025-07-10 00:45:35', '2025-07-14 04:04:32'),
(2, 'App\\Models\\User', 1, 'auth_token', 'd5afe9b4d11b61f0eb29acaf494788cfa31ccc1b74570442d89546f9fcbd68d4', '[\"*\"]', '2025-07-14 04:12:31', NULL, '2025-07-10 06:09:34', '2025-07-14 04:12:31'),
(3, 'App\\Models\\User', 1, 'auth_token', '40eedb0216578c4540bd3eb46c3bc713f20af9a03f0718e6fee355420248d5dd', '[\"*\"]', '2025-07-14 04:09:41', NULL, '2025-07-12 22:29:26', '2025-07-14 04:09:41'),
(4, 'App\\Models\\User', 1, 'auth_token', 'cc3a33efe28f906e4d4245f4db57612965afdb3fe0971e8a0a45d64f58ca9443', '[\"*\"]', '2025-07-14 06:10:41', NULL, '2025-07-14 06:09:41', '2025-07-14 06:10:41'),
(5, 'App\\Models\\User', 1, 'auth_token', 'af25f99657ae9f3203ae5860228f14666092a1664a219bf943ebcf0606944b11', '[\"*\"]', '2025-07-14 06:43:03', NULL, '2025-07-14 06:20:44', '2025-07-14 06:43:03');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shift_settings`
--

DROP TABLE IF EXISTS `shift_settings`;
CREATE TABLE IF NOT EXISTS `shift_settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` bigint NOT NULL,
  `branch_code` bigint DEFAULT NULL,
  `shift_name_en` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `shift_name_bn` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `start_time` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `end_time` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `eiin` bigint DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shift_settings_uid_unique` (`uid`),
  UNIQUE KEY `shift_settings_branch_code_shift_name_en_unique` (`branch_code`,`shift_name_en`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `shift_settings`
--

INSERT INTO `shift_settings` (`id`, `uid`, `branch_code`, `shift_name_en`, `shift_name_bn`, `start_time`, `end_time`, `description`, `eiin`, `status`, `created_at`, `updated_at`) VALUES
(27, 1843512983038465, 91, 'Tuhin', 'বাংলা', '07:00', '22:00', 'ffff', 1, 1, '2025-09-17 06:09:43', '2025-09-17 06:09:58'),
(21, 1843396486426437, 100, 'Morning Shift', 'সকালের শিফট', '06:00 AM', '12:00 PM', 'Morning Shift description', 123, 1, '2025-09-15 23:18:04', '2025-09-15 23:18:04'),
(22, 1843396552154259, 100, 'Evening Shift', 'বিকালের শিফট', '02:00 PM', '08:00 PM', 'Description', 123, 1, '2025-09-15 23:19:06', '2025-09-15 23:19:06'),
(23, 1843396575146735, 101, 'Morning Shift', 'সকালের শিফট', '06:00 AM', '12:00 PM', 'Description', 123, 1, '2025-09-15 23:19:28', '2025-09-15 23:19:28'),
(24, 1843396597712227, 101, 'Evening Shift', 'বিকালের শিফট', '02:00 PM', '08:00 PM', 'Description', 123, 1, '2025-09-15 23:19:50', '2025-09-15 23:19:50'),
(28, 1844031756388773, 91, 'aa', 'aa', '11:11 AM', '11:12 PM', '2321', 123, 2, '2025-09-22 23:35:24', '2025-09-22 23:35:24');

-- --------------------------------------------------------

--
-- Table structure for table `special_working_days`
--

DROP TABLE IF EXISTS `special_working_days`;
CREATE TABLE IF NOT EXISTS `special_working_days` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `day_type` tinyint NOT NULL DEFAULT '1',
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `special_working_days`
--

INSERT INTO `special_working_days` (`id`, `date`, `day_type`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, '2025-03-31', 1, 'csoff  Day Special....', 1, NULL, '2025-09-23 23:42:03'),
(3, '2025-09-30', 1, 'off  Day Special', 1, '2025-09-23 23:41:54', '2025-09-23 23:41:54');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `eiin` bigint DEFAULT NULL,
  `attached_eiin` int DEFAULT NULL,
  `suid` bigint DEFAULT NULL,
  `student_unique_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `uid` bigint NOT NULL,
  `caid` bigint DEFAULT NULL,
  `type` tinyint DEFAULT NULL,
  `incremental_no` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `student_name_en` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `student_name_bn` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `brid` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `reg_status` tinyint NOT NULL DEFAULT '0' COMMENT '0=pending,1=temp,2=registered',
  `scroll_num` bigint DEFAULT NULL,
  `registration_year` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `religion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `birth_place` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `gender` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `board_reg_no` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `recent_study_class` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `disability_status` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `blood_group` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `student_mobile_no` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ethnic_info` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `branch` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `version` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `shift` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `class` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `section` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `group` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `roll` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `is_regular` tinyint DEFAULT NULL,
  `father_name_en` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `father_name_bn` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `father_nid` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `father_brid` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `father_date_of_birth` date DEFAULT NULL,
  `father_mobile_no` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mother_name_en` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mother_name_bn` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mother_nid` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mother_brid` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mother_date_of_birth` date DEFAULT NULL,
  `mother_mobile_no` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `guardian_name_bn` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `guardian_name_en` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `guardian_mobile_no` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `guardian_nid` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `guardian_occupation` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `disability_types` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `disability_file` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `relation_with_guardian` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `present_address` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `permanent_address` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `post_office` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `division_id` bigint DEFAULT NULL,
  `district_id` bigint DEFAULT NULL,
  `upazilla_id` bigint DEFAULT NULL,
  `unions` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `image` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `br_file` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `role` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `data_source` tinyint DEFAULT NULL,
  `student_source` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `updated_by` bigint DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_uid_unique` (`uid`),
  UNIQUE KEY `students_suid_unique` (`suid`),
  UNIQUE KEY `students_caid_unique` (`caid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
CREATE TABLE IF NOT EXISTS `teachers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `eiin` bigint DEFAULT NULL,
  `caid` bigint DEFAULT NULL,
  `uid` bigint NOT NULL,
  `pdsid` bigint DEFAULT NULL,
  `index_number` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `type` tinyint DEFAULT NULL,
  `incremental_no` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `name_bn` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fathers_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mothers_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mobile_no` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `gender` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `institute_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `institute_type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `institute_category` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `workstation_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `branch_institute_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `branch_institute_category` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `service_break_institute` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `designation` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `designation_id` int DEFAULT NULL,
  `division_id` int DEFAULT NULL,
  `district_id` int DEFAULT NULL,
  `upazilla_id` int DEFAULT NULL,
  `is_foreign` tinyint NOT NULL DEFAULT '0' COMMENT '1=foreign,0=local',
  `country` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `joining_year` int DEFAULT NULL,
  `mpo_code` int DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `last_working_date` date DEFAULT NULL,
  `nid` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `blood_group` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `emergency_contact` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `image` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `role` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ismpo` tinyint DEFAULT NULL,
  `isactive` tinyint DEFAULT NULL,
  `data_source` tinyint DEFAULT NULL,
  `teacher_source` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `teacher_type` int DEFAULT NULL,
  `access_type` int DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `updated_by` bigint DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teachers_uid_unique` (`uid`),
  UNIQUE KEY `teachers_caid_unique` (`caid`),
  UNIQUE KEY `teachers_pdsid_unique` (`pdsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `upazilas`
--

DROP TABLE IF EXISTS `upazilas`;
CREATE TABLE IF NOT EXISTS `upazilas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` bigint NOT NULL,
  `upazila_name_bn` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `district_id` bigint DEFAULT NULL,
  `upazila_name_en` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `upazilas_uid_unique` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=545 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `upazilas`
--

INSERT INTO `upazilas` (`id`, `uid`, `upazila_name_bn`, `created_at`, `updated_at`, `district_id`, `upazila_name_en`) VALUES
(1, 1818755754768009, 'আমতলী', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 34, 'Amtali'),
(2, 1818755754790093, 'বামনা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 34, 'Bamna'),
(3, 1818755754805786, 'বরগুনা সদর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 34, 'Barguna Sadar'),
(4, 1818755754816004, 'বেতাগি', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 34, 'Betagi'),
(5, 1818755754822800, 'পাথরঘাটা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 34, 'Patharghata'),
(6, 1818755754831807, 'তালতলী', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 34, 'Taltali'),
(7, 1818755754841903, 'মুলাদি', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 35, 'Muladi'),
(8, 1818755754847478, 'বাবুগঞ্জ', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 35, 'Babuganj'),
(9, 1818755754858129, 'আগাইলঝরা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 35, 'Agailjhara'),
(10, 1818755754864951, 'বরিশাল সদর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 35, 'Barisal Sadar'),
(11, 1818755754872878, 'বাকেরগঞ্জ', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 35, 'Bakerganj'),
(12, 1818755754878503, 'বানাড়িপারা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 35, 'Banaripara'),
(13, 1818755754888840, 'গৌরনদী', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 35, 'Gaurnadi'),
(14, 1818755754894668, 'হিজলা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 35, 'Hizla'),
(15, 1818755754904693, 'মেহেদিগঞ্জ ', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 35, 'Mehendiganj'),
(16, 1818755754913307, 'ওয়াজিরপুর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 35, 'Wazirpur'),
(17, 1818755754922984, 'ভোলা সদর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 36, 'Bhola Sadar'),
(18, 1818755754930963, 'বুরহানউদ্দিন', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 36, 'Burhanuddin'),
(19, 1818755754938338, 'চর ফ্যাশন', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 36, 'Char Fasson'),
(20, 1818755754945617, 'দৌলতখান', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 36, 'Daulatkhan'),
(21, 1818755754954330, 'লালমোহন', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 36, 'Lalmohan'),
(22, 1818755754962274, 'মনপুরা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 36, 'Manpura'),
(23, 1818755754970257, 'তাজুমুদ্দিন', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 36, 'Tazumuddin'),
(24, 1818755754978013, 'ঝালকাঠি সদর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 37, 'Jhalokati Sadar'),
(25, 1818755754983532, 'কাঁঠালিয়া', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 37, 'Kathalia'),
(26, 1818755754991865, 'নালচিতি', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 37, 'Nalchity'),
(27, 1818755755000688, 'রাজাপুর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 37, 'Rajapur'),
(28, 1818755755007457, 'বাউফল', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 38, 'Bauphal'),
(29, 1818755755014556, 'দশমিনা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 38, 'Dashmina'),
(30, 1818755755020059, 'গলাচিপা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 38, 'Galachipa'),
(31, 1818755755028000, 'কালাপারা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 38, 'Kalapara'),
(32, 1818755755034482, 'মির্জাগঞ্জ ', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 38, 'Mirzaganj'),
(33, 1818755755041284, 'পটুয়াখালী সদর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 38, 'Patuakhali Sadar'),
(34, 1818755755046871, 'ডুমকি', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 38, 'Dumki'),
(35, 1818755755054402, 'রাঙ্গাবালি', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 38, 'Rangabali'),
(36, 1818755755060434, 'ভ্যান্ডারিয়া', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 39, 'Bhandaria'),
(37, 1818755755066938, 'কাউখালি', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 39, 'Kaukhali'),
(38, 1818755755075829, 'মাঠবাড়িয়া', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 39, 'Mathbaria'),
(39, 1818755755085275, 'নাজিরপুর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 39, 'Nazirpur'),
(40, 1818755755095109, 'নেসারাবাদ', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 39, 'Nesarabad'),
(41, 1818755755100002, 'পিরোজপুর সদর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 39, 'Pirojpur Sadar'),
(42, 1818755755109060, 'জিয়ানগর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 39, 'Zianagar'),
(43, 1818755755113938, 'বান্দরবন সদর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 40, 'Bandarban Sadar'),
(44, 1818755755121117, 'থানচি', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 40, 'Thanchi'),
(45, 1818755755127240, 'লামা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 40, 'Lama'),
(46, 1818755755134465, 'নাইখংছড়ি ', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 40, 'Naikhongchhari'),
(47, 1818755755142553, 'আলী কদম', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 40, 'Ali kadam'),
(48, 1818755755147529, 'রউয়াংছড়ি ', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 40, 'Rowangchhari'),
(49, 1818755755154591, 'রুমা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 40, 'Ruma'),
(50, 1818755755160619, 'ব্রাহ্মণবাড়িয়া সদর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 41, 'Brahmanbaria Sadar'),
(51, 1818755755167303, 'আশুগঞ্জ', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 41, 'Ashuganj'),
(52, 1818755755171920, 'নাসির নগর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 41, 'Nasirnagar'),
(53, 1818755755179557, 'নবীনগর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 41, 'Nabinagar'),
(54, 1818755755184520, 'সরাইল', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 41, 'Sarail'),
(55, 1818755755191567, 'শাহবাজপুর টাউন', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 41, 'Shahbazpur Town'),
(56, 1818755755197345, 'কসবা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 41, 'Kasba'),
(57, 1818755755202417, 'আখাউরা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 41, 'Akhaura'),
(58, 1818755755208275, 'বাঞ্ছারামপুর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 41, 'Bancharampur'),
(59, 1818755755215541, 'বিজয় নগর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 41, 'Bijoynagar'),
(60, 1818755755221223, 'চাঁদপুর সদর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 42, 'Chandpur Sadar'),
(61, 1818755755230956, 'ফরিদগঞ্জ', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 42, 'Faridganj'),
(62, 1818755755238377, 'হাইমচর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 42, 'Haimchar'),
(63, 1818755755245036, 'হাজীগঞ্জ', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 42, 'Haziganj'),
(64, 1818755755252327, 'কচুয়া', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 42, 'Kachua'),
(65, 1818755755259134, 'মতলব উত্তর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 42, 'Matlab Uttar'),
(66, 1818755755264543, 'মতলব দক্ষিণ', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 42, 'Matlab Dakkhin'),
(67, 1818755755272558, 'শাহরাস্তি', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 42, 'Shahrasti'),
(68, 1818755755277946, 'আনোয়ারা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 43, 'Anwara'),
(69, 1818755755285585, 'বাশখালি', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 43, 'Banshkhali'),
(70, 1818755755293829, 'বোয়ালখালি', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 43, 'Boalkhali'),
(71, 1818755755299828, 'চন্দনাইশ', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 43, 'Chandanaish'),
(72, 1818755755305924, 'ফটিকছড়ি', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 43, 'Fatikchhari'),
(73, 1818755755312185, 'হাঠহাজারী', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 43, 'Hathazari'),
(74, 1818755755317617, 'লোহাগারা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 43, 'Lohagara'),
(75, 1818755755327826, 'মিরসরাই', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 43, 'Mirsharai'),
(76, 1818755755334844, 'পটিয়া', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 43, 'Patiya'),
(77, 1818755755344883, 'রাঙ্গুনিয়া', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 43, 'Rangunia'),
(78, 1818755755351861, 'রাউজান', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 43, 'Raozan'),
(79, 1818755755358574, 'সন্দ্বীপ', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 43, 'Sandwip'),
(80, 1818755755365078, 'সাতকানিয়া', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 43, 'Satkania'),
(81, 1818755755371386, 'সীতাকুণ্ড', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 43, 'Sitakunda'),
(82, 1818755755381971, 'বড়ুরা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 44, 'Barura'),
(83, 1818755755388680, 'ব্রাহ্মণপাড়া', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 44, 'Brahmanpara'),
(84, 1818755755397239, 'বুড়িচং', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 44, 'Burichong'),
(85, 1818755755402744, 'চান্দিনা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 44, 'Chandina'),
(86, 1818755755410422, 'চৌদ্দগ্রাম', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 44, 'Chauddagram'),
(87, 1818755755417478, 'দাউদকান্দি', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 44, 'Daudkandi'),
(88, 1818755755422728, 'দেবীদ্বার', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 44, 'Debidwar'),
(89, 1818755755429694, 'হোমনা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 44, 'Homna'),
(90, 1818755755435403, 'কুমিল্লা সদর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 44, 'Comilla Sadar'),
(91, 1818755755443173, 'লাকসাম', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 44, 'Laksam'),
(92, 1818755755455885, 'মনোহরগঞ্জ', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 44, 'Monohorgonj'),
(93, 1818755755464058, 'মেঘনা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 44, 'Meghna'),
(94, 1818755755474253, 'মুরাদনগর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 44, 'Muradnagar'),
(95, 1818755755483467, 'নাঙ্গালকোট', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 44, 'Nangalkot'),
(96, 1818755755488796, 'কুমিল্লা সদর দক্ষিণ', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 44, 'Comilla Sadar South'),
(97, 1818755755497561, 'তিতাস', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 44, 'Titas'),
(98, 1818755755505577, 'চকরিয়া', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 45, 'Chakaria'),
(99, 1818755755510953, 'কক্স বাজার সদর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 45, '{{198}}\'\'{{199}}'),
(100, 1818755755517491, 'কুতুবদিয়া', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 45, 'Kutubdia'),
(101, 1818755755522720, 'মহেশখালী', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 45, 'Maheshkhali'),
(102, 1818755755530693, 'রামু', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 45, 'Ramu'),
(103, 1818755755535848, 'টেকনাফ', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 45, 'Teknaf'),
(104, 1818755755543914, 'উখিয়া', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 45, 'Ukhia'),
(105, 1818755755548637, 'পেকুয়া', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 45, 'Pekua'),
(106, 1818755755555744, 'ফেনী সদর', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 46, 'Feni Sadar'),
(107, 1818755755560719, 'ছাগল নাইয়া', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 46, 'Chagalnaiya'),
(108, 1818755755568372, 'দাগানভিয়া', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 46, 'Daganbhyan'),
(109, 1818755755576076, 'পরশুরাম', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 46, 'Parshuram'),
(110, 1818755755580844, 'ফুলগাজি', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 46, 'Fhulgazi'),
(111, 1818755755588969, 'সোনাগাজি', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 46, 'Sonagazi'),
(112, 1818755755594138, 'দিঘিনালা ', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 47, 'Dighinala'),
(113, 1818755755599181, 'খাগড়াছড়ি', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 47, 'Khagrachhari'),
(114, 1818755755606239, 'লক্ষ্মীছড়ি', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 47, 'Lakshmichhari'),
(115, 1818755755611498, 'মহলছড়ি', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 47, 'Mahalchhari'),
(116, 1818755755616755, 'মানিকছড়ি', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 47, 'Manikchhari'),
(117, 1818755755622503, 'মাটিরাঙ্গা', '2024-12-17 23:44:11', '2024-12-17 23:44:11', 47, 'Matiranga'),
(118, 1818755755677315, 'পানছড়ি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 47, 'Panchhari'),
(119, 1818755755682945, 'রামগড়', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 47, 'Ramgarh'),
(120, 1818755755691478, 'লক্ষ্মীপুর সদর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 48, 'Lakshmipur Sadar'),
(121, 1818755755697299, 'রায়পুর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 48, 'Raipur'),
(122, 1818755755703481, 'রামগঞ্জ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 48, 'Ramganj'),
(123, 1818755755709549, 'রামগতি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 48, 'Ramgati'),
(124, 1818755755716052, 'কমল নগর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 48, 'Komol Nagar'),
(125, 1818755755721736, 'নোয়াখালী সদর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 49, 'Noakhali Sadar'),
(126, 1818755755730214, 'বেগমগঞ্জ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 49, 'Begumganj'),
(127, 1818755755735734, 'চাটখিল', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 49, 'Chatkhil'),
(128, 1818755755742228, 'কোম্পানীগঞ্জ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 49, 'Companyganj'),
(129, 1818755755747377, 'শেনবাগ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 49, 'Shenbag'),
(130, 1818755755754906, 'হাতিয়া', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 49, 'Hatia'),
(131, 1818755755762117, 'কবিরহাট ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 49, 'Kobirhat'),
(132, 1818755755767089, 'সোনাইমুরি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 49, 'Sonaimuri'),
(133, 1818755755774089, 'সুবর্ণ চর ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 49, 'Suborno Char'),
(134, 1818755755779894, 'রাঙ্গামাটি সদর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 50, 'Rangamati Sadar'),
(135, 1818755755785881, 'বেলাইছড়ি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 50, 'Belaichhari'),
(136, 1818755755795531, 'বাঘাইছড়ি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 50, 'Bagaichhari'),
(137, 1818755755800431, 'বরকল', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 50, 'Barkal'),
(138, 1818755755807363, 'জুরাইছড়ি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 50, 'Juraichhari'),
(139, 1818755755814687, 'রাজাস্থলি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 50, 'Rajasthali'),
(140, 1818755755821387, 'কাপ্তাই', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 50, 'Kaptai'),
(141, 1818755755828188, 'লাঙ্গাডু', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 50, 'Langadu'),
(142, 1818755755833148, 'নান্নেরচর ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 50, 'Nannerchar'),
(143, 1818755755839518, 'কাউখালি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 50, 'Kaukhali'),
(144, 1818755755846263, 'ধামরাই', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 1, 'Dhamrai'),
(145, 1818755755851485, 'দোহার', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 1, 'Dohar'),
(146, 1818755755859455, 'কেরানীগঞ্জ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 1, 'Keraniganj'),
(147, 1818755755865057, 'নবাবগঞ্জ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 1, 'Nawabganj'),
(148, 1818755755871685, 'সাভার', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 1, 'Savar'),
(149, 1818755755884536, 'ফরিদপুর সদর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 2, 'Faridpur Sadar'),
(150, 1818755755892304, 'বোয়ালমারী', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 2, 'Boalmari'),
(151, 1818755755901047, 'আলফাডাঙ্গা', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 2, 'Alfadanga'),
(152, 1818755755914758, 'মধুখালি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 2, 'Madhukhali'),
(153, 1818755755922664, 'ভাঙ্গা', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 2, 'Bhanga'),
(154, 1818755755927819, 'নগরকান্ড', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 2, 'Nagarkanda'),
(155, 1818755755932862, 'চরভদ্রাসন ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 2, 'Charbhadrasan'),
(156, 1818755755937814, 'সদরপুর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 2, 'Sadarpur'),
(157, 1818755755944733, 'শালথা', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 2, 'Shaltha'),
(158, 1818755755951306, 'গাজীপুর সদর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 3, 'Gazipur Sadar-Joydebpur'),
(159, 1818755755959955, 'কালিয়াকৈর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 3, 'Kaliakior'),
(160, 1818755755965022, 'কাপাসিয়া', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 3, 'Kapasia'),
(161, 1818755755971679, 'শ্রীপুর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 3, 'Sripur'),
(162, 1818755755978996, 'কালীগঞ্জ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 3, 'Kaliganj'),
(163, 1818755755986978, 'টঙ্গি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 3, 'Tongi'),
(164, 1818755755994338, 'গোপালগঞ্জ সদর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 4, 'Gopalganj Sadar'),
(165, 1818755755999060, 'কাশিয়ানি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 4, 'Kashiani'),
(166, 1818755756004427, 'কোটালিপাড়া', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 4, 'Kotalipara'),
(167, 1818755756010173, 'মুকসুদপুর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 4, 'Muksudpur'),
(168, 1818755756016248, 'টুঙ্গিপাড়া', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 4, 'Tungipara'),
(169, 1818755756022146, 'দেওয়ানগঞ্জ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 5, 'Dewanganj'),
(170, 1818755756027748, 'বকসিগঞ্জ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 5, 'Baksiganj'),
(171, 1818755756032631, 'ইসলামপুর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 5, 'Islampur'),
(172, 1818755756039702, 'জামালপুর সদর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 5, 'Jamalpur Sadar'),
(173, 1818755756047183, 'মাদারগঞ্জ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 5, 'Madarganj'),
(174, 1818755756053115, 'মেলানদাহা', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 5, 'Melandaha'),
(175, 1818755756061801, 'সরিষাবাড়ি ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 5, 'Sarishabari'),
(176, 1818755756066778, 'নারুন্দি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 5, 'Narundi Police I.C'),
(177, 1818755756075456, 'অষ্টগ্রাম', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 6, 'Astagram'),
(178, 1818755756081491, 'বাজিতপুর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 6, 'Bajitpur'),
(179, 1818755756089427, 'ভৈরব', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 6, 'Bhairab'),
(180, 1818755756097819, 'হোসেনপুর ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 6, 'Hossainpur'),
(181, 1818755756103852, 'ইটনা', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 6, 'Itna'),
(182, 1818755756110889, 'করিমগঞ্জ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 6, 'Karimganj'),
(183, 1818755756119294, 'কতিয়াদি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 6, 'Katiadi'),
(184, 1818755756127578, 'কিশোরগঞ্জ সদর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 6, 'Kishoreganj Sadar'),
(185, 1818755756134074, 'কুলিয়ারচর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 6, 'Kuliarchar'),
(186, 1818755756141237, 'মিঠামাইন', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 6, 'Mithamain'),
(187, 1818755756147760, 'নিকলি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 6, 'Nikli'),
(188, 1818755756155614, 'পাকুন্ডা', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 6, 'Pakundia'),
(189, 1818755756161469, 'তাড়াইল', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 6, 'Tarail'),
(190, 1818755756170408, 'মাদারীপুর সদর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 7, 'Madaripur Sadar'),
(191, 1818755756176706, 'কালকিনি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 7, 'Kalkini'),
(192, 1818755756182039, 'রাজইর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 7, 'Rajoir'),
(193, 1818755756189193, 'শিবচর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 7, 'Shibchar'),
(194, 1818755756195198, 'মানিকগঞ্জ সদর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 8, 'Manikganj Sadar'),
(195, 1818755756204290, 'সিঙ্গাইর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 8, 'Singair'),
(196, 1818755756210232, 'শিবালয়', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 8, 'Shibalaya'),
(197, 1818755756217857, 'সাটুরিয়া', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 8, 'Saturia'),
(198, 1818755756224544, 'হরিরামপুর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 8, 'Harirampur'),
(199, 1818755756231544, 'ঘিওর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 8, 'Ghior'),
(200, 1818755756237577, 'দৌলতপুর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 8, 'Daulatpur'),
(201, 1818755756245279, 'লোহাজং', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 9, 'Lohajang'),
(202, 1818755756253906, 'শ্রীনগর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 9, 'Sreenagar'),
(203, 1818755756261439, 'মুন্সিগঞ্জ সদর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 9, 'Munshiganj Sadar'),
(204, 1818755756270796, 'সিরাজদিখান', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 9, 'Sirajdikhan'),
(205, 1818755756276997, 'টঙ্গিবাড়ি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 9, 'Tongibari'),
(206, 1818755756285796, 'গজারিয়া', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 9, 'Gazaria'),
(207, 1818755756292858, 'ভালুকা', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 10, 'Bhaluka'),
(208, 1818755756299889, 'ত্রিশাল', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 10, 'Trishal'),
(209, 1818755756305791, 'হালুয়াঘাট', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 10, 'Haluaghat'),
(210, 1818755756312616, 'মুক্তাগাছা', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 10, 'Muktagachha'),
(211, 1818755756320045, 'ধবারুয়া', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 10, 'Dhobaura'),
(212, 1818755756326127, 'ফুলবাড়িয়া', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 10, 'Fulbaria'),
(213, 1818755756333455, 'গফরগাঁও', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 10, 'Gaffargaon'),
(214, 1818755756339289, 'গৌরিপুর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 10, 'Gauripur'),
(215, 1818755756346274, 'ঈশ্বরগঞ্জ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 10, 'Ishwarganj'),
(216, 1818755756352494, 'ময়মনসিং সদর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 10, 'Mymensingh Sadar'),
(217, 1818755756359359, 'নন্দাইল', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 10, 'Nandail'),
(218, 1818755756365515, 'ফুলপুর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 10, 'Phulpur'),
(219, 1818755756371884, 'আড়াইহাজার', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 11, 'Araihazar'),
(220, 1818755756377196, 'সোনারগাঁও', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 11, 'Sonargaon'),
(221, 1818755756387953, 'বান্দার', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 11, 'Bandar'),
(222, 1818755756396026, 'নারায়ানগঞ্জ সদর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 11, 'Naryanganj Sadar'),
(223, 1818755756400691, 'রূপগঞ্জ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 11, 'Rupganj'),
(224, 1818755756407767, 'সিদ্ধিরগঞ্জ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 11, 'Siddirgonj'),
(225, 1818755756415476, 'বেলাবো', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 12, 'Belabo'),
(226, 1818755756420385, 'মনোহরদি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 12, 'Monohardi'),
(227, 1818755756427373, 'নরসিংদী সদর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 12, 'Narsingdi Sadar'),
(228, 1818755756433259, 'পলাশ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 12, 'Palash'),
(229, 1818755756438492, 'রায়পুর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 12, 'Raipura, Narsingdi'),
(230, 1818755756443431, 'শিবপুর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 12, 'Shibpur'),
(231, 1818755756449626, 'কেন্দুয়া', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 13, 'Kendua Upazilla'),
(232, 1818755756454403, 'আটপাড়া', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 13, 'Atpara Upazilla'),
(233, 1818755756459232, 'বরহাট্টা', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 13, 'Barhatta Upazilla'),
(234, 1818755756466992, 'দুর্গাপুর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 13, 'Durgapur Upazilla'),
(235, 1818755756472925, 'কলমাকান্দা', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 13, 'Kalmakanda Upazilla'),
(236, 1818755756480720, 'মদন', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 13, 'Madan Upazilla'),
(237, 1818755756487000, 'মোহনগঞ্জ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 13, 'Mohanganj Upazilla'),
(238, 1818755756494629, 'নেত্রকোনা সদর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 13, 'Netrakona-S Upazilla'),
(239, 1818755756499323, 'পূর্বধলা', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 13, 'Purbadhala Upazilla'),
(240, 1818755756506779, 'খালিয়াজুরি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 13, 'Khaliajuri Upazilla'),
(241, 1818755756513632, 'বালিয়াকান্দি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 14, 'Baliakandi'),
(242, 1818755756519481, 'গোয়ালন্দ ঘাট', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 14, 'Goalandaghat'),
(243, 1818755756526925, 'পাংশা', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 14, 'Pangsha'),
(244, 1818755756531627, 'কালুখালি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 14, 'Kalukhali'),
(245, 1818755756540118, 'রাজবাড়ি সদর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 14, 'Rajbari Sadar'),
(246, 1818755756545452, 'শরীয়তপুর সদর ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 15, 'Shariatpur Sadar -Palong'),
(247, 1818755756553737, 'দামুদিয়া', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 15, 'Damudya'),
(248, 1818755756558873, 'নড়িয়া', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 15, 'Naria'),
(249, 1818755756563877, 'জাজিরা', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 15, 'Jajira'),
(250, 1818755756570591, 'ভেদারগঞ্জ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 15, 'Bhedarganj'),
(251, 1818755756578037, 'গোসাইর হাট ', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 15, 'Gosairhat'),
(252, 1818755756582976, 'ঝিনাইগাতি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 16, 'Jhenaigati'),
(253, 1818755756588510, 'নাকলা', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 16, 'Nakla'),
(254, 1818755756602196, 'নালিতাবাড়ি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 16, 'Nalitabari'),
(255, 1818755756608301, 'শেরপুর সদর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 16, 'Sherpur Sadar'),
(256, 1818755756613800, 'শ্রীবরদি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 16, 'Sreebardi'),
(257, 1818755756620487, 'টাঙ্গাইল সদর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 17, 'Tangail Sadar'),
(258, 1818755756627732, 'সখিপুর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 17, 'Sakhipur'),
(259, 1818755756633511, 'বসাইল', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 17, 'Basail'),
(260, 1818755756641035, 'মধুপুর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 17, 'Madhupur'),
(261, 1818755756646838, 'ঘাটাইল', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 17, 'Ghatail'),
(262, 1818755756653102, 'কালিহাতি', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 17, 'Kalihati'),
(263, 1818755756661861, 'নগরপুর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 17, 'Nagarpur'),
(264, 1818755756667653, 'মির্জাপুর', '2024-12-17 23:44:12', '2024-12-17 23:44:12', 17, 'Mirzapur'),
(265, 1818755756723021, 'গোপালপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 17, 'Gopalpur'),
(266, 1818755756728088, 'দেলদুয়ার', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 17, 'Delduar'),
(267, 1818755756734384, 'ভুয়াপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 17, 'Bhuapur'),
(268, 1818755756741852, 'ধানবাড়ি', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 17, 'Dhanbari'),
(269, 1818755756751315, 'বাগেরহাট সদর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 55, 'Bagerhat Sadar'),
(270, 1818755756757631, 'চিতলমাড়ি', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 55, 'Chitalmari'),
(271, 1818755756763356, 'ফকিরহাট', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 55, 'Fakirhat'),
(272, 1818755756769079, 'কচুয়া', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 55, 'Kachua'),
(273, 1818755756774113, 'মোল্লাহাট ', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 55, 'Mollahat'),
(274, 1818755756780841, 'মংলা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 55, 'Mongla'),
(275, 1818755756786403, 'মরেলগঞ্জ', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 55, 'Morrelganj'),
(276, 1818755756792119, 'রামপাল', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 55, 'Rampal'),
(277, 1818755756797924, 'স্মরণখোলা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 55, 'Sarankhola'),
(278, 1818755756803588, 'দামুরহুদা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 56, 'Damurhuda'),
(279, 1818755756808827, 'চুয়াডাঙ্গা সদর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 56, 'Chuadanga-S'),
(280, 1818755756815366, 'জীবন নগর ', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 56, 'Jibannagar'),
(281, 1818755756823513, 'আলমডাঙ্গা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 56, 'Alamdanga'),
(282, 1818755756829451, 'অভয়নগর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 57, 'Abhaynagar'),
(283, 1818755756836018, 'কেশবপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 57, 'Keshabpur'),
(284, 1818755756841792, 'বাঘের পাড়া ', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 57, 'Bagherpara'),
(285, 1818755756849814, 'যশোর সদর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 57, 'Jessore Sadar'),
(286, 1818755756857250, 'চৌগাছা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 57, 'Chaugachha'),
(287, 1818755756863601, 'মনিরামপুর ', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 57, 'Manirampur'),
(288, 1818755756869400, 'ঝিকরগাছা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 57, 'Jhikargachha'),
(289, 1818755756875017, 'সারশা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 57, 'Sharsha'),
(290, 1818755756880460, 'ঝিনাইদহ সদর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 58, 'Jhenaidah Sadar'),
(291, 1818755756886720, 'মহেশপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 58, 'Maheshpur'),
(292, 1818755756894667, 'কালীগঞ্জ', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 58, 'Kaliganj'),
(293, 1818755756901299, 'কোট চাঁদপুর ', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 58, 'Kotchandpur'),
(294, 1818755756906938, 'শৈলকুপা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 58, 'Shailkupa'),
(295, 1818755756912447, 'হাড়িনাকুন্দা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 58, 'Harinakunda'),
(296, 1818755756918387, 'তেরোখাদা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 59, 'Terokhada'),
(297, 1818755756926269, 'বাটিয়াঘাটা ', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 59, 'Batiaghata'),
(298, 1818755756936952, 'ডাকপে', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 59, 'Dacope'),
(299, 1818755756942254, 'ডুমুরিয়া', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 59, 'Dumuria'),
(300, 1818755756952525, 'দিঘলিয়া', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 59, 'Dighalia'),
(301, 1818755756961794, 'কয়ড়া', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 59, 'Koyra'),
(302, 1818755756967760, 'পাইকগাছা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 59, 'Paikgachha'),
(303, 1818755756973572, 'ফুলতলা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 59, 'Phultala'),
(304, 1818755756979111, 'রূপসা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 59, 'Rupsa'),
(305, 1818755756985209, 'কুষ্টিয়া সদর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 60, 'Kushtia Sadar'),
(306, 1818755756991870, 'কুমারখালি', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 60, 'Kumarkhali'),
(307, 1818755756997181, 'দৌলতপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 60, 'Daulatpur'),
(308, 1818755757002731, 'মিরপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 60, 'Mirpur'),
(309, 1818755757011268, 'ভেরামারা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 60, 'Bheramara'),
(310, 1818755757017612, 'খোকসা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 60, 'Khoksa'),
(311, 1818755757026211, 'মাগুরা সদর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 61, 'Magura Sadar'),
(312, 1818755757034472, 'মোহাম্মাদপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 61, 'Mohammadpur'),
(313, 1818755757040371, 'শালিখা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 61, 'Shalikha'),
(314, 1818755757046078, 'শ্রীপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 61, 'Sreepur'),
(315, 1818755757052433, 'আংনি', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 62, 'angni'),
(316, 1818755757058215, 'মুজিব নগর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 62, 'Mujib Nagar'),
(317, 1818755757064051, 'মেহেরপুর সদর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 62, 'Meherpur-S'),
(318, 1818755757070790, 'নড়াইল সদর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 63, 'Narail-S Upazilla'),
(319, 1818755757076805, 'লোহাগাড়া', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 63, 'Lohagara Upazilla'),
(320, 1818755757082421, 'কালিয়া', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 63, 'Kalia Upazilla'),
(321, 1818755757089219, 'সাতক্ষীরা সদর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 64, 'Satkhira Sadar'),
(322, 1818755757095428, 'আসসাশুনি ', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 64, 'Assasuni'),
(323, 1818755757101675, 'দেভাটা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 64, 'Debhata'),
(324, 1818755757107957, 'তালা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 64, 'Tala'),
(325, 1818755757114143, 'কলরোয়া', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 64, 'Kalaroa'),
(326, 1818755757120555, 'কালীগঞ্জ', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 64, 'Kaliganj'),
(327, 1818755757128617, 'শ্যামনগর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 64, 'Shyamnagar'),
(328, 1818755757134599, 'আদমদিঘী', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 18, 'Adamdighi'),
(329, 1818755757141593, 'বগুড়া সদর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 18, 'Bogra Sadar'),
(330, 1818755757147256, 'শেরপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 18, 'Sherpur'),
(331, 1818755757152966, 'ধুনট', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 18, 'Dhunat'),
(332, 1818755757158070, 'দুপচাচিয়া', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 18, 'Dhupchanchia'),
(333, 1818755757165544, 'গাবতলি', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 18, 'Gabtali'),
(334, 1818755757172677, 'কাহালু', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 18, 'Kahaloo'),
(335, 1818755757180425, 'নন্দিগ্রাম', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 18, 'Nandigram'),
(336, 1818755757185871, 'শাহজাহানপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 18, 'Sahajanpur'),
(337, 1818755757192935, 'সারিয়াকান্দি', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 18, 'Sariakandi'),
(338, 1818755757203964, 'শিবগঞ্জ', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 18, 'Shibganj'),
(339, 1818755757212053, 'সোনাতলা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 18, 'Sonatala'),
(340, 1818755757218448, 'জয়পুরহাট সদর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 19, 'Joypurhat S'),
(341, 1818755757224351, 'আক্কেলপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 19, 'Akkelpur'),
(342, 1818755757235291, 'কালাই', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 19, 'Kalai'),
(343, 1818755757243276, 'খেতলাল', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 19, 'Khetlal'),
(344, 1818755757251541, 'পাঁচবিবি', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 19, 'Panchbibi'),
(345, 1818755757262268, 'নওগাঁ সদর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 20, 'Naogaon Sadar'),
(346, 1818755757274214, 'মহাদেবপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 20, 'Mohadevpur'),
(347, 1818755757280957, 'মান্দা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 20, 'Manda'),
(348, 1818755757286525, 'নিয়ামতপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 20, 'Niamatpur'),
(349, 1818755757292989, 'আত্রাই', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 20, 'Atrai'),
(350, 1818755757298439, 'রাণীনগর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 20, 'Raninagar'),
(351, 1818755757307109, 'পত্নীতলা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 20, 'Patnitala'),
(352, 1818755757315435, 'ধামইরহাট ', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 20, 'Dhamoirhat'),
(353, 1818755757323183, 'সাপাহার', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 20, 'Sapahar'),
(354, 1818755757327845, 'পোরশা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 20, 'Porsha'),
(355, 1818755757332364, 'বদলগাছি', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 20, 'Badalgachhi'),
(356, 1818755757341731, 'নাটোর সদর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 21, 'Natore Sadar'),
(357, 1818755757347200, 'বড়াইগ্রাম', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 21, 'Baraigram'),
(358, 1818755757354667, 'বাগাতিপাড়া', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 21, 'Bagatipara'),
(359, 1818755757359162, 'লালপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 21, 'Lalpur'),
(360, 1818755757366654, 'নাটোর সদর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 21, 'Natore Sadar'),
(361, 1818755757372777, 'বড়াই গ্রাম', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 21, 'Baraigram'),
(362, 1818755757379731, 'ভোলাহাট', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 22, 'Bholahat'),
(363, 1818755757385792, 'গোমস্তাপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 22, 'Gomastapur'),
(364, 1818755757393557, 'নাচোল', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 22, 'Nachole'),
(365, 1818755757401855, 'নবাবগঞ্জ সদর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 22, 'Nawabganj Sadar'),
(366, 1818755757407487, 'শিবগঞ্জ', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 22, 'Shibganj'),
(367, 1818755757418205, 'আটঘরিয়া', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 23, 'Atgharia'),
(368, 1818755757425570, 'বেড়া', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 23, 'Bera'),
(369, 1818755757432834, 'ভাঙ্গুরা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 23, 'Bhangura'),
(370, 1818755757441331, 'চাটমোহর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 23, 'Chatmohar'),
(371, 1818755757445800, 'ফরিদপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 23, 'Faridpur'),
(372, 1818755757452616, 'ঈশ্বরদী', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 23, 'Ishwardi'),
(373, 1818755757458313, 'পাবনা সদর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 23, 'Pabna Sadar'),
(374, 1818755757468873, 'সাথিয়া', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 23, 'Santhia'),
(375, 1818755757475512, 'সুজানগর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 23, 'Sujanagar'),
(376, 1818755757483489, 'বাঘা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 24, 'Bagha'),
(377, 1818755757489958, 'বাগমারা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 24, 'Bagmara'),
(378, 1818755757496763, 'চারঘাট', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 24, 'Charghat'),
(379, 1818755757502598, 'দুর্গাপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 24, 'Durgapur'),
(380, 1818755757510966, 'গোদাগারি', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 24, 'Godagari'),
(381, 1818755757515495, 'মোহনপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 24, 'Mohanpur'),
(382, 1818755757523185, 'পবা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 24, 'Paba'),
(383, 1818755757529380, 'পুঠিয়া', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 24, 'Puthia'),
(384, 1818755757535359, 'তানোর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 24, 'Tanore'),
(385, 1818755757543089, 'সিরাজগঞ্জ সদর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 25, 'Sirajganj Sadar'),
(386, 1818755757547518, 'বেলকুচি', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 25, 'Belkuchi'),
(387, 1818755757554454, 'চৌহালি', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 25, 'Chauhali'),
(388, 1818755757560066, 'কামারখান্দা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 25, 'Kamarkhanda'),
(389, 1818755757567135, 'কাজীপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 25, 'Kazipur'),
(390, 1818755757572472, 'রায়গঞ্জ', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 25, 'Raiganj'),
(391, 1818755757579983, 'শাহজাদপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 25, 'Shahjadpur'),
(392, 1818755757585516, 'তারাশ', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 25, 'Tarash'),
(393, 1818755757592977, 'উল্লাপাড়া', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 25, 'Ullahpara'),
(394, 1818755757599480, 'বিরামপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 26, 'Birampur'),
(395, 1818755757606013, 'বীরগঞ্জ', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 26, 'Birganj'),
(396, 1818755757613786, 'বিড়াল', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 26, 'Biral'),
(397, 1818755757620224, 'বোচাগঞ্জ', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 26, 'Bochaganj'),
(398, 1818755757627489, 'চিরিরবন্দর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 26, 'Chirirbandar'),
(399, 1818755757635383, 'ফুলবাড়ি', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 26, 'Phulbari'),
(400, 1818755757643930, 'ঘোড়াঘাট', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 26, 'Ghoraghat'),
(401, 1818755757648552, 'হাকিমপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 26, 'Hakimpur'),
(402, 1818755757656181, 'কাহারোল', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 26, 'Kaharole'),
(403, 1818755757662321, 'খানসামা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 26, 'Khansama'),
(404, 1818755757668988, 'দিনাজপুর সদর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 26, 'Dinajpur Sadar'),
(405, 1818755757675965, 'নবাবগঞ্জ', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 26, 'Nawabganj'),
(406, 1818755757680443, 'পার্বতীপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 26, 'Parbatipur'),
(407, 1818755757686677, 'ফুলছড়ি', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 27, 'Fulchhari'),
(408, 1818755757692594, 'গাইবান্ধা সদর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 27, 'Gaibandha sadar'),
(409, 1818755757698832, 'গোবিন্দগঞ্জ', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 27, 'Gobindaganj'),
(410, 1818755757705103, 'পলাশবাড়ী', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 27, 'Palashbari'),
(411, 1818755757711801, 'সাদুল্যাপুর', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 27, 'Sadullapur'),
(412, 1818755757717447, 'সাঘাটা', '2024-12-17 23:44:13', '2024-12-17 23:44:13', 27, 'Saghata'),
(413, 1818755757771580, 'সুন্দরগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 27, 'Sundarganj'),
(414, 1818755757779158, 'কুড়িগ্রাম সদর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 28, 'Kurigram Sadar'),
(415, 1818755757784321, 'নাগেশ্বরী', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 28, 'Nageshwari'),
(416, 1818755757791352, 'ভুরুঙ্গামারি', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 28, 'Bhurungamari'),
(417, 1818755757796089, 'ফুলবাড়ি', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 28, 'Phulbari'),
(418, 1818755757801908, 'রাজারহাট', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 28, 'Rajarhat'),
(419, 1818755757807797, 'উলিপুর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 28, 'Ulipur'),
(420, 1818755757816847, 'চিলমারি', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 28, 'Chilmari'),
(421, 1818755757827537, 'রউমারি', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 28, 'Rowmari'),
(422, 1818755757832956, 'চর রাজিবপুর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 28, 'Char Rajibpur'),
(423, 1818755757840369, 'লালমনিরহাট সদর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 29, 'Lalmanirhat Sadar'),
(424, 1818755757845380, 'আদিতমারি', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 29, 'Aditmari'),
(425, 1818755757852083, 'কালীগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 29, 'Kaliganj'),
(426, 1818755757857342, 'হাতিবান্ধা', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 29, 'Hatibandha'),
(427, 1818755757863951, 'পাটগ্রাম', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 29, 'Patgram'),
(428, 1818755757870040, 'নীলফামারী সদর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 30, 'Nilphamari Sadar'),
(429, 1818755757878059, 'সৈয়দপুর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 30, 'Saidpur'),
(430, 1818755757884022, 'জলঢাকা', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 30, 'Jaldhaka'),
(431, 1818755757890598, 'কিশোরগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 30, 'Kishoreganj'),
(432, 1818755757895604, 'ডোমার', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 30, 'Domar'),
(433, 1818755757901059, 'ডিমলা', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 30, 'Dimla'),
(434, 1818755757908571, 'পঞ্চগড় সদর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 31, 'Panchagarh Sadar'),
(435, 1818755757913468, 'দেবীগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 31, 'Debiganj'),
(436, 1818755757919334, 'বোদা', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 31, 'Boda'),
(437, 1818755757926235, 'আটোয়ারি', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 31, 'Atwari'),
(438, 1818755757934521, 'তেঁতুলিয়া', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 31, 'Tetulia'),
(439, 1818755757940336, 'বদরগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 32, 'Badarganj'),
(440, 1818755757945263, 'মিঠাপুকুর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 32, 'Mithapukur'),
(441, 1818755757950893, 'গঙ্গাচরা', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 32, 'Gangachara'),
(442, 1818755757957854, 'কাউনিয়া', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 32, 'Kaunia'),
(443, 1818755757967077, 'রংপুর সদর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 32, 'Rangpur Sadar'),
(444, 1818755757972964, 'পীরগাছা', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 32, 'Pirgachha'),
(445, 1818755757981498, 'পীরগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 32, 'Pirganj'),
(446, 1818755757988244, 'তারাগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 32, 'Taraganj'),
(447, 1818755757996267, 'ঠাকুরগাঁও সদর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 33, 'Thakurgaon Sadar'),
(448, 1818755758001246, 'পীরগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 33, 'Pirganj'),
(449, 1818755758006400, 'বালিয়াডাঙ্গি', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 33, 'Baliadangi'),
(450, 1818755758011476, 'হরিপুর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 33, 'Haripur'),
(451, 1818755758016844, 'রাণীসংকইল', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 33, 'Ranisankail'),
(452, 1818755758022001, 'আজমিরিগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 51, 'Ajmiriganj'),
(453, 1818755758028038, 'বানিয়াচং', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 51, 'Baniachang'),
(454, 1818755758033788, 'বাহুবল', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 51, 'Bahubal'),
(455, 1818755758040080, 'চুনারুঘাট', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 51, 'Chunarughat'),
(456, 1818755758045011, 'হবিগঞ্জ সদর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 51, 'Habiganj Sadar'),
(457, 1818755758050299, 'লাক্ষাই', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 51, 'Lakhai'),
(458, 1818755758058513, 'মাধবপুর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 51, 'Madhabpur'),
(459, 1818755758064981, 'নবীগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 51, 'Nabiganj'),
(460, 1818755758072739, 'শায়েস্তাগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 51, 'Shaistagonj'),
(461, 1818755758077581, 'মৌলভীবাজার', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 52, 'Moulvibazar Sadar'),
(462, 1818755758083064, 'বড়লেখা', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 52, 'Barlekha'),
(463, 1818755758090641, 'জুড়ি', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 52, 'Juri'),
(464, 1818755758095377, 'কামালগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 52, 'Kamalganj'),
(465, 1818755758101060, 'কুলাউরা', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 52, 'Kulaura'),
(466, 1818755758106196, 'রাজনগর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 52, 'Rajnagar'),
(467, 1818755758111116, 'শ্রীমঙ্গল', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 52, 'Sreemangal'),
(468, 1818755758116905, 'বিসশম্ভারপুর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 53, 'Bishwamvarpur'),
(469, 1818755758124481, 'ছাতক', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 53, 'Chhatak'),
(470, 1818755758130652, 'দেড়াই', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 53, 'Derai'),
(471, 1818755758137188, 'ধরমপাশা', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 53, 'Dharampasha'),
(472, 1818755758145207, 'দোয়ারাবাজার', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 53, 'Dowarabazar'),
(473, 1818755758150345, 'জগন্নাথপুর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 53, 'Jagannathpur'),
(474, 1818755758155573, 'জামালগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 53, 'Jamalganj'),
(475, 1818755758160534, 'সুল্লা', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 53, 'Sulla'),
(476, 1818755758167471, 'সুনামগঞ্জ সদর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 53, 'Sunamganj Sadar'),
(477, 1818755758172508, 'শান্তিগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 53, 'Shanthiganj'),
(478, 1818755758177424, 'তাহিরপুর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 53, 'Tahirpur'),
(479, 1818755758182988, 'সিলেট সদর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 54, 'Sylhet Sadar'),
(480, 1818755758189019, 'বেয়ানিবাজার', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 54, 'Beanibazar'),
(481, 1818755758194562, 'বিশ্বনাথ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 54, 'Bishwanath'),
(482, 1818755758201875, 'দক্ষিণ সুরমা', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 54, 'Dakshin Surma'),
(483, 1818755758209954, 'বালাগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 54, 'Balaganj'),
(484, 1818755758216787, 'কোম্পানিগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 54, 'Companiganj'),
(485, 1818755758222635, 'ফেঞ্চুগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 54, 'Fenchuganj'),
(486, 1818755758228732, 'গোলাপগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 54, 'Golapganj'),
(487, 1818755758234864, 'গোয়াইনঘাট', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 54, 'Gowainghat'),
(488, 1818755758241777, 'জৈন্তাপুর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 54, 'Jointapur'),
(489, 1818755758247459, 'কানাইঘাট', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 54, 'Kanaighat'),
(490, 1818755758255949, 'জাকিগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 54, 'Zakiganj'),
(491, 1818755758261676, 'নবীগঞ্জ', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 54, 'Nobigonj'),
(492, 1818755758267710, 'ঈদগাঁও', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 45, 'Eidgaon'),
(493, 1818755758274744, 'মধ্যনগর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 53, 'Modhyanagar'),
(494, 1818755758280640, 'দশর', '2024-12-17 23:44:14', '2024-12-17 23:44:14', 7, 'Dasar'),
(495, 1826462813835233, 'ঢাকা কোতয়ালী থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Dhaka Kotwali Thana'),
(496, 1826462813839204, 'কামরাঙ্গীরচর থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Kamrangirchar Thana'),
(497, 1826462813839945, 'চকবাজার থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Chawkbazar Thana'),
(498, 1826462813840684, 'তেজগাঁও থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Tejgaon Thana'),
(499, 1826462813841370, 'তেজগাঁও শিল্পাঞ্চল থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Tejgaon Industrial Area Thana'),
(500, 1826462813842043, 'শাহআলী থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Shah Ali Thana'),
(501, 1826462813842700, 'পল্লবী থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Pallabi Thana'),
(502, 1826462813843342, 'বাড্ডা থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Badda Thana'),
(503, 1826462813843956, 'ক্যান্টনমেন্ট থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Cantonment Thana'),
(504, 1826462813844614, 'উত্তরা মডেল থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Uttara Model Thana'),
(505, 1826462813845236, 'তুরাগ থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Turag Thana'),
(506, 1826462813845870, 'উত্তরখান থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Uttarkhan Thana');
INSERT INTO `upazilas` (`id`, `uid`, `upazila_name_bn`, `created_at`, `updated_at`, `district_id`, `upazila_name_en`) VALUES
(507, 1826462813846534, 'দক্ষিণখান থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Dakshinkhan Thana'),
(508, 1826462813847180, 'দারুস সালাম থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Darus Salam Thana'),
(509, 1826462813847807, 'মিরপুর মডেল থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Mirpur Model Thana'),
(510, 1826462813851864, 'শেরেবাংলা নগর থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Sher-e-Bangla Nagar Thana'),
(511, 1826462813852651, 'শাহজাহানপুর থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Shahjahanpur Thana'),
(512, 1826462813853321, 'ওয়ারী থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Wari Thana'),
(513, 1826462813853943, 'বনানী থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Banani Thana'),
(514, 1826462813854618, 'ভাটারা থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Vatara Thana'),
(515, 1826462813855332, 'ভাষানটেক থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Bhashantek Thana'),
(516, 1826462813855963, 'রূপনগর থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Rupnagar Thana'),
(517, 1826462813856625, 'মুগদা থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Mugda Thana'),
(518, 1826462813857259, 'উত্তরা পশ্চিম থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Uttara West Thana'),
(519, 1826462813857902, 'গুলশান থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Gulshan Thana'),
(520, 1826462813858564, 'বিমানবন্দর থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Airport Thana'),
(521, 1826462813859205, 'যাত্রাবাড়ী থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Jatrabari Thana'),
(522, 1826462813859901, 'সূত্রাপুর থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Sutrapur Thana'),
(523, 1826462813861041, 'মোহাম্মদপুর থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Mohammadpur Thana'),
(524, 1826462813862074, 'ধানমন্ডি থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Dhanmondi Thana'),
(525, 1826462813862910, 'কাফরুল থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Kafrul Thana'),
(526, 1826462813863841, 'খিলক্ষেত থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Khilkhet Thana'),
(527, 1826462813864582, 'আদাবর থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Adabor Thana'),
(528, 1826462813865261, 'রামপুরা থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Rampura Thana'),
(529, 1826462813866062, 'সবুজবাগ থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Sabujbagh Thana'),
(530, 1826462813866704, 'কদমতলী থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Kadamtali Thana'),
(531, 1826462813867325, 'গেন্ডারিয়া থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Gendaria Thana'),
(532, 1826462813867984, 'শ্যামপুর থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Shyampur Thana'),
(533, 1826462813868706, 'নিউমার্কেট থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'New Market Thana'),
(534, 1826462813869424, 'বংশাল থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Bangshal Thana'),
(535, 1826462813870109, 'পল্টন মডেল থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Paltan Model Thana'),
(536, 1826462813870763, 'ডেমরা থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Demra Thana'),
(537, 1826462813871390, 'রমনা মডেল থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Ramna Model Thana'),
(538, 1826462813872037, 'হাজারীবাগ থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Hazaribagh Thana'),
(539, 1826462813872738, 'খিলগাঁও থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Khilgaon Thana'),
(540, 1826462813873413, 'মতিঝিল থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Motijheel Thana'),
(541, 1826462813873997, 'শাহবাগ থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Shahbagh Thana'),
(542, 1826462813874599, 'কলাবাগান থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Kalabagan Thana'),
(543, 1826462813875216, 'লালবাগ থানা', '2025-03-13 01:24:35', '2025-03-13 01:24:35', 1, 'Lalbagh Thana');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, '$2y$12$QYzLoTVNdG2YNwhsCs5BUuqG74A0Xxcd2U3hPhooJ82KiPEu5hyzi', NULL, '2025-07-10 00:08:25', '2025-07-30 22:59:48');

-- --------------------------------------------------------

--
-- Table structure for table `work_days`
--

DROP TABLE IF EXISTS `work_days`;
CREATE TABLE IF NOT EXISTS `work_days` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `day_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `is_weekend` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `work_days_day_name_unique` (`day_name`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `work_days`
--

INSERT INTO `work_days` (`id`, `day_name`, `is_weekend`, `created_at`, `updated_at`) VALUES
(1, 'Sunday', 0, NULL, NULL),
(2, 'Monday', 0, NULL, NULL),
(3, 'Tuesday', 0, NULL, NULL),
(4, 'Wednesday', 0, NULL, NULL),
(5, 'Thursday', 0, NULL, NULL),
(6, 'Friday', 1, NULL, NULL),
(7, 'Saturday', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `work_day_group`
--

DROP TABLE IF EXISTS `work_day_group`;
CREATE TABLE IF NOT EXISTS `work_day_group` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_id` bigint UNSIGNED NOT NULL,
  `work_day_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `work_day_group_group_id_work_day_id_unique` (`group_id`,`work_day_id`),
  KEY `work_day_group_work_day_id_foreign` (`work_day_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `work_day_group`
--

INSERT INTO `work_day_group` (`id`, `group_id`, `work_day_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 3, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
