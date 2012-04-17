<?php
	/** 
	 * Classe EmploiDuTemps - Permet d'afficher l'emploi du temps et de les gérer
	 */ 
	class EmploiDuTemps {
		
		/**
		 * Renvoi la liste des id de cours de l'utilisateur "$type" entre deux timestamp
		 * @return List<Int> liste des id de cours
		 * @param $type Type de l'utilisateur
		 * @param $id ID de l'utilisateur
		 * @param $tsDebut timestamp de début
		 * @param $tsFin timestamp de fin
		 */
		public static function liste_id_cours_entre_dates($type, $id, $tsDebut, $tsFin) {
			$listeId = Array();
			$tsDebut = date('Y-m-d', $tsDebut);
			$tsFin = date('Y-m-d', $tsFin);
			try {
				$pdoOptions[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdoOptions);
				$bdd->query("SET NAMES utf8");
				switch ($type) {
					case "Etudiant":
						$requete = "SELECT idCours AS id FROM V_Infos_Cours_Etudiants WHERE idEtudiant=? AND tsDebut>? AND tsFin<? ;";
						break;
					case "Intervenant":
						$requete = "SELECT id AS id FROM Cours WHERE idIntervenant=? AND tsDebut>? AND tsFin<? ;";
						break;
					default :
						break;
				}
				$req = $bdd->prepare($requete);
				$req->execute(
					Array($id, $tsDebut, $tsFin)
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
		 * Renvoi le timestamp correspondant au lundi 00:00:00 (début de semaine) du timestamp en argument
		 * @return int timestamp du debut de la semaine
		 * @param $tsDebut timestamp date de la semaine dont on veut savoir le timestamp de début de semaine
		 */
		public static function timestamp_debut_semaine($timestamp) {
			return mktime(0, 0, 0, date('n', $timestamp), date('j', $timestamp), date('Y', $timestamp)) - ((date('N', $timestamp) - 1) * 3600 * 24); 
		}
		
		/**
		 * Renvoi les cours de la semaine d'un utilisateur
		 * @return int timestamp du debut de la semaine
		 * @param $type Type de l'utilisateur
		 * @param $id ID de l'utilisateur
		 * @param $tsDebut timestamp de début de la semaine
		 */
		public static function cours_semaine($type, $id, $tsDebut) {
			// DIVISER LES COURS DE PLUSIEURS JOURS !!!
			$joursDeLaSemaine = 
				Array(
					"Mon" => Array(), "Tue" => Array(), "Wed" => Array(), "Thu" => Array(), "Fri" => Array(), "Sat" => Array(), "Sun" => Array()
				);
			$listeIdCoursSemaine = EmploiDuTemps::liste_id_cours_entre_dates($type, $id, $tsDebut, $tsDebut + 604799);
			
			foreach ($listeIdCoursSemaine as $idCours) {
				$_cours = new Cours($idCours);
				$jourDebut = date("D", strtotime($_cours->getTsDebut()));
				$jourFin = date("D", strtotime($_cours->getTsFin()));
				if ($jourDebut == $jourFin) {
					array_push($joursDeLaSemaine[$jourDebut], $_cours->getId());
				}
				else {
					// Gerer les cours sur plusieurs jours (les diviser)
				}
			}
			return $joursDeLaSemaine;
		}
		
		/**
		 * Affiche en HTML (emploi du temps) une liste de cours
		 * @param $listeCoursSemaine : liste de cours d'une semaine
		 */
		public static function liste_cours_vers_affichage_semaine($listeCoursSemaine) {
			$jours = Array(
				'Lundi' => 'Mon',
				'Mardi' => 'Tue',
				'Mercredi' => 'Wed',
				'Jeudi' => 'Thu',
				'Vendredi' => 'Fri',
				'Samedi' => 'Sat'
			);
?>
	<table id="edt_semaine">
		<tr class="fondGrisFonce">
			<th>Jour \ Heure</th>
			<?php 
				for ($nbH = 7; $nbH <= 20; $nbH ++) {
					echo "<th>$nbH"."h</th><th></th><th></th><th></th>\n";
				} 
			?>
		</tr>
<?php
	$cptStyle = 0;
	foreach ($jours as $j => $t) {
		$_coursDuJours = $listeCoursSemaine[$t];
		$nombresCours = sizeof($_coursDuJours);
		$premierParcours = true;
		while ($premierParcours || sizeof($_coursDuJours) > 0) {
			echo "<tr>\n";
			echo ($premierParcours) ? "<th>" . $j . "</th>\n" : "<th></th>\n";
			$nbQuartsHeure = 0;
			while ($nbQuartsHeure < (14 * 4)) { 
				$heure = 7 + ((int)($nbQuartsHeure / 4));
				$heure = ($heure < 10) ? "0$heure" : "$heure";
				$quart = $nbQuartsHeure % 4;
				switch ($quart) {
					case 0: $quart = "00"; break;
					case 1: $quart = "15"; break;
					case 2: $quart = "30"; break;
					case 3: $quart = "45"; break;
					default: $quart = "00"; break;
				}
				$boolCours = false;
				for ($i = 0; $i < $nombresCours; $i++) {
					if (isset($_coursDuJours[$i])) {
						$idCours = $_coursDuJours[$i];
						$_cours = new V_Infos_Cours($idCours);
						if ($_cours->commence_a_heure("" . $heure . ":" . $quart . ":00")) {
							$nbQuartsCours = $_cours->nbQuartsHeure();
							echo EmploiDuTemps::cours_to_td($_cours, $nbQuartsCours);
							$boolCours = true;
							$nbQuartsHeure += $nbQuartsCours;
							unset($_coursDuJours[$i]); // Je supprime l'id du tableau
							break; // On sort de la boucle
						}
					}
				}
				if (!$boolCours) {
					$style = intval($heure) % 2;
					$style = ($style == 0) ? "heurePaire" : "heureImpaire";
					echo "<td class=\"" . $style . "\"></td>"; 
					$nbQuartsHeure ++;
				}
			} 
			$premierParcours = false;
		}
	}
?>
		</tr>
	</table>
<?php
		}
		
		/**
		 * Renvoi le code HTML correspondant à la transformation d'un cours en <td> (case de tableau HTML)
		 * @param $_cours : Cours à transformer
		 * @param $colspan : Taille de la case (colspan)
		 * @return String Code html correspondant au <td> du cours
		 */
		public static function cours_to_td($_cours, $colspan) {
			$td = "";
			$td .= "<td class=\"" . $_cours->getNomTypeCours() . "\" colspan=\"" . $colspan . "\">\n";
			$prenom = $_cours->getprenomIntervenant();
			$td .= "" . $_cours->getHeureDebut() . " - " . $_cours->getHeureFin() . "<br />";
			$td .= "" . $_cours->getNomUE() . " - " . strtoupper($_cours->getNomTypeCours()) . "<br />";
			$td .= "" . $_cours->getNomBatiment() . " - " . $_cours->getNomSalle() . "<br />";
			$td .= "" .$prenom[0] . ". " . $_cours->getNomIntervenant() . "";
			$td .= "</td>"; 
			return $td;
		}
		
		/**
		 * Affiche en HTML (emploi du temps) d'une semaine à partir du timestamp de début de semaine
		 * @param $type Type de l'utilisateur
		 * @param $id ID de l'utilisateur
		 * @param $tsDebut timestamp de début de la semaine
		 */ 
		public static function affichage_edt_semaine_table($type, $id, $tsDebut) {
			$_cours_semaine = EmploiDuTemps::cours_semaine($type, $id, $tsDebut);
			EmploiDuTemps::liste_cours_vers_affichage_semaine($_cours_semaine);
		}
	}
