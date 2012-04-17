<?php
	class Appartient_TypeSalle_TypeCours{
		
		public static $nomTable = "Appartient_TypeSalle_TypeCours";
		
		public static $attributs = Array(
			"idTypeCours",
			"idTypeSalle"
		);
		
		public function Appartient_Salle_TypeSalle() {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Appartient_TypeSalle_TypeCours::$nomTable);
				$req->execute();
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Appartient_TypeSalle_TypeCours::$attributs as $att) {
					$this->$att = $ligne["$att"];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function toString() {
			$string = "";
			foreach (Appartient_TypeSalle_TypeCours::$attributs as $att) {
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
		
		public static function creer_table() {
			return Utils_SQL::sql_from_file("./sql/".Appartient_TypeSalle_TypeCours::$nomTable.".sql");
		}
		
		public static function supprimer_table() {
			return Utils_SQL::sql_supprimer_table(Appartient_TypeSalle_TypeCours::$nomTable);
		}
	}
