SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

INSERT INTO `Promotion` (`nom`, `annee`, `tsDebut`, `tsFin`) VALUES
('L1 Informatique', 2011, '2011-09-01 00:00:00', '2012-05-31 00:00:00'),
('L2 Informatique', 2011, '2011-09-01 00:00:00', '2012-05-31 00:00:00'),
('L3 Informatique', 2011, '2011-09-01 00:00:00', '2012-05-31 00:00:00'),
('M1 Informatique', 2011, '2011-09-01 00:00:00', '2012-05-31 00:00:00'),
('M2 Informatique', 2011, '2011-09-01 00:00:00', '2012-05-31 00:00:00');

INSERT INTO `Specialite` (`idPromotion`, `nom`, `intitule`) VALUES
(4, 'DL', 'Développement Logiciel'),
(4, 'IHM', 'Interaction Homme-Machine'),
(4, 'IA&RF', 'Intelligence Artificielle et Reconnaissance des Formes'),
(4, 'IM', 'Images et Multimédia'),
(4, 'CAMSI', 'Conception d''Architectures de Machines et de Systèmes Informatiques');

INSERT INTO `Etudiant` (`numeroEtudiant`, `nom`, `prenom`, `email`, `telephone`, `notificationsActives`, `idPromotion`, `idSpecialite`) VALUES
(20000000, 'Curny', 'Jérémy', 'mail1@mail.com', '', 1, 4, 1),
(20100000, 'Letourneur', 'Anthony', 'mail2@mail.com', '', 1, 4, 1),
(20200000, 'Chapeyroux', 'Sebastien', 'mail3@mail.com', '', 1, 4, 1),
(20300000, 'Francois', 'Marie', 'mail4@mail.com', '', 1, 4, 2),
(204000000, 'Salvatore', 'Benoit', 'mail5@mail.com', '', 1, 4, 3),
(20500000, 'Dachy', 'Mathieu', 'mail6@mail.com', '', 1, 4, 1),
(20600000, 'Carassus', 'Vincent', 'mail7@mail.com', '', 1, 4, 1),
(20700000, 'Sans D''Aggut', 'Thomas', 'mail8@mail.com', '', 1, 4, 2),
(20800000, 'Collet', 'Lois', 'mail9@mail.com', '', 1, 4, 1),
(20900000, 'Weissebeck', 'Thierry', 'mail10@mail.com', '', 1, 4, 1);

INSERT INTO `Appartient_Salle_TypeSalle` (`idSalle`, `idTypeSalle`) VALUES
(1, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 1),
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(8, 3),
(9, 3),
(10, 3),
(11, 3),
(12, 3),
(13, 3),
(14, 3),
(15, 1),
(15, 2),
(16, 1),
(16, 2);

INSERT INTO `Appartient_TypeSalle_TypeCours` (`idTypeSalle`, `idTypeCours`) VALUES
(1, 1),
(1, 2),
(1, 4),
(1, 5),
(1, 6),
(2, 1),
(2, 2),
(2, 6),
(3, 3),
(3, 6);

