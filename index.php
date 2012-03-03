<?php
	session_start();
	
	// Redirection si l'utilisateur vas sur la page sans être connecté
	if(!isset($_SESSION['idUtilisateur'])){
		header('Location: ./connexion.php');
	}
	
	// Informations de base de données + Utils
	include_once('./includes/infos_bdd.php');
	include_once('./classes/class_Utils_SQL.php');
	
	// Importation des classes (phase dev : a la fin les mettres 1 par 1 pour eviter de charger le serveur)
	$repertoire = opendir("./classes/");
	while($fichier = readdir($repertoire)){
		if($fichier != '..' && $fichier != '.'){
			include_once("./classes/$fichier");
		}
	}
	
	if(!isset($_GET['semaine'])){
		$debutSemaine = EmploiDuTemps::timestamp_debut_semaine(time());
		header("Location: ./index.php?semaine=$debutSemaine");
	}
	else{
		$debutSemaine = EmploiDuTemps::timestamp_debut_semaine($_GET['semaine']);
		if($debutSemaine != $_GET['semaine']){
			header("Location: ./index.php?semaine=$debutSemaine");
		}
	}
	
	$nbSecondesSemaine = (7*24*3600);
	$semainePredente = $_GET['semaine'] - $nbSecondesSemaine;
	$semaineSuivante = $_GET['semaine'] + $nbSecondesSemaine;
	
	// Serialisation / Unserialisation / Variable de Session Utilisateur (necessaire quand la variable de session est un objet
	// ATTENTION SI MISE A JOUR D'ETUDIANT !!!
	if(!isset($_SESSION['Utilisateur_Serialize'])){
		switch($_SESSION['Type']){
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
	}
	else{
		$_SESSION['Utilisateur'] = unserialize($_SESSION['Utilisateur_Serialize']);
	}
	
?>
<!DOCTYPE html>
	<head>
		<title>UPS TimeTable</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="./css/style.php" />
	</head>
	<body>
		<div id="affichage_edt_semaine">
			<h1>Ups TimeTable</h1>
			<h2>Affichage emploi du temps semaine</h2>
			<table id="navigation_semaine">
				<tr>
					<td><a href="./index.php?semaine=<?php echo $semainePredente; ?>"><img src="./images/fleche_gauche.jpg" alt="fleche gauche" /></a></td>
					<td>Semaine du <?php echo date('d/m/Y',$_GET['semaine']); ?> au <?php echo date('d/m/Y',$semaineSuivante-1); ?></td>
					<td><a href="./index.php?semaine=<?php echo $semaineSuivante; ?>"><img src="./images/fleche_droite.jpg" alt="fleche droite" /></a></td>
				</tr>
			</table>
			<section>
<?php
	EmploiDuTemps::affichage_edt_semaine_table($_SESSION['Utilisateur']->getId(), EmploiDuTemps::timestamp_debut_semaine($_GET['semaine']));	
?>
			</section>
			<footer>
				<p><a href="#">Téléchargement PDF</a> - <a href="#">Téléchargement Google Agenda</a></p>
				<p><a href="#">Manuel Utilisateur</a></p>
				<p><a href="./deconnexion.php">Deconnexion</a></p>
			</footer>
		</div>
	</body>
</html>
	
	
