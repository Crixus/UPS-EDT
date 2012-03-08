<?php
	class V_Liste_Salles{
		
		public static $nomTable = "V_Liste_Salles";
		
		public static $attributs = Array(
			"nomBatiment",
			"nomSalle",
			"capacite",
			"lat",
			"lon"
		);
		
		public function getId(){return $this->id;}
		public function getNomSalle(){return $this->nomSalle;}
		public function getNomBatiment(){return $this->nomBatiment;}
		
		public function V_Liste_Salles($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".V_Liste_Salles::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(V_Liste_Salles::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function liste_salles(){
			$listeId = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".V_Liste_Salles::$nomTable." ORDER BY nomBatiment, nomSalle");
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
		
		public static function liste_salles_appartenant_typeCours($idTypeCours){
			$listeId = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM V_Liste_Salles 
					JOIN Appartient_Salle_TypeSalle ON Appartient_Salle_TypeSalle.idSalle = V_Liste_Salles.id
					JOIN Appartient_TypeSalle_TypeCours ON Appartient_TypeSalle_TypeCours.idTypeSalle = Appartient_Salle_TypeSalle.idTypeSalle
					WHERE Appartient_TypeSalle_TypeCours.idTypeCours = ?
					GROUP BY id
					ORDER BY nomBatiment, nomSalle");
				$req->execute(
					array($idTypeCours)
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
					echo "$tab\t\t<td><a href=\"$pageModification\"><img alt=\"icone modification\" src=\"../images/modify.png\"></a><a href=\"$pageSuppression\"><img alt=\"icone suppression\" src=\"../images/delete.png\" /></a>\n";
				}
				echo "$tab\t</tr>\n";
			}
			
			echo "$tab</table>\n";
		}
	}
