					<h1>Gestion des publications</h1>
<?php
	afficherNotifications(5);
	afficher_erreurs(5);
	Publication::liste_publication();
