<?php
	class Appartient_Cours_GroupeCours{
		
		public static $nomTable = "Appartient_Cours_GroupeCours";
		
		public static $attributs = Array(
			"idCours",
			"idGroupeCours"
		);
		
		public function Appartient_Cours_GroupeCours(){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Appartient_Cours_GroupeCours::$nomTable);
				$req->execute();
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Appartient_Cours_GroupeCours::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function liste_appartenance_cours_groupeCours() {
			$idPromotion = $_GET['idPromotion'];
			$liste_groupeCours = Groupe_Cours::liste_groupeCours($idPromotion);
			$nbreGroupeCours = Groupe_Cours::getNbreGroupeCours($idPromotion);
			$liste_cours = V_Infos_Cours::liste_cours($idPromotion);
			$nbre_cours = V_Infos_Cours::getNbreCours($idPromotion);
			$tab="";
			
			echo "$tab<h1>Gestion des groupes de cours</h1>\n";
			
			if ( ($nbre_cours == 0) || ($nbreGroupeCours == 0) ) {
				echo "$tab<h2>Aucun groupe de cours et/ou aucun cours n'a été créé pour cette promotion</h2>\n";
			}
			else {
				echo "$tab<table name=\"tabGestionGroupeCours\" class=\"table_liste_administration\">\n";
				
				echo "$tab\t<tr class=\"fondGrisFonce\">\n";
				echo "$tab\t\t<th class=\"fondBlanc\" colspan='2' rowspan='2'></th>\n";
				echo "$tab\t\t<th colspan='{$nbreGroupeCours}'>Nom des groupes de cours</th>\n";
				echo "$tab\t</tr>\n";
				
				echo "$tab\t<tr class=\"fondGrisFonce\">\n";
				foreach($liste_groupeCours as $idGroupeCours){
					$Groupe_Cours = new Groupe_Cours($idGroupeCours);
					echo "$tab\t\t<td>".$Groupe_Cours->getNom()."</td>\n";
				}
				echo "$tab\t</tr>\n";
				
				echo "$tab\t<tr>\n";
				echo "$tab\t<th class=\"fondGrisFonce\" rowspan='{$nbre_cours}'>Cours</th>\n";
				$cpt = 0;
				foreach($liste_cours as $idCours){
					if($cpt == 0){ $couleurFond="fondBlanc"; }
					else{ $couleurFond="fondGris"; }
					$cpt++; $cpt %= 2;
					
					$Cours = new V_Infos_Cours($idCours);
					echo "$tab\t\t<td class=\"fondGrisFonce\">".$Cours->getNomUE()." (".$Cours->getNomTypeCours()." / ".$Cours->getNomBatiment()."-".$Cours->getNomSalle().")</td>\n";
					
					foreach($liste_groupeCours as $idGroupeCours){
						$Groupe_Cours = new Groupe_Cours($idGroupeCours);

						$nom_case = "case_GroupeCours_".$idGroupeCours;
						if (Appartient_Cours_GroupeCours::appartenance_cours_groupeCours($idCours, $idGroupeCours))
							$checked = "checked = \"checked\"" ;
						else
							$checked = "";
								
						echo "$tab\t\t<td class=\"$couleurFond\"><input type=\"checkbox\" name= \"{$nom_case}\" value=\"{$nom_case}\" onclick=\"appartenance_cours_groupeCours({$idCours},{$idGroupeCours},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
						
					}
					echo "$tab\t</tr>\n";
				}
				
				echo "$tab\t<tr>\n";
				echo "$tab\t<th class=\"fondGrisFonce\" colspan='2'>Tous les cours</th>\n";
				foreach($liste_groupeCours as $idGroupeCours){
					$Groupe_Cours = new Groupe_Cours($idGroupeCours);

					$nom_case = "case_promotion_".$idGroupeCours;
					if ($nbre_cours == Appartient_Cours_GroupeCours::appartenance_promotion_groupe_Cours($idGroupeCours))
						$checked = "checked = \"checked\"" ;
					else
						$checked = "";		
						
					echo "$tab\t\t<td class=\"fondGrisFonce\"><input type=\"checkbox\" name= \"{$nom_case}\" value=\"{$nom_case}\" onclick=\"appartenance_promotion_groupeCours({$idPromotion},{$idGroupeCours},{$nbre_cours},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
					
				}
				echo "$tab\t</tr>\n";
				echo "$tab</table>\n";
			}
		}
		
		public function appartenance_cours_groupeCours($idCours, $idGroupeCours) {
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM ".Appartient_Cours_GroupeCours::$nomTable." WHERE idCours=? AND idGroupeCours=?");
				$req->execute(
					array(
						$idCours,
						$idGroupeCours
					)
				);
				$ligne = $req->fetch();
				
				$appartenance = $ligne["nb"];
				$req->closeCursor();
				
				return $appartenance;
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function appartenance_promotion_groupe_Cours($idGroupeCours) {
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM ".Appartient_Cours_GroupeCours::$nomTable." WHERE idGroupeCours=?");
				$req->execute(
					array(
						$idGroupeCours
					)
				);
				$ligne = $req->fetch();
				
				$appartenance = $ligne["nb"];
				$req->closeCursor();
				
				return $appartenance;
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function toString(){
			$string = "";
			foreach(Appartient_Cours_GroupeCours::$attributs as $att){
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Appartient_Cours_GroupeCours::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Appartient_Cours_GroupeCours::$nomTable);
		}
	}
