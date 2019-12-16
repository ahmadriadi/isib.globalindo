-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2019 at 10:12 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `isib_public_history`
--
CREATE DATABASE IF NOT EXISTS `isib_public_history` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `isib_public_history`;

-- --------------------------------------------------------

--
-- Table structure for table `cron01invitation`
--

DROP TABLE IF EXISTS `cron01invitation`;
CREATE TABLE IF NOT EXISTS `cron01invitation` (
`ID` int(11) NOT NULL,
  `ParamCron` int(1) NOT NULL DEFAULT '1' COMMENT '1=All Customer, 2 = Custome Customer',
  `FlagSendEmail` int(1) NOT NULL DEFAULT '0' COMMENT '0 = Not Yet Send, 1= Already Send',
  `ScheduleDate` date DEFAULT NULL,
  `FromEmail` varchar(50) DEFAULT NULL,
  `ToEmail` text,
  `CcEmail` text,
  `SubjectEmail` varchar(150) NOT NULL,
  `MessageEmail` text,
  `NoteEmail` text,
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
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL,
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `his01sendmailinvitation`
--

DROP TABLE IF EXISTS `his01sendmailinvitation`;
CREATE TABLE IF NOT EXISTS `his01sendmailinvitation` (
`ID` int(11) NOT NULL,
  `ParamSender` varchar(10) DEFAULT NULL,
  `ScheduleDate` date DEFAULT NULL,
  `FromEmail` varchar(50) DEFAULT NULL,
  `ToEmail` text,
  `CcEmail` text,
  `SubjectEmail` varchar(150) NOT NULL,
  `MessageEmail` text,
  `NoteEmail` text,
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL,
  `DeleteBy` varchar(20) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteIP` varchar(20) DEFAULT NULL,
  `DeleteFlag` varchar(1) DEFAULT 'A'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `m01personal`
--

DROP TABLE IF EXISTS `m01personal`;
CREATE TABLE IF NOT EXISTS `m01personal` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(10) DEFAULT NULL,
  `IDEmployeeParent` varchar(10) DEFAULT NULL,
  `IDEmployeePTL` varchar(15) DEFAULT NULL COMMENT 'IDEmployee Project Team Leader',
  `FullName` varchar(70) DEFAULT NULL,
  `NickName` varchar(15) DEFAULT NULL,
  `BirthPlace` varchar(30) DEFAULT NULL,
  `BirthDate` date DEFAULT NULL,
  `Gender` varchar(10) DEFAULT NULL,
  `BloodType` varchar(5) DEFAULT NULL,
  `Citizenship` varchar(25) DEFAULT NULL,
  `Height` int(3) DEFAULT NULL,
  `Weight` int(3) DEFAULT NULL,
  `Religion` varchar(20) DEFAULT NULL,
  `MaritalStatus` varchar(10) DEFAULT NULL,
  `MarriageCertificate` varchar(20) DEFAULT NULL,
  `CoupleName` varchar(30) DEFAULT NULL,
  `CoupleKTP` varchar(75) DEFAULT NULL,
  `FamilyMemberCertificate` varchar(5) DEFAULT NULL,
  `NoKTP` varchar(75) DEFAULT NULL,
  `NoAKDHK` varchar(60) DEFAULT NULL,
  `NoNPWP` varchar(20) DEFAULT NULL,
  `NoJamsostek` varchar(20) DEFAULT NULL,
  `NoKPJ` varchar(20) DEFAULT NULL,
  `BankAccount` varchar(30) DEFAULT NULL,
  `LiveAddress` varchar(250) DEFAULT NULL,
  `KTPAddress` varchar(250) DEFAULT NULL,
  `NoHP` varchar(200) DEFAULT NULL COMMENT 'bisa lebih dari satu, dipisahkan dengan koma(,)',
  `LiveAddressNoTelp` varchar(200) DEFAULT NULL COMMENT 'bisa lebih dari satu, dipisahkan dengan koma(,)',
  `KTPAddressNoTelp` varchar(200) DEFAULT NULL COMMENT 'bisa lebih dari satu, dipisahkan dengan koma(,)',
  `NumberChildren` int(3) DEFAULT NULL,
  `InternalEmail` varchar(100) DEFAULT NULL,
  `ExternalEmail` text COMMENT 'bisa lebih dari satu, dipisahkan dengan koma(,)',
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` date DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL,
  `F1` int(11) DEFAULT NULL,
  `F1s1` int(1) DEFAULT NULL,
  `F1s2` int(1) DEFAULT NULL,
  `F1s3` int(1) DEFAULT NULL,
  `F1s4` int(1) DEFAULT NULL,
  `F1s5` int(1) DEFAULT NULL,
  `F2` int(11) DEFAULT NULL,
  `F2f1` int(1) DEFAULT NULL,
  `F2f2` int(1) DEFAULT '0',
  `F3` int(11) DEFAULT NULL,
  `F4` int(11) DEFAULT NULL,
  `F5` int(11) DEFAULT NULL,
  `F6` int(11) DEFAULT NULL,
  `F7` int(11) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='versi baru m01 personal';

--
-- Dumping data for table `m01personal`
--

INSERT INTO `m01personal` (`ID`, `IDEmployee`, `IDEmployeeParent`, `IDEmployeePTL`, `FullName`, `NickName`, `BirthPlace`, `BirthDate`, `Gender`, `BloodType`, `Citizenship`, `Height`, `Weight`, `Religion`, `MaritalStatus`, `MarriageCertificate`, `CoupleName`, `CoupleKTP`, `FamilyMemberCertificate`, `NoKTP`, `NoAKDHK`, `NoNPWP`, `NoJamsostek`, `NoKPJ`, `BankAccount`, `LiveAddress`, `KTPAddress`, `NoHP`, `LiveAddressNoTelp`, `KTPAddressNoTelp`, `NumberChildren`, `InternalEmail`, `ExternalEmail`, `EditedBy`, `EditedDate`, `EditedIP`, `F1`, `F1s1`, `F1s2`, `F1s3`, `F1s4`, `F1s5`, `F2`, `F2f1`, `F2f2`, `F3`, `F4`, `F5`, `F6`, `F7`, `NoBPJSEmp`, `NoBPJSHlt`, `NoFamCert`, `LiveProvince`, `LiveCity`, `LiveSubdistrict`, `LiveVillage`, `LiveRW`, `LiveRT`, `KTPProvince`, `KTPCity`, `KTPSubdistrict`, `KTPVillage`, `KTPRT`, `KTPRW`, `LivePostalCode`, `KTPPostalCode`, `DeletedBy`, `DeletedIP`, `DeletedDate`, `DeleteFlag`, `HistBy`, `HistDate`, `HistIP`, `IDTable`, `FunctionOn`) VALUES
(1, '0001141219', '', NULL, 'ADMINISTRATOR', 'ADMIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A', '0001141219', '2019-12-14 07:30:34', '::1', 700, 'save_personal (on public)');

-- --------------------------------------------------------

--
-- Table structure for table `m01personal_course`
--

DROP TABLE IF EXISTS `m01personal_course`;
CREATE TABLE IF NOT EXISTS `m01personal_course` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(10) DEFAULT NULL,
  `IDCourse` varchar(5) DEFAULT NULL,
  `CourseProgram` varchar(30) DEFAULT NULL,
  `CourseFacilitator` varchar(100) DEFAULT NULL,
  `City` varchar(70) DEFAULT NULL,
  `Duration` varchar(15) DEFAULT NULL,
  `YearFrom` int(11) DEFAULT NULL,
  `YearUntil` int(11) DEFAULT NULL,
  `DeletedBy` varchar(20) DEFAULT NULL,
  `DeletedIP` varchar(20) DEFAULT NULL,
  `DeletedDate` datetime DEFAULT NULL,
  `DeleteFlag` varchar(1) DEFAULT NULL,
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL,
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `m01personal_education`
--

DROP TABLE IF EXISTS `m01personal_education`;
CREATE TABLE IF NOT EXISTS `m01personal_education` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(10) DEFAULT NULL,
  `IDEducation` varchar(5) DEFAULT NULL,
  `EducationLevel` varchar(10) DEFAULT NULL,
  `Course` varchar(80) DEFAULT NULL,
  `SchoolName` varchar(100) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `YearFrom` int(11) DEFAULT NULL,
  `YearUntil` int(11) DEFAULT NULL,
  `Certificate` varchar(5) DEFAULT NULL,
  `DeletedBy` varchar(20) DEFAULT NULL,
  `DeletedIP` varchar(20) DEFAULT NULL,
  `DeletedDate` datetime DEFAULT NULL,
  `DeleteFlag` varchar(1) DEFAULT NULL,
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL,
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `m01personal_family`
--

DROP TABLE IF EXISTS `m01personal_family`;
CREATE TABLE IF NOT EXISTS `m01personal_family` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(10) DEFAULT NULL,
  `IDFamily` varchar(5) DEFAULT NULL,
  `NoKTP` varchar(75) DEFAULT NULL,
  `FamilyMember` varchar(15) DEFAULT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `BirthPlace` varchar(50) DEFAULT NULL,
  `BirthDate` date DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `Address` varchar(200) DEFAULT NULL,
  `Education` varchar(10) DEFAULT NULL,
  `Occupation` varchar(30) DEFAULT NULL,
  `DeletedBy` varchar(20) DEFAULT NULL,
  `DeletedIP` varchar(20) DEFAULT NULL,
  `DeletedDate` datetime DEFAULT NULL,
  `DeleteFlag` varchar(1) DEFAULT NULL,
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL,
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `m01personal_job`
--

DROP TABLE IF EXISTS `m01personal_job`;
CREATE TABLE IF NOT EXISTS `m01personal_job` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(20) DEFAULT NULL,
  `IDEmployeeParent` varchar(20) DEFAULT NULL,
  `IDEmployeePTL` varchar(15) DEFAULT NULL COMMENT 'IDEmployee Project Team Leader',
  `Location` varchar(20) DEFAULT NULL,
  `JobGroup` varchar(5) DEFAULT NULL,
  `Department` varchar(50) DEFAULT NULL,
  `Position` varchar(20) DEFAULT NULL,
  `Unit` varchar(30) DEFAULT NULL,
  `DateFirstJoin` date DEFAULT NULL,
  `DateStartProbation` date DEFAULT NULL,
  `DateEndProbation` date DEFAULT NULL,
  `DatePassProbation` date DEFAULT NULL,
  `DateNewContract` date DEFAULT NULL,
  `DateEndContract` date DEFAULT NULL,
  `DateInField` date DEFAULT NULL,
  `Status` varchar(2) DEFAULT NULL,
  `HireDate` date DEFAULT NULL,
  `FlagHire` int(11) DEFAULT NULL,
  `ResignDate` date DEFAULT NULL,
  `FlagResign` varchar(3) DEFAULT NULL,
  `EmployeeStatus` varchar(20) DEFAULT NULL,
  `ResignReason` text,
  `Note` varchar(200) DEFAULT NULL,
  `DeletedBy` varchar(20) DEFAULT NULL,
  `DeletedIP` varchar(20) DEFAULT NULL,
  `DeletedDate` datetime DEFAULT NULL,
  `DeleteFlag` varchar(1) DEFAULT NULL,
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL,
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `m01personal_job`
--

INSERT INTO `m01personal_job` (`ID`, `IDEmployee`, `IDEmployeeParent`, `IDEmployeePTL`, `Location`, `JobGroup`, `Department`, `Position`, `Unit`, `DateFirstJoin`, `DateStartProbation`, `DateEndProbation`, `DatePassProbation`, `DateNewContract`, `DateEndContract`, `DateInField`, `Status`, `HireDate`, `FlagHire`, `ResignDate`, `FlagResign`, `EmployeeStatus`, `ResignReason`, `Note`, `DeletedBy`, `DeletedIP`, `DeletedDate`, `DeleteFlag`, `HistBy`, `HistDate`, `HistIP`, `IDTable`, `FunctionOn`) VALUES
(1, '0001141219', '', NULL, 'KAPUK', 'ST', '1', 'COMISSIONER', '', NULL, '2019-12-14', NULL, NULL, NULL, NULL, NULL, 'A', '2019-12-14', NULL, NULL, NULL, 'TETAP', NULL, NULL, NULL, NULL, NULL, 'A', '0001141219', '2019-12-14 07:30:45', '::1', 703, 'save_job (on public)'),
(2, '0001141219', '', 'undefined', 'KAPUK', 'ST', '1', 'DIRECTOR', '', NULL, '2019-12-14', NULL, NULL, NULL, NULL, NULL, 'A', '2019-12-14', NULL, NULL, NULL, 'TETAP', NULL, NULL, NULL, NULL, NULL, 'A', '0001141219', '2019-12-14 07:31:02', '::1', 703, 'save_job (on public)'),
(3, '0001141219', '', 'undefined', 'KAPUK', 'ST', '1', 'DIRECTOR', '', NULL, '2019-12-14', NULL, NULL, NULL, NULL, NULL, 'A', '2019-12-14', NULL, NULL, NULL, 'TETAP', NULL, NULL, NULL, NULL, NULL, 'A', '0001141219', '2019-12-14 07:31:12', '::1', 703, 'save_job (on public)'),
(4, '0001141219', '0506021112', 'undefined', 'KAPUK', 'ST', '1', 'DIRECTOR', '', NULL, '2019-12-14', NULL, NULL, NULL, NULL, NULL, 'A', '2019-12-14', NULL, NULL, NULL, 'TETAP', NULL, NULL, NULL, NULL, NULL, 'A', '0001141219', '2019-12-14 07:31:38', '::1', 703, 'save_job (on public)');

-- --------------------------------------------------------

--
-- Table structure for table `m01personal_language`
--

DROP TABLE IF EXISTS `m01personal_language`;
CREATE TABLE IF NOT EXISTS `m01personal_language` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(10) DEFAULT NULL,
  `IDLanguage` varchar(5) DEFAULT NULL,
  `Language` varchar(25) DEFAULT NULL,
  `Reading` varchar(10) DEFAULT NULL,
  `Listening` varchar(10) DEFAULT NULL,
  `Conversation` varchar(10) DEFAULT NULL,
  `Writing` varchar(10) DEFAULT NULL,
  `DeletedBy` varchar(20) DEFAULT NULL,
  `DeletedIP` varchar(20) DEFAULT NULL,
  `DeletedDate` datetime DEFAULT NULL,
  `DeleteFlag` varchar(1) DEFAULT NULL,
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL,
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `m01personal_workexp`
--

DROP TABLE IF EXISTS `m01personal_workexp`;
CREATE TABLE IF NOT EXISTS `m01personal_workexp` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(10) DEFAULT NULL,
  `IDWorkExp` varchar(5) DEFAULT NULL,
  `CompanyName` varchar(50) DEFAULT NULL,
  `CompanyAddress` varchar(200) DEFAULT NULL,
  `CompanyPhone` varchar(15) DEFAULT NULL,
  `Position` varchar(25) DEFAULT NULL,
  `WorkDuration` varchar(20) DEFAULT NULL,
  `DeletedBy` varchar(20) DEFAULT NULL,
  `DeletedIP` varchar(20) DEFAULT NULL,
  `DeletedDate` datetime DEFAULT NULL,
  `DeleteFlag` varchar(1) DEFAULT NULL,
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL,
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `m03organization`
--

DROP TABLE IF EXISTS `m03organization`;
CREATE TABLE IF NOT EXISTS `m03organization` (
`ID` int(11) NOT NULL,
  `IDStructure` int(5) DEFAULT NULL,
  `IDStructureParent` int(5) DEFAULT NULL,
  `RelType` varchar(2) DEFAULT NULL,
  `DescStructure` varchar(50) DEFAULT NULL,
  `Level` varchar(3) DEFAULT NULL,
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL,
  `DeleteBy` varchar(20) DEFAULT NULL,
  `DeleteFlag` varchar(3) DEFAULT NULL COMMENT 'Jika A maka di tampilkan jika D maka tidak di tampilkan',
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteIP` varchar(20) DEFAULT NULL,
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL,
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `p01emailroot`
--

DROP TABLE IF EXISTS `p01emailroot`;
CREATE TABLE IF NOT EXISTS `p01emailroot` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(15) DEFAULT NULL,
  `RootSite` varchar(2) DEFAULT NULL COMMENT '1(Kapuk),2(Bitung)',
  `Note` text,
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
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL,
  `IDTable` int(15) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='email untuk konfirmasi rootcause';

-- --------------------------------------------------------

--
-- Table structure for table `p02mailinvitation`
--

DROP TABLE IF EXISTS `p02mailinvitation`;
CREATE TABLE IF NOT EXISTS `p02mailinvitation` (
`ID` int(11) NOT NULL,
  `FromMail` text,
  `PasswordFromMail` text,
  `CCmail` text,
  `ReplyMail` text,
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
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL,
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `r02currency`
--

DROP TABLE IF EXISTS `r02currency`;
CREATE TABLE IF NOT EXISTS `r02currency` (
`ID` int(11) NOT NULL,
  `CurrencyCode` varchar(5) DEFAULT NULL,
  `CurrencyName` varchar(50) DEFAULT NULL,
  `Kurs` decimal(11,2) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL,
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
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL,
  `IDTable` int(15) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t01rootcause`
--

DROP TABLE IF EXISTS `t01rootcause`;
CREATE TABLE IF NOT EXISTS `t01rootcause` (
`ID` int(11) NOT NULL,
  `IDRoot` int(11) DEFAULT NULL,
  `IDLocation` int(2) DEFAULT NULL,
  `ComplainNote` text,
  `ComplainDate` date DEFAULT NULL,
  `RootCause` varchar(1) DEFAULT NULL,
  `ProblemNote` text,
  `SolutionNote` text,
  `SolutionDate` datetime DEFAULT NULL,
  `StatusProblem` varchar(1) NOT NULL DEFAULT '0' COMMENT '0(Waiting response), 1 (Solved), 2(Deferred), 3 (unsolved), 4 (In Progress), 5 (Reject), 6 (Reject By System)',
  `TypeProblem` varchar(1) DEFAULT NULL,
  `PIC` varchar(15) DEFAULT NULL,
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
  `ViewFlag` varchar(1) NOT NULL DEFAULT '1',
  `HoDConf` varchar(1) NOT NULL DEFAULT '2' COMMENT '0 : belum dikonfirmasi; 1: sudah dikonfirmasi; 2 : tidak butuh konfirmasi; ',
  `HoDConfDate` datetime DEFAULT NULL,
  `HodConfBy` varchar(20) DEFAULT NULL,
  `HodConfIP` varchar(20) DEFAULT NULL,
  `RejectNote` text,
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL,
  `IDTable` int(15) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table for handling problem';

-- --------------------------------------------------------

--
-- Table structure for table `t03customerinvitation_d`
--

DROP TABLE IF EXISTS `t03customerinvitation_d`;
CREATE TABLE IF NOT EXISTS `t03customerinvitation_d` (
`ID` int(11) NOT NULL,
  `IDH` int(11) NOT NULL,
  `Gender` int(1) NOT NULL DEFAULT '1',
  `VisitorName` varchar(35) DEFAULT NULL,
  `JobPosition` varchar(45) DEFAULT NULL,
  `EmailAddress` varchar(55) NOT NULL,
  `MobilePhone` varchar(50) NOT NULL,
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
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL,
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t03customerinvitation_h`
--

DROP TABLE IF EXISTS `t03customerinvitation_h`;
CREATE TABLE IF NOT EXISTS `t03customerinvitation_h` (
`ID` int(11) NOT NULL,
  `CustomerName` varchar(50) DEFAULT NULL,
  `Address` text,
  `NoTelp` varchar(35) DEFAULT NULL,
  `NoFax` varchar(35) DEFAULT NULL,
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
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL,
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cron01invitation`
--
ALTER TABLE `cron01invitation`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `his01sendmailinvitation`
--
ALTER TABLE `his01sendmailinvitation`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `m01personal`
--
ALTER TABLE `m01personal`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `m01personal_course`
--
ALTER TABLE `m01personal_course`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `m01personal_education`
--
ALTER TABLE `m01personal_education`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `m01personal_family`
--
ALTER TABLE `m01personal_family`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `m01personal_job`
--
ALTER TABLE `m01personal_job`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `m01personal_language`
--
ALTER TABLE `m01personal_language`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `m01personal_workexp`
--
ALTER TABLE `m01personal_workexp`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `m03organization`
--
ALTER TABLE `m03organization`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `p01emailroot`
--
ALTER TABLE `p01emailroot`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `p02mailinvitation`
--
ALTER TABLE `p02mailinvitation`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `r02currency`
--
ALTER TABLE `r02currency`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t01rootcause`
--
ALTER TABLE `t01rootcause`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t03customerinvitation_d`
--
ALTER TABLE `t03customerinvitation_d`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t03customerinvitation_h`
--
ALTER TABLE `t03customerinvitation_h`
 ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cron01invitation`
--
ALTER TABLE `cron01invitation`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `his01sendmailinvitation`
--
ALTER TABLE `his01sendmailinvitation`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `m01personal`
--
ALTER TABLE `m01personal`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `m01personal_course`
--
ALTER TABLE `m01personal_course`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `m01personal_education`
--
ALTER TABLE `m01personal_education`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `m01personal_family`
--
ALTER TABLE `m01personal_family`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `m01personal_job`
--
ALTER TABLE `m01personal_job`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `m01personal_language`
--
ALTER TABLE `m01personal_language`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `m01personal_workexp`
--
ALTER TABLE `m01personal_workexp`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `m03organization`
--
ALTER TABLE `m03organization`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `p01emailroot`
--
ALTER TABLE `p01emailroot`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `p02mailinvitation`
--
ALTER TABLE `p02mailinvitation`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `r02currency`
--
ALTER TABLE `r02currency`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t01rootcause`
--
ALTER TABLE `t01rootcause`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t03customerinvitation_d`
--
ALTER TABLE `t03customerinvitation_d`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t03customerinvitation_h`
--
ALTER TABLE `t03customerinvitation_h`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
