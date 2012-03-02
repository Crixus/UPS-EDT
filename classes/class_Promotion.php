<?php
	class Promotion{
		
		public static $nomTable = "Promotion";
		
		public static $attributs = Array(
			"id",
			"nom",
			"annee",
			"tsDebut",
			"tsFin"
		);
		
		public function Promotion($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Promotion::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Promotion::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function toString(){
			$string = "";
			foreach(Promotion::$attributs as $att){
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Promotion::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Promotion::$nomTable);
		}
	}
