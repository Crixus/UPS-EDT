<?php
	class EmploiDuTemps{
		
		private $idEtudiant;
		
		private $dateDebut;
		private $dateFin;
		
		private $listeCours = Array();
		
		public function EmploiDuTemps(){
			
		}
		
		public static function liste_cours_etudiants_entre_dates($idEtudiant, $tsDebut, $tsFin){
			$tsDebut = date('Y-m-d',$tsDebut);
			$tsFin = date('Y-m-d',$tsFin);
			$listeId = Array();
			try{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWORD, $pdo_options);
				$bdd->query("SET NAMES utf8");
				$requete = "SELECT idCours FROM V_Infos_Cours_Etudiants WHERE idEtudiant=? AND tsDebut>? AND tsFin<?";
				$req = $bdd->prepare($requete);
				$req->execute(
					Array(
						$idEtudiant,
						$tsDebut,
						$tsFin
					)
				);
				
				while($ligne = $req->fetch()){
					array_push($listeId, $ligne['idCours']);
				}
					
				$req->closeCursor();
			}
			catch(Exception $e){
				echo "Erreur : ".$e->getMessage()."<br />";
			}
			return $listeId;
		}
		
		public static function timestamp_debut_semaine($timestamp_actuel){
			return mktime(0, 0, 0, date('n',$timestamp_actuel), date('j',$timestamp_actuel), date('Y',$timestamp_actuel)) - ((date('N',$timestamp_actuel)-1)*3600*24); 
		}
		
		public static function cours_etudiants_semaine($idEtudiant, $tsDebut){
			// DIVISER LES COURS DE PLUSIEURS JOURS !!!
			$joursDeLaSemaine = 
				Array(
					"Mon" => Array(),
					"Tue" => Array(),
					"Wed" => Array(),
					"Thu" => Array(),
					"Fri" => Array(),
					"Sat" => Array(),
					"Sun" => Array(),
				);
			
			$listeCoursSemaine = EmploiDuTemps::liste_cours_etudiants_entre_dates($idEtudiant, $tsDebut, $tsDebut + (7*24*3600-1));
			
			foreach($listeCoursSemaine as $idCours){
				$Cours = new Cours($idCours);
				$jour = date("D", strtotime($Cours->getTsDebut()));
				array_push($joursDeLaSemaine[$jour], $Cours->getId());
			}
			return $joursDeLaSemaine;
		}
		
		public static function affichage_edt_semaine_table($idEtudiant, $tsDebut){
			$jours = Array(
				'Lundi' => 'Mon',
				'Mardi' => 'Tue',
				'Mercredi' => 'Wed',
				'Jeudi' => 'Thu',
				'Vendredi' => 'Fri',
				'Samedi' => 'Sat'
			);
			
			$cours_etudiants_semaine = EmploiDuTemps::cours_etudiants_semaine($idEtudiant, $tsDebut);
?>
	<table id="edt_semaine">
		<tr class="fondGrisFonce">
			<th>Jour \ Heure</th>
			<?php for($nbH=7 ; $nbH <= 20 ; $nbH ++) { echo "<th>$nbH"."h</th><th></th><th></th><th></th>\n"; } ?>
		</tr>
<?php
	$cptStyle = 0;
	foreach($jours as $j => $t){
		$coursDuJours = $cours_etudiants_semaine[$t];
		echo "<tr>\n";
		echo "<th>$j</th>\n";
		$nbQuartsHeure = 0;
		while($nbQuartsHeure < (14*4)) { 
			$heure = 7 + ((int)($nbQuartsHeure / 4));
			if($heure < 10){ $heure = "0$heure"; }else{$heure = "$heure";}
			$quart = $nbQuartsHeure % 4;
			switch($quart){
				case 0: $quart = "00"; break;
				case 1: $quart = "15"; break;
				case 2: $quart = "30"; break;
				case 3: $quart = "45"; break;
			}
			$boolCours = false;
			foreach($coursDuJours as $idCours){
				$Cours = new V_Infos_Cours($idCours);
				if($Cours->commence_a_heure("$heure:$quart:00")){
					$nbQuartsCours = $Cours->nbQuartsHeure();
					
					echo "<td class=\"{$Cours->getNomTypeCours()}\" colspan=\"$nbQuartsCours\">";
					echo "{$Cours->getHeureDebut()} - {$Cours->getHeureFin()}<br />{$Cours->getNomUE()} - ".strtoupper($Cours->getNomTypeCours())."<br />{$Cours->getNomBatiment()} - {$Cours->getNomSalle()}";
					echo "</td>"; 
					$boolCours = true;
					$nbQuartsHeure += $nbQuartsCours;
				}
			}
			if(!$boolCours){
				$style = intval($heure)%2;
				if($style == 0){
					$style = "heurePaire";
				}
				else{
					$style = "heureImpaire";
				}
				echo "<td class=\"$style\"></td>"; 
				$nbQuartsHeure ++;
			}
		} 
	}
?>
		</tr>
	</table>
<?php
		}

	}
