<?php

// Informations de base de données
include_once('../../includes/infos_bdd.php');
	
if (isset($_POST['appartient']) && isset($_POST['type']) ) {

	$appartient = $_POST['appartient'];
	$type = $_POST['type'];
	
	if ($type == 'etudiant') {
		$idEtudiant = $_POST['idEtudiant'];
	
		try{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
			$bdd->query("SET NAMES utf8");
			$req = $bdd->prepare("UPDATE Etudiant SET notificationsActives=? WHERE id=?;");
			$req->execute(
				Array(
					$appartient,
					$idEtudiant
				)
			);
		}
		catch(Exception $e){
			echo "Erreur : ".$e->getMessage()."<br />";
		}
	}
	else if ($type == 'intervenant_notification') {
		$idIntervenant = $_POST['idIntervenant'];
	
		try{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
			$bdd->query("SET NAMES utf8");
			$req = $bdd->prepare("UPDATE Intervenant SET notificationsActives=? WHERE id=?;");
			$req->execute(
				Array(
					$appartient,
					$idIntervenant
				)
			);
		}
		catch(Exception $e){
			echo "Erreur : ".$e->getMessage()."<br />";
		}
	}
	else if ($type == 'intervenant_actif') {
		$idIntervenant = $_POST['idIntervenant'];
	
		try{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
			$bdd->query("SET NAMES utf8");
			$req = $bdd->prepare("UPDATE Intervenant SET actif=? WHERE id=?;");
			$req->execute(
				Array(
					$appartient,
					$idIntervenant
				)
			);
		}
		catch(Exception $e){
			echo "Erreur : ".$e->getMessage()."<br />";
		}
	}	
}
?>

