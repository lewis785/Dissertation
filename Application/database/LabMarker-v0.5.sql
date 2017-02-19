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

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`courseID`, `courseName`, `courseCode`) VALUES
(1, 'Software Development 1', 'F21SD1'),
(2, 'Data Management', 'F20DM1');

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

--
-- Dumping data for table `course_lecturer`
--

INSERT INTO `course_lecturer` (`lecturerID`, `course`, `lecturer`) VALUES
(1, 1, 1),
(4, 1, 11),
(3, 2, 1),
(2, 2, 3);

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

--
-- Dumping data for table `labs`
--

INSERT INTO `labs` (`labID`, `courseRef`, `labName`, `maxMark`) VALUES
(13, 2, 'Lab 1', 9),
(14, 1, 'df', 6),
(15, 1, 'Lab 2', 10);

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

--
-- Dumping data for table `lab_helpers`
--

INSERT INTO `lab_helpers` (`labHelperID`, `userRef`, `course`) VALUES
(1, 2, 1),
(2, 2, 2),
(3, 4, 1),
(4, 5, 1);

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
(12, 15, 1, 2, 'Completed the lab', NULL, 5);

-- --------------------------------------------------------

--
-- Table structure for table `question_types`
--

CREATE TABLE IF NOT EXISTS `question_types` (
  `questionTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `typeName` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`questionTypeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `question_types`
--

INSERT INTO `question_types` (`questionTypeID`, `typeName`) VALUES
(1, 'boolean'),
(2, 'scale'),
(3, 'value'),
(4, 'text');

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

--
-- Dumping data for table `user_access`
--

INSERT INTO `user_access` (`access_id`, `access_name`, `access_level`) VALUES
(1, 'student', 10),
(2, 'lecturer', 20),
(3, 'admin', 30),
(4, 'super admin', 100),
(5, 'lab helper', 15);

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

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`detailsId`, `studentID`, `firstname`, `surname`) VALUES
(1, NULL, 'Lewis', 'McNeill'),
(2, NULL, 'Jack', 'Foster'),
(3, NULL, 'Robert', 'McNeill'),
(4, 'H00100234', 'Liam', 'Bruccy'),
(5, 'H00100098', 'Katherine', 'Parry'),
(11, 'H00152598', 'Test', 'Tester'),
(13, '', 'Alisdair', 'Gray');

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
-- Dumping data for table `user_login`
--

INSERT INTO `user_login` (`userID`, `username`, `password`, `accessLevel`) VALUES
(1, 'lewis785', 'test123', 3),
(2, 'Jack', 'Foster', 1),
(3, 'rob', 'test123', 2),
(4, 'liam', 'test123', 1),
(5, 'kath', 'test123', 1),
(11, 'tt526', 'test123', 1),
(13, 'ag974', 'test123', 2);

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
