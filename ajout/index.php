<?php
	// Informations de base de données
	include_once('../includes/infos_bdd.php');
	
	$repertoire = opendir("../classes/");
	while($fichier = readdir($repertoire)){
		if($fichier != '..' && $fichier != '.'){
			include_once("../classes/$fichier");
		}
	}
	
	Cours::prise_en_compte_formulaire();
	
	$promotion = $_GET['idPromotion'];
	
?>
<!DOCTYPE html>
	<head>
		<title>Administration</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="../css/style.css" />
	</head>
	<body>
		<nav>
			<ul>
				<li><a href="?idPromotion=<?php echo $promotion; ?>&page=ajoutCours" >Ajout d'un cours</a>
				<li><a href="?idPromotion=<?php echo $promotion; ?>&page=ajoutEtudiant" >Ajout d'un étudiant</a>
			</ul>
		</nav>
<?php
if (isset($_GET['page'])) {
	include_once("./pages/{$_GET['page']}.php");
}
?>
	</body>
</html>
	
