-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2019 at 10:17 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `isib_attendance_history`
--
CREATE DATABASE IF NOT EXISTS `isib_attendance_history` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `isib_attendance_history`;

-- --------------------------------------------------------

--
-- Table structure for table `t12employeepicket`
--

DROP TABLE IF EXISTS `t12employeepicket`;
CREATE TABLE IF NOT EXISTS `t12employeepicket` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(15) DEFAULT NULL,
  `FromDate` date DEFAULT NULL,
  `UntilDate` date DEFAULT NULL,
  `Note` text,
  `StatusPicket` varchar(1) DEFAULT 'A',
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL,
  `DeleteBy` varchar(20) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteIP` varchar(20) DEFAULT NULL,
  `DeleteFlag` varchar(1) DEFAULT 'A',
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL,
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL,
  `RangePicket` int(1) DEFAULT '1' COMMENT '1 = One Day, 2 = More One Day'
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t12employeepicket`
--

INSERT INTO `t12employeepicket` (`ID`, `IDEmployee`, `FromDate`, `UntilDate`, `Note`, `StatusPicket`, `AddedBy`, `AddedDate`, `AddedIP`, `EditedBy`, `EditedDate`, `EditedIP`, `DeleteBy`, `DeleteDate`, `DeleteIP`, `DeleteFlag`, `IDTable`, `FunctionOn`, `HistBy`, `HistDate`, `HistIP`, `RangePicket`) VALUES
(1, '0101291101', '2015-07-13', '2015-07-14', 'PIKET LEBARAN', 'A', '0538131212', '2015-06-17 13:14:55', '192.168.0.117', '', '0000-00-00 00:00:00', '', NULL, NULL, NULL, 'A', 5, 'edit', '0538131212', '2015-06-17 13:21:56', '192.168.0.117', 2),
(2, '0101291101', '2015-07-13', '2015-07-15', 'PIKET LEBARAN', 'A', '0538131212', '2015-06-17 13:14:55', '192.168.0.117', '0538131212', '2015-06-17 13:21:56', '192.168.0.117', NULL, NULL, NULL, 'A', 5, 'edit', '0538131212', '2015-06-17 13:22:36', '192.168.0.117', 0),
(3, '0590021013', '2015-07-13', '2015-07-14', 'PIKET LEBARAN', 'A', '0538131212', '2015-06-17 13:20:38', '192.168.0.117', '', '0000-00-00 00:00:00', '', NULL, NULL, NULL, 'A', 10, 'edit', '0538131212', '2015-06-27 12:00:41', '192.168.0.117', 2),
(4, '0538131212', '2015-07-13', '2015-07-14', 'PIKET LEBARAN', 'A', '0538131212', '2015-06-17 13:18:02', '192.168.0.117', '', '0000-00-00 00:00:00', '', NULL, NULL, NULL, 'A', 6, 'edit', '0538131212', '2015-06-27 12:02:26', '192.168.0.117', 2),
(5, '0538131212', '2015-07-12', '2015-07-14', 'PIKET LEBARAN', 'A', '0538131212', '2015-06-17 13:18:02', '192.168.0.117', '0538131212', '2015-06-27 12:02:26', '192.168.0.117', NULL, NULL, NULL, 'A', 6, 'edit', '0538131212', '2015-06-27 12:03:08', '192.168.0.117', 0),
(6, '0590021013', '2015-07-13', '2015-07-15', 'PIKET LEBARAN', 'A', '0538131212', '2015-06-17 13:20:38', '192.168.0.117', '0538131212', '2015-06-27 12:00:41', '192.168.0.117', NULL, NULL, NULL, 'A', 10, 'edit', '0538131212', '2015-06-27 12:03:16', '192.168.0.117', 0),
(7, '0173160306', '2015-07-15', '2015-07-16', 'PIKET LEBARAN', 'A', '0538131212', '2015-06-17 13:19:30', '192.168.0.117', '', '0000-00-00 00:00:00', '', NULL, NULL, NULL, 'A', 8, 'edit', '0538131212', '2015-07-07 14:12:21', '192.168.0.117', 2),
(8, '0481101012', '2015-07-19', '2015-07-23', 'DINAS KE KOREA', 'A', '0538131212', '2015-07-10 16:16:29', '192.168.0.117', '', '0000-00-00 00:00:00', '', NULL, NULL, NULL, 'A', 15, 'edit', '0538131212', '2015-07-11 12:59:52', '192.168.0.117', 2),
(9, '0267190710', '2015-07-12', '2015-07-12', 'PIKET KAPUK', 'A', '0538131212', '2015-06-27 12:01:59', '192.168.0.117', '', '0000-00-00 00:00:00', '', NULL, NULL, NULL, 'A', 13, 'edit', '0538131212', '2015-07-13 11:58:59', '192.168.0.117', 1),
(10, '0594100214', '2015-07-13', '2015-07-13', 'PIKET LEBARAN', 'A', '0538131212', '2015-07-11 08:08:20', '192.168.0.117', '', '0000-00-00 00:00:00', '', NULL, NULL, NULL, 'A', 18, 'edit', '0538131212', '2015-07-13 11:59:10', '192.168.0.117', 1),
(11, '0481101012', '2015-07-19', '2015-07-24', 'DINAS KE KOREA', 'A', '0538131212', '2015-07-10 16:16:29', '192.168.0.117', '0538131212', '2015-07-11 12:59:52', '192.168.0.117', NULL, NULL, NULL, 'A', 15, 'edit', '0538131212', '2015-07-22 14:19:42', '192.168.0.117', 2),
(12, '0242131008', '2015-07-19', '2015-07-23', 'DINAS KE KOREA', 'A', '0538131212', '2015-07-10 16:17:15', '192.168.0.117', '', '0000-00-00 00:00:00', '', NULL, NULL, NULL, 'A', 16, 'edit', '0538131212', '2015-07-22 14:19:59', '192.168.0.117', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t12employeepicket`
--
ALTER TABLE `t12employeepicket`
 ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t12employeepicket`
--
ALTER TABLE `t12employeepicket`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
