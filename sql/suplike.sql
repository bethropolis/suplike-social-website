-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2021 at 09:16 PM
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

CREATE TABLE IF NOT EXISTS `auth_key` (
  `id` int(116) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(256) NOT NULL,
  `user` int(116) NOT NULL,
  `last_access` datetime NOT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp(),
  `token` varchar(256) DEFAULT NULL,
  `user_auth` varchar(256) DEFAULT NULL,
  `chat_auth` varchar(256) DEFAULT NULL,
  `browser_auth` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COMMENT='still in beta';

--
-- Dumping data for table `auth_key`
--

INSERT INTO `auth_key` (`id`, `api_key`, `user`, `last_access`, `time`, `token`, `user_auth`, `chat_auth`, `browser_auth`) VALUES
(3, '988c955faa0218d3a8676ccb3fca5704c3bf14573097d5092898c81528f1e4b1', 1, '0000-00-00 00:00:00', '2021-07-21 07:31:06', 'b192d457dec4d7c40b75894b879dcdd8150d22e9da', 'b3f0828106b261e1213744689b45', 'd8b8968375a70378', '13083c853babc5ccd0daa98f58e40263'),
(4, 'a44b48e15d594656fa83e6a4c90f74324d24146a5a17ca665453c3b481aac13b', 2, '0000-00-00 00:00:00', '2021-07-21 07:31:06', '9fb249870c5b9ccb80a3da7dcf9d84b3f0b8673289', '580f85ea120b377d249d70de4462', '24dad1a75688c67b', 'b4f59126c26a337d683327019a55c98c'),
(5, '4145100364af1cff0996637153670c41c7cdaa77d21513981e6b80d6c9596d8f', 3, '0000-00-00 00:00:00', '2021-07-21 07:31:06', 'be0efc12df9944569f2c1bec48ff96a9b9c0eb3190', '5d136e3b63505a54ee1285d154d6', 'b77ca5d5b594302e', 'a573829d8d61a8f7c0df1e9509e6fcf2'),
(6, '01cb8afbea495a83fc50687ea9e4234849160547cc07b14080611abced00ae99', 4, '0000-00-00 00:00:00', '2021-07-21 07:31:06', 'a1b22e5b430dd3cda5fa94857d3fa3ede7f4d19280', 'c8ba4f30378819e6c5210acc7fab', '780d1324e303699b', 'ce79f962962380efa4e9778f986f26fd'),
(7, 'cc84e6bac4bad5b1bfa003aad1aabe4fbdcf45aab07478b0308657e912b9a8b1', 5, '0000-00-00 00:00:00', '2021-07-21 07:31:06', '7e60342d67d0931c8931775ec064d902b24eeaef2b', '2af09f3fa42e57e9ebe6947ae1b7', '0a7f379742b37c13', '6058d6e957d148df122caea5228eb716'),
(8, '26c7f577df41366532af78f863c8756856654a2a551c59c5233e9d539eb8f0fb', 6, '0000-00-00 00:00:00', '2021-07-21 07:31:06', 'd7410b2d8b608a88dca832b2d81105bce37685738f', 'ba79fdcdd0b037cb5c42e501b0a0', 'f2af5681e1940d55', '2bc77e3bf5dd9f06d56cc10e492c5318'),
(9, '8c3f69c70095138da5a8e9236f448c1ffe903f50fdf39d385eea42dfc5daebc4', 8, '0000-00-00 00:00:00', '2021-07-21 07:31:06', 'e122c235775e21339d033ac7e500ba95e9a15dd687', '09d13ba773a9f1632f7f7f4dceae', 'ed320ac4b7e52773', 'c77b5b4817c751791313dbcab19c6fa5'),
(10, '943b36401b148416c706a8f5c0e3c9c963daada9013312cbbd34f01c1fa03eef', 9, '0000-00-00 00:00:00', '2021-07-21 07:31:06', '9b8fa3f3efad51c7fcdd9332c870d16f0fae89aa21', 'a5677c3921322f1742b9840785bf', '82f844ee47589c49', '720d9daf8f07ec3d0ab8c69c9d2b1348'),
(36, '3f0642cae9f85d9524220c35e233dba8f646bb66b96fe9313b41f8d649cd571f', 11, '0000-00-00 00:00:00', '2021-07-25 00:44:45', '3051fd0aff14096c0ba0ba0fde14dd75303425ff0b', 'c601495a33350b16434f292b8791', 'eb5e83d38c2c5b26', 'ab0591c200fcb1c76e388553001c9387');

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text CHARACTER SET utf8mb4 DEFAULT NULL,
  `who_from` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `who_to` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` varchar(126) NOT NULL,
  `user` tinytext NOT NULL,
  `user_token` varchar(126) NOT NULL,
  `comment` longtext NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `following`
--

CREATE TABLE IF NOT EXISTS `following` (
  `id` int(21) NOT NULL AUTO_INCREMENT,
  `user` int(21) NOT NULL,
  `following` int(21) NOT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `following`
--

INSERT INTO `following` (`id`, `user`, `following`, `time`) VALUES
(2, 5, 6, '2021-07-21 07:52:17'),
(3, 1, 5, '2021-07-21 08:38:27'),
(6, 5, 1, '2021-07-23 11:23:25'),
(13, 5, 9, '2021-07-23 12:43:19'),
(14, 6, 5, '2021-07-25 14:39:58');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE IF NOT EXISTS `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` varchar(126) NOT NULL,
  `image` text DEFAULT NULL,
  `image_text` text DEFAULT NULL,
  `userid` int(11) NOT NULL,
  `type` varchar(21) NOT NULL DEFAULT 'txt',
  `repost` varchar(116) NOT NULL,
  `date_posted` text NOT NULL,
  `post_likes` int(21) NOT NULL DEFAULT 0,
  `day` varchar(12) DEFAULT NULL,
  `time` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `post_id`, `image`, `image_text`, `userid`, `type`, `repost`, `date_posted`, `post_likes`, `day`, `time`) VALUES
(5, 'b62224be', NULL, 'hey', 5, 'txt', '', '23 Jul', 1, 'Friday', '2021-07-23 10:31:18'),
(6, '9a72b6e8', NULL, 'yolo', 5, 'txt', '', '23 Jul', 1, 'Friday', '2021-07-23 20:01:27'),
(9, 'fe5cc041', NULL, 'hey', 1, 'txt', 'b62224be', '25 Jul', 1, 'Sunday', '2021-07-25 15:46:41'),
(12, '215fedd7', NULL, 'yello', 1, 'txt', '', '25 Jul', 1, 'Sunday', '2021-07-25 17:15:25'),
(13, '25a0f752', NULL, 'yolo', 1, 'txt', '9a72b6e8', '25 Jul', 1, 'Sunday', '2021-07-25 17:40:01');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `is_comment` tinyint(1) NOT NULL DEFAULT 0,
  `delt` tinyint(1) NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `idusers` int(11) NOT NULL AUTO_INCREMENT,
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
  `date_joined` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idusers`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`idusers`, `uidusers`, `emailusers`, `pwdUsers`, `usersFirstname`, `usersSecondname`, `isAdmin`, `last_online`, `usersAge`, `profile_picture`, `gender`, `followers`, `following`, `bio`, `date_joined`) VALUES
