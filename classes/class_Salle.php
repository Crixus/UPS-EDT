<?php
	class Salle{
		
		public static $nomTable = "Salle";
		
		public static $attributs = Array(
			"id",
			"nom",
			"nomBatiment",
			"capacite"
		);
		
		public function getId(){ return $this->id; }
		public function getNom(){ return $this->nom; }
		public function getNomBatiment(){ return $this->nomBatiment; }
		public function getCapacite(){ return $this->capacite; }
		
		public function Salle($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Salle::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Salle::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function existe_salle($id){
			try{
				$pdo_Options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_Options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Salle::$nomTable." WHERE id=?");
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
		
		public static function existe_salle_nomSalle_nomBatiment($nomSalle, $nomBatiment){
			try{
				$pdo_Options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_Options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Salle::$nomTable." WHERE nom=? AND nomBatiment=?");
				$req->execute(
					Array($nomSalle, $nomBatiment)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne['nb'] == 1;
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function infosSalles($idSalle) {
			$listeNom = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Salle::$nomTable." WHERE id=?");
				$req->execute(
					Array($idSalle)
				);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Salle::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		// Ne devrait pas être ici
		public function listeNomBatiment() {
			$listeNom = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT nom FROM ".Batiment::$nomTable." GROUP BY nom");
				$req->execute();
				while($ligne = $req->fetch()){
					array_push($listeNom, $ligne['nom']);
				}
				$req->closeCursor();
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeNom;
		}
		
		public static function ajouter_salle($nom, $nomBatiment, $capacite){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Salle::$nomTable." VALUES(?, ?, ?, ?)");
				
				$req->execute(
					Array(
						"",
						$nom,
						$nomBatiment, 
						$capacite
					)
				);			
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_salle($idSalle, $nom, $nomBatiment, $capacite){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Salle::$nomTable." SET nom=?, nomBatiment=?, capacite=? WHERE id=?;");
				$req->execute(
					Array(
						$nom, 
						$nomBatiment, 
						$capacite, 
						$idSalle
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function supprimer_salle($idSalle){
			//MAJ de la table "Cours" on met idSalle à 0 pour l'idSalle correspondant
			// Se fera tout seul avec la Base de données (quand on aura trouvé)
			Cours::modifier_salle_tout_cours($idSalle, 0);
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Salle::$nomTable." WHERE id=?;");
				$req->execute(
					Array($idSalle)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}		
		}
		
		public function formulaireAjoutSalle($nombreTabulations = 0){
			$tab = ""; for($i = 0 ; $i < $nombreTabulations ; $i++){ $tab .= "\t"; }
			$liste_nom_batiment = Salle::listeNomBatiment();
			
			if(isset($_GET['modifier_salle'])){ 
				$titre = "Modifier une salle";
				$Salle = new Salle($_GET['modifier_salle']);
				$nomModif = "value=\"{$Salle->getNom()}\"";
				$nomBatimentModif = $Salle->getNomBatiment();
				$capaciteModif = "value=\"{$Salle->getCapacite()}\"";
			}
			else{
				$titre = "Ajouter une salle";
				$nomModif = "";
				$nomBatimentModif = "";
				$capaciteModif = "";
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
			echo "$tab\t\t\t<td><label>Capacité</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<input name=\"capacite\" type=\"number\" min=\"0\" max=\"999\" required {$capaciteModif}/>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label>Bâtiment</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<select name=\"nomBatiment\" id=\"nomBatiment\">\n";
			foreach($liste_nom_batiment as $nom_batiment){
				if(isset($nomBatimentModif) && ($nomBatimentModif == $nom_batiment)){ $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo "$tab\t\t\t\t\t<option value=\"$nom_batiment\" $selected>$nom_batiment</option>\n";
			}
			echo "$tab\t\t\t\t</select>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td></td>\n";
			if(isset($_GET['modifier_salle'])){ $valueSubmit = "Modifier la salle"; }else{ $valueSubmit = "Ajouter la salle"; }
			echo "$tab\t\t\t<td><input type=\"submit\" name=\"validerAjoutSalle\" value=\"{$valueSubmit}\" style=\"cursor:pointer\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";			
		}	
		
		public static function prise_en_compte_formulaire(){
			if(isset($_POST['validerAjoutSalle'])){
				$nom = $_POST['nom'];
				$capacite = $_POST['capacite'];
				$nomBatiment = $_POST['nomBatiment'];
				$nom_correct = true; // Pas de vérifications spéciales pour un nom de salle
				$capacite_correct = PregMatch::est_nombre($capacite);
				$nomBatiment_correct = Batiment::existe_nom_batiment($nomBatiment);
				$salle_inexistante = !Salle::existe_salle_nomSalle_nomBatiment($nom, $nomBatiment);
				if($nom_correct && $capacite_correct && $nomBatiment_correct && $salle_inexistante){	
					if(isset($_GET['modifier_salle'])){
						// C'est une modification de salle
						Salle::modifier_salle($_GET['modifier_salle'], $nom, $nomBatiment, $capacite);
						$pageDestination = "./index.php?page=ajoutSalle&modification_salle=1";
					}
					else{
						// C'est une nouveau salle
						Salle::ajouter_salle($nom, $nomBatiment, $capacite);
						$pageDestination = "./index.php?page=ajoutSalle&ajout_salle=1";
					}
				}
				else{
					// Traitement des erreurs
					$pageDestination = "./index.php?page=ajoutSalle";
				}
				if(isset($_GET['idPromotion'])){
					$pageDestination .= "&idPromotion={$_GET['idPromotion']}";
				}
				header("Location: $pageDestination");
			}
		}
		
		public static function prise_en_compte_suppression(){
			if(isset($_GET['supprimer_salle'])){
				if(Salle::existe_salle($_GET['supprimer_salle'])){ // Test de saisie
					Salle::supprimer_salle($_GET['supprimer_salle']);
					$pageDestination = "./index.php?page=ajoutSalle&suppression_salle=1";	
				}
				else{
					$pageDestination = "./index.php?page=ajoutSalle";
				}
				if(isset($_GET['idPromotion'])){
					$pageDestination .= "&idPromotion={$_GET['idPromotion']}";
				}
				header("Location : $pageDestination"); // Ne fonctionne pas ??
			}
		}
		
		public static function liste_salle_to_table($administration, $nombreTabulations = 0){
			$tab = ""; for($i = 0 ; $i < $nombreTabulations ; $i++){ $tab .= "\t"; }
			
			$liste_salles = V_Liste_Salles::liste_salles();
			$liste_type_salle = Type_Salle::liste_type_salle();
			$nbre_type_salle = Type_Salle::getNbreTypeSalle();
			
			echo "$tab<table class=\"listeCours\">\n";
			
			echo "$tab\t<tr class=\"fondGrisFonce\">\n";
			echo "$tab\t\t<th rowspan=\"2\">Batiment</th>\n";
			echo "$tab\t\t<th rowspan=\"2\">Salle</th>\n";
			echo "$tab\t\t<th rowspan=\"2\">Capacité</th>\n";
			echo "$tab\t\t<th rowspan=\"2\">Latitude</th>\n";
			echo "$tab\t\t<th rowspan=\"2\">Longitude</th>\n";

			echo "$tab\t\t<th colspan='{$nbre_type_salle}'>Type de salles</th>\n";
			if($administration){
				echo "$tab\t\t<th rowspan=\"2\">Actions</th>\n";
			}
			echo "$tab\t</tr>\n";
			echo "$tab\t<tr class=\"fondGrisFonce\">\n";
			
			foreach($liste_type_salle as $idType_Salle) {					
				$Type_Salle = new Type_Salle($idType_Salle);
				$nomType_Salle = $Type_Salle->getNom();
				echo "$tab\t\t<th>$nomType_Salle</th>\n";
			}
			echo "$tab\t</tr>\n";
			
			$cpt = 0;
			foreach($liste_salles as $idSalle){
				$Salle = new V_Liste_Salles($idSalle);
				
				if($cpt == 0){ $couleurFond="fondBlanc"; }
				else{ $couleurFond="fondGris"; }
				$cpt++; $cpt %= 2;
				
				echo "$tab\t<tr class=\"$couleurFond\">\n";
				foreach(V_Liste_Salles::$attributs as $att){
					echo "$tab\t\t<td>".$Salle->$att."</td>\n";
				}
				
				foreach($liste_type_salle as $idType_Salle) {					
					$Type_Salle = new Type_Salle($idType_Salle);
					$nomType_Salle = $Type_Salle->getNom();
					if(Type_Salle::appartient_salle_typeSalle($idSalle, $idType_Salle)) 
						$checked = "checked = \"checked\"" ;
					else
						$checked = "";
					$nomCheckbox = "{$idSalle}_{$nomType_Salle}";
					echo "$tab\t\t<td><input type=\"checkbox\" name= \"{$idSalle}_{$nomType_Salle}\" value=\"{$idType_Salle}\" onclick=\"appartenance_salle_typeSalle({$idSalle},{$idType_Salle},this)\" style=\"cursor:pointer\" {$checked}></td>\n";
				}
			
				if($administration){
					$pageModification = "./index.php?page=ajoutSalle&amp;modifier_salle=$idSalle";
					$pageSuppression = "./index.php?page=ajoutSalle&amp;supprimer_salle=$idSalle";
					if(isset($_GET['idPromotion'])){
						$pageModification .= "&amp;idPromotion={$_GET['idPromotion']}";
						$pageSuppression .= "&amp;idPromotion={$_GET['idPromotion']}";
					}
					echo "$tab\t\t<td>";
					echo "<a href=\"$pageModification\"><img alt=\"icone modification\" src=\"../images/modify.png\"></a>";
					echo "<a href=\"$pageSuppression\" onclick=\"return confirm('Supprimer la salle ?')\"><img alt=\"icone suppression\" src=\"../images/delete.png\" /></a>";
					echo "</td>";
				}
				echo "$tab\t</tr>\n";
			}
			
			echo "$tab</table>\n";
		}
		
		public static function page_administration($nombreTabulations = 0){
			$tab = ""; for($i = 0 ; $i < $nombreTabulations ; $i++){ $tab .= "\t"; }
			if(isset($_GET['ajout_salle'])){
				echo "$tab<p class=\"notificationAdministration\">La salle a bien été ajoutée</p>";
			}
			else if(isset($_GET['modification_salle'])){
				echo "$tab<p class=\"notificationAdministration\">La salle a bien été modifiée</p>";
			}
			else if(isset($_GET['suppression_salle'])){
				echo "$tab<p class=\"notificationAdministration\">La salle a bien été supprimée</p>";
			}
			echo "$tab<h1>Gestion des salles</h1>\n";
			Salle::formulaireAjoutSalle($nombreTabulations + 1);
			echo "$tab<h2>Liste des salles</h2>\n";
			Salle::liste_Salle_to_table($nombreTabulations + 1);
		}
	}
