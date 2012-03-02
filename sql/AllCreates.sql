-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Sam 04 Février 2012 à 01:21
-- Version du serveur: 5.1.58
-- Version de PHP: 5.3.6-13ubuntu3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `UPS_EDT`
--


-- --------------------------------------------------------

--
-- Structure de la table `Promotion`
--

DROP TABLE IF EXISTS `Promotion`;

CREATE TABLE IF NOT EXISTS `Promotion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8_bin NOT NULL,
  `annee` int(11) NOT NULL,
  `tsDebut` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tsFin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Specialite`
--

DROP TABLE IF EXISTS `Specialite`;

CREATE TABLE IF NOT EXISTS `Specialite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idPromotion` int(11) NOT NULL,
  `nom` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`nom`,`idPromotion`),
  FOREIGN KEY (idPromotion) REFERENCES Promotion(id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Groupe_Administratif`
--

DROP TABLE IF EXISTS `Groupe_Administratif`;

CREATE TABLE IF NOT EXISTS `Groupe_Administratif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8_bin NOT NULL,
  `identifiant` varchar(100) COLLATE utf8_bin NOT NULL,
  `type` varchar(20) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`identifiant`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `Groupe_Cours`
--

DROP TABLE IF EXISTS `Groupe_Cours`;

CREATE TABLE IF NOT EXISTS `Groupe_Cours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8_bin NOT NULL,
  `identifiant` varchar(100) COLLATE utf8_bin NOT NULL,
  `idPromotion` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`idPromotion`) REFERENCES Promotion(`id`),
  UNIQUE (`identifiant`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Groupe_Etudiants`
--

DROP TABLE IF EXISTS `Groupe_Etudiants`;

CREATE TABLE IF NOT EXISTS `Groupe_Etudiants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8_bin NOT NULL,
  `identifiant` varchar(100) COLLATE utf8_bin NOT NULL,
  `idPromotion` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`idPromotion`) REFERENCES Promotion(`id`),
  UNIQUE (`identifiant`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

DROP TABLE IF EXISTS `Utilisateur`;

CREATE TABLE IF NOT EXISTS `Utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `motDePasse` varchar(32) NOT NULL,
  `type` varchar(32) NOT NULL,
  `idCorrespondant` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`login`),
  UNIQUE (`type`, `idCorrespondant`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Structure de la table `Etudiant`
--

DROP TABLE IF EXISTS `Etudiant`;

CREATE TABLE IF NOT EXISTS `Etudiant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numeroEtudiant` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `notificationsActives` tinyint(1) NOT NULL DEFAULT '1',
  `idPromotion` int(11) NOT NULL,
  `idSpecialite` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`idSpecialite`) REFERENCES Specialite(`id`),
  FOREIGN KEY (`idPromotion`) REFERENCES Promotion(`id`),
  UNIQUE (`numeroEtudiant`),
  UNIQUE (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Intervenant`
--

DROP TABLE IF EXISTS `Intervenant`;

CREATE TABLE IF NOT EXISTS `Intervenant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `notificationsActives` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `UE`
--

DROP TABLE IF EXISTS `UE`;

CREATE TABLE IF NOT EXISTS `UE` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8_bin NOT NULL,  
  `intitule` varchar(100) COLLATE utf8_bin NOT NULL,
  `nbHeuresCours` double NOT NULL,
  `nbHeuresTD` double NOT NULL,
  `nbHeuresTP` double NOT NULL,
  `ECTS` double NOT NULL,
  `idResponsable` int(11),
  `idPromotion` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`idResponsable`) REFERENCES Intervenant (`id`),
  FOREIGN KEY (`idPromotion`) REFERENCES Promotion (`id`),
  UNIQUE (`nom`, `idPromotion`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Batiment`
--

DROP TABLE IF EXISTS `Batiment`; 

CREATE TABLE IF NOT EXISTS `Batiment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `lat` double DEFAULT NULL,
  `lon` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`nom`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Type_Salle`
--

DROP TABLE IF EXISTS `Type_Salle`;

CREATE TABLE IF NOT EXISTS `Type_Salle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Salle`
--

DROP TABLE IF EXISTS `Salle`;

CREATE TABLE IF NOT EXISTS `Salle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8_bin NOT NULL,
  `nomBatiment` varchar(50) COLLATE utf8_bin NOT NULL,
  `capacite` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`,`nomBatiment`),
  FOREIGN KEY `nomBatiment` (`nomBatiment`) REFERENCES Batiment(`nom`),
  FOREIGN KEY (`nomBatiment`) REFERENCES Type_Salle(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Type_Cours`
--

DROP TABLE IF EXISTS `Type_Cours`;

CREATE TABLE IF NOT EXISTS `Type_Cours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`nom`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Cours`
--

DROP TABLE IF EXISTS `Cours`;

CREATE TABLE IF NOT EXISTS `Cours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUE` int(11) NOT NULL,
  `idSalle` int(11) NOT NULL,
  `idIntervenant` int(11) NOT NULL,
  `idTypeCours` int(11) NOT NULL,
  `tsDebut` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tsFin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`idUE`) REFERENCES UE(`id`),
  FOREIGN KEY (`idSalle`) REFERENCES Salle(`id`),
  FOREIGN KEY (`idIntervenant`) REFERENCES Intervenant(`id`),
  FOREIGN KEY (`idTypeCours`) REFERENCES Type_Cours(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Publication`
--

DROP TABLE IF EXISTS `Publication`;

CREATE TABLE IF NOT EXISTS `Publication` (
  `idGroupeEtudiants` int(11) NOT NULL,
  `idGroupeCours` int(11) NOT NULL,
  PRIMARY KEY (`idGroupeEtudiants`,`idGroupeCours`),
  FOREIGN KEY (`idGroupeEtudiants`) REFERENCES Groupe_Etudiants(`id`),
  FOREIGN KEY (`idGroupeCours`) REFERENCES Groupe_Cours(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `Appartient_Cours_GroupeCours`
--

DROP TABLE IF EXISTS `Appartient_Cours_GroupeCours`;

CREATE TABLE IF NOT EXISTS `Appartient_Cours_GroupeCours` (
  `idCours` int(11) NOT NULL,
  `idGroupeCours` int(11) NOT NULL,
  PRIMARY KEY (`idCours`,`idGroupeCours`),
  FOREIGN KEY (`idCours`) REFERENCES Cours(`id`),
  FOREIGN KEY (`idGroupeCours`) REFERENCES Groupe_Cours(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `Appartient_Etudiant_GroupeAdministratif`
--

DROP TABLE IF EXISTS `Appartient_Etudiant_GroupeAdministratif`;

CREATE TABLE IF NOT EXISTS `Appartient_Etudiant_GroupeAdministratif` (
  `idEtudiant` int(11) NOT NULL,
  `idGroupeAdministratif` int(11) NOT NULL,
  PRIMARY KEY (`idEtudiant`,`idGroupeAdministratif`),
  FOREIGN KEY (`idEtudiant`) REFERENCES Etudiant(`id`),
  FOREIGN KEY (`idGroupeAdministratif`) REFERENCES Groupe_Administratif(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `Appartient_Etudiant_GroupeEtudiants`
--

DROP TABLE IF EXISTS `Appartient_Etudiant_GroupeEtudiants`;

CREATE TABLE IF NOT EXISTS `Appartient_Etudiant_GroupeEtudiants` (
  `idEtudiant` int(11) NOT NULL,
  `idGroupeEtudiants` int(11) NOT NULL,
  PRIMARY KEY (`idEtudiant`,`idGroupeEtudiants`),
  FOREIGN KEY (`idEtudiant`) REFERENCES Etudiant(`id`),
  FOREIGN KEY (`idGroupeEtudiants`) REFERENCES Groupe_Etudiants(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `Appartient_Salle_TypeSalle`
--

DROP TABLE IF EXISTS `Appartient_Salle_TypeSalle`;

CREATE TABLE IF NOT EXISTS `Appartient_Salle_TypeSalle` (
  `idSalle` int(11) NOT NULL,
  `idTypeSalle` int(11) NOT NULL,
  PRIMARY KEY (`idSalle`,`idTypeSalle`),
  FOREIGN KEY (`idSalle`) REFERENCES Salle(`id`),
  FOREIGN KEY (`idTypeSalle`) REFERENCES Type_Salle(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
-- --------------------------------------------------------

--
-- Vues de la table `Cours`
--

DROP VIEW IF EXISTS `V_Infos_Cours`;

CREATE VIEW `V_Infos_Cours` AS
SELECT Cours.id AS id, UE.nom AS nomUE, Intervenant.nom AS nomIntervenant, Intervenant.prenom AS prenomIntervenant, Type_Cours.nom AS nomTypeCours, Cours.tsDebut as tsDebut, Cours.tsFin AS tsFin,
V_Liste_Salles.nomSalle AS nomSalle, V_Liste_Salles.nomBatiment AS nomBatiment, V_Liste_Salles.lat AS lat, V_Liste_Salles.lon AS lon,
UE.idPromotion AS idPromotion
FROM Cours
JOIN Type_Cours ON Type_Cours.id = Cours.idTypeCours
JOIN V_Liste_Salles ON V_Liste_Salles.id = Cours.idSalle
JOIN Intervenant ON Cours.idIntervenant = Intervenant.id
JOIN UE ON UE.id = Cours.idUE
ORDER BY idPromotion, tsDebut;

DROP VIEW IF EXISTS `V_Cours_Etudiants`;

CREATE VIEW `V_Cours_Etudiants` AS
SELECT Cours.id AS idCours, Etudiant.id AS idEtudiant
FROM Etudiant
JOIN Appartient_Etudiant_GroupeEtudiants ON Appartient_Etudiant_GroupeEtudiants.idEtudiant = Etudiant.id
JOIN Groupe_Etudiants ON Groupe_Etudiants.id = Appartient_Etudiant_GroupeEtudiants.idGroupeEtudiants
JOIN Publication ON Publication.idGroupeEtudiants = Groupe_Etudiants.id
JOIN Groupe_Cours ON Groupe_Cours.id = Publication.idGroupeCours
JOIN Appartient_Cours_GroupeCours ON Appartient_Cours_GroupeCours.idGroupeCours = Groupe_Cours.id
JOIN Cours ON Cours.id = Appartient_Cours_GroupeCours.idCours;

DROP VIEW IF EXISTS `V_Infos_Cours_Etudiants`;

CREATE VIEW `V_Infos_Cours_Etudiants` AS
SELECT V_Cours_Etudiants.idCours AS idCours, V_Cours_Etudiants.idEtudiant AS idEtudiant,
V_Infos_Cours.tsDebut as tsDebut, V_Infos_Cours.tsFin as tsFin
FROM V_Cours_Etudiants
JOIN V_Infos_Cours ON V_Infos_Cours.id = V_Cours_Etudiants.idCours
ORDER BY idEtudiant, tsDebut;

-- --------------------------------------------------------

--
-- Vue de la table `Salle`
--

DROP VIEW IF EXISTS `V_Liste_Salles`;

CREATE VIEW `V_Liste_Salles` AS
SELECT Salle.id AS id, Salle.nom AS nomSalle, Salle.nomBatiment AS nomBatiment, Salle.capacite AS capacite, Batiment.lat AS lat, Batiment.lon AS lon
FROM Salle
JOIN Batiment ON Batiment.nom = Salle.nomBatiment
ORDER BY nomBatiment, nomSalle;

-- --------------------------------------------------------

--
-- Vue de la table `Specialite`
--

DROP VIEW IF EXISTS `V_Liste_Specialite`;

CREATE VIEW `V_Liste_Specialite`
AS SELECT Specialite.id AS id, Specialite.nom AS nomSpecialite, Promotion.nom AS nomPromotion, Promotion.annee AS annee
FROM Specialite
JOIN Promotion ON Specialite.idPromotion = Promotion.id
ORDER BY annee, nomPromotion, nomSpecialite;

-- --------------------------------------------------------

--
-- Vues de la table `UE`
--

DROP VIEW IF EXISTS `V_Infos_UE`;

CREATE VIEW `V_Infos_UE` AS
SELECT UE.id AS id, UE.nom AS nom, UE.nbHeuresCours AS nbHeuresCours, UE.nbHeuresTD AS nbHeuresTD, UE.nbHeuresTP AS nbHeuresTP, UE.ECTS AS ECTS,
Intervenant.nom AS nomResponsable, Intervenant.prenom AS prenomResponsable, Intervenant.email AS emailResponsable,
Promotion.nom AS nomPromotion, Promotion.annee AS anneePromotion
FROM UE
JOIN Intervenant ON UE.idResponsable = Intervenant.id
JOIN Promotion ON UE.idPromotion = Promotion.id
ORDER BY anneePromotion, nom;