INSERT INTO `Intervenant` (`nom`, `prenom`, `email`, `telephone`, `notificationsActives`, `actif`) VALUES
('Palanque', 'Philippe', 'mailPalanque@mail.com', '', 1, 1),
('Chaplier', 'Claire', 'mailChaplier@mail.com', '', 1, 1),
('Bashoun', 'Jean-Paul', 'mailBashoun@mail.com', '', 1, 1),
('Massié', 'Henri', 'mailMassie@mail.com', '', 1, 1),
('Maurel', 'Christine', 'mailMaurel@mail.com', '', 1, 1),
('M''Zoughi', 'Abdelaziz', 'mailMZoughi@mail.com', '', 1, 1),
('Hameurlain', 'AbdelKader', 'mailHameurlain@mail.com', '', 1, 1),
('Cayrol', 'Claudette', 'mailCayrol@mail.com', '', 1, 1),
('Paulin', 'Mathias', 'mailPaulin@mail.com', '', 1, 1),
('Mammeri', 'Zoubir', 'mailMammeri@mail.com', '', 1, 1),
('Regnier', 'Pierre', 'mailRegnier@mail.com', '', 1, 1),
('Lalande', 'Séverine', 'mailLalande@mail.com', '', 1, 1),
('Cherbonneau', 'Bernard', 'mailCherbonneau@mail.com', '', 1, 1),
('Ober', 'Ilena', 'mailOber@mail.com', '', 1, 1),
('Mengin', 'Jerome', 'mailMengin@mail.com', '', 1, 1),
('Gleizes', 'Marie-Pierre', 'mailGleizes@mail.com', '', 1, 1),
('Lagasquie', 'Marie-Christine', 'mailLagasquie@mail.com', '', 1, 1),
('Bannay', 'Florence', 'mailBannay@mail.com', '', 1, 1),
('Bodeveix', 'Jean-Paul', 'mailBodeveix@mail.com', '', 1, 1),
('Joly', 'Philippe', 'mailJoly@mail.com', '', 1, 1),
('Sainrat', 'Pascal', 'mailSainrat@mail.com', '', 1, 1),
('Rochange', 'Christine', 'mailRochange@mail.com', '', 1, 1),
('Arcangeli', 'Jean-Paul', 'mailArcangeli@mail.com', '', 1, 1),
('Da Costa', 'Georges', 'mailDaCosta@mail.com', '', 1, 1),
('Pierson', 'Jean-Marc', 'mailPierson@mail.com', '', 1, 1),
('Jorda', 'Jacques', 'mailJorda@mail.com', '', 1, 1),
('Migeon', 'Frédéric', 'mailMigeon@mail.com', '', 1, 1),
('Winckler', 'Marco Antonio', 'mailWinckler@mail.com', '', 1, 1),
('André-Obrecht', 'Régine', 'mailAndreObrecht@mail.com', '', 1, 1),
('Barthe', 'Loïc', 'mailBarthe@mail.com', '', 1, 1),
('Kouame', 'Denis', 'mailKouame@mail.com', '', 1, 1),
('Crouzil', 'Alain', 'mailCrouzil@mail.com', '', 1, 1),
('Senac', 'Christine', 'mailSenac@mail.com', '', 1, 1);

