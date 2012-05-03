<?php
	// Informations de base de donn&eacute;es
	require_once('../includes/infos_bdd.php');
	
	require_once('../classes/class_Utils_SQL.php');
	$fail = 0;
	if (Utils_SQL::sql_from_file("../sql/AllCreates.sql")) {
		echo "<p>Base install&eacute;e</p>\n";
		if (!Utils_SQL::sql_from_file("../sql/baseTest.sql")) {
			$fail = 2;
		}
	} else {
		$fail = 1;
	}
	switch($fail){
		case 0:
			echo "<p>Base install&eacute;e, supprimer le dossier \"install/\"</p>";
			break;
		case 1: 
			echo "<p>Erreur : La base n'a pas &eacute;t&eacute; install&eacute;e : AllCreates.sql</p>";
			break;
		case 2:
			echo "<p>Erreur : La base de test n'a pas &eacute;t&eacute; install&eacute;e : baseTest.sql</p>";
			break;
	}
