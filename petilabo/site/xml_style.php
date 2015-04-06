<?php
define("_PADDING_INT_BLOC_TOP", "10");
define("_PADDING_INT_BLOC_RIGHT", "10");
define("_PADDING_INT_BLOC_BOTTOM", "10");
define("_PADDING_INT_BLOC_LEFT", "10");

class style_contenu extends xml_abstract {
	public function __construct($nom) {
		$this->enregistrer_chaine("nom", $nom);
		$this->enregistrer_entier("marge_haut", 0, _STYLE_CONTENU_MARGE_HAUT);
		$this->enregistrer_entier("marge_bas", 0, _STYLE_CONTENU_MARGE_BAS);
		$this->enregistrer_chaine("couleur_fond", null, _XML_COULEUR_FOND);
		$this->enregistrer_chaine("motif_fond", null, _XML_MOTIF_FOND);
		$this->enregistrer_chaine("papierpeint_fond", null, _STYLE_CONTENU_PAPIERPEINT_FOND);
		$this->enregistrer_chaine("type_special", null, _STYLE_CONTENU_TYPE_SPECIAL);
	}
}

class style_bloc extends xml_abstract {
	public function __construct($nom) {
		$this->enregistrer_chaine("nom", $nom);
		$this->enregistrer_entier("marge_haut", 0, _STYLE_BLOC_MARGE_HAUT);
		$this->enregistrer_entier("marge_bas", 0, _STYLE_BLOC_MARGE_BAS);
		$this->enregistrer_entier("marge_gauche", 0, _STYLE_BLOC_MARGE_GAUCHE);
		$this->enregistrer_entier("marge_droite", 0, _STYLE_BLOC_MARGE_DROITE);
		$this->enregistrer_chaine("couleur_fond", null, _XML_COULEUR_FOND);
		$this->enregistrer_chaine("motif_fond", null, _XML_MOTIF_FOND);
		$this->enregistrer_chaine("papierpeint_fond", null, _STYLE_BLOC_PAPIERPEINT_FOND);
		$this->enregistrer_chaine("bordure");$this->enregistrer_chaine("type_bordure");
	}
}					
					
class style_formulaire extends xml_abstract {
	public function __construct() {
		$this->enregistrer_chaine("couleur_texte_champ", null, _STYLE_FORMULAIRE_TEXTE_CHAMP);
		$this->enregistrer_chaine("couleur_fond_champ", null, _STYLE_FORMULAIRE_FOND_CHAMP);
		$this->enregistrer_chaine("couleur_fond_saisie", null, _STYLE_FORMULAIRE_FOND_SAISIE);
		$this->enregistrer_chaine("couleur_texte_bouton", null, _STYLE_FORMULAIRE_TEXTE_BOUTON);
		$this->enregistrer_chaine("couleur_fond_bouton", null, _STYLE_FORMULAIRE_FOND_BOUTON);
		$this->enregistrer_chaine("couleur_texte_statut", null, _STYLE_FORMULAIRE_TEXTE_STATUT);
	}
}
					
class style_actu extends xml_abstract {
	public function __construct() {
		$this->enregistrer_chaine("marge_gauche_titre", null, _STYLE_ACTUALITE_GAUCHE_TITRE);
		$this->enregistrer_chaine("marge_haut_titre", null, _STYLE_ACTUALITE_HAUT_TITRE);
		$this->enregistrer_chaine("couleur_titre", null, _STYLE_ACTUALITE_COULEUR_TITRE);
		$this->enregistrer_chaine("fond_titre", null, _STYLE_ACTUALITE_FOND_TITRE);
		$this->enregistrer_chaine("marge_gauche_sous_titre", null, _STYLE_ACTUALITE_GAUCHE_STITRE);
		$this->enregistrer_chaine("marge_haut_sous_titre", null, _STYLE_ACTUALITE_HAUT_STITRE);
		$this->enregistrer_chaine("couleur_sous_titre", null, _STYLE_ACTUALITE_COULEUR_STITRE);
		$this->enregistrer_chaine("fond_sous_titre", null, _STYLE_ACTUALITE_FOND_STITRE);
		$this->enregistrer_chaine("marge_gauche_resume", null, _STYLE_ACTUALITE_GAUCHE_RESUME);
		$this->enregistrer_chaine("marge_haut_resume", null, _STYLE_ACTUALITE_HAUT_RESUME);
		$this->enregistrer_chaine("couleur_resume", null, _STYLE_ACTUALITE_COULEUR_RESUME);
		$this->enregistrer_chaine("fond_resume", null, _STYLE_ACTUALITE_FOND_RESUME);
	}
}

