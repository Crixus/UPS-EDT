<?php
	/** 
	 * Classe Type_Cours - Permet de gérer les types de cours
	 */ 
	class Type_Cours{
		
		public static $nomTable = "Type_Cours";
		
		public static $attributs = Array(
			"nom"
		);
		
		/**
		 * Getter du nom du type de cours
		 * @return string : nom du type de cours
		 */
		public function getNom() { return $this->nom; }
		
		/**
		 * Constructeur de la classe Type_Cours
		 * Récupère les informations de Type_Cours dans la base de données depuis l'id
		 * @param $id : int id du Type_Cours
		 */
		public function Type_Cours($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Type_Cours::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Type_Cours::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Renvoi le nombre de ype de cours enregistré dans la base de données
		 * @return int : nombre de type de cours
		 */
		public function getNbreTypeCours() { 
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Type_Cours::$nomTable);
				$req->execute();
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne["nb"];
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Fonction testant l'existence d'un type de cours
		 * @param id : int id du type de cours
		 */
		public static function existe_typeCours($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Type_Cours::$nomTable." WHERE id=?");
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
		 * Renvoi la liste d'id des types de cours
		 * @return List<Type_Cours> liste des id des types de cours
		 */
		public function liste_id_type_cours() {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->query("SELECT * FROM ".Type_Cours::$nomTable." ORDER BY nom");
				
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
		 * Renvoi la liste des noms des types de cours
		 * @return List<Type_Cours> liste des noms des types de cours
		 */
		public function liste_nom_type_cours() {
			$listeNom = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->query("SELECT nom FROM ".Type_Cours::$nomTable." ORDER BY nom");
				
				while ($ligne = $req->fetch()) {
					array_push($listeNom, $ligne['nom']);
				}
				$req->closeCursor();
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeNom;
		}
		
		/**
		 * Fonction utilisée pour l'affichage de la liste des types de cours créé 
		 * @param $administration boolean : possibilité de modification et suppression si egal à 1
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public static function liste_type_cours_to_table($administration, $nombreTabulations = 0) {
			//Liste des types de cours enregistrés dans la base de donnée
			$listeTypeCours = Type_Cours::liste_id_type_cours();
			$nbre_type_cours = sizeof($listeTypeCours);
			
			//Liste des types de salles enregistrés dans la base de donnée
			$liste_type_salle = Type_Salle::liste_id_type_salle();
			$nbre_type_salle = sizeof($liste_type_salle);
			
			$tab = ""; while ($nombreTabulations > 0) { $tab .= "\t"; $nombreTabulations--; }
			
			if ($nbre_type_cours == 0) {
				echo $tab."<b>Aucun type de cours n'est enregistré</b>\n";
			}
			else {
				echo $tab."<table class=\"table_liste_administration\">\n";
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				echo $tab."\t\t<th rowspan='2'>Nom</th>\n";
				if ($nbre_type_salle != 0)
					echo $tab."\t\t<th colspan='{$nbre_type_salle}'>Type de salles</th>\n";
				
				if ($administration) {
					echo $tab."\t\t<th rowspan='2'>Actions</th>\n";
				}
				echo $tab."\t</tr>\n";
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				
				foreach ($liste_type_salle as $idType_Salle) {					
					$Type_Salle = new Type_Salle($idType_Salle);
					$nomType_Salle = $Type_Salle->getNom();
					echo $tab."\t\t<th>".$nomType_Salle."</th>\n";
				}
				echo $tab."\t</tr>\n";
				
				$cpt = 0;
				// Gestion de l'affichage des informations du type de cours
				foreach ($listeTypeCours as $idTypeCours) {
					$_TypeCours = new Type_Cours($idTypeCours);
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					echo $tab."\t<tr class=\"".$couleurFond."\">\n";
					foreach (Type_Cours::$attributs as $att) {
						echo $tab."\t\t<td>".$_TypeCours->$att."</td>\n";
					}
					
					foreach ($liste_type_salle as $idTypeSalle) {					
						$Type_Salle = new Type_Salle($idTypeSalle);
						$nomType_Salle = $Type_Salle->getNom();
						if (Type_Cours::appartenance_typeSalle_typeCours($idTypeCours, $idTypeSalle)) 
							$checked = "checked = \"checked\"";
						else
							$checked = "";
						$nomCheckbox = "{$idTypeCours}_{$nomType_Salle}";
						echo $tab."\t\t<td><input type=\"checkbox\" name= \"{$idTypeCours}_{$nomType_Salle}\" value=\"{$idTypeSalle}\" onclick=\"appartenance_typeSalle_typeCours({$idTypeCours},{$idTypeSalle},this)\" style=\"cursor:pointer\" {$checked}></td>\n";
					}
					
					// Création des liens pour la modification et la suppression des types de cours et gestion de l'URL 
					if ($administration) {
						$pageModification = "./index.php?page=ajoutTypeCours&amp;modifier_type_cours=".$idTypeCours;
						$pageSuppression = "./index.php?page=ajoutTypeCours&amp;supprimer_type_cours=".$idTypeCours;
						if (isset($_GET['idPromotion'])) {
							$pageModification .= "&amp;idPromotion=".$_GET['idPromotion'];
							$pageSuppression .= "&amp;idPromotion=".$_GET['idPromotion'];
						}
						echo $tab."\t\t<td>";
						echo "<a href=\"".$pageModification."\"><img src=\"../images/modify.png\" style=\"cursor:pointer;\" alt=\"icone de modification\" /></a>";
						echo "<a href=\"".$pageSuppression."\" onclick=\"return confirm('Supprimer le type de cours ?')\"><img src=\"../images/delete.png\" style=\"cursor:pointer;\" alt=\"icone de suppression\" /></a>";
					}
					echo $tab."\t</tr>\n";
				}
				
				echo $tab."</table>\n";
			}
		}
		
		/**
		 * Ajouter un type de cours dans la base de données
		 * @param $nom : nom du type de cours
		 */
		public static function ajouter_type_cours($nom) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Type_Cours::$nomTable." VALUES(?, ?)");
				
				$req->execute(
					Array(
						"",
						$nom
					)
				);			
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Modifier un type de cours dans la base de données
		 * @param $idTypeSalle : int id du type de cours a modifié
		 * @param $nom : nom du type de cours
		 */
		public static function modifier_type_cours($idTypeCours, $nom) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Type_Cours::$nomTable." SET nom=? WHERE id=?;");
				$req->execute(
					Array(
						$nom, 
						$idTypeCours
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Supprime un type de cours dans la base de données
		 * @param $idTypeSalle int : id du type de cours a supprimé
		 */
		public static function supprimer_type_cours($idTypeCours) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Type_Cours::$nomTable." WHERE id=?;");
				$req->execute(
					Array(
						$idTypeCours
					)
				);
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Fonction utilisée pour l'affichage du formulaire utilisé pour l'ajout d'un type de cours
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public function formulaireAjoutTypeCours($nombresTabulations = 0) {
			$tab = ""; while ($nombresTabulation = 0) { $tab .= "\t"; $nombresTabulations--; }
			
			// Gestion du formulaire suivant si on ajoute ou on modifie un type de cours
			if (isset($_GET['modifier_type_cours'])) { 
				$titre = "Modifier un type de cours";
				$_TypeCours = new Type_Cours($_GET['modifier_type_cours']);
				$nomModif = "value=\"{$_TypeCours->getNom()}\"";
				$valueSubmit = "Modifier le type de cours";
				$nameSubmit = "validerModificationTypeCours";				
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_type_cours']}\" />";
				$lienAnnulation = "index.php?page=ajoutTypeCours";
				if (isset($_GET['idPromotion'])) {
					$lienAnnulation .= "&amp;idPromotion=".$_GET['idPromotion'];
				}
			}
			else {
				$titre = "Ajouter un type de cours";
				$nomModif = (isset($_POST['nom'])) ? "value=\"".$_POST['nom']."\"" : "value=\"\"";
				$valueSubmit = "Ajouter le type de cours";
				$nameSubmit = "validerAjoutTypeCours";
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
			if (isset($_POST['validerAjoutTypeCours']) || isset($_POST['validerModificationTypeCours'])) {
				// Vérification des champs
				$nom = htmlentities($_POST['nom'], ENT_QUOTES, 'UTF-8');
				$nomCorrect = PregMatch::est_nom($nom);
				
				$validationAjout = false;
				if (isset($_POST['validerAjoutTypeCours'])) {
					// Ajout d'un nouveau type de cours					
					if ($nomCorrect) {		
						Type_Cours::ajouter_type_cours($nom);
						array_push($messagesNotifications, "Le type de cours a bien été ajouté");
						$validationAjout = true;
					}
				}
				else {			
					// Modification d'un type de cours	
					$id = htmlentities($_POST['id']);
					$idCorrect = Type_Cours::existe_typeCours($id);
					if ($idCorrect && $nomCorrect) {
						Type_Cours::modifier_type_cours($_GET['modifier_type_cours'], $nom);
						array_push($messagesNotifications, "Le type de cours a bien été modifié");
						$validationAjout = true;
					}
				}
				
				// Traitement des erreurs
				if (!$validationAjout) {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
					if (isset($idCorrect) && !$idCorrect) {
						array_push($messagesErreurs, "L'id du type de cours n'est pas correct, contacter un administrateur");
					}
					if (!$nomCorrect) {
						array_push($messagesErreurs, "Le nom n'est pas correct");
					}
				}
			}
		}
		
		/**
		 * Fonction permettant de prendre en compte la validation d'une demande de suppression d'un type de cours, on test s'il est bien enregistré dans la base de donnée
		 */
		public static function priseEnCompteSuppression() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_GET['supprimer_type_cours'])) {	
				if (Type_Cours::existe_typeCours($_GET['supprimer_type_cours'])) {
					// Le type de cours existe
					Type_Cours::supprimer_type_cours($_GET['supprimer_type_cours']);
					array_push($messagesNotifications, "Le type de cours a bien été supprimé");
				}
				else {
					// Le type de cours n'existe pas
					array_push($messagesErreurs, "Le type de cours n'existe pas");
				}
			}
		}
		
		/**
		* Fonction principale permettant l'affichage du formulaire d'ajout ou de modification d'un type de cours ainsi que l'affichage des types de cours enregistrés dans la base de données
		* @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		*/
		public static function pageAdministration($nombreTabulations = 0) {
			$tab = ""; while ($nombreTabulations > 0) { $tab .= "\t"; $nombreTabulations--; }
			Type_Cours::formulaireAjoutTypeCours($nombreTabulations + 1);
			echo $tab."<h2>Liste des types de cours</h2>\n";
			Type_Cours::liste_type_cours_to_table($nombreTabulations + 1);
		}
		
		/**
		 * Fonction testant l'appartenance d'un type de cours à un type de salle
		 * @param $idType_Cours int : id  du type de cours
		 * @param $idType_Salle int : id du type de salle
		 * @return boolean : renvoi 1 si le type de cours appartient bien à ce type de salle, 0 sinon
		 */
		public function appartenance_typeSalle_typeCours($idType_Cours, $idType_Salle) {
			$appartient = 0;
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM ".Appartient_TypeSalle_TypeCours::$nomTable." WHERE idTypeCours = ? AND idTypeSalle = ?");
				$req->execute(
					array (
						$idType_Cours,
						$idType_Salle
					)
				);
				$ligne = $req->fetch();
				$appartient = $ligne['nb'];
	
				$req->closeCursor();
				return $appartient;
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}			
		}
	}
