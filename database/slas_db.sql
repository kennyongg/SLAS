-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2019 at 11:55 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `slas_db`
--
CREATE DATABASE IF NOT EXISTS `slas_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `slas_db`;

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` varchar(20) NOT NULL,
  `title` varchar(30) NOT NULL,
  `description` varchar(60) DEFAULT NULL,
  `lecturer` varchar(30) NOT NULL,
  `student` varchar(30) NOT NULL,
  `subject` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_date` date NOT NULL,
  `end_time` time NOT NULL,
  `rejected_reason` varchar(50) DEFAULT NULL,
  `venue` varchar(20) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `title`, `description`, `lecturer`, `student`, `subject`, `start_date`, `start_time`, `end_date`, `end_time`, `rejected_reason`, `venue`, `status`) VALUES
('CVG0gOqdgayaTOaZwczc', 'SE Project', 'To check Documentations for SE Project', 'charisk@sunway.edu.my', 'ixgoh@slas.local', 'CSC2103', '2019-05-30', '09:00:00', '2019-05-30', '15:00:00', 'Expired. No response from lecturer', 'AE-3-2', 'rejected'),
('CVG0gOqdgayaTOaZwczD', 'SE Project', 'To check UML Diagrams for Project', 'charisk@sunway.edu.my', 'ixgoh@slas.local', 'CSC2103', '2019-05-30', '08:00:00', '2019-05-30', '15:00:00', '', 'AE-3-2', 'cancelled'),
('f9TLksi8CVWbxyipNeI7', 'Trees', 'M-Way Trees Explanation', 'muthuma@sunway.edu.my', 'ixgoh@slas.local', 'CSC2103', '2019-07-01', '08:00:00', '2019-07-01', '15:00:00', '', 'AE-3-11', 'approved'),
('f9TLksi8CVWbxyipNeI8', 'Queue', 'Assignment implementing queue', 'muthuma@sunway.edu.my', 'ixgoh@slas.local', 'CSC2103', '2019-05-30', '08:00:00', '2019-06-15', '14:58:00', 'Expired. No response from lecturer', 'AE-3-11', 'rejected');

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

CREATE TABLE `enrollment` (
  `student` varchar(30) NOT NULL,
  `subject` varchar(20) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `enrollment`
--

INSERT INTO `enrollment` (`student`, `subject`, `active`) VALUES
('ixgoh@slas.local', 'SEG2202', 1),
('grant@slas.local', 'SEG2202', 1),
('kenny@slas.local', 'SEG2202', 1),
('kevin@slas.local', 'SEG2202', 1),
('kelvin@slas.local', 'SEG2202', 1),
('ixgoh@slas.local', 'CSC2103', 1),
('grant@slas.local', 'CSC2103', 1),
('kenny@slas.local', 'CSC2103', 1),
('kevin@slas.local', 'CSC2103', 1),
('kelvin@slas.local', 'CSC2103', 1),
('ixgoh@slas.local', 'CSC2104', 1),
('grant@slas.local', 'CSC2104', 1),
('kenny@slas.local', 'CSC2104', 1),
('kevin@slas.local', 'CSC2104', 1),
('kelvin@slas.local', 'CSC2104', 1),
('ixgoh@slas.local', 'PRG2104', 1),
('grant@slas.local', 'PRG2104', 1),
('kevin@slas.local', 'PRG2104', 1),
('kelvin@slas.local', 'PRG2104', 1),
('ixgoh@slas.local', 'BIS2212_MU3 2422', 0),
('kevin@slas.local', 'BIS2212_MU3 2422', 0),
('kenny@slas.local', 'BIS2212_MU3 2422', 0);

-- --------------------------------------------------------

--
-- Table structure for table `lecturer_timetable`
--

CREATE TABLE `lecturer_timetable` (
  `lecturer_id` varchar(30) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `day` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lecturer_timetable`
--

