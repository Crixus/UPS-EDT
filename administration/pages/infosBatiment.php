<?php
	$Batiment = new Batiment($_GET['idBatiment']);
?>
					<h1>Bâtiment <?php echo $Batiment->getNom(); ?></h1>
					<h2>Informations</h2>
					<ul>
						<li>Nom : <?php echo $Batiment->getNom(); ?></li>
						<li>Latitude : <?php echo $Batiment->getLat(); ?></li>
						<li>Longitude : <?php echo $Batiment->getLon(); ?></li>
					</ul>
					<h2>Liste des salles du bâtiment</h2>
<?php
	$Batiment->table_salles(5);