class style_puce extends xml_abstract {
	public function __construct() {
		$this->enregistrer_chaine("icone", null, _XML_ICONE);
		$this->enregistrer_chaine("couleur", null, _XML_COULEUR);
		$this->enregistrer_flottant("taille", 0, _XML_TAILLE);
		$this->enregistrer_chaine("ombre", null, _STYLE_PUCE_OMBRE);
	}
}

class style_texte extends xml_abstract {
	// Propriétés
	private $police = null;private $src_police = null;private $famille_police = null;
	private $alignement = null;private $decoration = null;

	public function __construct() {
		$this->enregistrer_chaine("couleur", null, _XML_COULEUR);
		$this->enregistrer_chaine("couleur_lien", null, _XML_COULEUR_LIEN);
		$this->enregistrer_chaine("couleur_survol", null, _XML_COULEUR_SURVOL);
		$this->enregistrer_chaine("puce", null, _STYLE_TEXTE_PUCE);
		$this->enregistrer_flottant("taille", 0, _XML_TAILLE);
	}
	// Manipulateurs
	public function set_police($police, $famille_police, $src_police) {
		$this->famille_police = strtolower($this->normaliser_famille_police($famille_police));
		$this->src_police = $this->normaliser_src_police($src_police);
		$this->police = $this->normaliser_police($police);
	}
	public function set_alignement($param) {$this->alignement = $this->normaliser_alignement($param);}
	public function set_decoration($param) {$this->decoration = $this->normaliser_decoration($param);}

	// Accesseurs
	public function get_police() {return $this->police;}
	public function get_src_police() {return $this->src_police;}
	public function get_famille_police() {return $this->famille_police;}
	public function get_alignement() {return $this->alignement;}
	public function get_decoration() {return $this->decoration;}
	
	private function normaliser_alignement($param) {
		$ret = $param;
		if (strlen($ret) > 0) {
			$ret = trim(strtolower($ret));
			if ((strcmp($ret, _XML_GAUCHE)) && (strcmp($ret, _XML_DROITE))  && (strcmp($ret, _XML_JUSTIFIE))) {
				$ret = _XML_CENTRE;
			}
		}
		return $ret;
	}
	private function normaliser_decoration($param) {
		$param = trim(strtolower($param));
		$ret = ((strcmp($param, _STYLE_ATTR_DECORATION_GRAS)) && (strcmp($param, _STYLE_ATTR_DECORATION_ITALIQUE)))?null:$param;
		return $ret;
	}
	private function normaliser_famille_police($param) {
		$param = trim(ucwords(strtolower($param)));
		$ret = preg_replace('!\s+!', ' ', $param);
		$ret = str_replace(" Ui ", " UI ", $ret);
		return $ret;
	}
	private function normaliser_src_police($param) {
		$param = trim(strtolower($param));
		$ret = (strcmp($param, _STYLE_ATTR_POLICE_SOURCE_OFL))?_STYLE_ATTR_POLICE_SOURCE_GOOGLE:_STYLE_ATTR_POLICE_SOURCE_OFL;
		return $ret;
	}
	private function normaliser_police($param) {
		$ret = $this->normaliser_famille_police($param);
		if (strcmp($this->src_police, _STYLE_ATTR_POLICE_SOURCE_GOOGLE)) {
			$ret = str_replace("font", "Font", str_replace(" ", "", strtr($ret, "'", " ")));
		}
		return $ret;
	}
}

class xml_style {
	// Propriétés
	private $styles_contenus = array();
	private $styles_blocs = array();
	private $styles_puces = array();
	private $styles_textes = array();
	private $styles_formulaires = array();
	private $styles_actus = array();

