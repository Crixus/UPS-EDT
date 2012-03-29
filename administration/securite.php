<?php
	// Test sur la promotion (voir si la promotion est choisie et sécurité)
	if (isset($_GET['idPromotion'])) {
		if (!Promotion::existe_promotion($_GET['idPromotion'])) { 
			header('Location: ./index.php');
		}
	}
	
	// Test sur la page (voir si page choisie et sécurité)
	$listePagesAdminPromo = Array(
		"ajoutCours.php", "ajoutEtudiant.php", "ajoutGroupeCours.php", "ajoutGroupeEtudiants.php", "ajoutSpecialite.php", "gestionPublication.php", "gestionGroupeEtudiants.php", "gestionGroupeCours.php", "listeCoursParUE.php"
	);
	$listePagesAdminHorsPromo = Array(
		"ajoutBatiment.php", "ajoutIntervenant.php", "ajoutPromotion.php", "ajoutSalle.php", "ajoutTypeSalle.php", "ajoutUE.php", "ajoutTypeCours.php", "listeInscriptionsUE.php", "styleTypeCours.php", "infosBatiment.php", "infosSalle.php"
	);
	
	// Test des pages accessible : promotion choisie ou non
	if (isset($_GET['page'])) {
		if (isset($_GET['idPromotion'])) {
			if (!in_array("{$_GET['page']}.php", $listePagesAdminHorsPromo) && !in_array("{$_GET['page']}.php", $listePagesAdminPromo)) {
				header('Location: ./index.php');
			}
		}
		else {
			if (!in_array("{$_GET['page']}.php", $listePagesAdminHorsPromo)) {
				header('Location: ./index.php');
			}
		}
	}
	
	if(isset($_GET['page'])){
		// Page de gestion
		switch($_GET['page']) {
			case "ajoutBatiment" :
				$implemented = true;
				$class = get_class(new Batiment(0)); // A l'arrache...
				$post_ajouter = 'validerAjoutBatiment';
				$post_modifier = 'validerModificationBatiment';
				$get_modifier = 'modifier_batiment';
				$get_supprimer = 'supprimer_batiment';
				$methode_existe = 'existe_batiment';
				break;
				
			case "ajoutSalle":
				$implemented = true;
				$class = get_class(new Salle(0));
				$post_ajouter = 'validerAjoutSalle';
				$post_modifier = 'validerModificationSalle';
				$get_modifier = 'modifier_salle';
				$get_supprimer = 'supprimer_salle';
				$methode_existe = 'existe_salle';
				break;
				
			case "ajoutTypeSalle":
				$implemented = true;
				$class = get_class(new Type_Salle(0));
				$post_ajouter = 'validerAjoutTypeSalle';
				$post_modifier = 'validerModificationTypeSalle';
				$get_modifier = 'modifier_type_salle';
				$get_supprimer = 'supprimer_type_salle';
				$methode_existe = 'existe_type_salle';
				break;
			
			default:
				$implemented = false;
				break;
		
		}
		
		if($implemented){
			// Si tentative de modification et ajout en meme temps...
			if (isset($_POST[$post_ajouter]) && isset($_POST[$post_modifier])) {
				header('Location:./index.php');
			}
			
			// Si modification ou ajout a partir d'une page ou l'on vient de supprimer...
			if (isset($_GET[$get_supprimer]) && (isset($_POST[$post_ajouter]) || isset($_GET[$post_modifier]))) {
				// Cas ou l'on ajoute ou modifie a partir d'une page ou l'on vient de supprimer
				if (isset($_POST[$post_ajouter])) {
					$dest = "index.php?page={$_GET['page']}";
				}
				else {
					// Redirection vers modifier_batiment avec post
					$dest = "index.php?page={$_GET['page']}&modifier_batiment=".$_GET[$get_modifier];
				}
				if (isset($_GET['idPromotion'])) {
					$dest .= "&idPromotion=".$_GET['idPromotion'];
				}
				unset($_GET[$get_supprimer]);
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); // A verifier
				header('Location: '.$dest);
			}
			
			// Si suppression d'un inexistant
			if (isset($_GET[$get_supprimer]) && !$class::$methode_existe($_GET[$get_supprimer])){
				header('Location: index.php');
			}
			
			// Si modification d'un inexistant
			if (isset($_GET[$get_modifier]) && !$class::$methode_existe($_GET[$get_modifier])){
				header('Location: index.php');
			}
		}
	}
	
	if(isset($_GET['page'])){
		// Autres pages
		switch($_GET['page']) {
			case "infosBatiment":
				if (!isset($_GET['idBatiment']) || (isset($_GET['idBatiment']) && !Batiment::existe_batiment($_GET['idBatiment']))) {
					header('Location: ./index.php');
				}
				break;	
			case "infosSalle":
				if (!isset($_GET['idSalle']) || (isset($_GET['idSalle']) && !Batiment::existe_batiment($_GET['idSalle']))) {
					header('Location: ./index.php');
				}
				break;
		}
	}
