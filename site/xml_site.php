<?php

class xml_site {
	// Propriétés issues du fichier site.xml
	private $style_paragraphe = null;
	private $style_titre_1 = null;private $style_titre_2 = null;private $style_titre_3 = null;
	private $couleur_exterieur = null;private $motif_exterieur = null;private $papierpeint_exterieur = null;
	private $couleur_interieur = null;private $motif_interieur = null;
	private $largeur = null;private $largeur_max = null;private $largeur_responsive = null;private $largeur_min = null;

	// Propriétés issues du fichier general.xml
	private $url_racine = null;
	private $proprietaire = null;private $adresse = null;private $telephone = null;
	private $rcs = null;private $siret = null;
	private $redacteur = null;private $hebergeur = null;
	private $cnil = null;
	private $codes_langues = array();
	private $modules = array();
	private $plan = array();
	private $social = array();
	private $pied_de_page = null;

	// Accesseurs
	public function get_style_titre_1() {return $this->style_titre_1;}
	public function get_style_titre_2() {return $this->style_titre_2;}
	public function get_style_titre_3() {return $this->style_titre_3;}
	public function get_style_titre($niveau) {
		switch ($niveau) {
			case 1 : $ret = $this->style_titre_1;break;
			case 2 : $ret = $this->style_titre_2;break;
			case 3 : $ret = $this->style_titre_3;break;
			default : $ret = null;
		}
		return $ret;
	}
	public function get_style_paragraphe() {return $this->style_paragraphe;}
	public function get_couleur_exterieur() {return $this->couleur_exterieur;}
	public function get_motif_exterieur() {return $this->motif_exterieur;}
	public function get_papierpeint_exterieur() {return $this->papierpeint_exterieur;}
	public function get_couleur_interieur() {return $this->couleur_interieur;}
	public function get_motif_interieur() {return $this->motif_interieur;}
	public function get_largeur() {return $this->largeur;}
	public function get_largeur_max() {return $this->largeur_max;}
	public function get_largeur_responsive() {return $this->largeur_responsive;}
	public function get_largeur_min() {return $this->largeur_min;}
	public function get_url_racine() {return $this->url_racine;}
	public function get_proprietaire() {return $this->proprietaire;}
	public function get_adresse() {return $this->adresse;}
	public function get_telephone() {return $this->telephone;}
	public function get_rcs() {return $this->rcs;}
	public function get_siret() {return $this->siret;}
	public function get_redacteur() {return $this->redacteur;}
	public function get_hebergeur() {return $this->hebergeur;}
	public function get_cnil() {return $this->cnil;}
	public function get_social() {return $this->social;}
	public function get_pied_de_page() {return $this->pied_de_page;}
	public function get_nb_langues() {return count($this->codes_langues);}
	public function get_code_langue($index) {return $this->codes_langues[$index];}
	public function has_social() {return ((count($this->social) > 0)?true:false);}
	public function has_module($nom) {
		$ret = in_array($nom, $this->modules);
		return $ret;
	}
	public function get_nb_pages() {return count($this->plan);}
	public function get_page_ref($index) {
		$ret = $this->plan[$index];
		if ($ret) {$ret = $ret[_SITE_PLAN_PAGE_ATTR_REF];}
		return $ret;
	}
	public function get_page_nom($index) {
		$ret = $this->plan[$index];
		if ($ret) {$ret = $ret[_SITE_PLAN_PAGE_NOM];}
		return $ret;
	}
	public function get_page_touche($index) {
		$ret = $this->plan[$index];
		if ($ret) {$ret = $ret[_SITE_PLAN_PAGE_TOUCHE];}
		return $ret;
	}
	public function get_page_parent($index) {
		$ret = $this->plan[$index];
		if ($ret) {$ret = $ret[_SITE_PLAN_PAGE_PARENT];}
		return $ret;
	}

