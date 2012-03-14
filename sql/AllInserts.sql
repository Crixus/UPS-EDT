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
-- idPromotion 4 = Master 1 Informatique
(4, 'DL', 'Développement Logiciel'),
(4, 'IHM', 'Interaction Homme-Machine'),
(4, 'IA&RF', 'Intelligence Artificielle et Reconnaissance des Formes'),
(4, 'IM', 'Images et Multimédia'),
(4, 'CAMSI', 'Conception d''Architectures de Machines et de Systèmes Informatiques');

INSERT INTO `Etudiant` (`numeroEtudiant`, `nom`, `prenom`, `email`, `telephone`, `notificationsActives`, `idPromotion`, `idSpecialite`) VALUES
(1, 'Curny', 'Jérémy', 'jeremy.curny@gmail.com', '', 1, 4, 1),
(2, 'Letourneur', 'Anthony', 'mail1@mail.com', '', 1, 4, 1),
(3, 'Chapeyroux', 'Sebastien', 'chapeyroux.sebastien@gmail.com', '', 1, 4, 1),
(4, 'Francois', 'Marie', 'mail3@mail.com', '', 1, 4, 2),
(5, 'Salvatore', 'Benoit', 'mail4@mail.com', '', 1, 4, 3),
(6, 'Dachy', 'Mathieu', 'mail5@mail.com', '', 1, 4, 1),
(20800000, 'Carassus', 'Vincent', 'carassus@gmail.com', '', 1, 4, 1),
(155, 'Sans D''Aggut', 'Thomas', 'mailThomas', '', 1, 4, 2),
(12, 'Collet', 'Lois', 'mailCollet@mail.com', '', 1, 4, 1),
(20700251, 'Weissebeck', 'Thierry', 'mailThierry@mail.fr', '', 1, 4, 1);

INSERT INTO `Batiment` (`nom`, `lat`, `lon`) VALUES
("1A", 43.562186, 1.467211),
("1CN", 43.561237, 1.466922),
("1R1", 43.561222, 1.466289),
("1R2", 43.561751, 1.466407),
("1R3", 43.561681, 1.465945),
("1TP1", 43.561906, 1.466879),
("2A", NULL, NULL),
("2R1", NULL, NULL),
("2TP1", NULL, NULL),
("2TP2", NULL, NULL),
("2TP3", NULL, NULL),
("3A", NULL, NULL),
("3PN", NULL, NULL),
("3R1", NULL, NULL),
("3R2", 43.561136, 1.468745),
("3R3", NULL, NULL),
("3SC", NULL, NULL),
("3TP1", NULL, NULL),
("4A", 43.558369, 1.470087),
("4R3", NULL, NULL),
("4TP1", NULL, NULL),
("4TP2", NULL, NULL),
("4TP4", NULL, NULL),
("U1", 43.560343, 1.47029),
("U2", 43.561284, 1.470537),
("U3", 43.562038, 1.470076),
("U4", 43.562722, 1.469378);


INSERT INTO `Salle` (`nom`, `nomBatiment`, `capacite`) VALUES
('A16', '1A', 30),
('010', '1R1', 30),
('017', '1R1', 30),
('B7', '1TP1', 30),
('BMIG', '1TP1', 30),
('213', 'U2', 30),
('112', 'U3', 20),
('210', 'U3', 20),
('211', 'U3', 20),
('212', 'U3', 20),
('213', 'U3', 20),
('214', 'U3', 20),
('215', 'U3', 20),
('216', 'U3', 20),
('302', 'U4', 30),
('312', 'U4', 30);

INSERT INTO `Type_Cours` (`nom`, `idStyle`) VALUES
('Cours', 0),
('TD', 0),
('TP', 0),
('Examen', 0),
('Reunion', 0),
('Autre', 0);

INSERT INTO `Type_Salle` (`nom`) VALUES
('Amphi'),
('Salle de TD'),
('Salle de TP');

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
('LV', 'Anglais', 0, 24, 0, 3, 2, 4),
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


INSERT INTO `Cours` (`id`, `idUE`, `idSalle`, `idIntervenant`, `idTypeCours`, `tsDebut`, `tsFin`) VALUES
(1, 5, 4, 1, 1, '2012-02-06 06:45:00', '2012-02-06 08:45:00'),
(2, 10, 1, 1, 1, '2012-02-06 09:00:00', '2012-02-06 11:00:00'),
(3, 9, 1, 1, 1, '2012-02-07 06:45:00', '2012-02-07 08:45:00'),
(4, 6, 15, 1, 1, '2012-02-07 12:30:00', '2012-02-07 14:30:00'),
(5, 5, 1, 1, 3, '2012-02-07 07:45:00', '2012-02-07 09:45:00'),
(6, 6, 15, 1, 2, '2012-02-07 08:30:00', '2012-02-07 11:45:00'),
(7, 1, 15, 1, 4, '2012-02-07 08:45:00', '2012-02-07 11:45:00');

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


INSERT INTO `Utilisateur` (`login`, `motDePasse`, `type`, `idCorrespondant`) VALUES
('jeremy_curny', '1a1dc91c907325c69271ddf0c944bc72', 'Etudiant', 1),
('anthony_letourneur', '1a1dc91c907325c69271ddf0c944bc72', 'Etudiant', 2),
('sebastien_chapeyroux', '1a1dc91c907325c69271ddf0c944bc72', 'Etudiant', 3),
('francois_marie', '1a1dc91c907325c69271ddf0c944bc72', 'Etudiant', 4),
('benoit_salvatore', '1a1dc91c907325c69271ddf0c944bc72', 'Etudiant', 5),
('mathieu_dachy', '1a1dc91c907325c69271ddf0c944bc72', 'Etudiant', 6),
('philippe_palanque', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 1),
('claire_chaplier', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 2),
('vincent_carassus', '1a1dc91c907325c69271ddf0c944bc72', 'Etudiant', 7),
('jean-paul_bashoun', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 3),
('henri_massi', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 4),
('thomas_sans_d''aggut', '1a1dc91c907325c69271ddf0c944bc72', 'Etudiant', 8),
('lois_collet', '1a1dc91c907325c69271ddf0c944bc72', 'Etudiant', 9),
('thierry_weissebeck', '1a1dc91c907325c69271ddf0c944bc72', 'Etudiant', 10),
('christine_maurel', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 5),
('abdelaziz_m''zoughi', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 6),
('abdelkader_hameurlain', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 7),
('claudette_cayrol', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 8),
('mathias_paulin', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 9),
('zoubir_mammeri', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 10),
('pierre_regnier', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 11),
('s', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 12),
('bernard_cherbonneau', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 13),
('ilena_ober', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 14),
('jerome_mengin', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 15),
('marie-pierre_gleizes', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 16),
('marie-christine_lagasquie', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 17),
('florence_bannay', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 18),
('jean-paul_bodeveix', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 19),
('philippe_joly', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 20),
('pascal_sainrat', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 21),
('christine_rochange', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 22),
('jean-paul_arcangeli', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 23),
('georges_da costa', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 24),
('jean-marc_pierson', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 25),
('jacques_jorda', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 26),
('fr', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 27),
('marco antonio_winckler', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 28),
('r', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 29),
('lo', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 30),
('denis_kouame', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 31),
('alain_crouzil', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 32),
('christine_senac', '1a1dc91c907325c69271ddf0c944bc72', 'Intervenant', 33);


