-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2019 at 10:16 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `isib_employee_history`
--
CREATE DATABASE IF NOT EXISTS `isib_employee_history` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `isib_employee_history`;

-- --------------------------------------------------------

--
-- Table structure for table `m01personal`
--

DROP TABLE IF EXISTS `m01personal`;
CREATE TABLE IF NOT EXISTS `m01personal` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(20) DEFAULT NULL,
  `IDEmployeeParent` varchar(20) DEFAULT NULL,
  `IDEmployeePTL` varchar(15) DEFAULT NULL COMMENT 'IDEmployee Project Team Leader',
  `FullName` varchar(100) DEFAULT NULL,
  `EmailExternal` text,
  `EmailInternal` varchar(100) DEFAULT NULL,
  `Gender` varchar(5) DEFAULT NULL,
  `BankAccount` varchar(50) DEFAULT NULL,
  `IDLocation` varchar(1) DEFAULT NULL,
  `IDJobGroup` varchar(5) DEFAULT NULL,
  `IDDepartement` varchar(60) DEFAULT NULL,
  `IDUnitGroup` varchar(20) DEFAULT NULL,
  `HireDate` date DEFAULT NULL,
  `FlagHire` int(2) DEFAULT NULL,
  `ResignDate` date DEFAULT NULL,
  `FlagResign` int(2) DEFAULT NULL,
  `Status` varchar(1) DEFAULT NULL,
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL,
  `PublicStatus` varchar(2) DEFAULT NULL,
  `DeletedBy` varchar(20) DEFAULT NULL,
  `DeletedIP` varchar(20) DEFAULT NULL,
  `DeletedDate` datetime DEFAULT NULL,
  `DeleteFlag` varchar(1) DEFAULT NULL,
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL,
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `m01personal`
--

