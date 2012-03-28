
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

