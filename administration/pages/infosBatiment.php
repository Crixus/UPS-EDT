					<h1>Informations bâtiment</h1>
<?php
	$Batiment = new Batiment($_GET['idBatiment']);
	$Batiment->page_informations(5);
?>
