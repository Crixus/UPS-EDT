<?php
	// Informations de base de données
	include_once('../includes/infos_bdd.php');
	
	// Création des tables (phase de deploiement / tests)
	define('CREER_TABLES', false);
	
	$repertoire = opendir("../classes/");
	while($fichier = readdir($repertoire)){
		if($fichier != '..' && $fichier != '.'){
			include_once("../classes/$fichier");
		}
	}
	
	if(CREER_TABLES){
		Utils_SQL::sql_from_file("../sql/AllCreates.sql");
		Utils_SQL::sql_from_file("../sql/AllInserts.sql");
	}
	
	$promotion = $_GET['idPromotion'];
	// $promotion=0;
?>
<!DOCTYPE html>
	<head>
		<title>Administration</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="../css/style.css" />
		<script type="text/javascript" src="../js/prototype.js?v=<?php echo filemtime("../js/prototype.js");?>"></script>	
		<script type="text/javascript" src="../js/gestionPromotion.js?v=<?php echo filemtime("../js/gestionPromotion.js");?>"></script>
		<script type="text/javascript" src="../js/ajoutCours.js?v=<?php echo filemtime("../js/ajoutCours.js");?>"></script>
		<script type="text/javascript" src="../js/ajoutIntervenant.js?v=<?php echo filemtime("../js/ajoutIntervenant.js");?>"></script>
		<script type="text/javascript" src="../js/ajoutSalle.js?v=<?php echo filemtime("../js/ajoutSalle.js");?>"></script>	
		<script type="text/javascript" src="../js/gestionUtilisateurs.js?v=<?php echo filemtime("../js/gestionUtilisateurs.js");?>"></script>
		<script type="text/javascript" src="../js/gestionPublication.js?v=<?php echo filemtime("../js/gestionPublication.js");?>"></script>
		<script type="text/javascript" src="../js/inscriptionUE.js?v=<?php echo filemtime("../js/inscriptionUE.js");?>"></script>
	</head>
	<body>
		<nav> 
<?php include_once('./selection_promotion.php'); 
if ($_GET['idPromotion'] != 0) {
 include_once('./nav.php'); 
}?>
		</nav>
		<section>
<?php
if (isset($_GET['page'])) {
	include_once("./pages/{$_GET['page']}.php");
}
?>
		</section>
	</body>
</html>
	
