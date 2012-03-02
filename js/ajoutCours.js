var changeDateDebut = function (dateDebut){
	var inputObj = document.getElementsByName("dateFin")[0];
	inputObj.value = dateDebut
}
	
var changeHeureDebut = function (heureDebut){
	var heureFin = ((parseInt(heureDebut.substr(0, 2),10) + 2)%24);
	if(heureFin < 10){ heureFin = '0' + heureFin.toString() ; } else { heureFin = heureFin.toString(); }
	var nouvelleHeure = heureFin + heureDebut.substr(2, 3);
	var inputObj = document.getElementsByName("heureFin")[0];
	inputObj.value = nouvelleHeure;
}
