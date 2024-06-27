-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 27, 2024 at 02:23 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prototype2`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `email`, `password`) VALUES
(1, 'ajayupadhyay@99notes.in', '$2y$10$9F9tpuXDU5fNj7Bcsw5x8.x2iMIX1CFCMXC4KhIpCSg49kLvDoM2q');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option1` text NOT NULL,
  `option2` text NOT NULL,
  `option3` text NOT NULL,
  `option4` text NOT NULL,
  `correct_answer` int(11) NOT NULL,
  `taglist_id` int(11) NOT NULL,
  `question_rating` int(11) DEFAULT 0,
  `addedby` int(11) NOT NULL,
  `no_of_attempts` int(11) DEFAULT 1,
  `successful_attempts` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question`, `option1`, `option2`, `option3`, `option4`, `correct_answer`, `taglist_id`, `question_rating`, `addedby`, `no_of_attempts`, `successful_attempts`) VALUES
(1, 'What is your name', 'Ajay', 'Sonal', 'Cheetah', 'Gaurav', 1, 1, 2, 1, 20, 16),
(2, 'What is your name', 'Ajay', 'Sonal', 'Cheetah', 'Gaurav', 1, 1, 2, 1, 18, 8),
(3, 'what is your pet name', 'suspal', 'ramfal', 'jakal', 'tendulkar', 4, 2, 2, 1, 6, 1),
(4, 'how many hands you have.?', '7', '2', '4', '6', 2, 1, 2, 1, 6, 4),
(5, 'how many fingers do you have', '6', '8', '10', '12', 3, 1, 2, 1, 6, 5),
(6, 'how many states are there in india', '25', '26', '27', '28', 4, 1, 2, 1, 6, 3),
(7, 'how many union territories are there', '7', '8', '9', '10', 2, 1, 2, 1, 6, 5),
(8, 'capital of india', 'delhi', 'bengal', 'uttar pradesh', 'gujarat', 1, 1, 2, 1, 5, 4),
(9, 'capital of uttar pradesh', 'prayagraj', 'varanasi', 'bareli', 'lucknow', 4, 1, 2, 1, 5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `tag_name` varchar(255) NOT NULL,
  `parent_tag_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `tag_name`, `parent_tag_name`) VALUES
(1, 'History ', 'General Studies 1'),
(2, 'Society', 'General Studies 1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `globalRating` float DEFAULT 0,
  `tagwiseRating` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tagwiseRating`)),
  `addedBy` int(11) DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `category` varchar(50) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `email`, `phone`, `password`, `gender`, `globalRating`, `tagwiseRating`, `addedBy`, `updatedBy`, `status`, `category`, `createdAt`, `updatedAt`) VALUES
(1, 'Ajay', 'upadhyay', 'ajayupadhyay@99notes.in', '9354316007', '$2y$10$qGBk7laR87NFyfZSVjEHjesuOJ3evOEb97.w2H1eWTBf77ko4CMQy', 'Male', 71.9354, NULL, NULL, NULL, 'active', NULL, '2024-06-27 08:58:21', '2024-06-27 12:11:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `taglist_id` (`taglist_id`),
  ADD KEY `addedby` (`addedby`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`taglist_id`) REFERENCES `tags` (`id`),
  ADD CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`addedby`) REFERENCES `admin_users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
