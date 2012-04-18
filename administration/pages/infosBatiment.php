					<h1>Informations bâtiment</h1>
<?php
	$_Batiment = new Batiment($_GET['idBatiment']);
	$_Batiment->page_informations(5);
