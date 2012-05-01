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
			"type", // Etudiant, Intervenant, Administrateur
			"idCorrespondant"
		);
		
		/**
		 * Getter de l'id de l'Utilisateur
		 * @return int : id de l'Utilisateur
		 */
		public function getId() { 
			return $this->id;
		}
		
		/**
		 * Getter du login
		 * @return String : login
		 */
		public function getLogin() {
			return $this->login;
		}
		
		/**
		 * Getter du motDePasse
		 * @return String : motDePasse
		 */
		public function getMotDePasse() {
			return $this->motDePasse;
		}
		
		/**
		 * Getter du type
		 * @return String : type de l'utilisateur
		 */
		public function getType() {
			return $this->type;
		}
		
		/**
		 * Getter de l'idCorrespondant 
		 * @return int : idCorrespondant
		 */
		public function getIdCorrespondant() {
			return $this->idCorrespondant;
		}
		
		/**
		 * Constructeur de la classe Utilisateur depuis l'id
		 * Récupère les informations de Utilisateur dans la base de données depuis l'id
		 * @param $id int : id de l'Utilisateur
		 */
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
		 * @param $login string : login de l'utilisateur
		 * @param $motDePasse : motDePasse de l'utilisateur
		 * @return int : id de l'Utilisateur, false sinon
		 */
		public static function identification($login, $motDePasse = null) {
			$motDePasse = md5($motDePasse);
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				if (defined('MOT_DE_PASSE_ACTIF') && MOT_DE_PASSE_ACTIF == 1) {
					$requete = "SELECT id FROM ".Utilisateur::$nomTable." WHERE login=? AND motDePasse=?";
					$req = $bdd->prepare($requete);
					$req->execute(
						Array($login, $motDePasse)
					);
				} else {
					$requete = "SELECT id FROM ".Utilisateur::$nomTable." WHERE login=?";
					$req = $bdd->prepare($requete);
					$req->execute(
						Array($login)
					);
				}
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
		
		/**
		 * Ajoute un utilisateur dans la base de données
		 * @param $login String : login de l'utilisateur
		 * @param $motDePasse String : motDePasse de l'utilisateur (md5)
		 * @param $type String : type de l'utilisateur
		 * @param $idCorrespondant int : idCorrespondant
		 */
		public static function ajouterUtilisateur($login, $motDePasse, $type, $idCorrespondant) {
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
		
		/**
		 * Modifi un utilisateur dans la base de données 
		 * @param $id int : id de l'utilisateur à modifier
		 * @param $login String : login de l'utilisateur
		 * @param $motDePasse String : motDePasse de l'utilisateur (md5)
		 * @param $type String : type de l'utilisateur
		 * @param $idCorrespondant int : idCorrespondant
		 */
		public static function modifierUtilisateur($id, $login, $motDePasse, $type, $idCorrespondant) {
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
		
		/**
		 * Supprime un utilisateur dans la base de données
		 * @param $id int : id de l'utilisateur
		 */
		public static function supprimerUtilisateur($id) {
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
		
		/**
		 * Supprime tout les Utilisateurs
		 */
		public static function truncateUtilisateur() {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("TRUNCATE TABLE ".Utilisateur::$nomTable.";");
				$req->execute();		
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Renvoi la liste d'id des Utilisateur
		 */
		public static function listeIdUtilisateurs($orderBy = "login") {
			$listeIdUtilisateurs = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".Utilisateur::$nomTable." ORDER BY ?");
				$req->execute(
					Array($orderBy)
					);
				while ($ligne = $req->fetch()) {
					array_push($listeIdUtilisateurs, $ligne['id']);
				}
				$req->closeCursor();
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeIdUtilisateurs;
		}
		
		/**
		 * Retourne l'id de l'Utilisateur depuis son type et son idCorrespondant
		 * @return int : id de l'utilisateur
		 * @param $type string : type de l'utilisateur
		 * @param $idCorrespondant int : idCorrespondant de l'utilisateur
		 */
		public static function idDepuisTypeEtIdCorrespondant($type, $idCorrespondant) {
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
		
		/**
		 * Creer un utilisateur (generation de login et mot de passe)
		 * @return boolean : true si l'utilisateur est créé, false sinon
		 * @param $prenom String : prenom de l'utilisateur
		 * @param $nom String : nom de l'utilisateur
		 * @param $type String : type de l'utilisateur
		 * @param $idCorrespondant int : idCorrespondant de l'utilisateur
		 */
		public static function creerUtilisateur($prenom, $nom, $type, $idCorrespondant, $motDePasse = "") {
			switch ($type) {
				case "Etudiant":
					$destinataire = new Etudiant($idCorrespondant);
					break;
				case "Intervenant":
					$destinataire = new Intervenant($idCorrespondant);
					break;
				default:
					return false;
			}
			$login = Utilisateur::genererLogin($prenom, $nom);
			if ($motDePasse = "") {
				$motDePasse = MotDePasse::genererMotDePasse();
			}
			Mail::envoyer_creation_utilisateur($destinataire->getEmail(), $login, $motDePasse);
			$motDePasse = MotDePasse::crypterMd5($motDePasse); 
			Utilisateur::ajouterUtilisateur($login, $motDePasse, $type, $idCorrespondant);
			return true;
		}
		
		/**
		 * Verifi si un login existe
		 * @return boolean : true si le login existe, false sinon
		 * @param $login String : login à tester
		 */
		public static function existeLogin($login) {
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
				return false;
			}
		}
		
		/**
		 * Génère un login à partir d'un nom et prénom avec gestion des conflits
		 * @return String : login généré
		 * @param $prenom String : prenom de l'Utilisateur
		 * @param $nom String : nom de l'Utilisateur
		 */
		public static function genererLogin($prenom, $nom) {
			$login = strtolower($prenom)."_".strtolower($nom);
			if (Utilisateur::existeLogin($login)) {
				$count = 2;
				while (Utilisateur::existeLogin($login."_".$count)) {
					$count++;
				}
				$login .= "_".$count;
			}
			return $login;
		}
		
		/**
		 * Prise en compte des formulaires POST et GET associés à l'administration des Utilisateurs
		 */
		public static function priseEnCompteFormulaires() {
			if (isset($_POST['tout_regenerer'])) {
				// Tout regenerer : vide la table et regenère pour tout les utilisateurs
				$listeIdEtudiants = Etudiant::listeIdEtudiants();
				$listeIdIntervenants = Intervenant::listeIdIntervenants();
				
				Utilisateur::truncateUtilisateur();
						
				// Création des comptes associés aux étudiants
				foreach ($listeIdEtudiants as $id) {
					$_etudiant = new Etudiant($id);
					Utilisateur::creerUtilisateur(
						$_etudiant->getPrenom(), 
						$_etudiant->getNom(),
						"Etudiant",
						$id
					);
					unset($_etudiant);
				}
				
				// Création des comptes associés aux intervenants
				foreach ($listeIdIntervenants as $id) {
					$_intervenant = new Intervenant($id);
					Utilisateur::creerUtilisateur(
						$_intervenant->getPrenom(), 
						$_intervenant->getNom(),
						"Intervenant",
						$id
					);
					unset($_intervenant);
				}
			}
			if (isset($_POST['tout_supprimer'])) {
				Utilisateur::truncateUtilisateur();
			}
		}
		
		/**
		 * Page administration des Utilisateurs
		 * @param $nombreTabulations int : nombre de tabulations (esthétique d'affichage de la source)
		 */
		public static function pageAdministration($nombreTabulations = 0) {			
			$tab = ""; 
			for ($i = 0; $i < $nombreTabulations; $i++) {
				$tab .= "\t";
			}
			Utilisateur::formulaireUtilisateur($nombreTabulations);
			Utilisateur::tableAdministrationListeUtilisateurs($nombreTabulations);
		}
		
		/**
		 * Formulaire d'administration des Utilisateurs
		 * @param $nombreTabulations int : nombre de tabulations (esthétique d'affichage de la source)
		 */
		public static function formulaireUtilisateur($nombreTabulations) {
			$tab = ""; 
			for ($i = 0; $i < $nombreTabulations; $i++) {
				$tab .= "\t";
			}
			echo $tab."<h2>Formulaire d'administration</h2>\n";
			echo $tab."<p>Supprimer les Utilisateurs ne suppime pas les Etudiants et Intervenants associés</p>\n";
			echo $tab."<form class=\"formulaire_administration\" method=\"post\">\n";
			echo $tab."\t<fieldset>\n";
			echo $tab."\t\t<legend>Formulaire</legend>\n";
			echo $tab."\t\t<input type=\"submit\" value=\"Tout regénérer\" name=\"tout_regenerer\" />\n";
			echo $tab."\t\t<input type=\"submit\" value=\"Supprimer tout les Utilisateurs\" name=\"tout_supprimer\" />\n";
			echo $tab."</fieldset>\n";
			echo $tab."</form>";
		}
		
		/**
		 * Tableau d'administration des Utilisateurs (liste)
		 * @param $nombreTabulations int : nombre de tabulations (esthétique d'affichage de la source)
		 */
		public static function tableAdministrationListeUtilisateurs($nombreTabulations = 0) {
			$tab = ""; 
			for ($i = 0; $i < $nombreTabulations; $i++) {
				$tab .= "\t";
			}
			$listeIdUtilisateurs = Utilisateur::listeIdUtilisateurs();
			
			echo $tab."<h2>Tableau d'administration Utilisateur</h2>\n";
			if (!empty($listeIdUtilisateurs)) {
				echo $tab."<table class=\"table_liste_administration\">\n";
				echo $tab."\t<tr>\n";
				echo $tab."\t\t<th>Login</th>\n";
				echo $tab."\t\t<th>Type</th>\n";
				echo $tab."\t\t<th>Liens Etudiant/Intervenant</th>\n";
				echo $tab."\t\t<th>Actions</th>\n";
				echo $tab."\t</tr>\n";
				foreach ($listeIdUtilisateurs as $idUtilisateur) {
					$_utilisateur = new Utilisateur($idUtilisateur);
					echo $tab."\t<tr>\n";
					echo $tab."\t\t<td>".$_utilisateur->getLogin()."</td>\n";
					echo $tab."\t\t<td>".$_utilisateur->getType()."</td>\n";
					echo $tab."\t\t<td>".$_utilisateur->getIdCorrespondant()."</td>\n";
					echo $tab."\t\t<td>Regénérer MDP</td>\n";
					echo $tab."\t</tr>\n";
				}
				echo $tab."</table>\n";
			} else {
				echo $tab."<p>Pas d'utilisateurs créés</p>\n";
			}
		}
	}
