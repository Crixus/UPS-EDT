<?php

// Informations de base de données
include_once('../../includes/infos_bdd.php');
include_once("../../classes/class_V_Infos_Etudiant.php");
include_once("../../classes/class_Groupe_Etudiants.php");


if (isset($_POST['appartient']) && isset($_POST['type'])) {

	$appartient = $_POST['appartient'];
	$type = $_POST['type'];
	
	if ($type == 'etudiant') {	
	
		if (isset($_POST['idEtudiant']) && isset($_POST['idGroupeEtudiants'])) {

			$idEtudiant = $_POST['idEtudiant'];
			$idGroupeEtudiants = $_POST['idGroupeEtudiants'];
			
			if ((V_Infos_Etudiant::existe_etudiant($idEtudiant)) && (Groupe_Etudiants::existe_groupeEtudiants($idGroupeEtudiants))) { //Test de sécurité
				if ($appartient == 1) { //Ajout du lien dans la table Appartient_Etudiant_GroupeEtudiants
					try {
						$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
						$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
						$bdd->query("SET NAMES utf8");
						$req = $bdd->prepare("INSERT INTO Appartient_Etudiant_GroupeEtudiants VALUES(?, ?)");
						
						$req->execute(
							Array(					
								$idEtudiant,
								$idGroupeEtudiants
							)
						);			
					}
					catch (Exception $e) {
						echo "Erreur : ".$e->getMessage()."<br />";
					}	
				}
				else { //Suppression du lien dans la table Appartient_Etudiant_GroupeEtudiants
					try {
						$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
						$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
						$bdd->query("SET NAMES utf8");
						$req = $bdd->prepare("DELETE FROM Appartient_Etudiant_GroupeEtudiants WHERE idEtudiant=? AND idGroupeEtudiants=?;");
						$req->execute(
							Array(
								$idEtudiant,
								$idGroupeEtudiants
							)
						);
					}
					catch (Exception $e) {
						echo "Erreur : ".$e->getMessage()."<br />";
					}	
				}
			}
			else
				echo 0;
		}
	}
	else if ($type == 'promotion') {
	
		if (isset($_POST['idGroupeEtudiants']) && isset($_POST['idPromotion'])) {

			$idGroupeEtudiants = $_POST['idGroupeEtudiants'];
			
			if (Groupe_Etudiants::existe_groupeEtudiants($idGroupeEtudiants)) { //Test de sécurité
				$idPromotion = $_POST['idPromotion'];
				$liste_etudiants = V_Infos_Etudiant::liste_etudiant($idPromotion);
				
				foreach ($liste_etudiants as $idEtudiant) {
				
					try {
						$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
						$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
						$bdd->query("SET NAMES utf8");
						$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM Appartient_Etudiant_GroupeEtudiants WHERE idEtudiant=? AND idGroupeEtudiants=?;");
						$req->execute(
							Array(
								$idEtudiant,
								$idGroupeEtudiants
							)
						);
						$ligne = $req->fetch();
						$req->closeCursor();
						
						$nb = $ligne["nb"];
						
						if ($appartient == 1) { //Ajout du lien dans la table Appartient_Etudiant_GroupeEtudiants
							if ($nb == 0) { 					
								try {
									$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
									$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
									$bdd->query("SET NAMES utf8");
									$req = $bdd->prepare("INSERT INTO Appartient_Etudiant_GroupeEtudiants VALUES(?, ?)");
									
									$req->execute(
										Array(				
											$idEtudiant,
											$idGroupeEtudiants
										)
									);			
								}
								catch (Exception $e) {
									echo "Erreur : ".$e->getMessage()."<br />";
								}	
							}					
						}
						else { //Suppression du lien dans la table Appartient_Etudiant_GroupeEtudiants
							if ($nb == 1) { 					
								try {
									$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
									$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
									$bdd->query("SET NAMES utf8");
									$req = $bdd->prepare("DELETE FROM Appartient_Etudiant_GroupeEtudiants WHERE idEtudiant=? AND idGroupeEtudiants=?;");
									$req->execute(
										Array(
											$idEtudiant,
											$idGroupeEtudiants
										)
									);
								}
								catch (Exception $e) {
									echo "Erreur : ".$e->getMessage()."<br />";
								}
							}	
						}
					}
					catch (Exception $e) {
						echo "Erreur : ".$e->getMessage()."<br />";
					}				
				}
			}
			else 
				echo 0;
		}
	}
}
?>



