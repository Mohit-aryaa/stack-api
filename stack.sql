-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2022 at 06:19 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stack`
--

-- --------------------------------------------------------

--
-- Table structure for table `answerlikes`
--

CREATE TABLE `answerlikes` (
  `id` int(11) NOT NULL,
  `answer_id` varchar(100) NOT NULL,
  `liked_by` varchar(100) NOT NULL,
  `posted_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `answerlikes`
--

INSERT INTO `answerlikes` (`id`, `answer_id`, `liked_by`, `posted_on`) VALUES
(11, '1', '2', '2022-04-23 15:27:44'),
(15, '1', '1', '2022-04-23 15:49:06');

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `answer` longtext NOT NULL,
  `question_id` varchar(100) NOT NULL,
  `posted_by` varchar(100) NOT NULL,
  `posted_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `answer`, `question_id`, `posted_by`, `posted_on`) VALUES
(1, 'ghghfghgf', '1', '2', '2022-04-23 11:38:37');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `question_id` varchar(100) NOT NULL,
  `liked_by` varchar(100) NOT NULL,
  `posted_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `question_id`, `liked_by`, `posted_on`) VALUES
(6, '1', '2', '2022-04-23 14:45:41'),
(7, '1', '1', '2022-04-23 15:49:11');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `question` longtext NOT NULL,
  `slug` varchar(500) NOT NULL,
  `posted_by` varchar(100) NOT NULL,
  `posted_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `title`, `question`, `slug`, `posted_by`, `posted_on`) VALUES
(1, 'Best way to export data in xml and csv format', '<div>I\'m looking for the best way to let users export and download data in xml and csv formats. I have found maatwebsite package to export excel and csv file formats, but it doesn\'t provide xml. what do you guys recommend? why packages don\'t export xml? is it different?</div><div>1 like</div>', '1650711915-best-way-to-export-data-in-xml-and-csv-format', '2', '2022-04-23 11:05:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `avatar` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `avatar`, `password`, `designation`, `created_at`, `updated_at`) VALUES
(1, 'Mohit Arya', '123@example.com', '', 'e10adc3949ba59abbe56e057f20f883e', 'Developer', '2022-04-21 13:17:54', '0000-00-00 00:00:00'),
(2, 'Gurpreet', 'gurpreet@example.com', '23042022160530-whatsapp_image_2022-02-25_at_11.06.37_am.jpeg', 'e10adc3949ba59abbe56e057f20f883e', 'dev', '2022-04-23 14:05:30', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answerlikes`
--
ALTER TABLE `answerlikes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answerlikes`
--
ALTER TABLE `answerlikes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
