<?php
	class Groupe_Etudiants{
		
		public static $nomTable = "Groupe_Etudiants";
		
		public static $attributs = Array(
			"nom",
			"identifiant"
		);
		
		public function getNom(){ return $this->nom; }
		public function getIdentifiant(){ return $this->identifiant; }
		
		
		public function Groupe_Etudiants($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Groupe_Etudiants::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Groupe_Etudiants::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function getNbreGroupeEtudiants($idPromotion){ 
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Groupe_Etudiants::$nomTable." WHERE idPromotion=?");
				$req->execute(
					array($idPromotion)
				);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne["nb"];
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function ajouter_groupeEtudiants($idPromotion, $nom, $identifiant){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Groupe_Etudiants::$nomTable." VALUES(?, ?, ?, ?)");
				
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
		
		public static function modifier_groupeEtudiants($idGroupeEtudiants, $idPromotion, $nom, $identifiant) {
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Groupe_Etudiants::$nomTable." SET nom=?, identifiant=?, idPromotion=? WHERE id=?;");
				$req->execute(
					Array(
						$nom, 
						$identifiant, 
						$idPromotion,
						$idGroupeEtudiants
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function supprimer_groupeEtudiants($idGroupeEtudiants){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Groupe_Etudiants::$nomTable." WHERE id=?;");
				$req->execute(
					Array(
						$idGroupeEtudiants
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function liste_groupeEtudiants($idPromotion){
			$listeId = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Groupe_Etudiants::$nomTable." WHERE idPromotion=? ORDER BY nom");
				$req->execute(
					array($idPromotion)
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
		
		public static function liste_groupeEtudiants_to_table($idPromotion, $administration, $nombreTabulations = 0){
			$liste_groupeEtudiants = Groupe_Etudiants::liste_groupeEtudiants($idPromotion);
			$nbreGroupeEtudiants = Groupe_Etudiants::getNbreGroupeEtudiants($idPromotion);
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			
			if ($nbreGroupeEtudiants == 0) {
				echo "$tab<h2>Aucun groupe d'étudiants n'a été créés pour cette promotion</h2>\n";
			}
			else {			
				echo "$tab<table class=\"listeCours\">\n";
				
				echo "$tab\t<tr class=\"fondGrisFonce\">\n";
				echo "$tab\t\t<th>Nom</th>\n";
				echo "$tab\t\t<th>Identifiant</th>\n";
				
			
				if($administration){
					echo "$tab\t\t<th>Actions</th>\n";
				}
				echo "$tab\t</tr>\n";
				
				$cpt = 0;
				foreach($liste_groupeEtudiants as $idGroupeEtudiants){
					$Groupe_Etudiants = new Groupe_Etudiants($idGroupeEtudiants);
					
					if($cpt == 0){ $couleurFond="fondBlanc"; }
					else{ $couleurFond="fondGris"; }
					$cpt++; $cpt %= 2;
					
					echo "$tab\t<tr class=\"$couleurFond\">\n";
					foreach(Groupe_Etudiants::$attributs as $att){
						echo "$tab\t\t<td>".$Groupe_Etudiants->$att."</td>\n";
					}
					
					if($administration){
						$pageModification = "./index.php?idPromotion={$_GET['idPromotion']}&amp;page=ajoutGroupeEtudiants&amp;modifier_groupeEtudiants=$idGroupeEtudiants";
						$pageSuppression = "./index.php?idPromotion={$_GET['idPromotion']}&amp;page=ajoutGroupeEtudiants&amp;supprimer_groupeEtudiants=$idGroupeEtudiants";
						echo "$tab\t\t<td><img src=\"../images/modify.png\" style=\"cursor:pointer;\" onClick=\"location.href='{$pageModification}'\">  <img src=\"../images/delete.png\" style=\"cursor:pointer;\" OnClick=\"location.href=confirm('Voulez vous vraiment supprimer ce groupe d\'étudiants ?') ? '{$pageSuppression}' : ''\"/>\n";
					}
					echo "$tab\t</tr>\n";
				}
				
				echo "$tab</table>\n";
			}
		}
		
		public function formulaireAjoutGroupeEtudiants($idPromotion, $nombresTabulations = 0){
			$tab = ""; while($nombresTabulation = 0){ $tab .= "\t"; $nombresTabulations--; }
			
			if(isset($_GET['modifier_groupeEtudiants'])){ 
				$titre = "Modifier un groupe d'étudiant";
				$Groupe_Etudiants = new Groupe_Etudiants($_GET['modifier_groupeEtudiants']);
				$nomModif = "value=\"{$Groupe_Etudiants->getNom()}\"";
				$identifiantModif = "value=\"{$Groupe_Etudiants->getIdentifiant()}\"";
			}
			else{
				$titre = "Ajouter un groupe d'étudiant";
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
			if(isset($_GET['modifier_groupeEtudiants'])){ $valueSubmit = "Modifier le groupe d'étudiant"; }else{ $valueSubmit = "Ajouter le groupe d'étudiant"; }
			echo "$tab\t\t\t<td><input type=\"submit\" name=\"validerAjoutGroupeEtudiants\" value=\"{$valueSubmit}\" style=\"cursor:pointer;\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";			
		}		
		
		public static function prise_en_compte_formulaire(){
			if(isset($_POST['validerAjoutGroupeEtudiants'])){
				$nom = $_POST['nom'];
				$identifiant = $_POST['identifiant'];
				if(true){ // Test de saisie
					$idPromotion = $_GET['idPromotion'];					
					if(isset($_GET['modifier_groupeEtudiants'])){
						Groupe_Etudiants::modifier_groupeEtudiants($_GET['modifier_groupeEtudiants'], $idPromotion, $nom, $identifiant);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutGroupeEtudiants&modification_groupeEtudiants=1";
					} 
					else{
						// C'est un nouveau groupe d'étudiants
						Groupe_Etudiants::ajouter_groupeEtudiants($idPromotion, $nom, $identifiant);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutGroupeEtudiants&ajout_groupeEtudiants=1";
					}
					header("Location: $pageDestination");
				}
			}
		}
		
		public static function prise_en_compte_suppression(){
			if(isset($_GET['supprimer_groupeEtudiants'])){	
				$idPromotion = $_GET['idPromotion'];	
				if(true){ // Test de saisie
					Groupe_Etudiants::supprimer_groupeEtudiants($_GET['supprimer_groupeEtudiants']);
					$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutGroupeEtudiants&supprimer_groupeEtudiants=1";	
				}
			}
		}		
		
		public static function page_administration($nombreTabulations = 0){
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }	
			$idPromotion = $_GET['idPromotion'];
			if(isset($_GET['ajout_groupeEtudiants'])){
				echo "$tab<p class=\"notificationAdministration\">Le groupe d'étudiants a bien été ajouté</p>";
			}
			if(isset($_GET['modification_groupeEtudiants'])){
				echo "$tab<p class=\"notificationAdministration\">Le groupe d'étudiants a bien été modifié</p>";
			}
			Groupe_Etudiants::formulaireAjoutGroupeEtudiants($idPromotion, $nombreTabulations + 1);
			echo "$tab<h1>Liste des groupes d'étudiants</h1>\n";
			Groupe_Etudiants::liste_groupeEtudiants_to_table($idPromotion, $nombreTabulations + 1);
		}
		
		public function toString(){
			$string = "";
			foreach(Groupe_Etudiants::$attributs as $att){
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Groupe_Etudiants::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Groupe_Etudiants::$nomTable);
		}
	}
