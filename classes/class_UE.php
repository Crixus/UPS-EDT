<?php
	class UE{
		
		public static $nomTable = "UE";
		
		public static $attributs = Array(
			"nom",
			"intitule",
			"nbHeuresCours",
			"nbHeuresTD",
			"nbHeuresTP",
			"ECTS",
			"idResponsable"
		);
		
		public function UE($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".UE::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(UE::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function getId(){ return $this->id; }
		public function getNom(){ return $this->nom; }
		public function getIntitule(){ return $this->intitule; }
		public function getNbHeuresCours(){ return $this->nbHeuresCours; }
		public function getNbHeuresTD(){ return $this->nbHeuresTD; }
		public function getNbHeuresTP(){ return $this->nbHeuresTP; }
		public function getECTS(){ return $this->ECTS; }
		public function getIdResponsable(){ return $this->idResponsable; }
		
		public static function ajouter_UE($nom, $intitule, $nbHeuresCours, $nbHeuresTD, $nbHeuresTP, $ECTS, $idResponsable, $idPromotion){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".UE::$nomTable." VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
				
				$req->execute(
					Array(
						"",						
						$nom,
						$intitule,
						$nbHeuresCours, 
						$nbHeuresTD, 
						$nbHeuresTP, 
						$ECTS,
						$idResponsable,
						$idPromotion,
					)
				);			
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_UE($idUE, $nom, $intitule, $nbHeuresCours, $nbHeuresTD, $nbHeuresTP, $ECTS, $idResponsable, $idPromotion){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".UE::$nomTable." SET nom=?, intitule=?, nbHeuresCours=?, nbHeuresTD=?, nbHeuresTP=?, ECTS=?, idResponsable=?, idPromotion=? WHERE id=?;");
				$req->execute(
					Array(
						$nom,
						$intitule,
						$nbHeuresCours, 
						$nbHeuresTD, 
						$nbHeuresTP, 
						$ECTS,
						$idResponsable,
						$idPromotion,
						$idUE
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function supprimer_UE($idUE){
			//Suppression de l'UE dans la table "Inscription"
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Inscription::$nomTable." WHERE idUE=?;");
				$req->execute(
					Array(
						$idUE
					)
				);
				
				//Suppression de l'UE
				try{
					$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("DELETE FROM ".UE::$nomTable." WHERE id=?;");
					$req->execute(
						Array(
							$idUE
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
		
		public function getNbreUEPromotion($idPromotion){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".UE::$nomTable." WHERE idPromotion=?");
				$req->execute(
					Array($idPromotion)
				);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne["nb"];
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function liste_UE_promotion($idPromotion){
			$listeId = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".UE::$nomTable." WHERE idPromotion=? ORDER BY nom");
				$req->execute(
					Array($idPromotion)
				);
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
		
		public static function liste_UE_to_table($idPromotion, $administration, $nombreTabulations = 0){
			$liste_UE = V_Infos_UE::liste_UE($idPromotion);
			$nbUE = sizeof($liste_UE);
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			
			if ($nbUE == 0) {
				echo "$tab<b>Aucune UE n'est enregistré pour cette promotion</b>\n";
			}
			else {
			
				echo "$tab<table class=\"listeCours\">\n";
				
				echo "$tab\t<tr class=\"fondGrisFonce\">\n";
				
				echo "$tab\t\t<th colspan='2'>UE</th>\n";
				echo "$tab\t\t<th colspan='3'>Nombres d'heures</th>\n";
				echo "$tab\t\t<th rowspan='2'>ECTS</th>\n";
				echo "$tab\t\t<th rowspan='2'>Responsable</th>\n";
				echo "$tab\t\t<th rowspan='2'>Nombre<br>d'élèves inscrits</th>\n";
				
				if($administration){
					echo "$tab\t\t<th rowspan='2'>Actions</th>\n";
				}					
				echo "$tab\t</tr>\n";	
				
				echo "$tab\t<tr class=\"fondGrisFonce\">\n";	
				echo "$tab\t\t<th>Nom</th>\n";		
				echo "$tab\t\t<th>Intitule</th>\n";		
				echo "$tab\t\t<th>Cours</th>\n";
				echo "$tab\t\t<th>TD</th>\n";
				echo "$tab\t\t<th>TP</th>\n";
				echo "$tab\t</tr>\n";
				
				$cpt = 0;
				foreach($liste_UE as $idUE){
					$UE = new V_Infos_UE($idUE);
					
					if($cpt == 0){ $couleurFond="fondBlanc"; }
					else{ $couleurFond="fondGris"; }
					$cpt++; $cpt %= 2;
					
					echo "$tab\t<tr class=\"$couleurFond\">\n";
					$cptBoucle=0;
					$val_temp = "";
					foreach(V_Infos_UE::$attributs as $att){
						if ($cptBoucle == 6)
							$val_temp = $UE->$att;
						else if ($cptBoucle == 7)
							echo "$tab\t\t<td>".$UE->$att." ".$val_temp."</td>\n";
						else
							echo "$tab\t\t<td>".$UE->$att."</td>\n";
						$cptBoucle++;
					}
					
					$nbreUE = Inscription::nbre_etudiant_inscrit($idUE);
					echo "$tab\t\t<td>".$nbreUE."</td>\n";
					
					if($administration){
						$pageModification = "./index.php?idPromotion={$_GET['idPromotion']}&amp;page=ajoutUE&amp;modifier_UE=$idUE";
						$pageSuppression = "./index.php?idPromotion={$_GET['idPromotion']}&amp;page=ajoutUE&amp;supprimer_UE=$idUE";
						echo "$tab\t\t<td><img src=\"../images/modify.png\" style=\"cursor:pointer;\" onClick=\"location.href='{$pageModification}'\">  <img src=\"../images/delete.png\" style=\"cursor:pointer;\" OnClick=\"location.href=confirm('Voulez vous vraiment supprimer cette UE ?') ? '{$pageSuppression}' : ''\"/>\n";
				}
					echo "$tab\t</tr>\n";
				}
				
				echo "$tab</table>\n";
			}
		}
		
		// Formulaire
		public function formulaireAjoutUE($idPromotion, $nombresTabulations = 0){
			$tab = ""; while($nombresTabulation = 0){ $tab .= "\t"; $nombresTabulations--; }
			$liste_intervenant = Intervenant::liste_intervenant();
			
			if(isset($_GET['modifier_UE'])){ 
				$titre = "Modifier une UE";
				$UE = new UE($_GET['modifier_UE']);
				$nomModif = "value=\"{$UE->getNom()}\"";
				$intituleModif = "value=\"{$UE->getIntitule()}\"";
				$nbHeuresCoursModif = "value=\"{$UE->getNbHeuresCours()}\"";
				$nbHeuresTDModif = "value=\"{$UE->getNbHeuresTD()}\"";
				$nbHeuresTPModif = "value=\"{$UE->getNbHeuresTP()}\"";
				$ectsModif = "value=\"{$UE->getECTS()}\"";
				$idResponsableModif = $UE->getIdResponsable();
			}
			else{
				$titre = "Ajouter une UE";
				$nomModif = "";
				$intituleModif = "";
				$nbHeuresCoursModif = "";
				$nbHeuresTDModif = "";
				$nbHeuresTPModif = "";
				$ectsModif = "";
				$idResponsableModif = "";
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
			echo "$tab\t\t\t<td><label>Intitulé</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<input name=\"intitule\" type=\"text\" required {$intituleModif}/>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label>Nombre d'heures de cours</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<input name=\"nbHeuresCours\" type=\"number\" min=\"0\" max=\"99\" required {$nbHeuresCoursModif}/>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label>Nombre d'heures de TD</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<input name=\"nbHeuresTD\" type=\"number\" min=\"0\" max=\"99\" required {$nbHeuresTDModif}/>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label>Nombre d'heures de TP</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<input name=\"nbHeuresTP\" type=\"number\" min=\"0\" max=\"99\" required {$nbHeuresTPModif}/>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label>Nombre d'ECTS</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<input name=\"ects\" type=\"number\" min=\"0\" max=\"99\" required {$ectsModif}/>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label>Intervenant</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<select name=\"idIntervenant\" id=\"idIntervenant\">\n";
			
			if(isset($idResponsableModif) && ($idResponsableModif == 0)){ $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo "$tab\t\t\t\t\t<option value=\"0\" $selected>----- Inconnu -----</option>\n";
			foreach($liste_intervenant as $idIntervenant){
				$Intervenant = new Intervenant($idIntervenant);
				$nomIntervenant = $Intervenant->getNom(); $prenomIntervenant = $Intervenant->getPrenom();
				if(isset($idResponsableModif) && ($idResponsableModif == $idIntervenant)){ $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo "$tab\t\t\t\t\t<option value=\"$idIntervenant\" $selected>$nomIntervenant $prenomIntervenant</option>\n";
			}
			echo "$tab\t\t\t\t</select>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td></td>\n";
			if(isset($_GET['modifier_UE'])){ $valueSubmit = "Modifier l'UE"; }else{ $valueSubmit = "Ajouter l'UE"; }
			echo "$tab\t\t\t<td><input type=\"submit\" name=\"validerAjoutUE\" value=\"$valueSubmit\" style=\"cursor:pointer\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";
		}
		
		public static function prise_en_compte_formulaire(){
			if(isset($_POST['validerAjoutUE'])){
				$nom = $_POST['nom'];
				$intitule = $_POST['intitule'];
				$nbHeuresCours = $_POST['nbHeuresCours'];
				$nbHeuresTD = $_POST['nbHeuresTD'];
				$nbHeuresTP = $_POST['nbHeuresTP'];
				$ects = $_POST['ects'];
				$idIntervenant = $_POST['idIntervenant'];
				if(true){ // Test de saisie
					$idPromotion = $_GET['idPromotion'];
					if(isset($_GET['modifier_UE'])){
						UE::modifier_UE($_GET['modifier_UE'], $nom, $intitule, $nbHeuresCours, $nbHeuresTD, $nbHeuresTP, $ects, $idIntervenant, $idPromotion);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutUE&modification_UE=1";
					}
					else{
						// C'est une nouvelle UE
						UE::ajouter_UE($nom, $intitule, $nbHeuresCours, $nbHeuresTD, $nbHeuresTP, $ects, $idIntervenant, $idPromotion);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutUE&ajout_UE=1";
					}
					header("Location: $pageDestination");
				}
			}
		}
		
		public static function prise_en_compte_suppression(){
			if(isset($_GET['supprimer_UE'])){	
				$idPromotion = $_GET['idPromotion'];	
				if(true){ // Test de saisie
					UE::supprimer_UE($_GET['supprimer_UE']);
					$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutUE&supprimer_UE=1";	
				}
			}
		}
		
		public static function page_administration($nombreTabulations = 0){
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			if(isset($_GET['ajout_UE'])){
				echo "$tab<p class=\"notificationAdministration\">L'UE a bien été ajouté</p>";
			}
			if(isset($_GET['modification_UE'])){
				echo "$tab<p class=\"notificationAdministration\">L'UE a bien été modifié</p>";
			}
			UE::formulaireAjoutUE($_GET['idPromotion'], $nombreTabulations + 1);
			echo "$tab<h1>Liste des UE</h1>\n";
			UE::liste_UE_to_table($_GET['idPromotion'], true, $nombreTabulations + 1);
		}	
		
		public function toUl(){
			$string = "<ul>\n";
			foreach(UE::$attributs as $att){
				$string .= "<li>$att : ".$this->$att."</li>\n";
			}
			return "$string</ul>\n";
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".UE::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(UE::$nomTable);
		}
	}
