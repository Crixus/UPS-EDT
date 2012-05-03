<?php
	/** 
	 * Classe Salle - Permet de gérer les Salle
	 */ 
	class Salle{
		
		public static $nomTable = "Salle";
		
		public static $attributs = Array(
			"id",
			"nom",
			"nomBatiment",
			"capacite"
		);
		
		/**
		 * Getter de l'id de la salle
		 * @return int : id de la salle
		 */
		public function getId() {
			return $this->id;
		}
		
		/**
		 * Getter du nom de la salle
		 * @return string : nom de la salle
		 */
		public function getNom() {
			return $this->nom;
		}
		
		/**
		 * Getter du nomBatiment de la salle
		 * @return string : nom du batiment
		 */
		public function getNomBatiment() {
			return $this->nomBatiment;
		}
		
		/**
		 * Getter du capacite de la salle
		 * @return int : capacite de l'étudiant
		 */
		public function getCapacite() {
			return $this->capacite;
		}
		
		/**
		 * Constructeur de la classe Salle
		 * Récupère les informations de Salle dans la base de données depuis l'id
		 * @param $id : int id du Salle
		 */
		public function Salle($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Salle::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
				);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Salle::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Ajouter une salle dans la base de données
		 * @param $nom : string nom de la salle
		 * @param $nomBatiment : string prenom de la salle
		 * @param $capacite : int email de la salle
		 */
		public static function ajouterSalle($nom, $nomBatiment, $capacite) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Salle::$nomTable." VALUES(?, ?, ?, ?)");
				
				$req->execute(
					Array(
						"",
						$nom,
						$nomBatiment, 
						$capacite
					)
				);			
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Modifier une salle dans la base de données
		 * @param $idSalle : int id de la salle a modifiée
		 * @param $nom : string nom de la salle
		 * @param $nomBatiment : string prenom de la salle
		 * @param $capacite : int email de la salle
		 */
		public static function modifierSalle($idSalle, $nom, $nomBatiment, $capacite) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Salle::$nomTable." SET nom=?, nomBatiment=?, capacite=? WHERE id=?;");
				$req->execute(
					Array(
						$nom, 
						$nomBatiment, 
						$capacite, 
						$idSalle
					)
				);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Supprime une salle dans la base de données
		 * @param $idSalle int : id de la salle a supprimé
		 */
		public static function supprimerSalle($idSalle) {
			if ($idSalle != 0) {
				Cours::modifierSalleToutCours($idSalle, 0);
				try {
					$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("DELETE FROM ".Salle::$nomTable." WHERE id=?;");
					$req->execute(
						Array($idSalle)
					);
				}
				catch (Exception $e) {
					echo "Erreur : ".$e->getMessage()."<br />";
				}	
			}	
		}
		
		/**
		 * Renvoi la liste d'id des salles
		 * @return List<Salle> liste des salles
		 */
		public static function listeIdsSalles() {
			$listeIdSalle = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".Salle::$nomTable." ORDER BY nomBatiment, nom");
				$req->execute(
					Array()
					);
				while ($ligne = $req->fetch()) {
					array_push($listeIdSalle, $ligne['id']);
				}
				$req->closeCursor();
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeIdSalle;
		}
		
		/**
		 * Fonction testant l'existence d'une salle
		 * @param id : int id de la salle
		 */
		public static function existeSalle($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Salle::$nomTable." WHERE id=?");
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
		 * Fonction testant l'existence d'une salle à partir de son nom
		 * @param nom: string nom de la salle
		 */
		public static function existe_nom_salle($nom) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Salle::$nomTable." WHERE nom=?");
				$req->execute(
					Array($nom)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne['nb'] >= 1;
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Fonction testant l'existence d'une salle à partir de son nom ainsi que du nom du batiment
		 * @param nom: string nom de la salle
		 * @param nomBatiment: string nom du batiment de la salle
		 */
		public static function existe_salle_nomSalle_nomBatiment($nomSalle, $nomBatiment) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Salle::$nomTable." WHERE nom=? AND nomBatiment=?");
				$req->execute(
					Array($nomSalle, $nomBatiment)
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
		 * Liste les informations de la salle dont l'id est en paramètre
		 * @return Salle : informations de la salle
		 */
		public function infosSalles($idSalle) {
			$listeNom = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Salle::$nomTable." WHERE id=?");
				$req->execute(
					Array($idSalle)
				);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Salle::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Liste les noms des batiments
		 * @return string : nom des batiments
		 */
		public function listeNomBatiment() {
			$listeNom = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT nom FROM ".Batiment::$nomTable." GROUP BY nom");
				$req->execute();
				while ($ligne = $req->fetch()) {
					array_push($listeNom, $ligne['nom']);
				}
				$req->closeCursor();
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeNom;
		}
		
		/**
		 * Fonction utilisée pour l'affichage du formulaire utilisé pour l'ajout d'une salle
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public function formulaireAjoutModificationSalle($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			
			// Liste des noms des batiments enregistrés dans la base de donnée
			$liste_nom_batiment = Salle::listeNomBatiment();
			$nbBatiment = sizeof($liste_nom_batiment);
			
			// Gestion du formulaire suivant si on ajoute ou on modifie une salle
			if (isset($_GET['modifier_salle'])) { 
				$titre = "Modifier une salle";
				$_salle = new Salle($_GET['modifier_salle']);
				$nomModif = "value=\"".$_salle->getNom()."\"";
				$nomBatimentModif = $_salle->getNomBatiment();
				$capaciteModif = "value=\"".$_salle->getCapacite()."\"";
				$valueSubmit = "Modifier la salle";
				$nameSubmit = "validerModificationSalle";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_salle']}\" />";
				$lienAnnulation = "index.php?page=ajoutSalle";
				if (isset($_GET['idPromotion'])) {
					$lienAnnulation .= "&amp;idPromotion=".$_GET['idPromotion'];
				}
			}
			else {
				$titre = "Ajouter une salle";
				$nomModif = (isset($_POST['nom'])) ? "value=\"{$_POST['nom']}\"" : "value=\"\"";
				$nomBatimentModif = (isset($_POST['nomBatiment'])) ? "value=\"{$_POST['nomBatiment']}\"" : "value=\"\"";
				$capaciteModif = (isset($_POST['capacite'])) ? "value=\"{$_POST['capacite']}\"" : "";
				$valueSubmit = "Ajouter la salle";
				$nameSubmit = "validerAjoutSalle";
				$hidden = "";
			}
			
			if ($nbBatiment <= 1)
				echo $tab."<h2>Vous devez d'aboir créer des batiments avant de créer des salles</h2><br/><br/>\n";
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
				echo $tab."\t\t\t<td><label>Capacité</label></td>\n";
				echo $tab."\t\t\t<td>\n";
				echo $tab."\t\t\t\t<input name=\"capacite\" type=\"number\" min=\"1\" max=\"999\" required {$capaciteModif}/>\n";
				echo $tab."\t\t\t</td>\n";
				echo $tab."\t\t</tr>\n";
				
				echo $tab."\t\t<tr>\n";
				echo $tab."\t\t\t<td><label>Bâtiment</label></td>\n";
				echo $tab."\t\t\t<td>\n";
				echo $tab."\t\t\t\t<select name=\"nomBatiment\" id=\"nomBatiment\">\n";
				
				foreach ($liste_nom_batiment as $nomBatiment) {
					if ($nomBatiment != 'DEFAULT') {
						if (isset($nomBatimentModif) && ($nomBatimentModif == $nomBatiment)) {
							$selected = "selected=\"selected\" ";
						} else {
							$selected = "";
						}
						echo $tab."\t\t\t\t\t<option value=\"".$nomBatiment."\" ".$selected.">$nomBatiment</option>\n";
					}
				}
				echo $tab."\t\t\t\t</select>\n";
				echo $tab."\t\t\t</td>\n";
				echo $tab."\t\t</tr>\n";
				
				echo $tab."\t\t<tr>\n";
				echo $tab."\t\t\t<td></td>\n";
				echo $tab."\t\t\t<td>".$hidden."<input type=\"submit\" name=\"".$nameSubmit."\" value=\"$valueSubmit\"></td>\n";
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
			if (isset($_POST['validerAjoutSalle'])) {
				// Vérification des champs
				$nom = htmlentities($_POST['nom'], ENT_QUOTES, 'UTF-8');
				$capacite = htmlentities($_POST['capacite']);
				$nomBatiment = htmlentities($_POST['nomBatiment']);
				$nomCorrect = true;
				$capacite_correct = PregMatch::est_nombre($capacite);
				$nomBatiment_correct = Batiment::existe_nom_batiment($nomBatiment);
				$_salle_inexistante = !Salle::existe_salle_nomSalle_nomBatiment($nom, $nomBatiment);
				if ($nomCorrect && $capacite_correct && $nomBatiment_correct && $_salle_inexistante) {
					// Ajout d'une nouvelle salle
					Salle::ajouterSalle($nom, $nomBatiment, $capacite);
					array_push($messagesNotifications, "La salle a bien été ajouté");
				}
				else {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
				}
			}
			else if (isset($_POST['validerModificationSalle'])) {
				// Vérification des champs
				$id = htmlentities($_POST['id']);
				$nom = htmlentities($_POST['nom'], ENT_QUOTES, 'UTF-8');
				$capacite = htmlentities($_POST['capacite']);
				$nomBatiment = htmlentities($_POST['nomBatiment']);
				$idCorrect = Salle::existeSalle($id);
				$nomCorrect = true; 
				$capacite_correct = PregMatch::est_nombre($capacite);
				$nomBatiment_correct = Batiment::existe_nom_batiment($nomBatiment);
				if ($idCorrect && $nomCorrect && $capacite_correct && $nomBatiment_correct) {
					// Modification d'une salle
					Salle::modifierSalle($_GET['modifier_salle'], $nom, $nomBatiment, $capacite);
					array_push($messagesNotifications, "La salle a bien étée modifiée");
				}
				else {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
				}
			}
		}
		
		/**
		 * Fonction permettant de prendre en compte la validation d'une demande de suppression d'une salle, on test s'il est bien enregistré dans la base de donnée
		 */
		public static function priseEnCompteSuppression() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_GET['supprimer_salle'])) {
				if (Salle::existeSalle($_GET['supprimer_salle'])) { // Test de saisie
					Salle::supprimerSalle($_GET['supprimer_salle']);
					array_push($messagesNotifications, "La salle à bien été supprimée");
				}
			}
		}
		
		/**
		 * Fonction utilisée pour l'affichage de la liste des salles créés 
		 * @param $administration boolean : possibilité de modification et suppression si egal à 1
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public static function table_administration_batiments($administration, $nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			
			//Liste des salles enregistrées dans la base de donnée
			$liste_id_salles = Salle::listeIdsSalles();
			$nbSalles = sizeof($liste_id_salles);
			
			//Liste des types de salles enregistrées dans la base de donnée
			$listeTypeSalle = Type_Salle::liste_id_type_salle();
			
			if ($nbSalles <= 1) {
				echo $tab."<b>Aucunes salles n'est enregistrées</b>\n";
			}
			else {			
				echo $tab."<table class=\"table_liste_administration\">\n";
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				echo $tab."\t\t<th rowspan=\"2\">Nom</th>\n";
				echo $tab."\t\t<th rowspan=\"2\">Batiment</th>\n";
				echo $tab."\t\t<th rowspan=\"2\">Salle</th>\n";
				echo $tab."\t\t<th rowspan=\"2\">Capacité</th>\n";
				echo $tab."\t\t<th colspan=\"".sizeof($listeTypeSalle)."\">Type de salles</th>\n";
				if ($administration) {
					echo $tab."\t\t<th rowspan=\"2\">Actions</th>\n";
				}
				echo $tab."\t</tr>\n";
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				
				foreach ($listeTypeSalle as $idType_Salle) {					
					$Type_Salle = new Type_Salle($idType_Salle);
					$nomType_Salle = $Type_Salle->getNom();
					echo $tab."\t\t<th>".$nomType_Salle."</th>\n";
				}
				echo $tab."\t</tr>\n";
				
				$cpt = 0;
				// Gestion de l'affichage des informations des salles		
				foreach ($liste_id_salles as $idSalle) {
					if ($idSalle != 0) {
						$_salle = new Salle($idSalle);
						$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
						$lienInfosSalle = "./index.php?page=infosSalle&amp;idSalle={$_salle->getId()}";
						if (isset($_GET['idPromotion'])) {
							$lienInfosSalle .= "&amp;idPromotion=".$_GET['idPromotion'];
						}
						
						echo $tab."\t<tr class=\"".$couleurFond."\">\n";
						echo $tab."\t\t<td>";
						echo "<a href=\"".$lienInfosSalle."\">".$_salle->getNomBatiment()." - ".$_salle->getNom()."</a>";
						echo "</td>\n";
						echo $tab."\t\t<td>".$_salle->getNomBatiment()."</td>\n";
						echo $tab."\t\t<td>".$_salle->getNom()."</td>\n";
						echo $tab."\t\t<td>".$_salle->getCapacite()."</td>\n";
										
						foreach ($listeTypeSalle as $idType_Salle) {					
							$Type_Salle = new Type_Salle($idType_Salle);
							$nomType_Salle = $Type_Salle->getNom();
							if (Type_Salle::appartientSalleTypeSalle($idSalle, $idType_Salle)) 
								$checked = "checked = \"checked\"";
							else
								$checked = "";
							$nomCheckbox = "{$idSalle}_{$nomType_Salle}";
							echo $tab."\t\t<td><input type=\"checkbox\" name= \"{$idSalle}_{$nomType_Salle}\" value=\"{$idType_Salle}\" onclick=\"appartenance_salle_typeSalle({$idSalle},{$idType_Salle},this)\" style=\"cursor:pointer\" {$checked}></td>\n";
						}
						
						// Création des liens pour la modification et la suppression des salles et gestion de l'URL 
						if ($administration) {
							$pageModification = "./index.php?page=ajoutSalle&amp;modifier_salle=$idSalle";
							$pageSuppression = "./index.php?page=ajoutSalle&amp;supprimer_salle=$idSalle";
							if (isset($_GET['idPromotion'])) {
								$pageModification .= "&amp;idPromotion=".$_GET['idPromotion'];
								$pageSuppression .= "&amp;idPromotion=".$_GET['idPromotion'];
							}
							echo $tab."\t\t<td>";
							echo "<a href=\"".$pageModification."\"><img alt=\"icone modification\" src=\"../images/modify.png\"></a>";
							echo "<a href=\"".$pageSuppression."\" onclick=\"return confirm('Supprimer la salle ?')\"><img alt=\"icone suppression\" src=\"../images/delete.png\" /></a>";
							echo "</td>";
						}
						echo $tab."\t</tr>\n";
					}
				}
				
				echo $tab."</table>\n";
			}
		}
		
		/**
		* Fonction principale permettant l'affichage du formulaire d'ajout ou de modification d'une salle ainsi que l'affichage des salles enregistrées dans la base de données
		*/
		public static function pageAdministration($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			Salle::formulaireAjoutModificationSalle($nombreTabulations);
			echo $tab."<h2>Liste des salles</h2>\n";
			Salle::table_administration_batiments($nombreTabulations);
		}
		
		/**
		 * Fonction utilisée pour l'affichage des informations de la salle
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public function pageInformations($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			$Batiment = Batiment::Batiment_from_nom($this->getNomBatiment());
			echo $tab."<h2>Salle {$this->getNomBatiment()} - {$this->getNom()}</h2>\n";
			echo $tab."<h3>Informations</h3>\n";
			echo $tab."<ul>\n";
			echo $tab."\t<li>Nom : {$this->getNom()}</li>\n";
			echo $tab."\t<li>Batiment : ".$this->getNomBatiment()."</li>\n";
			echo $tab."\t<li>Capacite : ".$this->getCapacite()."</li>\n";
			echo $tab."</ul>\n";
			$Batiment->pageInformations($nombreTabulations);
		}
	}
