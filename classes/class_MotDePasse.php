<?php
	class MotDePasse{
		
		public static function genererMotDePasse() {
			$alphabet = "abcdefghijklmnopqrstuvwxyz".
						"ABCDEFGHIJKLMNOPQRSTUVWXYZ".
						"0123456789";
			$taille_alphabet = strlen($alphabet);
			
			$nombre_caracteres = 8;
			$mot_de_passe = "";
			for ($i = 0 ; $i < $nombre_caracteres ; $i++) {
				$mot_de_passe .= $alphabet[rand(0, $taille_alphabet - 1)];
			}
			return $mot_de_passe;			
		}
		
		public static function crypter_md5_motDePasse($motDePasse) {
			return md5($motDePasse);
		}
		
	}
