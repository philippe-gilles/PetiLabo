function traduire_statut(id_texte) {
	switch (id_texte) {
		case 0 :
			texte = "Espere por favor...";
			break;
		case 1 :
			texte = "Por favor verifique os campos do formulário";
			break;
		case 2 :
			texte = "Erro aquando do envio do formulário: acção necessária desconhecida";
			break;
		case 3 : 
			texte = "Erro do e-mail ao submeter o formulário";
			break;
		case 4 :
			texte = "A vossa mensagem foi enviada bem, obrigado";
			break;
		case 5 :
			texte = "Erro no código de segurança";
			break;
		default :
			texte = "";
	}
	
	return texte;
}

function traduire_erreur(id_texte) {
	switch (id_texte) {
		case 0 :
			texte = "Este campo é requerido";
			break;
		case 1 :
			texte = "Ação requerida desconhecida";
			break;
		case 2 :
			texte = "Entrada incorreta";
			break;
		case 3 : 
			texte = "Erro desconhecido";
			break;
		case 4 :
			texte = "Erro no código de segurança";
			break;
		default :
			texte = "";
	}
	
	return texte;
}