/*
* Permet de modifier l'appartenance d'un cours à un groupe de cours
* @param idCours : int id du cours
* @param idGroupeCours : int id du groupe de cours
* @param element : boolean 1 pour un ajout et 0 pour une suppression
*/
var appartenance_cours_groupeCours = function (idCours, idGroupeCours ,element) {
	if (element.checked) {
		var appartient = 1; //Ajout du lien dans la table Appartient_Cours_GroupeCours
	}
	else {
		var appartient = 0; //Suppression du lien dans la table Appartient_Cours_GroupeCours
		document.getElementsByName('case_promotion_'+idGroupeCours)[0].checked = "";
	}
	
	new Ajax.Request('../administration/fonctions/appartenance_cours_groupeCours.php', {
		method : 'POST',
		encoding : 'iso-8859-1',
		parameters : {
			type : 'cours',
			appartient : appartient,
			idCours : idCours,
			idGroupeCours : idGroupeCours
		},
		onSuccess : function (transport) {
			var erreur = parseInt(transport.responseText);
			if (erreur == 0)
				window.location = "./index.php";
		}
	});
}

/*
* Permet de modifier l'appartenance d'un groupe de cours à toute les cours d'une promotion
* @param idPromotion : int id de la promotion selectionnnee
* @param idGroupeCours : int id du groupe de cours
* @param nbre_cours : int nombre de cours enregistree pour cette promotion
* @param element : boolean 1 pour un ajout et 0 pour une suppression
*/
var appartenance_promotion_groupeCours = function (idPromotion, idGroupeCours, nbre_cours, element) {
	if (element.checked) {
		var appartient = 1; //Ajout du lien dans la table Appartient_Cours_GroupeCours
		for (i=0; i<nbre_cours; i++) {		
			document.getElementsByName('case_GroupeCours_'+idGroupeCours)[i].checked = "true";
		}
	}
	else {
		var appartient = 0; //Suppression du lien dans la table Appartient_Cours_GroupeCours
		for (i=0; i<nbre_cours; i++) {
			document.getElementsByName('case_GroupeCours_'+idGroupeCours)[i].checked = "";
		}
	}
	
	new Ajax.Request('../administration/fonctions/appartenance_cours_groupeCours.php', {
		method : 'POST',
		encoding : 'iso-8859-1',
		parameters : {
			type : 'promotion',
			appartient : appartient,
			idGroupeCours : idGroupeCours,
			idPromotion : idPromotion
		},
		onSuccess : function (transport) {
			var erreur = parseInt(transport.responseText);
			if (erreur == 0)
				window.location = "./index.php";
		}
	});	
}

/*
* Permet de modifier le champ identifiant pour la page de gestion des groupes de cours
* @param text : string correspondant au champ saisi dans nom à recopier dans l'identifiant
*/
var modification_identifiant = function(text) {
	document.getElementsByName('identifiant')[0].value = text;
	document.getElementsByName('identifiant')[0].value += document.getElementsByName('nom')[0].value;
}
