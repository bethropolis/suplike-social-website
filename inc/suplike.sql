-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2020 at 03:57 PM
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

create DATABASE suplike;
use suplike; 

-- --------------------------------------------------------

--
-- Table structure for table `following`
-- 

CREATE TABLE `following` (
  `id` int(21) NOT NULL,
  `user` int(21) NOT NULL,
  `following` int(21) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `following`
--

INSERT INTO `following` (`id`, `user`, `following`) VALUES
(2, 16, 16),
(10, 16, 6),
(12, 16, 15),
(18, 16, 17),
(19, 17, 18);

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `post_id`, `user_id`) VALUES
(1, 4, 5),
(11, 85, 1),
(12, 74, 1),
(17, 76, 1),
(18, 76, 2),
(46, 95, 17),
(47, 94, 17),
(48, 93, 17),
(49, 92, 17),
(50, 91, 17),
(51, 90, 17),
(52, 89, 17),
(53, 63, 17),
(54, 87, 17),
(55, 85, 17),
(56, 64, 17),
(57, 65, 17),
(58, 68, 17),
(59, 70, 17),
(61, 88, 17),
(62, 77, 17),
(63, 75, 17),
(64, 84, 17),
(73, 95, 18),
(89, 96, 17),
(97, 97, 17),
(100, 64, 1),
(104, 98, 17),
(105, 99, 17),
(106, 101, 17),
(107, 94, 16),
(108, 74, 18),
(109, 102, 18);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `image` text DEFAULT NULL,
  `image_text` text DEFAULT NULL,
  `userid` int(11) NOT NULL,
  `date_posted` varchar(21) NOT NULL,
  `date_of_upload` text DEFAULT NULL,
  `post_likes` int(21) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `image`, `image_text`, `userid`, `date_posted`, `date_of_upload`, `post_likes`) VALUES
(74, '', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 5, '29 oct', '1603996572000.', 3),
(102, 'IMG_0061.JPG', 'you can post images too', 18, '26 nov', '1606402411000.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `idusers` int(11) NOT NULL,
  `uidusers` tinytext NOT NULL,
  `emailusers` tinytext NOT NULL,
  `pwdUsers` longtext NOT NULL,
  `usersFirstname` tinytext DEFAULT NULL,
  `usersSecondname` tinytext DEFAULT NULL,
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

INSERT INTO `users` (`idusers`, `uidusers`, `emailusers`, `pwdUsers`, `usersFirstname`, `usersSecondname`, `usersAge`, `profile_picture`, `gender`, `followers`, `following`, `bio`, `date_joined`) VALUES
(1, 'admin', 'admin@admin.a', '$2y$10$h6nWi//mnMSfbR21vtf.weR0LWQaKYICx17P9XnU3cf77Hc.ugFdy', 'admin', 'admin', NULL, NULL, 0, 1, 1, 'admin here', '2020-11-22'),
(2, 'yo', 'h@y.k', '$2y$10$YoDwPntbsEml3Vv1ODEfJeK/h9Zy33KSmFgKfRiykWF2omI5hQASC', 'Bethuel', 'Kipsang', 2004, NULL, 0, 0, 0, '', '2020-11-22'),
(3, 't', 'test@bethlton.ga', '$2y$10$GjbFlPqrsbBXyc3ADQqh.uBW020C4TdT.dd.FKbUvBESYcOZ3GDBC', 't', 't', 0, NULL, 0, 0, 0, '', '2020-11-22'),
(4, 'wolfy', 'wo@lf.y', '$2y$10$my6D0P79CJa2mf5VLlo97OZx31WYAGYE4OjMLXnQ/YPcUHo846fVO', 'wolver', 'doss', 2020, 'M.jpg', 0, 2, 1, '', '2020-11-22'),
(5, 'test', 'test@this.me', '$2y$10$80J/UehfpdRVECPafYjCruIFSzhtsUnhK9HQO75g6UxRiRl3csyoK', 'testor', 'daniel', 2002, 'M.jpg', 0, 1, 0, '', '2020-11-22'),
(6, 'aaaaa', 'a@a.a', '$2y$10$OmayunDW.f7RxocQMSud7OnkOmVJuJGur5wHbCG1WeH0BuWt25s8e', 'a', 'a', 2020, NULL, 0, 0, 0, '', '2020-11-24');
 
-- 
-- Indexes for dumped tables
--

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idusers`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `following`
--
ALTER TABLE `following`
  MODIFY `id` int(21) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `idusers` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
