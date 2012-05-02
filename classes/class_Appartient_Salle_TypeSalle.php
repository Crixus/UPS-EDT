<?php
	/** 
	 * Classe Appartient_Salle_TypeSalle - Interface entre les salles et les types de salles
	 */ 
	class Appartient_Salle_TypeSalle {
		
		public static $nomTable = "Appartient_Salle_TypeSalle";
		
		public static $attributs = Array(
			"idSalle",
			"idTypeSalle"
		);
		
		/**
		 * Constructeur de la classe Appartient_Salle_TypeSalle
		 * Récupère les informations de Appartient_Salle_TypeSalle dans la base de données depuis l'id
		 */
		public function Appartient_Salle_TypeSalle() {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Appartient_Salle_TypeSalle::$nomTable);
				$req->execute();
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Appartient_Salle_TypeSalle::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
	}
