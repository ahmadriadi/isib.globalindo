-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2019 at 10:13 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `isib_payroll_history`
--
CREATE DATABASE IF NOT EXISTS `isib_payroll_history` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `isib_payroll_history`;

-- --------------------------------------------------------

--
-- Table structure for table `m01fieldpayroll`
--

DROP TABLE IF EXISTS `m01fieldpayroll`;
CREATE TABLE IF NOT EXISTS `m01fieldpayroll` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(20) NOT NULL COMMENT 'NIP',
  `BankAccountNo` varchar(20) NOT NULL,
  `MonthlySalary` int(11) DEFAULT '0',
  `Insurance` int(11) DEFAULT '0',
  `BPJS` decimal(11,2) DEFAULT NULL,
  `DailySalary` int(11) DEFAULT '0',
  `OvertimePerHour` int(11) DEFAULT '0',
  `OvertimeMeal` int(11) DEFAULT '0',
  `OvertimeIncentivePaid` varchar(1) NOT NULL,
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL,
  `DeleteBy` varchar(20) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteIP` varchar(20) DEFAULT NULL,
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A',
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL,
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `m02personalloan_h`
--

DROP TABLE IF EXISTS `m02personalloan_h`;
CREATE TABLE IF NOT EXISTS `m02personalloan_h` (
`ID` int(11) NOT NULL,
  `FlagPaid` int(1) DEFAULT '0' COMMENT '0 = belum lunas,1 = sudah lunas',
  `IDEmployee` varchar(20) NOT NULL COMMENT 'NIP',
  `LoanDate` date DEFAULT NULL COMMENT 'Tanggal Pinjaman',
  `Amount` int(11) DEFAULT NULL COMMENT 'Besar Pinjaman',
  `InterestLaon` int(11) DEFAULT NULL,
  `InterestInstalment` varchar(11) DEFAULT NULL,
  `Instalment` int(11) DEFAULT NULL COMMENT 'Besar Cicilan',
  `Term` int(13) DEFAULT NULL COMMENT 'Lama Cicilan',
  `DateInstalment` date DEFAULT NULL COMMENT 'Tanggal Mulai Cicilan',
  `Note` text COMMENT 'Keperluan',
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL,
  `DeleteBy` varchar(20) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteIP` varchar(20) DEFAULT NULL,
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A',
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL,
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `prm02payroll`
--

DROP TABLE IF EXISTS `prm02payroll`;
CREATE TABLE IF NOT EXISTS `prm02payroll` (
`ID` int(11) NOT NULL,
  `SumDaySalary` decimal(11,2) DEFAULT NULL,
  `InsurancePercent` decimal(11,2) DEFAULT NULL,
  `BPJSPercent` decimal(11,2) DEFAULT NULL,
  `OvertimeWorkHour` decimal(11,2) DEFAULT NULL,
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
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A',
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL,
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t03addition`
--

DROP TABLE IF EXISTS `t03addition`;
CREATE TABLE IF NOT EXISTS `t03addition` (
`ID` int(11) NOT NULL COMMENT 'ID Records',
  `PostingDate` date NOT NULL,
  `IDEmployee` varchar(20) DEFAULT NULL,
  `Amount` decimal(11,2) DEFAULT NULL,
  `Parameter` varchar(20) DEFAULT NULL,
  `FlagEntry` varchar(10) DEFAULT NULL,
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
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A',
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL,
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t04deductionmanual`
--

DROP TABLE IF EXISTS `t04deductionmanual`;
CREATE TABLE IF NOT EXISTS `t04deductionmanual` (
`ID` int(11) NOT NULL COMMENT 'ID Records',
  `PostingDate` date NOT NULL,
  `IDEmployee` varchar(20) DEFAULT NULL,
  `Amount` decimal(11,2) DEFAULT NULL,
  `Parameter` varchar(20) NOT NULL,
  `FlagLoan` varchar(10) NOT NULL DEFAULT 'Standar' COMMENT 'Untuk mengetahui hasil system atau bukan',
  `Note` varchar(50) DEFAULT NULL,
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL,
  `DeleteBy` varchar(20) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteIP` varchar(20) DEFAULT NULL,
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A',
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL,
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t06insentif`
--

DROP TABLE IF EXISTS `t06insentif`;
CREATE TABLE IF NOT EXISTS `t06insentif` (
`ID` int(11) NOT NULL COMMENT 'ID Records',
  `IDEmployee` varchar(20) DEFAULT NULL,
  `Amount` decimal(11,2) DEFAULT NULL,
  `Status` varchar(5) DEFAULT NULL,
  `Note` varchar(50) DEFAULT NULL,
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL,
  `DeleteBy` varchar(20) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteIP` varchar(20) DEFAULT NULL,
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A',
  `IDTable` int(15) DEFAULT NULL,
  `FunctionOn` varchar(35) DEFAULT NULL,
  `HistBy` varchar(20) DEFAULT NULL,
  `HistDate` datetime DEFAULT NULL,
  `HistIP` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m01fieldpayroll`
--
ALTER TABLE `m01fieldpayroll`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `m02personalloan_h`
--
ALTER TABLE `m02personalloan_h`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `prm02payroll`
--
ALTER TABLE `prm02payroll`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t03addition`
--
ALTER TABLE `t03addition`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t04deductionmanual`
--
ALTER TABLE `t04deductionmanual`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t06insentif`
--
ALTER TABLE `t06insentif`
 ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `m01fieldpayroll`
--
ALTER TABLE `m01fieldpayroll`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `m02personalloan_h`
--
ALTER TABLE `m02personalloan_h`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prm02payroll`
--
ALTER TABLE `prm02payroll`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t03addition`
--
ALTER TABLE `t03addition`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Records';
--
-- AUTO_INCREMENT for table `t04deductionmanual`
--
ALTER TABLE `t04deductionmanual`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Records';
--
-- AUTO_INCREMENT for table `t06insentif`
--
ALTER TABLE `t06insentif`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Records';
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
