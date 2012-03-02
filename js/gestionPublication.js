
var appartenance_groupeCours_groupeEtudiants = function (idGroupeCours, idGroupeEtudiants ,element) {

	if (element.checked)
		var appartient = 1; //Ajout du lien dans la table Publication
	else
		var appartient = 0; //Suppression du lien dans la table Publication
		
	new Ajax.Request('../ajax/appartenance_groupeCours_groupeEtudiants.php', {
		method : 'POST',
		encoding : 'iso-8859-1',
		parameters : {
			appartient : appartient,
			idGroupeCours : idGroupeCours,
			idGroupeEtudiants : idGroupeEtudiants
		},
		onSuccess : function (transport) {
			
		}
	});
}

