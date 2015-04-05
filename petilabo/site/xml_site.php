<?php

class xml_site extends xml_abstract {
	private $codes_langues = array();
	private $modules = array();
	private $plan = array();
	private $social = array();
	private $loi_cookie = null;

	public function __construct() {
		// Propriétés issues du fichier site.xml
		$this->enregistrer_chaine("style_titre_1", null);$this->enregistrer_chaine("style_titre_2", null);
		$this->enregistrer_chaine("style_titre_3", null);$this->enregistrer_chaine("style_paragraphe", null);
		$this->enregistrer_chaine("couleur_exterieur", null);$this->enregistrer_chaine("motif_exterieur", null);
		$this->enregistrer_chaine("papierpeint_exterieur", null);$this->enregistrer_chaine("couleur_interieur", null);
		$this->enregistrer_chaine("motif_interieur", null);$this->enregistrer_chaine("largeur", null);
		$this->enregistrer_chaine("largeur_max", null);$this->enregistrer_chaine("largeur_responsive", null);
		$this->enregistrer_chaine("largeur_min", null);
		// Propriétés issues du fichier general.xml
		$this->enregistrer_chaine("url_racine", null);$this->enregistrer_chaine("proprietaire", null);
		$this->enregistrer_chaine("adresse", null);$this->enregistrer_chaine("telephone", null);
		$this->enregistrer_chaine("rcs", null);$this->enregistrer_chaine("siret", null);
		$this->enregistrer_chaine("redacteur", null);$this->enregistrer_chaine("hebergeur", null);
		$this->enregistrer_chaine("cnil", null);$this->enregistrer_chaine("pied_de_page", null);
	}
	// Accesseurs
	public function get_style_titre($niveau) {
		switch ($niveau) {
			case 1 : $ret = $this->get_style_titre_1();break;
			case 2 : $ret = $this->get_style_titre_2();break;
			case 3 : $ret = $this->get_style_titre_3();break;
			default : $ret = null;
		}
		return $ret;
	}
	public function get_social() {return $this->social;}
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
	public function get_loi_cookie() {return (strlen($this->loi_cookie) > 0)?$this->loi_cookie:_SITE_ATTR_LOI_COOKIE_FAIBLE;}
	public function has_loi_cookie() {return (strlen($this->loi_cookie) > 0)?true:false;}

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
			$this->ins_url_racine($xml_site->lire_valeur(_SITE_RACINE));
			$this->ins_proprietaire($xml_site->lire_valeur(_SITE_PROPRIETAIRE));
			$this->ins_adresse($xml_site->lire_valeur(_SITE_ADR_PROPRIETAIRE));
			$this->ins_telephone($xml_site->lire_valeur(_SITE_TEL_PROPRIETAIRE));
			$this->ins_rcs($xml_site->lire_valeur(_SITE_RCS_PROPRIETAIRE));
			$this->ins_siret($xml_site->lire_valeur(_SITE_SIRET_PROPRIETAIRE));
			$this->ins_redacteur($xml_site->lire_valeur(_SITE_REDACTEUR));
			$this->ins_hebergeur($xml_site->lire_valeur(_SITE_HEBERGEUR));
			$this->ins_cnil($xml_site->lire_valeur(_SITE_CNIL));
			$loi_cookie = $xml_site->lire_valeur(_SITE_LOI_COOKIE);
			if (strlen($loi_cookie) > 0) {
				if ((strcmp($loi_cookie, _SITE_ATTR_LOI_COOKIE_MOYEN)) && (strcmp($loi_cookie, _SITE_ATTR_LOI_COOKIE_FORT))) {
					$this->loi_cookie = _SITE_ATTR_LOI_COOKIE_FAIBLE;
				}
				else {
					$this->loi_cookie = $loi_cookie;
				}
			}
			$this->ins_pied_de_page($xml_site->lire_valeur(_SITE_PIED_DE_PAGE));

			// Elements du fichier site.xml
			$this->set_style_titre_1($xml_site->lire_valeur(_SITE_STYLE_TITRE_1));
			$this->set_style_titre_2($xml_site->lire_valeur(_SITE_STYLE_TITRE_2));
			$this->set_style_titre_3($xml_site->lire_valeur(_SITE_STYLE_TITRE_3));
			$this->set_style_paragraphe($xml_site->lire_valeur(_SITE_STYLE_TEXTE));
			$this->set_couleur_exterieur($xml_site->lire_valeur(_SITE_COULEUR_EXTERIEUR));
			$this->set_motif_exterieur($xml_site->lire_valeur(_SITE_MOTIF_EXTERIEUR));
			$this->set_papierpeint_exterieur($xml_site->lire_valeur(_SITE_PAPIERPEINT_EXTERIEUR));
			$this->set_couleur_interieur($xml_site->lire_valeur(_SITE_COULEUR_INTERIEUR));
			$this->set_motif_interieur($xml_site->lire_valeur(_SITE_MOTIF_INTERIEUR));
			$this->set_largeur($xml_site->lire_valeur(_SITE_LARGEUR));
			$this->set_largeur_max($xml_site->lire_valeur(_SITE_LARGEUR_MAX));
			$this->set_largeur_responsive($xml_site->lire_valeur(_SITE_LARGEUR_RESPONSIVE));
			$this->set_largeur_min($xml_site->lire_valeur(_SITE_LARGEUR_MIN));
		}
		return $ret;
	}

	public function extraire_css() {
		$ret = ".exterieur {";
		if (strlen($this->get_papierpeint_exterieur()) > 0) {
			$ret .= "background: url('"._XML_PATH_IMAGES_SITE.$this->get_papierpeint_exterieur()."') no-repeat center center fixed;";
			$ret .= "-webkit-background-size: cover;";
			$ret .= "-moz-background-size: cover;";
			$ret .= "-o-background-size: cover;";
			$ret .= "background-size: cover;";
		}
		elseif (strlen($this->get_motif_exterieur()) > 0) {
			$ret .= "background:url('"._XML_PATH_IMAGES_SITE.$this->get_motif_exterieur()."') repeat;";
		}
		elseif (strlen($this->get_couleur_exterieur())) {
			$ret .= "background:".$this->get_couleur_exterieur().";";
		}
		$ret .= "}"._CSS_FIN_LIGNE;
		
		$ret .= ".interieur {";
		if (strlen($this->get_motif_interieur()) > 0) {
			$ret .= "background:url('"._XML_PATH_IMAGES_SITE.$this->get_motif_interieur()."') repeat;";
		}
		elseif (strlen($this->get_couleur_interieur())) {
			$ret .= "background:".$this->get_couleur_interieur().";";
		}
		$ret .= "}"._CSS_FIN_LIGNE;
		// Partie responsive
		if (strlen($this->get_largeur_responsive()) > 0) {
			$ret .= "@media screen and (max-width:".$this->get_largeur_responsive().") {";
			$ret .= ".bloc{display:block!important;width:96%!important;padding:0 2%!important;}}"._CSS_FIN_LIGNE;
		}
		return $ret;
	}
	public function extraire_css_ie() {
		$ret = "";
		$papierpeint = $this->get_papierpeint_exterieur();
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