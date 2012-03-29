<?php
	class Etudiant{
		
		public static $nomTable = "Etudiant";
		
		public static $attributs = Array(
			"id",
			"numeroEtudiant",
			"nom",
			"prenom",
			"email",
			"telephone",
			"idSpecialite",
			"idPromotion"
		);
		
		public function getId(){ return $this->id; }
		public function getNumeroEtudiant(){ return $this->numeroEtudiant; }
		public function getNom(){ return $this->nom; }
		public function getPrenom(){ return $this->prenom; }
		public function getEmail(){ return $this->email; }
		public function getTelephone(){ return $this->telephone; }
		public function getIdSpecialite(){ return $this->idSpecialite; }
		
		public function Etudiant($id){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Etudiant::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Etudiant::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function ajouter_etudiant($numeroEtudiant, $nom, $prenom, $email, $telephone, $idPromotion, $idSpecialite){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Etudiant::$nomTable." VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
				
				$req->execute(
					Array(
						"",
						$numeroEtudiant,
						$nom, 
						$prenom, 
						$email, 
						$telephone, 
						"1",
						$idPromotion,
						$idSpecialite
					)
				);
				
				//On créé maintenant l'Utilisateur associé
				Utilisateur::creer_utilisateur($prenom, $nom, "Etudiant", $bdd->lastInsertId());
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_etudiant($idEtudiant, $numeroEtudiant, $nom, $prenom, $email, $telephone, $idSpecialite){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Etudiant::$nomTable." SET numeroEtudiant=?, nom=?, prenom=?, email=?, telephone=?, idSpecialite=? WHERE id=?;");
				$req->execute(
					Array(
						$numeroEtudiant,
						$nom, 
						$prenom, 
						$email, 
						$telephone,
						$idSpecialite,
						$idEtudiant
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function supprimer_etudiant($idEtudiant){
			//Suppression de l'étudiant dans la table "Appartient_Etudiant_GroupeEtudiants"
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("DELETE FROM ".Appartient_Etudiant_GroupeEtudiants::$nomTable." WHERE idEtudiant=?;");
				$req->execute(
					Array(
						$idEtudiant
					)
				);
								
				//Suppression de l'étudiant dans la table "Inscription"
				try{
					$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("DELETE FROM ".Inscription::$nomTable." WHERE idEtudiant=?;");
					$req->execute(
						Array(
							$idEtudiant
						)
					);
		
					//Suppression de l'étudiant dans la table "Appartient_Etudiant_GroupeAdministratif"
					try{
						$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
						$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
						$bdd->query("SET NAMES utf8");
						$req = $bdd->prepare("DELETE FROM ".Appartient_Etudiant_GroupeAdministratif::$nomTable." WHERE idEtudiant=?;");
						$req->execute(
							Array(
								$idEtudiant
							)
						);
						
						//Suppression de l'étudiant
						try{
							$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
							$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
							$bdd->query("SET NAMES utf8");
							$req = $bdd->prepare("DELETE FROM ".Etudiant::$nomTable." WHERE id=?;");
							$req->execute(
								Array(
									$idEtudiant
								)
							);
							
							//Suppression de l'étudiant dans la table "Utilisateur"
							try{
								$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
								$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
								$bdd->query("SET NAMES utf8");
								$req = $bdd->prepare("DELETE FROM ".Utilisateur::$nomTable." WHERE idCorrespondant=? AND type='Etudiant';");
								$req->execute(
									Array(
										$idEtudiant
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
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function formulaireAjoutEtudiant($idPromotion, $nombresTabulations = 0){
			$tab = ""; while($nombresTabulation = 0){ $tab .= "\t"; $nombresTabulations--; }
			$liste_specialite = Specialite::liste_specialite($idPromotion);			
			
			if(isset($_GET['modifier_etudiant'])){ 
				$titre = "Modifier un étudiant";
				$Etudiant = new Etudiant($_GET['modifier_etudiant']);
				$numeroEtudiantModif = "value=\"{$Etudiant->getNumeroEtudiant()}\"";
				$nomModif = "value=\"{$Etudiant->getNom()}\"";
				$prenomModif = "value=\"{$Etudiant->getPrenom()}\"";
				$emailModif = "value=\"{$Etudiant->getEmail()}\"";
				$telephoneModif = "value=\"{$Etudiant->getTelephone()}\"";
				$idSpecialiteModif = $Etudiant->getIdSpecialite();
				$valueSubmit = "Modifier l'étudiant"; 
				$nameSubmit = "validerModificationEtudiant";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_etudiant']}\" />";
				$lienAnnulation = "index.php?page=ajoutEtudiant";
				if(isset($_GET['idPromotion'])){
					$lienAnnulation .= "&amp;idPromotion={$_GET['idPromotion']}";
				}
			}
			else{
				$titre = "Ajouter un étudiant";
				$numeroEtudiantModif = "";
				$nomModif = "";
				$prenomModif = "";
				$emailModif = "";
				$telephoneModif = "";
				$valueSubmit = "Ajouter l'étudiant"; 
				$nameSubmit = "validerAjoutEtudiant";
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
			echo "$tab\t\t\t<td><label>Numéro Etudiant</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<input name=\"numeroEtudiant\" type=\"number\" min=\"0\" max=\"99999999\" required {$numeroEtudiantModif}/>\n";
			echo "$tab\t\t\t</td>\n";
			echo "$tab\t\t</tr>\n";			
			
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td><label>Spécialité</label></td>\n";
			echo "$tab\t\t\t<td>\n";
			echo "$tab\t\t\t\t<select name=\"idSpecialite\" id=\"idSpecialite\">\n";
			
			if(isset($idSpecialiteModif) && ($idSpecialiteModif == 0)){ $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo "$tab\t\t\t\t\t<option value=\"0\" $selected>----- Inconnu -----</option>\n";
			foreach($liste_specialite as $idSpecialite){
				$Specialite = new Specialite($idSpecialite);
				$nomSpecialite = $Specialite->getNom();
				if(isset($idSpecialiteModif) && ($idSpecialiteModif == $idSpecialite)){ $selected = "selected=\"selected\" "; } else { $selected = ""; }
				echo "$tab\t\t\t\t\t<option value=\"$idSpecialite\" $selected>$nomSpecialite</option>\n";
			}
			echo "$tab\t\t\t\t</select>\n";
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
			echo "$tab\t\t\t\t<input name=\"telephone\" type=\"text\"  {$telephoneModif}/>\n";
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
			if(isset($_POST['validerAjoutEtudiant'])){
				$numeroEtudiant = $_POST['numeroEtudiant'];
				$numeroEtudiant_correct = PregMatch::est_nombre($numeroEtudiant);
				$nom = $_POST['nom'];
				$nom_correct = true;
				$prenom = $_POST['prenom'];
				$prenom_correct = true;
				$email = $_POST['email'];
				$email_correct = PregMatch::est_mail($email);
				$telephone = $_POST['telephone'];
				$telephone_correct = true;
				$idSpecialite = $_POST['idSpecialite'];
				$idSpecialite_correct = Specialite::existe_specialite($idSpecialite);
				if($numeroEtudiant && $nom_correct && $prenom_correct && $email_correct && $telephone_correct){		
					Etudiant::ajouter_etudiant($numeroEtudiant, $nom, $prenom, $email, $telephone, $_GET['idPromotion'], $idSpecialite);
					array_push($messages_notifications, "L'étudiant a bien été ajouté");
				}
				else{
					array_push($messages_erreurs, "La saisie n'est pas correcte");
				}
			}
			else if(isset($_POST['validerModificationEtudiant'])){
				$id = $_POST['id']; 
				$id_correct = V_Infos_Etudiant::existe_etudiant($id);
				$numeroEtudiant = $_POST['numeroEtudiant'];
				$numeroEtudiant_correct = true;
				$nom = $_POST['nom'];
				$nom_correct = true;
				$prenom = $_POST['prenom'];
				$prenom_correct = true;
				$email = $_POST['email'];
				$email_correct = PregMatch::est_mail($email);
				$telephone = $_POST['telephone'];
				$telephone_correct = true;
				$idSpecialite = $_POST['idSpecialite'];
				if($id_correct && $numeroEtudiant_correct && $nom_correct && $prenom_correct && $email_correct && $telephone_correct){		
					Etudiant::modifier_etudiant($_GET['modifier_etudiant'], $numeroEtudiant, $nom, $prenom, $email, $telephone, $idSpecialite);
					array_push($messages_notifications, "L'étudiant a bien été modifié");
				}
				else{
					array_push($messages_erreurs, "La saisie n'est pas correcte");
				}
			}
		}
		
		public static function prise_en_compte_suppression(){
			global $messages_notifications, $messages_erreurs;
			if(isset($_GET['supprimer_etudiant'])){	
				if(V_Infos_Etudiant::existe_etudiant($_GET['supprimer_etudiant'])){
					// L'étudiant existe
					Etudiant::supprimer_etudiant($_GET['supprimer_etudiant']);
					array_push($messages_notifications, "L'étudiant à bien été supprimé");
				}
				else{
					// L'étudiant n'existe pas
					array_push($messages_erreurs, "L'étudiant n'existe pas");
				}
			}
		}
		
		public static function page_administration($nombreTabulations = 0){			
			$tab = ""; for($i = 0 ; $i < $nombreTabulations ; $i++){ $tab .= "\t"; }
			Etudiant::formulaireAjoutEtudiant($_GET['idPromotion'], $nombreTabulations + 1);
			echo "$tab<h2>Liste des étudiants</h2>\n";
			V_Infos_Etudiant::liste_etudiant_to_table($_GET['idPromotion'], $nombreTabulations + 1);
		}
		
		
		public function toUl(){
			$string = "<ul>\n";
			foreach(Etudiant::$attributs as $att){
				$string .= "<li>$att : ".$this->$att."</li>\n";
			}
			return "$string</ul>\n";
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Etudiant::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Etudiant::$nomTable);
		}
	}
