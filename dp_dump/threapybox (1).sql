-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 07, 2018 at 03:54 PM
-- Server version: 5.7.20
-- PHP Version: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `threapybox`
--
CREATE DATABASE IF NOT EXISTS `threapybox` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `threapybox`;

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

DROP TABLE IF EXISTS `photo`;
CREATE TABLE `photo` (
  `photoID` int(10) UNSIGNED NOT NULL,
  `photoPath` varchar(256) NOT NULL,
  `photoUser` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `photo`
--

INSERT INTO `photo` (`photoID`, `photoPath`, `photoUser`) VALUES
(16, 'f7c0900e331708f9.png', 1),
(19, '9bf8ff645b13aac3.png', 1),
(20, '25163d887a4abfbd.jpg', 1),
(21, '494d6b6407263ae3.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

DROP TABLE IF EXISTS `task`;
CREATE TABLE `task` (
  `taskID` int(10) UNSIGNED NOT NULL,
  `taskDescription` varchar(256) NOT NULL,
  `taskStatus` tinyint(1) NOT NULL DEFAULT '0',
  `taskUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`taskID`, `taskDescription`, `taskStatus`, `taskUser`) VALUES
(1, 'Task 1', 1, 1),
(4, 'Task 2', 1, 1),
(5, 'Task 3', 1, 1),
(6, 'Task 4', 1, 1),
(7, 'Task 5', 1, 1),
(8, 'Task 6', 1, 1),
(9, 'Task 7', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `userID` int(10) UNSIGNED NOT NULL,
  `userName` varchar(32) NOT NULL,
  `userEmail` varchar(100) NOT NULL,
  `userPassword` varchar(256) NOT NULL,
  `userImage` varchar(256) NOT NULL DEFAULT ''''''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `userName`, `userEmail`, `userPassword`, `userImage`) VALUES
(1, 'michaelmichael', 'hw390@exeter.ac.uk', '$2y$10$VsIXb4WyNGAY0s.tkR3GseDhwzHz7gZzzLfa1ukxDsILPKxkU75B6', '\'\''),
(2, 'quytemichael', 'huiyue.wang@outlook.com', '$2y$10$JJalMDXHEOzrAZoSL5VV6OjUg.Vg3sL3rSCHmEoe59uVHPk90f7NG', '\'\''),
(3, 'michaelquyteadfsd', 'huiyue.wang@icloud.com', '$2y$10$IWIuL5wIP0hZhoxPzvs.7e.GSk2ifuFseAPP.bqmPiGnPGxFbPMQm', '\'\''),
(5, 'fsdfsdfdsdsf', 'hmichad@yahoo.com', '$2y$10$lszACTTC0OXVWG63zl8in.aaSFDiBS1IqDHBGKQdYfS23AOWMIqGO', 'f54a6d45f627d7f7.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`photoID`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`taskID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `userName` (`userName`),
  ADD UNIQUE KEY `userEmail` (`userEmail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `photo`
--
ALTER TABLE `photo`
  MODIFY `photoID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `taskID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
