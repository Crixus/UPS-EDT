<?php
	class Promotion{
		
		public static $nomTable = "Promotion";
		
		public static $attributs = Array(
			"nom",
			"annee",
			"tsDebut",
			"tsFin"
		);
		
		public function getNom(){ return $this->nom; }
		public function getAnnee(){ return $this->annee; }
		public function getTsDebut(){ return $this->tsDebut; }
		public function getTsFin(){ return $this->tsFin; }
		
		public function Promotion($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Promotion::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Promotion::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function ajouter_promotion($nom, $annee, $tsDebut, $tsFin){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
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
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_promotion($idPromotion, $nom, $annee, $tsDebut, $tsFin){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
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
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function existe_promotion($id){
			try{
				$pdo_Options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_Options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Promotion::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne['nb'] == 1;
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function liste_promotion(){
			$listeId = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Promotion::$nomTable." ORDER BY nom");
				$req->execute();
				while($ligne = $req->fetch()){
					array_push($listeId, $ligne['id']);
				}
				$req->closeCursor();
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeId;
		}
		
		public function liste_promotion_for_select($promotion = 0) {
			$tab="";
			$liste_promotion = Promotion::liste_promotion();
			echo "$tab<select onChange='selection_promotion(this)'>\n";
			
			if ($promotion == 0)
				echo "$tab\t<option value=0 selected>--</option>\n";
			else
				echo "$tab\t<option value=0>--</option>\n";
			
			foreach ($liste_promotion as $idPromotion) {
				$Promotion = new Promotion($idPromotion);
				$nom = $Promotion->getNom();
				
				if ($promotion == $idPromotion)
					echo "$tab\t<option value={$idPromotion} selected>{$nom}</option>\n";
				else
					echo "$tab\t<option value={$idPromotion}>{$nom}</option>\n";
			}
			
			echo "$tab</select>\n";
		}
		
		public static function liste_promotion_to_table($idPromotion, $administration, $nombreTabulations = 0){
			$liste_promotion = Promotion::liste_promotion();
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			
			echo "$tab<table class=\"listeCours\">\n";
			
			echo "$tab\t<tr class=\"fondGrisFonce\">\n";
			
			echo "$tab\t\t<th>Nom</th>\n";
			echo "$tab\t\t<th>Année</th>\n";
			echo "$tab\t\t<th>Date</th>\n";
			
			if($administration){
				echo "$tab\t\t<th>Actions</th>\n";
			}
			echo "$tab\t</tr>\n";
			
			$cpt = 0;
			foreach($liste_promotion as $idPromo){
				$Promotion = new Promotion($idPromo);
				
				if($cpt == 0){ $couleurFond="fondBlanc"; }
				else{ $couleurFond="fondGris"; }
				$cpt++; $cpt %= 2;
				
				echo "$tab\t<tr class=\"$couleurFond\">\n";
				$cptBoucle=0;
				$valTemp="";
				$valTemp2="";
				foreach(Promotion::$attributs as $att) {
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
				if($administration){
					$pageModification = "./index.php?idPromotion={$_GET['idPromotion']}&amp;page=ajoutPromotion&amp;modifier_promotion=$idPromo";
					echo "$tab\t\t<td><img src=\"../images/modify.png\" style=\"cursor:pointer;\" onClick=\"location.href='{$pageModification}'\"></td>\n";
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
		public function formulaireAjoutPromotion($idPromotion, $nombresTabulations = 0){
			$tab = ""; while($nombresTabulation = 0){ $tab .= "\t"; $nombresTabulations--; }
			
			if(isset($_GET['modifier_promotion'])){ 
				$titre = "Modifier une promotion";
				$Promotion = new Promotion($_GET['modifier_promotion']);
				$nomModif = "value=\"{$Promotion->getNom()}\"";
				$anneeModif = "value=\"{$Promotion->getAnnee()}\"";
				$tsDebutModif = $Promotion->getTsDebut();
				$tsFinModif = $Promotion->getTsFin();
			}
			else{
				$titre = "Ajouter une promotion";
				$nomModif = "";
				$anneeModif = "";
			}
			
			echo "$tab<h1>$titre</h1>\n";
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
			
			if(isset($tsDebutModif)){
				$explode = explode(" ", $tsDebutModif);
				$valueDateDebut = "value=\"{$explode[0]}\" ";
				$explodeHeure = explode(":", $explode[1]);
				$valueHeureDebut = $explodeHeure[0];
				$valueMinuteDebut = $explodeHeure[1];
			}
			else{
				$valueDateDebut = "";
				$valueHeureDebut = "";
				$valueMinuteDebut = "";
			}
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td>Date Debut</td>\n";
			echo "$tab\t\t\t<td><input name=\"tsDebut\" type=\"date\" required $valueDateDebut/> aaaa-mm-jj</td>\n";
			echo "$tab\t\t</tr>\n";
			
			if(isset($tsFinModif)){
				$explode = explode(" ", $tsFinModif);
				$valueDateFin = "value=\"{$explode[0]}\" ";
				$explodeHeure = explode(":", $explode[1]);
				$valueHeureFin = $explodeHeure[0];
				$valueMinuteFin = $explodeHeure[1];
			}
			else{
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
			if(isset($_GET['modifier_promotion'])){ $valueSubmit = "Modifier la promotion"; }else{ $valueSubmit = "Ajouter la promotion"; }
			echo "$tab\t\t\t<td><input type=\"submit\" name=\"validerAjoutPromotion\" value=\"{$valueSubmit}\" style=\"cursor:pointer\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";
		}
		
		public static function prise_en_compte_formulaire(){
			if(isset($_POST['validerAjoutPromotion'])){
				$nom = $_POST['nom'];
				$annee = $_POST['annee'];
				$tsDebut = $_POST['tsDebut'];
				$tsFin = $_POST['tsFin'];
				if(true){ // Test de saisie
					$idPromotion = $_GET['idPromotion'];					
					if(isset($_GET['modifier_promotion'])){
						Promotion::modifier_promotion($_GET['modifier_promotion'], $nom, $annee, $tsDebut, $tsFin);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutPromotion&modification_promotion=1";
					}
					else{
						// C'est une nouvelle promotion
						Promotion::ajouter_promotion($nom, $annee, $tsDebut, $tsFin);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutPromotion&ajout_promotion=1";
					}
					header("Location: $pageDestination");
				}
			}
		}
		
		public static function page_administration($nombreTabulations = 0){
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			if(isset($_GET['ajout_promotion'])){
				echo "$tab<p class=\"notificationAdministration\">La promotion a bien été ajouté</p>";
			}
			if(isset($_GET['modification_promotion'])){
				echo "$tab<p class=\"notificationAdministration\">La promotion a bien été modifié</p>";
			}
			Promotion::formulaireAjoutPromotion($nombreTabulations + 1);
			echo "$tab<h1>Liste des promotions</h1>\n";
			Promotion::liste_promotion_to_table($_GET['idPromotion'], true, $nombreTabulations + 1);
		}
		
		public function toString(){
			$string = "";
			foreach(Promotion::$attributs as $att){
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Promotion::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Promotion::$nomTable);
		}
	}
