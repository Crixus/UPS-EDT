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
		
		public function getId(){ return $this->id; }
		public function getNom(){ return $this->nom; }
		public function getPrenom(){ return $this->prenom; }
		public function getEmail(){ return $this->email; }
		public function getTelephone(){ return $this->telephone; }
		public function getNotificationsActives(){ return $this->notificationsActives; }
		public function getActif(){ return $this->actif; }
		
		public function Intervenant($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Intervenant::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Intervenant::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function getIntervenant($id) {
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT prenom, nom FROM ".Intervenant::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				$nomIntervenant = $ligne['prenom'].' '.$ligne['nom'];
			}
			catch(Exception $e){
				$nomIntervenant = "";
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $nomIntervenant;
		}		
		
		public static function ajouter_intervenant($nom, $prenom, $email, $telephone){
			try{
				//On ajoute d'abord l'Intervenant
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
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
				$idIntervenant = $bdd->lastInsertId(); 
				$type = "Intervenant";
				$login = strtolower($prenom)."_".strtolower($nom);
				if(Utilisateur::existe_login($login)){
					$count = 2;
					while(Utilisateur::existe_login($login."_$count")){
						$count++;
					}
					$login .= "_$count";
				}
				$motDePasse = md5("pass"); // Generer un mot de passe
					
				$req = $bdd->prepare("INSERT INTO ".Utilisateur::$nomTable." VALUES(?, ?, ?, ?, ?)");
				$req->execute(
					Array(
						"",
						$login,
						$motDePasse, 
						$type, 
						$idIntervenant
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_intervenant($idIntervenant, $nom, $prenom, $email, $telephone){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Intervenant::$nomTable." SET nom=?, prenom=?, email=?, telephone=? WHERE id=?;");
				$req->execute(
					Array(
						$nom,
						$prenom, 
						$email, 
						$telephone,
						$idIntervenant
					)
				);
				
				//Modification de l'intervenant dans la table Utilisateur
				try{				
					$type = "Etudiant";
					$login = strtolower($prenom)."_".strtolower($nom);
					$motDePasse = "1a1dc91c907325c69271ddf0c944bc72";
					
					$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("UPDATE ".Utilisateur::$nomTable." SET login=?, motDePasse=? WHERE idCorrespondant=? AND type='Intervenant'");
					
					$req->execute(
						Array(
							$login,
							$motDePasse, 
							$idIntervenant
						)
					);
				}
				catch(Exception $e){
					echo "Erreur : ".$e->getMessage()."<br />";
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function supprimer_intervenant($idIntervenant){
		
			//MAJ de la table "Cours" on met idIntervenant à 0 pour l'idIntervenant correspondant
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Cours::$nomTable." SET idIntervenant = 0 WHERE idIntervenant=?;");
				$req->execute(
					Array(
						$idIntervenant
					)
				);
		
				//MAJ de la table "UE" on met idResponsable à 0 pour l'idResponsable correspondant
				try{
					$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("UPDATE ".UE::$nomTable." SET idResponsable = 0 WHERE idResponsable=?;");
					$req->execute(
						Array(
							$idIntervenant
						)
					);
		
					//Suppression de l'intervenant
					try{
						$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
						$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
						$bdd->query("SET NAMES utf8");
						$req = $bdd->prepare("DELETE FROM ".Intervenant::$nomTable." WHERE id=?;");
						$req->execute(
							Array(
								$idIntervenant
							)
						);
						
						//Suppression de l'intervenant dans la table Utilisateur
						try{
							$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
							$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
							$bdd->query("SET NAMES utf8");
							$req = $bdd->prepare("DELETE FROM ".Utilisateur::$nomTable." WHERE idCorrespondant=? AND type='Intervenant';");
							$req->execute(
								Array(
									$idIntervenant
								)
							);
						}
						catch(Exception $e){
							echo "Erreur : ".$e->getMessage()."<br />";
						}
					}
					catch(Exception $e){
						echo "Erreur : ".$e->getMessage()."<br />";
					}
				}
				catch(Exception $e){
					echo "Erreur : ".$e->getMessage()."<br />";
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function existe_intervenant($id){
			try{
				$pdo_Options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_Options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Intervenant::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne['nb'] == 1;
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function liste_id_UE() {
			$listeIdUE = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".UE::$nomTable." WHERE idResponsable = ? ORDER BY nom");
				$req->execute(
					array(
						$this->id
					)
				);
				while($ligne = $req->fetch()){
					array_push($listeIdUE, $ligne['id']);
				}
				$req->closeCursor();
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeIdUE;		
		}
		
		public static function liste_intervenant(){
			$listeId = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".Intervenant::$nomTable." ORDER BY nom");
				$req->execute();
				while($ligne = $req->fetch()){
					array_push($listeId, $ligne['id']);
				}
				$req->closeCursor();
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeId;
		}
		
		public static function liste_intervenant_to_table($administration, $nombreTabulations = 0){
			$liste_intervenant = Intervenant::liste_intervenant();
			$tab = ""; for($i = 0 ; $i < $nombreTabulations ; $i++){ $tab .= "\t"; }
			
			echo "$tab<table class=\"table_liste_administration\">\n";
			
			echo "$tab\t<tr class=\"fondGrisFonce\">\n";
			
			echo "$tab\t\t<th>Nom</th>\n";
			echo "$tab\t\t<th>Prénom</th>\n";
			echo "$tab\t\t<th>Email</th>\n";
			echo "$tab\t\t<th>Téléphone</th>\n";
			echo "$tab\t\t<th>Notifications actives</th>\n";
			echo "$tab\t\t<th>Actif</th>\n";
			echo "$tab\t\t<th>UE de cette promotion dont il est responsable</th>\n";
			
			
			if($administration){
				echo "$tab\t\t<th>Actions</th>\n";
			}
			echo "$tab\t</tr>\n";
			
			$cpt = 0;
			foreach($liste_intervenant as $idIntervenant){
				if ($idIntervenant != 0) {
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					$Intervenant = new Intervenant($idIntervenant);
					$listeIdUE = $Intervenant->liste_id_UE();
					
					echo "$tab\t<tr class=\"$couleurFond\">\n";
					echo "$tab\t\t<td>".$Intervenant->nom."</td>\n";
					echo "$tab\t\t<td>".$Intervenant->prenom."</td>\n";
					echo "$tab\t\t<td>".$Intervenant->email."</td>\n";
					echo "$tab\t\t<td>".$Intervenant->telephone."</td>\n";
					$checked = ($Intervenant->notificationsActives) ? "checked = \"checked\"" : $checked = "";
					$nomCheckbox = "{$idIntervenant}_notifications";
					echo "$tab\t\t<td><input type=\"checkbox\" name= \"{$idIntervenant}_notifications\" value=\"{$idIntervenant}\" onclick=\"intervenant_notificationsActives({$idIntervenant},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
					$checked = ($Intervenant->actif) ? "checked = \"checked\"" : $checked = "";
					$nomCheckbox = "{$idIntervenant}_actif";
					echo "$tab\t\t<td><input type=\"checkbox\" name= \"{$idIntervenant}_actif\" value=\"{$idIntervenant}\" onclick=\"intervenant_actif({$idIntervenant},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
					
					$nbUE = sizeof($listeIdUE); $cptBoucle = 1;
					echo "$tab\t\t<td>";
					foreach ($listeIdUE as $idUE) {
						$UE = new UE($idUE);
						if($cptBoucle != 1){
							if ($cptBoucle != $nbUE){ echo ", "; }
							else{ echo" et "; }
						}
						echo "{$UE->getNom()}({$UE->getAnnee()})";
						$cptBoucle ++;
					}
					echo "</td>\n";
					
					if($administration){
						$pageModification = "./index.php?page=ajoutIntervenant&amp;modifier_intervenant=$idIntervenant";
						$pageSuppression = "./index.php?page=ajoutIntervenant&amp;supprimer_intervenant=$idIntervenant";
						if(isset($_GET['idPromotion'])){
							$pageModification .= "&amp;idPromotion={$_GET['idPromotion']}";
							$pageSuppression .= "&amp;idPromotion={$_GET['idPromotion']}";
						}
						echo "$tab\t\t<td>";
						echo "<a href=\"$pageModification\"><img src=\"../images/modify.png\" alt=\"icone de modification\" /></a>";
						echo "<a href=\"$pageSuppression\" onclick=\"return confirm('Supprimer l\'intervenant ?')\"><img src=\"../images/delete.png\" alt=\"icone de suppression\" /></a>";
						echo "</td>\n";
					}
					echo "$tab\t</tr>\n";
				}
			}
			
			echo "$tab</table>\n";
		}
		
		public function formulaireAjoutIntervenant($nombreTabulations = 0){
			$tab = ""; for($i = 0 ; $i < $nombreTabulations ; $i++){ $tab .= "\t"; }
			
			if(isset($_GET['modifier_intervenant'])){ 
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
				if(isset($_GET['idPromotion'])){
					$lienAnnulation .= "&amp;idPromotion={$_GET['idPromotion']}";
				}
			}
			else{
				$titre = "Ajouter un intervenant";
				$nomModif = "";
				$prenomModif = "";
				$emailModif = "";
				$telephoneModif = "";
				$valueSubmit = "Ajouter l'intervenant"; 
				$nameSubmit = "validerAjoutIntervenant";
				$hidden = "";
			}		
		
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
			echo "$tab\t\t\t<td><label>Prénom</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<input name=\"prenom\" type=\"text\" required {$prenomModif}/>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label>Email</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<input name=\"email\" type=\"email\" required {$emailModif}/>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label>Téléphone</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<input onChange='verification_tel(this)' name=\"telephone\" type=\"text\" {$telephoneModif}/>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td></td>\n";
			echo "$tab\t\t\t<td>$hidden<input type=\"submit\" name=\"$nameSubmit\" value=\"{$valueSubmit}\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";
			
			if(isset($lienAnnulation)){echo "$tab<p><a href=\"$lienAnnulation\">Annuler modification</a></p>";}		
		}		
		
		public static function prise_en_compte_formulaire(){
			global $messages_notifications, $messages_erreurs;
			if(isset($_POST['validerAjoutIntervenant'])){
				$nom = $_POST['nom'];
				$prenom = $_POST['prenom'];
				$email = $_POST['email'];
				$telephone = $_POST['telephone'];
				$nom_correct = true;
				$prenom_correct = true;
				$email_correct = PregMatch::est_mail($email);
				$telephone_correct = true;
				if($nom_correct && $prenom_correct && $email_correct && $telephone_correct){		
					Intervenant::ajouter_intervenant($nom, $prenom, $email, $telephone);
					array_push($messages_notifications, "L'intervenant a bien été ajouté");
				}
				else{
					array_push($messages_erreurs, "La saisie n'est pas correcte");
				}
			}
			else if(isset($_POST['validerModificationIntervenant'])){
				$id = $_POST['id']; $id_correct = Intervenant::existe_intervenant($id);
				$nom = $_POST['nom']; $nom_correct = true;
				$prenom = $_POST['prenom']; $prenom_correct = true;
				$email = $_POST['email']; $email_correct = PregMatch::est_mail($email);
				$telephone = $_POST['telephone']; $telephone_correct = true;
				if($id_correct && $nom_correct && $prenom_correct && $email_correct && $telephone_correct){	
					Intervenant::modifier_intervenant($_GET['modifier_intervenant'], $nom, $prenom, $email, $telephone);
					array_push($messages_notifications, "L'intervenant a bien été modifié");
				}
				else{
					array_push($messages_erreurs, "La saisie n'est pas correcte");
				}
			}
		}
		
		public static function prise_en_compte_suppression(){
			global $messages_notifications, $messages_erreurs;
			if(isset($_GET['supprimer_intervenant'])){	
				if(Intervenant::existe_intervenant($_GET['supprimer_intervenant'])){
					// L'intervenant existe
					Intervenant::supprimer_intervenant($_GET['supprimer_intervenant']);
					array_push($messages_notifications, "L'intervenant à bien été supprimé");
				}
				else{
					// L'intervenant n'existe pas
					array_push($messages_erreurs, "L'intervenant n'existe pas");
				}
			}
		}		
		
		public static function page_administration($nombreTabulations = 0){
			$tab = ""; for($i = 0 ; $i < $nombreTabulations ; $i++){ $tab .= "\t"; }
			Intervenant::formulaireAjoutIntervenant($nombreTabulations);
			echo "$tab<h2>Liste des intervenants</h2>\n";
			Intervenant::liste_Intervenant_to_table($nombreTabulations);
		}
	}
