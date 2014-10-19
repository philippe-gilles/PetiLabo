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
function redirect_mobile() {
	url = location.href;
	pos = url.lastIndexOf("/");
	new_url = url.substr(0, pos+1)+"mobile/"+url.substr(1+parseInt(pos));
	location.href = new_url;
}
function redirect_desktop() {
	url = location.href;
	new_url = url.replace("/mobile/", "/");
	location.href = new_url;
}
function url_mobile() {
	bool = false;
	url = location.href;
	pos = url.indexOf("/mobile/");
	if (pos >= 0) {bool = true;}
	return bool;
}
 /* Détection mobile par la méthode detectmobilebrowsers.com */
(function(a){(jQuery.browser=jQuery.browser||{}).mobile=/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))})(navigator.userAgent||navigator.vendor||window.opera);
function detect_mobile() {
	return jQuery.browser.mobile;
}
/* Fonctions pour le survol des légendes */
function montrer_legende(cadre) {
	cadre_legende = cadre.find(".survol_legende");
	if (cadre_legende) {
		// cadre_legende.stop().fadeIn("fast");
		cadre_legende.stop().fadeTo("fast",0.8);
	}
}
function cacher_legende(cadre) {
	cadre_legende = cadre.find(".survol_legende");
	if (cadre_legende) {
		cadre_legende.stop().fadeOut("fast");
	}
}
$(document).ready(function() {
	/* Question pour surf depuis mobile */
	cookie_surf = $.cookie('cookie_surf');
	version_mobile = url_mobile();
	if (cookie_surf) {
		if ((cookie_surf == "mobile") && (version_mobile == false)) {
			rep = confirm("Souhaitez-vous quitter la version allégée pour mobile ?");
			if (rep == true) {$.cookie('cookie_surf', 'desktop', { path: '/' });}
			else {redirect_mobile();}
		}
		else if ((cookie_surf == "desktop") && (version_mobile == true)) {
			rep = confirm("Souhaitez-vous quitter la version standard ?");
			if (rep == true) {$.cookie('cookie_surf', 'mobile', { path: '/' });}
			else {redirect_desktop();}
		}
	}
	else {
		mobile = detect_mobile();
		if (mobile == true) {
			if (version_mobile == false) {
				rep = confirm("Souhaitez-vous accéder à la version allégée pour mobile ?");
				if (rep == true) {
					$.cookie('cookie_surf', 'mobile', { path: '/' });
					redirect_mobile();
				}
				else {$.cookie('cookie_surf', 'desktop', { path: '/' });}
			}
			else {$.cookie('cookie_surf', 'mobile', { path: '/' });}
		}
		else {
			if (version_mobile == true) {
				rep = confirm("Souhaitez-vous accéder à la version standard ?");
				if (rep == true) {
					$.cookie('cookie_surf', 'desktop', { path: '/' });
					redirect_desktop();
				}
				else {$.cookie('cookie_surf', 'mobile', { path: '/' });}
			}
			else {$.cookie('cookie_surf', 'desktop', { path: '/' });}
		}
	}
	
	/* Activation du survol des légendes sur les images fixes */
	$(".image_cadre").mouseenter(function() {montrer_legende($(this));});
	$(".image_cadre").mouseleave(function() {cacher_legende($(this));});

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