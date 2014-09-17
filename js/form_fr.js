function traduire_statut(id_texte) {
	switch (id_texte) {
		case 0 :
			texte = "Veuillez patienter...";
			break;
		case 1 :
			texte = "Veuillez vérifier les champs du formulaire s'il vous plaît";
			break;
		case 2 :
			texte = "Erreur lors de l'envoi du formulaire : action requise inconnue";
			break;
		case 3 : 
			texte = "Erreur de messagerie lors de l'envoi du formulaire";
			break;
		case 4 :
			texte = "Votre message a bien été envoyé, merci";
			break;
		case 5 :
			texte = "Erreur dans le code de sécurité";
			break;
		default :
			texte = "";
	}
	
	return texte;
}

function traduire_erreur(id_texte) {
	switch (id_texte) {
		case 0 :
			texte = "Ce champ est obligatoire";
			break;
		case 1 :
			texte = "Action requise inconnue";
			break;
		case 2 :
			texte = "Saisie incorrecte";
			break;
		case 3 :
			texte = "Erreur inconnue";
			break;
		case 4 :
			texte = "Erreur dans le code de sécurité";
			break;
		default :
			texte = "";
	}
	
	return texte;
}