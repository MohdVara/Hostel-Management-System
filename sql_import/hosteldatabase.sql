-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2016 at 02:47 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hosteldatabase`
--

-- --------------------------------------------------------

--
-- Table structure for table `block`
--

CREATE TABLE IF NOT EXISTS `block` (
  `block_id` varchar(2) NOT NULL,
  `block_name` text NOT NULL,
  `block_cap` int(11) NOT NULL,
  `block_gen` enum('M','F') NOT NULL,
  PRIMARY KEY (`block_id`),
  UNIQUE KEY `block_id` (`block_id`),
  UNIQUE KEY `block_id_2` (`block_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `block`
--

INSERT INTO `block` (`block_id`, `block_name`, `block_cap`, `block_gen`) VALUES
('F', '', 20, 'M'),
('G', '', 30, 'F'),
('K', '', 18, 'M');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `permission` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `permission`) VALUES
(1, 'Student', '{"student":1}'),
(2, 'Warden', '{"warden":1}'),
(3, 'Admin', '{"admin":1}'),
(4, 'unverified_student', '{"student":0}');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_title` text NOT NULL,
  `message_body` text NOT NULL,
  `message_type` enum('private','feed') NOT NULL,
  `message_DateTime` datetime NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `readd` tinyint(1) NOT NULL,
  PRIMARY KEY (`message_id`),
  UNIQUE KEY `message_id` (`message_id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`message_id`, `message_title`, `message_body`, `message_type`, `message_DateTime`, `sender_id`, `receiver_id`, `readd`) VALUES
(1, 'Google', 'Type your message here', 'feed', '2014-09-01 08:46:00', 1131100275, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_type` enum('monthly','deposit') NOT NULL,
  `payment_regisdate` date NOT NULL,
  `payment_month` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `payment_amt` double NOT NULL,
  `receipt_no` varchar(50) NOT NULL,
  `payed` tinyint(1) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  UNIQUE KEY `payment_id` (`payment_id`),
  KEY `student_id` (`student_id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62 ;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `payment_type`, `payment_regisdate`, `payment_month`, `student_id`, `payment_amt`, `receipt_no`, `payed`, `admin_id`) VALUES
(31, 'monthly', '2014-09-02', 9, 1131100275, 250, '1234', 1, NULL),
(34, 'deposit', '2014-09-02', 9, 1131100275, 250, '1234', 1, NULL),
(35, 'deposit', '2014-09-02', 10, 1131100275, 250, '', 0, NULL),
(36, 'monthly', '2014-09-02', 10, 1131100275, 250, '1234', 1, NULL),
(38, 'monthly', '2014-09-02', 11, 1131100275, 250, '1234', 1, NULL),
(44, 'monthly', '2014-09-02', 12, 1131100275, 250, '1', 1, NULL),
(60, 'monthly', '2014-09-02', 1, 1131100275, 250, '12', 1, NULL),
(61, 'monthly', '2014-09-02', 2, 1131100275, 250, '15', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE IF NOT EXISTS `room` (
  `room_id` varchar(4) NOT NULL,
  `room_cap` int(11) NOT NULL,
  `room_price` double NOT NULL,
  `room_occupied` int(11) NOT NULL,
  `room_block` varchar(2) NOT NULL,
  `availability` tinyint(1) NOT NULL,
  PRIMARY KEY (`room_id`),
  UNIQUE KEY `room_id` (`room_id`),
  KEY `room_block` (`room_block`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `room_cap`, `room_price`, `room_occupied`, `room_block`, `availability`) VALUES
('F2C', 1, 250, 0, 'F', 1),
('F3C', 3, 0, 0, 'F', 1),
('G2C', 1, 0, 0, 'G', 1),
('K2C', 2, 0, 0, 'K', 1);

-- --------------------------------------------------------

--
-- Table structure for table `table`
--

CREATE TABLE IF NOT EXISTS `table` (
  `a` int(11) NOT NULL,
  `b` text NOT NULL,
  `c` varchar(30) NOT NULL,
  `d` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `Name` text NOT NULL,
  `ID` int(11) NOT NULL,
  `Password` varchar(64) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `IC` varchar(15) NOT NULL,
  `LastLogin` datetime NOT NULL,
  `Course` text NOT NULL,
  `School` varchar(50) NOT NULL,
  `TelephoneNo` varchar(50) NOT NULL,
  `EMail` varchar(50) NOT NULL,
  `Gender` enum('M','F') NOT NULL,
  `Race` text NOT NULL,
  `Religion` text NOT NULL,
  `House_No` text NOT NULL,
  `Area` text NOT NULL,
  `Postcode` int(11) NOT NULL,
  `State` text NOT NULL,
  `RegDateTime` datetime NOT NULL,
  `RoomID` varchar(4) NOT NULL,
  `GroupNo` int(11) NOT NULL,
  `GName` text NOT NULL,
  `GEMail` varchar(50) NOT NULL,
  `GHouse_No` text NOT NULL,
  `GArea` text NOT NULL,
  `GPostcode` int(11) NOT NULL,
  `GRelationship` text NOT NULL,
  `GTelephoneNo` varchar(50) NOT NULL,
  `Debt` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `RoomID` (`RoomID`),
  KEY `GroupNo` (`GroupNo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Name`, `ID`, `Password`, `salt`, `IC`, `LastLogin`, `Course`, `School`, `TelephoneNo`, `EMail`, `Gender`, `Race`, `Religion`, `House_No`, `Area`, `Postcode`, `State`, `RegDateTime`, `RoomID`, `GroupNo`, `GName`, `GEMail`, `GHouse_No`, `GArea`, `GPostcode`, `GRelationship`, `GTelephoneNo`, `Debt`) VALUES
('Mohd. Paramasvara', 1131100275, '23cd341138b7aa1171504750d8f238ec5682d7982f2486c848cb559c90c00259', 'Ù]d²Ü’`¬ðäÃ‘ÐtÍEˆ\0‰·ÑÏËŸ¥·', '950511125139', '2014-10-10 23:17:00', 'DSE', 'Engineering', '0148648630', 'wara_mohd@yahoo.com.sg', 'M', 'malay', 'islam', 'C~5~1', 'Gurney Heights', 54000, 'Kuala Lumpur', '2014-08-31 11:43:00', 'F2C', 3, 'Mohd. Paramasvara', 'mwara95@gmail.com', 'C~5~1', 'Gurney Heights', 54000, 'self', '0148648630', 0),
('Mohd. Paramasvara', 1131100277, '5f4530c89785400bc6f64d1abea45ddd27466aecd9512b8117d5f35a04bc05f9', 'VìpæK´q¬’ö™9µ§5»ZÖ¯R"1Ö‹»ã±ý', '950511125139', '2014-08-31 12:02:00', 'DSE', 'Engineering', '0148648630', 'wara_mohd@yahoo.com.sg', 'M', 'malay', 'islam', '0', '0', 0, 'Sabah', '2014-08-31 11:43:00', 'F2C', 2, 'Mohd. Paramasvara', 'mwara95@gmail.com', '', '', 0, 'self', '0148648630', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_session`
--

CREATE TABLE IF NOT EXISTS `users_session` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hash` int(50) NOT NULL,
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`ID`),
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`ID`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`ID`),
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `users` (`ID`);

--
-- Constraints for table `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`room_block`) REFERENCES `block` (`block_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`RoomID`) REFERENCES `room` (`room_id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`GroupNo`) REFERENCES `groups` (`id`);

--
-- Constraints for table `users_session`
--
ALTER TABLE `users_session`
  ADD CONSTRAINT `users_session_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
