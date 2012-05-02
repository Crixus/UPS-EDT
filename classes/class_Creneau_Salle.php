<?php
	/** 
	 * Classe Creneau_Salle - Permet de gerer les disponibilités des salles en enregistrant leur creneau de disponibilité
	 */ 
	class Creneau_Salle{
		
		public static $nomTable = "Creneau_Salle";
		
		public static $attributs = Array(
			"id",
			"tsDebut",
			"tsFin",
			"idSalle"
		);
		
		/**
		 * Getter de l'id du créneau salle
		 * @return int : id du créneau salle
		 */
		public function getId() { return $this->id; }
		
		/**
		 * Getter de l'idSalle du créneau salle
		 * @return int : idSalle du créneau salle
		 */
		public function getIdSalle() { return $this->idSalle; }
		
		/**
		 * Getter de TsDebut du créneau salle
		 * @return timestamp : TsDebut
		 */
		public function getTsDebut() { return $this->tsDebut; }
		
		/**
		 * Getter de tsFin du créneau salle
		 * @return timestamp : tsFin
		 */
		public function getTsFin() { return $this->tsFin; }
		
		/**
		 * Constructeur de la classe Creneau_Salle
		 * Récupère les informations de Creneau_Salle dans la base de données depuis l'id
		 * @param $id : int id du créneau salle
		 */
		public function Creneau_Salle($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Creneau_Salle::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Creneau_Salle::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Fonction testant l'existence d'un créneau salle
		 * @param $id : int id du créneau salle
		 */
		public static function existe_creneauSalle($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Creneau_Salle::$nomTable." WHERE id=?");
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
		 * Ajouter un creneau salle dans la base de données
		 * @param $idSalle : int id de la salle
		 * @param $tsDebut : timestamp tsDebut du creneau salle correspondant à la date de début du creneau salle
		 * @param $tsFin : timestamp tsFin du creneau salle correspondant à la date de fin du creneau salle
		 * @param $recursivite : int correspondant au nombre de fois que le creneau se créé récursivement la semaine suivante celle de la semaine courante (ex: recursivite=2 signifie que le creneau créé va être également créer avec les mêmes informations pour les 2 semaines qui suivra)
		 */
		public static function ajouter_creneauSalle($idSalle, $tsDebut, $tsFin, $recursivite) {
			
			/**
			* Boucle de création récursive des creneau salle
			*/
			for ($i=0; $i<=$recursivite; $i++) {
				try {
					$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("INSERT INTO ".Creneau_Salle::$nomTable." VALUES(?, ?, ?, ?)");
					$req->execute(
						Array(
							"",
							$tsDebut,
							$tsFin,
							$idSalle
						)
					);
				}
				catch (Exception $e) {
					echo "Erreur : ".$e->getMessage()."<br />";
				}
				
				/**
				* Modification de $tsDebut et $tsFin pour passer à la semaine suivante pour la prochaine itération
				*/
				$tsDebut = Cours::datePlusUneSemaine($tsDebut);
				$tsFin = Cours::datePlusUneSemaine($tsFin);
			}			
		}
		
		/**
		 * Modification du creneau salle dans la base de données
		 * @param $idCreneauIntervenant : int id du creneau salle a modifié
		 * @param $idSalle : int id de la salle
		 * @param $tsDebut : timestamp tsDebut du creneau salle correspondant à la date de début du creneau salle
		 * @param $tsFin : timestamp tsFin du creneau salle correspondant à la date de fin du creneau salle
		 */
		public static function modifier_creneauSalle($idCreneauSalle, $idSalle, $tsDebut, $tsFin) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Creneau_Salle::$nomTable." SET idSalle=?, tsDebut=?, tsFin=? WHERE id=?;");
				$req->execute(
					Array(
						$idSalle,
						$tsDebut,
						$tsFin,
						$idCreneauSalle
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Supprime un creneau salle dans la base de données
		 * @param $idCreneauSalle int : id du creneau salle a supprimé
		 */
		public static function supprimer_creneauSalle($idCreneauSalle) {
			//Suppression du creneau pour l'Salle
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Creneau_Salle::$nomTable." WHERE id=?;");
				$req->execute(
					Array(
						$idCreneauSalle
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Renvoi la liste des creneau salle
		 * @return List<Creneau_Salle> liste des creneau salle
		 */
		public static function liste_creneauSalle() {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".Creneau_Salle::$nomTable." ORDER BY tsDebut");
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
		
		/**
		 * Fonction utilisée pour l'affichage de la liste des creneau salle
		 * @param $administration boolean : possibilité de modification et suppression si egal à 1
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public static function liste_creneauSalle_to_table($administration, $nombreTabulations = 0) {
			//Liste des creneau salle enregistrés
			$liste_creneauSalle = Creneau_Salle::liste_creneauSalle();
			$nbCreneauSalle = sizeof($liste_creneauSalle);
			$tab = ""; while ($nombreTabulations > 0) { $tab .= "\t"; $nombreTabulations--; }
			
			if ($nbCreneauSalle == 0) {
				echo $tab."<b>Aucunes disponibilités n'a été enregistrés</b>\n";
			}
			else {
			
				echo $tab."<table class=\"table_liste_administration\">\n";
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				
				echo $tab."\t\t<th>Salle</th>\n";
				echo $tab."\t\t<th>Date</th>\n";
				
				if ($administration) {
					echo $tab."\t\t<th>Actions</th>\n";
				}
				echo $tab."\t</tr>\n";
				
				$cpt = 0;
				foreach ($liste_creneauSalle as $idCreneauSalle) {
					$Creneau_Salle = new Creneau_Salle($idCreneauSalle);
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					echo $tab."\t<tr class=\"".$couleurFond."\">\n";
					$cptBoucle=0;
					$valTemp="";
					$valTemp2="";
					
					// Gestion de l'affichage des informations du creneau
					foreach (Creneau_Salle::$attributs as $att) {
						if ($cptBoucle == 1)
							$valTemp = $Creneau_Salle->$att;
						else if ($cptBoucle == 2) {
							$valTemp2 = $Creneau_Salle->$att;
							$val = "De ".$valTemp." à ".$valTemp2;
							echo $tab."\t\t<td>";
							Creneau_Salle::dateCreneauSalle($valTemp, $valTemp2);
							echo "</td>\n";
						}
						else if ($cptBoucle == 3) {
							$idSalle = $Creneau_Salle->$att;
							if ($idSalle == 0)
								echo $tab."\t\t<td></td>\n";
							else {
								$_salle = new V_Liste_Salles($idSalle);
								$nomBatiment = $_salle->getNomBatiment();
								$nomSalle = $_salle->getNomSalle();
								echo $tab."\t\t<td>".$nomBatiment." ".$nomSalle."</td>\n";
							}
						}
						$cptBoucle++;
					}
					
					// Création des liens pour la modification et la suppression des creneau et gestion de l'URL 
					if ($administration) {
						$pageModification = "./index.php?page=ajoutCreneauSalle&modifier_creneauSalle=$idCreneauSalle";
						$pageSuppression = "./index.php?page=ajoutCreneauSalle&supprimer_creneauSalle=$idCreneauSalle";
						if (isset($_GET['idPromotion'])) {
							$pageModification .= "&amp;idPromotion=".$_GET['idPromotion'];
							$pageSuppression .= "&amp;idPromotion=".$_GET['idPromotion'];
						}
						
						echo $tab."\t\t<td>";
						echo "<a href=\"".$pageModification."\"><img src=\"../images/modify.png\" alt=\"icone de modification\" /></a>";
						echo "<a href=\"".$pageSuppression."\" onclick=\"return confirm('Supprimer le creneau Salle ?')\"><img src=\"../images/delete.png\" alt=\"icone de suppression\" /></a>";
						echo "</td>\n";
					}
					echo $tab."\t</tr>\n";
				}
				
				echo $tab."</table>\n";
			}
		}
		
		/**
		 * Fonction utilisée pour l'affichage de la date d'un creneau 
		 * @param $dateDebut timestamp : correspond à la date de début du creneau
		 * @param $dateFin timestamp : correspond à la date de fin du creneau
		 */
		public function dateCreneauSalle($dateDebut, $dateFin) {
			$chaineDateDebut = explode(' ', $dateDebut);
			$chaineJMADebut = explode('-', $chaineDateDebut[0]);
			$chaineHMSDebut = explode(':', $chaineDateDebut[1]);

			$chaineDateFin = explode(' ', $dateFin);
			$chaineJMAFin = explode('-', $chaineDateFin[0]);
			$chaineHMSFin = explode(':', $chaineDateFin[1]);
			
			if ($chaineJMADebut[2] == $chaineJMAFin[2]) {
				echo "Le ";
				echo Creneau_Salle::getDate($chaineJMADebut[2], $chaineJMADebut[1], $chaineJMADebut[0]);
				echo " de {$chaineHMSDebut[0]}h{$chaineHMSDebut[1]}";
				echo " à {$chaineHMSFin[0]}h{$chaineHMSFin[1]}";
			}
			else {
				echo "Du ";
				echo Creneau_Salle::getDate($chaineJMADebut[2], $chaineJMADebut[1], $chaineJMADebut[0]);
				echo " {$chaineHMSDebut[0]}h{$chaineHMSDebut[1]} au ";
				echo Creneau_Salle::getDate($chaineJMAFin[2], $chaineJMAFin[1], $chaineJMAFin[0]);
				echo " {$chaineHMSFin[0]}h{$chaineHMSFin[1]}";
			}
		}
		
		/**
		 * Fonction utilisée par la fonction dateCreneauIntervenant pour l'affichage de la date d'un creneau 
		 * @param $jour timestamp : int correspondant au nombre de jours d'une date
		 * @param $mois timestamp : int correspondant au nombre de mois d'une date
		 * @param $annee timestamp : int correspondant au nombre d'années d'une date
		 */
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
		
		
		/**
		 * Fonction utilisée pour l'affichage du formulaire utilisé pour l'ajout d'un creneau salle
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public function formulaireAjoutCreneauSalle($nombresTabulations = 0) {
			$tab = ""; while ($nombresTabulation = 0) { $tab .= "\t"; $nombresTabulations--; }
			
			// Liste des salles enregistrée dans la base de donnée
			$liste_Salle = V_Liste_Salles::liste_salles();
			
			// Gestion du formulaire suivant si on ajoute ou on modifie un creneau salle
			if (isset($_GET['modifier_creneauSalle'])) { 
				$titre = "Modifier un creneau de disponibilité pour une salle";
				$Creneau_Salle = new Creneau_Salle($_GET['modifier_creneauSalle']);
				$idSalleModif = $Creneau_Salle->getIdSalle();
				$tsDebutModif = $Creneau_Salle->getTsDebut();
				$tsFinModif = $Creneau_Salle->getTsFin();
				$valueSubmit = "Modifier le creneau Salle"; 
				$nameSubmit = "validerModificationCreneauSalle";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_creneauSalle']}\" />";
				$lienAnnulation = "index.php?page=ajoutCreneauSalle";
				if (isset($_GET['idPromotion'])) {
					$lienAnnulation .= "&amp;idPromotion=".$_GET['idPromotion'];
				}
			}
			else {
				$titre = "Ajouter un creneau de disponibilité pour une salle";
				$idTypeCoursModif = 1;
				$idSalleModif = 0;
				$valueSubmit = "Ajouter le creneau Salle"; 
				$nameSubmit = "validerAjoutCreneauSalle";
				$hidden = "";
			}
			
			echo $tab."<h2>".$titre."</h2>\n";
			echo $tab."<form method=\"post\">\n";
			echo $tab."\t<table>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label for=\"Salle\">Salle</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<select name=\"Salle\" id=\"Salle\">\n";
			
			if (isset($idSalleModif) && ($idSalleModif == 0)) { $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo $tab."\t\t\t\t\t<option value=\"0\" $selected>----- Inconnu -----</option>\n";
			foreach ($liste_Salle as $idSalle) {
				if ($idSalle != 0) {
					$_salle = new V_Liste_Salles($idSalle);
					$nomBatiment = $_salle->getNomBatiment();
					$nomSalle = $_salle->getNomSalle();
					if (isset($idSalleModif) && ($idSalleModif == $idSalle)) { $selected = "selected=\"selected\" "; } else { $selected = ""; }
					echo $tab."\t\t\t\t\t<option value=\"$idSalle\" $selected>$nomBatiment $nomSalle</option>\n";
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
			
			
			if (! isset($_GET['modifier_creneauSalle'])) { 
				echo $tab."\t\t<tr>\n";
				echo $tab."\t\t\t<td><label for=\"recursivite\">Récursivité</label></td>\n";
				echo $tab."\t\t\t<td>\n";
				echo $tab."\t\t\t\t<select name=\"recursivite\" id=\"recursivite\">\n";
				
				echo $tab."\t\t\t\t\t<option value=\"0\" $selected>----- Aucune -----</option>\n";
				for ($i=1; $i<=52; $i++) {
					echo $tab."\t\t\t\t\t<option value=\"".$i."\">$i</option>\n";					
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
			
			// Lien permettant de terminer la modification d'un creneau et de revenir au formulaire pour l'ajout d'un nouveau creneau
			if (isset($lienAnnulation)) {echo $tab."<p><a href=\"".$lienAnnulation."\">Annuler modification</a></p>";}	
		}
		
		/**
		 * Fonction permettant de prendre en compte les informations validées dans le formulaire pour la MAJ de la base de données
		 */
		public static function prise_en_compte_formulaire() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_POST['validerAjoutCreneauSalle']) || isset($_POST['validerModificationCreneauSalle'])) {
				// Vérification des champs
				$idSalle = $_POST['Salle'];
				$idSalle_correct = true;
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
				if (isset($_POST['validerAjoutCreneauSalle'])) {
					// Ajout d'un nouveau creneau Salle
					$recursivite = $_POST['recursivite'];
					$recursivite_correct = true;
					if ($idSalle_correct && $dateDebutCorrect && $heureDebutCorrect && $minuteDebutCorrect && $dateFinCorrect && $heureFin_correct && $minuteFin_correct && $recursivite_correct) {	
						Creneau_Salle::ajouter_creneauSalle($idSalle, "$dateDebut $heureDebut:$minuteDebut:00", "$dateFin $heureFin:$minuteFin:00", $recursivite);
						array_push($messagesNotifications, "Le creneau Salle a bien été ajouté");
						$validation_ajout = true;
					}
				}
				else  {
					// Modification d'un creneau Salle
					$id = htmlentities($_POST['id']); 
					$idCorrect = Creneau_Salle::existe_creneauSalle($id);
					if ($idSalle_correct && $dateDebutCorrect && $heureDebutCorrect && $minuteDebutCorrect && $dateFinCorrect && $heureFin_correct && $minuteFin_correct) {	
						Creneau_Salle::modifier_creneauSalle($_GET['modifier_creneauSalle'], $idSalle, "$dateDebut $heureDebut:$minuteDebut:00", "$dateFin $heureFin:$minuteFin:00");
						array_push($messagesNotifications, "Le creneau Salle a bien été modifié");
						$validation_ajout = true;
					}
				}
				
				// Traitement des erreurs
				if (!$validation_ajout) {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
					if (isset($idCorrect) && !$idCorrect) {
						array_push($messagesErreurs, "L'id du creneau Salle n'est pas correct, contacter un administrateur");
					}
					if (!$idSalle_correct) {
						array_push($messagesErreurs, "L'Salle n'est pas correcte");
					}
				}
			}
		}
		
		/**
		 * Fonction permettant de prendre en compte la validation d'une demande de suppression d'un creneau salle, on test s'il est bien enregistré dans la base de donnée
		 */
		public static function prise_en_compte_suppression() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_GET['supprimer_creneauSalle'])) {	
				if (Creneau_Salle::existe_creneauSalle($_GET['supprimer_creneauSalle'])) {
					Creneau_Salle::supprimer_creneauSalle($_GET['supprimer_creneauSalle']);
					array_push($messagesNotifications, "Le creneau Salle à bien été supprimé");
				}
				else {
					array_push($messagesErreurs, "Le creneau Salle n'existe pas");
				}
			}
		}
		
		/**
		* Fonction principale permettant l'affichage du formulaire d'ajout ou de modification d'un creneau salle ainsi que l'affichage des creneau salle enregistrée dans la base de données
		*/
		public static function pageAdministration($nombreTabulations = 0) {			
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			Creneau_Salle::formulaireAjoutCreneauSalle($nombreTabulations + 1);
			echo $tab."<h2>Liste des creneaux de disponibilités des salles</h2>\n";
			Creneau_Salle::liste_creneauSalle_to_table($nombreTabulations + 1);
		}
	}
	
