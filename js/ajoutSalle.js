/*
* Permet de modifier l'appartenance d'une salle à un type de salle
* @param idSalle : int id de la salle
* @param idType_Salle : int id du type de salle
* @param element : boolean 1 pour un ajout et 0 pour une suppression
*/
var appartenance_salle_typeSalle = function (idSalle, idType_Salle, element) {

	if (element.checked)
		var appartient = 1; //Ajout du lien dans la table Appartenance_Salle_TypeSalle
	else
		var appartient = 0; //Suppression du lien dans la table Appartenance_Salle_TypeSalle
	
	
	new Ajax.Request('../administration/fonctions/appartenance_salle_typeSalle.php', {
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

