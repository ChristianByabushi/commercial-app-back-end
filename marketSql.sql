-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for humanapp
CREATE DATABASE IF NOT EXISTS `humanapp` /*!40100 DEFAULT CHARACTER SET utf32 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `humanapp`;

-- Dumping structure for table humanapp.blog
CREATE TABLE IF NOT EXISTS `blog` (
  `post_id` int NOT NULL AUTO_INCREMENT,
  `post_title` varchar(255) NOT NULL,
  `post_description` text NOT NULL,
  `post_featured_image` varchar(255) NOT NULL,
  `post_created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 ;

-- Dumping structure for table humanapp.invoice
CREATE TABLE IF NOT EXISTS `invoice` (
  `id_invoice` int NOT NULL AUTO_INCREMENT,
  `id_line` int NOT NULL,
  `pu_sale` float NOT NULL,
  `amount_sale` float NOT NULL,
  `id_stock` int NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_invoice`),
  KEY `stock_fk_1` (`id_stock`),
  KEY `line_fk_2` (`id_line`),
  CONSTRAINT `line_fk_2` FOREIGN KEY (`id_line`) REFERENCES `lines_invoice` (`id_line`),
  CONSTRAINT `stock_fk_1` FOREIGN KEY (`id_stock`) REFERENCES `stock_merchandise` (`id_stock`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf32;

-- Dumping data for table humanapp.invoice: ~0 rows (approximately)

-- Dumping structure for table humanapp.lines_invoice
CREATE TABLE IF NOT EXISTS `lines_invoice` (
  `id_line` int NOT NULL AUTO_INCREMENT,
  `client` text,
  `decrease` int NOT NULL,
  `deleted` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_line`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf32;

-- Dumping data for table humanapp.lines_invoice: ~0 rows (approximately)

-- Dumping structure for table humanapp.merchandise
CREATE TABLE IF NOT EXISTS `merchandise` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `categorie` text NOT NULL,
  `description` longtext NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf32;

-- Dumping data for table humanapp.merchandise: ~0 rows (approximately)

-- Dumping structure for table humanapp.oauth_access_tokens
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `access_token` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(80) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(4000) DEFAULT NULL,
  PRIMARY KEY (`access_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Dumping data for table humanapp.oauth_access_tokens: ~0 rows (approximately)

-- Dumping structure for table humanapp.oauth_authorization_codes
CREATE TABLE IF NOT EXISTS `oauth_authorization_codes` (
  `authorization_code` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(80) DEFAULT NULL,
  `redirect_uri` varchar(2000) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(4000) DEFAULT NULL,
  `id_token` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`authorization_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Dumping data for table humanapp.oauth_authorization_codes: ~0 rows (approximately)

-- Dumping structure for table humanapp.oauth_clients
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `client_id` varchar(80) NOT NULL,
  `client_secret` varchar(80) DEFAULT NULL,
  `redirect_uri` varchar(2000) DEFAULT NULL,
  `grant_types` varchar(80) DEFAULT NULL,
  `scope` varchar(4000) DEFAULT NULL,
  `user_id` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Dumping data for table humanapp.oauth_clients: ~1 rows (approximately)
INSERT INTO `oauth_clients` (`client_id`, `client_secret`, `redirect_uri`, `grant_types`, `scope`, `user_id`) VALUES
	('murhchristian@gmail.com', 'christian', '', 'password', '', ''),
	('testclient', 'testsecret', NULL, 'password', 'app', NULL);

-- Dumping structure for table humanapp.oauth_jwt
CREATE TABLE IF NOT EXISTS `oauth_jwt` (
  `client_id` varchar(80) NOT NULL,
  `subject` varchar(80) DEFAULT NULL,
  `public_key` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Dumping data for table humanapp.oauth_jwt: ~0 rows (approximately)

-- Dumping structure for table humanapp.oauth_refresh_tokens
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `refresh_token` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(80) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(4000) DEFAULT NULL,
  PRIMARY KEY (`refresh_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Dumping data for table humanapp.oauth_refresh_tokens: ~0 rows (approximately)

-- Dumping structure for table humanapp.oauth_scopes
CREATE TABLE IF NOT EXISTS `oauth_scopes` (
  `scope` varchar(80) NOT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`scope`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Dumping data for table humanapp.oauth_scopes: ~0 rows (approximately)

-- Dumping structure for table humanapp.oauth_users
CREATE TABLE IF NOT EXISTS `oauth_users` (
  `username` varchar(80) NOT NULL,
  `password` varchar(80) DEFAULT NULL,
  `first_name` varchar(80) DEFAULT NULL,
  `last_name` varchar(80) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT NULL,
  `scope` varchar(4000) DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Dumping data for table humanapp.oauth_users: ~1 rows (approximately)
INSERT INTO `oauth_users` (`username`, `password`, `first_name`, `last_name`, `email`, `email_verified`, `scope`) VALUES
	('alexlancer', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Alex', 'Lancer', 'test@alexlancer.com', 1, 'app');

-- Dumping structure for table humanapp.stock_merchandise
CREATE TABLE IF NOT EXISTS `stock_merchandise` (
  `id_stock` int NOT NULL AUTO_INCREMENT,
  `id_merchandise` int NOT NULL,
  `description` text NOT NULL,
  `pu_by` float NOT NULL,
  `amount_by` float NOT NULL,
  `decrease` float DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `deleted` int NOT NULL DEFAULT '0',
  `saler` text,
  PRIMARY KEY (`id_stock`),
  KEY `id_merchandise` (`id_merchandise`),
  CONSTRAINT `stock_merchandise_ibfk_1` FOREIGN KEY (`id_merchandise`) REFERENCES `merchandise` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf32;

-- Dumping data for table humanapp.stock_merchandise: ~0 rows (approximately)

-- Dumping structure for table humanapp.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `scope` varchar(20) NOT NULL DEFAULT 'app',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ;

-- Dumping data for table humanapp.users: ~0 rows (approximately)
INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `scope`, `created_at`) VALUES
	(1, 'Alex', 'Lancer', 'test@alexlancer.com', '$2y$10$FKefMDOiw5PhFVMK1djqseHZlKwl5GwN/muVv/lnqEmPFapY9rBd6', 'app', '2020-04-20 01:49:05');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
