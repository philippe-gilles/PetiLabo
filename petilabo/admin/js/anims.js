/* Animations pour tous les types de formulaires   */
/* Ces scripts sont centralisés et chargés dès le  */
/* départ à cause d'un bug IE<9 avec MagnificPopup */
var idx_pm = -1;var idx_am = -1;
var background_hover = "";color_hover = "#000";
function diff_date(d1, d2) {
	var diff = d2.getTime() - d1.getTime();
	return Math.ceil(diff/(1000*60*60*24));
}
function uc_first(texte) {
	var init_ext = texte.substr(0,1);
	var reste_ext = texte.substr(1, texte.length-1);
	ucfirst_ext = init_ext.toUpperCase()+reste_ext;
	
	return ucfirst_ext;
}
function get_extension(nom_fichier) {
	var parts = nom_fichier.split(".");
	var ext = parts[(parts.length-1)];
	
	return ext;
}
function get_taille(nom_fichier) {
	var parts = nom_fichier.split(".");
	var base = parts[0];
	var parts = base.split("_");
	var taille = parseInt(parts[1]);
	
	return taille;
}
function upload(type) {
	if (type == "pj") {
		$("#id_taille_pj").html("Chargement...");
		$("#id_upload_pj").upload(
			"upload_pj.php",
			function (retour) {
				erreur = parseInt(retour);
				if (erreur > 0) {
					switch (erreur) {
						case 1:
							str_erreur = "Fichier introuvable";
							break;
						case 2:
							str_erreur = "Taille du fichier incorrecte";
							break;
						case 3:
							str_erreur = "Taille du fichier incorrecte (5 Mo maximum)";
							break;
						case 4:
						case 6:
							str_erreur = "Problème lors du téléchargement du fichier";
							break;
						case 5:
							str_erreur = "Nom du fichier incorrect";
							break;
						case 7:
							str_erreur = "Type du fichier incorrect (formats acceptés : JPG et PNG)";
							break;
						default :
							str_erreur = "Erreur inconnue";
					}
					$("#id_taille_pj").html("<strong>Erreur : </strong>"+str_erreur);
					$("#id_dif_ext").hide();
				}
				else {
					var ext = get_extension(retour);
					$("#id_extension_pj").html("<b>Extension</b> : "+uc_first(ext));
					var id_ext = $(".texte_extension").attr("id");
					var new_ext = id_ext.substr(7);
					if (new_ext != ext) {
						$("#id_dif_ext").show();
					}
					else {
						$("#id_dif_ext").hide();
					}
					var taille = get_taille(retour);
					$("#id_taille_pj").html("<b>Taille</b> : "+taille+" ko");
					$("input[name='upload_name_pj']").attr("value", retour);
				} 
			},
			"html");
	}
	
	if (type == "img") {
		$("#id_nouvelle_image").attr("src","images/loader.gif").show();
		$("#id_taille_image").html("Chargement...");
		$("#id_upload_image").upload(
			"upload_image.php",
			function (retour) {
				erreur = parseInt(retour);
				if (erreur > 0) {
					switch (erreur) {
						case 1:
							str_erreur = "Fichier introuvable";
							break;
						case 2:
							str_erreur = "Taille du fichier incorrecte";
							break;
						case 3:
							str_erreur = "Taille du fichier incorrecte (5 Mo maximum)";
							break;
						case 4:
						case 6:
							str_erreur = "Problème lors du téléchargement du fichier";
							break;
						case 5:
							str_erreur = "Nom du fichier incorrect";
							break;
						case 7:
							str_erreur = "Type du fichier incorrect (formats acceptés : JPG et PNG)";
							break;
						default :
							str_erreur = "Erreur inconnue";
					}
					$("#id_nouvelle_image").attr("src","").hide();
					taille = $('#id_taille_image');
					taille.addClass('erreur_zone');
					taille.html("<strong>Erreur : </strong>"+str_erreur);
					$("#id_taille_exces").hide();
					$("#id_ajustements").hide();
				}
				else {
					var src = "upload/"+retour;
					var img = new Image();
					img.onload = function() {
						$("#id_nouvelle_image").attr("src",this.src+"?id="+Math.floor(Math.random()* 1000000)).show();
						largeur = this.width;
						hauteur = this.height;
						taille = $('#id_taille_image');
						taille.removeClass('erreur_zone');
						taille.html("Largeur : "+parseInt(largeur)+"px, hauteur : "+parseInt(hauteur)+"px");
						if ((largeur > 1000) || (hauteur > 800)) {$("#id_taille_exces").show();}
						else {$("#id_taille_exces").hide();}
					}
					img.src = src;
					$("#id_ajustements").show();
				} 
			},
			"html");
	}
}
$(document).ready(function() {
	/* Survol des blocs  */
	$("div[id^=bloc_]").hover(function() {
	  $(this).stop(true).css("opacity", 1).css("cursor", "url('images/stylom.cur'),pointer");
	}, function() {
	  $(this).stop(true).css("opacity", 0.3).css("cursor", "default");
	});
	/* Clic sur les blocs */
	$("div[id^=bloc_]").click(function() {
		id = $(this).attr("id");
		tab_class = id.replace("bloc_", "tab_");
		tab = $("#"+tab_class);
		if (tab) {
			$(".tab_edit").slideUp("fast");
			tab.slideDown("slow").css("display", "block");
		}
	});
	/* Onglets dans le formulaire texte */
	$(document).on("click", "ul.tabs li", function() {
		$("ul.tabs li").removeClass("active");
		$(this).addClass("active");
		$(".tab_content").hide();
		var activeTab = $(this).find("a").attr("href");
		var hash_pos = activeTab.lastIndexOf("#");
		var tab = activeTab.substr(hash_pos);
		$(tab).fadeIn();
		return false;
	});
	/* Bouton suppression de l'image */
	$(document).on("click", "p.champ input.bouton_suppr", function() {
		formulaire = $("#id_suppr_image");
		if (formulaire) {
			display = formulaire.css("display");
			if (display == "none") {
				formulaire.slideDown(400);
			}
			else {
				formulaire.slideUp(400);
			}
		}
		return false;
	});
	$(document).on("click", "p.champ input.bouton_annul", function() {
		formulaire = $("#id_suppr_image");
		if (formulaire) {formulaire.slideUp(400);}
		return false;
	});
	/* Gestion des calendriers */
	$(document).on("mouseenter", "table.calendrier td.pm.active", function() {
		color_hover = $(this).css("color");
		background_hover = $(this).css("background-image");
		$(this).css("background-image","url('images/trame.png')").css("color","#fff").css("cursor","pointer");
	});
	$(document).on("mouseleave", "table.calendrier td.pm.active", function() {
		$(this).css("background-image", background_hover).css("color",color_hover).css("cursor","default");
	});
	$(document).on("mouseenter", "table.calendrier td.am.active", function() {
		idx_am = parseInt($("table.calendrier td.am").index($(this)));
		$("table.calendrier td.am").slice(1+idx_pm,1+idx_am).css("background-image","url('images/trame.png')").css("color","#fff").css("cursor","pointer");
		$("table.calendrier td.pm").slice(idx_pm,idx_am).css("background-image","url('images/trame.png')").css("color","#fff").css("cursor","pointer");
	});
	$(document).on("mouseleave", "table.calendrier td.am.active", function() {
		$("table.calendrier td.am").slice(1+idx_pm,1+idx_am).css("background-image", "").css("color","#000").css("cursor","default");
		$("table.calendrier td.pm").slice(1+idx_pm,idx_am).css("background-image", "").css("color","#000").css("cursor","default");
	});
	$(document).on("click", "table.calendrier td.pm.active", function() {
		idx_am = -1;
		idx_pm = parseInt($("table.calendrier td.pm").index($(this)));
		$("table.calendrier td.am").css("background-image", "").css("color","#000").css("cursor","default");
		$("table.calendrier td.pm").slice(0,idx_pm).css("background-image", "").css("color","#000")
		$("table.calendrier td.pm:gt("+idx_pm+")").css("background-image", "").css("color","#000");
		$("table.calendrier td.pm").removeClass("active").css("cursor","default");
		$("table.calendrier td.am:gt("+idx_pm+")").addClass("active");
		id = $(this).attr("id");
		if (id) {
			date_debut = id.replace("pm_", "");
			elt_date_debut = date_debut.split("_");
			val_date_debut = elt_date_debut[2]+"/"+elt_date_debut[1]+"/"+elt_date_debut[0];
			$("input[name=date_debut]").val(val_date_debut);
			$("input[name=date_fin]").val("");
			$("#id_nb_nuits").html("");
		}
	});
	$(document).on("click", "table.calendrier td.am.active", function() {
		idx_am = parseInt($("table.calendrier td.am").index($(this)));
		$("table.calendrier td.am").slice(1+idx_pm,1+idx_am).css("background-image","url('images/noir.png')").css("color","#fff");
		$("table.calendrier td.pm").slice(idx_pm,idx_am).css("background-image","url('images/noir.png')").css("color","#fff");
		$("table.calendrier td.pm").addClass("active");
		$("table.calendrier td.am").removeClass("active").css("cursor","default");
		id = $(this).attr("id");
		if (id) {
			date_fin = id.replace("am_", "");
			elt_date_fin = date_fin.split("_");
			val_date_fin = elt_date_fin[2]+"/"+elt_date_fin[1]+"/"+elt_date_fin[0];
			$("input[name=date_fin]").val(val_date_fin);
			date_debut = $("input[name=date_debut]").val();
			elt_date_debut = date_debut.split("/");
			obj_date_debut = new Date(elt_date_debut[2],elt_date_debut[1]-1,elt_date_debut[0],0,0,0);
			obj_date_fin  = new Date(elt_date_fin[0],elt_date_fin[1]-1,elt_date_fin[2],0,0,0);
			nb_nuits = diff_date(obj_date_debut, obj_date_fin);
			if (nb_nuits < 1) {$("#id_nb_nuits").html("");}
			else if (nb_nuits == 1) {$("#id_nb_nuits").html("pour une nuit");}
			else {$("#id_nb_nuits").html("pour "+nb_nuits+" nuits");}
		}
	});
});