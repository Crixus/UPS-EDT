<?php
	// Test sur la promotion (voir si la promotion est choisie et sécurité)
	if(isset($_GET['idPromotion'])){
		if(!Promotion::existe_promotion($_GET['idPromotion'])){ 
			header('Location: ./index.php');
		}
	}
	
	// Test sur la page (voir si page choisie et sécurité)
	$listePagesAdminPromo = Array(
		"ajoutCours.php", "ajoutEtudiant.php", "ajoutGroupeCours.php", "ajoutGroupeEtudiants.php", "ajoutSpecialite.php", "gestionPublication.php", "gestionGroupeEtudiants.php", "gestionGroupeCours.php"
	);
	$listePagesAdminHorsPromo = Array(
		"ajoutBatiment.php", "ajoutIntervenant.php", "ajoutPromotion.php", "ajoutSalle.php", "ajoutTypeSalle.php", "ajoutUE.php", "ajoutTypeCours.php", "listeInscriptionsUE.php", "styleTypeCours.php", "infosBatiment.php", "infosSalle.php"
	);
	
	if(isset($_GET['page'])){
		if(isset($_GET['idPromotion'])){
			if(!in_array("{$_GET['page']}.php", $listePagesAdminHorsPromo) && !in_array("{$_GET['page']}.php", $listePagesAdminPromo)){
				header('Location: ./index.php');
			}
		}
		else{
			if(!in_array("{$_GET['page']}.php", $listePagesAdminHorsPromo)){
				header('Location: ./index.php');
			}
		}
		if(($_GET['page'] == "infosBatiment" && !isset($_GET['idBatiment'])) || ($_GET['page'] == "infosBatiment" && isset($_GET['idBatiment']) && !Batiment::existe_batiment($_GET['idBatiment']))){
			header('Location: ./index.php');
		}
		if(($_GET['page'] == "infosSalle" && !isset($_GET['idSalle'])) || ($_GET['page'] == "infosSalle" && isset($_GET['idSalle']) && !Batiment::existe_batiment($_GET['idSalle']))){
			header('Location: ./index.php');
		}
	}
