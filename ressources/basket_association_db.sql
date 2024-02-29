-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 29 fév. 2024 à 13:05
-- Version du serveur : 8.3.0
-- Version de PHP : 8.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `basket_association_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `ba_category`
--

DROP TABLE IF EXISTS `ba_category`;
CREATE TABLE IF NOT EXISTS `ba_category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idCoach` int NOT NULL,
  `name` varchar(25) NOT NULL,
  `averageAge` int NOT NULL,
  `gender` varchar(20) NOT NULL,
  `titles` json DEFAULT NULL,
  `dateCreation` date NOT NULL,
  `story` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  PRIMARY KEY (`id`),
  KEY `ba_category_ibfk_1` (`idCoach`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ba_category`
--

INSERT INTO `ba_category` (`id`, `idCoach`, `name`, `averageAge`, `gender`, `titles`, `dateCreation`, `story`) VALUES
(2, 2, 'Junior', 14, 'Man', NULL, '2024-01-07', ''),
(3, 2, 'Cadet', 10, 'Man', NULL, '2024-01-01', ''),
(4, 2, 'Benjamin', 5, 'Man', NULL, '2024-01-01', '');

-- --------------------------------------------------------

--
-- Structure de la table `ba_coach`
--

DROP TABLE IF EXISTS `ba_coach`;
CREATE TABLE IF NOT EXISTS `ba_coach` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lastName` varchar(30) NOT NULL,
  `firstName` varchar(45) NOT NULL,
  `birth` date NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `nationality` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `city` varchar(40) NOT NULL,
  `address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `postal` int NOT NULL,
  `phone` int NOT NULL,
  `email` varchar(45) NOT NULL,
  `referencesPlayers` json DEFAULT NULL,
  `teamsCoached` json DEFAULT NULL,
  `titles` json DEFAULT NULL,
  `login` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ba_coach`
--

INSERT INTO `ba_coach` (`id`, `lastName`, `firstName`, `birth`, `description`, `nationality`, `city`, `address`, `postal`, `phone`, `email`, `referencesPlayers`, `teamsCoached`, `titles`, `login`, `password`) VALUES
(2, 'landry', 'kankeu', '1999-12-05', NULL, 'peru', 'nice', '2 rue labelle', 4000, 1234567890, 'kankeulandry22@gmail.com', NULL, NULL, NULL, 'LKankeu', 'f6cfe289bbfa10e1fa917b9d1a8ef547f3373e0b8e23b16446500d7c157bb0ed'),
(7, 'willy', 'Maxime', '2000-12-31', NULL, 'USA', 'Hesley', '73  Road BO North', 98235, 623434542, 'kankeulandry22@gmail.com', NULL, NULL, NULL, 'Mwilly', 'f6cfe289bbfa10e1fa917b9d1a8ef547f3373e0b8e23b16446500d7c157bb0ed'),
(9, 'Greggs', 'Popovich', '1989-01-19', NULL, 'Germany', 'Frankfurt', '2 Rd Hitler', 78432, 623223143, 'kankeulandry22@gmail.com', NULL, NULL, NULL, 'greggs', 'azertyui'),
(10, 'Deven', 'Will', '1990-01-19', NULL, 'USA', 'Los Angeles', '34 Rd Washington', 90876, 654365342, 'kankeulandry@icloud.com', NULL, NULL, NULL, 'Wdeven', 'f6cfe289bbfa10e1fa917b9d1a8ef547f3373e0b8e23b16446500d7c157bb0ed');

-- --------------------------------------------------------

--
-- Structure de la table `ba_event`
--

DROP TABLE IF EXISTS `ba_event`;
CREATE TABLE IF NOT EXISTS `ba_event` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `idCoach` int NOT NULL,
  `idTeam` int DEFAULT NULL,
  `type` varchar(10) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `importance` varchar(10) NOT NULL,
  `datePlanned` date NOT NULL,
  `time` time DEFAULT NULL,
  `currentDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `location` varchar(40) NOT NULL,
  `description` text,
  `close` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `idCoach` (`idCoach`),
  KEY `idTeam` (`idTeam`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ba_event`
--

INSERT INTO `ba_event` (`ID`, `idCoach`, `idTeam`, `type`, `subject`, `importance`, `datePlanned`, `time`, `currentDate`, `location`, `description`, `close`) VALUES
(1, 2, NULL, 'Match', 'ABVauban', 'Hight', '2024-01-01', '10:00:00', '2023-12-30 19:14:23', '', 'Match trés important qui determinera notre montée en Nationale 1 ou pas\n!!!Courage bro...', 0),
(2, 2, NULL, 'Training', 'Cannes', 'Low', '2024-01-02', '10:02:38', '2023-12-28 21:14:08', '', 'Rencontre avec les mec de la rue pour un street', 0),
(7, 2, NULL, 'Other', 'Voyage Marseille', 'Hight', '2023-12-31', '10:30:38', '2023-12-28 21:33:51', '', 'Ne ndem pas!Le trafic sera très pertubé!', 0),
(8, 2, NULL, 'Other', 'Meeting avec les Franchiseurs de LA', 'Hight', '2023-12-29', '10:30:38', '2023-12-28 21:52:25', '', 'Sensibilisation sur la nouvelle politique', 0),
(9, 7, NULL, 'Other', 'Meeting', 'Hight', '2024-01-13', '10:30:00', '2023-12-28 21:32:33', '', 'Rencontre avec les autres associations de la regions pour échanges sur nos objectifs communs', 0),
(12, 2, 4, 'Match', 'Cannes', 'Hight', '2024-01-22', '09:29:00', '2024-01-14 18:21:40', 'Gymanse de Vauban', 'vzrb dfsbs fd gfdg d', 1),
(13, 2, 4, 'Match', 'LIMES ', 'Hight', '2024-01-31', '20:30:00', '2024-01-14 18:44:35', 'Gymnase de Paris', 'zrbe erbz zersre zrstbdbze sf', 0),
(14, 2, 4, 'Match', 'PSG', 'Hight', '2024-01-28', '09:45:00', '2024-01-15 23:35:40', 'NICE', 'ZRBEZ SEBE', 1),
(15, 2, 4, 'Match', 'FRance', 'Hight', '2024-01-29', '09:29:00', '2024-01-15 23:53:30', 'UK', 'ZERVE E EQSDV DFS DSF ', 1),
(16, 2, 4, 'Match', 'Lakers', 'Hight', '2024-01-11', '20:00:00', '2024-01-31 11:00:06', 'LA', 'zuiorbv zoeuivz zoeic ', 0);

-- --------------------------------------------------------

--
-- Structure de la table `ba_media`
--

DROP TABLE IF EXISTS `ba_media`;
CREATE TABLE IF NOT EXISTS `ba_media` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `dateCreation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `size` int DEFAULT NULL,
  `typeMime` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `path` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ba_media`
--

INSERT INTO `ba_media` (`id`, `description`, `dateCreation`, `size`, `typeMime`, `path`) VALUES
(1, 'profile', '2023-12-26 20:24:31', NULL, 'Image', 'file:/C:/wamp64/www/Cour/Image/IMG_3771 .png'),
(4, 'Image de profile', '2024-01-08 23:00:00', NULL, 'Image', 'file:/C:/Users/Utilisateur/iCloudPhotos/Photos/IMG_1851.JPG'),
(6, 'Image de profile', '2024-01-17 09:30:57', NULL, 'Image', 'file:/C:/Users/Utilisateur/iCloudPhotos/Photos/IMG_3556.JPG'),
(7, 'Image de profile', '2024-01-16 23:00:00', NULL, 'Image', 'file:/C:/Users/Utilisateur/iCloudPhotos/Photos/IMG_3043.JPG'),
(8, 'profile', '2024-01-17 19:56:28', NULL, 'Image', 'file:/C:/Users/Utilisateur/iCloudPhotos/Photos/IMG_1915.JPG'),
(9, 'profile', '2024-01-17 20:11:12', NULL, 'Image', 'file:/C:/Users/Utilisateur/iCloudPhotos/Photos/IMG_2553.JPG');

-- --------------------------------------------------------

--
-- Structure de la table `ba_middlemediacategory`
--

DROP TABLE IF EXISTS `ba_middlemediacategory`;
CREATE TABLE IF NOT EXISTS `ba_middlemediacategory` (
  `id` int NOT NULL,
  `idMedia` int NOT NULL,
  `idCategory` int NOT NULL,
  KEY `idCategory` (`idCategory`),
  KEY `idMedia` (`idMedia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ba_middlemediacoach`
--

DROP TABLE IF EXISTS `ba_middlemediacoach`;
CREATE TABLE IF NOT EXISTS `ba_middlemediacoach` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idMedia` int NOT NULL,
  `idCoach` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idCoach` (`idCoach`),
  KEY `idMedia` (`idMedia`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ba_middlemediacoach`
--

INSERT INTO `ba_middlemediacoach` (`id`, `idMedia`, `idCoach`) VALUES
(1, 1, 2),
(2, 8, 9),
(3, 9, 10);

-- --------------------------------------------------------

--
-- Structure de la table `ba_middlemediaplayer`
--

DROP TABLE IF EXISTS `ba_middlemediaplayer`;
CREATE TABLE IF NOT EXISTS `ba_middlemediaplayer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idMedia` int NOT NULL,
  `idPlayer` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idMedia` (`idMedia`),
  KEY `idPlayer` (`idPlayer`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ba_middlemediaplayer`
--

INSERT INTO `ba_middlemediaplayer` (`id`, `idMedia`, `idPlayer`) VALUES
(1, 4, 10),
(2, 6, 4),
(3, 7, 11);

-- --------------------------------------------------------

--
-- Structure de la table `ba_player`
--

DROP TABLE IF EXISTS `ba_player`;
CREATE TABLE IF NOT EXISTS `ba_player` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idTeam` int DEFAULT NULL,
  `gender` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `firstName` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `lastName` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `birthday` date NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `country` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `city` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `address` varchar(50) NOT NULL,
  `postal` int NOT NULL,
  `phone` int NOT NULL,
  `phoneEmergency` int NOT NULL,
  `email` varchar(40) NOT NULL,
  `height` int NOT NULL,
  `weight` int NOT NULL,
  `position` varchar(25) NOT NULL,
  `hurt` tinyint(1) NOT NULL,
  `available` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idTeam` (`idTeam`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ba_player`
--

INSERT INTO `ba_player` (`id`, `idTeam`, `gender`, `firstName`, `lastName`, `birthday`, `description`, `country`, `city`, `address`, `postal`, `phone`, `phoneEmergency`, `email`, `height`, `weight`, `position`, `hurt`, `available`) VALUES
(4, 1, 'Man', 'Dwayne', 'Jhon', '1993-12-16', 'fun and talented player', 'USA', 'LA', '10 rue George Washington Houston state USA', 9789, 978909782, 978309781, 'kankeulandry@icloud.com', 201, 97, 'C - Center', 0, 1),
(5, 4, 'Man', 'Micheal', 'Jordan', '2024-01-11', 'clean and simple game', 'ERTB', 'LA', 'ERTB', 234123, 23134124, 12412313, 'kankeulandry@icloud.com', 2423, 13124, 'PG - Point Guard', 0, 0),
(7, 4, 'Man', 'William', 'Pit', '2024-01-11', 'VZERBG ZRBZ ZRB', 'USA', 'Pen silvana', '56 Road JPD', 234123, 23134124, 12412313, 'kankeulandry@icoud.com', 201, 100, 'SG - Shooting Guard', 0, 1),
(10, 4, 'Man', 'Pool', 'Williamson', '2004-01-11', 'too shifty', 'USA', 'Milwaker', '21 Road JPD', 234123, 23134124, 12412313, 'kankeulandry@icoud.com', 170, 70, 'SG - Shooting Guard', 0, 1),
(11, 4, 'Man', 'Joel', 'Embid', '1995-01-25', 'Strengh and too efficient', 'Cameroon', 'Douala', '14 Rd Stadium', 99999, 987865324, 123234532, 'kankeulandey22@gamil.com', 210, 108, 'PG - Point Guard', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `ba_statistique`
--

DROP TABLE IF EXISTS `ba_statistique`;
CREATE TABLE IF NOT EXISTS `ba_statistique` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idTeam` int NOT NULL,
  `idPlayer` int DEFAULT NULL,
  `date` datetime NOT NULL,
  `oppenent` varchar(35) NOT NULL,
  `score` varchar(15) DEFAULT NULL,
  `timeGame` int DEFAULT NULL,
  `points` int DEFAULT NULL,
  `rebounds` int DEFAULT NULL,
  `assists` int DEFAULT NULL,
  `steals` int DEFAULT NULL,
  `blocks` int DEFAULT NULL,
  `attempts` int DEFAULT NULL,
  `3pointsShotsAttempts` int DEFAULT NULL,
  `3pointsPlay` int DEFAULT NULL,
  `Victory` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idTeam` (`idTeam`),
  KEY `ba_statistique_ibfk_1` (`idPlayer`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ba_statistique`
--

INSERT INTO `ba_statistique` (`id`, `idTeam`, `idPlayer`, `date`, `oppenent`, `score`, `timeGame`, `points`, `rebounds`, `assists`, `steals`, `blocks`, `attempts`, `3pointsShotsAttempts`, `3pointsPlay`, `Victory`) VALUES
(1, 4, 5, '2024-01-31 00:00:00', 'LIMES', '76:79', 37, 31, 10, 7, 9, 6, 40, 21, 11, 'false'),
(2, 4, 7, '2024-01-31 00:00:00', 'LIMES', '76:79', 30, 12, 11, 7, 3, 2, 17, 8, 2, 'false'),
(3, 4, 10, '2024-01-31 00:00:00', 'LIMES', '76:79', 33, 20, 3, 7, 1, 1, 38, 12, 5, 'false'),
(4, 4, NULL, '2024-01-31 00:00:00', 'LIMES', '76:79', 33, 76, 3, 7, 1, 1, 38, 12, 5, 'false'),
(5, 4, 5, '2024-01-29 00:00:00', 'FRance', '100:97', 34, 45, 10, 7, 11, 4, 67, 20, 11, 'true'),
(6, 4, 7, '2024-01-29 00:00:00', 'FRance', '100:97', 30, 27, 12, 3, 2, 5, 30, 8, 5, 'true'),
(7, 4, 10, '2024-01-29 00:00:00', 'FRance', '100:97', 38, 17, 8, 11, 6, 8, 27, 7, 4, 'true'),
(8, 4, 11, '2024-01-29 00:00:00', 'FRance', '100:97', 39, 29, 11, 8, 9, 4, 33, 12, 7, 'true'),
(9, 4, NULL, '2024-01-29 00:00:00', 'FRance', '100:97', 39, 100, 11, 8, 9, 4, 33, 12, 7, 'true'),
(10, 4, 5, '2024-01-28 00:00:00', 'PSG', '120:132', 40, 30, 12, 6, 2, 6, 45, 12, 7, 'false'),
(11, 4, 7, '2024-01-28 00:00:00', 'PSG', '120:132', 37, 23, 17, 4, 31, 4, 38, 8, 5, 'false'),
(12, 4, 10, '2024-01-28 00:00:00', 'PSG', '120:132', 28, 12, 9, 15, 9, 8, 6, 0, 0, 'false'),
(13, 4, 11, '2024-01-28 00:00:00', 'PSG', '120:132', 20, 31, 12, 20, 32, 12, 38, 8, 4, 'false'),
(14, 4, NULL, '2024-01-28 00:00:00', 'PSG', '120:132', 20, 120, 12, 20, 32, 12, 38, 8, 4, 'false');

-- --------------------------------------------------------

--
-- Structure de la table `ba_team`
--

DROP TABLE IF EXISTS `ba_team`;
CREATE TABLE IF NOT EXISTS `ba_team` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idCategory` int NOT NULL,
  `name` varchar(30) NOT NULL,
  `gamePriority` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `gamePlan` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ba_team_ibfk_1` (`idCategory`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ba_team`
--

INSERT INTO `ba_team` (`id`, `idCategory`, `name`, `gamePriority`, `gamePlan`) VALUES
(1, 2, 'Team A', 'Main', 'Jeu Interieur/Exterieur'),
(4, 2, 'Team B', 'Second', 'Offensif'),
(5, 4, 'Team A', 'Main', 'Offensive');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `ba_event`
--
ALTER TABLE `ba_event` ADD FULLTEXT KEY `eventFullText` (`type`,`importance`,`subject`,`description`,`location`);

--
-- Index pour la table `ba_player`
--
ALTER TABLE `ba_player` ADD FULLTEXT KEY `firstName` (`firstName`,`lastName`,`country`,`city`,`address`,`email`,`position`);

--
-- Index pour la table `ba_statistique`
--
ALTER TABLE `ba_statistique` ADD FULLTEXT KEY `oppenent` (`oppenent`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `ba_category`
--
ALTER TABLE `ba_category`
  ADD CONSTRAINT `ba_category_ibfk_1` FOREIGN KEY (`idCoach`) REFERENCES `ba_coach` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `ba_event`
--
ALTER TABLE `ba_event`
  ADD CONSTRAINT `ba_event_ibfk_1` FOREIGN KEY (`idCoach`) REFERENCES `ba_coach` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `ba_event_ibfk_2` FOREIGN KEY (`idTeam`) REFERENCES `ba_team` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `ba_middlemediacategory`
--
ALTER TABLE `ba_middlemediacategory`
  ADD CONSTRAINT `ba_middlemediacategory_ibfk_1` FOREIGN KEY (`idCategory`) REFERENCES `ba_category` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `ba_middlemediacategory_ibfk_2` FOREIGN KEY (`idMedia`) REFERENCES `ba_media` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `ba_middlemediacoach`
--
ALTER TABLE `ba_middlemediacoach`
  ADD CONSTRAINT `ba_middlemediacoach_ibfk_1` FOREIGN KEY (`idCoach`) REFERENCES `ba_coach` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `ba_middlemediacoach_ibfk_2` FOREIGN KEY (`idMedia`) REFERENCES `ba_media` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `ba_middlemediaplayer`
--
ALTER TABLE `ba_middlemediaplayer`
  ADD CONSTRAINT `ba_middlemediaplayer_ibfk_1` FOREIGN KEY (`idMedia`) REFERENCES `ba_media` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `ba_middlemediaplayer_ibfk_2` FOREIGN KEY (`idPlayer`) REFERENCES `ba_player` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `ba_player`
--
ALTER TABLE `ba_player`
  ADD CONSTRAINT `ba_player_ibfk_1` FOREIGN KEY (`idTeam`) REFERENCES `ba_team` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `ba_statistique`
--
ALTER TABLE `ba_statistique`
  ADD CONSTRAINT `ba_statistique_ibfk_1` FOREIGN KEY (`idPlayer`) REFERENCES `ba_player` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `ba_statistique_ibfk_2` FOREIGN KEY (`idTeam`) REFERENCES `ba_team` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `ba_team`
--
ALTER TABLE `ba_team`
  ADD CONSTRAINT `ba_team_ibfk_1` FOREIGN KEY (`idCategory`) REFERENCES `ba_category` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
