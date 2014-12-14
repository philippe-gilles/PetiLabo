function traduire_statut(id_texte) {
	switch (id_texte) {
		case 0 :
			texte = "Even geduld aub...";
			break;
		case 1 :
			texte = "Kijk dan op de formuliervelden alstublieft";
			break;
		case 2 :
			texte = "Fout bij het verzenden van het formulier: Onbekend Actie vereist";
			break;
		case 3 : 
			texte = "Fout van e-mail bij het indienen van het formulier";
			break;
		case 4 :
			texte = "Uw bericht werd wel degelijk, bedankt verzonden";
			break;
		case 5 :
			texte = "Fout in de veiligheidscode";
			break;
		default :
			texte = "";
	}
	
	return texte;
}

function traduire_erreur(id_texte) {
	switch (id_texte) {
		case 0 :
			texte = "Dit gebied wordt vereist";
			break;
		case 1 :
			texte = "Onbekende vereiste actie";
			break;
		case 2 :
			texte = "Onjuiste invoer";
			break;
		case 3 : 
			texte = "Onbekende fout";
			break;
		case 4 :
			texte = "Fout in de veiligheidscode";
			break;
		default :
			texte = "";
	}
	
	return texte;
}