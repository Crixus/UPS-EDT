<?php
	/** 
	 * Classe Etudiant - Permet de gérer les Etudiants
	 */ 
	class Etudiant {
		
		public static $nomTable = "Etudiant";
		
		public static $attributs = Array(
			"id",
			"numeroEtudiant",
			"nom",
			"prenom",
			"email",
			"telephone",
			"idSpecialite",
			"idPromotion"
		);
		
		public function getId() {
			return $this->id; 
		}
		
		public function getNumeroEtudiant() {
			return $this->numeroEtudiant;
		}
		
		public function getNom() {
			return $this->nom;
		}
		
		public function getPrenom() {
			return $this->prenom;
		}
		
		public function getEmail() {
			return $this->email;
		}
		
		public function getTelephone() {
			return $this->telephone;
		}
		
		public function getIdSpecialite() {
			return $this->idSpecialite;
		}
		
		public function Etudiant($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Etudiant::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Etudiant::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function ajouter_etudiant($numeroEtudiant, $nom, $prenom, $email, $telephone, $idPromotion, $idSpecialite) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Etudiant::$nomTable." VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
				
				$req->execute(
					Array(
						"",
						$numeroEtudiant,
						$nom, 
						$prenom, 
						$email, 
						$telephone, 
						"1",
						$idPromotion,
						$idSpecialite
					)
				);
				
				//On créé maintenant l'Utilisateur associé
				Utilisateur::creerUtilisateur($prenom, $nom, "Etudiant", $bdd->lastInsertId());
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_etudiant($idEtudiant, $numeroEtudiant, $nom, $prenom, $email, $telephone, $idSpecialite) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Etudiant::$nomTable." SET numeroEtudiant=?, nom=?, prenom=?, email=?, telephone=?, idSpecialite=? WHERE id=?;");
				$req->execute(
					Array(
						$numeroEtudiant,
						$nom, 
						$prenom, 
						$email, 
						$telephone,
						$idSpecialite,
						$idEtudiant
					)
				);
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function supprimer_etudiant($idEtudiant) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Etudiant::$nomTable." WHERE id=?;");
				$req->execute(
					Array($idEtudiant)
				);
				$idUtilisateur = Utilisateur::idDepuisTypeEtIdCorrespondant("Etudiant", $idEtudiant);
				Utilisateur::supprimerUtilisateur($idUtilisateur);
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function existe_etudiant($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM Etudiant WHERE id=?");
				$req->execute(
					Array($id)
				);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne['nb'] == 1;
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function formulaireAjoutEtudiant($idPromotion, $nombresTabulations = 0) {
			$tab = ""; while ($nombresTabulation = 0) { $tab .= "\t"; $nombresTabulations--; }
			$liste_specialite = Specialite::liste_specialite($idPromotion);			
			
			if (isset($_GET['modifier_etudiant'])) { 
				$titre = "Modifier un étudiant";
				$_etudiant = new Etudiant($_GET['modifier_etudiant']);
				$numeroEtudiantModif = "value=\"{$_etudiant->getNumeroEtudiant()}\"";
				$nomModif = "value=\"{$_etudiant->getNom()}\"";
				$prenomModif = "value=\"{$_etudiant->getPrenom()}\"";
				$emailModif = "value=\"{$_etudiant->getEmail()}\"";
				$telephoneModif = "value=\"{$_etudiant->getTelephone()}\"";
				$idSpecialiteModif = $_etudiant->getIdSpecialite();
				$valueSubmit = "Modifier l'étudiant"; 
				$nameSubmit = "validerModificationEtudiant";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_etudiant']}\" />";
				$lienAnnulation = "index.php?page=ajoutEtudiant";
				if (isset($_GET['idPromotion'])) {
					$lienAnnulation .= "&amp;idPromotion=".$_GET['idPromotion'];
				}
			}
			else {
				$titre = "Ajouter un étudiant";
				$numeroEtudiantModif = (isset($_POST['numeroEtudiant'])) ? "value=\"".$_POST['numeroEtudiant']."\"" : "value=\"\"";
				$nomModif = (isset($_POST['nom'])) ? "value=\"".$_POST['nom']."\"" : "value=\"\"";
				$prenomModif = (isset($_POST['prenom'])) ? "value=\"".$_POST['prenom']."\"" : "value=\"\"";
				$emailModif = (isset($_POST['email'])) ? "value=\"".$_POST['email']."\"" : "value=\"\"";
				$telephoneModif = (isset($_POST['telephone'])) ? "value=\"".$_POST['telephone']."\"" : "value=\"\"";
				$valueSubmit = "Ajouter l'étudiant"; 
				$nameSubmit = "validerAjoutEtudiant";
				$hidden = "";
			}
		
			echo $tab."<h2>".$titre."</h2>\n";
			echo $tab."<form method=\"post\">\n";
			echo $tab."\t<table>\n";
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Nom</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"nom\" type=\"text\" required {$nomModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Prénom</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"prenom\" type=\"text\" required {$prenomModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Numéro Etudiant</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"numeroEtudiant\" type=\"number\" min=\"0\" max=\"99999999\" required {$numeroEtudiantModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";			
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Spécialité</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<select name=\"idSpecialite\" id=\"idSpecialite\">\n";
			
			if (isset($idSpecialiteModif) && ($idSpecialiteModif == 0)) { $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo $tab."\t\t\t\t\t<option value=\"0\" $selected>----- Inconnu -----</option>\n";
			foreach ($liste_specialite as $idSpecialite) {
				$Specialite = new Specialite($idSpecialite);
				$nomSpecialite = $Specialite->getNom();
				if (isset($idSpecialiteModif) && ($idSpecialiteModif == $idSpecialite)) { $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo $tab."\t\t\t\t\t<option value=\"$idSpecialite\" $selected>$nomSpecialite</option>\n";
			}
			echo $tab."\t\t\t\t</select>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Email</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"email\" type=\"email\" required {$emailModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Téléphone</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"telephone\" type=\"text\"  {$telephoneModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td></td>\n";
			echo $tab."\t\t\t<td>".$hidden."<input type=\"submit\" name=\"".$nameSubmit."\" value=\"{$valueSubmit}\"></td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t</table>\n";
			echo $tab."</form>\n";
			
			if (isset($lienAnnulation)) {echo $tab."<p><a href=\"".$lienAnnulation."\">Annuler modification</a></p>";}			
		}		
		
		public static function prise_en_compte_formulaire() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_POST['validerAjoutEtudiant']) || isset($_POST['validerModificationEtudiant'])) {
				// Vérification des champs
				$numeroEtudiant = $_POST['numeroEtudiant'];
				$numeroEtudiant_correct = PregMatch::est_numero_etudiant($numeroEtudiant);
				$nom = htmlentities($_POST['nom'],ENT_QUOTES,'UTF-8');
				$nomCorrect = PregMatch::est_nom($nom);
				$prenom = htmlentities($_POST['prenom'],ENT_QUOTES,'UTF-8');
				$prenomCorrect = PregMatch::est_prenom($prenom);
				$email = $_POST['email'];
				$email_correct = PregMatch::est_mail($email);
				$telephone = $_POST['telephone'];
				$telephoneCorrect = PregMatch::est_telephone($telephone);
				$idSpecialite = $_POST['idSpecialite'];
				$idSpecialiteCorrecte = Specialite::existe_specialite($idSpecialite);
				
				$validation_ajout = false;
				if (isset($_POST['validerAjoutEtudiant'])) {
					// Ajout d'un nouveau étudiant
					if ($numeroEtudiant_correct && $nomCorrect && $prenomCorrect && $email_correct && $telephoneCorrect && $idSpecialiteCorrecte) {		
						Etudiant::ajouter_etudiant($numeroEtudiant, $nom, $prenom, $email, $telephone, $_GET['idPromotion'], $idSpecialite);
						array_push($messagesNotifications, "L'étudiant a bien été ajouté");
						$validation_ajout = true;
					}
				}
				else  {
					// Modification d'un etudiant
					$id = htmlentities($_POST['id']); 
					$idCorrect = Etudiant::existe_etudiant($id);
					if ($idCorrect && $numeroEtudiant_correct && $nomCorrect && $prenomCorrect && $email_correct && $telephoneCorrect && $idSpecialiteCorrecte) {		
						Etudiant::modifier_etudiant($_GET['modifier_etudiant'], $numeroEtudiant, $nom, $prenom, $email, $telephone, $idSpecialite);
						array_push($messagesNotifications, "L'étudiant a bien été modifié");
						$validation_ajout = true;
					}
				}
				
				// Traitement des erreurs
				if (!$validation_ajout) {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
					if (isset($idCorrect) && !$idCorrect) {
						array_push($messagesErreurs, "L'id de l'étudiant n'est pas correct, contacter un administrateur");
					}
					if (!$numeroEtudiant_correct) {
						array_push($messagesErreurs, "Le numéro d'étudiant n'est pas correct");
					}
					if (!$nomCorrect) {
						array_push($messagesErreurs, "Le nom n'est pas correct");
					}
					if (!$prenomCorrect) {
						array_push($messagesErreurs, "Le prenom n'est pas correct");
					}
					if (!$email_correct) {
						array_push($messagesErreurs, "L'email n'est pas correct");
					}
					if (!$telephoneCorrect) {
						array_push($messagesErreurs, "Le téléphone n'est pas correct");
					}
					if (!$idSpecialiteCorrecte) {
						array_push($messagesErreurs, "La spécialité n'est pas correcte");
					}
				}
			}
		}
		
		public static function prise_en_compte_suppression() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_GET['supprimer_etudiant'])) {	
				if (Etudiant::existe_etudiant($_GET['supprimer_etudiant'])) {
					Etudiant::supprimer_etudiant($_GET['supprimer_etudiant']);
					array_push($messagesNotifications, "L'étudiant à bien été supprimé");
				}
				else {
					array_push($messagesErreurs, "L'étudiant n'existe pas");
				}
			}
		}
		
		public static function page_administration($nombreTabulations = 0) {			
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			Etudiant::formulaireAjoutEtudiant($_GET['idPromotion'], $nombreTabulations + 1);
			echo $tab."<h2>Liste des étudiants</h2>\n";
			V_Infos_Etudiant::liste_etudiant_to_table($_GET['idPromotion'], $nombreTabulations + 1);
		}
		
		
		public function toUl() {
			$string = "<ul>\n";
			foreach (Etudiant::$attributs as $att) {
				$string .= "<li>$att : ".$this->$att."</li>\n";
			}
			return "$string</ul>\n";
		}
	}
