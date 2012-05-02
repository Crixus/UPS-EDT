<?php
	/** 
	 * Classe Appartient_Utilisateur_Promotion - Interface entre les utilisateurs et les promotions
	 */ 
	class Appartient_Utilisateur_Promotion{
		
		public static $nomTable = "Appartient_Utilisateur_Promotion";
		
		public static $attributs = Array(
			"idUtilisateur",
			"idPromotion"
		);
		
		/**
		 * Constructeur de la classe Appartient_Utilisateur_Promotion
		 * Récupère les informations de Appartient_Utilisateur_Promotion dans la base de données depuis l'id
		 */
		public function Appartient_Utilisateur_Promotion () {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Appartient_Utilisateur_Promotion::$nomTable);
				$req->execute();
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Appartient_Utilisateur_Promotion::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
	}
