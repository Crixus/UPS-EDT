<?php
	/** 
	 * Classe JourNonOuvrable - Permet de gerer les jours non ouvrables (jours feriés ou jours en période de vacance scolaire)
	 */ 
	class JourNonOuvrable{
		
		public static $nomTable = "JourNonOuvrable";
		
		public static $attributs = Array(
			"id",
			"type",
			"tsDebut",
			"tsFin",
			"idPromotion"
		);
		
		/**
		 * Getter de l'id du jour non ouvrable
		 * @return int : id du jour non ouvrable
		 */
		public function getId() { return $this->id; }
		
		/**
		 * Getter du type du jour non ouvrable
		 * @return string : type du jour non ouvrable
		 */
		public function getType() { return $this->type; }
		
		/**
		 * Getter de TsDebut du jour non ouvrable
		 * @return timestamp : TsDebut
		 */
		public function getTsDebut() { return $this->tsDebut; }
		
		/**
		 * Getter de tsFin du jour non ouvrable
		 * @return timestamp : tsFin
		 */
		public function getTsFin() { return $this->tsFin; }
		
		/**
		 * Getter de l'idPromotion du jour non ouvrable
		 * @return int : idPromotion du jour non ouvrable
		 */
		public function getIdPromotion() { return $this->idPromotion; }
		
		/**
		 * Constructeur de la classe JourNonOuvrable
		 * Récupère les informations de JourNonOuvrable dans la base de données depuis l'id
		 * @param $id : int id du JourNonOuvrable
		 */
		public function JourNonOuvrable($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".JourNonOuvrable::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (JourNonOuvrable::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Fonction testant l'existence d'un jour non ouvrable
		 * @param id : int id du jour non ouvrable
		 */
		public static function existe_jourNonOuvrable($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".JourNonOuvrable::$nomTable." WHERE id=?");
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
		 * Renvoi la liste des jours non ouvrables
		 * @return List<JourNonOuvrable> liste des jours non ouvrables
		 */
		public static function liste_jourNonOuvrable($idPromotion) {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".JourNonOuvrable::$nomTable." WHERE idPromotion=? ORDER BY tsDebut, tsFin, type");
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
		 * Ajouter un jour non ouvrable dans la base de données
		 * @param $type : string type du jour non ouvrable
		 * @param $tsDebut : timestamp tsDebut du jour non ouvrable correspondant à la date de début du jour non ouvrable
		 * @param $tsFin : timestamp tsFin du jour non ouvrable correspondant à la date de fin du jour non ouvrable
		 * @param $idPromotion : int idPromotion du jour non ouvrable
		 */
		public static function ajouter_jourNonOuvrable($type, $tsDebut, $tsFin, $idPromotion) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".JourNonOuvrable::$nomTable." VALUES(?, ?, ?, ?, ?)");
				$req->execute(
					Array(
						"",
						$type,
						$tsDebut,
						$tsFin,
						$idPromotion
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}		
		}
		
		/**
		 * Modifier un jour non ouvrable dans la base de données
		 * @param $idJourNonOuvrable : int id du jour non ouvrable a modifié
		 * @param $type : string type du jour non ouvrable
		 * @param $tsDebut : timestamp tsDebut du jour non ouvrable correspondant à la date de début du jour non ouvrable
		 * @param $tsFin : timestamp tsFin du jour non ouvrable correspondant à la date de fin du jour non ouvrable
		 * @param $idPromotion : int idPromotion du jour non ouvrable
		 */
		public static function modifier_jourNonOuvrable($idJourNonOuvrable, $type, $tsDebut, $tsFin, $idPromotion) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".JourNonOuvrable::$nomTable." SET type=?, tsDebut=?, tsFin=?, idPromotion=? WHERE id=?;");
				$req->execute(
					Array(
						$type,
						$tsDebut,
						$tsFin,
						$idPromotion,
						$idJourNonOuvrable
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Supprime un jour non ouvrable dans la base de données
		 * @param $idCours int : id du jour non ouvrable a supprimé
		 */
		public static function supprimer_jourNonOuvrable($idJourNonOuvrable) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".JourNonOuvrable::$nomTable." WHERE id=?;");
				$req->execute(
					Array(
						$idJourNonOuvrable
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Fonction utilisée pour l'affichage de la liste des jour non ouvrable créé 
		 * @param $idPromotion int : id de la promotion sélectionnée
		 * @param $administration boolean : possibilité de modification et suppression si egal à 1
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public static function liste_jourNonOuvrable_to_table($idPromotion, $administration, $nombreTabulations = 0) {
			// Liste des jour non ouvrable de la promotion enregistrée dans la base de donnée
			$liste_jourNonOuvrable = JourNonOuvrable::liste_jourNonOuvrable($idPromotion);
			$nbJourNonOuvrable = sizeof($liste_jourNonOuvrable);
			
			$tab = ""; while ($nombreTabulations > 0) { $tab .= "\t"; $nombreTabulations--; }
			
			if ($nbJourNonOuvrable == 0) {
				echo $tab."<b>Aucun jours non ouvrables n'est enregistré pour cette promotion</b>\n";
			}
			else {
			
				echo $tab."<table class=\"table_liste_administration\">\n";
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				
				echo $tab."\t\t<th>Type</th>\n";
				echo $tab."\t\t<th>Date</th>\n";
				
				if ($administration) {
					echo $tab."\t\t<th>Actions</th>\n";
				}
				echo $tab."\t</tr>\n";
				
				$cpt = 0;
				foreach ($liste_jourNonOuvrable as $idJourNonOuvrable) {
					$JourNonOuvrable = new JourNonOuvrable($idJourNonOuvrable);
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					echo $tab."\t<tr class=\"".$couleurFond."\">\n";
					$cptBoucle=0;
					$valTemp="";
					$valTemp2="";
					
					// Gestion de l'affichage des informations du jour non ouvrable
					foreach (JourNonOuvrable::$attributs as $att) {
						if ($cptBoucle == 1) 
							echo $tab."\t\t<td>".$JourNonOuvrable->$att."</td>\n";	
							
						else if ($cptBoucle == 2) {
							$valTemp = $JourNonOuvrable->$att;
						}
						else if ($cptBoucle == 3) {
							$valTemp2 = $JourNonOuvrable->$att;
							echo $tab."\t\t<td>";
							Cours::dateCours($valTemp, $valTemp2);
							echo "</td>\n";
							$valTemp="";
							$valTemp2="";
						}
												
						$cptBoucle++;
					}
					
					// Création des liens pour la modification et la suppression des jour non ouvrable et gestion de l'URL 
					if ($administration) {
						$pageModification = "./index.php?page=ajoutJourNonOuvrable&modifier_jourNonOuvrable=$idJourNonOuvrable";
						$pageSuppression = "./index.php?page=ajoutJourNonOuvrable&supprimer_jourNonOuvrable=$idJourNonOuvrable";
						if (isset($_GET['idPromotion'])) {
							$pageModification .= "&amp;idPromotion=".$_GET['idPromotion'];
							$pageSuppression .= "&amp;idPromotion=".$_GET['idPromotion'];
						}
						
						echo $tab."\t\t<td>";
						echo "<a href=\"".$pageModification."\"><img src=\"../images/modify.png\" alt=\"icone de modification\" /></a>";
						echo "<a href=\"".$pageSuppression."\" onclick=\"return confirm('Supprimer le jour non ouvrable ?')\"><img src=\"../images/delete.png\" alt=\"icone de suppression\" /></a>";
						echo "</td>\n";
					}
					echo $tab."\t</tr>\n";
				}
				
				echo $tab."</table>\n";
			}
		}
		
		
		/**
		 * Fonction utilisée pour l'affichage du formulaire utilisé pour l'ajout d'un jour non ouvrable
		 * @param $idPromotion int : id de la promotion sélectionnée
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public function formulaireAjoutJourNonOuvrable($idPromotion, $nombresTabulations = 0) {
			$tab = ""; while ($nombresTabulation = 0) { $tab .= "\t"; $nombresTabulations--; }
			
			// Gestion du formulaire suivant si on ajoute ou on modifie un jour non ouvrable
			if (isset($_GET['modifier_jourNonOuvrable'])) { 
				$titre = "Modifier un jour non ouvrable";
				$JourNonOuvrable = new JourNonOuvrable($_GET['modifier_jourNonOuvrable']);
				$typeModif = "value=\"{$JourNonOuvrable->getType()}\"";
				$tsDebutModif = $JourNonOuvrable->getTsDebut();
				$tsFinModif = $JourNonOuvrable->getTsFin();
				$valueSubmit = "Modifier le jour non ouvrable"; 
				$nameSubmit = "validerModificationJourNonOuvrable";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_jourNonOuvrable']}\" />";
				$lienAnnulation = "index.php?page=ajoutJourNonOuvrable";
				if (isset($_GET['idPromotion'])) {
					$lienAnnulation .= "&amp;idPromotion=".$_GET['idPromotion'];
				}
			}
			else {
				$titre = "Ajouter un jour non ouvrable";
				$typeModif = (isset($_POST['type'])) ? "value=\"{$_POST['type']}\"" : "value=\"\"";
				$valueSubmit = "Ajouter le jour non ouvrable"; 
				$nameSubmit = "validerAjoutJourNonOuvrable";
				$hidden = "";
			}
			
			echo $tab."<h2>".$titre."</h2>\n";
			echo $tab."<form method=\"post\">\n";
			echo $tab."\t<table>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Type</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"type\" type=\"text\" required {$typeModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			if (isset($tsDebutModif)) {
				$explode = explode(" ", $tsDebutModif);
				$valueDateDebut = "value=\"{$explode[0]}\" ";
				$explodeHeure = explode(":", $explode[1]);
				$valueHeureDebut = $explodeHeure[0];
				$valueMinuteDebut = $explodeHeure[1];
			}
			else {
				$valueDateDebut = "";
				$valueHeureDebut = "";
				$valueMinuteDebut = "";
			}
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td>Date Debut</td>\n";
			echo $tab."\t\t\t<td><input onchange=\"changeDateDebut(this.value)\" name=\"dateDebut\" type=\"date\" required $valueDateDebut/> aaaa-mm-jj</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td>Heure Debut</td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<select name=\"heureDebut\" onchange=\"changeHeureDebut(this.value)\">\n";			
			for ($cpt=0;$cpt<=23;$cpt++) {
				if ($cpt == $valueHeureDebut)
					$selected = " selected";
				else if (($cpt == 7) && ($valueHeureDebut == ""))
					$selected = " selected";
				else
					$selected = "";
					
				if ($cpt < 10)
					echo $tab."\t\t\t\t\t<option value=\"0{$cpt}\" {$selected}>0{$cpt}</option>\n";
				else
					echo $tab."\t\t\t\t\t<option value=\"{$cpt}\" {$selected}>{$cpt}</option>\n";				
			}
			echo $tab."\t\t\t\t\t</select>\n";
			echo $tab."\t\t\t\t<select name=\"minuteDebut\" onchange=\"changeMinuteDebut(this.value)\">\n";		
			$tab_minute = array(0,15,30,45);
			$first=false;
			for ($cpt=0;$cpt<4;$cpt++) {
				if ($tab_minute[$cpt] == $valueMinuteDebut) {
					$selected = " selected";
				}
				else if (($cpt == 3) && ($valueMinuteDebut == ""))
					$selected = " selected";
				else
					$selected = "";
					
				if ($cpt == 0)
					echo $tab."\t\t\t\t\t<option value=\"0{$tab_minute[$cpt]}\" {$selected}>0{$tab_minute[$cpt]}</option>\n";
				else
					echo $tab."\t\t\t\t\t<option value=\"{$tab_minute[$cpt]}\" {$selected}>{$tab_minute[$cpt]}</option>\n";						
			}
			echo $tab."\t\t\t\t\t</select>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			if (isset($tsFinModif)) {
				$explode = explode(" ", $tsFinModif);
				$valueDateFin = "value=\"{$explode[0]}\" ";
				$explodeHeure = explode(":", $explode[1]);
				$valueHeureFin = $explodeHeure[0];
				$valueMinuteFin = $explodeHeure[1];
			}
			else {
				$valueDateFin = "";
				$valueHeureFin = "";
				$valueMinuteFin = "";
			}
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td>Date Fin</td>\n";
			echo $tab."\t\t\t<td><input id=\"dateFin\" name=\"dateFin\" type=\"date\" required $valueDateFin/> aaaa-mm-jj</td>\n";
			echo $tab."\t\t</tr>\n";
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td>Heure Fin</td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<select name=\"heureFin\">\n";			
			for ($cpt=0;$cpt<=23;$cpt++) {
				if ($cpt == $valueHeureFin)
					$selected = " selected";
				else if (($cpt == 9) && ($valueHeureFin == ""))
					$selected = " selected";
				else
					$selected = "";
					
				if ($cpt < 10)
					echo $tab."\t\t\t\t\t<option value=\"0{$cpt}\" {$selected}>0{$cpt}</option>\n";
				else
					echo $tab."\t\t\t\t\t<option value=\"{$cpt}\" {$selected}>{$cpt}</option>\n";				
			}
			echo $tab."\t\t\t\t\t</select>\n";
			echo $tab."\t\t\t\t<select name=\"minuteFin\">\n";		
			$tab_minute = array(0,15,30,45);
			$first=false;
			for ($cpt=0;$cpt<4;$cpt++) {
				if ($tab_minute[$cpt] == $valueMinuteFin) {
					$selected = " selected";
				}
				else if (($cpt == 3) && ($valueMinuteFin == ""))
					$selected = " selected";
				else
					$selected = "";
					
				if ($cpt == 0)
					echo $tab."\t\t\t\t\t<option value=\"0{$tab_minute[$cpt]}\" {$selected}>0{$tab_minute[$cpt]}</option>\n";
				else
					echo $tab."\t\t\t\t\t<option value=\"{$tab_minute[$cpt]}\" {$selected}>{$tab_minute[$cpt]}</option>\n";						
			}
			echo $tab."\t\t\t\t\t</select>\n";
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
			if (isset($_POST['validerAjoutJourNonOuvrable']) || isset($_POST['validerModificationJourNonOuvrable'])) {
				// Vérification des champs				
				$type = htmlentities($_POST['type'], ENT_QUOTES, 'UTF-8');
				$type_correct = PregMatch::est_nom($type);
				$dateDebut = $_POST['dateDebut'];
				$dateDebutCorrect = true;
				$heureDebut = $_POST['heureDebut'];
				$heureDebutCorrect = true;
				$minuteDebut = $_POST['minuteDebut'];
				$minuteDebutCorrect = true;
				$dateFin = $_POST['dateFin'];
				$dateFinCorrect = true;
				$heureFin = $_POST['heureFin'];
				$heureFin_correct = true;
				$minuteFin = $_POST['minuteFin'];
				$minuteFin_correct = true;
				
				$validationAjout = false;
				if (isset($_POST['validerAjoutJourNonOuvrable'])) {
					// Ajout d'un nouveau jour non ouvrable
					if ($type_correct && $dateDebutCorrect && $heureDebutCorrect && $minuteDebutCorrect && $dateFinCorrect && $heureFin_correct && $minuteFin_correct) {
						JourNonOuvrable::ajouter_jourNonOuvrable($type, "$dateDebut $heureDebut:$minuteDebut:00", "$dateFin $heureFin:$minuteFin:00", $_GET['idPromotion']);
						array_push($messagesNotifications, "Le jour non ouvrable a bien été ajouté");
						$validationAjout = true;
					}
				}
				else {
					// Modification d'un nouveau jour non ouvrable
					$id = htmlentities($_POST['id']); 
					$idCorrect = JourNonOuvrable::existe_jourNonOuvrable($id);
					if ($idCorrect && $type_correct && $dateDebutCorrect && $heureDebutCorrect && $minuteDebutCorrect && $dateFinCorrect && $heureFin_correct && $minuteFin_correct) {
						JourNonOuvrable::modifier_jourNonOuvrable($_GET['modifier_jourNonOuvrable'], $type, "$dateDebut $heureDebut:$minuteDebut:00", "$dateFin $heureFin:$minuteFin:00", $_GET['idPromotion']);
						array_push($messagesNotifications, "Le jour non ouvrable a bien été modifié");
						$validationAjout = true;
					}				
				}
				
				// Traitement des erreurs
				if (!$validationAjout) {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
					if (isset($idCorrect) && !$idCorrect) {
						array_push($messagesErreurs, "L'id du jour non ouvrable n'est pas correct, contacter un administrateur");
					}
					if (!$type_correct) {
						array_push($messagesErreurs, "Le type n'est pas correct");
					}
				}
			}
		}
		
		/**
		 * Fonction permettant de prendre en compte la validation d'une demande de suppression d'un jour non ouvrable, on test s'il est bien enregistré dans la base de donnée
		 */
		public static function priseEnCompteSuppression() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_GET['supprimer_jourNonOuvrable'])) {	
				if (JourNonOuvrable::existe_jourNonOuvrable($_GET['supprimer_jourNonOuvrable'])) {
					// Le jour non ouvrable existe
					JourNonOuvrable::supprimer_jourNonOuvrable($_GET['supprimer_jourNonOuvrable']);
					array_push($messagesNotifications, "Le jour non ouvrable à bien été supprimée");
				}
				else {
					// Le jour non ouvrable n'existe pas
					array_push($messagesErreurs, "Le jour non ouvrable n'existe pas");
				}
			}
		}
		
		/**
		* Fonction principale permettant l'affichage du formulaire d'ajout ou de modification d'un jour non ouvrable ainsi que l'affichage des jours non ouvrables de la promotion enregistrée dans la base de données
		*/
		public static function pageAdministration($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			JourNonOuvrable::formulaireAjoutJourNonOuvrable($_GET['idPromotion'], $nombreTabulations + 1);
			echo $tab."<h2>Liste des jours non ouvrables enregistrée</h2>\n";
			JourNonOuvrable::liste_jourNonOuvrable_to_table($_GET['idPromotion'], true, $nombreTabulations + 1);
		}
	}
	
	
