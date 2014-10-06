<?php
	inclure_inc("const", "param", "moteur");
	inclure_site("moteur_contenu");
	require_once "moteur_edit.php";

	// La classe moteur_adm hérite de la classe moteur
	class moteur_adm extends moteur {
		// Propriétés privées
		private $calendrier_ouvert = false;
	
		// Méthodes publiques
		public function __construct($nom_page) {
			// Récupération du nom de la page y compris le cas actu
			$match_actu = preg_match("/^"._HTML_PREFIXE_ACTU."-[1-5]$/", $nom_page);
			$this->est_actu = ($match_actu == 1)?true:false;
			if ($this->est_actu) {
				$this->no_actu = (int) substr($nom_page, 1+strlen(_HTML_PREFIXE_ACTU));
				$this->nom_page = _HTML_PREFIXE_ACTU;
			}
			else {
				$this->nom_page = $nom_page;
			}
			// Chargement des structures XML
			$this->charger_xml();
			// La langue d'administration est la langue par défaut
			$this->langue_page = $this->texte->get_langue_par_defaut();
		}
		public function ouvrir_entete() {
			$this->html->ouvrir($this->langue_page);
			$this->html->ouvrir_head();
		}
		public function ecrire_entete() {
			echo "<meta http-equiv=\"Pragma\" content=\"no-cache\">";
			echo "<meta http-equiv=\"Expires\" content=\"-1\">\n";
			// Ecriture de l'entête
			$this->html->ecrire_meta_titre("Administration");
			// Chargement des polices utilisées dans les styles
			$this->charger_polices_par_defaut();
			// CSS d'origine
			$this->html->charger_css("css/style.css");
			// CSS surchargé pour l'administration
			$this->html->charger_css(_HTTP_LOG_ADMIN."/css/style.css");
			$this->html->charger_css(_HTTP_LOG_ADMIN."/css/jqueryte.css");
			$this->charger_xml_css(true);
			// Chargement en local de JQuery
			$this->html->charger_js("js/jquery.js");
			$this->html->charger_js(_HTTP_LOG_ADMIN."/js/lb.ajax.js");
			$this->html->charger_js(_HTTP_LOG_ADMIN."/js/upload.js");
			$this->html->charger_js(_HTTP_LOG_ADMIN."/js/jqueryte.js");
			$this->html->charger_js(_HTTP_LOG_ADMIN."/js/anims.js");
			$this->charger_xml_js(true);
		}
		public function fermer_entete() {
			$this->html->fermer_head();
		}
		public function ouvrir_corps() {
			$this->html->ouvrir_body($this->police_par_defaut);
			$papierpeint = $this->site->get_papierpeint_exterieur();
			if (strlen($papierpeint) > 0) {$this->html->afficher_papierpeint_ie($papierpeint);}
			$this->html->ouvrir_page($this->site->get_largeur(),$this->site->get_largeur_max(),$this->site->get_largeur_min());
		}

		public function ecrire_corps() {
			$nb_contenus = $this->page->get_nb_contenus();
			for ($cpt_cont = 0;$cpt_cont < $nb_contenus;$cpt_cont++) {
				$obj_contenu = $this->page->get_contenu($cpt_cont);
				if ($obj_contenu) {$this->ecrire_contenu($obj_contenu, $cpt_cont, true);}
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
			$this->html->fermer_page(true, "deconnect.php", $proprietaire, $mentions, $credits, $plan, $webmaster, $social, $tab_social);
			// TODO : Déplacer le code HTML dans la classe html
			echo "<script type=\"text/javascript\">$('.symbole').magnificPopup({type: 'ajax',tClose:'Fermer',tLoading:'Chargement...'});</script>";
			$this->html->fermer_body();
			$this->html->fermer();
		}
		// Méthodes protégées
		protected function ecrire_contenu(&$obj_contenu, $cpt_cont, $admin) {
			$class_edit = "";
			$nb_blocs = $obj_contenu->get_nb_blocs();
			$style_contenu = $obj_contenu->get_style();
			$this->html->ouvrir_contenu($cpt_cont, $nb_blocs, $style_contenu);
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
			for ($cpt_bloc = 0;$cpt_bloc < $nb_blocs; $cpt_bloc++) {
				$obj_bloc = $obj_contenu->get_bloc($cpt_bloc);
				if ($obj_bloc) {
					if ($obj_bloc->get_nb_elems_admin() > 0) {
						if (strlen($class_edit) > 0) {$class_edit .= " ";}
						$class_edit .= $obj_bloc->get_repere();
						$moteur_edit = new moteur_edit($this->nom_page, $cpt_cont, $cpt_bloc, $this->est_actu, $this->no_actu);
						$moteur_edit->ecrire_corps();
					}
				}
			}
			$this->html->fermer_contenu();
			// Ajout de la div vide pour édition
			if (strlen($class_edit) > 0) {
				$this->html->ouvrir_div("edit_".$cpt_cont, $class_edit);
				$this->html->fermer_div();
			}
		}
		// Ecriture des titres
		protected function ecrire_titre($niveau, $style_titre, $id_texte) {
			$trad_texte = $this->texte->get_texte($id_texte, $this->langue_page);
			$this->html->ecrire_titre($niveau, $style_titre, $trad_texte);
		}
		// Ecriture des paragraphes
		protected function ecrire_paragraphe($style, $id_texte, $lien_telephonique) {
			$trad_texte = $this->texte->get_texte($id_texte, $this->langue_page);
			$this->html->ecrire_paragraphe(true, $style, $trad_texte, null, null);
		}
		// Ecriture des sauts
		protected function ecrire_saut($hauteur) {
			$this->html->ecrire_saut($hauteur);
		}
		// Ecriture des images simples
		protected function ecrire_image(&$image, $id_alt, $has_legende, $niveau_legende, $id_legende, $nom_style, $est_exterieur) {
			if ($image) {
				$alt = $this->texte->get_texte($id_alt, $this->langue_page);
				// Pour éviter la mise en cache des images on ajoute un paramètre à src
				$src = $image->get_src();
				$width = $image->get_width();
				$height = $image->get_height();
				if ($image->get_est_vide()) {
					$image->set_src("./images/"._ADMIN_IMAGE_VIDE);
					$image->set_width(_ADMIN_IMAGE_VIDE_LARGEUR_MIN);
					$image->set_height(_ADMIN_IMAGE_VIDE_HAUTEUR_MIN);
					$this->html->ecrire_image_sans_legende($image, $alt, null);
					$image->set_width($width);
					$image->set_height($height);
				}
				else {
					$image->set_src($src."?id=".uniqid());
					if ($has_legende) {
						$legende = $this->texte->get_texte($id_legende, $this->langue_page);
						$this->html->ecrire_image_avec_legende($image, $alt, $legende, $niveau_legende, $nom_style, $est_exterieur, null);
					}
					else {
						$this->html->ecrire_image_sans_legende($image, $alt, null);
					}
				}
				$image->set_src($src);
			}
		}
		// Ecriture des diaporamas (ouvrir, ajouter, fermer)
		protected function ouvrir_diaporama($nom_gal, $largeur_max) {
			$this->html->ouvrir_diaporama($nom_gal, $largeur_max);
		}
		protected function ajouter_diaporama(&$image, $id_alt, $has_legende, $id_legende, $nom_style, $est_exterieur) {
			if (($image) && (!($image->get_est_vide()))) {
				$alt = $this->texte->get_texte($id_alt, $this->langue_page);
				// Pour éviter la mise en cache des images on ajoute un paramètre à src
				$src = $image->get_src();
				$image->set_src($src."?id=".uniqid());
				if ($has_legende) {
					$legende = $this->texte->get_texte($id_legende, $this->langue_page);
					$this->html->ajouter_diaporama_avec_legende($image, $alt, $legende, $nom_style, $est_exterieur, null);
				}
				else {
					$this->html->ajouter_diaporama_sans_legende($image, $alt);
				}
				$image->set_src($src);
			}
		}
		protected function fermer_diaporama($nom_gal, $has_navigation, $has_boutons, $maxwidth) {
			$this->html->fermer_diaporama(null, false, false, 0);
		}
		// Ecriture des carrousels (ouvrir, ajouter, fermer)
		protected function ouvrir_carrousel($nom_gal) {
			$this->html->ouvrir_carrousel(null);
		}
		protected function ajouter_carrousel($no_img, &$image, $id_alt, $largeur_max) {
			if (($image) && (!($image->get_est_vide()))) {
				$alt = $this->texte->get_texte($id_alt, $this->langue_page);
				$this->html->ajouter_carrousel(false, $no_img, $image, $alt, $largeur_max);
			}
		}
		protected function fermer_carrousel($nom_gal, $has_navigation, $has_boutons, $largeur_max, $nb_cols) {
			$this->html->fermer_carrousel(null, false, false, 0, 0);
		}
		// Ecriture des vignettes (ouvrir, ajouter, fermer)
		protected function ouvrir_vignettes($nom_gal) {
			$this->html->ouvrir_vignettes($nom_gal);
		}
		protected function ajouter_vignette($nom_image, $src, $lien, $id_info, $nb_cols) {
			// La source nulle indique une image vide
			if ($src) {
				$info = $this->texte->get_texte($id_info, $this->langue_page);
				$this->html->ajouter_vignette($nom_image, $src."?id=".uniqid(), null, $info, $nb_cols);
			}
		}
		protected function fermer_vignettes($nom_gal) {
			$this->html->fermer_vignettes(null, null, null, null);
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
			$this->html->ouvrir_menu($alignement);
		}
		protected function ajouter_menu($style, $id_icone, $id_label, $lien, $id_info, $is_editable, $id_liste) {
			$label = $this->texte->get_texte($id_label, $this->langue_page);
			$icone = $this->texte->get_texte($id_icone, $this->langue_page);
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
			$info = $this->texte->get_texte($id_info, $this->langue_page);
			$this->html->ajouter_menu(false, $style, $label, null, null, $info);
		}
		protected function fermer_menu($nb_items_non_vide) {
			$this->html->fermer_menu();
		}
		// Ecriture des plans
		protected function ecrire_plan($id_texte) {
			$trad_texte = $this->texte->get_texte($id_texte, $this->langue_page);
			$this->html->ecrire_plan($id_texte, $trad_texte, $this->langue_page, false);
		}
		// Ecriture des videos
		protected function ecrire_video($source, $id_code) {
			$trad_code = $this->texte->get_texte($id_code, $this->langue_page);
			$this->html->ecrire_video($source, $trad_code, false);
		}
		// Ecriture des pj
		protected function ecrire_pj($id_pj, $lien, $style, $fichier, $id_info, $id_legende) {
			$trad_info = $this->texte->get_texte($id_info, $this->langue_page);
			$trad_legende = $this->texte->get_texte($id_legende, $this->langue_page);
			$this->html->ecrire_pj(null, $lien, $style, $fichier, $trad_info, $trad_legende);
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
			$this->html->ecrire_form_contact($style_paragraphe, $style, $nom, $prenom, $tel, $email, $message, $captcha, $envoyer, false);
		}
		// Ecriture des drapeaux (ouvrir, ajouter, fermer)
		protected function ouvrir_drapeaux($alignement) {
			$this->html->ouvrir_drapeaux($alignement);
		}
		protected function ajouter_drapeau($langue, $nom, $pos) {
			$this->html->ajouter_drapeau(null, null, $nom, $pos);
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
			$this->html->ecrire_plan_du_site($niveau, $style, $trad_nom, null, $touche);
		}
		protected function ecrire_plan_pied_du_site($nom, $ref, $touche) {
			$style = $this->site->get_style_paragraphe();
			$this->html->ecrire_plan_du_site(0, $style, $nom, null, $touche);
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
			$this->html->ecrire_credit_technique($titre, $style, null, $id_credit, $trad_visite);
		}
		protected function ecrire_credit_photo($src, $copyright, $largeur, $hauteur, $taille=0) {
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
			$this->html->ecrire_legal_site_editeur($style, "Le site", "nom du site", "est édité par");
			$this->html->ecrire_legal_coord_editeur($style, "Nom de l'éditeur", "Adresse", "Téléphone", "N° RCS", "SIRET");
			$this->html->ecrire_legal_resp_publication($style, "Responsable de la publication", "Nom du responsable");
			$this->html->ecrire_legal_hebergement($style, "Hébergement", "Nom et adresse de l'hébergeur");
		}
		protected function ecrire_legal_protection($id_le_site, $id_protection, $id_cnil) {
			$style = $this->site->get_style_paragraphe();
			$trad_protection = $this->texte->get_texte($id_protection, $this->langue_page);
			$trad_cnil = $this->texte->get_texte($id_cnil, $this->langue_page);
			$this->html->ecrire_legal_protection($style, "Le site", "nom du site", $trad_protection, $trad_cnil, "N° CNIL");
		}
		protected function ecrire_legal_cookies($id_le_site, $id_cookies) {
			$style = $this->site->get_style_paragraphe();
			$trad_cookies = $this->texte->get_texte($id_cookies, $this->langue_page);
			$this->html->ecrire_legal_cookies($style, "Le site", "nom du site", $trad_cookies);
		}
		protected function ecrire_legal_copyright($id_propriete, $id_reproduction, $id_infraction) {
			$style = $this->site->get_style_paragraphe();
			$trad_propriete = $this->texte->get_texte($id_propriete, $this->langue_page);
			$trad_reproduction = $this->texte->get_texte($id_reproduction, $this->langue_page);
			$trad_infraction = $this->texte->get_texte($id_infraction, $this->langue_page);
			$this->html->ecrire_legal_copyright($style, "nom du propriétaire", $trad_propriete, $trad_reproduction, $trad_infraction);
		}
		// Module addthis
		protected function ecrire_addthis($forme, $taille) {
			$this->html->ecrire_addthis(null, $forme, $taille);
		}
		// Module réservation
		protected function ouvrir_calendrier_resa($id_cal, $mois, $an) {
			$tab_mois = $this->texte->get_tab_mois($this->langue_page);
			$this->html->ouvrir_resa($id_cal, $tab_mois, $mois, $an, false);
			$this->calendrier_ouvert = true;
		}
		protected function ecrire_calendrier_resa($id_cal, $jour_deb, $mois_deb, $an_deb, $mois, $an) {
			if ($this->calendrier_ouvert) {
				$tab_am = array();$tab_pm = array();
				$tab_jour_sem = $this->texte->get_tab_semaine($this->langue_page);
				// 42 = 6 semaines de 7 jours
				$this->module_resa->get_info_resa($id_cal, $jour_deb, $mois_deb, $an_deb, 42, $tab_am, $tab_pm);
				$this->html->ecrire_mois_resa(0, $tab_jour_sem, $jour_deb, $mois_deb, $an_deb, $mois, $an, $tab_am, $tab_pm);
				$this->calendrier_ouvert = false;
			}
		}
		protected function fermer_calendrier_resa($id_cal) {
			$tab_statut_resa = $this->texte->get_tab_statut_resa($this->langue_page);
			$this->html->fermer_resa($tab_statut_resa);
			$this->calendrier_ouvert = false;
		}
		// Ecriture du module actualité (ouvrir, ecrire, fermer)
		protected function ouvrir_banniere_actu($largeur_max) {
			$this->html->ouvrir_actu($largeur_max);
		}
		protected function ecrire_banniere_actu($no_actu, $style) {
			if ($no_actu > 0) {
				$titre = $this->texte->get_titre_actu($no_actu, $this->langue_page);
				$sous_titre = $this->texte->get_sous_titre_actu($no_actu, $this->langue_page);
				$resume = $this->texte->get_resume_actu($no_actu, $this->langue_page);
				$image = $this->media->get_image_actu($no_actu);
				if ($image) {
					$src = $image->get_src();
					$image->set_src($src."?id=".uniqid());
				}
				$texte = $this->texte->get_texte_actu($no_actu, $this->langue_page);
				$num_clic = (strlen($texte) > 0)?$no_actu:0;
				$this->html->ecrire_actu($num_clic, $image, null, $titre, $sous_titre, $resume, $style);
			}
		}
		protected function fermer_banniere_actu() {
			$this->html->fermer_actu(false);
		}
	}