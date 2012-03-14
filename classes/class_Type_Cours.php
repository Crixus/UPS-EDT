<?php
	class Type_Cours{
		
		public static $nomTable = "Type_Cours";
		
		public static $attributs = Array(
			"nom"
		);
		
		public function getNom(){ return $this->nom; }
		
		public function Type_Cours($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Type_Cours::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Type_Cours::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function getNbreTypeCours(){ 
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Type_Cours::$nomTable);
				$req->execute();
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne["nb"];
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function liste_id_type_cours(){
			$listeId = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->query("SELECT * FROM ".Type_Cours::$nomTable." ORDER BY nom");
				
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
		
		public function liste_nom_type_cours(){
			$listeNom = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->query("SELECT nom FROM ".Type_Cours::$nomTable." ORDER BY nom");
				
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
		
		public static function liste_type_cours_to_table($idPromotion, $administration, $nombreTabulations = 0){
			$liste_type_cours = Type_Cours::liste_id_type_cours();
			$liste_type_salle = Type_Salle::liste_id_type_salle();
			$nbre_type_salle = sizeof($liste_type_salle);
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			
			echo "$tab<table class=\"table_liste_administration\">\n";
			
			echo "$tab\t<tr class=\"fondGrisFonce\">\n";
			echo "$tab\t\t<th rowspan='2'>Nom</th>\n";
			echo "$tab\t\t<th colspan='{$nbre_type_salle}'>Type de salles</th>\n";
			
			if($administration){
				echo "$tab\t\t<th rowspan='2'>Actions</th>\n";
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
			foreach($liste_type_cours as $idTypeCours){
				$Type_Cours = new Type_Cours($idTypeCours);
				
				if($cpt == 0){ $couleurFond="fondBlanc"; }
				else{ $couleurFond="fondGris"; }
				$cpt++; $cpt %= 2;
				
				echo "$tab\t<tr class=\"$couleurFond\">\n";
				foreach(Type_Cours::$attributs as $att){
					echo "$tab\t\t<td>".$Type_Cours->$att."</td>\n";
				}
				
				foreach($liste_type_salle as $idTypeSalle) {					
					$Type_Salle = new Type_Salle($idTypeSalle);
					$nomType_Salle = $Type_Salle->getNom();
					if(Type_Cours::appartenance_typeSalle_typeCours($idTypeCours, $idTypeSalle)) 
						$checked = "checked = \"checked\"" ;
					else
						$checked = "";
					$nomCheckbox = "{$idTypeCours}_{$nomType_Salle}";
					echo "$tab\t\t<td><input type=\"checkbox\" name= \"{$idTypeCours}_{$nomType_Salle}\" value=\"{$idTypeSalle}\" onclick=\"appartenance_typeSalle_typeCours({$idTypeCours},{$idTypeSalle},this)\" style=\"cursor:pointer\" {$checked}></td>\n";
				}
				
				if($administration){
					$pageModification = "./index.php?idPromotion=$idPromotion&amp;page=ajoutTypeCours&amp;modifier_type_cours=$idTypeCours";
					$pageSuppression = "./index.php?idPromotion=$idPromotion&amp;page=ajoutTypeCours&amp;supprimer_type_cours=$idTypeCours";
					echo "$tab\t\t<td><img src=\"../images/modify.png\" style=\"cursor:pointer;\" onClick=\"location.href='{$pageModification}'\">  <img src=\"../images/delete.png\" style=\"cursor:pointer;\" OnClick=\"location.href=confirm('Voulez vous vraiment supprimer ce type de cours ?') ? '{$pageSuppression}' : ''\"/>\n";
				}
				echo "$tab\t</tr>\n";
			}
			
			echo "$tab</table>\n";
		}
		
		public static function ajouter_type_cours($nom){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Type_Cours::$nomTable." VALUES(?, ?, ?)");
				
				$req->execute(
					Array(
						"",
						$nom,
						"0"
					)
				);			
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_type_cours($idTypeCours, $nom){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Type_Cours::$nomTable." SET nom=? WHERE id=?;");
				$req->execute(
					Array(
						$nom, 
						$idTypeCours
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function supprimer_type_cours($idTypeCours){
			//Suppression des entrées de la table "Appartient_TypeSalle_TypeCours
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Appartient_TypeSalle_TypeCours::$nomTable." WHERE idTypeCours=?;");
				$req->execute(
					Array(
						$idTypeCours
					)
				);
				
				//Suppression du type de cours
				try{
					$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("DELETE FROM ".Type_Cours::$nomTable." WHERE id=?;");
					$req->execute(
						Array(
							$idTypeCours
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
		
		public function formulaireAjoutTypeCours($nombresTabulations = 0){
			$tab = ""; while($nombresTabulation = 0){ $tab .= "\t"; $nombresTabulations--; }
			
			if(isset($_GET['modifier_type_cours'])){ 
				$titre = "Modifier un type de cours";
				$Type_Cours = new Type_Cours($_GET['modifier_type_cours']);
				$nomModif = "value=\"{$Type_Cours->getNom()}\"";
			}
			else{
				$titre = "Ajouter un type de cours";
				$nomModif = "value=\"\"";
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
			echo "$tab\t\t\t<td></td>\n";
			if(isset($_GET['modifier_type_cours'])){ $valueSubmit = "Modifier le type de cours"; }else{ $valueSubmit = "Ajouter le type de cours"; }
			echo "$tab\t\t\t<td><input type=\"submit\" name=\"validerAjoutTypeCours\" value=\"{$valueSubmit}\" style=\"cursor:pointer\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";			
		}	
		
		public static function prise_en_compte_formulaire(){
			if(isset($_POST['validerAjoutTypeCours'])){
				$nom = $_POST['nom'];
				if(true){ // Test de saisie
					$idPromotion = $_GET['idPromotion'];	
					if(isset($_GET['modifier_type_cours'])){
						Type_Cours::modifier_type_cours($_GET['modifier_type_cours'], $nom);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutTypeCours&modification_type_cours=1";
					}
					else{
						// C'est un nouveau type de cours
						Type_Cours::ajouter_type_cours($nom);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutTypeCours&modification_type_cours=1";
					}
					header("Location: $pageDestination");
				}
			}
		}
		
		public static function prise_en_compte_suppression(){
			if(isset($_GET['supprimer_type_cours'])){	
				$idPromotion = $_GET['idPromotion'];		
				if(true){ // Test de saisie
					Type_Cours::supprimer_type_cours($_GET['supprimer_type_cours']);
					$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutTypeCours&supprimer_type_cours=1";	
				}
			}
		}
		
		public static function page_administration($nombreTabulations = 0){
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			Type_Cours::formulaireAjoutTypeCours($nombreTabulations + 1);
			echo "$tab<h1>Liste des types de cours</h1>\n";
			Type_Cours::liste_type_cours_to_table($_GET['idPromotion'], $nombreTabulations + 1);
		}
		
		public function appartenance_typeSalle_typeCours($idType_Cours, $idType_Salle) {
			$appartient = 0;
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM ".Appartient_TypeSalle_TypeCours::$nomTable." WHERE idTypeCours = ? AND idTypeSalle = ?");
				$req->execute(
					array (
						$idType_Cours,
						$idType_Salle
					)
				);
				$ligne = $req->fetch();
				$appartient = $ligne['nb'];
	
				$req->closeCursor();
				return $appartient;
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}			
		}
		
		public function toString(){
			$string = "";
			foreach(Type_Cours::$attributs as $att){
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Type_Cours::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Type_Cours::$nomTable);
		}
	}
