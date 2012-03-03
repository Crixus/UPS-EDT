SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

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

DROP TABLE IF EXISTS `Specialite`;

CREATE TABLE IF NOT EXISTS `Specialite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idPromotion` int(11) NOT NULL,
  `nom` varchar(100) COLLATE utf8_bin NOT NULL,
  `intitule` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`nom`,`idPromotion`),
  FOREIGN KEY (idPromotion) REFERENCES Promotion(id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Groupe_Administratif`;

CREATE TABLE IF NOT EXISTS `Groupe_Administratif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8_bin NOT NULL,
  `idPromotion` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`idPromotion`) REFERENCES Promotion(`id`),
  UNIQUE (`nom`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

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

DROP TABLE IF EXISTS `Intervenant`;

CREATE TABLE IF NOT EXISTS `Intervenant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `notificationsActives` tinyint(1) NOT NULL DEFAULT '1',
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

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

DROP TABLE IF EXISTS `Batiment`; 

CREATE TABLE IF NOT EXISTS `Batiment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `lat` double DEFAULT NULL,
  `lon` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`nom`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Type_Salle`;

CREATE TABLE IF NOT EXISTS `Type_Salle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

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

CREATE TABLE IF NOT EXISTS `Style` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomCouleur` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `couleurTexte` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `couleurFond` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Type_Cours`;

CREATE TABLE IF NOT EXISTS `Type_Cours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) NOT NULL,
  `idStyle` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`nom`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

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

DROP TABLE IF EXISTS `Publication`;

CREATE TABLE IF NOT EXISTS `Publication` (
  `idGroupeEtudiants` int(11) NOT NULL,
  `idGroupeCours` int(11) NOT NULL,
  PRIMARY KEY (`idGroupeEtudiants`,`idGroupeCours`),
  FOREIGN KEY (`idGroupeEtudiants`) REFERENCES Groupe_Etudiants(`id`),
  FOREIGN KEY (`idGroupeCours`) REFERENCES Groupe_Cours(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `Appartient_Cours_GroupeCours`;

CREATE TABLE IF NOT EXISTS `Appartient_Cours_GroupeCours` (
  `idCours` int(11) NOT NULL,
  `idGroupeCours` int(11) NOT NULL,
  PRIMARY KEY (`idCours`,`idGroupeCours`),
  FOREIGN KEY (`idCours`) REFERENCES Cours(`id`),
  FOREIGN KEY (`idGroupeCours`) REFERENCES Groupe_Cours(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `Appartient_Etudiant_GroupeAdministratif`;

CREATE TABLE IF NOT EXISTS `Appartient_Etudiant_GroupeAdministratif` (
  `idEtudiant` int(11) NOT NULL,
  `idGroupeAdministratif` int(11) NOT NULL,
  PRIMARY KEY (`idEtudiant`,`idGroupeAdministratif`),
  FOREIGN KEY (`idEtudiant`) REFERENCES Etudiant(`id`),
  FOREIGN KEY (`idGroupeAdministratif`) REFERENCES Groupe_Administratif(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `Appartient_Etudiant_GroupeEtudiants`;

CREATE TABLE IF NOT EXISTS `Appartient_Etudiant_GroupeEtudiants` (
  `idEtudiant` int(11) NOT NULL,
  `idGroupeEtudiants` int(11) NOT NULL,
  PRIMARY KEY (`idEtudiant`,`idGroupeEtudiants`),
  FOREIGN KEY (`idEtudiant`) REFERENCES Etudiant(`id`),
  FOREIGN KEY (`idGroupeEtudiants`) REFERENCES Groupe_Etudiants(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



DROP TABLE IF EXISTS `Appartient_Salle_TypeSalle`;

CREATE TABLE IF NOT EXISTS `Appartient_Salle_TypeSalle` (
  `idSalle` int(11) NOT NULL,
  `idTypeSalle` int(11) NOT NULL,
  PRIMARY KEY (`idSalle`,`idTypeSalle`),
  FOREIGN KEY (`idSalle`) REFERENCES Salle(`id`),
  FOREIGN KEY (`idTypeSalle`) REFERENCES Type_Salle(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Appartient_TypeSalle_TypeCours`;

CREATE TABLE IF NOT EXISTS `Appartient_TypeSalle_TypeCours`(
  `idTypeSalle` int(11) NOT NULL,
  `idTypeCours` int(11) NOT NULL,
  PRIMARY KEY (`idTypeSalle`,`idTypeCours`),
  FOREIGN KEY (`idTypeSalle`) REFERENCES Type_Salle(`id`),
  FOREIGN KEY (`idTypeCours`) REFERENCES Type_Cours(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `Options`;

CREATE TABLE IF NOT EXISTS `Options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `valeur` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

INSERT INTO `Options` (`nom`, `valeur`) VALUES
("background_color_Cours", "#96C8FF"),
("background_color_TD", "#FFFFFF"),
("background_color_TP", "#FFFFFF"),
("background_color_Examen", "#FF3333"),
("background_color_Reunion", "#FFFFFF"),
("background_color_Autre", "#FFFFFF");

DROP TABLE IF EXISTS `Inscription`;

CREATE TABLE IF NOT EXISTS `Inscription` (
  `idUE` int(11) NOT NULL,
  `idEtudiant` int(11) NOT NULL,
  PRIMARY KEY (`idUE`,`idEtudiant`),
  FOREIGN KEY (`idUE`) REFERENCES UE(`id`),
  FOREIGN KEY (`idEtudiant`) REFERENCES Etudiant(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;


DROP VIEW IF EXISTS `V_Infos_Cours`;

CREATE VIEW `V_Infos_Cours` AS
SELECT Cours.id AS id, UE.nom AS nomUE, 
	CASE Cours.idIntervenant WHEN 0 THEN "" ELSE Intervenant.nom END AS nomIntervenant, 
	CASE Cours.idIntervenant WHEN 0 THEN "" ELSE Intervenant.prenom END AS prenomIntervenant, 
	Cours.idIntervenant AS idIntervenant, Type_Cours.nom AS nomTypeCours, Cours.tsDebut as tsDebut, Cours.tsFin AS tsFin,
	CASE Cours.idSalle WHEN 0 THEN "" ELSE V_Liste_Salles.nomSalle END AS nomSalle, 
	CASE Cours.idSalle WHEN 0 THEN "" ELSE V_Liste_Salles.nomBatiment END AS nomBatiment, 
	CASE Cours.idSalle WHEN 0 THEN "" ELSE V_Liste_Salles.lat END AS lat, 	
	CASE Cours.idSalle WHEN 0 THEN "" ELSE V_Liste_Salles.lon END AS lon, 
UE.idPromotion AS idPromotion
FROM Cours
JOIN Type_Cours ON Type_Cours.id = Cours.idTypeCours
JOIN V_Liste_Salles ON (V_Liste_Salles.id = Cours.idSalle OR Cours.idSalle = 0)
JOIN Intervenant ON (Cours.idIntervenant = Intervenant.id OR Cours.idIntervenant = 0)
JOIN UE ON UE.id = Cours.idUE
GROUP BY Cours.id
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

DROP VIEW IF EXISTS `V_Infos_Etudiant`;

CREATE VIEW `V_Infos_Etudiant` AS
SELECT `Etudiant`.`id` AS `id`,`Etudiant`.`nom` AS `nom`,`Etudiant`.`prenom` AS `prenom`,`Etudiant`.`numeroEtudiant` AS `numeroEtudiant`,
	CASE `Etudiant`.`idSpecialite` WHEN 0 THEN "" ELSE `Specialite`.`nom` END AS `nomSpecialite`, 
	`Etudiant`.`email` AS `email`,`Etudiant`.`telephone` AS `telephone`,`Etudiant`.`notificationsActives` AS `notificationsActives`,`Etudiant`.`idPromotion` AS `idPromotion` 
FROM `Etudiant` 
JOIN `Specialite` ON (`Specialite`.`id` = `Etudiant`.`idSpecialite` OR `Etudiant`.`idSpecialite` = 0)
GROUP BY `Etudiant`.id
ORDER BY `Etudiant`.`nom`,`Etudiant`.`prenom`,`Etudiant`.`numeroEtudiant`;

DROP VIEW IF EXISTS `V_Liste_Salles`;

CREATE VIEW `V_Liste_Salles` AS
SELECT Salle.id AS id, Salle.nom AS nomSalle, Salle.nomBatiment AS nomBatiment, Salle.capacite AS capacite, Batiment.lat AS lat, Batiment.lon AS lon
FROM Salle
JOIN Batiment ON Batiment.nom = Salle.nomBatiment
ORDER BY nomBatiment, nomSalle;

DROP VIEW IF EXISTS `V_Liste_Specialite`;

CREATE VIEW `V_Liste_Specialite`
AS SELECT Specialite.id AS id, Specialite.nom AS nomSpecialite, Promotion.nom AS nomPromotion, Promotion.annee AS annee
FROM Specialite
JOIN Promotion ON Specialite.idPromotion = Promotion.id
ORDER BY annee, nomPromotion, nomSpecialite;

DROP VIEW IF EXISTS `V_Infos_UE`;

CREATE VIEW `V_Infos_UE` AS
SELECT UE.id AS id, UE.nom AS nom, UE.intitule AS intitule, UE.nbHeuresCours AS nbHeuresCours, UE.nbHeuresTD AS nbHeuresTD, UE.nbHeuresTP AS nbHeuresTP, UE.ECTS AS ECTS,
	CASE UE.idResponsable WHEN 0 THEN "" ELSE Intervenant.nom END AS nomResponsable, 
	CASE UE.idResponsable WHEN 0 THEN "" ELSE Intervenant.prenom END AS prenomResponsable, 
	CASE UE.idResponsable WHEN 0 THEN "" ELSE Intervenant.email END AS emailResponsable, 
	CASE UE.idResponsable WHEN 0 THEN "" ELSE Intervenant.id END AS idResponsable, 
	Promotion.nom AS nomPromotion, Promotion.annee AS anneePromotion, UE.idPromotion AS idPromotion
FROM UE
JOIN Intervenant ON (UE.idResponsable = Intervenant.id OR UE.idResponsable = 0)
JOIN Promotion ON UE.idPromotion = Promotion.id
GROUP BY UE.id
ORDER BY anneePromotion, nom;