(1, 'admin', 'admin@admin.a', '$2y$10$OctA/Gm2pPCqamBhn0zmx.ko3V8p2v.RMI.J5ltn2h5Cqekljh72O', 'admin', 'admin', 1, '2021-07-21 08:24:31', NULL, NULL, 0, 6, 3, 'admin here', '2020-11-22'),
(2, 'yo', 'h@y.k', '$2y$10$YoDwPntbsEml3Vv1ODEfJeK/h9Zy33KSmFgKfRiykWF2omI5hQASC', 'Bethuel', 'Kipsang', 0, '2020-12-21 22:08:29', 2004, NULL, 0, 3, 1, '', '2020-11-22'),
(4, 'wolfy', 'wo@lf.y', '$2y$10$my6D0P79CJa2mf5VLlo97OZx31WYAGYE4OjMLXnQ/YPcUHo846fVO', 'wolver', 'doss', 0, '2020-12-21 22:08:29', 2020, 'M.jpg', 0, 3, 3, '', '2020-11-22'),
(5, 'test', 'test@this.me', '$2y$10$80J/UehfpdRVECPafYjCruIFSzhtsUnhK9HQO75g6UxRiRl3csyoK', 'testor', 'daniel', 1, '2020-12-24 00:00:27', 2002, 'image-4.png', 0, 9, 10, 'this is a test account', '2020-11-22'),
(6, 'aaaaa', 'a@a.a', '$2y$10$OmayunDW.f7RxocQMSud7OnkOmVJuJGur5wHbCG1WeH0BuWt25s8e', 'a', 'a', 0, '2020-12-21 22:08:29', 2020, NULL, 0, 3, 1, '', '2020-11-24'),
(8, 'hk', 'gggg@fffg.k', '$2y$10$XiMyQ0qbGeKlTeLaOqe2jOf2ZHLcIE1QMUeBgJAm.2DKUsPntWfhS', 'hungry', 'k', 0, '2020-12-21 22:08:29', 2012, NULL, 0, 0, 1, '', '2020-12-16'),
(9, 'kk', 'Kk@k.k', '$2y$10$jcu91jSMjb5rTX2MoQXFs.150d1pFyIpwqFNcSDQUPJvc9i0fDEY2', 'Kroos', 'kook', 0, '2020-12-23 00:05:34', 2020, NULL, 0, 2, 1, '', '2020-12-21'),
(11, 'u', 'u@u.u', '$2y$10$W3ZNbJYkZYtTqHZTLdJoa.diVreIq3RIojTwhVHPtmUAFmi1La2gG', 'u', 'u', 0, '2021-07-25 00:44:45', 2021, NULL, 0, 0, 0, '', '2021-07-25');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
