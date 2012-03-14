<?php
	class Publication{
		
		public static $nomTable = "Publication";
		
		public static $attributs = Array(
			"idGroupeEtudiants",
			"idGroupeCours"
		);
		
		public function Publication(){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Publication::$nomTable);
				$req->execute();
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Publication::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function liste_publication() {
			$idPromotion = $_GET['idPromotion'];
			$liste_groupeCours = Groupe_Cours::liste_groupeCours($idPromotion);
			$nbre_groupeCours = Groupe_Cours::getNbreGroupeCours($idPromotion);
			$liste_groupeEtudiants = Groupe_Etudiants::liste_groupeEtudiants($idPromotion);
			$nbre_groupeEtudiants = Groupe_Etudiants::getNbreGroupeEtudiants($idPromotion);
			$tab="";
			
			echo "$tab<h1>Gestion de la publication</h1>\n";
			if ( ($nbre_groupeCours == 0) || ($nbre_groupeEtudiants == 0) ) {
				echo "$tab<h2>Aucun groupe de cours et/ou aucun groupe d'étudiants n'a été crées pour cette promotion</h2>\n";
			}
			else {
				echo "$tab<table class=\"table_liste_administration\">\n";
				
				echo "$tab\t<tr class=\"fondGrisFonce\">\n";
				echo "$tab\t\t<th class=\"fondBlanc\" colspan='2' rowspan='2' border='none'></th>\n";
				echo "$tab\t\t<th colspan='{$nbre_groupeEtudiants}'>Nom des groupes d'étudiants</th>\n";
				echo "$tab\t</tr>\n";
				
				echo "$tab\t<tr class=\"fondGrisFonce\">\n";
				foreach($liste_groupeEtudiants as $idGroupeEtudiants){
					$Groupe_Etudiants = new Groupe_Etudiants($idGroupeEtudiants);
					echo "$tab\t\t<td>".$Groupe_Etudiants->getNom()."</td>\n";
				}
				echo "$tab\t</tr>\n";
				
				echo "$tab\t<tr>\n";
				echo "$tab\t<th class=\"fondGrisFonce\" rowspan='{$nbre_groupeCours}'>Nom des groupes <br/>de cours</th>\n";
				$cpt = 0;
				foreach($liste_groupeCours as $idGroupeCours){
					if($cpt == 0){ $couleurFond="fondBlanc"; }
					else{ $couleurFond="fondGris"; }
					$cpt++; $cpt %= 2;
					
					$Groupe_Cours = new Groupe_Cours($idGroupeCours);
					echo "$tab\t\t<td class=\"fondGrisFonce\">".$Groupe_Cours->getNom()."</td>\n";
					foreach($liste_groupeEtudiants as $idGroupeEtudiants){
						$Groupe_Etudiants = new Groupe_Etudiants($idGroupeEtudiants);

						$nom_case = "case_".$idGroupeCours."_".$idGroupeEtudiants;
						if (Publication::appartenance_publication ($idGroupeCours, $idGroupeEtudiants))
							$checked = "checked = \"checked\"" ;
						else
							$checked = "";
								
						echo "$tab\t\t<td class=\"$couleurFond\"><input type=\"checkbox\" name= \"{$nom_case}\" value=\"{$nom_case}\" onclick=\"appartenance_groupeCours_groupeEtudiants({$idGroupeCours},{$idGroupeEtudiants},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
						
					}
					echo "$tab\t</tr>\n";
				}
				echo "$tab</table>\n";
			}
		}
		
		public function appartenance_publication ($idGroupe_Cours, $idGroupe_Etudiants) {
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM ".Publication::$nomTable." WHERE idGroupeEtudiants=? AND idGroupeCours=?");
				$req->execute(
					array(
						$idGroupe_Etudiants,
						$idGroupe_Cours
					)
				);
				$ligne = $req->fetch();
				
				$appartient = $ligne["nb"];
				$req->closeCursor();
				
				return $appartient;
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		
		}
		
		
		public function toString(){
			$string = "";
			foreach(Publication::$attributs as $att){
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Publication::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Publication::$nomTable);
		}
	}
