<?php

	// La classe moteur_admin hérite de la classe moteur
	class moteur_admin extends moteur {
		private $fragment = null;
		private $config_analitix = null;

		// Méthodes publiques
		public function __construct($nom_page, $fragment = null) {
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
			$this->fragment = $fragment;
			// Chargement des structures XML
			$this->charger_xml();
			// Chargement de la langue
			$this->charger_langue();
			// Mesure d'audience
			$pa = $this->page->get_meta_pa();
			if (strlen($pa) > 0) {
				$this->config_analitix = new xml_analitix();
				if ($this->config_analitix) {$this->config_analitix->ouvrir($pa, false);}
			}
		}
		public function ouvrir_entete() {
			$this->html->ouvrir($this->langue_page);
			$this->html->ouvrir_head();
		}
		public function ecrire_entete() {
			echo "<meta http-equiv=\"Pragma\" content=\"no-cache\">";
			echo "<meta http-equiv=\"Expires\" content=\"-1\">\n";
			$this->html->ecrire_meta_noindex();
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
			$this->html->charger_js(_HTTP_LOG_ADMIN."/js/simple-chart.js");
			// Passage du paramètre largeur_responsive à Javascript
			$largeur_responsive = $this->site->get_largeur_responsive();
			$responsive_js = (int) ((strlen($largeur_responsive) > 0)?trim(str_replace("px", "", $largeur_responsive)):"1");
			$this->html->ecrire_js("var largeur_responsive=".$responsive_js.";");
			// Chargement des animations
			$this->html->charger_js(_HTTP_LOG_ADMIN."/js/anims.js");
			$this->html->charger_js_ie(_HTTP_LOG_ADMIN."/js/ie.js");
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
			$obj_admin = new obj_admin($this->nom_page, $this->page->get_meta_multilingue(), $this->site->get_nb_langues(), $this->page->get_meta_noindex());
			if ($obj_admin) {
				if ($this->config_analitix) {$obj_admin->ajouter_statistiques($this->config_analitix->get_filtre_pays(), $this->config_analitix->get_filtre_referents());}
				$obj_admin->afficher(_PETILABO_MODE_EDIT, $this->langue_page);
			}
			$titre_editable = $this->page->get_meta_titre_editable();
			$descr_editable = $this->page->get_meta_descr_editable();
			$obj_meta = new obj_meta($this->texte, $titre_editable, $descr_editable);
			if ($obj_meta) {$obj_meta->afficher(_PETILABO_MODE_EDIT, $this->langue_page);}
			$nb_contenus = $this->page->get_nb_contenus();
			for ($cpt_cont = 0;$cpt_cont < $nb_contenus;$cpt_cont++) {
				$obj_contenu = $this->page->get_contenu($cpt_cont);
				if ($obj_contenu) {
					$style = $obj_contenu->get_style();
					$obj_style = $this->style->get_style_contenu($style);
					$type_contenu = ($obj_style)?$obj_style->get_type_special():null;
					$this->ecrire_contenu($obj_contenu, $cpt_cont, $type_contenu);
				}
			}
		}
		public function fermer_corps() {
			$proprietaire = $this->site->get_proprietaire();
			$webmaster = $this->texte->get_label_webmaster($this->langue_page);
			$social = $this->texte->get_label_social($this->langue_page);
			$tab_social = $this->site->get_social();
			$this->html->fermer_page(true, "deconnect.php", $proprietaire, $webmaster, $social, $tab_social);
			$this->html->ecrire_js("$('.symbole').magnificPopup({type: 'ajax',tClose:'Fermer',tLoading:'Chargement...'});");
			$this->html->fermer_body();
			$this->html->fermer();
		}
		// Méthodes protégées
		protected function ecrire_contenu(&$obj_contenu, $cpt_cont, $type_contenu) {
			$tab_bloc = array();$no_bloc = array();
			$nb_blocs = $obj_contenu->get_nb_blocs();
			$style_contenu = $obj_contenu->get_style();
			$this->html->ouvrir_contenu($cpt_cont, $nb_blocs, $style_contenu, $type_contenu);
			// Partie admin
			for ($cpt_bloc = 0;$cpt_bloc < $nb_blocs; $cpt_bloc++) {
				$obj_bloc = $obj_contenu->get_bloc($cpt_bloc);
				if (!($obj_bloc)) {continue;}
				// Vérification de la présence d'un style
				$style = null;
				$nom_style = $obj_bloc->get_style();
				if (strlen($nom_style) > 0) {$style = $this->style->get_style_bloc($nom_style);}
				// Ouverture du bloc et de son style
				$this->html->ouvrir_bloc($obj_bloc, $obj_contenu->get_taille_totale(), true);
				if ($style) {$this->html->ouvrir_style_bloc($style);}
				// Ecriture du bloc
				$tab_obj = $this->ecrire_bloc(_PETILABO_MODE_ADMIN, $obj_bloc, $cpt_cont, $cpt_bloc);
				if (($tab_obj) && ($obj_bloc->get_nb_elems_admin() > 0)) {
					$tab_bloc[] = $tab_obj;
					$no_bloc[] = $cpt_bloc;
				}
				// Fermeture du bloc et de son style
				if ($style) {$this->html->fermer_style_bloc($style);}
				$this->html->fermer_bloc($obj_bloc);
			}
			$this->html->fermer_contenu();
			// Partie edit
			$nb_tab_blocs = count($tab_bloc);
			for ($cpt_bloc = 0;$cpt_bloc < $nb_tab_blocs; $cpt_bloc++) {
				$tab_obj = $tab_bloc[$cpt_bloc];
				$no_tab_obj = $no_bloc[$cpt_bloc];
				$id_tab = $this->html->ouvrir_tab($cpt_cont, $no_tab_obj, $this->fragment);
				foreach ($tab_obj as $obj) {
					$obj->set_id_tab($id_tab);
					$obj->afficher(_PETILABO_MODE_EDIT, $this->langue_page);
				}
				$this->html->fermer_tab();
			}
		}
	}