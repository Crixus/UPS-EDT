
var inscription_UE = function (idEtudiant, idUE ,element) {
	var nbreUE = parseInt(document.getElementsByName("nbreUE_"+idEtudiant)[0].innerHTML);
	
	if (element.checked) {
		var appartient = 1; //Ajout du lien dans la table Inscription
		document.getElementsByName("nbreUE_"+idEtudiant)[0].innerHTML = nbreUE + 1;
	}
	else {
		var appartient = 0; //Suppression du lien dans la table Inscription
		document.getElementsByName("nbreUE_"+idEtudiant)[0].innerHTML = nbreUE - 1;
		document.getElementsByName('case_promotion_'+idUE)[0].checked = "";
	}
	
	new Ajax.Request('../ajax/inscription_UE.php', {
		method : 'POST',
		encoding : 'iso-8859-1',
		parameters : {
			type : 'etudiant',
			appartient : appartient,
			idEtudiant : idEtudiant,
			idUE : idUE
		},
		onSuccess : function (transport) {
			
		}
	});
}


var inscription_UE_promotion = function (idPromotion, idUE, nbre_etudiants, element) {
	if (element.checked) {
		var appartient = 1; //Ajout du lien dans la table Inscription
		for (i=0; i<nbre_etudiants; i++) {			
			if (document.getElementsByName('case_UE_'+idUE)[i].checked == "") {
				if (i==0) {				
					var nbreUE = parseInt(document.getElementsByName('tabInscription')[0].rows[i+2].cells[2].innerHTML);
					document.getElementsByName('tabInscription')[0].rows[i+2].cells[2].innerHTML = nbreUE + 1;
				}
				else {			
					var nbreUE = parseInt(document.getElementsByName('tabInscription')[0].rows[i+2].cells[1].innerHTML);
					document.getElementsByName('tabInscription')[0].rows[i+2].cells[1].innerHTML = nbreUE + 1;
				}
			}
			document.getElementsByName('case_UE_'+idUE)[i].checked = "true";
		}
	}
	else {
		var appartient = 0; //Suppression du lien dans la table Inscription
		for (i=0; i<nbre_etudiants; i++) {
			if (! document.getElementsByName('case_UE_'+idUE)[i].checked == "") {			
				if (i==0) {				
					var nbreUE = parseInt(document.getElementsByName('tabInscription')[0].rows[i+2].cells[2].innerHTML);
					document.getElementsByName('tabInscription')[0].rows[i+2].cells[2].innerHTML = nbreUE - 1;
				}
				else {			
					var nbreUE = parseInt(document.getElementsByName('tabInscription')[0].rows[i+2].cells[1].innerHTML);
					document.getElementsByName('tabInscription')[0].rows[i+2].cells[1].innerHTML = nbreUE - 1;
				}
			}
			document.getElementsByName('case_UE_'+idUE)[i].checked = "";
		}
	}
	
	new Ajax.Request('../ajax/inscription_UE.php', {
		method : 'POST',
		encoding : 'iso-8859-1',
		parameters : {
			type : 'promotion',
			appartient : appartient,
			idUE : idUE,
			idPromotion : idPromotion
		},
		onSuccess : function (transport) {
			
		}
	});	
}

