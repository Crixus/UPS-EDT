<?php
	class Intervenant{
		
		public static $nomTable = "Intervenant";
		
		public static $attributs = Array(
			"id",
			"nom",
			"prenom",
			"email",
			"telephone",
			"notificationsActives",
			"actif"
		);
		
		public function getId() { return $this->id; }
		public function getNom() { return $this->nom; }
		public function getPrenom() { return $this->prenom; }
		public function getEmail() { return $this->email; }
		public function getTelephone() { return $this->telephone; }
		public function getNotificationsActives() { return $this->notificationsActives; }
		public function getActif () { return $this->actif; }
		
		public function Intervenant($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Intervenant::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Intervenant::$attributs as $att) {
					$this->$att = $ligne["$att"];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function ajouter_intervenant($nom, $prenom, $email, $telephone) {
			try {
				//On ajoute d'abord l'Intervenant
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Intervenant::$nomTable." VALUES(?, ?, ?, ?, ?, ?, ?)");
				
				$req->execute(
					Array(
						"",
						$nom, 
						$prenom, 
						$email, 
						$telephone, 
						"1",
						"1"
					)
				);
				
				//On créé maintenant l'Utilisateur associé
				Utilisateur::creer_utilisateur($prenom, $nom, "Intervenant", $bdd->lastInsertId());
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_intervenant($idIntervenant, $nom, $prenom, $email, $telephone) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Intervenant::$nomTable." SET nom=?, prenom=?, email=?, telephone=? WHERE id=?;");
				$req->execute(
					Array($nom, $prenom, $email, $telephone, $idIntervenant)
				);
				
				//Modification de l'intervenant dans la table Utilisateur
				$type = "Etudiant";
				$login = strtolower($prenom)."_".strtolower($nom);
				$motDePasse = "1a1dc91c907325c69271ddf0c944bc72";
					
				$req = $bdd->prepare("UPDATE ".Utilisateur::$nomTable." SET login=?, motDePasse=? WHERE idCorrespondant=? AND type='Intervenant'");
				$req->execute(
					Array($login, $motDePasse, $idIntervenant)
					);
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function supprimer_intervenant($idIntervenant) {
		
			//MAJ de la table "Cours" on met idIntervenant à 0 pour l'idIntervenant correspondant
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Cours::$nomTable." SET idIntervenant = 0 WHERE idIntervenant=?;");
				$req->execute(
					Array($idIntervenant)
				);
		
				//MAJ de la table "UE" on met idResponsable à 0 pour l'idResponsable correspondant
				$req = $bdd->prepare("UPDATE ".UE::$nomTable." SET idResponsable = 0 WHERE idResponsable=?;");
				$req->execute(
					Array($idIntervenant)
				);
		
				//Suppression de l'intervenant
				$req = $bdd->prepare("DELETE FROM ".Intervenant::$nomTable." WHERE id=?;");
				$req->execute(
					Array($idIntervenant)
				);
				
				$idUtilisateur = Utilisateur::id_depuis_type_et_idCorrespondant("Intervenant", $idIntervenant);
				Utilisateur::supprimer_utilisateur($idUtilisateur);
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function existe_intervenant($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Intervenant::$nomTable." WHERE id=?");
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
		
		// Methode à refaire car nom incorrect
		public static function getIntervenant($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT prenom, nom FROM ".Intervenant::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				$nomIntervenant = $ligne['prenom'].' '.$ligne['nom'];
			}
			catch (Exception $e) {
				$nomIntervenant = "";
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $nomIntervenant;
		}
		
		public function liste_id_UE() {
			$listeIdUE = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".UE::$nomTable." WHERE idResponsable = ? ORDER BY nom");
				$req->execute(
					array($this->id)
				);
				while ($ligne = $req->fetch()) {
					array_push($listeIdUE, $ligne['id']);
				}
				$req->closeCursor();
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeIdUE;		
		}
		
		public static function liste_intervenant() {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".Intervenant::$nomTable." ORDER BY nom");
				$req->execute();
				while ($ligne = $req->fetch()) {
					array_push($listeId, $ligne['id']);
				}
				$req->closeCursor();
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeId;
		}
		
		public static function liste_intervenant_to_table($administration, $nombreTabulations = 0) {
			$liste_intervenant = Intervenant::liste_intervenant();			
			$nbre_intervenant = sizeof($liste_intervenant);
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			
			if ($nbre_intervenant <= 1) {
				echo $tab."<b>Aucun intervenant n'est enregistré</b>\n";
			}
			else {
				echo $tab."<table class=\"table_liste_administration\">\n";
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				
				echo $tab."\t\t<th>Nom</th>\n";
				echo $tab."\t\t<th>Prénom</th>\n";
				echo $tab."\t\t<th>Email</th>\n";
				echo $tab."\t\t<th>Téléphone</th>\n";
				echo $tab."\t\t<th>Notifications actives</th>\n";
				echo $tab."\t\t<th>Actif</th>\n";
				echo $tab."\t\t<th>UE de cette promotion dont il est responsable</th>\n";
				
				
				if ($administration) {
					echo $tab."\t\t<th>Actions</th>\n";
				}
				echo $tab."\t</tr>\n";
				
				$cpt = 0;
				foreach ($liste_intervenant as $idIntervenant) {
					if ($idIntervenant != 0) {
						$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
						
						$Intervenant = new Intervenant($idIntervenant);
						$listeIdUE = $Intervenant->liste_id_UE();
						
						echo $tab."\t<tr class=\"$couleurFond\">\n";
						echo $tab."\t\t<td>".$Intervenant->nom."</td>\n";
						echo $tab."\t\t<td>".$Intervenant->prenom."</td>\n";
						echo $tab."\t\t<td>".$Intervenant->email."</td>\n";
						echo $tab."\t\t<td>".$Intervenant->telephone."</td>\n";
						$checked = ($Intervenant->notificationsActives) ? "checked = \"checked\"" : $checked = "";
						$nomCheckbox = "{$idIntervenant}_notifications";
						echo $tab."\t\t<td><input type=\"checkbox\" name= \"{$idIntervenant}_notifications\" value=\"{$idIntervenant}\" onclick=\"intervenant_notificationsActives({$idIntervenant},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
						$checked = ($Intervenant->actif) ? "checked = \"checked\"" : $checked = "";
						$nomCheckbox = "{$idIntervenant}_actif";
						echo $tab."\t\t<td><input type=\"checkbox\" name= \"{$idIntervenant}_actif\" value=\"{$idIntervenant}\" onclick=\"intervenant_actif ({$idIntervenant},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
						
						$nbUE = sizeof($listeIdUE); $cptBoucle = 1;
						echo $tab."\t\t<td>";
						foreach ($listeIdUE as $idUE) {
							$UE = new UE($idUE);
							if ($cptBoucle != 1) {
								if ($cptBoucle != $nbUE) { echo ", "; }
								else { echo" et "; }
							}
							echo "{$UE->getNom()}({$UE->getAnnee()})";
							$cptBoucle ++;
						}
						echo "</td>\n";
						
						if ($administration) {
							$pageModification = "./index.php?page=ajoutIntervenant&amp;modifier_intervenant=$idIntervenant";
							$pageSuppression = "./index.php?page=ajoutIntervenant&amp;supprimer_intervenant=$idIntervenant";
							if (isset($_GET['idPromotion'])) {
								$pageModification .= "&amp;idPromotion=".$_GET['idPromotion'];
								$pageSuppression .= "&amp;idPromotion=".$_GET['idPromotion'];
							}
							echo $tab."\t\t<td>";
							echo "<a href=\"".$pageModification."\"><img src=\"../images/modify.png\" alt=\"icone de modification\" /></a>";
							echo "<a href=\"".$pageSuppression."\" onclick=\"return confirm('Supprimer l\'intervenant ?')\"><img src=\"../images/delete.png\" alt=\"icone de suppression\" /></a>";
							echo "</td>\n";
						}
						echo $tab."\t</tr>\n";
					}
				}
				
				echo $tab."</table>\n";
			}
		}
		
		public function formulaireAjoutIntervenant($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			
			if (isset($_GET['modifier_intervenant'])) { 
				$titre = "Modifier un intervenant";
				$Intervenant = new Intervenant($_GET['modifier_intervenant']);
				$nomModif = "value=\"{$Intervenant->getNom()}\"";
				$prenomModif = "value=\"{$Intervenant->getPrenom()}\"";
				$emailModif = "value=\"{$Intervenant->getEmail()}\"";
				$telephoneModif = "value=\"{$Intervenant->getTelephone()}\"";
				$valueSubmit = "Modifier l'intervenant"; 
				$nameSubmit = "validerModificationIntervenant";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_intervenant']}\" />";
				$lienAnnulation = "index.php?page=ajoutIntervenant";
				if (isset($_GET['idPromotion'])) {
					$lienAnnulation .= "&amp;idPromotion=".$_GET['idPromotion'];
				}
			}
			else {
				$titre = "Ajouter un intervenant";
				$nomModif = (isset($_POST['nom'])) ? "value=\"".$_POST['nom']."\"" : "value=\"\"";
				$prenomModif = (isset($_POST['prenom'])) ? "value=\"".$_POST['prenom']."\"" : "value=\"\"";
				$emailModif = (isset($_POST['email'])) ? "value=\"".$_POST['email']."\"" : "value=\"\"";
				$telephoneModif = (isset($_POST['telephone'])) ? "value=\"".$_POST['telephone']."\"" : "value=\"\"";
				$valueSubmit = "Ajouter l'intervenant"; 
				$nameSubmit = "validerAjoutIntervenant";
				$hidden = "";
			}		
		
			echo $tab."<h2>$titre</h2>\n";
			echo $tab."<form method=\"post\">\n";
			echo $tab."\t<table>\n";
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Nom</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"nom\" type=\"text\" required {$nomModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Prénom</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"prenom\" type=\"text\" required {$prenomModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Email</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"email\" type=\"email\" required {$emailModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Téléphone</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input onChange='verification_tel(this)' name=\"telephone\" type=\"text\" {$telephoneModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td></td>\n";
			echo $tab."\t\t\t<td>$hidden<input type=\"submit\" name=\"".$nameSubmit."\" value=\"{$valueSubmit}\"></td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t</table>\n";
			echo $tab."</form>\n";
			
			if (isset($lienAnnulation)) {echo $tab."<p><a href=\"".$lienAnnulation."\">Annuler modification</a></p>";}		
		}		
		
		public static function prise_en_compte_formulaire() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_POST['validerAjoutIntervenant']) || isset($_POST['validerModificationIntervenant'])) {
				// Vérification des champs			
				$nom = htmlentities($_POST['nom'],ENT_QUOTES,'UTF-8');
				$nomCorrect = PregMatch::est_nom($nom);
				$prenom = htmlentities($_POST['prenom'],ENT_QUOTES,'UTF-8');
				$prenomCorrect = PregMatch::est_prenom($prenom);
				$email = $_POST['email'];
				$email_correct = PregMatch::est_mail($email);
				$telephone = $_POST['telephone'];
				$telephoneCorrect = PregMatch::est_telephone($telephone);
			
				$validation_ajout = false;
				if (isset($_POST['validerAjoutIntervenant'])) {
					// Ajout d'un nouveau intervenant
					if ($nomCorrect && $prenomCorrect && $email_correct && $telephoneCorrect) {		
						Intervenant::ajouter_intervenant($nom, $prenom, $email, $telephone);
						array_push($messagesNotifications, "L'intervenant a bien été ajouté");
						$validation_ajout = true;
					}
				}
				else {
					// Modification d'un intervenant
					$id = htmlentities($_POST['id']); 
					$idCorrect = Intervenant::existe_intervenant($id);
					if ($idCorrect && $nomCorrect && $prenomCorrect && $email_correct && $telephoneCorrect) {	
						Intervenant::modifier_intervenant($_GET['modifier_intervenant'], $nom, $prenom, $email, $telephone);
						array_push($messagesNotifications, "L'intervenant a bien été modifié");
						$validation_ajout = true;
					}
				}
				
				// Traitement des erreurs
				if (!$validation_ajout) {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
					if (isset($idCorrect) && !$idCorrect) {
						array_push($messagesErreurs, "L'id de l'intervenant n'est pas correct, contacter un administrateur");
					}
					if (!$nomCorrect) {
						array_push($messagesErreurs, "Le nom n'est pas correct");
					}
					if (!$prenomCorrect) {
						array_push($messagesErreurs, "Le prenom n'est pas correct");
					}
					if (!$email_correct) {
						array_push($messagesErreurs, "L'email n'est pas correct");
					}
					if (!$telephoneCorrect) {
						array_push($messagesErreurs, "Le téléphone n'est pas correct");
					}
				}
			}
		}
		
		public static function prise_en_compte_suppression() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_GET['supprimer_intervenant'])) {	
				if (Intervenant::existe_intervenant($_GET['supprimer_intervenant'])) {
					// L'intervenant existe
					Intervenant::supprimer_intervenant($_GET['supprimer_intervenant']);
					array_push($messagesNotifications, "L'intervenant à bien été supprimé");
				}
				else {
					// L'intervenant n'existe pas
					array_push($messagesErreurs, "L'intervenant n'existe pas");
				}
			}
		}		
		
		public static function page_administration($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			Intervenant::formulaireAjoutIntervenant($nombreTabulations);
			echo $tab."<h2>Liste des intervenants</h2>\n";
			Intervenant::liste_Intervenant_to_table($nombreTabulations);
		}
	}
