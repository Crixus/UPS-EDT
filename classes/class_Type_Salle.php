<?php
	class Appartient_Type_Salle{
		
		public static $nomTable = "Appartient_Type_Salle";
		
		public static $attributs = Array(
			"id",
			"nom"
		);
		
		public function Appartient_Type_Salle($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Appartient_Type_Salle::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Appartient_Type_Salle::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function toString(){
			$string = "";
			foreach(Appartient_Type_Salle::$attributs as $att){
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Appartient_Type_Salle::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Appartient_Type_Salle::$nomTable);
		}
	}
