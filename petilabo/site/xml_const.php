<?php
	// Booléens
	define("_XML_TRUE", "oui");
	define("_XML_FALSE", "non");
	
	// Sources des fichiers XML
	define("_XML_SOURCE_SITE", "site");
	define("_XML_SOURCE_PAGE", "page");
	define("_XML_SOURCE_MODULE", "module");
	define("_XML_SOURCE_LIBRAIRIE", "librairie");
	define("_XML_SOURCE_INTERNE", "interne");
	
	// Balises, attributs et valeurs transversaux
	define("_XML_NOM", "nom");
	define("_XML_GAUCHE", "gauche");
	define("_XML_CENTRE", "centre");
	define("_XML_DROITE", "droite");
	define("_XML_JUSTIFIE", "justifie");
	define("_XML_HAUT", "haut");
	define("_XML_MILIEU", "milieu");
	define("_XML_BAS", "bas");
	define("_XML_COULEUR", "couleur");
	define("_XML_COULEUR_FOND", "couleur_fond");
	define("_XML_COULEUR_LIEN", "couleur_lien");
	define("_XML_COULEUR_SURVOL", "couleur_survol");
	define("_XML_COULEUR_ACTIF", "couleur_actif");
	define("_XML_MOTIF_FOND", "motif_fond");
	define("_XML_TAILLE", "taille");
	define("_XML_STYLE", "style");
	define("_XML_ALIGNEMENT", "alignement");
	define("_XML_LIEN", "lien");
	define("_XML_LIEN_EDITABLE", "lien_editable");
	define("_XML_ICONE", "icone");

	// Balises pour le fichier XML site (general.xml)
	define("_SITE_RACINE", "racine");
	define("_SITE_LANGUE", "langue");
	define("_SITE_PROPRIETAIRE", "proprietaire");
	define("_SITE_ADR_PROPRIETAIRE", "adresse");
	define("_SITE_TEL_PROPRIETAIRE", "telephone");
	define("_SITE_RCS_PROPRIETAIRE", "rcs");
	define("_SITE_SIRET_PROPRIETAIRE", "siret");
	define("_SITE_REDACTEUR", "redacteur");
	define("_SITE_HEBERGEUR", "hebergeur");
	define("_SITE_CNIL", "cnil");
	define("_SITE_DESTINATAIRE", "destinataire");
	define("_SITE_MODULE", "module");
	define("_SITE_PLAN", "plan");
	define("_SITE_PLAN_PAGE", "page");
	define("_SITE_PLAN_PAGE_NOM", "nom");
	define("_SITE_PLAN_PAGE_TOUCHE", "touche");
	define("_SITE_PLAN_PAGE_PARENT", "parent");
	define("_SITE_SOCIAL_FACEBOOK", "facebook");
	define("_SITE_SOCIAL_TWITTER", "twitter");
	define("_SITE_SOCIAL_GOOGLE_PLUS", "google_plus");
	define("_SITE_SOCIAL_PINTEREST", "pinterest");
	define("_SITE_SOCIAL_TUMBLR", "tumblr");
	define("_SITE_SOCIAL_INSTAGRAM", "instagram");
	define("_SITE_SOCIAL_LINKEDIN", "linkedin");
	define("_SITE_SOCIAL_YOUTUBE", "youtube");
	define("_SITE_SOCIAL_FLICKR", "flickr");
	define("_SITE_PIED_DE_PAGE", "pied_de_page");
	define("_SITE_LOI_COOKIE", "loi_cookie");

	// Valeurs possibles pour l'attribut loi cookie
	define("_SITE_ATTR_LOI_COOKIE_FAIBLE", "faible");
	define("_SITE_ATTR_LOI_COOKIE_MOYEN", "moyen");
	define("_SITE_ATTR_LOI_COOKIE_FORT", "fort");

	// Balises pour le fichier XML site (site.xml)
	define("_SITE_STYLE_TITRE_1", "style_titre_1");
	define("_SITE_STYLE_TITRE_2", "style_titre_2");
	define("_SITE_STYLE_TITRE_3", "style_titre_3");
	define("_SITE_STYLE_TEXTE", "style_texte");
	define("_SITE_LARGEUR", "largeur");
	define("_SITE_LARGEUR_MAX", "largeur_max");
	define("_SITE_LARGEUR_RESPONSIVE", "largeur_responsive");
	define("_SITE_LARGEUR_MIN", "largeur_min");
	define("_SITE_COULEUR_INTERIEUR", "couleur_interieur");
	define("_SITE_MOTIF_INTERIEUR", "motif_interieur");
	define("_SITE_COULEUR_EXTERIEUR", "couleur_exterieur");
	define("_SITE_MOTIF_EXTERIEUR", "motif_exterieur");
	define("_SITE_PAPIERPEINT_EXTERIEUR", "papierpeint_exterieur");

	// Attributs pour le fichier XML site
	define("_SITE_PLAN_PAGE_ATTR_REF", "ref");

	// Valeurs possibles pour les attributs du fichier XML site
	define("_SITE_MODULE_ACTU", "actu");
	define("_SITE_MODULE_RESA", "resa");
	define("_SITE_PIED_DE_PAGE_MANUEL", "manuel");
	define("_SITE_PIED_DE_PAGE_AUTO", "standard");
	define("_SITE_PIED_DE_PAGE_REDUIT", "reduit");

	// Balises pour le fichier XML texte
	define("_TEXTE_TEXTE", "texte");

	// Balises méta pour le fichier XML page
	define("_PAGE_META_TITRE", "meta_titre");
	define("_PAGE_META_DESCR", "meta_descr");
	define("_PAGE_META_TITRE_EDITABLE", "meta_titre_editable");
	define("_PAGE_META_DESCR_EDITABLE", "meta_descr_editable");
	define("_PAGE_META_MULTILINGUE", "meta_multilingue");
	define("_PAGE_META_GOOGLE_ANALYTICS", "meta_google_analytics");
	define("_PAGE_META_PETILABO_ANALITIX", "meta_petilabo_analitix");
	define("_PAGE_META_NOINDEX", "meta_noindex");

	// Balises éléments pour le fichier XML page
	define("_PAGE_CONTENU", "contenu");
	define("_PAGE_BLOC", "bloc");
	define("_PAGE_DRAPEAUX", "drapeaux");
	define("_PAGE_IMAGE", "image");
	define("_PAGE_DIAPORAMA", "diaporama");
	define("_PAGE_VIGNETTES", "vignettes");
	define("_PAGE_CARROUSEL", "carrousel");
	define("_PAGE_GALERIE", "galerie");
	define("_PAGE_TITRE", "titre");
	define("_PAGE_TITRE_BANDEAU", "titre_bandeau");
	define("_PAGE_PARAGRAPHE", "paragraphe");
	define("_PAGE_SYMBOLE", "symbole");
	define("_PAGE_SAUT", "saut");
	define("_PAGE_MENU", "menu");
	define("_PAGE_CARTE", "carte");
	define("_PAGE_VIDEO", "video");
	define("_PAGE_PJ", "piece_jointe");
	define("_PAGE_FORM_CONTACT", "formulaire_contact");
	define("_PAGE_PLAN_DU_SITE", "plan_du_site");
	define("_PAGE_CREDITS", "credits");
	define("_PAGE_MENTIONS_LEGALES", "mentions_legales");
	define("_PAGE_CALENDRIER_RESA", "calendrier");
	define("_PAGE_BANNIERE_ACTU", "banniere_actu");
	define("_PAGE_SOCIAL", "partage_social");
	define("_PAGE_BOUTON_ADMIN", "bouton_admin");
	define("_PAGE_CODE_HTML", "html");
	
	// Attributs pour le fichier XML page
	define("_PAGE_ATTR_SOURCE", "source");
	define("_PAGE_ATTR_NBCOLS", "cols");
	define("_PAGE_ATTR_POSITION", "position");
	define("_PAGE_ATTR_CONTENU_SIGNET", "signet");
	define("_PAGE_ATTR_CONTENU_SEMANTIQUE", "semantique");
	define("_PAGE_ATTR_NIVEAU_TITRE", "niveau");
	define("_PAGE_ATTR_LIEN_TELEPHONIQUE", "telephone");
	define("_PAGE_ATTR_CARTE_ZOOM", "zoom");
	define("_PAGE_ATTR_CARTE_ORIENTATION", "orientation");
	
	// Valeurs possibles pour les attributs du fichier XML page
	define("_PAGE_ATTR_CONTENU_SRC", "src");
	define("_PAGE_ATTR_LIEN_IMAGE", "image");
	define("_PAGE_ATTR_CARTE_PORTRAIT", "portrait");
	define("_PAGE_ATTR_CARTE_PAYSAGE", "paysage");
	define("_PAGE_ATTR_CARTE_CARRE", "carre");
	define("_PAGE_ATTR_LIEN_LEGENDE", "legende");
	define("_PAGE_ATTR_LIEN_FICHIER", "fichier");
	define("_PAGE_ATTR_FORMULAIRE_COURT", "court");
	define("_PAGE_ATTR_FORMULAIRE_LONG", "long");
	define("_PAGE_ATTR_SOURCE_GOOGLE", "google");
	define("_PAGE_ATTR_SOURCE_OSM", "osm");
	define("_PAGE_ATTR_SOURCE_YOUTUBE", "youtube");
	define("_PAGE_ATTR_SOURCE_DAILYMOTION", "dailymotion");
	define("_PAGE_ATTR_SOURCE_VIMEO", "vimeo");
	define("_PAGE_ATTR_FORME_CARRE", "carre");
	define("_PAGE_ATTR_FORME_ROND", "rond");
	define("_PAGE_ATTR_CHAPITRE_LEGAL", "legal");
	define("_PAGE_ATTR_CHAPITRE_PROTECTION", "protection");
	define("_PAGE_ATTR_CHAPITRE_COOKIES", "cookies");
	define("_PAGE_ATTR_CHAPITRE_COPYRIGHT", "copyright");
	define("_PAGE_ATTR_CHAPITRE_TECHNIQUE", "technique");
	define("_PAGE_ATTR_CHAPITRE_PHOTOGRAPHIQUE", "photographique");
	
	// Balises pour le fichier XML media
	define("_MEDIA_STYLE_LEGENDE", "style_legende");
	define("_MEDIA_STYLE_LEGENDE_MARGE_HAUT", "marge_haut");
	define("_MEDIA_STYLE_LEGENDE_MARGE_BAS", "marge_bas");
	define("_MEDIA_STYLE_LEGENDE_MARGE_GAUCHE", "marge_gauche");
	define("_MEDIA_STYLE_LEGENDE_MARGE_DROITE", "marge_droite");
	define("_MEDIA_STYLE_LEGENDE_COULEUR_TEXTE", "couleur_texte");
	define("_MEDIA_STYLE_LEGENDE_STYLE_TEXTE", "style_texte");
	define("_MEDIA_STYLE_LEGENDE_LIEN_SOULIGNE", "lien_souligne");
	define("_MEDIA_STYLE_LEGENDE_SURVOL", "survol");
	define("_MEDIA_STYLE_LEGENDE_NIVEAU_TITRE", "niveau_titre");
	define("_MEDIA_IMAGE", "image");
	define("_MEDIA_IMAGE_SRC", "fichier");
	define("_MEDIA_IMAGE_ALT", "alt");
	define("_MEDIA_IMAGE_LEGENDE", "legende");
	define("_MEDIA_IMAGE_LARGEUR_STANDARD", "largeur_standard");
	define("_MEDIA_IMAGE_HAUTEUR_STANDARD", "hauteur_standard");
	define("_MEDIA_IMAGE_LARGEUR_REDUITE", "largeur_reduite");
	define("_MEDIA_IMAGE_HAUTEUR_REDUITE", "hauteur_reduite");
	define("_MEDIA_IMAGE_COPYRIGHT", "copyright");
	define("_MEDIA_GALERIE", "galerie");
	define("_MEDIA_GALERIE_ELEMENT", "element");
	
	// Attributs pour le fichier XML media
	define("_MEDIA_ATTR_NAVIGATION", "navigation");
	define("_MEDIA_ATTR_BOUTONS", "boutons");
	define("_MEDIA_ATTR_AUTO", "auto");
	define("_MEDIA_ATTR_LARGEUR", "largeur_standard");

	// Balises pour le fichier XML menu
	define("_MENU_STYLE", "style_item");
	define("_MENU_STYLE_TEXTE", "style_texte");
	define("_MENU_STYLE_FOND", "fond");
	define("_MENU_STYLE_FOND_SURVOL", "fond_survol");
	define("_MENU_STYLE_FOND_ACTIF", "fond_actif");
	define("_MENU_STYLE_ESPACE_VERTICAL", "espace_vertical");
	define("_MENU_STYLE_ESPACE_HORIZONTAL", "espace_horizontal");
	define("_MENU_STYLE_EXT_ACTIF", "_actif");
	define("_MENU_ITEM", "item");
	define("_MENU_ITEM_LABEL", "label");
	define("_MENU_ITEM_INFO", "info");
	define("_MENU_MENU", "menu");
	define("_MENU_MENU_CHOIX", "choix");
	define("_MENU_LISTE_CIBLES", "liste_cibles");
	define("_MENU_LISTE_CIBLES_CIBLE", "cible");
	
	// Attributs pour le fichier XML menu
	define("_MENU_ATTR_ITEM_LIEN_EDITABLE_LISTE", "liste");

	// Balises pour le fichier XML style
	define("_STYLE_CONTENU", "style_contenu");
	define("_STYLE_CONTENU_MARGE_HAUT", "marge_haut");
	define("_STYLE_CONTENU_MARGE_BAS", "marge_bas");
	define("_STYLE_CONTENU_PAPIERPEINT_FOND", "papierpeint_fond");
	define("_STYLE_CONTENU_TYPE_SPECIAL", "type_special");
	define("_STYLE_BLOC", "style_bloc");
	define("_STYLE_BLOC_MARGE_HAUT", "marge_haut");
	define("_STYLE_BLOC_MARGE_BAS", "marge_bas");
	define("_STYLE_BLOC_MARGE_GAUCHE", "marge_gauche");
	define("_STYLE_BLOC_MARGE_DROITE", "marge_droite");
	define("_STYLE_BLOC_PAPIERPEINT_FOND", "papierpeint_fond");
	define("_STYLE_BLOC_BORDURE", "bordure");
	define("_STYLE_PUCE", "style_puce");
	define("_STYLE_PUCE_OMBRE", "ombre");
	define("_STYLE_TEXTE", "style_texte");
	define("_STYLE_TEXTE_POLICE", "police");
	define("_STYLE_TEXTE_PUCE", "puce");
	define("_STYLE_TEXTE_DECORATION", "decoration");
	define("_STYLE_FORMULAIRE", "style_formulaire");
	define("_STYLE_FORMULAIRE_TEXTE_CHAMP", "couleur_texte_champ");
	define("_STYLE_FORMULAIRE_FOND_CHAMP", "couleur_fond_champ");
	define("_STYLE_FORMULAIRE_FOND_SAISIE", "couleur_fond_saisie");
	define("_STYLE_FORMULAIRE_TEXTE_BOUTON", "couleur_texte_bouton");
	define("_STYLE_FORMULAIRE_FOND_BOUTON", "couleur_fond_bouton");
	define("_STYLE_FORMULAIRE_TEXTE_STATUT", "couleur_texte_statut");
	define("_STYLE_ACTUALITE", "style_actu");
	define("_STYLE_ACTUALITE_GAUCHE_TITRE", "marge_gauche_titre");
	define("_STYLE_ACTUALITE_HAUT_TITRE", "marge_haut_titre");
	define("_STYLE_ACTUALITE_COULEUR_TITRE", "couleur_titre");
	define("_STYLE_ACTUALITE_FOND_TITRE", "fond_titre");
	define("_STYLE_ACTUALITE_GAUCHE_STITRE", "marge_gauche_sous_titre");
	define("_STYLE_ACTUALITE_HAUT_STITRE", "marge_haut_sous_titre");
	define("_STYLE_ACTUALITE_COULEUR_STITRE", "couleur_sous_titre");
	define("_STYLE_ACTUALITE_FOND_STITRE", "fond_sous_titre");
	define("_STYLE_ACTUALITE_GAUCHE_RESUME", "marge_gauche_resume");
	define("_STYLE_ACTUALITE_HAUT_RESUME", "marge_haut_resume");
	define("_STYLE_ACTUALITE_COULEUR_RESUME", "couleur_resume");
	define("_STYLE_ACTUALITE_FOND_RESUME", "fond_resume");
	
	// Attributs pour le fichier XML style
	define("_STYLE_BLOC_ATTR_TYPE_BORDURE", "type");
	define("_STYLE_TEXTE_ATTR_POLICE_SOURCE", "source");
	define("_STYLE_TEXTE_ATTR_POLICE_FAMILLE", "famille");

	// Valeurs possibles pour les attributs du fichier XML style
	define("_STYLE_ATTR_DECORATION_GRAS", "gras");
	define("_STYLE_ATTR_DECORATION_ITALIQUE", "italique");
	define("_STYLE_ATTR_POLICE_SOURCE_GOOGLE", "google");
	define("_STYLE_ATTR_POLICE_SOURCE_OFL", "ofl");
	define("_STYLE_ATTR_TYPE_BORDURE_MOTIF", "motif");
	define("_STYLE_ATTR_TYPE_BORDURE_OMBRE", "ombre");
	define("_STYLE_ATTR_TYPE_BORDURE_SCOTCH", "scotch");
	define("_STYLE_ATTR_TYPE_SPECIAL_PLEIN_ECRAN", "plein_ecran");
	define("_STYLE_ATTR_TYPE_SPECIAL_HAUTEURS_EGALES", "hauteurs_egales");
	define("_STYLE_ATTR_TYPE_SPECIAL_PIED_DE_PAGE", "pied_de_page");
	
	// Balises pour les documents
	define("_DOCUMENT_DOC", "document");
	define("_DOCUMENT_DOC_FICHIER", "fichier");
	define("_DOCUMENT_DOC_INFO", "info");
	define("_DOCUMENT_DOC_LEGENDE", "legende");
	
	// Balises pour le fichier XML analitix (analitix.xml)
	define("_ANALITIX_LISTE_IP", "liste_ip");
	define("_ANALITIX_IP", "ip");
	define("_ANALITIX_LISTE_PAYS", "liste_pays");
	define("_ANALITIX_PAYS", "pays");
	define("_ANALITIX_LISTE_REFERENTS", "liste_referents");
	define("_ANALITIX_REFERENT", "referent");
	define("_ANALITIX_CONFIG", "configuration");
	define("_ANALITIX_CONFIG_FILTRE_IP", "filtre_ip");
	define("_ANALITIX_CONFIG_FILTRE_PAYS", "filtre_pays");
	define("_ANALITIX_CONFIG_FILTRE_REFERENTS", "filtre_referents");
	define("_ANALITIX_CONFIG_ANONYMISATION_IP", "anonymisation_ip");
	define("_ANALITIX_CONFIG_RESPECT_DNT", "respect_do_not_track");

	// Balises pour le fichier XML config mail
	define("_MAIL_CONFIG_DELAI_SUCCES", "delai_apres_succes");
	define("_MAIL_CONFIG_DELAI_ECHEC", "delai_apres_echec");
	define("_MAIL_CONFIG_MAX_JOUR", "max_envois_par_jour");
	
	// Balises pour le fichier XML journal antiflooding
	define("_MAIL_FLOOD_HORODATAGE_SUCCES", "horodatage_succes");
	define("_MAIL_FLOOD_HORODATAGE_ECHEC", "horodatage_echec");
	define("_MAIL_FLOOD_COMPTAGE_ENVOIS", "comptage_envois");
	
	// Balises pour le module réservations
	define("_MODULE_RESA_DATE", "date");
	define("_MODULE_RESA_ATTR_STATUT", "statut");
	define("_MODULE_RESA_ATTR_PARTIE", "partie");

	// Balises pour le module actualités
	define("_MODULE_ACTU_SOMMAIRE_", "sommaire_");
	define("_MODULE_ACTU_SOMMAIRE_1", "sommaire_1");
	define("_MODULE_ACTU_SOMMAIRE_2", "sommaire_2");
	define("_MODULE_ACTU_SOMMAIRE_3", "sommaire_3");
	define("_MODULE_ACTU_SOMMAIRE_4", "sommaire_4");
	define("_MODULE_ACTU_SOMMAIRE_5", "sommaire_5");