<?php
inclure_inc("const");
inclure_site("xml_const", "xml_struct");

define("_MODULE_ACTU_IMAGE", "image_actu_");
define("_IMAGE_EXTENSION_JPEG", "jpeg");
define("_IMAGE_EXTENSION_JPG", "jpg");
define("_IMAGE_EXTENSION_PNG", "png");
define("_IMAGE_EXTENSION_GIF", "gif");

class style_media {
	// Propriétés
	private $marge_ext_haut = 0;private $marge_ext_bas = 0;private $marge_ext_gauche = 0;private $marge_ext_droite = 0;
	private $marge_int_haut = 0;private $marge_int_bas = 0;private $marge_int_gauche = 0;private $marge_int_droite = 0;
	private $couleur_fond = null;private $couleur_texte = null;private $survol = false;
	private $style_texte = null;private $lien_souligne = false;private $niveau_titre = 0;

	// Manipulateurs
	public function set_marge_haut($param) {
		if ($param >= 0) {
			$this->marge_int_haut = (int) $param;
			$this->marge_ext_haut = 0;
		}
		else {
			$this->marge_int_haut = 0;
			$this->marge_ext_haut = - ((float) $param);
		}
	}
	public function set_marge_bas($param) {
		if ($param >= 0) {
			$this->marge_int_bas = (int) $param;
			$this->marge_ext_bas = 0;
		}
		else {
			$this->marge_int_bas = 0;
			$this->marge_ext_bas = - ((float) $param);
		}
	}
	public function set_marge_gauche($param) {
		if ($param >= 0) {
			$this->marge_int_gauche = (int) $param;
			$this->marge_ext_gauche = 0;
		}
		else {
			$this->marge_int_gauche = 0;
			$this->marge_ext_gauche = - ((float) $param);
		}
	}
	public function set_marge_droite($param) {
		if ($param >= 0) {
			$this->marge_int_droite = (int) $param;
			$this->marge_ext_droite = 0;
		}
		else {
			$this->marge_int_droite = 0;
			$this->marge_ext_droite = - ((float) $param);
		}
	}
	public function set_couleur_fond($param) {$this->couleur_fond = $param;}
	public function set_couleur_texte($param) {$this->couleur_texte = $param;}
	public function set_style_texte($param) {$this->style_texte = $param;}

	public function set_lien_souligne($param) {
		$str = trim(strtolower($param));
		$souligne = (!(strcmp($str, _XML_TRUE)))?true:false;
		$this->lien_souligne = $souligne;
	}
	public function set_niveau_titre($param) {
		$niveau = (int) $param;
		$niveau = ($niveau < 1)?0:(($niveau > 3)?3:$niveau);
		$this->niveau_titre = $niveau;
	}
	public function set_survol($param) {
		$str = trim(strtolower($param));
		$survol = (!(strcmp($str, _XML_TRUE)))?true:false;
		$this->survol = $survol;
	}

	// Accesseurs
	public function get_marge_int_haut() {return $this->marge_int_haut;}
	public function get_marge_int_bas() {return $this->marge_int_bas;}
	public function get_marge_int_gauche() {return $this->marge_int_gauche;}
	public function get_marge_int_droite() {return $this->marge_int_droite;}
	public function get_marge_ext_haut() {return $this->marge_ext_haut;}
	public function get_marge_ext_bas() {return $this->marge_ext_bas;}
	public function get_marge_ext_gauche() {return $this->marge_ext_gauche;}
	public function get_marge_ext_droite() {return $this->marge_ext_droite;}
	public function get_width() {
		$ret = 100 -  ((int) $this->marge_int_gauche)  - ((int) $this->marge_int_droite);
		return $ret;
	}
	public function get_height() {
		$ret = 100 - ((int) $this->marge_int_haut)  - ((int) $this->marge_int_bas);
		return $ret;
	}
	public function get_est_exterieur() {
		$marges_ext = (float) $this->marge_ext_haut;
		$marges_ext += (float) $this->marge_ext_bas;
		$marges_ext += (float) $this->marge_ext_gauche;
		$marges_ext += (float) $this->marge_ext_droite;
		$ret = ($marges_ext > 0)?true:false;
		return $ret;
	}
	public function get_couleur_fond() {return $this->couleur_fond;}
	public function get_couleur_texte() {return $this->couleur_texte;}
	public function get_style_texte() {return $this->style_texte;}
	public function get_lien_souligne() {return $this->lien_souligne;}
	public function get_niveau_titre() {return $this->niveau_titre;}
	public function get_survol() {return $this->survol;}
}

