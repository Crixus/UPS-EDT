<?php

// Informations de base de données
include_once('../../includes/infos_bdd.php');
include_once("../../classes/class_V_Liste_Salles.php");
	
if (isset($_POST['type'])) {	
	
	$type = $_POST['type'];
	if ($type == 'update_table') {	
		if (isset($_POST['appartient']) && isset($_POST['idType_Cours']) && isset($_POST['idType_Salle'])) {

			$appartient = $_POST['appartient'];
			$idType_Cours = $_POST['idType_Cours'];
			$idType_Salle = $_POST['idType_Salle'];


			if ($appartient == 1) { //Ajout du lien dans la table Appartient_TypeSalle_TypeCours
				try {
					$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("INSERT INTO Appartient_TypeSalle_TypeCours VALUES(?, ?)");
					
					$req->execute(
						Array(					
							$idType_Salle,
							$idType_Cours
						)
					);			
				}
				catch (Exception $e) {
					echo "Erreur : ".$e->getMessage()."<br />";
				}	
			}
			else { //Suppression du lien dans la table Appartient_TypeSalle_TypeCours
				try {
					$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("DELETE FROM Appartient_TypeSalle_TypeCours WHERE idTypeSalle = ? AND idTypeCours=?;");
					$req->execute(
						Array(
							$idType_Salle,
							$idType_Cours
						)
					);
				}
				catch (Exception $e) {
					echo "Erreur : ".$e->getMessage()."<br />";
				}	
			}
		}
	}
	else {
		if (isset($_POST['idType_Cours']) && isset($_POST['idSalle'])) {
			$idType_Cours = $_POST['idType_Cours'];
			$idSalleInitial = $_POST['idSalle'];
			$tab = "";
			$liste_salle = V_Liste_Salles::liste_salles_appartenant_typeCours($idType_Cours);
			
			if ($idSalleInitial == 0) { $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo $tab."\t\t\t\t\t<option value=\"0\" $selected>----- Inconnu -----</option>\n";
			foreach ($liste_salle as $idSalle) {
				$_salle = new V_listeSalles($idSalle);
				$nomBatiment = $_salle->getNomBatiment();
				$nomSalle = $_salle->getNomSalle();
				if ($idSalleInitial == $idSalle) { $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo $tab."\t\t\t\t\t<option value=\"$idSalle\" $selected>$nomBatiment $nomSalle</option>\n";
			}
		}
	}
}
?>
