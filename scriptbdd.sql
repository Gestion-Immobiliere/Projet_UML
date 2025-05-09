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


DROP TABLE IF EXISTS `avis`;
DROP TABLE IF EXISTS `paiements`;
DROP TABLE IF EXISTS `contrats`;
DROP TABLE IF EXISTS `bien_immobiliers`;
DROP TABLE IF EXISTS `utilisateurs`;

-- Utilisateurs
CREATE TABLE `utilisateurs` (
  `idUser` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(50) NOT NULL,
  `prenom` VARCHAR(50) NOT NULL,
  `adresseMail` VARCHAR(50) DEFAULT NULL,
  `motDePasse` VARCHAR(255) NOT NULL,
  `numTel` TEXT,
  `role` ENUM('locataire', 'agent_immobilier', 'admin') NOT NULL,
  PRIMARY KEY (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Biens immobiliers
CREATE TABLE `bien_immobiliers` (
  `idImmobilier` INT NOT NULL AUTO_INCREMENT,
  `adresse` VARCHAR(50) DEFAULT NULL,
  `montant` DECIMAL(10,2) DEFAULT NULL,
  `type` TEXT,
  `datePublication` DATE DEFAULT NULL,
  `idAgent` INT DEFAULT NULL,
  `idAdmin` INT DEFAULT NULL,
  PRIMARY KEY (`idImmobilier`),
  FOREIGN KEY (`idAgent`) REFERENCES `utilisateurs`(`idUser`),
  FOREIGN KEY (`idAdmin`) REFERENCES `utilisateurs`(`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contrats (créée après bien_immobiliers)
CREATE TABLE `contrats` (
  `idContrat` INT NOT NULL AUTO_INCREMENT,
  `dateCreation` DATE DEFAULT NULL,
  `idAgent` INT NOT NULL,
  `idLocataire` INT NOT NULL,
  `idBien` INT NOT NULL,
  `accepte` BOOLEAN DEFAULT FALSE,
  `cheminPdf` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`idContrat`),
  FOREIGN KEY (`idAgent`) REFERENCES `utilisateurs`(`idUser`),
  FOREIGN KEY (`idLocataire`) REFERENCES `utilisateurs`(`idUser`),
  FOREIGN KEY (`idBien`) REFERENCES `bien_immobiliers`(`idImmobilier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Paiements
CREATE TABLE `paiements` (
  `idPaiement` INT NOT NULL AUTO_INCREMENT,
  `datePaiement` DATE DEFAULT NULL,
  `montant` DECIMAL(10,2) DEFAULT NULL,
  `dureeValidite` INT NOT NULL,
  `idLocataire` INT NOT NULL,
  `idImmobilier` INT NOT NULL,
  PRIMARY KEY (`idPaiement`),
  FOREIGN KEY (`idLocataire`) REFERENCES `utilisateurs`(`idUser`),
  FOREIGN KEY (`idImmobilier`) REFERENCES `bien_immobiliers`(`idImmobilier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Avis
CREATE TABLE `avis` (
  `idAvis` INT NOT NULL AUTO_INCREMENT,
  `idLocataire` INT NOT NULL,
  `idImmobilier` INT NOT NULL,
  `note` DECIMAL(10,2) DEFAULT NULL,
  `commentaire` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`idAvis`),
  FOREIGN KEY (`idLocataire`) REFERENCES `utilisateurs`(`idUser`),
  FOREIGN KEY (`idImmobilier`) REFERENCES `bien_immobiliers`(`idImmobilier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
