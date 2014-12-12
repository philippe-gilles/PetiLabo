function raz_champs_long() {
	$('#id_nom').val("");
	$('#id_prenom').val("");
	$('#id_tel').val("");
	$('#id_email').val("");
	$('#id_message').val("");
	$('#id_captcha').val("");
}
function raz_champs_court() {
	$('#id_nom').val("");
	$('#id_email').val("");
	$('#id_message').val("");
}
function ecrire_erreur(id, err, id_texte) {
	elem = $("#err_"+id);
	if (elem) {
		switch (err) {
			case 0 :
				elem.html("&nbsp;");
				break;
			case 1 :
				texte = traduire_erreur(0);
				elem.html(texte);
				break;
			case 2 :
				texte = traduire_erreur(id_texte);
				elem.html(texte);
				break;
			default :
				texte = traduire_erreur(3);
				elem.html(texte);
		}
	}
}
function ecrire_statut(id_texte) {
	statut = $("#status_msg");
	if (statut) {
		texte = traduire_statut(id_texte);
		statut.html(texte);
	}
}

$(document).ready(function() {
	var cap1 = 1+parseInt(Math.floor(Math.random()*9));
	var cap2 = 1+parseInt(Math.floor(Math.random()*9));
	var cap3 = cap1 + cap2;
	var q_captcha = $('#q_captcha');
	if (q_captcha) {q_captcha.html(cap1+"&nbsp;+&nbsp;"+cap2+"&nbsp;=&nbsp;");}

	$('#id_form_contact_long').submit(function(e) {
		rep = parseInt($('#id_captcha').val());
		if (isNaN(rep)) { rep=0; }
		if (rep != cap3) {
			ecrire_statut(5);
			ecrire_erreur("captcha", 2, 4);
			return false;
		}
		else {
			ecrire_statut(0);
			$("#err_captcha").html("");
			$.ajax({
				type:$(this).attr("method"),
				url:$(this).attr("action"),
				data: $(this).serialize(),
				dataType: "json",
				success: function(data){
					field_err = parseInt(data.nom)+parseInt(data.prenom)+parseInt(data.tel)+parseInt(data.email)+parseInt(data.message);
					ecrire_erreur("action", parseInt(data.action), 1);
					ecrire_erreur("nom", parseInt(data.nom), 2);
					ecrire_erreur("prenom", parseInt(data.prenom), 2);
					ecrire_erreur("tel", parseInt(data.tel), 2);
					ecrire_erreur("email", parseInt(data.email), 2);
					ecrire_erreur("message", parseInt(data.message), 2);
					if (field_err > 0) {
						ecrire_statut(1);
					}
					else if (parseInt(data.action) > 0) {
						ecrire_statut(2);
					}
					else if (parseInt(data.send) > 0) {
						ecrire_statut(3);
					}
					else {
						ecrire_statut(4);
						raz_champs_long();
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					ecrire_statut(3);
				}
			});
		}
		cap1 = 1+parseInt(Math.floor(Math.random()*9));
		cap2 = 1+parseInt(Math.floor(Math.random()*9));
		cap3 = cap1 + cap2;
		$("#q_captcha").html(cap1+"&nbsp;+&nbsp;"+cap2+"&nbsp;=&nbsp;");
		$("#id_captcha").attr("value", "");
 
		e.preventDefault();
		return false;
	});

	$('#id_form_contact_court').submit(function(e) {
		ecrire_statut(0);
		$("#err_captcha").html("");
		$.ajax({
			type:$(this).attr("method"),
			url:$(this).attr("action"),
			data: $(this).serialize(),
			dataType: "json",
			success: function(data){
				field_err = parseInt(data.nom)+parseInt(data.email)+parseInt(data.message);
				ecrire_erreur("action", parseInt(data.action), 1);
				ecrire_erreur("nom", parseInt(data.nom), 2);
				ecrire_erreur("email", parseInt(data.email), 2);
				ecrire_erreur("message", parseInt(data.message), 2);
				if (field_err > 0) {
					ecrire_statut(1);
				}
				else if (parseInt(data.action) > 0) {
					ecrire_statut(2);
				}
				else if (parseInt(data.send) > 0) {
					ecrire_statut(3);
				}
				else {
					ecrire_statut(4);
					raz_champs_court();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				ecrire_statut(3);
			}
		});

		e.preventDefault();
		return false;
	});
});