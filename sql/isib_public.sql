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
-- Database: `isib_public`
--
CREATE DATABASE IF NOT EXISTS `isib_public` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `isib_public`;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE IF NOT EXISTS `appointments` (
`id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL DEFAULT '',
  `start_time` datetime DEFAULT NULL,
  `is_reminded` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
CREATE TABLE IF NOT EXISTS `country` (
`country_id` int(5) NOT NULL,
  `iso2` char(2) DEFAULT NULL,
  `short_name` varchar(80) NOT NULL DEFAULT '',
  `long_name` varchar(80) NOT NULL DEFAULT '',
  `iso3` char(3) DEFAULT NULL,
  `numcode` varchar(6) DEFAULT NULL,
  `un_member` varchar(12) DEFAULT NULL,
  `calling_code` varchar(8) DEFAULT NULL,
  `cctld` varchar(5) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=251 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`country_id`, `iso2`, `short_name`, `long_name`, `iso3`, `numcode`, `un_member`, `calling_code`, `cctld`) VALUES
(1, 'AF', 'Afghanistan', 'Islamic Republic of Afghanistan', 'AFG', '004', 'yes', '93', '.af'),
(2, 'AX', 'Aland Islands', '&Aring;land Islands', 'ALA', '248', 'no', '358', '.ax'),
(3, 'AL', 'Albania', 'Republic of Albania', 'ALB', '008', 'yes', '355', '.al'),
(4, 'DZ', 'Algeria', 'People''s Democratic Republic of Algeria', 'DZA', '012', 'yes', '213', '.dz'),
(5, 'AS', 'American Samoa', 'American Samoa', 'ASM', '016', 'no', '1+684', '.as'),
(6, 'AD', 'Andorra', 'Principality of Andorra', 'AND', '020', 'yes', '376', '.ad'),
(7, 'AO', 'Angola', 'Republic of Angola', 'AGO', '024', 'yes', '244', '.ao'),
(8, 'AI', 'Anguilla', 'Anguilla', 'AIA', '660', 'no', '1+264', '.ai'),
(9, 'AQ', 'Antarctica', 'Antarctica', 'ATA', '010', 'no', '672', '.aq'),
(10, 'AG', 'Antigua and Barbuda', 'Antigua and Barbuda', 'ATG', '028', 'yes', '1+268', '.ag'),
(11, 'AR', 'Argentina', 'Argentine Republic', 'ARG', '032', 'yes', '54', '.ar'),
(12, 'AM', 'Armenia', 'Republic of Armenia', 'ARM', '051', 'yes', '374', '.am'),
(13, 'AW', 'Aruba', 'Aruba', 'ABW', '533', 'no', '297', '.aw'),
(14, 'AU', 'Australia', 'Commonwealth of Australia', 'AUS', '036', 'yes', '61', '.au'),
(15, 'AT', 'Austria', 'Republic of Austria', 'AUT', '040', 'yes', '43', '.at'),
(16, 'AZ', 'Azerbaijan', 'Republic of Azerbaijan', 'AZE', '031', 'yes', '994', '.az'),
(17, 'BS', 'Bahamas', 'Commonwealth of The Bahamas', 'BHS', '044', 'yes', '1+242', '.bs'),
(18, 'BH', 'Bahrain', 'Kingdom of Bahrain', 'BHR', '048', 'yes', '973', '.bh'),
(19, 'BD', 'Bangladesh', 'People''s Republic of Bangladesh', 'BGD', '050', 'yes', '880', '.bd'),
(20, 'BB', 'Barbados', 'Barbados', 'BRB', '052', 'yes', '1+246', '.bb'),
(21, 'BY', 'Belarus', 'Republic of Belarus', 'BLR', '112', 'yes', '375', '.by'),
(22, 'BE', 'Belgium', 'Kingdom of Belgium', 'BEL', '056', 'yes', '32', '.be'),
(23, 'BZ', 'Belize', 'Belize', 'BLZ', '084', 'yes', '501', '.bz'),
(24, 'BJ', 'Benin', 'Republic of Benin', 'BEN', '204', 'yes', '229', '.bj'),
(25, 'BM', 'Bermuda', 'Bermuda Islands', 'BMU', '060', 'no', '1+441', '.bm'),
(26, 'BT', 'Bhutan', 'Kingdom of Bhutan', 'BTN', '064', 'yes', '975', '.bt'),
(27, 'BO', 'Bolivia', 'Plurinational State of Bolivia', 'BOL', '068', 'yes', '591', '.bo'),
(28, 'BQ', 'Bonaire, Sint Eustatius and Saba', 'Bonaire, Sint Eustatius and Saba', 'BES', '535', 'no', '599', '.bq'),
(29, 'BA', 'Bosnia and Herzegovina', 'Bosnia and Herzegovina', 'BIH', '070', 'yes', '387', '.ba'),
(30, 'BW', 'Botswana', 'Republic of Botswana', 'BWA', '072', 'yes', '267', '.bw'),
(31, 'BV', 'Bouvet Island', 'Bouvet Island', 'BVT', '074', 'no', 'NONE', '.bv'),
(32, 'BR', 'Brazil', 'Federative Republic of Brazil', 'BRA', '076', 'yes', '55', '.br'),
(33, 'IO', 'British Indian Ocean Territory', 'British Indian Ocean Territory', 'IOT', '086', 'no', '246', '.io'),
(34, 'BN', 'Brunei', 'Brunei Darussalam', 'BRN', '096', 'yes', '673', '.bn'),
(35, 'BG', 'Bulgaria', 'Republic of Bulgaria', 'BGR', '100', 'yes', '359', '.bg'),
(36, 'BF', 'Burkina Faso', 'Burkina Faso', 'BFA', '854', 'yes', '226', '.bf'),
(37, 'BI', 'Burundi', 'Republic of Burundi', 'BDI', '108', 'yes', '257', '.bi'),
(38, 'KH', 'Cambodia', 'Kingdom of Cambodia', 'KHM', '116', 'yes', '855', '.kh'),
(39, 'CM', 'Cameroon', 'Republic of Cameroon', 'CMR', '120', 'yes', '237', '.cm'),
(40, 'CA', 'Canada', 'Canada', 'CAN', '124', 'yes', '1', '.ca'),
(41, 'CV', 'Cape Verde', 'Republic of Cape Verde', 'CPV', '132', 'yes', '238', '.cv'),
(42, 'KY', 'Cayman Islands', 'The Cayman Islands', 'CYM', '136', 'no', '1+345', '.ky'),
(43, 'CF', 'Central African Republic', 'Central African Republic', 'CAF', '140', 'yes', '236', '.cf'),
(44, 'TD', 'Chad', 'Republic of Chad', 'TCD', '148', 'yes', '235', '.td'),
(45, 'CL', 'Chile', 'Republic of Chile', 'CHL', '152', 'yes', '56', '.cl'),
(46, 'CN', 'China', 'People''s Republic of China', 'CHN', '156', 'yes', '86', '.cn'),
(47, 'CX', 'Christmas Island', 'Christmas Island', 'CXR', '162', 'no', '61', '.cx'),
(48, 'CC', 'Cocos (Keeling) Islands', 'Cocos (Keeling) Islands', 'CCK', '166', 'no', '61', '.cc'),
(49, 'CO', 'Colombia', 'Republic of Colombia', 'COL', '170', 'yes', '57', '.co'),
(50, 'KM', 'Comoros', 'Union of the Comoros', 'COM', '174', 'yes', '269', '.km'),
(51, 'CG', 'Congo', 'Republic of the Congo', 'COG', '178', 'yes', '242', '.cg'),
(52, 'CK', 'Cook Islands', 'Cook Islands', 'COK', '184', 'some', '682', '.ck'),
(53, 'CR', 'Costa Rica', 'Republic of Costa Rica', 'CRI', '188', 'yes', '506', '.cr'),
(54, 'CI', 'Cote d''ivoire (Ivory Coast)', 'Republic of C&ocirc;te D''Ivoire (Ivory Coast)', 'CIV', '384', 'yes', '225', '.ci'),
(55, 'HR', 'Croatia', 'Republic of Croatia', 'HRV', '191', 'yes', '385', '.hr'),
(56, 'CU', 'Cuba', 'Republic of Cuba', 'CUB', '192', 'yes', '53', '.cu'),
(57, 'CW', 'Curacao', 'Cura&ccedil;ao', 'CUW', '531', 'no', '599', '.cw'),
(58, 'CY', 'Cyprus', 'Republic of Cyprus', 'CYP', '196', 'yes', '357', '.cy'),
(59, 'CZ', 'Czech Republic', 'Czech Republic', 'CZE', '203', 'yes', '420', '.cz'),
(60, 'CD', 'Democratic Republic of the Congo', 'Democratic Republic of the Congo', 'COD', '180', 'yes', '243', '.cd'),
(61, 'DK', 'Denmark', 'Kingdom of Denmark', 'DNK', '208', 'yes', '45', '.dk'),
(62, 'DJ', 'Djibouti', 'Republic of Djibouti', 'DJI', '262', 'yes', '253', '.dj'),
(63, 'DM', 'Dominica', 'Commonwealth of Dominica', 'DMA', '212', 'yes', '1+767', '.dm'),
(64, 'DO', 'Dominican Republic', 'Dominican Republic', 'DOM', '214', 'yes', '1+809, 8', '.do'),
(65, 'EC', 'Ecuador', 'Republic of Ecuador', 'ECU', '218', 'yes', '593', '.ec'),
(66, 'EG', 'Egypt', 'Arab Republic of Egypt', 'EGY', '818', 'yes', '20', '.eg'),
(67, 'SV', 'El Salvador', 'Republic of El Salvador', 'SLV', '222', 'yes', '503', '.sv'),
(68, 'GQ', 'Equatorial Guinea', 'Republic of Equatorial Guinea', 'GNQ', '226', 'yes', '240', '.gq'),
(69, 'ER', 'Eritrea', 'State of Eritrea', 'ERI', '232', 'yes', '291', '.er'),
(70, 'EE', 'Estonia', 'Republic of Estonia', 'EST', '233', 'yes', '372', '.ee'),
(71, 'ET', 'Ethiopia', 'Federal Democratic Republic of Ethiopia', 'ETH', '231', 'yes', '251', '.et'),
(72, 'FK', 'Falkland Islands (Malvinas)', 'The Falkland Islands (Malvinas)', 'FLK', '238', 'no', '500', '.fk'),
(73, 'FO', 'Faroe Islands', 'The Faroe Islands', 'FRO', '234', 'no', '298', '.fo'),
(74, 'FJ', 'Fiji', 'Republic of Fiji', 'FJI', '242', 'yes', '679', '.fj'),
(75, 'FI', 'Finland', 'Republic of Finland', 'FIN', '246', 'yes', '358', '.fi'),
(76, 'FR', 'France', 'French Republic', 'FRA', '250', 'yes', '33', '.fr'),
(77, 'GF', 'French Guiana', 'French Guiana', 'GUF', '254', 'no', '594', '.gf'),
(78, 'PF', 'French Polynesia', 'French Polynesia', 'PYF', '258', 'no', '689', '.pf'),
(79, 'TF', 'French Southern Territories', 'French Southern Territories', 'ATF', '260', 'no', NULL, '.tf'),
(80, 'GA', 'Gabon', 'Gabonese Republic', 'GAB', '266', 'yes', '241', '.ga'),
(81, 'GM', 'Gambia', 'Republic of The Gambia', 'GMB', '270', 'yes', '220', '.gm'),
(82, 'GE', 'Georgia', 'Georgia', 'GEO', '268', 'yes', '995', '.ge'),
(83, 'DE', 'Germany', 'Federal Republic of Germany', 'DEU', '276', 'yes', '49', '.de'),
(84, 'GH', 'Ghana', 'Republic of Ghana', 'GHA', '288', 'yes', '233', '.gh'),
(85, 'GI', 'Gibraltar', 'Gibraltar', 'GIB', '292', 'no', '350', '.gi'),
(86, 'GR', 'Greece', 'Hellenic Republic', 'GRC', '300', 'yes', '30', '.gr'),
(87, 'GL', 'Greenland', 'Greenland', 'GRL', '304', 'no', '299', '.gl'),
(88, 'GD', 'Grenada', 'Grenada', 'GRD', '308', 'yes', '1+473', '.gd'),
(89, 'GP', 'Guadaloupe', 'Guadeloupe', 'GLP', '312', 'no', '590', '.gp'),
(90, 'GU', 'Guam', 'Guam', 'GUM', '316', 'no', '1+671', '.gu'),
(91, 'GT', 'Guatemala', 'Republic of Guatemala', 'GTM', '320', 'yes', '502', '.gt'),
(92, 'GG', 'Guernsey', 'Guernsey', 'GGY', '831', 'no', '44', '.gg'),
(93, 'GN', 'Guinea', 'Republic of Guinea', 'GIN', '324', 'yes', '224', '.gn'),
(94, 'GW', 'Guinea-Bissau', 'Republic of Guinea-Bissau', 'GNB', '624', 'yes', '245', '.gw'),
(95, 'GY', 'Guyana', 'Co-operative Republic of Guyana', 'GUY', '328', 'yes', '592', '.gy'),
(96, 'HT', 'Haiti', 'Republic of Haiti', 'HTI', '332', 'yes', '509', '.ht'),
(97, 'HM', 'Heard Island and McDonald Islands', 'Heard Island and McDonald Islands', 'HMD', '334', 'no', 'NONE', '.hm'),
(98, 'HN', 'Honduras', 'Republic of Honduras', 'HND', '340', 'yes', '504', '.hn'),
(99, 'HK', 'Hong Kong', 'Hong Kong', 'HKG', '344', 'no', '852', '.hk'),
(100, 'HU', 'Hungary', 'Hungary', 'HUN', '348', 'yes', '36', '.hu'),
(101, 'IS', 'Iceland', 'Republic of Iceland', 'ISL', '352', 'yes', '354', '.is'),
(102, 'IN', 'India', 'Republic of India', 'IND', '356', 'yes', '91', '.in'),
(103, 'ID', 'Indonesia', 'Republic of Indonesia', 'IDN', '360', 'yes', '62', '.id'),
(104, 'IR', 'Iran', 'Islamic Republic of Iran', 'IRN', '364', 'yes', '98', '.ir'),
(105, 'IQ', 'Iraq', 'Republic of Iraq', 'IRQ', '368', 'yes', '964', '.iq'),
(106, 'IE', 'Ireland', 'Ireland', 'IRL', '372', 'yes', '353', '.ie'),
(107, 'IM', 'Isle of Man', 'Isle of Man', 'IMN', '833', 'no', '44', '.im'),
(108, 'IL', 'Israel', 'State of Israel', 'ISR', '376', 'yes', '972', '.il'),
(109, 'IT', 'Italy', 'Italian Republic', 'ITA', '380', 'yes', '39', '.jm'),
(110, 'JM', 'Jamaica', 'Jamaica', 'JAM', '388', 'yes', '1+876', '.jm'),
(111, 'JP', 'Japan', 'Japan', 'JPN', '392', 'yes', '81', '.jp'),
(112, 'JE', 'Jersey', 'The Bailiwick of Jersey', 'JEY', '832', 'no', '44', '.je'),
(113, 'JO', 'Jordan', 'Hashemite Kingdom of Jordan', 'JOR', '400', 'yes', '962', '.jo'),
(114, 'KZ', 'Kazakhstan', 'Republic of Kazakhstan', 'KAZ', '398', 'yes', '7', '.kz'),
(115, 'KE', 'Kenya', 'Republic of Kenya', 'KEN', '404', 'yes', '254', '.ke'),
(116, 'KI', 'Kiribati', 'Republic of Kiribati', 'KIR', '296', 'yes', '686', '.ki'),
(117, 'XK', 'Kosovo', 'Republic of Kosovo', '---', '---', 'some', '381', ''),
(118, 'KW', 'Kuwait', 'State of Kuwait', 'KWT', '414', 'yes', '965', '.kw'),
(119, 'KG', 'Kyrgyzstan', 'Kyrgyz Republic', 'KGZ', '417', 'yes', '996', '.kg'),
(120, 'LA', 'Laos', 'Lao People''s Democratic Republic', 'LAO', '418', 'yes', '856', '.la'),
(121, 'LV', 'Latvia', 'Republic of Latvia', 'LVA', '428', 'yes', '371', '.lv'),
(122, 'LB', 'Lebanon', 'Republic of Lebanon', 'LBN', '422', 'yes', '961', '.lb'),
(123, 'LS', 'Lesotho', 'Kingdom of Lesotho', 'LSO', '426', 'yes', '266', '.ls'),
(124, 'LR', 'Liberia', 'Republic of Liberia', 'LBR', '430', 'yes', '231', '.lr'),
(125, 'LY', 'Libya', 'Libya', 'LBY', '434', 'yes', '218', '.ly'),
(126, 'LI', 'Liechtenstein', 'Principality of Liechtenstein', 'LIE', '438', 'yes', '423', '.li'),
(127, 'LT', 'Lithuania', 'Republic of Lithuania', 'LTU', '440', 'yes', '370', '.lt'),
(128, 'LU', 'Luxembourg', 'Grand Duchy of Luxembourg', 'LUX', '442', 'yes', '352', '.lu'),
(129, 'MO', 'Macao', 'The Macao Special Administrative Region', 'MAC', '446', 'no', '853', '.mo'),
(130, 'MK', 'Macedonia', 'The Former Yugoslav Republic of Macedonia', 'MKD', '807', 'yes', '389', '.mk'),
(131, 'MG', 'Madagascar', 'Republic of Madagascar', 'MDG', '450', 'yes', '261', '.mg'),
(132, 'MW', 'Malawi', 'Republic of Malawi', 'MWI', '454', 'yes', '265', '.mw'),
(133, 'MY', 'Malaysia', 'Malaysia', 'MYS', '458', 'yes', '60', '.my'),
(134, 'MV', 'Maldives', 'Republic of Maldives', 'MDV', '462', 'yes', '960', '.mv'),
(135, 'ML', 'Mali', 'Republic of Mali', 'MLI', '466', 'yes', '223', '.ml'),
(136, 'MT', 'Malta', 'Republic of Malta', 'MLT', '470', 'yes', '356', '.mt'),
(137, 'MH', 'Marshall Islands', 'Republic of the Marshall Islands', 'MHL', '584', 'yes', '692', '.mh'),
(138, 'MQ', 'Martinique', 'Martinique', 'MTQ', '474', 'no', '596', '.mq'),
(139, 'MR', 'Mauritania', 'Islamic Republic of Mauritania', 'MRT', '478', 'yes', '222', '.mr'),
(140, 'MU', 'Mauritius', 'Republic of Mauritius', 'MUS', '480', 'yes', '230', '.mu'),
(141, 'YT', 'Mayotte', 'Mayotte', 'MYT', '175', 'no', '262', '.yt'),
(142, 'MX', 'Mexico', 'United Mexican States', 'MEX', '484', 'yes', '52', '.mx'),
(143, 'FM', 'Micronesia', 'Federated States of Micronesia', 'FSM', '583', 'yes', '691', '.fm'),
(144, 'MD', 'Moldava', 'Republic of Moldova', 'MDA', '498', 'yes', '373', '.md'),
(145, 'MC', 'Monaco', 'Principality of Monaco', 'MCO', '492', 'yes', '377', '.mc'),
(146, 'MN', 'Mongolia', 'Mongolia', 'MNG', '496', 'yes', '976', '.mn'),
(147, 'ME', 'Montenegro', 'Montenegro', 'MNE', '499', 'yes', '382', '.me'),
(148, 'MS', 'Montserrat', 'Montserrat', 'MSR', '500', 'no', '1+664', '.ms'),
(149, 'MA', 'Morocco', 'Kingdom of Morocco', 'MAR', '504', 'yes', '212', '.ma'),
(150, 'MZ', 'Mozambique', 'Republic of Mozambique', 'MOZ', '508', 'yes', '258', '.mz'),
(151, 'MM', 'Myanmar (Burma)', 'Republic of the Union of Myanmar', 'MMR', '104', 'yes', '95', '.mm'),
(152, 'NA', 'Namibia', 'Republic of Namibia', 'NAM', '516', 'yes', '264', '.na'),
(153, 'NR', 'Nauru', 'Republic of Nauru', 'NRU', '520', 'yes', '674', '.nr'),
(154, 'NP', 'Nepal', 'Federal Democratic Republic of Nepal', 'NPL', '524', 'yes', '977', '.np'),
(155, 'NL', 'Netherlands', 'Kingdom of the Netherlands', 'NLD', '528', 'yes', '31', '.nl'),
(156, 'NC', 'New Caledonia', 'New Caledonia', 'NCL', '540', 'no', '687', '.nc'),
(157, 'NZ', 'New Zealand', 'New Zealand', 'NZL', '554', 'yes', '64', '.nz'),
(158, 'NI', 'Nicaragua', 'Republic of Nicaragua', 'NIC', '558', 'yes', '505', '.ni'),
(159, 'NE', 'Niger', 'Republic of Niger', 'NER', '562', 'yes', '227', '.ne'),
(160, 'NG', 'Nigeria', 'Federal Republic of Nigeria', 'NGA', '566', 'yes', '234', '.ng'),
(161, 'NU', 'Niue', 'Niue', 'NIU', '570', 'some', '683', '.nu'),
(162, 'NF', 'Norfolk Island', 'Norfolk Island', 'NFK', '574', 'no', '672', '.nf'),
(163, 'KP', 'North Korea', 'Democratic People''s Republic of Korea', 'PRK', '408', 'yes', '850', '.kp'),
(164, 'MP', 'Northern Mariana Islands', 'Northern Mariana Islands', 'MNP', '580', 'no', '1+670', '.mp'),
(165, 'NO', 'Norway', 'Kingdom of Norway', 'NOR', '578', 'yes', '47', '.no'),
(166, 'OM', 'Oman', 'Sultanate of Oman', 'OMN', '512', 'yes', '968', '.om'),
(167, 'PK', 'Pakistan', 'Islamic Republic of Pakistan', 'PAK', '586', 'yes', '92', '.pk'),
(168, 'PW', 'Palau', 'Republic of Palau', 'PLW', '585', 'yes', '680', '.pw'),
(169, 'PS', 'Palestine', 'State of Palestine (or Occupied Palestinian Territory)', 'PSE', '275', 'some', '970', '.ps'),
(170, 'PA', 'Panama', 'Republic of Panama', 'PAN', '591', 'yes', '507', '.pa'),
(171, 'PG', 'Papua New Guinea', 'Independent State of Papua New Guinea', 'PNG', '598', 'yes', '675', '.pg'),
(172, 'PY', 'Paraguay', 'Republic of Paraguay', 'PRY', '600', 'yes', '595', '.py'),
(173, 'PE', 'Peru', 'Republic of Peru', 'PER', '604', 'yes', '51', '.pe'),
(174, 'PH', 'Phillipines', 'Republic of the Philippines', 'PHL', '608', 'yes', '63', '.ph'),
(175, 'PN', 'Pitcairn', 'Pitcairn', 'PCN', '612', 'no', 'NONE', '.pn'),
(176, 'PL', 'Poland', 'Republic of Poland', 'POL', '616', 'yes', '48', '.pl'),
(177, 'PT', 'Portugal', 'Portuguese Republic', 'PRT', '620', 'yes', '351', '.pt'),
(178, 'PR', 'Puerto Rico', 'Commonwealth of Puerto Rico', 'PRI', '630', 'no', '1+939', '.pr'),
(179, 'QA', 'Qatar', 'State of Qatar', 'QAT', '634', 'yes', '974', '.qa'),
(180, 'RE', 'Reunion', 'R&eacute;union', 'REU', '638', 'no', '262', '.re'),
(181, 'RO', 'Romania', 'Romania', 'ROU', '642', 'yes', '40', '.ro'),
(182, 'RU', 'Russia', 'Russian Federation', 'RUS', '643', 'yes', '7', '.ru'),
(183, 'RW', 'Rwanda', 'Republic of Rwanda', 'RWA', '646', 'yes', '250', '.rw'),
(184, 'BL', 'Saint Barthelemy', 'Saint Barth&eacute;lemy', 'BLM', '652', 'no', '590', '.bl'),
(185, 'SH', 'Saint Helena', 'Saint Helena, Ascension and Tristan da Cunha', 'SHN', '654', 'no', '290', '.sh'),
(186, 'KN', 'Saint Kitts and Nevis', 'Federation of Saint Christopher and Nevis', 'KNA', '659', 'yes', '1+869', '.kn'),
(187, 'LC', 'Saint Lucia', 'Saint Lucia', 'LCA', '662', 'yes', '1+758', '.lc'),
(188, 'MF', 'Saint Martin', 'Saint Martin', 'MAF', '663', 'no', '590', '.mf'),
(189, 'PM', 'Saint Pierre and Miquelon', 'Saint Pierre and Miquelon', 'SPM', '666', 'no', '508', '.pm'),
(190, 'VC', 'Saint Vincent and the Grenadines', 'Saint Vincent and the Grenadines', 'VCT', '670', 'yes', '1+784', '.vc'),
(191, 'WS', 'Samoa', 'Independent State of Samoa', 'WSM', '882', 'yes', '685', '.ws'),
(192, 'SM', 'San Marino', 'Republic of San Marino', 'SMR', '674', 'yes', '378', '.sm'),
(193, 'ST', 'Sao Tome and Principe', 'Democratic Republic of S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'STP', '678', 'yes', '239', '.st'),
(194, 'SA', 'Saudi Arabia', 'Kingdom of Saudi Arabia', 'SAU', '682', 'yes', '966', '.sa'),
(195, 'SN', 'Senegal', 'Republic of Senegal', 'SEN', '686', 'yes', '221', '.sn'),
(196, 'RS', 'Serbia', 'Republic of Serbia', 'SRB', '688', 'yes', '381', '.rs'),
(197, 'SC', 'Seychelles', 'Republic of Seychelles', 'SYC', '690', 'yes', '248', '.sc'),
(198, 'SL', 'Sierra Leone', 'Republic of Sierra Leone', 'SLE', '694', 'yes', '232', '.sl'),
(199, 'SG', 'Singapore', 'Republic of Singapore', 'SGP', '702', 'yes', '65', '.sg'),
(200, 'SX', 'Sint Maarten', 'Sint Maarten', 'SXM', '534', 'no', '1+721', '.sx'),
(201, 'SK', 'Slovakia', 'Slovak Republic', 'SVK', '703', 'yes', '421', '.sk'),
(202, 'SI', 'Slovenia', 'Republic of Slovenia', 'SVN', '705', 'yes', '386', '.si'),
(203, 'SB', 'Solomon Islands', 'Solomon Islands', 'SLB', '090', 'yes', '677', '.sb'),
(204, 'SO', 'Somalia', 'Somali Republic', 'SOM', '706', 'yes', '252', '.so'),
(205, 'ZA', 'South Africa', 'Republic of South Africa', 'ZAF', '710', 'yes', '27', '.za'),
(206, 'GS', 'South Georgia and the South Sandwich Islands', 'South Georgia and the South Sandwich Islands', 'SGS', '239', 'no', '500', '.gs'),
(207, 'KR', 'South Korea', 'Republic of Korea', 'KOR', '410', 'yes', '82', '.kr'),
(208, 'SS', 'South Sudan', 'Republic of South Sudan', 'SSD', '728', 'yes', '211', '.ss'),
(209, 'ES', 'Spain', 'Kingdom of Spain', 'ESP', '724', 'yes', '34', '.es'),
(210, 'LK', 'Sri Lanka', 'Democratic Socialist Republic of Sri Lanka', 'LKA', '144', 'yes', '94', '.lk'),
(211, 'SD', 'Sudan', 'Republic of the Sudan', 'SDN', '729', 'yes', '249', '.sd'),
(212, 'SR', 'Suriname', 'Republic of Suriname', 'SUR', '740', 'yes', '597', '.sr'),
(213, 'SJ', 'Svalbard and Jan Mayen', 'Svalbard and Jan Mayen', 'SJM', '744', 'no', '47', '.sj'),
(214, 'SZ', 'Swaziland', 'Kingdom of Swaziland', 'SWZ', '748', 'yes', '268', '.sz'),
(215, 'SE', 'Sweden', 'Kingdom of Sweden', 'SWE', '752', 'yes', '46', '.se'),
(216, 'CH', 'Switzerland', 'Swiss Confederation', 'CHE', '756', 'yes', '41', '.ch'),
(217, 'SY', 'Syria', 'Syrian Arab Republic', 'SYR', '760', 'yes', '963', '.sy'),
(218, 'TW', 'Taiwan', 'Republic of China (Taiwan)', 'TWN', '158', 'former', '886', '.tw'),
(219, 'TJ', 'Tajikistan', 'Republic of Tajikistan', 'TJK', '762', 'yes', '992', '.tj'),
(220, 'TZ', 'Tanzania', 'United Republic of Tanzania', 'TZA', '834', 'yes', '255', '.tz'),
(221, 'TH', 'Thailand', 'Kingdom of Thailand', 'THA', '764', 'yes', '66', '.th'),
(222, 'TL', 'Timor-Leste (East Timor)', 'Democratic Republic of Timor-Leste', 'TLS', '626', 'yes', '670', '.tl'),
(223, 'TG', 'Togo', 'Togolese Republic', 'TGO', '768', 'yes', '228', '.tg'),
(224, 'TK', 'Tokelau', 'Tokelau', 'TKL', '772', 'no', '690', '.tk'),
(225, 'TO', 'Tonga', 'Kingdom of Tonga', 'TON', '776', 'yes', '676', '.to'),
(226, 'TT', 'Trinidad and Tobago', 'Republic of Trinidad and Tobago', 'TTO', '780', 'yes', '1+868', '.tt'),
(227, 'TN', 'Tunisia', 'Republic of Tunisia', 'TUN', '788', 'yes', '216', '.tn'),
(228, 'TR', 'Turkey', 'Republic of Turkey', 'TUR', '792', 'yes', '90', '.tr'),
(229, 'TM', 'Turkmenistan', 'Turkmenistan', 'TKM', '795', 'yes', '993', '.tm'),
(230, 'TC', 'Turks and Caicos Islands', 'Turks and Caicos Islands', 'TCA', '796', 'no', '1+649', '.tc'),
(231, 'TV', 'Tuvalu', 'Tuvalu', 'TUV', '798', 'yes', '688', '.tv'),
(232, 'UG', 'Uganda', 'Republic of Uganda', 'UGA', '800', 'yes', '256', '.ug'),
(233, 'UA', 'Ukraine', 'Ukraine', 'UKR', '804', 'yes', '380', '.ua'),
(234, 'AE', 'United Arab Emirates', 'United Arab Emirates', 'ARE', '784', 'yes', '971', '.ae'),
(235, 'GB', 'United Kingdom', 'United Kingdom of Great Britain and Nothern Ireland', 'GBR', '826', 'yes', '44', '.uk'),
(236, 'US', 'United States', 'United States of America', 'USA', '840', 'yes', '1', '.us'),
(237, 'UM', 'United States Minor Outlying Islands', 'United States Minor Outlying Islands', 'UMI', '581', 'no', 'NONE', 'NONE'),
(238, 'UY', 'Uruguay', 'Eastern Republic of Uruguay', 'URY', '858', 'yes', '598', '.uy'),
(239, 'UZ', 'Uzbekistan', 'Republic of Uzbekistan', 'UZB', '860', 'yes', '998', '.uz'),
(240, 'VU', 'Vanuatu', 'Republic of Vanuatu', 'VUT', '548', 'yes', '678', '.vu'),
(241, 'VA', 'Vatican City', 'State of the Vatican City', 'VAT', '336', 'no', '39', '.va'),
(242, 'VE', 'Venezuela', 'Bolivarian Republic of Venezuela', 'VEN', '862', 'yes', '58', '.ve'),
(243, 'VN', 'Vietnam', 'Socialist Republic of Vietnam', 'VNM', '704', 'yes', '84', '.vn'),
(244, 'VG', 'Virgin Islands, British', 'British Virgin Islands', 'VGB', '092', 'no', '1+284', '.vg'),
(245, 'VI', 'Virgin Islands, US', 'Virgin Islands of the United States', 'VIR', '850', 'no', '1+340', '.vi'),
(246, 'WF', 'Wallis and Futuna', 'Wallis and Futuna', 'WLF', '876', 'no', '681', '.wf'),
(247, 'EH', 'Western Sahara', 'Western Sahara', 'ESH', '732', 'no', '212', '.eh'),
(248, 'YE', 'Yemen', 'Republic of Yemen', 'YEM', '887', 'yes', '967', '.ye'),
(249, 'ZM', 'Zambia', 'Republic of Zambia', 'ZMB', '894', 'yes', '260', '.zm'),
(250, 'ZW', 'Zimbabwe', 'Republic of Zimbabwe', 'ZWE', '716', 'yes', '263', '.zw');

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
  `UrlAttachment1` text,
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
-- Table structure for table `emailnotvalid`
--

DROP TABLE IF EXISTS `emailnotvalid`;
CREATE TABLE IF NOT EXISTS `emailnotvalid` (
`ID` int(11) NOT NULL,
  `InternalEmail` varchar(100) DEFAULT NULL
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
  `F1s1` int(1) NOT NULL DEFAULT '0',
  `F1s2` int(1) NOT NULL DEFAULT '0',
  `F1s3` int(1) NOT NULL DEFAULT '0',
  `F1s4` int(1) NOT NULL DEFAULT '0',
  `F1s5` int(1) NOT NULL DEFAULT '0',
  `F2` int(11) DEFAULT NULL,
  `F2f2` int(1) DEFAULT '0',
  `F2f1` int(1) NOT NULL DEFAULT '0',
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
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB AUTO_INCREMENT=701 DEFAULT CHARSET=utf8 COMMENT='versi baru m01 personal';

--
-- Dumping data for table `m01personal`
--

INSERT INTO `m01personal` (`ID`, `IDEmployee`, `IDEmployeeParent`, `IDEmployeePTL`, `FullName`, `NickName`, `BirthPlace`, `BirthDate`, `Gender`, `BloodType`, `Citizenship`, `Height`, `Weight`, `Religion`, `MaritalStatus`, `MarriageCertificate`, `CoupleName`, `CoupleKTP`, `FamilyMemberCertificate`, `NoKTP`, `NoAKDHK`, `NoNPWP`, `NoJamsostek`, `NoKPJ`, `BankAccount`, `LiveAddress`, `KTPAddress`, `NoHP`, `LiveAddressNoTelp`, `KTPAddressNoTelp`, `NumberChildren`, `InternalEmail`, `ExternalEmail`, `EditedBy`, `EditedDate`, `EditedIP`, `F1`, `F1s1`, `F1s2`, `F1s3`, `F1s4`, `F1s5`, `F2`, `F2f2`, `F2f1`, `F3`, `F4`, `F5`, `F6`, `F7`, `NoBPJSEmp`, `NoBPJSHlt`, `NoFamCert`, `LiveProvince`, `LiveCity`, `LiveSubdistrict`, `LiveVillage`, `LiveRW`, `LiveRT`, `KTPProvince`, `KTPCity`, `KTPSubdistrict`, `KTPVillage`, `KTPRT`, `KTPRW`, `LivePostalCode`, `KTPPostalCode`, `DeletedBy`, `DeletedIP`, `DeletedDate`, `DeleteFlag`) VALUES
(397, '0506021112', NULL, NULL, 'AHMAD RIADI', 'RIADI', 'TANGERANG', '1989-12-16', 'M', 'A', 'INDONESIA', 168, 72, 'ISLAM', 'SINGLE', '0', '-', 'N', 'yes', '3603181612890002', '027800806JKU 43', '98.366.931.8-451.000', '-', '12043352421', '5180130440111', 'Mess PT. Trias Indra Saputra, Sentra Industri Terpadu Pantai Indah Kapuk, Jl. Dokter Kamal Muara VII Blok A No.6 Jakarta Utara 14470', 'Jl. Raya Serang KM 15, Kp. Talaga Rt/Rw. 04/01, Cikupa Tangerang Banten 15710', '089601286802', '&#039;', '&#039;', 0, 'riadi@tis.loc', 'testerdev72@gmail.com,info.riadii@gmail.com', '0506021112', '2015-06-18', '192.168.0.61', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '-', '-', '3603182805080015', 'DKI Jakarta', 'KOTA JAKARTA UTARA', 'PENJARINGAN', 'KAMAL MUARA', '', '', 'Banten', 'KABUPATEN TANGGERANG', 'CIKUPA', 'TALAGA', '04', '01', '14470', '15710', NULL, NULL, NULL, 'A'),
(700, '0001141219', '0506021112', 'undefined', 'ADMINISTRATOR', 'ADMIN', '', '1970-01-01', 'M', 'A', '', 0, 0, 'ISLAM', 'undefined', '0', '0', '0', '0', '', '', '', '', '', '', '', '', '', '', '', 0, '', '', '0001141219', '2019-12-14', '::1', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '-', '-', '-', 'null', 'null', 'null', 'null', '', '', 'null', 'null', 'null', 'null', '', '', '', '', NULL, NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `m01personal_course`
--

DROP TABLE IF EXISTS `m01personal_course`;
CREATE TABLE IF NOT EXISTS `m01personal_course` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(10) NOT NULL,
  `IDCourse` varchar(5) NOT NULL,
  `CourseProgram` varchar(30) NOT NULL,
  `CourseFacilitator` varchar(100) NOT NULL,
  `City` varchar(70) NOT NULL,
  `Duration` varchar(15) NOT NULL,
  `YearFrom` int(11) NOT NULL,
  `YearUntil` int(11) NOT NULL,
  `DeletedBy` varchar(20) DEFAULT NULL,
  `DeletedIP` varchar(20) DEFAULT NULL,
  `DeletedDate` datetime DEFAULT NULL,
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `m01personal_course`
--

INSERT INTO `m01personal_course` (`ID`, `IDEmployee`, `IDCourse`, `CourseProgram`, `CourseFacilitator`, `City`, `Duration`, `YearFrom`, `YearUntil`, `DeletedBy`, `DeletedIP`, `DeletedDate`, `DeleteFlag`) VALUES
(1, '0506021112', '1', 'BINTEK ENTREPRENEUR DAN KEPEMI', 'GUBERNUR BANTEN', 'SERANG', '3 hari', 2010, 2010, NULL, NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `m01personal_education`
--

DROP TABLE IF EXISTS `m01personal_education`;
CREATE TABLE IF NOT EXISTS `m01personal_education` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(10) NOT NULL,
  `IDEducation` varchar(5) NOT NULL,
  `EducationLevel` varchar(10) NOT NULL,
  `Course` varchar(80) NOT NULL,
  `SchoolName` varchar(100) NOT NULL,
  `City` varchar(50) NOT NULL,
  `YearFrom` int(11) NOT NULL,
  `YearUntil` int(11) NOT NULL,
  `Certificate` varchar(5) NOT NULL,
  `DeletedBy` varchar(20) DEFAULT NULL,
  `DeletedIP` varchar(20) DEFAULT NULL,
  `DeletedDate` datetime DEFAULT NULL,
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB AUTO_INCREMENT=399 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `m01personal_education`
--

INSERT INTO `m01personal_education` (`ID`, `IDEmployee`, `IDEducation`, `EducationLevel`, `Course`, `SchoolName`, `City`, `YearFrom`, `YearUntil`, `Certificate`, `DeletedBy`, `DeletedIP`, `DeletedDate`, `DeleteFlag`) VALUES
(1, '0506021112', '1', 'S1', 'TEKNIK INFORMATIKA', 'STMIK RAHARJA', 'TANGERANG', 2009, 2012, 'yes', NULL, NULL, NULL, 'A'),
(198, '0506021112', '2', 'SMA/SMK', 'TEKNIK MESIN', 'SMK YUPPENTEK 03', 'TANGERANG', 2005, 2008, 'yes', NULL, NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `m01personal_family`
--

DROP TABLE IF EXISTS `m01personal_family`;
CREATE TABLE IF NOT EXISTS `m01personal_family` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(10) NOT NULL,
  `IDFamily` varchar(5) NOT NULL,
  `NoKTP` varchar(75) NOT NULL,
  `FamilyMember` varchar(15) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `BirthPlace` varchar(50) DEFAULT NULL,
  `BirthDate` date DEFAULT NULL,
  `Age` int(11) NOT NULL,
  `Address` text NOT NULL,
  `Education` varchar(10) NOT NULL,
  `Occupation` varchar(30) NOT NULL,
  `DeletedBy` varchar(20) DEFAULT NULL,
  `DeletedIP` varchar(20) DEFAULT NULL,
  `DeletedDate` datetime DEFAULT NULL,
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB AUTO_INCREMENT=685 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `m01personal_family`
--

INSERT INTO `m01personal_family` (`ID`, `IDEmployee`, `IDFamily`, `NoKTP`, `FamilyMember`, `Name`, `BirthPlace`, `BirthDate`, `Age`, `Address`, `Education`, `Occupation`, `DeletedBy`, `DeletedIP`, `DeletedDate`, `DeleteFlag`) VALUES
(23, '0506021112', '1', '', 'father', 'SAID', NULL, NULL, 50, 'Jl. Raya Serang Km 15 kec. Cikupa - Tangerang Rt 04/ 01 kode pos 15710', 'SMP', 'Karyawan Swasta', NULL, NULL, NULL, 'A'),
(24, '0506021112', '2', '', 'mother', 'MARYAM', '', '1970-01-01', 46, 'Jl Raya Serang Km 15, Kec Cikupa - Tangerang, RT 05/01 15710', 'SD', 'ibu Rumah Tangga', NULL, NULL, NULL, 'A'),
(53, '0506021112', '3', '', 'sibling', 'MUHAMAD MUHLIS', NULL, NULL, 22, 'Jl Raya Serang Km 15, Kec Cikupa - Tangerang, RT 05/01 15710', 'SMK', 'Karyawan Swasta', NULL, NULL, NULL, 'A'),
(54, '0506021112', '4', '', 'sibling', 'MUHAMAD HENDRI', NULL, NULL, 17, 'Jl Raya Serang Km 15, Kec Cikupa - Tangerang, RT 05/01 15710', 'SMA', 'Pelajar', NULL, NULL, NULL, 'A'),
(55, '0506021112', '5', '', 'sibling', 'PUTRI', NULL, NULL, 5, 'Jl Raya Serang Km 15, Kec Cikupa - Tangerang, RT 05/01 15710', 'TK', 'Pelajar', NULL, NULL, NULL, 'A'),
(683, '0001141219', '1', '0', 'spouse', '0', NULL, NULL, 0, '', '', '', NULL, NULL, NULL, 'A'),
(684, '0001141219', '2', '123', 'mother', 'TEST', '', '1970-01-01', 0, '', '', '', NULL, NULL, NULL, 'A');

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
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB AUTO_INCREMENT=704 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `m01personal_job`
--

INSERT INTO `m01personal_job` (`ID`, `IDEmployee`, `IDEmployeeParent`, `IDEmployeePTL`, `Location`, `JobGroup`, `Department`, `Position`, `Unit`, `DateFirstJoin`, `DateStartProbation`, `DateEndProbation`, `DatePassProbation`, `DateNewContract`, `DateEndContract`, `DateInField`, `Status`, `HireDate`, `FlagHire`, `ResignDate`, `FlagResign`, `EmployeeStatus`, `ResignReason`, `Note`, `DeletedBy`, `DeletedIP`, `DeletedDate`, `DeleteFlag`) VALUES
(397, '0506021112', '0267190710', NULL, 'KAPUK', 'ST', '19', 'SUPERVISOR', '', '2012-11-02', NULL, NULL, '2013-02-01', NULL, NULL, NULL, 'A', '2012-11-02', 1, NULL, '0', 'TETAP', NULL, NULL, NULL, NULL, NULL, 'A'),
(703, '0001141219', '0506021112', 'undefined', 'KAPUK', 'ST', '1', 'DIRECTOR', '', NULL, '2019-12-14', NULL, NULL, NULL, NULL, NULL, 'A', '2019-12-14', NULL, NULL, NULL, 'TETAP', NULL, NULL, NULL, NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `m01personal_language`
--

DROP TABLE IF EXISTS `m01personal_language`;
CREATE TABLE IF NOT EXISTS `m01personal_language` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(10) NOT NULL,
  `IDLanguage` varchar(5) NOT NULL,
  `Language` varchar(25) NOT NULL,
  `Reading` varchar(10) NOT NULL,
  `Listening` varchar(10) NOT NULL,
  `Conversation` varchar(10) NOT NULL,
  `Writing` varchar(10) NOT NULL,
  `DeletedBy` varchar(20) DEFAULT NULL,
  `DeletedIP` varchar(20) DEFAULT NULL,
  `DeletedDate` datetime DEFAULT NULL,
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `m01personal_language`
--

INSERT INTO `m01personal_language` (`ID`, `IDEmployee`, `IDLanguage`, `Language`, `Reading`, `Listening`, `Conversation`, `Writing`, `DeletedBy`, `DeletedIP`, `DeletedDate`, `DeleteFlag`) VALUES
(1, '0506021112', '1', 'INDONESIA', '100', '100', '100', '100', NULL, NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `m01personal_workexp`
--

DROP TABLE IF EXISTS `m01personal_workexp`;
CREATE TABLE IF NOT EXISTS `m01personal_workexp` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(10) NOT NULL,
  `IDWorkExp` varchar(5) NOT NULL,
  `CompanyName` varchar(50) NOT NULL,
  `CompanyAddress` varchar(200) NOT NULL,
  `CompanyPhone` varchar(15) NOT NULL,
  `Position` varchar(25) NOT NULL,
  `WorkDuration` varchar(20) NOT NULL,
  `DeletedBy` varchar(20) DEFAULT NULL,
  `DeletedIP` varchar(20) DEFAULT NULL,
  `DeletedDate` datetime DEFAULT NULL,
  `DeleteFlag` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `m03organization`
--

DROP TABLE IF EXISTS `m03organization`;
CREATE TABLE IF NOT EXISTS `m03organization` (
`ID` int(11) NOT NULL,
  `IDStructure` int(5) NOT NULL,
  `IDStructureParent` int(5) NOT NULL,
  `RelType` varchar(2) NOT NULL,
  `DescStructure` varchar(50) NOT NULL,
  `Level` varchar(3) NOT NULL,
  `AddedBy` varchar(20) NOT NULL,
  `AddedDate` datetime NOT NULL,
  `AddedIP` varchar(20) NOT NULL,
  `EditedBy` varchar(20) NOT NULL,
  `EditedDate` datetime NOT NULL,
  `EditedIP` varchar(20) NOT NULL,
  `DeleteBy` varchar(20) NOT NULL,
  `DeleteFlag` varchar(3) NOT NULL DEFAULT 'A' COMMENT 'Jika A maka di tampilkan jika D maka tidak di tampilkan',
  `DeleteDate` datetime NOT NULL,
  `DeleteIP` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `m03organization`
--

INSERT INTO `m03organization` (`ID`, `IDStructure`, `IDStructureParent`, `RelType`, `DescStructure`, `Level`, `AddedBy`, `AddedDate`, `AddedIP`, `EditedBy`, `EditedDate`, `EditedIP`, `DeleteBy`, `DeleteFlag`, `DeleteDate`, `DeleteIP`) VALUES
(1, 1, 0, '', 'MANAGING DIRECTOR', '1', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(2, 2, 0, '', 'SECRETARY', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(3, 3, 0, '', 'OPERATIONAL DIRECTOR', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(4, 4, 0, '', 'TECHNICAL DIRECTOR', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(5, 5, 0, '', 'ASST TECHNICAL DIRECTOR & DEVELOPMENT', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(6, 6, 0, '', 'B & D BUILDING', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(7, 7, 0, '', 'B & D INDUSTRY', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(8, 8, 0, '', 'MARCOMM', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(9, 9, 0, '', 'CMS', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(10, 10, 0, '', 'BUILDING & FACILITY MANAGEMENT', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(11, 11, 0, '', 'LOGISTIC', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(12, 12, 0, '', 'HSE', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(13, 13, 0, '', 'RTC', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(14, 14, 0, '', 'HRD', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(15, 15, 0, '', 'MACHINING CENTER', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(16, 16, 0, '', 'PRODUCTION', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(17, 17, 0, '', 'IT', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(18, 18, 0, '', 'SYSDEV BUSINESS PROCESS', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(19, 19, 0, '', 'SYSDEV INTERNAL PROCESS', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(20, 20, 0, '', 'WAREHOUSE', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(21, 21, 0, '', 'ENGINEERING', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(22, 22, 0, '', 'QC', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(23, 23, 0, '', 'R & D', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(24, 24, 0, '', 'PURCHASING', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(25, 25, 0, '', 'FINANCE', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(26, 26, 0, '', 'ACCOUNTING', '3', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(27, 27, 0, '', 'PROJECT', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', ''),
(28, 28, 0, '', 'MR - DCC', '', '', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '', 'A', '0000-00-00 00:00:00', '');

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
  `DeleteFlag` varchar(1) DEFAULT 'A'
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COMMENT='email untuk konfirmasi rootcause';

--
-- Dumping data for table `p01emailroot`
--

INSERT INTO `p01emailroot` (`ID`, `IDEmployee`, `RootSite`, `Note`, `AddedBy`, `AddedDate`, `AddedIP`, `EditedBy`, `EditedDate`, `EditedIP`, `DeleteBy`, `DeleteDate`, `DeleteIP`, `DeleteFlag`) VALUES
(5, '0506021112', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A');

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
  `DeleteFlag` varchar(1) DEFAULT 'A'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `r01rootcause`
--

DROP TABLE IF EXISTS `r01rootcause`;
CREATE TABLE IF NOT EXISTS `r01rootcause` (
`IDRoot` int(11) NOT NULL,
  `RootName` varchar(60) DEFAULT NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `r01rootcause`
--

INSERT INTO `r01rootcause` (`IDRoot`, `RootName`, `AddedBy`, `AddedDate`, `AddedIP`, `EditedBy`, `EditedDate`, `EditedIP`, `DeleteBy`, `DeleteDate`, `DeleteIP`, `DeleteFlag`) VALUES
(1, 'ATTENDANCE', '0506021112', '2014-12-18 09:40:09', '192.168.0.61', NULL, NULL, NULL, NULL, NULL, NULL, 'A'),
(2, 'FIELDPAYROLL', '0506021112', '2014-12-18 09:40:16', '192.168.0.61', NULL, NULL, NULL, NULL, NULL, NULL, 'A'),
(3, 'EMPLOYEE CENTER', '0506021112', '2014-12-18 09:40:22', '192.168.0.61', NULL, NULL, NULL, NULL, NULL, NULL, 'A'),
(6, 'JARINGAN', '0506021112', '2014-12-18 09:41:02', '192.168.0.61', NULL, NULL, NULL, NULL, NULL, NULL, 'A'),
(7, 'PRINTER', '0506021112', '2014-12-18 09:41:07', '192.168.0.61', NULL, NULL, NULL, NULL, NULL, NULL, 'A'),
(8, 'KOMPUTER', '0506021112', '2014-12-18 09:41:27', '192.168.0.61', NULL, NULL, NULL, NULL, NULL, NULL, 'A'),
(9, 'REQUEST', '0506021112', '2014-12-23 15:17:28', '192.168.0.61', NULL, NULL, NULL, NULL, NULL, NULL, 'A'),
(11, 'EMAIL', '0506021112', '2015-01-24 10:40:49', '192.168.0.61', NULL, NULL, NULL, NULL, NULL, NULL, 'A'),
(12, 'UPS', '0506021112', '2015-04-27 13:58:57', '192.168.0.61', NULL, NULL, NULL, NULL, NULL, NULL, 'A'),
(13, 'MS.OFFICE', '0249230309', '2015-04-29 15:21:08', '192.168.0.121', NULL, NULL, NULL, NULL, NULL, NULL, 'A'),
(14, 'SOFTWARE', '0249230309', '2015-04-29 15:22:04', '192.168.0.121', NULL, NULL, NULL, NULL, NULL, NULL, 'A'),
(15, 'OPERATING SYSTEM &amp;#40;OS&amp;#41;', '0249230309', '2015-04-29 15:22:38', '192.168.0.121', NULL, NULL, NULL, '0249230309', '2015-04-29 15:22:53', '192.168.0.121', 'D'),
(16, 'OPERATING SYSTEM', '0249230309', '2015-04-29 15:23:09', '192.168.0.121', NULL, NULL, NULL, NULL, NULL, NULL, 'A'),
(17, 'SCANNER', '0506021112', '2015-06-29 11:27:19', '192.168.0.61', NULL, NULL, NULL, NULL, NULL, NULL, 'A'),
(20, 'CCTV', '0506021112', '2015-09-10 13:10:23', '192.168.0.61', NULL, NULL, NULL, NULL, NULL, NULL, 'A');

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
-- Table structure for table `r03education`
--

DROP TABLE IF EXISTS `r03education`;
CREATE TABLE IF NOT EXISTS `r03education` (
`ID` int(11) NOT NULL,
  `EduName` varchar(10) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `r03education`
--

INSERT INTO `r03education` (`ID`, `EduName`) VALUES
(1, 'SD'),
(2, 'SMP'),
(3, 'SMA/SMK'),
(4, 'DI'),
(5, 'DII'),
(6, 'DIII'),
(7, 'DIV'),
(8, 'S1'),
(9, 'S2'),
(10, 'S3');

-- --------------------------------------------------------

--
-- Table structure for table `registered`
--

DROP TABLE IF EXISTS `registered`;
CREATE TABLE IF NOT EXISTS `registered` (
`ID` int(11) NOT NULL,
  `User` varchar(50) DEFAULT NULL,
  `Country` varchar(10) DEFAULT NULL,
  `ZipCode` varchar(10) DEFAULT NULL,
  `Address` text,
  `Email` varchar(60) DEFAULT NULL,
  `Phone` varchar(16) DEFAULT NULL,
  `Password` varchar(60) DEFAULT NULL,
  `Status` varchar(2) DEFAULT 'A'
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
  `SuspendedBy` varchar(20) DEFAULT NULL,
  `SuspendedDate` datetime DEFAULT NULL,
  `SuspendedIP` varchar(20) DEFAULT NULL,
  `UnsolvedBy` varchar(20) DEFAULT NULL,
  `UnsolvedDate` datetime DEFAULT NULL,
  `UnsolvedIP` varchar(20) DEFAULT NULL,
  `ProgressBy` varchar(20) DEFAULT NULL,
  `ProgressDate` datetime DEFAULT NULL,
  `ProgressIP` varchar(20) DEFAULT NULL,
  `SolvedBy` varchar(20) DEFAULT NULL,
  `SolvedDate` datetime DEFAULT NULL,
  `SolvedIP` varchar(20) DEFAULT NULL,
  `RejectedBy` varchar(20) DEFAULT NULL,
  `RejectedDate` datetime DEFAULT NULL,
  `RejectedIP` varchar(20) DEFAULT NULL,
  `RejectReason` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table for handling problem';

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
  `Location` varchar(1) NOT NULL COMMENT 'Lokasi Absensi'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t02request_accessfolder`
--

DROP TABLE IF EXISTS `t02request_accessfolder`;
CREATE TABLE IF NOT EXISTS `t02request_accessfolder` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(10) DEFAULT NULL,
  `CounterReq` varchar(11) DEFAULT NULL,
  `FolderAccess` varchar(60) DEFAULT NULL,
  `AccessStatus` varchar(1) DEFAULT NULL COMMENT '0(N/A), 1(R/Ol),2(R/W)',
  `FlagSend` varchar(1) NOT NULL DEFAULT '0' COMMENT '0 = Dont Send, 1= Send Data',
  `Note` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t02request_agreement`
--

DROP TABLE IF EXISTS `t02request_agreement`;
CREATE TABLE IF NOT EXISTS `t02request_agreement` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(11) DEFAULT NULL,
  `CounterReq` varchar(11) DEFAULT NULL,
  `StatusAgreement` varchar(1) DEFAULT NULL COMMENT '1(Yes),0(No)'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t02request_createfolder`
--

DROP TABLE IF EXISTS `t02request_createfolder`;
CREATE TABLE IF NOT EXISTS `t02request_createfolder` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(10) DEFAULT NULL,
  `CounterReq` varchar(11) DEFAULT NULL,
  `FolderName` varchar(60) DEFAULT NULL,
  `FolderStatus` varchar(1) DEFAULT NULL COMMENT '0(Delete), 1(Create)',
  `FlagSend` varchar(1) DEFAULT '0' COMMENT '0 = Dont Send, 1= Send Data',
  `Note` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t02request_createuser`
--

DROP TABLE IF EXISTS `t02request_createuser`;
CREATE TABLE IF NOT EXISTS `t02request_createuser` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(10) DEFAULT NULL,
  `CounterReq` varchar(11) DEFAULT NULL,
  `UserID` varchar(100) DEFAULT NULL,
  `StatusUser` varchar(1) DEFAULT NULL COMMENT '1(Create),0(Banned)',
  `InternalEmail` varchar(60) DEFAULT NULL,
  `ExternalEmail` varchar(60) DEFAULT NULL,
  `InternetStatus` varchar(1) DEFAULT NULL COMMENT '1(With Access Internet),0(No Access Internet)',
  `FlagSend` varchar(1) NOT NULL DEFAULT '0' COMMENT '0 = Dont Send, 1= Send Data'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table Form Create User';

-- --------------------------------------------------------

--
-- Table structure for table `t02request_installsoftware`
--

DROP TABLE IF EXISTS `t02request_installsoftware`;
CREATE TABLE IF NOT EXISTS `t02request_installsoftware` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(10) DEFAULT NULL,
  `CounterReq` varchar(11) DEFAULT NULL,
  `SoftwareName` varchar(60) DEFAULT NULL,
  `SoftwareStatus` varchar(1) DEFAULT NULL COMMENT '0(Uninstall), 1(Install)',
  `FlagSend` varchar(1) NOT NULL DEFAULT '0' COMMENT '0 = Dont Send, 1= Send Data',
  `Note` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t02request_user`
--

DROP TABLE IF EXISTS `t02request_user`;
CREATE TABLE IF NOT EXISTS `t02request_user` (
`ID` int(11) NOT NULL,
  `IDEmployee` varchar(10) DEFAULT NULL,
  `ComputerName` varchar(25) DEFAULT NULL,
  `NoCounter` varchar(11) DEFAULT NULL,
  `CurDate` date DEFAULT NULL,
  `StatusDoc` varchar(1) DEFAULT NULL COMMENT '0(Ignore),1(''Accept'')',
  `FlagSend` varchar(1) NOT NULL DEFAULT '0' COMMENT '0 = Dont Send, 1= Send Data'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table Form Request';

-- --------------------------------------------------------

--
-- Table structure for table `t03customerinvitation_d`
--

DROP TABLE IF EXISTS `t03customerinvitation_d`;
CREATE TABLE IF NOT EXISTS `t03customerinvitation_d` (
`ID` int(11) NOT NULL,
  `IDH` int(11) NOT NULL,
  `Gender` int(1) NOT NULL DEFAULT '1' COMMENT '1 = Mr,2 = Mrs,3=Mis,4=-',
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
  `DeleteFlag` varchar(1) DEFAULT 'A'
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
  `DeleteFlag` varchar(1) DEFAULT 'A'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tmp01contact`
--

DROP TABLE IF EXISTS `tmp01contact`;
CREATE TABLE IF NOT EXISTS `tmp01contact` (
  `IDEmployee` varchar(50) DEFAULT NULL,
  `InternalEmail` text,
  `ExternalEmail` text,
  `NoHP` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tmpattachment`
--

DROP TABLE IF EXISTS `tmpattachment`;
CREATE TABLE IF NOT EXISTS `tmpattachment` (
`ID` int(11) NOT NULL,
  `IDCron` varchar(11) DEFAULT NULL,
  `FileImages` text,
  `UrlImages` text,
  `UrlPath` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
 ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `cron01invitation`
--
ALTER TABLE `cron01invitation`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `emailnotvalid`
--
ALTER TABLE `emailnotvalid`
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
-- Indexes for table `r01rootcause`
--
ALTER TABLE `r01rootcause`
 ADD PRIMARY KEY (`IDRoot`);

--
-- Indexes for table `r02currency`
--
ALTER TABLE `r02currency`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `r03education`
--
ALTER TABLE `r03education`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `registered`
--
ALTER TABLE `registered`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t01rootcause`
--
ALTER TABLE `t01rootcause`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t02rawdata`
--
ALTER TABLE `t02rawdata`
 ADD PRIMARY KEY (`ID`), ADD KEY `SECONDARY` (`DataText`);

--
-- Indexes for table `t02request_accessfolder`
--
ALTER TABLE `t02request_accessfolder`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t02request_agreement`
--
ALTER TABLE `t02request_agreement`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t02request_createfolder`
--
ALTER TABLE `t02request_createfolder`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t02request_createuser`
--
ALTER TABLE `t02request_createuser`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t02request_installsoftware`
--
ALTER TABLE `t02request_installsoftware`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `t02request_user`
--
ALTER TABLE `t02request_user`
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
-- Indexes for table `tmp01contact`
--
ALTER TABLE `tmp01contact`
 ADD KEY `IDEmployee` (`IDEmployee`);

--
-- Indexes for table `tmpattachment`
--
ALTER TABLE `tmpattachment`
 ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
MODIFY `country_id` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=251;
--
-- AUTO_INCREMENT for table `cron01invitation`
--
ALTER TABLE `cron01invitation`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `emailnotvalid`
--
ALTER TABLE `emailnotvalid`
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
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=701;
--
-- AUTO_INCREMENT for table `m01personal_course`
--
ALTER TABLE `m01personal_course`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `m01personal_education`
--
ALTER TABLE `m01personal_education`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=399;
--
-- AUTO_INCREMENT for table `m01personal_family`
--
ALTER TABLE `m01personal_family`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=685;
--
-- AUTO_INCREMENT for table `m01personal_job`
--
ALTER TABLE `m01personal_job`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=704;
--
-- AUTO_INCREMENT for table `m01personal_language`
--
ALTER TABLE `m01personal_language`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `m01personal_workexp`
--
ALTER TABLE `m01personal_workexp`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `m03organization`
--
ALTER TABLE `m03organization`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `p01emailroot`
--
ALTER TABLE `p01emailroot`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `p02mailinvitation`
--
ALTER TABLE `p02mailinvitation`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `r01rootcause`
--
ALTER TABLE `r01rootcause`
MODIFY `IDRoot` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `r02currency`
--
ALTER TABLE `r02currency`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `r03education`
--
ALTER TABLE `r03education`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `registered`
--
ALTER TABLE `registered`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t01rootcause`
--
ALTER TABLE `t01rootcause`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t02rawdata`
--
ALTER TABLE `t02rawdata`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t02request_accessfolder`
--
ALTER TABLE `t02request_accessfolder`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t02request_agreement`
--
ALTER TABLE `t02request_agreement`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t02request_createfolder`
--
ALTER TABLE `t02request_createfolder`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t02request_createuser`
--
ALTER TABLE `t02request_createuser`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t02request_installsoftware`
--
ALTER TABLE `t02request_installsoftware`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `t02request_user`
--
ALTER TABLE `t02request_user`
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
--
-- AUTO_INCREMENT for table `tmpattachment`
--
ALTER TABLE `tmpattachment`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
