
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

