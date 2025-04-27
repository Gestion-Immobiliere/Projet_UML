-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 27 avr. 2025 à 19:57
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `immo-plateform`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateurs`
--

DROP TABLE IF EXISTS `administrateurs`;
CREATE TABLE IF NOT EXISTS `administrateurs` (
  `idAdmin` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `adresseMail` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `motDePasse` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `numTel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`idAdmin`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `administrateurs`
--

INSERT INTO `administrateurs` (`idAdmin`, `adresseMail`, `motDePasse`, `numTel`) VALUES
(1, 'admin@example.com', '$2y$12$F2Dp2QWtnc4.qstphsrHkuGGrIDipvPwZMbSdOnzFhoFoBO5S1Gb6', '700112233');

-- --------------------------------------------------------

--
-- Structure de la table `agent_immobiliers`
--

DROP TABLE IF EXISTS `agent_immobiliers`;
CREATE TABLE IF NOT EXISTS `agent_immobiliers` (
  `idAgent` int NOT NULL AUTO_INCREMENT,
  `adresseMail` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numTel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`idAgent`),
  KEY `fk_agent_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `agent_immobiliers`
--

INSERT INTO `agent_immobiliers` (`idAgent`, `adresseMail`, `numTel`, `user_id`) VALUES
(2, ' ssudoku105@gmail.com', '771234567', 7),
(3, 'djibril3110@example.com', '761234512', 10),
(4, 'npp1@gmail.com', '771322234', 14),
(5, 'diouf@gmail.com', '771122234', 15);

-- --------------------------------------------------------

--
-- Structure de la table `annonces`
--

DROP TABLE IF EXISTS `annonces`;
CREATE TABLE IF NOT EXISTS `annonces` (
  `idAnnonce` int NOT NULL AUTO_INCREMENT,
  `libelle` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `idAgent` int NOT NULL,
  PRIMARY KEY (`idAnnonce`),
  KEY `idAgent` (`idAgent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bien_immobiliers`
--

DROP TABLE IF EXISTS `bien_immobiliers`;
CREATE TABLE IF NOT EXISTS `bien_immobiliers` (
  `idImmobilier` int NOT NULL AUTO_INCREMENT,
  `adresse` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `type` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `datePublication` date DEFAULT NULL,
  `idAgent` int NOT NULL,
  PRIMARY KEY (`idImmobilier`),
  KEY `idAgent` (`idAgent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contrats`
--

DROP TABLE IF EXISTS `contrats`;
CREATE TABLE IF NOT EXISTS `contrats` (
  `idContrat` int NOT NULL AUTO_INCREMENT,
  `dateCreation` date DEFAULT NULL,
  `dateDebut` date DEFAULT NULL,
  `dateFin` date DEFAULT NULL,
  `dateMaj` date DEFAULT NULL,
  `idLocataire` int NOT NULL,
  `idAgent` int NOT NULL,
  PRIMARY KEY (`idContrat`),
  KEY `idLocataire` (`idLocataire`),
  KEY `idAgent` (`idAgent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `evaluers`
--

