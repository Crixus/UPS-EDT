<?php

// Informations de base de données
include_once('../../includes/infos_bdd.php');
include_once("../../classes/class_V_Infos_Cours.php");
include_once("../../classes/class_Cours.php");

if ( (isset($_POST['nomUE'])) && (isset($_POST['idPromotion'])) ) {

	$nomUE = $_POST['nomUE'];
	$idPromotion = $_POST['idPromotion'];
	
	$liste_cours = V_Infos_Cours::liste_cours_futur_par_UE($idPromotion, $nomUE);
	$nbCours = sizeof($liste_cours);
	$tab = "";
	
	if ($nbCours == 0) {
		echo "$tab<b>Aucun cours à venir n'est enregistré pour cette promotion</b>\n";
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
		foreach($liste_cours as $idCours){
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
		
		echo "$tab</table>\n";
	}
}

?>