<?php
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
		
		public function getId() {return $this->id;}
		public function getNom() {return $this->nom;}
		public function getDuree() { return $this->duree; }
		public function getEffectue() { return $this->effectue; }
		public function getNomUE() {return $this->nomUE;}
		public function getIdUE() { return $this->idUE; }
		public function getNomSalle() {return $this->nomSalle;}
		public function getNomBatiment() {return $this->nomBatiment;}
		public function getIdSalle() { return $this->idSalle; }
		public function getPrenomIntervenant() {return $this->prenomIntervenant;}
		public function getNomIntervenant() {return $this->nomIntervenant;}
		public function getIdIntervenant() { return $this->idIntervenant; }
		public function getNomTypeCours() {return $this->nomTypeCours;}
		public function getIdTypeCours() { return $this->idTypeCours; }
		public function getIdSeancePrecedente() { return $this->idSeancePrecedente; }
		public function getIdPromotion() { return $this->idPromotion; }
		
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
	
		
