SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE IF NOT EXISTS `api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `key` varchar(128) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='still in beta';

CREATE TABLE IF NOT EXISTS `bots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bot_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL COMMENT 'creator',
  `webhook` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bot_uid` (`bot_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `who_from` varchar(128) NOT NULL,
  `who_to` varchar(128) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` varchar(6) NOT NULL DEFAULT 'txt',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` varchar(126) NOT NULL,
  `user` tinytext NOT NULL,
  `comment` longtext NOT NULL,
  `likes` int(15) NOT NULL DEFAULT 0,
  `parent_id` int(11) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `following` (
  `id` int(21) NOT NULL AUTO_INCREMENT,
  `user` int(21) NOT NULL,
  `following` int(21) NOT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `following_ibfk_1` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `password_reset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` tinytext NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` varchar(116) NOT NULL,
  `repost` varchar(116) NOT NULL,
  `image` text DEFAULT NULL,
  `image_text` text DEFAULT NULL,
  `userid` int(11) NOT NULL,
  `type` varchar(21) NOT NULL DEFAULT 'txt',
  `date_posted` text NOT NULL,
  `post_likes` int(21) NOT NULL DEFAULT 0,
  `day` varchar(12) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `time` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `post_tags` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`post_id`,`tag_id`),
  KEY `post_tags_ibfk_2` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `is_comment` tinyint(1) NOT NULL DEFAULT 0,
  `delt` tinyint(1) NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `share` (
  `id` int(128) NOT NULL AUTO_INCREMENT,
  `user` int(21) NOT NULL,
  `time` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `stories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` varchar(10) NOT NULL,
  `userid` int(21) NOT NULL,
  `type` varchar(5) NOT NULL DEFAULT 'text',
  `text` varchar(200) NOT NULL,
  `image` tinytext NOT NULL,
  `time_created` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `idusers` int(11) NOT NULL AUTO_INCREMENT,
  `uidusers` varchar(21) NOT NULL,
  `emailusers` tinytext NOT NULL,
  `pwdUsers` varchar(255) NOT NULL,
  `usersFirstname` tinytext DEFAULT '',
  `usersSecondname` tinytext DEFAULT '',
  `isAdmin` tinyint(1) NOT NULL DEFAULT 0,
  `isBot` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified` tinyint(1) DEFAULT 0,
  `page_visit` int(21) NOT NULL DEFAULT 0,
  `last_online` datetime NOT NULL DEFAULT current_timestamp(),
  `usersAge` int(11) DEFAULT NULL,
  `profile_picture` tinytext DEFAULT NULL,
  `gender` text DEFAULT 'M',
  `followers` int(11) NOT NULL,
  `following` int(11) NOT NULL,
  `bio` longtext NOT NULL,
  `status` varchar(12) NOT NULL DEFAULT 'active',
  `date_joined` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idusers`),
  UNIQUE KEY `uidusers` (`uidusers`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `bots`
  ADD CONSTRAINT `bots_ibfk_1` FOREIGN KEY (`bot_id`) REFERENCES `users` (`idusers`);

ALTER TABLE `following`
  ADD CONSTRAINT `following_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`idusers`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `post_tags`
  ADD CONSTRAINT `post_tags_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
