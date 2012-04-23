					<h1>Association des étudiants aux groupes d'étudiants</h1>
<?php
	afficherNotifications(5);
	afficher_erreurs(5);
	Appartient_Etudiant_GroupeEtudiants::liste_appartenance_etudiant_groupeEtudiants();
