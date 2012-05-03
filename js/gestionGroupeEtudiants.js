/*
* Permet de modifier l'appartenance d'un etudiant à un groupe d'etudiants
* @param idEtudiant : int id de l'etudiant
* @param idGroupeEtudiants : int id du groupe d'etudiants
* @param element : boolean 1 pour un ajout et 0 pour une suppression
*/
var appartenance_etudiant_groupeEtudiants = function (idEtudiant, idGroupeEtudiants ,element) {
	if (element.checked) {
		var appartient = 1; //Ajout du lien dans la table Appartient_Etudiant_GroupeEtudiants
	}
	else {
		var appartient = 0; //Suppression du lien dans la table Appartient_Etudiant_GroupeEtudiants
		document.getElementsByName('case_promotion_'+idGroupeEtudiants)[0].checked = "";
	}
	
	new Ajax.Request('../administration/fonctions/appartenance_etudiant_groupeEtudiants.php', {
		method : 'POST',
		encoding : 'iso-8859-1',
		parameters : {
			type : 'etudiant',
			appartient : appartient,
			idEtudiant : idEtudiant,
			idGroupeEtudiants : idGroupeEtudiants
		},
		onSuccess : function (transport) {
			var erreur = parseInt(transport.responseText);
			if (erreur == 0)
				window.location = "./index.php";
		}
	});
}

/*
* Permet de modifier l'appartenance d'un groupe d'etudiants à toute les etudiants d'une promotion
* @param idPromotion : int id de la promotion selectionnnee
* @param idGroupeEtudiants : int id du groupe d'etudiants
* @param nbre_etudiants : int nombre d'etudiants inscrits dans cette promotion
* @param element : boolean 1 pour un ajout et 0 pour une suppression
*/
var appartenance_promotion_groupeEtudiants = function (idPromotion, idGroupeEtudiants, nbre_etudiants, element) {
	if (element.checked) {
		var appartient = 1; //Ajout du lien dans la table Appartient_Etudiant_GroupeEtudiants
		for (i=0; i<nbre_etudiants; i++) {		
			document.getElementsByName('case_GroupeEtudiants_'+idGroupeEtudiants)[i].checked = "true";
		}
	}
	else {
		var appartient = 0; //Suppression du lien dans la table Appartient_Etudiant_GroupeEtudiants
		for (i=0; i<nbre_etudiants; i++) {
			document.getElementsByName('case_GroupeEtudiants_'+idGroupeEtudiants)[i].checked = "";
		}
	}
	
	new Ajax.Request('../administration/fonctions/appartenance_etudiant_groupeEtudiants.php', {
		method : 'POST',
		encoding : 'iso-8859-1',
		parameters : {
			type : 'promotion',
			appartient : appartient,
			idGroupeEtudiants : idGroupeEtudiants,
			idPromotion : idPromotion
		},
		onSuccess : function (transport) {
			var erreur = parseInt(transport.responseText);
			if (erreur == 0)
				window.location = "./index.php";
		}
	});	
}

