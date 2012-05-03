<?php
	/** 
	 * Classe Cours - Permet de gerer les cours
	 */ 
	class Cours {
		
		public static $nomTable = "Cours";
		
		public static $attributs = Array(
			"id",
			"idUE",
			"idSalle",
			"idIntervenant",
			"idTypeCours",
			"tsDebut",
			"tsFin"
		);
		
		/**
		 * Getter de l'id du Cours
		 * @return int : id du Cours
		 */
		public function getId() {
			return $this->id;
		}
		
		/**
		 * Getter de idUE du Cours
		 * @return int : idUE
		 */
		public function getIdUE() {
			return $this->idUE;
		}
		
		/**
		 * Getter de idSalle du Cours
		 * @return int : idSalle
		 */
		public function getIdSalle() {
			return $this->idSalle;
		}
		
		/**
		 * Getter de idIntervenant du Cours
		 * @return int : idIntervenant
		 */
		public function getIdIntervenant() {
			return $this->idIntervenant;
		}
		
		/**
		 * Getter de idTypeCours du Cours
		 * @return int : idTypeCours
		 */
		public function getIdTypeCours() {
			return $this->idTypeCours;
		}
		
		/**
		 * Getter de TsDebut du Cours
		 * @return timestamp : TsDebut
		 */
		public function getTsDebut() {
			return $this->tsDebut;
		}
		
		/**
		 * Getter de tsFin du Cours
		 * @return timestamp : tsFin
		 */
		public function getTsFin() {
			return $this->tsFin;
		}
		
		/**
		 * Constructeur de la classe Cours
		 * Récupère les informations de Cours dans la base de données depuis l'id
		 * @param $id : int id du Cours
		 */
		public function Cours($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Cours::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Cours::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Ajouter un cours dans la base de données
		 * @param $idUE : int id de l'UE
		 * @param $idSalle : int id de la salle
		 * @param $idIntervenant : int id de l'intervenant
		 * @param $type : int id du type de cours
		 * @param $tsDebut : timestamp tsDebut du cours correspondant à la date de début du cours
		 * @param $tsFin : timestamp tsFin du cours correspondant à la date de fin du cours
		 * @param $recursivite : int correspondant au nombre de fois que le cours se créé récursivement la semaine suivante celle de la semaine courante (ex: recursivite=2 signifie que le cours créé va être également créer avec les mêmes informations pour les 2 semaines qui suivra)
		 */
		public static function ajouterCours($idUE, $idSalle, $idIntervenant, $type, $tsDebut, $tsFin, $recursivite) {
			
			/**
			* Boucle de création récursive des cours
			*/
			for ($i = 0; $i <= $recursivite; $i++) {
				try {
					$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("INSERT INTO ".Cours::$nomTable." VALUES(?, ?, ?, ?, ?, ?, ?)");
					$req->execute(
						Array(
							"",
							$idUE,
							$idSalle,
							$idIntervenant,
							$type,
							$tsDebut,
							$tsFin
						)
					);
				} catch (Exception $e) {
					echo "Erreur : ".$e->getMessage()."<br />";
				}
				
				/**
				* Modification de $tsDebut et $tsFin pour passer à la semaine suivante pour la prochaine itération
				*/
				$tsDebut = Cours::datePlusUneSemaine($tsDebut);
				$tsFin = Cours::datePlusUneSemaine($tsFin);
			}			
		}
		
		/**
		 * Fonction utilisée pour l'ajout des cours dans le cas de l'utilisation de la récursivité
		 * @param $tsDebut : timestamp d'une date
		 * @return timestam^p : retourne la date de la semaine suivante (tsDebut + 7 jours)
		 */
		public static function datePlusUneSemaine($tsDate) {
			$tsDateExplode = explode(' ', $tsDate);
			$tsDateJMA = $tsDateExplode[0];
			$tsDateHMS = $tsDateExplode[1];
			$tsDateJMA_explode = explode('-', $tsDateJMA);
			$timestamp = mktime(0, 0, 0, $tsDateJMA_explode[1], $tsDateJMA_explode[2], $tsDateJMA_explode[0]);
			$timestamp_plus_une_semaine = $timestamp + (3600 * 24 * 7); //On ajoute une semaine
			$date_jma = date('Y-m-d', $timestamp_plus_une_semaine);
			$date = $date_jma." ".$tsDateHMS;
			return $date;
		}
		
		/**
		 * Modification du cours dans la base de données
		 * @param $idCours : int id du cours a modifié
		 * @param $idUE : int id de l'UE
		 * @param $idSalle : int id de la salle
		 * @param $idIntervenant : int id de l'intervenant
		 * @param $type : int id du type de cours
		 * @param $tsDebut : timestamp tsDebut du cours correspondant à la date de début du cours
		 * @param $tsFin : timestamp tsFin du cours correspondant à la date de fin du cours
		 */
		public static function modifierCours($idCours, $idUE, $idSalle, $idIntervenant, $idTypeCours, $tsDebut, $tsFin) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Cours::$nomTable." SET idUE=?, idSalle=?, idIntervenant=?, idTypeCours=?, tsDebut=?, tsFin=? WHERE id=?;");
				$req->execute(
					Array(
						$idUE,
						$idSalle,
						$idIntervenant,
						$idTypeCours,
						$tsDebut,
						$tsFin,
						$idCours
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Supprime un cours dans la base de données
		 * @param $idCours int : id du cours a supprimé
		 */
		public static function supprimerCours($idCours) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Cours::$nomTable." WHERE id=?;");
				$req->execute(
					Array(
						$idCours
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Modifie la salle liée à un cours dans la base de données
		 * @param $idCours int : id du cours où l'on modifie la salle
		 * @param $idSalle int : id de la nouvelle salle affectée
		 */
		public static function modifierSalle($idCours, $idSalle) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Cours::$nomTable." SET idSalle=? WHERE id=?;");
				$req->execute(
					Array($idSalle, $idCours)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Modifie la salle liée à tous les cours étant affectée à la salle a modifié (modification de la table Cours dans la base de donnée)
		 * @param $idSalleSrc int : id de l'ancienne salle à changer
		 * @param $idSalleDst int : id de la nouvelle salle affectée
		 */
		public static function modifierSalleToutCours($idSalleSrc, $idSalleDst) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Cours::$nomTable." SET idSalle=? WHERE idSalle=?;");
				$req->execute(
					Array($idSalleDst, $idSalleSrc)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Fonction utilisée pour l'affichage de la liste des cours créé 
		 * @param $idPromotion int : id de la promotion sélectionnée
		 * @param $administration boolean : possibilité de modification et suppression si egal à 1
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public static function listeCoursToTable($idPromotion, $administration, $nombreTabulations = 0) {
			// Liste des cours de la promotion enregistrée dans la base de donnée
			$listeCours = V_Infos_Cours::liste_cours($idPromotion);
			$nbCours = sizeof($listeCours);
			$tab = ""; while ($nombreTabulations > 0) { $tab .= "\t"; $nombreTabulations--; }
			
			if ($nbCours == 0) {
				echo $tab."<b>Aucun cours à venir n'est enregistré pour cette promotion</b>\n";
			}
			else {
			
				echo $tab."<table class=\"table_liste_administration\">\n";
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				
				echo $tab."\t\t<th>UE</th>\n";
				echo $tab."\t\t<th>Intervenant</th>\n";
				echo $tab."\t\t<th>Type</th>\n";
				echo $tab."\t\t<th>Date</th>\n";
				echo $tab."\t\t<th>Salle</th>\n";
				
				if ($administration) {
					echo $tab."\t\t<th>Actions</th>\n";
				}
				echo $tab."\t</tr>\n";
				
				$cpt = 0;
				foreach ($listeCours as $idCours) {
					$_Cours = new V_Infos_Cours($idCours);
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					echo $tab."\t<tr class=\"".$couleurFond."\">\n";
					$cptBoucle= 0;
					$valTemp="";
					$valTemp2="";
					
					// Gestion de l'affichage des informations du cours
					foreach (V_Infos_Cours::$attributs as $att) {
						if (($cptBoucle == 1) || ($cptBoucle == 4) || ($cptBoucle == 6))
							$valTemp = $_Cours->$att;
						else if (($cptBoucle == 2) || ($cptBoucle == 7)) {
							$val = $_Cours->$att." ".$valTemp;
							$valTemp="";
							echo $tab."\t\t<td>".$val."</td>\n";
						}
						else if ($cptBoucle == 5) {
							$valTemp2 = $_Cours->$att;
							echo $tab."\t\t<td>";
							Cours::dateCours($valTemp, $valTemp2);
							echo "</td>\n";
						}
						else {
							echo $tab."\t\t<td>".$_Cours->$att."</td>\n";
						}
						$cptBoucle++;
					}
					
					// Création des liens pour la modification et la suppression des cours et gestion de l'URL 
					if ($administration) {
						$pageModification = "./index.php?page=ajoutCours&modifier_cours=$idCours";
						$pageSuppression = "./index.php?page=ajoutCours&supprimer_cours=$idCours";
						if (isset($_GET['idPromotion'])) {
							$pageModification .= "&amp;idPromotion=".$_GET['idPromotion'];
							$pageSuppression .= "&amp;idPromotion=".$_GET['idPromotion'];
						}
						
						echo $tab."\t\t<td>";
						echo "<a href=\"".$pageModification."\"><img src=\"../images/modify.png\" alt=\"icone de modification\" /></a>";
						echo "<a href=\"".$pageSuppression."\" onclick=\"return confirm('Supprimer le cours ?')\"><img src=\"../images/delete.png\" alt=\"icone de suppression\" /></a>";
						echo "</td>\n";
					}
					echo $tab."\t</tr>\n";
				}
				
				echo $tab."</table>\n";
			}
		}
		
		/**
		 * Fonction utilisée pour l'affichage de la liste des futur cours créé 
		 * @param $idPromotion int : id de la promotion sélectionnée
		 * @param $administration boolean : possibilité de modification et suppression si egal à 1
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public static function listeCoursFutursToTable($idPromotion, $administration, $nombreTabulations = 0) {
			// Liste des futurs cours de la promotion enregistrée dans la base de donnée
			$listeCours = V_Infos_Cours::liste_cours_futur($idPromotion);
			$nbCours = sizeof($listeCours);
			$tab = ""; while ($nombreTabulations > 0) { $tab .= "\t"; $nombreTabulations--; }
			
			if ($nbCours == 0) {
				echo $tab."<b>Aucun cours à venir n'est enregistré pour cette promotion</b>\n";
			}
			else {
			
				echo $tab."<table class=\"table_liste_administration\">\n";
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				
				echo $tab."\t\t<th>UE</th>\n";
				echo $tab."\t\t<th>Intervenant</th>\n";
				echo $tab."\t\t<th>Type</th>\n";
				echo $tab."\t\t<th>Date</th>\n";
				echo $tab."\t\t<th>Salle</th>\n";
				
				if ($administration) {
					echo $tab."\t\t<th>Actions</th>\n";
				}
				echo $tab."\t</tr>\n";
				
				$cpt = 0;
				foreach ($listeCours as $idCours) {
					$_Cours = new V_Infos_Cours($idCours);
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					echo $tab."\t<tr class=\"".$couleurFond."\">\n";
					$cptBoucle = 0;
					$valTemp = "";
					$valTemp2 = "";
					
					// Gestion de l'affichage des informations du cours
					foreach (V_Infos_Cours::$attributs as $att) {
						if (($cptBoucle == 1) || ($cptBoucle == 4) || ($cptBoucle == 6))
							$valTemp = $_Cours->$att;
						else if (($cptBoucle == 2) || ($cptBoucle == 7)) {
							$val = $_Cours->$att." ".$valTemp;
							$valTemp="";
							echo $tab."\t\t<td>".$val."</td>\n";
						}
						else if ($cptBoucle == 5) {
							$valTemp2 = $_Cours->$att;
							echo $tab."\t\t<td>";
							Cours::dateCours($valTemp, $valTemp2);
							echo "</td>\n";
						}
						else {
							echo $tab."\t\t<td>".$_Cours->$att."</td>\n";
						}
						$cptBoucle++;
					}
					
					// Création des liens pour la modification et la suppression des cours et gestion de l'URL 
					if ($administration) {
						$pageModification = "./index.php?page=ajoutCours&modifier_cours=".$idCours;
						$pageSuppression = "./index.php?page=ajoutCours&supprimer_cours".$idCours;
						if (isset($_GET['idPromotion'])) {
							$pageModification .= "&amp;idPromotion=".$_GET['idPromotion'];
							$pageSuppression .= "&amp;idPromotion=".$_GET['idPromotion'];
						}
						
						echo $tab."\t\t<td>";
						echo "<a href=\"".$pageModification."\"><img src=\"../images/modify.png\" alt=\"icone de modification\" /></a>";
						echo "<a href=\"".$pageSuppression."\" onclick=\"return confirm('Supprimer le cours ?')\"><img src=\"../images/delete.png\" alt=\"icone de suppression\" /></a>";
						echo "</td>\n";
					}
					echo $tab."\t</tr>\n";
				}
				
				echo $tab."</table>\n";
			}
		}
	
		/**
		 * Fonction utilisée pour l'affichage de la date d'un cours 
		 * @param $dateDebut timestamp : correspond à la date de début du cours
		 * @param $dateFin timestamp : correspond à la date de fin du cours
		 */
		public function dateCours($dateDebut, $dateFin) {
			$chaineDateDebut = explode(' ', $dateDebut);
			$chaineJMADebut = explode('-', $chaineDateDebut[0]);
			$chaineHMSDebut = explode(':', $chaineDateDebut[1]);

			$chaineDateFin = explode(' ', $dateFin);
			$chaineJMAFin = explode('-', $chaineDateFin[0]);
			$chaineHMSFin = explode(':', $chaineDateFin[1]);
			
			if ($chaineJMADebut[2] == $chaineJMAFin[2]) {
				echo "Le ";
				echo Cours::getDate($chaineJMADebut[2], $chaineJMADebut[1], $chaineJMADebut[0]);
				echo " de {$chaineHMSDebut[0]}h{$chaineHMSDebut[1]}";
				echo " à {$chaineHMSFin[0]}h{$chaineHMSFin[1]}";
			}
			else {
				echo "Du ";
				echo Cours::getDate($chaineJMADebut[2], $chaineJMADebut[1], $chaineJMADebut[0]);
				echo " {$chaineHMSDebut[0]}h{$chaineHMSDebut[1]} au ";
				echo Cours::getDate($chaineJMAFin[2], $chaineJMAFin[1], $chaineJMAFin[0]);
				echo " {$chaineHMSFin[0]}h{$chaineHMSFin[1]}";
			}
		}
		
		/**
		 * Fonction utilisée par la fonction dateCours pour l'affichage de la date d'un cours 
		 * @param $jour timestamp : int correspondant au nombre de jours d'une date
		 * @param $mois timestamp : int correspondant au nombre de mois d'une date
		 * @param $annee timestamp : int correspondant au nombre d'années d'une date
		 */
		public function getDate($jour, $mois, $annee) {
			if ($jour == 1)  
				$numeroJour = '1er';
			else if ($jour < 10)
				$numeroJour = $jour[1];
			else 
				$numeroJour = $jour;
				
			$nomMois = "";
			switch ($mois) {
				case 1 : 
					$nomMois = 'Janvier';
					break;
				case 2 : 
					$nomMois = 'Fevrier';
					break;
				case 3 : 
					$nomMois = 'Mars';
					break;
				case 4 : 
					$nomMois = 'Avril';
					break;
				case 5 : 
					$nomMois = 'Mai';
					break;
				case 6 : 
					$nomMois = 'Juin';
					break;
				case 7 : 
					$nomMois = 'Juillet';
					break;
				case 8 : 
					$nomMois = 'Août';
					break;
				case 9 : 
					$nomMois = 'Septembre';
					break;
				case 10 : 
					$nomMois = 'Octobre';
					break;
				case 11 : 
					$nomMois = 'Novembre';
					break;
				case 12 : 
					$nomMois = 'Décembre';
					break;
			}
			
			echo $numeroJour." ".$nomMois." ".$annee;
		}		
		
		/**
		 * Fonction utilisée pour l'affichage du formulaire utilisé pour l'ajout d'un cours
		 * @param $idPromotion int : id de la promotion sélectionnée
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public function formulaireAjoutCours($idPromotion, $nombresTabulations = 0) {
			$tab = ""; while ($nombresTabulation = 0) { $tab .= "\t"; $nombresTabulations--; }
			
			// Liste des UE de la promotion enregistrée dans la base de donnée
			$listeUE_promotion = UE::liste_UE_promotion($idPromotion);
			$nbUE = sizeof($listeUE_promotion);
			
			// Liste des intervenants enregistrée dans la base de donnée
			$listeIntervenants = Intervenant::listeIdIntervenants();
			
			// Liste des type de cours enregistrée dans la base de donnée
			$listeTypeCours = Type_Cours::liste_id_type_cours();
			$nbTypeCours = sizeof($listeTypeCours);
			
			// Gestion du formulaire suivant si on ajoute ou on modifie un cours
			if (isset($_GET['modifier_cours'])) { 
				$titre = "Modifier un cours";
				$_Cours = new Cours($_GET['modifier_cours']);
				$idUEModif = $_Cours->getIdUE();
				$idSalleModif = $_Cours->getIdSalle();
				$idIntervenantModif = $_Cours->getIdIntervenant();
				$idTypeCoursModif = $_Cours->getIdTypeCours();
				$tsDebutModif = $_Cours->getTsDebut();
				$tsFinModif = $_Cours->getTsFin();
				$valueSubmit = "Modifier le cours"; 
				$nameSubmit = "validerModificationCours";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"".$_GET['modifier_cours']."\" />";
				$lienAnnulation = "index.php?page=ajoutCours";
				if (isset($_GET['idPromotion'])) {
					$lienAnnulation .= "&amp;idPromotion=".$_GET['idPromotion'];
				}
			}
			else {
				$titre = "Ajouter un cours";
				$idTypeCoursModif = 1;
				$idSalleModif = 0;
				$valueSubmit = "Ajouter le cours"; 
				$nameSubmit = "validerAjoutCours";
				$hidden = "";
			}
			
			if ($nbUE == 0)
				echo $tab."<h2>Vous devez d'aboir créer des UE pour cette promotion avant de créer des séances</h2><br/><br/>\n";
			else if ($nbTypeCours == 0)
				echo $tab."<h2>Vous devez d'aboir créer des types de cours avant de créer des séances</h2><br/><br/>\n";
			else {
				echo $tab."<h2>".$titre."</h2>\n";
				echo $tab."<form method=\"post\">\n";
				echo $tab."\t<table>\n";
				echo $tab."\t\t<tr>\n";
				echo $tab."\t\t\t<td><label for=\"UE\">UE</label></td>\n";
				echo $tab."\t\t\t<td>\n";
				echo $tab."\t\t\t\t<select name=\"UE\" id=\"UE\">\n";
				foreach ($listeUE_promotion as $idUE) {
					$_UE = new UE($idUE);
					$nomUE = $_UE->getNom();
					if (isset($idUEModif) && ($idUEModif == $idUE)) {
						$selected = "selected=\"selected\" ";
					} else {
						$selected = "";
					}
					echo $tab."\t\t\t\t\t<option value=\"".$idUE."\" ".$selected.">".$nomUE."</option>\n";
				}
				echo $tab."\t\t\t\t</select>\n";
				echo $tab."\t\t\t</td>\n";
				echo $tab."\t\t</tr>\n";
				
				echo $tab."\t\t<tr>\n";
				echo $tab."\t\t\t<td><label for=\"type\">Type</label></td>\n";
				echo $tab."\t\t\t<td>\n";
				echo $tab."\t\t\t\t<select name=\"typeCours\" id=\"typeCours\" onChange=\"update_select_typeSalle({$idSalleModif})\">\n";
				foreach ($listeTypeCours as $idTypeCours) {
					$_TypeCours = new Type_Cours($idTypeCours);
					$nomTypeCours = $_TypeCours->getNom();
					if ($idTypeCoursModif == $idTypeCours) {
						$selected = "selected=\"selected\"";
					} else {
						$selected = "";
					}
					echo $tab."\t\t\t\t\t<option value=\"".$idTypeCours."\" ".$selected.">".$nomTypeCours."</option>\n";
				}
				echo $tab."\t\t\t\t</select>\n";
				echo $tab."\t\t\t</td>\n";
				echo $tab."\t\t</tr>\n";
				
				echo $tab."\t\t<tr>\n";
				echo $tab."\t\t\t<td><label for=\"intervenant\">Intervenant</label></td>\n";
				echo $tab."\t\t\t<td>\n";
				echo $tab."\t\t\t\t<select name=\"intervenant\" id=\"intervenant\">\n";
				
				if (isset($idIntervenantModif) && ($idIntervenantModif == 0)) { $selected = "selected=\"selected\" "; } else { $selected = ""; }
					echo $tab."\t\t\t\t\t<option value=\"0\" ".$selected.">----- Inconnu -----</option>\n";
				foreach ($listeIntervenants as $idIntervenant) {
					if ($idIntervenant != 0) {
						$_Intervenant = new Intervenant($idIntervenant);
						$nomIntervenant = $_Intervenant->getNom(); $prenomIntervenant = $_Intervenant->getPrenom();
						if (isset($idIntervenantModif) && ($idIntervenantModif == $idIntervenant)) {
							$selected = "selected=\"selected\" ";
						} else {
							$selected = "";
						}
						echo $tab."\t\t\t\t\t<option value=\"".$idIntervenant."\" ".$selected.">".$nomIntervenant." ".$prenomIntervenant.".</option>\n";
					}
				}
				echo $tab."\t\t\t\t</select>\n";
				echo $tab."\t\t\t</td>\n";
				echo $tab."\t\t</tr>\n";
				
				if (isset($tsDebutModif)) {
					$explode = explode(" ", $tsDebutModif);
					$valueDateDebut = "value=\"".$explode[0]."\" ";
					$explodeHeure = explode(":", $explode[1]);
					$valueHeureDebut = $explodeHeure[0];
					$valueMinuteDebut = $explodeHeure[1];
				}
				else {
					$valueDateDebut = "";
					$valueHeureDebut = "";
					$valueMinuteDebut = "";
				}
				echo $tab."\t\t<tr>\n";
				echo $tab."\t\t\t<td>Date Debut</td>\n";
				echo $tab."\t\t\t<td><input onchange=\"changeDateDebut(this.value)\" name=\"dateDebut\" type=\"date\" required ".$valueDateDebut."/> aaaa-mm-jj</td>\n";
				echo $tab."\t\t</tr>\n";
				
				echo $tab."\t\t<tr>\n";
				echo $tab."\t\t\t<td>Heure Debut</td>\n";
				echo $tab."\t\t\t<td>\n";
				echo $tab."\t\t\t\t<select name=\"heureDebut\" onchange=\"changeHeureDebut(this.value)\">\n";			
				for ($cpt= 0;$cpt<=23;$cpt++) {
					if ($cpt == $valueHeureDebut)
						$selected = " selected";
					else if (($cpt == 7) && ($valueHeureDebut == ""))
						$selected = " selected";
					else
						$selected = "";
						
					if ($cpt < 10)
						echo $tab."\t\t\t\t\t<option value=\"0".$cpt."\" ".$selected.">0".$cpt."</option>\n";
					else
						echo $tab."\t\t\t\t\t<option value=\"".$cpt."\" ".$selected.">".$cpt."</option>\n";				
				}
				echo $tab."\t\t\t\t\t</select>\n";
				echo $tab."\t\t\t\t<select name=\"minuteDebut\" onchange=\"changeMinuteDebut(this.value)\">\n";		
				$tab_minute = array(0,15,30,45);
				$first=false;
				for ($cpt= 0;$cpt<4;$cpt++) {
					if ($tab_minute[$cpt] == $valueMinuteDebut) {
						$selected = " selected";
					}
					else if (($cpt == 3) && ($valueMinuteDebut == ""))
						$selected = " selected";
					else
						$selected = "";
						
					if ($cpt == 0)
						echo $tab."\t\t\t\t\t<option value=\"0{$tab_minute[$cpt]}\" {$selected}>0{$tab_minute[$cpt]}</option>\n";
					else
						echo $tab."\t\t\t\t\t<option value=\"{$tab_minute[$cpt]}\" {$selected}>{$tab_minute[$cpt]}</option>\n";						
				}
				echo $tab."\t\t\t\t\t</select>\n";
				echo $tab."\t\t\t</td>\n";
				echo $tab."\t\t</tr>\n";
				
				if (isset($tsFinModif)) {
					$explode = explode(" ", $tsFinModif);
					$valueDateFin = "value=\"{$explode[0]}\" ";
					$explodeHeure = explode(":", $explode[1]);
					$valueHeureFin = $explodeHeure[0];
					$valueMinuteFin = $explodeHeure[1];
				}
				else {
					$valueDateFin = "";
					$valueHeureFin = "";
					$valueMinuteFin = "";
				}
				echo $tab."\t\t<tr>\n";
				echo $tab."\t\t\t<td>Date Fin</td>\n";
				echo $tab."\t\t\t<td><input id=\"dateFin\" name=\"dateFin\" type=\"date\" required ".$valueDateFin."/> aaaa-mm-jj</td>\n";
				echo $tab."\t\t</tr>\n";
				echo $tab."\t\t<tr>\n";
				echo $tab."\t\t\t<td>Heure Fin</td>\n";
				echo $tab."\t\t\t<td>\n";
				echo $tab."\t\t\t\t<select name=\"heureFin\">\n";			
				for ($cpt= 0;$cpt<=23;$cpt++) {
					if ($cpt == $valueHeureFin)
						$selected = " selected";
					else if (($cpt == 9) && ($valueHeureFin == ""))
						$selected = " selected";
					else
						$selected = "";
						
					if ($cpt < 10)
						echo $tab."\t\t\t\t\t<option value=\"0".$cpt."\" {$selected}>0".$cpt."</option>\n";
					else
						echo $tab."\t\t\t\t\t<option value=\"".$cpt."\" {$selected}>{$cpt}</option>\n";				
				}
				echo $tab."\t\t\t\t\t</select>\n";
				echo $tab."\t\t\t\t<select name=\"minuteFin\">\n";		
				$tab_minute = array(0,15,30,45);
				$first=false;
				for ($cpt= 0;$cpt<4;$cpt++) {
					if ($tab_minute[$cpt] == $valueMinuteFin) {
						$selected = " selected";
					}
					else if (($cpt == 3) && ($valueMinuteFin == ""))
						$selected = " selected";
					else
						$selected = "";
						
					if ($cpt == 0)
						echo $tab."\t\t\t\t\t<option value=\"0{$tab_minute[$cpt]}\" {$selected}>0{$tab_minute[$cpt]}</option>\n";
					else
						echo $tab."\t\t\t\t\t<option value=\"{$tab_minute[$cpt]}\" {$selected}>{$tab_minute[$cpt]}</option>\n";						
				}
				echo $tab."\t\t\t\t\t</select>\n";
				echo $tab."\t\t\t</td>\n";
				echo $tab."\t\t</tr>\n";
				
				echo $tab."\t\t<tr>\n";
				echo $tab."\t\t\t<td><label for=\"salle\">Salle</label></td>\n";
				echo $tab."\t\t\t<td>\n";
				echo $tab."\t\t\t\t<select name=\"salle\" id=\"salle\">\n";
				
				// Affichage des salles suivant le type de cours sélectionée
				Cours::listeSalleSuivantTypeCours($idSalleModif, $idTypeCoursModif);
				
				echo $tab."\t\t\t\t</select>\n";
				echo $tab."\t\t\t</td>\n";
				echo $tab."\t\t</tr>\n";
				
				if (! isset($_GET['modifier_cours'])) { 
					echo $tab."\t\t<tr>\n";
					echo $tab."\t\t\t<td><label for=\"recursivite\">Récursivité</label></td>\n";
					echo $tab."\t\t\t<td>\n";
					echo $tab."\t\t\t\t<select name=\"recursivite\" id=\"recursivite\">\n";
					
					echo $tab."\t\t\t\t\t<option value=\"0\" ".$selected.">----- Aucune -----</option>\n";
					for ($i=1; $i<=10; $i++) {
						echo $tab."\t\t\t\t\t<option value=\"".$i."\">$i</option>\n";					
					}
					echo $tab."\t\t\t\t</select> (en semaines)\n";
					echo $tab."\t\t\t</td>\n";
					echo $tab."\t\t</tr>\n";
				}			
				
				echo $tab."\t\t<tr>\n";
				echo $tab."\t\t\t<td></td>\n";
				echo $tab."\t\t\t<td>".$hidden."<input type=\"submit\" name=\"".$nameSubmit."\" value=\"".$valueSubmit."\"></td>\n";
				echo $tab."\t\t</tr>\n";
				
				echo $tab."\t</table>\n";
				echo $tab."</form>\n";
				
				// Lien permettant de terminer la modification d'un cours et de revenir au formulaire pour l'ajout d'un nouveau cours
				if (isset($lienAnnulation)) {echo $tab."<p><a href=\"".$lienAnnulation."\">Annuler modification</a></p>";}	
			}
		}
		
		/**
		 * Liste des salles suivant le type de cours sélectionné
		 * @param $idSalleModif int : id de la salle pour le formulaire de modification du cours
		 * @param $idTypeCours int : correspond au type de cours sélectionné
		 */
		public static function listeSalleSuivantTypeCours($idSalleModif, $idTypeCours) {
			$tab = "";
			$liste_salle = V_Liste_Salles::liste_salles_appartenant_typeCours($idTypeCours);
			
			if (isset($idSalleModif) && ($idSalleModif == 0)) {
				$selected = "selected=\"selected\" ";
			} else {
				$selected = "";
			}
				echo $tab."\t\t\t\t\t<option value=\"0\" ".$selected.">----- Inconnu -----</option>\n";
			foreach ($liste_salle as $idSalle) {
<<<<<<< HEAD
				$_salle = new V_Liste_Salles($idSalle);
=======
				$_salle = new V_liste_Salles($idSalle);
>>>>>>> 0aadca45d7b005ef6bb97ffeeb5178ce796496c0
				$nomBatiment = $_salle->getNomBatiment();
				$nomSalle = $_salle->getNomSalle();
				if (isset($idSalleModif) && ($idSalleModif == $idSalle)) {
					$selected = "selected=\"selected\" ";
				} else {
					$selected = "";
				}
				echo $tab."\t\t\t\t\t<option value=\"".$idSalle."\" ".$selected.">".$nomBatiment." ".$nomSalle."</option>\n";
			}
		}
		
		/**
		 * Fonction permettant de prendre en compte les informations validées dans le formulaire pour la MAJ de la base de données
		 */
		public static function priseEnCompteFormulaire() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_POST['validerAjoutCours'])) { //pour l'Ajout d'un cours
				$idUE = $_POST['UE'];
				$idUECorrect = true;
				$idSalle = $_POST['salle'];
				$idSalleCorrect = true;
				$idIntervenant = $_POST['intervenant'];
				$idIntervenantCorrect = true;
				$typeCours = $_POST['typeCours'];
				$typeCoursCorrect = true;
				$dateDebut = $_POST['dateDebut'];
				$dateDebutCorrect = true;
				$heureDebut = $_POST['heureDebut'];
				$heureDebutCorrect = true;
				$minuteDebut = $_POST['minuteDebut'];
				$minuteDebutCorrect = true;
				$dateFin = $_POST['dateFin'];
				$dateFinCorrect = true;
				$heureFin = $_POST['heureFin'];
				$heureFinCorrect = true;
				$minuteFin = $_POST['minuteFin'];
				$minuteFinCorrect = true;
				$recursivite = $_POST['recursivite'];
				$recursiviteCorrect = true;
				if ($idUECorrect && $idSalleCorrect && $idIntervenantCorrect && $typeCoursCorrect && $dateDebutCorrect && $heureDebutCorrect && $minuteDebutCorrect && $dateFinCorrect && $heureFinCorrect && $minuteFinCorrect && $recursiviteCorrect) {	
					Cours::ajouterCours($idUE, $idSalle, $idIntervenant, $typeCours, "$dateDebut $heureDebut:$minuteDebut:00", "$dateFin $heureFin:$minuteFin:00", $recursivite);				
					array_push($messagesNotifications, "Le cours a bien été ajouté");
				}
				else {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
				}
			}
			else if (isset($_POST['validerModificationCours'])) { //pour la modification d'un cours
				$id = $_POST['id']; 
				$idCorrect = V_Infos_Cours::existe_cours($id);			
				$idUE = $_POST['UE'];
				$idUECorrect = true;
				$idSalle = $_POST['salle'];
				$idSalleCorrect = true;
				$idIntervenant = $_POST['intervenant'];
				$idIntervenantCorrect = true;
				$typeCours = $_POST['typeCours'];
				$typeCoursCorrect = true;
				$dateDebut = $_POST['dateDebut'];
				$dateDebutCorrect = true;
				$heureDebut = $_POST['heureDebut'];
				$heureDebutCorrect = true;
				$minuteDebut = $_POST['minuteDebut'];
				$minuteDebutCorrect = true;
				$dateFin = $_POST['dateFin'];
				$dateFinCorrect = true;
				$heureFin = $_POST['heureFin'];
				$heureFinCorrect = true;
				$minuteFin = $_POST['minuteFin'];
				$minuteFinCorrect = true;
				if ($idCorrect && $idUECorrect && $idSalleCorrect && $idIntervenantCorrect && $typeCoursCorrect && $dateDebutCorrect && $heureDebutCorrect && $minuteDebutCorrect && $dateFinCorrect && $heureFinCorrect && $minuteFinCorrect) {
					Cours::modifierCours($_GET['modifier_cours'], $idUE, $idSalle, $idIntervenant, $typeCours, "$dateDebut $heureDebut:$minuteDebut:00", "$dateFin $heureFin:$minuteFin:00");
					array_push($messagesNotifications, "Le cours a bien été modifié");
				}
				else {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
				}
			}
		}
		
		/**
		 * Fonction permettant de prendre en compte la validation d'une demande de suppression d'un cours, on test s'il est bien enregistré dans la base de donnée
		 */
		public static function priseEnCompteSuppression() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_GET['supprimer_cours'])) {	
				if (V_Infos_Cours::existe_cours($_GET['supprimer_cours'])) {
					// Le cours existe
					Cours::supprimerCours($_GET['supprimer_cours']);
					array_push($messagesNotifications, "Le cours à bien été supprimé");
				}
				else {
					// Le cours n'existe pas
					array_push($messagesErreurs, "Le cours n'existe pas");
				}
			}
		}
		
		/**
		* Fonction principale permettant l'affichage du formulaire d'ajout ou de modification d'un cours ainsi que l'affichage des cours de la promotion enregistrée dans la base de données
		*/
		public static function pageAdministration($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			//Affichage du formulaire
			Cours::formulaireAjoutCours($_GET['idPromotion'], $nombreTabulations + 1);
			
			//Affichage des cours à venir
			echo $tab."<h2>Liste des cours à venir</h2>\n";
			Cours::listeCoursFutursToTable($_GET['idPromotion'], true, $nombreTabulations + 1);
			
			// Commande test pour l'affichage de tous les cours de la promotion
			//Cours::listeCoursToTable($_GET['idPromotion'], true, $nombreTabulations + 1);
		}		
	}
