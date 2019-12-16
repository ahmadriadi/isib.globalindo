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
-- Database: `isib_payroll`
--
CREATE DATABASE IF NOT EXISTS `isib_payroll` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `isib_payroll`;

-- --------------------------------------------------------

--
-- Table structure for table `additionalovertime`
--

DROP TABLE IF EXISTS `additionalovertime`;
CREATE TABLE IF NOT EXISTS `additionalovertime` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(15) NOT NULL,
  `Selisih` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `addtionalleave`
--

DROP TABLE IF EXISTS `addtionalleave`;
CREATE TABLE IF NOT EXISTS `addtionalleave` (
  `IDEmployee` varchar(30) NOT NULL DEFAULT '',
  `PostingDate` date NOT NULL DEFAULT '0000-00-00',
  `Amount` varchar(30) NOT NULL,
  `Parameter` varchar(15) DEFAULT NULL,
  `Note` varchar(50) DEFAULT NULL,
  `AddedBy` varchar(20) NOT NULL,
  `AddedDate` datetime NOT NULL,
  `AddedIP` varchar(20) NOT NULL,
  `EditedBy` varchar(20) NOT NULL,
  `EditedDate` datetime NOT NULL,
  `EditedIP` varchar(20) NOT NULL,
  `DeleteBy` varchar(20) NOT NULL,
  `DeleteDate` datetime NOT NULL,
  `DeleteIP` varchar(20) NOT NULL,
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `m02personalloan_d`
--

DROP TABLE IF EXISTS `m02personalloan_d`;
CREATE TABLE IF NOT EXISTS `m02personalloan_d` (
`ID` int(11) NOT NULL,
  `IDHeader` int(11) NOT NULL,
  `IDEmployee` varchar(20) NOT NULL COMMENT 'NIP',
  `InstallmentDate` date DEFAULT NULL COMMENT 'Tanggal Cicilan',
  `Installment` int(11) DEFAULT NULL COMMENT 'Besar Cicilan',
  `Note` text NOT NULL,
  `Flag` int(11) NOT NULL,
  `DeleteBy` varchar(20) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteIP` varchar(20) DEFAULT NULL,
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A',
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL
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
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `prm01insentive`
--

DROP TABLE IF EXISTS `prm01insentive`;
CREATE TABLE IF NOT EXISTS `prm01insentive` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(15) DEFAULT NULL,
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
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prm01insentive`
--

INSERT INTO `prm01insentive` (`ID`, `IDEmployee`, `Note`, `AddedBy`, `AddedDate`, `AddedIP`, `EditedBy`, `EditedDate`, `EditedIP`, `DeleteBy`, `DeleteDate`, `DeleteIP`, `DeleteFlag`) VALUES
(1, '0638100213', 'Jadwal piket yang sudah fix siang dan malam', '0538131212', '2014-11-25 16:01:21', '192.168.0.117', NULL, NULL, NULL, NULL, NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `prm02payroll`
--

DROP TABLE IF EXISTS `prm02payroll`;
CREATE TABLE IF NOT EXISTS `prm02payroll` (
`ID` int(11) NOT NULL,
  `SumDaySalary` int(2) DEFAULT NULL,
  `InsurancePercent` decimal(11,2) DEFAULT NULL,
  `BPJSPercent` decimal(11,2) DEFAULT NULL,
  `OvertimeWorkHour` int(11) DEFAULT NULL,
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
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prm02payroll`
--

INSERT INTO `prm02payroll` (`ID`, `SumDaySalary`, `InsurancePercent`, `BPJSPercent`, `OvertimeWorkHour`, `Note`, `AddedBy`, `AddedDate`, `AddedIP`, `EditedBy`, `EditedDate`, `EditedIP`, `DeleteBy`, `DeleteDate`, `DeleteIP`, `DeleteFlag`) VALUES
(1, 31, '2.00', '1.00', 173, 'Parameter Field Payroll ', '0506021112', '2015-01-28 15:38:43', '192.168.0.61', '0506021112', '2015-07-01 14:20:02', '192.168.0.61', NULL, NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `r01deduction`
--

DROP TABLE IF EXISTS `r01deduction`;
CREATE TABLE IF NOT EXISTS `r01deduction` (
`ID` int(11) NOT NULL,
  `CodeType` varchar(15) NOT NULL DEFAULT '',
  `Description` varchar(60) DEFAULT NULL,
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL,
  `DeleteBy` varchar(20) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteIP` varchar(20) DEFAULT NULL,
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `r01deduction`
--

INSERT INTO `r01deduction` (`ID`, `CodeType`, `Description`, `AddedBy`, `AddedDate`, `AddedIP`, `EditedBy`, `EditedDate`, `EditedIP`, `DeleteBy`, `DeleteDate`, `DeleteIP`, `DeleteFlag`) VALUES
(1, 'LOAN', 'LOAN', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'A'),
(2, 'OTHER', 'OTHER', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'A'),
(3, 'OUTSTANDING', 'OUTSTANDING', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `r02addition`
--

DROP TABLE IF EXISTS `r02addition`;
CREATE TABLE IF NOT EXISTS `r02addition` (
`ID` int(11) NOT NULL,
  `CodeType` varchar(15) NOT NULL,
  `Description` varchar(60) DEFAULT NULL,
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL,
  `DeleteBy` varchar(20) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteIP` varchar(20) DEFAULT NULL,
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `r02addition`
--

INSERT INTO `r02addition` (`ID`, `CodeType`, `Description`, `AddedBy`, `AddedDate`, `AddedIP`, `EditedBy`, `EditedDate`, `EditedIP`, `DeleteBy`, `DeleteDate`, `DeleteIP`, `DeleteFlag`) VALUES
(1, 'INSENTIF', 'INSENTIF', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'A'),
(2, 'SHIFT', 'INSENTIF SHIFT', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `t01dailysalary`
--

DROP TABLE IF EXISTS `t01dailysalary`;
CREATE TABLE IF NOT EXISTS `t01dailysalary` (
`ID` int(11) NOT NULL COMMENT 'ID Records',
  `PostingDate` date DEFAULT NULL COMMENT 'Tgl Awal Periode',
  `IDEmployee` varchar(20) DEFAULT NULL COMMENT 'Nomor Induk Pegawai',
  `PresenceDate` date DEFAULT NULL COMMENT 'Tgl Absensi',
  `PresenceStatus` varchar(1) DEFAULT NULL,
  `DailySalaryPayment` decimal(11,2) DEFAULT NULL,
  `MontlyPayment` decimal(11,2) DEFAULT NULL,
  `DailyIncentiveShift` decimal(11,2) DEFAULT NULL,
  `InsurancePayment` decimal(11,2) DEFAULT NULL,
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `DeleteBy` varchar(20) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteIP` varchar(20) DEFAULT NULL,
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t02dailyovertime`
--

DROP TABLE IF EXISTS `t02dailyovertime`;
CREATE TABLE IF NOT EXISTS `t02dailyovertime` (
`ID` int(11) NOT NULL COMMENT 'ID Records',
  `PostingDate` date DEFAULT NULL COMMENT 'Tgl Awal Periode',
  `IDEmployee` varchar(20) DEFAULT NULL COMMENT 'Nomor Induk Pegawai',
  `PresenceDate` date DEFAULT NULL COMMENT 'Tgl Absensi',
  `OvertimeIn` datetime DEFAULT NULL,
  `OvertimeOut` datetime DEFAULT NULL,
  `OvertimeHour` decimal(11,2) DEFAULT NULL,
  `OvertimeTotalHour` decimal(11,2) DEFAULT NULL,
  `OvertimePerHour` decimal(11,2) DEFAULT NULL,
  `PaymentStatus` varchar(1) DEFAULT NULL,
  `DailyOvertimePayment` decimal(11,2) DEFAULT NULL,
  `OvertimeIncentive` decimal(11,2) DEFAULT NULL,
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL,
  `DeleteBy` varchar(20) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteIP` varchar(20) DEFAULT NULL,
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
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
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t04deduction`
--

DROP TABLE IF EXISTS `t04deduction`;
CREATE TABLE IF NOT EXISTS `t04deduction` (
`ID` int(11) NOT NULL COMMENT 'ID Records',
  `PostingDate` date NOT NULL,
  `IDEmployee` varchar(20) DEFAULT NULL,
  `Amount` decimal(11,2) DEFAULT NULL,
  `Parameter` varchar(20) NOT NULL,
  `FlagLoan` varchar(10) NOT NULL DEFAULT 'Standar' COMMENT 'Untuk mengetahui hasil system atau bukan',
  `IDRecord` int(11) DEFAULT NULL COMMENT 'ID Record dari setiap transaksi',
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
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
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
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t05payrollslip`
--

DROP TABLE IF EXISTS `t05payrollslip`;
CREATE TABLE IF NOT EXISTS `t05payrollslip` (
`ID` int(11) NOT NULL COMMENT 'ID Records',
  `PostingDate` date DEFAULT NULL COMMENT 'Tgl Awal Periode',
  `IDEmployee` varchar(20) DEFAULT NULL COMMENT 'Nomor Induk Pegawai',
  `SumDailySalaryPayment` int(11) DEFAULT NULL,
  `SumDailyIncentiveShift` int(11) DEFAULT NULL,
  `SumDailyOvertimePayment` int(11) DEFAULT NULL,
  `OtherIncome` int(11) DEFAULT NULL,
  `InsurancePayment` int(11) DEFAULT NULL,
  `AbsencePayment` int(11) DEFAULT NULL,
  `LoanPayment` int(11) DEFAULT NULL,
  `OutstandingPayment` int(11) DEFAULT NULL,
  `OtherPayment` int(11) DEFAULT NULL,
  `TotalIncome` int(11) DEFAULT NULL,
  `TotalDeduction` int(11) DEFAULT NULL,
  `TakeHomePay` int(11) DEFAULT NULL,
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL,
  `DeleteBy` varchar(20) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteIP` varchar(20) DEFAULT NULL,
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t05slip`
--

DROP TABLE IF EXISTS `t05slip`;
CREATE TABLE IF NOT EXISTS `t05slip` (
`ID` int(11) NOT NULL COMMENT 'ID Records',
  `PostingDate` date DEFAULT NULL COMMENT 'Tgl Awal Periode',
  `IDEmployee` varchar(20) DEFAULT NULL COMMENT 'Nomor Induk Pegawai',
  `SumDailySalaryPayment` int(11) DEFAULT NULL,
  `SumDailyIncentiveShift` int(11) DEFAULT NULL,
  `SumDailyOvertimePayment` int(11) DEFAULT NULL,
  `OtherIncome` int(11) DEFAULT NULL,
  `InsurancePayment` int(11) DEFAULT NULL,
  `AbsencePayment` int(11) DEFAULT NULL,
  `LoanPayment` int(11) DEFAULT NULL,
  `OutstandingPayment` int(11) DEFAULT NULL,
  `OtherPayment` int(11) DEFAULT NULL,
  `TotalIncome` int(11) DEFAULT NULL,
  `TotalDeduction` int(11) DEFAULT NULL,
  `TakeHomePay` int(11) DEFAULT NULL,
  `AddedBy` varchar(20) NOT NULL,
  `AddedDate` datetime NOT NULL,
  `AddedIP` varchar(20) NOT NULL,
  `EditedBy` varchar(20) NOT NULL,
  `EditedDate` datetime NOT NULL,
  `EditedIP` varchar(20) NOT NULL
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
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t06loaninterest`
--

DROP TABLE IF EXISTS `t06loaninterest`;
CREATE TABLE IF NOT EXISTS `t06loaninterest` (
`ID` int(11) NOT NULL,
  `IDRecord` int(11) DEFAULT NULL,
  `IDEmployee` varchar(15) DEFAULT NULL,
  `PostingDate` date DEFAULT NULL,
  `Amount` decimal(11,2) NOT NULL,
  `FlagProcess` int(1) DEFAULT '0',
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
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table for cut discount loan schedule per period';

-- --------------------------------------------------------

--
-- Table structure for table `t07deduclate`
--

DROP TABLE IF EXISTS `t07deduclate`;
CREATE TABLE IF NOT EXISTS `t07deduclate` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(20) DEFAULT NULL,
  `PostingDate` date DEFAULT NULL,
  `PresenceDate` date DEFAULT NULL,
  `EstimateShift` varchar(5) DEFAULT NULL,
  `LateTime` varchar(10) DEFAULT NULL,
  `LateHour` varchar(8) DEFAULT NULL,
  `DeducAmount` decimal(11,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tmp01userbpjs`
--

DROP TABLE IF EXISTS `tmp01userbpjs`;
CREATE TABLE IF NOT EXISTS `tmp01userbpjs` (
`ID` int(11) NOT NULL,
  `FullName` varchar(100) DEFAULT NULL,
  `MonthlySalary` varchar(15) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tmpnotyetpaid`
--

DROP TABLE IF EXISTS `tmpnotyetpaid`;
CREATE TABLE IF NOT EXISTS `tmpnotyetpaid` (
  `IDEmployee` varchar(15) DEFAULT NULL,
  `PostingDate` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additionalovertime`
--
ALTER TABLE `additionalovertime`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `addtionalleave`
--
ALTER TABLE `addtionalleave`
 ADD PRIMARY KEY (`IDEmployee`,`PostingDate`);

--
-- Indexes for table `m01fieldpayroll`
--
ALTER TABLE `m01fieldpayroll`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `SECONDARY` (`IDEmployee`);

--
-- Indexes for table `m02personalloan_d`
--
ALTER TABLE `m02personalloan_d`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `SECONDARY` (`IDHeader`,`IDEmployee`,`InstallmentDate`);

--
-- Indexes for table `m02personalloan_h`
--
ALTER TABLE `m02personalloan_h`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `prm01insentive`
--
ALTER TABLE `prm01insentive`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `prm02payroll`
--
ALTER TABLE `prm02payroll`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `r01deduction`
--
ALTER TABLE `r01deduction`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `r02addition`
--
ALTER TABLE `r02addition`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t01dailysalary`
--
ALTER TABLE `t01dailysalary`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `SECONDARY` (`PostingDate`,`IDEmployee`,`PresenceDate`);

--
-- Indexes for table `t02dailyovertime`
--
ALTER TABLE `t02dailyovertime`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `SECONDARY` (`PostingDate`,`IDEmployee`,`PresenceDate`,`OvertimeIn`);

--
-- Indexes for table `t03addition`
--
ALTER TABLE `t03addition`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t04deduction`
--
ALTER TABLE `t04deduction`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t04deductionmanual`
--
ALTER TABLE `t04deductionmanual`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t05payrollslip`
--
ALTER TABLE `t05payrollslip`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `SECONDARY` (`PostingDate`,`IDEmployee`);

--
-- Indexes for table `t05slip`
--
ALTER TABLE `t05slip`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `SECONDARY` (`PostingDate`,`IDEmployee`);

--
-- Indexes for table `t06insentif`
--
ALTER TABLE `t06insentif`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t06loaninterest`
--
ALTER TABLE `t06loaninterest`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t07deduclate`
--
ALTER TABLE `t07deduclate`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tmp01userbpjs`
--
ALTER TABLE `tmp01userbpjs`
 ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `additionalovertime`
--
ALTER TABLE `additionalovertime`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `m01fieldpayroll`
--
ALTER TABLE `m01fieldpayroll`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `m02personalloan_d`
--
ALTER TABLE `m02personalloan_d`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `m02personalloan_h`
--
ALTER TABLE `m02personalloan_h`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `prm01insentive`
--
ALTER TABLE `prm01insentive`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `prm02payroll`
--
ALTER TABLE `prm02payroll`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `r01deduction`
--
ALTER TABLE `r01deduction`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `r02addition`
--
ALTER TABLE `r02addition`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `t01dailysalary`
--
ALTER TABLE `t01dailysalary`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Records';
--
-- AUTO_INCREMENT for table `t02dailyovertime`
--
ALTER TABLE `t02dailyovertime`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Records';
--
-- AUTO_INCREMENT for table `t03addition`
--
ALTER TABLE `t03addition`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Records';
--
-- AUTO_INCREMENT for table `t04deduction`
--
ALTER TABLE `t04deduction`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Records';
--
-- AUTO_INCREMENT for table `t04deductionmanual`
--
ALTER TABLE `t04deductionmanual`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Records';
--
-- AUTO_INCREMENT for table `t05payrollslip`
--
ALTER TABLE `t05payrollslip`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Records';
--
-- AUTO_INCREMENT for table `t05slip`
--
ALTER TABLE `t05slip`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Records';
--
-- AUTO_INCREMENT for table `t06insentif`
--
ALTER TABLE `t06insentif`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Records';
--
-- AUTO_INCREMENT for table `t06loaninterest`
--
ALTER TABLE `t06loaninterest`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t07deduclate`
--
ALTER TABLE `t07deduclate`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tmp01userbpjs`
--
ALTER TABLE `tmp01userbpjs`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
