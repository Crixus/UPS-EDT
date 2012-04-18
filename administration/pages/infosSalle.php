					<h1>Informations salle</h1>
<?php
	$_Salle = new Salle($_GET['idSalle']);
	$_Salle->page_informations(5);
