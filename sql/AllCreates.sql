SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- On doit les faire dans l'ordre

DROP TABLE IF EXISTS `Options`;

DROP TABLE IF EXISTS `Utilisateur`;

DROP TABLE IF EXISTS `Inscription`;

DROP TABLE IF EXISTS `Publication`;
DROP TABLE IF EXISTS `Appartient_Cours_GroupeCours`;
DROP TABLE IF EXISTS `Appartient_Etudiant_GroupeEtudiants`;
DROP TABLE IF EXISTS `Groupe_Cours`;
DROP TABLE IF EXISTS `Groupe_Etudiants`;

DROP TABLE IF EXISTS `Seance`;
DROP TABLE IF EXISTS `Cours`;
DROP TABLE IF EXISTS `UE`;

DROP TABLE IF EXISTS `Creneau_Intervenant`;
DROP TABLE IF EXISTS `Intervenant`;

DROP TABLE IF EXISTS `Appartient_Salle_TypeSalle`;
DROP TABLE IF EXISTS `Appartient_TypeSalle_TypeCours`;

DROP TABLE IF EXISTS `Type_Salle`;
DROP TABLE IF EXISTS `Type_Cours`;

DROP TABLE IF EXISTS `Creneau_Salle`;
DROP TABLE IF EXISTS `Salle`;
DROP TABLE IF EXISTS `Batiment`; 

DROP TABLE IF EXISTS `Etudiant`;

DROP TABLE IF EXISTS `JourNonOuvrable`;

DROP TABLE IF EXISTS `Specialite`;

DROP TABLE IF EXISTS `Promotion`;

DROP VIEW IF EXISTS `V_Infos_Cours`;
DROP VIEW IF EXISTS `V_Cours_Etudiants`;
DROP VIEW IF EXISTS `V_Infos_Etudiant`;
DROP VIEW IF EXISTS `V_Liste_Salles`;
DROP VIEW IF EXISTS `V_Liste_Specialite`;
DROP VIEW IF EXISTS `V_Infos_UE`;
DROP VIEW IF EXISTS `V_Infos_Cours_Etudiants`;
DROP VIEW IF EXISTS `V_Infos_Seance_Promotion`;
DROP VIEW IF EXISTS `V_Cours_Promotion`;

