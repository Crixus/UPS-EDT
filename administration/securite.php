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
		switch($_GET['page']) {
			case "ajoutBatiment":
				
				// Si tentative de modification et ajout en meme temps...
				if (isset($_GET['ajouter_batiment']) && isset($_GET['modifier_batiment'])) {
					header('Location:./index.php');
				}
				
				// Si modification ou ajout a partir d'une page ou l'on vient de supprimer...
				if (isset($_GET['supprimer_batiment']) && (isset($_GET['ajouter_batiment']) || isset($_GET['modifier_batiment']))) {
					// Cas ou l'on ajoute ou modifi a partir d'une page ou l'on vient de supprimer
					if (isset($_GET['ajouter_batiment'])) {
						// Redirection vers ajouter_batiment avec post
						$dest = "index.php?page=ajoutBatiment&ajouter_batiment=".$_GET['ajouter_batiment'];
					}
					else {
						// Redirection vers modifier_batiment avec post
						$dest = "index.php?page=ajoutBatiment&modifier_batiment=".$_GET['modifier_batiment'];
					}
					if (isset($_GET['idPromotion'])) {
						$dest .= "&idPromotion=".$_GET['idPromotion'];
					}
					// header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); // A verifier
					// header(dest)
				}
				
				// Si modification ou suppression d'un batiment inexistant...
				if (
					(isset($_GET['supprimer_batiment']) && !Batiment::existe_batiment($_GET['supprimer_batiment']))
					|| (isset($_GET['modifier_batiment']) && !Batiment::existe_batiment($_GET['modifier_batiment']))){
						header('Location: ./index.php');
				}
				break;
				
			case "ajoutSalle":
				if (isset($_GET['supprimer_salle']) && !Salle::existe_salle($_GET['supprimer_salle'])) {
					header('Location: ./index.php');
				}
				if (isset($_GET['modifier_salle']) && !Salle::existe_salle($_GET['modifier_salle'])) {
					header('Location: ./index.php');
				}
				break;
			case "ajoutTypeSalle":
				if (isset($_GET['supprimer_type_salle']) && !Type_Salle::existe_type_salle($_GET['supprimer_type_salle'])) {
					header('Location: ./index.php');
				}
				if (isset($_GET['modifier_type_salle']) && !Type_Salle::existe_type_salle($_GET['modifier_type_salle'])) {
					header('Location: ./index.php');
				}
				break;
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
			
			case "ajoutIntervenant":
				if (isset($_GET['supprimer_intervenant']) && !Intervenant::existe_intervenant($_GET['supprimer_intervenant'])) {
					header('Location: ./index.php');
				}
				if (isset($_GET['modifier_intervenant']) && !Intervenant::existe_intervenant($_GET['modifier_intervenant'])) {
					header('Location: ./index.php');
				}
				break;
			
			case "ajoutTypeCours":
				if (isset($_GET['supprimer_type_cours']) && !Type_Cours::existe_typeCours($_GET['supprimer_type_cours'])) {
					header('Location: ./index.php');
				}
				if (isset($_GET['modifier_type_cours']) && !Type_Cours::existe_typeCours($_GET['modifier_type_cours'])) {
					header('Location: ./index.php');
				}
				break;
				
			case "ajoutCours":
				if (isset($_GET['supprimer_cours']) && !V_Infos_Cours::existe_cours($_GET['supprimer_cours'])) {
					header('Location: ./index.php');
				}
				if (isset($_GET['modifier_cours']) && !V_Infos_Cours::existe_cours($_GET['modifier_cours'])) {
					header('Location: ./index.php');
				}
				break;
				
			case "ajoutSpecialite":
				if (isset($_GET['supprimer_specialite']) && !Specialite::existe_specialite($_GET['supprimer_specialite'])) {
					header('Location: ./index.php');
				}
				if (isset($_GET['modifier_specialite']) && !Specialite::existe_specialite($_GET['modifier_specialite'])) {
					header('Location: ./index.php');
				}
				break;
				
			case "ajoutUE":
				if (isset($_GET['supprimer_UE']) && !UE::existe_UE($_GET['supprimer_UE'])) {
					header('Location: ./index.php');
				}
				if (isset($_GET['modifier_UE']) && !UE::existe_UE($_GET['modifier_UE'])) {
					header('Location: ./index.php');
				}
				break;
			
			case "ajoutEtudiant":
				if (isset($_GET['supprimer_etudiant']) && !V_Infos_Etudiant::existe_etudiant($_GET['supprimer_etudiant'])) {
					header('Location: ./index.php');
				}
				if (isset($_GET['modifier_etudiant']) && !V_Infos_Etudiant::existe_etudiant($_GET['modifier_etudiant'])) {
					header('Location: ./index.php');
				}
				break;
			
			case "ajoutGroupeCours":
				if (isset($_GET['supprimer_groupeCours']) && !Groupe_Cours::existe_groupeCours($_GET['supprimer_groupeCours'])) {
					header('Location: ./index.php');
				}
				if (isset($_GET['modifier_groupeCours']) && !Groupe_Cours::existe_groupeCours($_GET['modifier_groupeCours'])) {
					header('Location: ./index.php');
				}
				break;
			
			case "ajoutGroupeEtudiants":
				if (isset($_GET['supprimer_groupeEtudiants']) && !Groupe_Etudiants::existe_groupeEtudiants($_GET['supprimer_groupeEtudiants'])) {
					header('Location: ./index.php');
				}
				if (isset($_GET['modifier_groupeEtudiants']) && !Groupe_Etudiants::existe_groupeEtudiants($_GET['modifier_groupeEtudiants'])) {
					header('Location: ./index.php');
				}
				break;
		}
	}