class img_media {
	// Propriétés
	private $nom = null;private $source = null;
	private $src = null;private $src_reduite = null;
	private $alt = null;private $legende = null;private $copyright = null;
	private $lien = null;
	private $width_standard = 0;private $height_standard = 0;
	private $width = 0;private $height = 0;
	private $style_legende = null;

	public function __construct($source, $nom) {
		$this->source = $source;
		$this->nom = $nom;
	}

	// Manipulateurs
	public function set_src($param) {$this->src = $param;}
	public function set_src_reduite($param) {$this->src_reduite = $param;}
	public function set_alt($param) {$this->alt = $param;}
	public function set_legende($param) {$this->legende = $param;}
	public function set_copyright($param) {$this->copyright = $param;}
	public function set_width_standard($param) {$this->width_standard = $param;}
	public function set_height_standard($param) {$this->height_standard = $param;}
	public function set_width($param) {$this->width = $param;}
	public function set_height($param) {$this->height = $param;}
	public function set_style_legende($param) {$this->style_legende = $param;}
	public function set_lien($param) {$this->lien = $param;}
	public function set_vide() {
		$extension = $this->get_extension();
		$image_vide = _IMAGE_VIDE_1X1.".".$extension;
		@copy(_PHP_PATH_ROOT."images/".$image_vide, $this->src);
		@copy(_PHP_PATH_ROOT."images/".$image_vide, $this->src_reduite);
		list($this->width, $this->height) = @getimagesize($this->src);
	}

	// Accesseurs
	public function get_source() {return $this->source;}
	public function get_nom() {return $this->nom;}
	public function get_src() {return $this->src;}
	public function get_src_reduite() {return $this->src_reduite;}
	public function get_alt() {return $this->alt;}
	public function get_legende() {return $this->legende;}
	public function get_copyright() {return $this->copyright;}
	public function get_width_standard() {return $this->width_standard;}
	public function get_height_standard() {return $this->height_standard;}
	public function get_width() {return $this->width;}
	public function get_height() {return $this->height;}
	public function get_style_legende() {return $this->style_legende;}
	public function get_lien() {return $this->lien;}
	public function get_est_vide() {return (($this->width == 1) && ($this->height == 1)); }
	public function get_extension() {
		$ext = strtolower(@pathinfo($this->src, PATHINFO_EXTENSION));
		$ret = ($ext == _IMAGE_EXTENSION_JPEG)?_IMAGE_EXTENSION_JPG:$ext;
		return $ret;
	}
}

class galerie_media {
	private $liste_elems = array();
	
	function ajouter_elem($nom_elem) {$this->liste_elems[] = $nom_elem;}
	function get_nb_elems() {return count($this->liste_elems);}
	function get_elem($index) {return $this->liste_elems[$index];}
}
	
class xml_media {
	// Propriétés
	private $styles = array();
	private $images = array();
	private $galeries = array();
	private $mobile = false;

	// Méthodes publiques
	public function __construct($mobile=false) {
		$this->mobile = $mobile;
	}

