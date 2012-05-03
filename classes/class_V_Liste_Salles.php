<?php
	/** 
	 * Classe V_Liste_Salles - Permet de gerer la vue V_Liste_Salles
	 */
	class V_Liste_Salles {
		
		public static $nomTable = "V_Liste_Salles";
		
		public static $attributs = Array(
			"nomBatiment",
			"nomSalle",
			"capacite",
			"lat",
			"lon"
		);
		
		/**
		 * Getter de l'id de la vue V_Liste_Salles
		 * @return int : id de V_Liste_Salles
		 */
		public function getId() {
			return $this->id;
		}
		
		/**
		 * Getter du nom de la salle de la vue V_Liste_Salles
		 * @return string : nomSalle
		 */
		public function getNomSalle() {
			return $this->nomSalle;
		}
		
		/**
		 * Getter du nom du batiment de la vue V_Liste_Salles
		 * @return string : nomBatiment
		 */
		public function getNomBatiment() {
			return $this->nomBatiment;
		}
		
		/**
		 * Constructeur de la classe V_Liste_Salles
		 * Récupère les informations de V_Liste_Salles dans la base de données depuis l'id
		 * @param $id : int id du V_Liste_Salles
		 */
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
		
		/**
		 * Renvoi la liste des salles
		 * @return List<V_Liste_Salles> liste des salles enregistrés dans la base de donnée
		 */
		public static function listeSalles() {
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
		
		/**
		 * Renvoi la liste des salles appartenant au type de cours placé en paramètre
		 * @param $idTypeCours : int id du type de cours sélectionné
		 * @return List<V_Liste_Salles> liste des salles enregistrés lié au type de cours placé en paramètre
		 */
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
