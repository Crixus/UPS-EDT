<?php

// Informations de base de données
include_once('../../includes/infos_bdd.php');
include_once("../../classes/class_V_Infos_UE.php");
include_once("../../classes/class_Inscription.php");
include_once("../../classes/class_V_Infos_Cours.php");
include_once("../../classes/class_Cours.php");

if ( (isset($_POST['idUE'])) && (isset($_POST['nomUE'])) && (isset($_POST['idPromotion'])) ) {

	$idUE = $_POST['idUE'];
	$nomUE = $_POST['nomUE'];
	$idPromotion = $_POST['idPromotion'];
	$tab = "";
	
	echo "$tab\t<h2>Informations de l'UE</h2>\n";
	echo "$tab<table class=\"table_liste_administration\" style=\"text-align:center;\">\n";	
	
	echo "$tab\t<tr class=\"fondGrisFonce\">\n";				
	echo "$tab\t\t<th colspan='2'>UE</th>\n";
	echo "$tab\t\t<th colspan='3'>Nombres d'heures</th>\n";
	echo "$tab\t\t<th rowspan='2'>ECTS</th>\n";
	echo "$tab\t\t<th rowspan='2'>Responsable</th>\n";
	echo "$tab\t\t<th rowspan='2'>Nombre<br>d'élèves inscrits</th>\n";				
	echo "$tab\t</tr>\n";	
	
	echo "$tab\t<tr class=\"fondGrisFonce\">\n";	
	echo "$tab\t\t<th>Nom</th>\n";		
	echo "$tab\t\t<th>Intitule</th>\n";		
	echo "$tab\t\t<th>Cours</th>\n";
	echo "$tab\t\t<th>TD</th>\n";
	echo "$tab\t\t<th>TP</th>\n";
	echo "$tab\t</tr>\n";
	
	echo "$tab\t<tr>\n";
	$cptBoucle=0;
	$val_temp = "";
	$UE = new V_Infos_UE($idUE);
	foreach(V_Infos_UE::$attributs as $att){
		if ($cptBoucle == 6)
			$val_temp = $UE->$att;
		else if ($cptBoucle == 7)
			echo "$tab\t\t<td>".$UE->$att." ".$val_temp."</td>\n";
		else
			echo "$tab\t\t<td>".$UE->$att."</td>\n";
		$cptBoucle++;
	}
	$nbreUE = Inscription::nbre_etudiant_inscrit($idUE);
	echo "$tab\t\t<td>".$nbreUE."</td>\n";
	echo "$tab\t</tr>\n";
	
	echo "$tab</table>\n";
	echo "$tab<br/>\n";
	
	
	$liste_cours_passe = V_Infos_Cours::liste_cours_passe_par_UE($idPromotion, $nomUE);
	$nbCoursPasse = sizeof($liste_cours_passe);
	if ($nbCoursPasse != 0) {
		echo "$tab\t<h2>Liste des cours terminés</h2>\n";
		
		echo "$tab<table class=\"table_liste_administration\">\n";		
		echo "$tab\t<tr class=\"fondGrisFonce\">\n";		
		echo "$tab\t\t<th>Intervenant</th>\n";
		echo "$tab\t\t<th>Type</th>\n";
		echo "$tab\t\t<th>Date</th>\n";
		echo "$tab\t\t<th>Salle</th>\n";
		echo "$tab\t</tr>\n";
		
		$cpt = 0;
		foreach($liste_cours_passe as $idCours){
			$Cours = new V_Infos_Cours($idCours);
			
			$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
			
			echo "$tab\t<tr class=\"$couleurFond\">\n";
			$cptBoucle=0;
			$valTemp="";
			$valTemp2="";
			foreach(V_Infos_Cours::$attributs as $att){
				if ( ($cptBoucle == 1) || ($cptBoucle == 4) || ($cptBoucle == 6) )
					$valTemp = $Cours->$att;
				else if ( ($cptBoucle == 2) || ($cptBoucle == 7) ) {
					$val = $Cours->$att." ".$valTemp;
					$valTemp="";
					echo "$tab\t\t<td>".$val."</td>\n";
				}
				else if ($cptBoucle == 5) {
					$valTemp2 = $Cours->$att;
					$val = "De ".$valTemp." à ".$valTemp2;
					echo "$tab\t\t<td>";
					Cours::dateCours($valTemp, $valTemp2);
					echo "</td>\n";
				}
				else if ($cptBoucle != 0){
					echo "$tab\t\t<td>".$Cours->$att."</td>\n";
				}
				$cptBoucle++;
			}
		}
		echo "$tab\t</tr>\n";
		echo "$tab</table>\n";
		
		echo "$tab<br/>\n";
	}
	
	
	echo "$tab\t<h2>Liste des cours à venir</h2>\n";
	$liste_cours_futur = V_Infos_Cours::liste_cours_futur_par_UE($idPromotion, $nomUE);
	$nbCoursFutur = sizeof($liste_cours_futur);
	
	if ($nbCoursFutur == 0) {
		echo "$tab<b>Aucun cours à venir n'est enregistré pour cette UE dans cette promotion</b>\n";
	}
	else {
	
		echo "$tab<table class=\"table_liste_administration\">\n";		
		echo "$tab\t<tr class=\"fondGrisFonce\">\n";		
		echo "$tab\t\t<th>Intervenant</th>\n";
		echo "$tab\t\t<th>Type</th>\n";
		echo "$tab\t\t<th>Date</th>\n";
		echo "$tab\t\t<th>Salle</th>\n";
		echo "$tab\t</tr>\n";
		
		$cpt = 0;
		foreach($liste_cours_futur as $idCours){
			$Cours = new V_Infos_Cours($idCours);
			
			$couleurFond = ($cpt == 0) ? "fondBlanc" : "fondGris"; $cpt++; $cpt %= 2;
			
			echo "$tab\t<tr class=\"$couleurFond\">\n";
			$cptBoucle=0;
			$valTemp="";
			$valTemp2="";
			foreach(V_Infos_Cours::$attributs as $att){
				if ( ($cptBoucle == 1) || ($cptBoucle == 4) || ($cptBoucle == 6) )
					$valTemp = $Cours->$att;
				else if ( ($cptBoucle == 2) || ($cptBoucle == 7) ) {
					$val = $Cours->$att." ".$valTemp;
					$valTemp="";
					echo "$tab\t\t<td>".$val."</td>\n";
				}
				else if ($cptBoucle == 5) {
					$valTemp2 = $Cours->$att;
					$val = "De ".$valTemp." à ".$valTemp2;
					echo "$tab\t\t<td>";
					Cours::dateCours($valTemp, $valTemp2);
					echo "</td>\n";
				}
				else if ($cptBoucle != 0){
					echo "$tab\t\t<td>".$Cours->$att."</td>\n";
				}
				$cptBoucle++;
			}
		}
		echo "$tab\t</tr>\n";
		echo "$tab</table>\n";
	}
}

?>