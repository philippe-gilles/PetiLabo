<?php
	inclure_inc("const", "param", "moteur");
	inclure_site("moteur_contenu");

	// La classe moteur_site hérite de la classe moteur
	class moteur_site extends moteur {
		// Propriétés privées
		private $cpt_mois_resa = 0;

		// Méthodes publiques
		public function __construct() {
			// Récupération du nom de domaine en cours
			$domaine = htmlentities($_SERVER['SERVER_NAME']);
			$this->nom_domaine = $domaine;
	
			// Récupération du nom de la page en cours
			$page_en_cours = basename(htmlentities($_SERVER['PHP_SELF']));
			$this->nom_page = str_replace(_PXP_EXT, "", $page_en_cours);

			// Cas de la page actu
			if (!(strcmp($page_en_cours, _HTML_PATH_ACTU))) {
				$this->est_actu = true;
				$param = new param();
				$param_actu = $param->get(_PARAM_ID);
				$this->no_actu = (int) $param_actu;
			}
			// Récupération de la mention mobile
			$this->mobile = isset($_GET[_PARAM_MOBILE])?true:false;

			// Chargement des structures XML
			$this->charger_xml();

			// Récupération de la langue en cours
			if (isset($_GET[_PARAM_LANGUE])) {
				$param = $_GET[_PARAM_LANGUE];
				$param_clean = str_replace("\0", '', $param);
				$param_sec = htmlentities($param_clean, ENT_COMPAT | ENT_XHTML, "UTF-8");
				$this->langue_page = $this->texte->verifier_langue($param_sec);
			}
			else {
				$this->langue_page = $this->texte->get_langue_par_defaut();
			}
		}
		public function ouvrir_entete() {
			$url_canonique = $this->site->get_url_racine()."/".($this->nom_page)._PXP_EXT;
			// Ouverture de l'entête
			$this->html->ouvrir($this->langue_page);
			$this->html->ouvrir_head($url_canonique);
		}
		public function ecrire_entete() {
			// Ecriture de l'entête
			$this->html->ecrire_meta_titre($this->page->get_meta_titre());
			$this->html->ecrire_meta_descr($this->page->get_meta_descr());
			// Chargement des polices utilisées dans les styles
			$this->charger_polices_par_defaut();
			// Chargement en local de JQuery
			$this->html->charger_js("js/jquery.js");
			$this->html->charger_js("js/jquery.cookie.js");
			// CSS
			$this->html->charger_css("css/style.css");
			$this->charger_xml_css(false);
			// On charge les JS supplémentaires uniquement si nécessaire
			$has_bx = $this->has_bx();
			if ($has_bx) {$this->html->charger_js("js/bx.min.js");}
			$has_rs = $this->has_rs();
			if ($has_rs) {$this->html->charger_js("js/rs.min.js");}
			$has_lb = $this->has_lb();
			if ($has_lb) {$this->html->charger_js("js/lb.min.js");}
			$has_form = $this->has_form();
			if ($has_form) {
				$this->html->charger_js("js/form_".$this->langue_page.".js");
				$this->html->charger_js("js/form.js");
			}
			
			// Chargement systématique des animations
			$this->html->charger_js("js/anims.js");
			$this->charger_xml_js(false);
		}
		public function fermer_entete() {
			// Fermeture de l'entête
			$this->html->fermer_head();
		}
		public function ouvrir_corps() {
			// Corps
			$this->html->ouvrir_body($this->police_par_defaut);
			
			// Traitement du cas papier peint <= IE8
			$papierpeint = $this->site->get_papierpeint_exterieur();
			if (strlen($papierpeint) > 0) {$this->html->afficher_papierpeint_ie($papierpeint);}
			$this->html->ouvrir_page($this->site->get_largeur(),$this->site->get_largeur_max(),$this->site->get_largeur_min());
		}
		public function ecrire_corps() {
			$nb_contenus = $this->page->get_nb_contenus();
			for ($cpt_cont = 0;$cpt_cont < $nb_contenus;$cpt_cont++) {
				$obj_contenu = $this->page->get_contenu($cpt_cont);
				if ($obj_contenu) {$this->ecrire_contenu($obj_contenu, $cpt_cont, false);}
			}
		}
		public function fermer_corps() {
			$proprietaire = $this->site->get_proprietaire();
			$mentions = $this->texte->get_label_mentions($this->langue_page);
			$credits = $this->texte->get_label_credits($this->langue_page);
			$plan = $this->texte->get_label_plan($this->langue_page);
			$webmaster = $this->texte->get_label_webmaster($this->langue_page);
			$social = $this->texte->get_label_social($this->langue_page);
			$tab_social = $this->site->get_social();
			// Si page d'actu alors on intègre le no de l'actu dans le nom
			$page = $this->nom_page;
			if (!(strcmp($page, _HTML_PREFIXE_ACTU))) {
				$param = new param();
				$param_actu = $param->get(_PARAM_ID);
				if (strlen($param_actu) > 0) {$page .= "-".$param_actu;}
			}
			$this->html->fermer_page(false, $page, $proprietaire, $mentions, $credits, $plan, $webmaster, $social, $tab_social);
			$this->html->fermer_body();
			$this->html->fermer();
		}

		// Méthodes protégées
		protected function ecrire_contenu(&$obj_contenu, $cpt_cont, $admin) {
			$nb_blocs = $obj_contenu->get_nb_blocs();
			$style_contenu = $obj_contenu->get_style();
			$signet_contenu = $obj_contenu->get_signet();
			$semantique_contenu = $obj_contenu->get_semantique();
			$this->html->ouvrir_balise_html5($semantique_contenu);
			$this->html->ouvrir_contenu($cpt_cont, $nb_blocs, $style_contenu);
			if (strlen($signet_contenu) > 0) {$this->html->ecrire_signet($signet_contenu);}
			for ($cpt_bloc = 0;$cpt_bloc < $nb_blocs; $cpt_bloc++) {
				$obj_bloc = $obj_contenu->get_bloc($cpt_bloc);
				if ($obj_bloc) {
					// Vérification de la présence d'un style
					$style = null;
					$nom_style = $obj_bloc->get_style();
					if (strlen($nom_style) > 0) {
						$style = $this->style->get_style_bloc($nom_style);
						if ($style) {$this->preparer_style_bloc($obj_bloc, $style);}
					}
					// Ouverture du bloc et de son style
					$this->html->ouvrir_bloc($obj_bloc, $obj_contenu->get_taille_totale(), $admin);
					if ($style) {$this->html->ouvrir_style_bloc($style);}
					// Ecriture du bloc
					$this->ecrire_bloc($obj_bloc, $cpt_cont, $cpt_bloc);
					// Fermeture du bloc et de son style
					if ($style) {$this->html->fermer_style_bloc($style);}
					$this->html->fermer_bloc($obj_bloc);
				}
			}
			$this->html->fermer_contenu();
			$this->html->fermer_balise_html5($semantique_contenu);
		}
		// Ecriture des titres
		protected function ecrire_titre($niveau, $style_titre, $id_texte) {
			$trad_texte = $this->texte->get_texte($id_texte, $this->langue_page);
			$this->html->ecrire_titre($niveau, $style_titre, $trad_texte);
		}
		// Ecriture des paragraphes
		protected function ecrire_paragraphe($style, $id_texte, $lien_telephonique) {
			$trad_texte = $this->texte->get_texte($id_texte, $this->langue_page);
			if (strlen($lien_telephonique) > 0) {
				$trad_tel = ($this->mobile)?$this->texte->get_label_appeler_tel($this->langue_page):$this->texte->get_label_appeler_skype($this->langue_page);
			}
			else {$trad_tel = null;}
			$this->html->ecrire_paragraphe(false, $style, $trad_texte, $lien_telephonique, $trad_tel);
		}
		// Ecriture des sauts
		protected function ecrire_saut($hauteur) {
			$this->html->ecrire_saut($hauteur);
		}
		// Ecriture des images simples
		protected function ecrire_image(&$image, $id_alt, $has_legende, $niveau_legende, $id_legende, $nom_style, $est_exterieur) {
			if ($image) {
				if (!($image->get_est_vide())) {
					$alt = $this->texte->get_texte($id_alt, $this->langue_page);
					// On récupère le lien éventuel
					$lien = $image->get_lien();
					$access_key = $this->url_accesskey($lien);
					$lien_multilingue = $this->url_multilingue($lien);
					if ($has_legende) {
						$legende = $this->texte->get_texte($id_legende, $this->langue_page);
						$this->html->ecrire_image_avec_legende($image, $alt, $legende, $niveau_legende, $nom_style, $est_exterieur, $lien_multilingue, $access_key);
					}
					else {
						$this->html->ecrire_image_sans_legende($image, $alt, $lien_multilingue, $access_key);
					}
				}
			}
		}
		// Ecriture des diaporamas (ouvrir, ajouter, fermer)
		protected function ouvrir_diaporama($nom_gal, $largeur_max) {
			$this->html->ouvrir_diaporama($nom_gal);
		}
		protected function ajouter_diaporama(&$image, $id_alt, $has_legende, $id_legende, $nom_style, $est_exterieur) {
			if (($image) && (!($image->get_est_vide()))) {
				$alt = $this->texte->get_texte($id_alt, $this->langue_page);
				if ($has_legende) {
					$legende = $this->texte->get_texte($id_legende, $this->langue_page);
					// On récupère le lien éventuel
					$lien = $image->get_lien();
					$access_key = $this->url_accesskey($lien);
					$lien_multilingue = $this->url_multilingue($lien);
					$this->html->ajouter_diaporama_avec_legende($image, $alt, $legende, $nom_style, $est_exterieur, $lien_multilingue, $access_key);
				}
				else {
					$this->html->ajouter_diaporama_sans_legende($image, $alt);
				}
			}
		}
		protected function fermer_diaporama($nom_gal, $has_navigation, $has_boutons, $largeur_max) {
			$this->html->fermer_diaporama($nom_gal, $has_navigation, $has_boutons, $largeur_max);
		}
		// Ecriture des carrousels (ouvrir, ajouter, fermer)
		protected function ouvrir_carrousel($nom_gal) {
			$this->html->ouvrir_carrousel($nom_gal);
		}
		protected function ajouter_carrousel($no_img, &$image, $id_alt, $largeur_max) {
			if (($image) && (!($image->get_est_vide()))) {
				$alt = $this->texte->get_texte($id_alt, $this->langue_page);
				$this->html->ajouter_carrousel(true, $no_img, $image, $alt, $largeur_max);
			}
		}
		protected function fermer_carrousel($nom_gal, $has_navigation, $has_boutons, $largeur_max, $nb_cols) {
			$this->html->fermer_carrousel($nom_gal, $has_navigation, $has_boutons, $largeur_max, $nb_cols);
		}
		// Ecriture des vignettes (ouvrir, ajouter, fermer)
		protected function ouvrir_vignettes($nom_gal) {
			$this->html->ouvrir_vignettes($nom_gal);
		}
		protected function ajouter_vignette($nom_image, $src, $lien, $id_info, $nb_cols) {
			// La source nulle indique une image vide
			if ($src) {
				$info = $this->texte->get_texte($id_info, $this->langue_page);
				$this->html->ajouter_vignette($nom_image, $src, $lien, $info, $nb_cols);
			}
		}
		protected function fermer_vignettes($nom_gal) {
			$label_prec = $this->texte->get_label_precedent($this->langue_page);
			$label_suiv = $this->texte->get_label_suivant($this->langue_page);
			$label_fermer = $this->texte->get_label_fermer($this->langue_page);
			$this->html->fermer_vignettes($nom_gal, $label_prec, $label_suiv, $label_fermer);
		}
		// Ecriture des galeries (ouvrir, ajouter, fermer)
		protected function ouvrir_vue_galerie($nom_gal, &$image_init, $vertical) {
			$this->html->ouvrir_vue_galerie($nom_gal, $image_init, $vertical);
		}
		protected function ajouter_legende_galerie($nom_gal, $id_legende, $nom_style, $index) {
			$legende = $this->texte->get_texte($id_legende, $this->langue_page);
			$this->html->ajouter_legende_galerie($nom_gal, $legende, $nom_style, $index);
		}
		protected function fermer_vue_galerie($nom_gal) {
			$this->html->fermer_vue_galerie($nom_gal);
		}
		protected function ouvrir_onglet_galerie($nom_gal, $vertical) {
			$this->html->ouvrir_onglet_galerie($nom_gal, $vertical);
		}
		protected function ajouter_onglet_galerie($nom_gal, &$image, $id_alt, $index, $nb_cols) {
			if (($image) && (!($image->get_est_vide()))) {
				$alt = $this->texte->get_texte($id_alt, $this->langue_page);
				$this->html->ajouter_onglet_galerie($nom_gal, $image, $alt, $index, $nb_cols);
			}
		}
		protected function fermer_onglet_galerie($nom_gal) {
			$this->html->fermer_onglet_galerie($nom_gal);
		}
		protected function fermer_galerie($nom_gal, $vertical) {
			$this->html->fermer_galerie($nom_gal, $vertical);
		}
		// Ecriture des menus (ouvrir, ajouter, fermer)
		protected function ouvrir_menu($nom_menu, $nb_items_non_vide, $alignement) {
			if ($nb_items_non_vide > 0) {$this->html->ouvrir_menu($alignement);}
		}
		protected function ajouter_menu($style, $id_icone, $id_label, $lien, $id_info, $is_editable, $id_liste) {
			$label = $this->texte->get_texte($id_label, $this->langue_page);
			$icone = $this->texte->get_texte($id_icone, $this->langue_page);
			if ($is_editable) {$lien = $this->texte->get_texte($lien, $this->langue_page);}
			// Lien vide : on ne crée pas l'item */
			if (strlen($lien) > 0) {
				if (strlen($icone) > 0) {
					$code_icone = _MENU_PREFIXE_ICONE.$icone._MENU_SUFFIXE_ICONE;
					if (strlen($label) > 0) {
						// TODO : Reporter l'écriture HTML dans la classe html
						$label = "<span class=\"menu_icone_sur_label\">".$code_icone."</span><br/>".$label;
					}
					else {
						$label = $code_icone;
					}
				}
				// Si c'est une icone on ajoute préfixe et suffixe
				$info = $this->texte->get_texte($id_info, $this->langue_page);
				// On internationalise le lien
				$lien_actif = $this->est_url_active($lien);
				$access_key = $this->url_accesskey($lien);
				$lien_multilingue = $this->url_multilingue($lien);
				$this->html->ajouter_menu($lien_actif, $style, $label, $lien_multilingue, $access_key, $info);
			}
		}
		protected function fermer_menu($nb_items_non_vide) {
			if ($nb_items_non_vide > 0) {$this->html->fermer_menu();}
		}
		// Ecriture des plans
		protected function ecrire_plan($id_texte) {
			$trad_texte = $this->texte->get_texte($id_texte, $this->langue_page);
			$this->html->ecrire_plan($id_texte, $trad_texte, $this->langue_page);
		}
		// Ecriture des videos
		protected function ecrire_video($source, $id_code) {
			$trad_code = $this->texte->get_texte($id_code, $this->langue_page);
			$this->html->ecrire_video($source, $trad_code);
		}
		// Ecriture des pj
		protected function ecrire_pj($id_pj, $lien, $style, $fichier, $id_info, $id_legende) {
			$trad_info = $this->texte->get_texte($id_info, $this->langue_page);
			$trad_legende = $this->texte->get_texte($id_legende, $this->langue_page);
			$this->html->ecrire_pj($id_pj, $lien, $style, $fichier, $trad_info, $trad_legende);
		}
		// Ecriture du formulaire de contact
		protected function ecrire_form_contact($style) {
			$style_paragraphe = $this->site->get_style_paragraphe();
			$nom = $this->texte->get_label_nom($this->langue_page);
			$prenom = $this->texte->get_label_prenom($this->langue_page);
			$tel = $this->texte->get_label_tel($this->langue_page);
			$email = $this->texte->get_label_email($this->langue_page);
			$message = $this->texte->get_label_message($this->langue_page);
			$captcha = $this->texte->get_label_captcha($this->langue_page);
			$envoyer = $this->texte->get_label_envoyer($this->langue_page);
			$this->html->ecrire_form_contact($style_paragraphe, $style, $nom, $prenom, $tel, $email, $message, $captcha, $envoyer);
		}

		// Ecriture des drapeaux (ouvrir, ajouter, fermer)
		protected function ouvrir_drapeaux($alignement) {
			$this->html->ouvrir_drapeaux($alignement);
		}
		protected function ajouter_drapeau($langue, $nom, $pos) {
			$href = $this->nom_page.".php";
			// On ajoute le paramètre si ce n'est pas la langue par défaut
			if (strcmp($langue, $this->texte->get_langue_par_defaut())) {
				if ($this->est_actu) {$href .= "?"._PARAM_ID."=".$this->no_actu."&"._PARAM_LANGUE."=".$langue;}
				else {$href .= "?l=".$langue;}
			}
			else {
				if ($this->est_actu) {$href .= "?"._PARAM_ID."=".$this->no_actu;}
			}
			$this->html->ajouter_drapeau($langue, $href, $nom, $pos);
		}
		protected function fermer_drapeaux() {
			$this->html->fermer_drapeaux();
		}
		// Plan du site
		protected function ecrire_section_plan_du_site($nom) {
			$style = $this->site->get_style_paragraphe();
			$this->html->ecrire_titre_legal($style, $nom);
		}
		protected function ecrire_plan_du_site($niveau, $nom, $ref, $touche) {
			$style = $this->site->get_style_paragraphe();
			$trad_nom = $this->texte->get_texte($nom, $this->langue_page);
			$this->html->ecrire_plan_du_site($niveau, $style, $trad_nom, $ref, $touche);
		}
		protected function ecrire_plan_pied_du_site($nom, $ref, $touche) {
			$style = $this->site->get_style_paragraphe();
			$this->html->ecrire_plan_du_site(0, $style, $nom, $ref, $touche);
		}
		protected function fermer_plan_du_site() {
			$legende_accesskey = $this->texte->get_label_accesskey($this->langue_page);
			$this->html->fermer_plan_du_site($legende_accesskey);
		}
		// Crédits
		protected function ouvrir_credit_section($id_texte) {
			$style = $this->site->get_style_paragraphe();
			$trad_texte = $this->texte->get_texte($id_texte, $this->langue_page);
			$this->html->ecrire_titre_legal($style, $trad_texte);
		}
		protected function ecrire_credit_technique($titre, $lien, $id_credit, $id_visite) {
			$style = $this->site->get_style_paragraphe();
			$trad_visite = $this->texte->get_texte($id_visite, $this->langue_page);
			$this->html->ecrire_credit_technique($titre, $style, $lien, $id_credit, $trad_visite);
		}
		protected function ecrire_credit_photo($src, $copyright, $largeur, $hauteur, $taille = 0) {
			$this->html->ecrire_credit_photo($src, $copyright, $largeur, $hauteur, $taille);
		}
		protected function fermer_credit_section() {
			$this->html->fermer_credit_section();
		}
		// Mentions légales
		protected function ouvrir_legal_section($id_texte) {
			$style = $this->site->get_style_paragraphe();
			$trad_texte = $this->texte->get_texte($id_texte, $this->langue_page);
			$this->html->ecrire_titre_legal($style, $trad_texte);
		}
		protected function ecrire_legal_mentions($id_le_site, $id_est_edite, $id_resp, $id_hebergement) {
			$style = $this->site->get_style_paragraphe();
			// Editeur
			$trad_le_site = $this->texte->get_texte($id_le_site, $this->langue_page);
			$trad_est_edite = $this->texte->get_texte($id_est_edite, $this->langue_page);
			$this->html->ecrire_legal_site_editeur($style, $trad_le_site, $this->site->get_url_racine(), $trad_est_edite);
			$nom_editeur = $this->site->get_proprietaire();
			$adr_editeur = $this->site->get_adresse();
			$tel_editeur = $this->site->get_telephone();
			$rcs_editeur = $this->site->get_rcs();
			$siret_editeur = $this->site->get_siret();
			$this->html->ecrire_legal_coord_editeur($style, $nom_editeur, $adr_editeur, $tel_editeur, $rcs_editeur, $siret_editeur);
			// Responsable de la publication
			$trad_resp = $this->texte->get_texte($id_resp, $this->langue_page);
			$resp = $this->site->get_redacteur();
			$this->html->ecrire_legal_resp_publication($style, $trad_resp, $resp);
			// Hébergeur
			$trad_hebergement = $this->texte->get_texte($id_hebergement, $this->langue_page);
			$hebergeur = $this->site->get_hebergeur();
			$this->html->ecrire_legal_hebergement($style, $trad_hebergement, $hebergeur);
		}
		protected function ecrire_legal_protection($id_le_site, $id_protection, $id_cnil) {
			$style = $this->site->get_style_paragraphe();
			$trad_le_site = $this->texte->get_texte($id_le_site, $this->langue_page);
			$trad_protection = $this->texte->get_texte($id_protection, $this->langue_page);
			$trad_cnil = $this->texte->get_texte($id_cnil, $this->langue_page);
			$this->html->ecrire_legal_protection($style, $trad_le_site, $this->site->get_url_racine(), $trad_protection, $trad_cnil, $this->site->get_cnil());
		}
		protected function ecrire_legal_cookies($id_le_site, $id_cookies) {
			$style = $this->site->get_style_paragraphe();
			$trad_le_site = $this->texte->get_texte($id_le_site, $this->langue_page);
			$trad_cookies = $this->texte->get_texte($id_cookies, $this->langue_page);
			$this->html->ecrire_legal_cookies($style, $trad_le_site, $this->site->get_url_racine(), $trad_cookies);
		}
		protected function ecrire_legal_copyright($id_propriete, $id_reproduction, $id_infraction) {
			$style = $this->site->get_style_paragraphe();
			$trad_propriete = $this->texte->get_texte($id_propriete, $this->langue_page);
			$trad_reproduction = $this->texte->get_texte($id_reproduction, $this->langue_page);
			$trad_infraction = $this->texte->get_texte($id_infraction, $this->langue_page);
			$this->html->ecrire_legal_copyright($style, $this->site->get_proprietaire(), $trad_propriete, $trad_reproduction, $trad_infraction);
		}
		// Module addthis
		protected function ecrire_addthis($forme, $taille) {
			$titre = urlencode(trim($this->page->get_meta_titre()));
			$this->html->ecrire_addthis($titre, $forme, $taille);
		}
		// Module réservation
		protected function ouvrir_calendrier_resa($id_cal, $mois, $an) {
			$tab_mois = $this->texte->get_tab_mois($this->langue_page);
			$this->cpt_mois_resa = 0;
			$this->html->ouvrir_resa($id_cal, $tab_mois, $mois, $an);
		}
		protected function ecrire_calendrier_resa($id_cal, $jour_deb, $mois_deb, $an_deb, $mois, $an) {
			$tab_am = array();$tab_pm = array();
			$tab_jour_sem = $this->texte->get_tab_semaine($this->langue_page);
			// 42 = 6 semaines de 7 jours
			$this->module_resa->get_info_resa($id_cal, $jour_deb, $mois_deb, $an_deb, 42, $tab_am, $tab_pm);
			$this->html->ecrire_mois_resa($this->cpt_mois_resa, $tab_jour_sem, $jour_deb, $mois_deb, $an_deb, $mois, $an, $tab_am, $tab_pm);
			$this->cpt_mois_resa += 1;
		}
		protected function fermer_calendrier_resa($id_cal) {
			$tab_statut_resa = $this->texte->get_tab_statut_resa($this->langue_page);
			$this->html->fermer_resa($tab_statut_resa);
			$this->cpt_mois_resa = 0;
		}
		// Module actualités
		protected function ouvrir_banniere_actu($largeur_max) {
			$this->html->ouvrir_actu($largeur_max);
		}
		protected function ecrire_banniere_actu($no_actu, $style) {
			if ($no_actu > 0) {
				$titre = $this->texte->get_titre_actu($no_actu, $this->langue_page);
				$sous_titre = $this->texte->get_sous_titre_actu($no_actu, $this->langue_page);
				$resume = $this->texte->get_resume_actu($no_actu, $this->langue_page);
				$image = $this->media->get_image_actu($no_actu);
				$alt = null;
				if ($image) {
					$id_alt = $image->get_alt();
					$alt = $this->texte->get_texte($id_alt, $this->langue_page);
				}
				$texte = $this->texte->get_texte_actu($no_actu, $this->langue_page);
				$num_clic = (strlen($texte) > 0)?$no_actu:0;
				$this->html->ecrire_actu($num_clic, $image, $alt, $titre, $sous_titre, $resume, $style);
			}
		}
		protected function fermer_banniere_actu() {
			$this->html->fermer_actu();
		}

		// Méthodes privées
		private function has_bx() {
			$ret = false;
			$nb_contenus = $this->page->get_nb_contenus();
			for ($cpt_cont = 0;(($cpt_cont < $nb_contenus) && (!($ret)));$cpt_cont++) {
				$obj_contenu = $this->page->get_contenu($cpt_cont);
				if ($obj_contenu) {$ret = $obj_contenu->get_has_bx();}
			}
			return $ret;
		}
		private function has_rs() {
			$ret = false;
			$nb_contenus = $this->page->get_nb_contenus();
			for ($cpt_cont = 0;(($cpt_cont < $nb_contenus) && (!($ret)));$cpt_cont++) {
				$obj_contenu = $this->page->get_contenu($cpt_cont);
				if ($obj_contenu) {$ret = $obj_contenu->get_has_rs();}
			}
			return $ret;
		}
		private function has_lb() {
			$ret = false;
			$nb_contenus = $this->page->get_nb_contenus();
			for ($cpt_cont = 0;(($cpt_cont < $nb_contenus) && (!($ret)));$cpt_cont++) {
				$obj_contenu = $this->page->get_contenu($cpt_cont);
				if ($obj_contenu) {$ret = $obj_contenu->get_has_lb();}
			}
			return $ret;
		}
		private function has_gal() {
			$ret = false;
			$nb_contenus = $this->page->get_nb_contenus();
			for ($cpt_cont = 0;(($cpt_cont < $nb_contenus) && (!($ret)));$cpt_cont++) {
				$obj_contenu = $this->page->get_contenu($cpt_cont);
				if ($obj_contenu) {$ret = $obj_contenu->get_has_gal();}
			}
			return $ret;
		}
		private function has_form() {
			$ret = false;
			$nb_contenus = $this->page->get_nb_contenus();
			for ($cpt_cont = 0;(($cpt_cont < $nb_contenus) && (!($ret)));$cpt_cont++) {
				$obj_contenu = $this->page->get_contenu($cpt_cont);
				if ($obj_contenu) {$ret = $obj_contenu->get_has_form();}
			}
			return $ret;
		}
		private function url_accesskey($lien) {
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
		private function url_multilingue($lien) {
			$ret = $lien;
			if (strlen($lien) > 0) {
				// TODO  : Gestion des langues dans la version mobile
				if (($this->est_url_interne($lien)) && ($this->mobile)) {$lien = "mobile/".$lien;}

				// On vérifie s'il s'agit ou non de la langue par défaut
				$langue = "";
				if (strcmp($this->langue_page, $this->texte->get_langue_par_defaut())) {
					$langue = $this->langue_page;
				}
				if (strlen($langue) == 2) {
					$lien_interne = $this->est_url_interne($lien);
					// Ajout du paramètre si le lien est interne et la langue est étrangère				
					if ($lien_interne) {
						// En cas de lien sur un signet de la même page, on ne rajoute pas le paramètre
						if (strncmp($lien, "#", 1)) {
							// Si un paramètre est déjà présent dans l'URL le séparateur est "&"
							$separateur = (strlen(parse_url($lien, PHP_URL_QUERY)) > 0)?"&amp;":"?";
							$ret = $lien.$separateur._PARAM_LANGUE."=".$langue;
						}
					}
				}
			}
			return $ret;
		}
		private function est_url_active($lien) {
			// On récupère le nom de la page en cours
			$page_en_cours = ($this->nom_page)._PXP_EXT;
			$ret = !(strcmp($lien, $page_en_cours));
			return $ret;
		}
		private function est_url_interne($lien) {
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
	}