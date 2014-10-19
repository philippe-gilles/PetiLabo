<?php
	inclure_inc("const", "param", "moteur");
	inclure_site("moteur_contenu");
	require_once "inc/html_edit.php";

	// Constantes pour les couleurs de catégorie
	define("_EDIT_COULEUR", "#444");

	// Constantes pour les noms de catégorie
	define("_EDIT_LABEL_TITRE", "Titre");
	define("_EDIT_LABEL_SOUS_TITRE", "Sous-titre");
	define("_EDIT_LABEL_TEXTE", "Paragraphe");
	define("_EDIT_LABEL_DIAPORAMA", "Diaporama");
	define("_EDIT_LABEL_CARROUSEL", "Carrousel");
	define("_EDIT_LABEL_VIGNETTES", "Vignettes");
	define("_EDIT_LABEL_GALERIE", "Galerie");
	define("_EDIT_LABEL_IMAGE", "Image");
	define("_EDIT_LABEL_MENU", "Menu");
	define("_EDIT_LABEL_ITEM", "Choix");
	define("_EDIT_LABEL_PLAN", "Plan");
	define("_EDIT_LABEL_VIDEO", "Video");
	define("_EDIT_LABEL_PJ", "Pièce jointe");
	define("_EDIT_LABEL_CALENDRIER", "Calendrier");
	define("_EDIT_LABEL_BANNIERE_ACTUALITE", "Bannière d'actualités");
	define("_EDIT_LABEL_ACTUALITE", "Actualité");
	define("_EDIT_LABEL_SOMMAIRE", "Sommaire");
	
	// Constantes pour les symboles
	define("_EDIT_SYMBOLE_LABEL", "15c");
	define("_EDIT_SYMBOLE_IMAGE", "03e");
	define("_EDIT_SYMBOLE_ALT", "02b");
	define("_EDIT_SYMBOLE_COPY", "0a3");
	define("_EDIT_SYMBOLE_INFO", "075");
	define("_EDIT_SYMBOLE_ICONE", "005");
	define("_EDIT_SYMBOLE_LEGENDE", "02d");
	define("_EDIT_SYMBOLE_LIEN", "0c1");
	define("_EDIT_SYMBOLE_PLAN", "041");
	define("_EDIT_SYMBOLE_VIDEO", "03d");
	define("_EDIT_SYMBOLE_SOMMAIRE", "03a");
	define("_EDIT_SYMBOLE_CALENDRIER", "073");

	// La classe moteur_adm hérite de la classe moteur
	class moteur_edit extends moteur {
		private $no_contenu = -1;
		private $no_bloc = -1;
		private $html_edit = null;
		private $compteur_menu = 0;
		private $compteur_galerie = 0;
		private $tmp = array();

		// Méthodes publiques
		public function __construct($nom_page, $no_contenu, $no_bloc, $est_actu = false, $no_actu = 0) {
			// Récupération du nom de la page y compris le cas actu
			$this->nom_page = $nom_page;
			$this->no_contenu = $no_contenu;
			$this->no_bloc = $no_bloc;
			$this->est_actu = $est_actu;
			$this->no_actu = $no_actu;
			
			// Chargement des structures XML
			$this->charger_xml();
			
			// Outils HTML propres à l'édition
			$this->html_edit = new html_edit($this->nom_page, $no_contenu, $no_bloc);

			// La langue d'administration est la langue par défaut
			$this->langue_page = $this->texte->get_langue_par_defaut();
		}
		public function ecrire_corps() {
			$obj_contenu = $this->page->get_contenu($this->no_contenu);
			if ($obj_contenu) {
				$obj_bloc = $obj_contenu->get_bloc($this->no_bloc);
				if ($obj_bloc) {
					// TODO : A intégrer dans une classe html
					echo "<div id=\"tab_".$this->no_contenu."_".$this->no_bloc."\" class=\"tab_edit\">";
					$this->ecrire_bloc($obj_bloc, $this->no_contenu, $this->no_bloc);
					echo "</div>";
				}
			}
		}
		// Ecriture des titres
		protected function ecrire_titre($niveau, $style_titre, $id_texte) {
			$trad_texte = $this->check_texte($id_texte);
			$this->html_edit->ouvrir_tableau_simple();
			$this->html_edit->ouvrir_ligne();
			$titre = ($niveau > 1)?_EDIT_LABEL_SOUS_TITRE:_EDIT_LABEL_TITRE;
			$this->html_edit->ecrire_cellule_categorie($titre, _EDIT_COULEUR, 1);
			$this->html_edit->ecrire_cellule_symbole_texte($id_texte, _EDIT_SYMBOLE_LABEL, "Modifier le texte du titre");
			$this->html_edit->ecrire_cellule_texte($id_texte, $this->relook_texte($trad_texte));
			$this->html_edit->fermer_ligne();
			$this->html_edit->fermer_tableau();
		}
		// Ecriture des paragraphes
		protected function ecrire_paragraphe($style, $id_texte, $lien_telephonique) {
			$trad_texte = $this->check_texte($id_texte);
			$this->html_edit->ouvrir_tableau_simple();
			$this->html_edit->ouvrir_ligne();
			$this->html_edit->ecrire_cellule_categorie(_EDIT_LABEL_TEXTE, _EDIT_COULEUR, 1);
			$this->html_edit->ecrire_cellule_symbole_texte($id_texte, _EDIT_SYMBOLE_LABEL, "Modifier le texte du paragraphe");
			$this->html_edit->ecrire_cellule_texte($id_texte, $this->relook_texte($trad_texte));
			$this->html_edit->fermer_ligne();
			$this->html_edit->fermer_tableau();
		}
		// Ecriture des images simples
		protected function ecrire_image(&$image, $id_alt, $has_legende, $niveau_legende, $id_legende, $nom_style, $est_exterieur) {
			$src = $image->get_src();
			if (strlen($src) > 0) {
				$titre = ($this->compteur_galerie > 0)?_EDIT_LABEL_IMAGE."&nbsp;n°".$this->compteur_galerie:_EDIT_LABEL_IMAGE;
				$this->html_edit->ouvrir_tableau_simple();
				if ($image->get_est_vide()) {
					$this->html_edit->ouvrir_ligne();
					$this->html_edit->ecrire_cellule_categorie($titre, _EDIT_COULEUR, 1);
					$this->html_edit->ecrire_cellule_symbole_image($image->get_nom(), _EDIT_SYMBOLE_IMAGE);
					$this->html_edit->ecrire_cellule_image();
					$this->html_edit->fermer_ligne();
				}
				else {
					$nb_lignes = ($has_legende)?4:3;
					$this->html_edit->ouvrir_ligne();
					$this->html_edit->ecrire_cellule_categorie($titre, _EDIT_COULEUR, $nb_lignes);
					$this->html_edit->ecrire_cellule_symbole_image($image->get_nom(), _EDIT_SYMBOLE_IMAGE);
					$this->html_edit->ecrire_cellule_image($src);
					$this->html_edit->fermer_ligne();
					$trad_alt = $this->check_texte($id_alt);
					$this->html_edit->ouvrir_ligne();
					$this->html_edit->ecrire_cellule_symbole_texte_brut($id_alt, _EDIT_SYMBOLE_ALT, "Modifier le texte alternatif de l'image");
					$this->html_edit->ecrire_cellule_texte($id_alt, $this->relook_texte($trad_alt));
					$this->html_edit->fermer_ligne();
					$id_copyright = $image->get_copyright();
					$trad_copyright = $this->check_texte($id_copyright);
					$this->html_edit->ouvrir_ligne();
					$this->html_edit->ecrire_cellule_symbole_texte_simple(_EDIT_TYPE_COPY, $id_copyright, _EDIT_SYMBOLE_COPY, "Modifier le copyright de l'image");
					$this->html_edit->ecrire_cellule_texte($id_copyright, $this->relook_texte($trad_copyright));
					$this->html_edit->fermer_ligne();
					if ($has_legende) {
						$trad_legende = $this->check_texte($id_legende);
						$this->html_edit->ouvrir_ligne();
						$this->html_edit->ecrire_cellule_symbole_texte($id_legende, _EDIT_SYMBOLE_LEGENDE, "Modifier la légende de l'image");
						$this->html_edit->ecrire_cellule_texte($id_legende, $this->relook_texte($trad_legende));
						$this->html_edit->fermer_ligne();
					}
				}
				$this->html_edit->fermer_tableau();
			}
		}
		// Ecriture des diaporamas (ouvrir, ajouter, fermer)
		protected function ouvrir_diaporama($nom_gal, $largeur_max) {
			$this->compteur_galerie = 0;
			$titre = $this->construire_etiquette(_EDIT_LABEL_DIAPORAMA, $nom_gal);
			$this->html_edit->ouvrir_tableau_multiple($titre, _EDIT_COULEUR, $nom_gal);
		}
		protected function ajouter_diaporama(&$image, $id_alt, $has_legende, $id_legende, $nom_style, $est_exterieur) {
			$this->compteur_galerie += 1;
			$this->ecrire_image($image, $id_alt, $has_legende, 0, $id_legende, $nom_style, $est_exterieur);
		}
		protected function fermer_diaporama($nom_gal, $has_navigation, $has_boutons, $maxwidth) {
			$this->compteur_galerie = 0;
			$this->html_edit->fermer_tableau();
		}
		// Ecriture des carrousels (ouvrir, ajouter, fermer)
		protected function ouvrir_carrousel($nom_gal) {
			$this->compteur_galerie = 0;
			$titre = $this->construire_etiquette(_EDIT_LABEL_CARROUSEL, $nom_gal);
			$this->html_edit->ouvrir_tableau_multiple($titre, _EDIT_COULEUR, $nom_gal);
		}
		protected function ajouter_carrousel($no_img, &$image, $id_alt, $largeur_max) {
			$this->compteur_galerie += 1;
			$this->ecrire_image($image, $id_alt, false, 0, null, null, false);
		}
		protected function fermer_carrousel($nom_gal, $has_navigation, $has_boutons, $largeur_max, $nb_cols) {
			$this->compteur_galerie = 0;
			$this->html_edit->fermer_tableau();
		}
		// Ecriture des vignettes (ouvrir, ajouter, fermer)
		protected function ouvrir_vignettes($nom_gal) {
			$this->compteur_galerie = 0;
			$titre = $this->construire_etiquette(_EDIT_LABEL_VIGNETTES, $nom_gal);
			$this->html_edit->ouvrir_tableau_multiple($titre, _EDIT_COULEUR, $nom_gal);
		}
		protected function ajouter_vignette($nom_image, $src, $lien, $id_info, $nb_cols) {
			$this->compteur_galerie += 1;
			$titre = ($this->compteur_galerie > 0)?_EDIT_LABEL_IMAGE."&nbsp;n°".$this->compteur_galerie:_EDIT_LABEL_IMAGE;
			$this->html_edit->ouvrir_tableau_simple();
			if (strlen($src) > 0) {
				$this->html_edit->ouvrir_ligne();
				$this->html_edit->ecrire_cellule_categorie($titre, _EDIT_COULEUR, 2);
				$this->html_edit->ecrire_cellule_symbole_image($nom_image, _EDIT_SYMBOLE_IMAGE);
				$this->html_edit->ecrire_cellule_image($src);
				$this->html_edit->fermer_ligne();
				$trad_info = $this->check_texte($id_info);
				$this->html_edit->ouvrir_ligne();
				$this->html_edit->ecrire_cellule_symbole_texte_brut($id_info, _EDIT_SYMBOLE_ALT, "Modifier le texte alternatif de l'image");
				$this->html_edit->ecrire_cellule_texte($id_info, $this->relook_texte($trad_info));
				$this->html_edit->fermer_ligne();
			}
			else {
				$this->html_edit->ouvrir_ligne();
				$this->html_edit->ecrire_cellule_categorie($titre, _EDIT_COULEUR, 1);
				$this->html_edit->ecrire_cellule_symbole_image($nom_image, _EDIT_SYMBOLE_IMAGE);
				$this->html_edit->ecrire_cellule_image();
				$this->html_edit->fermer_ligne();
			}
			$this->html_edit->fermer_tableau();
		}
		protected function fermer_vignettes($nom_gal) {
			$this->compteur_galerie = 0;
			$this->html_edit->fermer_tableau();
		}
		// Ecriture des galeries (ouvrir, ajouter, fermer)
		protected function ouvrir_galerie($nom_gal, $vertical) {
			$this->compteur_galerie = 0;
			$titre = $this->construire_etiquette(_EDIT_LABEL_GALERIE, $nom_gal);
			$this->html_edit->ouvrir_tableau_multiple($titre, _EDIT_COULEUR, $nom_gal);
		}
		protected function ajouter_vue_galerie($nom_gal, &$image, $id_legende, $nom_style, $index) {
			$this->tmp[$index]["id_legende"] = $id_legende;
			$this->tmp[$index]["nom_style"] = $nom_style;
		}
		protected function ajouter_onglet_galerie($nom_gal, &$image, $id_alt, $index, $nb_cols) {
			$this->compteur_galerie += 1;
			if (array_key_exists($index, $this->tmp)) {
				$has_legende = true;
				$id_legende = $this->tmp[$index]["id_legende"];
				$nom_style = $this->tmp[$index]["nom_style"];
			}
			else {
				$has_legende = false;
				$id_legende = null;
				$nom_style = null;
			}
			$this->ecrire_image($image, $id_alt, $has_legende, 0, $id_legende, $nom_style, false);
		}
		protected function fermer_galerie($nom_gal, $vertical, $has_legende) {
			$this->tmp = array();
			$this->compteur_galerie = 0;
			$this->html_edit->fermer_tableau();
		}
		// Ecriture des menus (ouvrir, ajouter, fermer)
		protected function ouvrir_menu($nom_menu, $nb_items_non_vide, $alignement) {
			$this->compteur_menu = 0;
			$titre = $this->construire_etiquette(_EDIT_LABEL_MENU, $nom_menu);
			$this->html_edit->ouvrir_tableau_multiple($titre, _EDIT_COULEUR, $nom_menu);
		}
		protected function ajouter_menu($style, $id_icone, $id_label, $lien, $id_info, $is_editable, $id_liste) {
			$this->compteur_menu += 1;
			$titre = _EDIT_LABEL_ITEM."&nbsp;n°".$this->compteur_menu;
			$nb_lignes = (strlen($id_icone)>0)?2:0;
			$nb_lignes += (strlen($id_label)>0)?2:0;
			$nb_lignes += ($is_editable)?1:0;
			$this->html_edit->ouvrir_tableau_simple();
			$this->html_edit->ouvrir_ligne();
			$this->html_edit->ecrire_cellule_categorie($titre, _EDIT_COULEUR, $nb_lignes);
			if (strlen($id_icone)>0) {
				$trad_icone = $this->check_texte($id_icone);
				$icone = _MENU_PREFIXE_ICONE.$trad_icone._MENU_SUFFIXE_ICONE;
				$this->html_edit->ecrire_cellule_symbole_texte_simple(_EDIT_TYPE_ICONE, $id_icone, _EDIT_SYMBOLE_ICONE, "Modifier le code de l'icône");
				$this->html_edit->ecrire_cellule_icone($icone);
				$this->html_edit->fermer_ligne();
				$trad_info = $this->check_texte($id_info);
				$this->html_edit->ouvrir_ligne();
				$this->html_edit->ecrire_cellule_symbole_texte_brut($id_info, _EDIT_SYMBOLE_INFO, "Modifier le texte de l'infobulle");
				$this->html_edit->ecrire_cellule_texte($id_info, $this->relook_texte($trad_info));
				$this->html_edit->fermer_ligne();
			}
			if (strlen($id_label)>0) {
				$trad_label = $this->check_texte($id_label);
				$this->html_edit->ouvrir_ligne();
				$this->html_edit->ecrire_cellule_symbole_texte($id_label, _EDIT_SYMBOLE_LABEL, "Modifier le texte de l'item de menu");
				$this->html_edit->ecrire_cellule_texte($id_label, $this->relook_texte($trad_label));
				$this->html_edit->fermer_ligne();
			}
			if ($is_editable) {
				$trad_label = $this->check_texte($lien);
				$this->html_edit->ouvrir_ligne();
				$this->html_edit->ecrire_cellule_symbole_lien_editable($lien, _EDIT_SYMBOLE_LIEN, "Modifier le lien de l'item de menu", $id_liste);
				$this->html_edit->ecrire_cellule_texte($lien, $this->relook_texte($trad_label));
				$this->html_edit->fermer_ligne();
			}
			$this->html_edit->fermer_tableau();
		}
		protected function fermer_menu($nb_items_non_vide) {
			$this->compteur_menu = 0;
			$this->html_edit->fermer_tableau();
		}
		// Ecriture des plans
		protected function ecrire_plan($id_texte) {
			$trad_texte = $this->check_texte($id_texte);
			$this->html_edit->ouvrir_tableau_simple();
			$this->html_edit->ouvrir_ligne();
			$this->html_edit->ecrire_cellule_categorie(_EDIT_LABEL_PLAN, _EDIT_COULEUR, 1);
			$this->html_edit->ecrire_cellule_symbole_texte_simple(_EDIT_TYPE_PLAN, $id_texte, _EDIT_SYMBOLE_PLAN, "Modifier l'adresse du plan");
			$code = $this->relook_texte($trad_texte);
			// Patch permettant de gérer les retours à la ligne
			// $code = str_replace("&", "&#8203;&", $code);
			$this->html_edit->ecrire_cellule_texte($id_texte, $code);
			$this->html_edit->fermer_ligne();
			$this->html_edit->fermer_tableau();
		}
		// Ecriture des plans
		protected function ecrire_video($source, $id_code) {
			$trad_code = $this->check_texte($id_code);
			$this->html_edit->ouvrir_tableau_simple();
			$this->html_edit->ouvrir_ligne();
			$cat = _EDIT_LABEL_VIDEO."<br/>".ucwords($source);
			$this->html_edit->ecrire_cellule_categorie($cat, _EDIT_COULEUR, 1);
			$this->html_edit->ecrire_cellule_symbole_texte_simple(_EDIT_TYPE_VIDEO, $id_code, _EDIT_SYMBOLE_VIDEO, "Modifier l'identifiant de la vidéo");
			$code = $this->relook_texte($trad_code);
			// Patch permettant de gérer les retours à la ligne
			// $code = str_replace("&", "&#8203;&", $code);
			// $this->html_edit->ecrire_cellule_texte($id_code, $code);
			$this->html_edit->ecrire_cellule_video($id_code, $code, $source);
			$this->html_edit->fermer_ligne();
			$this->html_edit->fermer_tableau();
		}
		// Ecriture des pj
		protected function ecrire_pj($id_pj, $lien, $style, $fichier, $id_info, $id_legende) {
			$trad_info = $this->check_texte($id_info);
			$trad_legende = $this->check_texte($id_legende);
			$this->html_edit->ouvrir_tableau_simple();
			$this->html_edit->ouvrir_ligne();
			$this->html_edit->ecrire_cellule_categorie(_EDIT_LABEL_PJ, _EDIT_COULEUR, 3);
			$this->html_edit->ecrire_cellule_symbole_pj($id_pj, _EDIT_SYMBOLE_LIEN);
			$code = strtolower(basename($fichier));
			$this->html_edit->ecrire_cellule_texte($id_pj, $code);
			$this->html_edit->fermer_ligne();
			$this->html_edit->ouvrir_ligne();
			$this->html_edit->ecrire_cellule_symbole_texte_brut($id_info, _EDIT_SYMBOLE_INFO,"Modifier le texte de l'infobulle");
			$code = $this->relook_texte($trad_info);
			$this->html_edit->ecrire_cellule_texte($id_info, $code);
			$this->html_edit->fermer_ligne();
			$this->html_edit->ouvrir_ligne();
			$this->html_edit->ecrire_cellule_symbole_texte($id_legende, _EDIT_SYMBOLE_LEGENDE, "Modifier la description du fichier");
			$code = $this->relook_texte($trad_legende);
			$this->html_edit->ecrire_cellule_texte($id_legende, $code);
			$this->html_edit->fermer_ligne();
			$this->html_edit->fermer_tableau();
		}
		// Ecriture des calendriers
		protected function ouvrir_calendrier_resa($id_cal, $mois, $an) {
			$this->html_edit->ouvrir_tableau_simple();
			$this->html_edit->ouvrir_ligne();
			$this->html_edit->ecrire_cellule_categorie(_EDIT_LABEL_CALENDRIER, _EDIT_COULEUR, 1);
			$this->html_edit->ecrire_cellule_symbole_calendrier($id_cal, _EDIT_SYMBOLE_CALENDRIER);
			$this->html_edit->ecrire_cellule_texte($id_cal, $id_cal);
			$this->html_edit->fermer_ligne();
			$this->html_edit->fermer_tableau();
		}
		// Ecriture des actus
		protected function ouvrir_banniere_actu($largeur_max) {
			$this->html_edit->ouvrir_tableau_multiple(_EDIT_LABEL_BANNIERE_ACTUALITE, _EDIT_COULEUR, null);
		}
		protected function ecrire_banniere_actu($no_actu, $style) {
			$this->tmp[] = $no_actu;
		}
		protected function fermer_banniere_actu() {
			$nb_sommaire = count($this->tmp);
			$this->html_edit->ouvrir_tableau_simple();
			// Sommaire
			for ($cpt = 0;$cpt < $nb_sommaire;$cpt++) {
				$this->html_edit->ouvrir_ligne();
				if ($cpt == 0) {$this->html_edit->ecrire_cellule_categorie(_EDIT_LABEL_SOMMAIRE, _EDIT_COULEUR, $nb_sommaire);}
				$this->html_edit->ecrire_cellule_symbole_sommaire($cpt+1, _EDIT_SYMBOLE_SOMMAIRE);
				$label_actu = ($this->tmp[$cpt] > 0)?_EDIT_LABEL_ACTUALITE."&nbsp;n°".$this->tmp[$cpt]:"Pas d'actualité";
				$this->html_edit->ecrire_cellule_texte(strval($cpt), $label_actu);
				$this->html_edit->fermer_ligne();
			}
			// Actualités classées par ordre de numéro
			for ($no_actu = 1;$no_actu <= 5;$no_actu++) {
				$this->edit_actu($no_actu);
			}
			$this->tmp = array();
			$this->html_edit->fermer_tableau();
		}
		private function construire_etiquette($titre, $nom, $separateur="&nbsp;") {
			$etiquette = $titre.$separateur."<span style=\"font-size:0.8em;font-style:italic;\">(".$nom.")</span>";
			return $etiquette;
		}
		private function relook_texte($texte) {
			$ret = $texte;
			// $ret = strtr($texte, "<>", "[]");
			// $ret = htmlentities($ret, ENT_COMPAT | ENT_XHTML, "UTF-8");
			return $ret;
		}
		private function check_texte(&$id) {
			if ($this->texte->existe_texte($id)) {$trad = $this->texte->get_texte($id, $this->langue_page);}
			else {$id = null;$trad = null;}
			return $trad;
		}
	}