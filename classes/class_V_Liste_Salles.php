<?php
	class V_Liste_Salles {
		
		public static $nomTable = "V_Liste_Salles";
		
		public static $attributs = Array(
			"nomBatiment",
			"nomSalle",
			"capacite",
			"lat",
			"lon"
		);
		
		public function getId() {return $this->id;}
		public function getNomSalle() {return $this->nomSalle;}
		public function getNomBatiment() {return $this->nomBatiment;}
		
		public function V_Liste_Salles($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".V_Liste_Salles::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (V_Liste_Salles::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function liste_salles() {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".V_Liste_Salles::$nomTable." ORDER BY nomBatiment, nomSalle");
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
		
		public static function liste_salles_appartenant_typeCours($idTypeCours) {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM V_Liste_Salles 
					JOIN Appartient_Salle_TypeSalle ON Appartient_Salle_TypeSalle.idSalle = V_Liste_Salles.id
					JOIN Appartient_TypeSalle_TypeCours ON Appartient_TypeSalle_TypeCours.idTypeSalle = Appartient_Salle_TypeSalle.idTypeSalle
					WHERE Appartient_TypeSalle_TypeCours.idTypeCours = ?
					GROUP BY id
					ORDER BY nomBatiment, nomSalle");
				$req->execute(
					array($idTypeCours)
				);
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
	}
