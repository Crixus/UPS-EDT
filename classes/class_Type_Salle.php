<?php
	class Type_Salle{
		
		public static $nomTable = "Type_Salle";
		
		public static $attributs = Array(
			"nom"
		);
		
		public function getId(){ return $this->id; }
		public function getNom(){ return $this->nom; }
		
		public function Type_Salle($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Type_Salle::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Type_Salle::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function getNbreTypeSalle(){ 
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Type_Salle::$nomTable);
				$req->execute();
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne["nb"];
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function liste_type_salle(){
			$listeId = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".Type_Salle::$nomTable." ORDER BY nom");
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
		
		public static function liste_type_salle_to_table($idPromotion, $administration, $nombreTabulations = 0){
			$liste_type_salle = Type_Salle::liste_type_salle();
			$liste_type_cours = Type_Cours::liste_id_type_cours();
			$nbre_type_cours = Type_Cours::getNbreTypeCours();
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			
			echo "$tab<table class=\"listeCours\">\n";
			
			echo "$tab\t<tr class=\"fondGrisFonce\">\n";
			echo "$tab\t\t<th rowspan='2'>Nom</th>\n";
			echo "$tab\t\t<th colspan='{$nbre_type_cours}'>Type de cours</th>\n";
			
			if($administration){
				echo "$tab\t\t<th rowspan='2'>Actions</th>\n";
			}
			echo "$tab\t</tr>\n";
			echo "$tab\t<tr class=\"fondGrisFonce\">\n";
			
			foreach($liste_type_cours as $idType_Cours) {					
				$Type_Cours = new Type_Cours($idType_Cours);
				$nomType_Cours = $Type_Cours->getNom();
				echo "$tab\t\t<th>$nomType_Cours</th>\n";
			}
			echo "$tab\t</tr>\n";
			
			$cpt = 0;
			foreach($liste_type_salle as $idTypeSalle){
				$Type_Salle = new Type_Salle($idTypeSalle);
				
				if($cpt == 0){ $couleurFond="fondBlanc"; }
				else{ $couleurFond="fondGris"; }
				$cpt++; $cpt %= 2;
				
				echo "$tab\t<tr class=\"$couleurFond\">\n";
				foreach(Type_Salle::$attributs as $att){
					echo "$tab\t\t<td>".$Type_Salle->$att."</td>\n";
				}
				
				foreach($liste_type_cours as $idTypeCours) {				
					$Type_Cours = new Type_Cours($idTypeCours);
					$nomType_Cours = $Type_Cours->getNom();
					if(Type_Cours::appartenance_typeSalle_typeCours($idTypeCours, $idTypeSalle)) 
						$checked = "checked = \"checked\"" ;
					else
						$checked = "";
					$nomCheckbox = "{$idTypeCours}_{$nomType_Cours}";
					echo "$tab\t\t<td><input type=\"checkbox\" name= \"{$idTypeCours}_{$nomType_Cours}\" value=\"{$idTypeSalle}\" onclick=\"appartenance_typeSalle_typeCours({$idTypeCours},{$idTypeSalle},this)\" style=\"cursor:pointer\" {$checked}></td>\n";
				}
				
				if($administration){
					$pageModification = "./index.php?idPromotion=$idPromotion&amp;page=ajoutTypeSalle&amp;modifier_type_salle=$idTypeSalle";
					$pageSuppression = "./index.php?idPromotion=$idPromotion&amp;page=ajoutTypeSalle&amp;supprimer_type_salle=$idTypeSalle";
					echo "$tab\t\t<td><img src=\"../images/modify.png\" style=\"cursor:pointer;\" onClick=\"location.href='{$pageModification}'\">  <img src=\"../images/delete.png\" style=\"cursor:pointer;\" OnClick=\"location.href=confirm('Voulez vous vraiment supprimer ce type de salle ?') ? '{$pageSuppression}' : ''\"/>\n";
				}
				echo "$tab\t</tr>\n";
			}
			
			echo "$tab</table>\n";
		}
		
		public static function ajouter_type_salle($nom){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Type_Salle::$nomTable." VALUES(?, ?)");
				
				$req->execute(
					Array(
						"",
						$nom
					)
				);			
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_type_salle($idTypeSalle, $nom){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Type_Salle::$nomTable." SET nom=? WHERE id=?;");
				$req->execute(
					Array(
						$nom, 
						$idTypeSalle
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function supprimer_type_salle($idTypeSalle){
			//Suppression des entrées de la table "Appartient_Salle_TypeSalle
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Appartient_Salle_TypeSalle::$nomTable." WHERE idTypeSalle=?;");
				$req->execute(
					Array(
						$idTypeSalle
					)
				);
				
				//Suppression des entrées de la table "Appartient_TypeSalle_TypeCours
				try{
					$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("DELETE FROM ".Appartient_TypeSalle_TypeCours::$nomTable." WHERE idTypeSalle=?;");
					$req->execute(
						Array(
							$idTypeSalle
						)
					);
				
					//Suppression du type de salle
					try{
						$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
						$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
						$bdd->query("SET NAMES utf8");
						$req = $bdd->prepare("DELETE FROM ".Type_Salle::$nomTable." WHERE id=?;");
						$req->execute(
							Array(
								$idTypeSalle
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
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function formulaireAjoutTypeSalle($nombresTabulations = 0){
			$tab = ""; while($nombresTabulation = 0){ $tab .= "\t"; $nombresTabulations--; }
			
			if(isset($_GET['modifier_type_salle'])){ 
				$titre = "Modifier un type de salle";
				$Type_Salle = new Type_Salle($_GET['modifier_type_salle']);
				$nomModif = "value=\"{$Type_Salle->getNom()}\"";
			}
			else{
				$titre = "Ajouter un type de salle";
				$nomModif = "";
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
			if(isset($_GET['modifier_type_salle'])){ $valueSubmit = "Modifier le type de salle"; }else{ $valueSubmit = "Ajouter le type de salle"; }
			echo "$tab\t\t\t<td><input type=\"submit\" name=\"validerAjoutTypeSalle\" value=\"{$valueSubmit}\" style=\"cursor:pointer\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";			
		}	
		
		public static function prise_en_compte_formulaire(){
			if(isset($_POST['validerAjoutTypeSalle'])){
				$nom = $_POST['nom'];
				if(true){ // Test de saisie
					$idPromotion = $_GET['idPromotion'];	
					if(isset($_GET['modifier_type_salle'])){
						Type_Salle::modifier_type_salle($_GET['modifier_type_salle'], $nom);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutTypeSalle&modification_type_salle=1";
					}
					else{
						// C'est un nouveau type de salle
						Type_Salle::ajouter_type_salle($nom);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutTypeSalle&modification_type_salle=1";
					}
					header("Location: $pageDestination");
				}
			}
		}
		
		public static function prise_en_compte_suppression(){
			if(isset($_GET['supprimer_type_salle'])){	
				$idPromotion = $_GET['idPromotion'];		
				if(true){ // Test de saisie
					Type_Salle::supprimer_type_salle($_GET['supprimer_type_salle']);
					$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutTypeSalle&supprimer_type_salle=1";	
				}
			}
		}
		
		public static function page_administration($nombreTabulations = 0){
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			if(isset($_GET['ajout_type_salle'])){
				echo "$tab<p class=\"notificationAdministration\">Le type de salle a bien été ajouté</p>";
			}
			if(isset($_GET['modification_type_salle'])){
				echo "$tab<p class=\"notificationAdministration\">Le type de salle a bien été modifié</p>";
			}
			Type_Salle::formulaireAjoutTypeSalle($nombreTabulations + 1);
			echo "$tab<h1>Liste des types de salles</h1>\n";
			Type_Salle::liste_type_salle_to_table($_GET['idPromotion'], $nombreTabulations + 1);
		}
		
		public function appartient_salle_typeSalle($idSalle, $idType_Salle) {
			$appartient = 0;
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM ".Appartient_Salle_TypeSalle::$nomTable." WHERE idSalle = ? AND idTypeSalle = ?");
				$req->execute(
					array (
						$idSalle,
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
			foreach(Type_Salle::$attributs as $att){
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Type_Salle::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Type_Salle::$nomTable);
		}
	}
