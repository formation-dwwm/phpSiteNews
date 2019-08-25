-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3305
-- Généré le :  Dim 25 août 2019 à 15:05
-- Version du serveur :  5.7.26
-- Version de PHP :  7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `phpauth`
--

-- --------------------------------------------------------

--
-- Structure de la table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE IF NOT EXISTS `accounts` (
  `account_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_name` varchar(254) NOT NULL,
  `account_password` varchar(254) NOT NULL,
  `account_enabled` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `account_expiry` date NOT NULL DEFAULT '1999-01-01',
  `account_email` varchar(50) DEFAULT NULL,
  `account_token` varchar(255) DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `confirmation_token` enum('false','true') NOT NULL DEFAULT 'false',
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `account_name` (`account_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `accounts`
--

INSERT INTO `accounts` (`account_id`, `account_name`, `account_password`, `account_enabled`, `account_expiry`, `account_email`, `account_token`, `confirmed_at`, `confirmation_token`) VALUES
(1, 'administrateur', '$2y$10$iqPIr3yThFr/YM3grV2G3OWWCZl3L1UrLF39I3rXPz8HeR2JYQVuW', 1, '1999-01-01', 'admin@gmail.com', NULL, '2019-08-25 14:18:49', 'true'),
(3, 'cursusdev', '$2y$10$wXOh8yPiNtq/XhbCV0WR0uWKuOR6PgRffxlmum5jwz60BMx7fdyO6', 1, '1999-01-01', 'cursusdev@gmail.com', NULL, '2019-08-25 14:55:42', 'true');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_account_id` int(10) UNSIGNED NOT NULL,
  `session_cookie` char(32) NOT NULL,
  `session_start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`session_id`),
  UNIQUE KEY `session_cookie` (`session_cookie`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
