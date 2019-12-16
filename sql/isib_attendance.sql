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
-- Database: `isib_attendance`
--
CREATE DATABASE IF NOT EXISTS `isib_attendance` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `isib_attendance`;

-- --------------------------------------------------------

--
-- Table structure for table `m01machine`
--

DROP TABLE IF EXISTS `m01machine`;
CREATE TABLE IF NOT EXISTS `m01machine` (
`ID` int(11) NOT NULL,
  `IP` varchar(20) DEFAULT NULL,
  `PORT` int(11) DEFAULT NULL,
  `NUM` int(2) DEFAULT NULL,
  `DIR` int(2) DEFAULT NULL,
  `LOC` int(2) DEFAULT NULL,
  `DEV` int(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `m01personal`
--

DROP TABLE IF EXISTS `m01personal`;
CREATE TABLE IF NOT EXISTS `m01personal` (
`ID` int(11) NOT NULL COMMENT 'ID Records',
  `IDEmployee` varchar(20) NOT NULL COMMENT 'NIP',
  `FullName` varchar(100) DEFAULT NULL COMMENT 'Nama Lengkap',
  `IDUnitGroup` varchar(20) DEFAULT NULL,
  `IDJobGroup` varchar(2) DEFAULT NULL COMMENT 'ST-Staff LT-Lap.Tetap LK-Lap.Kontrak HL-Har.Lepas',
  `IDLocation` varchar(1) DEFAULT NULL,
  `IDEmployeeParent` varchar(20) DEFAULT NULL,
  `HireDate` date NOT NULL COMMENT 'Tgl Awal Bekerja',
  `ResignDate` date DEFAULT NULL COMMENT 'Tgl Akhir Bekerja',
  `Status` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'Status Aktif/Resign',
  `AddedBy` varchar(20) NOT NULL,
  `AddedDate` datetime NOT NULL,
  `AddedIP` varchar(20) NOT NULL,
  `EditedBy` varchar(20) NOT NULL,
  `EditedDate` datetime NOT NULL,
  `EditedIP` varchar(20) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=633 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m01personal`
--

INSERT INTO `m01personal` (`ID`, `IDEmployee`, `FullName`, `IDUnitGroup`, `IDJobGroup`, `IDLocation`, `IDEmployeeParent`, `HireDate`, `ResignDate`, `Status`, `AddedBy`, `AddedDate`, `AddedIP`, `EditedBy`, `EditedDate`, `EditedIP`) VALUES
(534, '0506021112', 'AHMAD RIADI', 'IT', 'ST', '1', '0267190710', '2012-11-02', NULL, 'A', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `m02cardmap`
--

DROP TABLE IF EXISTS `m02cardmap`;
CREATE TABLE IF NOT EXISTS `m02cardmap` (
`ID` int(11) NOT NULL,
  `IDCard` varchar(20) NOT NULL,
  `IDEmployee` varchar(20) NOT NULL,
  `CardType` varchar(1) DEFAULT NULL,
  `LastStatus` varchar(1) DEFAULT 'T' COMMENT 'untuk status kartu aktif  atau pasif',
  `CardNumber` varchar(20) DEFAULT NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=1092 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m02cardmap`
--

INSERT INTO `m02cardmap` (`ID`, `IDCard`, `IDEmployee`, `CardType`, `LastStatus`, `CardNumber`, `AddedBy`, `AddedDate`, `AddedIP`, `EditedBy`, `EditedDate`, `EditedIP`, `DeleteBy`, `DeleteDate`, `DeleteIP`, `DeleteFlag`) VALUES
(713, '00000396', '0506021112', '2', 'T', '0003886096', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'A'),
(863, '00029001', '0506021112', '2', 'T', '0014656535', '0506021112', '2014-03-29 12:26:55', '192.168.0.61', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `m03machine`
--

DROP TABLE IF EXISTS `m03machine`;
CREATE TABLE IF NOT EXISTS `m03machine` (
`ID` int(11) NOT NULL,
  `EnrollNumber` int(11) DEFAULT NULL,
  `Name` varchar(30) DEFAULT NULL,
  `Location` varchar(15) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `paramlate`
--

DROP TABLE IF EXISTS `paramlate`;
CREATE TABLE IF NOT EXISTS `paramlate` (
`ID` int(11) NOT NULL,
  `ParamSite` varchar(1) DEFAULT NULL COMMENT 'lokasi',
  `ParamDate` date DEFAULT NULL COMMENT 'tanggal',
  `StartTimeLate` varchar(5) DEFAULT NULL COMMENT 'jam mulai di hitung keterlambatan',
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
  `DeleteFlag` varchar(1) DEFAULT 'A'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='untuk setting keterlambatan';

-- --------------------------------------------------------

--
-- Table structure for table `parampresence`
--

DROP TABLE IF EXISTS `parampresence`;
CREATE TABLE IF NOT EXISTS `parampresence` (
`ID` int(11) NOT NULL,
  `ParamDate` date DEFAULT NULL,
  `BlockInsentifeShift` int(1) NOT NULL DEFAULT '0',
  `BlockWorkTime` int(1) NOT NULL DEFAULT '0',
  `FromTIme` varchar(10) DEFAULT NULL,
  `UntilTIme` varchar(10) DEFAULT NULL,
  `ParamSite` int(2) DEFAULT NULL,
  `ParamWorkHour` decimal(11,2) DEFAULT NULL,
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
  `DeleteFlag` varchar(1) DEFAULT 'A'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `r01workschedule`
--

DROP TABLE IF EXISTS `r01workschedule`;
CREATE TABLE IF NOT EXISTS `r01workschedule` (
`ID` int(11) NOT NULL,
  `IDSchedule` varchar(3) NOT NULL,
  `Note` varchar(50) NOT NULL,
  `TimeIn` time DEFAULT NULL,
  `TimeOut` time DEFAULT NULL,
  `BreakDuration` float DEFAULT NULL,
  `Overtime` time DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `r01workschedule`
--

INSERT INTO `r01workschedule` (`ID`, `IDSchedule`, `Note`, `TimeIn`, `TimeOut`, `BreakDuration`, `Overtime`) VALUES
(1, 'N1', 'NORMAL SENIN-KAMIS', '08:00:00', '16:00:00', 1, '00:00:00'),
(2, 'N2', 'NORMAL JUMAT', '08:00:00', '16:30:00', 1.5, '00:00:00'),
(3, 'N3', 'NORMAL SABTU', '08:00:00', '13:00:00', 0, '00:00:00'),
(4, 'SUN', 'SUNDAY MINGGU', NULL, NULL, 0, '00:00:00'),
(5, 'OFF', 'HOLIDAY LIBUR NASIONAL', NULL, NULL, 0, '00:00:00'),
(6, 'S1', 'SHIFT 2 SENIN-KAMIS', '16:00:00', '23:59:00', 1, '04:00:00'),
(7, 'S2', 'SHIFT 2 JUMAT', '16:30:00', '00:30:00', 1.5, '04:00:00'),
(8, 'S3', 'SHIFT 2 SABTU', '13:00:00', '09:00:00', 0, '00:00:00'),
(9, 'S4', 'SHIFT 3 SENIN-KAMIS', '00:00:00', '08:00:00', 1, NULL),
(10, 'S5', 'SHIFT 3 SENIN-KAMIS', '00:00:00', '08:00:00', 1, '07:00:00'),
(11, 'S41', 'SHIFT 3 SENIN-KAMIS', '00:00:00', '08:00:00', 1, '07:00:00');

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
  `DeleteIP` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `r03periodfield`
--

DROP TABLE IF EXISTS `r03periodfield`;
CREATE TABLE IF NOT EXISTS `r03periodfield` (
`IDPeriod` int(11) NOT NULL,
  `StartPeriod` date NOT NULL,
  `EndPeriod` date NOT NULL,
  `NPeriod` varchar(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `r04typeattendance`
--

DROP TABLE IF EXISTS `r04typeattendance`;
CREATE TABLE IF NOT EXISTS `r04typeattendance` (
`ID` int(11) NOT NULL,
  `IDType` varchar(5) NOT NULL,
  `Description` varchar(100) DEFAULT NULL,
  `Note` varchar(100) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `r04typeattendance`
--

INSERT INTO `r04typeattendance` (`ID`, `IDType`, `Description`, `Note`) VALUES
(1, 'P', 'PRESENCE', 'HADIR'),
(2, 'A', 'ABSENCE', 'MANGKIR'),
(3, 'LSN', 'SICKNESS LEAVE WITH LETTER', 'SAKIT DENGAN SURAT DOKTER'),
(4, 'SN', 'SICKNESS LEAVE', 'SAKIT TANPA SURAT DOKTER'),
(5, 'AL', 'ANNUAL LEAVE', 'CUTI TAHUNAN'),
(6, 'MRL', 'MARRIAGE LEAVE', 'CUTI MENIKAH'),
(7, 'MTL', 'MATERNITY LEAVE', 'CUTI MELAHIRKAN'),
(8, 'CL', 'CONDOLENCE LEAVE', 'CUTI DUKA CITA'),
(9, 'SL', 'SICK LEAVE', 'CUTI SAKIT'),
(10, 'OL', 'UNPAID LEAVE', 'CUTI LAINNYA/TDK DIBAYAR'),
(11, 'LP', 'LEAVE PERMIT', 'IJIN'),
(12, 'OT', 'OFFICIAL TRAVEL', 'DINAS LUAR'),
(13, 'NC', 'NOT COMPLETE', 'PRESENSI TIDAK LENGKAP'),
(14, 'MP', 'MANUAL PRESENCE', 'PRESENSI MANUAL'),
(15, 'FML', 'FORCE MAJEURE LEAVE', 'CUTI KARENA KEADAAN DARURAT'),
(16, 'PLW', 'PERMISSION', 'IJIN KULIAH/KURSUS/TRAINING');

-- --------------------------------------------------------

--
-- Table structure for table `r05jobgroup`
--

DROP TABLE IF EXISTS `r05jobgroup`;
CREATE TABLE IF NOT EXISTS `r05jobgroup` (
`ID` int(11) NOT NULL,
  `IDJobGroup` varchar(5) DEFAULT NULL,
  `GroupName` varchar(50) DEFAULT NULL,
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL,
  `DeleteBy` varchar(20) DEFAULT NULL,
  `DeleteFlag` varchar(3) NOT NULL DEFAULT 'A' COMMENT 'Jika A maka di tampilkan jika D maka tidak di tampilkan',
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteIP` varchar(20) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `r05jobgroup`
--

INSERT INTO `r05jobgroup` (`ID`, `IDJobGroup`, `GroupName`, `AddedBy`, `AddedDate`, `AddedIP`, `EditedBy`, `EditedDate`, `EditedIP`, `DeleteBy`, `DeleteFlag`, `DeleteDate`, `DeleteIP`) VALUES
(2, 'ST', 'STAFF', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A', NULL, NULL),
(3, 'LT', 'LAPANGAN TETAP', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A', NULL, NULL),
(4, 'LK', 'LAPANGAN KONTRAK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A', NULL, NULL),
(7, 'HL', 'HARIAN LEPAS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A', NULL, NULL),
(5, 'MAG', 'MAGANG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A', NULL, NULL),
(6, 'OS', 'MITRA KERJA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A', NULL, NULL),
(1, 'AL', 'ALL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A', NULL, NULL),
(8, 'LL', 'LAIN-LAIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `r06parameter`
--

DROP TABLE IF EXISTS `r06parameter`;
CREATE TABLE IF NOT EXISTS `r06parameter` (
`ID` int(11) NOT NULL,
  `Name` varchar(20) DEFAULT NULL,
  `Value` varchar(50) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `r06parameter`
--

INSERT INTO `r06parameter` (`ID`, `Name`, `Value`) VALUES
(1, 'IDSPKL', '5000');

-- --------------------------------------------------------

--
-- Table structure for table `r07typeleave`
--

DROP TABLE IF EXISTS `r07typeleave`;
CREATE TABLE IF NOT EXISTS `r07typeleave` (
  `CodeType` varchar(5) NOT NULL,
  `DescType` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `r07typeleave`
--

INSERT INTO `r07typeleave` (`CodeType`, `DescType`) VALUES
('AL', 'ANNUAL LEAVE'),
('CIR', 'CIRCUMCISION LEAVE'),
('CL', 'CONDOLENCE LEAVE'),
('FML', 'FORCE MAJEURE LEAVE'),
('MRL', 'MARRIAGE LEAVE'),
('MTL', 'MATERNITY LEAVE'),
('OL', 'UNPAID LEAVE'),
('SL', 'SICK LEAVE');

-- --------------------------------------------------------

--
-- Table structure for table `t01cardraw`
--

DROP TABLE IF EXISTS `t01cardraw`;
CREATE TABLE IF NOT EXISTS `t01cardraw` (
  `DataText` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'String Rawdata',
`ID` int(11) NOT NULL,
  `ProcessBy` varchar(20) DEFAULT NULL,
  `ProcessDate` datetime DEFAULT NULL,
  `ProcessIP` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t01machine`
--

DROP TABLE IF EXISTS `t01machine`;
CREATE TABLE IF NOT EXISTS `t01machine` (
`ID` int(11) NOT NULL,
  `EnrollNumber` varchar(20) DEFAULT NULL,
  `EnrollDate` date DEFAULT NULL,
  `EnrollTime` time DEFAULT NULL,
  `Direction` int(2) DEFAULT NULL,
  `Location` int(2) NOT NULL,
  `Machine` int(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t02presence`
--

DROP TABLE IF EXISTS `t02presence`;
CREATE TABLE IF NOT EXISTS `t02presence` (
`ID` int(11) NOT NULL,
  `EnrollNumberIn` varchar(20) DEFAULT NULL,
  `EnrollDateIn` date DEFAULT NULL,
  `EnrollTimeIn` time DEFAULT NULL,
  `EnrollDateOut` date DEFAULT NULL,
  `EnrollTimeOut` time DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t02rawdata`
--

DROP TABLE IF EXISTS `t02rawdata`;
CREATE TABLE IF NOT EXISTS `t02rawdata` (
`ID` int(11) NOT NULL,
  `DataText` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'String Rawdata',
  `IDCard` varchar(20) DEFAULT NULL COMMENT 'No. Kartu Barcode/RFID',
  `IDEmployee` varchar(20) DEFAULT NULL COMMENT 'NIP',
  `PresenceDate` date DEFAULT NULL COMMENT 'Tanggal Kehadiran',
  `PresenceTime` time DEFAULT NULL COMMENT 'Jam Kehadiran',
  `Direction` varchar(1) DEFAULT NULL COMMENT 'Datang/Pulang',
  `Location` varchar(1) NOT NULL COMMENT 'Lokasi Absensi',
  `ProcessBy` varchar(20) DEFAULT NULL,
  `ProcessDate` datetime DEFAULT NULL,
  `ProcessIP` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t03presence`
--

DROP TABLE IF EXISTS `t03presence`;
CREATE TABLE IF NOT EXISTS `t03presence` (
`IDPresence` int(11) NOT NULL COMMENT 'ID Records',
  `IDEmployee` varchar(20) DEFAULT NULL COMMENT 'Nomor Induk Pegawai',
  `PresenceDate` date DEFAULT NULL COMMENT 'Tgl Absensi',
  `WorkDay` varchar(3) DEFAULT 'N1' COMMENT 'Hari Kerja Normal/Libur',
  `DayOfWeek` int(11) DEFAULT '1' COMMENT 'Hari keberapa dlm seminggu',
  `ActualIn` datetime DEFAULT NULL COMMENT 'Jam Masuk Aktual',
  `ActualOut` datetime DEFAULT NULL COMMENT 'Jam Pulang Aktual',
  `ActualHour` decimal(6,2) DEFAULT '0.00' COMMENT 'Jumlah Jam Kerja Aktual',
  `ManualIn` datetime DEFAULT NULL,
  `ManualOut` datetime DEFAULT NULL,
  `ManualHour` decimal(6,2) DEFAULT '0.00',
  `IMKOut` datetime DEFAULT NULL COMMENT 'Jam  keluar dari  Kantor',
  `IMKIn` datetime DEFAULT NULL COMMENT 'Jam masuk ke Kantor',
  `IMKHour` decimal(6,2) DEFAULT NULL COMMENT 'Total Jam keluar',
  `PLWHour` int(2) NOT NULL DEFAULT '0' COMMENT 'Paid on Hour, for addition Work Hour because PLW',
  `WorkIn` time DEFAULT NULL COMMENT 'Jam Masuk Standar',
  `WorkOut` time DEFAULT NULL COMMENT 'Jam Pulang Standar',
  `WorkHour` decimal(6,2) DEFAULT '0.00' COMMENT 'Jumlah Jam Kerja Strandar',
  `LateHour` decimal(6,2) DEFAULT NULL COMMENT 'Jumlah Jam Terlambat',
  `ExcessHour` decimal(6,2) DEFAULT NULL COMMENT 'Jumlah Kelebihan Jam Kerja',
  `Description` varchar(5) DEFAULT NULL,
  `Necessity` varchar(5) DEFAULT NULL COMMENT '1 = Pribadi , 2 = Kantor',
  `Note` text COMMENT 'Catatan',
  `PresenceType` varchar(3) DEFAULT NULL COMMENT 'SWL-Sickness With Letter, SLV-Sickness Leave, ANL-Annual Leave, OTL-Other Leave, ETC',
  `CatatanProses` text,
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t04overtime`
--

DROP TABLE IF EXISTS `t04overtime`;
CREATE TABLE IF NOT EXISTS `t04overtime` (
`ID` bigint(20) NOT NULL COMMENT 'ID Records',
  `FlagInput` varchar(5) DEFAULT 'emp' COMMENT 'Di gunakan untuk mengetahui data tersebut di input dari program  module Employee atau HRD, jika Employee maka = ''emp'' jika dari HRD = ''hrd''',
  `IDSPKL` varchar(50) NOT NULL COMMENT 'Nomor Surat Perintah Kerja Lembur',
  `IDEmployee` varchar(20) NOT NULL COMMENT 'NIP',
  `PresenceDate` date NOT NULL,
  `OvertimeIn` datetime DEFAULT NULL COMMENT 'Jam Mulai Lembur',
  `OvertimeOut` datetime DEFAULT NULL COMMENT 'Jam Selesai Lembur',
  `Note` text COMMENT 'Keterangan Lembur',
  `ConfirmFlag` tinyint(1) NOT NULL DEFAULT '0',
  `ConfirmDate` datetime NOT NULL,
  `ConfirmIP` varchar(20) NOT NULL,
  `ConfirmBy` varchar(20) NOT NULL,
  `RejectReason` varchar(200) NOT NULL,
  `OvertimeHour` decimal(11,2) DEFAULT '0.00',
  `OvertimeTotalHour` decimal(11,2) DEFAULT '0.00',
  `CheckData` int(2) NOT NULL DEFAULT '0',
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
-- Table structure for table `t05incomplete`
--

DROP TABLE IF EXISTS `t05incomplete`;
CREATE TABLE IF NOT EXISTS `t05incomplete` (
`ID` bigint(20) NOT NULL,
  `FlagInput` varchar(5) NOT NULL DEFAULT 'emp',
  `IDIncomplete` int(7) NOT NULL,
  `IDEmployee` varchar(20) NOT NULL,
  `IncompleteDate` date NOT NULL,
  `TimeIn` time DEFAULT NULL,
  `TimeOut` time DEFAULT NULL,
  `Note` varchar(100) DEFAULT NULL,
  `ConfirmFlag` tinyint(1) NOT NULL DEFAULT '0',
  `ConfirmDate` datetime NOT NULL,
  `ConfirmIP` varchar(20) NOT NULL,
  `ConfirmBy` varchar(20) NOT NULL,
  `RejectReason` int(200) NOT NULL,
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
-- Table structure for table `t06sicknessleave`
--

DROP TABLE IF EXISTS `t06sicknessleave`;
CREATE TABLE IF NOT EXISTS `t06sicknessleave` (
`ID` bigint(20) NOT NULL,
  `IDEmployee` varchar(20) NOT NULL,
  `SicknessDate` date DEFAULT NULL,
  `UntilDate` date DEFAULT NULL,
  `SicknessLetter` varchar(1) DEFAULT NULL,
  `Note` varchar(100) DEFAULT NULL,
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t07officialtravel`
--

DROP TABLE IF EXISTS `t07officialtravel`;
CREATE TABLE IF NOT EXISTS `t07officialtravel` (
`ID` bigint(20) NOT NULL,
  `FlagInput` varchar(5) NOT NULL DEFAULT 'emp',
  `IDTravel` int(11) NOT NULL,
  `IDEmployee` varchar(20) NOT NULL,
  `OfficialTravelDate` date DEFAULT NULL,
  `UntilDate` date DEFAULT NULL,
  `Note` varchar(100) DEFAULT NULL,
  `VehicleNo` varchar(20) NOT NULL,
  `ConfirmFlag` tinyint(1) NOT NULL DEFAULT '0',
  `ConfirmDate` datetime NOT NULL,
  `ConfirmIP` varchar(20) NOT NULL,
  `ConfirmBy` varchar(20) NOT NULL,
  `RejectReason` varchar(200) NOT NULL,
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A',
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL,
  `DeletedBy` varchar(20) DEFAULT NULL,
  `DeletedDate` datetime DEFAULT NULL,
  `DeletedIP` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t08leavepermit`
--

DROP TABLE IF EXISTS `t08leavepermit`;
CREATE TABLE IF NOT EXISTS `t08leavepermit` (
`ID` int(11) NOT NULL,
  `FlagInput` varchar(5) DEFAULT 'emp' COMMENT 'Di gunakan untuk mengetahui data tersebut di input dari program  module Employee atau HRD, jika Employee maka = ''emp'' jika dari HRD = ''hrd''',
  `IDEmployee` varchar(20) NOT NULL,
  `IDLeavePermit` int(10) NOT NULL,
  `LeavePermitDate` date DEFAULT NULL,
  `OutDate` datetime DEFAULT NULL COMMENT 'Jam  keluar dari  Kantor',
  `InDate` datetime DEFAULT NULL COMMENT 'Jam masuk ke Kantor',
  `IMKHour` decimal(6,2) DEFAULT NULL COMMENT 'Total Jam keluar',
  `Necessity` varchar(5) DEFAULT NULL COMMENT '1 = Pribadi , 2 = Kantor',
  `Note` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `VehicleNo` varchar(15) NOT NULL,
  `ConfirmFlag` tinyint(1) NOT NULL DEFAULT '0',
  `ConfirmDate` datetime NOT NULL,
  `ConfirmIP` varchar(20) NOT NULL,
  `ConfirmBy` varchar(20) NOT NULL,
  `RejectReason` varchar(200) NOT NULL,
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A',
  `AddedBy` varchar(20) NOT NULL,
  `AddedIP` varchar(20) NOT NULL,
  `AddedDate` datetime NOT NULL,
  `EditedBy` varchar(20) NOT NULL,
  `EditedIP` varchar(20) NOT NULL,
  `EditedDate` datetime NOT NULL,
  `DeletedBy` varchar(20) NOT NULL,
  `DeletedIP` varchar(20) NOT NULL,
  `DeletedDate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t08leavepermit_d`
--

DROP TABLE IF EXISTS `t08leavepermit_d`;
CREATE TABLE IF NOT EXISTS `t08leavepermit_d` (
`ID` int(11) NOT NULL,
  `IDLeavePermit` varchar(10) NOT NULL,
  `IDEmployee` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t09leave`
--

DROP TABLE IF EXISTS `t09leave`;
CREATE TABLE IF NOT EXISTS `t09leave` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(20) NOT NULL,
  `LeaveDate` date DEFAULT NULL,
  `UntilDate` date DEFAULT NULL,
  `TypeLeave` varchar(5) DEFAULT NULL,
  `Note` varchar(100) DEFAULT NULL,
  `AddedBy` varchar(20) NOT NULL,
  `AddedDate` datetime NOT NULL,
  `AddedIP` varchar(20) NOT NULL,
  `EditedBy` varchar(20) NOT NULL,
  `EditedDate` datetime NOT NULL,
  `EditedIP` varchar(20) NOT NULL,
  `DeleteBy` varchar(20) NOT NULL,
  `DeleteDate` datetime NOT NULL,
  `DeleteIP` varchar(20) NOT NULL,
  `DeleteFlag` varchar(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t10suspension`
--

DROP TABLE IF EXISTS `t10suspension`;
CREATE TABLE IF NOT EXISTS `t10suspension` (
`ID` bigint(20) NOT NULL,
  `IDEmployee` varchar(20) NOT NULL,
  `SuspensionDate` date DEFAULT NULL,
  `UntilDate` date DEFAULT NULL,
  `Note` varchar(100) DEFAULT NULL,
  `AddedBy` varchar(20) NOT NULL,
  `AddedDate` datetime NOT NULL,
  `AddedIP` varchar(20) NOT NULL,
  `EditedBy` varchar(20) NOT NULL,
  `EditedDate` datetime NOT NULL,
  `EditedIP` varchar(20) NOT NULL,
  `LongDay` int(3) DEFAULT NULL,
  `DeleteBy` varchar(20) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteIP` varchar(20) DEFAULT NULL,
  `DeleteFlag` varchar(1) DEFAULT 'A'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t11leavework`
--

DROP TABLE IF EXISTS `t11leavework`;
CREATE TABLE IF NOT EXISTS `t11leavework` (
`ID` bigint(20) NOT NULL,
  `IDEmployee` varchar(20) NOT NULL,
  `StartDate` date DEFAULT NULL,
  `FinishDate` date DEFAULT NULL,
  `Day0` varchar(10) DEFAULT NULL,
  `Day1` varchar(10) DEFAULT NULL,
  `Day2` varchar(10) DEFAULT NULL,
  `Day3` varchar(10) DEFAULT NULL,
  `Day4` varchar(10) DEFAULT NULL,
  `Day5` varchar(10) DEFAULT NULL,
  `Day6` varchar(10) DEFAULT NULL,
  `LeaveHour` varchar(3) DEFAULT NULL,
  `Note` varchar(100) DEFAULT NULL,
  `AddedBy` varchar(20) NOT NULL,
  `AddedDate` datetime NOT NULL,
  `AddedIP` varchar(20) NOT NULL,
  `EditedBy` varchar(20) NOT NULL,
  `EditedDate` datetime NOT NULL,
  `EditedIP` varchar(20) NOT NULL,
  `DeleteBy` varchar(20) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteIP` varchar(20) DEFAULT NULL,
  `DeleteFlag` varchar(1) DEFAULT 'A'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
  `AddedBy` varchar(20) NOT NULL,
  `AddedDate` datetime NOT NULL,
  `AddedIP` varchar(20) NOT NULL,
  `EditedBy` varchar(20) NOT NULL,
  `EditedDate` datetime NOT NULL,
  `EditedIP` varchar(20) NOT NULL,
  `DeleteBy` varchar(20) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteIP` varchar(20) DEFAULT NULL,
  `DeleteFlag` varchar(1) DEFAULT 'A',
  `RangePicket` int(1) DEFAULT '1' COMMENT '1 = One Day, 2 = More One Day'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `temp01outbond2015`
--

DROP TABLE IF EXISTS `temp01outbond2015`;
CREATE TABLE IF NOT EXISTS `temp01outbond2015` (
  `IDEmployee` varchar(25) NOT NULL DEFAULT '',
  `FullName` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tmp02manualpresence`
--

DROP TABLE IF EXISTS `tmp02manualpresence`;
CREATE TABLE IF NOT EXISTS `tmp02manualpresence` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(15) DEFAULT NULL,
  `PresenceDate` varchar(15) DEFAULT NULL,
  `Note` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tparam`
--

DROP TABLE IF EXISTS `tparam`;
CREATE TABLE IF NOT EXISTS `tparam` (
`ID` int(11) NOT NULL,
  `IDParam` varchar(50) DEFAULT NULL,
  `Val1` varchar(50) DEFAULT NULL,
  `Val2` varchar(50) DEFAULT NULL,
  `Val3` varchar(50) DEFAULT NULL,
  `Val4` varchar(10) DEFAULT NULL COMMENT 'Limit Time',
  `AddedBy` varchar(20) DEFAULT NULL,
  `AddedDate` datetime DEFAULT NULL,
  `AddedIP` varchar(20) DEFAULT NULL,
  `EditedBy` varchar(20) DEFAULT NULL,
  `EditedDate` datetime DEFAULT NULL,
  `EditedIP` varchar(20) DEFAULT NULL,
  `DeleteBy` varchar(20) DEFAULT NULL,
  `DeleteFlag` varchar(3) NOT NULL DEFAULT 'A' COMMENT 'Jika A maka di tampilkan jika D maka tidak di tampilkan',
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteIP` varchar(20) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tparam`
--

INSERT INTO `tparam` (`ID`, `IDParam`, `Val1`, `Val2`, `Val3`, `Val4`, `AddedBy`, `AddedDate`, `AddedIP`, `EditedBy`, `EditedDate`, `EditedIP`, `DeleteBy`, `DeleteFlag`, `DeleteDate`, `DeleteIP`) VALUES
(1, 'overtime', '25', '25', '', '', '0506021112', '2014-09-23 11:00:53', '192.168.0.61', '0538131212', '2015-07-24 13:41:32', '192.168.5.23', NULL, 'A', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m01machine`
--
ALTER TABLE `m01machine`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `m01personal`
--
ALTER TABLE `m01personal`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `SECONDARY` (`IDEmployee`), ADD KEY `IDEmployeeParent` (`IDEmployeeParent`);

--
-- Indexes for table `m02cardmap`
--
ALTER TABLE `m02cardmap`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `m03machine`
--
ALTER TABLE `m03machine`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `paramlate`
--
ALTER TABLE `paramlate`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `parampresence`
--
ALTER TABLE `parampresence`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `r01workschedule`
--
ALTER TABLE `r01workschedule`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `SECONDARY` (`IDSchedule`);

--
-- Indexes for table `r02holiday`
--
ALTER TABLE `r02holiday`
 ADD PRIMARY KEY (`IDHoliday`);

--
-- Indexes for table `r03periodfield`
--
ALTER TABLE `r03periodfield`
 ADD PRIMARY KEY (`IDPeriod`);

--
-- Indexes for table `r04typeattendance`
--
ALTER TABLE `r04typeattendance`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `r05jobgroup`
--
ALTER TABLE `r05jobgroup`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `r06parameter`
--
ALTER TABLE `r06parameter`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `r07typeleave`
--
ALTER TABLE `r07typeleave`
 ADD PRIMARY KEY (`CodeType`);

--
-- Indexes for table `t01cardraw`
--
ALTER TABLE `t01cardraw`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t01machine`
--
ALTER TABLE `t01machine`
 ADD PRIMARY KEY (`ID`), ADD KEY `SECONDARY` (`EnrollNumber`,`EnrollDate`,`EnrollTime`,`Direction`);

--
-- Indexes for table `t02presence`
--
ALTER TABLE `t02presence`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t02rawdata`
--
ALTER TABLE `t02rawdata`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `DATATEXT` (`DataText`), ADD KEY `SECONDARY` (`IDEmployee`,`IDCard`,`PresenceDate`,`PresenceTime`,`Direction`);

--
-- Indexes for table `t03presence`
--
ALTER TABLE `t03presence`
 ADD PRIMARY KEY (`IDPresence`), ADD UNIQUE KEY `SECONDARY` (`IDEmployee`,`PresenceDate`,`ActualIn`,`ActualOut`);

--
-- Indexes for table `t04overtime`
--
ALTER TABLE `t04overtime`
 ADD PRIMARY KEY (`ID`), ADD KEY `KEY_IDEMPLOYEE` (`IDEmployee`);

--
-- Indexes for table `t05incomplete`
--
ALTER TABLE `t05incomplete`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t06sicknessleave`
--
ALTER TABLE `t06sicknessleave`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `SECONDARY` (`IDEmployee`,`SicknessDate`);

--
-- Indexes for table `t07officialtravel`
--
ALTER TABLE `t07officialtravel`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t08leavepermit`
--
ALTER TABLE `t08leavepermit`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t08leavepermit_d`
--
ALTER TABLE `t08leavepermit_d`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t09leave`
--
ALTER TABLE `t09leave`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t10suspension`
--
ALTER TABLE `t10suspension`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `SECONDARY` (`IDEmployee`,`SuspensionDate`);

--
-- Indexes for table `t11leavework`
--
ALTER TABLE `t11leavework`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `SECONDARY` (`IDEmployee`,`StartDate`);

--
-- Indexes for table `t12employeepicket`
--
ALTER TABLE `t12employeepicket`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `temp01outbond2015`
--
ALTER TABLE `temp01outbond2015`
 ADD PRIMARY KEY (`IDEmployee`);

--
-- Indexes for table `tmp02manualpresence`
--
ALTER TABLE `tmp02manualpresence`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tparam`
--
ALTER TABLE `tparam`
 ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `m01machine`
--
ALTER TABLE `m01machine`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `m01personal`
--
ALTER TABLE `m01personal`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Records',AUTO_INCREMENT=633;
--
-- AUTO_INCREMENT for table `m02cardmap`
--
ALTER TABLE `m02cardmap`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1092;
--
-- AUTO_INCREMENT for table `m03machine`
--
ALTER TABLE `m03machine`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `paramlate`
--
ALTER TABLE `paramlate`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `parampresence`
--
ALTER TABLE `parampresence`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `r01workschedule`
--
ALTER TABLE `r01workschedule`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `r02holiday`
--
ALTER TABLE `r02holiday`
MODIFY `IDHoliday` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `r03periodfield`
--
ALTER TABLE `r03periodfield`
MODIFY `IDPeriod` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `r04typeattendance`
--
ALTER TABLE `r04typeattendance`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `r05jobgroup`
--
ALTER TABLE `r05jobgroup`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `r06parameter`
--
ALTER TABLE `r06parameter`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `t01cardraw`
--
ALTER TABLE `t01cardraw`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t01machine`
--
ALTER TABLE `t01machine`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t02presence`
--
ALTER TABLE `t02presence`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t02rawdata`
--
ALTER TABLE `t02rawdata`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t03presence`
--
ALTER TABLE `t03presence`
MODIFY `IDPresence` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Records';
--
-- AUTO_INCREMENT for table `t04overtime`
--
ALTER TABLE `t04overtime`
MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID Records';
--
-- AUTO_INCREMENT for table `t05incomplete`
--
ALTER TABLE `t05incomplete`
MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t06sicknessleave`
--
ALTER TABLE `t06sicknessleave`
MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t07officialtravel`
--
ALTER TABLE `t07officialtravel`
MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t08leavepermit`
--
ALTER TABLE `t08leavepermit`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t08leavepermit_d`
--
ALTER TABLE `t08leavepermit_d`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t09leave`
--
ALTER TABLE `t09leave`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t10suspension`
--
ALTER TABLE `t10suspension`
MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t11leavework`
--
ALTER TABLE `t11leavework`
MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t12employeepicket`
--
ALTER TABLE `t12employeepicket`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tmp02manualpresence`
--
ALTER TABLE `tmp02manualpresence`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tparam`
--
ALTER TABLE `tparam`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