	// Méthodes publiques
	function ouvrir($source, $nom, $suffixe = null) {
		$xml_media = new xml_struct();
		$ret = $xml_media->ouvrir($nom);
		if ($ret) {
			// Traitement des styles de légende
			$nb_styles = $xml_media->compter_elements(_MEDIA_STYLE_LEGENDE);
			$xml_media->pointer_sur_balise(_MEDIA_STYLE_LEGENDE);
			for ($cpt = 0;$cpt < $nb_styles; $cpt++) {
				$nom = $xml_media->lire_n_attribut(_MEDIA_ATTR_NOM, $cpt);
				if (strlen($nom) > 0) {
					$marge_haut = $xml_media->lire_n_valeur(_MEDIA_STYLE_LEGENDE_MARGE_HAUT, $cpt);
					$marge_bas = $xml_media->lire_n_valeur(_MEDIA_STYLE_LEGENDE_MARGE_BAS, $cpt);
					$marge_gauche = $xml_media->lire_n_valeur(_MEDIA_STYLE_LEGENDE_MARGE_GAUCHE, $cpt);
					$marge_droite = $xml_media->lire_n_valeur(_MEDIA_STYLE_LEGENDE_MARGE_DROITE, $cpt);
					$couleur_fond = $xml_media->lire_n_valeur(_MEDIA_STYLE_LEGENDE_COULEUR_FOND, $cpt);
					$couleur_texte = $xml_media->lire_n_valeur(_MEDIA_STYLE_LEGENDE_COULEUR_TEXTE, $cpt);
					$style_texte = $xml_media->lire_n_valeur(_MEDIA_STYLE_LEGENDE_STYLE_TEXTE, $cpt);
					$lien_souligne = $xml_media->lire_n_valeur(_MEDIA_STYLE_LEGENDE_LIEN_SOULIGNE, $cpt);
					$niveau_titre = $xml_media->lire_n_valeur(_MEDIA_STYLE_LEGENDE_NIVEAU_TITRE, $cpt);
					$survol = $xml_media->lire_n_valeur(_MEDIA_STYLE_LEGENDE_SURVOL, $cpt);
					
					// Création de l'objet images
					$style = new style_media();
					$style->set_marge_haut($marge_haut);
					$style->set_marge_bas($marge_bas);
					$style->set_marge_gauche($marge_gauche);
					$style->set_marge_droite($marge_droite);
					$style->set_couleur_fond($couleur_fond);
					$style->set_couleur_texte($couleur_texte);
					$style->set_style_texte($style_texte);
					$style->set_lien_souligne($lien_souligne);
					$style->set_niveau_titre($niveau_titre);
					$style->set_survol($survol);
					$this->styles[$nom] = $style;
				}
			}

			// Traitement des images
			$xml_media->pointer_sur_origine();
			$nb_images = $xml_media->compter_elements(_MEDIA_IMAGE);
			$xml_media->pointer_sur_balise(_MEDIA_IMAGE);
			for ($cpt = 0;$cpt < $nb_images; $cpt++) {
				$nom = $xml_media->lire_n_attribut(_MEDIA_ATTR_NOM, $cpt);
				if (strlen($nom) > 0) {
					$src = $xml_media->lire_n_valeur(_MEDIA_IMAGE_SRC, $cpt);
					if ($src) {
						$src_reduite = _XML_PATH_IMAGES_REDUITES_SITE.$src;
						$src = _XML_PATH_IMAGES_SITE.$src;

						// Vérifications
						if (file_exists($src)) {
							list($width, $height) = @getimagesize($src);
							$alt = $xml_media->lire_n_valeur(_MEDIA_IMAGE_ALT, $cpt);
							$legende = $xml_media->lire_n_valeur(_MEDIA_IMAGE_LEGENDE, $cpt);
							// En cas de légende on va lire l'attribut de style
							if (strlen($legende) > 0) {
								$xml_media->creer_repere($nom);
								$xml_media->pointer_sur_index($cpt);
								$xml_media->pointer_sur_balise(_MEDIA_IMAGE_LEGENDE);
								$nom_style = $xml_media->lire_attribut(_MEDIA_ATTR_STYLE);
								$xml_media->pointer_sur_repere($nom);
							}
							else {
								$nom_style = null;
							}
							$copyright = $xml_media->lire_n_valeur(_MEDIA_IMAGE_COPYRIGHT, $cpt);
							$lien = $xml_media->lire_n_valeur(_MEDIA_IMAGE_LIEN, $cpt);
							$largeur_standard = $xml_media->lire_n_valeur(_MEDIA_IMAGE_LARGEUR_STANDARD, $cpt);
							$hauteur_standard = $xml_media->lire_n_valeur(_MEDIA_IMAGE_HAUTEUR_STANDARD, $cpt);
							
							// Création de l'objet images
							$image = new img_media($source, $nom);
							if ($this->mobile) {
								$image->set_src($src_reduite);
								$image->set_src_reduite($src_reduite);
							}
							else {
								$image->set_src($src);
								$image->set_src_reduite($src_reduite);
							}
							$image->set_alt($alt);
							$image->set_legende($legende);
							$key_copy = (strlen($suffixe) > 0)?$copyright."_".$suffixe:$copyright;
							$image->set_copyright($key_copy);
							$image->set_lien($lien);
							$image->set_width_standard($largeur_standard);
							$image->set_height_standard($hauteur_standard);
							$image->set_width($width);
							$image->set_height($height);
							$image->set_style_legende($nom_style);
							$key = (strlen($suffixe) > 0)?$nom."_".$suffixe:$nom;
							$this->images[$key] = $image;
						}
					}
				}
			}

			// Traitement des galeries
			$xml_media->pointer_sur_origine();
			$nb_gals = $xml_media->compter_elements(_MEDIA_GALERIE);
			$xml_media->pointer_sur_balise(_MEDIA_GALERIE);
			$xml_media->creer_repere(_MEDIA_GALERIE);
			for ($cpt = 0;$cpt < $nb_gals; $cpt++) {
				$xml_media->pointer_sur_repere(_MEDIA_GALERIE);
				$xml_media->pointer_sur_index($cpt);
				$nom = (string) $xml_media->lire_attribut(_MEDIA_ATTR_NOM);
				if (strlen($nom) > 0) {
					$nb_elems = $xml_media->compter_elements(_MEDIA_GALERIE_ELEMENT);
					if ($nb_elems > 0) {
						$gal = new galerie_media();
						for ($cpt_elem = 0; $cpt_elem < $nb_elems; $cpt_elem++) {
							$valeur = $xml_media->lire_valeur_n(_MEDIA_GALERIE_ELEMENT, $cpt_elem);
							$gal->ajouter_elem((string) $valeur);
						}
						$this->galeries[$nom] = $gal;
					}
				}
			}
		}

		return $ret;
	}
	
