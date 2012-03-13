<?php
	class Batiment{
		
		public static $nomTable = "Batiment";
		
		public static $attributs = Array(
			"id", "nom", "lat", "lon"
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
		
		public static function table_administration_batiments($nombreTabulations = 0){
			$tab = ""; for($i = 0 ; $i < $nombreTabulations ; $i++){ $tab .= "\t"; }
			$liste_batiment = Batiment::liste_batiment();
			
			echo "$tab<table class=\"listeCours\">\n";			
			echo "$tab\t<tr class=\"fondGrisFonce\">\n";			
			echo "$tab\t\t<th>Nom</th>\n";
			echo "$tab\t\t<th>Latitude</th>\n";
			echo "$tab\t\t<th>Longitude</th>\n";
			echo "$tab\t\t<th>Actions</th>\n";
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
				echo "$tab\t</tr>\n";
			}
			echo "$tab</table>\n";
		}
		
		public function formulaireAjoutBatiment($nombreTabulations = 0){
			$tab = ""; for($i = 0 ; $i < $nombreTabulations ; $i++){ $tab .= "\t"; }
			
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
			if(isset($_GET['modifier_batiment'])){ 
				$valueSubmit = "Modifier le batiment"; 
				$nameSubmit = "validerModificationBatiment";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_batiment']}\" />";
				$lienAnnulation = "index.php?page=ajoutBatiment";
				if(isset($_GET['idPromotion'])){
					$lienAnnulation .= "&amp;idPromotion={$_GET['idPromotion']}";
				}
			}
			else{ 
				$valueSubmit = "Ajouter le batiment"; 
				$nameSubmit = "validerAjoutBatiment";
				$hidden = "";
			}
			echo "$tab\t\t\t<td>$hidden<input type=\"submit\" name=\"$nameSubmit\" value=\"{$valueSubmit}\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";	
			if(isset($lienAnnulation)){echo "$tab<p><a href=\"$lienAnnulation\">Annuler modification</a></p>";}		
		}	
		
		public static function prise_en_compte_formulaire(){
			global $messages_notifications, $messages_erreurs;
			if(isset($_POST['validerAjoutBatiment'])){
				$nom = $_POST['nom'];
				$lat = ($_POST['lat'] == '') ? NULL : $_POST['lat'];
				$lon = ($_POST['lon'] == '') ? NULL : $_POST['lon'];
				$nom_correct = true; // Pas de vérifications spéciales pour un nom de batiment
				$lat_correct = ($lat == NULL || PregMatch::est_float($lat));
				$lon_correct = ($lon == NULL || PregMatch::est_float($lon));
				if($nom_correct && $lat_correct && $lon_correct){
					Batiment::ajouter_batiment($nom, $lat, $lon);
					array_push($messages_notifications, "Le bâtiment a bien été ajouté");
				}
				else{
					array_push($messages_erreurs, "La saisie n'est pas correcte");
				}
			}
			if(isset($_POST['validerModificationBatiment'])){
				$nom = $_POST['nom'];
				$lat = ($_POST['lat'] == '') ? NULL : $_POST['lat'];
				$lon = ($_POST['lon'] == '') ? NULL : $_POST['lon'];
				$id = $_POST['id'];
				$nom_correct = true; // Pas de vérifications spéciales pour un nom de batiment
				$lat_correct = ($lat == NULL || PregMatch::est_float($lat));
				$lon_correct = ($lon == NULL || PregMatch::est_float($lon));
				$id_correct = true;
				if($nom_correct && $lat_correct && $lon_correct && $id_correct){
					Batiment::modifier_batiment($id, $nom, $lat, $lon);
					array_push($messages_notifications, "Le bâtiment a bien été modifié");
				}
				else{
					array_push($messages_erreurs, "La saisie n'est pas correcte");
				}
				
			}
		}
		
		public static function prise_en_compte_suppression(){
			global $messages_notifications, $messages_erreurs;
			if(isset($_GET['supprimer_batiment'])){			
				if(Batiment::existe_batiment($_GET['supprimer_batiment'])){
					// Le batiment existe
					Batiment::supprimer_batiment($_GET['supprimer_batiment']);
					array_push($messages_notifications, "Le bâtiment à bien été supprimé");
				}
				else{
					// Le batiment n'existe pas
					array_push($messages_erreurs, "Le bâtiment n'existe pas");
				}	
			}
		}
		
		public static function page_administration($nombreTabulations = 0){
			$tab = ""; for($i = 0 ; $i < $nombreTabulations ; $i++){ $tab .= "\t"; }
			Batiment::formulaireAjoutBatiment($nombreTabulations);
			echo "$tab<h2>Liste des bâtiments</h2>\n";
			Batiment::table_administration_batiments($nombreTabulations);
		}
	}
