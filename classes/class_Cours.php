<?php
	class Cours{
		
		public static $nomTable = "Cours";
		
		public static $attributs = Array(
			"id",
			"idUE",
			"idSalle",
			"idIntervenant",
			"idTypeCours",
			"tsDebut",
			"tsFin"
		);
		
		public function getId(){ return $this->id; }
		public function getIdUE(){ return $this->idUE; }
		public function getIdSalle(){ return $this->idSalle; }
		public function getIdIntervenant(){ return $this->idIntervenant; }
		public function getIdTypeCours(){ return $this->idTypeCours; }
		public function getTsDebut(){ return $this->tsDebut; }
		public function getTsFin(){ return $this->tsFin; }
		
		public function Cours($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Cours::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Cours::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function ajouter_cours($idUE, $idSalle, $idIntervenant, $type, $tsDebut, $tsFin){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Cours::$nomTable." VALUES(?, ?, ?, ?, ?, ?, ?)");
				$req->execute(
					Array(
						"",
						$idUE,
						$idSalle,
						$idIntervenant,
						$type,
						$tsDebut,
						$tsFin
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_cours($idCours, $idUE, $idSalle, $idIntervenant, $idTypeCours, $tsDebut, $tsFin){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Cours::$nomTable." SET idUE=?, idSalle=?, idIntervenant=?, idTypeCours=?, tsDebut=?, tsFin=? WHERE id=?;");
				$req->execute(
					Array(
						$idUE,
						$idSalle,
						$idIntervenant,
						$idTypeCours,
						$tsDebut,
						$tsFin,
						$idCours
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function supprimer_cours($idCours){
			//Suppression des apparitions du cours dans la table "Appartient_Cours_GroupeCours"
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Appartient_Cours_GroupeCours::$nomTable." WHERE idCours=?;");
				$req->execute(
					Array(
						$idCours
					)
				);			
			
				//Suppression du cours
				try{
					$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("DELETE FROM ".Cours::$nomTable." WHERE id=?;");
					$req->execute(
						Array(
							$idCours
						)
					);
				}
				catch(Exception $e){
					echo "Erreur : ".$e->getMessage()."<br />";
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function liste_cours_to_table($idPromotion, $administration, $nombreTabulations = 0){
			$liste_cours = V_Infos_Cours::liste_cours($idPromotion);
			$nbCours = sizeof($liste_cours);
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			
			if ($nbCours == 0) {
				echo "$tab<b>Aucun cours n'est enregistré pour cette promotion</b>\n";
			}
			else {
			
				echo "$tab<table class=\"listeCours\">\n";
				
				echo "$tab\t<tr class=\"fondGrisFonce\">\n";
				/*
				foreach(V_Infos_Cours::$attributs as $att){
					echo "$tab\t\t<th>$att</th>\n";
				}*/
					echo "$tab\t\t<th>UE</th>\n";
					echo "$tab\t\t<th>Intervenant</th>\n";
					echo "$tab\t\t<th>Type</th>\n";
					echo "$tab\t\t<th>Date</th>\n";
					echo "$tab\t\t<th>Salle</th>\n";
				
				if($administration){
					echo "$tab\t\t<th>Actions</th>\n";
				}
				echo "$tab\t</tr>\n";
				
				$cpt = 0;
				foreach($liste_cours as $idCours){
					$Cours = new V_Infos_Cours($idCours);
					
					if($cpt == 0){ $couleurFond="fondBlanc"; }
					else{ $couleurFond="fondGris"; }
					$cpt++; $cpt %= 2;
					
					echo "$tab\t<tr class=\"$couleurFond\">\n";
					$cptBoucle=0;
					$valTemp="";
					$valTemp2="";
					foreach(V_Infos_Cours::$attributs as $att){
						if ( ($cptBoucle == 1) || ($cptBoucle == 4) || ($cptBoucle == 6) )
							$valTemp = $Cours->$att;
						else if ( ($cptBoucle == 2) || ($cptBoucle == 7) ) {
							$val = $Cours->$att." ".$valTemp;
							$valTemp="";
							echo "$tab\t\t<td>".$val."</td>\n";
						}
						else if ($cptBoucle == 5) {
							$valTemp2 = $Cours->$att;
							$val = "De ".$valTemp." à ".$valTemp2;
							echo "$tab\t\t<td>";
							Cours::dateCours($valTemp, $valTemp2);
							echo "</td>\n";
						}
						else {
							echo "$tab\t\t<td>".$Cours->$att."</td>\n";
						}
						$cptBoucle++;
					}
					if($administration){
						$pageModification = "./index.php?idPromotion={$_GET['idPromotion']}&page=ajoutCours&modifier_cours=$idCours";
						$pageSuppression = "./index.php?idPromotion={$_GET['idPromotion']}&page=ajoutCours&supprimer_cours=$idCours";
						echo "$tab\t\t<td><img src=\"../images/modify.jpg\" width=\"20\" height=\"20\" style=\"cursor:pointer;\" onClick=\"location.href='{$pageModification}'\">  <img src=\"../images/delete.jpg\" width=\"20\" height=\"20\" style=\"cursor:pointer;\" OnClick=\"location.href=confirm('Voulez vous vraiment supprimer ce cours ?') ? '{$pageSuppression}' : ''\"/>\n";
					}
					echo "$tab\t</tr>\n";
				}
				
				echo "$tab</table>\n";
			}
		}
	
		public function dateCours($dateDebut, $dateFin) {
			$chaineDateDebut = explode(' ',$dateDebut);
			$chaineJMADebut = explode('-',$chaineDateDebut[0]);
			$chaineHMSDebut = explode(':',$chaineDateDebut[1]);

			$chaineDateFin = explode(' ',$dateFin);
			$chaineJMAFin = explode('-',$chaineDateFin[0]);
			$chaineHMSFin = explode(':',$chaineDateFin[1]);
			
			if ($chaineJMADebut[2] == $chaineJMAFin[2]) {
				echo "Le ";
				echo Cours::getDate($chaineJMADebut[2],$chaineJMADebut[1],$chaineJMADebut[0]);
				echo " de {$chaineHMSDebut[0]}h{$chaineHMSDebut[1]}";
				echo " à {$chaineHMSFin[0]}h{$chaineHMSFin[1]}";
			}
			else {
				echo "Du ";
				echo Cours::getDate($chaineJMADebut[2],$chaineJMADebut[1],$chaineJMADebut[0]);
				echo " {$chaineHMSDebut[0]}h{$chaineHMSDebut[1]} au ";
				echo Cours::getDate($chaineJMAFin[2],$chaineJMAFin[1],$chaineJMAFin[0]);
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
		public function formulaireAjoutCours($idPromotion, $nombresTabulations = 0){
			$tab = ""; while($nombresTabulation = 0){ $tab .= "\t"; $nombresTabulations--; }
			$liste_UE_promotion = UE::liste_UE_promotion($idPromotion);
			$liste_intervenant = Intervenant::liste_intervenant();
			$liste_type_cours = Type_Cours::liste_id_type_cours();
			
			if(isset($_GET['modifier_cours'])){ 
				$titre = "Modifier un cours";
				$Cours = new Cours($_GET['modifier_cours']);
				$idUEModif = $Cours->getIdUE();
				$idSalleModif = $Cours->getIdSalle();
				$idIntervenantModif = $Cours->getIdIntervenant();
				$idTypeCoursModif = $Cours->getIdTypeCours();
				$tsDebutModif = $Cours->getTsDebut();
				$tsFinModif = $Cours->getTsFin();
			}
			else{
				$titre = "Ajouter un cours";
				$idTypeCoursModif = 1;
				$idSalleModif = 0;
			}
			
			echo "$tab<h1>$titre</h1>\n";
			echo "$tab<form method=\"post\">\n";
			echo "$tab\t<table>\n";
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label for=\"UE\">UE</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<select name=\"UE\" id=\"UE\">\n";
			foreach($liste_UE_promotion as $idUE){
				$UE = new UE($idUE);
				$nomUE = $UE->getNom();
				if(isset($idUEModif) && ($idUEModif == $idUE)){ $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo "$tab\t\t\t\t\t<option value=\"$idUE\" $selected>$nomUE</option>\n";
			}
			echo "$tab\t\t\t\t</select>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label for=\"type\">Type</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<select name=\"typeCours\" id=\"typeCours\" onChange=\"update_select_typeSalle({$idSalleModif})\">\n";
			foreach($liste_type_cours as $idTypeCours){
				$Type_Cours = new Type_Cours($idTypeCours);
				$nomTypeCours = $Type_Cours->getNom();
				if($idTypeCoursModif == $idTypeCours){ $selected = "selected=\"selected\""; } else{ $selected = ""; }
				echo "$tab\t\t\t\t\t<option value=\"$idTypeCours\"$selected>$nomTypeCours</option>\n";
			}
			echo "$tab\t\t\t\t</select>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label for=\"intervenant\">Intervenant</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<select name=\"intervenant\" id=\"intervenant\">\n";
			
			if(isset($idIntervenantModif) && ($idIntervenantModif == 0)){ $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo "$tab\t\t\t\t\t<option value=\"0\" $selected>----- Inconnu -----</option>\n";
			foreach($liste_intervenant as $idIntervenant){
				$Intervenant = new Intervenant($idIntervenant);
				$nomIntervenant = $Intervenant->getNom(); $prenomIntervenant = $Intervenant->getPrenom();
				if(isset($idIntervenantModif) && ($idIntervenantModif == $idIntervenant)){ $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo "$tab\t\t\t\t\t<option value=\"$idIntervenant\" $selected>$nomIntervenant $prenomIntervenant.</option>\n";
			}
			echo "$tab\t\t\t\t</select>\n";
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
			echo "$tab\t\t\t<td><input onchange=\"changeDateDebut(this.value)\" name=\"dateDebut\" type=\"date\" required $valueDateDebut/> aaaa-mm-jj</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td>Heure Debut</td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<select name=\"heureDebut\" onchange=\"changeHeureDebut(this.value)\">\n";			
			for ($cpt=0;$cpt<=23;$cpt++) {
				if ($cpt == $valueHeureDebut)
					$selected = " selected";
				else if ( ($cpt == 7) && ($valueHeureDebut == "") )
					$selected = " selected";
				else
					$selected = "";
					
				if ($cpt < 10)
					echo "$tab\t\t\t\t\t<option value=\"0{$cpt}\" {$selected}>0{$cpt}</option>\n";
				else
					echo "$tab\t\t\t\t\t<option value=\"{$cpt}\" {$selected}>{$cpt}</option>\n";				
			}
			echo "$tab\t\t\t\t\t</select>\n";
			echo "$tab\t\t\t\t<select name=\"minuteDebut\" onchange=\"changeMinuteDebut(this.value)\">\n";		
			$tab_minute = array(0,15,30,45);
			$first=false;
			for ($cpt=0;$cpt<4;$cpt++) {
				if($tab_minute[$cpt] == $valueMinuteDebut) {
					$selected = " selected";
				}
				else if ( ($cpt == 3) && ($valueMinuteDebut == "") )
					$selected = " selected";
				else
					$selected = "";
					
				if ($cpt == 0)
					echo "$tab\t\t\t\t\t<option value=\"0{$tab_minute[$cpt]}\" {$selected}>0{$tab_minute[$cpt]}</option>\n";
				else
					echo "$tab\t\t\t\t\t<option value=\"{$tab_minute[$cpt]}\" {$selected}>{$tab_minute[$cpt]}</option>\n";						
			}
			echo "$tab\t\t\t\t\t</select>\n";
			echo "$tab\t\t\t</td>\n";
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
			echo "$tab\t\t\t<td><input id=\"dateFin\" name=\"dateFin\" type=\"date\" required $valueDateFin/> aaaa-mm-jj</td>\n";
			echo "$tab\t\t</tr>\n";
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td>Heure Fin</td>\n";
			//echo "$tab\t\t\t<td><input name=\"heureFin\" type=\"time\" required $valueHeureFin/> hh:mm</td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<select name=\"heureFin\">\n";			
			for ($cpt=0;$cpt<=23;$cpt++) {
				if ($cpt == $valueHeureFin)
					$selected = " selected";
				else if ( ($cpt == 9) && ($valueHeureFin == "") )
					$selected = " selected";
				else
					$selected = "";
					
				if ($cpt < 10)
					echo "$tab\t\t\t\t\t<option value=\"0{$cpt}\" {$selected}>0{$cpt}</option>\n";
				else
					echo "$tab\t\t\t\t\t<option value=\"{$cpt}\" {$selected}>{$cpt}</option>\n";				
			}
			echo "$tab\t\t\t\t\t</select>\n";
			echo "$tab\t\t\t\t<select name=\"minuteFin\">\n";		
			$tab_minute = array(0,15,30,45);
			$first=false;
			for ($cpt=0;$cpt<4;$cpt++) {
				if($tab_minute[$cpt] == $valueMinuteFin) {
					$selected = " selected";
				}
				else if ( ($cpt == 3) && ($valueMinuteFin == "") )
					$selected = " selected";
				else
					$selected = "";
					
				if ($cpt == 0)
					echo "$tab\t\t\t\t\t<option value=\"0{$tab_minute[$cpt]}\" {$selected}>0{$tab_minute[$cpt]}</option>\n";
				else
					echo "$tab\t\t\t\t\t<option value=\"{$tab_minute[$cpt]}\" {$selected}>{$tab_minute[$cpt]}</option>\n";						
			}
			echo "$tab\t\t\t\t\t</select>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label for=\"salle\">Salle</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<select name=\"salle\" id=\"salle\">\n";
			
			Cours::liste_salle_suivant_typeCours($idSalleModif, $idTypeCoursModif);
			
			echo "$tab\t\t\t\t</select>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td></td>\n";
			if(isset($_GET['modifier_cours'])){ $valueSubmit = "Modifier le cours"; }else{ $valueSubmit = "Ajouter le cours"; }
			echo "$tab\t\t\t<td><input type=\"submit\" name=\"validerAjoutCours\" value=\"$valueSubmit\" style=\"cursor:pointer\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";
		}
		
		public static function liste_salle_suivant_typeCours($idSalleModif, $idTypeCours) {
			$tab = "";
			$liste_salle = V_Liste_Salles::liste_salles_appartenant_typeCours($idTypeCours);
			
			if(isset($idSalleModif) && ($idSalleModif == 0)){ $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo "$tab\t\t\t\t\t<option value=\"0\" $selected>----- Inconnu -----</option>\n";
			foreach($liste_salle as $idSalle){
				$Salle = new V_Liste_Salles($idSalle);
				$nomBatiment = $Salle->getNomBatiment();
				$nomSalle = $Salle->getNomSalle();
				if(isset($idSalleModif) && ($idSalleModif == $idSalle)){ $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo "$tab\t\t\t\t\t<option value=\"$idSalle\" $selected>$nomBatiment $nomSalle</option>\n";
			}
		}
		
		public static function prise_en_compte_formulaire(){
			if(isset($_POST['validerAjoutCours'])){
				$idUE = $_POST['UE'];
				$idSalle = $_POST['salle'];
				$idIntervenant = $_POST['intervenant'];
				$typeCours = $_POST['typeCours'];
				$dateDebut = $_POST['dateDebut'];
				$heureDebut = $_POST['heureDebut'];
				$minuteDebut = $_POST['minuteDebut'];
				$dateFin = $_POST['dateFin'];
				$heureFin = $_POST['heureFin'];
				$minuteFin = $_POST['minuteFin'];
				if(true){ // Test de saisie
					$idPromotion = $_GET['idPromotion'];					
					if(isset($_GET['modifier_cours'])){
						Cours::modifier_cours($_GET['modifier_cours'], $idUE, $idSalle, $idIntervenant, $typeCours, "$dateDebut $heureDebut:$minuteDebut:00", "$dateFin $heureFin:$minuteFin:00");
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutCours&modification_cours=1";
					}
					else{
						// C'est un nouveau cours
						Cours::ajouter_cours($idUE, $idSalle, $idIntervenant, $typeCours, "$dateDebut $heureDebut:$minuteDebut:00", "$dateFin $heureFin:$minuteFin:00");
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutCours&ajout_cours=1";
					}
					header("Location: $pageDestination");
				}
			}
		}
		
		public static function prise_en_compte_suppression(){
			if(isset($_GET['supprimer_cours'])){	
				$idPromotion = $_GET['idPromotion'];	
				if(true){ // Test de saisie
					Cours::supprimer_cours($_GET['supprimer_cours']);
					$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutCours&supprimer_cours=1";	
				}
			}
		}
		
		public static function page_administration($nombreTabulations = 0){
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			if(isset($_GET['ajout_cours'])){
				echo "$tab<p class=\"notificationAdministration\">Le cours a bien été ajouté</p>";
			}
			if(isset($_GET['modification_cours'])){
				echo "$tab<p class=\"notificationAdministration\">Le cours a bien été modifié</p>";
			}
			Cours::formulaireAjoutCours($_GET['idPromotion'], $nombreTabulations + 1);
			echo "$tab<h1>Liste des cours</h1>\n";
			Cours::liste_cours_to_table($_GET['idPromotion'], true, $nombreTabulations + 1);
		}		
		
		public function toUl(){
			$string = "<ul>\n";
			foreach(Cours::$attributs as $att){
				$string .= "<li>$att : ".$this->$att."</li>\n";
			}
			return "$string</ul>\n";
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Cours::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Cours::$nomTable);
		}
	}
