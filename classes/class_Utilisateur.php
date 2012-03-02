<?php
	class Utilisateur{
		
		public static $nomTable = "Utilisateur";
		
		public static $attributs = Array(
			"id",
			"login",
			"motDePasse",
			"type",
			"idCorrespondant"
		);
		
		public function getId(){ return $this->id; }
		public function getType(){ return $this->type; }
		
		/**
		 * Renvoi l'id de l'Utilisateur si le login et le motDePasse sont corrects, false sinon
		 * */
		public static function identification($login, $motDePasse){
			$motDePasse = md5($motDePasse);
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$requete = "SELECT id FROM ".Utilisateur::$nomTable." WHERE login='$login' AND motDePasse='$motDePasse'";
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
		
		public function Utilisateur($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Utilisateur::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Utilisateur::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
	}
