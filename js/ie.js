function ajuster_plein_ecran() {
	var hauteur = $(window).height();
	var hauteur_min = $(".contenu_plein_ecran").css('min-height');
	if (hauteur_min) {
		if ((hauteur_min.length > 2) && (hauteur_min != "none")) {
			hauteur_min.replace(" ", "");
			var unite = hauteur_min.substr(hauteur_min.length - 2);
			if (unite == "px") {
				var valeur_min = parseInt(hauteur_min.substr(0, hauteur_min.length - 2));
				if ((valeur_min > 0) && (hauteur < valeur_min)) {hauteur = valeur_min;}
			}
		}
	}
	$(".contenu_plein_ecran").css('height', hauteur+'px');
}
$(document).ready(function() {
	$(window).resize(ajuster_plein_ecran);
	ajuster_plein_ecran();
});