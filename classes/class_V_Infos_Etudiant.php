<?php
	class V_Infos_Etudiant {
		
		public static $nomTable = "V_Infos_Etudiant";
		
		public static $attributs = Array(
			"nom",
			"prenom",
			"numeroEtudiant",
			"nomSpecialite",
			"email",
			"telephone",
			"notificationsActives"
		);
		
		public function getId() {return $this->id;}

		public function V_Infos_Etudiant($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".V_Infos_Etudiant::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
				);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (V_Infos_Etudiant::$attributs as $att) {
					$this->$att = $ligne["$att"];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function getNbreEtudiants($idPromotion) { 
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".V_Infos_Etudiant::$nomTable." WHERE idPromotion = ?");
				$req->execute(
					Array($idPromotion)
				);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne["nb"];
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function existe_etudiant($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".V_Infos_Etudiant::$nomTable." WHERE id=?");
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
		
		public static function liste_etudiant($idPromotion) {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".V_Infos_Etudiant::$nomTable." WHERE idPromotion = ? ORDER BY nom, prenom, numeroEtudiant");
				$req->execute(
					Array($idPromotion)
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
		
		public static function liste_etudiant_to_table($idPromotion, $administration, $nombreTabulations = 0) {
			$liste_etudiant = V_Infos_Etudiant::liste_etudiant($idPromotion);
			$nbEtudiants = sizeof($liste_etudiant);
			$tab = ""; while ($nombreTabulations > 0) { $tab .= "\t"; $nombreTabulations--; }
			
			if ($nbEtudiants == 0) {
				echo $tab."<b>Aucun étudiant n'est enregistré pour cette promotion</b>\n";
			}
			else {
			
				echo $tab."<table class=\"table_liste_administration\">\n";
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				echo $tab."\t\t<th>Nom</th>\n";
				echo $tab."\t\t<th>Prénom</th>\n";
				echo $tab."\t\t<th>Numéro de l'étudiant</th>\n";
				echo $tab."\t\t<th>Spécialité</th>\n";
				echo $tab."\t\t<th>Email</th>\n";
				echo $tab."\t\t<th>Téléphone</th>\n";
				echo $tab."\t\t<th>Notifications actives</th>\n";
				
				if ($administration) {
					echo $tab."\t\t<th>Actions</th>\n";
				}
				echo $tab."\t</tr>\n";
				
				$cpt = 0;
				foreach ($liste_etudiant as $idEtudiant) {
					$Etudiant = new V_Infos_Etudiant($idEtudiant);
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					echo $tab."\t<tr class=\"$couleurFond\">\n";
					foreach (V_Infos_Etudiant::$attributs as $att) {
						if ($att == "notificationsActives") { 
							$checked = ($Etudiant->$att) ? "checked = \"checked\"" : "";
							$nomCheckbox = "{$idEtudiant}_notifications";
							echo $tab."\t\t<td><input type=\"checkbox\" name= \"{$idEtudiant}_notifications\" value=\"{$idEtudiant}\" onclick=\"etudiant_notificationsActives({$idEtudiant},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
						}
						else
							echo $tab."\t\t<td>".$Etudiant->$att."</td>\n";
					}
					if ($administration) {
						$pageModification = "./index.php?page=ajoutEtudiant&amp;modifier_etudiant=$idEtudiant";
						$pageSuppression = "./index.php?page=ajoutEtudiant&amp;supprimer_etudiant=$idEtudiant";
						if (isset($_GET['idPromotion'])) {
							$pageModification .= "&amp;idPromotion=".$_GET['idPromotion'];
							$pageSuppression .= "&amp;idPromotion=".$_GET['idPromotion'];
						}
						echo $tab."\t\t<td>";
						echo "<a href=\"".$pageModification."\"><img src=\"../images/modify.png\" alt=\"icone de modification\" /></a>";
						echo "<a href=\"".$pageSuppression."\" onclick=\"return confirm('Supprimer l\'étudiant ?')\"><img src=\"../images/delete.png\" alt=\"icone de suppression\" /></a>";
						echo "</td>\n";
					}
					echo $tab."\t</tr>\n";
				}
				
				echo $tab."</table>\n";
			}
		}
		
		public function toString() {
			$string = "";
			foreach (V_Infos_Etudiant::$attributs as $att) {
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
	}
?>
