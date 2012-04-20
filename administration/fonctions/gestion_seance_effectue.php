<?php

// Informations de base de données
include_once('../../includes/infos_bdd.php');
	
if (isset($_POST['effectue']) && isset($_POST['idSeance']) ) {

	$effectue = $_POST['effectue'];
	$idSeance = $_POST['idSeance'];
	
	try{
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
		$bdd->query("SET NAMES utf8");
		$req = $bdd->prepare("UPDATE Seance SET effectue=? WHERE id=?;");
		$req->execute(
			Array(
				$effectue,
				$idSeance
			)
		);
	}
	catch(Exception $e){
		echo "Erreur : ".$e->getMessage()."<br />";
	}
}
?>