INSERT INTO `lecturer_timetable` (`lecturer_id`, `start_time`, `end_time`, `day`) VALUES
('charisk@sunway.edu.my', '14:00:00', '15:00:00', 'Tuesday'),
('charisk@sunway.edu.my', '13:00:00', '15:00:00', 'Wednesday'),
('charisk@sunway.edu.my', '13:00:00', '15:00:00', 'Friday'),
('charisk@sunway.edu.my', '08:00:00', '10:00:00', 'Tuesday'),
('charisk@sunway.edu.my', '12:00:00', '13:00:00', 'Tuesday'),
('charisk@sunway.edu.my', '09:00:00', '09:30:00', 'Monday');

-- --------------------------------------------------------

--
-- Table structure for table `password_recovery`
--

CREATE TABLE `password_recovery` (
  `id` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `email` varchar(30) NOT NULL,
  `name` varchar(40) NOT NULL,
  `department` varchar(50) NOT NULL,
  `office_location` varchar(30) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`email`, `name`, `department`, `office_location`, `phone`) VALUES
('admin@slas.local', 'System Adminstrator', 'Information Technology Services (ITS)', 'Level 2, South Building', '3918'),
('charisk@sunway.edu.my', 'Charis Kwan Shwu Chen', 'Department of Computing and Information Systems', 'AE-3-2', '7134'),
('muthuma@sunway.edu.my', 'Muthukumaran Maruthappa', 'Department of Computing and Information Systems', 'AE-3-5', '7137'),
('teckminc@sunway.edu.my', 'Dr. Chin Teck Min', 'Department of Computing and Information Systems', 'AE-3-9', '7141'),
('waichongc@sunway.edu.my', 'Dr. Chia Wai Chong', 'Department of Computing and Information Systems', 'AE-3-27', '7143'),
('zahariny@sunway.edu.my', 'Prof. Dr. Zaharin Yussof', 'Department of Computing and Information Systems', 'AE-0-0', '0000');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `email` varchar(30) NOT NULL,
  `name` varchar(35) NOT NULL,
  `course` varchar(40) NOT NULL,
  `intake` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`email`, `name`, `course`, `intake`) VALUES
('grant@slas.local', 'Grant Yong Yung Ching', 'BSc (Hons) Computer Science', '201903'),
('ixgoh@slas.local', 'Goh Ie Xiann', 'BSc (Hons) Computer Science', '201901'),
('kelvin@slas.local', 'Ting Sai Foong', 'BSc (Hons) Computer Science', '201903'),
('kenny@slas.local', 'Kenny Ong Jon Yen', 'BSc (Hons) Software Engineering', '201901'),
('kevin@slas.local', 'Foo Chee Keong', 'BSc (Hons) Computer Science', '201901');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `id` varchar(20) NOT NULL,
  `name` varchar(40) NOT NULL,
  `lecturer` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`id`, `name`, `lecturer`) VALUES
('BIS2212_MU3 2422', 'SOCIAL AND PROFESIIONAL RESPONSIBILITIES', 'zahariny@sunway.edu.my'),
('CSC2103', 'DATA STRUCTURES & ALGORITHMS', 'muthuma@sunway.edu.my'),
('CSC2104', 'OPERATING SYSTEMS FUNDAMENTALS', 'waichongc@sunway.edu.my'),
('PRG2104', 'OBJECT ORIENTED PROGRAMMING', 'teckminc@sunway.edu.my'),
('SEG2202', 'SOFTWARE ENGINEERING', 'charisk@sunway.edu.my');

-- --------------------------------------------------------

--
-- Table structure for table `user_credential`
--

