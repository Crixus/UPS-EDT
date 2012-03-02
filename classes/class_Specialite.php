<?php
	class Specialite{
		
		public static $nomTable = "Specialite";
		
		public static $attributs = Array(
			"nom",
			"intitule"
		);
		
		public function getId(){ return $this->id; }
		public function getNom(){ return $this->nom; }
		public function getIntitule(){ return $this->intitule; }
		
		public function Specialite($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Specialite::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Specialite::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function ajouter_specialite($nom, $intitule, $idPromotion){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Specialite::$nomTable." VALUES(?, ?, ?, ?)");
				
				$req->execute(
					Array(
						"",
						$idPromotion,
						$nom, 
						$intitule
					)
				);			
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_specialite($idSpecialite, $nom, $intitule, $idPromotion){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Specialite::$nomTable." SET nom=?, intitule=?, idPromotion=? WHERE id=?;");
				$req->execute(
					Array(
						$nom, 
						$intitule, 
						$idPromotion,
						$idSpecialite
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function supprimer_specialite($idSpecialite){
		
			//MAJ de la table "Etudiant" on met idSpecialite à 0 pour l'idSpecialite correspondant
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Etudiant::$nomTable." SET idSpecialite = 0 WHERE idSpecialite=?;");
				$req->execute(
					Array(
						$idSpecialite
					)
				);
				
				//Suppression de la spécialité
				try{
					$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("DELETE FROM ".Specialite::$nomTable." WHERE id=?;");
					$req->execute(
						Array(
							$idSpecialite
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
		
		public function formulaireAjoutSpecialite($idPromotion, $nombresTabulations = 0){
			$tab = ""; while($nombresTabulation = 0){ $tab .= "\t"; $nombresTabulations--; }
			
			if(isset($_GET['modifier_specialite'])){ 
				$titre = "Modifier une spécialité";
				$Specialite = new Specialite($_GET['modifier_specialite']);
				$nomModif = "value=\"{$Specialite->getNom()}\"";
				$intituleModif = "value=\"{$Specialite->getIntitule()}\"";
			}
			else{
				$titre = "Ajouter une spécialité";
				$nomModif = "value=\"\"";
				$intituleModif = "value=\"\"";
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
			echo "$tab\t\t\t<td></td>\n";
			if(isset($_GET['modifier_specialite'])){ $valueSubmit = "Modifier la spécialité"; }else{ $valueSubmit = "Ajouter la spécialité"; }
			echo "$tab\t\t\t<td><input type=\"submit\" name=\"validerAjoutSpecialite\" value=\"{$valueSubmit}\" style=\"cursor:pointer\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";			
		}	
		
		public static function prise_en_compte_formulaire(){
			if(isset($_POST['validerAjoutSpecialite'])){
				$nom = $_POST['nom'];
				$intitule = $_POST['intitule'];
				if(true){ // Test de saisie
					$idPromotion = $_GET['idPromotion'];
					if(isset($_GET['modifier_specialite'])){
						Specialite::modifier_specialite($_GET['modifier_specialite'], $nom, $intitule, $idPromotion);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutSpecialite&modification_specialite=1";
					}
					else{
						// C'est une nouvelle spécialité
						Specialite::ajouter_specialite($nom, $intitule, $idPromotion);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutSpecialite&ajout_specialite=1";
					}
					header("Location: $pageDestination");
				}
			}
		}
		
		public static function prise_en_compte_suppression(){
			if(isset($_GET['supprimer_specialite'])){	
				$idPromotion = $_GET['idPromotion'];	
				if(true){ // Test de saisie
					Specialite::supprimer_specialite($_GET['supprimer_specialite']);
					$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutSpecialite&supprimer_specialite=1";	
				}
			}
		}
		
		public static function page_administration($nombreTabulations = 0){
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			if(isset($_GET['ajout_specialite'])){
				echo "$tab<p class=\"notificationAdministration\">La spécialité a bien été ajouté</p>";
			}
			if(isset($_GET['modification_specialite'])){
				echo "$tab<p class=\"notificationAdministration\">La spécialité a bien été modifié</p>";
			}
			Specialite::formulaireAjoutSpecialite($_GET['idPromotion'], $nombreTabulations + 1);
			echo "$tab<h1>Liste des spécialités</h1>\n";
			Specialite::liste_specialite_to_table($_GET['idPromotion'], $nombreTabulations + 1);
		}
		
		
		public static function liste_specialite($idPromotion){
			$listeId = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Specialite::$nomTable." WHERE idPromotion = ? ORDER BY nom");
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
		
		public static function liste_specialite_to_table($idPromotion, $administration, $nombreTabulations = 0){
			$liste_specialite = Specialite::liste_specialite($idPromotion);
			$nbSpecialite = sizeof($liste_specialite);
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			
			if ($nbSpecialite == 0) {
				echo "$tab<b>Aucune spécialité n'est enregistré pour cette promotion</b>\n";
			}
			else {
			
				echo "$tab<table class=\"listeCours\">\n";
				
				echo "$tab\t<tr class=\"fondGrisFonce\">\n";
				echo "$tab\t\t<th>Nom</th>\n";
				echo "$tab\t\t<th>Intitulé</th>\n";
				
				if($administration){
					echo "$tab\t\t<th>Actions</th>\n";
				}
				echo "$tab\t</tr>\n";
				
				$cpt = 0;
				foreach($liste_specialite as $idSpecialite){
					$Specialite = new Specialite($idSpecialite);
					
					if($cpt == 0){ $couleurFond="fondBlanc"; }
					else{ $couleurFond="fondGris"; }
					$cpt++; $cpt %= 2;
					
					echo "$tab\t<tr class=\"$couleurFond\">\n";
					foreach(Specialite::$attributs as $att){
						echo "$tab\t\t<td>".$Specialite->$att."</td>\n";
					}
					if($administration){
						$pageModification = "./index.php?idPromotion={$_GET['idPromotion']}&amp;page=ajoutSpecialite&amp;modifier_specialite=$idSpecialite";
						$pageSuppression = "./index.php?idPromotion={$_GET['idPromotion']}&amp;page=ajoutSpecialite&amp;supprimer_specialite=$idSpecialite";
						echo "$tab\t\t<td><img src=\"../images/modify.jpg\" width=\"20\" height=\"20\" style=\"cursor:pointer;\" onClick=\"location.href='{$pageModification}'\">  <img src=\"../images/delete.jpg\" width=\"20\" height=\"20\" style=\"cursor:pointer;\" OnClick=\"location.href=confirm('Voulez vous vraiment supprimer cette spécialité ?') ? '{$pageSuppression}' : ''\"/>\n";
					}
					echo "$tab\t</tr>\n";
				}
				
				echo "$tab</table>\n";
			}
		}
		
		public function toString(){
			$string = "";
			foreach(Specialite::$attributs as $att){
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Specialite::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Specialite::$nomTable);
		}
	}
