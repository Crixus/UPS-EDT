var changeDateDebut = function (dateDebut){
	var inputObj = document.getElementsByName("dateFin")[0];
	inputObj.value = dateDebut
}
	
var changeHeureDebut = function (heureDebut){
	var heureFin = ((parseInt(heureDebut.substr(0, 2),10) + 2)%24);
	if(heureFin < 10){ heureFin = '0' + heureFin.toString() ; } else { heureFin = heureFin.toString(); }
	var inputObj = document.getElementsByName("heureFin")[0];
	inputObj.value = heureFin;
}

var changeMinuteDebut = function (minuteDebut){
	var inputObj = document.getElementsByName("minuteFin")[0];
	inputObj.value = minuteDebut;
}



var dateCours = function (element, dateDebut, dateFin) {
	var chaineDateDebut = dateDebut.split(' ');
	var chaineJMADebut = chaineDateDebut[0].split('-');
	var chaineHMSDebut = chaineDateDebut[1].split(':');
	
	var date1 = chaineJMADebut[2] + " " + chaineJMADebut[1] + " " + chaineJMADebut[0];
	date1 += chaineHMSDebut[0] + "h" + chaineHMSDebut[1];
	
	element.innerText = date1;
}


var appartenance_typeSalle_typeCours = function (idType_Cours, idType_Salle, element) {

	if (element.checked)
		var appartient = 1; //Ajout du lien dans la table Appartenance_TypeSalle_TypeCours
	else
		var appartient = 0; //Suppression du lien dans la table Appartenance_TypeSalle_TypeCours
	
	new Ajax.Request('../ajax/appartenance_typeSalle_typeCours.php', {
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


var update_select_typeSalle = function(idSalle) {
	var idType_Cours = document.getElementsByName("typeCours")[0].options[document.getElementsByName("typeCours")[0].selectedIndex].value;

	new Ajax.Request('../ajax/appartenance_typeSalle_typeCours.php', {
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



