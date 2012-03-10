<?php
	class Batiment{
		
		public static $nomTable = "Batiment";
		
		public static $attributs = Array(
			"id",
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
		
		public function liste_salles(){
			$listeSalle = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".Salle::$nomTable." WHERE nomBatiment=?");
				$req->execute(
					Array($this->nomBatiment)
					);
				while($ligne = $req->fetch()){
					array_push($listeSalle, new Salle($ligne['id']));
				}
				$req->closeCursor();
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeSalle;
		}
		
		public static function existe_batiment($id){
			try{
				$pdo_Options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_Options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Batiment::$nomTable." WHERE id=?");
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
		
		public static function existe_nom_batiment($nom){
			try{
				$pdo_Options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_Options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Batiment::$nomTable." WHERE nom=?");
				$req->execute(
					Array($nom)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne['nb'] == 1;
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
		
		public static function liste_Batiment_to_table($administration, $nombreTabulations = 0){
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
				$couleurFond = ($cpt == 0) ? "fondblanc" : "fondGris";
				$cpt++; $cpt %= 2;
				
				echo "$tab\t<tr class=\"$couleurFond\">\n";
				echo "$tab\t\t<td>{$Batiment->getNom()}</td>\n";
				echo "$tab\t\t<td>{$Batiment->getLat()}</td>\n";
				echo "$tab\t\t<td>{$Batiment->getLon()}</td>\n";

				if($administration){
					$pageModification = "./index.php?page=ajoutBatiment&amp;modifier_batiment=$idBatiment";
					$pageSuppression = "./index.php?page=ajoutBatiment&amp;supprimer_batiment=$idBatiment";
					if(isset($_GET['idPromotion'])){
						$pageModification .= "&amp;idPromotion={$_GET['idPromotion']}";
						$pageSuppression .= "&amp;idPromotion={$_GET['idPromotion']}";
					}
					echo "$tab\t\t<td>";
					echo "<a href=\"$pageModification\"><img alt=\"icone modification\" src=\"../images/modify.png\"></a>";
					echo "<a href=\"$pageSuppression\" onclick=\"return confirm('Supprimer le bâtiment ?')\"><img alt=\"icone suppression\" src=\"../images/delete.png\" /></a>";
					echo "</td>\n";
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
					Array("", $nom, $lat, $lon)
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
					Array($nom, $lat, $lon, $idBatiment)
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
					Array($idBatiment)
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
			echo "$tab\t\t\t<td><input type=\"submit\" name=\"validerAjoutBatiment\" value=\"{$valueSubmit}\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";			
		}	
		
		public static function prise_en_compte_formulaire(){
			if(isset($_POST['validerAjoutBatiment'])){
				$nom = $_POST['nom'];
				$lat = ($_POST['lat'] == '') ? NULL : $_POST['lat'];
				$lon = ($_POST['lon'] == '') ? NULL : $_POST['lon'];
				$nom_correct = true; // Pas de vérifications spéciales pour un nom de batiment
				$lat_correct = ($lat == NULL || PregMatch::est_float($lat));
				$lon_correct = ($lon == NULL || PregMatch::est_float($lon));
				if($nom_correct && $lat_correct && $lon_correct){
					if(isset($_GET['modifier_batiment'])){
						// C'est une modification de batiment
						Batiment::modifier_batiment($_GET['modifier_batiment'], $nom, $lat, $lon);
						$pageDestination = "./index.php?page=ajoutBatiment&modification_batiment=1";
					}
					else{
						// C'est un nouveau batiment
						Batiment::ajouter_batiment($nom, $lat, $lon);
						$pageDestination = "./index.php?page=ajoutBatiment&ajout_batiment=1";
					}
					if(isset($_GET['idPromotion'])){
						// Si l'idPromotion est choisie, on l'ajoute au lien (pour ne pas le perdre)
						$pageDestination .= "&idPromotion={$_GET['idPromotion']}";
					}
					//header("Location: $pageDestination");
				}
			}
		}
		
		public static function prise_en_compte_suppression(){
			if(isset($_GET['supprimer_batiment'])){			
				if(Batiment::existe_batiment($_GET['supprimer_batiment'])){
					// Le batiment existe
					Batiment::supprimer_batiment($_GET['supprimer_batiment']);
					$pageDestination = "./index.php?page=ajoutBatiment&suppression_batiment=1";	
				}
				else{
					// Le batiment n'existe pas
					$pageDestination = "./index.php?page=ajoutBatiment";
				}
				if(isset($_GET['idPromotion'])){
					// Si l'idPromotion est choisie, on l'ajoute au lien (pour ne pas le perdre)
					$pageDestination .= "&idPromotion={$_GET['idPromotion']}";
				}
				header("Location: $pageDestination");	
			}
		}
		
		public static function page_administration($nombreTabulations = 0){
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			if(isset($_GET['ajout_batiment'])){
				echo "$tab<p class=\"notificationAdministration\">Le bâtiment a bien été ajouté</p>\n";
			}
			else if(isset($_GET['modification_batiment'])){
				echo "$tab<p class=\"notificationAdministration\">Le bâtiment a bien été modifié</p>\n";
			}
			else if(isset($_GET['suppression_batiment'])){
				echo "$tab<p class=\"notificationAdministration\">Le bâtiment a bien été supprimé</p>\n";
			}
			echo "$tab<h1>Gestion des bâtiments</h1>\n";
			Batiment::formulaireAjoutBatiment($nombreTabulations + 1);
			echo "$tab<h2>Liste des bâtiments</h2>\n";
			Batiment::liste_Batiment_to_table($nombreTabulations + 1);
		}
	}
