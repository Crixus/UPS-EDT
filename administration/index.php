<?php
	// Informations de base de données
	include_once('../includes/infos_bdd.php');
	
	// Importation des classes
	$repertoire = opendir("../classes/");
	while($fichier = readdir($repertoire)){
		if($fichier != '..' && $fichier != '.'){
			include_once("../classes/$fichier");
		}
	}
	
	// Test sur la promotion (voir si la promotion est choisie et sécurité)
	if(isset($_GET['idPromotion'])){
		$promotion_choisie = true;
		$idPromotion = $_GET['idPromotion'];
		if(!Promotion::existe_promotion($idPromotion)){ 
			header('Location: ./index.php');
		}
	}
	else{
		$promotion_choisie = false;
	}
	
	// Test sur la page (voir si page choisie et sécurité)
	$listePagesAdminPromo = Array(
		"ajoutCours.php", "ajoutEtudiant.php", "ajoutGroupeCours.php", "ajoutGroupeEtudiants.php", "ajoutSpecialite.php", "gestionPublication.php"
	);
	$listePagesAdminHorsPromo = Array(
		"ajoutBatiment.php", "ajoutIntervenant.php", "ajoutPromotion.php", "ajoutSalle.php", "ajoutTypeSalle.php", "ajoutUE.php", "ajoutTypeCours.php", "listeInscriptionsUE.php", "styleTypeCours.php"
	);
	
	if(isset($_GET['page'])){
		if(isset($_GET['idPromotion'])){
			if(!in_array("{$_GET['page']}.php", $listePagesAdminHorsPromo) && !in_array("{$_GET['page']}.php", $listePagesAdminPromo)){
				header('Location: ./index.php');
			}
		}
		else{
			if(!in_array("{$_GET['page']}.php", $listePagesAdminHorsPromo)){
				header('Location: ./index.php');
			}
		}
	}
	
	Batiment::prise_en_compte_formulaire();
	Batiment::prise_en_compte_suppression();	
	Intervenant::prise_en_compte_formulaire();
	Intervenant::prise_en_compte_suppression();
	Options::prise_en_compte_formulaire();
	Salle::prise_en_compte_formulaire();
	Salle::prise_en_compte_suppression();
	Type_Salle::prise_en_compte_formulaire();
	Type_Salle::prise_en_compte_suppression();
?>
<!DOCTYPE html>
	<head>
		<title>Administration</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="../css/style.php" />
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
		<div id="page_administration">
			<div id="page_administration_haut">
				<div id="page_administration_titre">
					<h1><a href="./index.php<?php if($promotion_choisie){ echo "?idPromotion=$idPromotion"; } ?>">Administration</a></h1>
				</div>
				<div id="barre_selection_promotion">
					<table>
						<tr>
							<td>Selection d'une promotion</td>
							<td>
<?php 
	if($promotion_choisie){
		echo Promotion::liste_promotion_for_select($idPromotion, 8); 
	}
	else{
		echo Promotion::liste_promotion_for_select(null, 8); 
	}
?>
							</td>
							<td><a href="?page=ajoutPromotion" >Ajout d'une promotion</a></td>
						</tr>
					</table>
				</div>
			</div>
			<div id="page_administration_milieu">
				<nav>
<?php 
	include_once('./nav_hors_promo.php');
	if(isset($_GET['idPromotion'])){ include_once('./nav_promo.php'); }
?>
				</nav>
				<section>
<?php 
	if(isset($_GET['page'])){ include_once("./pages/{$_GET['page']}.php"); } 
?>
				</section>
			</div>
			<div id="page_administration_bas">
				<p>Manuel Administration</p>
			</div>
		</div>
	</body>
</html>
	
