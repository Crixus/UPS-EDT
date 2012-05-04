					<h1>Informations salle</h1>
<?php
	$_Salle = new Salle($_GET['idSalle']);
	$_Salle->pageInformations(5);
