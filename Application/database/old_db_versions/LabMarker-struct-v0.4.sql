-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 17, 2017 at 06:27 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `course_lecturer`
--

CREATE TABLE `course_lecturer` (
  `lecturerID` int(11) NOT NULL,
  `course` int(11) NOT NULL,
  `lecturer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `labs`
--

CREATE TABLE `labs` (
  `labID` int(11) NOT NULL,
  `courseCodeRef` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `labNumber` int(11) NOT NULL,
  `labName` text COLLATE utf8_unicode_ci NOT NULL,
  `maxMark` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `maxMark` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question_types`
--

CREATE TABLE `question_types` (
  `questionTypeID` int(11) NOT NULL,
  `typeName` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students_on_courses`
--

CREATE TABLE `students_on_courses` (
  `socID` int(11) NOT NULL,
  `student` int(9) NOT NULL,
  `course` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_access`
--

CREATE TABLE `user_access` (
  `access_id` int(11) NOT NULL,
  `access_name` text CHARACTER SET latin1 NOT NULL,
  `access_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  ADD KEY `courseCodeRef` (`courseCodeRef`);

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
  MODIFY `labID` int(11) NOT NULL AUTO_INCREMENT;
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
  MODIFY `questionID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `students_on_courses`
--
ALTER TABLE `students_on_courses`
  MODIFY `socID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_access`
--
ALTER TABLE `user_access`
  MODIFY `access_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user_login`
--
ALTER TABLE `user_login`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
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
  ADD CONSTRAINT `Course IDs` FOREIGN KEY (`courseCodeRef`) REFERENCES `courses` (`courseCode`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `Question Type Valid` FOREIGN KEY (`questionType`) REFERENCES `question_types` (`questionTypeID`) ON DELETE CASCADE ON UPDATE CASCADE,
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
