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
		
		public static function liste_id_salles(){
			$listeIdSalle = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".Salle::$nomTable." ORDER BY nomBatiment, nom");
				$req->execute(
					Array()
					);
				while($ligne = $req->fetch()){
					array_push($listeIdSalle, $ligne['id']);
				}
				$req->closeCursor();
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeIdSalle;
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
		
		public function formulaireAjoutModificationSalle($nombreTabulations = 0){
			$tab = ""; for($i = 0 ; $i < $nombreTabulations ; $i++){ $tab .= "\t"; }
			$liste_nom_batiment = Salle::listeNomBatiment();
			
			if(isset($_GET['modifier_salle'])){ 
				$titre = "Modifier une salle";
				$Salle = new Salle($_GET['modifier_salle']);
				$nomModif = "value=\"{$Salle->getNom()}\"";
				$nomBatimentModif = $Salle->getNomBatiment();
				$capaciteModif = "value=\"{$Salle->getCapacite()}\"";
				$valueSubmit = "Modifier la salle";
				$nameSubmit = "validerModificationSalle";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_salle']}\" />";
				$lienAnnulation = "index.php?page=ajoutSalle";
				if(isset($_GET['idPromotion'])){
					$lienAnnulation .= "&amp;idPromotion={$_GET['idPromotion']}";
				}
			}
			else{
				$titre = "Ajouter une salle";
				$nomModif = (isset($_POST['nom'])) ? "value=\"{$_POST['nom']}\"" : "value=\"\"";
				$nomBatimentModif = (isset($_POST['nomBatiment'])) ? "value=\"{$_POST['nomBatiment']}\"" : "value=\"\"";
				$capaciteModif = (isset($_POST['capacite'])) ? "value=\"{$_POST['capacite']}\"" : "";
				$valueSubmit = "Ajouter la salle";
				$nameSubmit = "validerAjoutSalle";
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
			echo "$tab\t\t\t<td>$hidden<input type=\"submit\" name=\"$nameSubmit\" value=\"$valueSubmit\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";			
		}	
		
		public static function prise_en_compte_formulaire(){
			global $messages_notifications, $messages_erreurs;
			if(isset($_POST['validerAjoutSalle'])){
				$nom = $_POST['nom'];
				$capacite = $_POST['capacite'];
				$nomBatiment = $_POST['nomBatiment'];
				$nom_correct = true; // Pas de vérifications spéciales pour un nom de salle
				$capacite_correct = PregMatch::est_nombre($capacite);
				$nomBatiment_correct = Batiment::existe_nom_batiment($nomBatiment);
				$salle_inexistante = !Salle::existe_salle_nomSalle_nomBatiment($nom, $nomBatiment);
				if($nom_correct && $capacite_correct && $nomBatiment_correct && $salle_inexistante){
					Salle::ajouter_salle($nom, $nomBatiment, $capacite);
					array_push($messages_notifications, "La salle a bien été ajouté");
				}
				else{
					array_push($messages_erreurs, "La saisie n'est pas correcte");
				}
			}
			else if(isset($_POST['validerModificationSalle'])){
				$id = $_POST['id'];
				$nom = $_POST['nom'];
				$capacite = $_POST['capacite'];
				$nomBatiment = $_POST['nomBatiment'];
				$id_correct = Salle::existe_salle($id);
				$nom_correct = true; 
				$capacite_correct = PregMatch::est_nombre($capacite);
				$nomBatiment_correct = Batiment::existe_nom_batiment($nomBatiment);
				if($id_correct && $nom_correct && $capacite_correct && $nomBatiment_correct){
					Salle::modifier_salle($_GET['modifier_salle'], $nom, $nomBatiment, $capacite);
					array_push($messages_notifications, "La salle a bien étée modifiée");
				}
				else{
					array_push($messages_erreurs, "La saisie n'est pas correcte");
				}
			}
		}
		
		public static function prise_en_compte_suppression(){
			global $messages_notifications, $messages_erreurs;
			if(isset($_GET['supprimer_salle'])){
				if(Salle::existe_salle($_GET['supprimer_salle'])){ // Test de saisie
					Salle::supprimer_salle($_GET['supprimer_salle']);
					array_push($messages_notifications, "La salle à bien été supprimée");
				}
				else{
					array_push($messages_erreurs, "La salle n'existe pas");
				}
			}
		}
		
		public static function table_administration_batiments($administration, $nombreTabulations = 0){
			$tab = ""; for($i = 0 ; $i < $nombreTabulations ; $i++){ $tab .= "\t"; }
			
			$liste_id_salles = Salle::liste_id_salles();
			$liste_type_salle = Type_Salle::liste_type_salle();
			
			echo "$tab<table class=\"table_liste_administration\">\n";
			
			echo "$tab\t<tr class=\"fondGrisFonce\">\n";
			echo "$tab\t\t<th rowspan=\"2\">Nom</th>\n";
			echo "$tab\t\t<th rowspan=\"2\">Batiment</th>\n";
			echo "$tab\t\t<th rowspan=\"2\">Salle</th>\n";
			echo "$tab\t\t<th rowspan=\"2\">Capacité</th>\n";
			echo "$tab\t\t<th colspan=\"".sizeof($liste_type_salle)."\">Type de salles</th>\n";
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
			foreach($liste_id_salles as $idSalle){
				$Salle = new Salle($idSalle);
				$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
				$lienInfosSalle = "./index.php?page=infosSalle&amp;idSalle={$Salle->getId()}";
				if(isset($_GET['idPromotion'])){
					$lienInfosSalle .= "&amp;idPromotion={$_GET['idPromotion']}";
				}
				
				echo "$tab\t<tr class=\"$couleurFond\">\n";
				echo "$tab\t\t<td>";
				echo "<a href=\"$lienInfosSalle\">{$Salle->getNomBatiment()} - {$Salle->getNom()}</a>";
				echo "</td>\n";
				echo "$tab\t\t<td>{$Salle->getNomBatiment()}</td>\n";
				echo "$tab\t\t<td>{$Salle->getNom()}</td>\n";
				echo "$tab\t\t<td>{$Salle->getCapacite()}</td>\n";
								
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
			Salle::formulaireAjoutModificationSalle($nombreTabulations);
			echo "$tab<h2>Liste des salles</h2>\n";
			Salle::table_administration_batiments($nombreTabulations);
		}
		
		public function page_informations($nombreTabulations = 0){
			$tab = ""; for($i = 0 ; $i < $nombreTabulations ; $i++){ $tab .= "\t"; }
			$Batiment = Batiment::Batiment_from_nom($this->getNomBatiment());
			echo "$tab<h2>Salle {$this->getNomBatiment()} - {$this->getNom()}</h2>\n";
			echo "$tab<h3>Informations</h3>\n";
			echo "$tab<ul>\n";
			echo "$tab\t<li>Nom : {$this->getNom()}</li>\n";
			echo "$tab\t<li>Batiment : {$this->getNomBatiment()}</li>\n";
			echo "$tab\t<li>Capacite : {$this->getCapacite()}</li>\n";
			echo "$tab</ul>\n";
			$Batiment->page_informations($nombreTabulations);
		}
	}
