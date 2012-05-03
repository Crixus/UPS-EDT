<?php
	/** 
	 * Classe V_Cours_Etudiants - Permet de gerer la vue V_Cours_Etudiants
	 */
	class V_Cours_Etudiants{
		
		public static $nomTable = "V_Cours_Etudiants";
		
		public static $attributs = Array(
			"idCours",
			"idEtudiant"
		);
			
		/**
		 * Constructeur de la classe V_Cours_Etudiants
		 * Récupère les informations de V_Cours_Etudiants dans la base de données depuis l'id
		 * @param $id : int id du V_Cours_Etudiants
		 */
		public function V_Cours_Etudiants($id) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT * FROM ".V_Cours_Etudiants::$nomTable." WHERE id=?");
				$req->execute(
					Array($id)
					);
				$ligne = $req->fetch();
				$req->closeCursor();
				
				foreach (V_Cours_Etudiants::$attributs as $att) {
					$this->$att = $ligne[$att];
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		/**
		 * Renvoi la liste des cours de l'étudiant
		 * @param $idEtudiant : int id de l'étudiant
		 * @return List<V_Cours_Etudiants> liste des cours de l'étudiants
		 */
		public static function liste_idCours_Un_Etudiants($idEtudiant) {
			$listeId = Array();
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = $bdd->prepare("SELECT idCours FROM ".V_Cours_Etudiants::$nomTable." WHERE idEtudiant=?");
				$req->execute(
					Array($idEtudiant)
				);
				
				while ($ligne = $req->fetch()) {
					array_push($listeId, $ligne['idCours']);
				}
				$req->closeCursor();
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeId;
		}
	}
