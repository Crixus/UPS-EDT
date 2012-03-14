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
		switch($_GET['page']){
			case "ajoutBatiment":
				if(isset($_GET['supprimer_batiment']) && !Batiment::existe_batiment($_GET['supprimer_batiment'])){
					header('Location: ./index.php');
				}
				if(isset($_GET['modifier_batiment']) && !Batiment::existe_batiment($_GET['modifier_batiment'])){
					header('Location: ./index.php');
				}
				break;
			case "ajoutSalle":
				if(isset($_GET['supprimer_salle']) && !Salle::existe_salle($_GET['supprimer_salle'])){
					header('Location: ./index.php');
				}
				if(isset($_GET['modifier_salle']) && !Salle::existe_salle($_GET['modifier_salle'])){
					header('Location: ./index.php');
				}
				break;
			case "ajoutTypeSalle":
				if(isset($_GET['supprimer_type_salle']) && !Type_Salle::existe_type_salle($_GET['supprimer_type_salle'])){
					header('Location: ./index.php');
				}
				if(isset($_GET['modifier_type_salle']) && !Type_Salle::existe_type_salle($_GET['modifier_type_salle'])){
					header('Location: ./index.php');
				}
				break;
			case "infosBatiment":
				if(!isset($_GET['idBatiment']) || (isset($_GET['idBatiment']) && !Batiment::existe_batiment($_GET['idBatiment']))){
					header('Location: ./index.php');
				}
				break;	
			case "infosSalle":
				if(!isset($_GET['idSalle']) || (isset($_GET['idSalle']) && !Batiment::existe_batiment($_GET['idSalle']))){
					header('Location: ./index.php');
				}
				break;
		}
	}
