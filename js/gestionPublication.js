/*
* Permet de modifier l'appartenance d'un groupe de cours à un groupe d'etudiants
* @param idGroupeCours : int id du groupe de cours
* @param idGroupeEtudiants : int id du groupe d'etudiants
* @param element : boolean 1 pour un ajout et 0 pour une suppression
*/
var appartenance_groupeCours_groupeEtudiants = function (idGroupeCours, idGroupeEtudiants ,element) {

	if (element.checked)
		var appartient = 1; //Ajout du lien dans la table Publication
	else
		var appartient = 0; //Suppression du lien dans la table Publication
		
	new Ajax.Request('../administration/fonctions/appartenance_groupeCours_groupeEtudiants.php', {
		method : 'POST',
		encoding : 'iso-8859-1',
		parameters : {
			appartient : appartient,
			idGroupeCours : idGroupeCours,
			idGroupeEtudiants : idGroupeEtudiants
		},
		onSuccess : function (transport) {
			var erreur = parseInt(transport.responseText);
			if (erreur == 0)
				window.location = "./index.php";
		}
	});
}

