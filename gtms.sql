-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2023 at 01:26 AM
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
(1, 'GTMS', 'admin', '../admin/index.php'),
(2, 'GTMS', 'employee', '../employee/index.php'),
(3, 'GTMS', 'evaluator', '../evaluator/index.php');

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `access` varchar(50) NOT NULL,
  `sec_id` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `password`, `fname`, `lname`, `email`, `access`, `sec_id`, `status`) VALUES
(1, 'ADMIN', '12345', 'SYSTEM', 'ADMIN', 'b.solomon@glory.com.ph', '1', 'SK', 1),
(2, 'OBUGARIN', '12345', 'OLIVE', 'BUGARIN', 'o.bugarin@glory.com.ph', '2', 'SK', 1),
(3, 'KMARERO', '12345', 'KEVIN', 'MARERO', 'mis.support@glory.com.ph', '2', 'MIS', 1),
(4, 'FRAMIREZ', '12345', 'FRANCIS', 'RAMIREZ', 'f.ramirez@glory.com.ph', '2', 'FEM', 1),
(5, 'JNEMEDEZ', '12345', 'JONATHAN', 'NEMEDEZ', 'j.nemedez@glory.com.ph', '3', 'MIS', 1),
(6, 'BSOLOMON', '12345', 'BOBBY JOHN', 'SOLOMON', 'b.solomon@glory.com.ph', '2', 'SK', 1),
(7, 'ANEGRITE', '12345', 'ANTON', 'NEGRITE', 'a.negrite@glory.com.ph', '2', 'FEM', 1),
(8, 'JSIERRA', '12345', 'JOHN CARLO', 'SIERRA', 'jc.sierra@glory.com.ph', '2', 'FEM', 1),
(9, 'FAPIL', '12345', 'FRANK', 'APIL', 'f.apil@glory.com.ph', '2', 'FEM', 1),
(10, 'RMAGAT', '12345', 'ROEL', 'MAGAT', 'r.magat@glory.com.ph', '2', 'FEM', 1),
(11, 'TESCAMILLAS', '12345', 'TRICY', 'ESCAMILLAS', 't.escamillas@glory.com.ph', '2', 'FEM', 1),
(12, 'JNATUEL', '12345', 'JONATHAN', 'NATUEL', 'j.natuel@glorylocal.com.ph', '2', 'FEM', 1),
(13, 'RPARMA', '12345', 'RALPH GABRIEL', 'PARMA', 'r.parma@glorylocal.com.ph', '2', 'FEM', 1),
(14, 'FVIVO', '12345', 'FELMHAR', 'VIVO', '', '2', 'MIS', 1),
(15, 'COROZO', '12345', 'CEDRICK JAMES', 'OROZO', 'mis.dev@glory.com.ph', '2', 'MIS', 1),
(16, 'YDAGANTA', '12345', 'YOSHIYUKI JOHN', 'DAGANTA', '', '2', 'MIS', 1),
(17, 'MARAGON', '12345', 'MARK ELY', 'ARAGON', '', '2', 'MIS', 1),
(18, 'ADOMO', '12345', 'AILEEN', 'DOMO', '', '2', 'MIS', 1),
(19, 'ELUMAGUI', '12345', 'EDVIR', 'LUMAGUI', '', '2', 'FEM', 1),
(20, 'JRONDERO', '12345', 'JENMARK', 'RONDERO', '', '2', 'FEM', 1),
(21, 'MBUENAVIDES', '12345', 'MARK JHASE', 'BUENAVIDES', '', '2', 'FEM', 1),
(22, 'JALCALA', '12345', 'JANSEL', 'ALCALA', '', '2', 'FEM', 1),
(23, 'MLOPEZ', '12345', 'MARK LAWRENCE', 'LOPEZ', '', '2', 'FEM', 1),
(24, 'CLOPEZ', '12345', 'CHRISTIAN JOHN', 'LOPEZ', 'c.lopez@glorylocal.com.ph', '2', 'SK', 1);

-- --------------------------------------------------------

--
-- Table structure for table `day_off`
--

CREATE TABLE `day_off` (
  `id` int(11) NOT NULL,
  `date_off` date NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `day_off`
--

INSERT INTO `day_off` (`id`, `date_off`, `status`) VALUES
(1, '2023-11-28', 1),
(2, '2023-12-08', 1),
(3, '2023-12-09', 1),
(4, '2023-12-11', 0),
(5, '2023-12-07', 0),
(6, '2023-12-11', 1);

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `id` int(11) NOT NULL,
  `sec_id` varchar(20) NOT NULL,
  `sec_name` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`id`, `sec_id`, `sec_name`, `status`) VALUES
(1, 'SK', 'SYSTEM KAIZEN', 1),
(2, 'MIS', 'MANAGEMENT INFORMATION SYSTEM', 1),
(3, 'FEM', 'FACILITIES AND EQUIPMENT MAINTENANCE', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(50) NOT NULL,
  `task_code` varchar(150) NOT NULL,
  `in_charge` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tasks_details`
--

CREATE TABLE `tasks_details` (
  `id` int(11) NOT NULL,
  `task_code` varchar(50) NOT NULL,
  `date_created` date NOT NULL,
  `due_date` date NOT NULL,
  `in_charge` varchar(150) NOT NULL,
  `status` varchar(50) NOT NULL,
  `remarks` varchar(100) NOT NULL,
  `date_accomplished` date DEFAULT NULL,
  `task_status` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `task_code` varchar(50) NOT NULL,
  `task_name` varchar(150) NOT NULL,
  `task_details` varchar(500) DEFAULT NULL,
  `task_class` varchar(150) NOT NULL,
  `task_for` varchar(50) NOT NULL,
  `status` int(1) NOT NULL
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
-- Indexes for table `day_off`
--
ALTER TABLE `day_off`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access`
--
ALTER TABLE `access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `day_off`
--
ALTER TABLE `day_off`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `InsertToTask` ON SCHEDULE EVERY 1 DAY STARTS '2023-11-28 13:05:00' ON COMPLETION PRESERVE ENABLE DO IF NOT UTC_TIMESTAMP() IN (SELECT date_created from tasks_details) 
AND NOT DAYOFWEEK(UTC_TIMESTAMP()) IN (1) 
AND NOT UTC_TIMESTAMP() IN (SELECT date_off from day_off) THEN

INSERT INTO tasks_details(task_code,date_created,due_date,in_charge,status, date_accomplished, task_status)

SELECT DISTINCT task_code,UTC_TIMESTAMP(),UTC_TIMESTAMP(),in_charge,'NOT YET STARTED',null,true
FROM tasks
WHERE task_code LIKE '%TD%';
END IF$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
