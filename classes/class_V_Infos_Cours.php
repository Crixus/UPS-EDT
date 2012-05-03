<?php
	/** 
	 * Classe V_Infos_Cours - Permet de gerer la vue V_Infos_Cours
	 */
	class V_Infos_Cours{
		
		public static $nomTable = "V_Infos_Cours";
		
		public static $attributs = Array(
			"nomUE",
			"prenomIntervenant",
			"nomIntervenant",
			"nomTypeCours",
			"tsDebut",
			"tsFin",
			"nomSalle",
			"nomBatiment"
		);
		
		/**
		 * Getter de l'id de la vue V_Infos_Cours
		 * @return int : id de V_Infos_Cours
		 */
		public function getId() {return $this->id;}
		
		/**
		 * Getter du nom de l'UE
		 * @return string : nomUE de V_Infos_Cours
		 */
		public function getNomUE() {return $this->nomUE;}
		
		/**
		 * Getter du nom de la salle
		 * @return string : nomSalle de V_Infos_Cours
		 */
		public function getNomSalle() {return $this->nomSalle;}
		
		/**
		 * Getter du prenom de l'intervenant
		 * @return string : prenomIntervenant de V_Infos_Cours
		 */
		public function getPrenomIntervenant() {return $this->prenomIntervenant;}
		
		/**
		 * Getter du nom de l'intervenant
		 * @return string : nomIntervenant de V_Infos_Cours
		 */
		public function getNomIntervenant() {return $this->nomIntervenant;}
		
		/**
		 * Getter du nom du type de cours
		 * @return string : nomTypeCours de V_Infos_Cours
		 */
		public function getNomTypeCours() {return $this->nomTypeCours;}
		
		/**
		 * Getter du nom du batiment
		 * @return string : nomBatiment de V_Infos_Cours
		 */
		public function getNomBatiment() {return $this->nomBatiment;}
		
		/**
		 * Constructeur de la classe V_Infos_Cours
		 * Récupère les informations de V_Infos_Cours dans la base de données depuis l'id
		 * @param $id : int id du V_Infos_Cours
		 */
		public function V_Infos_Cours($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".V_Infos_Cours::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (V_Infos_Cours::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Renvoi la liste des cours de la promotion
		 * @param $idPromotion : int id de la promotion
		 * @return List<V_Infos_Cours> liste des cours de la promotion
		 */
		public static function liste_cours($idPromotion) {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".V_Infos_Cours::$nomTable." WHERE idPromotion=? ORDER BY tsDebut");
				$req->execute(
					Array($idPromotion)
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
		
		/**
		 * Renvoi la liste des futurs cours de la promotion
		 * @param $idPromotion : int id de la promotion
		 * @return List<V_Infos_Cours> liste des futurs cours de la promotion
		 */
		public static function liste_cours_futur($idPromotion) {
			$listeId = Array();
			$date_now = date('Y-m-d 00:00:00');
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".V_Infos_Cours::$nomTable." WHERE idPromotion=? AND tsDebut > '".$date_now."' ORDER BY tsDebut");
				$req->execute(
					Array($idPromotion)
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
		
		/**
		 * Renvoi la liste des cours passés d'une UE
		 * @param $idPromotion : int id de la promotion
		 * @param $nomUE : string nom de l'UE
		 * @return List<V_Infos_Cours> liste des cours passé de l'UE
		 */
		public static function liste_cours_passe_par_UE($idPromotion, $nomUE) {
			$listeId = Array();
			$date_now = date('Y-m-d 00:00:00');
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".V_Infos_Cours::$nomTable." WHERE idPromotion=? AND nomUE = ? AND tsDebut < '".$date_now."' ORDER BY tsDebut");
				$req->execute(
					Array(
						$idPromotion,
						$nomUE
					)
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
		
		/**
		 * Renvoi la liste des futurs cours d'une UE
		 * @param $idPromotion : int id de la promotion
		 * @param $nomUE : string nom de l'UE
		 * @return List<V_Infos_Cours> liste des futurs cours de l'UE
		 */
		public static function liste_cours_futur_par_UE($idPromotion, $nomUE) {
			$listeId = Array();
			$date_now = date('Y-m-d 00:00:00');
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".V_Infos_Cours::$nomTable." WHERE idPromotion=? AND nomUE = ? AND tsDebut > '".$date_now."' ORDER BY tsDebut");
				$req->execute(
					Array(
						$idPromotion,
						$nomUE
					)
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
		
		/**
		 * Fonction testant l'existence d'un cours dans la vue V_Infos_Cours
		 * @param id : int id du cours
		 */
		public static function existe_cours($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".V_Infos_Cours::$nomTable." WHERE id=?");
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
		 * Renvoi le nombre de cours de la promotion
		 * @return id : int nombre de cours de la promotion
		 */
		public function getNbreCours($idPromotion) { 
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".V_Infos_Cours::$nomTable." WHERE idPromotion = ?");
				$req->execute(
					Array($idPromotion)
				);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne["nb"];
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Renvoi le nombre de cours de la promotion à venir
		 * @return id : int nombre de cours de la promotion à venir
		 */
		public function getNbreCoursFutur($idPromotion) { 
			$date_now = date('Y-m-d 00:00:00');
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT COUNT(id) AS nb FROM ".V_Infos_Cours::$nomTable." WHERE idPromotion = ? AND tsDebut > '".$date_now."'");
				$req->execute(
					Array($idPromotion)
				);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				return $ligne["nb"];
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Récupère l'heure du timestamp tsDebut correspondant à la date de début du cours
		 * @return string : string heureDebut du cours
		 */
		public function getHeureDebut() {
			$explode = explode(" ", $this->tsDebut);
			$heureDebut = $explode[1];
			$explode = explode(":", $heureDebut);
			$heureDebut = "$explode[0]:$explode[1]";
			return $heureDebut;
		}
		
		/**
		 * Récupère l'heure du timestamp tsFin correspondant à la date de fin du cours
		 * @return string : string heureFin du cours
		 */
		public function getHeureFin() {
			$explode = explode(" ", $this->tsFin);
			$heureFin = $explode[1];
			$explode = explode(":", $heureFin);
			$heureFin = "$explode[0]:$explode[1]";
			return $heureFin;
		}
		
		/**
		 * Verifie si l'heure placé en paramètre est celle du début du cours (utilisé pour savoir quand commence le cours)
		 * @param string : string correspondant à l'heure à comparer
		 * @return boolean : 1 si oui, 0 sinon
		 */
		public function commence_a_heure($heure) {
			$explode = explode(" ", $this->tsDebut);
			$heureDebut = $explode[1];
			return ($heure == $heureDebut);
		}
		
		/**
		 * Calcul le nombre de quarts d'heures entre le début et la fin du cours
		 * @return int : nombre de quarts d'heures
		 */
		public function nbQuartsHeure() {
			$time = (strtotime($this->tsFin) - strtotime($this->tsDebut)) / 900;
			return $time;
		}
	}
