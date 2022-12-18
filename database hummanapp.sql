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

-- Dumping structure for table humanapp.invoice
CREATE TABLE IF NOT EXISTS `invoice` (
  `id_invoice` int NOT NULL AUTO_INCREMENT,
  `id_line` int NOT NULL,
  `pu_sale` float NOT NULL,
  `amount_sale` float NOT NULL,
  `id_stock` int NOT NULL,
  `deleted` int DEFAULT NULL,
  PRIMARY KEY (`id_invoice`),
  KEY `stock_fk_1` (`id_stock`),
  KEY `line_fk_2` (`id_line`),
  CONSTRAINT `line_fk_2` FOREIGN KEY (`id_line`) REFERENCES `lines_invoice` (`id_line`),
  CONSTRAINT `stock_fk_1` FOREIGN KEY (`id_stock`) REFERENCES `stock_merchandise` (`id_stock`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf32;

-- Dumping data for table humanapp.invoice: ~6 rows (approximately)
INSERT INTO `invoice` (`id_invoice`, `id_line`, `pu_sale`, `amount_sale`, `id_stock`, `deleted`) VALUES
	(26, 71, 3, 3, 8, 0),
	(27, 72, 12, 2, 8, 0),
	(28, 73, 4, 4, 10, 0),
	(29, 73, 3, 2, 8, 0),
	(30, 74, 3, 2, 10, 0),
	(31, 74, 3, 2, 8, 0);

-- Dumping structure for table humanapp.lines_invoice
CREATE TABLE IF NOT EXISTS `lines_invoice` (
  `id_line` int NOT NULL AUTO_INCREMENT,
  `client` text,
  `decrease` int NOT NULL,
  `deleted` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_line`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf32;

-- Dumping data for table humanapp.lines_invoice: ~4 rows (approximately)
INSERT INTO `lines_invoice` (`id_line`, `client`, `decrease`, `deleted`, `created_at`) VALUES
	(71, 'Gerard', 1, 0, '2022-12-10 21:49:07'),
	(72, 'ado', 5, 0, '2022-12-16 15:58:56'),
	(73, 'sylvie', 1, 0, '2022-12-16 16:12:07'),
	(74, 'Neok', 1, 0, '2022-12-18 01:06:04');

-- Dumping structure for table humanapp.merchandise
CREATE TABLE IF NOT EXISTS `merchandise` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `categorie` text NOT NULL,
  `description` longtext NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `deleted` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf32;

-- Dumping data for table humanapp.merchandise: ~4 rows (approximately)
INSERT INTO `merchandise` (`id`, `title`, `categorie`, `description`, `created_at`, `deleted`) VALUES
	(16, 'Matelas xxl', 'Matelas', 'Pour dormir', '2022-12-15 00:00:00', 0),
	(17, 'nutela', 'Autres', 'pour le petit dejeuner', '2022-12-16 00:00:00', 0),
	(18, 'Riz', 'Autres', 'Haricot', '2022-12-16 00:00:00', 0),
	(19, 'Soulier', 'Autres', 'pour le soin des pieds', '2022-12-16 00:00:00', 0);

-- Dumping structure for table humanapp.oauth_access_tokens
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `access_token` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(80) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(4000) DEFAULT NULL,
  PRIMARY KEY (`access_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table humanapp.oauth_access_tokens: ~11 rows (approximately)
INSERT INTO `oauth_access_tokens` (`access_token`, `client_id`, `user_id`, `expires`, `scope`) VALUES
	('074be5a4355f28d3499a54778eaed64b0fd8d303', 'claude@gmail.com', '8', '2022-12-17 16:14:38', 'app'),
	('373610a66f058fe8364487537c1f57e3525e1069', 'murhchristian@gmail.com', '2', '2022-12-17 04:24:41', 'admin'),
	('4067a517930962834ca7dbcc72049b8181e0ee51', 'murhchristian@gmail.com', '2', '2022-12-16 03:01:56', 'christian'),
	('5599b190b9d58314cb8a892aed8845f663951a0b', 'murhchristian@gmail.com', '2', '2022-12-17 08:45:37', 'admin'),
	('6ca841b2cf792d6ac57682c9f054556b58bc1ed5', 'murhchristian@gmail.com', '2', '2022-12-16 03:03:10', 'admin'),
	('6dd2994e9c2be3464204117b15f62e97a0694a2a', 'murhchristian@gmail.com', '3', '2022-12-15 01:17:21', 'admin'),
	('93438e5d5f0ab00df7ebe042149e618857c0e233', 'murhchristian@gmail.com', '3', '2022-12-15 03:13:04', 'admin'),
	('b61d72bfafcd8372a7fd195a825e1c64d0900d0f', 'murhchristian@gmail.com', '3', '2022-12-15 07:06:49', 'admin'),
	('bf39e6ad1610bca7cce51862fe05b3b5ee2467fc', 'murhchristian@gmail.com', '3', '2022-12-16 02:51:00', 'admin'),
	('d72a76a3e34e2b6b4a26cfaca69d6987a374ffb4', 'murhchristian@gmail.com', '3', '2022-12-15 01:16:56', 'app'),
	('e12409718d0d6e2e6c8f675b528d4d1cc756f1fd', 'murhchristian@gmail.com', '3', '2022-12-15 01:22:46', 'admin');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table humanapp.oauth_clients: ~8 rows (approximately)
INSERT INTO `oauth_clients` (`client_id`, `client_secret`, `redirect_uri`, `grant_types`, `scope`, `user_id`) VALUES
	('amani@gmail.com', 'MurhulaMurhula', '', 'password', 'app', '4'),
	('claude@gmail.com', 'claudeclaude', '', 'password', 'agent', '8'),
	('emerance@gmail.com', 'emeranceemerance', '', 'password', 'agent', '7'),
	('etienne@gmail.com', 'cbvcvxcxc', '', 'password', 'agent', '6'),
	('hchristian@gmail.com', 'hchristian', '', 'password', 'agent', '10'),
	('murhchristian@gmail.com', 'chriisnadnd', '', 'password', 'agent', '10'),
	('nzigire@gmail.com', 'nzigirenzigire', '', 'password', 'agent', '5'),
	('testclient', 'testsecret', NULL, 'password', 'app', NULL);

-- Dumping structure for table humanapp.oauth_jwt
CREATE TABLE IF NOT EXISTS `oauth_jwt` (
  `client_id` varchar(80) NOT NULL,
  `subject` varchar(80) DEFAULT NULL,
  `public_key` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table humanapp.oauth_jwt: ~0 rows (approximately)

-- Dumping structure for table humanapp.oauth_refresh_tokens
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `refresh_token` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(80) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `scope` varchar(4000) DEFAULT NULL,
  PRIMARY KEY (`refresh_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table humanapp.oauth_refresh_tokens: ~11 rows (approximately)
INSERT INTO `oauth_refresh_tokens` (`refresh_token`, `client_id`, `user_id`, `expires`, `scope`) VALUES
	('07a9e3c2eaf8bf59a0f597e514ba63588169c50f', 'murhchristian@gmail.com', '3', '2022-12-29 02:13:04', 'admin'),
	('0b7b2dba7166c7fd10d5a30060c5d69aafc0f904', 'murhchristian@gmail.com', '3', '2022-12-30 01:51:00', 'admin'),
	('10942600d55e49857f13cf8c4de3d931c5688fb2', 'claude@gmail.com', '8', '2022-12-31 15:14:38', 'app'),
	('22e9cba4fdee483fe7f3e63cfdfa52331e3569a1', 'murhchristian@gmail.com', '3', '2022-12-29 06:06:49', 'admin'),
	('24b4f02d05e411cf679c0a2687a56f5fdaff6430', 'murhchristian@gmail.com', '2', '2022-12-31 07:45:37', 'admin'),
	('38692b0a92c751fca1dc1a91290ec5ea02b6ea07', 'murhchristian@gmail.com', '3', '2022-12-29 00:22:46', 'admin'),
	('41b641cbf6abefd74e4fce430d7a85785b62dd89', 'murhchristian@gmail.com', '2', '2022-12-30 02:03:10', 'admin'),
	('49ea0265baf0e283f8a72af00bf136f694284d03', 'murhchristian@gmail.com', '3', '2022-12-29 00:17:21', 'admin'),
	('c7d91f8d395da09bb966ff79dd03f2e36f96a158', 'murhchristian@gmail.com', '2', '2022-12-30 02:01:56', 'christian'),
	('e27997afa202034742fce6b536dfd7a98c694f06', 'murhchristian@gmail.com', '2', '2022-12-31 03:24:41', 'admin'),
	('f3ac69a3104943e9bdb48b2086c6c84b93ed5c92', 'murhchristian@gmail.com', '3', '2022-12-29 00:16:56', 'app');

-- Dumping structure for table humanapp.oauth_scopes
CREATE TABLE IF NOT EXISTS `oauth_scopes` (
  `scope` varchar(80) NOT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`scope`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table humanapp.oauth_users: ~9 rows (approximately)
INSERT INTO `oauth_users` (`username`, `password`, `first_name`, `last_name`, `email`, `email_verified`, `scope`) VALUES
	('10', 'chriisnadnd', 'Christian', 'Murhula', 'murhchristian@gmail.com', 1, 'agent'),
	('2', 'christian', 'ado', 'murhula', 'murhchristian@gmail.com', 1, 'admin'),
	('4', 'MurhulaMurhula', 'Christian', 'Murhula', 'amani@gmail.com', 1, '-'),
	('5', 'nzigirenzigire', 'Christian', 'Murhula', 'nzigire@gmail.com', 1, 'agent'),
	('6', 'cbvcvxcxc', 'Christian', 'Murhula', 'etienne@gmail.com', 1, 'agent'),
	('7', 'emeranceemerance', 'emerance', 'm\'mwa', 'emerance@gmail.com', 1, 'agent'),
	('8', 'claudeclaude', 'claude', 'zibera', 'claude@gmail.com', 1, 'agent'),
	('alexlancer', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Alex', 'Lancer', 'test@alexlancer.com', 1, 'app'),
	('christian', 'christian', 'murhula', 'byabushi', 'murhchristian@gmail.com', 1, 'admin');

-- Dumping structure for table humanapp.servicemessage
CREATE TABLE IF NOT EXISTS `servicemessage` (
  `idService` int NOT NULL AUTO_INCREMENT,
  `message` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `writer` int DEFAULT NULL,
  `receiver` int DEFAULT NULL,
  `kind` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idService`),
  KEY `fk_userwriter` (`writer`),
  KEY `fk_userreceiver` (`receiver`),
  CONSTRAINT `servicemessage_ibfk_1` FOREIGN KEY (`writer`) REFERENCES `users` (`id`),
  CONSTRAINT `servicemessage_ibfk_2` FOREIGN KEY (`receiver`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table humanapp.servicemessage: ~2 rows (approximately)
INSERT INTO `servicemessage` (`idService`, `message`, `created_at`, `writer`, `receiver`, `kind`) VALUES
	(26, 'Les quantités se sont déjà épuisés', '2022-12-17 01:11:09', 10, 2, 0),
	(27, 'Ile ma magari zenye mulileta leo, zikona matundu, uzirudia fournisseur', '2022-12-17 01:14:01', 10, 3, 0);

-- Dumping structure for table humanapp.stock_merchandise
CREATE TABLE IF NOT EXISTS `stock_merchandise` (
  `id_stock` int NOT NULL AUTO_INCREMENT,
  `id_merchandise` int NOT NULL,
  `description` text NOT NULL,
  `pu_by` float NOT NULL,
  `amount_by` float NOT NULL,
  `decrease` float DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `deleted` int DEFAULT NULL,
  `saler` text,
  PRIMARY KEY (`id_stock`),
  KEY `id_merchandise` (`id_merchandise`),
  CONSTRAINT `stock_merchandise_ibfk_1` FOREIGN KEY (`id_merchandise`) REFERENCES `merchandise` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf32;

-- Dumping data for table humanapp.stock_merchandise: ~3 rows (approximately)
INSERT INTO `stock_merchandise` (`id_stock`, `id_merchandise`, `description`, `pu_by`, `amount_by`, `decrease`, `created_at`, `deleted`, `saler`) VALUES
	(8, 16, 'Achat de la semaine du 22', 12, 34, 5, '2022-12-15 00:00:00', 0, 'Dubai'),
	(9, 16, 'depuis dubai', 12, 2, 5, '2022-12-16 00:00:00', 1, 'Dubai'),
	(10, 17, 'vfcfxdfxdxd', 6, 7, 1, '2022-12-16 00:00:00', 0, 'Dodoma');

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table humanapp.users: ~9 rows (approximately)
INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `scope`, `created_at`) VALUES
	(1, 'Alex', 'Lancer', 'test@alexlancer.com', '$2y$10$FKefMDOiw5PhFVMK1djqseHZlKwl5GwN/muVv/lnqEmPFapY9rBd6', 'app', '2020-04-20 01:49:05'),
	(2, 'ado', 'murhula', 'amani@gmail.com', '$2y$10$l/dyVMSeDFQgHxQMPsAj..R9E9FAmsdpmMhZx7shAHQjRbYxfD/9m', 'agent', '2022-09-28 15:15:13'),
	(3, 'christian', 'murhula', 'murhchristian@gmail.com', '$2y$10$efOR1w8qLwoGJIhBwWeiweJHYYzNQF08imPs5O/AL4taIWsqGKFEq', 'admin', '2022-09-28 18:47:38'),
	(5, 'Christian', 'Murhula', 'nzigire@gmail.com', '$2y$10$heDxr3QZQUX4p6/r/8DU3.YcK7aqFDfVUrRKIbG10BtuOJq0mJeaK', 'app', '2022-12-17 23:34:43'),
	(6, 'Christian', 'Murhula', 'etienne@gmail.com', '$2y$10$QhrPjD5bk02rhtFVNXrNMOaXLXIkdpcEa5eKX3q8wEBr.KrNiHRgO', 'app', '2022-12-17 23:58:31'),
	(7, 'emerance', 'm\'mwa', 'emerance@gmail.com', '$2y$10$5MT.uwWVW.TK6wlnBn9yT.RDQa9/HSuvA0arwstNHKaMN6GVU2yeu', 'app', '2022-12-18 00:03:44'),
	(8, 'claude', 'zibera', 'claude@gmail.com', '$2y$10$69QxOzErTUBYHxBRNqUzcONgRUpi2vtcuhOdXiNa2mfCauw9DJpW6', 'admin', '2022-12-18 00:09:10'),
	(9, 'Christian', 'Murhula', 'rhchristian@gmail.com', '$2y$10$./bYZqTVuYZBIjDGVor/vua3rQo9hiffJNp5r3bVWEngeTuWWAIPG', 'app', '2022-12-18 00:18:35'),
	(10, 'Christian', 'Murhula', 'murhchristian@gmail.com', '$2y$10$A2qIABX1miSCg67j96jh4.9O9Y60OefYLRE6iP7tY6h8bxTq2UL5G', 'agent', '2022-12-18 00:21:04');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
