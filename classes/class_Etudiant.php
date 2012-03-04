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
		
		/**
		 * Renvoi l'id de l'etudiant si le login et le motDePasse sont corrects, false sinon
		 * */
		public static function identification($login, $motDePasse){
			$motDePasse = md5($motDePasse);
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$requete = "SELECT id FROM ".Etudiant::$nomTable." WHERE login='$login' AND motDePasse='$motDePasse'";
				$req = $bdd->query($requete);
				$nbResultat = $req->rowCount();
				switch($nbResultat){
					case 0:
						return false;
						break;
					case 1:
						$ligne = $req->fetch();
						return $ligne['id'];
						break;
					default:
						// Erreur dans BD
						return false;
						break;
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
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
				
				//Ajout de l'étudiant dans la table Utilisateur
				$idEtudiant = $bdd->lastInsertId(); 
				try{ // try dans un try de meme style
					$idCorrespondant = $idEtudiant;
					$type = "Etudiant";
					$login = strtolower($prenom)."_".strtolower($nom); // Pas de gestion de conflit...
					$motDePasse = "1a1dc91c907325c69271ddf0c944bc72"; // Mot de passe pareil pour tlm...
					
					$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION; // Inutile
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options); // Nouvelle instance de BD dans une instance de BD
					$bdd->query("SET NAMES utf8"); 
					$req = $bdd->prepare("INSERT INTO ".Utilisateur::$nomTable." VALUES(?, ?, ?, ?, ?)");
					
					$req->execute(
						Array(
							"",
							$login,
							$motDePasse, 
							$type, 
							$idCorrespondant
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
				
				//Modification de l'étudiant dans la table Utilisateur
				try{				
					$type = "Etudiant";					
					$search  = array(' ', 'ê', 'è', 'é', 'à', 'â', 'î', 'ç', 'ù');
					$replace = array('_', 'e', 'e', 'e', 'a', 'a', 'î', 'c', 'u');
					// $login = strtolower(str_replace($search, $replace, $prenom))."_".strtolower(str_replace($search, $replace, $nom));
					$login = strtolower($prenom).'_'.strtolower($nom);
					$motDePasse = "1a1dc91c907325c69271ddf0c944bc72";
					
					$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("UPDATE ".Utilisateur::$nomTable." SET login=?, motDePasse=? WHERE idCorrespondant=? AND type='Etudiant'");
					
					$req->execute(
						Array(
							$login,
							$motDePasse, 
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
			}
			else{
				$titre = "Ajouter un étudiant";
				$numeroEtudiantModif = "";
				$nomModif = "";
				$prenomModif = "";
				$emailModif = "";
				$telephoneModif = "";
			}
		
			echo "$tab<h1>$titre</h1>\n";
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
			if(isset($_GET['modifier_etudiant'])){ $valueSubmit = "Modifier l'étudiant"; }else{ $valueSubmit = "Ajouter l'étudiant"; }
			echo "$tab\t\t\t<td><input type=\"submit\" name=\"validerAjoutEtudiant\" value=\"{$valueSubmit}\" style=\"cursor:pointer;\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";			
		}		
		
		public static function prise_en_compte_formulaire(){
			if(isset($_POST['validerAjoutEtudiant'])){
				$numeroEtudiant = $_POST['numeroEtudiant'];
				$nom = $_POST['nom'];
				$prenom = $_POST['prenom'];
				$email = $_POST['email'];
				$telephone = $_POST['telephone'];
				$idSpecialite = $_POST['idSpecialite'];
				if(true){ // Test de saisie
					$idPromotion = $_GET['idPromotion'];
					if(isset($_GET['modifier_etudiant'])){
						Etudiant::modifier_etudiant($_GET['modifier_etudiant'], $numeroEtudiant, $nom, $prenom, $email, $telephone, $idSpecialite);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutEtudiant&modification_etudiant=1";
					}
					else{
						// C'est un nouveau étudiant
						Etudiant::ajouter_etudiant($numeroEtudiant, $nom, $prenom, $email, $telephone, $idPromotion, $idSpecialite);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutEtudiant&ajout_etudiant=1";
					}
					header("Location: $pageDestination");
				}
			}
		}
		
		public static function prise_en_compte_suppression(){
			if(isset($_GET['supprimer_etudiant'])){	
				$idPromotion = $_GET['idPromotion'];	
				if(true){ // Test de saisie
					Etudiant::supprimer_etudiant($_GET['supprimer_etudiant']);
					$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutEtudiant&supprimer_etudiant=1";	
				}
			}
		}
		
		public static function page_administration($nombreTabulations = 0){
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			if(isset($_GET['ajout_etudiant'])){
				echo "$tab<p class=\"notificationAdministration\">L'étudiant a bien été ajouté</p>";
			}
			if(isset($_GET['modification_etudiant'])){
				echo "$tab<p class=\"notificationAdministration\">L'étudiant a bien été modifié</p>";
			}
			Etudiant::formulaireAjoutEtudiant($_GET['idPromotion'], $nombreTabulations + 1);
			echo "$tab<h1>Liste des étudiants</h1>\n";
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
