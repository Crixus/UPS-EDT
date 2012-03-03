<?php
	class Option{
		
		public static $nomTable = "Options";
		
		public static $attributs = Array(
			"id",
			"nom",
			"valeur"
		);
		
		public function getId(){ return $this->id; }
		public function getNom(){ return $this->nom; }
		public function getValeur(){ return $this->valeur; }
		
		public function Option($nom){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Option::$nomTable." WHERE nom=?");
				$req->execute(
					Array($nom)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach(Option::$attributs as $att){
					$this->$att = $ligne["$att"];
				}
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function generer_array_options_style(){
			$options_style = Array();
			foreach(Type_Cours::liste_nom_type_cours() as $nom){
				$options_style["Arriere Plan $nom"] = "background_color_$nom";
				$options_style["Couleur Texte $nom"] = "color_$nom";
			}
			return $options_style;
		}
		
		public static function existe_option($nom){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(*) AS nb FROM ".Option::$nomTable." WHERE nom=?");
				$req->execute(
					Array($nom)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne['nb'] == 1;
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function valeur_from_nom($nom){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".Option::$nomTable." WHERE nom=?");
				$req->execute(
					Array($nom)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne['valeur'];
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function ajouter_option($nom, $valeur){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("INSERT INTO ".Option::$nomTable." VALUES(?, ?, ?)");
				
				$req->execute(
					Array(
						"",
						$nom, 
						$valeur
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function modifier_option($nom, $valeur){
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("UPDATE ".Option::$nomTable." SET valeur=? WHERE nom=?;");
				$req->execute(
					Array(
						$valeur,
						$nom
					)
				);
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function formulaire_modification_options_style_typeCours($nombreTabulations = 0){
			
			$tab = ""; for($i = 0 ; $i < $nombreTabulations ; $i++){ $tab .= "\t"; }
			
			echo "$tab<form id=\"administration_stype_typeCours\" method=\"post\">\n";
			echo "$tab\t<table>\n";
			foreach(Option::generer_array_options_style() as $label => $nom){
				$valeur = Option::valeur_from_nom($nom);
				echo "$tab\t\t<tr>\n";
				echo "$tab\t\t\t<td>$label</td>\n";
				echo "$tab\t\t\t<td><input type=\"text\" name=\"$nom\" value=\"$valeur\"/></td>\n";
				echo "$tab\t\t</tr>\n";
			}
			echo "$tab\t\t<tr>\n";
			echo "$tab\t\t\t<td></td>\n";
			echo "$tab\t\t\t<td><input type=\"submit\" name=\"valider_formulaire_administration_stype_typeCours\" value=\"Valider\"/></td>\n";
			echo "$tab\t\t</tr>\n";
			echo "$tab\t</table>\n";
			echo "$tab</form>\n";
		}
		
		public static function test_validation_formulaire_administration(){
			$name_formulaire = "valider_formulaire_administration_stype_typeCours";
			if(isset($_POST[$name_formulaire])){
				foreach(Option::generer_array_options_style() as $nom){
					if(Option::existe_option($nom)){
						// TEST SI COULEUR
						Option::modifier_option($nom, $_POST[$nom]);
					}
					else{
						// TEST SI COULEUR
						Option::ajouter_option($nom, $_POST[$nom]);
					}
					header("Location: index.php?idPromotion={$_GET['idPromotion']}&page=styleTypeCours");
				}
			}
		}
	}
