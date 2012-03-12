<?php

// Informations de base de données
include_once('../../includes/infos_bdd.php');
include_once("../../classes/class_V_Infos_Cours.php");


if (isset($_POST['appartient']) && isset($_POST['type']) ) {

	$appartient = $_POST['appartient'];
	$type = $_POST['type'];
	
	if ($type == 'cours') {	
	
		if (isset($_POST['idCours']) && isset($_POST['idGroupeCours'])) {

			$idCours = $_POST['idCours'];
			$idGroupeCours = $_POST['idGroupeCours'];
			
			if ($appartient == 1) { //Ajout du lien dans la table Appartient_Cours_GroupeCours
				try{
					$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("INSERT INTO Appartient_Cours_GroupeCours VALUES(?, ?)");
					
					$req->execute(
						Array(					
							$idCours,
							$idGroupeCours
						)
					);			
				}
				catch(Exception $e){
					echo "Erreur : ".$e->getMessage()."<br />";
				}	
			}
			else { //Suppression du lien dans la table Appartient_Cours_GroupeCours
				try{
					$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("DELETE FROM Appartient_Cours_GroupeCours WHERE idCours=? AND idGroupeCours=?;");
					$req->execute(
						Array(
							$idCours,
							$idGroupeCours
						)
					);
				}
				catch(Exception $e){
					echo "Erreur : ".$e->getMessage()."<br />";
				}	
			}
		}
	}
	else if ($type == 'promotion') {
	
		if (isset($_POST['idGroupeCours']) && isset($_POST['idPromotion'])) {

			$idGroupeCours = $_POST['idGroupeCours'];
			$idPromotion = $_POST['idPromotion'];
			$liste_cours = V_Infos_Cours::liste_cours($idPromotion);
			
			foreach($liste_cours as $idCours) {
			
				try{
					$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM Appartient_Cours_GroupeCours WHERE idCours=? AND idGroupeCours=?;");
					$req->execute(
						Array(
							$idCours,
							$idGroupeCours
						)
					);
					$ligne = $req->fetch();
					$req->closeCursor();
					
					$nb = $ligne["nb"];
					
					if ($appartient == 1) { //Ajout du lien dans la table Appartient_Cours_GroupeCours
						if ($nb == 0) { 					
							try{
								$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
								$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
								$bdd->query("SET NAMES utf8");
								$req = $bdd->prepare("INSERT INTO Appartient_Cours_GroupeCours VALUES(?, ?)");
								
								$req->execute(
									Array(
										$idCours,
										$idGroupeCours
									)
								);			
							}
							catch(Exception $e){
								echo "Erreur : ".$e->getMessage()."<br />";
							}	
						}					
					}
					else { //Suppression du lien dans la table Appartient_Cours_GroupeCours
						if ($nb == 1) { 					
							try{
								$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
								$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
								$bdd->query("SET NAMES utf8");
								$req = $bdd->prepare("DELETE FROM Appartient_Cours_GroupeCours WHERE idCours=? AND idGroupeCours=?;");
								$req->execute(
									Array(
										$idCours,
										$idGroupeCours
									)
								);
							}
							catch(Exception $e){
								echo "Erreur : ".$e->getMessage()."<br />";
							}
						}	
					}
				}
				catch(Exception $e){
					echo "Erreur : ".$e->getMessage()."<br />";
				}				
			}
		}
	}
}
?>