INSERT INTO `UE` (`nom`, `intitule`, `nbHeuresCours`, `nbHeuresTD`, `nbHeuresTP`, `ECTS`, `idResponsable`, `idPromotion`) VALUES
('MCPR', 'Modèles et Concepts du Parralèlisme et de la Répartition', 12, 12, 12, 3, 3, 4),
('MCPOO', 'Modelisation, Conception et Programmation Orientées Objet', 10, 8, 12, 3, 4, 4),
('TL', 'Traduction des Langages', 16, 14, 0, 3, 5, 4),
('APP', 'Architecture et Programmation Parallèle', 16, 14, 0, 3, 6, 4),
('SBDR', 'Systèmes de Bases de Données Relationnels', 14, 10, 6, 3, 7, 4),
('TRP', 'Techniques de base pour la Résolution des Problèmes', 10, 10, 10, 3, 8, 4),
('OIM', 'Outils Informatiques pour le Multimédia', 10, 8, 12, 3, 9, 4),
('R', 'Réseaux', 12, 10, 8, 3, 10, 4),
('CISI', 'Conception Informatique des Systèmes Intéractifs', 10, 10, 10, 3, 1, 4),
('RC', 'Représentation des Connaissances', 14, 16, 0, 3, 11, 4),
('MàN', 'Mise à Niveau', 20, 10, 0, 0, 0, 4),
('CGE', 'Communication et Gestion des entreprises', 16, 14, 0, 3, 12, 4),
('ANGLAIS', 'Anglais', 0, 24, 0, 3, 2, 4),
('MA', 'Méthodes Agiles', 14, 10, 6, 3, 13, 4),
('DCLL', 'Développement Collaboratif et Logiciels Libres', 12, 6, 12, 3, 13, 4),
('MPI', 'Management de Projets Informatiques', 14, 16, 0, 3, 13, 4),
('IAWS', 'Interopérabilité des Applications et introduction aux Web Services', 16, 0, 14, 3, 27, 4),
('AL', 'Architecture Logicielle', 16, 12, 8, 3, 23, 4),
('MCPOOA', 'Modelisation, Conception et Programmation Orientées Objet - Approfondissement ', 14, 8, 8, 3, 4, 4),
('JEE', 'architectures multi-couches et développement avec java JEE', 18, 0, 18, 3, 27, 4),
('IHMUL', 'introduction à l''Intéraction Homme-Machine et à l''Utilisation des Logiciels', 16, 14, 6, 3, 28, 4),
('IAA', 'Introduction àl''Apprentissage Automatique', 10, 10, 12, 3, 29, 4),
('3DIS', 'représentation et manipulation de contenus 3D, Image et Son', 10, 10, 12, 3, 30, 4),
('IG3D', 'introduction à l''Informatique Graphique 3D', 10, 10, 12, 3, 30, 4),
('ATI', 'introduction à l''Analyse et au Traitement d''Images', 10, 10, 12, 3, 31, 4),
('IAN', 'Introduction à l''Audio Numérique', 10, 10, 12, 3, 33, 4),
('BDOO', 'Bases de Données Orientées Objets', 15, 8, 8, 3, 0, 4),
('BDPR', 'Bases de Données Parallèles et Réparties', 14, 10, 6, 3, 0, 4),
('IR', 'Interconnexion de Réseaux', 14, 10, 6, 3, 0, 4),
('SPR', 'Surveillance et Protection des Réseaux', 15, 9, 6, 3, 0, 4),
('PROJET', 'Projet', 20, 10, 0, 3, 13, 4),
('TER', 'Travail Encadré de Recherche', 8, 10, 0, 3, 14, 4),
('STAGE', 'Stage MASTER 1ère Année', 0, 0, 0, 3, 5, 4),
('TIA', 'Techniques avancées de l''Intelligence Artificielle', 15, 0, 15, 3, 15, 4),
('RCP', 'Résolution Collective de Problèmes', 10, 10, 12, 3, 16, 4),
('RAIS', 'RAIsonnement et Connaissances', 15, 15, 0, 3, 17, 4),
('RO', 'Recherche Operationnelle', 12, 12, 6, 3, 18, 4),
('LSL', 'Logique construtive et Sémantique des Langages de programmation', 16, 8, 8, 3, 19, 4),
('AHP', 'Architecture Haute Performance', 16, 16, 0, 3, 6, 4),
('AS', 'Architecture Specialisées', 3, 20, 12, 3, 21, 4),
('ODMC', 'Outils de Develloppement, Méthodes de Conception architectures', 16, 0, 16, 3, 22, 4),
('NSTR', 'Noyaux Systèmes Temps Réel', 10, 8, 14, 3, 10, 4),
('TAS', 'Techniques Avancées des Systèmes d''exploitation', 10, 8, 14, 3, 24, 4),
('CSR', 'Conception des Systémes Répartis', 10, 8, 14, 3, 25, 4),
('OPP', 'Optimisation de Programmes Parallèles', 12, 20, 0, 3, 26, 4),
('ASDSI', 'Algorithmes et Structures de Données pour la Synthèse d''Images', 10, 10, 12, 3, 9, 4),
('IVO', 'Introduction à la Vision par Ordinateur', 10, 10, 12, 3, 32, 4);


