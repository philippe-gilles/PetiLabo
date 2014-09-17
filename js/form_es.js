function traduire_statut(id_texte) {
	switch (id_texte) {
		case 0 :
			texte = "Espere, por favor...";
			break;
		case 1 :
			texte = "Verificar la información en el formulario, por favor";
			break;
		case 2 :
			texte = "Error al enviar el formulario: acción desconocido requerido";
			break;
		case 3 : 
			texte = "Error de correo al enviar el formulario";
			break;
		case 4 :
			texte = "Se ha enviado su mensaje, gracias";
			break;
		case 5 :
			texte = "Error en el código de seguridad";
			break;
		default :
			texte = "";
	}
	
	return texte;
}

function traduire_erreur(id_texte) {
	switch (id_texte) {
		case 0 :
			texte = "Este campo es obligatorio";
			break;
		case 1 :
			texte = "Acción requerida desconocida";
			break;
		case 2 :
			texte = "Entrada incorrecta";
			break;
		case 3 : 
			texte = "Error desconocido";
			break;
		case 4 :
			texte = "Error en el código de seguridad";
			break;
		default :
			texte = "";
	}
	
	return texte;
}