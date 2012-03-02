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
	
	if(isset($_GET['idPromotion'])){
		$promotion_choisie = true;
		$idPromotion = $_GET['idPromotion'];
		if($idPromotion == 0){ // AJOUTER TEST SI EXISTANT
			header('Location: ./index.php');
		}
	}
	else{
		$promotion_choisie = false;
	}
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
		<div id="barre_selection_promotion">
			<table>
				<tr>
					<td>Selection d'une promotion</td>
					<td>
<?php 
	if($promotion_choisie){
		echo Promotion::liste_promotion_for_select($idPromotion); 
	}
	else{
		echo Promotion::liste_promotion_for_select(); 
	}
?>
					</td>
					<td><a href="?page=ajoutPromotion" >Ajout d'une promotion</a></td>
				</tr>
			</table>
<?php
	if($promotion_choisie){
		$promotion = $_GET['idPromotion'];
?>
		<nav> 
<?php
	include_once('./nav.php'); 
?>
		</nav>
		<section>
<?php
if (isset($_GET['page'])) {
	include_once("./pages/{$_GET['page']}.php");
}
?>
		</section>
<?php
	}
	else if(isset($_GET['page']) && $_GET['page'] == "ajoutPromotion"){
		include_once("./pages/{$_GET['page']}.php");
	}
	else{
?>
	<p>Merci de choisir une promotion</p>
<?php
	}
?>
	</body>
</html>
	
