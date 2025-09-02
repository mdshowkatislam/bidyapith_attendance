-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 17, 2025 at 08:43 AM
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
-- Database: `attendance_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_statuses`
--

DROP TABLE IF EXISTS `attendance_statuses`;
CREATE TABLE IF NOT EXISTS `attendance_statuses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `short_form` varchar(5) COLLATE utf8mb3_unicode_ci NOT NULL,
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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
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
  `machine_id` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `in_time` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `out_time` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
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
  `key` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` tinyint NOT NULL,
  `db_location` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `db_location_settings_key_unique` (`key`),
  UNIQUE KEY `db_location_settings_value_unique` (`value`)
) ;

--
-- Dumping data for table `db_location_settings`
--

INSERT INTO `db_location_settings` (`id`, `key`, `value`, `db_location`, `created_at`, `updated_at`) VALUES
(1, 'sync_time', 1, 'tuhin', '2025-07-20 04:08:57', '2025-08-07 04:33:23');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `division_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `division_id`, `created_at`, `updated_at`) VALUES
(1, 'department-1', 2, '2025-07-27 00:41:43', '2025-07-27 00:42:22'),
(3, 'department-2', 2, '2025-07-27 00:42:38', '2025-07-27 00:42:38'),
(4, 'department-01', 3, '2025-07-27 02:55:31', '2025-07-27 02:55:31'),
(5, 'department-02', 3, '2025-07-27 02:55:43', '2025-07-27 02:55:43'),
(6, 'department-111', 4, '2025-07-27 02:56:06', '2025-07-27 02:56:35'),
(7, 'department-222', 4, '2025-07-27 02:56:26', '2025-07-27 02:56:45');

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

DROP TABLE IF EXISTS `divisions`;
CREATE TABLE IF NOT EXISTS `divisions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`id`, `name`, `created_at`, `updated_at`) VALUES
(2, 'division-a', '2025-07-27 00:21:51', '2025-07-27 00:21:59'),
(3, 'division-b', '2025-07-27 02:54:18', '2025-07-27 02:54:18'),
(4, 'division-c', '2025-07-27 02:54:28', '2025-07-27 02:54:28');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `profile_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `father_name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mother_name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `picture` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `nid` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `mobile_number` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `present_address` text COLLATE utf8mb3_unicode_ci,
  `permanent_address` text COLLATE utf8mb3_unicode_ci,
  `division_id` bigint UNSIGNED NOT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `section_id` bigint UNSIGNED NOT NULL,
  `badgenumber` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `company_id` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `card_number` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `employee_group`
--

INSERT INTO `employee_group` (`id`, `group_id`, `employee_id`, `created_at`, `updated_at`) VALUES
(27, 22, 7, NULL, NULL),
(3, 2, 3, NULL, NULL),
(26, 18, 5, NULL, NULL),
(18, 23, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
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
  `shift_id` bigint UNSIGNED NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `flexible_in_time` int DEFAULT NULL,
  `flexible_out_time` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_group_name_unique` (`group_name`),
  KEY `groups_shift_id_foreign` (`shift_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `group_name`, `description`, `shift_id`, `status`, `flexible_in_time`, `flexible_out_time`, `created_at`, `updated_at`) VALUES
