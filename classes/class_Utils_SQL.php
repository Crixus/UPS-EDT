<?php
	class Utils_SQL{
		
		public function sql_from_file($cheminFichier) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$req = "";
				$finRequete = false;
				$fichierSQL = file($cheminFichier);
				foreach ($fichierSQL AS $ligne) {
					if ($ligne[0] != "-" && $ligne[0] != "") {
						$req .= $ligne;
						$test = explode(";", $ligne);
						if (sizeof($test) > 1) {
							$finRequete = true;
						}
					}
					if ($finRequete) {
						$stmt = $bdd->prepare($req);
						$stmt->execute();
						$req = "";
						$finRequete = false;
					}
				}
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public function existeTable($nomTable) {
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				$requete = "SHOW TABLES FROM ".DB_NAME." LIKE \"$nomTable\"";
				$req = $bdd->query($requete);
				return ($req->rowCount() != 0);
			}
			catch (Exception $e) {
				echo "Erreur : ".$e->getMessage()."<br />";
			}
		}
		
		public static function sql_supprimer_table($nomTable) {
			if (Utils_SQL::existeTable($nomTable)) {
				try {
					$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
					$bdd->query("SET NAMES utf8");
					$req = $bdd->prepare("DROP TABLE ".$nomTable);
					$req->execute();
					return true;
				}
				catch (Exception $e) {
					echo "Erreur : ".$e->getMessage()."<br />";
					return false;
				}
			}
			else {
				return false;
			}
		}
	}
