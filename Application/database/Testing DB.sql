-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2017 at 02:25 AM
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
(2, 'Data Management', 'F20DM1'),
(3, 'Games Programming', 'F20GP'),
(4, 'Software Development 2', 'SD2F29');

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
(1, 1, 30),
(2, 2, 30),
(3, 3, 30);

-- --------------------------------------------------------

--
-- Table structure for table `labs`
--

CREATE TABLE `labs` (
  `labID` int(11) NOT NULL,
  `courseRef` int(10) NOT NULL,
  `labName` text COLLATE utf8_unicode_ci NOT NULL,
  `canMark` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `labs`
--

INSERT INTO `labs` (`labID`, `courseRef`, `labName`, `canMark`) VALUES
(1, 1, 'Lab 2', 'true'),
(4, 3, 'Lab 1', 'false'),
(5, 1, 'Lab 3', 'false');

-- --------------------------------------------------------

--
-- Table structure for table `lab_answers`
--

CREATE TABLE `lab_answers` (
  `answerID` int(11) NOT NULL,
  `labQuestionRef` int(11) NOT NULL,
  `socRef` int(11) NOT NULL,
  `answerNumber` int(11) DEFAULT NULL,
  `answerBoolean` text COLLATE utf8_unicode_ci,
  `answerText` text COLLATE utf8_unicode_ci,
  `mark` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lab_answers`
--

INSERT INTO `lab_answers` (`answerID`, `labQuestionRef`, `socRef`, `answerNumber`, `answerBoolean`, `answerText`, `mark`) VALUES
(1, 1, 4, 3, NULL, NULL, 3),
(2, 2, 4, NULL, 'true', NULL, 2),
(3, 3, 4, 0, NULL, 'Could do with additional commenting', 0),
(4, 1, 1, 0, NULL, NULL, 0),
(5, 2, 1, NULL, 'true', NULL, 2),
(6, 3, 1, 0, NULL, 'N/A', 0),
(7, 1, 2, 5, NULL, NULL, 5),
(8, 2, 2, NULL, 'true', NULL, 2),
(9, 3, 2, 0, NULL, '', 0),
(11, 1, 3, 2, NULL, NULL, 2),
(12, 2, 3, NULL, 'false', NULL, 0),
(13, 3, 3, 0, NULL, '', 0);

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
(1, 29, 1),
(2, 29, 2),
(3, 29, 3),
(4, 29, 4);

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
  `maxMark` int(11) DEFAULT NULL,
  `private` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lab_questions`
--

INSERT INTO `lab_questions` (`questionID`, `labRef`, `questionType`, `questionNumber`, `question`, `minMark`, `maxMark`, `private`) VALUES
(1, 1, 2, 1, 'Quality of code', 0, 5, 'false'),
(2, 1, 1, 2, 'Completed the lab', NULL, 2, 'false'),
(3, 1, 4, 3, 'Additional Comments', 0, 0, 'false'),
(7, 4, 2, 1, 'Commenting', 0, 6, 'false'),
(8, 4, 1, 2, 'Completed Lab 1', NULL, 4, 'false'),
(9, 5, 2, 1, 'Closeness to specification', 0, 5, 'false'),
(10, 5, 1, 2, 'Completed the task', NULL, 2, 'false');

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
(1, 27, 1),
(2, 16, 1),
(3, 4, 1),
(4, 28, 1),
(5, 2, 1),
(6, 18, 1),
(7, 32, 1),
(8, 33, 1),
(9, 17, 1),
(10, 29, 1),
(11, 27, 2),
(12, 2, 2),
(13, 29, 2),
(14, 19, 2),
(15, 26, 2),
(16, 15, 2),
(17, 16, 2),
(18, 4, 2),
(19, 32, 2),
(20, 33, 2),
(21, 27, 3),
(22, 18, 3),
(23, 32, 3),
(24, 14, 3),
(25, 5, 3),
(26, 28, 3),
(27, 17, 3),
(28, 33, 3),
(29, 29, 3),
(30, 33, 4),
(31, 18, 4),
(32, 32, 4),
(33, 14, 4),
(34, 19, 4),
(35, 2, 4);

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
(2, 'H12345678', 'Jack', 'Foster'),
(3, NULL, 'Robert', 'McNeill'),
(4, 'H00100234', 'Liam', 'Bruccy'),
(5, 'H00100098', 'Katherine', 'Parry'),
(11, NULL, 'Test', 'Tester'),
(14, 'H00152596', 'Sam', 'Smith'),
(15, 'H00152595', 'Duncan', 'Cameron'),
(16, 'H00152594', 'Fraser', 'Brown'),
(17, 'H00152593', 'Alex', 'Trump'),
(18, 'H00152580', 'Andrew', 'Smith'),
(19, 'H00152678', 'Tanya', 'Howden'),
(21, 'h00111111', 'Jane', 'McNeill'),
(23, 'h00111123', 'Michael', 'Mckay'),
(24, 'h00111124', 'Scott', 'Lammond'),
(25, 'h00111126', 'Chris', 'Wilkie'),
(26, 'H00111118', 'Ciaran', 'Harkness'),
(27, 'h00152589', 'Heather', 'Arbuckle'),
(28, 'H00528954', 'student', 'Student'),
(29, 'H00152597', 'Lab', 'Helper'),
(30, NULL, 'Lecturer', 'Lecturer'),
(31, NULL, 'Admin', 'Admin'),
(32, 'H00000001', 'John', 'Smith'),
(33, 'H00000002', 'Jane', 'Doe');

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
(1, 'lewis785', 'test123', 4),
(2, 'Jack', 'Foster', 5),
(3, 'rob', 'test123', 2),
(4, 'liam', 'test123', 5),
(5, 'kath', 'test123', 1),
(11, 'tt526', 'test123', 2),
(14, 'ss419', 'test123', 1),
(15, 'dc016', 'test123', 1),
(16, 'fb829', 'test123', 1),
(17, 'at125', 'test123', 1),
(18, 'as878', 'test123', 1),
(19, 'th684', 'test123', 5),
(21, 'jm444', 'test123', 5),
(23, 'mm556', 'test123', 5),
(24, 'sl038', 'test123', 1),
(25, 'cw154', 'test123', 1),
(26, 'ch998', 'test123', 5),
(27, 'ha570', 'test123', 1),
(28, 'student', 'test123', 1),
(29, 'helper', 'test123', 5),
(30, 'lecturer', 'test123', 2),
(31, 'admin', 'test123', 3),
(32, 'js788', 'test123', 1),
(33, 'jd065', 'test123', 1);

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
  MODIFY `courseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `course_lecturer`
--
ALTER TABLE `course_lecturer`
  MODIFY `lecturerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `labs`
--
ALTER TABLE `labs`
  MODIFY `labID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `lab_answers`
--
ALTER TABLE `lab_answers`
  MODIFY `answerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `lab_helpers`
--
ALTER TABLE `lab_helpers`
  MODIFY `labHelperID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `lab_questions`
--
ALTER TABLE `lab_questions`
  MODIFY `questionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `question_types`
--
ALTER TABLE `question_types`
  MODIFY `questionTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `students_on_courses`
--
ALTER TABLE `students_on_courses`
  MODIFY `socID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `user_access`
--
ALTER TABLE `user_access`
  MODIFY `access_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `user_login`
--
ALTER TABLE `user_login`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
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
