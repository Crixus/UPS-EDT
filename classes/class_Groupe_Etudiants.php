<?php
	class Groupe_Etudiants{
		
		public static $nomTable = "Groupe_Etudiants";
		
		public static $attributs = Array(
			"nom",
			"identifiant"
		);
		
		public function getNom() { return $this->nom; }
		public function getIdentifiant() { return $this->identifiant; }
		
		
		public function Groupe_Etudiants($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Groupe_Etudiants::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Groupe_Etudiants::$attributs as $att) {
					$this->$att = $ligne["$att"];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function existe_groupeEtudiants($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Groupe_Etudiants::$nomTable." WHERE id=?");
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
		
		public function getNbreGroupeEtudiants($idPromotion) { 
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Groupe_Etudiants::$nomTable." WHERE idPromotion=?");
				$req->execute(
					array($idPromotion)
				);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne["nb"];
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function ajouter_groupeEtudiants($idPromotion, $nom, $identifiant) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Groupe_Etudiants::$nomTable." VALUES(?, ?, ?, ?)");
				
				$req->execute(
					Array(
						"",
						$nom, 
						$identifiant, 
						$idPromotion
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_groupeEtudiants($idGroupeEtudiants, $idPromotion, $nom, $identifiant) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Groupe_Etudiants::$nomTable." SET nom=?, identifiant=?, idPromotion=? WHERE id=?;");
				$req->execute(
					Array(
						$nom, 
						$identifiant, 
						$idPromotion,
						$idGroupeEtudiants
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function supprimer_groupeEtudiants($idGroupeEtudiants) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Groupe_Etudiants::$nomTable." WHERE id=?;");
				$req->execute(
					Array(
						$idGroupeEtudiants
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function liste_groupeEtudiants($idPromotion) {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Groupe_Etudiants::$nomTable." WHERE idPromotion=? ORDER BY nom");
				$req->execute(
					array($idPromotion)
				);
				while ($ligne = $req->fetch()) {
					array_push($listeId, $ligne['id']);
				}
				$req->closeCursor();
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeId;
		}
		
		public static function liste_groupeEtudiants_to_table($idPromotion, $administration, $nombreTabulations = 0) {
			$liste_groupeEtudiants = Groupe_Etudiants::liste_groupeEtudiants($idPromotion);
			$nbreGroupeEtudiants = Groupe_Etudiants::getNbreGroupeEtudiants($idPromotion);
			$tab = ""; while ($nombreTabulations > 0) { $tab .= "\t"; $nombreTabulations--; }
			
			if ($nbreGroupeEtudiants == 0) {
				echo $tab."<h2>Aucun groupe d'étudiants n'a été créés pour cette promotion</h2>\n";
			}
			else {			
				echo $tab."<table class=\"table_liste_administration\">\n";
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				echo $tab."\t\t<th>Nom</th>\n";
				echo $tab."\t\t<th>Identifiant</th>\n";
				
			
				if ($administration) {
					echo $tab."\t\t<th>Actions</th>\n";
				}
				echo $tab."\t</tr>\n";
				
				$cpt = 0;
				foreach ($liste_groupeEtudiants as $idGroupeEtudiants) {
					$Groupe_Etudiants = new Groupe_Etudiants($idGroupeEtudiants);
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					echo $tab."\t<tr class=\"$couleurFond\">\n";
					foreach (Groupe_Etudiants::$attributs as $att) {
						echo $tab."\t\t<td>".$Groupe_Etudiants->$att."</td>\n";
					}
					
					if ($administration) {
						$pageModification = "./index.php?page=ajoutGroupeEtudiants&amp;modifier_groupeEtudiants=$idGroupeEtudiants";
						$pageSuppression = "./index.php?page=ajoutGroupeEtudiants&amp;supprimer_groupeEtudiants=$idGroupeEtudiants";
						if (isset($_GET['idPromotion'])) {
							$pageModification .= "&amp;idPromotion=".$_GET['idPromotion'];
							$pageSuppression .= "&amp;idPromotion=".$_GET['idPromotion'];
						}
						echo $tab."\t\t<td>";
						echo "<a href=\"".$pageModification."\"><img src=\"../images/modify.png\" alt=\"icone de modification\" /></a>";
						echo "<a href=\"".$pageSuppression."\" onclick=\"return confirm('Supprimer le groupe d\'étudiant ?')\"><img src=\"../images/delete.png\" alt=\"icone de suppression\" /></a>";
						echo "</td>\n";
					}
					echo $tab."\t</tr>\n";
				}
				
				echo $tab."</table>\n";
			}
		}
		
		public function formulaireAjoutGroupeEtudiants($idPromotion, $nombresTabulations = 0) {
			$tab = ""; while ($nombresTabulation = 0) { $tab .= "\t"; $nombresTabulations--; }
			
			if (isset($_GET['modifier_groupeEtudiants'])) { 
				$titre = "Modifier un groupe d'étudiant";
				$Groupe_Etudiants = new Groupe_Etudiants($_GET['modifier_groupeEtudiants']);
				$nomModif = "value=\"{$Groupe_Etudiants->getNom()}\"";
				$Promotion = new Promotion($idPromotion);
				$nom_promotion = $Promotion->getNom();
				$annee_promotion = $Promotion->getAnnee();
				$pre_identifiant = "{$annee_promotion}-{$nom_promotion}-";
				$identifiantModif = "value=\"{$Groupe_Etudiants->getIdentifiant()}\"";
				$valueSubmit = "Modifier le groupe d'étudiant"; 
				$nameSubmit = "validerModificationGroupeEtudiants";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_groupeEtudiants']}\" />";
				$lienAnnulation = "index.php?page=ajoutGroupeEtudiants";
				if (isset($_GET['idPromotion'])) {
					$lienAnnulation .= "&amp;idPromotion=".$_GET['idPromotion'];
				}
			}
			else {
				$titre = "Ajouter un groupe d'étudiant";
				$nomModif = (isset($_POST['nom'])) ? "value=\"".$_POST['nom']."\"" : "value=\"\"";
				$Promotion = new Promotion($idPromotion);
				$nom_promotion = $Promotion->getNom();
				$annee_promotion = $Promotion->getAnnee();
				$pre_identifiant = "{$annee_promotion}-{$nom_promotion}-";
				$identifiantModif = "value=\"{$pre_identifiant}\"";
				$valueSubmit = "Ajouter le groupe d'étudiant"; 
				$nameSubmit = "validerAjoutGroupeEtudiants";
				$hidden = "";
			}		
		
			echo $tab."<h2>$titre</h2>\n";
			echo $tab."<form method=\"post\">\n";
			echo $tab."\t<table>\n";
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Nom</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"nom\" type=\"text\" onChange=\"modification_identifiant('{$pre_identifiant}')\" required {$nomModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Identifiant</label></td>\n";
			echo $tab."\t\t\t<td><input name=\"identifiant\" type=\"text\" disabled=\"disabled\" required {$identifiantModif}/></td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td></td>\n";
			echo $tab."\t\t\t<td>$hidden<input type=\"submit\" name=\"".$nameSubmit."\" value=\"{$valueSubmit}\"></td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t</table>\n";
			echo $tab."</form>\n";
			
			if (isset($lienAnnulation)) {echo $tab."<p><a href=\"".$lienAnnulation."\">Annuler modification</a></p>";}			
		}		
		
		public static function prise_en_compte_formulaire() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_POST['validerAjoutGroupeEtudiants']) || isset($_POST['validerModificationGroupeEtudiants'])) {
				// Vérification des champs
				$nom = htmlentities($_POST['nom'],ENT_QUOTES,'UTF-8');
				$nomCorrect = PregMatch::est_nom($nom);
				
				$Promotion = new Promotion($_GET['idPromotion']);
				$nom_promotion = $Promotion->getNom();
				$annee_promotion = $Promotion->getAnnee();
				$pre_identifiant = "{$annee_promotion}-{$nom_promotion}-";
				$identifiant = $pre_identifiant.$nom;
				$identifiant_correct = $nomCorrect;
				
				$validation_ajout = false;
				if (isset($_POST['validerAjoutGroupeEtudiants'])) {
					// Ajout d'un nouveau groupe d'étudiants				
					if ($nomCorrect && $identifiant_correct) {	
						Groupe_Etudiants::ajouter_groupeEtudiants($_GET['idPromotion'], $nom, $identifiant);
						array_push($messagesNotifications, "Le groupe d'étudiant a bien été ajouté");
						$validation_ajout = true;
					}
				}
				else {
					// Modification d'un groupe d'étudiants
					$id = htmlentities($_POST['id']);
					$idCorrect = Groupe_Etudiants::existe_groupeEtudiants($id);
					if ($idCorrect && $nomCorrect && $identifiant_correct) {	
						Groupe_Etudiants::modifier_groupeEtudiants($_GET['modifier_groupeEtudiants'], $_GET['idPromotion'], $nom, $identifiant);
						array_push($messagesNotifications, "Le groupe d'étudiant a bien été modifié");
						$validation_ajout = true;
					}
				}
				
				// Traitement des erreurs
				if (!$validation_ajout) {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
					if (isset($idCorrect) && !$idCorrect) {
						array_push($messagesErreurs, "L'id du groupe d'étudiants n'est pas correct, contacter un administrateur");
					}
					if (!$nomCorrect) {
						array_push($messagesErreurs, "Le nom n'est pas correct");
					}
					if (!$identifiant_correct) {
						array_push($messagesErreurs, "L'identifiant n'est pas correct");
					}
				}
			}
		}
		
		public static function prise_en_compte_suppression() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_GET['supprimer_groupeEtudiants'])) {	
				if (Groupe_Etudiants::existe_groupeEtudiants($_GET['supprimer_groupeEtudiants'])) {
					// Le groupe d'étudiant existe
					Groupe_Etudiants::supprimer_groupeEtudiants($_GET['supprimer_groupeEtudiants']);
					array_push($messagesNotifications, "Le groupe d'étudiant à bien été supprimé");
				}
				else {
					// Le groupe d'étudiant n'existe pas
					array_push($messagesErreurs, "Le groupe d'étudiant n'existe pas");
				}
			}
		}		
		
		public static function page_administration($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			Groupe_Etudiants::formulaireAjoutGroupeEtudiants($_GET['idPromotion'], $nombreTabulations + 1);
			echo $tab."<h2>Liste des groupes d'étudiants</h2>\n";
			Groupe_Etudiants::liste_groupeEtudiants_to_table($_GET['idPromotion'], $nombreTabulations + 1);
		}
		
		public function toString() {
			$string = "";
			foreach (Groupe_Etudiants::$attributs as $att) {
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
		
		public static function creer_table() {
			return Utils_SQL::sql_from_file("./sql/".Groupe_Etudiants::$nomTable.".sql");
		}
		
		public static function supprimer_table() {
			return Utils_SQL::sql_supprimer_table(Groupe_Etudiants::$nomTable);
		}
	}
