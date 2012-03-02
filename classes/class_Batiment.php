<?php
	class Batiment{
		
		public static $nomTable = "Batiment";
		
		public static $attributs = Array(
			"nom",
			"lat",
			"lon"
		);
		
		public function getId(){ return $this->id; }
		public function getNom(){ return $this->nom; }
		public function getLat(){ return $this->lat; }
		public function getLon(){ return $this->lon; }
		
		public function Batiment($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Batiment::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Batiment::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function liste_batiment(){
			$listeId = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".Batiment::$nomTable." ORDER BY UPPER(nom)");
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
		
		public static function liste_Batiment_to_table($idPromotion, $administration, $nombreTabulations = 0){
			$liste_batiment = Batiment::liste_batiment();
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			
			echo "$tab<table class=\"listeCours\">\n";
			
			echo "$tab\t<tr class=\"fondGrisFonce\">\n";
			
			echo "$tab\t\t<th>Nom</th>\n";
			echo "$tab\t\t<th>Latitude</th>\n";
			echo "$tab\t\t<th>Longitude</th>\n";
			
			if($administration){
				echo "$tab\t\t<th>Actions</th>\n";
			}
			echo "$tab\t</tr>\n";
			
			$cpt = 0;
			foreach($liste_batiment as $idBatiment){
				$Batiment = new Batiment($idBatiment);
				
				if($cpt == 0){ $couleurFond="fondBlanc"; }
				else{ $couleurFond="fondGris"; }
				$cpt++; $cpt %= 2;
				
				echo "$tab\t<tr class=\"$couleurFond\">\n";
				foreach(Batiment::$attributs as $att){
					echo "$tab\t\t<td>".$Batiment->$att."</td>\n";
				}
				if($administration){
					$pageModification = "./index.php?idPromotion=$idPromotion&amp;page=ajoutBatiment&amp;modifier_batiment=$idBatiment";
					$pageSuppression = "./index.php?idPromotion=$idPromotion&amp;page=ajoutBatiment&amp;supprimer_batiment=$idBatiment";
					echo "$tab\t\t<td><img src=\"../images/modify.jpg\" width=\"20\" height=\"20\" style=\"cursor:pointer;\" onClick=\"location.href='{$pageModification}'\">  <img src=\"../images/delete.jpg\" width=\"20\" height=\"20\" style=\"cursor:pointer;\" OnClick=\"location.href=confirm('Voulez vous vraiment supprimer ce batiment ?') ? '{$pageSuppression}' : ''\"/>\n";
				}
				echo "$tab\t</tr>\n";
			}
			
			echo "$tab</table>\n";
		}
		
		public static function ajouter_batiment($nom, $lat, $lon){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Batiment::$nomTable." VALUES(?, ?, ?, ?)");
				
				$req->execute(
					Array(
						"",
						$nom,
						$lat, 
						$lon
					)
				);			
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_batiment($idBatiment, $nom, $lat, $lon){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Batiment::$nomTable." SET nom=?, lat=?, lon=? WHERE id=?;");
				$req->execute(
					Array(
						$nom, 
						$lat, 
						$lon, 
						$idBatiment
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function supprimer_batiment($idBatiment){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Batiment::$nomTable." WHERE id=?;");
				$req->execute(
					Array(
						$idBatiment
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function formulaireAjoutBatiment($nombresTabulations = 0){
			$tab = ""; while($nombresTabulation = 0){ $tab .= "\t"; $nombresTabulations--; }
			
			if(isset($_GET['modifier_batiment'])){ 
				$titre = "Modifier un batiment";
				$Batiment = new Batiment($_GET['modifier_batiment']);
				$nomModif = "value=\"{$Batiment->getNom()}\"";
				$latModif = "value=\"{$Batiment->getLat()}\"";
				$lonModif = "value=\"{$Batiment->getLon()}\"";
			}
			else{
				$titre = "Ajouter un batiment";
				$nomModif = "value=\"\"";
				$latModif = "value=\"\"";
				$lonModif = "value=\"\"";
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
			echo "$tab\t\t\t<td><label>Latitude</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<input name=\"lat\" type=\"text\" {$latModif}/>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label>Longitude</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<input name=\"lon\" type=\"text\" {$lonModif}/>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td></td>\n";
			if(isset($_GET['modifier_batiment'])){ $valueSubmit = "Modifier le batiment"; }else{ $valueSubmit = "Ajouter le batiment"; }
			echo "$tab\t\t\t<td><input type=\"submit\" name=\"validerAjoutBatiment\" value=\"{$valueSubmit}\" style=\"cursor:pointer\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";			
		}	
		
		public static function prise_en_compte_formulaire(){
			if(isset($_POST['validerAjoutBatiment'])){
				$nom = $_POST['nom'];
				$lat = ($_POST['lat'] == '') ? NULL : $_POST['lat'];
				$lon = ($_POST['lon'] == '') ? NULL : $_POST['lon'];
				if(true){ // Test de saisie
					$idPromotion = $_GET['idPromotion'];	
					if(isset($_GET['modifier_batiment'])){
						Batiment::modifier_batiment($_GET['modifier_batiment'], $nom, $lat, $lon);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutBatiment&modification_batiment=1";
					}
					else{
						// C'est un nouveau batiment
						Batiment::ajouter_batiment($nom, $lat, $lon);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutBatiment&modification_batiment=1";
					}
					header("Location: $pageDestination");
				}
			}
		}
		
		public static function prise_en_compte_suppression(){
			if(isset($_GET['supprimer_batiment'])){	
				$idPromotion = $_GET['idPromotion'];		
				if(true){ // Test de saisie
					Batiment::supprimer_batiment($_GET['supprimer_batiment']);
					$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutBatiment&supprimer_batiment=1";	
				}
			}
		}
		
		public static function page_administration($nombreTabulations = 0){
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			if(isset($_GET['ajout_batiment'])){
				echo "$tab<p class=\"notificationAdministration\">Le batiment a bien été ajouté</p>";
			}
			if(isset($_GET['modification_batiment'])){
				echo "$tab<p class=\"notificationAdministration\">Le batiment a bien été modifié</p>";
			}
			Batiment::formulaireAjoutBatiment($nombreTabulations + 1);
			echo "$tab<h1>Liste des batiments</h1>\n";
			Batiment::liste_Batiment_to_table($_GET['idPromotion'], $nombreTabulations + 1);
		}
		
		public function toString(){
			$string = "";
			foreach(Batiment::$attributs as $att){
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Batiment::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Batiment::$nomTable);
		}
	}
