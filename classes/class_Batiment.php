<?php
	/** 
	 * Classe Batiment - Permet de gerer les batiments
	 */ 
	class Batiment {
		
		public static $nomTable = "Batiment";
		
		public static $attributs = Array(
			"id", "nom", "lat", "lon"
		);

		/**
		 * Getter de l'id du Batiment
		 * @return int id du Batiment
		 */
		public function getId() {
			return $this->id;
		}
		
		/**
		 * Getter du nom du Batiment
		 * @return String nom du Batiment
		 */
		public function getNom() {
			return $this->nom; 
		}
		
		/**
		 * Getter de la latitude du Batiment
		 * @return double latitude du Batiment
		 */
		public function getLat() {
			return $this->lat;
		}
		
		/**
		 * Getter de la longitude du Batiment
		 * @return double longitude du Batiment
		 */
		public function getLon() {
			return $this->lon;
		}
		
		/**
		 * Constructeur de la classe Batiment
		 * Récupère les informations de Batiment dans la base de données depuis l'id
		 * @param $id int id du Batiment
		 */
		public function Batiment($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Batiment::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Batiment::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Renvoi le Batiment a partir du nom de batiment (unique)
		 * Récupère les informations de Batiment dans la base de données depuis le nom
		 * @param $nom String nom du Batiment
		 * @return Batiment
		 */
		public static function Batiment_from_nom($nom) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".Batiment::$nomTable." WHERE nom=?");
				$req->execute(
					Array($nom)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return new Batiment($ligne['id']);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Ajouter un batiment dans la base de données
		 * @param $nom String nom du Batiment
		 * @param $lat double latitude du Batiment
		 * @param $lon double longitude du Batiment
		 */
		public static function ajouter_batiment($nom, $lat, $lon) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Batiment::$nomTable." VALUES(?, ?, ?, ?)");
				
				$req->execute(
					Array("", $nom, $lat, $lon)
				);			
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Modifie un batiment dans la base de données à partir de son id
		 * @param $idBatiment int id du Batiment à modifier
		 * @param $nom String nom du Batiment à modifier
		 * @param $lat double latitude du Batiment à modifier
		 * @param $lon double longitude du Batiment à modifier
		 */
		public static function modifierBatiment($idBatiment, $nom, $lat, $lon) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Batiment::$nomTable." SET nom=?, lat=?, lon=? WHERE id=?;");
				$req->execute(
					Array($nom, $lat, $lon, $idBatiment)
				);
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Supprime un batiment dans la base de données à partir de son id
		 * @param $idBatiment int id du Batiment à supprimer
		 */
		public static function supprimerBatiment($idBatiment) {
			if ($idBatiment != 0) {
				$_batiment = new Batiment($idBatiment);
				foreach ($_batiment->liste_salles() as $_salle) {
					Cours::modifier_salle_tout_cours($_salle->getId(), 0);
				}
				try {
					$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("DELETE FROM ".Batiment::$nomTable." WHERE id=?;");
					$req->execute(
						Array($idBatiment)
					);
				} catch (Exception $e) {
					echo "Erreur : ".$e->getMessage()."<br />";
				}
			}
		}
		
		/**
		 * Renvoi la liste des salles du batiment
		 * @return List<Salle> liste des salles du batiment
		 */
		public function liste_salles() {
			$listeSalle = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".Salle::$nomTable." WHERE nomBatiment=?");
				$req->execute(
					Array($this->nom)
					);
				while ($ligne = $req->fetch()) {
					array_push($listeSalle, new Salle($ligne['id']));
				}
				$req->closeCursor();
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeSalle;
		}
		
		/**
		 * Fonction testant l'existence d'un batiment
		 * @param $id : int id du batiment
		 */
		public static function existeBatiment($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Batiment::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne['nb'] == 1;
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Fonction testant l'existence d'un batiment avec le nom
		 * @param nom : string nom du batiment
		 */
		public static function existe_nom_batiment($nom) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Batiment::$nomTable." WHERE nom=?");
				$req->execute(
					Array($nom)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne['nb'] == 1;
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Fonction affichant la liste des salles avec nom et capacite
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public function table_salles($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			$liste_salles = $this->liste_salles();
			
			if (sizeof($liste_salles) == 0) {
				echo $tab."<p class=\"erreur\">Pas de salles</p>\n";	
			}
			else {
				echo $tab."<table class=\"table_liste_administration\">\n";			
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";			
				echo $tab."\t\t<th>Nom</th>\n";
				echo $tab."\t\t<th>Capacite</th>\n";
				echo $tab."\t</tr>\n";
				
				$cpt = 0;
				foreach ($liste_salles as $_salle) {
					$couleurFond = ($cpt == 0) ? "fondblanc" : "fondGris";
					$cpt++; $cpt %= 2;
					echo $tab."\t<tr class=\"".$couleurFond."\">\n";
					echo $tab."\t\t<td>".$_salle->getNom()."</td>\n";
					echo $tab."\t\t<td>".$_salle->getCapacite()."</td>\n";
					echo $tab."\t</tr>\n";
				}
				echo $tab."</table>\n";
			}
		}

		/**
		 * Fonction permettant d'enregistrer dans une liste les batiments enregistrés dans la base de donnée
		 * @return List<Batiment> liste des batiment
		 */
		public static function liste_batiment() {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".Batiment::$nomTable." ORDER BY UPPER(nom)");
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
		
		/**
		 * Fonction affichant la liste des batiments enregistrées en base de données
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public static function table_administration_batiments($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			$liste_batiment = Batiment::liste_batiment();			
			$nbBatiment = sizeof($liste_batiment);
			
			if ($nbBatiment <= 1) {
				echo $tab."<b>Aucun batiment n'est enregistré</b>\n";
			}
			else {
				echo $tab."<table class=\"table_liste_administration\">\n";			
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";			
				echo $tab."\t\t<th>Nom</th>\n";
				echo $tab."\t\t<th>Latitude</th>\n";
				echo $tab."\t\t<th>Longitude</th>\n";
				echo $tab."\t\t<th>Actions</th>\n";
				echo $tab."\t</tr>\n";
				
				$cpt = 0;
				foreach ($liste_batiment as $idBatiment) {
					if ($idBatiment != 0) {
						$_batiment = new Batiment($idBatiment);
						$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
						$lienInfosBatiment = "./index.php?page=infosBatiment&amp;idBatiment={$_batiment->getId()}";
						if (isset($_GET['idPromotion'])) {
							$lienInfosBatiment .= "&amp;idPromotion=".$_GET['idPromotion'];
						}
						
						echo $tab."\t<tr class=\"".$couleurFond."\">\n";
						echo $tab."\t\t<td>";
						echo "<a href=\"" . $lienInfosBatiment . "\">".$_batiment->getNom()."</a>";
						echo "</td>\n";
						echo $tab."\t\t<td>{$_batiment->getLat()}</td>\n";
						echo $tab."\t\t<td>{$_batiment->getLon()}</td>\n";

						$pageModification = "./index.php?page=ajoutBatiment&amp;modifier_batiment=$idBatiment";
						$pageSuppression = "./index.php?page=ajoutBatiment&amp;supprimer_batiment=$idBatiment";
						if (isset($_GET['idPromotion'])) {
							$pageModification .= "&amp;idPromotion=".$_GET['idPromotion'];
							$pageSuppression .= "&amp;idPromotion=".$_GET['idPromotion'];
						}
						echo $tab."\t\t<td>";
						echo "<a href=\"".$pageModification."\"><img alt=\"icone modification\" src=\"../images/modify.png\"></a>";
						echo "<a href=\"".$pageSuppression."\" onclick=\"return confirm('Supprimer le bâtiment ?')\"><img alt=\"icone suppression\" src=\"../images/delete.png\" /></a>";
						echo "</td>\n";
						echo $tab."\t</tr>\n";
					}
				}
				echo $tab."</table>\n";
			}
		}
		
		/**
		 * Fonction utilisée pour l'affichage du formulaire utilisé pour l'ajout d'un batiment
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public function formulaireAjoutBatiment($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			
			// Gestion du formulaire suivant si on ajoute ou on modifie un batiment
			if (isset($_GET['modifier_batiment'])) { 
				$titre = "Modifier un batiment";
				$_batiment = new Batiment($_GET['modifier_batiment']);
				$nomModif = "value=\"" . $_batiment->getNom() . "\"";
				$latModif = "value=\"" . $_batiment->getLat() . "\"";
				$lonModif = "value=\"" . $_batiment->getLon() . "\"";
				$valueSubmit = "Modifier le batiment"; 
				$nameSubmit = "validerModificationBatiment";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_batiment']}\" />";
				$lienAnnulation = "index.php?page=ajoutBatiment";
				if (isset($_GET['idPromotion'])) {
					$lienAnnulation .= "&amp;idPromotion=".$_GET['idPromotion'];
				}
			}
			else {
				$titre = "Ajouter un batiment";
				$nomModif = (isset($_POST['nom'])) ? "value=\"".$_POST['nom']."\"" : "value=\"\"";
				$latModif = (isset($_POST['lat'])) ? "value=\"".$_POST['lat']."\"" : "value=\"\"";
				$lonModif = (isset($_POST['lon'])) ? "value=\"".$_POST['lon']."\"" : "value=\"\"";
				$valueSubmit = "Ajouter le batiment"; 
				$nameSubmit = "validerAjoutBatiment";
				$hidden = "";
			}
			
			echo $tab."<h2>".$titre."</h2>\n";
			echo $tab."<form method=\"post\">\n";
			echo $tab."\t<table>\n";
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Nom</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"nom\" type=\"text\" required ".$nomModif." />\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Latitude</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"lat\" type=\"text\" ".$latModif." />\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td><label>Longitude</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"lon\" type=\"text\" ".$lonModif." />\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td></td>\n";
			echo $tab."\t\t\t<td>".$hidden."<input type=\"submit\" name=\"".$nameSubmit."\" value=\"".$valueSubmit."\"></td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t</table>\n";
			echo $tab."</form>\n";	
			
			// Lien permettant de terminer la modification d'un batiment et de revenir au formulaire pour l'ajout d'un nouveau batiment
			if (isset($lienAnnulation)) {echo $tab."<p><a href=\"".$lienAnnulation."\">Annuler modification</a></p>";}
		}	
		
		/**
		 * Fonction permettant de prendre en compte les informations validées dans le formulaire pour la MAJ de la base de données
		 */
		public static function prise_en_compte_formulaire() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_POST['validerAjoutBatiment'])) { //pour l'Ajout d'un batiment
				if (!isset($_POST['nom']) || !isset($_POST['lat']) || !isset($_POST['lon'])) {
					array_push($messagesErreurs, "Problème de formulaire");
				}
				else {
					$nom = htmlentities($_POST['nom'],ENT_QUOTES,'UTF-8');
					$lat = ($_POST['lat'] == '') ? NULL : htmlentities($_POST['lat']);
					$lon = ($_POST['lon'] == '') ? NULL : htmlentities($_POST['lon']);
					$nomCorrect = true; // Pas de vérifications spéciales pour un nom de batiment
					$lat_correct = ($lat == NULL || PregMatch::est_float($lat));
					$lon_correct = ($lon == NULL || PregMatch::est_float($lon));
					if ($nomCorrect && $lat_correct && $lon_correct) {
						Batiment::ajouter_batiment($nom, $lat, $lon);
						array_push($messagesNotifications, "Le bâtiment a bien été ajouté");
					}
					else {
						array_push($messagesErreurs, "La saisie n'est pas correcte");
						$nomCorrect ? true : array_push($messagesErreurs, "Le nom n'est pas correct");
						$lat_correct ? true : array_push($messagesErreurs, "La latitude n'est pas correcte");
						$lon_correct ? true : array_push($messagesErreurs, "La longitude n'est pas correcte");
					}
				}
			}
			if (isset($_POST['validerModificationBatiment'])) { //pour la modification d'un batiment
				$nom = htmlentities($_POST['nom'],ENT_QUOTES,'UTF-8');
				$lat = ($_POST['lat'] == '') ? NULL : htmlentities($_POST['lat']);
				$lon = ($_POST['lon'] == '') ? NULL : htmlentities($_POST['lon']);
				$id = $_POST['id'];
				$nomCorrect = true; // Pas de vérifications spéciales pour un nom de batiment
				$lat_correct = ($lat == NULL || PregMatch::est_float($lat));
				$lon_correct = ($lon == NULL || PregMatch::est_float($lon));
				$idCorrect = true;
				if ($nomCorrect && $lat_correct && $lon_correct && $idCorrect) {
					Batiment::modifierBatiment($id, $nom, $lat, $lon);
					array_push($messagesNotifications, "Le bâtiment a bien été modifié");
				}
				else {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
					$idCorrect  ? true : array_push($messagesErreurs, "id de modification n'est pas correct : Merci de contacter un administrateur");
					$nomCorrect ? true : array_push($messagesErreurs, "Le nom n'est pas correct");
					$lat_correct ? true : array_push($messagesErreurs, "La latitude n'est pas correcte");
					$lon_correct ? true : array_push($messagesErreurs, "La longitude n'est pas correcte");
				}
			}
		}
		
		/**
		 * Fonction permettant de prendre en compte la validation d'une demande de suppression d'un batiment, on test s'il est bien enregistré dans la base de donnée
		 */
		public static function prise_en_compte_suppression() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_GET['supprimer_batiment'])) {			
				if (Batiment::existeBatiment($_GET['supprimer_batiment'])) {
					// Le batiment existe
					Batiment::supprimerBatiment($_GET['supprimer_batiment']);
					array_push($messagesNotifications, "Le bâtiment a bien été supprimé");
				}
			}
		}
		
		/**
		* Fonction principale permettant l'affichage du formulaire d'ajout ou de modification d'un batiment ainsi que l'affichage des batiment enregistrée dans la base de données
		* @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		*/
		public static function pageAdministration($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			Batiment::formulaireAjoutBatiment($nombreTabulations);
			echo $tab."<h2>Liste des bâtiments</h2>\n";
			Batiment::table_administration_batiments($nombreTabulations);
		}
		
		/**
		* Fonction pour l'affichage des infos du batiment et de la liste de ses salles 
		* @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		*/
		public function page_informations($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			$latitude = ($this->getLat() == NULL) ? "<span class=\"erreur\">Pas de latitude saisie</span>" : $this->getLat();
			$longitude = ($this->getLon() == NULL) ? "<span class=\"erreur\">Pas de longitude saisie</span>" : $this->getLon();
			echo $tab."<h2>Bâtiment ".$this->getNom()."</h2>\n";
			echo $tab."<h3>Informations</h3>\n";
			echo $tab."<ul>\n";
			echo $tab."\t<li>Nom : ".$this->getNom()."</li>\n";
			echo $tab."\t<li>Latitude : $latitude</li>\n";
			echo $tab."\t<li>Longitude : $longitude</li>\n";
			echo $tab."</ul>\n";
			echo $tab."<h3>Liste des salles du bâtiment</h3>\n";
			$this->table_salles($nombreTabulations);
			if ($this->getLat() != NULL && $this->getLon() != NULL) {
				echo $tab."<h3>Plan Google Maps</h3>\n";
				echo $tab."<iframe width=\"700\" height=\"500\" frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\"http://maps.google.com/maps?q={$this->getLat()},+{$this->getLon()}+(Bâtiment {$this->getNom()})&amp;z=16&amp;output=embed\"></iframe>";
			}
		}
	}
