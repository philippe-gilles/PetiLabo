<?php

define("_MODULE_ACTU_IMAGE", "image_actu_");
define("_IMAGE_EXTENSION_JPEG", "jpeg");
define("_IMAGE_EXTENSION_JPG", "jpg");
define("_IMAGE_EXTENSION_PNG", "png");
define("_IMAGE_EXTENSION_GIF", "gif");

class style_media extends xml_abstract {
	private $marge_ext_haut = 0;private $marge_ext_bas = 0;private $marge_ext_gauche = 0;private $marge_ext_droite = 0;
	private $marge_int_haut = 0;private $marge_int_bas = 0;private $marge_int_gauche = 0;private $marge_int_droite = 0;
	private $survol = false;private $lien_souligne = false;private $niveau_titre = 0;
	
	// Constructeur
	public function __construct($nom) {
		$this->enregistrer_chaine("nom", $nom);
		$this->enregistrer_chaine("style_texte", $nom, _MEDIA_STYLE_LEGENDE_STYLE_TEXTE);
		$this->enregistrer_chaine("couleur_fond", $nom, _MEDIA_STYLE_LEGENDE_COULEUR_FOND);
		$this->enregistrer_chaine("couleur_texte", $nom, _MEDIA_STYLE_LEGENDE_COULEUR_TEXTE);
	}

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
	public function get_lien_souligne() {return $this->lien_souligne;}
	public function get_niveau_titre() {return $this->niveau_titre;}
	public function get_survol() {return $this->survol;}
}

class img_media extends xml_abstract {
	public function __construct($source, $nom) {
		$this->enregistrer_chaine("source", $source);$this->enregistrer_chaine("nom", $nom);
		$this->enregistrer_entier("width_standard", 0, _MEDIA_IMAGE_LARGEUR_STANDARD);
		$this->enregistrer_entier("height_standard", 0, _MEDIA_IMAGE_HAUTEUR_STANDARD);
		$this->enregistrer_entier("width_reduite", 0, _MEDIA_IMAGE_LARGEUR_REDUITE);
		$this->enregistrer_entier("height_reduite", 0, _MEDIA_IMAGE_HAUTEUR_REDUITE);
		$this->enregistrer_chaine("alt", null, _MEDIA_IMAGE_ALT);
		$this->enregistrer_chaine("lien", null, _MEDIA_IMAGE_LIEN);
		$this->enregistrer_chaine("copyright");
		$this->enregistrer_chaine("src");$this->enregistrer_chaine("src_reduite");
		$this->enregistrer_chaine("dest");$this->enregistrer_chaine("dest_reduite");
		$this->enregistrer_chaine("legende");$this->enregistrer_chaine("style_legende");
		$this->enregistrer_entier("width");$this->enregistrer_entier("height");
		$this->enregistrer_chaine("base");$this->enregistrer_entier("version");
	}
	public function set_vide() {
		$extension = $this->get_extension();
		$image_vide = _IMAGE_VIDE_1X1.".".$extension;
		@copy(_PHP_PATH_ROOT."images/".$image_vide, $this->get_src());
		if (($this->get_width_reduite() > 0) || ($this->get_height_reduite() > 0)) {
			@copy(_PHP_PATH_ROOT."images/".$image_vide, $this->get_src_reduite());
		}
		@rename($this->get_src(), $this->get_dest());
		$this->set_src($this->get_dest());
		if (($this->get_width_reduite() > 0) || ($this->get_height_reduite() > 0)) {
			@rename($this->get_src_reduite(), $this->get_dest_reduite());
			$this->set_src_reduit($this->get_dest_reduite());
		}
		list($width, $height) = @getimagesize($this->get_src());
		$this->set_width($width);$this->set_height($height);
	}

