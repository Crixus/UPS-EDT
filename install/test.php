<?php
	// Informations de base de données
	include_once('../includes/infos_bdd.php');
	
	// Création des tables (phase de deploiement / tests)
	define('CREER_BD', true);
	
	include_once('../classes/class_Utils_SQL.php');
	
	if (CREER_BD) {
		Utils_SQL::sql_from_file("../sql/AllCreates.sql");
		Utils_SQL::sql_from_file("../sql/baseTest.sql");
	}