DROP TABLE IF EXISTS `evaluers`;
CREATE TABLE IF NOT EXISTS `evaluers` (
  `idLocataire` int NOT NULL,
  `idAnnonce` int NOT NULL,
  `note` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`idLocataire`,`idAnnonce`),
  KEY `idAnnonce` (`idAnnonce`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `locataires`
--

DROP TABLE IF EXISTS `locataires`;
CREATE TABLE IF NOT EXISTS `locataires` (
  `idLocataire` int NOT NULL AUTO_INCREMENT,
  `adresseMail` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numTel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`idLocataire`),
  KEY `fk_locataires_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `locataires`
--

INSERT INTO `locataires` (`idLocataire`, `adresseMail`, `numTel`, `user_id`) VALUES
(1, 'talla@gmail.com', '781122234', 17);

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_04_21_194853_create_personal_access_tokens_table', 1),
(5, '2025_04_22_000000_create_sessions_table', 2),
(6, '2025_04_23_020147_add_expires_at_to_personal_access_tokens_table', 3),
(7, '2025_04_23_033152_create_password_reset_tokens_table', 4),
(8, '2025_04_23_034100_create_password_resets_table', 5);

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
CREATE TABLE IF NOT EXISTS `paiements` (
  `idPaiement` int NOT NULL AUTO_INCREMENT,
  `datePaiement` date DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `idLocataire` int NOT NULL,
  `idContrat` int NOT NULL,
  PRIMARY KEY (`idPaiement`),
  KEY `idLocataire` (`idLocataire`),
  KEY `idContrat` (`idContrat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('djibril@example.com', '$2y$12$1XZcvHBPoyFV6WwGSz0NIeIS2LTFOKWw4xMWSToD2ZCOAXfKZoXSa', '2025-04-23 03:53:37'),
('namalpita0@gmail.com', '$2y$12$pHUnq9KClwD..yJnmNeN4eE/eI6..bYa/pI5hiM1phg18rlmdV9L.', '2025-04-23 12:54:24'),
('ssudoku105@gmail.com', '$2y$12$604PTPoCpZxF1PZxDAxmv.ESoBay3jJg1fjkTbA1hbna4rClGaRga', '2025-04-23 14:32:04');

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'api-token', 'e30d6e31dd0579f3da71db5b47f777e409d2f4d2c56853363b5392de083db261', '[\"*\"]', NULL, NULL, '2025-04-23 02:03:17', '2025-04-23 02:03:17'),
(2, 'App\\Models\\User', 1, 'api-token', '1ac9a6cf81b934b53d874a7143ad0aea913ec52331d412c131b633688ffe57d2', '[\"*\"]', '2025-04-23 02:57:11', NULL, '2025-04-23 02:10:24', '2025-04-23 02:57:11'),
(4, 'App\\Models\\User', 15, 'api-token', 'a34f01d052d0d70040172ea03973bf895c1911551de9c0691a1cf2a54103c052', '[\"*\"]', NULL, NULL, '2025-04-24 16:25:16', '2025-04-24 16:25:16'),
(5, 'App\\Models\\User', 2, 'api-token', '645ffd02cb335ccf77f9ab7063d2278b3fa57dfdaf57bf8bdf41bfae58cab285', '[\"*\"]', NULL, NULL, '2025-04-25 03:15:02', '2025-04-25 03:15:02');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('hjhiTK3tvg27l6JQnhirgELkYLoCpInBcAq5hp3V', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM3VtbGtDRFpGaXQ2SUxqOEZtY21PWUtQaDdmZlFLcWExb2Y1aTR5eCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1745349074),
('H3DS6iTTjHpzrlkG0D6F6mIbiBJNeqnoL6QlepSd', NULL, '127.0.0.1', 'PostmanRuntime/7.43.3', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVjBES0ZzaGNlMWR2bG14aFl3b3RlaXdTbW9wbUVPVjFwbWxRall2UCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1745353464),
('Cj7e6CyBDdMhutanvkzUjSxbuo75tRe8LHfU73yn', NULL, '127.0.0.1', 'PostmanRuntime/7.43.3', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRUNDaVZuQUt0S1lsV1hrbWRFUlM1eEZLbWVtWkJZR0FmelVUSDdReCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1745371891),
('YnoZRGjC6dCODbz8v4SlceM08cyyyuZSVwDexNwN', NULL, '127.0.0.1', 'PostmanRuntime/7.43.3', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVlNsa0tCb2c2dEE5dEt1SW9MVVkxZGNEMHVObGZROFBpTHhoeDR6eCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1745411890),
('Dgc1DgwzJYYva2VRyZnfw4pgIOh8TWAiBmRvbVDu', NULL, '127.0.0.1', 'PostmanRuntime/7.43.3', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT1lvZHV3SXpoY1oyVlBGdlNWa3E1OExsM25MR0I0V3F2NlhrQWUxRyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1745420350),
('TGeyF6zR5BhAEDkELXcEj20T1sX1Y3ronOj0QZuK', NULL, '127.0.0.1', 'PostmanRuntime/7.43.3', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiME5kUGpZaUxQNVd4WHpiOWxUTHNvMExFVE1UdUNGM3d3UnVnM04yUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1745512048),
('XlHnHYdTGeKe8w17UMK9TqK83bnJmI1htipLE0Of', NULL, '192.168.10.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT1NWazd3SFRYNHhONDJxRnVrcEwxVkZ1dUxza2pEclUzYm5Sd25TdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjQ6Imh0dHA6Ly8xOTIuMTY4LjEwLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1745544905),
('2d1oofMz25NujlOxQJxPzLb5v4Gfetyhd2YOjt9P', NULL, '127.0.0.1', 'PostmanRuntime/7.43.3', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR1hPNXRidkJyNTh0Z1l0ZVB1R1ZaQ1htUVVLenU3Q1lLeHdBcjl5MiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1745554546);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('agent','locataire') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone`, `password`, `role`, `created_at`, `updated_at`) VALUES
(2, 'Gyomei', 'Himejima', 'la7168674@gmail.com', '771234562', '$2y$12$F93uGk.ALlfpWDHrV.GMl.yBH6.4RYNnWMc8vzcRUqFdBIu/YiWGe', 'agent', '2025-04-23 01:17:38', '2025-04-25 02:29:50'),
(3, 'Eren', 'Jaguer', ' ssudoku1052@gmail.com', '771234162', '$2y$12$CGNttawxW2YahlCwbnfVY.x5DcGi09pADTSC3vz8iEf8yls.JYerC', 'agent', '2025-04-23 01:28:45', '2025-04-23 01:28:45'),
(7, 'Djibril', 'Diallo', ' ssudoku10115@gmail.com', '771234567', '$2y$12$j.0RMaE4trI9OCSbAMMcKu2XCRZy11D.DUSCzVYSpFQ4OI73d99om', 'agent', '2025-04-23 03:07:33', '2025-04-23 03:07:33'),
(8, 'sam', 'Diallo', 'ssudoku105@gmail.com', '771234567', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'locataire', '2025-04-23 14:30:55', '2025-04-23 14:30:55'),
(9, 'Djibral', 'Dialo', 'djibril30@example.com', '771234517', '$2y$12$n084v82SAlbpL8Nnfpk7u.xw.tqsoxcuCzOHiXPVmM7iPjYiNa0D2', 'locataire', '2025-04-23 14:41:00', '2025-04-23 14:41:00'),
(10, 'papa', 'sougou', 'djibril3110@example.com', '761234512', '$2y$12$C.ladjSwBcTuunVggL25Xu/.M6XB7964LDhP7uKPwvVqnYrIF6MRO', 'agent', '2025-04-23 14:52:14', '2025-04-23 14:52:14'),
(11, 'cheikh', 'sougou', 'djibril3111@example.com', '761234518', '$2y$12$J.1HkIivbt5.KsU7.yslQ.eWa/UVzAP4odEFJDOIooAqmciCctLli', 'locataire', '2025-04-23 14:53:36', '2025-04-23 14:53:36'),
(12, 'cheikhe', 'sougou', 'djibril311333@example.com', '771234513', '$2y$12$rfhlGYwnyAO2h/zshl1cjOFGpc94SPm.u7NtH1q4RwDMKkj5kjqWa', 'locataire', '2025-04-23 14:55:54', '2025-04-23 14:55:54'),
(13, 'papp', 'sddd', 'npp@gmail.com', '771322234', '$2y$12$LVS7auNd8tj1XaIrxIjnje5j.5X.y9WuCKiV/tBgz75mWCZCKaFca', 'locataire', '2025-04-23 14:57:23', '2025-04-23 14:57:23'),
(14, 'papp', 'sddd', 'npp1@gmail.com', '771322234', '$2y$12$A2OWPa.ssiFqKRuW3Ck8I.AJ7A0bGg09QpVxfUoBYvP3.SIeRkNAS', 'agent', '2025-04-23 14:59:33', '2025-04-23 14:59:33'),
(15, 'ousmane', 'diouf', 'diouf@gmail.com', '771122234', '$2y$12$J7SL6pQjmEPtVcLfYz5uye76T2K21SAkVv17YlsinkqL1VialBKBy', 'agent', '2025-04-24 16:24:13', '2025-04-24 16:24:13'),
(17, 'talla', 'diouf', 'talla@gmail.com', '781122234', '$2y$12$aAjbYDouO2Khcr2.SWi/eefe1YrmnP4FqwbJoz4PRsj3oX/UBPqb6', 'locataire', '2025-04-25 03:13:19', '2025-04-25 03:13:19');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `agent_immobiliers`
--
ALTER TABLE `agent_immobiliers`
  ADD CONSTRAINT `fk_agent_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `annonces`
--
ALTER TABLE `annonces`
  ADD CONSTRAINT `annonces_ibfk_1` FOREIGN KEY (`idAgent`) REFERENCES `agent_immobiliers` (`idAgent`);

--
-- Contraintes pour la table `bien_immobiliers`
--
ALTER TABLE `bien_immobiliers`
  ADD CONSTRAINT `bien_immobiliers_ibfk_1` FOREIGN KEY (`idAgent`) REFERENCES `agent_immobiliers` (`idAgent`);

--
-- Contraintes pour la table `contrats`
--
ALTER TABLE `contrats`
  ADD CONSTRAINT `contrats_ibfk_1` FOREIGN KEY (`idLocataire`) REFERENCES `locataires` (`idLocataire`),
  ADD CONSTRAINT `contrats_ibfk_2` FOREIGN KEY (`idAgent`) REFERENCES `agent_immobiliers` (`idAgent`);

--
-- Contraintes pour la table `evaluers`
--
ALTER TABLE `evaluers`
  ADD CONSTRAINT `evaluers_ibfk_1` FOREIGN KEY (`idLocataire`) REFERENCES `locataires` (`idLocataire`),
  ADD CONSTRAINT `evaluers_ibfk_2` FOREIGN KEY (`idAnnonce`) REFERENCES `annonces` (`idAnnonce`);

--
-- Contraintes pour la table `locataires`
--
ALTER TABLE `locataires`
  ADD CONSTRAINT `fk_locataires_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD CONSTRAINT `paiements_ibfk_1` FOREIGN KEY (`idLocataire`) REFERENCES `locataires` (`idLocataire`),
  ADD CONSTRAINT `paiements_ibfk_2` FOREIGN KEY (`idContrat`) REFERENCES `contrats` (`idContrat`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
