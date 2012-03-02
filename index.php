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
		<link rel="stylesheet" href="./css/style.css" />
	</head>
	<body>
		<h1>Ups TimeTable</h1>
		<p><a href="./deconnexion.php">Deconnexion</a></p>
<?php
	EmploiDuTemps::affichage_edt_semaine_table($_SESSION['Utilisateur']->getId(), '2012-02-06', '2012-02-13');	
?>
	</body>
</html>
	
	
