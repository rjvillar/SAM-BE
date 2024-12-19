-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2024 at 07:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `corememories`
--

-- --------------------------------------------------------

--
-- Table structure for table `islandcontents`
--

CREATE TABLE `islandcontents` (
  `islandContentID` int(4) NOT NULL,
  `islandOfPersonalityID` int(4) NOT NULL,
  `image` varchar(50) DEFAULT NULL,
  `content` varchar(300) NOT NULL,
  `color` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `islandcontents`
--

INSERT INTO `islandcontents` (`islandContentID`, `islandOfPersonalityID`, `image`, `content`, `color`) VALUES
(1, 1, 'img/nature1.jpg', 'Unwinding somewhere no one knows me.', '#FAD02C'),
(2, 1, 'img/nature2.jpg', 'Watching at the shore to get peace.', '#FAD02C'),
(3, 1, 'img/nature3.jpg', 'The best view that day.', '#FAD02C'),
(4, 2, 'img/cats1.jpg', 'This is one of my siblings cat, Luna.', '#6495ED'),
(5, 2, 'img/cats2.jpg', 'Another one of my siblings cat, her name is Lola.', '#6495ED'),
(6, 2, 'img/cats3.jpg', 'This is my very first cat, Felix.', '#6495ED'),
(7, 3, 'img/pupstc1.jpeg', 'After perfoming bench yell at GA.', '#E35D5D'),
(8, 3, 'img/pupstc2.jpeg', 'We won Overall Champion category.', '#E35D5D'),
(9, 3, 'img/pupstc3.jpeg', 'Basketball Boys won championship.', '#E35D5D'),
(10, 4, 'img/hirono1.jpg', 'My first two pull from reshape series.', '#9ACD32'),
(11, 4, 'img/hirono2.jpg', 'Drowning and Fading on my bag chilling.', '#9ACD32'),
(12, 4, 'img/hirono3.jpg', 'My friends hironos and mine altogether.', '#9ACD32');

-- --------------------------------------------------------

--
-- Table structure for table `islandsofpersonality`
--

CREATE TABLE `islandsofpersonality` (
  `islandOfPersonalityID` int(4) NOT NULL,
  `name` varchar(40) NOT NULL,
  `shortDescription` varchar(300) DEFAULT NULL,
  `longDescription` varchar(900) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `islandsofpersonality`
--

INSERT INTO `islandsofpersonality` (`islandOfPersonalityID`, `name`, `shortDescription`, `longDescription`, `color`, `image`, `status`) VALUES
(1, 'Random Island', 'Random nature pics.', 'This island stores memories about the time I am wandering and seeing the beauty of the environment.', '#FAD02C', 'img/nature.jpg', 'active'),
(2, 'Pet Island', 'Memories of my pets.', 'This island contains memories about my cats, shared moments, and the bond of our love and care.', '#6495ED', 'img/cats.jpg', 'active'),
(3, 'PUP Island', 'Learn, grow and make some issues.', 'This island represents memories of studying in PUPSTC, the events, and significant achievements.', '#E35D5D', 'img/pupstc.jpeg', 'active'),
(4, 'Hirono Island', 'Collection and cuteness.', 'This island is about my collection of hironos that bring happiness and fulfillment for my inner child.', '#9ACD32', 'img/hirono.jpg', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `islandcontents`
--
ALTER TABLE `islandcontents`
  ADD PRIMARY KEY (`islandContentID`);

--
-- Indexes for table `islandsofpersonality`
--
ALTER TABLE `islandsofpersonality`
  ADD PRIMARY KEY (`islandOfPersonalityID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `islandcontents`
--
ALTER TABLE `islandcontents`
  MODIFY `islandContentID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `islandsofpersonality`
--
ALTER TABLE `islandsofpersonality`
  MODIFY `islandOfPersonalityID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
