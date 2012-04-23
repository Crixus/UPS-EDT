<?php

// Informations de base de données
include_once('../../includes/infos_bdd.php');
	
if (isset($_POST['appartient']) && isset($_POST['idSalle']) && isset($_POST['idType_Salle'])) {

	$appartient = $_POST['appartient'];
	$idSalle = $_POST['idSalle'];
	$idType_Salle = $_POST['idType_Salle'];


	if ($appartient == 1) { //Ajout du lien dans la table Appartenance_Salle_TypeSalle
		try {
			$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
			$bdd->query("SET NAMES utf8");
			$req = $bdd->prepare("INSERT INTO Appartient_Salle_TypeSalle VALUES(?, ?)");
			
			$req->execute(
				Array(
					$idSalle,
					$idType_Salle
				)
			);			
		}
		catch (Exception $e) {
			echo "Erreur : ".$e->getMessage()."<br />";
		}	
	}
	else { //Suppression du lien dans la table Appartenance_Salle_TypeSalle
		try {
			$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
			$bdd->query("SET NAMES utf8");
			$req = $bdd->prepare("DELETE FROM Appartient_Salle_TypeSalle WHERE idSalle=? AND idTypeSalle = ?;");
			$req->execute(
				Array(
					$idSalle,
					$idType_Salle
				)
			);
		}
		catch (Exception $e) {
			echo "Erreur : ".$e->getMessage()."<br />";
		}	
	}
}
?>
