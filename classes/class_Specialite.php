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
		
		public static function existe_specialite($id){
			try{
				$pdo_Options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_Options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Specialite::$nomTable." WHERE id=?");
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
				$valueSubmit = "Modifier la spécialité"; 
				$nameSubmit = "validerModificationSpecialite";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_specialite']}\" />";
				$lienAnnulation = "index.php?page=ajoutSpecialite";
				if(isset($_GET['idPromotion'])){
					$lienAnnulation .= "&amp;idPromotion={$_GET['idPromotion']}";
				}
			}
			else{
				$titre = "Ajouter une spécialité";
				$nomModif = (isset($_POST['nom'])) ? "value=\"".$_POST['nom']."\"" : "value=\"\"";
				$intituleModif = (isset($_POST['intitule'])) ? "value=\"".$_POST['intitule']."\"" : "value=\"\"";
				$valueSubmit = "Ajouter la spécialité"; 
				$nameSubmit = "validerAjoutSpecialite";
				$hidden = "";
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
			echo "$tab\t\t\t<td><label>Intitulé</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<input name=\"intitule\" type=\"text\" required {$intituleModif}/>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td></td>\n";
			echo "$tab\t\t\t<td>$hidden<input type=\"submit\" name=\"$nameSubmit\" value=\"{$valueSubmit}\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";	

			if(isset($lienAnnulation)){echo "$tab<p><a href=\"$lienAnnulation\">Annuler modification</a></p>";}				
		}	
		
		public static function prise_en_compte_formulaire(){
			global $messages_notifications, $messages_erreurs;
			if (isset($_POST['validerAjoutSpecialite']) || isset($_POST['validerModificationSpecialite'])){
				// Vérification des champs				
				$nom = htmlentities($_POST['nom']);
				$nom_correct = PregMatch::est_nom($nom);
				$intitule = htmlentities($_POST['intitule']);
				$intitule_correct = PregMatch::est_intitule($intitule);
				
				$validation_ajout = false;
				if(isset($_POST['validerAjoutSpecialite'])){
					// Ajout d'une nouvelle spécialité
					if($nom_correct && $intitule_correct) {
						Specialite::ajouter_specialite($nom, $intitule, $_GET['idPromotion']);
						array_push($messages_notifications, "La spécialité a bien été ajouté");
						$validation_ajout = true;
					}
				}
				else {
					// Modification d'une nouvelle spécialité
					$id = htmlentities($_POST['id']); 
					$id_correct = Specialite::existe_specialite($id);
					if($id_correct && $nom_correct && $intitule_correct) {
						Specialite::modifier_specialite($_GET['modifier_specialite'], $nom, $intitule, $_GET['idPromotion']);
						array_push($messages_notifications, "La spécialité a bien été modifié");
						$validation_ajout = true;
					}				
				}
				
				// Traitement des erreurs
				if (!$validation_ajout){
					array_push($messages_erreurs, "La saisie n'est pas correcte");
					if(isset($id_correct) && !$id_correct){
						array_push($messages_erreurs, "L'id de la spécialité n'est pas correct, contacter un administrateur");
					}
					if(!$nom_correct){
						array_push($messages_erreurs, "Le nom n'est pas correct");
					}
					if(!$intitule_correct){
						array_push($messages_erreurs, "L'intitulé n'est pas correct");
					}
				}
			}			
		}
		
		public static function prise_en_compte_suppression(){
			global $messages_notifications, $messages_erreurs;
			if(isset($_GET['supprimer_specialite'])){	
				if(Specialite::existe_specialite($_GET['supprimer_specialite'])){
					// La spécialité existe
					Specialite::supprimer_specialite($_GET['supprimer_specialite']);
					array_push($messages_notifications, "La spécialité à bien été supprimé");
				}
				else{
					// La spécialité n'existe pas
					array_push($messages_erreurs, "La spécialité n'existe pas");
				}
			}
		}
		
		public static function page_administration($nombreTabulations = 0){			
			$tab = ""; for($i = 0 ; $i < $nombreTabulations ; $i++){ $tab .= "\t"; }
			Specialite::formulaireAjoutSpecialite($_GET['idPromotion'], $nombreTabulations + 1);
			echo "$tab<h2>Liste des spécialités</h2>\n";
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
			
				echo "$tab<table class=\"table_liste_administration\">\n";
				
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
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					echo "$tab\t<tr class=\"$couleurFond\">\n";
					foreach(Specialite::$attributs as $att){
						echo "$tab\t\t<td>".$Specialite->$att."</td>\n";
					}
					if($administration){
						$pageModification = "./index.php?page=ajoutSpecialite&amp;modifier_specialite=$idSpecialite";
						$pageSuppression = "./index.php?page=ajoutSpecialite&amp;supprimer_specialite=$idSpecialite";
						
						if(isset($_GET['idPromotion'])){
							$pageModification .= "&amp;idPromotion={$_GET['idPromotion']}";
							$pageSuppression .= "&amp;idPromotion={$_GET['idPromotion']}";
						}
						echo "$tab\t\t<td>";
						echo "<a href=\"$pageModification\"><img src=\"../images/modify.png\" alt=\"icone de modification\" /></a>";
						echo "<a href=\"$pageSuppression\" onclick=\"return confirm('Supprimer la specialite ?')\"><img src=\"../images/delete.png\" alt=\"icone de suppression\" /></a>";
						echo "</td>\n";
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
	}