CREATE TABLE IF NOT EXISTS `Promotion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8_bin NOT NULL,
  `annee` int(11) NOT NULL,
  `tsDebut` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tsFin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

INSERT INTO `Promotion` (`id`, `nom`, `annee`, `tsDebut`, `tsFin`) VALUES
(0, 'DEFAULT', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

CREATE TABLE IF NOT EXISTS `Specialite` (
  -- Si la Promotion est supprimée, alors la spécialité est supprimée
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idPromotion` int(11) NOT NULL,
  `nom` varchar(100) COLLATE utf8_bin NOT NULL,
  `intitule` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`nom`,`idPromotion`),
  FOREIGN KEY (idPromotion) REFERENCES Promotion(id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

INSERT INTO `Specialite` (`id`, `idPromotion`, `nom`, `intitule`) VALUES
(0, 0, 'DEFAULT', 'DEFAULT');

CREATE TABLE IF NOT EXISTS `JourNonOuvrable` (
  -- Si la Promotion est supprimée, alors le jour non ouvrable est supprimée
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL,
  `tsDebut` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tsFin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `idPromotion` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (idPromotion) REFERENCES Promotion(id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `Groupe_Cours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8_bin NOT NULL,
  `identifiant` varchar(100) COLLATE utf8_bin NOT NULL,
  `idPromotion` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`idPromotion`) REFERENCES Promotion(`id`) ON DELETE CASCADE ,
  UNIQUE (`identifiant`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `Groupe_Etudiants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8_bin NOT NULL,
  `identifiant` varchar(100) COLLATE utf8_bin NOT NULL,
  `idPromotion` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`idPromotion`) REFERENCES Promotion(`id`) ON DELETE CASCADE ,
  UNIQUE (`identifiant`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `Utilisateur` (
  -- En fait on aurait du laisser séparé, là on va se faire chier tanpis
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `motDePasse` varchar(32) NOT NULL,
  `type` varchar(32) NOT NULL,
  `idCorrespondant` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`login`),
  UNIQUE (`type`, `idCorrespondant`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

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
  FOREIGN KEY (`idSpecialite`) REFERENCES Specialite(`id`), -- Suppression = remetre à 0
  FOREIGN KEY (`idPromotion`) REFERENCES Promotion(`id`), -- Suppression = remetre à 0
  UNIQUE (`numeroEtudiant`),
  UNIQUE (`email`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

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
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=0 ;

INSERT INTO `Intervenant` (`id`, `nom`, `prenom`, `email`, `telephone`, `notificationsActives`, `actif`) VALUES
(0, 'DEFAULT', 'DEFAULT', 'DEFAULT@UPS-EDT.com', '', 0, 1);

CREATE TABLE IF NOT EXISTS `Creneau_Intervenant` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`tsDebut` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	`tsFin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	`idIntervenant` int(11) NOT NULL ,
	PRIMARY KEY (`id`),
	FOREIGN KEY (`idIntervenant`) REFERENCES Intervenant(`id`) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `UE` (
  -- Si l'intervenant est supprimé, alors l'id doit être mise à celle par defaut (a faire)
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,  
  `intitule` varchar(100) NOT NULL,
  `nbHeuresCours` double NOT NULL,
  `nbHeuresTD` double NOT NULL,
  `nbHeuresTP` double NOT NULL,
  `ECTS` double NOT NULL,
  `idResponsable` int(11) NOT NULL DEFAULT 0,
  `idPromotion` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`idResponsable`) REFERENCES Intervenant (`id`),
  FOREIGN KEY (`idPromotion`) REFERENCES Promotion (`id`) ,
  UNIQUE (`nom`, `idPromotion`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
INSERT INTO `UE` (`id`, `nom`, `intitule`, `nbHeuresCours`, `nbHeuresTD`, `nbHeuresTP`, `ECTS`, `idResponsable`, `idPromotion`) VALUES
(0, 'DEFAULT', 'DEFAULT', 0, 0, 0, 0, 0, 0);

CREATE TABLE IF NOT EXISTS `Batiment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `lat` double DEFAULT NULL,
  `lon` double DEFAULT NULL,
  PRIMARY KEY (`id`), 
  UNIQUE KEY (`nom`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=0;

INSERT INTO `Batiment` (`id`, `nom`, `lat`, `lon`) VALUES
(0, 'DEFAULT', NULL, NULL);


CREATE TABLE IF NOT EXISTS `Type_Salle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`nom`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `Salle` (
  -- Si le Batiment est modifie, on modifier le nomBatiment de la salle (clé primaire)
  -- Si le Batiment est supprimé alors on supprime la salle
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `nomBatiment` varchar(50) NOT NULL,
  `capacite` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`,`nomBatiment`),
  FOREIGN KEY `nomBatiment` (`nomBatiment`) REFERENCES Batiment(`nom`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=0;

INSERT INTO `Salle` (`id`, `nom`, `nomBatiment`, `capacite`) VALUES
(0, 'Non assigné', 'DEFAULT', 999);

CREATE TABLE IF NOT EXISTS `Creneau_Salle` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`tsDebut` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	`tsFin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
	`idSalle` int(11) NOT NULL ,
	PRIMARY KEY (`id`),
	FOREIGN KEY (`idSalle`) REFERENCES Salle(`id`) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `Type_Cours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`nom`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
INSERT INTO `Type_Cours` (`id`, `nom`) VALUES
(0, 'DEFAULT');

CREATE TABLE IF NOT EXISTS `Cours` (
   -- Si la salle est supprimée, alors on met la salle par defaut (vérifier que la salle par défaut est dans la BD)
   -- Si l'intervenant est supprimé, alors on met l'intervenant par defaut (vérifier que l'intervenant par défaut est dans le BD)
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUE` int(11) NOT NULL,
  `idSalle` int(11) NOT NULL DEFAULT 0,
  `idIntervenant` int(11) NOT NULL DEFAULT 0,
  `idTypeCours` int(11) NOT NULL,
  `tsDebut` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tsFin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`idUE`) REFERENCES UE(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`idSalle`) REFERENCES Salle(`id`),
  FOREIGN KEY (`idIntervenant`) REFERENCES Intervenant(`id`),
  FOREIGN KEY (`idTypeCours`) REFERENCES Type_Cours(`id`) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `Seance` (
   -- Si la salle est supprimée, alors on met la salle par defaut (vérifier que la salle par défaut est dans la BD)
   -- Si l'intervenant est supprimé, alors on met l'intervenant par defaut (vérifier que l'intervenant par défaut est dans le BD)
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `duree` int(11) NOT NULL,
  `effectue` int(11) NOT NULL DEFAULT 0,
  `idUE` int(11) NOT NULL DEFAULT 0,
  `idSalle` int(11) NOT NULL DEFAULT 0,
  `idIntervenant` int(11) NOT NULL DEFAULT 0,
  `idTypeCours` int(11) NOT NULL DEFAULT 0,
  `idSeancePrecedente` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`idUE`) REFERENCES UE(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`idSalle`) REFERENCES Salle(`id`),
  FOREIGN KEY (`idIntervenant`) REFERENCES Intervenant(`id`),
  FOREIGN KEY (`idTypeCours`) REFERENCES Type_Cours(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`idSeancePrecedente`) REFERENCES Seance(`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

INSERT INTO `Seance` (`id`, `nom`, `duree`, `effectue`, `idUE`, `idSalle`, `idIntervenant`, `idTypeCours`, `idSeancePrecedente`) VALUES
(0, '.....', 0, 0, 0, 0, 0, 0, 0);

CREATE TABLE IF NOT EXISTS `Publication` (
  -- Si le groupe d'etudiant est supprimé, alors on supprime la publication
  -- Si le groupe de cours est supprimé, alors on supprime la publication
  `idGroupeEtudiants` int(11) NOT NULL,
  `idGroupeCours` int(11) NOT NULL,
  PRIMARY KEY (`idGroupeEtudiants`,`idGroupeCours`),
  FOREIGN KEY (`idGroupeEtudiants`) REFERENCES Groupe_Etudiants(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`idGroupeCours`) REFERENCES Groupe_Cours(`id`) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `Appartient_Cours_GroupeCours` (
  `idCours` int(11) NOT NULL,
  `idGroupeCours` int(11) NOT NULL,
  PRIMARY KEY (`idCours`,`idGroupeCours`),
  FOREIGN KEY (`idCours`) REFERENCES Cours(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`idGroupeCours`) REFERENCES Groupe_Cours(`id`) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `Appartient_Etudiant_GroupeEtudiants` (
  `idEtudiant` int(11) NOT NULL,
  `idGroupeEtudiants` int(11) NOT NULL,
  PRIMARY KEY (`idEtudiant`,`idGroupeEtudiants`),
  FOREIGN KEY (`idEtudiant`) REFERENCES Etudiant(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`idGroupeEtudiants`) REFERENCES Groupe_Etudiants(`id`) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `Appartient_Salle_TypeSalle` (
  -- Si une salle est suprimée, alors Appartient_Salle_TypeSalle associé et supprimé
  -- Si un type de salle est suprimée, alors Appartient_Salle_TypeSalle associé et supprimé
  `idSalle` int(11) NOT NULL,
  `idTypeSalle` int(11) NOT NULL,
  PRIMARY KEY (`idSalle`,`idTypeSalle`),
  FOREIGN KEY (`idSalle`) REFERENCES Salle(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`idTypeSalle`) REFERENCES Type_Salle(`id`) ON DELETE CASCADE
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `Appartient_TypeSalle_TypeCours`(
  `idTypeSalle` int(11) NOT NULL,
  `idTypeCours` int(11) NOT NULL,
  PRIMARY KEY (`idTypeSalle`,`idTypeCours`),
  FOREIGN KEY (`idTypeSalle`) REFERENCES Type_Salle(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`idTypeCours`) REFERENCES Type_Cours(`id`) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE IF NOT EXISTS `Options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `valeur` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

INSERT INTO `Options` (`nom`, `valeur`) VALUES
("background_color_Cours", "#FFFF00"),
("background_color_TD", "#99D9EA"),
("background_color_TP", "#7092BE"),
("background_color_Examen", "#FF3333"),
("background_color_Reunion", "#FF7F27"),
("background_color_Autre", "#FFFFFF"),
("color_Cours", "#000000"),
("color_TD", "#000000"),
("color_TP", "#000000"),
("color_Examen", "#000000"),
("color_Reunion", "#000000"),
("color_Autre", "#000000");

CREATE TABLE IF NOT EXISTS `Inscription` (
  `idUE` int(11) NOT NULL,
  `idEtudiant` int(11) NOT NULL,
  PRIMARY KEY (`idUE`,`idEtudiant`),
  FOREIGN KEY (`idUE`) REFERENCES UE(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`idEtudiant`) REFERENCES Etudiant(`id`) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;


CREATE VIEW `V_Liste_Salles` AS
SELECT `Salle`.`id` AS `id`, `Salle`.`nom` AS `nomSalle`, `Salle`.`nomBatiment` AS `nomBatiment`, `Salle`.`capacite` AS `capacite`, `Batiment`.`lat` AS lat, `Batiment`.`lon` AS `lon`
FROM `Salle`
JOIN `Batiment` ON `Batiment`.`nom` = `Salle`.`nomBatiment`
ORDER BY `nomBatiment`, `nomSalle`;


CREATE VIEW `V_Infos_Cours` AS
SELECT `Cours`.`id` AS `id`, `UE`.`nom` AS `nomUE`, 
	CASE `Cours`.`idIntervenant` WHEN 0 THEN "" ELSE `Intervenant`.`nom` END AS `nomIntervenant`, 
	CASE `Cours`.`idIntervenant` WHEN 0 THEN "" ELSE `Intervenant`.`prenom` END AS `prenomIntervenant`, 
	`Cours`.`idIntervenant` AS `idIntervenant`, `Type_Cours`.`nom` AS `nomTypeCours`, `Cours`.`tsDebut` AS `tsDebut`, `Cours`.`tsFin` AS `tsFin`,
	CASE `Cours`.`idSalle` WHEN 0 THEN "" ELSE `V_Liste_Salles`.`nomSalle` END AS `nomSalle`, 
	CASE `Cours`.`idSalle` WHEN 0 THEN "" ELSE `V_Liste_Salles`.`nomBatiment` END AS `nomBatiment`, 
	CASE `Cours`.`idSalle` WHEN 0 THEN "" ELSE `V_Liste_Salles`.`lat` END AS `lat`, 	
	CASE `Cours`.`idSalle` WHEN 0 THEN "" ELSE `V_Liste_Salles`.`lon` END AS `lon`, 
	`UE`.`idPromotion` AS `idPromotion`
FROM `Cours`
JOIN `Type_Cours` ON `Type_Cours`.`id` = `Cours`.`idTypeCours`
JOIN `V_Liste_Salles` ON (`V_Liste_Salles`.`id` = `Cours`.`idSalle` OR `Cours`.`idSalle` = 0)
JOIN `Intervenant` ON (`Cours`.`idIntervenant` = `Intervenant`.`id` OR `Cours`.`idIntervenant` = 0)
JOIN `UE` ON `UE`.`id` = `Cours`.`idUE`
GROUP BY `Cours`.`id`
ORDER BY `idPromotion`, `tsDebut`;

CREATE VIEW `V_Cours_Etudiants` AS
SELECT `Cours`.`id` AS `idCours`, `Etudiant`.`id` AS `idEtudiant`
FROM `Etudiant`
JOIN `Appartient_Etudiant_GroupeEtudiants` ON `Appartient_Etudiant_GroupeEtudiants`.`idEtudiant` = `Etudiant`.`id`
JOIN `Groupe_Etudiants` ON `Groupe_Etudiants`.`id` = `Appartient_Etudiant_GroupeEtudiants`.`idGroupeEtudiants`
JOIN `Publication` ON `Publication`.`idGroupeEtudiants` = `Groupe_Etudiants`.`id`
JOIN `Groupe_Cours` ON `Groupe_Cours`.`id` = `Publication`.`idGroupeCours`
JOIN `Appartient_Cours_GroupeCours` ON `Appartient_Cours_GroupeCours`.`idGroupeCours` = `Groupe_Cours`.`id`
JOIN `Cours` ON `Cours`.`id` = `Appartient_Cours_GroupeCours`.`idCours`;



CREATE VIEW `V_Infos_Etudiant` AS
SELECT `Etudiant`.`id` AS `id`,`Etudiant`.`nom` AS `nom`,`Etudiant`.`prenom` AS `prenom`,`Etudiant`.`numeroEtudiant` AS `numeroEtudiant`,
	CASE `Etudiant`.`idSpecialite` WHEN 0 THEN "" ELSE `Specialite`.`nom` END AS `nomSpecialite`, 
	`Etudiant`.`email` AS `email`,`Etudiant`.`telephone` AS `telephone`,`Etudiant`.`notificationsActives` AS `notificationsActives`,`Etudiant`.`idPromotion` AS `idPromotion` 
FROM `Etudiant` 
JOIN `Specialite` ON (`Specialite`.`id` = `Etudiant`.`idSpecialite` OR `Etudiant`.`idSpecialite` = 0)
GROUP BY `Etudiant`.id
ORDER BY `Etudiant`.`nom`,`Etudiant`.`prenom`,`Etudiant`.`numeroEtudiant`;



CREATE VIEW `V_Liste_Specialite`
AS SELECT `Specialite`.`id` AS `id`, `Specialite`.`nom` AS `nomSpecialite`, `Promotion`.`nom` AS `nomPromotion`, `Promotion`.`annee` AS `annee`
FROM `Specialite`
JOIN `Promotion` ON `Specialite`.`idPromotion` = `Promotion`.`id`
ORDER BY `annee`, `nomPromotion`, `nomSpecialite`;

CREATE VIEW `V_Infos_UE` AS
SELECT `UE`.`id` AS `id`, `UE`.`nom` AS `nom`, `UE`.`intitule` AS `intitule`, `UE`.`nbHeuresCours` AS `nbHeuresCours`, `UE`.`nbHeuresTD` AS `nbHeuresTD`, `UE`.`nbHeuresTP` AS `nbHeuresTP`, `UE`.`ECTS` AS `ECTS`,
	CASE `UE`.`idResponsable` WHEN 0 THEN "" ELSE `Intervenant`.`nom` END AS `nomResponsable`, 
	CASE `UE`.`idResponsable` WHEN 0 THEN "" ELSE `Intervenant`.`prenom` END AS `prenomResponsable`, 
	CASE `UE`.`idResponsable` WHEN 0 THEN "" ELSE `Intervenant`.`email` END AS `emailResponsable`, 
	CASE `UE`.`idResponsable` WHEN 0 THEN "" ELSE `Intervenant`.`id` END AS `idResponsable`, 
	`Promotion`.`nom` AS `nomPromotion`, `Promotion`.`annee` AS `anneePromotion`, `UE`.`idPromotion` AS `idPromotion`
FROM `UE`
JOIN `Intervenant` ON (`UE`.`idResponsable` = `Intervenant`.`id` OR `UE`.`idResponsable` = 0)
JOIN `Promotion` ON `UE`.`idPromotion` = `Promotion`.`id`
GROUP BY `UE`.`id`, `UE`.`idPromotion`
ORDER BY `anneePromotion`, `nom`;

CREATE VIEW `V_Infos_Cours_Etudiants` AS 
SELECT `V_Cours_Etudiants`.`idCours` AS `idCours`,`V_Cours_Etudiants`.`idEtudiant` AS `idEtudiant`,`V_Infos_Cours`.`tsDebut` AS `tsDebut`,`V_Infos_Cours`.`tsFin` AS `tsFin` 
FROM `V_Cours_Etudiants` 
JOIN `V_Infos_Cours` ON `V_Infos_Cours`.`id` = `V_Cours_Etudiants`.`idCours` 
ORDER BY `V_Cours_Etudiants`.`idEtudiant`,`V_Infos_Cours`.`tsDebut`;


CREATE VIEW `V_Infos_Seance_Promotion` AS 
SELECT `Seance`.`id` AS `id`, `Seance`.`nom` AS `nom`, `Seance`.`duree` AS `duree`, `Seance`.`effectue` AS `effectue`, 
	`UE`.`nom` AS `nomUE`, `Seance`.`idUE` AS `idUE`, 
	CASE `Seance`.`idSalle` WHEN 0 THEN "" ELSE `V_Liste_Salles`.`nomSalle` END AS `nomSalle`, 
	CASE `Seance`.`idSalle` WHEN 0 THEN "" ELSE `V_Liste_Salles`.`nomBatiment` END AS `nomBatiment`, 
	`Seance`.`idSalle` AS `idSalle`,
	CASE `Seance`.`idIntervenant` WHEN 0 THEN "" ELSE `Intervenant`.`nom` END AS `nomIntervenant`, 
	CASE `Seance`.`idIntervenant` WHEN 0 THEN "" ELSE `Intervenant`.`prenom` END AS `prenomIntervenant`, 
	`Seance`.`idIntervenant` AS `idIntervenant`, 
	`Type_Cours`.`nom` AS `nomTypeCours`, `Seance`.`idTypeCours` AS `idTypeCours`, 
	`Seance`.`idSeancePrecedente` AS `idSeancePrecedente`, 
	`Promotion`.`id` AS `idPromotion`
FROM `Seance`
JOIN `UE` ON `Seance`.`idUE` = `UE`.`id`
JOIN `Intervenant` ON (`Seance`.`idIntervenant` = `Intervenant`.`id` OR `Seance`.`idIntervenant` = 0)
JOIN `V_Liste_Salles` ON (`Seance`.`idSalle` = `V_Liste_Salles`.`id` OR `Seance`.`idSalle` = 0)
JOIN `Type_Cours` ON `Type_Cours`.`id` = `Seance`.`idTypeCours`
JOIN `Promotion` ON `UE`.`idPromotion` = `Promotion`.`id`
GROUP BY `Seance`.`id`
ORDER BY `idPromotion`, `nom`;

CREATE VIEW `V_Cours_Promotion` AS
SELECT DISTINCT Cours.id AS idCours, Promotion.id AS idPromotion, Cours.tsDebut AS tsDebut, Cours.tsFin as tsFin
FROM Promotion
	JOIN Etudiant 
		ON Etudiant.idPromotion = Promotion.id
	JOIN Appartient_Etudiant_GroupeEtudiants 
		ON Appartient_Etudiant_GroupeEtudiants.idEtudiant = Etudiant.id
	JOIN Groupe_Etudiants
		ON Groupe_Etudiants.id = Appartient_Etudiant_GroupeEtudiants.idGroupeEtudiants
	JOIN Publication
		ON Publication.idGroupeEtudiants = Groupe_Etudiants.id
	JOIN Groupe_Cours 
		ON Groupe_Cours.id = Publication.idGroupeCours
	JOIN Appartient_Cours_GroupeCours
		ON Appartient_Cours_GroupeCours.idGroupeCours = Groupe_Cours.id
	JOIN Cours
		ON Cours.id = Appartient_Cours_GroupeCours.idCours
ORDER BY Cours.tsDebut;