	function get_nb_galeries() {return count($this->galeries);}
	function get_galerie($nom) {return $this->galeries[$nom];}
	function get_nb_images() {return count($this->images);}
	function get_image($nom) {return isset($this->images[$nom])?$this->images[$nom]:null;}
	function get_image_by_index($index) {$tab_index = array_keys($this->images);return $this->images[$tab_index[$index]];}
	function get_style($nom) {return $this->styles[$nom];}
	function get_id_image_actu($no) {return _MODULE_ACTU_IMAGE.$no;}
	function get_image_actu($no) {
		$nom = $this->get_id_image_actu($no);
		$image = $this->get_image($nom);
		return $image;
	}

	public function extraire_css() {
		$css = "";
		foreach ($this->styles as $nom_style => $style) {
			if ($style) {
				// Si nécessaire, création du style "extérieur"
				if ($style->get_est_exterieur()) {
					$css .= "."._CSS_PREFIXE_EXTERIEUR.$nom_style." {";
					$css .= "position:relative;margin:0 auto;vertical-align:middle;";
					if ($style->get_marge_ext_haut() > 0) {
						$css .= "padding-top:".$style->get_marge_ext_haut()."em;";
					}
					if ($style->get_marge_ext_droite() > 0) {
						$css .= "padding-right:".$style->get_marge_ext_droite()."em;";
					}
					if ($style->get_marge_ext_bas() > 0) {
						$css .= "padding-bottom:".$style->get_marge_ext_bas()."em;";
					}
					if ($style->get_marge_ext_gauche() > 0) {
						$css .= "padding-left:".$style->get_marge_ext_gauche()."em;";
					}
					$css .= "}"._CSS_FIN_LIGNE;
				}
				// Création du style "intérieur"
				$css .= "."._CSS_PREFIXE_INTERIEUR.$nom_style." {";
				$css .= $this->format_pc("top", $style->get_marge_int_haut());
				$css .= $this->format_pc("left", $style->get_marge_int_gauche());
				$css .= $this->format_pc("width", $style->get_width());
				$css .= $this->format_pc("height", $style->get_height());
				$fond = $style->get_couleur_fond();
				if (strlen($fond) > 0) {
					$css .= "background:".$fond.";";
				}
				$css .= "}"._CSS_FIN_LIGNE;
				$couleur = $style->get_couleur_texte();
				if (strlen($couleur) > 0) {
					$css .= "."._CSS_PREFIXE_INTERIEUR.$nom_style." a {";
					$css .= "color:".$couleur.";";
					if (!($style->get_lien_souligne())) {
						$css .= "text-decoration:none;";
					}
					$css .= "} ";
					$css .= "."._CSS_PREFIXE_INTERIEUR.$nom_style." p {";
					$css .= "color:".$couleur.";";
					$css .= "}"._CSS_FIN_LIGNE;
				}
			}
		}
		
		return $css;
	}
	
	private function format_pc($str_prop, $str_pc) {
		$pc = (int) $str_pc;
		$ret = $str_prop.":";
		$ret .= ($pc == 0)?$pc:$pc."%";
		$ret .= ";";
		
		return $ret;
	}
}