	// Méthodes publiques
	public function ouvrir($nom) {
		$xml_site = new xml_struct();
		$ret = $xml_site->ouvrir($nom);
		if ($ret) {
			// Lecture des modules
			$nb_modules = $xml_site->compter_elements(_SITE_MODULE);
			for ($cpt_module = 0;$cpt_module < $nb_modules;$cpt_module++) {
				$module = $xml_site->lire_valeur_n(_SITE_MODULE, $cpt_module);
				$this->modules[] = $module;
			}
			// Lecture des langues
			$nb_langues = $xml_site->compter_elements(_SITE_LANGUE);
			for ($cpt_langue = 0;$cpt_langue < $nb_langues;$cpt_langue++) {
				$code_langue = $xml_site->lire_valeur_n(_SITE_LANGUE, $cpt_langue);
				$this->codes_langues[] = $code_langue;
			}
			// Lecture du plan du site
			$has_plan = ($xml_site->compter_elements(_SITE_PLAN) > 0);
			if ($has_plan) {
				$xml_site->pointer_sur_balise(_SITE_PLAN);
				$nb_pages = $xml_site->compter_elements(_SITE_PLAN_PAGE);
				$xml_site->pointer_sur_balise(_SITE_PLAN_PAGE);
				$cpt_ref = 0;
				for ($cpt = 0;$cpt < $nb_pages; $cpt++) {
					$ref = $xml_site->lire_n_attribut(_SITE_PLAN_PAGE_ATTR_REF, $cpt);
					if (strlen($ref) > 0) {
						$nom = $xml_site->lire_n_valeur(_SITE_PLAN_PAGE_NOM, $cpt);
						$touche = $xml_site->lire_n_valeur(_SITE_PLAN_PAGE_TOUCHE, $cpt);
						$parent = $xml_site->lire_n_valeur(_SITE_PLAN_PAGE_PARENT, $cpt);
						$this->plan[$cpt_ref][_SITE_PLAN_PAGE_ATTR_REF] = $ref;
						$this->plan[$cpt_ref][_SITE_PLAN_PAGE_NOM] = $nom;
						$this->plan[$cpt_ref][_SITE_PLAN_PAGE_TOUCHE] = $touche;
						$this->plan[$cpt_ref][_SITE_PLAN_PAGE_PARENT] = $parent;
						$cpt_ref += 1;
					}
				}
				$xml_site->pointer_sur_origine();
			}
			// Liens sociaux
			$lien = $xml_site->lire_valeur(_SITE_SOCIAL_FACEBOOK);
			if (strlen($lien) > 0) {$this->social[_SITE_SOCIAL_FACEBOOK]=$lien;}
			$lien = $xml_site->lire_valeur(_SITE_SOCIAL_TWITTER);
			if (strlen($lien) > 0) {$this->social[_SITE_SOCIAL_TWITTER]=$lien;}
			$lien = $xml_site->lire_valeur(_SITE_SOCIAL_GOOGLE_PLUS);
			if (strlen($lien) > 0) {$this->social[_SITE_SOCIAL_GOOGLE_PLUS]=$lien;}
			$lien = $xml_site->lire_valeur(_SITE_SOCIAL_PINTEREST);
			if (strlen($lien) > 0) {$this->social[_SITE_SOCIAL_PINTEREST]=$lien;}
			$lien = $xml_site->lire_valeur(_SITE_SOCIAL_TUMBLR);
			if (strlen($lien) > 0) {$this->social[_SITE_SOCIAL_TUMBLR]=$lien;}
			$lien = $xml_site->lire_valeur(_SITE_SOCIAL_INSTAGRAM);
			if (strlen($lien) > 0) {$this->social[_SITE_SOCIAL_INSTAGRAM]=$lien;}
			$lien = $xml_site->lire_valeur(_SITE_SOCIAL_LINKEDIN);
			if (strlen($lien) > 0) {$this->social[_SITE_SOCIAL_LINKEDIN]=$lien;}
			$lien = $xml_site->lire_valeur(_SITE_SOCIAL_YOUTUBE);
			if (strlen($lien) > 0) {$this->social[_SITE_SOCIAL_YOUTUBE]=$lien;}
			$lien = $xml_site->lire_valeur(_SITE_SOCIAL_FLICKR);
			if (strlen($lien) > 0) {$this->social[_SITE_SOCIAL_FLICKR]=$lien;}

			// Autres éléments du fichier general.xml
			$racine = $xml_site->lire_valeur(_SITE_RACINE);
			$this->url_racine = (strlen($racine) > 0)?$racine:$this->url_racine;
			$proprietaire = $xml_site->lire_valeur(_SITE_PROPRIETAIRE);
			$this->proprietaire = (strlen($proprietaire) > 0)?$proprietaire:$this->proprietaire;
			$adresse = $xml_site->lire_valeur(_SITE_ADR_PROPRIETAIRE);
			$this->adresse = (strlen($adresse) > 0)?$adresse:$this->adresse;
			$telephone = $xml_site->lire_valeur(_SITE_TEL_PROPRIETAIRE);
			$this->telephone = (strlen($telephone) > 0)?$telephone:$this->telephone;
			$rcs = $xml_site->lire_valeur(_SITE_RCS_PROPRIETAIRE);
			$this->rcs = (strlen($rcs) > 0)?$rcs:$this->rcs;
			$siret = $xml_site->lire_valeur(_SITE_SIRET_PROPRIETAIRE);
			$this->siret = (strlen($siret) > 0)?$siret:$this->siret;
			$redacteur = $xml_site->lire_valeur(_SITE_REDACTEUR);
			$this->redacteur = (strlen($redacteur) > 0)?$redacteur:$this->redacteur;
			$hebergeur = $xml_site->lire_valeur(_SITE_HEBERGEUR);
			$this->hebergeur = (strlen($hebergeur) > 0)?$hebergeur:$this->hebergeur;
			$cnil = $xml_site->lire_valeur(_SITE_CNIL);
			$this->cnil = (strlen($cnil) > 0)?$cnil:$this->cnil;
			$pdp = $xml_site->lire_valeur(_SITE_PIED_DE_PAGE);
			$this->pied_de_page = (strlen($pdp) > 0)?$pdp:$this->pied_de_page;

			// Elements du fichier site.xml
			$this->style_titre_1 = $xml_site->lire_valeur(_SITE_STYLE_TITRE_1);
			$this->style_titre_2 = $xml_site->lire_valeur(_SITE_STYLE_TITRE_2);
			$this->style_titre_3 = $xml_site->lire_valeur(_SITE_STYLE_TITRE_3);
			$this->style_paragraphe = $xml_site->lire_valeur(_SITE_STYLE_TEXTE);
			$this->couleur_exterieur = $xml_site->lire_valeur(_SITE_COULEUR_EXTERIEUR);
			$this->motif_exterieur = $xml_site->lire_valeur(_SITE_MOTIF_EXTERIEUR);
			$this->papierpeint_exterieur = $xml_site->lire_valeur(_SITE_PAPIERPEINT_EXTERIEUR);
			$this->couleur_interieur = $xml_site->lire_valeur(_SITE_COULEUR_INTERIEUR);
			$this->motif_interieur = $xml_site->lire_valeur(_SITE_MOTIF_INTERIEUR);
			$this->largeur = $xml_site->lire_valeur(_SITE_LARGEUR);
			$this->largeur_max = $xml_site->lire_valeur(_SITE_LARGEUR_MAX);
			$this->largeur_responsive = $xml_site->lire_valeur(_SITE_LARGEUR_RESPONSIVE);
			$this->largeur_min = $xml_site->lire_valeur(_SITE_LARGEUR_MIN);
		}
		return $ret;
	}

