<?php
	// Upload des images
	define("_UPLOAD_DOSSIER", "upload/");
	define("_UPLOAD_FICHIER", "tmp");
	define("_UPLOAD_EXTENSION_JPEG", "jpeg");
	define("_UPLOAD_EXTENSION_JPG", "jpg");
	define("_UPLOAD_EXTENSION_PNG", "png");
	define("_UPLOAD_EXTENSION_GIF", "gif");
	
	// Upload des pièces jointes
	define("_UPLOAD_EXTENSION_PJ", "pj");

	// Types d'erreurs sur les fichiers uploadés
	define("_UPLOAD_NO_ERROR", "0");
	define("_UPLOAD_NOFILE_ERROR", "1");
	define("_UPLOAD_MINSIZE_ERROR", "2");
	define("_UPLOAD_MAXSIZE_ERROR", "3");
	define("_UPLOAD_UPLOAD_ERROR", "4");
	define("_UPLOAD_NAME_ERROR", "5");
	define("_UPLOAD_UNKNOWN_ERROR", "6");
	define("_UPLOAD_TYPE_ERROR", "7");

	// Types pour l'édition de texte simple
	define("_EDIT_TYPE_COPY", "copy");
	define("_EDIT_TYPE_ICONE", "icone");
	define("_EDIT_TYPE_PLAN", "plan");
	define("_EDIT_TYPE_VIDEO", "video");
	define("_EDIT_TYPE_LIEN", "lien");