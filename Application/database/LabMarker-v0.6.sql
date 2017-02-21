-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2017 at 05:41 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lab-marker`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `courseID` int(11) NOT NULL,
  `courseName` text COLLATE utf8_unicode_ci NOT NULL,
  `courseCode` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE `course_lecturer` (
  `lecturerID` int(11) NOT NULL,
  `course` int(11) NOT NULL,
  `lecturer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE `labs` (
  `labID` int(11) NOT NULL,
  `courseRef` int(10) NOT NULL,
  `labName` text COLLATE utf8_unicode_ci NOT NULL,
  `maxMark` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `labs`
--

INSERT INTO `labs` (`labID`, `courseRef`, `labName`, `maxMark`) VALUES
(13, 2, 'Lab 1', 9),
(14, 1, 'df', 6),
(15, 1, 'Lab 2', 10),
(16, 2, 'Test', 6),
(17, 1, 'abc', 14);

-- --------------------------------------------------------

--
-- Table structure for table `lab_answers`
--

CREATE TABLE `lab_answers` (
  `answerID` int(11) NOT NULL,
  `labQuestionRef` int(11) NOT NULL,
  `labRef` int(11) NOT NULL,
  `socRef` int(11) NOT NULL,
  `answerNumber` int(11) NOT NULL,
  `answerBoolean` tinyint(1) DEFAULT NULL,
  `answerText` text COLLATE utf8_unicode_ci,
  `mark` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_helpers`
--

CREATE TABLE `lab_helpers` (
  `labHelperID` int(11) NOT NULL,
  `userRef` int(11) NOT NULL,
  `course` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE `lab_questions` (
  `questionID` int(11) NOT NULL,
  `labRef` int(11) NOT NULL,
  `questionType` int(11) NOT NULL,
  `questionNumber` int(11) NOT NULL,
  `question` text COLLATE utf8_unicode_ci NOT NULL,
  `minMark` int(11) DEFAULT NULL,
  `maxMark` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `question_types`
--

CREATE TABLE `question_types` (
  `questionTypeID` int(11) NOT NULL,
  `typeName` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE `students_on_courses` (
  `socID` int(11) NOT NULL,
  `student` int(9) NOT NULL,
  `course` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `students_on_courses`
--

INSERT INTO `students_on_courses` (`socID`, `student`, `course`) VALUES
(1, 4, 1),
(2, 4, 2),
(3, 5, 1),
(4, 11, 2),
(5, 17, 1),
(6, 16, 2),
(7, 14, 1),
(8, 15, 2),
(9, 18, 1),
(10, 18, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user_access`
--

CREATE TABLE `user_access` (
  `access_id` int(11) NOT NULL,
  `access_name` text CHARACTER SET latin1 NOT NULL,
  `access_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE `user_details` (
  `detailsId` int(11) NOT NULL,
  `studentID` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `firstname` text CHARACTER SET latin1 NOT NULL,
  `surname` text CHARACTER SET latin1 NOT NULL
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
(13, '', 'Alisdair', 'Gray'),
(14, 'H00152596', 'Sam', 'Smith'),
(15, 'H00152595', 'Duncan', 'Cameron'),
(16, 'H00152594', 'Fraser', 'Brown'),
(17, 'H00152593', 'Alex', 'Trump'),
(18, 'H00152580', 'Andrew', 'Smith');

-- --------------------------------------------------------

--
-- Table structure for table `user_login`
--

CREATE TABLE `user_login` (
  `userID` int(11) NOT NULL,
  `username` text CHARACTER SET latin1 NOT NULL,
  `password` text CHARACTER SET latin1 NOT NULL,
  `accessLevel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_login`
--

INSERT INTO `user_login` (`userID`, `username`, `password`, `accessLevel`) VALUES
(1, 'lewis785', 'test123', 3),
(2, 'Jack', 'Foster', 5),
(3, 'rob', 'test123', 2),
(4, 'liam', 'test123', 1),
(5, 'kath', 'test123', 1),
(11, 'tt526', 'test123', 1),
(13, 'ag974', 'test123', 2),
(14, 'ss419', 'test123', 1),
(15, 'dc016', 'test123', 1),
(16, 'fb829', 'test123', 1),
(17, 'at125', 'test123', 1),
(18, 'as878', 'test123', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`courseID`),
  ADD UNIQUE KEY `courseCode` (`courseCode`);

--
-- Indexes for table `course_lecturer`
--
ALTER TABLE `course_lecturer`
  ADD PRIMARY KEY (`lecturerID`),
  ADD KEY `courseRef` (`course`,`lecturer`),
  ADD KEY `Lecturer Exists` (`lecturer`);

--
-- Indexes for table `labs`
--
ALTER TABLE `labs`
  ADD PRIMARY KEY (`labID`),
  ADD KEY `courseCodeRef` (`courseRef`);

--
-- Indexes for table `lab_answers`
--
ALTER TABLE `lab_answers`
  ADD PRIMARY KEY (`answerID`),
  ADD KEY `markingSheetID` (`labQuestionRef`),
  ADD KEY `labID` (`labRef`),
  ADD KEY `socID` (`socRef`);

--
-- Indexes for table `lab_helpers`
--
ALTER TABLE `lab_helpers`
  ADD PRIMARY KEY (`labHelperID`),
  ADD KEY `userRef` (`userRef`),
  ADD KEY `course` (`course`);

--
-- Indexes for table `lab_questions`
--
ALTER TABLE `lab_questions`
  ADD PRIMARY KEY (`questionID`),
  ADD KEY `questionType` (`questionType`),
  ADD KEY `labSheetID` (`labRef`);

--
-- Indexes for table `question_types`
--
ALTER TABLE `question_types`
  ADD PRIMARY KEY (`questionTypeID`);

--
-- Indexes for table `students_on_courses`
--
ALTER TABLE `students_on_courses`
  ADD PRIMARY KEY (`socID`),
  ADD KEY `studentID` (`student`),
  ADD KEY `courseID` (`course`);

--
-- Indexes for table `user_access`
--
ALTER TABLE `user_access`
  ADD PRIMARY KEY (`access_id`),
  ADD KEY `access_id` (`access_id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`detailsId`),
  ADD UNIQUE KEY `studentID` (`studentID`);

--
-- Indexes for table `user_login`
--
ALTER TABLE `user_login`
  ADD PRIMARY KEY (`userID`),
  ADD KEY `accessLevel` (`accessLevel`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `courseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `course_lecturer`
--
ALTER TABLE `course_lecturer`
  MODIFY `lecturerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `labs`
--
ALTER TABLE `labs`
  MODIFY `labID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `lab_answers`
--
ALTER TABLE `lab_answers`
  MODIFY `answerID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lab_helpers`
--
ALTER TABLE `lab_helpers`
  MODIFY `labHelperID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `lab_questions`
--
ALTER TABLE `lab_questions`
  MODIFY `questionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `question_types`
--
ALTER TABLE `question_types`
  MODIFY `questionTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `students_on_courses`
--
ALTER TABLE `students_on_courses`
  MODIFY `socID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `user_access`
--
ALTER TABLE `user_access`
  MODIFY `access_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `user_login`
--
ALTER TABLE `user_login`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
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
