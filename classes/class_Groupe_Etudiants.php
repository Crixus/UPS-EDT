<?php
	class Groupe_Etudiants{
		
		public static $nomTable = "Groupe_Etudiants";
		
		public static $attributs = Array(
			"id",
			"nom",
			"identifiant",
			"type"
		);
		
		public function Groupe_Etudiants($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Groupe_Etudiants::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Groupe_Etudiants::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function toString(){
			$string = "";
			foreach(Groupe_Etudiants::$attributs as $att){
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Groupe_Etudiants::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Groupe_Etudiants::$nomTable);
		}
	}
