					<h1>Informations salle</h1>
<?php
	$Salle = new Salle($_GET['idSalle']);
	$Salle->page_informations(5);
?>
