
var selection_promotion = function(select) {
	var idPromotion = select.selectedIndex;
	
	document.location = "../administration/index.php?idPromotion="+idPromotion+"";	
}
