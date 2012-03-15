					<h1>Gestion des inscriptions</h1>
<?php
	afficher_notifications(5);
	afficher_erreurs(5);
	Inscription::liste_inscription();
?>
