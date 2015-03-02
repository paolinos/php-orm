-- phpMyAdmin SQL Dump
-- version 4.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 02, 2015 at 01:08 PM
-- Server version: 5.5.41-0+wheezy1
-- PHP Version: 5.4.36-0+deb7u3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `example-orm`
--

-- --------------------------------------------------------

--
-- Table structure for table `Country`
--

CREATE TABLE IF NOT EXISTS `Country` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `Country`
--

INSERT INTO `Country` (`id`, `name`) VALUES
(1, 'Argentina'),
(2, 'Bolivia'),
(3, 'Brazil'),
(4, 'Chile'),
(5, 'Colombia'),
(6, 'Costa Rica'),
(7, 'Cuba'),
(8, 'Dominican Republic'),
(9, 'Ecuador'),
(10, 'El Salvador'),
(11, 'Guatemala'),
(12, 'Honduras'),
(13, 'Mexico'),
(14, 'Nicaragua'),
(15, 'Panama'),
(16, 'Paraguay'),
(17, 'Peru'),
(18, 'Puerto Rico'),
(19, 'Uruguay'),
(20, 'Venezuela'),
(21, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `Stuff`
--

CREATE TABLE IF NOT EXISTS `Stuff` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `Stuff`
--

INSERT INTO `Stuff` (`id`, `name`) VALUES
(1, 'LED TV 40"'),
(2, 'LED TV 55"'),
(3, 'Cellphone'),
(4, 'Phone'),
(5, 'Descktop Computer'),
(6, 'Notebook'),
(7, 'VideoGame Console'),
(8, 'SmartTV 55"'),
(9, 'SmartTV 120"'),
(10, 'Nuclear Reactor'),
(11, 'Wire Speakers'),
(12, 'Wifi Speakers'),
(13, 'Bluetooth Speakers');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) COLLATE utf8_bin NOT NULL,
  `lastname` varchar(100) COLLATE utf8_bin NOT NULL,
  `born_date` date NOT NULL,
  `countryId` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`id`, `firstname`, `lastname`, `born_date`, `countryId`) VALUES
(1, 'Bred', 'Pit', '1960-10-21', 3),
(2, 'Jhon', 'Doe', '1932-01-01', 6),
(3, 'Carl', 'Teves', '1980-01-01', 1),
(4, 'Pibe', 'Balderma', '1960-07-07', 5);

-- --------------------------------------------------------

--
-- Table structure for table `UserStuff`
--

CREATE TABLE IF NOT EXISTS `UserStuff` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `stuffId` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `UserStuff`
--

INSERT INTO `UserStuff` (`id`, `userId`, `stuffId`) VALUES
(2, 1, 2),
(3, 1, 4),
(4, 1, 6),
(5, 2, 1),
(6, 2, 3),
(7, 2, 5),
(8, 2, 9),
(9, 3, 3),
(10, 3, 4),
(11, 3, 9),
(12, 3, 12),
(13, 3, 13),
(14, 4, 7),
(15, 4, 8),
(16, 4, 9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Country`
--
ALTER TABLE `Country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Stuff`
--
ALTER TABLE `Stuff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`), ADD KEY `user_country` (`countryId`);

--
-- Indexes for table `UserStuff`
--
ALTER TABLE `UserStuff`
  ADD PRIMARY KEY (`id`), ADD KEY `userstuff-user` (`userId`), ADD KEY `userstuff-stuff` (`stuffId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Country`
--
ALTER TABLE `Country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `Stuff`
--
ALTER TABLE `Stuff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `UserStuff`
--
ALTER TABLE `UserStuff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `User`
--
ALTER TABLE `User`
ADD CONSTRAINT `user_country` FOREIGN KEY (`countryId`) REFERENCES `Country` (`id`);

--
-- Constraints for table `UserStuff`
--
ALTER TABLE `UserStuff`
ADD CONSTRAINT `userstuff-user` FOREIGN KEY (`userId`) REFERENCES `User` (`id`),
ADD CONSTRAINT `userstuff-stuff` FOREIGN KEY (`stuffId`) REFERENCES `Stuff` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
