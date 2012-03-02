<?php
	class Salle{
		
		public static $nomTable = "Salle";
		
		public static $attributs = Array(
			"id",
			"nom",
			"nomBatiment",
			"capacite"
		);
		
		public function Salle($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Salle::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Salle::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
				
				$this->Batiment = new Batiment($this->idBatiment);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function toUl(){
			$string = "<ul>\n";
			foreach(Salle::$attributs as $att){
				$string .= "<li>$att : ".$this->$att."</li>\n";
			}
			return "$string</ul>\n";
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Salle::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Salle::$nomTable);
		}
	}
