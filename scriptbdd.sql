-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 03 avr. 2025 à 22:01
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13


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


DROP TABLE IF EXISTS `contrats`;
CREATE TABLE IF NOT EXISTS `contrats` (
  `idContrat` int NOT NULL AUTO_INCREMENT,
  `dateCreation` date DEFAULT NULL,
  `idAgent` int NOT NULL,
  PRIMARY KEY (`idContrat`),
  FOREIGN KEY (`idAgent`) REFERENCES `utilisateurs`(`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `bien_immobiliers`;
CREATE TABLE IF NOT EXISTS `bien_immobiliers` (
  `idImmobilier` int NOT NULL AUTO_INCREMENT,
  `adresse` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `type` text COLLATE utf8mb4_unicode_ci,
  `datePublication` date DEFAULT NULL,
  `idAgent` int DEFAULT NULL,
  `idAdmin` int DEFAULT NULL,
  `idContrat` int DEFAULT NULL,
  PRIMARY KEY (`idImmobilier`),
  FOREIGN KEY (`idAgent`) REFERENCES `utilisateurs`(`idUser`),
  FOREIGN KEY (`idContrat`) REFERENCES `contrats`(`idContrat`),
  FOREIGN KEY (`idAdmin`) REFERENCES `utilisateurs`(`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `paiements`;
CREATE TABLE IF NOT EXISTS `paiements` (
  `idPaiement` int NOT NULL AUTO_INCREMENT,
  `datePaiement` date DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `dureeValidite` INT NOT NULL,
  `idLocataire` int NOT NULL,
  `idImmobilier` int NOT NULL,
  PRIMARY KEY (`idPaiement`),
  FOREIGN KEY (`idLocataire`) REFERENCES `utilisateurs`(`idUser`),
  FOREIGN KEY (`idImmobilier`) REFERENCES `bien_immobiliers`(`idImmobilier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `avis`;
CREATE TABLE IF NOT EXISTS `avis` (
  `idAvis` int NOT NULL AUTO_INCREMENT,
  `idLocataire` int NOT NULL,
  `idImmobilier` int NOT NULL,
  `note` decimal(10,2) DEFAULT NULL,
  `commentaire` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`idAvis`),
  FOREIGN KEY (`idLocataire`) REFERENCES `utilisateurs`(`idUser`),
  FOREIGN KEY (`idImmobilier`) REFERENCES `bien_immobiliers`(`idImmobilier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
