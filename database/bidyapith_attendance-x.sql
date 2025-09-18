-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 18, 2025 at 12:34 PM
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
  `branch_name_en` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `branch_name_bn` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `branch_location` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `head_of_branch_id` bigint DEFAULT NULL,
  `eiin` bigint DEFAULT NULL,
  `rec_status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branches_branch_code_unique` (`branch_code`),
  UNIQUE KEY `branches_uid_unique` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

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
(1, 58, NULL, NULL, '2025-07-22', '12:50', '18:40', 1, NULL, NULL),
(2, 55, NULL, NULL, '2025-07-22', '09:16', '17:40', 1, NULL, NULL),
(3, 57, NULL, NULL, '2025-07-22', '10:05', '18:03', 1, NULL, NULL),
(4, 62, NULL, NULL, '2025-07-22', '12:45', '19:30', 1, NULL, NULL),
(5, 64, NULL, NULL, NULL, '', NULL, 1, NULL, NULL),
(6, 51, NULL, NULL, '2025-07-22', '12:50', '20:30', 1, NULL, NULL),
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
(1, 'sync_time', 1, 'tuhin', '2025-07-20 04:08:57', '2025-08-07 04:33:23');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

DROP TABLE IF EXISTS `designations`;
CREATE TABLE IF NOT EXISTS `designations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` bigint NOT NULL,
  `designation_name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `updated_by` bigint DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `designations_uid_unique` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

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
  `created_by` bigint DEFAULT NULL,
  `updated_by` bigint DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `districts_uid_unique` (`uid`),
  KEY `districts_division_id_foreign` (`division_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `uid`, `division_id`, `district_name_en`, `district_name_bn`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1818754494274317, 3, 'Dhaka', 'ঢাকা', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(2, 1818754494301107, 3, 'Faridpur', 'ফরিদপুর', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(3, 1818754494307903, 3, 'Gazipur', 'গাজীপুর', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(4, 1818754494314948, 3, 'Gopalganj', 'গোপালগঞ্জ', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(5, 1818754494324313, 8, 'Jamalpur', 'জামালপুর', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(6, 1818754494336158, 3, 'Kishoreganj', 'কিশোরগঞ্জ', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(7, 1818754494345431, 3, 'Madaripur', 'মাদারীপুর', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(8, 1818754494355597, 3, 'Manikganj', 'মানিকগঞ্জ', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(9, 1818754494363594, 3, 'Munshiganj', 'মুন্সিগঞ্জ', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(10, 1818754494374466, 8, 'Mymensingh', 'ময়মনসিংহ', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(11, 1818754494384124, 3, 'Narayanganj', 'নারায়ণগঞ্জ', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(12, 1818754494392205, 3, 'Narsingdi', 'নরসিংদী', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(13, 1818754494401035, 8, 'Netrokona', 'নেত্রকোণা', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(14, 1818754494415610, 3, 'Rajbari', 'রাজবাড়ী', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(15, 1818754494421333, 3, 'Shariatpur', 'শরীয়তপুর', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(16, 1818754494429741, 8, 'Sherpur', 'শেরপুর', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(17, 1818754494436356, 3, 'Tangail', 'টাঙ্গাইল', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(18, 1818754494441154, 5, 'Bogura', 'বগুড়া', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(19, 1818754494448893, 5, 'Joypurhat', 'জয়পুরহাট', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(20, 1818754494455284, 5, 'Naogaon', 'নওগাঁ', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(21, 1818754494462404, 5, 'Natore', 'নাটোর', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(22, 1818754494467412, 5, 'Nawabganj', 'নবাবগঞ্জ', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(23, 1818754494473956, 5, 'Pabna', 'পাবনা', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(24, 1818754494479026, 5, 'Rajshahi', 'রাজশাহী', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(25, 1818754494487144, 5, 'Sirajgonj', 'সিরাজগঞ্জ', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(26, 1818754494492250, 6, 'Dinajpur', 'দিনাজপুর', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(27, 1818754494498847, 6, 'Gaibandha', 'গাইবান্ধা', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(28, 1818754494506332, 6, 'Kurigram', 'কুড়িগ্রাম', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(29, 1818754494512713, 6, 'Lalmonirhat', 'লালমনিরহাট', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(30, 1818754494520411, 6, 'Nilphamari', 'নীলফামারী', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(31, 1818754494527153, 6, 'Panchagarh', 'পঞ্চগড়', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(32, 1818754494536005, 6, 'Rangpur', 'রংপুর', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(33, 1818754494542190, 6, 'Thakurgaon', 'ঠাকুরগাঁও', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(34, 1818754494550715, 1, 'Barguna', 'বরগুনা', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(35, 1818754494556420, 1, 'Barishal', 'বরিশাল', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(36, 1818754494563790, 1, 'Bhola', 'ভোলা', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(37, 1818754494570992, 1, 'Jhalokati', 'ঝালকাঠি', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(38, 1818754494577361, 1, 'Patuakhali', 'পটুয়াখালী', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(39, 1818754494583646, 1, 'Pirojpur', 'পিরোজপুর', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(40, 1818754494593459, 2, 'Bandarban', 'বান্দরবান', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(41, 1818754494600666, 2, 'Brahmanbaria', 'ব্রাহ্মণবাড়িয়া', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(42, 1818754494606145, 2, 'Chandpur', 'চাঁদপুর', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(43, 1818754494615089, 2, 'Chattogram', 'চট্টগ্রাম', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(44, 1818754494620602, 2, 'Cumilla', 'কুমিল্লা', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(45, 1818754494625681, 2, 'Cox\'s Bazar', 'কক্স বাজার', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(46, 1818754494632288, 2, 'Feni', 'ফেনী', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(47, 1818754494640621, 2, 'Khagrachari', 'খাগড়াছড়ি', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(48, 1818754494647530, 2, 'Lakshmipur', 'লক্ষ্মীপুর', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(49, 1818754494655208, 2, 'Noakhali', 'নোয়াখালী', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(50, 1818754494662103, 2, 'Rangamati', 'রাঙ্গামাটি', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(51, 1818754494667372, 7, 'Habiganj', 'হবিগঞ্জ', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(52, 1818754494673570, 7, 'Maulvibazar', 'মৌলভীবাজার', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(53, 1818754494678765, 7, 'Sunamganj', 'সুনামগঞ্জ', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(54, 1818754494685258, 7, 'Sylhet', 'সিলেট', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(55, 1818754494690418, 4, 'Bagerhat', 'বাগেরহাট', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(56, 1818754494698281, 4, 'Chuadanga', 'চুয়াডাঙ্গা', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(57, 1818754494705680, 4, 'Jashore', 'যশোর', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(58, 1818754494717228, 4, 'Jhenaidah', 'ঝিনাইদহ', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(59, 1818754494723624, 4, 'Khulna', 'খulনা', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(60, 1818754494733217, 4, 'Kushtia', 'কুষ্টিয়া', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(61, 1818754494745385, 4, 'Magura', 'মাগুরা', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(62, 1818754494756334, 4, 'Meherpur', 'মেহেরপুর', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(63, 1818754494766236, 4, 'Narail', 'নড়াইল', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL),
(64, 1818754494777020, 4, 'Satkhira', 'সাতক্ষীরা', 3110479, 3110479, NULL, '2024-12-17 23:24:09', '2024-12-17 23:24:09', NULL);

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
  `created_by` bigint DEFAULT NULL,
  `updated_by` bigint DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `divisions_uid_unique` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`id`, `uid`, `division_name_en`, `division_name_bn`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1818754028556574, 'Barishal', 'বরিশাল', 3110479, 3110479, NULL, '2024-12-17 23:16:44', '2024-12-17 23:16:44', NULL),
(2, 1818754028594249, 'Chattogram', 'চট্টগ্রাম', 3110479, 3110479, NULL, '2024-12-17 23:16:44', '2024-12-17 23:16:44', NULL),
(3, 1818754028605202, 'Dhaka', 'ঢাকা', 3110479, 3110479, NULL, '2024-12-17 23:16:44', '2024-12-17 23:16:44', NULL),
(4, 1818754028612320, 'Khulna', 'খুলনা', 3110479, 3110479, NULL, '2024-12-17 23:16:44', '2024-12-17 23:16:44', NULL),
(5, 1818754028668735, 'Rajshahi', 'রাজশাহী', 3110479, 3110479, NULL, '2024-12-17 23:16:45', '2024-12-17 23:16:45', NULL),
(6, 1818754028674153, 'Rangpur', 'রংপুর', 3110479, 3110479, NULL, '2024-12-17 23:16:45', '2024-12-17 23:16:45', NULL),
(7, 1818754028683614, 'Sylhet', 'সিলেট', 3110479, 3110479, NULL, '2024-12-17 23:16:45', '2024-12-17 23:16:45', NULL),
(8, 1818754028689730, 'Mymensingh', 'ময়মনসিংহ', 3110479, 3110479, NULL, '2024-12-17 23:16:45', '2024-12-17 23:16:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `profile_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `father_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mother_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `picture` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `nid` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mobile_number` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `present_address` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `permanent_address` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `division_id` bigint UNSIGNED NOT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `section_id` bigint UNSIGNED NOT NULL,
  `badgenumber` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `company_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `card_number` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_profile_id_unique` (`profile_id`),
  KEY `employees_division_id_foreign` (`division_id`),
  KEY `employees_department_id_foreign` (`department_id`),
  KEY `employees_section_id_foreign` (`section_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `profile_id`, `name`, `father_name`, `mother_name`, `dob`, `joining_date`, `picture`, `nid`, `mobile_number`, `present_address`, `permanent_address`, `division_id`, `department_id`, `section_id`, `badgenumber`, `company_id`, `card_number`, `status`, `created_at`, `updated_at`) VALUES
(2, 62, 'tuhin', 'hossain', 'selina', '1994-12-10', '2023-07-01', 'employee_pictures/IdWOQoApaB4wA7tQbka8hlhRFm49zFmqHELmTuqA.png', '12345678', '01534770999', 'hazrat-para-road,', 'Permanent Address', 2, 1, 6, '12', 'nano12', '123', 1, '2025-07-27 22:40:20', '2025-08-10 03:40:13'),
(1, 64, 'toha', 'toha father name', 'toha mother name', '2005-08-01', '2024-08-01', 'employee_pictures/IdWOQoApaB4wA7tQbka8hlhRFm49zFmqHELmTuqA.png', '1234', '01534770999', 'dhaka', 'chittagong', 2, 1, 6, '64', '1234', '1234', 1, NULL, '2025-08-10 03:40:12'),
(3, 58, 'Rumel', 'father_name', 'mother_name', '1995-08-01', NULL, 'employee_pictures/IdWOQoApaB4wA7tQbka8hlhRFm49zFmqHELmTuqA.png', '1234', '01534770999', 'dhaka', 'borishal', 3, 5, 12, '58', '123', '1234', 1, NULL, '2025-08-12 03:30:01'),
(4, 55, 'Mahfuz', 'father_name', 'mother_name', '1996-08-01', NULL, 'employee_pictures/IdWOQoApaB4wA7tQbka8hlhRFm49zFmqHELmTuqA.png', '123', '01534770999', 'dhaka', 'Rajshahi', 2, 3, 1, '55', '123', '12', 1, NULL, '2025-08-10 03:40:04'),
(5, 59, 'Rizvi', 'father_name Rizvi', 'mother_name Rizvi', '2015-08-06', NULL, NULL, '123', '01534770999', 'dhaka', 'Khulna', 3, 2, 1, '32', '987', '456', 1, NULL, '2025-08-10 03:40:08'),
(9, 100, 'ripon', 'ripon father', 'ripon mother', '2000-12-12', '2024-10-15', 'employee_pictures/4Pq9EfauU50KHKqN3fyxOQEfR4jwPO32VrltkY6b.jpg', '11111111111111', '9999999999999', 'hp gym center,moyenar bag road,dhaka,bangladesh', 'Permanent Address', 4, 6, 14, '1212', 'nanosoft', 'asdf', 1, '2025-08-12 02:48:16', '2025-08-12 03:11:02'),
(6, 57, 'Rasel Ahmed', 'Rasel Ahmed father_name', 'Rasel Ahmed mother_name', '1985-08-01', NULL, 'employee_pictures/IdWOQoApaB4wA7tQbka8hlhRFm49zFmqHELmTuqA.png', '56745', '01718366277', 'dhaka', 'Laksham', 2, 3, 3, '3453', '5673', '78978', 1, NULL, '2025-08-10 03:40:07'),
(7, 51, 'kutub', 'kutub father_name', 'kutub mother_name', '1999-01-01', NULL, 'employee_pictures/IdWOQoApaB4wA7tQbka8hlhRFm49zFmqHELmTuqA.png', '56715', '01544770888', 'dhaka', 'Rongpur', 4, 7, 16, '34534534', '54344', '723', 1, NULL, '2025-08-12 03:26:55'),
(8, 52, 'Rakesh', 'father_name Rakesh', 'father_name Rakesh', '1991-01-01', '2024-08-01', 'employee_pictures/IdWOQoApaB4wA7tQbka8hlhRFm49zFmqHELmTuqA.png', '344', '444', 'Gajipur', 'Narshingdi', 2, 2, 2, '666', '333', '555', 1, NULL, '2025-08-10 03:40:05'),
(10, 211, 'ripon', 'father_name', NULL, NULL, NULL, NULL, NULL, '01534770999', 'hp gym center,moyenar bag road,dhaka,bangladesh', NULL, 2, 1, 6, NULL, 'nanosoft', NULL, 1, '2025-08-12 02:52:15', '2025-08-12 03:06:20');

-- --------------------------------------------------------

--
-- Table structure for table `employee_group`
--

DROP TABLE IF EXISTS `employee_group`;
CREATE TABLE IF NOT EXISTS `employee_group` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_group_employee_id_unique` (`employee_id`),
  KEY `employee_group_group_id_foreign` (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `employee_group`
--

INSERT INTO `employee_group` (`id`, `group_id`, `employee_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NULL, NULL),
(15, 3, 7, NULL, NULL),
(4, 2, 3, NULL, NULL),
(14, 2, 5, NULL, NULL),
(17, 1, 1, NULL, NULL),
(16, 3, 8, NULL, NULL);

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
  `group_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `shift_id` bigint UNSIGNED NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `flexible_in_time` int DEFAULT NULL,
  `flexible_out_time` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_group_name_unique` (`group_name`),
  KEY `groups_shift_id_foreign` (`shift_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `group_name`, `description`, `shift_id`, `status`, `flexible_in_time`, `flexible_out_time`, `created_at`, `updated_at`) VALUES
(1, 'g1', 'Description', 21, 1, 15, 15, '2025-09-15 23:20:46', '2025-09-15 23:20:46');

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `group_special_workdays`
--

INSERT INTO `group_special_workdays` (`id`, `group_id`, `special_workingday_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 2, 1, NULL, NULL);

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `holiday_name`, `start_date`, `end_date`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Janmashtami', '2025-08-16', NULL, '3rd half', 1, '2025-07-22 06:03:20', '2025-07-22 06:03:20'),
(2, '15 roe august.', '2025-08-15', '2025-08-15', 'qwqe', 1, NULL, '2025-07-30 22:24:14'),
(3, '15 august', '2025-08-15', '2025-08-15', 'sd', 1, '2025-07-30 06:30:10', '2025-07-30 06:30:10'),
(5, 'july day', '2025-08-05', NULL, 'sdf', 1, '2025-08-03 00:07:21', '2025-08-03 00:07:21');

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
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

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
(12, '2025_07_09_195112_groups', 4),
(13, '2025_07_13_085205_work_day_group', 5),
(24, '2025_07_16_060939_special_working_days', 12),
(25, '2025_07_16_063955_create_group_special_workdays_table', 13),
(38, '2025_07_22_053601_create_check_in_outs_table', 21),
(32, '2025_07_20_042610_create_db_location_settings_table', 17),
(33, '2025_07_20_101539_create_attendance_statuses_table', 18),
(36, '2025_07_09_194434_employee_group', 20),
(41, '2025_07_22_112036_create_holidays_table', 22),
(67, '2023_09_21_142101_create_districts_table', 32),
(66, '2023_09_21_142029_create_divisions_table', 32),
(51, '2025_07_24_083657_create_employees_table', 26),
(52, '2025_08_06_131351_add_flexible_time_to_groups_table', 27),
(65, '2025_05_21_183235_create_shift_settings_table', 31),
(68, '2023_09_21_142126_create_upazilas_table', 32),
(69, '2023_09_21_142202_create_teachers_table', 32),
(70, '2023_09_23_073501_create_students_table', 33),
(71, '2023_11_04_062551_create_designations_table', 33);

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
  `shift_name_en` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `shift_name_bn` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `start_time` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `end_time` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `eiin` bigint DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shift_settings_uid_unique` (`uid`),
  UNIQUE KEY `shift_settings_branch_code_shift_name_en_unique` (`branch_code`,`shift_name_en`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `shift_settings`
--

INSERT INTO `shift_settings` (`id`, `uid`, `branch_code`, `shift_name_en`, `shift_name_bn`, `start_time`, `end_time`, `description`, `eiin`, `status`, `created_at`, `updated_at`) VALUES
(27, 1843512983038465, 91, 'Tuhin', 'বাংলা', '07:00', '22:00', 'ffff', 1, 1, '2025-09-17 06:09:43', '2025-09-17 06:09:58'),
(21, 1843396486426437, 100, 'Morning Shift', 'সকালের শিফট', '06:00 AM', '12:00 PM', 'Morning Shift description', 123, 1, '2025-09-15 23:18:04', '2025-09-15 23:18:04'),
(22, 1843396552154259, 100, 'Evening Shift', 'বিকালের শিফট', '02:00 PM', '08:00 PM', 'Description', 123, 1, '2025-09-15 23:19:06', '2025-09-15 23:19:06'),
(23, 1843396575146735, 101, 'Morning Shift', 'সকালের শিফট', '06:00 AM', '12:00 PM', 'Description', 123, 1, '2025-09-15 23:19:28', '2025-09-15 23:19:28'),
(24, 1843396597712227, 101, 'Evening Shift', 'বিকালের শিফট', '02:00 PM', '08:00 PM', 'Description', 123, 1, '2025-09-15 23:19:50', '2025-09-15 23:19:50'),
(25, 1843512202644701, 91, 'shift-branch-test', 'বাংলা', '09:00 AM', '06:30 PM', 'ffff', 1, 1, '2025-09-17 05:57:19', '2025-09-17 05:57:19');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `special_working_days`
--

INSERT INTO `special_working_days` (`id`, `date`, `day_type`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, '2025-08-01', 1, 'Special Working day 1 description', 1, NULL, NULL);

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
  `student_unique_id` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `uid` bigint NOT NULL,
  `caid` bigint DEFAULT NULL,
  `type` tinyint DEFAULT NULL,
  `incremental_no` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `student_name_en` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `student_name_bn` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `brid` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `reg_status` tinyint NOT NULL DEFAULT '0' COMMENT '0=pending,1=temp,2=registered',
  `scroll_num` bigint DEFAULT NULL,
  `registration_year` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `religion` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `birth_place` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `board_reg_no` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `recent_study_class` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `disability_status` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `blood_group` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `student_mobile_no` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ethnic_info` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `branch` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `version` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `shift` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `class` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `section` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `group` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `roll` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `is_regular` tinyint DEFAULT NULL,
  `father_name_en` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `father_name_bn` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `father_nid` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `father_brid` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `father_date_of_birth` date DEFAULT NULL,
  `father_mobile_no` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mother_name_en` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mother_name_bn` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mother_nid` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mother_brid` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mother_date_of_birth` date DEFAULT NULL,
  `mother_mobile_no` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `guardian_name_bn` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `guardian_name_en` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `guardian_mobile_no` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `guardian_nid` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `guardian_occupation` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `disability_types` text COLLATE utf8mb3_unicode_ci,
  `disability_file` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `relation_with_guardian` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `present_address` text COLLATE utf8mb3_unicode_ci,
  `permanent_address` text COLLATE utf8mb3_unicode_ci,
  `post_office` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `division_id` bigint DEFAULT NULL,
  `district_id` bigint DEFAULT NULL,
  `upazilla_id` bigint DEFAULT NULL,
  `unions` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8mb3_unicode_ci,
  `br_file` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `role` varchar(30) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `data_source` tinyint DEFAULT NULL,
  `student_source` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
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
  `index_number` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `type` tinyint DEFAULT NULL,
  `incremental_no` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `name_en` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `name_bn` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fathers_name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mothers_name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mobile_no` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `institute_name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `institute_type` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `institute_category` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `workstation_name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `branch_institute_name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `branch_institute_category` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `service_break_institute` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `designation` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `designation_id` int DEFAULT NULL,
  `division_id` int DEFAULT NULL,
  `district_id` int DEFAULT NULL,
  `upazilla_id` int DEFAULT NULL,
  `is_foreign` tinyint NOT NULL DEFAULT '0' COMMENT '1=foreign,0=local',
  `country` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `joining_year` int DEFAULT NULL,
  `mpo_code` int DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `last_working_date` date DEFAULT NULL,
  `nid` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `blood_group` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `emergency_contact` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8mb3_unicode_ci,
  `role` varchar(30) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ismpo` tinyint DEFAULT NULL,
  `isactive` tinyint DEFAULT NULL,
  `data_source` tinyint DEFAULT NULL,
  `teacher_source` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
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
  `district_id` bigint DEFAULT NULL,
  `upazila_id` bigint DEFAULT NULL,
  `upazila_name_en` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `upazila_name_bn` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_by` bigint DEFAULT NULL,
  `updated_by` bigint DEFAULT NULL,
  `deleted_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `upazilas_uid_unique` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `work_days`
--

INSERT INTO `work_days` (`id`, `day_name`, `is_weekend`, `created_at`, `updated_at`) VALUES
(1, 'Sunday', 0, NULL, NULL),
(2, 'Monday', 0, '2025-07-22 22:34:50', '2025-07-22 22:34:50'),
(3, 'Tuesday', 0, '2025-07-22 22:34:57', '2025-07-22 22:34:57'),
(4, 'Wednesday', 0, '2025-07-22 22:35:36', '2025-07-22 22:35:36'),
(5, 'Thursday', 0, '2025-07-22 22:36:26', '2025-07-22 22:36:26'),
(6, 'Friday', 1, '2025-07-22 22:36:37', '2025-07-22 22:51:32'),
(13, 'Saturday', 1, '2025-07-31 02:53:43', '2025-07-31 02:53:43');

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
) ENGINE=MyISAM AUTO_INCREMENT=109 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `work_day_group`
--

INSERT INTO `work_day_group` (`id`, `group_id`, `work_day_id`, `created_at`, `updated_at`) VALUES
(75, 18, 2, NULL, NULL),
(79, 19, 2, NULL, NULL),
(106, 2, 4, NULL, NULL),
(6, 2, 2, NULL, NULL),
(74, 18, 1, NULL, NULL),
(78, 18, 5, NULL, NULL),
(77, 18, 4, NULL, NULL),
(76, 18, 3, NULL, NULL),
(80, 20, 2, NULL, NULL),
(81, 21, 2, NULL, NULL),
(82, 22, 3, NULL, NULL),
(83, 23, 2, NULL, NULL),
(85, 1, 2, NULL, NULL),
(92, 3, 3, NULL, NULL),
(91, 3, 2, NULL, NULL),
(90, 3, 1, NULL, NULL),
(94, 3, 5, NULL, NULL),
(95, 4, 1, NULL, NULL),
(96, 4, 2, NULL, NULL),
(97, 4, 3, NULL, NULL),
(98, 4, 4, NULL, NULL),
(99, 4, 5, NULL, NULL),
(100, 1, 3, NULL, NULL),
(108, 2, 6, NULL, NULL),
(107, 2, 5, NULL, NULL),
(105, 1, 1, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