	// Méthodes publiques
	public function ouvrir($nom) {
		$xml_style = new xml_struct();
		$ret = $xml_style->ouvrir($nom);
		if ($ret) {
			// Traitement des styles de contenu
			$nb_styles = $xml_style->compter_elements(_STYLE_CONTENU);
			$xml_style->pointer_sur_balise(_STYLE_CONTENU);
			for ($cpt = 0;$cpt < $nb_styles; $cpt++) {
				$nom = $xml_style->lire_n_attribut(_XML_NOM, $cpt);
				if (strlen($nom) > 0) {
					$style = new style_contenu($nom);
					$style->load($xml_style, $cpt);
					$this->styles_contenus[$nom] = $style;
				}
			}
			// Traitement des styles de bloc
			$xml_style->pointer_sur_origine();
			$nb_styles = $xml_style->compter_elements(_STYLE_BLOC);
			$xml_style->pointer_sur_balise(_STYLE_BLOC);
			for ($cpt = 0;$cpt < $nb_styles; $cpt++) {
				$nom = $xml_style->lire_n_attribut(_XML_NOM, $cpt);
				if (strlen($nom) > 0) {
					$style = new style_bloc($nom);
					$style->load($xml_style, $cpt);
					$bordure = $xml_style->lire_n_valeur(_STYLE_BLOC_BORDURE, $cpt);
					// En cas de bordure on va lire le type de bordure
					if (strlen($bordure) > 0) {
						$xml_style->creer_repere($nom);
						$xml_style->pointer_sur_index($cpt);
						$xml_style->pointer_sur_balise(_STYLE_BLOC_BORDURE);
						$type_bordure = $xml_style->lire_attribut(_STYLE_BLOC_ATTR_TYPE_BORDURE);
						$xml_style->pointer_sur_repere($nom);
					}
					else {
						$type_bordure = null;
					}
					$style->set_bordure($bordure);$style->set_type_bordure($type_bordure);
					$this->styles_blocs[$nom] = $style;
				}
			}
			// Traitement des styles de puce
			$xml_style->pointer_sur_origine();
			$nb_styles = $xml_style->compter_elements(_STYLE_PUCE);
			$xml_style->pointer_sur_balise(_STYLE_PUCE);
			for ($cpt = 0;$cpt < $nb_styles; $cpt++) {
				$nom = $xml_style->lire_n_attribut(_XML_NOM, $cpt);
				if (strlen($nom) > 0) {
					$style = new style_puce();
					$style->load($xml_style, $cpt);
					$this->styles_puces[$nom] = $style;
				}
			}
			// Traitement des styles de texte
			$xml_style->pointer_sur_origine();
			$nb_styles = $xml_style->compter_elements(_STYLE_TEXTE);
			$xml_style->pointer_sur_balise(_STYLE_TEXTE);
			for ($cpt = 0;$cpt < $nb_styles; $cpt++) {
				$nom = $xml_style->lire_n_attribut(_XML_NOM, $cpt);
				if (strlen($nom) > 0) {
					$style = new style_texte();
					$style->load($xml_style, $cpt);
					$alignement = $xml_style->lire_n_valeur(_XML_ALIGNEMENT, $cpt);
					$decoration = $xml_style->lire_n_valeur(_STYLE_TEXTE_DECORATION, $cpt);
					$police = $xml_style->lire_n_valeur(_STYLE_TEXTE_POLICE, $cpt);
					// En cas de police on va lire la source et la famille de la police
					if (strlen($police) > 0) {
						$xml_style->creer_repere($nom);
						$xml_style->pointer_sur_index($cpt);
						$xml_style->pointer_sur_balise(_STYLE_TEXTE_POLICE);
						$src_police = $xml_style->lire_attribut(_STYLE_TEXTE_ATTR_POLICE_SOURCE);
						$famille_police = $xml_style->lire_attribut(_STYLE_TEXTE_ATTR_POLICE_FAMILLE);
						$xml_style->pointer_sur_repere($nom);
					}
					else {
						$src_police = null;$famille_police = null;
					}
					$style->set_police($police, $famille_police, $src_police);
					$style->set_alignement($alignement);$style->set_decoration($decoration);
					$this->styles_textes[$nom] = $style;
				}
			}
			// Traitement des styles de formulaire
			$xml_style->pointer_sur_origine();
			$nb_styles = $xml_style->compter_elements(_STYLE_FORMULAIRE);
			$xml_style->pointer_sur_balise(_STYLE_FORMULAIRE);
			for ($cpt = 0;$cpt < $nb_styles; $cpt++) {
				$nom = $xml_style->lire_n_attribut(_XML_NOM, $cpt);
				if (strlen($nom) > 0) {
					$style = new style_formulaire();
					$style->load($xml_style, $cpt);
					$this->styles_formulaires[$nom] = $style;
				}
			}
			// Traitement des styles d'actualité
			$xml_style->pointer_sur_origine();
			$nb_styles = $xml_style->compter_elements(_STYLE_ACTUALITE);
			$xml_style->pointer_sur_balise(_STYLE_ACTUALITE);
			for ($cpt = 0;$cpt < $nb_styles; $cpt++) {
				$nom = $xml_style->lire_n_attribut(_XML_NOM, $cpt);
				if (strlen($nom) > 0) {
					// Création du style d'actualité
					$style = new style_actu();
					$style->load($xml_style, $cpt);
					$this->styles_actus[$nom] = $style;
				}
			}
		}
		return $ret;
	}

