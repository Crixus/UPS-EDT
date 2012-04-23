<?php
	/*
	 * Classe qui permet de gérer les Groupe_Cours
	 */
	class Groupe_Cours {
		
		public static $nomTable = "Groupe_Cours";
		
		public static $attributs = Array(
			"nom",
			"identifiant"
		);
		
		public function getNom() {
			return $this->nom;
		}
		public function getIdentifiant() {
			return $this->identifiant;
		}
		
		public function Groupe_Cours($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Groupe_Cours::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Groupe_Cours::$attributs as $att) {
					$this->$att = $ligne["$att"];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function existe_groupeCours($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Groupe_Cours::$nomTable." WHERE id=?");
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
		
		public function getNbreGroupeCours($idPromotion) { 
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Groupe_Cours::$nomTable." WHERE idPromotion=?");
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
		
		public static function ajouter_groupeCours($idPromotion, $nom, $identifiant) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Groupe_Cours::$nomTable." VALUES(?, ?, ?, ?)");
				
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
		
		public static function modifier_groupeCours($idGroupeCours, $idPromotion, $nom, $identifiant) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Groupe_Cours::$nomTable." SET nom=?, identifiant=?, idPromotion=? WHERE id=?;");
				$req->execute(
					Array(
						$nom, 
						$identifiant, 
						$idPromotion,
						$idGroupeCours
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function supprimer_groupeCours($idGroupeCours) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Groupe_Cours::$nomTable." WHERE id=?;");
				$req->execute(
					Array(
						$idGroupeCours
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function liste_groupeCours($idPromotion) {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Groupe_Cours::$nomTable." WHERE idPromotion=? ORDER BY nom");
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
		
		public static function liste_groupeCours_to_table($idPromotion, $administration, $nombreTabulations = 0) {
			$liste_groupeCours = Groupe_Cours::liste_groupeCours($idPromotion);
			$nbreGroupeCours = Groupe_Cours::getNbreGroupeCours($idPromotion);
			$tab = ""; while ($nombreTabulations > 0) { $tab .= "\t"; $nombreTabulations--; }
			
			if ($nbreGroupeCours == 0) {
				echo $tab."<h2>Aucun groupe de cours n'a été créés pour cette promotion</h2>\n";
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
				foreach ($liste_groupeCours as $idGroupeCours) {
					$Groupe_Cours = new Groupe_Cours($idGroupeCours);
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					echo $tab."\t<tr class=\"$couleurFond\">\n";
					foreach (Groupe_Cours::$attributs as $att) {
						echo $tab."\t\t<td>".$Groupe_Cours->$att."</td>\n";
					}
					
					if ($administration) {
						$pageModification = "./index.php?page=ajoutGroupeCours&amp;modifier_groupeCours=$idGroupeCours";
						$pageSuppression = "./index.php?page=ajoutGroupeCours&amp;supprimer_groupeCours=$idGroupeCours";
						if (isset($_GET['idPromotion'])) {
							$pageModification .= "&amp;idPromotion=".$_GET['idPromotion'];
							$pageSuppression .= "&amp;idPromotion=".$_GET['idPromotion'];
						}
						echo $tab."\t\t<td>";
						echo "<a href=\"".$pageModification."\"><img src=\"../images/modify.png\" alt=\"icone de modification\" /></a>";
						echo "<a href=\"".$pageSuppression."\" onclick=\"return confirm('Supprimer le groupe de cours ?')\"><img src=\"../images/delete.png\" alt=\"icone de suppression\" /></a>";
						echo "</td>\n";
					}
					echo $tab."\t</tr>\n";
				}
				
				echo $tab."</table>\n";
			}
		}
		
		public function formulaireAjoutGroupeCours($idPromotion, $nombresTabulations = 0) {
			$tab = ""; while ($nombresTabulation = 0) { $tab .= "\t"; $nombresTabulations--; }
			
			if (isset($_GET['modifier_groupeCours'])) { 
				$titre = "Modifier un groupe de cours";
				$Groupe_Cours = new Groupe_Cours($_GET['modifier_groupeCours']);
				$nomModif = "value=\"".$Groupe_Cours->getNom()."\"";
				$Promotion = new Promotion($idPromotion);
				$nom_promotion = $Promotion->getNom();
				$annee_promotion = $Promotion->getAnnee();
				$pre_identifiant = $annee_promotion."-".$nom_promotion."-";
				$identifiantModif = "value=\"".$Groupe_Cours->getIdentifiant()."\"";
				$valueSubmit = "Modifier le groupe de cours"; 
				$nameSubmit = "validerModificationGroupeCours";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"".$_GET['modifier_groupeCours']."\" />";
				$lienAnnulation = "index.php?page=ajoutGroupeCours";
				if (isset($_GET['idPromotion'])) {
					$lienAnnulation .= "&amp;idPromotion=".$_GET['idPromotion'];
				}
			}
			else {
				$titre = "Ajouter un groupe de cours";
				$nomModif = (isset($_POST['nom'])) ? "value=\"".$_POST['nom']."\"" : "value=\"\"";
				$Promotion = new Promotion($idPromotion);
				$nom_promotion = $Promotion->getNom();
				$annee_promotion = $Promotion->getAnnee();
				$pre_identifiant = $annee_promotion."-".$nom_promotion."-";
				$identifiantModif = "value=\"".$pre_identifiant."\"";
				$valueSubmit = "Ajouter le groupe de cours"; 
				$nameSubmit = "validerAjoutGroupeCours";
				$hidden = "";
			}		
		
			echo $tab."<h2>".$titre."</h2>\n";
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
			echo $tab."\t\t\t<td>".$hidden."<input type=\"submit\" name=\"".$nameSubmit."\" value=\"{$valueSubmit}\"></td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t</table>\n";
			echo $tab."</form>\n";
			
			if (isset($lienAnnulation)) {
				echo $tab."<p><a href=\"".$lienAnnulation."\">Annuler modification</a></p>";}				
		}		
		
		public static function prise_en_compte_formulaire() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_POST['validerAjoutGroupeCours']) || isset($_POST['validerModificationGroupeCours'])) {
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
				if (isset($_POST['validerAjoutGroupeCours'])) {
					// Ajout d'un nouveau groupe de cours					
					if ($nomCorrect && $identifiant_correct) {		
						Groupe_Cours::ajouter_groupeCours($_GET['idPromotion'], $nom, $identifiant);
						array_push($messagesNotifications, "Le groupe de cours a bien été ajouté");
						$validation_ajout = true;
					}
				} else {
					// Modification d'un etudiant
					$id = htmlentities($_POST['id']);
					$idCorrect = Groupe_Cours::existe_groupeCours($id);				
					if ($idCorrect && $nomCorrect && $identifiant_correct) {	
						Groupe_Cours::modifier_groupeCours($_GET['modifier_groupeCours'], $_GET['idPromotion'], $nom, $identifiant);
						array_push($messagesNotifications, "Le groupe de cours a bien été modifié");
						$validation_ajout = true;
					}
				}
				
				// Traitement des erreurs
				if (!$validation_ajout) {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
					if (isset($idCorrect) && !$idCorrect) {
						array_push($messagesErreurs, "L'id du groupe de cours n'est pas correct, contacter un administrateur");
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
			if (isset($_GET['supprimer_groupeCours'])) {	
				if (Groupe_Cours::existe_groupeCours($_GET['supprimer_groupeCours'])) {
					// Le groupe de cours existe
					Groupe_Cours::supprimer_groupeCours($_GET['supprimer_groupeCours']);
					array_push($messagesNotifications, "Le groupe de cours à bien été supprimé");
				} else {
					// Le groupe de cours n'existe pas
					array_push($messagesErreurs, "Le groupe de cours n'existe pas");
				}
			}
		}
		
		public static function page_administration($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			Groupe_Cours::formulaireAjoutGroupeCours($_GET['idPromotion'], $nombreTabulations + 1);
			echo $tab."<h2>Liste des groupes de cours</h2>\n";
			Groupe_Cours::liste_groupeCours_to_table($_GET['idPromotion'], $nombreTabulations + 1);
		}	
		
		public function toString() {
			$string = "";
			foreach (Groupe_Cours::$attributs as $att) {
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
	}
