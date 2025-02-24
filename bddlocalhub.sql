-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
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


-- Listage de la structure de la base pour bddlocalhub
CREATE DATABASE IF NOT EXISTS `bddlocalhub` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `bddlocalhub`;

-- Listage de la structure de table bddlocalhub. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table bddlocalhub.doctrine_migration_versions : ~0 rows (environ)

-- Listage de la structure de table bddlocalhub. event
CREATE TABLE IF NOT EXISTS `event` (
  `id` int NOT NULL AUTO_INCREMENT,
  `event_title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_date` datetime NOT NULL,
  `event_location` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table bddlocalhub.event : ~0 rows (environ)

-- Listage de la structure de table bddlocalhub. genre
CREATE TABLE IF NOT EXISTS `genre` (
  `id` int NOT NULL AUTO_INCREMENT,
  `genre_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table bddlocalhub.genre : ~0 rows (environ)
INSERT INTO `genre` (`id`, `genre_name`) VALUES
	(1, 'Electro'),
	(2, 'Rock'),
	(3, 'Jazz'),
	(4, 'Hip-Hop'),
	(5, 'Indie');

-- Listage de la structure de table bddlocalhub. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table bddlocalhub.messenger_messages : ~0 rows (environ)

-- Listage de la structure de table bddlocalhub. playlist
CREATE TABLE IF NOT EXISTS `playlist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `playlist_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visibility` tinyint(1) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D782112DA76ED395` (`user_id`),
  CONSTRAINT `FK_D782112DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table bddlocalhub.playlist : ~0 rows (environ)

-- Listage de la structure de table bddlocalhub. playlist_track
CREATE TABLE IF NOT EXISTS `playlist_track` (
  `playlist_id` int NOT NULL,
  `track_id` int NOT NULL,
  PRIMARY KEY (`playlist_id`,`track_id`),
  KEY `IDX_75FFE1E56BBD148` (`playlist_id`),
  KEY `IDX_75FFE1E55ED23C43` (`track_id`),
  CONSTRAINT `FK_75FFE1E55ED23C43` FOREIGN KEY (`track_id`) REFERENCES `track` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_75FFE1E56BBD148` FOREIGN KEY (`playlist_id`) REFERENCES `playlist` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table bddlocalhub.playlist_track : ~0 rows (environ)

-- Listage de la structure de table bddlocalhub. playlist_user
CREATE TABLE IF NOT EXISTS `playlist_user` (
  `playlist_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`playlist_id`,`user_id`),
  KEY `IDX_2D8AE12B6BBD148` (`playlist_id`),
  KEY `IDX_2D8AE12BA76ED395` (`user_id`),
  CONSTRAINT `FK_2D8AE12B6BBD148` FOREIGN KEY (`playlist_id`) REFERENCES `playlist` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_2D8AE12BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table bddlocalhub.playlist_user : ~0 rows (environ)

-- Listage de la structure de table bddlocalhub. track
CREATE TABLE IF NOT EXISTS `track` (
  `id` int NOT NULL AUTO_INCREMENT,
  `track_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `audio_file` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` int NOT NULL,
  `upload_date` datetime DEFAULT NULL,
  `view_count` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D6E3F8A6A76ED395` (`user_id`),
  CONSTRAINT `FK_D6E3F8A6A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table bddlocalhub.track : ~0 rows (environ)
INSERT INTO `track` (`id`, `track_name`, `audio_file`, `duration`, `upload_date`, `view_count`, `user_id`) VALUES
	(1, 'Neon Pulse', NULL, 210, '2025-01-20 17:10:12', 7800, 1),
	(2, 'Echo Waves', NULL, 190, '2025-01-20 17:13:14', 7852, 1),
	(3, 'Synthetic Dreams', NULL, 230, '2025-02-20 18:00:00', 1350, 1),
	(4, 'Night Drive', NULL, 200, '2025-01-20 17:13:21', 1300, 1),
	(5, 'Cyber Mirage', NULL, 220, '2025-02-20 17:13:21', 1200, 1),
	(6, 'Retro Love', NULL, 215, '2025-02-10 17:13:22', 755, 2),
	(7, 'Midnight Lights', NULL, 205, '2025-01-20 17:13:23', 785, 2),
	(8, 'Neon Glow', NULL, 240, '2024-02-20 17:13:23', 4580, 2),
	(9, 'Echoed Memories', NULL, 195, '2024-02-08 17:13:24', 5200, 2),
	(10, 'Skyline Drive', NULL, 225, '2025-02-12 17:13:24', 5000, 2),
	(11, 'Electric Road', NULL, 250, '2025-01-09 17:13:25', 250, 3),
	(12, 'Last Sunset', NULL, 220, '2025-01-20 17:13:26', 200, 3),
	(13, 'Burning Horizon', NULL, 235, '2025-02-15 17:13:25', 2550, 3),
	(14, 'Chasing the Storm', NULL, 215, '2025-01-15 17:13:27', 5222, 3),
	(15, 'Wanderlust', NULL, 240, '2024-12-20 17:13:28', 8000, 3),
	(16, 'Misty Blue', NULL, 255, '2024-11-20 17:13:29', 7500, 4),
	(17, 'Cosmic Vibes', NULL, 230, '2025-02-15 17:13:29', 4550, 4),
	(18, 'Lo-Fi Groove', NULL, 215, '2025-01-20 12:20:20', 4222, 4),
	(19, 'Golden Hour', NULL, 245, '2025-01-15 17:13:33', 425, 4),
	(20, 'Fusion Flow', NULL, 260, '2025-02-10 17:13:33', 780, 4),
	(21, 'Street Anthem', NULL, 200, '2025-02-10 17:13:34', 3000, 5),
	(22, 'Hustle Hard', NULL, 210, '2025-02-12 17:13:32', 4500, 5),
	(23, 'No Sleep', NULL, 190, '2025-02-18 17:13:32', 4555, 5),
	(24, 'Fast Lane', NULL, 205, '2025-02-20 17:13:31', 2300, 5),
	(25, 'Dream Chaser', NULL, 215, '2025-02-20 17:13:31', 3200, 5);

-- Listage de la structure de table bddlocalhub. track_genre
CREATE TABLE IF NOT EXISTS `track_genre` (
  `track_id` int NOT NULL,
  `genre_id` int NOT NULL,
  PRIMARY KEY (`track_id`,`genre_id`),
  KEY `IDX_F3A7915F5ED23C43` (`track_id`),
  KEY `IDX_F3A7915F4296D31F` (`genre_id`),
  CONSTRAINT `FK_F3A7915F4296D31F` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_F3A7915F5ED23C43` FOREIGN KEY (`track_id`) REFERENCES `track` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table bddlocalhub.track_genre : ~0 rows (environ)
INSERT INTO `track_genre` (`track_id`, `genre_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(6, 5),
	(7, 5),
	(8, 5),
	(9, 5),
	(10, 5),
	(11, 2),
	(12, 2),
	(13, 2),
	(14, 2),
	(15, 2),
	(16, 3),
	(17, 3),
	(18, 3),
	(19, 3),
	(20, 3),
	(21, 4),
	(22, 4),
	(23, 4),
	(24, 4),
	(25, 4);

-- Listage de la structure de table bddlocalhub. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table bddlocalhub.user : ~3 rows (environ)
INSERT INTO `user` (`id`, `pseudo`, `email`, `password`, `is_verified`, `role`, `departement`) VALUES
	(1, 'Liam Shadow', 'liam.shadow@email.com', 'password_hashed', 1, 'artiste', '35'),
	(2, 'Nova Sky', 'nova.sky@email.com', 'password_hashed', 1, 'artiste', '22'),
	(3, 'Axel Storm', 'axel.storm@email.com', 'password_hashed', 1, 'artiste', '95'),
	(4, 'Serena Moon', 'serena.moon@email.com', 'password_hashed', 1, 'artiste', '67'),
	(5, 'Drake Orion', 'drake.orion@email.com', 'password_hashed', 1, 'artiste', '93'),
	(8, 'Admin', 'admin@localhub.com', '$2y$13$MmT6R9jCPKJWeiEQf8F0QOVyZE0vEMi/3XliU50bf9z3ULcD0hWHy', 1, 'admin', '35'),
	(9, 'Seif', 'seif@auditeur.com', '$2y$13$0nsRyjAO3gd1GyhrTMt0t.9S4FnUvwk8OaPdsrSDwsQaqe4IlQBTy', 1, 'auditeur', '93'),
	(10, 'Simon', 'simon@auditeur.com', '$2y$13$tgkmdAA3F/3f9cqdXjBQNuaq6vql011oAD0hy.cu8a7TOQwRCI2jG', 1, 'auditeur', '22'),
	(11, 'Ines', 'ines@auditeur.com', '$2y$13$HWLCBt3Wmcwcl7boucmh3egFhIpzZgg0yXeroGEcU1f7z6KCR2dE6', 1, 'auditeur', '35'),
	(13, 'Rozenn', 'rozenn@auditeur.com', '$2y$13$45lOoN30d6jCv06FGe6HPu/DJAG5oW7DNXn1vn0gnO.jtE/kcgTY2', 0, 'auditeur', '67'),
	(14, 'Marie', 'marie@auditeur.com', '$2y$13$fvZWD5ac0GoyRGV0J4noReDfuT9Ve5zZ7QktbUORRkH/vxsDwzyKO', 0, 'auditeur', '67'),
	(15, 'Alexandre', 'alexandre@auditeur.com', '$2y$13$v85CwxcZU5Pi4Pd/FQIOJuUBH5F/hAqqvqxRmBW4daY8QDYm5F3pS', 0, 'auditeur', '22'),
	(16, 'Laury', 'laury@auditeur.com', '$2y$13$9D4pY21WqTZEUbFwpSAh6u841K3eBz4eRT2gnzK2oFAQv1CrtB.qi', 0, 'auditeur', '67'),
	(18, 'Aymeric', 'aymeric@auditeur.com', '$2y$13$2B4Dg0yxF9ybDt9nRU7kR.jHDPSVCe7gca2svXrKEAOChGx/tuG12', 1, 'auditeur', '44');

-- Listage de la structure de table bddlocalhub. user_track
CREATE TABLE IF NOT EXISTS `user_track` (
  `user_id` int NOT NULL,
  `track_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`track_id`),
  KEY `IDX_342103FEA76ED395` (`user_id`),
  KEY `IDX_342103FE5ED23C43` (`track_id`),
  CONSTRAINT `FK_342103FE5ED23C43` FOREIGN KEY (`track_id`) REFERENCES `track` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_342103FEA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table bddlocalhub.user_track : ~0 rows (environ)
INSERT INTO `user_track` (`user_id`, `track_id`) VALUES
	(9, 2),
	(9, 3),
	(9, 6),
	(9, 9);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
