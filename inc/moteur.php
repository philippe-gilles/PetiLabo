<?php
	class moteur {
		protected $nom_domaine = null;protected $nom_page = null;protected $dir_page = null;
		protected $html = null;
		protected $site = null;
		protected $texte = null;protected $style = null;protected $document = null;
		protected $media = null;protected $menu = null;	protected $page = null;
		protected $langue_page = null;
		protected $module_actu = null;protected $est_actu = false;protected $no_actu = 0;
		protected $module_resa = null;
		protected $polices = array();protected $police_par_defaut = null;
		protected $est_pied_interne = false;protected $est_pied_reduit = false;

		protected function charger_xml() {
			// Création des structures au niveau site
			$this->site = new xml_site();
			// Fichier general.xml
			$ret = $this->site->ouvrir(_XML_PATH._XML_GENERAL._XML_EXT);
			if (!$ret) {
				echo "<p>Erreur lors de l'ouverture du fichier "._XML_PATH._XML_GENERAL._XML_EXT."</p>\n";
				return null;
			}
			// Fichier site.xml
			$ret = $this->site->ouvrir(_XML_PATH._XML_SITE._XML_EXT);
			if (!$ret) {
				echo "<p>Erreur lors de l'ouverture du fichier XML site</p>\n";
				return null;
			}
			// Ouverture des modules facultatifs
			if ($this->site->has_module(_SITE_MODULE_ACTU)) {
				$this->module_actu = new xml_module_actu();
				$ret = $this->module_actu->ouvrir(_XML_PATH_MODULES._XML_MODULE_ACTU._XML_EXT);
			}
			if ($this->site->has_module(_SITE_MODULE_RESA)) {
				$this->module_resa = new xml_module_resa();
			}
			// Gestion des textes
			$this->texte = new xml_texte();
			// Création de la liste des langues utilisées sur le site
			$this->charger_liste_langues();
			// Lecture des textes pour le site et la page
			$ret = $this->texte->ouvrir(_XML_SOURCE_SITE, _XML_PATH._XML_TEXTE._XML_EXT);
			$ret = $this->texte->ouvrir(_XML_SOURCE_PAGE, _XML_PATH_PAGES.$this->nom_page."/"._XML_TEXTE._XML_EXT);
			if ($this->site->has_module(_SITE_MODULE_ACTU)) {
				$ret = $this->texte->ouvrir(_XML_SOURCE_MODULE, _XML_PATH_MODULES._XML_TEXTE._XML_EXT);
			}
			// Ouverture des styles
			$this->style = new xml_style();
			$ret = $this->style->ouvrir(_XML_PATH._XML_STYLE._XML_EXT);
			$ret = $this->style->ouvrir(_XML_PATH_PAGES.$this->nom_page."/"._XML_STYLE._XML_EXT);
			if ($this->site->has_module(_SITE_MODULE_ACTU)) {
				$ret = $this->style->ouvrir(_XML_PATH_MODULES._XML_STYLE._XML_EXT);
			}
			// Ouverture des documents
			$this->document = new xml_document();
			$ret = $this->document->ouvrir(_XML_PATH._XML_DOCUMENT._XML_EXT);
			$ret = $this->document->ouvrir(_XML_PATH_PAGES.$this->nom_page."/"._XML_DOCUMENT._XML_EXT);
			// Ouverture des médiathèques
			$this->media = new xml_media();
			$ret = $this->media->ouvrir(_XML_SOURCE_SITE, _XML_PATH._XML_MEDIA._XML_EXT);
			$ret = $this->media->ouvrir(_XML_SOURCE_PAGE, _XML_PATH_PAGES.$this->nom_page."/"._XML_MEDIA._XML_EXT);
			if ($this->site->has_module(_SITE_MODULE_ACTU)) {
				$ret = $this->media->ouvrir(_XML_SOURCE_MODULE, _XML_PATH_MODULES._XML_MEDIA._XML_EXT);
			}
			// Ouverture des menuthèques
			$this->menu = new xml_menu();
			$ret = $this->menu->ouvrir(_XML_PATH._XML_MENU._XML_EXT);
			$ret = $this->menu->ouvrir(_XML_PATH_PAGES.$this->nom_page."/"._XML_MENU._XML_EXT);
			if ($this->site->has_module(_SITE_MODULE_ACTU)) {
				$ret = $this->menu->ouvrir(_XML_PATH_MODULES._XML_MENU._XML_EXT);
			}
			// Ouverture du fichier XML page
			$this->page = new xml_page();
			$ret = $this->page->ouvrir(_XML_PATH_PAGES.$this->nom_page."/"._XML_PAGE._XML_EXT);
			if (!$ret) {
				echo "<p>Erreur lors de l'ouverture du fichier XML page</p>\n";
				return null;
			}
			// Positionnement du drapeau "interne/externe"
			$this->est_pied_interne = (strcmp($this->site->get_pied_de_page(),_SITE_PIED_DE_PAGE_INTERNE))?false:true;
			$this->est_pied_reduit = (strcmp($this->site->get_pied_de_page(),_SITE_PIED_DE_PAGE_REDUIT))?false:true;
			// Création de l'utilitaire html
			$this->html = new html($this->est_pied_interne, $this->est_pied_reduit);
		}
		protected function charger_xml_css($admin = false) {
			// Surcharge du fichier CSS standard
			$this->html->charger_xml_css();
			// CSS général
			$css = "";
			if ($this->site) {$css .= $this->site->extraire_css();}
			if ($this->style) {$css .= $this->style->extraire_css();}
			// Si c'est pour administration on désactive les hover
			if ($this->menu) {$css .= $this->menu->extraire_css(!($admin));}
			if ($this->media) {$css .= $this->media->extraire_css();}
			$this->html->ecrire_css($css);
			// Spécifique IE7/IE8
			$css_ie = "";
			if ($this->site) {$css_ie .= $this->site->extraire_css_ie();}
			if ($this->style) {$css_ie .= $this->style->extraire_css_ie();}
			$this->html->ecrire_css_ie($css_ie);
		}
		protected function charger_xml_js($admin = false) {
			if (!($admin)) {$this->html->charger_xml_js();}
		}
		protected function charger_police($nom_style) {
			if (strlen($nom_style) > 0) {
				$style = $this->style->get_style_texte($nom_style);
				if ($style) {
					$police = $style->get_police();
					if ((strlen($police) > 0) && (strcmp(trim(strtolower($police)), "serif"))) {
						// On vérifie que la police n'a pas été déjà chargée
						if (!(in_array($police, $this->polices))) {
							$this->html->charger_police($police);
							$this->polices[] = $police;
						}
					}
				}
			}
		}
		protected function charger_polices_par_defaut() {
			// Chargement des polices utilisées dans les styles
			$police_h1 = $this->site->get_style_titre_1();
			$this->charger_police($police_h1);
			$police_h2 = $this->site->get_style_titre_2();
			$this->charger_police($police_h2);
			$police_h3 = $this->site->get_style_titre_3();
			$this->charger_police($police_h3);
			$police_p = $this->site->get_style_paragraphe();
			$this->charger_police($police_p);
			// Au passage on récupère la police par défaut
			if (strlen($police_p) > 0) {
				$style = $this->style->get_style_texte($police_p);
				if ($style) {
					$police = $style->get_police();
					if (strlen($police) > 0) {
						$this->police_par_defaut = $police;
					}
				}
			}
		}
		// Cette méthode se positionne sur un bloc et parcourt son contenu
		// Pour chaque type de contenu une méthode d'écriture est appelée
		// Les méthodes d'écriture se contentent de parser les paramètres
		// propres à ce type de contenu afin de les transmettre à des  
		// méthodes génériques qui sont à implémenter dans les classes filles
		protected function ecrire_bloc($mode, &$obj_bloc, $cpt_cont, $cpt_bloc) {
			$tab_obj = array();
			$repere = $obj_bloc->get_repere();
			if ($repere) {
				$this->page->pointer_sur_bloc($repere);
				$nb_elems = $obj_bloc->get_nb_elems();
				for ($cpt_elem = 0;$cpt_elem < $nb_elems;$cpt_elem++) {
					$balise = $obj_bloc->get_elem($cpt_elem);
					$occ = $obj_bloc->get_idx_elem($cpt_elem);
					try {
						$fonction = "ecrire_bloc_".$balise;
						$obj = $this->$fonction($mode, $occ);
						if ($obj) {$tab_obj[] = $obj;}
					}
					catch (Exception $e) {
						echo $e->getMessage(), "\n";
					}
				}
			}
			return $tab_obj;
		}

		// Ecriture des titres
		protected function ecrire_bloc_titre($mode, $occ) {
			// Lecture de l'id texte
			$id_valeur = $this->page->lire_valeur_n(_PAGE_TITRE, $occ);
			$id_texte = $this->parser_id_crochets_actu($id_valeur);
			// Lecture de l'attribut "niveau"
			$niveau = (int) $this->page->lire_attribut_n(_PAGE_TITRE, $occ, _PAGE_ATTR_NIVEAU_TITRE);
			$niveau = min(max($niveau, 1), 3);
			// Lecture de l'attribut "style"
			$style_inline = $this->page->lire_attribut_n(_PAGE_TITRE, $occ, _PAGE_ATTR_STYLE_PARAGRAPHE);
			$style_titre = (strlen($style_inline) > 0)?$style_inline:$this->site->get_style_titre($niveau);
			// Création de l'objet "titre"
			$obj = new obj_titre($this->texte, $niveau, $style_titre, $id_texte);
			if ($obj) {$obj->afficher($mode, $this->langue_page);}
			return $obj;
		}
		// Ecriture des paragraphes
		protected function ecrire_bloc_paragraphe($mode, $occ) {
			// Lecture de l'id texte
			$id_valeur = $this->page->lire_valeur_n(_PAGE_PARAGRAPHE, $occ);
			$id_texte = $this->parser_id_crochets_actu($id_valeur);
			// Lecture de l'attribut "style"
			$style_inline = $this->page->lire_attribut_n(_PAGE_PARAGRAPHE, $occ, _PAGE_ATTR_STYLE_PARAGRAPHE);
			$style = (strlen($style_inline) > 0)?$style_inline:$this->site->get_style_paragraphe();
			// Lecture du lien téléphonique
			$lien_telephonique = $this->page->lire_attribut_n(_PAGE_PARAGRAPHE, $occ, _PAGE_ATTR_LIEN_TELEPHONIQUE);
			// Création de l'objet "paragraphe"
			$obj = new obj_paragraphe($this->texte, $style, $id_texte, $lien_telephonique);
			if ($obj) {$obj->afficher($mode, $this->langue_page);}
			return $obj;
		}
		// Ecriture des sauts
		protected function ecrire_bloc_saut($mode, $occ) {
			// Lecture de la hauteur
			$h_saut = (float) $this->page->lire_valeur_n(_PAGE_SAUT, $occ);
			// Création de l'objet "saut"
			$obj = new obj_saut($h_saut);
			if ($obj) {$obj->afficher($mode, $this->langue_page);}
			return $obj;
		}
		// Ecriture des images
		protected function ecrire_bloc_image($mode, $occ) {
			// Lecture de l'id image
			$valeur = $this->page->lire_valeur_n(_PAGE_IMAGE, $occ);
			$src = $this->parser_id_crochets_actu($valeur);
			$image = $this->media->get_image($src);
			if (!($image)) {return null;}
			// Création de l'objet "image"
			$obj = $this->parser_image($image);
			if ($obj) {$obj->afficher($mode, $this->langue_page);}
			return $obj;
		}
		// Ecriture des diaporamas
		protected function ecrire_bloc_diaporama($mode, $occ) {
			// Lecture de l'id galerie
			$val_gal = $this->page->lire_valeur_n(_PAGE_DIAPORAMA, $occ);
			$nom_gal = $this->parser_id_crochets_actu($val_gal);
			$gal = $this->media->get_galerie($nom_gal);
			if (!($gal)) {return null;}
			// Lecture de l'attribut "navigation"
			$navigation = $this->page->lire_attribut_n(_PAGE_DIAPORAMA, $occ, _MEDIA_ATTR_NAVIGATION);
			$has_navigation = (!(strcmp(trim(strtolower($navigation)), _XML_TRUE)))?true:false;
			// Lecture de l'attribut "boutons"
			$boutons = $this->page->lire_attribut_n(_PAGE_DIAPORAMA, $occ, _MEDIA_ATTR_BOUTONS);
			$has_boutons = (!(strcmp(trim(strtolower($boutons)), _XML_TRUE)))?true:false;
			// Création de l'objet diaporama
			$obj = new obj_diaporama($this->texte, $nom_gal, $has_navigation, $has_boutons);
			if (!($obj)) {return null;}
			// Lecture des images
			$largeur_max = 0;
			$nb_images = $gal->get_nb_elems();
			for ($cpt_img = 0;$cpt_img < $nb_images;$cpt_img++) {
				$nom_image = $gal->get_elem($cpt_img);
				$image = $this->media->get_image($nom_image);
				if (!($image)) {continue;}
				// Calcul de la largeur max
				$largeur = $image->get_width();
				$largeur_max = ($largeur > $largeur_max)?$largeur:$largeur_max;
				// Création de l'objet "image"
				$obj_image = $this->parser_image($image);
				if ($obj_image) {$obj->ajouter_image($obj_image);}
			}
			$obj->afficher($mode, $this->langue_page, $largeur_max);
			return $obj;
		}
		// Ecriture des carrousels
		protected function ecrire_bloc_carrousel($mode, $occ) {
			// Lecture de l'id galerie
			$val_gal = $this->page->lire_valeur_n(_PAGE_CARROUSEL, $occ);
			$nom_gal = $this->parser_id_crochets_actu($val_gal);
			$gal = $this->media->get_galerie($nom_gal);
			if (!($gal)) {return null;}
			// Lecture de l'attribut "navigation"
			$navigation = $this->page->lire_attribut_n(_PAGE_CARROUSEL, $occ, _MEDIA_ATTR_NAVIGATION);
			$has_navigation = (!(strcmp(trim(strtolower($navigation)), _XML_TRUE)))?true:false;
			// Lecture de l'attribut "boutons"
			$boutons = $this->page->lire_attribut_n(_PAGE_CARROUSEL, $occ, _MEDIA_ATTR_BOUTONS);
			$has_boutons = (!(strcmp(trim(strtolower($boutons)), _XML_TRUE)))?true:false;
			// Lecture de l'attribut "largeur_standard"
			$largeur_max = (int) $this->page->lire_attribut_n(_PAGE_CARROUSEL, $occ, _MEDIA_ATTR_LARGEUR);
			// Lecture de l'attribut "nbcols"
			$nb_cols = (int) $this->page->lire_attribut_n(_PAGE_CARROUSEL, $occ, _PAGE_ATTR_NBCOLS_VIGNETTE);
			// Création de l'objet carrousel
			$obj = new obj_carrousel($this->texte, $nom_gal, $has_navigation, $has_boutons, $largeur_max, $nb_cols);
			if (!($obj)) {return null;}
			$nb_images = $gal->get_nb_elems();
			for ($cpt_img = 0;$cpt_img < $nb_images;$cpt_img++) {
				$nom_image = $gal->get_elem($cpt_img);
				$image = $this->media->get_image($nom_image);
				if (!($image)) {continue;}
				// Création de l'objet "image"
				$obj_image = $this->parser_image($image);
				if ($obj_image) {$obj->ajouter_image($obj_image);}
			}
			$obj->afficher($mode, $this->langue_page);
			return $obj;
		}
		// Ecriture des vignettes
		protected function ecrire_bloc_vignettes($mode, $occ) {
			// Lecture de l'id galerie
			$val_gal = $this->page->lire_valeur_n(_PAGE_VIGNETTES, $occ);
			$nom_gal = $this->parser_id_crochets_actu($val_gal);
			$gal = $this->media->get_galerie($nom_gal);
			if (!($gal)) {return null;}
			// Lecture de l'attribut "nbcols"
			$cols = $this->page->lire_attribut_n(_PAGE_VIGNETTES, $occ, _PAGE_ATTR_NBCOLS_VIGNETTE);
			$nb_cols = max($cols, 1);
			// Création de l'objet vignettes
			$obj = new obj_vignettes($this->texte, $nom_gal, $nb_cols);
			if (!($obj)) {return null;}
			$nb_images = $gal->get_nb_elems();
			for ($cpt_img = 0;$cpt_img < $nb_images;$cpt_img++) {
				$nom_image = $gal->get_elem($cpt_img);
				$image = $this->media->get_image($nom_image);
				if (!($image)) {continue;}
				// Création de l'objet "image"
				$obj_image = $this->parser_image($image);
				if ($obj_image) {$obj->ajouter_image($obj_image);}
			}
			$obj->afficher($mode, $this->langue_page);
			return $obj;
		}
		// Ecriture des galeries
		protected function ecrire_bloc_galerie($mode, $occ) {
			// Lecture de l'id galerie
			$val_gal = $this->page->lire_valeur_n(_PAGE_GALERIE, $occ);
			$nom_gal = $this->parser_id_crochets_actu($val_gal);
			$gal = $this->media->get_galerie($nom_gal);
			if (!($gal)) {return null;}
			// Lecture de l'attribut "navigation"
			$navigation = $this->page->lire_attribut_n(_PAGE_GALERIE, $occ, _MEDIA_ATTR_NAVIGATION);
			$has_navigation = (!(strcmp(trim(strtolower($navigation)), _XML_TRUE)))?true:false;
			// Lecture de l'attribut "boutons"
			$boutons = $this->page->lire_attribut_n(_PAGE_GALERIE, $occ, _MEDIA_ATTR_BOUTONS);
			$has_boutons = (!(strcmp(trim(strtolower($boutons)), _XML_TRUE)))?true:false;
			// Lecture de l'attribut "nbcols"
			$cols = $this->page->lire_attribut_n(_PAGE_GALERIE, $occ, _PAGE_ATTR_NBCOLS_VIGNETTE);
			$nb_cols = max($cols, 1);
			// Lecture de l'attribut "position"
			$position = $this->page->lire_attribut_n(_PAGE_GALERIE, $occ, _PAGE_ATTR_POSITION_GALERIE);
			// Création de l'objet galerie
			$obj = new obj_galerie($this->texte, $nom_gal, $has_navigation, $has_boutons, $nb_cols);
			if (!($obj)) {return null;}
			$nb_images = $gal->get_nb_elems();
			for ($cpt_img = 0;$cpt_img < $nb_images;$cpt_img++) {
				$nom_image = $gal->get_elem($cpt_img);
				$image = $this->media->get_image($nom_image);
				if (!($image)) {continue;}
				// Création de l'objet "image"
				$obj_image = $this->parser_image($image);
				if ($obj_image) {$obj->ajouter_image($obj_image);}
			}
			// Affichage de la galerie en fonction de la position
			switch ($position) {
				case _PAGE_ATTR_POSITION_DROITE :
					$obj->afficher($mode, $this->langue_page, true, false);
					break;
				case _PAGE_ATTR_POSITION_BAS :
					$obj->afficher($mode, $this->langue_page, false, false);
					break;
				case _PAGE_ATTR_POSITION_GAUCHE :
					$obj->afficher($mode, $this->langue_page, true, true);
					break;
				default :
					$obj->afficher($mode, $this->langue_page, false, true);
					break;
			}
			return $obj;
		}
		// Ecriture des menus
		protected function ecrire_bloc_menu($mode, $occ) {
			// Lecture de l'id menu
			$val_menu = $this->page->lire_valeur_n(_PAGE_MENU, $occ);
			$nom_menu = $this->parser_id_crochets_actu($val_menu);
			$menu = $this->menu->get_menu($nom_menu);
			if (!($menu)) {return null;}
			// Lecture de l'attribut "alignement"
			$alignement = $this->page->lire_attribut_n(_PAGE_MENU, $occ, _PAGE_ATTR_ALIGNEMENT);
			// Création de l'objet menu
			$obj = new obj_menu($this->texte, $nom_menu, $alignement);
			$nb_items = $menu->get_nb_items();
			$nb_items_non_vides = 0;
			for ($cpt = 0;$cpt < $nb_items; $cpt++) {
				$cle_item = (string) $menu->get_item($cpt);
				$item = $this->menu->get_item($cle_item);
				if (!($item)) {continue;}
				// Création de l'item
				$id_label_brut = $item->get_label();
				$id_label = $this->parser_id_crochets_actu($id_label_brut);
				$id_style = $item->get_style();
				$style_menu = $this->menu->get_style($id_style);
				$obj_item = $obj->ajouter_item($item, $style_menu, $id_label);
				// Ajout des infos sur le lien de l'item
				$lien_brut = $item->get_lien();
				$lien_simple = $this->parser_lien_crochets_actu($lien_brut);
				$lien_editable = $item->get_lien_editable();
				$is_editable = (strlen($lien_editable) > 0)?true:false;
				if ($is_editable) {
					$cible = $this->texte->get_texte($lien_editable, $this->langue_page);
					$id_liste = $item->get_liste_cibles();
				}
				else {$cible = $lien_simple;$id_liste = null;}
				if (strlen($cible) > 0) {
					$nb_items_non_vides += 1;
					$lien_actif = $this->est_url_active($cible);
					$access_key = $this->url_accesskey($cible);
					$cible_multilingue = $this->url_multilingue($cible);
				}
				else {$lien_actif = false;$access_key = null;$cible_multilingue = null;}
				$obj_item->ajouter_lien($cible_multilingue, $is_editable, $id_liste, $lien_actif, $access_key);
			}
			$obj->afficher($mode, $this->langue_page, $nb_items_non_vides);
			return $obj;
		}
		// Ecriture des cartes
		protected function ecrire_bloc_carte($mode, $occ) {
			// Lecture de l'id texte
			$id_valeur = $this->page->lire_valeur_n(_PAGE_CARTE, $occ);
			$id_texte = $this->parser_id_crochets_actu($id_valeur);
			// Création de l'objet carte
			$obj = new obj_carte($this->texte, $id_texte);
			if ($obj) {$obj->afficher($mode, $this->langue_page);}
			return $obj;
		}
		// Ecriture des videos 
		protected function ecrire_bloc_video($mode, $occ) {
			// Lecture de l'attribut source
			$source = $this->page->lire_attribut_n(_PAGE_VIDEO, $occ, _PAGE_ATTR_SOURCE_VIDEO);
			if (strlen($source) > 0) {
				// Lecture de l'id texte
				$id_valeur = $this->page->lire_valeur_n(_PAGE_VIDEO, $occ);
				$id_texte = $this->parser_id_crochets_actu($id_valeur);
				// Création de l'objet video
				$obj = new obj_video($this->texte, $id_texte, $source);
				if ($obj) {$obj->afficher($mode, $this->langue_page);}
			} else {$obj = null;}
			return $obj;
		}
		// Ecriture des pièces jointes
		protected function ecrire_bloc_piece_jointe($mode, $occ) {
			// Lecture de l'id PJ
			$id_pj = $this->page->lire_valeur_n(_PAGE_PJ, $occ);
			$pj = $this->document->get_document($id_pj);
			if (!($pj)) {return null;}
			// Lecture de l'attribut "lien"
			$type_lien = $this->page->lire_attribut_n(_PAGE_PJ, $occ, _PAGE_ATTR_LIEN_PJ);
			$lien = ((strcmp($type_lien, _PAGE_ATTR_LIEN_IMAGE)) && (strcmp($type_lien, _PAGE_ATTR_LIEN_FICHIER)))?_PAGE_ATTR_LIEN_LEGENDE:$type_lien;
			// Préparation du style par défaut
			$style = $this->site->get_style_paragraphe();
			// Création de l'objet PJ
			$obj = new obj_pj($pj, $this->texte, $id_pj, $lien);
			if ($obj) {$obj->afficher($mode, $this->langue_page, $style);}
			return $obj;
		}
		// Ecriture du formulaire de contact
		protected function ecrire_bloc_formulaire_contact($mode, $occ) {
			// Lecture de l'attribut "style"
			$style = $this->page->lire_attribut_n(_PAGE_FORM_CONTACT, $occ, _PAGE_ATTR_FORMULAIRE_STYLE);
			// Préparation du style par défaut
			$style_p = $this->site->get_style_paragraphe();
			// Création de l'objet formulaire
			$obj = new obj_formulaire($this->texte, $style);
			if ($obj) {$obj->afficher($mode, $this->langue_page, $style_p);}
			return $obj;
		}
		// Ecriture des drapeaux
		protected function ecrire_bloc_drapeaux($mode, $occ) {
			// Lecture de l'attribut "alignement"
			$alignement = $this->page->lire_attribut_n(_PAGE_DRAPEAUX, $occ, _PAGE_ATTR_ALIGNEMENT);
			// Création de l'objet drapeaux
			$obj = new obj_drapeaux($this->texte, $alignement, $this->page->get_meta_multilingue());
			if (!($obj)) return null;
			$nb_langues = $this->texte->get_nb_langues();
			for ($cpt_langue = 0;$cpt_langue < $nb_langues;$cpt_langue++) {
				$langue = $this->texte->get_langue($cpt_langue);
				$nom = $this->texte->get_nom($langue);
				$pos = $this->texte->get_position($langue);
				$href = ($this->dir_page);
				if (strcmp($langue, $this->texte->get_langue_par_defaut())) {$href .= "/".$langue;}
				$href .= "/".$this->nom_page._PXP_EXT;
				if ($this->est_actu) {$href .= "?"._PARAM_ID."=".$this->no_actu;}
				$href = str_replace("//", "/", $href);
				$obj->ajouter_drapeau($langue, $nom, $pos, $href);
			}
			$obj->afficher($mode, $this->langue_page);
			return $obj;
		}
		// Ecriture du plan du site
		protected function ecrire_bloc_plan_du_site($mode, $occ) {
			// Création de l'objet plan du site
			$obj = new obj_plan_du_site($this->texte);
			$this->parcourir_plan_du_site($obj, 0, null);
			$style_p = $this->site->get_style_paragraphe();
			$obj->afficher($mode, $this->langue_page, $style_p);
			return $obj;
		}
		// Ecriture des crédits
		protected function ecrire_bloc_credits($mode, $occ) {
			// Lecture du chapitre
			$chapitre = $this->page->lire_valeur_n(_PAGE_CREDITS, $occ);
			$chapitre_technique = ((strlen($chapitre) == 0) || (!(strcmp($chapitre, _PAGE_ATTR_CHAPITRE_TECHNIQUE))))?true:false;
			$chapitre_photographique = ((strlen($chapitre) == 0) || (!(strcmp($chapitre, _PAGE_ATTR_CHAPITRE_PHOTOGRAPHIQUE))))?true:false;
			$sections_chapitre = (strlen($chapitre) == 0)?true:false;
			// Lecture de l'attribut taille
			$taille_vignette = (int) $this->page->lire_attribut_n(_PAGE_CREDITS, $occ, _PAGE_ATTR_CREDITS_TAILLE);
			// En cas de crédits techniques on charge les textes supplémentaires
			if ($chapitre_technique) {
				$ret = $this->texte->ouvrir(_XML_SOURCE_INTERNE, _XML_PATH_INTERNE._XML_CREDITS._XML_EXT);
				if (!($ret)) {$chapitre_technique = false;}
			}
			// En cas de crédits photographiques on charge les images supplémentaires
			if ($chapitre_photographique) {
				$nb_pages = $this->site->get_nb_pages();
				for ($cpt = 0;$cpt < $nb_pages;$cpt++) {
					$ref = $this->site->get_page_ref($cpt);
					$nom = str_replace(_PXP_EXT, "", $ref);
					$src_media = _XML_PATH_PAGES.$nom."/"._XML_MEDIA._XML_EXT;
					$src_texte = _XML_PATH_PAGES.$nom."/"._XML_TEXTE._XML_EXT;
					$ret = $this->media->ouvrir(_XML_SOURCE_PAGE, $src_media, $cpt);
					$ret = $this->texte->ouvrir(_XML_SOURCE_PAGE, $src_texte, $cpt);
				}
			}
			// Création de l'objet credits
			$style_p = $this->site->get_style_paragraphe();
			$obj = new obj_credits($this->texte, $chapitre_technique, $chapitre_photographique, $sections_chapitre, $taille_vignette);
			if (!($obj)) {return null;}
			if ($chapitre_photographique) {
				// Parcours des images pour repérer celles qui portent un copyright
				$nb_images = $this->media->get_nb_images();
				for ($cpt = 0;$cpt < $nb_images;$cpt++) {
					$image = $this->media->get_image_by_index($cpt);
					if (!($image)) {continue;}
					$est_vide = $image->get_est_vide();
					if ($est_vide) {continue;}
					$id_copyright = $image->get_copyright();
					if (strlen($id_copyright) == 0) {continue;}
					$copyright = trim($this->texte->get_texte($id_copyright, $this->texte->get_langue_par_defaut()));
					if (strlen($copyright) == 0) {continue;}
					$src = $image->get_src_reduite();
					if (strlen($src) == 0) {continue;}
					$obj->ajouter_credit_photo($src, $copyright, $image->get_width(), $image->get_height());
				}
			}
			$obj->afficher($mode, $this->langue_page, $style_p);
			return $obj;
		}
		// Ecriture des mentions légales
		protected function ecrire_bloc_mentions_legales($mode, $occ) {
			// Lecture du chapitre
			$chapitre = $this->page->lire_valeur_n(_PAGE_MENTIONS_LEGALES, $occ);
			$chapitre_mentions = ((strlen($chapitre) == 0) || (!(strcmp($chapitre, _PAGE_ATTR_CHAPITRE_LEGAL))))?true:false;
			$chapitre_protection = ((strlen($chapitre) == 0) || (!(strcmp($chapitre, _PAGE_ATTR_CHAPITRE_PROTECTION))))?true:false;
			$chapitre_cookies = ((strlen($chapitre) == 0) || (!(strcmp($chapitre, _PAGE_ATTR_CHAPITRE_COOKIES))))?true:false;
			$chapitre_copyright = ((strlen($chapitre) == 0) || (!(strcmp($chapitre, _PAGE_ATTR_CHAPITRE_COPYRIGHT))))?true:false;
			$sections_chapitre = (strlen($chapitre) == 0)?true:false;
			// On charge les textes supplémentaires pour les mentions légales
			$ret = $this->texte->ouvrir(_XML_SOURCE_INTERNE, _XML_PATH_INTERNE._XML_LEGAL._XML_EXT);
			// Création de l'objet mentions légales
			$style_p = $this->site->get_style_paragraphe();
			$obj = new obj_mentions_legales($this->texte, $chapitre_mentions, $chapitre_protection, $chapitre_cookies, $chapitre_copyright, $sections_chapitre);
			if (!($obj)) {return null;}
			// Ajout des informations
			$adr_site = $this->site->get_url_racine();
			$obj->ajouter_site($adr_site);
			$nom_editeur = $this->site->get_proprietaire();
			$adr_editeur = $this->site->get_adresse();
			$tel_editeur = $this->site->get_telephone();
			$rcs_editeur = $this->site->get_rcs();
			$siret_editeur = $this->site->get_siret();
			$obj->ajouter_editeur($nom_editeur, $adr_editeur, $tel_editeur, $rcs_editeur, $siret_editeur);
			$redacteur = $this->site->get_redacteur();
			$obj->ajouter_redacteur($redacteur);
			$hebergeur = $this->site->get_hebergeur();
			$obj->ajouter_hebergeur($hebergeur);
			$no_cnil = $this->site->get_cnil();
			$obj->ajouter_cnil($no_cnil);
			$cookie_info = $this->site->has_loi_cookie();
			$obj->ajouter_cookie_info($cookie_info);
			// Affichage
			$obj->afficher($mode, $this->langue_page, $style_p);
			return $obj;
		}
		// Ecriture des boutons de partage social
		protected function ecrire_bloc_partage_social($mode, $occ) {
			// Lecture de la forme des boutons
			$forme = $this->page->lire_valeur_n(_PAGE_SOCIAL, $occ);
			$forme_carree = (strcmp($forme, _PAGE_ATTR_FORME_ROND))?true:false;
			// Lecture de l'attribut "taille"
			$taille = (int) $this->page->lire_attribut_n(_PAGE_SOCIAL, $occ, _PAGE_ATTR_SOCIAL_TAILLE);
			$grande_taille = ($taille < 33)?false:true;
			// Récupération des données du partage
			$titre_editable = $this->page->get_meta_titre_editable();
			if (strlen($titre_editable) > 0) {
				$titre = $this->texte->get_texte($titre_editable, $this->langue_page);
			}
			else {
				$titre = $this->page->get_meta_titre();
			}
			$titre_partage = urlencode(trim($titre));
			$url = urlencode("http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]);
			// Création de l'objet partage social
			$obj = new obj_partage_social($this->texte, $url, $titre_partage, $forme_carree, $grande_taille);
			if ($obj) {$obj->afficher($mode, $this->langue_page);}
			return $obj;
		}
		// Ecriture d'un calendrier de réservation
		protected function ecrire_bloc_calendrier($mode, $occ) {
			if (!($this->module_resa)) {return null;}
			// Lecture de l'id calendrier
			$id_cal = $this->page->lire_valeur_n(_PAGE_CALENDRIER_RESA, $occ);
			if (strlen($id_cal) == 0) {return null;}
			$this->module_resa->ouvrir($id_cal, _XML_PATH_MODULES.$id_cal."/"._XML_MODULE_RESA._XML_EXT);
			// Création de l'objet calendrier
			$obj = new obj_calendrier($this->module_resa, $this->texte, $id_cal);
			if ($obj) {$obj->afficher($mode, $this->langue_page);}
			return $obj;
		}
		// Ecriture de la bannière d'actualités
		protected function ecrire_bloc_banniere_actu($mode, $occ) {
			if (!($this->module_actu)) {return null;}
			// Lecture du nombre d'actualités
			$nb_actus = $this->page->lire_valeur_n(_PAGE_BANNIERE_ACTU, $occ);
			// Création de l'objet diaporama
			$style = $this->module_actu->get_style();
			$obj = new obj_actu($this->texte, $style);
			// Ajout des actualités
			for ($cpt = 1;$cpt <= 5;$cpt++) {
				$image = $this->media->get_image_actu($cpt);
				$obj->ajouter_actu($image, $cpt);
			}
			// Ajout du sommaire
			for ($cpt=0; $cpt < $nb_actus; $cpt++) {
				$no_actu = $this->module_actu->get_sommaire($cpt);
				$obj->ajouter_sommaire($no_actu);
			}
			$obj->afficher($mode, $this->langue_page);
			return $obj;
		}

		protected function url_accesskey($lien) {
			$ret = null;
			if (strlen($lien) > 0) {
				$nb_pages = $this->site->get_nb_pages();
				for ($cpt = 0;(($cpt < $nb_pages) && (strlen($ret) == 0));$cpt++) {
					$ref = $this->site->get_page_ref($cpt);
					if (!(strcmp($lien, $ref))) {
						$ret = $this->site->get_page_touche($cpt);
					}
				}
			}
			return $ret;
		}
		protected function url_multilingue($lien) {
			// Passage d'une page multilingue à une page non multilingue
			if (strlen($lien) == 0) {return null;}
			if (!(strcmp($this->langue_page, $this->texte->get_langue_par_defaut()))) {return $lien;}
			if (!($this->page->get_meta_multilingue())) {return $lien;}
			$lien_interne = $this->est_url_interne($lien);
			if (!($lien_interne)) {return $lien;}
			// Ouverture des méta de la page cible
			$cible = basename($lien);$ext = strpos($cible, _PXP_EXT);
			$page_cible = substr($cible, 0, (int) $ext);
			$xml_cible = new xml_page();
			$ret = $xml_cible->ouvrir(_XML_PATH_PAGES.$page_cible."/"._XML_PAGE._XML_EXT);
			if ($ret) {
				$multilingue = $xml_cible->get_meta_multilingue();
				if (!($multilingue)) {
					$lien = $this->dir_page."/".$cible;
					$lien = str_replace("//", "/", $lien);
				}
			}
			return $lien;
		}

		protected function est_url_active($lien) {
			// On récupère le nom de la page en cours
			$page_en_cours = ($this->nom_page)._PXP_EXT;
			$ret = !(strcmp($lien, $page_en_cours));
			return $ret;
		}
		protected function est_url_interne($lien) {
			$ret = true;
			$struct_url = parse_url($lien);
			$host = isset($struct_url["host"])?$struct_url["host"]:null;
			// Si host absent, on est en interne
			if ($host) {
				// Si host identique au domaine, on est en interne
				$ret = !(strcmp($host, $this->nom_domaine));
			}
			return $ret;
		}

		// Méthodes privées
		private function parcourir_plan_du_site(&$obj, $niveau, $parent) {
			$nb_pages = $this->site->get_nb_pages();
			for ($cpt = 0;$cpt < $nb_pages;$cpt++) {
				$parent_page = $this->site->get_page_parent($cpt);
				if (!(strcmp($parent_page, $parent))) {
					$nom = $this->site->get_page_nom($cpt);
					$ref = $this->site->get_page_ref($cpt);
					$touche = $this->site->get_page_touche($cpt);
					$obj->ajouter_entree($niveau, $nom, $ref, $touche);
					$this->parcourir_plan_du_site($obj, $niveau+1, $ref);
				}
			}
		}

		private function parser_image(&$image) {
			// Lecture du style de légende
			$nom_style = $image->get_style_legende();
			$style = (strlen($nom_style) > 0)?$this->media->get_style($nom_style):null;
			// Lecture du lien de l'image
			$lien = $image->get_lien();
			$access_key = $this->url_accesskey($lien);
			$lien_multilingue = $this->url_multilingue($lien);
			// Création de l'objet "image"
			$obj = new obj_image($image, $style, $this->texte, $lien_multilingue, $access_key);
			return $obj;
		}

		private function charger_liste_langues() {
			$nb_langues = $this->site->get_nb_langues();
			for ($cpt_langue = 0;$cpt_langue < $nb_langues;$cpt_langue++) {
				$code_langue = $this->site->get_code_langue($cpt_langue);
				$this->texte->ajouter_langue($code_langue);
			}
		}
		private function parser_id_crochets_actu($id_valeur) {
			$id_texte = $id_valeur;
			if ($this->module_actu) {
				$id_trim = trim($id_valeur);
				$crochets_n = !(strcmp("[n]", strtolower(substr($id_trim, -3))));
				if ($crochets_n) {
					$id_texte = substr($id_trim, 0, strlen($id_trim)-3).($this->no_actu);
				}
				else {
					$crochets_n_plus_1 = !(strcmp("[n+1]", strtolower(substr($id_trim, -5))));
					if ($crochets_n_plus_1) {
						$id_texte = substr($id_trim, 0, strlen($id_trim)-5).($this->module_actu->get_next_actu($this->no_actu));
					}
					else {
						$crochets_n_moins_1 = !(strcmp("[n-1]", strtolower(substr($id_trim, -5))));
						if ($crochets_n_moins_1) {
							$id_texte = substr($id_trim, 0, strlen($id_trim)-5).($this->module_actu->get_prev_actu($this->no_actu));
						}
					}
				}
			}
			return $id_texte;
		}
		private function parser_lien_crochets_actu($id_valeur) {
			$id_texte = $id_valeur;
			if ($this->module_actu) {
				$id_trim = trim($id_valeur);
				if (!(strcmp($id_trim, _HTML_PREFIXE_ACTU."-[n]"._PXP_EXT))) {
					$id_texte =  _HTML_PATH_ACTU."?"._PARAM_ID."=".($this->no_actu);
				}
				elseif (!(strcmp($id_trim, _HTML_PREFIXE_ACTU."-[n+1]"._PXP_EXT))) {
					$id_texte =  _HTML_PATH_ACTU."?"._PARAM_ID."=".($this->module_actu->get_next_actu($this->no_actu));
				}
				elseif (!(strcmp($id_trim, _HTML_PREFIXE_ACTU."-[n-1]"._PXP_EXT))) {
					$id_texte =  _HTML_PATH_ACTU."?"._PARAM_ID."=".($this->module_actu->get_prev_actu($this->no_actu));
				}
			}
			return $id_texte;
		}
	}