	public function get_style_contenu($nom) {return (isset($this->styles_contenus[$nom])?$this->styles_contenus[$nom]:null);}
	public function get_style_bloc($nom) {return $this->styles_blocs[$nom];}
	public function get_style_puce($nom) {return $this->styles_puces[$nom];}
	public function get_style_texte($nom) {return $this->styles_textes[$nom];}
	public function get_style_formulaire($nom) {return $this->styles_formulaires[$nom];}
	public function get_style_actu($nom) {return $this->styles_actus[$nom];}

	public function extraire_css($trad_icone_methode) {
		$css = "";
		// Styles de contenu
		foreach ($this->styles_contenus as $nom_style => $style) {
			if ($style) {
				$css .= "."._CSS_PREFIXE_CONTENU.$nom_style." {";
				$marge_h = $style->get_marge_haut();
				$marge_b = $style->get_marge_bas();
				$css .= "margin-top:".$marge_h."em;margin-bottom:".$marge_b."em;";
				$couleur_fond = $style->get_couleur_fond();
				if (strlen($couleur_fond) > 0) {$css .= "background:".$couleur_fond.";";}
				$motif_fond = $style->get_motif_fond();
				if (strlen($motif_fond) > 0) {$css .= "background:url('"._XML_PATH_IMAGES_SITE.$motif_fond."') repeat;";}
				$papierpeint_fond = $style->get_papierpeint_fond();
				if (strlen($papierpeint_fond) > 0) {
					$css .= "background:url('"._XML_PATH_IMAGES_SITE.$papierpeint_fond."') no-repeat center center;";
					$css .= "-webkit-background-size: cover;";
					$css .= "-moz-background-size: cover;";
					$css .= "-o-background-size: cover;";
					$css .= "background-size: cover;";
				}
				$css .= "}"._CSS_FIN_LIGNE;
			}
		}
		// Styles de bloc
		foreach ($this->styles_blocs as $nom_style => $style) {
			if ($style) {
				$css .= "."._CSS_PREFIXE_EXTERIEUR.$nom_style." {";
				$marge_h = $style->get_marge_haut();
				$marge_b = $style->get_marge_bas();
				$egal_v = ($marge_h == $marge_b)?true:false;
				$marge_g = $style->get_marge_gauche();
				$marge_d = $style->get_marge_droite();
				$egal_h = ($marge_g == $marge_d)?true:false;
				if (($egal_v) && ($egal_h)) {
					$egal_hv = ($marge_h == $marge_g)?true:false;
					if ($egal_hv) {$css .= $this->format_marge_1($marge_h);}
					else {$css .= $this->format_marge_2($marge_h, $marge_g);}
				}
				else {
					$css .= $this->format_marge_4($marge_h, $marge_d, $marge_b, $marge_g);
				}
				$bordure = $style->get_bordure();
				$type_bordure = $style->get_type_bordure();
				switch ($type_bordure) {
					case _XML_COULEUR :
						$css .= "background:".$bordure.";";
						break;
					case _STYLE_ATTR_TYPE_BORDURE_MOTIF :
						$css .= "background:url('"._XML_PATH_IMAGES_SITE.$bordure."') repeat;";
						break;
					default :
						break;
				}
				$css .= "}"._CSS_FIN_LIGNE;
				$css .= "."._CSS_PREFIXE_INTERIEUR.$nom_style." {";
				$fond = $style->get_couleur_fond();
				if (strlen($fond) > 0) {$css .= "background:".$fond.";";}
				$motif_fond = $style->get_motif_fond();
				if (strlen($motif_fond) > 0) {$css .= "background:url('"._XML_PATH_IMAGES_SITE.$motif_fond."') repeat;";}
				$papierpeint_fond = $style->get_papierpeint_fond();
				if (strlen($papierpeint_fond) > 0) {
					$css .= "background:url('"._XML_PATH_IMAGES_SITE.$papierpeint_fond."') no-repeat center center;";
					$css .= "-webkit-background-size: cover;";
					$css .= "-moz-background-size: cover;";
					$css .= "-o-background-size: cover;";
					$css .= "background-size: cover;";
				}
				// Le padding-top est augmenté en cas de bandeau ou de scotch
				switch ($type_bordure) {
					case _STYLE_ATTR_TYPE_BORDURE_SCOTCH :
						$padding_top = "20px";
						break;
					default :
						$padding_top = _PADDING_INT_BLOC_TOP."px";
						break;
				}
				$css .= "padding:".$padding_top." "._PADDING_INT_BLOC_RIGHT."px "._PADDING_INT_BLOC_BOTTOM."px "._PADDING_INT_BLOC_LEFT."px;";
				$type_bordure = $style->get_type_bordure();
				switch ($type_bordure) {
					case _STYLE_ATTR_TYPE_BORDURE_OMBRE :
						$distance = ($marge_h + $marge_b + $marge_g + $marge_d) / 4;
						$css .= "box-shadow:0 0 ".$distance."px ".$bordure.";";
						$css .= "-moz-box-shadow:0 0 ".$distance."px ".$bordure.";";
						$css .= "-webkit-box-shadow:0 0 ".$distance."px ".$bordure.";";
						break;
					default :
						break;
				}
				$css .= "}"._CSS_FIN_LIGNE;
			}
		}
		// Styles de texte
		foreach ($this->styles_textes as $nom_style => $style) {
			if ($style) {
				// Style pour le texte
				$css .= "."._CSS_PREFIXE_TEXTE.$nom_style. "{";
				$police = $style->get_police();
				if (strlen($police) > 0) {
					if (strcmp(trim(strtolower($police)), "serif")) {
						$css .= "font-family:'".$police."',sans-serif;";
					}
					else {
						$css .= "font-family:'Times New Roman',Times,serif;";
					}
				}
				$couleur = $style->get_couleur();
				if (strlen($couleur) > 0) {
					$css .= "color:".$couleur.";";
				}
				$taille = $style->get_taille();
				if ($taille > 0) {
					$size = (float) $taille;
					$css .= "font-size:".$size."em;";
				}
				$alignement = $style->get_alignement();
				$css .= $this->extraire_css_alignement($alignement);
				$decoration = $style->get_decoration();
				if (!(strcmp($decoration, _STYLE_ATTR_DECORATION_GRAS))) {
					$css .= "font-weight:bold;";
				}
				elseif (!(strcmp($decoration, _STYLE_ATTR_DECORATION_ITALIQUE))) {
					$css .= "font-style:italic;";
				}
				$css .= "padding:0;";
				$css .= "}"._CSS_FIN_LIGNE;
				// Gestion des puces
				$nom_puce = $style->get_puce();
				if (strlen($nom_puce) > 0) {
					$puce = isset($this->styles_puces[$nom_puce])?$this->styles_puces[$nom_puce]:null;
					if ($puce) {
						$icone_puce = $puce->get_icone();
						if (strlen($icone_puce) > 0) {
							$icone_puce = $trad_icone_methode($icone_puce, true);
							$taille_puce = ($puce->get_taille() < 0.1)?1.0:$puce->get_taille();
							$indentation = round($taille_puce*2.4, 1);
							$taille_pc = (int) ($taille_puce * 100);
							$couleur_puce = $puce->get_couleur();
							$ombre_puce = strtolower(trim($puce->get_ombre()));
							$css .= "."._CSS_PREFIXE_TEXTE.$nom_style. "{";
							$css .= "text-indent:-".$indentation."em;padding-left:".$indentation."em!important;";
							$css .= "}"._CSS_FIN_LIGNE;
							$css .= "."._CSS_PREFIXE_TEXTE.$nom_style. ":before{font-family:'FontAwesome';";
							$css .= "font-size:".$taille_pc."%;content:'\\".$icone_puce."';";
							$css .= "padding-left:0.8em;padding-right:0.8em;";
							if (strlen($couleur_puce) > 0) {$css .= "color:".$couleur_puce.";";}
							if (!(strcmp($ombre_puce, _XML_TRUE))) {$css .= "text-shadow:2px 2px 3px #aaa;";}
							$css .= "}"._CSS_FIN_LIGNE;
						}
					}
				}
				// Style équivalent pour les icones
				$css .= "."._CSS_PREFIXE_ICONE.$nom_style. "{";
				$css .= "font-family:'FontAwesome',sans-serif;";
				if (strlen($couleur) > 0) {
					$css .= "color:".$couleur.";";
				}
				$taille = $style->get_taille();
				if ($taille > 0) {
					$size = (float) $taille;
					$css .= "font-size:".$size."em;";
				}
				$alignement = $style->get_alignement();
				$css .= $this->extraire_css_alignement($alignement);
				$decoration = $style->get_decoration();
				if (!(strcmp($decoration, _STYLE_ATTR_DECORATION_GRAS))) {
					$css .= "font-weight:bold;";
				}
				elseif (!(strcmp($decoration, _STYLE_ATTR_DECORATION_ITALIQUE))) {
					$css .= "font-style:italic;";
				}
				$css .= "}"._CSS_FIN_LIGNE;
				// Style pour les liens dans le paragraphe
				$couleur_lien = $style->get_couleur_lien();
				if (strlen($couleur_lien) > 0) {
					$css .= "."._CSS_PREFIXE_TEXTE.$nom_style. " a{";
					$css .= "color:".$couleur_lien.";";
					$css .= "}"._CSS_FIN_LIGNE;
				}
				$couleur_survol = $style->get_couleur_survol();
				if (strlen($couleur_survol) > 0) {
					$css .= "."._CSS_PREFIXE_TEXTE.$nom_style. " a:hover{";
					$css .= "color:".$couleur_survol.";";
					$css .= "}"._CSS_FIN_LIGNE;
				}
			}
		}
		// Styles de formulaire
		foreach ($this->styles_formulaires as $nom_style => $style) {
			if ($style) {
				// Style pour les champs
				$css .= "."._CSS_PREFIXE_FORMULAIRE_CHAMP.$nom_style. "{";
				$couleur_texte_champ = $style->get_couleur_texte_champ();
				if (strlen($couleur_texte_champ) > 0) {
					$css .= "color:".$couleur_texte_champ.";";
				}
				$couleur_fond_champ = $style->get_couleur_fond_champ();
				if (strlen($couleur_fond_champ) > 0) {
					$css .= "background:".$couleur_fond_champ.";";
				}
				$css .= "}"._CSS_FIN_LIGNE;
				$couleur_fond_saisie = $style->get_couleur_fond_saisie();
				if (strlen($couleur_fond_saisie) > 0) {
					$css .= "."._CSS_PREFIXE_FORMULAIRE_CHAMP.$nom_style. ":focus {";
					$css .= "background:".$couleur_fond_saisie.";";
					$css .= "}"._CSS_FIN_LIGNE;
				}
				// TODO : Styles pour les alertes et pour le bouton envoyer
			}
		}
		// Style de l'actualité
		foreach ($this->styles_actus as $nom_style => $style) {
			if ($style) {
				$css .= "."._CSS_PREFIXE_ACTU."titre_".$nom_style. "{";
				$marge_gauche_titre = (int) $style->get_marge_gauche_titre();
				$css .= "left:".$marge_gauche_titre.(($marge_gauche_titre > 0)?"%":"").";";
				$marge_haut_titre = (int) $style->get_marge_haut_titre();
				$css .= "top:".$marge_haut_titre.(($marge_haut_titre > 0)?"%":"").";";
				$couleur_titre = $style->get_couleur_titre();
				if (strlen($couleur_titre) > 0) {
					$css .= "color:".$couleur_titre.";";
				}
				$fond_titre = $style->get_fond_titre();
				if (strlen($fond_titre) > 0) {
					$css .= "background:".$fond_titre.";";
				}
				$css .= "}"._CSS_FIN_LIGNE;
				$css .= "."._CSS_PREFIXE_ACTU."sous_titre_".$nom_style. "{";
				$marge_gauche_sous_titre = (int) $style->get_marge_gauche_sous_titre();
				$css .= "left:".$marge_gauche_sous_titre.(($marge_gauche_sous_titre > 0)?"%":"").";";
				$marge_haut_sous_titre = (int) $style->get_marge_haut_sous_titre();
				$css .= "top:".$marge_haut_sous_titre.(($marge_haut_sous_titre > 0)?"%":"").";";
				$couleur_sous_titre = $style->get_couleur_sous_titre();
				if (strlen($couleur_sous_titre) > 0) {
					$css .= "color:".$couleur_sous_titre.";";
				}
				$fond_sous_titre = $style->get_fond_sous_titre();
				if (strlen($fond_sous_titre) > 0) {
					$css .= "background:".$fond_sous_titre.";";
				}
				$css .= "}"._CSS_FIN_LIGNE;
				$css .= "."._CSS_PREFIXE_ACTU."resume_".$nom_style. "{";
				$marge_gauche_resume = (int) $style->get_marge_gauche_resume();
				$css .= "left:".$marge_gauche_resume.(($marge_gauche_resume > 0)?"%":"").";";
				$marge_haut_resume = (int) $style->get_marge_haut_resume();
				$css .= "top:".$marge_haut_resume.(($marge_haut_resume > 0)?"%":"").";";
				$couleur_resume = $style->get_couleur_resume();
				if (strlen($couleur_resume) > 0) {
					$css .= "color:".$couleur_resume.";";
				}
				$fond_resume = $style->get_fond_resume();
				if (strlen($fond_resume) > 0) {
					$css .= "background:".$fond_resume.";";
				}
				$css .= "}"._CSS_FIN_LIGNE;
			}
		}
		
		return $css;
	}
	public function extraire_css_ie() {
		$css = "";return $css;
	}

