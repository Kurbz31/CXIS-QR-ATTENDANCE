-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 02, 2026 at 02:34 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `company_attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE IF NOT EXISTS `attendance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `employee_code` varchar(20) NOT NULL,
  `time_in` datetime DEFAULT NULL,
  `time_out` datetime DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id` (`employee_id`,`date`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `employee_id`, `employee_code`, `time_in`, `time_out`, `date`) VALUES
(1, 5, 'CXIS-0005', '2026-02-28 06:19:56', '2026-02-28 06:19:57', '2026-02-27'),
(2, 6, 'CXIS-0006', '2026-02-28 06:31:53', '2026-02-28 06:32:48', '2026-02-27'),
(3, 7, 'CXIS-0007', '2026-02-28 06:38:01', '2026-02-28 06:40:39', '2026-02-27');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_code` varchar(100) DEFAULT NULL,
  `emp_id` varchar(50) NOT NULL,
  `fullname` varchar(150) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `qr_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_code` (`employee_code`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_code`, `emp_id`, `fullname`, `profile_pic`, `department`, `qr_path`, `created_at`) VALUES
(5, 'CXIS-0005', '', 'romero kerbien', NULL, 'jddm agent', 'qrcodes/CXIS-0005.png', '2026-02-27 22:09:45'),
(4, 'CXIS-0004', '', 'kerbien Romero', NULL, 'JDDM', 'qrcodes/CXIS-0004.png', '2026-02-27 22:09:35'),
(6, 'CXIS-0006', '', 'cxis', NULL, 'JDDM', 'qrcodes/CXIS-0006.png', '2026-02-27 22:30:57'),
(7, 'CXIS-0007', 'CXIS0005', 'cxis1', 'uploads/69a21c9321049.png', 'JDDM', 'qrcodes/CXIS-0007.png', '2026-02-27 22:37:07');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
