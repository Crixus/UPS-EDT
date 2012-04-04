
var listeCoursParUE = function(idPromotion) {
	var idUE = document.getElementsByName("idUE")[0].options[document.getElementsByName("idUE")[0].selectedIndex].value;
	var nomUE = document.getElementsByName("idUE")[0].options[document.getElementsByName("idUE")[0].selectedIndex].text;
	
	var div_name = document.getElementsByName("page_administration_listeCoursParUE")[0];
	if (idUE == 0)
		div_name.style.display = 'none';		
	else {
		div_name.style.display = 'block';	

		new Ajax.Request('../administration/fonctions/liste_cours_par_UE.php', {
			method : 'POST',
			encoding : 'iso-8859-1',
			parameters : {
				idPromotion : idPromotion,
				nomUE : nomUE,
				idUE : idUE
			},
			onSuccess : function (transport) {
				document.getElementsByName("page_administration_listeCoursParUE_coursFutur")[0].innerHTML = transport.responseText;
			}
		});
	
	}
}

