-- phpMyAdmin SQL Dump
-- version 5.2.2-dev+20231128.484ac06cd8
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2023 at 07:24 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bloodbankdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `bloodbank`
--

CREATE TABLE `bloodbank` (
  `bloodgroup` varchar(3) NOT NULL,
  `amount` int(11) NOT NULL,
  `username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bloodbank`
--

INSERT INTO `bloodbank` (`bloodgroup`, `amount`, `username`) VALUES
('A+', 0, 'Pheonix'),
('A+', 6, 'Sarvanand'),
('A-', 6, 'Sarvanand'),
('AB+', 0, 'Pheonix'),
('AB+', 6, 'Sarvanand'),
('AB-', 0, 'Pheonix'),
('AB-', 6, 'Sarvanand'),
('B+', 3, 'Pheonix'),
('B+', 3, 'Sarvanand'),
('B-', 0, 'Pheonix'),
('B-', 6, 'Sarvanand'),
('O+', 1, 'Pheonix'),
('O+', 0, 'Sarvanand'),
('O-', 0, 'Pheonix'),
('O-', 0, 'Sarvanand');

-- --------------------------------------------------------

--
-- Table structure for table `hospital`
--

CREATE TABLE `hospital` (
  `username` varchar(20) NOT NULL,
  `password` varchar(16) NOT NULL,
  `hospitalname` text NOT NULL,
  `address` text NOT NULL,
  `email` text NOT NULL,
  `number` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hospital`
--

INSERT INTO `hospital` (`username`, `password`, `hospitalname`, `address`, `email`, `number`) VALUES
('Pheonix', 'Pheonix@123', 'Pheonix Hospital', 'Kurla Camp,Ulhasnagar', 'pheonixhospital@gmail.com', '9763312069'),
('Sarvanand', 'Sarvanand@123', 'Sarvanand Hospital', 'Netaji Chowk, Ulhasnagar-4', 'sarvanand@gmail.com', '9156949732');

-- --------------------------------------------------------

--
-- Table structure for table `receiver`
--

CREATE TABLE `receiver` (
  `username` varchar(20) NOT NULL,
  `password` varchar(16) NOT NULL,
  `name` text NOT NULL,
  `age` int(11) NOT NULL,
  `gender` text NOT NULL,
  `bloodgroup` varchar(3) NOT NULL,
  `email` text NOT NULL,
  `number` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `receiver`
--

INSERT INTO `receiver` (`username`, `password`, `name`, `age`, `gender`, `bloodgroup`, `email`, `number`) VALUES
('Aarti', 'Aarti@123', 'Aarti Kumbhar', 15, 'male', 'A+', 'ajaykumbhar9876@gmail.com', '9763312069'),
('Ajay', 'Ajay@123', 'Ajay Kumbhar', 23, 'male', 'B+', 'ajaykumbhar9876@gmail.com', '9763312069');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `bloodgroup` varchar(3) NOT NULL,
  `receivername` varchar(20) NOT NULL,
  `hospitalname` varchar(20) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` varchar(15) NOT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`bloodgroup`, `receivername`, `hospitalname`, `amount`, `status`, `time`) VALUES
('B-', 'Ajay', 'Pheonix', 3, 'Accepted', '2023-12-07 09:12:31'),
('B-', 'Ajay', 'Sarvanand', 3, 'Rejected', '2023-12-07 09:16:32'),
('B+', 'Ajay', 'Pheonix', 2, 'Rejected', '2023-12-07 09:17:05'),
('O+', 'Ajay', 'Pheonix', 3, 'Accepted', '2023-12-07 09:20:06'),
('B+', 'Ajay', 'Sarvanand', 3, 'Accepted', '2023-12-07 09:20:16'),
('O+', 'Ajay', 'Sarvanand', 6, 'Rejected', '2023-12-07 10:34:22'),
('O-', 'Ajay', 'Pheonix', 3, 'Accepted', '2023-12-07 10:35:31'),
('O-', 'Ajay', 'Sarvanand', 4, 'Rejected', '2023-12-07 10:49:45'),
('O+', 'Aarti', 'Pheonix', 1, 'Accepted', '2023-12-07 11:14:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bloodbank`
--
ALTER TABLE `bloodbank`
  ADD PRIMARY KEY (`bloodgroup`,`username`),
  ADD KEY `fk1` (`username`);

--
-- Indexes for table `hospital`
--
ALTER TABLE `hospital`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `receiver`
--
ALTER TABLE `receiver`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD KEY `fk2` (`hospitalname`),
  ADD KEY `fk3` (`receivername`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bloodbank`
--
ALTER TABLE `bloodbank`
  ADD CONSTRAINT `fk1` FOREIGN KEY (`username`) REFERENCES `hospital` (`username`);

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `fk2` FOREIGN KEY (`hospitalname`) REFERENCES `hospital` (`username`),
  ADD CONSTRAINT `fk3` FOREIGN KEY (`receivername`) REFERENCES `receiver` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
