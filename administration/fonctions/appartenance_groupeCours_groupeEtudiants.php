<?php

// Informations de base de données
include_once('../../includes/infos_bdd.php');
include_once("../../classes/class_V_Liste_Salles.php");
include_once("../../classes/class_Groupe_Cours.php");
include_once("../../classes/class_Groupe_Etudiants.php");

if (isset($_POST['appartient']) && isset($_POST['idGroupeCours']) && isset($_POST['idGroupeEtudiants'])) {

	$appartient = $_POST['appartient'];
	$idGroupeCours = $_POST['idGroupeCours'];
	$idGroupeEtudiants = $_POST['idGroupeEtudiants'];
	
	if ( (Groupe_Cours::existe_groupeCours($idGroupeCours)) && (Groupe_Etudiants::existe_groupeEtudiants($idGroupeEtudiants)) ) { //Test de sécurité
	
		if ($appartient == 1) { //Ajout du lien dans la table Publication
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO Publication VALUES(?, ?)");
				
				$req->execute(
					Array(					
						$idGroupeEtudiants,
						$idGroupeCours
					)
				);			
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}	
		}
		else { //Suppression du lien dans la table Publication
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM Publication WHERE idGroupeEtudiants = ? AND idGroupeCours=?;");
				$req->execute(
					Array(
						$idGroupeEtudiants,
						$idGroupeCours
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}	
		}
	}
	else 
		echo 0;
}
?>
