<?php
	class PregMatch{
		
		public static function est_couleur_avec_diez($string){
			$regexCouleur = '`^#([A-F0-9]{6})$`';
			$string = strtoupper($regexCouleur);
			return preg_match($regexCouleur, $string);
		}
		
		public static function est_float($string){
			$regexCouleur = '`^([0-9]+.[0-9]+)$`';
			return preg_match($regexCouleur, $string);
		}
	}

