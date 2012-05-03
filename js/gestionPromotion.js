/*
* Permet de modifier l'URL de la page suivant la promotion selectionnee
* @param select : int correspondant a l'id de la promotion choisie
*/
var selection_promotion = function(select) {
	var idPromotion = select.selectedIndex;
	
	document.location = "../administration/index.php?idPromotion="+idPromotion+"";	
}
