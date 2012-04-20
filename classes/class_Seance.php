<?php
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
		
		public function getId() { return $this->id; }
		public function getNom() { return $this->nom; }
		public function getDuree() { return $this->duree; }
		public function getEffectue() { return $this->effectue; }
		public function getIdUE() { return $this->idUE; }
		public function getIdSalle() { return $this->idSalle; }
		public function getIdIntervenant() { return $this->idIntervenant; }
		public function getIdTypeCours() { return $this->idTypeCours; }
		public function getIdSeancePrecedente() { return $this->idSeancePrecedente; }
		
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
					$this->$att = $ligne["$att"];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
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
		
		
		public static function liste_seance_to_table($idPromotion, $administration, $nombreTabulations = 0) {
			$liste_seance = V_Infos_Seance_Promotion::liste_seance($idPromotion);
			$nbSeance = sizeof($liste_seance);
			$tab = ""; while ($nombreTabulations > 0) { $tab .= "\t"; $nombreTabulations--; }
			
			if ($nbSeance == 0) {
				echo "$tab<b>Aucunes séances à venir n'est enregistré pour cette promotion</b>\n";
			}
			else {
			
				echo "$tab<table class=\"table_liste_administration\">\n";
				
				echo "$tab\t<tr class=\"fondGrisFonce\">\n";
				
				echo "$tab\t\t<th>Nom</th>\n";
				echo "$tab\t\t<th>UE</th>\n";
				echo "$tab\t\t<th>Type de cours</th>\n";
				echo "$tab\t\t<th>Intervenant</th>\n";
				echo "$tab\t\t<th>Salle</th>\n";
				echo "$tab\t\t<th>Durée</th>\n";
				echo "$tab\t\t<th>Séance Effectué</th>\n";
				echo "$tab\t\t<th>Seance Précédente</th>\n";
				
				if ($administration) {
					echo "$tab\t\t<th>Actions</th>\n";
				}
				echo "$tab\t</tr>\n";
				
				$cpt = 0;
				foreach ($liste_seance as $idSeance) {
					$Seance = new V_Infos_Seance_Promotion($idSeance);
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					echo "$tab\t<tr class=\"$couleurFond\">\n";
					$cptBoucle=0;
					$valTemp="";
					$valTemp2="";
					foreach (V_Infos_Seance_Promotion::$attributs as $att) {
						if ( ($cptBoucle == 1) || ($cptBoucle == 3) || ($cptBoucle == 5) || ($cptBoucle == 12) )
							echo "$tab\t\t<td>".$Seance->$att."</td>\n";	
							
						else if ( ($cptBoucle == 7) || ($cptBoucle == 10) )
							$valTemp = $Seance->$att;
							
						else if ( ($cptBoucle == 8) || ($cptBoucle == 11) ) {
							$val = $Seance->$att." ".$valTemp;
							$valTemp="";
							echo "$tab\t\t<td>".$val."</td>\n";
						}	
						
						else if ($cptBoucle == 13) {
							$checked = ($Seance->$att) ? "checked = \"checked\"" : $checked = "";
							$nomCheckbox = "{$idSeance}_effectue";
							echo "$tab\t\t<td><input type=\"checkbox\" name= \"{$idSeance}_effectue\" value=\"{$idSeance}\" onclick=\"seance_effectue({$idSeance},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
						}
						
						else if ($cptBoucle == 14) {
							$idSeancePrecedente = $Seance->$att;
							$SeancePrecedente = new Seance($idSeancePrecedente);
							$nomSeancePrecedente = ($idSeancePrecedente == 0) ? "" :  $SeancePrecedente->getNom();
							echo "$tab\t\t<td>".$nomSeancePrecedente."</td>\n";
						}
												
						$cptBoucle++;
					}
					if ($administration) {
						$pageModification = "./index.php?page=ajoutSeance&modifier_seance=$idSeance";
						$pageSuppression = "./index.php?page=ajoutSeance&supprimer_seance=$idSeance";
						if (isset($_GET['idPromotion'])) {
							$pageModification .= "&amp;idPromotion={$_GET['idPromotion']}";
							$pageSuppression .= "&amp;idPromotion={$_GET['idPromotion']}";
						}
						
						echo "$tab\t\t<td>";
						echo "<a href=\"$pageModification\"><img src=\"../images/modify.png\" alt=\"icone de modification\" /></a>";
						echo "<a href=\"$pageSuppression\" onclick=\"return confirm('Supprimer la séance ?')\"><img src=\"../images/delete.png\" alt=\"icone de suppression\" /></a>";
						echo "</td>\n";
					}
					echo "$tab\t</tr>\n";
				}
				
				echo "$tab</table>\n";
			}
		}
		
		
		// Formulaire
		public function formulaireAjoutSeance($idPromotion, $nombresTabulations = 0) {
			$tab = ""; while ($nombresTabulation = 0) { $tab .= "\t"; $nombresTabulations--; }
			$liste_UE_promotion = UE::liste_UE_promotion($idPromotion);
			$nbUE = sizeof($liste_UE_promotion);
			$liste_type_cours = Type_Cours::liste_id_type_cours();
			$nbTypeCours = sizeof($liste_type_cours);
			$liste_intervenant = Intervenant::liste_intervenant();
			$liste_seance_precedente_promotion = V_Infos_Seance_Promotion::liste_seance($idPromotion);
			
			if (isset($_GET['modifier_seance'])) { 
				$titre = "Modifier une séance";
				$Seance = new Seance($_GET['modifier_seance']);
				$idSeanceEnregistrer = $Seance->getId();
				$nomModif = "value=\"{$Seance->getNom()}\"";
				$dureeModif = "value=\"{$Seance->getDuree()}\"";
				$effectueModif = $Seance->getEffectue();
				$idSalleModif = $Seance->getIdSalle();
				$idIntervenantModif = $Seance->getIdIntervenant();
				$idTypeCoursModif = $Seance->getIdTypeCours();
				$idSeancePrecedenteModif = $Seance->getIdSeancePrecedente();
				$valueSubmit = "Modifier la séance"; 
				$nameSubmit = "validerModificationSeance";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_seance']}\" />";
				$lienAnnulation = "index.php?page=ajoutSeance";
				if (isset($_GET['idPromotion'])) {
					$lienAnnulation .= "&amp;idPromotion={$_GET['idPromotion']}";
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
				echo "$tab<h2>Vous devez d'aboir créer des UE pour cette promotion avant de créer des séances</h2><br/><br/>\n";
			else if ($nbTypeCours == 0)
				echo "$tab<h2>Vous devez d'aboir créer des types de cours avant de créer des séances</h2><br/><br/>\n";
			else {
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
				echo "$tab\t\t\t<td><label>Durée</label></td>\n";
				echo "$tab\t\t\t<td>\n";
				echo "$tab\t\t\t\t<input name=\"duree\" type=\"number\" min=\"1\" max=\"99\" required {$dureeModif}/>\n";
				echo "$tab\t\t\t</td>\n";
				echo "$tab\t\t</tr>\n";
				
				echo "$tab\t\t<tr>\n";
				echo "$tab\t\t\t<td><label for=\"UE\">UE</label></td>\n";
				echo "$tab\t\t\t<td>\n";
				echo "$tab\t\t\t\t<select name=\"UE\" id=\"UE\">\n";
				foreach ($liste_UE_promotion as $idUE) {
					$UE = new UE($idUE);
					$nomUE = $UE->getNom();
					if (isset($idUEModif) && ($idUEModif == $idUE)) { $selected = "selected=\"selected\" "; } else { $selected = ""; }
					echo "$tab\t\t\t\t\t<option value=\"$idUE\" $selected>$nomUE</option>\n";
				}
				echo "$tab\t\t\t\t</select>\n";
				echo "$tab\t\t\t</td>\n";
				echo "$tab\t\t</tr>\n";
				
				echo "$tab\t\t<tr>\n";
				echo "$tab\t\t\t<td><label for=\"type\">Type</label></td>\n";
				echo "$tab\t\t\t<td>\n";
				echo "$tab\t\t\t\t<select name=\"typeCours\" id=\"typeCours\" onChange=\"update_select_typeSalle({$idSalleModif})\">\n";
				foreach ($liste_type_cours as $idTypeCours) {
					$Type_Cours = new Type_Cours($idTypeCours);
					$nomTypeCours = $Type_Cours->getNom();
					if ($idTypeCoursModif == $idTypeCours) { $selected = "selected=\"selected\""; } else { $selected = ""; }
					echo "$tab\t\t\t\t\t<option value=\"$idTypeCours\"$selected>$nomTypeCours</option>\n";
				}
				echo "$tab\t\t\t\t</select>\n";
				echo "$tab\t\t\t</td>\n";
				echo "$tab\t\t</tr>\n";
				
				echo "$tab\t\t<tr>\n";
				echo "$tab\t\t\t<td><label for=\"intervenant\">Intervenant</label></td>\n";
				echo "$tab\t\t\t<td>\n";
				echo "$tab\t\t\t\t<select name=\"intervenant\" id=\"intervenant\">\n";
				
				if (isset($idIntervenantModif) && ($idIntervenantModif == 0)) { $selected = "selected=\"selected\" "; } else { $selected = ""; }
					echo "$tab\t\t\t\t\t<option value=\"0\" $selected>----- Inconnu -----</option>\n";
				foreach ($liste_intervenant as $idIntervenant) {
					if ($idIntervenant != 0) {
						$Intervenant = new Intervenant($idIntervenant);
						$nomIntervenant = $Intervenant->getNom(); $prenomIntervenant = $Intervenant->getPrenom();
						if (isset($idIntervenantModif) && ($idIntervenantModif == $idIntervenant)) { $selected = "selected=\"selected\" "; } else { $selected = ""; }
						echo "$tab\t\t\t\t\t<option value=\"$idIntervenant\" $selected>$nomIntervenant $prenomIntervenant.</option>\n";
					}
				}
				echo "$tab\t\t\t\t</select>\n";
				echo "$tab\t\t\t</td>\n";
				echo "$tab\t\t</tr>\n";
				
				echo "$tab\t\t<tr>\n";
				echo "$tab\t\t\t<td><label for=\"salle\">Salle</label></td>\n";
				echo "$tab\t\t\t<td>\n";
				echo "$tab\t\t\t\t<select name=\"salle\" id=\"salle\">\n";
				
				Cours::liste_salle_suivant_typeCours($idSalleModif, $idTypeCoursModif);
				
				echo "$tab\t\t\t\t</select>\n";
				echo "$tab\t\t\t</td>\n";
				echo "$tab\t\t</tr>\n";
				
				echo "$tab\t\t<tr>\n";
				echo "$tab\t\t\t<td><label for=\"seancePrecedente\">Seance Précèdente</label></td>\n";
				echo "$tab\t\t\t<td>\n";
				echo "$tab\t\t\t\t<select name=\"seancePrecedente\" id=\"seancePrecedente\">\n";
				if (isset($idSeancePrecedenteModif) && ($idSeancePrecedenteModif == 0) ) { $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo "$tab\t\t\t\t\t<option value=\"0\" $selected>----- Inconnu -----</option>\n";
				foreach ($liste_seance_precedente_promotion as $idSeance) {
					if ( !(isset($_GET['modifier_seance']) && ($idSeance == $idSeanceEnregistrer)) ){
						$Seance = new Seance($idSeance);
						$nomSeance = $Seance->getNom();
						if (isset($idSeancePrecedenteModif) && ($idSeancePrecedenteModif == $idSeance)) { $selected = "selected=\"selected\" "; } else { $selected = ""; }
						echo "$tab\t\t\t\t\t<option value=\"$idSeance\" $selected>$nomSeance</option>\n";
					}
				}
				echo "$tab\t\t\t\t</select>\n";
				echo "$tab\t\t\t</td>\n";
				echo "$tab\t\t</tr>\n";
				
				echo "$tab\t\t<tr>\n";
				echo "$tab\t\t\t<td></td>\n";
				echo "$tab\t\t\t<td>$hidden<input type=\"submit\" name=\"$nameSubmit\" value=\"{$valueSubmit}\"></td>\n";
				echo "$tab\t\t</tr>\n";
				
				echo "$tab\t</table>\n";
				echo "$tab</form>\n";
				
				if (isset($lienAnnulation)) {echo "$tab<p><a href=\"$lienAnnulation\">Annuler modification</a></p>";}	
			}
		}
		
		
		public static function prise_en_compte_formulaire() {
			global $messages_notifications, $messages_erreurs;
			if (isset($_POST['validerAjoutSeance']) || isset($_POST['validerModificationSeance'])) {
				// Vérification des champs
				$nom = htmlentities($_POST['nom'],ENT_QUOTES,'UTF-8');
				$nom_correct = PregMatch::est_nom($nom);
				$duree = $_POST['duree'];
				$duree_correct = true;
				$idUE = $_POST['UE'];
				$idUE_correct = true;
				$typeCours = $_POST['typeCours'];
				$typeCours_correct = true;
				$idIntervenant = $_POST['intervenant'];
				$idIntervenant_correct = true;
				$idSalle = $_POST['salle'];
				$idSalle_correct = true;
				$idSeancePrecedente = $_POST['seancePrecedente'];
				$idSeancePrecedenteCorrecte = true;		
				
				$validation_ajout = false;
				if (isset($_POST['validerAjoutSeance'])) {
					// Ajout d'une nouvelle seance
					if ($nom_correct && $duree_correct && $idUE_correct && $typeCours_correct && $idIntervenant_correct && $idSalle_correct && $idSeancePrecedenteCorrecte) {
						Seance::ajouter_seance($nom, $duree, 0, $idUE, $idSalle, $idIntervenant, $typeCours, $idSeancePrecedente);				
						array_push($messages_notifications, "La séance a bien été ajouté");
						$validation_ajout = true;
					}
				}
				else {
					// Modification d'une nouvelle seance
					$id = htmlentities($_POST['id']); 
					$id_correct = JourNonOuvrable::existe_jourNonOuvrable($id);
					if ($id_correct && $nom_correct && $duree_correct && $idUE_correct && $typeCours_correct && $idIntervenant_correct && $idSalle_correct && $idSeancePrecedenteCorrecte) {
						Seance::modifier_seance($_GET['modifier_seance'], $nom, $duree, $idUE, $idSalle, $idIntervenant, $typeCours, $idSeancePrecedente);
						array_push($messages_notifications, "La séance a bien été modifié");
						$validation_ajout = true;
					}				
				}
				
				// Traitement des erreurs
				if (!$validation_ajout) {
					array_push($messages_erreurs, "La saisie n'est pas correcte");
					if (isset($id_correct) && !$id_correct) {
						array_push($messages_erreurs, "L'id de la séance n'est pas correct, contacter un administrateur");
					}
					if (!$nom_correct) {
						array_push($messages_erreurs, "Le nom n'est pas correct");
					}
				}
			}
		}
		
		
		public static function prise_en_compte_suppression() {
			global $messages_notifications, $messages_erreurs;
			if (isset($_GET['supprimer_seance'])) {	
				if (Seance::existe_seance($_GET['supprimer_seance'])) {
					// La séance existe
					Seance::supprimer_seance($_GET['supprimer_seance']);
					array_push($messages_notifications, "La séance à bien été supprimée");
				}
				else {
					// La séance n'existe pas
					array_push($messages_erreurs, "La séance n'existe pas");
				}
			}
		}
		
		public static function page_administration($nombreTabulations = 0) {
			$tab = ""; for ($i = 0 ; $i < $nombreTabulations ; $i++) { $tab .= "\t"; }
			Seance::formulaireAjoutSeance($_GET['idPromotion'], $nombreTabulations + 1);
			echo "$tab<h2>Liste des séances enregistrée</h2>\n";
			Seance::liste_seance_to_table($_GET['idPromotion'], true, $nombreTabulations + 1);
		}
	}
	
	