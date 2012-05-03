<?php
	/**
	 * Classe permettant de gerer les options
	 * (couleurs, sma ...)
	 */
	class Options {
		
		public static $nomTable = "Options";
		
		public static $attributs = Array(
			"id",
			"nom",
			"valeur"
		);
		
		/**
		 * Getter de l'id de l'Options
		 * @return int id de l'Options
		 */
		public function getId() {
			return $this->id;
		}
		
		/**
		 * Getter du nom de l'Options
		 * @return String nom de l'Options
		 */
		public function getNom() {
			return $this->nom;
		}
		
		/**
		 * Getter de la valeur de l'Options
		 * @return String valeur de l'Options
		 */
		public function getValeur() {
			return $this->valeur;
		}
		
		/**
		 * Constructeur de la classe Options
		 * Récupère les informations de Options dans la base de données depuis l'id
		 * @param $id int id de l'Options
		 */
		public function Options($nom) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Options::$nomTable." WHERE nom=?");
				$req->execute(
					Array($nom)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (Options::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
				
			} catch (Exception $e) {
				echo "Erreur : " . $e->getMessage() . "<br />";
			}
		}
		
		/**
		 * Ajouter une Option dans la base de données
		 * @param $nom String nom de l'Option
		 * @param $valeur String valeur de l'Option
		 */
		public static function ajouter_Options($nom, $valeur) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Options::$nomTable." VALUES(?, ?, ?)");
				
				$req->execute(
					Array(
						"",
						$nom, 
						$valeur
					)
				);
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_Options($nom, $valeur) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Options::$nomTable." SET valeur=? WHERE nom=?;");
				$req->execute(
					Array(
						$valeur,
						$nom
					)
				);
			} catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function existe_Options($nom) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM ".Options::$nomTable." WHERE nom=?");
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
		
		public static function generer_array_Options_style() {
			$Options_style = Array();
			foreach (Type_Cours::liste_nom_type_cours() as $nom) {
				$Options_style["Arriere Plan $nom"] = "background_color_$nom";
				$Options_style["Couleur Texte $nom"] = "color_$nom";
			}
			return $Options_style;
		}
		
		public static function valeur_from_nom($nom) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT valeur FROM ".Options::$nomTable." WHERE nom=?");
				$req->execute(
					Array($nom)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne['valeur'];
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function toutes_valeurs_distinct() {
			$listeValeurs = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT DISTINCT valeur FROM ".Options::$nomTable."");
				$req->execute(
					Array()
					);
				while ($ligne = $req->fetch()) {
					array_push($listeValeurs, $ligne['valeur']);
				}
				$req->closeCursor();
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeValeurs;
		}
		
		public static function formulaire_modification_Options_style_typeCours($nombreTabulations = 0) {
			$tab = ""; 
			for ($i = 0; $i < $nombreTabulations; $i++) {
				$tab .= "\t";
			}
			
			echo $tab . "<h2>Gestion des couleurs de type de cours</h2>\n";
			$listeOptionStyle = Options::generer_array_Options_style();
			$nbOptionStyle = sizeof($listeOptionStyle);
			if ($nbOptionStyle == 0)
				echo $tab . "<b>Veuillez créer un type de cours dans un premier temps</b>\n";
			else {
				echo $tab . "<form id=\"administration_stype_typeCours\" method=\"post\">\n";
				echo $tab . "\t<table id=\"administration_style_typesCours\">\n";
				foreach ($listeOptionStyle as $label => $nom) {
					$valeur = Options::valeur_from_nom($nom);
					echo $tab . "\t\t<tr>\n";
					echo $tab . "\t\t\t<td>".$label."</td>\n";
					echo $tab . "\t\t\t<td><input type=\"color\" name=\"".$nom."\" value=\"".$valeur."\" /></td>\n";
					$class = "bg".substr($valeur, 1);
					echo $tab . "\t\t\t<td class=\"".$class."\"></td>\n";
					echo $tab . "\t\t</tr>\n";
				}
				echo $tab . "\t\t<tr>\n";
				echo $tab . "\t\t\t<td></td>\n";
				echo $tab . "\t\t\t<td><input type=\"submit\" name=\"valider_formulaire_administration_stype_typeCours\" value=\"Valider\"/></td>\n";
				echo $tab . "\t\t\t<td></td>\n";
				echo $tab . "\t\t</tr>\n";
				echo $tab . "\t</table>\n";
				echo $tab . "</form>\n";
				Options::afficher_apercu_style($nombreTabulations);
			}
		}
		
		public static function afficher_apercu_style($nombreTabulations = 0) {
			$tab = ""; 
			for ($i = 0; $i < $nombreTabulations; $i++) {
				$tab .= "\t";
			}
			
			echo $tab . "<table id=\"edt_semaine\">\n";
			foreach (Type_Cours::liste_nom_type_cours() as $nom) {
				echo $tab . "\t\t<tr>\n";
				echo $tab . "\t\t\t<td class=\"".$nom."\">".$nom."</td>\n";
				echo $tab . "\t\t</tr>\n";
			}
			echo $tab . "</table>\n";
		}
		
		public static function priseEnCompteFormulaire() {
			global $messagesNotifications, $messagesErreurs;
			$name_formulaire = "valider_formulaire_administration_stype_typeCours";
			if (isset($_POST[$name_formulaire])) {
				// Formulaire de modification (seul possible)
				foreach (Options::generer_array_Options_style() as $nom) {
					$_POST[$nom] = strtoupper($_POST[$nom]);
					if (PregMatch::est_couleur_avec_diez($_POST[$nom])) {
						if (Options::existe_Options($nom)) {
							Options::modifier_Options($nom, $_POST[$nom]);
						}
						else {
							Options::ajouter_Options($nom, $_POST[$nom]);
						}
					}
					else {
						// La couleur est incorrecte
						array_push($messagesErreurs, $_POST[$nom]." n'est pas une couleur valide");
					}
				}
				array_push($messagesNotifications, "Les couleurs ont bien étes appliquées");
			}
		}
	}
