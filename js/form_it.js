function traduire_statut(id_texte) {
	switch (id_texte) {
		case 0 :
			texte = "Attendere prego...";
			break;
		case 1 :
			texte = "Si prega di controllare i campi del modulo";
			break;
		case 2 :
			texte = "Errore durante l'invio del modulo: azione sconosciuta richiesto";
			break;
		case 3 : 
			texte = "Errore di e-mail al momento della presentazione del modulo";
			break;
		case 4 :
			texte = "Il vostro messaggio è stato bene inviato, grazie";
			break;
		case 5 :
			texte = "Errore nel codice di sicurezza";
			break;
		default :
			texte = "";
	}
	
	return texte;
}

function traduire_erreur(id_texte) {
	switch (id_texte) {
		case 0 :
			texte = "Questo campo è richiesto";
			break;
		case 1 :
			texte = "Azione richiesta sconosciuta";
			break;
		case 2 :
			texte = "Entrata errata";
			break;
		case 3 : 
			texte = "Errore sconosciuto";
			break;
		case 4 :
			texte = "Errore nel codice di sicurezza";
			break;
		default :
			texte = "";
	}
	
	return texte;
}