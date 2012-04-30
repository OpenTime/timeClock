-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 10, 2011 at 12:26 PM
-- Server version: 5.1.50
-- PHP Version: 5.2.14

-- @author      MarQuis L. Knox <opensource@marquisknox.com>
-- @license     GPL v2
-- @link        http://www.gnu.org/licenses/gpl-2.0.html
-- @since       Thursday, March 10, 2011 / 12:37 PM GMT+1 mknox
-- @edited      $Date: 2011-03-10 12:38:09 +0100 (Thu, 10 Mar 2011) $
-- @version     $Revision: 1 $
-- @package     Time Clock
-- @subpackage	Database

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `time_clock`
--

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `Id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`Id`, `key`, `value`, `comment`) VALUES
(1, 'requiredHoursPerDay', '9', 'work hours required per day, including lunch break'),
(2, 'requiredHoursPerWeek', '40', 'work hours required per week, including lunch breaks'),
(3, 'timeZone', 'Europe/Berlin', 'time zone for all dates'),
(4, 'dateFormat', 'H:i', NULL),
(5, 'requiredDaysPerWeek', '5', 'days of work required per week'),
(6, 'lunchBreakDuration', '3600', 'duration of lunch break in seconds'),
(7, 'requiredWorkDays', 'Monday,Tuesday,Wednesday,Thursday,Friday', 'days that work is required'),
(8, 'displayModal', '0', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE IF NOT EXISTS `records` (
  `Id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `month` enum('01','02','03','04','05','06','07','08','09','10','11','12') COLLATE utf8_unicode_ci NOT NULL,
  `day` enum('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') COLLATE utf8_unicode_ci NOT NULL,
  `year` char(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT '2012',
  `week` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49','50','51','52') COLLATE utf8_unicode_ci NOT NULL,
  `inTimestamp` int(11) DEFAULT NULL,
  `outTimestamp` int(11) DEFAULT NULL,
  `createDate` timestamp NULL DEFAULT NULL,
  `lastEdit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `uc_day` (`month`,`day`,`year`,`inTimestamp`),
  KEY `year_and_day` (`year`,`day`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `records_chronological`
--
CREATE TABLE IF NOT EXISTS `records_chronological` (
`Id` bigint(20) unsigned
,`month` enum('01','02','03','04','05','06','07','08','09','10','11','12')
,`day` enum('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31')
,`year` char(4)
,`week` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49','50','51','52')
,`inTimestamp` int(11)
,`outTimestamp` int(11)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `records_recent_first`
--
CREATE TABLE IF NOT EXISTS `records_recent_first` (
`Id` bigint(20) unsigned
,`month` enum('01','02','03','04','05','06','07','08','09','10','11','12')
,`day` enum('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31')
,`year` char(4)
,`week` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49','50','51','52')
,`inTimestamp` int(11)
,`outTimestamp` int(11)
);
-- --------------------------------------------------------

--
-- Structure for view `records_chronological`
--
DROP TABLE IF EXISTS `records_chronological`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `records_chronological` AS select `records`.`Id` AS `Id`,`records`.`month` AS `month`,`records`.`day` AS `day`,`records`.`year` AS `year`,`records`.`week` AS `week`,`records`.`inTimestamp` AS `inTimestamp`,`records`.`outTimestamp` AS `outTimestamp` from `records` order by `records`.`month`,`records`.`day`,`records`.`year`;

-- --------------------------------------------------------

--
-- Structure for view `records_recent_first`
--
DROP TABLE IF EXISTS `records_recent_first`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `records_recent_first` AS select `records`.`Id` AS `Id`,`records`.`month` AS `month`,`records`.`day` AS `day`,`records`.`year` AS `year`,`records`.`week` AS `week`,`records`.`inTimestamp` AS `inTimestamp`,`records`.`outTimestamp` AS `outTimestamp` from `records` order by `records`.`month` desc,`records`.`day` desc,`records`.`year` desc;