	private function format_marge_1($marge) {
		$ret = "padding:";
		$ret .= $this->format_px($marge);
		$ret .= ";";
		return $ret;
	}
	private function format_marge_2($marge_v, $marge_h) {
		$ret = "padding:";
		$ret .= $this->format_px($marge_v)." ".$this->format_px($marge_h);
		$ret .= ";";	
		return $ret;
	}
	private function format_marge_4($marge_h, $marge_d, $marge_b, $marge_g) {
		$ret = "padding:";
		$ret .= $this->format_px($marge_h)." ";
		$ret .= $this->format_px($marge_d)." ";
		$ret .= $this->format_px($marge_b)." ";
		$ret .= $this->format_px($marge_g);
		$ret .= ";";
		return $ret;
	}
	private function format_px($marge) {
		$ret = $marge;
		if ($marge != 0) {
			$ret .= "px";
		}
		return $ret;
	}
	private function extraire_css_alignement($param) {
		$ret = "";
		if (strlen($param) > 0) {
			if (!(strcmp($param, _XML_GAUCHE))) {
				$ret = "text-align:left;";
			}
			elseif (!(strcmp($param, _XML_DROITE))) {
				$ret = "text-align:right;";
			}
			elseif (!(strcmp($param, _XML_JUSTIFIE))) {
				$ret = "text-align:justify;";
			}
			else {
				$ret = "text-align:center;";
			}
		}
		else {
			$ret = "text-align:center;";
		}
		return $ret;
	}
}