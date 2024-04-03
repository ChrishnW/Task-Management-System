-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2024 at 08:24 AM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gtms`
--

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

CREATE TABLE `access` (
  `id` int(11) NOT NULL,
  `system_name` varchar(50) NOT NULL,
  `access` varchar(20) NOT NULL,
  `link` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `access`
--

INSERT INTO `access` (`id`, `system_name`, `access`, `link`) VALUES
(1, 'G-TMS', 'admin', '../admin/index.php'),
(2, 'G-TMS', 'employee', '../employee/index.php'),
(3, 'G-TMS', 'head', '../head/index.php'),
(4, 'G-TMS', 'attendance', '../attendance.php');

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `card` varchar(15) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `access` varchar(50) NOT NULL,
  `sec_id` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `card`, `username`, `password`, `fname`, `lname`, `email`, `access`, `sec_id`, `status`, `file_name`) VALUES
(1, '', 'ADMIN', '$2y$10$vCbT1dsVtnf/4imwCbNtO.IwwCiohrBNYBmIQVtpLQ8qatzQlDt1a', 'SYSTEM ADMIN', '', '', '1', 'INFOSYS', 1, ''),
(2, '0010013109', 'OBUGARIN', '$2y$10$qSP5WBslzovBEJM5gX789eRAsC6eNWnnpr5oROIjzHHXUyKMGX8Vi', 'OLIVE', 'BUGARIN', 'o.bugarin@glory.com.ph', '2', 'INFOSYS', 1, ''),
(3, '0008590093', 'KMARERO', '$2y$10$/HdOGamePH.JVuRaiBII9.cw1bbALJ3JY1gLZwXPMlSR.OSNafTk6', 'KEVIN', 'MARERO', 'mis.support@glory.com.ph', '2', 'INFOSEC', 1, ''),
(4, '0013667126', 'FRAMIREZ', '$2y$10$7cFyICzKEEjK/9q8dGmW9u5094j1SXbkgQQtjlU77nR0ILl022Uk2', 'FRANCIS', 'RAMIREZ', 'f.ramirez@glory.com.ph', '2', 'FEM', 0, ''),
(5, '0013667029', 'JNEMEDEZ', '$2y$10$zZtsGzID.XWahe18rm9.3eB23RlDGvOrdPhBKK00XiQWoQ9PWSKay', 'JONATHAN', 'NEMEDEZ', 'j.nemedez@glory.com.ph', '3', 'INFOSEC', 1, ''),
(6, '0014456178', 'BSOLOMON', '$2y$10$y8UDM08KOzb6lL2AIpS40etrmQgXjH.8xjw6UOYmZ37KlCBioL6fm', 'BOBBY JOHN', 'SOLOMON', 'b.solomon@glory.com.ph', '2', 'INFOSYS', 1, ''),
(7, '0014526271', 'ANEGRITE', '$2y$10$7cFyICzKEEjK/9q8dGmW9u5094j1SXbkgQQtjlU77nR0ILl022Uk2', 'ANTON', 'NEGRITE', 'a.negrite@glory.com.ph', '2', 'FEM', 0, ''),
(8, '0013574231', 'JSIERRA', '$2y$10$7cFyICzKEEjK/9q8dGmW9u5094j1SXbkgQQtjlU77nR0ILl022Uk2', 'JOHN CARLO', 'SIERRA', 'jc.sierra@glory.com.ph', '2', 'FEM', 0, ''),
(9, '0008502769', 'FAPIL', '$2y$10$pQj5/k1Pkq4LIBO64PrUyOYsmEQcOXdkdTnrE46MJBooW/f00Ee8m', 'FRANK', 'APIL', 'f.apil@glory.com.ph', '2', 'FEM', 0, ''),
(10, '0008565660', 'RMAGAT', '$2y$10$7cFyICzKEEjK/9q8dGmW9u5094j1SXbkgQQtjlU77nR0ILl022Uk2', 'ROEL', 'MAGAT', 'r.magat@glory.com.ph', '2', 'FEM', 0, ''),
(11, '0013590994', 'TESCAMILLAS', '$2y$10$7cFyICzKEEjK/9q8dGmW9u5094j1SXbkgQQtjlU77nR0ILl022Uk2', 'TRICY', 'ESCAMILLAS', 't.escamillas@glory.com.ph', '2', 'FEM', 0, ''),
(12, '0014517607', 'JNATUEL', '$2y$10$JCU6TA.W4QaKDqJApK0gMeEup6Sn1loggIBoIpSqpxabzwKtY11Pm', 'JONATHAN', 'NATUEL', 'j.natuel@glorylocal.com.ph', '2', 'FEM', 0, ''),
(13, '0013667132', 'RPARMA', '$2y$10$7cFyICzKEEjK/9q8dGmW9u5094j1SXbkgQQtjlU77nR0ILl022Uk2', 'RALPH GABRIEL', 'PARMA', 'r.parma@glorylocal.com.ph', '2', 'FEM', 0, ''),
(14, '0013572190', 'FVIVO', '$2y$10$aKvaHMcPbPSafb4mDCeQou0XF/PWwSNdPr4Qqs5YYwPrz79du5yLa', 'FELMHAR', 'VIVO', 'mis@glory.com.ph', '2', 'INFOSEC', 1, ''),
(15, '0008584956', 'COROZO', '$2y$10$KIhybB/o6Ax0cUBj7F1PwOv0GT1oQnAvQsvazYY6pqxJm2BUoLSmy', 'CEDRICK JAMES', 'OROZO', 'mis.dev@glory.com.ph', '2', 'INFOSYS', 1, ''),
(16, '0009727321', 'YDAGANTA', '$2y$10$l1NplX4LjiyGCVuf1XfGletkBFqytCoN9A./WI5bx22J/feCav.B.', 'YOSHIYUKI JOHN', 'DAGANTA', '', '2', 'INFOSEC', 1, ''),
(17, '0013618307', 'MARAGON', '$2y$10$BLKJwv9ZxbaMnLpfqamZ.OsPlsMrd7mYn6RGEGBTYzWzyBFDsUPt.', 'MARK ELY', 'ARAGON', '', '2', 'INFOSEC', 1, ''),
(18, '0010885149', 'ADOMO', '$2y$10$xsFYKI2ddf1ZQRlxFjMUNuIenZTSVdAhnZO2LHvFQR.duI3sZnCoW', 'AILEEN', 'DOMO', '', '2', 'INFOSEC', 1, ''),
(19, '0008582605', 'ELUMAGUI', '$2y$10$7cFyICzKEEjK/9q8dGmW9u5094j1SXbkgQQtjlU77nR0ILl022Uk2', 'EDVIR', 'LUMAGUI', '', '2', 'FEM', 0, ''),
(20, '0008641016', 'JRONDERO', '$2y$10$7cFyICzKEEjK/9q8dGmW9u5094j1SXbkgQQtjlU77nR0ILl022Uk2', 'JENMARK', 'RONDERO', '', '2', 'FEM', 0, ''),
(21, '0011444836', 'MBUENAVIDES', '$2y$10$7cFyICzKEEjK/9q8dGmW9u5094j1SXbkgQQtjlU77nR0ILl022Uk2', 'MARK JHASE', 'BUENAVIDES', '', '2', 'FEM', 0, ''),
(22, '0011974370', 'JALCALA', '$2y$10$v1xI1xa45X/aDTfxpgwNuOW7VIcZZBm/1slD443YAatozZ.66ufDy', 'JANSEL', 'ALCALA', '', '2', 'FEM', 0, ''),
(23, '0010873987', 'MLOPEZ', '$2y$10$sJNTCcZ/G76L.xmPQyvMTOSYCbIa.07OWKaIG5rw7sO4rCKcgnLgS', 'MARK LAWRENCE', 'LOPEZ', '', '2', 'FEM', 0, ''),
(24, '0014202206', 'CLOPEZ', '$2y$10$vSC5oW5M/CgfNBu8vkSdIeJotvSIYU.HuJtleP6NGXsnOFus5ZOgu', 'CHRISTIAN JOHN', 'LOPEZ', 'c.lopez@glorylocal.com.ph', '2', 'INFOSYS', 1, ''),
(25, '', 'ATTENDANCE', '$2y$10$Xsdu7.lY4Cwffdsp7krch.D2/0ZC.FRbRCsa3BWj9Nq5.tIxtmqsK', 'ATTENDANCE MONITORING', '', '', '4', 'INFOSEC', 1, ''),
(27, '0014510281', 'GCALALO', '$2y$10$KjRVrTpMYdJndhuS8wKUVuZ5X2Z6RGIANH9u4iTa2nUcGGzSk.IgG', 'GEMMA', 'CALALO', 'g.anda@glory.com.ph', '3', 'INFOSEC', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `card` varchar(15) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `automation`
--

CREATE TABLE `automation` (
  `module` varchar(255) CHARACTER SET utf8 NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `automation`
--

INSERT INTO `automation` (`module`, `status`) VALUES
('Daily Tasks', 1);

-- --------------------------------------------------------

--
-- Table structure for table `day_off`
--

CREATE TABLE `day_off` (
  `id` int(11) NOT NULL,
  `date_off` date NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `dept_id` varchar(20) DEFAULT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `dept_id`, `dept_name`, `status`) VALUES
(1, '101', 'PRODUCTION1', 1),
(2, '102', 'PRODUCTION2', 1),
(3, '103', 'PRODUCTION MANAGEMENT', 1),
(4, '104', 'PURCHASING', 1),
(5, '105', 'QUALITY CONTROL', 1),
(6, '106', 'PRODUCTION SUPPORT', 1),
(7, '107', 'WAREHOUSE', 1),
(8, '108', 'PARTS INSPECTION', 1),
(9, '109', 'MOLDING', 1),
(10, '110', 'FABRICATION', 1),
(11, '111', 'ACCOUNTING', 1),
(12, '112', 'PRODUCTION TECHNOLOGY', 1),
(13, '113', 'QUALITY ASSURANCE', 1),
(14, '114', 'ADMINISTRATION', 1),
(16, '115', 'INFORMATION SYSTEM AND SECURITY', 1);

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `id` int(11) NOT NULL,
  `sec_id` varchar(20) NOT NULL,
  `sec_name` varchar(50) NOT NULL,
  `dept_id` int(3) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`id`, `sec_id`, `sec_name`, `dept_id`, `status`) VALUES
(1, 'INFOSYS', 'INFORMATION SYSTEM', 115, 1),
(2, 'INFOSEC', 'INFORMATION SECURITY', 115, 1);

-- --------------------------------------------------------

--
-- Table structure for table `system_log`
--

CREATE TABLE `system_log` (
  `case #` int(11) NOT NULL,
  `action` varchar(5000) CHARACTER SET utf8 NOT NULL,
  `date_created` datetime NOT NULL,
  `user` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(50) NOT NULL,
  `task_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `task_class` varchar(255) NOT NULL,
  `task_details` varchar(255) DEFAULT NULL,
  `task_for` varchar(255) NOT NULL,
  `requirement_status` int(11) NOT NULL DEFAULT '0',
  `in_charge` varchar(150) CHARACTER SET utf8 NOT NULL,
  `submission` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='List of tasks of users';

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `task_name`, `task_class`, `task_details`, `task_for`, `requirement_status`, `in_charge`, `submission`) VALUES
(1, 'Responding to user helpdesk tickets', '1', 'System developers may also be involved in addressing user issues reported through helpdesk tickets.', 'INFOSYS', 1, 'BSOLOMON', 'Daily'),
(2, 'Responding to user helpdesk tickets', '1', 'System developers may also be involved in addressing user issues reported through helpdesk tickets.', 'INFOSYS', 1, 'OBUGARIN', 'Daily'),
(3, 'Responding to user helpdesk tickets', '1', 'System developers may also be involved in addressing user issues reported through helpdesk tickets.', 'INFOSYS', 1, 'COROZO', 'Daily'),
(4, 'Responding to user helpdesk tickets', '1', 'System developers may also be involved in addressing user issues reported through helpdesk tickets.', 'INFOSYS', 1, 'CLOPEZ', 'Daily'),
(5, 'Coding and Development', '1', 'Daily tasks typically involve coding, debugging, unit testing, and integration testing, where they translate requirements into functional software, identify and resolve errors, and ensure seamless operation across system components.', 'INFOSYS', 1, 'BSOLOMON', 'Daily'),
(6, 'Coding and Development', '1', 'Daily tasks typically involve coding, debugging, unit testing, and integration testing, where they translate requirements into functional software, identify and resolve errors, and ensure seamless operation across system components.', 'INFOSYS', 1, 'OBUGARIN', 'Daily'),
(7, 'Coding and Development', '1', 'Daily tasks typically involve coding, debugging, unit testing, and integration testing, where they translate requirements into functional software, identify and resolve errors, and ensure seamless operation across system components.', 'INFOSYS', 1, 'COROZO', 'Daily'),
(8, 'Coding and Development', '1', 'Daily tasks typically involve coding, debugging, unit testing, and integration testing, where they translate requirements into functional software, identify and resolve errors, and ensure seamless operation across system components.', 'INFOSYS', 1, 'CLOPEZ', 'Daily'),
(9, 'Collaboration & Planning', '1', 'Involve attending various meetings, discussing requirements, designs, and collaborating with colleagues through activities like pair programming to solve problems collectively.', 'INFOSYS', 1, 'BSOLOMON', 'Daily'),
(10, 'System monitoring', '1', 'Checking system logs for errors, performance issues, and security threats.', 'INFOSYS', 1, 'BSOLOMON', 'Daily'),
(11, 'System monitoring', '1', 'Checking system logs for errors, performance issues, and security threats.', 'INFOSYS', 1, 'OBUGARIN', 'Daily'),
(12, 'System monitoring', '1', 'Checking system logs for errors, performance issues, and security threats.', 'INFOSYS', 1, 'COROZO', 'Daily'),
(13, 'Database Backup', '1', 'Performing regular database backups is a critical task in maintaining data integrity and ensuring disaster recovery preparedness. ', 'INFOSYS', 1, 'BSOLOMON', 'Daily'),
(14, 'Database Backup', '1', 'Performing regular database backups is a critical task in maintaining data integrity and ensuring disaster recovery preparedness. ', 'INFOSYS', 1, 'OBUGARIN', 'Daily'),
(15, 'Database Backup', '1', 'Performing regular database backups is a critical task in maintaining data integrity and ensuring disaster recovery preparedness. ', 'INFOSYS', 1, 'COROZO', 'Daily'),
(16, 'Database Backup', '1', 'Performing regular database backups is a critical task in maintaining data integrity and ensuring disaster recovery preparedness. ', 'INFOSYS', 1, 'CLOPEZ', 'Daily'),
(17, 'Documentation & Maintenance', '3', 'Encompass writing technical documentation, managing version control, and staying updated on new technologies and best practices.', 'INFOSYS', 1, 'BSOLOMON', '4th week of Friday'),
(18, 'Documentation & Maintenance', '3', 'Encompass writing technical documentation, managing version control, and staying updated on new technologies and best practices.', 'INFOSYS', 1, 'OBUGARIN', '4th week of Friday'),
(19, 'Documentation & Maintenance', '3', 'Encompass writing technical documentation, managing version control, and staying updated on new technologies and best practices.', 'INFOSYS', 1, 'COROZO', '4th week of Friday'),
(20, 'Documentation & Maintenance', '3', 'Encompass writing technical documentation, managing version control, and staying updated on new technologies and best practices.', 'INFOSYS', 1, 'CLOPEZ', '4th week of Friday'),
(21, 'Summary of Project Progress Report', '3', 'Provide overview of the project including chanllenges and also line up of project.', 'INFOSYS', 1, 'BSOLOMON', '1st week of Friday'),
(22, 'Individual System Depeloment Monthly Progress', '3', 'Provide a report for individual system development progress.', 'INFOSYS', 1, 'OBUGARIN', '1st week of Tuesday'),
(23, 'Individual System Depeloment Monthly Progress', '3', 'Provide a report for individual system development progress.', 'INFOSYS', 1, 'COROZO', '1st week of Tuesday'),
(24, 'Individual System Depeloment Monthly Progress', '3', 'Provide a report for individual system development progress.', 'INFOSYS', 1, 'CLOPEZ', '1st week of Tuesday'),
(25, 'Backup Report', '3', 'Database and System Codes ', 'INFOSYS', 1, 'BSOLOMON', '1st week of Wednesday'),
(26, 'Helpdesk', '3', 'System User Support ', 'INFOSYS', 1, 'OBUGARIN', '1st week of Wednesday'),
(27, 'Responding to user helpdesk tickets', '1', 'Troubleshooting technical issues for end-users, such as password resets, software installation problems, and connectivity issues.', 'INFOSEC', 1, 'FVIVO', 'Daily'),
(28, 'Responding to user helpdesk tickets', '1', 'Troubleshooting technical issues for end-users, such as password resets, software installation problems, and connectivity issues.', 'INFOSEC', 1, 'MARAGON', 'Daily'),
(29, 'Responding to user helpdesk tickets', '1', 'Troubleshooting technical issues for end-users, such as password resets, software installation problems, and connectivity issues.', 'INFOSEC', 1, 'YDAGANTA', 'Daily'),
(30, 'Receiving and filing of helpdesk request', '1', 'Receive request thru phone call, e-mail, etc. and filing to Helpdesk as ticket.', 'INFOSEC', 1, 'MARAGON', 'Daily'),
(31, 'Receiving and filing of helpdesk request', '1', 'Receive request thru phone call, e-mail, etc. and filing to Helpdesk as ticket.', 'INFOSEC', 1, 'ADOMO', 'Daily'),
(32, 'Incident response', '1', 'Investigating and responding to security incidents.', 'INFOSEC', 1, 'KMARERO', 'Daily'),
(33, 'Security monitoring', '1', 'Identifying and responding to security threats and vulnerabilities.', 'INFOSEC', 1, 'KMARERO', 'Daily'),
(34, 'System and device monitoring', '1', 'Checking system and logs for errors and performance issues.', 'INFOSEC', 1, 'FVIVO', 'Daily'),
(35, 'Patch management', '1', 'Applying security patches and updates to software and hardware to address vulnerabilities.', 'INFOSEC', 1, 'KMARERO', 'Daily'),
(36, 'Patch management', '1', 'Applying security patches and updates to software and hardware to address vulnerabilities.', 'INFOSEC', 1, 'FVIVO', 'Daily'),
(37, 'Network monitoring', '1', 'Monitoring network traffic for suspicious activity and ensuring smooth operation.', 'INFOSEC', 1, 'KMARERO', 'Daily'),
(38, 'Maintaining user accounts and permissions', '1', 'Adding, modifying, and deleting user accounts as needed.', 'INFOSEC', 1, 'KMARERO', 'Daily'),
(39, 'Security Audit', '2', 'Auditing of server and  workstation, checking of backup,process,documents and policy.', 'INFOSEC', 1, 'KMARERO', 'Tuesday'),
(40, 'PMS ', '2', 'Optimizing system and hardware performance (software update,hdd defragment and hardware cleaning)', 'INFOSEC', 1, 'FVIVO', 'Thursday'),
(41, 'PMS ', '2', 'Optimizing system and hardware performance (software update,hdd defragment and hardware cleaning)', 'INFOSEC', 1, 'MARAGON', 'Thursday'),
(42, 'PMS ', '2', 'Optimizing system and hardware performance (software update,hdd defragment and hardware cleaning)', 'INFOSEC', 1, 'YDAGANTA', 'Thursday'),
(43, 'Server & Data backups', '2', 'Backing up critical data regularly to ensure it can be recovered in case of a disaster.', 'INFOSEC', 1, 'KMARERO', 'Friday'),
(44, 'Documentation', '2', 'Updating records,documents (ex. Email list,telephone, device, accounts etc.)', 'INFOSEC', 1, 'FVIVO', 'Friday'),
(45, 'Documentation', '2', 'Updating records,documents (ex. Email list,telephone, device, accounts etc.)', 'INFOSEC', 1, 'MARAGON', 'Friday'),
(46, 'Documentation', '2', 'Updating records,documents (ex. Email list,telephone, device, accounts etc.)', 'INFOSEC', 1, 'ADOMO', 'Friday'),
(47, 'Documentation', '2', 'Updating records,documents (ex. Email list,telephone, device, accounts etc.)', 'INFOSEC', 1, 'YDAGANTA', 'Friday'),
(48, 'Kaizen Improvement', '2', 'Continuous improvement, should be embedded into the IT management process.', 'INFOSEC', 1, 'FVIVO', 'Friday'),
(49, 'Kaizen Improvement', '2', 'Continuous improvement, should be embedded into the IT management process.', 'INFOSEC', 1, 'KMARERO', 'Friday'),
(50, 'Others ', '2', 'Checking consumable, payment processing for billing, purchase request.', 'INFOSEC', 1, 'ADOMO', 'Friday'),
(51, 'Security awareness training & BCP', '3', 'Educating users about cybersecurity best practices.', 'INFOSEC', 1, 'KMARERO', '1st week of Thursday'),
(52, 'Disposal', '3', 'Disposal of IT assets is crucial to ensure data security, environmental compliance, and resource recovery.', 'INFOSEC', 1, 'MARAGON', '1st week of Thursday'),
(53, 'Disposal', '3', 'Disposal of IT assets is crucial to ensure data security, environmental compliance, and resource recovery.', 'INFOSEC', 1, 'YDAGANTA', '1st week of Thursday'),
(54, 'Disposal', '3', 'Disposal of IT assets is crucial to ensure data security, environmental compliance, and resource recovery.', 'INFOSEC', 1, 'ADOMO', '1st week of Thursday'),
(55, 'Infosec: I', '3', 'Security Patch Approved Updates Report', 'INFOSEC', 1, 'KMARERO', '1st week of Wednesday'),
(56, 'Infosec: II', '3', 'Information Security Report', 'INFOSEC', 1, 'KMARERO', '1st week of Wednesday'),
(57, 'Infosec: III', '3', 'Backup Report ', 'INFOSEC', 1, 'KMARERO', '1st week of Wednesday'),
(58, 'MIS: IV', '3', 'Computer Masterlist Report', 'INFOSEC', 1, 'MARAGON', '1st week of Wednesday'),
(59, 'MIS: V', '3', 'Summary of IP Address Report', 'INFOSEC', 1, 'MARAGON', '1st week of Wednesday'),
(60, 'MIS: VI', '3', 'Helpdesk Report', 'INFOSEC', 1, 'FVIVO', '1st week of Wednesday'),
(61, 'MIS: VII', '3', 'PMS Report', 'INFOSEC', 1, 'FVIVO', '1st week of Wednesday'),
(62, 'MIS: Summary of email, telephone list and cctv', '3', 'Summary of email, telephone list and cctv', 'INFOSEC', 1, 'YDAGANTA', '1st week of Wednesday'),
(63, 'MIS: Summary of MIS budget and expenses', '3', 'Summary of MIS budget and expenses', 'INFOSEC', 1, 'ADOMO', '1st week of Wednesday'),
(64, 'MIS: Summary of Problem encountered and Disposal', '3', 'Summary of Problem encountered and Disposal', 'INFOSEC', 1, 'ADOMO', '1st week of Wednesday');

-- --------------------------------------------------------

--
-- Table structure for table `tasks_details`
--

CREATE TABLE `tasks_details` (
  `id` int(11) NOT NULL,
  `task_code` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `task_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `task_class` varchar(255) NOT NULL,
  `task_for` varchar(255) NOT NULL,
  `in_charge` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT 'This column is used to classify tasks assigned to users. Default is NULL.',
  `date_created` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `old_due` date DEFAULT NULL,
  `date_accomplished` datetime DEFAULT NULL,
  `achievement` int(11) DEFAULT NULL,
  `attachment` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `remarks` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `requirement_status` int(11) NOT NULL DEFAULT '0' COMMENT 'If this task requires an attachment, the value will be 1. ',
  `task_status` int(50) NOT NULL COMMENT 'The default is 1 which means it is active and 0 which means it is inactive',
  `approval_status` int(1) DEFAULT '0' COMMENT 'This column is used to separate the task that is the subject of the reschedule request. The default value is 0 and 1 if the request is enabled.',
  `reschedule` int(1) NOT NULL DEFAULT '0' COMMENT 'This column is used to track whether this task has been requested to be rescheduled or not, defaults to 0 and 1 if yes and 2 is used by system to make a auto request.',
  `resched_reason` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `head_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `head_note` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='List of deployed tasks of users';

-- --------------------------------------------------------

--
-- Table structure for table `task_class`
--

CREATE TABLE `task_class` (
  `id` int(50) NOT NULL,
  `task_class` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `task_class`
--

INSERT INTO `task_class` (`id`, `task_class`) VALUES
(1, 'DAILY ROUTINE'),
(2, 'WEEKLY ROUTINE'),
(3, 'MONTHLY ROUTINE'),
(4, 'ADDITIONAL TASK'),
(5, 'PROJECT');

-- --------------------------------------------------------

--
-- Table structure for table `task_list`
--

CREATE TABLE `task_list` (
  `id` int(50) NOT NULL,
  `task_name` varchar(150) NOT NULL,
  `task_details` varchar(500) DEFAULT NULL,
  `task_class` varchar(150) NOT NULL,
  `task_for` varchar(50) NOT NULL,
  `date_created` date NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='List of tasks registered';

--
-- Dumping data for table `task_list`
--

INSERT INTO `task_list` (`id`, `task_name`, `task_details`, `task_class`, `task_for`, `date_created`, `status`) VALUES
(1, 'Responding to user helpdesk tickets', 'System developers may also be involved in addressing user issues reported through helpdesk tickets.', '1', 'INFOSYS', '2024-03-27', 1),
(2, 'Coding and Development', 'Daily tasks typically involve coding, debugging, unit testing, and integration testing, where they translate requirements into functional software, identify and resolve errors, and ensure seamless operation across system components.', '1', 'INFOSYS', '2024-03-27', 1),
(3, 'Collaboration & Planning', 'Involve attending various meetings, discussing requirements, designs, and collaborating with colleagues through activities like pair programming to solve problems collectively.', '1', 'INFOSYS', '2024-03-27', 1),
(4, 'System monitoring', 'Checking system logs for errors, performance issues, and security threats.', '1', 'INFOSYS', '2024-03-27', 1),
(5, 'Database Backup', 'Performing regular database backups is a critical task in maintaining data integrity and ensuring disaster recovery preparedness. ', '1', 'INFOSYS', '2024-03-27', 1),
(6, 'Documentation & Maintenance', 'Encompass writing technical documentation, managing version control, and staying updated on new technologies and best practices.', '3', 'INFOSYS', '2024-03-27', 1),
(7, 'Summary of Project Progress Report', 'Provide overview of the project including chanllenges and also line up of project.', '3', 'INFOSYS', '2024-03-27', 1),
(8, 'Individual System Depeloment Monthly Progress', 'Provide a report for individual system development progress.', '3', 'INFOSYS', '2024-03-27', 1),
(9, 'Backup Report', 'Database and System Codes ', '3', 'INFOSYS', '2024-03-27', 1),
(10, 'Helpdesk', 'System User Support ', '3', 'INFOSYS', '2024-03-27', 1),
(11, 'Responding to user helpdesk tickets', 'Troubleshooting technical issues for end-users, such as password resets, software installation problems, and connectivity issues.', '1', 'INFOSEC', '2024-03-27', 1),
(12, 'Receiving and filing of helpdesk request', 'Receive request thru phone call, e-mail, etc. and filing to Helpdesk as ticket.', '1', 'INFOSEC', '2024-03-27', 1),
(13, 'Incident response', 'Investigating and responding to security incidents.', '1', 'INFOSEC', '2024-03-27', 1),
(14, 'Security monitoring', 'Identifying and responding to security threats and vulnerabilities.', '1', 'INFOSEC', '2024-03-27', 1),
(15, 'System and device monitoring', 'Checking system and logs for errors and performance issues.', '1', 'INFOSEC', '2024-03-27', 1),
(16, 'Patch management', 'Applying security patches and updates to software and hardware to address vulnerabilities.', '1', 'INFOSEC', '2024-03-27', 1),
(17, 'Network monitoring', 'Monitoring network traffic for suspicious activity and ensuring smooth operation.', '1', 'INFOSEC', '2024-03-27', 1),
(18, 'Maintaining user accounts and permissions', 'Adding, modifying, and deleting user accounts as needed.', '1', 'INFOSEC', '2024-03-27', 1),
(19, 'Security Audit', 'Auditing of server and  workstation, checking of backup,process,documents and policy.', '2', 'INFOSEC', '2024-03-27', 1),
(20, 'PMS ', 'Optimizing system and hardware performance (software update,hdd defragment and hardware cleaning)', '2', 'INFOSEC', '2024-03-27', 1),
(21, 'Server & Data backups', 'Backing up critical data regularly to ensure it can be recovered in case of a disaster.', '2', 'INFOSEC', '2024-03-27', 1),
(22, 'Documentation', 'Updating records,documents (ex. Email list,telephone, device, accounts etc.)', '2', 'INFOSEC', '2024-03-27', 1),
(23, 'Kaizen Improvement', 'Continuous improvement, should be embedded into the IT management process.', '2', 'INFOSEC', '2024-03-27', 1),
(24, 'Others ', 'Checking consumable, payment processing for billing, purchase request.', '2', 'INFOSEC', '2024-03-27', 1),
(25, 'Security awareness training & BCP', 'Educating users about cybersecurity best practices.', '3', 'INFOSEC', '2024-03-27', 1),
(26, 'Disposal', 'Disposal of IT assets is crucial to ensure data security, environmental compliance, and resource recovery.', '3', 'INFOSEC', '2024-03-27', 1),
(27, 'Infosec: I', 'Security Patch Approved Updates Report', '3', 'INFOSEC', '2024-03-27', 1),
(28, 'Infosec: II', 'Information Security Report', '3', 'INFOSEC', '2024-03-27', 1),
(29, 'Infosec: III', 'Backup Report ', '3', 'INFOSEC', '2024-03-27', 1),
(30, 'MIS: IV', 'Computer Masterlist Report', '3', 'INFOSEC', '2024-03-27', 1),
(31, 'MIS: V', 'Summary of IP Address Report', '3', 'INFOSEC', '2024-03-27', 1),
(32, 'MIS: VI', 'Helpdesk Report', '3', 'INFOSEC', '2024-03-27', 1),
(33, 'MIS: VII', 'PMS Report', '3', 'INFOSEC', '2024-03-27', 1),
(34, 'MIS: Summary of email, telephone list and cctv', 'Summary of email, telephone list and cctv', '3', 'INFOSEC', '2024-03-27', 1),
(35, 'MIS: Summary of MIS budget and expenses', 'Summary of MIS budget and expenses', '3', 'INFOSEC', '2024-03-27', 1),
(36, 'MIS: Summary of Problem encountered and Disposal', 'Summary of Problem encountered and Disposal', '3', 'INFOSEC', '2024-03-27', 1);

-- --------------------------------------------------------

--
-- Table structure for table `task_temp`
--

CREATE TABLE `task_temp` (
  `id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_details` varchar(255) CHARACTER SET utf8 NOT NULL,
  `task_class` int(11) NOT NULL,
  `task_for` varchar(255) NOT NULL,
  `in_charge` varchar(255) NOT NULL,
  `submission` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'Everyday',
  `status` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `day_off`
--
ALTER TABLE `day_off`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dept_id` (`dept_id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_log`
--
ALTER TABLE `system_log`
  ADD PRIMARY KEY (`case #`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks_details`
--
ALTER TABLE `tasks_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_class`
--
ALTER TABLE `task_class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_list`
--
ALTER TABLE `task_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_temp`
--
ALTER TABLE `task_temp`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access`
--
ALTER TABLE `access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `day_off`
--
ALTER TABLE `day_off`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `system_log`
--
ALTER TABLE `system_log`
  MODIFY `case #` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `tasks_details`
--
ALTER TABLE `tasks_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_class`
--
ALTER TABLE `task_class`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `task_list`
--
ALTER TABLE `task_list`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `task_temp`
--
ALTER TABLE `task_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