CREATE TABLE `user_credential` (
  `id` varchar(30) NOT NULL,
  `password` varchar(60) NOT NULL,
  `acc_type` varchar(10) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_credential`
--

INSERT INTO `user_credential` (`id`, `password`, `acc_type`, `active`) VALUES
('admin@slas.local', '$2y$10$F4NJgtEgMOceqhXZ5RftOuSx0MXXFlq1OKCihDAaXPEAr8T/j07mW', 'admin', 1),
('charisk@sunway.edu.my', '$2y$10$F4NJgtEgMOceqhXZ5RftOuSx0MXXFlq1OKCihDAaXPEAr8T/j07mW', 'lecturer', 1),
('grant@slas.local', '$2y$10$F4NJgtEgMOceqhXZ5RftOuSx0MXXFlq1OKCihDAaXPEAr8T/j07mW', 'student', 1),
('ixgoh@slas.local', '$2y$10$F4NJgtEgMOceqhXZ5RftOuSx0MXXFlq1OKCihDAaXPEAr8T/j07mW', 'student', 1),
('kelvin@slas.local', '$2y$10$F4NJgtEgMOceqhXZ5RftOuSx0MXXFlq1OKCihDAaXPEAr8T/j07mW', 'student', 1),
('kenny@slas.local', '$2y$10$F4NJgtEgMOceqhXZ5RftOuSx0MXXFlq1OKCihDAaXPEAr8T/j07mW', 'student', 1),
('kevin@slas.local', '$2y$10$F4NJgtEgMOceqhXZ5RftOuSx0MXXFlq1OKCihDAaXPEAr8T/j07mW', 'student', 1),
('muthuma@sunway.edu.my', '$2y$10$F4NJgtEgMOceqhXZ5RftOuSx0MXXFlq1OKCihDAaXPEAr8T/j07mW', 'lecturer', 1),
('teckminc@sunway.edu.my', '$2y$10$F4NJgtEgMOceqhXZ5RftOuSx0MXXFlq1OKCihDAaXPEAr8T/j07mW', 'lecturer', 1),
('vahabi@sunway.edu.my', '$2y$10$F4NJgtEgMOceqhXZ5RftOuSx0MXXFlq1OKCihDAaXPEAr8T/j07mW', 'lecturer', 0),
('waichongc@sunway.edu.my', '$2y$10$F4NJgtEgMOceqhXZ5RftOuSx0MXXFlq1OKCihDAaXPEAr8T/j07mW', 'lecturer', 1),
('zahariny@sunway.edu.my', '$2y$10$F4NJgtEgMOceqhXZ5RftOuSx0MXXFlq1OKCihDAaXPEAr8T/j07mW', 'lecturer', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_student_email_fk` (`student`),
  ADD KEY `appointment_lecturer_email_fk` (`lecturer`),
  ADD KEY `appointment_subject_id_fk` (`subject`);

--
-- Indexes for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD KEY `enrollment_student_email_fk` (`student`),
  ADD KEY `enrollment_subject_id_fk` (`subject`);

--
-- Indexes for table `lecturer_timetable`
--
ALTER TABLE `lecturer_timetable`
  ADD KEY `lecturer_timetable_staff_email_fk` (`lecturer_id`);

--
-- Indexes for table `password_recovery`
--
ALTER TABLE `password_recovery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password_recovery_user_credential_id_fk` (`email`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_lecturer_email_fk` (`lecturer`);

--
-- Indexes for table `user_credential`
--
ALTER TABLE `user_credential`
  ADD PRIMARY KEY (`id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_lecturer_email_fk` FOREIGN KEY (`lecturer`) REFERENCES `staff` (`email`),
  ADD CONSTRAINT `appointment_student_email_fk` FOREIGN KEY (`student`) REFERENCES `student` (`email`),
  ADD CONSTRAINT `appointment_subject_id_fk` FOREIGN KEY (`subject`) REFERENCES `subject` (`id`);

--
-- Constraints for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD CONSTRAINT `enrollment_student_email_fk` FOREIGN KEY (`student`) REFERENCES `student` (`email`),
  ADD CONSTRAINT `enrollment_subject_id_fk` FOREIGN KEY (`subject`) REFERENCES `subject` (`id`);

--
-- Constraints for table `lecturer_timetable`
--
ALTER TABLE `lecturer_timetable`
  ADD CONSTRAINT `lecturer_timetable_staff_email_fk` FOREIGN KEY (`lecturer_id`) REFERENCES `staff` (`email`);

--
-- Constraints for table `password_recovery`
--
ALTER TABLE `password_recovery`
  ADD CONSTRAINT `password_recovery_user_credential_id_fk` FOREIGN KEY (`email`) REFERENCES `user_credential` (`id`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `lecturer_user_credential_id_fk` FOREIGN KEY (`email`) REFERENCES `user_credential` (`id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_user_credential_id_fk` FOREIGN KEY (`email`) REFERENCES `user_credential` (`id`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_lecturer_email_fk` FOREIGN KEY (`lecturer`) REFERENCES `staff` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
