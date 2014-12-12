<?php
	@include(_PHP_PATH_ROOT."version.php");

	// Constantes pour les modes d'affichage */
	define("_PETILABO_MODE_SITE", "site");
	define("_PETILABO_MODE_ADMIN", "admin");
	define("_PETILABO_MODE_EDIT", "edit");

	// Constantes générales
	define("_HTTP_LOG_PREFIXE", "log");
	define("_HTTP_LOG_ADMIN", "admin");
	define("_HTML_FIN_LIGNE", "");
	define("_CSS_FIN_LIGNE", "");
	
	// Constantes pour les classes CSS
	define("_CSS_PREFIXE_CONTENU", "contenu_");
	define("_CSS_PREFIXE_INTERIEUR", "int_");
	define("_CSS_PREFIXE_EXTERIEUR", "ext_");
	define("_CSS_PREFIXE_MENU", "menu_");
	define("_CSS_PREFIXE_TEXTE", "texte_");
	define("_CSS_PREFIXE_ICONE", "icone_");
	define("_CSS_PREFIXE_LEGENDE", "legende_");
	define("_CSS_PREFIXE_FORMULAIRE_CHAMP", "form_champ_");
	define("_CSS_PREFIXE_PLAN_NIVEAU", "plan_niveau_");
	define("_CSS_PREFIXE_ACTU", "actu_");
	define("_CSS_CLASSE_SURVOL", "survol_legende");

	// Noms de pages fixes
	define("_HTML_PREFIXE_ACTU", "actu");
	define("_HTML_PATH_ACTU", _HTML_PREFIXE_ACTU._PXP_EXT);
	define("_HTML_PATH_MENTIONS_LEGALES", "legal.php");
	define("_HTML_PATH_CREDITS", "credits.php");
	define("_HTML_PATH_PLAN_DU_SITE", "plandusite.php");
	define("_HTML_PATH_WEBMASTER", "http://www.petilabo.net");

	// Chemins pour les fichiers XML
	define("_XML_PATH", _XML_PATH_ROOT);
	define("_XML_PATH_PAGES", _XML_PATH."pages/");
	define("_XML_PATH_FICHIERS", _XML_PATH."fichiers/");
	define("_XML_PATH_IMAGES_SITE", _XML_PATH."images/");
	define("_XML_PATH_IMAGES_REDUITES_SITE", _XML_PATH."images/reduites/");
	define("_XML_PATH_MODULES", _XML_PATH."modules/");
	define("_XML_PATH_LIBRAIRIE", _XML_PATH."librairie/");
	define("_XML_PATH_CSS", _XML_PATH."css/");
	define("_XML_PATH_JS", _XML_PATH."js/");
	define("_XML_PATH_INTERNE", _PHP_PATH_ROOT."xml/");
	
	// Définitions des fichiers XML
	define("_XML_TRADUCTION", "traduction");
	define("_XML_LEGAL", "legal");
	define("_XML_CREDITS", "credits");
	define("_XML_GENERAL", "general");
	define("_XML_SITE", "site");
	define("_XML_STYLE", "style");
	define("_XML_TEXTE", "texte");
	define("_XML_DOCUMENT", "document");
	define("_XML_MEDIA", "media");
	define("_XML_MENU", "menu");
	define("_XML_PAGE", "page");
	define("_XML_CONTENU", "contenu");
	define("_XML_EXT", ".xml");
	
	// Fichiers XML des modules
	define("_XML_MODULE_ACTU", "actu");
	define("_XML_MODULE_RESA", "resa");

	// Constantes pour la gestion multilingue
	define("_LANGUE_DE", "de");
	define("_LANGUE_EN", "en");
	define("_LANGUE_ES", "es");
	define("_LANGUE_FR", "fr");
	define("_LANGUE_IT", "it");
	define("_LANGUE_NL", "nl");
	define("_LANGUE_PT", "pt");
	
	// Nom de l'image vide
	define("_IMAGE_VIDE_1X1", "vide-1x1");
	define("_ADMIN_IMAGE_VIDE", "admin-image-vide.png");
	
	// Paramètres
	define("_PARAM_LANGUE", "l");
	define("_PARAM_MOBILE", "m");
	define("_PARAM_PAGE", "page");
	define("_PARAM_ID", "id");
	define("_PARAM_TYPE", "t");
	define("_PARAM_POINT_RETOUR", "pr");
	define("_PARAM_FRAGMENT", "id_tab_pr");
	define("_PARAM_ID_LISTE", "il");
	define("_PARAM_MDP", "mdp");
	define("_PARAM_HASH_MDP", "sha1mdp");
	define("_PARAM_CODE_SECRET", "cs");
	