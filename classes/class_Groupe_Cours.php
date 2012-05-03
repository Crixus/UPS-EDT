<?php
	/**
	 * Classe qui permet de gérer les Groupe_Cours
	 */
	class Groupe_Cours {
		
		public static $nomTable = "Groupe_Cours";
		
		public static $attributs = Array(
			"nom",
			"identifiant"
		);
		
		/**
		 * Getter du nom du Groupe_Cours
		 * @return string : nom du Groupe_Cours
		 */
		public function getNom() {
			return $this->nom;
		}
		
		/**
		 * Getter du identifiant du Groupe_Cours
		 * @return string : identifiant du Groupe_Cours
		 */
		public function getIdentifiant() {
			return $this->identifiant;
		}
		
		/**
		 * Constructeur de la classe Groupe_Cours
		 * Récupère les informations de Groupe_Cours dans la base de données depuis l'id
		 * @param $id : int id du Groupe_Cours
		 */
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
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Fonction testant l'existence d'un groupe de cours
		 * @param $id : int id du groupe de cours
		 */
		public static function existeGroupeCours($id) {
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
		
		/**
		 * Fonction renvoyant le nombre de groupe de cours de la promotion
		 * @param $idPromotion : int idPromotion du groupe de cours
		 */
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
		
		/**
		 * Ajouter un groupe de cours dans la base de données
		 * @param $idPromotion : int idPromotion du groupe de cours
		 * @param $nom : int nom du groupe de cours
		 * @param $identifiant : int identifiant du groupe de cours
		 */
		public static function ajouterGroupeCours($idPromotion, $nom, $identifiant) {
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
		
		/**
		 * Modifier un groupe de cours dans la base de données
		 * @param $idGroupeCours : int id du groupe de cours a modifié
		 * @param $idPromotion : int idPromotion du groupe de cours
		 * @param $nom : int nom du groupe de cours
		 * @param $identifiant : int identifiant du groupe de cours
		 */
		public static function modifierGroupeCours($idGroupeCours, $idPromotion, $nom, $identifiant) {
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
		
		/**
		 * Supprime un groupe de cours dans la base de données
		 * @param $idGroupeCours int : id du groupe de cours a supprimé
		 */
		public static function supprimerGroupeCours($idGroupeCours) {
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
		
		/**
		 * Liste des groupes de cours de la promotion enregistrée dans la base de donnée
		 * @param $idPromotion int : id de la promotion sélectionnée
		 * @return List<Groupe_Cours> liste des groupes de cours
		 */
		public static function listeGroupeCours($idPromotion) {
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
		
		/**
		 * Fonction utilisée pour l'affichage de la liste des groupes de cours créé 
		 * @param $idPromotion int : id de la promotion sélectionnée
		 * @param $administration boolean : possibilité de modification et suppression si egal à 1
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public static function listeGroupeCoursToTable($idPromotion, $administration, $nombreTabulations = 0) {
			// Liste des groupes de cours de la promotion enregistrée dans la base de donnée
			$listeGroupeCours = Groupe_Cours::listeGroupeCours($idPromotion);
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
				foreach ($listeGroupeCours as $idGroupeCours) {
					$_GroupeCours = new Groupe_Cours($idGroupeCours);
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					echo $tab."\t<tr class=\"".$couleurFond."\">\n";
					
					// Gestion de l'affichage des informations du groupe de cours
					foreach (Groupe_Cours::$attributs as $att) {
						echo $tab."\t\t<td>".$_GroupeCours->$att."</td>\n";
					}
					
					// Création des liens pour la modification et la suppression des groupes de cours et gestion de l'URL 
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
		
		/**
		 * Fonction utilisée pour l'affichage du formulaire utilisé pour l'ajout d'un groupe de cours
		 * @param $idPromotion int : id de la promotion sélectionnée
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public function formulaireAjoutGroupeCours($idPromotion, $nombresTabulations = 0) {
			$tab = ""; while ($nombresTabulation = 0) { $tab .= "\t"; $nombresTabulations--; }
			
			// Gestion du formulaire suivant si on ajoute ou on modifie d'un groupe de cours
			if (isset($_GET['modifier_groupeCours'])) { 
				$titre = "Modifier un groupe de cours";
				$_GroupeCours = new Groupe_Cours($_GET['modifier_groupeCours']);
				$nomModif = "value=\"".$_GroupeCours->getNom()."\"";
				$_Promotion = new Promotion($idPromotion);
				$nomPromotion = $_Promotion->getNom();
				$anneePromotion = $_Promotion->getAnnee();
				$preIdentifiant = $anneePromotion."-".$nomPromotion."-";
				$identifiantModif = "value=\"".$_GroupeCours->getIdentifiant()."\"";
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
				$_Promotion = new Promotion($idPromotion);
				$nomPromotion = $_Promotion->getNom();
				$anneePromotion = $_Promotion->getAnnee();
				$preIdentifiant = $anneePromotion."-".$nomPromotion."-";
				$identifiantModif = "value=\"".$preIdentifiant."\"";
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
			echo $tab."\t\t\t\t<input name=\"nom\" type=\"text\" onChange=\"modification_identifiant('".$preIdentifiant."')\" required ".$nomModif."/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Identifiant</label></td>\n";
			echo $tab."\t\t\t<td><input name=\"identifiant\" type=\"text\" disabled=\"disabled\" required ".$identifiantModif."/></td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td></td>\n";
			echo $tab."\t\t\t<td>".$hidden."<input type=\"submit\" name=\"".$nameSubmit."\" value=\"".$valueSubmit."\"></td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t</table>\n";
			echo $tab."</form>\n";
			
			if (isset($lienAnnulation)) {
				echo $tab."<p><a href=\"".$lienAnnulation."\">Annuler modification</a></p>";}				
		}		
		
		/**
		 * Fonction permettant de prendre en compte les informations validées dans le formulaire pour la MAJ de la base de données
		 */
		public static function priseEnCompteFormulaire() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_POST['validerAjoutGroupeCours']) || isset($_POST['validerModificationGroupeCours'])) {
				// Vérification des champs
				$nom = htmlentities($_POST['nom'], ENT_QUOTES, 'UTF-8');
				$nomCorrect = PregMatch::est_nom($nom);
				
				$_Promotion = new Promotion($_GET['idPromotion']);
				$nomPromotion = $_Promotion->getNom();
				$anneePromotion = $_Promotion->getAnnee();
				$preIdentifiant = $anneePromotion."-".$nomPromotion."-";
				$identifiant = $preIdentifiant.$nom;
				$identifiantCorrect = $nomCorrect;
				
				$validationAjout = false;
				if (isset($_POST['validerAjoutGroupeCours'])) {
					// Ajout d'un nouveau groupe de cours					
					if ($nomCorrect && $identifiantCorrect) {		
						Groupe_Cours::ajouterGroupeCours($_GET['idPromotion'], $nom, $identifiant);
						array_push($messagesNotifications, "Le groupe de cours a bien été ajouté");
						$validationAjout = true;
					}
				} else {
					// Modification d'un etudiant
					$id = htmlentities($_POST['id']);
					$idCorrect = Groupe_Cours::existeGroupeCours($id);				
					if ($idCorrect && $nomCorrect && $identifiantCorrect) {	
						Groupe_Cours::modifierGroupeCours($_GET['modifier_groupeCours'], $_GET['idPromotion'], $nom, $identifiant);
						array_push($messagesNotifications, "Le groupe de cours a bien été modifié");
						$validationAjout = true;
					}
				}
				
				// Traitement des erreurs
				if (!$validationAjout) {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
					if (isset($idCorrect) && !$idCorrect) {
						array_push($messagesErreurs, "L'id du groupe de cours n'est pas correct, contacter un administrateur");
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
		 * Fonction permettant de prendre en compte la validation d'une demande de suppression d'un groupe de cours, on test s'il est bien enregistré dans la base de donnée
		 */
		public static function priseEnCompteSuppression() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_GET['supprimer_groupeCours'])) {	
				if (Groupe_Cours::existeGroupeCours($_GET['supprimer_groupeCours'])) {
					// Le groupe de cours existe
					Groupe_Cours::supprimerGroupeCours($_GET['supprimer_groupeCours']);
					array_push($messagesNotifications, "Le groupe de cours à bien été supprimé");
				} 
				else {
					// Le groupe de cours n'existe pas
					array_push($messagesErreurs, "Le groupe de cours n'existe pas");
				}
			}
		}
		
		/**
		* Fonction principale permettant l'affichage du formulaire d'ajout ou de modification d'un groupe de cours ainsi que l'affichage des groupes de cours de la promotion enregistrée dans la base de données
		*/
		public static function pageAdministration($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			Groupe_Cours::formulaireAjoutGroupeCours($_GET['idPromotion'], $nombreTabulations + 1);
			echo $tab."<h2>Liste des groupes de cours</h2>\n";
			Groupe_Cours::listeGroupeCoursToTable($_GET['idPromotion'], $nombreTabulations + 1);
		}	
	}
