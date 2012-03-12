<?php
	class Inscription {
		
		public static $nomTable = "Inscription";
		
		public static $attributs = Array(
			"idUE",
			"idEtudiant"
		);
		
		public function Inscription(){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Inscription::$nomTable);
				$req->execute();
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Inscription::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function liste_inscription() {
			$idPromotion = $_GET['idPromotion'];
			$liste_UE = UE::liste_UE_promotion($idPromotion);
			$nbre_UE = UE::getNbreUEPromotion($idPromotion);
			$liste_etudiants = V_Infos_Etudiant::liste_etudiant($idPromotion);
			$nbre_etudiants = V_Infos_Etudiant::getNbreEtudiants($idPromotion);
			$tab="";
			
			echo "$tab<h1>Gestion des inscriptions des étudiants aux UE</h1>\n";
			
			if ( ($nbre_etudiants == 0) || ($nbre_UE == 0) ){
				echo "$tab<h2>Aucun étudiant n'a été inscrit et/ou aucune UE n'a été créé pour cette promotion</h2>\n";
			}
			else {
				echo "$tab<table name=\"tabInscription\" class=\"listeCours\">\n";
				
				echo "$tab\t<tr class=\"fondGrisFonce\">\n";
				echo "$tab\t\t<th class=\"fondBlanc\" colspan='2' rowspan='2'></th>\n";
				echo "$tab\t\t<th rowspan='2'>Nbre<br/>d'UE</th>\n";
				echo "$tab\t\t<th colspan='{$nbre_UE}'>Nom des UE</th>\n";
				echo "$tab\t</tr>\n";
				
				echo "$tab\t<tr class=\"fondGrisFonce\">\n";
				foreach($liste_UE as $idUE){
					$UE = new UE($idUE);
					echo "$tab\t\t<td>".$UE->getNom()."</td>\n";
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
					echo "$tab\t\t<td class=\"$couleurFond\" name=\"nbreUE_{$idEtudiant}\" style=\"text-align:center;\">".Inscription::nbre_UE_inscrit($idEtudiant)."</td>\n";
					
					foreach($liste_UE as $idUE){
						$UE = new UE($idUE);

						$nom_case = "case_UE_".$idUE;
						if (Inscription::est_inscrit($idEtudiant, $idUE))
							$checked = "checked = \"checked\"" ;
						else
							$checked = "";
								
						echo "$tab\t\t<td class=\"$couleurFond\"><input type=\"checkbox\" name= \"{$nom_case}\" value=\"{$nom_case}\" onclick=\"inscription_UE({$idEtudiant},{$idUE},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
						
					}
					echo "$tab\t</tr>\n";
				}
				
				echo "$tab\t<tr>\n";
				echo "$tab\t<th class=\"fondGrisFonce\" colspan='3'>Toute la promotion</th>\n";
				foreach($liste_UE as $idUE){
					$UE = new UE($idUE);

					$nom_case = "case_promotion_".$idUE;
					if ($nbre_etudiants == Inscription::est_inscrit_promotion($idUE))
						$checked = "checked = \"checked\"" ;
					else
						$checked = "";		
						
					echo "$tab\t\t<td class=\"fondGrisFonce\"><input type=\"checkbox\" name= \"{$nom_case}\" value=\"{$nom_case}\" onclick=\"inscription_UE_promotion({$idPromotion},{$idUE},{$nbre_etudiants},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
					
				}
				echo "$tab\t</tr>\n";
				echo "$tab</table>\n";
			}
		}
		
		public function est_inscrit ($idEtudiant, $idUE) {
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM ".Inscription::$nomTable." WHERE idUE=? AND idEtudiant=?");
				$req->execute(
					array(
						$idUE,
						$idEtudiant
					)
				);
				$ligne = $req->fetch();
				
				$estInscrit = $ligne["nb"];
				$req->closeCursor();
				
				return $estInscrit;
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}		
		}
		
		public function est_inscrit_promotion ($idUE) {
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM ".Inscription::$nomTable." WHERE idUE=?");
				$req->execute(
					array(
						$idUE
					)
				);
				$ligne = $req->fetch();
				
				$estInscrit = $ligne["nb"];
				$req->closeCursor();
				
				return $estInscrit;
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}		
		}		
		
		public function nbre_UE_inscrit ($idEtudiant) {
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(idUE) AS nb FROM ".Inscription::$nomTable." WHERE idEtudiant=?");
				$req->execute(
					array($idEtudiant)
				);
				$ligne = $req->fetch();
				
				$nbUE = $ligne["nb"];
				$req->closeCursor();
				
				return $nbUE;
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function nbre_etudiant_inscrit ($idUE) {
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(idEtudiant) AS nb FROM ".Inscription::$nomTable." WHERE idUE=?");
				$req->execute(
					array($idUE)
				);
				$ligne = $req->fetch();
				
				$nbUE = $ligne["nb"];
				$req->closeCursor();
				
				return $nbUE;
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function toString(){
			$string = "";
			foreach(Inscription::$attributs as $att){
				$string .= "$att".":".$this->$att." ";
			}
			return $string;
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Inscription::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Inscription::$nomTable);
		}
	}
