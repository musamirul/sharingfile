-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2022 at 11:22 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sharefolder`
--

-- --------------------------------------------------------

--
-- Table structure for table `docfolder`
--

CREATE TABLE `docfolder` (
  `doc_id` int(254) NOT NULL,
  `doc_name` varchar(254) NOT NULL,
  `doc_date` varchar(254) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `docfolder`
--

INSERT INTO `docfolder` (`doc_id`, `doc_name`, `doc_date`) VALUES
(7, 'test123', '2022-10-20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `docfolder`
--
ALTER TABLE `docfolder`
  ADD PRIMARY KEY (`doc_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `docfolder`
--
ALTER TABLE `docfolder`
  MODIFY `doc_id` int(254) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
