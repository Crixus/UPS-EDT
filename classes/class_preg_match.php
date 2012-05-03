<?php
	/** 
	 * Classe PregMatch - Classe utilisée pour les tests sur le typages des paramètres (int, string, format de type email, format de type téléphone, ...)
	 */ 
	class PregMatch{
		
		/**
		 * Fonction testant si la chaine de caratères correspond au format couleur (liste de 6 caractères => lettres A à F ou chiffres 0 à 9)
		 * @param string : string de la chaine de caractères à tester
		 * @return int : 1 si test OK, 0 sinon
		 */
		public static function est_couleur_avec_diez($string) {
			$regexCouleur = '`^#([A-F0-9]{6})$`';
			$string = strtoupper($string);
			return preg_match($regexCouleur, $string);
		}
		
		/**
		 * Fonction testant si la chaine de caratères est une liste de float
		 * @param string : string de la chaine de caractères à tester
		 * @return int : 1 si test OK, 0 sinon
		 */
		public static function est_float($string) {
			$regexFloat = '`^([0-9]+.[0-9]+)$`';
			return preg_match($regexFloat, $string);
		}
		
		/**
		 * Fonction testant si la chaine de caratères est un nombre
		 * @param string : string de la chaine de caractères à tester
		 * @return int : 1 si test OK, 0 sinon
		 */
		public static function est_nombre($string) {
			$regexNombre = '`^([0-9]+)$`';
			return preg_match($regexNombre, $string);
		}
		
		/**
		 * Fonction testant si la chaine de caratères a un format de type email
		 * @param string : string de la chaine de caractères à tester
		 * @return int : 1 si test OK, 0 sinon
		 */
		public static function est_mail($string) {
			$regexMail = '`^([a-zA-Z0-9]+(([\.\-\_]?[a-zA-Z0-9]+)+)?)\@(([a-zA-Z0-9]+[\.\-\_])+[a-zA-Z]{2,4})$`';
			return preg_match($regexMail, $string);
		}
		
		/**
		 * Fonction testant si la chaine de caratères a un format de type telephone
		 * @param string : string de la chaine de caractères à tester
		 * @return int : 1 si test OK, 0 sinon
		 */
		public static function est_telephone($string) {
			$regexTelephone = '`^0([0-9]{9})$`';
			return preg_match($regexTelephone, $string);
			//return true;
		}
		
		/**
		 * Fonction testant si la chaine de caratères a un format de type prenom
		 * @param string : string de la chaine de caractères à tester
		 * @return int : 1 si test OK, 0 sinon
		 */
		public static function est_prenom($string) {
			return true;
		}
		
		/**
		 * Fonction testant si la chaine de caratères a un format de type enom
		 * @param string : string de la chaine de caractères à tester
		 * @return int : 1 si test OK, 0 sinon
		 */
		public static function est_nom($string) {
			return true;
		}
		
		/**
		 * Fonction testant si la chaine de caratères a un format de type intitule
		 * @param string : string de la chaine de caractères à tester
		 * @return int : 1 si test OK, 0 sinon
		 */
		public static function est_intitule($string) {
			return true;
		}
		
		/**
		 * Fonction testant si la chaine de caratères a le format du numéro étudiant (nombre composé de 8 chiffres)
		 * @param string : string de la chaine de caractères à tester
		 * @return int : 1 si test OK, 0 sinon
		 */
		public static function est_numero_etudiant($string) {
			return ((PregMatch::est_nombre($string)) && (strlen($string)==8));
		}
		
		/**
		 * Fonction testant si la chaine de caratères est un nombre
		 * @param string : string de la chaine de caractères à tester
		 * @return int : 1 si test OK, 0 sinon
		 */
		public static function est_nbre_heures($string) {
			return PregMatch::est_nombre($string);
		}
	}

