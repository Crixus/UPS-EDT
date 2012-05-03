/*
* Permet de changer la valeur de notificationsActives de l'Etudiant
* @param idEtudiant : int id de l'Etudiant
* @param element : int correspondant a la valeur de notificationsActives a MAJ
*/
var etudiant_notificationsActives = function (idEtudiant, element) {

	if (element.checked)
		var appartient = 1; //MAJ de "notifications_actives" de la table Etudiant à 1
	else
		var appartient = 0; //MAJ de "notifications_actives" de la table Etudiant à 0
	
	new Ajax.Request('../administration/fonctions/gestion_notificationActives_et_actif.php', {
		method : 'POST',
		encoding : 'iso-8859-1',
		parameters : {
			appartient : appartient,
			type : 'etudiant',
			idEtudiant : idEtudiant
		},
		onSuccess : function (transport) {
		}
	});
}

/*
* Permet de changer la valeur de notificationsActives de l'Intervenant
* @param idIntervenant : int id de l'Intervenant
* @param element : int correspondant a la valeur de notificationsActives a MAJ
*/
var intervenant_notificationsActives = function (idIntervenant, element) {

	if (element.checked)
		var appartient = 1; //MAJ de "notifications_actives" de la table Intervenant à 1
	else
		var appartient = 0; //MAJ de "notifications_actives" de la table Intervenant à 0
	
	new Ajax.Request('../administration/fonctions/gestion_notificationActives_et_actif.php', {
		method : 'POST',
		encoding : 'iso-8859-1',
		parameters : {
			appartient : appartient,
			type : 'intervenant_notification',
			idIntervenant : idIntervenant
		},
		onSuccess : function (transport) {
		}
	});
}

/*
* Permet de changer la valeur de actif de l'Intervenant
* @param idIntervenant : int id de l'Intervenant
* @param element : int correspondant a la valeur de actif a MAJ
*/
var intervenant_actif = function (idIntervenant, element) {

	if (element.checked)
		var appartient = 1; //MAJ de "actif" de la table Intervenant à 1
	else
		var appartient = 0; //MAJ de "actif" de la table Intervenant à 0
	
	new Ajax.Request('../ajax/gestion_notificationActives_et_actif.php', {
		method : 'POST',
		encoding : 'iso-8859-1',
		parameters : {
			appartient : appartient,
			type : 'intervenant_actif',
			idIntervenant : idIntervenant
		},
		onSuccess : function (transport) {
		}
	});
}

