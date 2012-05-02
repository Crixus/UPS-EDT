<?php
	session_start();
	
	// Redirection si l'utilisateur vas sur la page sans être connecté
	if (!isset($_SESSION['idUtilisateur'])) {
		header('Location: ./connexion.php');
	}
	
	// Informations de base de données + Utils
	require_once('./includes/infos_bdd.php');
	require_once('./includes/infos_motDePasse.php');
	
	require_once('./includes/fonctions.php');
	
	importerClasses();
	
	if (!isset($_GET['semaine'])) {
		$debutSemaine = EmploiDuTemps::timestamp_debut_semaine(time());
		header("Location: ./index.php?semaine=" . $debutSemaine);
	} else {
		$debutSemaine = EmploiDuTemps::timestamp_debut_semaine($_GET['semaine']);
		if ($debutSemaine != $_GET['semaine']) {
			header("Location: ./index.php?semaine=" . $debutSemaine);
		}
	}
	
	$debutSemaine = mktime(0, 0, 0, date('n'), date('j'), date('Y')) - ((date('N') - 1) * 3600 * 24);
	$semainePredente = $_GET['semaine'] - 604800;
	$semaineSuivante = $_GET['semaine'] + 604800;
	// Gestion heures d'été
	if (date('H:i:s', $semaineSuivante) == "23:00:00") {
		$semaineSuivante += 3600;
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
				//
				break;
		}
	} else {
		$_SESSION['Utilisateur'] = unserialize($_SESSION['Utilisateur_Serialize']);
	}
	
?>
<!DOCTYPE html>
	<head>
		<title>UPS-EDT</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="./css/style.php" />
	</head>
	<body>
		<div id="affichage_edt_semaine">
			<h1>UPS-EDT</h1>
			<h2>Affichage emploi du temps semaine</h2>
			<table id="navigation_semaine">
				<tr>
					<td><a href="./index.php?semaine=<?php echo $semainePredente; ?>"><img src="./images/fleche_gauche.jpg" alt="fleche gauche" /></a></td>
					<td>Semaine du <?php echo date('d/m/Y', $_GET['semaine']); ?> au <?php echo date('d/m/Y', $semaineSuivante - 1); ?></td>
					<td><a href="./index.php?semaine=<?php echo $semaineSuivante; ?>"><img src="./images/fleche_droite.jpg" alt="fleche droite" /></a></td>
				</tr>
			</table>
			<section>
<?php
	if (!isset($_GET['type'])) {
		EmploiDuTemps::affichage_edt_semaine_table($_SESSION['Type'], $_SESSION['Utilisateur']->getId(), EmploiDuTemps::timestamp_debut_semaine($_GET['semaine']));	
	} else {
		if ($_GET['type'] == "Promotion") {
			EmploiDuTemps::affichage_edt_semaine_promotion_table($_SESSION['Utilisateur']->getIdPromotion(), EmploiDuTemps::timestamp_debut_semaine($_GET['semaine']));
		}
	}
?>
			</section>
			<footer>
				<p><p><a href="./index.php?semaine=<?php echo $_GET['semaine']; ?>">Mon emploi du temps</a></p>
				<p><p><a href="./index.php?type=Promotion&amp;semaine=<?php echo $_GET['semaine']; ?>">Emploi du temps de ma promotion</a></p>
				<p><a href="./export.php?semaine=<?php echo $_GET['semaine']; ?>">Téléchargement PDF</a> - <a href="#">Téléchargement Google Agenda</a></p>
				<p><a href="./manuels/Manuel_Utilisateur.pdf">Manuel Utilisateur</a></p>
				<p><a href="./deconnexion.php">Deconnexion</a></p>
				<div id="imageBasEdt">
						<img src="./images/logo_UPS.jpg" alt="Logo université Toulouse 3" />
					</div>
			</footer>
		</div>
	</body>
</html>
	
	
