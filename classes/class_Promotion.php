<?php
	/*
	 * Classe qui permet de gérer les Promotions
	 */
	class Promotion {
		
		public static $nomTable = "Promotion";
		
		public static $attributs = Array(
			"nom",
			"annee",
			"tsDebut",
			"tsFin"
		);
		
		/**
		 * Getter du nom de la Promotion
		 * @return String : nom de la Promotion
		 */
		public function getNom() { 
			return $this->nom;
		}
		
		/**
		 * Getter de l'année de la Promotion
		 * @return int : annee de la Promotion
		 */
		public function getAnnee() { return $this->annee; }
		
		/**
		 * Getter du timestamp correspondant à la date de début de la Promotion
		 * @return timestamp : tsDebut de la Promotion
		 */
		public function getTsDebut() { return $this->tsDebut; }
		
		/**
		 * Getter du timestamp correspondant à la date de fin de la Promotion
		 * @return timestamp : tsFin de la Promotion
		 */
		public function getTsFin() { return $this->tsFin; }
		
		/**
		 * Constructeur de la classe Promotion
		 * Récupère les informations de Promotion dans la base de données depuis l'id
		 * @param $id : int id du Promotion
		 */
		public function Promotion($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Promotion::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Promotion::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Ajouter une promotion dans la base de données
		 * @param $nom : string nom de la promotion
		 * @param $annee : int année de la promotion
		 * @param $tsDebut : timestamp correspondant à la date de début de la promotion
		 * @param $tsFin : timestamp correspondant à la date de fin de la promotion
		 */
		public static function ajouter_promotion($nom, $annee, $tsDebut, $tsFin) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Promotion::$nomTable." VALUES(?, ?, ?, ?, ?)");
				
				$req->execute(
					Array(
						"",
						$nom, 
						$annee, 
						$tsDebut, 
						$tsFin
					)
				);			
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Modifier une promotion dans la base de données
		 * @param $idPromotion : int id de la promotion a modifiée
		 * @param $nom : string nom de la promotion
		 * @param $annee : int année de la promotion
		 * @param $tsDebut : timestamp correspondant à la date de début de la promotion
		 * @param $tsFin : timestamp correspondant à la date de fin de la promotion
		 */
		public static function modifier_promotion($idPromotion, $nom, $annee, $tsDebut, $tsFin) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Promotion::$nomTable." SET nom=?, annee=?, tsDebut=?, tsFin=? WHERE id=?;");
				$req->execute(
					Array(
						$nom, 
						$annee, 
						$tsDebut, 
						$tsFin,
						$idPromotion
					)
				);
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Fonction testant l'existence d'une promotion
		 * @param id : int id de la promotion
		 */
		public static function existe_promotion($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Promotion::$nomTable." WHERE id=?");
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
		 * Liste les informations des promotions
		 * @return List<Promotion> : informations des promotions
		 */
		public static function liste_promotion() {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Promotion::$nomTable." WHERE id!= 0 ORDER BY nom");
				$req->execute();
				while ($ligne = $req->fetch()) {
					array_push($listeId, $ligne['id']);
				}
				$req->closeCursor();
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeId;
		}
		
		/**
		 * Retourne le nombre de promotions enregistrées dans la base de données
		 * @return int : nombre de promotions
		 */
		public static function nb_promotion() {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".Promotion::$nomTable." WHERE id!= 0 ORDER BY nom");
				$req->execute();
				$ligne = $req->fetch();
				$req->closeCursor();
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $ligne['nb'];
		}
		
		/**
		 * Fonction utilisée pour l'affichage du select pour le choix de la promotion a sélectionnée
		 * @param $idPromotion : int id de la promotion sélectionnée (null initialement)
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public function liste_promotion_for_select($idPromotion = null, $nombreTabulations = 0) {
			$liste_promotion = Promotion::liste_promotion();
			$tab = ""; while ($nombreTabulations > 0) { $tab .= "\t"; $nombreTabulations--; }
			echo $tab."<select onChange='selection_promotion(this)'>\n";
			
			echo $tab."\t<option value= 0>--</option>\n";
			
			foreach ($liste_promotion as $idPromotionListe) {
				$_Promotion = new Promotion($idPromotionListe);
				$nom = $_Promotion->getNom();
				
				if ($idPromotionListe == $idPromotion)
					echo $tab."\t<option value={$idPromotionListe} selected>{$nom}</option>\n";
				else
					echo $tab."\t<option value={$idPromotionListe}>{$nom}</option>\n";
			}
			
			echo $tab."</select>\n";
		}
		
		/**
		 * Fonction utilisée pour l'affichage de la liste des promotions créés 
		 * @param $administration boolean : possibilité de modification si egal à 1 (ATTENTION : On ne peut supprimer une promotion)
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public static function liste_promotion_to_table($administration, $nombreTabulations = 0) {
			//Liste des promotions enregistrées dans la base de données
			$liste_promotion = Promotion::liste_promotion();
			$nbListePromotion = sizeof($liste_promotion);
			
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			
			if ($nbListePromotion == 0)
				echo $tab."<b>Aucunes promotions n'est enregistré</b>\n";
			else {
				echo $tab."<table class=\"table_liste_administration\">\n";
				
				echo $tab."\t<tr class=\"fondGrisFonce\">\n";
				
				echo $tab."\t\t<th>Nom</th>\n";
				echo $tab."\t\t<th>Année</th>\n";
				echo $tab."\t\t<th>Date</th>\n";
				
				if ($administration) {
					echo $tab."\t\t<th>Actions</th>\n";
				}
				echo $tab."\t</tr>\n";
				
				$cpt = 0;
				// Gestion de l'affichage des informations des promotions
				foreach ($liste_promotion as $idPromo) {
					$_Promotion = new Promotion($idPromo);
					
					$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
					
					echo $tab."\t<tr class=\"".$couleurFond."\">\n";
					$cptBoucle= 0;
					$valTemp="";
					$valTemp2="";
					foreach (Promotion::$attributs as $att) {
						if ($cptBoucle == 2)
							$valTemp = $_Promotion->$att;
						else if ($cptBoucle == 3) {
							$valTemp2 = $_Promotion->$att;
							echo $tab."\t\t<td>";
							Promotion::dateCours($valTemp, $valTemp2);
							echo "</td>\n";
						}
						else {
							echo $tab."\t\t<td>".$_Promotion->$att."</td>\n";
						}
						$cptBoucle++;
					}
					
					// Création des liens pour la modification et la suppression des promotions et gestion de l'URL 
					if ($administration) {
						$pageModification = "./index.php?page=ajoutPromotion&amp;modifier_promotion=$idPromo";
						if (isset($_GET['idPromotion'])) {
							$pageModification .= "&amp;idPromotion=".$_GET['idPromotion'];
						}
						echo $tab."\t\t<td>";
						echo "<a href=\"".$pageModification."\"><img src=\"../images/modify.png\" alt=\"icone de modification\" /></a>";
						echo "</td>\n";
					}
					echo $tab."\t</tr>\n";
				}
				
				echo $tab."</table>\n";
			}
		}
		
		/**
		 * Fonction utilisée pour l'affichage de la date de la promotion (dateDebut à dateFin)
		 * @param $dateDebut timestamp : correspondant à la date de début de la promotion
		 * @param $dateFin timestamp : correspondant à la date de fin de la promotion
		 */
		public function dateCours($dateDebut, $dateFin) {
			$chaineDateDebut = explode(' ', $dateDebut);
			$chaineJMADebut = explode('-', $chaineDateDebut[0]);

			$chaineDateFin = explode(' ', $dateFin);
			$chaineJMAFin = explode('-', $chaineDateFin[0]);
			
			if ($chaineJMADebut[2] == $chaineJMAFin[2]) {
				echo Promotion::getDate($chaineJMADebut[2], $chaineJMADebut[1], $chaineJMADebut[0]);
			}
			else {
				echo "Du ";
				echo Promotion::getDate($chaineJMADebut[2], $chaineJMADebut[1], $chaineJMADebut[0]);
				echo " au ";
				echo Promotion::getDate($chaineJMAFin[2], $chaineJMAFin[1], $chaineJMAFin[0]);
			}
		}
		
		/**
		 * Fonction utilisée par la fonction dateCours pour l'affichage de la date d'une promotion 
		 * @param $jour timestamp : int correspondant au nombre de jours d'une promotion 
		 * @param $mois timestamp : int correspondant au nombre de mois d'une promotion 
		 * @param $annee timestamp : int correspondant au nombre d'années d'une promotion 
		 */
		public function getDate($jour, $mois, $annee) {
			if ($jour == 1)  
				$numeroJour = '1er';
			else if ($jour < 10)
				$numeroJour = $jour[1];
			else 
				$numeroJour = $jour;
			
			$nomMois = "";
			switch ($mois) {
				case 1 : 
					$nomMois = 'Janvier';
					break;
				case 2 : 
					$nomMois = 'Fevrier';
					break;
				case 3 : 
					$nomMois = 'Mars';
					break;
				case 4 : 
					$nomMois = 'Avril';
					break;
				case 5 : 
					$nomMois = 'Mai';
					break;
				case 6 : 
					$nomMois = 'Juin';
					break;
				case 7 : 
					$nomMois = 'Juillet';
					break;
				case 8 : 
					$nomMois = 'Août';
					break;
				case 9 : 
					$nomMois = 'Septembre';
					break;
				case 10 : 
					$nomMois = 'Octobre';
					break;
				case 11 : 
					$nomMois = 'Novembre';
					break;
				case 12 : 
					$nomMois = 'Décembre';
					break;
			}
			
			echo "{$numeroJour} {$nomMois} {$annee}";
		}		
		
		/**
		 * Fonction utilisée pour l'affichage du formulaire utilisé pour l'ajout d'une promotion
		 * @param $idPromotion : int id de la promotion sélectionnée
		 * @param $nombreTabulations int : correspond au nombre de tabulations pour le fichier source
		 */
		public function formulaireAjoutPromotion($idPromotion, $nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			// Gestion du formulaire suivant si on ajoute ou on modifie une promotion
			if (isset($_GET['modifier_promotion'])) { 
				$titre = "Modifier une promotion";
				$_Promotion = new Promotion($_GET['modifier_promotion']);
				$nomModif = "value=\"{$_Promotion->getNom()}\"";
				$anneeModif = "value=\"{$_Promotion->getAnnee()}\"";
				$tsDebutModif = $_Promotion->getTsDebut();
				$tsFinModif = $_Promotion->getTsFin();
				$valueSubmit = "Modifier la promotion"; 
				$nameSubmit = "validerModificationPromotion";
				$hidden = "<input name=\"id\" type=\"hidden\" value=\"{$_GET['modifier_promotion']}\" />";
				$lienAnnulation = "index.php?page=ajoutPromotion";
				if (isset($_GET['idPromotion'])) {
					$lienAnnulation .= "&amp;idPromotion=".$_GET['idPromotion'];
				}
			}
			else {
				$titre = "Ajouter une promotion";
				$nomModif = "";
				$anneeModif = "";
				$valueSubmit = "Ajouter la promotion"; 
				$nameSubmit = "validerAjoutPromotion";
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
			echo $tab."\t\t\t<td><label>Annéee</label></td>\n";
			echo $tab."\t\t\t<td>\n";
			echo $tab."\t\t\t\t<input name=\"annee\" type=\"number\" min=\"2010\" max=\"9999\" required {$anneeModif}/>\n";
			echo $tab."\t\t\t</td>\n";
			echo $tab."\t\t</tr>\n";
			
			if (isset($tsDebutModif)) {
				$explode = explode(" ", $tsDebutModif);
				$valueDateDebut = "value=\"{$explode[0]}\" ";
				$explodeHeure = explode(":", $explode[1]);
				$valueHeureDebut = $explodeHeure[0];
				$valueMinuteDebut = $explodeHeure[1];
			}
			else {
				$valueDateDebut = "";
				$valueHeureDebut = "";
				$valueMinuteDebut = "";
			}
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td>Date Debut</td>\n";
			echo $tab."\t\t\t<td><input name=\"tsDebut\" type=\"date\" required $valueDateDebut/> aaaa-mm-jj</td>\n";
			echo $tab."\t\t</tr>\n";
			
			if (isset($tsFinModif)) {
				$explode = explode(" ", $tsFinModif);
				$valueDateFin = "value=\"{$explode[0]}\" ";
				$explodeHeure = explode(":", $explode[1]);
				$valueHeureFin = $explodeHeure[0];
				$valueMinuteFin = $explodeHeure[1];
			}
			else {
				$valueDateFin = "";
				$valueHeureFin = "";
				$valueMinuteFin = "";
			}
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td>Date Fin</td>\n";
			echo $tab."\t\t\t<td><input name=\"tsFin\" type=\"date\" required $valueDateFin/> aaaa-mm-jj</td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t\t<tr>\n";
			echo $tab."\t\t\t<td></td>\n";
			echo $tab."\t\t\t<td>".$hidden."<input type=\"submit\" name=\"".$nameSubmit."\" value=\"".$valueSubmit."\"></td>\n";
			echo $tab."\t\t</tr>\n";
			
			echo $tab."\t</table>\n";
			echo $tab."</form>\n";	

			if (isset($lienAnnulation)) {echo $tab."<p><a href=\"".$lienAnnulation."\">Annuler modification</a></p>";}	
		}
		
		/**
		 * Fonction permettant de prendre en compte les informations validées dans le formulaire pour la MAJ de la base de données
		 */
		public static function priseEnCompteFormulaire() {
			global $messagesNotifications, $messagesErreurs;
			if (isset($_POST['validerAjoutPromotion'])) {
				// Vérification des champs
				$nom = $_POST['nom'];
				$nomCorrect = true;
				$annee = $_POST['annee'];
				$annee_correct = true;
				$tsDebut = $_POST['tsDebut'];
				$tsDebut_correct = true;
				$tsFin = $_POST['tsFin'];
				$tsFin_correct = true;
				if ($nomCorrect && $annee_correct && $tsDebut_correct && $tsFin_correct) {
					// Ajout d'une nouvelle promotion
					Promotion::ajouter_promotion($nom, $annee, $tsDebut, $tsFin);
					array_push($messagesNotifications, "La promotion a bien été ajouté");
				}
				else {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
				}
			}
			else if (isset($_POST['validerModificationPromotion'])) {
				// Vérification des champs
				$id = $_POST['id']; 
				$idCorrect = Promotion::existe_promotion($id);
				$nom = $_POST['nom'];
				$nomCorrect = true;
				$annee = $_POST['annee'];
				$annee_correct = true;
				$tsDebut = $_POST['tsDebut'];
				$tsDebut_correct = true;
				$tsFin = $_POST['tsFin'];
				$tsFin_correct = true;
				if ($idCorrect && $nomCorrect && $annee_correct && $tsDebut_correct && $tsFin_correct) {
					// Modification d'une promotion
					Promotion::modifier_promotion($_GET['modifier_promotion'], $nom, $annee, $tsDebut, $tsFin);
					array_push($messagesNotifications, "La promotion a bien été modifié");
				}
				else {
					array_push($messagesErreurs, "La saisie n'est pas correcte");
				}
			}
		}
		
		/**
		* Fonction principale permettant l'affichage du formulaire d'ajout ou de modification d'une promotion ainsi que l'affichage des promotions enregistrées dans la base de données
		*/
		public static function pageAdministration($nombreTabulations = 0) {
			$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) { $tab .= "\t"; }
			Promotion::formulaireAjoutPromotion($nombreTabulations + 1);
			echo $tab."<h2>Liste des promotions</h2>\n";
			Promotion::liste_promotion_to_table(true, $nombreTabulations + 1);
		}
	}
