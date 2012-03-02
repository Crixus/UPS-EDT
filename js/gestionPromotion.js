
var selection_promotion = function(select) {
	var idPromotion = select.selectedIndex;
	
	document.location = "../formulaires/index.php?idPromotion="+idPromotion+"";	
}