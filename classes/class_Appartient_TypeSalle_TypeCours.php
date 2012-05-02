<?php
	/** 
	 * Classe Appartient_TypeSalle_TypeCours - Interface entre les types de cours et les types de salles
	 */ 
	class Appartient_TypeSalle_TypeCours{
		
		public static $nomTable = "Appartient_TypeSalle_TypeCours";
		
		public static $attributs = Array(
			"idTypeCours",
			"idTypeSalle"
		);
		
		/**
		 * Constructeur de la classe Appartient_Salle_TypeSalle
		 * Récupère les informations de Appartient_Salle_TypeSalle dans la base de données depuis l'id
		 */
		public function Appartient_TypeSalle_TypeCours() {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Appartient_TypeSalle_TypeCours::$nomTable);
				$req->execute();
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Appartient_TypeSalle_TypeCours::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
	}
