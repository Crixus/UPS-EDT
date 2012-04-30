<?php
	if (!isset($ini_array)) {
		$ini_array = parse_ini_file(dirname(__FILE__)."/../conf/config.conf", true);
	}

	// Informations de base de données
	define('MOT_DE_PASSE_ACTIF', $ini_array['mot_de_passe']["MOT_DE_PASSE_ACTIF"]);
