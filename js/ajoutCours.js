/*
* Permet de changer la date de fin et de prendre la valeur de la date de debut selectionnee
* @param dateDebut : string date de debut
*/
var changeDateDebut = function (dateDebut){
	var inputObj = document.getElementsByName("dateFin")[0];
	inputObj.value = dateDebut
}

/*
* Permet de changer l'heure de la date de fin et de prendre la valeur de l'heure de la date de debut selectionnee
* @param heureDebut : string heure de la date de debut
*/
var changeHeureDebut = function (heureDebut){
	var heureFin = ((parseInt(heureDebut.substr(0, 2),10) + 2)%24);
	if(heureFin < 10){ heureFin = '0' + heureFin.toString() ; } else { heureFin = heureFin.toString(); }
	var inputObj = document.getElementsByName("heureFin")[0];
	inputObj.value = heureFin;
}

/*
* Permet de changer la minute de la date de fin et de prendre la valeur de la minute de la date de debut selectionnee
* @param heureDebut : string minute de la date de debut
*/
var changeMinuteDebut = function (minuteDebut){
	var inputObj = document.getElementsByName("minuteFin")[0];
	inputObj.value = minuteDebut;
}


/*
* Modifie le champ date
*/
var dateCours = function (element, dateDebut, dateFin) {
	var chaineDateDebut = dateDebut.split(' ');
	var chaineJMADebut = chaineDateDebut[0].split('-');
	var chaineHMSDebut = chaineDateDebut[1].split(':');
	
	var date1 = chaineJMADebut[2] + " " + chaineJMADebut[1] + " " + chaineJMADebut[0];
	date1 += chaineHMSDebut[0] + "h" + chaineHMSDebut[1];
	
	element.innerText = date1;
}

/*
* Permet de modifier l'appartenance d'un type de cours à un type de salle
* @param idType_Cours : int id du type de cours
* @param idType_Salle : int id du type de salle
* @param element : boolean 1 pour un ajout et 0 pour une suppression
*/
var appartenance_typeSalle_typeCours = function (idType_Cours, idType_Salle, element) {

	if (element.checked)
		var appartient = 1; //Ajout du lien dans la table Appartenance_TypeSalle_TypeCours
	else
		var appartient = 0; //Suppression du lien dans la table Appartenance_TypeSalle_TypeCours
	
	new Ajax.Request('../administration/fonctions/appartenance_typeSalle_typeCours.php', {
		method : 'POST',
		encoding : 'iso-8859-1',
		parameters : {
			type : 'update_table',
			appartient : appartient,
			idType_Cours : idType_Cours,
			idType_Salle : idType_Salle
		},
		onSuccess : function (transport) {
		}
	});
}

/*
* Met à jour la liste des type de salles suivant la salle selectionnee
* @param idSalle : int id de la salle
*/
var update_select_typeSalle = function(idSalle) {
	var idType_Cours = document.getElementsByName("typeCours")[0].options[document.getElementsByName("typeCours")[0].selectedIndex].value;

	new Ajax.Request('../administration/fonctions/appartenance_typeSalle_typeCours.php', {
		method : 'POST',
		encoding : 'iso-8859-1',
		parameters : {
			type : 'update_select',
			idType_Cours : idType_Cours,
			idSalle : idSalle
		},
		onSuccess : function (transport) {
			document.getElementsByName("salle")[0].innerHTML = transport.responseText;
		}
	});
}



