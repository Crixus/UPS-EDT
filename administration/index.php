<?php
	// Informations de base de données
	include_once('../includes/infos_bdd.php');
	require_once('../includes/fonctions.php');
	
	$messagesNotifications = Array();
	$messagesErreurs = Array();
	
	importerClasses();
	
	// Test de sécurité
	include_once("./securite.php");
	
	function afficherNotifications($nombreTabulations = 0) {
		$tab = "";
		for ($i = 0; $i < $nombreTabulations; $i++) {
			$tab .= "\t";
		}
		
		global $messagesNotifications;
		if (!empty($messagesNotifications)) {
			echo $tab . "<ul class=\"messages_notifications\">\n";
			foreach ($messagesNotifications as $mess) {
				echo $tab . "\t<li>Notification : " . $mess . "</li>\n";
			}
			echo $tab . "</ul>\n";
		}
	}
	
	function afficher_erreurs($nombreTabulations = 0) {
		$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) {
			$tab .= "\t";
		}
		
		global $messagesErreurs;
		if (!empty($messagesErreurs)) {
			echo $tab . "<ul class=\"messages_erreurs\">\n";
			foreach ($messagesErreurs as $mess) {
				echo $tab . "\t<li>Erreur : " . $mess . "</li>\n";
			}
			echo $tab . "</ul>\n";
		}
	}
	
	// Prise en compte des formulaires et suppression avant l'envoi de code HTML (gestion erreurs, sécurité...)
	Batiment::priseEnCompteFormulaire();
	Batiment::priseEnCompteSuppression();
	Cours::priseEnCompteFormulaire();
	Cours::priseEnCompteSuppression();
	Etudiant::priseEnCompteFormulaire();
	Etudiant::priseEnCompteSuppression();
	Groupe_Cours::priseEnCompteFormulaire();
	Groupe_Cours::priseEnCompteSuppression();
	Groupe_Etudiants::priseEnCompteFormulaire();
	Groupe_Etudiants::priseEnCompteSuppression();	
	Intervenant::priseEnCompteFormulaire();
	Intervenant::priseEnCompteSuppression();
	Options::priseEnCompteFormulaire();
	Promotion::priseEnCompteFormulaire();
	Salle::priseEnCompteFormulaire();
	Salle::priseEnCompteSuppression();
	Specialite::priseEnCompteFormulaire();
	Specialite::priseEnCompteSuppression();
	Type_Cours::priseEnCompteFormulaire();
	Type_Cours::priseEnCompteSuppression();
	Type_Salle::priseEnCompteFormulaire();
	Type_Salle::priseEnCompteSuppression();
	UE::priseEnCompteFormulaire();
	UE::priseEnCompteSuppression();
	Utilisateur::priseEnCompteFormulaires();
	
	Creneau_Intervenant::priseEnCompteFormulaire();
	Creneau_Intervenant::priseEnCompteSuppression();
	Creneau_Salle::priseEnCompteFormulaire();
	Creneau_Salle::priseEnCompteSuppression();
	Seance::priseEnCompteFormulaire();
	Seance::priseEnCompteSuppression();
	
	JourNonOuvrable::priseEnCompteFormulaire();
	JourNonOuvrable::priseEnCompteSuppression();
?>
<!DOCTYPE html>
	<head>
		<title>Administration</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="../css/style.php" />
		<script type="text/javascript" src="../js/prototype.js?v=<?php echo filemtime("../js/prototype.js");?>"></script>	
		<script type="text/javascript" src="../js/gestionPromotion.js?v=<?php echo filemtime("../js/gestionPromotion.js");?>"></script>
		<script type="text/javascript" src="../js/ajoutCours.js?v=<?php echo filemtime("../js/ajoutCours.js");?>"></script>
		<script type="text/javascript" src="../js/ajoutSalle.js?v=<?php echo filemtime("../js/ajoutSalle.js");?>"></script>	
		<script type="text/javascript" src="../js/gestionUtilisateurs.js?v=<?php echo filemtime("../js/gestionUtilisateurs.js");?>"></script>
		<script type="text/javascript" src="../js/gestionPublication.js?v=<?php echo filemtime("../js/gestionPublication.js");?>"></script>
		<script type="text/javascript" src="../js/gestionGroupeEtudiants.js?v=<?php echo filemtime("../js/gestionGroupeEtudiants.js");?>"></script>
		<script type="text/javascript" src="../js/gestionGroupeCours.js?v=<?php echo filemtime("../js/gestionGroupeCours.js");?>"></script>
		<script type="text/javascript" src="../js/inscriptionUE.js?v=<?php echo filemtime("../js/inscriptionUE.js");?>"></script>
		<script type="text/javascript" src="../js/listeCoursParUE.js?v=<?php echo filemtime("../js/listeCoursParUE.js");?>"></script>
		<script type="text/javascript" src="../js/gestionSeance.js?v=<?php echo filemtime("../js/gestionSeance.js");?>"></script>
	</head>
	<body>
		<div id="page_administration">
			<div id="page_administration_haut">
				<div id="page_administration_titre">
					<h1><a href="./index.php<?php if (isset($_GET['idPromotion'])) { 
						echo "?idPromotion=".$_GET['idPromotion']; 
					} ?>">Administration</a></h1>
				</div>
				<div id="barre_selection_promotion">
					<table>
						<tr>
<?php
	if (Promotion::nb_promotion() != 0) {
?>
							<td>Selection d'une promotion</td>
							<td>
<?php 
	if (isset($_GET['idPromotion'])) {
		echo Promotion::liste_promotion_for_select($_GET['idPromotion'], 8); 
	}
	else {
		echo Promotion::liste_promotion_for_select(null, 8); 
	}
?>
							</td>
<?php
	}
?>
							<td><a href="?page=ajoutPromotion" >Ajout d'une promotion</a></td>
						</tr>
					</table>
				</div>
			</div>
			<div id="page_administration_milieu">
				<nav>
<?php include_once('./nav.php'); ?>
				</nav>
				<section>
<?php 
	if (isset($_GET['page'])) {
		include_once("./pages/" . $_GET['page'] . ".php");
	} 
?>
				</section>
			</div>
			<div id="page_administration_bas">
				<p>Manuel Administration</p>
			</div>
		</div>
	</body>
</html>
	
