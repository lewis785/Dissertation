-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 19, 2017 at 07:00 PM
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
-- Table structure for table `courses`
--

CREATE TABLE IF NOT EXISTS `courses` (
  `courseID` int(11) NOT NULL AUTO_INCREMENT,
  `courseName` text COLLATE utf8_unicode_ci NOT NULL,
  `courseCode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`courseID`),
  UNIQUE KEY `courseCode` (`courseCode`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `course_lecturer`
--

CREATE TABLE IF NOT EXISTS `course_lecturer` (
  `lecturerID` int(11) NOT NULL AUTO_INCREMENT,
  `course` int(11) NOT NULL,
  `lecturer` int(11) NOT NULL,
  PRIMARY KEY (`lecturerID`),
  KEY `courseRef` (`course`,`lecturer`),
  KEY `Lecturer Exists` (`lecturer`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `labs`
--

CREATE TABLE IF NOT EXISTS `labs` (
  `labID` int(11) NOT NULL AUTO_INCREMENT,
  `courseRef` int(10) NOT NULL,
  `labName` text COLLATE utf8_unicode_ci NOT NULL,
  `maxMark` int(11) NOT NULL,
  PRIMARY KEY (`labID`),
  KEY `courseCodeRef` (`courseRef`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `lab_answers`
--

CREATE TABLE IF NOT EXISTS `lab_answers` (
  `answerID` int(11) NOT NULL AUTO_INCREMENT,
  `labQuestionRef` int(11) NOT NULL,
  `labRef` int(11) NOT NULL,
  `socRef` int(11) NOT NULL,
  `answerNumber` int(11) NOT NULL,
  `answerBoolean` tinyint(1) DEFAULT NULL,
  `answerText` text COLLATE utf8_unicode_ci,
  `mark` int(11) NOT NULL,
  PRIMARY KEY (`answerID`),
  KEY `markingSheetID` (`labQuestionRef`),
  KEY `labID` (`labRef`),
  KEY `socID` (`socRef`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lab_helpers`
--

CREATE TABLE IF NOT EXISTS `lab_helpers` (
  `labHelperID` int(11) NOT NULL AUTO_INCREMENT,
  `userRef` int(11) NOT NULL,
  `course` int(11) NOT NULL,
  PRIMARY KEY (`labHelperID`),
  KEY `userRef` (`userRef`),
  KEY `course` (`course`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `question_types`
--

CREATE TABLE IF NOT EXISTS `question_types` (
  `questionTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `typeName` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`questionTypeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `students_on_courses`
--

CREATE TABLE IF NOT EXISTS `students_on_courses` (
  `socID` int(11) NOT NULL AUTO_INCREMENT,
  `student` int(9) NOT NULL,
  `course` int(10) NOT NULL,
  PRIMARY KEY (`socID`),
  KEY `studentID` (`student`),
  KEY `courseID` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_access`
--

CREATE TABLE IF NOT EXISTS `user_access` (
  `access_id` int(11) NOT NULL AUTO_INCREMENT,
  `access_name` text CHARACTER SET latin1 NOT NULL,
  `access_level` int(11) NOT NULL,
  PRIMARY KEY (`access_id`),
  KEY `access_id` (`access_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE IF NOT EXISTS `user_details` (
  `detailsId` int(11) NOT NULL,
  `studentID` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `firstname` text CHARACTER SET latin1 NOT NULL,
  `surname` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`detailsId`),
  UNIQUE KEY `studentID` (`studentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_login`
--

CREATE TABLE IF NOT EXISTS `user_login` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `username` text CHARACTER SET latin1 NOT NULL,
  `password` text CHARACTER SET latin1 NOT NULL,
  `accessLevel` int(11) NOT NULL,
  PRIMARY KEY (`userID`),
  KEY `accessLevel` (`accessLevel`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `course_lecturer`
--
ALTER TABLE `course_lecturer`
  ADD CONSTRAINT `Course Exists` FOREIGN KEY (`course`) REFERENCES `courses` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Lecturer Exists` FOREIGN KEY (`lecturer`) REFERENCES `user_login` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `labs`
--
ALTER TABLE `labs`
  ADD CONSTRAINT `Existing Course` FOREIGN KEY (`courseRef`) REFERENCES `courses` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lab_answers`
--
ALTER TABLE `lab_answers`
  ADD CONSTRAINT `Lab answer is from` FOREIGN KEY (`labRef`) REFERENCES `labs` (`labID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Question Sheet` FOREIGN KEY (`labQuestionRef`) REFERENCES `lab_questions` (`questionID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Who answer belongs too` FOREIGN KEY (`socRef`) REFERENCES `students_on_courses` (`socID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lab_helpers`
--
ALTER TABLE `lab_helpers`
  ADD CONSTRAINT `User Exists` FOREIGN KEY (`userRef`) REFERENCES `user_login` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Valid Course` FOREIGN KEY (`course`) REFERENCES `courses` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lab_questions`
--
ALTER TABLE `lab_questions`
  ADD CONSTRAINT `Valid Question Types` FOREIGN KEY (`questionType`) REFERENCES `question_types` (`questionTypeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `labExists` FOREIGN KEY (`labRef`) REFERENCES `labs` (`labID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `students_on_courses`
--
ALTER TABLE `students_on_courses`
  ADD CONSTRAINT `courseExists` FOREIGN KEY (`course`) REFERENCES `courses` (`courseID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `studentExists` FOREIGN KEY (`student`) REFERENCES `user_login` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_details`
--
ALTER TABLE `user_details`
  ADD CONSTRAINT `user_details_ibfk_1` FOREIGN KEY (`detailsId`) REFERENCES `user_login` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_login`
--
ALTER TABLE `user_login`
  ADD CONSTRAINT `Valid Access IDs` FOREIGN KEY (`accessLevel`) REFERENCES `user_access` (`access_id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
