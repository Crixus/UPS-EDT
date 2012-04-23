<?php
	/** 
	 * Classe Utilisateur - Permet de gerer les Utilisateurs
	 */ 
	class Utilisateur {
		
		public static $nomTable = "Utilisateur";
		
		public static $attributs = Array(
			"id",
			"login",
			"motDePasse",
			"type", // Etudiant, Intervenant
			"idCorrespondant"
		);
		
		/**
		 * Getter de l'id de l'Utilisateur
		 * @return int id de l'Utilisateur
		 */
		public function getId() { 
			return $this->id;
		}
		
		/**
		 * Getter du login
		 * @return String du login
		 */
		public function getLogin() {
			return $this->login;
		}
		
		/**
		 * Getter du motDePasse
		 * @return String du motDePasse
		 */
		public function getMotDePasse() {
			return $this->motDePasse;
		}
		
		public function getType() {
			return $this->type;
		}
		
		public function getIdCorrespondant() {
			return $this->idCorrespondant;
		}
		
		public function Utilisateur($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Utilisateur::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Utilisateur::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Renvoi l'id de l'Utilisateur si le login et le motDePasse sont corrects, false sinon
		 */
		public static function identification($login, $motDePasse) {
			$motDePasse = md5($motDePasse);
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$requete = "SELECT id FROM ".Utilisateur::$nomTable." WHERE login=? AND motDePasse=?";
				$req = $bdd->prepare($requete);
				$req->execute(
					Array($login, $motDePasse)
					);
				$nbResultat = $req->rowCount();
				switch ($nbResultat) {
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
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function ajouter_Utilisateur($login, $motDePasse, $type, $idCorrespondant) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Utilisateur::$nomTable." VALUES(?, ?, ?, ?, ?)");
				
				$req->execute(
					Array("", $login, $motDePasse, $type, $idCorrespondant)
				);
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function creer_utilisateur($prenom, $nom, $type, $idCorrespondant) {
			switch ($type) {
				case "Etudiant":
					$Destinataire = new Etudiant($idCorrespondant);
					break;
				case "Intervenant":
					$Destinataire = new Intervenant($idCorrespondant);
					break;
			}
			$login = Utilisateur::gererer_login($prenom, $nom);
			$motDePasse = MotDePasse::genererMotDePasse();
			Mail::envoyer_creation_utilisateur($Destinataire->getEmail(), $login, $motDePasse);
			$motDePasse = MotDePasse::crypterMd5($motDePasse); 
			Utilisateur::ajouter_Utilisateur($login, $motDePasse, $type, $idCorrespondant);
		}
		
		public static function modifier_Utilisateur($id, $login, $motDePasse, $type, $idCorrespondant) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Utilisateur::$nomTable." SET login=?, motDePasse=?, type=?, idCorrespondant=?, WHERE id=?;");
				$req->execute(
					Array(
						$login,
						$motDePasse,
						$type,
						$idCorrespondant,
						$id
					)
				);
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function supprimer_utilisateur($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Utilisateur::$nomTable." WHERE id=?;");
				$req->execute(
					Array($id)
				);					
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function id_depuis_type_et_idCorrespondant($type, $idCorrespondant) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".Utilisateur::$nomTable." WHERE type=? AND idCorrespondant=?;");
				$req->execute(
					Array($type, $idCorrespondant)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne['id'];
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function existe_login($login) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(login) AS nb FROM ".Utilisateur::$nomTable." WHERE login=?");
				$req->execute(
					Array($login)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne['nb'] == 1;
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function gererer_login($prenom, $nom) {
			$login = strtolower($prenom)."_".strtolower($nom);
			if (Utilisateur::existe_login($login)) {
				$count = 2;
				while (Utilisateur::existe_login($login."_".$count)) {
					$count++;
				}
				$login .= "_".$count;
			}
			return $login;
		}
	}
