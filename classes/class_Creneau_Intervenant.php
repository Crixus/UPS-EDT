<?php
	class Creneau_Intervenant{
		
		public static $nomTable = "Creneau_Intervenant";
		
		public static $attributs = Array(
			"id",
			"tsDebut",
			"tsFin",
			"idIntervenant"
		);
		
		
		public function getId() { 
			return $this->id; 
		}
		
		public function getIdIntervenant() {
			return $this->idIntervenant;
		}
		
		public function getTsDebut() {
			return $this->tsDebut;
		}
		
		public function getTsFin() {
			return $this->tsFin;
		}
		
		
		public function Creneau_Intervenant($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Creneau_Intervenant::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Creneau_Intervenant::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function existe_creneauIntervenant($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Creneau_Intervenant::$nomTable." WHERE id=?");
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
		
		public static function ajouter_creneauIntervenant($idIntervenant, $tsDebut, $tsFin, $recursivite) {
		
			for ($i=0; $i<=$recursivite; $i++) {
				try {
					$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("INSERT INTO ".Creneau_Intervenant::$nomTable." VALUES(?, ?, ?, ?)");
					$req->execute(
						Array(
							"",
							$tsDebut,
							$tsFin,
							$idIntervenant
						)
					);
				}
				catch (Exception $e) {
					echo "Erreur : ".$e->getMessage()."<br />";
				}
				
				$tsDebut = Cours::datePlusUneSemaine($tsDebut);
				$tsFin = Cours::datePlusUneSemaine($tsFin);
			}			
		}
		
		public static function modifier_creneauIntervenant($idCreneauIntervenant, $idIntervenant, $tsDebut, $tsFin) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Creneau_Intervenant::$nomTable." SET idIntervenant=?, tsDebut=?, tsFin=? WHERE id=?;");
				$req->execute(
					Array(
						$idIntervenant,
						$tsDebut,
						$tsFin,
						$idCreneauIntervenant
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function supprimer_creneauIntervenant($idCreneauIntervenant) {
			//Suppression du creneau pour l'intervenant
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Creneau_Intervenant::$nomTable." WHERE id=?;");
				$req->execute(
					Array(
						$idCreneauIntervenant
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		
		public static function liste_creneauIntervenant() {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".Creneau_Intervenant::$nomTable." ORDER BY tsDebut");
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
		
		
		public static function liste_creneauIntervenant_to_table($administration, $nombreTabulations = 0) {
			$liste_creneauIntervenant = Creneau_Intervenant::liste_creneauIntervenant();
			$nbCreneauIntervenant = sizeof($liste_creneauIntervenant);
			$tab = ""; while ($nombreTabulations > 0) { $tab .= "\t"; $nombreTabulations--; }
			
			if ($nbCreneauIntervenant == 0) {
				echo $tab."<b>Aucunes disponibilités n'a été enregistrés</b>\n";
			}
			else {
			
				echo $tab."<table class=\"table_liste_administration\">\n";
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				
				echo $tab."\t\t<th>Intervenant</th>\n";
				echo $tab."\t\t<th>Date</th>\n";
				
				if ($administration) {
					echo $tab."\t\t<th>Actions</th>\n";
				}
				echo $tab."\t</tr>\n";
				
				$cpt = 0;
				foreach ($liste_creneauIntervenant as $idCreneauIntervenant) {
					$Creneau_Intervenant = new Creneau_Intervenant($idCreneauIntervenant);
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					echo $tab."\t<tr class=\"".$couleurFond."\">\n";
					$cptBoucle=0;
					$valTemp="";
					$valTemp2="";
					foreach (Creneau_Intervenant::$attributs as $att) {
						if ($cptBoucle == 1)
							$valTemp = $Creneau_Intervenant->$att;
						else if ($cptBoucle == 2) {
							$valTemp2 = $Creneau_Intervenant->$att;
							$val = "De ".$valTemp." à ".$valTemp2;
							echo $tab."\t\t<td>";
							Creneau_Intervenant::dateCreneauIntervenant($valTemp, $valTemp2);
							echo "</td>\n";
						}
						else if ($cptBoucle == 3) {
							$idIntervenant = $Creneau_Intervenant->$att;
							if ($idIntervenant == 0)
								echo $tab."\t\t<td></td>\n";
							else {
								$Intervenant = new Intervenant($idIntervenant);
								$nomIntervenant = $Intervenant->getNom(); $prenomIntervenant = $Intervenant->getPrenom();
								echo $tab."\t\t<td>".$nomIntervenant." ".$prenomIntervenant."</td>\n";
							}
						}
						$cptBoucle++;
					}
					if ($administration) {
						$pageModification = "./index.php?page=ajoutCreneauIntervenant&modifier_creneauIntervenant=$idCreneauIntervenant";
						$pageSuppression = "./index.php?page=ajoutCreneauIntervenant&supprimer_creneauIntervenant=$idCreneauIntervenant";
						if (isset($_GET['idPromotion'])) {
							$pageModification .= "&amp;idPromotion=".$_GET['idPromotion'];
							$pageSuppression .= "&amp;idPromotion=".$_GET['idPromotion'];
						}
						
						echo $tab."\t\t<td>";
						echo "<a href=\"".$pageModification."\"><img src=\"../images/modify.png\" alt=\"icone de modification\" /></a>";
						echo "<a href=\"".$pageSuppression."\" onclick=\"return confirm('Supprimer le creneau intervenant ?')\"><img src=\"../images/delete.png\" alt=\"icone de suppression\" /></a>";
						echo "</td>\n";
					}
					echo $tab."\t</tr>\n";
				}
				
				echo $tab."</table>\n";
			}
		}
		
		public function dateCreneauIntervenant($dateDebut, $dateFin) {
			$chaineDateDebut = explode(' ', $dateDebut);
			$chaineJMADebut = explode('-', $chaineDateDebut[0]);
			$chaineHMSDebut = explode(':', $chaineDateDebut[1]);

			$chaineDateFin = explode(' ', $dateFin);
			$chaineJMAFin = explode('-', $chaineDateFin[0]);
			$chaineHMSFin = explode(':', $chaineDateFin[1]);
			
			if ($chaineJMADebut[2] == $chaineJMAFin[2]) {
				echo "Le ";
				echo Creneau_Intervenant::getDate($chaineJMADebut[2], $chaineJMADebut[1], $chaineJMADebut[0]);
				echo " de {$chaineHMSDebut[0]}h{$chaineHMSDebut[1]}";
				echo " à {$chaineHMSFin[0]}h{$chaineHMSFin[1]}";
			}
			else {
				echo "Du ";
				echo Creneau_Intervenant::getDate($chaineJMADebut[2], $chaineJMADebut[1], $chaineJMADebut[0]);
				echo " {$chaineHMSDebut[0]}h{$chaineHMSDebut[1]} au ";
				echo Creneau_Intervenant::getDate($chaineJMAFin[2], $chaineJMAFin[1], $chaineJMAFin[0]);
				echo " {$chaineHMSFin[0]}h{$chaineHMSFin[1]}";
			}
		}
		
		public function getDate($jour, $mois, $annee) {
			if ($jour == 1)  
				$numero_jour = '1er';
			else if ($jour < 10)
				$numero_jour = $jour[1];
			else 
				$numero_jour = $jour;
				
			$nom_mois = "";
			switch ($mois) {
				case 1 : 
					$nom_mois = 'Janvier';
					break;
				case 2 : 
					$nom_mois = 'Fevrier';
					break;
				case 3 : 
					$nom_mois = 'Mars';
					break;
				case 4 : 
					$nom_mois = 'Avril';
					break;
				case 5 : 
					$nom_mois = 'Mai';
					break;
				case 6 : 
					$nom_mois = 'Juin';
					break;
				case 7 : 
					$nom_mois = 'Juillet';
					break;
				case 8 : 
					$nom_mois = 'Août';
					break;
				case 9 : 
					$nom_mois = 'Septembre';
					break;
				case 10 : 
					$nom_mois = 'Octobre';
					break;
				case 11 : 
					$nom_mois = 'Novembre';
					break;
				case 12 : 
					$nom_mois = 'Décembre';
					break;
			}
			
			echo "{$numero_jour} {$nom_mois} {$annee}";
		}
		
		
		// Formulaire
		public function formulaireAjoutCreneauIntervenant($nombresTabulations = 0) {
			$tab = ""; while ($nombresTabulation = 0) { $tab .= "\t"; $nombresTabulations--; }
			$liste_intervenant = Intervenant::listeIdIntervenants();
			
			if (isset($_GET['modifier_creneauIntervenant'])) { 
				$titre = "Modifier un creneau de disponibilité pour un intervenant";
				$Creneau_Intervenant = new Creneau_Intervenant($_GET['modifier_creneauIntervenant']);
				$idIntervenantModif = $Creneau_Intervenant->getIdIntervenant();
				$tsDebutModif = $Creneau_Intervenant->getTsDebut();
				$tsFinModif = $Creneau_Intervenant->getTsFin();
				$valueSubmit = "Modifier le creneau intervenant"; 
				$nameSubmit = "validerModificationCreneauIntervenant";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_creneauIntervenant']}\" />";
				$lienAnnulation = "index.php?page=ajoutCreneauIntervenant";
				if (isset($_GET['idPromotion'])) {
					$lienAnnulation .= "&amp;idPromotion=".$_GET['idPromotion'];
				}
			}
			else {
				$titre = "Ajouter un creneau de disponibilité pour un intervenant";
				$idTypeCoursModif = 1;
				$idSalleModif = 0;
				$valueSubmit = "Ajouter le creneau intervenant"; 
				$nameSubmit = "validerAjoutCreneauIntervenant";
				$hidden = "";
			}
			
			echo $tab."<h2>".$titre."</h2>\n";
			echo $tab."<form method=\"post\">\n";
			echo $tab."\t<table>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label for=\"intervenant\">Intervenant</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<select name=\"intervenant\" id=\"intervenant\">\n";
			
			if (isset($idIntervenantModif) && ($idIntervenantModif == 0)) { $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo $tab."\t\t\t\t\t<option value=\"0\" $selected>----- Inconnu -----</option>\n";
			foreach ($liste_intervenant as $idIntervenant) {
				if ($idIntervenant != 0) {
					$Intervenant = new Intervenant($idIntervenant);
					$nomIntervenant = $Intervenant->getNom(); $prenomIntervenant = $Intervenant->getPrenom();
					if (isset($idIntervenantModif) && ($idIntervenantModif == $idIntervenant)) { $selected = "selected=\"selected\" "; } else { $selected = ""; }
					echo $tab."\t\t\t\t\t<option value=\"$idIntervenant\" $selected>$nomIntervenant $prenomIntervenant.</option>\n";
				}
			}
			echo $tab."\t\t\t\t</select>\n";
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
			
			
			if (! isset($_GET['modifier_creneauIntervenant'])) { 
				echo $tab."\t\t<tr>\n";
				echo $tab."\t\t\t<td><label for=\"recursivite\">Récursivité</label></td>\n";
				echo $tab."\t\t\t<td>\n";
				echo $tab."\t\t\t\t<select name=\"recursivite\" id=\"recursivite\">\n";
				
				echo $tab."\t\t\t\t\t<option value=\"0\" $selected>----- Aucune -----</option>\n";
				for ($i=1; $i<=52; $i++) {
					echo $tab."\t\t\t\t\t<option value=\"".$i."\">".$i."</option>\n";					
				}
				echo $tab."\t\t\t\t</select> (en semaines)\n";
				echo $tab."\t\t\t</td>\n";
				echo $tab."\t\t</tr>\n";
			}			
			
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
			if (isset($_POST['validerAjoutCreneauIntervenant']) || isset($_POST['validerModificationCreneauIntervenant'])) {
				// Vérification des champs
				$idIntervenant = $_POST['intervenant'];
				$idIntervenantCorrect = true;
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
				if (isset($_POST['validerAjoutCreneauIntervenant'])) {
					// Ajout d'un nouveau creneau intervenant
					$recursivite = $_POST['recursivite'];
					$recursivite_correct = true;
					if ($idIntervenantCorrect && $dateDebutCorrect && $heureDebutCorrect && $minuteDebutCorrect && $dateFinCorrect && $heureFin_correct && $minuteFin_correct && $recursivite_correct) {	
						Creneau_Intervenant::ajouter_creneauIntervenant($idIntervenant, "$dateDebut $heureDebut:$minuteDebut:00", "$dateFin $heureFin:$minuteFin:00", $recursivite);
						array_push($messagesNotifications, "Le creneau intervenant a bien été ajouté");
						$validation_ajout = true;
					}
				}
				else  {
					// Modification d'un creneau intervenant
					$id = htmlentities($_POST['id']); 
					$idCorrect = Creneau_Intervenant::existe_creneauIntervenant($id);
					if ($idIntervenantCorrect && $dateDebutCorrect && $heureDebutCorrect && $minuteDebutCorrect && $dateFinCorrect && $heureFin_correct && $minuteFin_correct) {	
						Creneau_Intervenant::modifier_creneauIntervenant($_GET['modifier_creneauIntervenant'], $idIntervenant, "$dateDebut $heureDebut:$minuteDebut:00", "$dateFin $heureFin:$minuteFin:00");
						array_push($messagesNotifications, "Le creneau intervenant a bien été modifié");
						$validation_ajout = true;
					}
				}
				
				// Traitement des erreurs
				if (!$validation_ajout) {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
					if (isset($idCorrect) && !$idCorrect) {
						array_push($messagesErreurs, "L'id du creneau intervenant n'est pas correct, contacter un administrateur");
					}
					if (!$idIntervenantCorrect) {
						array_push($messagesErreurs, "L'intervenant n'est pas correcte");
					}
				}
			}
		}
		
		
		public static function prise_en_compte_suppression() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_GET['supprimer_creneauIntervenant'])) {	
				if (Creneau_Intervenant::existe_creneauIntervenant($_GET['supprimer_creneauIntervenant'])) {
					Creneau_Intervenant::supprimer_creneauIntervenant($_GET['supprimer_creneauIntervenant']);
					array_push($messagesNotifications, "Le creneau intervenant à bien été supprimé");
				}
				else {
					array_push($messagesErreurs, "Le creneau intervenant n'existe pas");
				}
			}
		}
		
		
		public static function pageAdministration($nombreTabulations = 0) {			
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			Creneau_Intervenant::formulaireAjoutCreneauIntervenant($nombreTabulations + 1);
			echo $tab."<h2>Liste des creneaux de disponibilités des intervenants</h2>\n";
			Creneau_Intervenant::liste_creneauIntervenant_to_table($nombreTabulations + 1);
		}
	}
	
