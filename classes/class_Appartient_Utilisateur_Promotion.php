<?php
	class Appartient_Utilisateur_Promotion{
		
		public static $nomTable = "Appartient_Utilisateur_Promotion";
		
		public static $attributs = Array(
			"idUtilisateur",
			"idPromotion"
		);
		
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
		
		public function toString() {
			$string = "";
			foreach (Appartient_Utilisateur_Promotion::$attributs as $att) {
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
		
		public static function creer_table() {
			return Utils_SQL::sql_from_file("./sql/".Appartient_Utilisateur_Promotion::$nomTable.".sql");
		}
		
		public static function supprimer_table() {
			return Utils_SQL::sql_supprimer_table(Appartient_Utilisateur_Promotion::$nomTable);
		}
	}
