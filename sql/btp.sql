-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2020 at 12:25 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `btp`
--

-- --------------------------------------------------------

--
-- Table structure for table `card`
--

CREATE TABLE `card` (
  `id` int(11) NOT NULL,
  `name` varchar(256) COLLATE latin1_general_ci NOT NULL,
  `expset` text COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `text` varchar(1024) COLLATE latin1_general_ci NOT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp(),
  `receiver_user_id` int(11) NOT NULL,
  `sender_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `playgroup`
--

CREATE TABLE `playgroup` (
  `id` int(11) NOT NULL,
  `name` varchar(256) COLLATE latin1_general_ci NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(256) COLLATE latin1_general_ci NOT NULL,
  `password` varchar(2048) COLLATE latin1_general_ci NOT NULL,
  `displayname` varchar(256) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `displayname`) VALUES
(1, 'erikbuur@hotmail.com', '$2y$10$Vg5sZHVLZBmhI9I9ueGjH.fP29YG5bJsw4thd43sGWIsQ/1m9AE9S', 'VeryThankYou');

-- --------------------------------------------------------

--
-- Table structure for table `user_card`
--

CREATE TABLE `user_card` (
  `card_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `want` int(11) NOT NULL,
  `trading` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_playgroup`
--

CREATE TABLE `user_playgroup` (
  `user_id` int(11) NOT NULL,
  `playgroup_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `playgroup`
--
ALTER TABLE `playgroup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_playgroup`
--
ALTER TABLE `user_playgroup`
  ADD PRIMARY KEY (`user_id`,`playgroup_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `playgroup`
--
ALTER TABLE `playgroup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
