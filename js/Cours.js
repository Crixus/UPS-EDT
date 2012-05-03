/*
* Permet de changer la date de fin et de prendre la valeur de la date de debut selectionnee
* @param dateDebut : string date de debut
*/
	function changeDateDebut(dateDebut){
		var inputObj = document.getElementsByName(dateFin)[0];
		inputObj.value = dateDebut;
	}

/*
* Permet de changer l'heure de la date de fin et de prendre la valeur de l'heure de la date de debut selectionnee
* @param heureDebut : string heure de la date de debut
*/
	function changeHeureDebut(heureDebut){
		var heureFin = ((parseInt(heureDebut.substr(0, 2),10) + 2)%24);
		if(heureFin < 10){ 
			heureFin = '0' + heureFin.toString();
		}
		else{ 
			heureFin = heureFin.toString();
		}
		var nouvelleHeure = heureFin + heureDebut.substr(2, 3);
		var inputObj = document.getElementsByName(heureFin)[0];
		inputObj.value = nouvelleHeure;
	}
