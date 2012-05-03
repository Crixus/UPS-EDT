/*
* Permet de changer la valeur de effectue de la Seance (0 si seance a venir, 1 si seance termine)
* @param idSeance : int id de la seance
* @param element : int correspondant a la valeur de effectue a MAJ
*/
var seance_effectue = function (idSeance, element) {

	if (element.checked)
		var effectue = 1; //MAJ de "effectue" de la table Seance à 1
	else
		var effectue = 0; //MAJ de "effectue" de la table Seance à 0
	
	new Ajax.Request('../administration/fonctions/gestion_seance_effectue.php', {
		method : 'POST',
		encoding : 'iso-8859-1',
		parameters : {
			effectue : effectue,
			idSeance : idSeance
		},
		onSuccess : function (transport) {
		}
	});
}