INSERT INTO `Cours` (`idUE`, `idSalle`, `idIntervenant`, `idTypeCours`, `tsDebut`, `tsFin`) VALUES
(5, 4, 1, 1, '2012-02-06 07:45:00', '2012-02-06 09:45:00'),
(10, 0, 1, 1, '2012-02-06 10:00:00', '2012-02-06 12:00:00'),
(9, 0, 1, 1, '2012-02-07 07:45:00', '2012-02-07 09:45:00'),
(6, 15, 1, 1, '2012-02-07 13:30:00', '2012-02-07 15:30:00'),
(5, 0, 1, 3, '2012-02-07 08:45:00', '2012-02-07 10:45:00'),
(6, 15, 1, 2, '2012-02-07 09:30:00', '2012-02-07 12:45:00'),
(1, 15, 1, 4, '2012-02-07 09:45:00', '2012-02-07 12:45:00'),
(15, 0, 4, 3, '2012-03-27 07:45:00', '2012-03-27 09:45:00'),
(14, 5, 4, 1, '2012-03-28 13:30:00', '2012-03-28 15:30:00'),
(17, 10, 0, 3, '2012-03-28 07:45:00', '2012-03-28 09:45:00'),
(20, 13, 27, 3, '2012-03-28 10:00:00', '2012-03-28 12:00:00'),
(21, 7, 0, 2, '2012-03-29 13:30:00', '2012-03-29 15:30:00'),
(20, 8, 27, 3, '2012-03-29 13:30:00', '2012-03-29 15:30:00'),
(28, 7, 2, 1, '2012-03-31 12:00:00', '2012-03-31 14:00:00'),
(13, 7, 2, 1, '2012-04-07 12:00:00', '2012-04-07 14:00:00'),
(13, 7, 2, 1, '2012-04-14 12:00:00', '2012-04-14 14:00:00'),
(13, 7, 2, 1, '2012-04-21 12:00:00', '2012-04-21 14:00:00'),
(18, 4, 23, 2, '2012-04-04 07:45:00', '2012-04-04 09:45:00'),
(18, 4, 23, 2, '2012-04-11 07:45:00', '2012-04-11 09:45:00'),
(18, 4, 23, 2, '2012-04-18 07:45:00', '2012-04-18 09:45:00'),
(18, 4, 23, 2, '2012-04-25 07:45:00', '2012-04-25 09:45:00'),
(18, 4, 23, 2, '2012-05-02 07:45:00', '2012-05-02 09:45:00'),
(20, 14, 27, 3, '2012-04-11 13:30:00', '2012-04-11 15:30:00'),
(20, 14, 27, 3, '2012-04-18 13:30:00', '2012-04-18 15:30:00'),
(20, 14, 27, 3, '2012-04-25 13:30:00', '2012-04-25 15:30:00'),
(20, 14, 27, 3, '2012-05-02 13:30:00', '2012-05-02 15:30:00');

INSERT INTO `Groupe_Etudiants` (`nom`, `identifiant`, `idPromotion`) VALUES
('Promotion', '2011-M1 Informatique-Promotion', 4),
('Etudiants de CISI', '2011-M1 Informatique-CISI', 4),
('Etudiants de R', '2011-M1 Informatique-R', 4),
('Etudiants de RC', '2011-M1 Informatique-RC', 4);

INSERT INTO `Groupe_Cours` (`nom`, `identifiant`, `idPromotion`) VALUES
('Cours Tronc Commun', '2011-M1 Informatique-Tronc Commun-Cours', 4),
('Cours RC', '2011-M1 Informatique-RC-Cours', 4),
('Cours CISI', '2011-M1 Informatique-CISI-Cours', 4);

INSERT INTO `Appartient_Etudiant_GroupeEtudiants` (`idEtudiant`, `idGroupeEtudiants`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(4, 1),
(4, 2),
(5, 1),
(5, 4),
(6, 1),
(6, 2);

INSERT INTO `Appartient_Cours_GroupeCours` (`idCours`, `idGroupeCours`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 1),
(5, 1),
(6, 1),
(7, 1);

INSERT INTO `Publication` (`idGroupeEtudiants`, `idGroupeCours`) VALUES
(1, 1),
(2, 3),
(4, 2);
