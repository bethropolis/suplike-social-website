-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2022 at 12:28 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

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
CREATE DATABASE IF NOT EXISTS `suplike` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `suplike`;

-- --------------------------------------------------------

--
-- Table structure for table `api`
--

CREATE TABLE IF NOT EXISTS `api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `key` varchar(128) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `auth_key`
--

CREATE TABLE IF NOT EXISTS `auth_key` (
  `id` int(116) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(256) NOT NULL,
  `user` int(11) NOT NULL,
  `last_access` datetime NOT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp(),
  `token` varchar(256) DEFAULT NULL,
  `user_auth` varchar(256) DEFAULT NULL,
  `chat_auth` varchar(256) DEFAULT NULL,
  `browser_auth` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='still in beta';

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'txt',
  `message` text CHARACTER SET utf8mb4 DEFAULT NULL,
  `who_from` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `who_to` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `following`
--

CREATE TABLE IF NOT EXISTS `following` (
  `id` int(21) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `following` int(21) NOT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `following_ibfk_1` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `notify`
--

CREATE TABLE IF NOT EXISTS `notify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_id` varchar(8) NOT NULL,
  `user` int(11) NOT NULL,
  `text` longtext NOT NULL,
  `type` varchar(5) NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `NOTIFY` (`notification_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_id` varchar(8) NOT NULL,
  `name` varchar(32) NOT NULL,
  `type` int(32) NOT NULL,
  `host` int(32) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` text NOT NULL,
  `port` int(8) NOT NULL,
  `counter` int(32) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDENTIFY` (`name_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `share`
--

CREATE TABLE IF NOT EXISTS `share` (
  `id` int(128) NOT NULL AUTO_INCREMENT,
  `user` int(21) NOT NULL,
  `time` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `date_joined` datetime NOT NULL DEFAULT current_timestamp(),
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idusers`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;