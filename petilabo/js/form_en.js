function traduire_statut(id_texte) { 
	switch (id_texte) {
		case 0 :
			texte = "Please wait...";
			break;
		case 1 :
			texte = "Please check the form fields";
			break;
		case 2 :
			texte = "Error while sending the form : unknown action required";
			break;
		case 3 : 
			texte = "E-mail error when submitting the form";
			break;
		case 4 :
			texte = "Your message has been sent, thank you";
			break;
		case 5 :
			texte = "Error in security code";
			break;
		default :
			texte = "";
	}
	
	return texte;
}

function traduire_erreur(id_texte) {
	switch (id_texte) {
		case 0 :
			texte = "This field is required";
			break;
		case 1 :
			texte = "Unknown required action";
			break;
		case 2 :
			texte = "Incorrect input";
			break;
		case 3 : 
			texte = "Unknown error";
			break;
		case 4 :
			texte = "Error in security code";
			break;
		default :
			texte = "";
	}
	
	return texte;
}