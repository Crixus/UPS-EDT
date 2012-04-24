<?php
	class JourNonOuvrable{
		
		public static $nomTable = "JourNonOuvrable";
		
		public static $attributs = Array(
			"id",
			"type",
			"tsDebut",
			"tsFin",
			"idPromotion"
		);
		
		public function getId() { return $this->id; }
		public function getType() { return $this->type; }
		public function getTsDebut() { return $this->tsDebut; }
		public function getTsFin() { return $this->tsFin; }
		public function getIdPromotion() { return $this->idPromotion; }
		
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
		
		
		public static function liste_jourNonOuvrable_to_table($idPromotion, $administration, $nombreTabulations = 0) {
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
		
		
		// Formulaire
		public function formulaireAjoutJourNonOuvrable($idPromotion, $nombresTabulations = 0) {
			$tab = ""; while ($nombresTabulation = 0) { $tab .= "\t"; $nombresTabulations--; }
			
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
			echo $tab."\t\t\t<td>".$hidden."<input type=\"submit\" name=\"".$nameSubmit."\" value=\"{$valueSubmit}\"></td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t</table>\n";
			echo $tab."</form>\n";
			
			if (isset($lienAnnulation)) {echo $tab."<p><a href=\"".$lienAnnulation."\">Annuler modification</a></p>";}	
		}
		
		
		public static function prise_en_compte_formulaire() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_POST['validerAjoutJourNonOuvrable']) || isset($_POST['validerModificationJourNonOuvrable'])) {
				// Vérification des champs				
				$type = htmlentities($_POST['type'],ENT_QUOTES,'UTF-8');
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
				
				$validation_ajout = false;
				if (isset($_POST['validerAjoutJourNonOuvrable'])) {
					// Ajout d'un nouveau jour non ouvrable
					if ($type_correct && $dateDebutCorrect && $heureDebutCorrect && $minuteDebutCorrect && $dateFinCorrect && $heureFin_correct && $minuteFin_correct) {
						JourNonOuvrable::ajouter_jourNonOuvrable($type, "$dateDebut $heureDebut:$minuteDebut:00", "$dateFin $heureFin:$minuteFin:00", $_GET['idPromotion']);
						array_push($messagesNotifications, "Le jour non ouvrable a bien été ajouté");
						$validation_ajout = true;
					}
				}
				else {
					// Modification d'un nouveau jour non ouvrable
					$id = htmlentities($_POST['id']); 
					$idCorrect = JourNonOuvrable::existe_jourNonOuvrable($id);
					if ($idCorrect && $type_correct && $dateDebutCorrect && $heureDebutCorrect && $minuteDebutCorrect && $dateFinCorrect && $heureFin_correct && $minuteFin_correct) {
						JourNonOuvrable::modifier_jourNonOuvrable($_GET['modifier_jourNonOuvrable'], $type, "$dateDebut $heureDebut:$minuteDebut:00", "$dateFin $heureFin:$minuteFin:00", $_GET['idPromotion']);
						array_push($messagesNotifications, "Le jour non ouvrable a bien été modifié");
						$validation_ajout = true;
					}				
				}
				
				// Traitement des erreurs
				if (!$validation_ajout) {
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
		
		public static function prise_en_compte_suppression() {
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
		
		public static function page_administration($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			JourNonOuvrable::formulaireAjoutJourNonOuvrable($_GET['idPromotion'], $nombreTabulations + 1);
			echo $tab."<h2>Liste des jours non ouvrables enregistrée</h2>\n";
			JourNonOuvrable::liste_jourNonOuvrable_to_table($_GET['idPromotion'], true, $nombreTabulations + 1);
		}
	}
	
	
