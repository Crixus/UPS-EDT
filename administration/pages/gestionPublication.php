					<h1>Gestion des publications</h1>
<?php
	afficher_notifications(5);
	afficher_erreurs(5);
	Publication::liste_publication();
