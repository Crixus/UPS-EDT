<?php
	/** 
	 * Classe MotDePasse - Permet de gerer les mot de passes
	 * Génération et Cryptage
	 */ 
	class MotDePasse {
		
		/**
		 * Renvoi un mot de passe généré aléatoirement
		 * @return String mot de passe généré
		 */
		public static function genererMotDePasse() {
			$alphabet = "abcdefghijklmnopqrstuvwxyz".
						"ABCDEFGHIJKLMNOPQRSTUVWXYZ".
						"0123456789";
			$tailleAlphabet = strlen($alphabet);
			
			$nombreCaracteres = 8;
			$motDePasse = "";
			for ($i = 0; $i < $nombreCaracteres; $i++) {
				$motDePasse .= $alphabet[rand(0, $tailleAlphabet - 1)];
			}
			return $motDePasse;			
		}
		
		/**
		 * Crypte une chaine de caractère en md5
		 * @param $nom String chaine à crypter
		 * @return chaine cryptée en md5
		 */
		public static function crypterMd5($chaine) {
			return md5($chaine);
		}
		
	}
