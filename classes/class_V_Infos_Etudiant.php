<?php
	/** 
	 * Classe V_Infos_Etudiant - Permet de gerer la vue V_Infos_Etudiant
	 */
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
		
		/**
		 * Getter de l'id de la vue V_Infos_Etudiant
		 * @return int : id de V_Infos_Etudiant
		 */
		public function getId() {return $this->id;}

		/**
		 * Constructeur de la classe V_Infos_Etudiant
		 * Récupère les informations de V_Infos_Etudiant dans la base de données depuis l'id
		 * @param $id : int id du V_Infos_Etudiant
		 */
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
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Renvoi le nombre d'étudiants de la promotion
		 * @return id : int nombre d'étudiants de la promotion
		 */
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
		
		/**
		 * Fonction testant l'existence d'étudiants dans la vue V_Infos_Cours
		 * @param id : int id de l'étudiant
		 */
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
		
		/**
		 * Renvoi la liste des étudiants de la promotion
		 * @param $idPromotion : int id de la promotion
		 * @return List<V_Infos_Etudiant> liste des étudiants de la promotion
		 */
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
		
		/**
		 * Fonction utilisée pour l'affichage de la liste des étudiants créé 
		 * @param $idPromotion int : id de la promotion sélectionnée
		 * @param $administration boolean : possibilité de modification et suppression si egal à 1
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public static function liste_etudiant_to_table($idPromotion, $administration, $nombreTabulations = 0) {
			// Liste des étudiants de la promotion enregistrée dans la base de donnée
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
				// Gestion de l'affichage des informations des étudiants
				foreach ($liste_etudiant as $idEtudiant) {
					$_etudiant = new V_Infos_Etudiant($idEtudiant);
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					echo $tab."\t<tr class=\"".$couleurFond."\">\n";
					foreach (V_Infos_Etudiant::$attributs as $att) {
						if ($att == "notificationsActives") { 
							$checked = ($_etudiant->$att) ? "checked = \"checked\"" : "";
							$nomCheckbox = "{$idEtudiant}_notifications";
							echo $tab."\t\t<td><input type=\"checkbox\" name= \"{$idEtudiant}_notifications\" value=\"{$idEtudiant}\" onclick=\"etudiant_notificationsActives({$idEtudiant},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
						}
						else
							echo $tab."\t\t<td>".$_etudiant->$att."</td>\n";
					}
					
					// Création des liens pour la modification et la suppression des étudiants et gestion de l'URL 
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
	}
?>
