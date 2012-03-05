<?php
	class Intervenant{
		
		public static $nomTable = "Intervenant";
		
		public static $attributs = Array(
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
		
		public function getIntervenant($id) {
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
		
		public function liste_UE_responsable($idPromotion, $idIntervenant) {
			$listeNomUE = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT nom FROM ".UE::$nomTable." WHERE idResponsable = ? AND idPromotion = ? ORDER BY nom");
				$req->execute(
					array(
						$idIntervenant,
						$idPromotion
					)
				);
				while($ligne = $req->fetch()){
					array_push($listeNomUE, $ligne['nom']);
				}
				$req->closeCursor();
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeNomUE;		
		}
		
		public static function liste_intervenant(){
			$listeId = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Intervenant::$nomTable." ORDER BY nom");
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
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			
			echo "$tab<table class=\"listeCours\">\n";
			
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
				$Intervenant = new Intervenant($idIntervenant);
				
				if($cpt == 0){ $couleurFond="fondBlanc"; }
				else{ $couleurFond="fondGris"; }
				$cpt++; $cpt %= 2;
				
				echo "$tab\t<tr class=\"$couleurFond\">\n";
				foreach(Intervenant::$attributs as $att){
					if ($att == "notificationsActives"){ 
						if ($Intervenant->$att)
							$checked = "checked = \"checked\"" ;
						else
							$checked = "";
						$nomCheckbox = "{$idIntervenant}_notifications";
						echo "$tab\t\t<td><input type=\"checkbox\" name= \"{$idIntervenant}_notifications\" value=\"{$idIntervenant}\" onclick=\"intervenant_notificationsActives({$idIntervenant},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
					}
					else if ($att == "actif"){ 
						if ($Intervenant->$att)
							$checked = "checked = \"checked\"" ;
						else
							$checked = "";
						$nomCheckbox = "{$idIntervenant}_actif";
						echo "$tab\t\t<td><input type=\"checkbox\" name= \"{$idIntervenant}_actif\" value=\"{$idIntervenant}\" onclick=\"intervenant_actif({$idIntervenant},this)\" style=\"cursor:pointer;\" {$checked}></td>\n";
					}
					else
						echo "$tab\t\t<td>".$Intervenant->$att."</td>\n";
				}
				
				$liste_UE_responsable = Intervenant::liste_UE_responsable($_GET['idPromotion'], $idIntervenant);
				$nbUE = sizeof($liste_UE_responsable);
				if ($nbUE == 0)
					echo "$tab\t\t<td></td>\n";
				else {
					$cptBoucle = 1;
					echo "$tab\t\t<td>";
					foreach ($liste_UE_responsable as $nomUE) {
						if ($cptBoucle == 1)							
							echo $nomUE;
						else if ($cptBoucle != $nbUE)												
							echo ", ".$nomUE;
						else
							echo  " et ".$nomUE;
						$cptBoucle ++;
					}
					echo "</td>\n";
				}
				
				if($administration){
					$pageModification = "./index.php?idPromotion={$_GET['idPromotion']}&amp;page=ajoutIntervenant&amp;modifier_intervenant=$idIntervenant";
					$pageSuppression = "./index.php?idPromotion={$_GET['idPromotion']}&amp;page=ajoutIntervenant&amp;supprimer_intervenant=$idIntervenant";
					echo "$tab\t\t<td><img src=\"../images/modify.png\" style=\"cursor:pointer;\" onClick=\"location.href='{$pageModification}'\">  <img src=\"../images/delete.png\" style=\"cursor:pointer;\" OnClick=\"location.href=confirm('Voulez vous vraiment supprimer cet intervenant ?') ? '{$pageSuppression}' : ''\"/>\n";
				}
				echo "$tab\t</tr>\n";
			}
			
			echo "$tab</table>\n";
		}
		
		public function formulaireAjoutIntervenant($nombresTabulations = 0){
			$tab = ""; while($nombresTabulation = 0){ $tab .= "\t"; $nombresTabulations--; }
			
			if(isset($_GET['modifier_intervenant'])){ 
				$titre = "Modifier un intervenant";
				$Intervenant = new Intervenant($_GET['modifier_intervenant']);
				$nomModif = "value=\"{$Intervenant->getNom()}\"";
				$prenomModif = "value=\"{$Intervenant->getPrenom()}\"";
				$emailModif = "value=\"{$Intervenant->getEmail()}\"";
				$telephoneModif = "value=\"{$Intervenant->getTelephone()}\"";
			}
			else{
				$titre = "Ajouter un intervenant";
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
			if(isset($_GET['modifier_intervenant'])){ $valueSubmit = "Modifier l'intervenant"; }else{ $valueSubmit = "Ajouter l'intervenant"; }
			echo "$tab\t\t\t<td><input type=\"submit\" name=\"validerAjoutIntervenant\" value=\"{$valueSubmit}\" style=\"cursor:pointer;\"></td>\n";
			echo "$tab\t\t</tr>\n";
			
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";			
		}		
		
		public static function prise_en_compte_formulaire(){
			if(isset($_POST['validerAjoutIntervenant'])){
				$nom = $_POST['nom'];
				$prenom = $_POST['prenom'];
				$email = $_POST['email'];
				$telephone = $_POST['telephone'];
				if(true){ // Test de saisie
					$idPromotion = $_GET['idPromotion'];					
					if(isset($_GET['modifier_intervenant'])){
						Intervenant::modifier_intervenant($_GET['modifier_intervenant'], $nom, $prenom, $email, $telephone);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutIntervenant&modification_intervenant=1";
					}
					else{
						// C'est un nouveau intervenant
						Intervenant::ajouter_intervenant($nom, $prenom, $email, $telephone);
						$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutIntervenant&ajout_intervenant=1";
					}
					header("Location: $pageDestination");
				}
			}
		}
		
		public static function prise_en_compte_suppression(){
			if(isset($_GET['supprimer_intervenant'])){	
				$idPromotion = $_GET['idPromotion'];	
				if(true){ // Test de saisie
					Intervenant::supprimer_intervenant($_GET['supprimer_intervenant']);
					$pageDestination = "./index.php?idPromotion=$idPromotion&page=ajoutIntervenant&supprimer_intervenant=1";	
				}
			}
		}		
		
		public static function page_administration($nombreTabulations = 0){
			$tab = ""; while($nombreTabulations > 0){ $tab .= "\t"; $nombreTabulations--; }
			if(isset($_GET['ajout_intervenant'])){
				echo "$tab<p class=\"notificationAdministration\">L'intervenant a bien été ajouté</p>";
			}
			if(isset($_GET['modification_intervenant'])){
				echo "$tab<p class=\"notificationAdministration\">L'intervenant a bien été modifié</p>";
			}
			Intervenant::formulaireAjoutIntervenant($nombreTabulations + 1);
			echo "$tab<h1>Liste des intervenants</h1>\n";
			Intervenant::liste_Intervenant_to_table($nombreTabulations + 1);
		}
		
		public function toUl(){
			$string = "<ul>\n";
			foreach(Intervenant::$attributs as $att){
				$string .= "<li>$att : ".$this->$att."</li>\n";
			}
			return "$string</ul>\n";
		}
		
		public static function creer_table(){
			return Utils_SQL::sql_from_file("./sql/".Intervenant::$nomTable.".sql");
		}
		
		public static function supprimer_table(){
			return Utils_SQL::sql_supprimer_table(Intervenant::$nomTable);
		}
	}
