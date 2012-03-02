<?php
	class UE{
		
		public static $nomTable = "UE";
		
		public static $attributs = Array(
			"id",
			"nom",
			"nbHeuresCours",
			"nbHeuresTD",
			"nbHeuresTP",
			"ECTS",
			"idResponsable",
			"idPromotion"
		);
		
		public function UE($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".UE::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(UE::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function getId(){ return $this->id; }
		public function getNom(){ return $this->nom; }
		
		public static function liste_UE_promotion($idPromotion){
			$listeId = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".UE::$nomTable." WHERE idPromotion=? ORDER BY nom");
				$req->execute(
					Array($idPromotion)
					);
				while($ligne = $req->fetch()){
					array_push($listeId, $ligne['id']);
				}
				$req->closeCursor();
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeId;
		}
		
		public function toUl(){
			$string = "<ul>\n";
			foreach(UE::$attributs as $att){
				$string .= "<li>$att : ".$this->$att."</li>\n";
			}
			return "$string</ul>\n";
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".UE::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(UE::$nomTable);
		}
	}
