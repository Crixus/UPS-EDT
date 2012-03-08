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
			//Suppression des entrées de la table "Appartient_Salle_TypeSalle
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Appartient_Salle_TypeSalle::$nomTable." WHERE idSalle=?;");
				$req->execute(
					Array(
						$idSalle
					)
				);
			
				//MAJ de la table "Cours" on met idSalle à 0 pour l'idSalle correspondant
				try{
					$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("UPDATE ".Cours::$nomTable." SET idSalle = 0 WHERE idSalle=?;");
					$req->execute(
						Array(
							$idSalle
						)
					);
					
					//Suppression de la salle
					try{
						$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
						$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
						$bdd->query("SET NAMES utf8");
						$req = $bdd->prepare("DELETE FROM ".Salle::$nomTable." WHERE id=?;");
						$req->execute(
							Array(
								$idSalle
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
				if(true){ // Test de saisie
					$idPromotion = $_GET['idPromotion'];	
					if(isset($_GET['modifier_salle'])){
						Salle::modifier_salle($_GET['modifier_salle'], $nom, $nomBatiment, $capacite);
						$pageDestination = "./index.php?page=ajoutSalle&modification_salle=1";
					}
					else{
						// C'est une nouveau salle
						Salle::ajouter_salle($nom, $nomBatiment, $capacite);
						$pageDestination = "./index.php?page=ajoutSalle&ajout_salle=1";
					}
					if(isset($_GET['idPromotion'])){
						$pageDestination .= "&idPromotion={$_GET['idPromotion']}";
					}
					header("Location: $pageDestination");
				}
			}
		}
		
		public static function prise_en_compte_suppression(){
			if(isset($_GET['supprimer_salle'])){
				if(true){ // Test de saisie
					Salle::supprimer_salle($_GET['supprimer_salle']);
					$pageDestination = "./index.php?page=ajoutSalle&suppression_salle=1";	
					if(isset($_GET['idPromotion'])){
						$pageDestination .= "&idPromotion={$_GET['idPromotion']}";
					}
					header("Location : $pageDestination"); // Ne fonctionne pas ??
				}
			}
		}
		
		public static function page_administration($nombreTabulations = 0){
			$tab = ""; for($i = 0 ; $i < $nombreTabulations ; $i++){ $tab .= "\t"; }
			if(isset($_GET['ajout_salle'])){
				echo "$tab<p class=\"notificationAdministration\">La salle a bien été ajoutée</p>";
			}
			if(isset($_GET['modification_salle'])){
				echo "$tab<p class=\"notificationAdministration\">La salle a bien été modifiée</p>";
			}
			if(isset($_GET['suppression_salle'])){
				echo "$tab<p class=\"notificationAdministration\">La salle a bien été supprimée</p>";
			}
			echo "$tab<h1>Gestion des salles</h1>\n";
			Salle::formulaireAjoutSalle($nombreTabulations + 1);
			echo "$tab<h2>Liste des salles</h2>\n";
			V_Liste_Salles::liste_Salle_to_table($nombreTabulations + 1);
		}
		
		public function toUl(){
			$string = "<ul>\n";
			foreach(Salle::$attributs as $att){
				$string .= "<li>$att : ".$this->$att."</li>\n";
			}
			return "$string</ul>\n";
		}
	}
