<?php
	// Informations de base de données
	include_once('../includes/infos_bdd.php');
	$messages_notifications = Array();
	$messages_erreurs = Array();
	
	// Importation des classes
	$repertoire = opendir("../classes/");
	while ($fichier = readdir($repertoire)) {
		if ($fichier != '..' && $fichier != '.') {
			include_once("../classes/" . $fichier);
		}
	}
	
	// Test de sécurité
	include_once("./securite.php");
	
	function afficher_notifications($nombreTabulations = 0) {
		$tab = "";
		for ($i = 0; $i < $nombreTabulations; $i++) {
			$tab .= "\t";
		}
		
		global $messages_notifications;
		if (!empty($messages_notifications)) {
			echo $tab . "<ul class=\"messages_notifications\">\n";
			foreach ($messages_notifications as $mess) {
				echo $tab . "\t<li>Notification : " . $mess . "</li>\n";
			}
			echo $tab . "</ul>\n";
		}
	}
	
	function afficher_erreurs($nombreTabulations = 0) {
		$tab = ""; for ($i = 0; $i < $nombreTabulations; $i++) {
			$tab .= "\t";
		}
		
		global $messages_erreurs;
		if (!empty($messages_erreurs)) {
			echo $tab . "<ul class=\"messages_erreurs\">\n";
			foreach ($messages_erreurs as $mess) {
				echo $tab . "\t<li>Erreur : " . $mess . "</li>\n";
			}
			echo $tab . "</ul>\n";
		}
	}
	
	// Prise en compte des formulaires et suppression avant l'envoi de code HTML (gestion erreurs, sécurité...)
	Batiment::prise_en_compte_formulaire();
	Batiment::prise_en_compte_suppression();
	Cours::prise_en_compte_formulaire();
	Cours::prise_en_compte_suppression();
	Etudiant::prise_en_compte_formulaire();
	Etudiant::prise_en_compte_suppression();
	Groupe_Cours::prise_en_compte_formulaire();
	Groupe_Cours::prise_en_compte_suppression();
	Groupe_Etudiants::prise_en_compte_formulaire();
	Groupe_Etudiants::prise_en_compte_suppression();	
	Intervenant::prise_en_compte_formulaire();
	Intervenant::prise_en_compte_suppression();
	Options::prise_en_compte_formulaire();
	Promotion::prise_en_compte_formulaire();
	Salle::prise_en_compte_formulaire();
	Salle::prise_en_compte_suppression();
	Specialite::prise_en_compte_formulaire();
	Specialite::prise_en_compte_suppression();
	Type_Cours::prise_en_compte_formulaire();
	Type_Cours::prise_en_compte_suppression();
	Type_Salle::prise_en_compte_formulaire();
	Type_Salle::prise_en_compte_suppression();
	UE::prise_en_compte_formulaire();
	UE::prise_en_compte_suppression();
	
	Creneau_Intervenant::prise_en_compte_formulaire();
	Creneau_Intervenant::prise_en_compte_suppression();
	Creneau_Salle::prise_en_compte_formulaire();
	Creneau_Salle::prise_en_compte_suppression();
	Seance::prise_en_compte_formulaire();
	Seance::prise_en_compte_suppression();
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
						echo "?idPromotion={$_GET['idPromotion']}"; 
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
	
