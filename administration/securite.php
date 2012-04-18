<?php
	// Test sur la promotion (voir si la promotion est choisie et sécurité)
	if (isset($_GET['idPromotion'])) {
		if (!Promotion::existe_promotion($_GET['idPromotion'])) { 
			header('Location: ./index.php');
		}
	}
	
	// Test sur la page (voir si page choisie et sécurité)
	$listePagesAdminPromo = Array(
		"ajoutCours.php", "ajoutEtudiant.php", "ajoutGroupeCours.php", "ajoutGroupeEtudiants.php", 
		"ajoutSpecialite.php", "gestionPublication.php", "gestionGroupeEtudiants.php", 
		"gestionGroupeCours.php", "listeCoursParUE.php"
	);
	$listePagesAdminHorsPromo = Array(
		"ajoutBatiment.php", "ajoutIntervenant.php", "ajoutPromotion.php", "ajoutSalle.php",
		"ajoutTypeSalle.php", "ajoutUE.php", "ajoutTypeCours.php", "listeInscriptionsUE.php",
		"styleTypeCours.php", "infosBatiment.php", "infosSalle.php", "ajoutCreneauSalle.php",
		"ajoutCreneauIntervenant.php"
	);
	
	// Test des pages accessible : promotion choisie ou non
	if (isset($_GET['page'])) {
		if (isset($_GET['idPromotion'])) {
			if (!in_array($_GET['page'] . ".php", $listePagesAdminHorsPromo) && !in_array($_GET['page'] . ".php", $listePagesAdminPromo)) {
				header('Location: ./index.php');
			}
		} else {
			if (!in_array($_GET['page'] . ".php", $listePagesAdminHorsPromo)) {
				header('Location: ./index.php');
			}
		}
	}
	
	if (isset($_GET['page'])) {
		// Page de gestion
		switch ($_GET['page']) {
			case "ajoutBatiment" :
				$implemented = true;
				$class = get_class(new Batiment(0)); // A l'arrache...
				$postAjouter = 'validerAjoutBatiment';
				$postModifier = 'validerModificationBatiment';
				$getModifier = 'modifier_batiment';
				$getSupprimer = 'supprimer_batiment';
				$methodeExiste = 'existe_batiment';
				break;
				
			case "ajoutSalle":
				$implemented = true;
				$class = get_class(new Salle(0));
				$postAjouter = 'validerAjoutSalle';
				$postModifier = 'validerModificationSalle';
				$getModifier = 'modifier_salle';
				$getSupprimer = 'supprimer_salle';
				$methodeExiste = 'existe_salle';
				break;
				
			case "ajoutTypeSalle":
				$implemented = true;
				$class = get_class(new Type_Salle(0));
				$postAjouter = 'validerAjoutTypeSalle';
				$postModifier = 'validerModificationTypeSalle';
				$getModifier = 'modifier_type_salle';
				$getSupprimer = 'supprimer_type_salle';
				$methodeExiste = 'existe_type_salle';
				break;
			
			case "ajoutIntervenant":
				$implemented = true;
				$class = get_class(new Intervenant(0));
				$postAjouter = 'validerAjoutIntervenant';
				$postModifier = 'validerModificationIntervenant';
				$getModifier = 'modifier_intervenant';
				$getSupprimer = 'supprimer_intervenant';
				$methodeExiste = 'existe_intervenant';
				break;
			
			case "ajoutTypeCours":
				$implemented = true;
				$class = get_class(new Type_Cours(0));
				$postAjouter = 'validerAjoutTypeCours';
				$postModifier = 'validerModificationTypeCours';
				$getModifier = 'modifier_type_cours';
				$getSupprimer = 'supprimer_type_cours';
				$methodeExiste = 'existe_typeCours';
				break;
			
			case "ajoutSpecialite":
				$implemented = true;
				$class = get_class(new Specialite(0));
				$postAjouter = 'validerAjoutSpecialite';
				$postModifier = 'validerModificationSpecialite';
				$getModifier = 'modifier_specialite';
				$getSupprimer = 'supprimer_specialite';
				$methodeExiste = 'existe_specialite';
				break;
			
			case "ajoutUE":
				$implemented = true;
				$class = get_class(new UE(0));
				$postAjouter = 'validerAjoutUE';
				$postModifier = 'validerModificationUE';
				$getModifier = 'modifier_UE';
				$getSupprimer = 'supprimer_UE';
				$methodeExiste = 'existe_UE';
				break;
			
			case "ajoutEtudiant":
				$implemented = true;
				$class = get_class(new Etudiant(0));
				$postAjouter = 'validerAjoutEtudiant';
				$postModifier = 'validerModificationEtudiant';
				$getModifier = 'modifier_etudiant';
				$getSupprimer = 'supprimer_etudiant';
				$methodeExiste = 'existe_etudiant';
				break;
			
			case "ajoutGroupeCours":
				$implemented = true;
				$class = get_class(new Groupe_Cours(0));
				$postAjouter = 'validerAjoutGroupeCours';
				$postModifier = 'validerModificationGroupeCours';
				$getModifier = 'modifier_groupeCours';
				$getSupprimer = 'supprimer_groupeCours';
				$methodeExiste = 'existe_groupeCours';
				break;
			
			case "ajoutGroupeEtudiants":
				$implemented = true;
				$class = get_class(new Groupe_Etudiants(0));
				$postAjouter = 'validerAjoutGroupeEtudiants';
				$postModifier = 'validerModificationGroupeEtudiants';
				$getModifier = 'modifier_groupeEtudiants';
				$getSupprimer = 'supprimer_groupeEtudiants';
				$methodeExiste = 'existe_groupeEtudiants';
				break;
			
			case "ajoutCreneauIntervenant":
				$implemented = true;
				$class = get_class(new Creneau_Intervenant(0));
				$postAjouter = 'validerAjoutCreneauIntervenant';
				$postModifier = 'validerModificationCreneauIntervenant';
				$getModifier = 'modifier_creneauIntervenant';
				$getSupprimer = 'supprimer_creneauIntervenant';
				$methodeExiste = 'existe_creneauIntervenant';
				break;
			
			case "ajoutCreneauSalle":
				$implemented = true;
				$class = get_class(new Creneau_Salle(0));
				$postAjouter = 'validerAjoutCreneauSalle';
				$postModifier = 'validerModificationCreneauSalle';
				$getModifier = 'modifier_creneauSalle';
				$getSupprimer = 'supprimer_creneauSalle';
				$methodeExiste = 'existe_creneauSalle';
				break;
				
			default:
				$implemented = false;
				break;
		
		}
		
		if ($implemented) {
			// Si tentative de modification et ajout en meme temps...
			if (isset($_POST[$postAjouter]) && isset($_POST[$postModifier])) {
				header('Location:./index.php');
			}
			
			// Si modification ou ajout a partir d'une page ou l'on vient de supprimer...
			if (isset($_GET[$getSupprimer]) && (isset($_POST[$postAjouter]) || isset($_GET[$postModifier]))) {
				// Cas ou l'on ajoute ou modifie a partir d'une page ou l'on vient de supprimer
				if (isset($_POST[$postAjouter])) {
					$dest = "index.php?page=" . $_GET['page'];
				} else {
					// Redirection vers modifier_batiment avec post
					$dest = "index.php?page=" . $_GET['page'] . "&modifier_batiment=" . $_GET[$getModifier];
				}
				if (isset($_GET['idPromotion'])) {
					$dest .= "&idPromotion=".$_GET['idPromotion'];
				}
				unset($_GET[$getSupprimer]);
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); // A verifier
				header('Location: '.$dest);
			}
			
			// Si suppression d'un inexistant
			if (isset($_GET[$getSupprimer]) && !$class::$methodeExiste($_GET[$getSupprimer])) {
				header('Location: index.php');
			}
			
			// Si modification d'un inexistant
			if (isset($_GET[$getModifier]) && !$class::$methodeExiste($_GET[$getModifier])) {
				header('Location: index.php');
			}
		}
	}
	
	if (isset($_GET['page'])) {
		// Autres pages
		switch ($_GET['page']) {
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
			default:
				break;
		}
	}
