-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 08, 2010 at 05:20 AM
-- Server version: 5.1.37
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `agitation`
--

-- --------------------------------------------------------

--
-- Table structure for table `resident_mapping`
--

CREATE TABLE IF NOT EXISTS `resident_mapping` (
  `mapkey` int(11) NOT NULL AUTO_INCREMENT,
  `residentkey` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `duration` int(11) NOT NULL,
  `trigger` varchar(100) NOT NULL,
  `intervention` varchar(100) NOT NULL,
  `behavior` varchar(100) NOT NULL,
  `intensity` int(11) NOT NULL,
  `PRN` varchar(23) NOT NULL,
  PRIMARY KEY (`mapkey`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=63 ;

--
-- Dumping data for table `resident_mapping`
--

INSERT INTO `resident_mapping` (`mapkey`, `residentkey`, `date`, `time`, `duration`, `trigger`, `intervention`, `behavior`, `intensity`, `PRN`) VALUES
(1, 1, '2010-02-28', '01:00:00', 30, 'k', 'k', 'motor', 1, 'yes'),
(2, 1, '2010-02-28', '01:00:00', 30, 'k', 'k', 'motor', 1, 'yes'),
(3, 1, '0000-00-00', '01:00:00', 0, '', '', '', 0, ''),
(4, 1, '0000-00-00', '01:00:00', 0, '', '', '', 0, ''),
(5, 1, '0000-00-00', '01:00:00', 0, '', '', '', 0, ''),
(6, 1, '0000-00-00', '01:00:00', 0, '', '', '', 0, ''),
(7, 1, '2010-03-20', '01:00:00', 15, 'breakfast', 'talking', 'resistance', 3, 'no'),
(8, 1, '2010-03-20', '01:00:00', 50, 'loud noise', 'quiet', 'resistance', 2, 'yes'),
(9, 1, '2010-03-20', '01:00:00', 15, 'new people', 'isolation', 'vocalizations', 4, 'yes'),
(10, 1, '2010-03-20', '01:00:00', 50, 'medication', 'distraction', 'resistance', 4, 'no'),
(11, 1, '0000-00-00', '01:00:00', 0, '', '', '', 0, ''),
(12, 0, '0000-00-00', '01:00:00', 0, '', '', '', 0, ''),
(13, 1, '2010-04-03', '15:10:00', 30, 'loud noise', 'quiet', 'motor', 2, 'no'),
(14, 1, '0000-00-00', '00:00:00', 0, '', '', '', 0, ''),
(15, 1, '2010-04-03', '00:00:00', 45, 'sunlight', 'darkness', 'motor', 3, 'no'),
(16, 1, '2010-04-04', '06:25:00', 45, 'eating', 'separate', 'motor', 2, 'no'),
(17, 1, '0000-00-00', '02:10:00', 15, 'family visit', 'walk', 'motor', 2, 'no'),
(18, 1, '2010-06-22', '17:35:00', 75, 'wakeup', 'alarm clock', 'agressiveness', 4, 'no'),
(19, 1, '2010-06-22', '04:15:00', 45, 'TV off', 'Allow to watch TV', 'agressiveness', 4, 'no'),
(20, 1, '2010-06-22', '18:25:00', 45, 'Could not remember relative', 'View family album', 'agressiveness', 3, ''),
(55, 2, '0000-00-00', '02:05:00', 34, 'sdf', 'sdf', '', 0, ''),
(54, 2, '0000-00-00', '01:10:00', 56, 'sdf', 'sdf', 'motor', 0, ''),
(53, 2, '0000-00-00', '01:10:00', 56, 'sdf', 'sdf', 'motor', 0, ''),
(52, 2, '0000-00-00', '00:00:00', 0, '', '', '', 0, ''),
(51, 2, '0000-00-00', '00:00:00', 0, '', '', '', 0, ''),
(50, 2, '0000-00-00', '00:00:00', 0, '', '', '', 0, ''),
(62, 2, '0000-00-00', '15:10:00', 45, 'dfg', 'df', 'motor', 0, ''),
(61, 2, '0000-00-00', '14:05:00', 45, 'sdf', 'sdf', '', 0, ''),
(60, 2, '0000-00-00', '02:05:00', 45, 'sdf', 'sdf', '', 0, ''),
(59, 2, '0000-00-00', '00:00:00', 0, '', '', '', 0, ''),
(58, 2, '0000-00-00', '00:00:00', 0, '', '', '', 0, ''),
(57, 2, '0000-00-00', '00:00:00', 0, '', '', '', 0, ''),
(56, 2, '0000-00-00', '00:00:00', 0, '', '', '', 0, '');
