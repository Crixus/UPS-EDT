<?php
	if (!isset($ini_array)) {
		$ini_array = parse_ini_file(dirname(__FILE__)."/../conf/config.conf", true);
	}

	// Informations de base de données
	define('SMA_ACTIF', $ini_array['sma']["SMA_ACTIF"]);
