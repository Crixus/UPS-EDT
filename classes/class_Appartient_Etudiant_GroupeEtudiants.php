<?php
	class Appartient_Etudiant_GroupeEtudiants{
		
		public static $nomTable = "Appartient_Etudiant_GroupeEtudiants";
		
		public static $attributs = Array(
			"idEtudiant",
			"idGroupeEtudiants"
		);
		
		public function Appartient_Etudiant_GroupeEtudiants(){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Appartient_Etudiant_GroupeEtudiants::$nomTable);
				$req->execute();
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Appartient_Etudiant_GroupeEtudiants::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function liste_appartenance_etudiant_groupeEtudiants() {
			$idPromotion = $_GET['idPromotion'];
			$liste_groupeEtudiants = Groupe_Etudiants::liste_groupeEtudiants($idPromotion);
			$nbreGroupeEtudiants = Groupe_Etudiants::getNbreGroupeEtudiants($idPromotion);
			$liste_etudiants = V_Infos_Etudiant::liste_etudiant($idPromotion);
			$nbre_etudiants = V_Infos_Etudiant::getNbreEtudiants($idPromotion);
			$tab="";
			
			echo "$tab<h1>Gestion des groupes d'étudiants</h1>\n";
			
			if ($nbre_etudiants == 0) {
				echo "$tab<h2>Aucun groupe d'étudiants n'a été créé pour cette promotion et aucun etudiants n'y a été inscrits</h2>\n";
			}
			else {
				echo "$tab<table name=\"tabGestionGroupeEtudiants\" class=\"listeCours\">\n";
				
				echo "$tab\t<tr class=\"fondGrisFonce\">\n";
				echo "$tab\t\t<th class=\"fondBlanc\" colspan='2' rowspan='2'></th>\n";
				echo "$tab\t\t<th colspan='{$nbreGroupeEtudiants}'>Nom des groupes d'étudiants</th>\n";
				echo "$tab\t</tr>\n";
				
				echo "$tab\t<tr class=\"fondGrisFonce\">\n";
				foreach($liste_groupeEtudiants as $idGroupeEtudiants){
					$Groupe_Etudiants = new Groupe_Etudiants($idGroupeEtudiants);
					echo "$tab\t\t<td>".$Groupe_Etudiants->getNom()."</td>\n";
				}
				echo "$tab\t</tr>\n";
				
				echo "$tab\t<tr>\n";
				echo "$tab\t<th class=\"fondGrisFonce\" rowspan='{$nbre_etudiants}'>Nom<br/>des<br/>étudiants</th>\n";
				$cpt = 0;
				foreach($liste_etudiants as $idEtudiant){
					if($cpt == 0){ $couleurFond="fondBlanc"; }
					else{ $couleurFond="fondGris"; }
					$cpt++; $cpt %= 2;
					
					$Etudiant = new Etudiant($idEtudiant);
					echo "$tab\t\t<td class=\"fondGrisFonce\">".$Etudiant->getPrenom()." ".$Etudiant->getNom()."</td>\n";
					
					foreach($liste_groupeEtudiants as $idGroupeEtudiants){
						$Groupe_Etudiants = new Groupe_Etudiants($idGroupeEtudiants);

						$nom_case = "case_GroupeEtudiants_".$idGroupeEtudiants;
						if (Appartient_Etudiant_GroupeEtudiants::appartenance_etudiant_groupeEtudiants($idEtudiant, $idGroupeEtudiants))
							$checked = "checked = \"checked\"" ;
						else
							$checked = "";
								
						echo "$tab\t\t<td class=\"$couleurFond\"><input type=\"checkbox\" name= \"{$nom_case}\" value=\"{$nom_case}\" onclick=\"appartenance_etudiant_groupeEtudiants({$idEtudiant},{$idGroupeEtudiants},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
						
					}
					echo "$tab\t</tr>\n";
				}
				
				echo "$tab\t<tr>\n";
				echo "$tab\t<th class=\"fondGrisFonce\" colspan='2'>Toute la promotion</th>\n";
				foreach($liste_groupeEtudiants as $idGroupeEtudiants){
					$Groupe_Etudiants = new Groupe_Etudiants($idGroupeEtudiants);

					$nom_case = "case_promotion_".$idGroupeEtudiants;
					if ($nbre_etudiants == Appartient_Etudiant_GroupeEtudiants::appartenance_promotion_groupeEtudiants($idGroupeEtudiants))
						$checked = "checked = \"checked\"" ;
					else
						$checked = "";		
						
					echo "$tab\t\t<td class=\"fondGrisFonce\"><input type=\"checkbox\" name= \"{$nom_case}\" value=\"{$nom_case}\" onclick=\"appartenance_promotion_groupeEtudiants({$idPromotion},{$idGroupeEtudiants},{$nbre_etudiants},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
					
				}
				echo "$tab\t</tr>\n";
				echo "$tab</table>\n";
			}
		}
		
		public function appartenance_etudiant_groupeEtudiants($idEtudiant, $idGroupeEtudiants) {
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM ".Appartient_Etudiant_GroupeEtudiants::$nomTable." WHERE idEtudiant=? AND idGroupeEtudiants=?");
				$req->execute(
					array(
						$idEtudiant,
						$idGroupeEtudiants
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
		
		public function appartenance_promotion_groupeEtudiants($idGroupeEtudiants) {
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM ".Appartient_Etudiant_GroupeEtudiants::$nomTable." WHERE idGroupeEtudiants=?");
				$req->execute(
					array(
						$idGroupeEtudiants
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
			foreach(Appartient_Etudiant_GroupeEtudiants::$attributs as $att){
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Appartient_Etudiant_GroupeEtudiants::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Appartient_Etudiant_GroupeEtudiants::$nomTable);
		}
	}
