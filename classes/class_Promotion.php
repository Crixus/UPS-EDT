<?php
	class Promotion{
		
		public static $nomTable = "Promotion";
		
		public static $attributs = Array(
			"nom",
			"annee",
			"tsDebut",
			"tsFin"
		);
		
		public function getNom() { return $this->nom; }
		public function getAnnee() { return $this->annee; }
		public function getTsDebut() { return $this->tsDebut; }
		public function getTsFin() { return $this->tsFin; }
		
		public function Promotion($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Promotion::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Promotion::$attributs as $att) {
					$this->$att = $ligne["$att"];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function ajouter_promotion($nom, $annee, $tsDebut, $tsFin) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Promotion::$nomTable." VALUES(?, ?, ?, ?, ?)");
				
				$req->execute(
					Array(
						"",
						$nom, 
						$annee, 
						$tsDebut, 
						$tsFin
					)
				);			
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_promotion($idPromotion, $nom, $annee, $tsDebut, $tsFin) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Promotion::$nomTable." SET nom=?, annee=?, tsDebut=?, tsFin=? WHERE id=?;");
				$req->execute(
					Array(
						$nom, 
						$annee, 
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
		
		public static function existe_promotion($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Promotion::$nomTable." WHERE id=?");
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
		
		public static function liste_promotion() {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Promotion::$nomTable." WHERE id!=0 ORDER BY nom");
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
		
		public function liste_promotion_for_select($idPromotion = null, $nombreTabulations = 0) {
			$liste_promotion = Promotion::liste_promotion();
			$tab = ""; while ($nombreTabulations > 0) { $tab .= "\t"; $nombreTabulations--; }
			echo "$tab<select onChange='selection_promotion(this)'>\n";
			
			echo "$tab\t<option value=0>--</option>\n";
			
			foreach ($liste_promotion as $idPromotionListe) {
				$Promotion = new Promotion($idPromotionListe);
				$nom = $Promotion->getNom();
				
				if ($idPromotionListe == $idPromotion)
					echo "$tab\t<option value={$idPromotionListe} selected>{$nom}</option>\n";
				else
					echo "$tab\t<option value={$idPromotionListe}>{$nom}</option>\n";
			}
			
			echo "$tab</select>\n";
		}
		
		public static function liste_promotion_to_table($administration, $nombreTabulations = 0) {
			$liste_promotion = Promotion::liste_promotion();
			$tab = ""; for ($i = 0; $i < $nombreTabulations ; $i++) { $tab .= "\t"; }
			
			echo "$tab<table class=\"table_liste_administration\">\n";
			
			echo "$tab\t<tr class=\"fondGrisFonce\">\n";
			
			echo "$tab\t\t<th>Nom</th>\n";
			echo "$tab\t\t<th>Année</th>\n";
			echo "$tab\t\t<th>Date</th>\n";
			
			if ($administration) {
				echo "$tab\t\t<th>Actions</th>\n";
			}
			echo "$tab\t</tr>\n";
			
			$cpt = 0;
			foreach ($liste_promotion as $idPromo) {
				$Promotion = new Promotion($idPromo);
				
				$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
				
				echo "$tab\t<tr class=\"$couleurFond\">\n";
				$cptBoucle=0;
				$valTemp="";
				$valTemp2="";
				foreach (Promotion::$attributs as $att) {
					if ($cptBoucle == 2)
						$valTemp = $Promotion->$att;
					else if ($cptBoucle == 3) {
						$valTemp2 = $Promotion->$att;
						echo "$tab\t\t<td>";
						Promotion::dateCours($valTemp, $valTemp2);
						echo "</td>\n";
					}
					else {
						echo "$tab\t\t<td>".$Promotion->$att."</td>\n";
					}
					$cptBoucle++;
				}
				if ($administration) {
					$pageModification = "./index.php?page=ajoutPromotion&amp;modifier_promotion=$idPromo";
					if (isset($_GET['idPromotion'])) {
						$pageModification .= "&amp;idPromotion={$_GET['idPromotion']}";
					}
					echo "$tab\t\t<td>";
					echo "<a href=\"$pageModification\"><img src=\"../images/modify.png\" alt=\"icone de modification\" /></a>";
					echo "</td>\n";
				}
				echo "$tab\t</tr>\n";
			}
			
			echo "$tab</table>\n";
		}
		
		public function dateCours($dateDebut, $dateFin) {
			$chaineDateDebut = explode(' ',$dateDebut);
			$chaineJMADebut = explode('-',$chaineDateDebut[0]);

			$chaineDateFin = explode(' ',$dateFin);
			$chaineJMAFin = explode('-',$chaineDateFin[0]);
			
			if ($chaineJMADebut[2] == $chaineJMAFin[2]) {
				echo Promotion::getDate($chaineJMADebut[2],$chaineJMADebut[1],$chaineJMADebut[0]);
			}
			else {
				echo "Du ";
				echo Promotion::getDate($chaineJMADebut[2],$chaineJMADebut[1],$chaineJMADebut[0]);
				echo " au ";
				echo Promotion::getDate($chaineJMAFin[2],$chaineJMAFin[1],$chaineJMAFin[0]);
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
		public function formulaireAjoutPromotion($idPromotion, $nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations ; $i++) { $tab .= "\t"; }
			if (isset($_GET['modifier_promotion'])) { 
				$titre = "Modifier une promotion";
				$Promotion = new Promotion($_GET['modifier_promotion']);
				$nomModif = "value=\"{$Promotion->getNom()}\"";
				$anneeModif = "value=\"{$Promotion->getAnnee()}\"";
				$tsDebutModif = $Promotion->getTsDebut();
				$tsFinModif = $Promotion->getTsFin();
				$valueSubmit = "Modifier la promotion"; 
				$nameSubmit = "validerModificationPromotion";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_promotion']}\" />";
				$lienAnnulation = "index.php?page=ajoutPromotion";
				if (isset($_GET['idPromotion'])) {
					$lienAnnulation .= "&amp;idPromotion={$_GET['idPromotion']}";
				}
			}
			else {
				$titre = "Ajouter une promotion";
				$nomModif = "";
				$anneeModif = "";
				$valueSubmit = "Ajouter la promotion"; 
				$nameSubmit = "validerAjoutPromotion";
				$hidden = "";
			}
			
			echo "$tab<h2>$titre</h2>\n";
			echo "$tab<form method=\"post\">\n";
			echo "$tab\t<table>\n";
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label>Nom</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<input name=\"nom\" type=\"text\" required {$nomModif}/>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label>Annéee</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<input name=\"annee\" type=\"number\" min=\"2010\" max=\"9999\" required {$anneeModif}/>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
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
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td>Date Debut</td>\n";
			echo "$tab\t\t\t<td><input name=\"tsDebut\" type=\"date\" required $valueDateDebut/> aaaa-mm-jj</td>\n";
			echo "$tab\t\t</tr>\n";
			
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
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td>Date Fin</td>\n";
			echo "$tab\t\t\t<td><input name=\"tsFin\" type=\"date\" required $valueDateFin/> aaaa-mm-jj</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td></td>\n";
			echo "$tab\t\t\t<td>$hidden<input type=\"submit\" name=\"$nameSubmit\" value=\"{$valueSubmit}\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";	

			if (isset($lienAnnulation)) {echo "$tab<p><a href=\"$lienAnnulation\">Annuler modification</a></p>";}	
		}
		
		public static function prise_en_compte_formulaire() {
			global $messages_notifications, $messages_erreurs;
			if (isset($_POST['validerAjoutPromotion'])) {
				$nom = $_POST['nom'];
				$nom_correct = true;
				$annee = $_POST['annee'];
				$annee_correct = true;
				$tsDebut = $_POST['tsDebut'];
				$tsDebut_correct = true;
				$tsFin = $_POST['tsFin'];
				$tsFin_correct = true;
				if ($nom_correct && $annee_correct && $tsDebut_correct && $tsFin_correct) {
					Promotion::ajouter_promotion($nom, $annee, $tsDebut, $tsFin);
					array_push($messages_notifications, "La promotion a bien été ajouté");
				}
				else {
					array_push($messages_erreurs, "La saisie n'est pas correcte");
				}
			}
			else if (isset($_POST['validerModificationPromotion'])) {
				$id = $_POST['id']; 
				$id_correct = Promotion::existe_promotion($id);
				$nom = $_POST['nom'];
				$nom_correct = true;
				$annee = $_POST['annee'];
				$annee_correct = true;
				$tsDebut = $_POST['tsDebut'];
				$tsDebut_correct = true;
				$tsFin = $_POST['tsFin'];
				$tsFin_correct = true;
				if ($id_correct && $nom_correct && $annee_correct && $tsDebut_correct && $tsFin_correct) {
					Promotion::modifier_promotion($_GET['modifier_promotion'], $nom, $annee, $tsDebut, $tsFin);
					array_push($messages_notifications, "La promotion a bien été modifié");
				}
				else {
					array_push($messages_erreurs, "La saisie n'est pas correcte");
				}
			}
		}
		
		public static function page_administration($nombreTabulations = 0) {
			$tab = ""; for ($i = 0 ; $i < $nombreTabulations ; $i++) { $tab .= "\t"; }
			Promotion::formulaireAjoutPromotion($nombreTabulations + 1);
			echo "$tab<h2>Liste des promotions</h2>\n";
			Promotion::liste_promotion_to_table(true, $nombreTabulations + 1);
		}
		
		public function toString() {
			$string = "";
			foreach (Promotion::$attributs as $att) {
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
		
		public static function creer_table() {
			return Utils_SQL::sql_from_file("./sql/".Promotion::$nomTable.".sql");
		}
		
		public static function supprimer_table() {
			return Utils_SQL::sql_supprimer_table(Promotion::$nomTable);
		}
	}
