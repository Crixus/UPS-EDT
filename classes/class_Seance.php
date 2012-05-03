<?php
	/** 
	 * Classe Seance - Permet de gérer les Séances
	 */ 
	class Seance{
		
		public static $nomTable = "Seance";
		
		public static $attributs = Array(
			"id",
			"nom",
			"duree",
			"effectue",
			"idUE",
			"idSalle",
			"idIntervenant",
			"idTypeCours",
			"idSeancePrecedente"
		);
		
		/**
		 * Getter de l'id de la Séance
		 * @return int : id de la Séance
		 */
		public function getId() { return $this->id; }
		
		/**
		 * Getter du nom de la séance
		 * @return string : nom
		 */
		public function getNom() { return $this->nom; }
		
		/**
		 * Getter de la durée de la séance
		 * @return string : duree
		 */
		public function getDuree() { return $this->duree; }		
		
		/**
		 * Getter du boolean effectue (1 si la seance a été effectué)
		 * @return boolean : effectue
		 */
		public function getEffectue() { return $this->effectue; }
		
		/**
		 * Getter de idUE de la séance
		 * @return int : idUE
		 */
		public function getIdUE() { return $this->idUE; }
		
		/**
		 * Getter de idSalle de la séance
		 * @return int : idSalle
		 */
		public function getIdSalle() { return $this->idSalle; }
		
		/**
		 * Getter de idIntervenant de la séance
		 * @return int : idIntervenant
		 */
		public function getIdIntervenant() { return $this->idIntervenant; }
		
		/**
		 * Getter de idTypeCours de la séance
		 * @return int : idTypeCours 
		 */
		public function getIdTypeCours() { return $this->idTypeCours; }
		
		/**
		 * Getter de l'id de la séance précèdente de la séance
		 * @return int : idSeancePrecedente
		 */
		public function getIdSeancePrecedente() { return $this->idSeancePrecedente; }
		
		/**
		 * Constructeur de la classe Seance
		 * Récupère les informations de Seance dans la base de données depuis l'id
		 * @param $id : int id du Seance
		 */
		public function Seance($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Seance::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Seance::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Fonction testant l'existence d'une séance
		 * @param id : int id de la séance
		 */
		public static function existe_seance($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Seance::$nomTable." WHERE id=?");
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
		 * Ajouter une séance dans la base de données
		 * @param $nom : string nom de la séance
		 * @param $duree : int duree de la séance
		 * @param $effectue : boolean effectue de la séance
		 * @param $idUE : int idUE de la séance
		 * @param $idIntervenant : int idIntervenant de la séance
		 * @param $idTypeCours : int idTypeCours de la séance
		 * @param $idSeancePrecedente : int idSeancePrecedente de la séance
		 */
		public static function ajouter_seance($nom, $duree, $effectue, $idUE, $idSalle, $idIntervenant, $idTypeCours, $idSeancePrecedente) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Seance::$nomTable." VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$req->execute(
					Array(
						"",
						$nom,
						$duree,
						$effectue,
						$idUE,
						$idSalle,
						$idIntervenant,
						$idTypeCours,
						$idSeancePrecedente
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}		
		}
		
		/**
		 * Modifier une séance dans la base de données
		 * @param $idSeance : int id de la séance a modifiée
		 * @param $nom : string nom de la séance
		 * @param $duree : int duree de la séance
		 * @param $effectue : boolean effectue de la séance
		 * @param $idUE : int idUE de la séance
		 * @param $idIntervenant : int idIntervenant de la séance
		 * @param $idTypeCours : int idTypeCours de la séance
		 * @param $idSeancePrecedente : int idSeancePrecedente de la séance
		 */
		public static function modifier_seance($idSeance, $nom, $duree, $idUE, $idSalle, $idIntervenant, $idTypeCours, $idSeancePrecedente) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Seance::$nomTable." SET nom=?, duree=?, idUE=?, idSalle=?, idIntervenant=?, idTypeCours=?, idSeancePrecedente=? WHERE id=?;");
				$req->execute(
					Array(
						$nom,
						$duree,
						$idUE,
						$idSalle,
						$idIntervenant,
						$idTypeCours,
						$idSeancePrecedente,
						$idSeance
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Supprime une séance dans la base de données
		 * @param $idSalle int : id de la séance a supprimé
		 */
		public static function supprimer_seance($idSeance) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Seance::$nomTable." WHERE id=?;");
				$req->execute(
					Array(
						$idSeance
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Fonction utilisée pour l'affichage de la liste des séances créés 
		 * @param $idPromotion : int id de la promotion sélectionnée
		 * @param $administration boolean : possibilité de modification et suppression si egal à 1
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public static function liste_seance_to_table($idPromotion, $administration, $nombreTabulations = 0) {
			//Liste des séances enregistrées dans la base de donnée
			$liste_seance = V_Infos_Seance_Promotion::liste_seance($idPromotion);
			$nbSeance = sizeof($liste_seance);
			
			$tab = ""; while ($nombreTabulations > 0) { $tab .= "\t"; $nombreTabulations--; }
			
			if ($nbSeance == 0) {
				echo $tab."<b>Aucunes séances à venir n'est enregistré pour cette promotion</b>\n";
			}
			else {
			
				echo $tab."<table class=\"table_liste_administration\">\n";
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				
				echo $tab."\t\t<th>Nom</th>\n";
				echo $tab."\t\t<th>UE</th>\n";
				echo $tab."\t\t<th>Type de cours</th>\n";
				echo $tab."\t\t<th>Intervenant</th>\n";
				echo $tab."\t\t<th>Salle</th>\n";
				echo $tab."\t\t<th>Durée</th>\n";
				echo $tab."\t\t<th>Séance Effectué</th>\n";
				echo $tab."\t\t<th>Seance Précédente</th>\n";
				
				if ($administration) {
					echo $tab."\t\t<th>Actions</th>\n";
				}
				echo $tab."\t</tr>\n";
				
				$cpt = 0;
				// Gestion de l'affichage des informations des séances
				foreach ($liste_seance as $idSeance) {
					$_Seance = new V_Infos_Seance_Promotion($idSeance);
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					echo $tab."\t<tr class=\"".$couleurFond."\">\n";
					$cptBoucle= 0;
					$valTemp="";
					$valTemp2="";
					foreach (V_Infos_Seance_Promotion::$attributs as $att) {
						if (($cptBoucle == 1) || ($cptBoucle == 3) || ($cptBoucle == 5) || ($cptBoucle == 12))
							echo $tab."\t\t<td>".$_Seance->$att."</td>\n";	
							
						else if (($cptBoucle == 7) || ($cptBoucle == 10))
							$valTemp = $_Seance->$att;
							
						else if (($cptBoucle == 8) || ($cptBoucle == 11)) {
							$val = $_Seance->$att." ".$valTemp;
							$valTemp="";
							echo $tab."\t\t<td>".$val."</td>\n";
						}	
						
						else if ($cptBoucle == 13) {
							$checked = ($_Seance->$att) ? "checked = \"checked\"" : $checked = "";
							$nomCheckbox = "{$idSeance}_effectue";
							echo $tab."\t\t<td><input type=\"checkbox\" name= \"{$idSeance}_effectue\" value=\"{$idSeance}\" onclick=\"seance_effectue({$idSeance},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
						}
						
						else if ($cptBoucle == 14) {
							$idSeancePrecedente = $_Seance->$att;
							$_SeancePrecedente = new Seance($idSeancePrecedente);
							$nomSeancePrecedente = ($idSeancePrecedente == 0) ? "" :  $_SeancePrecedente->getNom();
							echo $tab."\t\t<td>".$nomSeancePrecedente."</td>\n";
						}
												
						$cptBoucle++;
					}
					
					// Création des liens pour la modification et la suppression des séances et gestion de l'URL 
					if ($administration) {
						$pageModification = "./index.php?page=ajoutSeance&modifier_seance=$idSeance";
						$pageSuppression = "./index.php?page=ajoutSeance&supprimer_seance=$idSeance";
						if (isset($_GET['idPromotion'])) {
							$pageModification .= "&amp;idPromotion=".$_GET['idPromotion'];
							$pageSuppression .= "&amp;idPromotion=".$_GET['idPromotion'];
						}
						
						echo $tab."\t\t<td>";
						echo "<a href=\"".$pageModification."\"><img src=\"../images/modify.png\" alt=\"icone de modification\" /></a>";
						echo "<a href=\"".$pageSuppression."\" onclick=\"return confirm('Supprimer la séance ?')\"><img src=\"../images/delete.png\" alt=\"icone de suppression\" /></a>";
						echo "</td>\n";
					}
					echo $tab."\t</tr>\n";
				}
				
				echo $tab."</table>\n";
			}
		}
		
		
		/**
		 * Fonction utilisée pour l'affichage du formulaire utilisé pour l'ajout d'une séance
		 * @param $idPromotion : int id de la promotion sélectionnée
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public function formulaireAjoutSeance($idPromotion, $nombresTabulations = 0) {
			$tab = ""; while ($nombresTabulation = 0) { $tab .= "\t"; $nombresTabulations--; }
			$listeUE_promotion = UE::liste_UE_promotion($idPromotion);
			$nbUE = sizeof($listeUE_promotion);
			$listeTypeCours = Type_Cours::liste_id_type_cours();
			$nbTypeCours = sizeof($listeTypeCours);
			$listeIntervenants = Intervenant::listeIdIntervenants();
			$liste_seance_precedente_promotion = V_Infos_Seance_Promotion::liste_seance($idPromotion);
			
			// Gestion du formulaire suivant si on ajoute ou on modifie une séance
			if (isset($_GET['modifier_seance'])) { 
				$titre = "Modifier une séance";
				$_Seance = new Seance($_GET['modifier_seance']);
				$idSeanceEnregistrer = $_Seance->getId();
				$nomModif = "value=\"{$_Seance->getNom()}\"";
				$dureeModif = "value=\"{$_Seance->getDuree()}\"";
				$effectueModif = $_Seance->getEffectue();
				$idSalleModif = $_Seance->getIdSalle();
				$idIntervenantModif = $_Seance->getIdIntervenant();
				$idTypeCoursModif = $_Seance->getIdTypeCours();
				$idSeancePrecedenteModif = $_Seance->getIdSeancePrecedente();
				$valueSubmit = "Modifier la séance"; 
				$nameSubmit = "validerModificationSeance";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_seance']}\" />";
				$lienAnnulation = "index.php?page=ajoutSeance";
				if (isset($_GET['idPromotion'])) {
					$lienAnnulation .= "&amp;idPromotion=".$_GET['idPromotion'];
				}
			}
			else {
				$titre = "Ajouter une séance";
				$nomModif = (isset($_POST['nom'])) ? "value=\"{$_POST['nom']}\"" : "value=\"\"";
				$dureeModif = (isset($_POST['duree'])) ? "value=\"{$_POST['duree']}\"" : "";
				$idTypeCoursModif = 1;
				$idSalleModif = 0;
				$idSeancePrecedenteModif = 0;
				$valueSubmit = "Ajouter la séance"; 
				$nameSubmit = "validerAjoutSeance";
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
				echo $tab."\t\t\t<td><label>Nom</label></td>\n";
				echo $tab."\t\t\t<td>\n";
				echo $tab."\t\t\t\t<input name=\"nom\" type=\"text\" required {$nomModif}/>\n";
				echo $tab."\t\t\t</td>\n";
				echo $tab."\t\t</tr>\n";
				
				echo $tab."\t\t<tr>\n";
				echo $tab."\t\t\t<td><label>Durée</label></td>\n";
				echo $tab."\t\t\t<td>\n";
				echo $tab."\t\t\t\t<input name=\"duree\" type=\"number\" min=\"1\" max=\"99\" required {$dureeModif}/>\n";
				echo $tab."\t\t\t</td>\n";
				echo $tab."\t\t</tr>\n";
				
				echo $tab."\t\t<tr>\n";
				echo $tab."\t\t\t<td><label for=\"UE\">UE</label></td>\n";
				echo $tab."\t\t\t<td>\n";
				echo $tab."\t\t\t\t<select name=\"UE\" id=\"UE\">\n";
				foreach ($listeUE_promotion as $idUE) {
					$_UE = new UE($idUE);
					$nomUE = $_UE->getNom();
					if (isset($idUEModif) && ($idUEModif == $idUE)) { $selected = "selected=\"selected\" "; } else { $selected = ""; }
					echo $tab."\t\t\t\t\t<option value=\"$idUE\" ".$selected.">$nomUE</option>\n";
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
					if ($idTypeCoursModif == $idTypeCours) { $selected = "selected=\"selected\""; } else { $selected = ""; }
					echo $tab."\t\t\t\t\t<option value=\"$idTypeCours\"$selected>$nomTypeCours</option>\n";
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
						if (isset($idIntervenantModif) && ($idIntervenantModif == $idIntervenant)) { $selected = "selected=\"selected\" "; } else { $selected = ""; }
						echo $tab."\t\t\t\t\t<option value=\"$idIntervenant\" ".$selected.">$nomIntervenant $prenomIntervenant.</option>\n";
					}
				}
				echo $tab."\t\t\t\t</select>\n";
				echo $tab."\t\t\t</td>\n";
				echo $tab."\t\t</tr>\n";
				
				echo $tab."\t\t<tr>\n";
				echo $tab."\t\t\t<td><label for=\"salle\">Salle</label></td>\n";
				echo $tab."\t\t\t<td>\n";
				echo $tab."\t\t\t\t<select name=\"salle\" id=\"salle\">\n";
				
				Cours::listeSalleSuivantTypeCours($idSalleModif, $idTypeCoursModif);
				
				echo $tab."\t\t\t\t</select>\n";
				echo $tab."\t\t\t</td>\n";
				echo $tab."\t\t</tr>\n";
				
				echo $tab."\t\t<tr>\n";
				echo $tab."\t\t\t<td><label for=\"seancePrecedente\">Seance Précèdente</label></td>\n";
				echo $tab."\t\t\t<td>\n";
				echo $tab."\t\t\t\t<select name=\"seancePrecedente\" id=\"seancePrecedente\">\n";
				if (isset($idSeancePrecedenteModif) && ($idSeancePrecedenteModif == 0)) { $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo $tab."\t\t\t\t\t<option value=\"0\" ".$selected.">----- Inconnu -----</option>\n";
				foreach ($liste_seance_precedente_promotion as $idSeance) {
					if (!(isset($_GET['modifier_seance']) && ($idSeance == $idSeanceEnregistrer))) {
						$_Seance = new Seance($idSeance);
						$nomSeance = $_Seance->getNom();
						if (isset($idSeancePrecedenteModif) && ($idSeancePrecedenteModif == $idSeance)) { $selected = "selected=\"selected\" "; } else { $selected = ""; }
						echo $tab."\t\t\t\t\t<option value=\"$idSeance\" ".$selected.">$nomSeance</option>\n";
					}
				}
				echo $tab."\t\t\t\t</select>\n";
				echo $tab."\t\t\t</td>\n";
				echo $tab."\t\t</tr>\n";
				
				echo $tab."\t\t<tr>\n";
				echo $tab."\t\t\t<td></td>\n";
				echo $tab."\t\t\t<td>".$hidden."<input type=\"submit\" name=\"".$nameSubmit."\" value=\"".$valueSubmit."\"></td>\n";
				echo $tab."\t\t</tr>\n";
				
				echo $tab."\t</table>\n";
				echo $tab."</form>\n";
				
				if (isset($lienAnnulation)) {echo $tab."<p><a href=\"".$lienAnnulation."\">Annuler modification</a></p>";}	
			}
		}
		
		/**
		 * Fonction permettant de prendre en compte les informations validées dans le formulaire pour la MAJ de la base de données
		 */
		public static function priseEnCompteFormulaire() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_POST['validerAjoutSeance']) || isset($_POST['validerModificationSeance'])) {
				// Vérification des champs
				$nom = htmlentities($_POST['nom'], ENT_QUOTES, 'UTF-8');
				$nomCorrect = PregMatch::est_nom($nom);
				$duree = $_POST['duree'];
				$dureeCorrect = true;
				$idUE = $_POST['UE'];
				$idUECorrect = true;
				$typeCours = $_POST['typeCours'];
				$typeCoursCorrect = true;
				$idIntervenant = $_POST['intervenant'];
				$idIntervenantCorrect = true;
				$idSalle = $_POST['salle'];
				$idSalleCorrect = true;
				$idSeancePrecedente = $_POST['seancePrecedente'];
				$idSeancePrecedenteCorrecte = true;		
				
				$validationAjout = false;
				if (isset($_POST['validerAjoutSeance'])) {
					// Ajout d'une nouvelle seance
					if ($nomCorrect && $dureeCorrect && $idUECorrect && $typeCoursCorrect && $idIntervenantCorrect && $idSalleCorrect && $idSeancePrecedenteCorrecte) {
						Seance::ajouter_seance($nom, $duree, 0, $idUE, $idSalle, $idIntervenant, $typeCours, $idSeancePrecedente);				
						array_push($messagesNotifications, "La séance a bien été ajouté");
						$validationAjout = true;
					}
				}
				else {
					// Modification d'une nouvelle seance
					$id = htmlentities($_POST['id']); 
<<<<<<< HEAD
					$idCorrect = JourNonOuvrable::existe_jourNonOuvrable($id);
					if ($idCorrect && $nomCorrect && $dureeCorrect && $idUECorrect && $typeCoursCorrect && $idIntervenantCorrect && $idSalleCorrect && $idSeancePrecedenteCorrecte) {
=======
					$idCorrect = Seance::existe_seance($id);
					if ($idCorrect && $nomCorrect && $dureeCorrect && $idUE_correct && $typeCours_correct && $idIntervenantCorrect && $idSalle_correct && $idSeancePrecedenteCorrecte) {
>>>>>>> 0aadca45d7b005ef6bb97ffeeb5178ce796496c0
						Seance::modifier_seance($_GET['modifier_seance'], $nom, $duree, $idUE, $idSalle, $idIntervenant, $typeCours, $idSeancePrecedente);
						array_push($messagesNotifications, "La séance a bien été modifié");
						$validationAjout = true;
					}				
				}
				
				// Traitement des erreurs
				if (!$validationAjout) {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
					if (isset($idCorrect) && !$idCorrect) {
						array_push($messagesErreurs, "L'id de la séance n'est pas correct, contacter un administrateur");
					}
					if (!$nomCorrect) {
						array_push($messagesErreurs, "Le nom n'est pas correct");
					}
				}
			}
		}
		
		/**
		 * Fonction permettant de prendre en compte la validation d'une demande de suppression d'une séance, on test s'il est bien enregistré dans la base de donnée
		 */
		public static function priseEnCompteSuppression() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_GET['supprimer_seance'])) {	
				if (Seance::existe_seance($_GET['supprimer_seance'])) {
					// La séance existe
					Seance::supprimer_seance($_GET['supprimer_seance']);
					array_push($messagesNotifications, "La séance à bien été supprimée");
				}
				else {
					// La séance n'existe pas
					array_push($messagesErreurs, "La séance n'existe pas");
				}
			}
		}
		
		/**
		* Fonction principale permettant l'affichage du formulaire d'ajout ou de modification d'une séance ainsi que l'affichage des séances enregistrées dans la base de données
		*/
		public static function pageAdministration($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			Seance::formulaireAjoutSeance($_GET['idPromotion'], $nombreTabulations + 1);
			echo $tab."<h2>Liste des séances enregistrée</h2>\n";
			Seance::liste_seance_to_table($_GET['idPromotion'], true, $nombreTabulations + 1);
		}
	}
	
	
