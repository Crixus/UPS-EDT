<?php
	session_start();
	
	// Redirection si l'utilisateur vas sur la page sans être connecté
	if (!isset($_SESSION['idUtilisateur'])) {
		header('Location: ./connexion.php');
	}
	
	// Informations de base de données + Utils
	include_once('./includes/infos_bdd.php');
	
	// Importation des classes (phase dev : a la fin les mettres 1 par 1 pour eviter de charger le serveur)
	$repertoire = opendir("./classes/");
	while ($fichier = readdir($repertoire)) {
		if ($fichier != '..' && $fichier != '.') {
			include_once("./classes/" . $fichier);
		}
	}
	
	// Serialisation / Unserialisation / Variable de Session Utilisateur (necessaire quand la variable de session est un objet
	// ATTENTION SI MISE A JOUR D'ETUDIANT !!!
	if (!isset($_SESSION['Utilisateur_Serialize'])) {
		switch ($_SESSION['Type']) {
			case "Etudiant":
				$_SESSION['Utilisateur'] = new Etudiant($_SESSION['idUtilisateur']);
				$_SESSION['Utilisateur_Serialize'] = serialize($_SESSION['Utilisateur']);
				break;
			case "Intervenant":
				$_SESSION['Utilisateur'] = new Intervenant($_SESSION['idUtilisateur']);
				$_SESSION['Utilisateur_Serialize'] = serialize($_SESSION['Utilisateur']);
				break;
			default :
				break;
		}
	} else {
		$_SESSION['Utilisateur'] = unserialize($_SESSION['Utilisateur_Serialize']);
	}
	
	require('./lib/fpdf.php');
	
	class EDT_PDF extends FPDF {
		function Header() {
			$debutSemaine = EmploiDuTemps::timestamp_debut_semaine($_GET['semaine']);
			$finSemaine = $debutSemaine + 604799;
			// Gestion heures d'été
			if (date('H:i:s', $finSemaine) == "22:59:59") {
				$finSemaine += 3600;
			}
			$dateDebutSemaine = date('d/m/Y', $debutSemaine);
			$dateFinSemaine = date('d/m/Y', $finSemaine);
			
			$this->SetY(5);
			$this->SetFont('Arial', 'I', 8); // Police Arial italique 8
			$this->Cell(0, 10, "Emploi du temps du " . $dateDebutSemaine . "au " . $dateFinSemaine, 0, 0, 'C'); 
			$this->Ln(20);
		}
		
		function Footer() {
			$this->SetY(-15); // Positionnement à 1,5 cm du bas
			$this->SetFont('Arial', 'I', 8); // Police Arial italique 8
			$this->Cell(0, 10, 'Emploi du temps', 0, 0, 'C'); // Numéro de page
		}
		
		function BasicTable($listeCoursSemaine) {
			$jours = Array(
				'Lundi' => 'Mon',
				'Mardi' => 'Tue',
				'Mercredi' => 'Wed',
				'Jeudi' => 'Thu',
				'Vendredi' => 'Fri',
				'Samedi' => 'Sat'
			);
								
			$this->SetFont('Arial', 'B', 5);
			
			// Génération de l'en tête
			$header = Array();
			for ($i = 7; $i <= 20; $i++) {
				array_push($header, $i."h");
				array_push($header, "");
				array_push($header, "");
				array_push($header, "");
			}
			
			// Ecriture de l'en tête			
			$this->Cell(17, 5, "Heures", 1);
			foreach ($header as $col) {
				$this->Cell(5, 5, $col, 1);
			}
			$this->Ln();
			
			foreach ($jours as $j => $t) {
				$coursDuJours = $listeCoursSemaine[$t];
				$nombresCours = sizeof($coursDuJours);
				$premierParcours = true;
				while ($premierParcours || sizeof($coursDuJours) > 0) {
					if ($premierParcours) {
						$this->Cell(17, 5, $j, 1);
					} else {
						$this->Cell(17, 5, "", 1);
					}
					
					$nbQuartsHeure = 0;
					while ($nbQuartsHeure < (14 * 4)) { 
						$heure = 7 + ((int)($nbQuartsHeure / 4));
						$heure = ($heure < 10) ? "0" . $heure : "" . $heure;
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
							if (isset($coursDuJours[$i])) {
								$idCours = $coursDuJours[$i];
								$_Cours = new V_Infos_Cours($idCours);
								if ($_Cours->commence_a_heure("" . $heure . ":" . $quart . ":00")) {
									$nbQuartsCours = $_Cours->nbQuartsHeure();
									
									$this->Cell(5 * $nbQuartsCours, 5, $_Cours->getNomUE(), 1, 0, 'C');
									$boolCours = true;
									$nbQuartsHeure += $nbQuartsCours;
									unset($coursDuJours[$i]); // Je supprime l'id du tableau
									break; // On sort de la boucle
								}
							}
						}
						if (!$boolCours) {
							$this->Cell(5, 5, "", 1); 
							$nbQuartsHeure ++;
						}
					} 
					$premierParcours = false;
					$this->Ln();
				}
			}
		}
	}
	
	
	
	// L : Landscape, mm : millimètre, format A4
	$pdf = new EDT_PDF('L', 'mm', 'A4');
	$pdf->SetFont('Arial', 'B', 16);
	$pdf->SetLeftMargin(0);
	$pdf->SetRightMargin(0);
	
	// Ajout d'une page
	$pdf->AddPage();
	
	$coursSemaine = EmploiDuTemps::cours_semaine($_SESSION['Type'], $_SESSION['Utilisateur']->getId(), EmploiDuTemps::timestamp_debut_semaine($_GET['semaine']));
	
	$pdf->BasicTable($coursSemaine);
	
	$pdf->Output();
