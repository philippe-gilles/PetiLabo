function traduire_statut(id_texte) {
	switch (id_texte) {
		case 0 :
			texte = "Bitte warten...";
			break;
		case 1 :
			texte = "Bitte überprüfen Sie die Formularfelder";
			break;
		case 2 :
			texte = "Fehler bei der übermittlung des Formulars: unbekannte erforderliche Aktion";
			break;
		case 3 : 
			texte = "Fehler von E-Mail, wenn Sie das Formular absenden";
			break;
		case 4 :
			texte = "Ihre Nachricht wurde gesendet, danke";
			break;
		case 5 :
			texte = "Fehler im Sicherheitscode";
			break;
		default :
			texte = "";
	}
	
	return texte;
}

function traduire_erreur(id_texte) {
	switch (id_texte) {
		case 0 :
			texte = "Dieses fangen wird angefordert auf";
			break;
		case 1 :
			texte = "Unbekannte erforderliche Tätigkeit";
			break;
		case 2 :
			texte = "Falscher Eingang";
			break;
		case 3 : 
			texte = "Unbekannte Störung";
			break;
		case 4 :
			texte = "Fehler im Sicherheitscode";
			break;
		default :
			texte = "";
	}
	
	return texte;
}