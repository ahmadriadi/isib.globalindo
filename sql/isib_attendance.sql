/*
SQLyog Community v13.0.1 (64 bit)
MySQL - 5.6.21 : Database - isib_attendance
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`isib_attendance` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `isib_attendance`;

/*Table structure for table `m01machine` */

DROP TABLE IF EXISTS `m01machine`;

CREATE TABLE `m01machine` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IP` varchar(20) DEFAULT NULL,
  `PORT` int(11) DEFAULT NULL,
  `NUM` int(2) DEFAULT NULL,
  `DIR` int(2) DEFAULT NULL,
  `LOC` int(2) DEFAULT NULL,
  `DEV` int(2) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `m01machine` */

/*Table structure for table `m01personal` */

DROP TABLE IF EXISTS `m01personal`;

CREATE TABLE `m01personal` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Records',
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
  `EditedIP` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `SECONDARY` (`IDEmployee`),
  KEY `IDEmployeeParent` (`IDEmployeeParent`)
) ENGINE=MyISAM AUTO_INCREMENT=633 DEFAULT CHARSET=latin1;

/*Data for the table `m01personal` */

insert  into `m01personal`(`ID`,`IDEmployee`,`FullName`,`IDUnitGroup`,`IDJobGroup`,`IDLocation`,`IDEmployeeParent`,`HireDate`,`ResignDate`,`Status`,`AddedBy`,`AddedDate`,`AddedIP`,`EditedBy`,`EditedDate`,`EditedIP`) values 
(534,'0506021112','AHMAD RIADI','IT','ST','1','0267190710','2012-11-02',NULL,'A','','0000-00-00 00:00:00','','','0000-00-00 00:00:00','');

/*Table structure for table `m02cardmap` */

DROP TABLE IF EXISTS `m02cardmap`;

CREATE TABLE `m02cardmap` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
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
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1092 DEFAULT CHARSET=latin1;

/*Data for the table `m02cardmap` */

insert  into `m02cardmap`(`ID`,`IDCard`,`IDEmployee`,`CardType`,`LastStatus`,`CardNumber`,`AddedBy`,`AddedDate`,`AddedIP`,`EditedBy`,`EditedDate`,`EditedIP`,`DeleteBy`,`DeleteDate`,`DeleteIP`,`DeleteFlag`) values 
(713,'00000396','0506021112','2','T','0003886096','','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','A'),
(863,'00029001','0506021112','2','T','0014656535','0506021112','2014-03-29 12:26:55','192.168.0.61','','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','A');

/*Table structure for table `m03machine` */

DROP TABLE IF EXISTS `m03machine`;

CREATE TABLE `m03machine` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `EnrollNumber` int(11) DEFAULT NULL,
  `Name` varchar(30) DEFAULT NULL,
  `Location` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `m03machine` */

/*Table structure for table `paramlate` */

DROP TABLE IF EXISTS `paramlate`;

CREATE TABLE `paramlate` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
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
  `DeleteFlag` varchar(1) DEFAULT 'A',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='untuk setting keterlambatan';

/*Data for the table `paramlate` */

/*Table structure for table `parampresence` */

DROP TABLE IF EXISTS `parampresence`;

CREATE TABLE `parampresence` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
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
  `DeleteFlag` varchar(1) DEFAULT 'A',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `parampresence` */

/*Table structure for table `r01workschedule` */

DROP TABLE IF EXISTS `r01workschedule`;

CREATE TABLE `r01workschedule` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDSchedule` varchar(3) NOT NULL,
  `Note` varchar(50) NOT NULL,
  `TimeIn` time DEFAULT NULL,
  `TimeOut` time DEFAULT NULL,
  `BreakDuration` float DEFAULT NULL,
  `Overtime` time DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `SECONDARY` (`IDSchedule`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `r01workschedule` */

insert  into `r01workschedule`(`ID`,`IDSchedule`,`Note`,`TimeIn`,`TimeOut`,`BreakDuration`,`Overtime`) values 
(1,'N1','NORMAL SENIN-KAMIS','08:00:00','16:00:00',1,'00:00:00'),
(2,'N2','NORMAL JUMAT','08:00:00','16:30:00',1.5,'00:00:00'),
(3,'N3','NORMAL SABTU','08:00:00','13:00:00',0,'00:00:00'),
(4,'SUN','SUNDAY MINGGU',NULL,NULL,0,'00:00:00'),
(5,'OFF','HOLIDAY LIBUR NASIONAL',NULL,NULL,0,'00:00:00'),
(6,'S1','SHIFT 2 SENIN-KAMIS','16:00:00','23:59:00',1,'04:00:00'),
(7,'S2','SHIFT 2 JUMAT','16:30:00','00:30:00',1.5,'04:00:00'),
(8,'S3','SHIFT 2 SABTU','13:00:00','09:00:00',0,'00:00:00'),
(9,'S4','SHIFT 3 SENIN-KAMIS','00:00:00','08:00:00',1,NULL),
(10,'S5','SHIFT 3 SENIN-KAMIS','00:00:00','08:00:00',1,'07:00:00'),
(11,'S41','SHIFT 3 SENIN-KAMIS','00:00:00','08:00:00',1,'07:00:00');

/*Table structure for table `r02holiday` */

DROP TABLE IF EXISTS `r02holiday`;

CREATE TABLE `r02holiday` (
  `IDHoliday` int(11) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`IDHoliday`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

/*Data for the table `r02holiday` */

insert  into `r02holiday`(`IDHoliday`,`Date`,`Note`,`Flag`,`AddedBy`,`AddedDate`,`AddedIP`,`EditedBy`,`EditedDate`,`EditedIP`,`DeleteBy`,`DeleteFlag`,`DeleteDate`,`DeleteIP`) values 
(1,'2020-01-25','hari raya imlek',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(2,'2020-02-01','sabtu minggu pertama',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(3,'2020-03-07','sabtu minggu pertama',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(4,'2020-03-25','Hari raya nyepi',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(5,'2020-04-04','Sabtu minggu pertama',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(6,'2020-04-10','Wafat Yesus Kristus',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(7,'2020-05-01','Hari Buruh Internasional',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(8,'2020-05-02','Sabtu Minggu Pertama',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(9,'2020-05-07','Kenaikan yesus kristus',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(10,'2020-05-21','Kenaikan Yesus Kristus',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(11,'2020-05-25','Hari Raya Idul Fitri',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(12,'2020-06-01','Hari Lahir Pancasila',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(13,'2020-06-06','Sabtu Minggu Pertama',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(14,'2020-07-04','Sabtu Minggu Pertama',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(15,'2020-07-31','Hari Raya Idul Adha',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(16,'2020-08-17','Hari Proklamasi Kemerdekaan RI',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(17,'2020-08-20','Tahun Baru Hijjriyah',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(18,'2020-09-05','Sabtu Minggu Pertama',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(19,'2020-10-03','Sabtu Minggu Pertama',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(20,'2020-10-29','Maulid Muhammad SAW',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(21,'2020-11-07','sabtu minggu pertama',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(22,'2020-12-05','Sabtu Minggu Pertama',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(23,'2020-12-25','Hari Raya Natal ',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(24,'2020-01-05','DAY OFF',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(25,'2020-01-12','DAY OFF',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(26,'2020-01-19','DAY OFF',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(27,'2020-01-26','DAY OFF',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL);

/*Table structure for table `r03periodfield` */

DROP TABLE IF EXISTS `r03periodfield`;

CREATE TABLE `r03periodfield` (
  `IDPeriod` int(11) NOT NULL AUTO_INCREMENT,
  `StartPeriod` date NOT NULL,
  `EndPeriod` date NOT NULL,
  `NPeriod` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`IDPeriod`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `r03periodfield` */

/*Table structure for table `r04typeattendance` */

DROP TABLE IF EXISTS `r04typeattendance`;

CREATE TABLE `r04typeattendance` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDType` varchar(5) NOT NULL,
  `Description` varchar(100) DEFAULT NULL,
  `Note` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

/*Data for the table `r04typeattendance` */

insert  into `r04typeattendance`(`ID`,`IDType`,`Description`,`Note`) values 
(1,'P','PRESENCE','HADIR'),
(2,'A','ABSENCE','MANGKIR'),
(3,'LSN','SICKNESS LEAVE WITH LETTER','SAKIT DENGAN SURAT DOKTER'),
(4,'SN','SICKNESS LEAVE','SAKIT TANPA SURAT DOKTER'),
(5,'AL','ANNUAL LEAVE','CUTI TAHUNAN'),
(6,'MRL','MARRIAGE LEAVE','CUTI MENIKAH'),
(7,'MTL','MATERNITY LEAVE','CUTI MELAHIRKAN'),
(8,'CL','CONDOLENCE LEAVE','CUTI DUKA CITA'),
(9,'SL','SICK LEAVE','CUTI SAKIT'),
(10,'OL','UNPAID LEAVE','CUTI LAINNYA/TDK DIBAYAR'),
(11,'LP','LEAVE PERMIT','IJIN'),
(12,'OT','OFFICIAL TRAVEL','DINAS LUAR'),
(13,'NC','NOT COMPLETE','PRESENSI TIDAK LENGKAP'),
(14,'MP','MANUAL PRESENCE','PRESENSI MANUAL'),
(15,'FML','FORCE MAJEURE LEAVE','CUTI KARENA KEADAAN DARURAT'),
(16,'PLW','PERMISSION','IJIN KULIAH/KURSUS/TRAINING');

/*Table structure for table `r05jobgroup` */

DROP TABLE IF EXISTS `r05jobgroup`;

CREATE TABLE `r05jobgroup` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
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
  `DeleteIP` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `r05jobgroup` */

insert  into `r05jobgroup`(`ID`,`IDJobGroup`,`GroupName`,`AddedBy`,`AddedDate`,`AddedIP`,`EditedBy`,`EditedDate`,`EditedIP`,`DeleteBy`,`DeleteFlag`,`DeleteDate`,`DeleteIP`) values 
(1,'PKR','PASIFIK KREASI PRIMAJAYA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(2,'MIC','MEGAH INTI CEMERLANG',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(3,'S','SURYATEX',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(4,'V','VISTA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(5,'MDP','MENTARI ADHI PRATAMA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(6,'H','HARIAN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(7,'B','BORONGAN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(9,'MAP','MENTARI ADHI PRATAMA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL),
(10,'I','IPACCO',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'A',NULL,NULL);

/*Table structure for table `r06parameter` */

DROP TABLE IF EXISTS `r06parameter`;

CREATE TABLE `r06parameter` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(20) DEFAULT NULL,
  `Value` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `r06parameter` */

insert  into `r06parameter`(`ID`,`Name`,`Value`) values 
(1,'IDSPKL','0');

/*Table structure for table `r07typeleave` */

DROP TABLE IF EXISTS `r07typeleave`;

CREATE TABLE `r07typeleave` (
  `CodeType` varchar(5) NOT NULL,
  `DescType` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`CodeType`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `r07typeleave` */

insert  into `r07typeleave`(`CodeType`,`DescType`) values 
('AL','ANNUAL LEAVE'),
('CIR','CIRCUMCISION LEAVE'),
('CL','CONDOLENCE LEAVE'),
('FML','FORCE MAJEURE LEAVE'),
('MRL','MARRIAGE LEAVE'),
('MTL','MATERNITY LEAVE'),
('OL','UNPAID LEAVE'),
('SL','SICK LEAVE');

/*Table structure for table `t01cardraw` */

DROP TABLE IF EXISTS `t01cardraw`;

CREATE TABLE `t01cardraw` (
  `DataText` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'String Rawdata',
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ProcessBy` varchar(20) DEFAULT NULL,
  `ProcessDate` datetime DEFAULT NULL,
  `ProcessIP` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `t01cardraw` */

/*Table structure for table `t01machine` */

DROP TABLE IF EXISTS `t01machine`;

CREATE TABLE `t01machine` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `EnrollNumber` varchar(20) DEFAULT NULL,
  `EnrollDate` date DEFAULT NULL,
  `EnrollTime` time DEFAULT NULL,
  `Direction` int(2) DEFAULT NULL,
  `Location` int(2) NOT NULL,
  `Machine` int(2) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `SECONDARY` (`EnrollNumber`,`EnrollDate`,`EnrollTime`,`Direction`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `t01machine` */

/*Table structure for table `t02presence` */

DROP TABLE IF EXISTS `t02presence`;

CREATE TABLE `t02presence` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `EnrollNumberIn` varchar(20) DEFAULT NULL,
  `EnrollDateIn` date DEFAULT NULL,
  `EnrollTimeIn` time DEFAULT NULL,
  `EnrollDateOut` date DEFAULT NULL,
  `EnrollTimeOut` time DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `t02presence` */

/*Table structure for table `t02rawdata` */

DROP TABLE IF EXISTS `t02rawdata`;

CREATE TABLE `t02rawdata` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DataText` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'String Rawdata',
  `IDCard` varchar(20) DEFAULT NULL COMMENT 'No. Kartu Barcode/RFID',
  `IDEmployee` varchar(20) DEFAULT NULL COMMENT 'NIP',
  `PresenceDate` date DEFAULT NULL COMMENT 'Tanggal Kehadiran',
  `PresenceTime` time DEFAULT NULL COMMENT 'Jam Kehadiran',
  `Direction` varchar(1) DEFAULT NULL COMMENT 'Datang/Pulang',
  `Location` varchar(1) NOT NULL COMMENT 'Lokasi Absensi',
  `ProcessBy` varchar(20) DEFAULT NULL,
  `ProcessDate` datetime DEFAULT NULL,
  `ProcessIP` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `DATATEXT` (`DataText`),
  KEY `SECONDARY` (`IDEmployee`,`IDCard`,`PresenceDate`,`PresenceTime`,`Direction`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `t02rawdata` */

/*Table structure for table `t03presence` */

DROP TABLE IF EXISTS `t03presence`;

CREATE TABLE `t03presence` (
  `IDPresence` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Records',
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
  `EditedIP` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`IDPresence`),
  UNIQUE KEY `SECONDARY` (`IDEmployee`,`PresenceDate`,`ActualIn`,`ActualOut`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `t03presence` */

/*Table structure for table `t04overtime` */

DROP TABLE IF EXISTS `t04overtime`;

CREATE TABLE `t04overtime` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID Records',
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
  `DeleteFlag` varchar(1) DEFAULT 'A',
  PRIMARY KEY (`ID`),
  KEY `KEY_IDEMPLOYEE` (`IDEmployee`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `t04overtime` */

/*Table structure for table `t05incomplete` */

DROP TABLE IF EXISTS `t05incomplete`;

CREATE TABLE `t05incomplete` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
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
  `DeleteFlag` varchar(1) DEFAULT 'A',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `t05incomplete` */

/*Table structure for table `t06sicknessleave` */

DROP TABLE IF EXISTS `t06sicknessleave`;

CREATE TABLE `t06sicknessleave` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
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
  `EditedIP` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `SECONDARY` (`IDEmployee`,`SicknessDate`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `t06sicknessleave` */

/*Table structure for table `t07officialtravel` */

DROP TABLE IF EXISTS `t07officialtravel`;

CREATE TABLE `t07officialtravel` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
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
  `DeletedIP` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `t07officialtravel` */

/*Table structure for table `t08leavepermit` */

DROP TABLE IF EXISTS `t08leavepermit`;

CREATE TABLE `t08leavepermit` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
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
  `DeletedDate` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `t08leavepermit` */

/*Table structure for table `t08leavepermit_d` */

DROP TABLE IF EXISTS `t08leavepermit_d`;

CREATE TABLE `t08leavepermit_d` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDLeavePermit` varchar(10) NOT NULL,
  `IDEmployee` varchar(10) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t08leavepermit_d` */

/*Table structure for table `t09leave` */

DROP TABLE IF EXISTS `t09leave`;

CREATE TABLE `t09leave` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
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
  `DeleteFlag` varchar(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `t09leave` */

/*Table structure for table `t10suspension` */

DROP TABLE IF EXISTS `t10suspension`;

CREATE TABLE `t10suspension` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
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
  `DeleteFlag` varchar(1) DEFAULT 'A',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `SECONDARY` (`IDEmployee`,`SuspensionDate`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `t10suspension` */

/*Table structure for table `t11leavework` */

DROP TABLE IF EXISTS `t11leavework`;

CREATE TABLE `t11leavework` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
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
  `DeleteFlag` varchar(1) DEFAULT 'A',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `SECONDARY` (`IDEmployee`,`StartDate`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `t11leavework` */

/*Table structure for table `t12employeepicket` */

DROP TABLE IF EXISTS `t12employeepicket`;

CREATE TABLE `t12employeepicket` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
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
  `RangePicket` int(1) DEFAULT '1' COMMENT '1 = One Day, 2 = More One Day',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `t12employeepicket` */

/*Table structure for table `temp01outbond2015` */

DROP TABLE IF EXISTS `temp01outbond2015`;

CREATE TABLE `temp01outbond2015` (
  `IDEmployee` varchar(25) NOT NULL DEFAULT '',
  `FullName` text,
  PRIMARY KEY (`IDEmployee`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `temp01outbond2015` */

/*Table structure for table `tmp02manualpresence` */

DROP TABLE IF EXISTS `tmp02manualpresence`;

CREATE TABLE `tmp02manualpresence` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDEmployee` varchar(15) DEFAULT NULL,
  `PresenceDate` varchar(15) DEFAULT NULL,
  `Note` text,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tmp02manualpresence` */

/*Table structure for table `tparam` */

DROP TABLE IF EXISTS `tparam`;

CREATE TABLE `tparam` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
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
  `DeleteIP` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `tparam` */

insert  into `tparam`(`ID`,`IDParam`,`Val1`,`Val2`,`Val3`,`Val4`,`AddedBy`,`AddedDate`,`AddedIP`,`EditedBy`,`EditedDate`,`EditedIP`,`DeleteBy`,`DeleteFlag`,`DeleteDate`,`DeleteIP`) values 
(1,'overtime','25','25','','','0506021112','2014-09-23 11:00:53','192.168.0.61','0538131212','2015-07-24 13:41:32','192.168.5.23',NULL,'A',NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
