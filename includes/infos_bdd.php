<?php
	if (!isset($ini_array)) {
		$ini_array = parse_ini_file(dirname(__FILE__)."/../conf/config.conf", true);
	}

	// Informations de base de données
	define('DB_HOST', $ini_array['base_de_donnees']["DB_HOST"]);
	define('DB_NAME', $ini_array['base_de_donnees']["DB_NAME"]);
	define('DB_LOGIN', $ini_array['base_de_donnees']["DB_LOGIN"]);
	define('DB_PASSWORD', $ini_array['base_de_donnees']["DB_PASSWORD"]);