	public function extraire_css() {
		$ret = ".exterieur {";
		if (strlen($this->papierpeint_exterieur) > 0) {
			$ret .= "background: url('"._XML_PATH_IMAGES_SITE.$this->papierpeint_exterieur."') no-repeat center center fixed;";
			$ret .= "-webkit-background-size: cover;";
			$ret .= "-moz-background-size: cover;";
			$ret .= "-o-background-size: cover;";
			$ret .= "background-size: cover;";
		}
		elseif (strlen($this->motif_exterieur) > 0) {
			$ret .= "background:url('"._XML_PATH_IMAGES_SITE.$this->motif_exterieur."') repeat;";
		}
		elseif (strlen($this->couleur_exterieur)) {
			$ret .= "background:".$this->couleur_exterieur.";";
		}
		$ret .= "}"._CSS_FIN_LIGNE;
		
		$ret .= ".interieur {";
		if (strlen($this->motif_interieur) > 0) {
			$ret .= "background:url('"._XML_PATH_IMAGES_SITE.$this->motif_interieur."') repeat;";
		}
		elseif (strlen($this->couleur_interieur)) {
			$ret .= "background:".$this->couleur_interieur.";";
		}
		$ret .= "}"._CSS_FIN_LIGNE;
		// Partie responsive
		if (strlen($this->largeur_responsive) > 0) {
			$ret .= "@media screen and (max-width:".$this->largeur_responsive.") {";
			$ret .= ".bloc{display:block!important;width:96%!important;padding:0 2%!important;}}"._CSS_FIN_LIGNE;
		}
		return $ret;
	}

	public function extraire_css_ie() {
		$ret = "";
		$papierpeint = $this->papierpeint_exterieur;
		if (strlen($papierpeint) > 0) {
			list($largeur_pp, $hauteur_pp) = @getimagesize(_XML_PATH_IMAGES_SITE.$papierpeint);
			if (($largeur_pp > 0) && ($hauteur_pp > 0)) {
				$ret .= "img.papierpeint {z-index:-999;";
				$ret .= "min-height:100%;min-width:".$largeur_pp."px;";
				$ret .= "width: 100%;height: auto;";
				$ret .= "position:fixed;top:0;left:0;}"._CSS_FIN_LIGNE;
				$demi_largeur_pp = (int) (((int) $largeur_pp) /2);
				$ret .= "@media screen and (max-width: ".$largeur_pp."px) {";
				$ret .= "img.papierpeint {";
				$ret .= "left:50%;margin-left:-".$demi_largeur_pp."px;} }"._CSS_FIN_LIGNE;
			}
		}
		return $ret;
	}
}