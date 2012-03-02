<?php
	class Etudiant{
		
		public static $nomTable = "Etudiant";
		
		public static $attributs = Array(
			"id",
			"numeroEtudiant",
			"nom",
			"prenom",
			"email",
			"telephone",
			"notificationsActives",
			"idSpecialite",
			"idPromotion"
		);
		
		public function getId(){ return $this->id; }
		
		/**
		 * Renvoi l'id de l'etudiant si le login et le motDePasse sont corrects, false sinon
		 * */
		public static function identification($login, $motDePasse){
			$motDePasse = md5($motDePasse);
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$requete = "SELECT id FROM ".Etudiant::$nomTable." WHERE login='$login' AND motDePasse='$motDePasse'";
				$req = $bdd->query($requete);
				$nbResultat = $req->rowCount();
				switch($nbResultat){
					case 0:
						return false;
						break;
					case 1:
						$ligne = $req->fetch();
						return $ligne['id'];
						break;
					default:
						// Erreur dans BD
						return false;
						break;
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function Etudiant($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Etudiant::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Etudiant::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function toUl(){
			$string = "<ul>\n";
			foreach(Etudiant::$attributs as $att){
				$string .= "<li>$att : ".$this->$att."</li>\n";
			}
			return "$string</ul>\n";
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Etudiant::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Etudiant::$nomTable);
		}
	}
