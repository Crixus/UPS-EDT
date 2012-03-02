<?php
	class Groupe_Cours{
		
		public static $nomTable = "Groupe_Cours";
		
		public static $attributs = Array(
			"nom",
			"identifiant"
		);
		
		public function getNom(){ return $this->nom; }
		public function getIdentifiant(){ return $this->identifiant; }
		
		
		public function Groupe_Cours($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Groupe_Cours::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Groupe_Cours::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function getNbreGroupeCours(){ 
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Groupe_Cours::$nomTable);
				$req->execute();
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne["nb"];
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function ajouter_groupeCours($idPromotion, $nom, $identifiant){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Groupe_Cours::$nomTable." VALUES(?, ?, ?, ?)");
				
				$req->execute(
					Array(
						"",
						$nom, 
						$identifiant, 
						$idPromotion
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_groupeCours($idGroupeCours, $idPromotion, $nom, $identifiant) {
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Groupe_Cours::$nomTable." SET nom=?, identifiant=?, idPromotion=? WHERE id=?;");
				$req->execute(
					Array(
						$nom, 
						$identifiant, 
						$idPromotion,
						$idGroupeCours
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function supprimer_groupeCours($idGroupeCours){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Groupe_Cours::$nomTable." WHERE id=?;");
				$req->execute(
					Array(
						$idGroupeCours
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function liste_groupeCours(){
			$listeId = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Groupe_Cours::$nomTable." ORDER BY nom");
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
		
		public static function liste_groupeCours_to_table($administration, $nombreTabulations = 0){
			$liste_groupeCours = Groupe_Cours::liste_groupeCours();
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			
			echo "$tab<table class=\"listeCours\">\n";
			
			echo "$tab\t<tr class=\"fondGrisFonce\">\n";
			echo "$tab\t\t<th>Nom</th>\n";
			echo "$tab\t\t<th>Identifiant</th>\n";
			
		
			if($administration){
				echo "$tab\t\t<th>Actions</th>\n";
			}
			echo "$tab\t</tr>\n";
			
			$cpt = 0;
			foreach($liste_groupeCours as $idGroupeCours){
				$Groupe_Cours = new Groupe_Cours($idGroupeCours);
				
				if($cpt == 0){ $couleurFond="fondBlanc"; }
				else{ $couleurFond="fondGris"; }
				$cpt++; $cpt %= 2;
				
				echo "$tab\t<tr class=\"$couleurFond\">\n";
				foreach(Groupe_Cours::$attributs as $att){
					echo "$tab\t\t<td>".$Groupe_Cours->$att."</td>\n";
				}
				
				if($administration){
					$pageModification = "./index.php?idPromotion={$_GET['idPromotion']}&amp;page=ajoutGroupeCours&amp;modifier_groupeCours=$idGroupeCours";
					$pageSuppression = "./index.php?idPromotion={$_GET['idPromotion']}&amp;page=ajoutGroupeCours&amp;supprimer_groupeCours=$idGroupeCours";
					echo "$tab\t\t<td><img src=\"../images/modify.jpg\" width=\"20\" height=\"20\" style=\"cursor:pointer;\" onClick=\"location.href='{$pageModification}'\">  <img src=\"../images/delete.jpg\" width=\"20\" height=\"20\" style=\"cursor:pointer;\" OnClick=\"location.href=confirm('Voulez vous vraiment supprimer ce groupe de cours ?') ? '{$pageSuppression}' : ''\"/>\n";
				}
				echo "$tab\t</tr>\n";
			}
			
			echo "$tab</table>\n";
		}
		
		public function formulaireAjoutGroupeCours($idPromotion, $nombresTabulations = 0){
			$tab = ""; while($nombresTabulation = 0){ $tab .= "\t"; $nombresTabulations--; }
			
			if(isset($_GET['modifier_groupeCours'])){ 
				$titre = "Modifier un groupe de cours";
				$Groupe_Cours = new Groupe_Cours($_GET['modifier_groupeCours']);
				$nomModif = "value=\"{$Groupe_Cours->getNom()}\"";
				$identifiantModif = "value=\"{$Groupe_Cours->getIdentifiant()}\"";
			}
			else{
				$titre = "Ajouter un groupe de cours";
				$nomModif = "";
				$Promotion = new Promotion($idPromotion);
				$nom_promotion = $Promotion->getNom();
				$annee_promotion = $Promotion->getAnnee();
				$identifiantModif = "value=\"{$annee_promotion}-{$nom_promotion}-\"";
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
			echo "$tab\t\t\t<td><label>Identifiant</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<input name=\"identifiant\" type=\"text\" required {$identifiantModif}/> \"Année Promotion\"-\"Nom Promotion\"-\"Nom d'identifiant\"\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td></td>\n";
			if(isset($_GET['modifier_groupeCours'])){ $valueSubmit = "Modifier le groupe de cours"; }else{ $valueSubmit = "Ajouter le groupe de cours"; }
			echo "$tab\t\t\t<td><input type=\"submit\" name=\"validerAjoutGroupeCours\" value=\"{$valueSubmit}\" style=\"cursor:pointer;\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";			
		}		
		
		public static function prise_en_compte_formulaire(){
			if(isset($_POST['validerAjoutGroupeCours'])){
				$nom = $_POST['nom'];
				$identifiant = $_POST['identifiant'];
				if(true){ // Test de saisie
					$idPromotion = $_GET['idPromotion'];					
					if(isset($_GET['modifier_groupeCours'])){
						Groupe_Cours::modifier_groupeCours($_GET['modifier_groupeCours'], $idPromotion, $nom, $identifiant);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutGroupeCours&modification_groupeCours=1";
					} 
					else{
						// C'est un nouveau groupe de cours
						Groupe_Cours::ajouter_groupeCours($idPromotion, $nom, $identifiant);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutGroupeCours&ajout_groupeCours=1";
					}
					header("Location: $pageDestination");
				}
			}
		}
		
		public static function prise_en_compte_suppression(){
			if(isset($_GET['supprimer_groupeCours'])){	
				$idPromotion = $_GET['idPromotion'];	
				if(true){ // Test de saisie
					Groupe_Cours::supprimer_groupeCours($_GET['supprimer_groupeCours']);
					$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutGroupeCours&supprimer_groupeCours=1";	
				}
			}
		}
		
		public static function page_administration($nombreTabulations = 0){
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }	
			$idPromotion = $_GET['idPromotion'];
			if(isset($_GET['ajout_groupeCours'])){
				echo "$tab<p class=\"notificationAdministration\">Le groupe de cours a bien été ajouté</p>";
			}
			if(isset($_GET['modification_groupeCours'])){
				echo "$tab<p class=\"notificationAdministration\">Le groupe de cours a bien été modifié</p>";
			}
			Groupe_Cours::formulaireAjoutGroupeCours($idPromotion, $nombreTabulations + 1);
			echo "$tab<h1>Liste des groupes de cours</h1>\n";
			Groupe_Cours::liste_groupeCours_to_table($nombreTabulations + 1);
		}	
		
		public function toString(){
			$string = "";
			foreach(Groupe_Cours::$attributs as $att){
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Groupe_Cours::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Groupe_Cours::$nomTable);
		}
	}
