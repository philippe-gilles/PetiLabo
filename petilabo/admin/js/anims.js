function diff_date(e,t){var r=t.getTime()-e.getTime();return Math.ceil(r/864e5)}function uc_first(e){var t=e.substr(0,1),r=e.substr(1,e.length-1);return ucfirst_ext=t.toUpperCase()+r,ucfirst_ext}function get_extension(e){var t=e.split("."),r=t[t.length-1];return r}function get_taille(e){var t=e.split("."),r=t[0],t=r.split("_"),a=parseInt(t[1]);return a}function upload(e){"pj"==e&&($("#id_taille_pj").html("Chargement..."),$("#id_upload_pj").upload("upload_pj.php",function(e){if(erreur=parseInt(e),erreur>0){switch(erreur){case 1:str_erreur="Fichier introuvable";break;case 2:str_erreur="Taille du fichier incorrecte";break;case 3:str_erreur="Taille du fichier incorrecte (5 Mo maximum)";break;case 4:case 6:str_erreur="Problème lors du téléchargement du fichier";break;case 5:str_erreur="Nom du fichier incorrect";break;case 7:str_erreur="Type du fichier incorrect (formats acceptés : JPG et PNG)";break;default:str_erreur="Erreur inconnue"}$("#id_taille_pj").html("<strong>Erreur : </strong>"+str_erreur),$("#id_dif_ext").hide()}else{var t=get_extension(e);$("#id_extension_pj").html("<b>Extension</b> : "+uc_first(t));var r=$(".texte_extension").attr("id"),a=r.substr(7);a!=t?$("#id_dif_ext").show():$("#id_dif_ext").hide();var i=get_taille(e);$("#id_taille_pj").html("<b>Taille</b> : "+i+" ko"),$("input[name='upload_name_pj']").attr("value",e)}},"html")),"img"==e&&($("#id_nouvelle_image").attr("src","images/loader.gif").show(),$("#id_taille_image").html("Chargement..."),$("#id_upload_image").upload("upload_image.php",function(e){if(erreur=parseInt(e),erreur>0){switch(erreur){case 1:str_erreur="Fichier introuvable";break;case 2:str_erreur="Taille du fichier incorrecte";break;case 3:str_erreur="Taille du fichier incorrecte (5 Mo maximum)";break;case 4:case 6:str_erreur="Problème lors du téléchargement du fichier";break;case 5:str_erreur="Nom du fichier incorrect";break;case 7:str_erreur="Type du fichier incorrect (formats acceptés : JPG et PNG)";break;default:str_erreur="Erreur inconnue"}$("#id_nouvelle_image").attr("src","").hide(),taille=$("#id_taille_image"),taille.addClass("erreur_zone"),taille.html("<strong>Erreur : </strong>"+str_erreur),$("#id_taille_exces").hide(),$("#id_ajustements").hide()}else{var t="upload/"+e,r=new Image;r.onload=function(){$("#id_nouvelle_image").attr("src",this.src+"?id="+Math.floor(1e6*Math.random())).show(),largeur=this.width,hauteur=this.height,taille=$("#id_taille_image"),taille.removeClass("erreur_zone"),taille.html("Largeur : "+parseInt(largeur)+"px, hauteur : "+parseInt(hauteur)+"px"),largeur>1e3||hauteur>800?$("#id_taille_exces").show():$("#id_taille_exces").hide()},r.src=t,$("#id_ajustements").show()}},"html"))}var idx_pm=-1,idx_am=-1,background_hover="";color_hover="#000",$(document).ready(function(){$("div[id^=bloc_]").hover(function(){$(this).stop(!0).css("opacity",1).css("cursor","url('images/stylom.cur'),pointer")},function(){$(this).stop(!0).css("opacity",.3).css("cursor","default")}),$("div[id^=bloc_]").click(function(){id=$(this).attr("id"),tab_class=id.replace("bloc_","tab_"),tab=$("#"+tab_class),tab&&($(".tab_edit").slideUp("fast"),tab.slideDown("slow").css("display","block"))}),$("p.admin_onglets_stats a").click(function(){var e=$(this).attr("id"),t=e.replace("onglet_","");return $("p.admin_onglets_stats a").css("background","#333").attr("href","#"),$(this).css("background","#666").removeAttr("href"),$("div.admin_courbe_stats").css("display","none"),$("#"+t).css("display","block"),!1}),$(document).on("click","ul.tabs li",function(){$("ul.tabs li").removeClass("active"),$(this).addClass("active"),$(".tab_content").hide();var e=$(this).find("a").attr("href"),t=e.lastIndexOf("#"),r=e.substr(t);return $(r).fadeIn(),!1}),$(document).on("click","p.champ input.bouton_suppr",function(){return formulaire=$("#id_suppr_image"),formulaire&&(display=formulaire.css("display"),"none"==display?formulaire.slideDown(400):formulaire.slideUp(400)),!1}),$(document).on("click","p.champ input.bouton_annul",function(){return formulaire=$("#id_suppr_image"),formulaire&&formulaire.slideUp(400),!1}),$(document).on("mouseenter","table.calendrier td.pm.active",function(){color_hover=$(this).css("color"),background_hover=$(this).css("background-image"),$(this).css("background-image","url('images/trame.png')").css("color","#fff").css("cursor","pointer")}),$(document).on("mouseleave","table.calendrier td.pm.active",function(){$(this).css("background-image",background_hover).css("color",color_hover).css("cursor","default")}),$(document).on("mouseenter","table.calendrier td.am.active",function(){idx_am=parseInt($("table.calendrier td.am").index($(this))),$("table.calendrier td.am").slice(1+idx_pm,1+idx_am).css("background-image","url('images/trame.png')").css("color","#fff").css("cursor","pointer"),$("table.calendrier td.pm").slice(idx_pm,idx_am).css("background-image","url('images/trame.png')").css("color","#fff").css("cursor","pointer")}),$(document).on("mouseleave","table.calendrier td.am.active",function(){$("table.calendrier td.am").slice(1+idx_pm,1+idx_am).css("background-image","").css("color","#000").css("cursor","default"),$("table.calendrier td.pm").slice(1+idx_pm,idx_am).css("background-image","").css("color","#000").css("cursor","default")}),$(document).on("click","table.calendrier td.pm.active",function(){idx_am=-1,idx_pm=parseInt($("table.calendrier td.pm").index($(this))),$("table.calendrier td.am").css("background-image","").css("color","#000").css("cursor","default"),$("table.calendrier td.pm").slice(0,idx_pm).css("background-image","").css("color","#000"),$("table.calendrier td.pm:gt("+idx_pm+")").css("background-image","").css("color","#000"),$("table.calendrier td.pm").removeClass("active").css("cursor","default"),$("table.calendrier td.am:gt("+idx_pm+")").addClass("active"),id=$(this).attr("id"),id&&(date_debut=id.replace("pm_",""),elt_date_debut=date_debut.split("_"),val_date_debut=elt_date_debut[2]+"/"+elt_date_debut[1]+"/"+elt_date_debut[0],$("input[name=date_debut]").val(val_date_debut),$("input[name=date_fin]").val(""),$("#id_nb_nuits").html(""))}),$(document).on("click","table.calendrier td.am.active",function(){idx_am=parseInt($("table.calendrier td.am").index($(this))),$("table.calendrier td.am").slice(1+idx_pm,1+idx_am).css("background-image","url('images/noir.png')").css("color","#fff"),$("table.calendrier td.pm").slice(idx_pm,idx_am).css("background-image","url('images/noir.png')").css("color","#fff"),$("table.calendrier td.pm").addClass("active"),$("table.calendrier td.am").removeClass("active").css("cursor","default"),id=$(this).attr("id"),id&&(date_fin=id.replace("am_",""),elt_date_fin=date_fin.split("_"),val_date_fin=elt_date_fin[2]+"/"+elt_date_fin[1]+"/"+elt_date_fin[0],$("input[name=date_fin]").val(val_date_fin),date_debut=$("input[name=date_debut]").val(),elt_date_debut=date_debut.split("/"),obj_date_debut=new Date(elt_date_debut[2],elt_date_debut[1]-1,elt_date_debut[0],0,0,0),obj_date_fin=new Date(elt_date_fin[0],elt_date_fin[1]-1,elt_date_fin[2],0,0,0),nb_nuits=diff_date(obj_date_debut,obj_date_fin),$("#id_nb_nuits").html(nb_nuits<1?"":1==nb_nuits?"pour une nuit":"pour "+nb_nuits+" nuits"))}),$("#geoloc").css("display","none")}),$(window).load(function(){$("#visites_uniques").css("display","none"),$("#visites").css("display","none")});