<?php
	class PregMatch{
		
		public static function est_couleur_avec_diez($string) {
			$regexCouleur = '`^#([A-F0-9]{6})$`';
			$string = strtoupper($string);
			return preg_match($regexCouleur, $string);
		}
		
		public static function est_float($string) {
			$regexFloat = '`^([0-9]+.[0-9]+)$`';
			return preg_match($regexFloat, $string);
		}
		
		public static function est_nombre($string) {
			$regexNombre = '`^([0-9]+)$`';
			return preg_match($regexNombre, $string);
		}
		
		public static function est_mail($string) {
			$regexMail = '`^([a-zA-Z0-9]+(([\.\-\_]?[a-zA-Z0-9]+)+)?)\@(([a-zA-Z0-9]+[\.\-\_])+[a-zA-Z]{2,4})$`';
			return preg_match($regexMail, $string);
		}
		
		public static function est_telephone($string) {
			$regexTelephone = '`^0([0-9]{9})$`';
			return preg_match($regexTelephone, $string);
			//return true;
		}
		
		public static function est_prenom($string) {
			return true;
		}
		
		public static function est_nom($string) {
			return true;
		}
		
		public static function est_intitule($string) {
			return true;
		}
		
		public static function est_numero_etudiant($string) {
			return ((PregMatch::est_nombre($string)) && (strlen($string)==8));
		}
		
		public static function est_nbre_heures($string) {
			return PregMatch::est_nombre($string);
		}
	}

