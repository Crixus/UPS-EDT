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
(20500000, 'Dachy', 'Mathieu', 'mail6@mail.com', '', 1, 4, 1);

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

INSERT INTO `Groupe_Etudiants` (`nom`, `identifiant`, `idPromotion`) VALUES
('Promotion', '2011-M1 Informatique-Promotion', 4),
('Promotion TD1', '2011-M1 Informatique-Promotion TD1', 4),
('Promotion TD2', '2011-M1 Informatique-Promotion TD2', 4),
('Promotion TP1', '2011-M1 Informatique-Promotion TP1', 4),
('Promotion TP2', '2011-M1 Informatique-Promotion TP2', 4),
('Promotion TP3', '2011-M1 Informatique-Promotion TP3', 4),
('Promotion TP4', '2011-M1 Informatique-Promotion TP4', 4),
('Etudiants de CISI', '2011-M1 Informatique-CISI', 4),
('Etudiants de CISI TD', '2011-M1 Informatique-CISI TD', 4),
('Etudiants de CISI TP1', '2011-M1 Informatique-CISI TP1', 4),
('Etudiants de CISI TP2', '2011-M1 Informatique-CISI TP2', 4),
('Etudiants de R', '2011-M1 Informatique-R', 4);

INSERT INTO `Groupe_Cours` (`nom`, `identifiant`, `idPromotion`) VALUES
('Cours Tronc Commun', '2011-M1 Informatique-Cours Tronc Commun', 4),
('TD1 Tronc Commun', '2011-M1 Informatique-TD1 Tronc Commun', 4),
('TD2 Tronc Commun', '2011-M1 Informatique-TD2 Tronc Commun', 4),
('TP1 Tronc Commun', '2011-M1 Informatique-TP1 Tronc Commun', 4),
('TP2 Tronc Commun', '2011-M1 Informatique-TP2 Tronc Commun', 4),
('TP3 Tronc Commun', '2011-M1 Informatique-TP3 Tronc Commun', 4),
('TP4 Tronc Commun', '2011-M1 Informatique-TP4 Tronc Commun', 4),
('Cours CISI', '2011-M1 Informatique-Cours CISI', 4),
('TD CISI', '2011-M1 Informatique-TD CISI', 4),
('TP1 CISI', '2011-M1 Informatique-TP1 CISI', 4),
('TP2 CISI', '2011-M1 Informatique-TP2 CISI', 4);

INSERT INTO `Cours` (`id`, `idUE`, `idSalle`, `idIntervenant`, `idTypeCours`, `tsDebut`, `tsFin`) VALUES
(1, 2, 1, 4, 1, '2012-05-07 05:45:00', '2012-05-07 07:45:00'),
(2, 12, 3, 27, 2, '2012-05-07 08:00:00', '2012-05-07 10:00:00'),
(3, 2, 4, 4, 2, '2012-05-07 08:15:00', '2012-05-07 10:15:00'),
(4, 6, 1, 29, 1, '2012-05-07 11:30:00', '2012-05-07 14:30:00');

INSERT INTO `Appartient_Cours_GroupeCours` (`idCours`, `idGroupeCours`) VALUES
(1, 1),
(4, 1),
(2, 2),
(3, 3);

INSERT INTO `Appartient_Etudiant_GroupeEtudiants` (`idEtudiant`, `idGroupeEtudiants`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(1, 2),
(3, 2),
(5, 2),
(6, 2),
(2, 3),
(4, 3),
(1, 4),
(3, 4),
(6, 5),
(2, 6),
(4, 6),
(5, 7),
(1, 8),
(2, 8),
(3, 8),
(4, 8),
(6, 8),
(1, 9),
(2, 9),
(3, 9),
(4, 9),
(6, 9),
(1, 10),
(4, 10),
(2, 11),
(3, 11),
(6, 11);

INSERT INTO `Publication` (`idGroupeEtudiants`, `idGroupeCours`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(11, 11);