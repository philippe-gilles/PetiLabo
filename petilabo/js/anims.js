// Récupération de la query string d'une URL
(function($) {
    $.QueryString = (function(a) {
        if (a == "") return {};
        var b = {};
        for (var i = 0; i < a.length; ++i)
        {
            var p=a[i].split('=');
            if (p.length != 2) continue;
            b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
        }
        return b;
    })(window.location.search.substr(1).split('&'))
})(jQuery);

// Egalisation des hauteurs de blocs (stylés) dans un contenu
function ajuster_hauteurs_egales() {
	var largeur_courante = $("body").width()+20;
	if (largeur_courante >= largeur_responsive) {
		$("div.contenu_hauteurs_egales").each( function() {
			var children = $(this).find("div[class*='int_']");
			var max = 0;
			children.each(function() {
				hauteur = $(this).css("height", "auto").height();
				if (hauteur > max) {max = hauteur;}
			});
			children.css("height", max+'px');
		});
	}
	else {
		$("div.contenu_hauteurs_egales").each( function() {
			$(this).find("div[class*='int_']").css("height", "auto");
		});
	}
}

/* Fonctions pour le survol des légendes */
function montrer_legende(cadre) {
	var cadre_legende = cadre.find(".survol_legende");
	if (cadre_legende) {
		// cadre_legende.stop().fadeIn("fast");
		cadre_legende.stop().fadeTo("fast",0.8);
	}
}
function cacher_legende(cadre) {
	var cadre_legende = cadre.find(".survol_legende");
	if (cadre_legende) {
		cadre_legende.stop().fadeOut("fast");
	}
}

$(document).ready(function() {
	/* Activation du survol des légendes sur les images fixes */
	$(".image_cadre").mouseenter(function() {montrer_legende($(this));});
	$(".image_cadre").mouseleave(function() {cacher_legende($(this));});
	$(".image_cadre_gauche").mouseenter(function() {montrer_legende($(this));});
	$(".image_cadre_gauche").mouseleave(function() {cacher_legende($(this));});
	$(".image_cadre_droite").mouseenter(function() {montrer_legende($(this));});
	$(".image_cadre_droite").mouseleave(function() {cacher_legende($(this));});

	/* Activation du survol des légendes sur les vues de galeries */
	$('div[class^="vue_galerie_"]').mouseenter(function() {montrer_legende($(this));});
	$('div[class^="vue_galerie_"]').mouseleave(function() {cacher_legende($(this));});

	/* Activation du survol des légendes sur les diaporamas */
	$('div.diaporama').mouseenter(function() {montrer_legende($(this));});
	$('div.diaporama').mouseleave(function() {cacher_legende($(this));});

	/* Image avec légende cliquable */
	$(".legende_avec_lien").mouseenter(function() {
		legende = $(this).parent().children(".cadre_legende");
		if (legende) {legende.css("opacity", "1");}
	});
	$(".legende_avec_lien").mouseleave(function() {
		legende = $(this).parent().children(".cadre_legende");
		if (legende) {legende.css("opacity", "0.8");}
	});
	/* Lien sur la div contenant la légende */
	$(".cadre_legende").mouseenter(function() {
		ancre = $(this).find("a");
		if (ancre) {
			url = ancre.attr("href");
			if (url) {$(this).css("cursor", "pointer").css("opacity", "1");}
		}
	});
	$(".cadre_legende").mouseleave(function() {
		ancre = $(this).find("a");
		if (ancre) {
			url = ancre.attr("href");
			if (url) {$(this).css("cursor", "default").css("opacity", "0.8");}
		}
	});
	$(".cadre_legende").click(function() {
		ancre = $(this).find("a");
		if (ancre) {
			url = ancre.attr("href");
			if (url) {location.href = url;}
		}
	});
	/* Lien sur la div contenant l'image de la PJ */
	$(".file").mouseenter(function() {
		ancre = $(this).find("a");
		if (ancre) {
			url = ancre.attr("href");
			if (url) {$(this).css("cursor", "pointer");}
		}
		etiquette = $(this).find("p");
		if (etiquette) {
			etiquette.css("background", "#000000");
		}
	});
	$(".file").mouseleave(function() {
		ancre = $(this).find("a");
		if (ancre) {
			url = ancre.attr("href");
			if (url) {$(this).css("cursor", "default");}
		}
		if (etiquette) {etiquette.css("background", "#b80000");}
	});
	$(".file").click(function() {
		ancre = $(this).find("a");
		if (ancre) {
			url = ancre.attr("href");
			if (url) {location.href = url;}
		}
	});
	/* Navigation dans les diaporamas */
	$(".diaporama").mouseenter(function() {
		$(".boutons_diapo_nav").css("display", "block");
	});
	$(".diaporama").mouseleave(function() {
		$(".boutons_diapo_nav").css("display", "none");
	});
	/* Actu */
	$(".actu").mouseenter(function() {
		$(".boutons_actu_nav").css("display", "block");
	});
	$(".actu").mouseleave(function() {
		$(".boutons_actu_nav").css("display", "none");
	});
	$(".div_actu").mouseenter(function() {
		id = $(this).attr("id");
		if (id) {
			no_actu = parseInt(id.substr(5));
			if ((no_actu > 0) && (no_actu < 6)) {
				$(this).css("cursor", "pointer").css("opacity", "0.8");
			}
		}
	});
	$(".div_actu").mouseleave(function() {
		id = $(this).attr("id");
		if (id) {
			no_actu = parseInt(id.substr(5));
			if ((no_actu > 0) && (no_actu < 6)) {
				$(this).css("cursor", "default").css("opacity", "1");
			}
		}
	});
	$(".div_actu").click(function() {
		id = $(this).attr("id");
		if (id) {
			no_actu = parseInt(id.substr(5));
			if ((no_actu > 0) && (no_actu < 6)) {
				var lang = $.QueryString["l"];
				url = "actu.php?id="+no_actu;
				if (lang) {if (lang.length > 0) {url = url+"&l="+lang;}}
				location.href = url;
			}
		}
	});
	/* Liens locaux sur signets (scrollTo) */
	$('a[href^="#"]').click(function(){
		var the_id = $(this).attr("href");  
		$('html, body').animate({scrollTop:$(the_id).offset().top},'slow');  
		return false;  
	});
	/* Changement de mois dans un calendrier de réservation */
	$("select.select_resa").change(function() {
		var id = $(this).attr("id");
		if (id) {
			div_id = id.replace("select_", "resa_");
			var no_mois = parseInt($(this).val());
			var hauteur = parseInt($("div.wrap_resa").height());
			var pos = no_mois * hauteur;
			$("#"+div_id).animate({scrollTop:pos},'slow');
		}
	});
});

$(window).load(function() {
	ajuster_hauteurs_egales();
	$(window).resize(ajuster_hauteurs_egales);
});