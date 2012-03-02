var appartenance_salle_typeSalle = function (idSalle, idType_Salle, element) {

	if (element.checked)
		var appartient = 1; //Ajout du lien dans la table Appartenance_Salle_TypeSalle
	else
		var appartient = 0; //Suppression du lien dans la table Appartenance_Salle_TypeSalle
	
	new Ajax.Request('../ajax/appartenance_salle_typeSalle.php', {
		method : 'POST',
		encoding : 'iso-8859-1',
		parameters : {
			appartient : appartient,
			idSalle : idSalle,
			idType_Salle : idType_Salle
		},
		onSuccess : function (transport) {
		}
	});
}

