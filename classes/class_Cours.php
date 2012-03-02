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
		
		public function commence_a_heure($heure){
			$explode = explode(" ",$this->tsDebut);
			$heureDebut = $explode[1];
			return ($heure == $heureDebut);
		}
		
		public static function liste_cours_to_table($idPromotion, $administration, $nombreTabulations = 0){
			$liste_cours = V_Infos_Cours::liste_cours($idPromotion);
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			
			echo "$tab<table class=\"listeCours\">\n";
			
			echo "$tab\t<tr class=\"fondGrisFonce\">\n";
			foreach(V_Infos_Cours::$attributs as $att){
				echo "$tab\t\t<th>$att</th>\n";
			}
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
				foreach(V_Infos_Cours::$attributs as $att){
					echo "$tab\t\t<td>".$Cours->$att."</td>\n";
				}
				if($administration){
					$pageModification = "./index.php?idPromotion={$_GET['idPromotion']}&page=ajoutCours&modifier_cours=$idCours";
					echo "$tab\t\t<td><a href=\"$pageModification\">Modifier</a> / Supprimer</td>\n";
				}
				echo "$tab\t</tr>\n";
			}
			
			echo "$tab</table>\n";
		}
		
		// Formulaire
		
		public function formulaireAjoutCours($idPromotion, $nombresTabulations = 0){
			$tab = ""; while($nombresTabulation = 0){ $tab .= "\t"; $nombresTabulations--; }
			$liste_UE_promotion = UE::liste_UE_promotion($idPromotion);
			$liste_intervenant = Intervenant::liste_intervenant();
			$liste_salle = V_Liste_Salles::liste_salles();
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
			echo "$tab\t\t\t\t<select name=\"type\" id=\"type\">\n";
			foreach($liste_type_cours as $idTypeCours){
				$Type_Cours = new Type_Cours($idTypeCours);
				$nomTypeCours = $Type_Cours->getNom();
				if(!isset($idTypeCoursModif)){ if($nomTypeCours == "Cours"){ $selected = "selected=\"selected\""; } else{ $selected = ""; }}
				else{ if($idTypeCoursModif == $idTypeCours){ $selected = "selected=\"selected\""; } else{ $selected = ""; }}
				echo "$tab\t\t\t\t\t<option value=\"$idTypeCours\"$selected>$nomTypeCours</option>\n";
			}
			echo "$tab\t\t\t\t</select>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label for=\"intervenant\">Intervenant</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<select name=\"intervenant\" id=\"intervenant\">\n";
			foreach($liste_intervenant as $idIntervenant){
				$Intervenant = new Intervenant($idIntervenant);
				$idInt = $Intervenant->getId(); $nomIntervenant = $Intervenant->getNom(); $prenomIntervenant = $Intervenant->getPrenom();
				echo "$tab\t\t\t\t\t<option value=\"$idInt\">$nomIntervenant $prenomIntervenant</option>\n";
			}
			echo "$tab\t\t\t\t</select>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			if(isset($tsDebutModif)){
				$explode = explode(" ", $tsDebutModif);
				$valueDateDebut = "value=\"{$explode[0]}\" ";
				$valueHeureDebut = "value=\"{$explode[1]}\" ";
			}
			else{
				$valueDateDebut = "";
				$valueHeureDebut = "";
			}
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td>Date Debut</td>\n";
			echo "$tab\t\t\t<td><input onchange=\"changeDateDebut(this.value)\" name=\"dateDebut\" type=\"date\" required $valueDateDebut/> aaaa-mm-jj</td>\n";
			echo "$tab\t\t</tr>\n";
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td>Heure Debut</td>\n";
			echo "$tab\t\t\t<td><input onchange=\"changeHeureDebut(this.value)\" name=\"heureDebut\" type=\"time\" required $valueHeureDebut/> hh:mm</td>\n";
			echo "$tab\t\t</tr>\n";
			
			if(isset($tsFinModif)){
				$explode = explode(" ", $tsFinModif);
				$valueDateFin = "value=\"{$explode[0]}\" ";
				$valueHeureFin = "value=\"{$explode[1]}\" ";
			}
			else{
				$valueDateFin = "";
				$valueHeureFin = "";
			}
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td>Date Fin</td>\n";
			echo "$tab\t\t\t<td><input id=\"dateFin\" name=\"dateFin\" type=\"date\" required $valueDateFin/> aaaa-mm-jj</td>\n";
			echo "$tab\t\t</tr>\n";
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td>Heure Fin</td>\n";
			echo "$tab\t\t\t<td><input name=\"heureFin\" type=\"time\" required $valueHeureFin/> hh:mm</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label for=\"salle\">Salle</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<select name=\"salle\" id=\"salle\">\n";
			foreach($liste_salle as $idSalle){
				$Salle = new V_Liste_Salles($idSalle);
				$nomBatiment = $Salle->getNomBatiment();
				$nomSalle = $Salle->getNomSalle();
				if(isset($idSalleModif) && ($idSalleModif == $idSalle)){ $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo "$tab\t\t\t\t\t<option value=\"$idSalle\" $selected>$nomBatiment $nomSalle</option>\n";
			}
			echo "$tab\t\t\t\t</select>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td></td>\n";
			if(isset($_GET['modifier_cours'])){ $valueSubmit = "Modifier le cours"; }else{ $valueSubmit = "Ajouter le cours"; }
			echo "$tab\t\t\t<td><input type=\"submit\" name=\"validerAjoutCours\" value=\"$valueSubmit\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";
		}
		
		public static function prise_en_compte_formulaire(){
			if(isset($_POST['validerAjoutCours'])){
				$idUE = $_POST['UE'];
				$idSalle = $_POST['salle'];
				$idIntervenant = $_POST['intervenant'];
				$type = $_POST['type'];
				$dateDebut = $_POST['dateDebut'];
				$heureDebut = $_POST['heureDebut'];
				$dateFin = $_POST['dateFin'];
				$heureFin = $_POST['heureFin'];
				if(true){ // Test de saisie
					$idPromotion = $_GET['idPromotion'];
					if(isset($_GET['modifier_cours'])){
						Cours::modifier_cours($_GET['modifier_cours'], $idUE, $idSalle, $idIntervenant, $type, "$dateDebut $heureDebut:00", "$dateFin $heureFin:00");
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutCours&modification_cours=1";
					}
					else{
						// C'est un nouveau cours
						Cours::ajouter_cours($idUE, $idSalle, $idIntervenant, $type, "$dateDebut $heureDebut:00", "$dateFin $heureFin:00");
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutCours&ajout_cours=1";
					}
					header("Location: $pageDestination");
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
