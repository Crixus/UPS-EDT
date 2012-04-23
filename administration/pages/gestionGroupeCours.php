					<h1>Association des cours aux groupes de cours</h1>
<?php
	afficherNotifications(5);
	afficher_erreurs(5);
	Appartient_Cours_GroupeCours::liste_appartenance_cours_groupeCours();
