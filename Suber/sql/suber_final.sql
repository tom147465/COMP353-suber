-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2016 at 05:43 AM
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `book`
--
DELIMITER $$
CREATE TRIGGER `decrease_offer_number` AFTER INSERT ON `book` FOR EACH ROW BEGIN
DECLARE off_number int;
DECLARE rid_number int;

set off_number = (SELECT Number_offer FROM trip WHERE ID = NEW.tid);
set rid_number = (SELECT Number_rider FROM trip WHERE ID = NEW.tid);
if(off_number < rid_number)THEN
	set off_number = off_number +1;
	UPDATE trip SET Number_offer = off_number WHERE ID = NEW.tid;
ELSE
	SIGNAL SQLSTATE '45000'
	set MESSAGE_TEXT = 'cannot book more!';
END IF;

END
$$
DELIMITER ;

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
('admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'admin@encs.concordia.ca', 'active', 'Concordia university\r\nCOMP 353, group 18', 'CE123F', 'H2CNC4', '2013-01-18', 50, '{"policies":"123","DOB":"2013-01-18"}'),
('root11', '202cb962ac59075b964b07152d234b70', 'member', '123@123.com', 'suspended', 'concordia 601', '321386876', 'N3HG3', '2016-12-24', 20, '[]');

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
(8, 'Test send message function!!', 'root11', 'admin', '2016-12-09 00:40:27'),
(9, 'check!!check!!', 'admin', 'root11', '2016-12-09 00:40:56');

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
(1, '2016-12-09', 'Hello', 'Hello, welcome Suber.'),
(2, '2016-12-09', 'Test', 'This public item just test the function.');

-- --------------------------------------------------------

--
-- Table structure for table `specialoffer`
--

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
(1, 'pickup', 'montreal', 'h3h 2n7', 'montreal', 'h3h 2n4', '2016-12-18', '10:00:00', 'puckup service, anyone need it ??', 'admin', NULL, 0, 3);

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
(1, 'oneTime', 'montreal', 'H3H 2N7', 'toronto', 'M5S 3H7', '2016-12-24', NULL, '12:03:00', 4, 0, 'driving to Toronto, is there anyone come with me ?\r\n4 riders available.', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`username`,`tid`),
  ADD KEY `tid` (`tid`);

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
  ADD PRIMARY KEY (`ID`),
  ADD KEY `m_to` (`m_to`),
  ADD KEY `m_from` (`m_from`);

--
-- Indexes for table `publicitem`
--
ALTER TABLE `publicitem`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `specialoffer`
--
ALTER TABLE `specialoffer`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `driver` (`driver`);

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `publicitem`
--
ALTER TABLE `publicitem`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `specialoffer`
--
ALTER TABLE `specialoffer`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `trip`
--
ALTER TABLE `trip`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `book_ibfk_3` FOREIGN KEY (`username`) REFERENCES `member` (`username`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_ibfk_4` FOREIGN KEY (`tid`) REFERENCES `trip` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_3` FOREIGN KEY (`m_from`) REFERENCES `member` (`username`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_ibfk_4` FOREIGN KEY (`m_to`) REFERENCES `member` (`username`) ON DELETE CASCADE;

--
-- Constraints for table `specialoffer`
--
ALTER TABLE `specialoffer`
  ADD CONSTRAINT `specialoffer_ibfk_2` FOREIGN KEY (`driver`) REFERENCES `member` (`username`) ON DELETE CASCADE;

--
-- Constraints for table `trip`
--
ALTER TABLE `trip`
  ADD CONSTRAINT `trip_ibfk_1` FOREIGN KEY (`driver`) REFERENCES `member` (`username`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
