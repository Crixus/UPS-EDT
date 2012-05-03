<?php
	/** 
	 * Classe Inscription - Permet de gérer les Inscriptions des étudiants aux UE
	 */ 
	class Inscription {
		
		public static $nomTable = "Inscription";
		
		public static $attributs = Array(
			"idUE",
			"idEtudiant"
		);
		
		/**
		 * Constructeur de la classe Inscription
		 * Récupère les informations de Inscription dans la base de données depuis l'id
		 * @param $id : int id du Inscription
		 */
		public function Inscription() {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Inscription::$nomTable);
				$req->execute();
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Inscription::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Fonction utilisée pour l'affichage du tableau des inscriptions des étudiants aux UE 
		 */
		public function liste_inscription() {
			$idPromotion = $_GET['idPromotion'];
			
			//Liste des UE de la promotion
			$listeUE = UE::liste_UE_promotion($idPromotion);
			$nbre_UE = UE::getNbreUEPromotion($idPromotion);
			
			//Liste des étudiants de la promotion
			$liste_etudiants = V_Infos_Etudiant::liste_etudiant($idPromotion);
			$nbre_etudiants = V_Infos_Etudiant::getNbreEtudiants($idPromotion);
			$tab = "";
			
			if (($nbre_etudiants == 0) || ($nbre_UE == 0)) {
				echo $tab."<h2>Aucun étudiant n'a été inscrit et/ou aucune UE n'a été créé pour cette promotion</h2>\n";
			}
			else {
				echo $tab."<table name=\"tabInscription\" class=\"table_liste_administration\">\n";
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				echo $tab."\t\t<th class=\"fondBlanc\" colspan='2' rowspan='2'></th>\n";
				echo $tab."\t\t<th rowspan='2'>Nbre<br/>d'UE</th>\n";
				echo $tab."\t\t<th colspan='{$nbre_UE}'>Nom des UE</th>\n";
				echo $tab."\t</tr>\n";
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				foreach ($listeUE as $idUE) {
					$_UE = new UE($idUE);
					echo $tab."\t\t<td>".$_UE->getNom()."</td>\n";
				}
				echo $tab."\t</tr>\n";
				
				echo $tab."\t<tr>\n";
				echo $tab."\t<th class=\"fondGrisFonce\" rowspan='{$nbre_etudiants}'>Nom<br/>des<br/>étudiants</th>\n";
				$cpt = 0;
				foreach ($liste_etudiants as $idEtudiant) {
					if ($cpt == 0) { $couleurFond="fondBlanc"; }
					else { $couleurFond="fondGris"; }
					$cpt++; $cpt %= 2;
					
					$_etudiant = new Etudiant($idEtudiant);
					echo $tab."\t\t<td class=\"fondGrisFonce\">".$_etudiant->getPrenom()." ".$_etudiant->getNom()."</td>\n";
					echo $tab."\t\t<td class=\"".$couleurFond."\" name=\"nbreUE_{$idEtudiant}\" style=\"text-align:center;\">".Inscription::nbre_UE_inscrit($idEtudiant)."</td>\n";
					
					foreach ($listeUE as $idUE) {
						$_UE = new UE($idUE);

						$nom_case = "case_UE_".$idUE;
						//On test si l'étudiant est inscrit à l'UE
						if (Inscription::est_inscrit($idEtudiant, $idUE))
							$checked = "checked = \"checked\"";
						else
							$checked = "";
								
						echo $tab."\t\t<td class=\"".$couleurFond."\"><input type=\"checkbox\" name= \"{$nom_case}\" value=\"{$nom_case}\" onclick=\"inscription_UE({$idEtudiant},{$idUE},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
						
					}
					echo $tab."\t</tr>\n";
				}
				
				echo $tab."\t<tr>\n";
				echo $tab."\t<th class=\"fondGrisFonce\" colspan='3'>Toute la promotion</th>\n";
				foreach ($listeUE as $idUE) {
					$_UE = new UE($idUE);

					$nom_case = "case_promotion_".$idUE;
					
					//On test si tous les étudiants sont inscrit à l'UE
					if ($nbre_etudiants == Inscription::est_inscrit_promotion($idUE))
						$checked = "checked = \"checked\"";
					else
						$checked = "";		
						
					echo $tab."\t\t<td class=\"fondGrisFonce\"><input type=\"checkbox\" name= \"{$nom_case}\" value=\"{$nom_case}\" onclick=\"inscription_UE_promotion({$idPromotion},{$idUE},{$nbre_etudiants},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
					
				}
				echo $tab."\t</tr>\n";
				echo $tab."</table>\n";
			}
		}
		
		/**
		 * Fonction utilisée pour tester si un étudiant est inscrit à une UE
		 * @param int idEtudiant : int id de l'étudiant
		 * @param int idUE : int id de l'UE
		 * @return boolean : 1 si l'étudiant est inscrit à l'UE, 0 sinon
		 */
		public function est_inscrit ($idEtudiant, $idUE) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
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
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}		
		}
		
		/**
		 * Fonction utilisée pour tester si toute une promotion d'étudiants est inscrit à une UE
		 * @param int idUE : int id de l'UE
		 * @return boolean : 1 si toute la promotion est inscrit à l'UE, 0 sinon
		 */
		public function est_inscrit_promotion ($idUE) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
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
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}		
		}		
		
		/**
		 * Renvoi le nombre d'UE auquel l'étudiant est inscrit
		 * @param int idEtudiant : int id de l'étudiant
		 * @return int : le nombre d'UE
		 */
		public function nbre_UE_inscrit ($idEtudiant) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
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
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Renvoi le nombre d'étudiants qui est inscrit à une UE
		 * @param int idUE : int id de l'UE
		 * @return int : le nombre d'étudiants
		 */
		public function nbre_etudiant_inscrit ($idUE) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
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
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
	}