	// Accesseurs
	public function get_est_vide() {return (($this->get_width() == 1) && ($this->get_height() == 1)); }
	public function get_extension() {
		$ext = strtolower(@pathinfo($this->get_src(), PATHINFO_EXTENSION));
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
	private $styles = array();
	private $images = array();
	private $galeries = array();

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
					$lien_souligne = $xml_media->lire_n_valeur(_MEDIA_STYLE_LEGENDE_LIEN_SOULIGNE, $cpt);
					$niveau_titre = $xml_media->lire_n_valeur(_MEDIA_STYLE_LEGENDE_NIVEAU_TITRE, $cpt);
					$survol = $xml_media->lire_n_valeur(_MEDIA_STYLE_LEGENDE_SURVOL, $cpt);
					
					// Création de l'objet images
					$style = new style_media($nom);
					$style->load($xml_media, $cpt);
					$style->set_marge_haut($marge_haut);$style->set_marge_bas($marge_bas);
					$style->set_marge_gauche($marge_gauche);$style->set_marge_droite($marge_droite);
					$style->set_lien_souligne($lien_souligne);
					$style->set_niveau_titre($niveau_titre);$style->set_survol($survol);
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
					$fichier = $xml_media->lire_n_valeur(_MEDIA_IMAGE_SRC, $cpt);
					if (strlen($fichier) > 0) {
						list($base, $extension) = $this->parser_extension($fichier);
						$version = $this->parser_version(_XML_PATH_IMAGES_SITE, _XML_PATH_IMAGES_REDUITES_SITE, $base, $extension);
						$src_reduite = sprintf("%s%s-v%03d%s", _XML_PATH_IMAGES_REDUITES_SITE, $base, $version, $extension);
						$src = sprintf("%s%s-v%03d%s", _XML_PATH_IMAGES_SITE, $base, $version, $extension);
						$dest_reduite = sprintf("%s%s-v%03d%s", _XML_PATH_IMAGES_REDUITES_SITE, $base, ((int) $version) + 1, $extension);
						$dest = sprintf("%s%s-v%03d%s", _XML_PATH_IMAGES_SITE, $base, ((int) $version) + 1, $extension);

						// Vérifications
						if (file_exists($src)) {
							$image = new img_media($source, $nom);
							$image->load($xml_media, $cpt);
							list($width, $height) = @getimagesize($src);
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
							$image->set_src($src);$image->set_src_reduite($src_reduite);
							$image->set_dest($dest);$image->set_dest_reduite($dest_reduite);
							$image->set_legende($legende);
							$key_copy = (strlen($suffixe) > 0)?$copyright."_".$suffixe:$copyright;
							$image->set_copyright($key_copy);
							$image->set_base($base);$image->set_version($version);
							$image->set_width($width);$image->set_height($height);
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
	private function parser_extension($fichier) {
		$point = (int) strpos($fichier, ".");
		if ($point > 0) {
			$ext = substr($fichier, $point);
			$base = substr($fichier, 0, $point);
		}
		else {
			$ext = "";
			$base = $fichier;
		}
		return (array($base, $ext));
	}
	private function parser_version($dir, $dir_reduite, $base, $ext) {
		$version = 0;
		$src = $dir."/".$base.$ext;
		if (file_exists($src)) {
			$dest = $dir."/".$base."-v000".$ext;
			@rename($src, $dest);
			$src_reduite = $dir_reduite."/".$base.$ext;
			$dest_reduite = $dir_reduite."/".$base."-v000".$ext;
			@rename($src_reduite, $dest_reduite);
		}
		else {
			$pattern = $dir."/".$base."-v[0-9][0-9][0-9]".$ext;
			$list = glob($pattern);
			$nb_list = count($list);
			if ($nb_list > 1) {
				for ($cpt = 0;$cpt < ($nb_list - 1); $cpt++) {@unlink($list[$cpt]);}
			}
			$fichier = @basename($list[((int) ($nb_list - 1))]);
			$scan = sscanf($fichier, $base."-v%d".$ext);
			if (count($scan) > 0) {$version = $scan[0];}
		}
		return $version;
	}
}