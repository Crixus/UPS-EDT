<?php
	class Type_Salle{
		
		public static $nomTable = "Type_Salle";
		
		public static $attributs = Array(
			"id",
			"nom"
		);
		
		public function getId() { return $this->id; }
		public function getNom() { return $this->nom; }
		
		public function Type_Salle($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Type_Salle::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Type_Salle::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function ajouter_type_salle($nom) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Type_Salle::$nomTable." VALUES(?, ?)");
				
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
		
		public static function modifierTypeSalle($idTypeSalle, $nom) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Type_Salle::$nomTable." SET nom=? WHERE id=?;");
				$req->execute(
					Array(
						$nom, 
						$idTypeSalle
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function supprimerTypeSalle($idTypeSalle) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Type_Salle::$nomTable." WHERE id=?;");
				$req->execute(
					Array($idTypeSalle)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function liste_id_type_salle() {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".Type_Salle::$nomTable." ORDER BY nom");
				$req->execute();
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
		
		public static function liste_type_salle_to_table($nombreTabulations = 0, $administration = false) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			$liste_type_salle = Type_Salle::liste_id_type_salle();
			$nbTypeSalles = sizeof($liste_type_salle);
			$liste_type_cours = Type_Cours::liste_id_type_cours();
			$nbre_type_cours = sizeof($liste_type_cours);
			
			if ($nbTypeSalles == 0) {
				echo $tab."<b>Aucun type de salles n'est enregistré</b>\n";
			}
			else {
				echo $tab."<table class=\"table_liste_administration\">\n";
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				echo $tab."\t\t<th rowspan='2'>Nom</th>\n";
				if ($nbre_type_cours != 0)
				echo $tab."\t\t<th colspan='{$nbre_type_cours}'>Type de cours</th>\n";
				
				if ($administration) {
					echo $tab."\t\t<th rowspan='2'>Actions</th>\n";
				}
				echo $tab."\t</tr>\n";
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				
				foreach ($liste_type_cours as $idType_Cours) {					
					$Type_Cours = new Type_Cours($idType_Cours);
					$nomType_Cours = $Type_Cours->getNom();
					echo $tab."\t\t<th>$nomType_Cours</th>\n";
				}
				echo $tab."\t</tr>\n";
				
				$cpt = 0;
				foreach ($liste_type_salle as $idTypeSalle) {
					$Type_Salle = new Type_Salle($idTypeSalle);
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt = ($cpt + 1)%2;
					
					echo $tab."\t<tr class=\"".$couleurFond."\">\n";
					echo $tab."\t\t<td>{$Type_Salle->getNom()}</td>\n";
					
					foreach ($liste_type_cours as $idTypeCours) {				
						$Type_Cours = new Type_Cours($idTypeCours);
						$nomType_Cours = $Type_Cours->getNom();
						if (Type_Cours::appartenance_typeSalle_typeCours($idTypeCours, $idTypeSalle)) 
							$checked = "checked = \"checked\"";
						else
							$checked = "";
						$nameCheckbox = "{$idTypeCours}_{$nomType_Cours}";
						echo $tab."\t\t<td>";
						echo "<input type=\"checkbox\" name=\"$nameCheckbox\" value=\"$idTypeSalle\" onclick=\"appartenance_typeSalle_typeCours($idTypeCours, $idTypeSalle,this)\" $checked />";
						echo "</td>\n";
					}
					
					if ($administration) {
						$pageModification = "./index.php?page=ajoutTypeSalle&amp;modifier_type_salle=$idTypeSalle";
						$pageSuppression = "./index.php?page=ajoutTypeSalle&amp;supprimer_type_salle=$idTypeSalle";
						if (isset($_GET['idPromotion'])) {
							$pageModification .= "&amp;idPromotion=".$_GET['idPromotion'];
							$pageSuppression .= "&amp;idPromotion=".$_GET['idPromotion'];
						}
						echo $tab."\t\t<td>";
						echo "<a href=\"".$pageModification."\"><img src=\"../images/modify.png\" alt=\"icone de modification\" /></a>";
						echo "<a href=\"".$pageSuppression."\" onclick=\"return confirm('Supprimer le type de salle ?')\"><img src=\"../images/delete.png\" alt=\"icone de suppression\" /></a>";
						echo "</td>\n";
					}
					echo $tab."\t</tr>\n";
				}
				echo $tab."</table>\n";
			}
		}
		
		public static function existeTypeSalle($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Type_Salle::$nomTable." WHERE id=?");
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
		
		public static function existe_nom_type_salle($nom) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Type_Salle::$nomTable." WHERE nom=?");
				$req->execute(
					Array($nom)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne['nb'] >= 1;
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function formulaireAjoutTypeSalle($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			
			if (isset($_GET['modifier_type_salle'])) { 
				$titre = "Modifier un type de salle";
				$Type_Salle = new Type_Salle($_GET['modifier_type_salle']);
				$nomModif = "value=\"{$Type_Salle->getNom()}\"";
				$valueSubmit = "Modifier le type de salle"; 
				$nameSubmit = "validerModificationTypeSalle";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_type_salle']}\" />";
				$lienAnnulation = "index.php?page=ajoutTypeSalle";
				if (isset($_GET['idPromotion'])) {
					$lienAnnulation .= "&amp;idPromotion=".$_GET['idPromotion'];
				}
			}
			else {
				$titre = "Ajouter un type de salle";
				$nomModif = (isset($_POST['nom'])) ? "value=\"{$_POST['nom']}\"" : "";
				$valueSubmit = "Ajouter un type de salle"; 
				$nameSubmit = "validerAjoutTypeSalle";
				$hidden = "";
			}
			
			echo $tab."<h2>".$titre."</h2>\n";
			echo $tab."<form method=\"post\">\n";
			echo $tab."\t<table>\n";
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td>Nom</td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"nom\" type=\"text\" required $nomModif/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td></td>\n";
			echo $tab."\t\t\t<td>".$hidden."<input type=\"submit\" name=\"".$nameSubmit."\" value=\"$valueSubmit\" /></td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t</table>\n";
			echo $tab."</form>\n";	
			
			if (isset($lienAnnulation)) {echo $tab."<p><a href=\"".$lienAnnulation."\">Annuler modification</a></p>";}		
		}	
		
		public static function prise_en_compte_formulaire() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_POST['validerAjoutTypeSalle'])) {
				$nom = htmlentities($_POST['nom'],ENT_QUOTES,'UTF-8');
				$nomCorrect = !Type_Salle::existe_nom_type_salle($nom);
				if ($nomCorrect) { // Test de saisie	
					Type_Salle::ajouter_type_salle($nom);
					array_push($messagesNotifications, "Le type de salle a bien été ajouté");
				}
				else {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
					if (!$nomCorrect) { array_push($messagesErreurs, "Le nom de type de cours existe déjà"); }
				}
			}
			else if (isset($_POST['validerModificationTypeSalle'])) {
				$id = htmlentities($_POST['id']);
				$nom = htmlentities($_POST['nom'],ENT_QUOTES,'UTF-8');
				$nomCorrect = true;
				if ($nomCorrect) { // Test de saisie	
					Type_Salle::modifierTypeSalle($id, $nom);
					array_push($messagesNotifications, "Le type de salle a bien été modifié");
				}
				else {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
				}
			}
		}
		
		public static function prise_en_compte_suppression() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_GET['supprimer_type_salle'])) {		
				if (Type_Salle::existeTypeSalle($_GET['supprimer_type_salle'])) {
					Type_Salle::supprimerTypeSalle($_GET['supprimer_type_salle']);
					array_push($messagesNotifications, "Le type de salle a bien été supprimé");
				}
			}
		}
		
		public static function pageAdministration($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			Type_Salle::formulaireAjoutTypeSalle($nombreTabulations + 1);
			echo $tab."<h2>Liste des types de salles</h2>\n";
			Type_Salle::liste_type_salle_to_table($nombreTabulations + 1, true);
		}
		
		public function appartient_salle_typeSalle($idSalle, $idType_Salle) {
			$appartient = 0;
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM ".Appartient_Salle_TypeSalle::$nomTable." WHERE idSalle = ? AND idTypeSalle = ?");
				$req->execute(
					array (
						$idSalle,
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
