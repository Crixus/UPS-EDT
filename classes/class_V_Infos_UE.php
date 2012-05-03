<?php
	/** 
	 * Classe V_Infos_UE - Permet de gerer la vue V_Infos_UE
	 */
	class V_Infos_UE{
		
		public static $nomTable = "V_Infos_UE";
		
		public static $attributs = Array(
			"nom",
			"intitule",
			"nbHeuresCours",
			"nbHeuresTD",
			"nbHeuresTP",
			"ECTS",
			"prenomResponsable",
			"nomResponsable"
		);
		
		/**
		 * Getter de l'id de la vue V_Infos_UE
		 * @return int : id de V_Infos_UE
		 */
		public function getId() {return $this->id;}
		
		/**
		 * Getter du nom de la salle de la vue V_Infos_UE
		 * @return string : nomSalle
		 */
		public function getNomSalle() {return $this->nomSalle;}
		
		/**
		 * Getter du nom du batiment de la vue V_Infos_UE
		 * @return string : nomBatiment
		 */
		public function getNomBatiment() {return $this->nomBatiment;}
		
		/**
		 * Constructeur de la classe V_Infos_UE
		 * Récupère les informations de V_Infos_UE dans la base de données depuis l'id
		 * @param $id : int id du V_Infos_UE
		 */
		public function V_Infos_UE($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".V_Infos_UE::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (V_Infos_UE::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Renvoi la liste des UE de la promotion
		 * @param $idPromotion : int id de la promotion
		 * @return List<V_Infos_UE> liste des UE de la promotion
		 */
		public static function liste_UE($idPromotion) {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT id FROM ".V_Infos_UE::$nomTable." WHERE idPromotion=?");
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
