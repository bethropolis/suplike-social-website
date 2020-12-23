-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2020 at 10:19 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suplike`
--
CREATE DATABASE IF NOT EXISTS `suplike` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `suplike`;

-- --------------------------------------------------------

--
-- Table structure for table `auth_key`
--

DROP TABLE IF EXISTS `auth_key`;
CREATE TABLE `auth_key` (
  `id` int(11) NOT NULL,
  `api_key` varchar(50) NOT NULL,
  `user` int(11) NOT NULL,
  `last_access` datetime NOT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='still in beta';

--
-- Dumping data for table `auth_key`
--

INSERT INTO `auth_key` (`id`, `api_key`, `user`, `last_access`, `time`) VALUES
(1, 'OytsaB78Ze5Y912027', 1, '2020-12-21 10:37:34', '2020-12-21 10:35:13');

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

DROP TABLE IF EXISTS `chat`;
CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `who_from` varchar(128) NOT NULL,
  `who_to` varchar(128) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `following`
--

DROP TABLE IF EXISTS `following`;
CREATE TABLE `following` (
  `id` int(21) NOT NULL,
  `user` int(21) NOT NULL,
  `following` int(21) NOT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `following`
--

INSERT INTO `following` (`id`, `user`, `following`, `time`) VALUES
(2, 16, 16, '2020-12-17 21:43:49'),
(10, 16, 6, '2020-12-17 21:43:49'),
(12, 16, 15, '2020-12-17 21:43:49'),
(18, 16, 17, '2020-12-17 21:43:49'),
(19, 17, 18, '2020-12-17 21:43:49'),
(20, 4, 1, '2020-12-17 21:43:49'),
(25, 8, 5, '2020-12-17 21:43:49'),
(27, 5, 1, '2020-12-18 23:22:29'),
(28, 5, 6, '2020-12-18 23:22:30'),
(32, 5, 4, '2020-12-19 22:20:39'),
(35, 5, 3, '2020-12-20 11:08:21'),
(36, 4, 5, '2020-12-20 11:40:12'),
(38, 5, 2, '2020-12-20 16:16:37'),
(39, 1, 5, '2020-12-20 19:54:12'),
(40, 2, 1, '2020-12-20 22:15:50'),
(41, 9, 5, '2020-12-21 13:19:10'),
(42, 5, 9, '2020-12-21 13:28:16');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `post_id`, `user_id`, `time`) VALUES
(1, 4, 5, '2020-12-17 21:29:31'),
(11, 116, 8, '2020-12-17 21:29:31'),
(12, 74, 1, '2020-12-17 21:29:31'),
(17, 76, 1, '2020-12-17 21:29:31'),
(18, 76, 2, '2020-12-17 21:29:31'),
(46, 95, 17, '2020-12-17 21:29:31'),
(47, 94, 17, '2020-12-17 21:29:31'),
(48, 93, 17, '2020-12-17 21:29:31'),
(49, 92, 17, '2020-12-17 21:29:31'),
(50, 91, 17, '2020-12-17 21:29:31'),
(51, 90, 17, '2020-12-17 21:29:31'),
(52, 89, 17, '2020-12-17 21:29:31'),
(53, 63, 17, '2020-12-17 21:29:31'),
(54, 87, 17, '2020-12-17 21:29:31'),
(55, 85, 17, '2020-12-17 21:29:31'),
(56, 64, 17, '2020-12-17 21:29:31'),
(57, 65, 17, '2020-12-17 21:29:31'),
(58, 68, 17, '2020-12-17 21:29:31'),
(59, 70, 17, '2020-12-17 21:29:31'),
(61, 88, 17, '2020-12-17 21:29:31'),
(62, 77, 17, '2020-12-17 21:29:31'),
(63, 75, 17, '2020-12-17 21:29:31'),
(64, 84, 17, '2020-12-17 21:29:31'),
(73, 95, 18, '2020-12-17 21:29:31'),
(89, 96, 17, '2020-12-17 21:29:31'),
(97, 97, 17, '2020-12-17 21:29:31'),
(100, 64, 1, '2020-12-17 21:29:31'),
(104, 98, 17, '2020-12-17 21:29:31'),
(105, 99, 17, '2020-12-17 21:29:31'),
(106, 101, 17, '2020-12-17 21:29:31'),
(107, 94, 16, '2020-12-17 21:29:31'),
(108, 74, 18, '2020-12-17 21:29:31'),
(109, 102, 18, '2020-12-17 21:29:31'),
(110, 103, 5, '2020-12-17 21:29:31'),
(111, 103, 8, '2020-12-17 21:29:31'),
(113, 104, 5, '2020-12-17 21:29:31'),
(114, 102, 5, '2020-12-17 21:29:31'),
(115, 105, 5, '2020-12-17 22:05:50'),
(119, 117, 5, '2020-12-20 09:40:25'),
(121, 115, 5, '2020-12-20 10:17:43'),
(122, 74, 5, '2020-12-20 10:17:55'),
(123, 114, 5, '2020-12-20 10:19:45'),
(124, 106, 5, '2020-12-20 10:31:07'),
(125, 112, 5, '2020-12-20 10:36:28'),
(126, 106, 4, '2020-12-20 11:39:52'),
(127, 117, 4, '2020-12-20 11:39:56'),
(128, 115, 4, '2020-12-20 11:40:29'),
(129, 74, 4, '2020-12-20 11:44:13'),
(130, 131, 5, '2020-12-20 19:43:01'),
(131, 106, 1, '2020-12-20 19:48:30'),
(132, 112, 1, '2020-12-20 19:55:15'),
(133, 131, 1, '2020-12-20 19:55:41'),
(134, 118, 4, '2020-12-20 22:11:50'),
(135, 118, 8, '2020-12-20 22:12:11'),
(136, 115, 9, '2020-12-21 13:35:29'),
(137, 118, 9, '2020-12-21 13:37:02'),
(138, 74, 9, '2020-12-21 13:37:15'),
(139, 134, 9, '2020-12-21 13:37:40'),
(140, 131, 9, '2020-12-21 14:42:44'),
(142, 114, 9, '2020-12-21 21:42:19'),
(143, 112, 9, '2020-12-21 21:42:21'),
(145, 105, 9, '2020-12-21 21:42:23'),
(147, 133, 5, '2020-12-23 00:04:08'),
(149, 134, 5, '2020-12-23 23:45:50');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `image` text DEFAULT NULL,
  `image_text` text DEFAULT NULL,
  `userid` int(11) NOT NULL,
  `type` varchar(21) NOT NULL DEFAULT 'txt',
  `date_posted` text NOT NULL,
  `post_likes` int(21) NOT NULL DEFAULT 0,
  `day` varchar(12) DEFAULT NULL,
  `time` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `image`, `image_text`, `userid`, `type`, `date_posted`, `post_likes`, `day`, `time`) VALUES
(103, '', 'this is a post', 5, 'txt', '16 dec', 2, '0', '0000-00-00 00:00:00'),
(105, '', 'make a post and we shall see about it', 5, 'txt', '', 2, NULL, '2020-12-17 19:03:44'),
(106, NULL, 'hello world, this is an api test', 1, 'txt', '', 3, NULL, '2020-12-19 15:28:08'),
(112, '1962.webp', 'hffch fcgh', 5, 'txt', '19 Dec', 3, 'Saturday', '2020-12-19 16:06:49'),
(114, NULL, 'another test from main page', 5, 'txt', '19 Dec', 2, 'Saturday', '2020-12-19 16:16:23'),
(115, NULL, 'viva la france', 5, 'txt', '19 Dec', 2, 'Saturday', '2020-12-19 16:16:59'),
(117, NULL, 'what\\\'s up guy\\\'s', 4, 'txt', '19 Dec', 2, 'Saturday', '2020-12-19 19:21:21'),
(118, '1572.gif', 'the code goes round an...', 5, 'img', '20 Dec', 2, 'Sunday', '2020-12-20 07:35:48'),
(131, NULL, 'post for the date', 5, 'txt', '20 Dec', 3, 'Sunday', '2020-12-20 16:42:55'),
(132, NULL, 'wooooooooooooooooooooooooo hoooooooooooooooooooooo', 1, 'txt', '20 Dec', 0, 'Sunday', '2020-12-20 16:55:59'),
(133, NULL, 'checking time post ', 1, 'txt', '21 Dec', 1, 'Monday', '2020-12-21 06:56:42'),
(134, NULL, 'kk kroos in the house ', 9, 'txt', '21 Dec', 2, 'Monday', '2020-12-21 10:37:35'),
(135, NULL, 'this is awsome', 5, 'txt', '23 Dec', 0, 'Wednesday', '2020-12-23 18:33:24');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `delt` tinyint(1) NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `post_id`, `delt`, `time`) VALUES
(1, 74, 1, '2020-12-22 21:02:17'),
(2, 114, 0, '2020-12-22 22:56:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `idusers` int(11) NOT NULL,
  `uidusers` tinytext NOT NULL,
  `emailusers` tinytext NOT NULL,
  `pwdUsers` longtext NOT NULL,
  `usersFirstname` tinytext DEFAULT NULL,
  `usersSecondname` tinytext DEFAULT NULL,
  `isAdmin` tinyint(1) NOT NULL DEFAULT 0,
  `last_online` datetime NOT NULL DEFAULT current_timestamp(),
  `usersAge` int(11) DEFAULT NULL,
  `profile_picture` tinytext DEFAULT NULL,
  `gender` int(2) NOT NULL DEFAULT 0,
  `followers` int(11) NOT NULL,
  `following` int(11) NOT NULL,
  `bio` longtext NOT NULL,
  `date_joined` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`idusers`, `uidusers`, `emailusers`, `pwdUsers`, `usersFirstname`, `usersSecondname`, `isAdmin`, `last_online`, `usersAge`, `profile_picture`, `gender`, `followers`, `following`, `bio`, `date_joined`) VALUES
(1, 'admin', 'admin@admin.a', '$2y$10$OctA/Gm2pPCqamBhn0zmx.ko3V8p2v.RMI.J5ltn2h5Cqekljh72O', 'admin', 'admin', 1, '2020-12-21 22:08:29', NULL, NULL, 0, 4, 2, 'admin here', '2020-11-22'),
(2, 'yo', 'h@y.k', '$2y$10$YoDwPntbsEml3Vv1ODEfJeK/h9Zy33KSmFgKfRiykWF2omI5hQASC', 'Bethuel', 'Kipsang', 0, '2020-12-21 22:08:29', 2004, NULL, 0, 1, 1, '', '2020-11-22'),
(3, 't', 'test@bethlton.ga', '$2y$10$GjbFlPqrsbBXyc3ADQqh.uBW020C4TdT.dd.FKbUvBESYcOZ3GDBC', 't', 't', 0, '2020-12-21 22:08:29', 0, NULL, 0, 1, 0, '', '2020-11-22'),
(4, 'wolfy', 'wo@lf.y', '$2y$10$my6D0P79CJa2mf5VLlo97OZx31WYAGYE4OjMLXnQ/YPcUHo846fVO', 'wolver', 'doss', 0, '2020-12-21 22:08:29', 2020, 'M.jpg', 0, 3, 3, '', '2020-11-22'),
(5, 'test', 'test@this.me', '$2y$10$80J/UehfpdRVECPafYjCruIFSzhtsUnhK9HQO75g6UxRiRl3csyoK', 'testor', 'daniel', 1, '2020-12-24 00:00:27', 2002, 'M.jpg', 0, 5, 7, 'this is a test account', '2020-11-22'),
(6, 'aaaaa', 'a@a.a', '$2y$10$OmayunDW.f7RxocQMSud7OnkOmVJuJGur5wHbCG1WeH0BuWt25s8e', 'a', 'a', 0, '2020-12-21 22:08:29', 2020, NULL, 0, 1, 0, '', '2020-11-24'),
(8, 'hk', 'gggg@fffg.k', '$2y$10$XiMyQ0qbGeKlTeLaOqe2jOf2ZHLcIE1QMUeBgJAm.2DKUsPntWfhS', 'hungry', 'k', 0, '2020-12-21 22:08:29', 2012, NULL, 0, 0, 1, '', '2020-12-16'),
(9, 'kk', 'Kk@k.k', '$2y$10$jcu91jSMjb5rTX2MoQXFs.150d1pFyIpwqFNcSDQUPJvc9i0fDEY2', 'Kroos', 'kook', 0, '2020-12-23 00:05:34', 2020, NULL, 0, 1, 1, '', '2020-12-21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_key`
--
ALTER TABLE `auth_key`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `following`
--
ALTER TABLE `following`
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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idusers`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_key`
--
ALTER TABLE `auth_key`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `following`
--
ALTER TABLE `following`
  MODIFY `id` int(21) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `idusers` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
