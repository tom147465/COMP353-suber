-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2016 at 12:58 AM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suber`
--

-- --------------------------------------------------------

--
-- Table structure for table `book`
--


CREATE TABLE `book` (
  `username` varchar(12) NOT NULL,
  `tid` int(11) NOT NULL,
  `type` varchar(8) NOT NULL,
  `price` float NOT NULL,
  `rate` int(1) NOT NULL DEFAULT '3'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`username`, `tid`, `type`, `price`, `rate`) VALUES
('admin', 8, 'trip', 0, 3),
('admin', 1, 'trip', 0, 2),
('123', 4, 'trip', 5.08, 3);

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `username` varchar(12) NOT NULL,
  `password` varchar(50) NOT NULL,
  `privilege` varchar(6) NOT NULL DEFAULT 'member',
  `email` varchar(40) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'inactive',
  `address` text,
  `driver_license` varchar(10) DEFAULT NULL,
  `policies` varchar(10) DEFAULT NULL,
  `DOB` date DEFAULT NULL,
  `balance` float NOT NULL DEFAULT '0',
  `userInfo` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`username`, `password`, `privilege`, `email`, `status`, `address`, `driver_license`, `policies`, `DOB`, `balance`, `userInfo`) VALUES
('123', 'bcbe3365e6ac95ea2c0343a2395834dd', 'member', '111', 'active', 'kjksjdk', '21321312', '1232', '2016-11-14', 95.58, '{"email":"123@encs.concordia.ca","DOB":"2016-11-14"}'),
('admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'admin@encs.concordia.ca', 'active', 'sdadasdsa', '12', '123', '2013-01-18', 50, '{"policies":"123","DOB":"2013-01-18"}'),
('libo', '202cb962ac59075b964b07152d234b70', 'member', 'sadsad', 'active', 'dsadsadas', 'sasadddsda', 'dsa', '2016-12-23', 0, '{"email":"sadsad","policies":"dsa"}');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `ID` int(11) NOT NULL,
  `content` text NOT NULL,
  `m_from` varchar(12) NOT NULL,
  `m_to` varchar(12) NOT NULL,
  `senddate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`ID`, `content`, `m_from`, `m_to`, `senddate`) VALUES
(1, 'dsads', 'admin', '123', '2016-11-29 20:47:49'),
(2, 'dsadsaddas', 'admin', '123', '2016-11-29 22:37:01'),
(3, 'asdsada', '123', 'admin', '2016-11-29 22:47:22'),
(4, 'hello', 'libo', '123', '2016-12-01 10:40:38'),
(5, 'hello', 'admin', '123', '2016-12-01 10:49:43'),
(6, 'hello', 'admin', '123', '2016-12-01 10:51:22'),
(7, 'hahaha', 'admin', 'libo', '2016-12-01 10:51:58');

-- --------------------------------------------------------

--
-- Table structure for table `publicitem`
--

CREATE TABLE `publicitem` (
  `ID` int(11) NOT NULL,
  `date` date NOT NULL,
  `title` varchar(20) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `publicitem`
--

INSERT INTO `publicitem` (`ID`, `date`, `title`, `content`) VALUES
(2, '2016-11-07', 'hahahah', ';;;;;;;;;;;;laaaaaaaaaaaa'),
(3, '2016-11-30', 'wqeqw', 'nihao a  wo xiang ni a '),
(5, '2016-12-01', 'test', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `specialoffer`
--


*/

CREATE TABLE `specialoffer` (
  `ID` int(11) NOT NULL,
  `type` varchar(8) NOT NULL,
  `City_departure` varchar(12) NOT NULL,
  `Postcode_departure` varchar(8) NOT NULL,
  `City_destination` varchar(12) NOT NULL,
  `Postcode_destination` varchar(8) NOT NULL,
  `Date` date NOT NULL,
  `depart_time` time NOT NULL,
  `detail` text NOT NULL,
  `driver` varchar(12) NOT NULL,
  `customer` varchar(12) DEFAULT NULL,
  `price` float NOT NULL DEFAULT '0',
  `rate` int(1) NOT NULL DEFAULT '3'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `specialoffer`
--

INSERT INTO `specialoffer` (`ID`, `type`, `City_departure`, `Postcode_departure`, `City_destination`, `Postcode_destination`, `Date`, `depart_time`, `detail`, `driver`, `customer`, `price`, `rate`) VALUES
(2, 'delivery', ' das', 'dsa', 'dsa', 'das', '2016-12-24', '23:12:00', 'dsadsadasvcvxcvbx', '123', NULL, 0, 3),
(3, 'pickup', 'sdad sdada', 'h3h 2n7', 'montreal', 'h3h 2n4', '2016-12-22', '03:00:00', '3213', 'admin', '123', 1.17, 3),
(4, 'pickup', 'montreal', '312', 'montreal', '321', '2016-12-08', '03:21:00', '3213213123', 'admin', NULL, 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `trip`
--

CREATE TABLE `trip` (
  `ID` int(11) NOT NULL,
  `type` varchar(8) NOT NULL,
  `City_departure` varchar(10) NOT NULL,
  `Postcode_departure` varchar(8) NOT NULL,
  `City_destination` varchar(10) NOT NULL,
  `Postcode_destination` varchar(8) NOT NULL,
  `Date` date DEFAULT NULL,
  `Day` varchar(10) DEFAULT NULL,
  `depart_time` time NOT NULL,
  `Number_rider` int(11) NOT NULL DEFAULT '1',
  `Number_offer` int(11) NOT NULL DEFAULT '0',
  `detail` text NOT NULL,
  `driver` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trip`
--

INSERT INTO `trip` (`ID`, `type`, `City_departure`, `Postcode_departure`, `City_destination`, `Postcode_destination`, `Date`, `Day`, `depart_time`, `Number_rider`, `Number_offer`, `detail`, `driver`) VALUES
(1, 'oneTime', 'montreal', 'h3h2n7', 'montreal', 'h3c5a2', '2016-12-31', NULL, '09:30:00', 3, 0, '123', '123'),
(4, 'oneTime', 'montreal', 'h3c 2n4', 'montreal', 'h3h 2n7', '2016-11-02', '', '00:00:00', 1, 0, 'dasdsa111', 'admin'),
(10, 'oneTime', 'montreal', 'H3H 1N0', 'toronto', 'M5S 3H7', '2016-12-10', NULL, '12:00:00', 5, 0, 'dsadsafhfghfada', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`username`,`tid`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `publicitem`
--
ALTER TABLE `publicitem`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `specialoffer`
--
ALTER TABLE `specialoffer`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `trip`
--
ALTER TABLE `trip`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `driver` (`driver`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `publicitem`
--
ALTER TABLE `publicitem`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `specialoffer`
--
ALTER TABLE `specialoffer`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `trip`
--
ALTER TABLE `trip`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
