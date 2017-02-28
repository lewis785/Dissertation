-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2017 at 07:05 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `lab-marker`
--

-- --------------------------------------------------------

--
-- Table structure for table `lab_questions`
--

CREATE TABLE IF NOT EXISTS `lab_questions` (
  `questionID` int(11) NOT NULL AUTO_INCREMENT,
  `labRef` int(11) NOT NULL,
  `questionType` int(11) NOT NULL,
  `questionNumber` int(11) NOT NULL,
  `question` text COLLATE utf8_unicode_ci NOT NULL,
  `minMark` int(11) DEFAULT NULL,
  `maxMark` int(11) DEFAULT NULL,
  PRIMARY KEY (`questionID`),
  KEY `questionType` (`questionType`),
  KEY `labSheetID` (`labRef`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

--
-- Dumping data for table `lab_questions`
--

INSERT INTO `lab_questions` (`questionID`, `labRef`, `questionType`, `questionNumber`, `question`, `minMark`, `maxMark`) VALUES
(5, 13, 2, 1, 'Efficence', 0, 4),
(6, 13, 1, 2, 'Completed Lab 1', NULL, 1),
(7, 13, 1, 3, 'Completed Lab 2', NULL, 1),
(8, 13, 2, 4, 'Quality', 0, 5),
(9, 14, 2, 1, 'wd', 1, 4),
(10, 14, 1, 2, 'sdfs', NULL, 2),
(11, 15, 2, 1, 'Knowledge of the program', 0, 5),
(12, 15, 1, 2, 'Completed the lab', NULL, 5),
(13, 16, 2, 1, 'sfsf', 1, 4),
(14, 16, 1, 2, 'saddad', NULL, 2),
(15, 17, 2, 1, 'hhl;lhlj', 0, 4),
(16, 17, 1, 2, 'completed lab1', NULL, 2),
(17, 17, 1, 3, 'kjkhhii', NULL, 8);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lab_questions`
--
ALTER TABLE `lab_questions`
  ADD CONSTRAINT `labExists` FOREIGN KEY (`labRef`) REFERENCES `labs` (`labID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Valid Question Types` FOREIGN KEY (`questionType`) REFERENCES `question_types` (`questionTypeID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
