
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
			
		}
	});
}


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
			
		}
	});	
}


