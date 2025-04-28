-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 03 avr. 2025 à 22:01
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
--

-- --------------------------------------------------------


DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `idUser` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresseMail` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `motDePasse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numTel` text COLLATE utf8mb4_unicode_ci,
  `role` ENUM('locataire', 'agent_immobilier', 'admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `bien_immobiliers`;
CREATE TABLE IF NOT EXISTS `bien_immobiliers` (
  `idImmobilier` int NOT NULL AUTO_INCREMENT,
  `adresse` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `type` text COLLATE utf8mb4_unicode_ci,
  `datePublication` date DEFAULT NULL,
  `idAgent` int NOT NULL,
  PRIMARY KEY (`idImmobilier`),
  FOREIGN KEY (`idAgent`) REFERENCES `utilisateurs`(`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `annonces`;
CREATE TABLE IF NOT EXISTS `annonces` (
  `idAnnonce` int NOT NULL AUTO_INCREMENT,
  `libelle` text COLLATE utf8mb4_unicode_ci,
  `idAgent` int NOT NULL,
  PRIMARY KEY (`idAnnonce`),
  FOREIGN KEY (`idAgent`) REFERENCES `utilisateurs`(`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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
  FOREIGN KEY (`idLocataire`) REFERENCES `utilisateurs`(`idUser`),
  FOREIGN KEY (`idAgent`) REFERENCES `utilisateurs`(`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `paiements`;
CREATE TABLE IF NOT EXISTS `paiements` (
  `idPaiement` int NOT NULL AUTO_INCREMENT,
  `datePaiement` date DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `idLocataire` int NOT NULL,
  `idContrat` int NOT NULL,
  PRIMARY KEY (`idPaiement`),
  FOREIGN KEY (`idLocataire`) REFERENCES `utilisateurs`(`idUser`),
  FOREIGN KEY (`idContrat`) REFERENCES `contrats`(`idContrat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `evaluers`;
CREATE TABLE IF NOT EXISTS `evaluers` (
  `idLocataire` int NOT NULL,
  `idAnnonce` int NOT NULL,
  `note` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`idLocataire`,`idAnnonce`),
  FOREIGN KEY (`idLocataire`) REFERENCES `utilisateurs`(`idUser`),
  FOREIGN KEY (`idAnnonce`) REFERENCES `annonces`(`idAnnonce`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
