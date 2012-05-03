<?php
	/** 
	 * Classe V_Infos_Seance_Promotion - Permet de gerer la vue V_Infos_Seance_Promotion
	 */
	class V_Infos_Seance_Promotion{
		
		public static $nomTable = "V_Infos_Seance_Promotion";
		
		public static $attributs = Array(
			"id",
			"nom",
			"idUE",	
			"nomUE",
			"idTypeCours",
			"nomTypeCours",			
			"idIntervenant",
			"prenomIntervenant",
			"nomIntervenant",
			"idSalle",
			"nomSalle",
			"nomBatiment",			
			"duree",
			"effectue",		
			"idSeancePrecedente",
			"idPromotion"
		);
		
		/**
		 * Getter de l'id de la vue V_Infos_Seance_Promotion
		 * @return int : id de V_Infos_Seance_Promotion
		 */
		public function getId() {
			return $this->id;
		}
		
		/**
		 * Getter du nom de la s�ance de la vue V_Infos_Seance_Promotion
		 * @return string : nom
		 */
		public function getNom() {return $this->nom;}
		
		/**
		 * Getter de la dur�e de la s�ance de la vue V_Infos_Seance_Promotion
		 * @return string : duree
		 */
		public function getDuree() { return $this->duree; }
		
		/**
		 * Getter du boolean effectue (1 si la seance a �t� effectu�) de la vue V_Infos_Seance_Promotion
		 * @return boolean : effectue
		 */
		public function getEffectue() { return $this->effectue; }
		
		/**
		 * Getter du nom de l'UE de la vue V_Infos_Seance_Promotion
		 * @return string : nomUE
		 */
		public function getNomUE() {return $this->nomUE;}
		
		/**
		 * Getter de idUE de la vue V_Infos_Seance_Promotion
		 * @return int : idUE
		 */
		public function getIdUE() { return $this->idUE; }
		
		/**
		 * Getter du nom de la salle de la vue V_Infos_Seance_Promotion
		 * @return string : nomSalle
		 */
		public function getNomSalle() {return $this->nomSalle;}
		
		/**
		 * Getter du nom du batiment de la vue V_Infos_Seance_Promotion
		 * @return string : nomBatiment
		 */
		public function getNomBatiment() {return $this->nomBatiment;}
		
		/**
		 * Getter de idSalle de la vue V_Infos_Seance_Promotion
		 * @return int : idSalle
		 */
		public function getIdSalle() { return $this->idSalle; }
		
		/**
		 * Getter du prenom de l'intervenant de la vue V_Infos_Seance_Promotion
		 * @return string : prenomIntervenant
		 */
		public function getPrenomIntervenant() {return $this->prenomIntervenant;}
		
		/**
		 * Getter du nom de l'intervenant de la vue V_Infos_Seance_Promotion
		 * @return string : nomIntervenant
		 */
		public function getNomIntervenant() {return $this->nomIntervenant;}
		
		/**
		 * Getter de idIntervenant de la vue V_Infos_Seance_Promotion
		 * @return int : idIntervenant
		 */
		public function getIdIntervenant() { return $this->idIntervenant; }
		
		/**
		 * Getter du nom du type de cours de la vue V_Infos_Seance_Promotion
		 * @return string : nomTypeCours 
		 */
		public function getNomTypeCours() {return $this->nomTypeCours;}
		
		/**
		 * Getter de idTypeCours de la vue V_Infos_Seance_Promotion
		 * @return int : idTypeCours 
		 */
		public function getIdTypeCours() { return $this->idTypeCours; }
		
		/**
		 * Getter de l'id de la s�ance pr�c�dente de la vue V_Infos_Seance_Promotion
		 * @return int : idSeancePrecedente
		 */
		public function getIdSeancePrecedente() { return $this->idSeancePrecedente; }
		
		/**
		 * Getter de l'id de la promotion li� � la s�ance de la vue V_Infos_Seance_Promotion
		 * @return int : idPromotion
		 */
		public function getIdPromotion() { return $this->idPromotion; }
		
		/**
		 * Constructeur de la classe V_Infos_Seance_Promotion
		 * R�cup�re les informations de V_Infos_Seance_Promotion dans la base de donn�es depuis l'id
		 * @param $id : int id du V_Infos_Seance_Promotion
		 */
		public function V_Infos_Seance_Promotion($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".V_Infos_Seance_Promotion::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (V_Infos_Seance_Promotion::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Renvoi la liste des s�ances de la promotion
		 * @param $idPromotion : int id de la promotion
		 * @return List<V_Infos_Seance_Promotion> liste des s�ances de la promotion
		 */
		public static function liste_seance($idPromotion) {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".V_Infos_Seance_Promotion::$nomTable." WHERE idPromotion=? ORDER BY nomUE, nom");
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
	}
	
		
