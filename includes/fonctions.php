<?php
	function importerClasses() {
		$repertoire = opendir(dirname(__FILE__)."/../classes/");
		while ($fichier = readdir($repertoire)) {
			if ($fichier != '..' && $fichier != '.') {
				require_once(dirname(__FILE__)."/../classes/" . $fichier);
			}
		}
	}
