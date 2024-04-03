-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2020 at 05:44 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_template`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetEmployeeaccounts` (IN `empid` VARCHAR(20), IN `acc` VARCHAR(20))  SELECT DISTINCT acc.username,acc.password,acc.fname,acc.lname,acc.email,acc.position,dept.dept_name,acc.status,concat(acc.fname,' ',acc.lname) as name, ac.access
FROM accounts acc
LEFT JOIN department dept on acc.dept_id=dept.dept_id
LEFT JOIN access ac on acc.access=ac.id
WHERE acc.emp_id=empid and acc.access=acc$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetEmployeeSystemAccess` (IN `empid` VARCHAR(50))  NO SQL
SELECT 
b.id,b.access,c.id as sys_id,c.system_code,c.system_name,c.fa_code,b.link,a.ip_add
FROM system_access a 
LEFT JOIN access b on a.access_id=b.id
LEFT JOIN system c on a.sys_id=c.id
WHERE a.emp_id=empid

ORDER BY c.id ASC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Login` (IN `user` VARCHAR(30), IN `pass` VARCHAR(30))  BEGIN
 SELECT * 
 FROM accounts where username=user and PASSWORD=pass and status=true;
 END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

CREATE TABLE `access` (
  `id` int(11) NOT NULL,
  `access` varchar(20) DEFAULT NULL,
  `sys_id` varchar(20) DEFAULT NULL,
  `link` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `access`
--

INSERT INTO `access` (`id`, `access`, `sys_id`, `link`) VALUES
(1, 'requestor', '1', '../requestor/index.php'),
(2, 'approval', '1', '../approval/index.php'),
(3, 'viewer', '1', '../viewer/index.php'),
(4, 'admin', '2', '../admin/index.php'),
(5, 'fm', '2', '../fm/index.php'),
(6, 'pm', '2', '../pm/index.php'),
(7, 'ac', '2', '../ac/index.php'),
(8, 'stu', '2', '../stu/index.php'),
(9, 'mfpt', '2', '../mfpt/index.php'),
(10, 'guest', '2', '../guest/index.php'),
(11, 'operator', '1', '../operator/index.php'),
(12, 'tools_requestor', '3', '../tools_req/index.php'),
(13, 'tools_approval', '3', '../tools_app/index.php'),
(14, 'tech_leader', '5', '../ptms_app/index.php'),
(15, 'tech_req', '5', '../ptms_req/index.php');

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `fname` varchar(50) DEFAULT NULL,
  `lname` varchar(50) DEFAULT NULL,
  `emp_id` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `access` varchar(50) DEFAULT NULL,
  `position` varchar(20) DEFAULT NULL,
  `dept_id` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `app_email_seq` varchar(20) DEFAULT NULL,
  `auto_email_seq` varchar(20) DEFAULT NULL,
  `sec_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `password`, `fname`, `lname`, `emp_id`, `email`, `access`, `position`, `dept_id`, `status`, `app_email_seq`, `auto_email_seq`, `sec_id`) VALUES
(1, 'ADMIN', '12345', 'SYSTEM', 'ADMIN', 'ADMIN', 's.sedutan@glory.com.ph', '4', '0', '115', 1, '0', '0', '0');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `dept_id` varchar(20) DEFAULT NULL,
  `dept_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `dept_id`, `dept_name`) VALUES
(1, '101', 'PRODUCTION1'),
(2, '102', 'PRODUCTION2'),
(3, '103', 'PRODUCTION MANAGEMENT'),
(4, '104', 'PURCHASING'),
(5, '105', 'QUALITY CONTROL'),
(6, '106', 'PRODUCTION SUPPORT'),
(7, '107', 'WAREHOUSE'),
(8, '108', 'PARTS INSPECTION'),
(9, '109', 'MOLDING'),
(10, '110', 'FABRICATION'),
(11, '111', 'ACCOUNTING'),
(12, '112', 'PRODUCTION TECHNOLOGY'),
(13, '113', 'QUALITY ASSURANCE'),
(14, '114', 'ADMINISTRATION'),
(15, '115', 'SYSTEM KAIZEN');

-- --------------------------------------------------------

--
-- Table structure for table `system`
--

CREATE TABLE `system` (
  `id` int(11) NOT NULL,
  `system_code` varchar(20) DEFAULT NULL,
  `system_name` varchar(200) DEFAULT NULL,
  `fa_code` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `system`
--

INSERT INTO `system` (`id`, `system_code`, `system_name`, `fa_code`) VALUES
(1, 'PSMS', 'PRODUCTION SCHEDULE MONITORING SYSTEM', 'fa-calendar-alt'),
(2, 'PQS', 'PRODUCT QUOTATION SYSTEM', 'fa-comments-dollar'),
(3, 'PSTMS', 'PRODUCTION SUPPORT TOOLS MONITORING SYSTEM', 'fa-tools'),
(4, 'POF', 'PARTS ORDER FORM SYSTEM', 'fa-cart-arrow-down'),
(5, 'PTMS', 'PRODUCTION TECHNICIAN MONITORING SYSTEM', 'fa-wrench');

-- --------------------------------------------------------

--
-- Table structure for table `system_access`
--

CREATE TABLE `system_access` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(50) DEFAULT NULL,
  `access_id` varchar(20) DEFAULT NULL,
  `sys_id` varchar(20) DEFAULT NULL,
  `ip_add` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `system_access`
--

INSERT INTO `system_access` (`id`, `emp_id`, `access_id`, `sys_id`, `ip_add`) VALUES
(1, 'ADMIN', '4', '1', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_id` (`emp_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dept_id` (`dept_id`);

--
-- Indexes for table `system`
--
ALTER TABLE `system`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_access`
--
ALTER TABLE `system_access`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access`
--
ALTER TABLE `access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `system`
--
ALTER TABLE `system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `system_access`
--
ALTER TABLE `system_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