(2, 'group-b', 'group b description', 2, 1, 10, 10, '2025-07-13 00:03:48', '2025-08-07 00:12:50'),
(19, 'group_a', 'group_a Description', 1, 1, NULL, NULL, '2025-08-07 00:51:17', '2025-08-07 00:51:17'),
(20, 'group_ad', 'group_ab Desdcription', 4, 1, NULL, NULL, '2025-08-07 00:53:25', '2025-08-07 00:53:25'),
(21, 'group_addf', 'group_ab Desdcriptionfff', 5, 1, NULL, NULL, '2025-08-07 00:57:58', '2025-08-07 00:57:58'),
(22, 'group-c', 'sdf', 2, 1, NULL, 10, '2025-08-07 01:01:47', '2025-08-07 01:01:47'),
(23, 'group-d', 'sdf', 2, 1, 20, 0, '0000-00-00 00:00:00', '2025-08-07 01:03:18');

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
  `holiday_name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
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
  `queue` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
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
  `id` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb3_unicode_ci,
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
  `migration` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_07_10_044942_create_personal_access_tokens_table', 1),
(5, '2025_05_21_183235_create_shift_settings_table', 2),
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
(43, '2025_07_24_075304_create_divisions_table', 24),
(44, '2025_07_24_075312_create_departments_table', 24),
(45, '2025_07_24_075331_create_sections_table', 24),
(51, '2025_07_24_083657_create_employees_table', 26),
(52, '2025_08_06_131351_add_flexible_time_to_groups_table', 27);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
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
  `tokenable_type` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb3_unicode_ci,
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
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
CREATE TABLE IF NOT EXISTS `sections` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `name`, `department_id`, `created_at`, `updated_at`) VALUES
(6, 'section-111', 1, '2025-07-27 02:57:33', '2025-07-27 02:57:33'),
(5, 'section-000', 1, '2025-07-27 02:57:19', '2025-07-27 02:57:19'),
(7, 'section-aaa', 3, '2025-07-27 02:57:50', '2025-07-27 02:57:50'),
(8, 'section-bbb', 3, '2025-07-27 02:58:03', '2025-07-27 02:58:03'),
(9, 'section-xxx', 4, '2025-07-27 02:58:23', '2025-07-27 02:58:23'),
(10, 'section-zzz', 4, '2025-07-27 02:58:41', '2025-07-27 02:58:41'),
(11, 'section-m', 5, '2025-07-27 02:59:03', '2025-07-27 02:59:03'),
(12, 'section-n', 5, '2025-07-27 02:59:16', '2025-07-27 02:59:16'),
(13, 'section-z1', 6, '2025-07-27 02:59:30', '2025-07-27 03:00:01'),
(14, 'section-z2', 6, '2025-07-27 02:59:52', '2025-07-27 02:59:52'),
(15, 'section-123', 7, '2025-07-27 03:00:22', '2025-07-27 03:00:22'),
(16, 'section-1234', 7, '2025-07-27 03:00:43', '2025-07-27 03:00:43');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb3_unicode_ci,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
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
  `shift_name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `start_time` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `end_time` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `shift_settings`
--

INSERT INTO `shift_settings` (`id`, `shift_name`, `start_time`, `end_time`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Morning Shift', '06:00', '01:00', '1ST half', 1, '2025-07-14 06:43:00', '2025-07-30 02:38:30'),
(2, 'Evening Shift', '12:30', '19:00', '2nd half', 1, '2025-07-14 21:54:18', '2025-07-14 21:54:18'),
(4, 'asfxx', '05:54', '17:57', 'test', 1, '2025-07-29 06:02:35', '2025-07-29 22:57:12'),
(5, 'asfdfxxx', '03:00', '22:00', 'asdf', 1, '2025-07-29 06:35:56', '2025-07-29 22:57:54'),
(6, 'qwexxssss', '10:00', '22:00', 'asdf', 1, '2025-07-29 06:44:59', '2025-07-30 00:40:49'),
(7, 'new shiftss', '12:01', '03:01', 'sdf', 1, '2025-07-29 23:01:11', '2025-07-30 00:28:50'),
(9, 'bbb', '01:42', '17:42', 'sdf', 1, '2025-07-30 00:43:02', '2025-07-30 02:33:03'),
(10, 'gggg', '02:47', '17:47', 'sdf', 1, '2025-07-30 00:47:48', '2025-07-30 00:47:48'),
(11, 'bbbxxxx', '03:39', '19:39', 'hhh', 1, '2025-07-30 02:39:19', '2025-07-30 02:39:19'),
(12, 'asfxxxx', '03:41', '18:41', 'sdf', 1, '2025-07-30 02:41:49', '2025-07-30 02:41:49');

-- --------------------------------------------------------

--
-- Table structure for table `special_working_days`
--

DROP TABLE IF EXISTS `special_working_days`;
CREATE TABLE IF NOT EXISTS `special_working_days` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `day_type` tinyint NOT NULL DEFAULT '1',
  `description` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
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
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
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
  `day_name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `work_day_group`
--

INSERT INTO `work_day_group` (`id`, `group_id`, `work_day_id`, `created_at`, `updated_at`) VALUES
(75, 18, 2, NULL, NULL),
(79, 19, 2, NULL, NULL),
(5, 2, 1, NULL, NULL),
(6, 2, 2, NULL, NULL),
(7, 2, 3, NULL, NULL),
(8, 2, 4, NULL, NULL),
(9, 2, 5, NULL, NULL),
(74, 18, 1, NULL, NULL),
(78, 18, 5, NULL, NULL),
(77, 18, 4, NULL, NULL),
(76, 18, 3, NULL, NULL),
(80, 20, 2, NULL, NULL),
(81, 21, 2, NULL, NULL),
(82, 22, 3, NULL, NULL),
(83, 23, 2, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
