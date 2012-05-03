<?php
	/** 
	 * Classe UE - Permet de gérer les UE
	 */ 
	class UE {
		
		public static $nomTable = "UE";
		
		public static $attributs = Array(
			"nom",
			"intitule",
			"nbHeuresCours",
			"nbHeuresTD",
			"nbHeuresTP",
			"ECTS",
			"idResponsable",
			"idPromotion"
		);
		
		/**
		 * Getter de l'id de l'UE
		 * @return int : id de l'UE
		 */
		public function getId() { return $this->id; }
		
		/**
		 * Getter du nom de l'UE
		 * @return string : nom de l'UE
		 */
		public function getNom() { return $this->nom; }
		
		/**
		 * Getter de l'intitule de l'UE
		 * @return string : intitule l'UE
		 */
		public function getIntitule() { return $this->intitule; }
		
		/**
		 * Getter du nombres d'heures de cours de l'UE
		 * @return int : nbHeuresCours de l'UE
		 */
		public function getNbHeuresCours() { return $this->nbHeuresCours; }
		
		/**
		 * Getter du nombres d'heures de TD de l'UE
		 * @return int : nbHeuresTD de l'UE
		 */
		public function getNbHeuresTD() { return $this->nbHeuresTD; }
		
		/**
		 * Getter du nombres d'heures de TP de l'UE
		 * @return int : nbHeuresTP de l'UE
		 */
		public function getNbHeuresTP() { return $this->nbHeuresTP; }
		
		/**
		 * Getter du nombres d'ECTS de l'UE
		 * @return int : ECTS de l'UE
		 */
		public function getECTS() { return $this->ECTS; }
		
		/**
		 * Getter de l'idResponsable de l'UE (id de l'intervenant nommé responsable de l'UE)
		 * @return int : idResponsable de l'UE
		 */
		public function getIdResponsable() { return $this->idResponsable; }
		
		/**
		 * Constructeur de la classe UE
		 * Récupère les informations de UE dans la base de données depuis l'id
		 * @param $id : int id du UE
		 */
		public function UE($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".UE::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (UE::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Fonction testant l'existence d'une UE
		 * @param id : int id de l'UE
		 */
		public static function existe_UE($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".UE::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
				);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne['nb'] == 1;
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Ajouter une UE dans la base de données
		 * @param $nom : string nom de l'UE
		 * @param $intitule : string intitulé de l'UE
		 * @param nbHeuresCours : int nbHeuresCours de l'UE
		 * @param nbHeuresTP : int nbHeuresTP de l'UE
		 * @param ECTS : int ECTS de l'UE
		 * @param idResponsable : int idResponsable de l'UE
		 * @param $idPromotion : int idPromotion de l'UE
		 */
		public static function ajouter_UE($nom, $intitule, $nbHeuresCours, $nbHeuresTD, $nbHeuresTP, $ECTS, $idResponsable, $idPromotion) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".UE::$nomTable." VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
				
				$req->execute(
					Array(
						"",						
						$nom,
						$intitule,
						$nbHeuresCours, 
						$nbHeuresTD, 
						$nbHeuresTP, 
						$ECTS,
						$idResponsable,
						$idPromotion,
					)
				);			
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Permet de savoir l'année de l'UE en récupérant l'heure de la promotion associée
		 * @return string : année de l'UE
		 */
		public function getAnnee() {
			$Promotion = new Promotion($this->idPromotion);
			return $Promotion->getAnnee();
		}
		
		/**
		 * Modifier une UE dans la base de données
		 * @param $idUE : int id de l'UE a modifié
		 * @param $nom : string nom de l'UE
		 * @param $intitule : string intitulé de l'UE
		 * @param nbHeuresCours : int nbHeuresCours de l'UE
		 * @param nbHeuresTP : int nbHeuresTP de l'UE
		 * @param ECTS : int ECTS de l'UE
		 * @param idResponsable : int idResponsable de l'UE
		 * @param $idPromotion : int idPromotion de l'UE
		 */
		public static function modifier_UE($idUE, $nom, $intitule, $nbHeuresCours, $nbHeuresTD, $nbHeuresTP, $ECTS, $idResponsable, $idPromotion) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".UE::$nomTable." SET nom=?, intitule=?, nbHeuresCours=?, nbHeuresTD=?, nbHeuresTP=?, ECTS=?, idResponsable=?, idPromotion=? WHERE id=?;");
				$req->execute(
					Array(
						$nom,
						$intitule,
						$nbHeuresCours, 
						$nbHeuresTD, 
						$nbHeuresTP, 
						$ECTS,
						$idResponsable,
						$idPromotion,
						$idUE
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Supprime une UE dans la base de données
		 * @param $idUE int : id de l'UE a supprimé
		 */
		public static function supprimer_UE($idUE) {
			//Suppression de l'UE dans la table "Inscription"
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Inscription::$nomTable." WHERE idUE=?;");
				$req->execute(
					Array(
						$idUE
					)
				);
				
				//Suppression de l'UE
				try {
					$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("DELETE FROM ".UE::$nomTable." WHERE id=?;");
					$req->execute(
						Array(
							$idUE
						)
					);
				}
				catch (Exception $e) {
					echo "Erreur : ".$e->getMessage()."<br />";
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Récupère le nombre d'UE crées pour la promotion sélectionnée
		 * @param $idPromotion : int id de la promotion sélectionnée
		 * @return int : nombre d'UE de la promotion sélectionnée
		 */
		public function getNbreUEPromotion($idPromotion) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".UE::$nomTable." WHERE idPromotion=?");
				$req->execute(
					Array($idPromotion)
				);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne["nb"];
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Liste des UE de la promotion sélectionnée
		 * @param $idPromotion : int id de la promotion sélectionnée
		 * @return List<UE> : informations des UE de la promotion sélectionnée
		 */
		public static function liste_UE_promotion($idPromotion) {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".UE::$nomTable." WHERE idPromotion=? ORDER BY nom");
				$req->execute(
					Array($idPromotion)
				);
				while ($ligne = $req->fetch()) {
					array_push($listeId, $ligne['id']);
				}
				$req->closeCursor();
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeId;
		}
		
		/**
		 * Fonction utilisée pour l'affichage de la liste des UE créés 
		 * @param $idPromotion : int id de la promotion sélectionnée
		 * @param $administration boolean : possibilité de modification et suppression si egal à 1
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public static function liste_UE_to_table($idPromotion, $administration, $nombreTabulations = 0) {
			$tab = ""; 
			while ($nombreTabulations > 0) {
				$tab .= "\t"; $nombreTabulations--;
			}
			
			//Liste des UE de la promotion
			$liste_UE = V_Infos_UE::liste_UE($idPromotion);
			$nbUE = sizeof($liste_UE);
			
			if ($nbUE == 0) {
				echo $tab."<b>Aucune UE n'est enregistré pour cette promotion</b>\n";
			}
			else {
			
				echo $tab."<table class=\"table_liste_administration\">\n";
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				
				echo $tab."\t\t<th colspan='2'>UE</th>\n";
				echo $tab."\t\t<th colspan='3'>Nombres d'heures</th>\n";
				echo $tab."\t\t<th rowspan='2'>ECTS</th>\n";
				echo $tab."\t\t<th rowspan='2'>Responsable</th>\n";
				echo $tab."\t\t<th rowspan='2'>Nombre<br>d'élèves inscrits</th>\n";
				
				if ($administration) {
					echo $tab."\t\t<th rowspan='2'>Actions</th>\n";
				}					
				echo $tab."\t</tr>\n";	
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";	
				echo $tab."\t\t<th>Nom</th>\n";		
				echo $tab."\t\t<th>Intitule</th>\n";		
				echo $tab."\t\t<th>Cours</th>\n";
				echo $tab."\t\t<th>TD</th>\n";
				echo $tab."\t\t<th>TP</th>\n";
				echo $tab."\t</tr>\n";
				
				$cpt = 0;
				// Gestion de l'affichage des informations des UE
				foreach ($liste_UE as $idUE) {
					$UE = new V_Infos_UE($idUE);
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					echo $tab."\t<tr class=\"".$couleurFond."\">\n";
					$cptBoucle=0;
					$val_temp = "";
					foreach (V_Infos_UE::$attributs as $att) {
						if ($cptBoucle == 6)
							$val_temp = $UE->$att;
						else if ($cptBoucle == 7)
							echo $tab."\t\t<td>".$UE->$att." ".$val_temp."</td>\n";
						else
							echo $tab."\t\t<td>".$UE->$att."</td>\n";
						$cptBoucle++;
					}
					
					//On récupère le nombre d'étudiant inscrit à l'UE
					$nbreUE = Inscription::nbre_etudiant_inscrit($idUE);
					echo $tab."\t\t<td>".$nbreUE."</td>\n";
					
					// Création des liens pour la modification et la suppression des UE et gestion de l'URL 
					if ($administration) {
						$pageModification = "./index.php?page=ajoutUE&amp;modifier_UE=".$idUE;
						$pageSuppression = "./index.php?page=ajoutUE&amp;supprimer_UE=".$idUE;
						if (isset($_GET['idPromotion'])) {
							$pageModification .= "&amp;idPromotion=".$_GET['idPromotion'];
							$pageSuppression .= "&amp;idPromotion=".$_GET['idPromotion'];
						}
						echo $tab."\t\t<td>";
						echo "<a href=\"".$pageModification."\"><img src=\"../images/modify.png\" alt=\"icone de modification\" /></a>";
						echo "<a href=\"".$pageSuppression."\" onclick=\"return confirm('Supprimer l\'UE ?')\"><img src=\"../images/delete.png\" alt=\"icone de suppression\" /></a>";
						echo "</td>\n";
				}
					echo $tab."\t</tr>\n";
				}
				
				echo $tab."</table>\n";
			}
		}
		
		/**
		 * Fonction utilisée pour l'affichage du formulaire utilisé pour l'ajout d'une UE
		 * @param $idPromotion : int id de la promotion sélectionnée
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public function formulaireAjoutUE($idPromotion, $nombresTabulations = 0) {
			$tab = ""; while ($nombresTabulation = 0) { $tab .= "\t"; $nombresTabulations--; }
			
			//Liste des intervenants enregistrés dans la base de données
			$liste_intervenant = Intervenant::listeIdIntervenants();
			
			// Gestion du formulaire suivant si on ajoute ou on modifie une UE
			if (isset($_GET['modifier_UE'])) { 
				$titre = "Modifier une UE";
				$UE = new UE($_GET['modifier_UE']);
				$nomModif = "value=\"{$UE->getNom()}\"";
				$intituleModif = "value=\"{$UE->getIntitule()}\"";
				$nbHeuresCoursModif = "value=\"{$UE->getNbHeuresCours()}\"";
				$nbHeuresTDModif = "value=\"{$UE->getNbHeuresTD()}\"";
				$nbHeuresTPModif = "value=\"{$UE->getNbHeuresTP()}\"";
				$ectsModif = "value=\"{$UE->getECTS()}\"";
				$idResponsableModif = $UE->getIdResponsable();
				$valueSubmit = "Modifier l'UE"; 
				$nameSubmit = "validerModificationUE";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_UE']}\" />";
				$lienAnnulation = "index.php?page=ajoutUE";
				if (isset($_GET['idPromotion'])) {
					$lienAnnulation .= "&amp;idPromotion=".$_GET['idPromotion'];
				}
			}
			else {
				$titre = "Ajouter une UE";
				$nomModif = (isset($_POST['nom'])) ? "value=\"".$_POST['nom']."\"" : "value=\"\"";
				$intituleModif = (isset($_POST['intitule'])) ? "value=\"".$_POST['intitule']."\"" : "value=\"\"";
				$nbHeuresCoursModif = (isset($_POST['nbHeuresCours'])) ? "value=\"".$_POST['nbHeuresCours']."\"" : "value=\"\"";
				$nbHeuresTDModif = (isset($_POST['nbHeuresTD'])) ? "value=\"".$_POST['nbHeuresTD']."\"" : "value=\"\"";
				$nbHeuresTPModif = (isset($_POST['nbHeuresTP'])) ? "value=\"".$_POST['nbHeuresTP']."\"" : "value=\"\"";
				$ectsModif = (isset($_POST['ects'])) ? "value=\"".$_POST['ects']."\"" : "value=\"\"";
				$valueSubmit = "Ajouter l'UE"; 
				$nameSubmit = "validerAjoutUE";
				$hidden = "";
			}
			
			echo $tab."<h2>".$titre."</h2>\n";
			echo $tab."<form method=\"post\">\n";
			echo $tab."\t<table>\n";
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Nom</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"nom\" type=\"text\" required {$nomModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Intitulé</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"intitule\" type=\"text\" required {$intituleModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Nombre d'heures de cours</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"nbHeuresCours\" type=\"number\" min=\"0\" max=\"99\" required {$nbHeuresCoursModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Nombre d'heures de TD</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"nbHeuresTD\" type=\"number\" min=\"0\" max=\"99\" required {$nbHeuresTDModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Nombre d'heures de TP</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"nbHeuresTP\" type=\"number\" min=\"0\" max=\"99\" required {$nbHeuresTPModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Nombre d'ECTS</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"ects\" type=\"number\" min=\"0\" max=\"99\" required {$ectsModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Intervenant</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<select name=\"idIntervenant\" id=\"idIntervenant\">\n";
			
			$selected = (isset($idResponsableModif) && ($idResponsableModif == 0)) ? "selected=\"selected\" " : "";
			echo $tab."\t\t\t\t\t<option value=\"0\" $selected>----- Inconnue -----</option>\n";
			foreach ($liste_intervenant as $idIntervenant) {
				if ($idIntervenant != 0) {
					$Intervenant = new Intervenant($idIntervenant);
					$nomIntervenant = $Intervenant->getNom(); $prenomIntervenant = $Intervenant->getPrenom();
					$selected = (isset($idResponsableModif) && ($idResponsableModif == $idIntervenant)) ? "selected=\"selected\" " : "";
					echo $tab."\t\t\t\t\t<option value=\"".$idIntervenant."\" ".$selected.">".$nomIntervenant." ".$prenomIntervenant."</option>\n";
				}
			}
			echo $tab."\t\t\t\t</select>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td></td>\n";
			echo $tab."\t\t\t<td>".$hidden."<input type=\"submit\" name=\"".$nameSubmit."\" value=\"{$valueSubmit}\"></td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t</table>\n";
			echo $tab."</form>\n";
			
			if (isset($lienAnnulation)) {
				echo $tab."<p><a href=\"".$lienAnnulation."\">Annuler modification</a></p>";
			}		
		}
		
		/**
		 * Fonction permettant de prendre en compte les informations validées dans le formulaire pour la MAJ de la base de données
		 */
		public static function prise_en_compte_formulaire() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_POST['validerAjoutUE']) || isset($_POST['validerModificationUE'])) {
				// Vérification des champs
				$nom = htmlentities($_POST['nom'],ENT_QUOTES,'UTF-8');
				$nomCorrect = PregMatch::est_nom($nom);
				$intitule = htmlentities($_POST['intitule'],ENT_QUOTES,'UTF-8');
				$intitule_correct = PregMatch::est_intitule($intitule);
				$nbHeuresCours = $_POST['nbHeuresCours'];
				$nbHeuresCours_correct = PregMatch::est_nbre_heures($nbHeuresCours);
				$nbHeuresTD = $_POST['nbHeuresTD'];
				$nbHeuresTD_correct = PregMatch::est_nbre_heures($nbHeuresTD);
				$nbHeuresTP = $_POST['nbHeuresTP'];
				$nbHeuresTP_correct = PregMatch::est_nbre_heures($nbHeuresTP);
				$ects = $_POST['ects'];
				$ects_correct = PregMatch::est_nbre_heures($ects);
				$idIntervenant = $_POST['idIntervenant'];
				$idIntervenantCorrecte = Intervenant::existe_intervenant($idIntervenant);
				
				$validation_ajout = false;
				if (isset($_POST['validerAjoutUE'])) {				
					// Ajout d'une nouvelle UE
					if ($nomCorrect && $intitule_correct && $nbHeuresCours_correct && $nbHeuresTD_correct && $nbHeuresTP_correct && $ects_correct && $idIntervenantCorrecte) {		
						UE::ajouter_UE($nom, $intitule, $nbHeuresCours, $nbHeuresTD, $nbHeuresTP, $ects, $idIntervenant, $_GET['idPromotion']);
						array_push($messagesNotifications, "L'UE a bien été ajouté");
						$validation_ajout = true;
					}
				}
				else {				
					// Modification d'une nouvelle UE
					$id = htmlentities($_POST['id']); 
					$idCorrect = UE::existe_UE($id);
					if ($idCorrect && $nomCorrect && $intitule_correct && $nbHeuresCours_correct && $nbHeuresTD_correct && $nbHeuresTP_correct && $ects_correct && $idIntervenantCorrecte) {			
						UE::modifier_UE($_GET['modifier_UE'], $nom, $intitule, $nbHeuresCours, $nbHeuresTD, $nbHeuresTP, $ects, $idIntervenant, $_GET['idPromotion']);
						array_push($messagesNotifications, "L'UE a bien été modifié");
						$validation_ajout = true;
					}
				}

				// Traitement des erreurs
				if (!$validation_ajout) {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
					if (isset($idCorrect) && !$idCorrect) {
						array_push($messagesErreurs, "L'id de l'UE n'est pas correct, contacter un administrateur");
					}
					if (!$nomCorrect) {
						array_push($messagesErreurs, "Le nom n'est pas correct");
					}
					if (!$intitule_correct) {
						array_push($messagesErreurs, "L'intitulé n'est pas correct");
					}
					if (!$nbHeuresCours_correct) {
						array_push($messagesErreurs, "Le nombre d'heures de cours n'est pas correct");
					}
					if (!$nbHeuresTD_correct) {
						array_push($messagesErreurs, "Le nombre d'heures de TD n'est pas correct");
					}
					if (!$nbHeuresTP_correct) {
						array_push($messagesErreurs, "Le nombre d'heures de TP n'est pas correct");
					}
					if (!$ects_correct) {
						array_push($messagesErreurs, "L'ECTS n'est pas correct");
					}
					if (!$idIntervenantCorrecte) {
						array_push($messagesErreurs, "L'intervenant n'est pas correcte");
					}
				}
			}
		}
		
		/**
		 * Fonction permettant de prendre en compte la validation d'une demande de suppression d'une UE, on test s'il est bien enregistré dans la base de donnée
		 */
		public static function prise_en_compte_suppression() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_GET['supprimer_UE'])) {	
				if (UE::existe_UE($_GET['supprimer_UE'])) {
					// L'UE existe
					UE::supprimer_UE($_GET['supprimer_UE']);
					array_push($messagesNotifications, "L'UE à bien été supprimé");
				}
				else {
					// L'UE n'existe pas
					array_push($messagesErreurs, "L'UE n'existe pas");
				}
			}
		}
		
		/**
		* Fonction principale permettant l'affichage du formulaire d'ajout ou de modification d'une UE ainsi que l'affichage des UE de la promotion enregistrées dans la base de données
		* @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		*/
		public static function pageAdministration($nombreTabulations = 0) {			
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			UE::formulaireAjoutUE($_GET['idPromotion'], $nombreTabulations + 1);
			echo $tab."<h2>Liste des UE</h2>\n";
			UE::liste_UE_to_table($_GET['idPromotion'], true, $nombreTabulations + 1);
		}	
		
		/**
		* Fonction listant les nom des UE pour l'utiliser dans la page affichant tous les informations sur les cours d'une UE sélectionné
		* @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		*/
		public static function page_administration_listeCoursParUE($nombreTabulations = 0) {	
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			UE::liste_UE_to_table_for_listeCoursParUE($_GET['idPromotion'], true, $nombreTabulations + 1);
		}
		
		/**
		* Fonction affichant la liste des UE a sélectionné pour la liste des cours d'une UE
		 * @param $idPromotion : int id de la promotion sélectionnée
		 * @param $administration boolean : possibilité de modification et suppression si egal à 1
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		*/
		public static function liste_UE_to_table_for_listeCoursParUE($idPromotion, $administration, $nombreTabulations = 0) {
			$liste_UE = V_Infos_UE::liste_UE($idPromotion);
			$nbUE = sizeof($liste_UE);
			$tab = ""; while ($nombreTabulations > 0) { $tab .= "\t"; $nombreTabulations--; }
			
			if ($nbUE == 0) {
				echo $tab."<h2>Aucune UE n'est enregistré pour cette promotion</h2>\n";
			}
			else {
				echo $tab."<table class=\"table_liste_administration\">\n";
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				
				echo $tab."\t\t<th>Nom de l'UE</th>\n";
				
				echo $tab."\t\t<th>\n";
				echo $tab."\t\t\t<select name=\"idUE\" id=\"idUE\" onChange='listeCoursParUE({$idPromotion})'>\n";
				echo $tab."\t\t\t\t<option value=\"0\">----- Sélection de l'UE -----</option>\n";
				foreach ($liste_UE as $idUE) {	
					$UE = new UE($idUE);
					echo $tab."\t\t\t\t<option value=\"".$idUE."\">".$UE->getNom()."</option>\n";
				}
				echo $tab."\t\t\t</select>\n";
				echo $tab."\t\t</th>\n";
				echo $tab."\t</tr>\n";	
				echo $tab."</table>\n";				
			}
			
			echo $tab."<div name='page_administration_listeCoursParUE' style='display: none;'>\n";
			echo $tab."\t<div name='page_administration_listeCoursParUE_coursFutur'>\n";
			echo $tab."\t</div>\n";
			echo $tab."</div>\n";
		}
	}
