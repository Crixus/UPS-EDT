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
?>
					<h2>Plan Google Maps</h2>
					<iframe width="700" height="500" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q=<?php echo $Batiment->getLat(); ?>,+<?php echo $Batiment->getLon(); ?>+(Bâtiment <?php echo $Batiment->getNom(); ?>)&amp;z=16&amp;output=embed"></iframe>
