<?php
	/*
	 * Classe qui permet de gérer les Groupe_Etudiants
	 */
	class Groupe_Etudiants{
		
		public static $nomTable = "Groupe_Etudiants";
		
		public static $attributs = Array(
			"nom",
			"identifiant"
		);
		
		/**
		 * Getter du nom du Groupe_Etudiants
		 * @return string : nom du Groupe_Etudiants
		 */
		public function getNom() { return $this->nom; }
		
		/**
		 * Getter du identifiant du Groupe_Etudiants
		 * @return string : identifiant du Groupe_Etudiants
		 */
		public function getIdentifiant() { return $this->identifiant; }
		
		/**
		 * Constructeur de la classe Groupe_Etudiants
		 * Récupère les informations de Groupe_Etudiants dans la base de données depuis l'id
		 * @param $id : int id du Groupe_Etudiants
		 */
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
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Fonction testant l'existence d'un groupe d'étudiants
		 * @param $id : int id du groupe d'étudiants
		 */
		public static function existeGroupeEtudiants($id) {
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
		
		/**
		 * Fonction renvoyant le nombre de groupe d'étudiants de la promotion
		 * @param $idPromotion : int idPromotion du groupe d'étudiants
		 */
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
		
		/**
		 * Ajouter un groupe d'étudiants dans la base de données
		 * @param $idPromotion : int idPromotion du groupe d'étudiants
		 * @param $nom : int nom du groupe d'étudiants
		 * @param $identifiant : int identifiant du groupe d'étudiants
		 */
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
		
		/**
		 * Modifier un groupe d'étudiants dans la base de données
		 * @param $idGroupeEtudiants : int id du groupe d'étudiants a modifié
		 * @param $idPromotion : int idPromotion du groupe d'étudiants
		 * @param $nom : int nom du groupe d'étudiants
		 * @param $identifiant : int identifiant du groupe d'étudiants
		 */
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
		
		/**
		 * Supprime un groupe d'étudiants dans la base de données
		 * @param $idGroupeEtudiants int : id du groupe d'étudiants a supprimé
		 */
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
		
		/**
		 * Liste des groupes d'étudiants de la promotion enregistrée dans la base de donnée
		 * @param $idPromotion int : id de la promotion sélectionnée
		 * @return List<Groupe_Etudiants> liste des groupes d'étudiants
		 */
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
		
		/**
		 * Fonction utilisée pour l'affichage de la liste des groupes d'étudiants créé 
		 * @param $idPromotion int : id de la promotion sélectionnée
		 * @param $administration boolean : possibilité de modification et suppression si egal à 1
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public static function liste_groupeEtudiants_to_table($idPromotion, $administration, $nombreTabulations = 0) {
			// Liste des groupes d'étudiants de la promotion enregistrée dans la base de donnée
			$listeGroupeEtudiants = Groupe_Etudiants::liste_groupeEtudiants($idPromotion);
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
				foreach ($listeGroupeEtudiants as $idGroupeEtudiants) {
					$_GroupeEtudiants = new Groupe_Etudiants($idGroupeEtudiants);
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					echo $tab."\t<tr class=\"".$couleurFond."\">\n";
					
					// Gestion de l'affichage des informations du groupes d'étudiants
					foreach (Groupe_Etudiants::$attributs as $att) {
						echo $tab."\t\t<td>".$_GroupeEtudiants->$att."</td>\n";
					}
					
					// Création des liens pour la modification et la suppression des groupes d'étudiants et gestion de l'URL 
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
		
		/**
		 * Fonction utilisée pour l'affichage du formulaire utilisé pour l'ajout d'un groupe d'étudiants
		 * @param $idPromotion int : id de la promotion sélectionnée
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public function formulaireAjoutGroupeEtudiants($idPromotion, $nombresTabulations = 0) {
			$tab = ""; while ($nombresTabulation = 0) { $tab .= "\t"; $nombresTabulations--; }
			
			// Gestion du formulaire suivant si on ajoute ou on modifie d'un groupe d'étudiants
			if (isset($_GET['modifier_groupeEtudiants'])) { 
				$titre = "Modifier un groupe d'étudiant";
				$_GroupeEtudiants = new Groupe_Etudiants($_GET['modifier_groupeEtudiants']);
				$nomModif = "value=\"{$_GroupeEtudiants->getNom()}\"";
				$_Promotion = new Promotion($idPromotion);
				$nomPromotion = $_Promotion->getNom();
				$anneePromotion = $_Promotion->getAnnee();
				$preIdentifiant = "{$anneePromotion}-{$nomPromotion}-";
				$identifiantModif = "value=\"{$_GroupeEtudiants->getIdentifiant()}\"";
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
				$_Promotion = new Promotion($idPromotion);
				$nomPromotion = $_Promotion->getNom();
				$anneePromotion = $_Promotion->getAnnee();
				$preIdentifiant = "{$anneePromotion}-{$nomPromotion}-";
				$identifiantModif = "value=\"{$preIdentifiant}\"";
				$valueSubmit = "Ajouter le groupe d'étudiant"; 
				$nameSubmit = "validerAjoutGroupeEtudiants";
				$hidden = "";
			}		
		
			echo $tab."<h2>".$titre."</h2>\n";
			echo $tab."<form method=\"post\">\n";
			echo $tab."\t<table>\n";
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Nom</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"nom\" type=\"text\" onChange=\"modification_identifiant('{$preIdentifiant}')\" required {$nomModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Identifiant</label></td>\n";
			echo $tab."\t\t\t<td><input name=\"identifiant\" type=\"text\" disabled=\"disabled\" required {$identifiantModif}/></td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td></td>\n";
			echo $tab."\t\t\t<td>".$hidden."<input type=\"submit\" name=\"".$nameSubmit."\" value=\"".$valueSubmit."\"></td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t</table>\n";
			echo $tab."</form>\n";
			
			if (isset($lienAnnulation)) {echo $tab."<p><a href=\"".$lienAnnulation."\">Annuler modification</a></p>";}			
		}		
		
		/**
		 * Fonction permettant de prendre en compte les informations validées dans le formulaire pour la MAJ de la base de données
		 */
		public static function priseEnCompteFormulaire() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_POST['validerAjoutGroupeEtudiants']) || isset($_POST['validerModificationGroupeEtudiants'])) {
				// Vérification des champs
				$nom = htmlentities($_POST['nom'], ENT_QUOTES, 'UTF-8');
				$nomCorrect = PregMatch::est_nom($nom);
				
				$_Promotion = new Promotion($_GET['idPromotion']);
				$nomPromotion = $_Promotion->getNom();
				$anneePromotion = $_Promotion->getAnnee();
				$preIdentifiant = "{$anneePromotion}-{$nomPromotion}-";
				$identifiant = $preIdentifiant.$nom;
				$identifiantCorrect = $nomCorrect;
				
				$validationAjout = false;
				if (isset($_POST['validerAjoutGroupeEtudiants'])) {
					// Ajout d'un nouveau groupe d'étudiants				
					if ($nomCorrect && $identifiantCorrect) {	
						Groupe_Etudiants::ajouter_groupeEtudiants($_GET['idPromotion'], $nom, $identifiant);
						array_push($messagesNotifications, "Le groupe d'étudiant a bien été ajouté");
						$validationAjout = true;
					}
				}
				else {
					// Modification d'un groupe d'étudiants
					$id = htmlentities($_POST['id']);
					$idCorrect = Groupe_Etudiants::existeGroupeEtudiants($id);
					if ($idCorrect && $nomCorrect && $identifiantCorrect) {	
						Groupe_Etudiants::modifier_groupeEtudiants($_GET['modifier_groupeEtudiants'], $_GET['idPromotion'], $nom, $identifiant);
						array_push($messagesNotifications, "Le groupe d'étudiant a bien été modifié");
						$validationAjout = true;
					}
				}
				
				// Traitement des erreurs
				if (!$validationAjout) {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
					if (isset($idCorrect) && !$idCorrect) {
						array_push($messagesErreurs, "L'id du groupe d'étudiants n'est pas correct, contacter un administrateur");
					}
					if (!$nomCorrect) {
						array_push($messagesErreurs, "Le nom n'est pas correct");
					}
					if (!$identifiantCorrect) {
						array_push($messagesErreurs, "L'identifiant n'est pas correct");
					}
				}
			}
		}
		
		/**
		 * Fonction permettant de prendre en compte la validation d'une demande de suppression d'un groupe d'étudiants, on test s'il est bien enregistré dans la base de donnée
		 */
		public static function priseEnCompteSuppression() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_GET['supprimer_groupeEtudiants'])) {	
				if (Groupe_Etudiants::existeGroupeEtudiants($_GET['supprimer_groupeEtudiants'])) {
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
		
		/**
		* Fonction principale permettant l'affichage du formulaire d'ajout ou de modification d'un groupe d'étudiants ainsi que l'affichage des groupes de cours de la promotion enregistrée dans la base de données
		*/
		public static function pageAdministration($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			Groupe_Etudiants::formulaireAjoutGroupeEtudiants($_GET['idPromotion'], $nombreTabulations + 1);
			echo $tab."<h2>Liste des groupes d'étudiants</h2>\n";
			Groupe_Etudiants::liste_groupeEtudiants_to_table($_GET['idPromotion'], $nombreTabulations + 1);
		}
	}