INSERT INTO `m01personal` (`ID`, `IDEmployee`, `IDEmployeeParent`, `IDEmployeePTL`, `FullName`, `EmailExternal`, `EmailInternal`, `Gender`, `BankAccount`, `IDLocation`, `IDJobGroup`, `IDDepartement`, `IDUnitGroup`, `HireDate`, `FlagHire`, `ResignDate`, `FlagResign`, `Status`, `AddedBy`, `AddedDate`, `AddedIP`, `EditedBy`, `EditedDate`, `EditedIP`, `PublicStatus`, `DeletedBy`, `DeletedIP`, `DeletedDate`, `DeleteFlag`, `IDTable`, `FunctionOn`, `HistBy`, `HistDate`, `HistIP`) VALUES
(1, '0001141219', '', NULL, 'ADMINISTRATOR', '', '', NULL, '', '1', 'ST', '1', '', '2019-12-14', 0, NULL, 0, 'A', '0506021112', '2019-12-14 07:26:03', '::1', '', '0000-00-00 00:00:00', '', 'Y', NULL, NULL, NULL, 'A', 870, 'save_personal (on public)', '0001141219', '2019-12-14 07:30:34', '::1'),
(2, '0001141219', '', NULL, 'ADMINISTRATOR', '', '', 'M', '', '1', 'ST', '1', '', '2019-12-14', 0, NULL, 0, 'A', '0506021112', '2019-12-14 07:26:03', '::1', '', '0000-00-00 00:00:00', '', 'Y', NULL, NULL, NULL, 'A', 870, 'save_job (on public)', '0001141219', '2019-12-14 07:30:45', '::1'),
(3, '0001141219', '', 'undefined', 'ADMINISTRATOR', '', '', 'M', '', '1', 'ST', '1', '', '2019-12-14', 0, NULL, 0, 'A', '0506021112', '2019-12-14 07:26:03', '::1', '', '0000-00-00 00:00:00', '', 'Y', NULL, NULL, NULL, 'A', 870, 'save_job (on public)', '0001141219', '2019-12-14 07:31:02', '::1'),
(4, '0001141219', '', 'undefined', 'ADMINISTRATOR', '', '', 'M', '', '1', 'ST', '1', '', '2019-12-14', 0, NULL, 0, 'A', '0506021112', '2019-12-14 07:26:03', '::1', '', '0000-00-00 00:00:00', '', 'Y', NULL, NULL, NULL, 'A', 870, 'save_job (on public)', '0001141219', '2019-12-14 07:31:12', '::1'),
(5, '0001141219', '0506021112', 'undefined', 'ADMINISTRATOR', '', '', 'M', '', '1', 'ST', '1', '', '2019-12-14', 0, NULL, 0, 'A', '0506021112', '2019-12-14 07:26:03', '::1', '', '0000-00-00 00:00:00', '', 'Y', NULL, NULL, NULL, 'A', 870, 'save_job (on public)', '0001141219', '2019-12-14 07:31:38', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `m01personal_d`
--

DROP TABLE IF EXISTS `m01personal_d`;
CREATE TABLE IF NOT EXISTS `m01personal_d` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(20) DEFAULT NULL,
  `IDEmployeeParent` varchar(20) DEFAULT NULL,
  `IDEmployeePTL` varchar(15) DEFAULT NULL COMMENT 'IDEmployee Project Team Leader',
  `FullName` varchar(100) DEFAULT NULL,
  `BankAccount` varchar(50) DEFAULT NULL,
  `NoJamsostek` varchar(35) DEFAULT NULL,
  `NickName` varchar(20) DEFAULT NULL,
  `BirthPlace` varchar(50) DEFAULT NULL,
  `Citizenship` varchar(35) DEFAULT NULL,
  `BirthDate` date DEFAULT NULL,
  `Height` varchar(3) DEFAULT NULL,
  `Weight` int(3) DEFAULT NULL,
  `Religion` varchar(20) DEFAULT NULL,
  `IDEducation` varchar(20) DEFAULT NULL,
  `IDMajors` varchar(20) DEFAULT NULL,
  `MaritalStatus` varchar(20) DEFAULT NULL,
  `Gender` varchar(10) DEFAULT NULL,
  `MarriageCertificate` varchar(10) DEFAULT NULL,
  `FamilyMemberCertificate` varchar(10) DEFAULT NULL,
  `CoupleKTP` varchar(75) DEFAULT NULL,
  `NumberChildren` varchar(5) DEFAULT NULL,
  `FirstChild` varchar(50) DEFAULT NULL,
  `SecondChild` varchar(50) DEFAULT NULL,
  `CoupleName` varchar(30) DEFAULT NULL,
  `BloodType` varchar(5) DEFAULT NULL,
  `NoTelp` varchar(13) DEFAULT NULL,
  `NoHp` varchar(13) DEFAULT NULL,
  `NoNPWP` varchar(80) DEFAULT NULL,
  `NoKPJ` varchar(80) DEFAULT NULL,
  `NoKTP` varchar(80) DEFAULT NULL,
  `NoAKDHK` varchar(60) DEFAULT NULL,
  `LiveAddress` text,
  `LiveAddressNoTelp` varchar(50) DEFAULT NULL,
  `KTPAddress` text,
  `KTPAddressNoTelp` varchar(50) DEFAULT NULL,
  `WorkExperience` text,
  `IDJobPosition` varchar(50) DEFAULT NULL,
  `IDLocation` varchar(1) DEFAULT NULL,
  `IDJobGroup` varchar(5) DEFAULT NULL,
  `IDDepartement` varchar(60) DEFAULT NULL,
  `IDUnitGroup` varchar(20) DEFAULT NULL,
  `DateFirstJoint` date DEFAULT NULL,
  `DateStartProbation` date DEFAULT NULL,
  `DateEndProbation` date DEFAULT NULL,
  `DatePassProbation` date DEFAULT NULL,
  `DateNewContract` date DEFAULT NULL,
  `DateEndContract` date DEFAULT NULL,
  `DateInField` date DEFAULT NULL,
  `EmailInternal` varchar(100) DEFAULT NULL,
  `EmailExternal` text,
  `Extension` varchar(20) DEFAULT NULL,
  `HireDate` date DEFAULT NULL,
  `FlagHire` int(2) DEFAULT NULL,
  `ResignDate` date DEFAULT NULL,
  `FlagResign` int(2) DEFAULT NULL,
  `ReasonResign` text,
  `Status` varchar(1) DEFAULT NULL,
  `EmployeeStatus` varchar(20) DEFAULT NULL,
  `Note` text,
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL,
  `NoBPJSEmp` varchar(30) DEFAULT NULL,
  `NoBPJSHlt` varchar(30) DEFAULT NULL,
  `NoFamCert` varchar(30) DEFAULT NULL,
  `LiveProvince` varchar(100) DEFAULT NULL,
  `LiveCity` varchar(100) DEFAULT NULL,
  `LiveSubdistrict` varchar(100) DEFAULT NULL,
  `LiveVillage` varchar(100) DEFAULT NULL,
  `LiveRW` varchar(5) DEFAULT NULL,
  `LiveRT` varchar(5) DEFAULT NULL,
  `KTPProvince` varchar(100) DEFAULT NULL,
  `KTPCity` varchar(100) DEFAULT NULL,
  `KTPSubdistrict` varchar(100) DEFAULT NULL,
  `KTPVillage` varchar(100) DEFAULT NULL,
  `KTPRT` varchar(5) DEFAULT NULL,
  `KTPRW` varchar(5) DEFAULT NULL,
  `LivePostalCode` varchar(5) DEFAULT NULL,
  `KTPPostalCode` varchar(5) DEFAULT NULL,
  `DeletedBy` varchar(20) DEFAULT NULL,
  `DeletedIP` varchar(20) DEFAULT NULL,
  `DeletedDate` datetime DEFAULT NULL,
  `DeleteFlag` varchar(1) DEFAULT NULL,
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL,
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `m01personal_d`
--

INSERT INTO `m01personal_d` (`ID`, `IDEmployee`, `IDEmployeeParent`, `IDEmployeePTL`, `FullName`, `BankAccount`, `NoJamsostek`, `NickName`, `BirthPlace`, `Citizenship`, `BirthDate`, `Height`, `Weight`, `Religion`, `IDEducation`, `IDMajors`, `MaritalStatus`, `Gender`, `MarriageCertificate`, `FamilyMemberCertificate`, `CoupleKTP`, `NumberChildren`, `FirstChild`, `SecondChild`, `CoupleName`, `BloodType`, `NoTelp`, `NoHp`, `NoNPWP`, `NoKPJ`, `NoKTP`, `NoAKDHK`, `LiveAddress`, `LiveAddressNoTelp`, `KTPAddress`, `KTPAddressNoTelp`, `WorkExperience`, `IDJobPosition`, `IDLocation`, `IDJobGroup`, `IDDepartement`, `IDUnitGroup`, `DateFirstJoint`, `DateStartProbation`, `DateEndProbation`, `DatePassProbation`, `DateNewContract`, `DateEndContract`, `DateInField`, `EmailInternal`, `EmailExternal`, `Extension`, `HireDate`, `FlagHire`, `ResignDate`, `FlagResign`, `ReasonResign`, `Status`, `EmployeeStatus`, `Note`, `AddedBy`, `AddedDate`, `AddedIP`, `EditedBy`, `EditedDate`, `EditedIP`, `NoBPJSEmp`, `NoBPJSHlt`, `NoFamCert`, `LiveProvince`, `LiveCity`, `LiveSubdistrict`, `LiveVillage`, `LiveRW`, `LiveRT`, `KTPProvince`, `KTPCity`, `KTPSubdistrict`, `KTPVillage`, `KTPRT`, `KTPRW`, `LivePostalCode`, `KTPPostalCode`, `DeletedBy`, `DeletedIP`, `DeletedDate`, `DeleteFlag`, `HistBy`, `HistDate`, `HistIP`, `IDTable`, `FunctionOn`) VALUES
(1, '0001141219', '', NULL, 'ADMINISTRATOR', '', NULL, 'ADMIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'COMISSIONER', '1', 'ST', '1', '', NULL, '2019-12-14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-12-14', 0, NULL, 0, NULL, 'A', 'TETAP', '', '0506021112', '2019-12-14 07:26:03', '::1', '', '0000-00-00 00:00:00', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A', '0001141219', '2019-12-14 07:30:34', '::1', 872, 'save_personal (on public)'),
(2, '0001141219', '', NULL, 'ADMINISTRATOR', '', '', 'ADMIN', '', '', '1970-01-01', '', 0, 'ISLAM', NULL, NULL, 'undefined', 'M', '0', '0', '0', '0', NULL, NULL, '0', 'A', NULL, '', '', '', '', '', '', '''', '', '''', NULL, 'COMISSIONER', '1', 'ST', '1', '', NULL, '2019-12-14', NULL, NULL, NULL, NULL, NULL, '', '', NULL, '2019-12-14', 0, NULL, 0, NULL, 'A', 'TETAP', '', '0506021112', '2019-12-14 07:26:03', '::1', '', '0000-00-00 00:00:00', '', '-', '-', '-', 'null', 'null', 'null', 'null', '', '', 'null', 'null', 'null', 'null', '', '', '', '', NULL, NULL, NULL, 'A', '0001141219', '2019-12-14 07:30:45', '::1', 872, 'save_job (on public)'),
(3, '0001141219', '', 'undefined', 'ADMINISTRATOR', '', '', 'ADMIN', '', '', '1970-01-01', '', 0, 'ISLAM', NULL, NULL, 'undefined', 'M', '0', '0', '0', '0', NULL, NULL, '0', 'A', NULL, '', '', '', '', '', '', '''', '', '''', NULL, 'DIRECTOR', '1', 'ST', '1', '', NULL, '2019-12-14', NULL, NULL, NULL, NULL, NULL, '', '', NULL, '2019-12-14', 0, NULL, 0, NULL, 'A', 'TETAP', '', '0506021112', '2019-12-14 07:26:03', '::1', '', '0000-00-00 00:00:00', '', '-', '-', '-', 'null', 'null', 'null', 'null', '', '', 'null', 'null', 'null', 'null', '', '', '', '', NULL, NULL, NULL, 'A', '0001141219', '2019-12-14 07:31:02', '::1', 872, 'save_job (on public)'),
(4, '0001141219', '', 'undefined', 'ADMINISTRATOR', '', '', 'ADMIN', '', '', '1970-01-01', '', 0, 'ISLAM', NULL, NULL, 'undefined', 'M', '0', '0', '0', '0', NULL, NULL, '0', 'A', NULL, '', '', '', '', '', '', '''', '', '''', NULL, 'DIRECTOR', '1', 'ST', '1', '', NULL, '2019-12-14', NULL, NULL, NULL, NULL, NULL, '', '', NULL, '2019-12-14', 0, NULL, 0, NULL, 'A', 'TETAP', '', '0506021112', '2019-12-14 07:26:03', '::1', '', '0000-00-00 00:00:00', '', '-', '-', '-', 'null', 'null', 'null', 'null', '', '', 'null', 'null', 'null', 'null', '', '', '', '', NULL, NULL, NULL, 'A', '0001141219', '2019-12-14 07:31:12', '::1', 872, 'save_job (on public)'),
(5, '0001141219', '0506021112', 'undefined', 'ADMINISTRATOR', '', '', 'ADMIN', '', '', '1970-01-01', '', 0, 'ISLAM', NULL, NULL, 'undefined', 'M', '0', '0', '0', '0', NULL, NULL, '0', 'A', NULL, '', '', '', '', '', '', '''', '', '''', NULL, 'DIRECTOR', '1', 'ST', '1', '', NULL, '2019-12-14', NULL, NULL, NULL, NULL, NULL, '', '', NULL, '2019-12-14', 0, NULL, 0, NULL, 'A', 'TETAP', '', '0506021112', '2019-12-14 07:26:03', '::1', '', '0000-00-00 00:00:00', '', '-', '-', '-', 'null', 'null', 'null', 'null', '', '', 'null', 'null', 'null', 'null', '', '', '', '', NULL, NULL, NULL, 'A', '0001141219', '2019-12-14 07:31:38', '::1', 872, 'save_job (on public)');

-- --------------------------------------------------------

--
-- Table structure for table `r02holiday`
--

DROP TABLE IF EXISTS `r02holiday`;
CREATE TABLE IF NOT EXISTS `r02holiday` (
`IDHoliday` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Note` varchar(50) NOT NULL,
  `Flag` varchar(5) DEFAULT NULL COMMENT 'JIka berisi ALD maka lapangan di potong',
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL,
  `DeleteBy` varchar(20) DEFAULT NULL,
  `DeleteFlag` varchar(3) NOT NULL DEFAULT 'A' COMMENT 'Jika A maka di tampilkan jika D maka tidak di tampilkan',
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteIP` varchar(20) DEFAULT NULL,
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL,
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t04weeklyactivity`
--

DROP TABLE IF EXISTS `t04weeklyactivity`;
CREATE TABLE IF NOT EXISTS `t04weeklyactivity` (
`ID` int(11) NOT NULL,
  `JobActivity` text,
  `PIC` varchar(15) DEFAULT NULL,
  `DateLine` date DEFAULT NULL,
  `StatusActivity` int(2) NOT NULL DEFAULT '0' COMMENT '0 = Inprogress,1=Done,2=Pending',
  `Tested` int(2) DEFAULT '0' COMMENT '0 = Belum, 1 = Sudah',
  `TestedNote` text,
  `TestedBy` varchar(20) DEFAULT NULL,
  `TestedDate` datetime NOT NULL,
  `TestedIP` varchar(20) DEFAULT NULL,
  `Note` text,
  `AddedIP` varchar(20) DEFAULT NULL,
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `DeletedBy` varchar(20) DEFAULT NULL,
  `DeletedIP` varchar(20) DEFAULT NULL,
  `DeletedDate` datetime DEFAULT NULL,
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A',
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL,
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Tabel untuk program dateline pekerjaan mingguan';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m01personal`
--
ALTER TABLE `m01personal`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `m01personal_d`
--
ALTER TABLE `m01personal_d`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `r02holiday`
--
ALTER TABLE `r02holiday`
 ADD PRIMARY KEY (`IDHoliday`);

--
-- Indexes for table `t04weeklyactivity`
--
ALTER TABLE `t04weeklyactivity`
 ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `m01personal`
--
ALTER TABLE `m01personal`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `m01personal_d`
--
ALTER TABLE `m01personal_d`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `r02holiday`
--
ALTER TABLE `r02holiday`
MODIFY `IDHoliday` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t04weeklyactivity`
--
ALTER TABLE `t04weeklyactivity`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
