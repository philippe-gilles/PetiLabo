<?php
define("_PADDING_INT_BLOC_TOP", "10");
define("_PADDING_INT_BLOC_RIGHT", "10");
define("_PADDING_INT_BLOC_BOTTOM", "10");
define("_PADDING_INT_BLOC_LEFT", "10");

class style_contenu {
	// Propriétés
	private $nom = null;
	private $marge_haut = 0;private $marge_bas = 0;
	private $couleur_fond = null;private $motif_fond = null;private $papierpeint_fond = null;
	private $type_special = null;

	public function __construct($nom) {$this->nom = $nom;}

	// Manipulateurs
	public function set_marge_haut($param) {$this->marge_haut = (int) $param;}
	public function set_marge_bas($param) {$this->marge_bas = (int) $param;}
	public function set_couleur_fond($param) {$this->couleur_fond = $param;}
	public function set_motif_fond($param) {$this->motif_fond = $param;}
	public function set_papierpeint_fond($param) {$this->papierpeint_fond = $param;}
	public function set_type_special($param) {$this->type_special = $param;}

	// Accesseurs
	public function get_nom() {return $this->nom;}
	public function get_marge_haut() {return $this->marge_haut;}
	public function get_marge_bas() {return $this->marge_bas;}
	public function get_couleur_fond() {return $this->couleur_fond;}
	public function get_motif_fond() {return $this->motif_fond;}
	public function get_papierpeint_fond() {return $this->papierpeint_fond;}
	public function get_type_special() {return $this->type_special;}
}

class style_bloc {
	// Propriétés
	private $nom = null;
	private $marge_haut = 0;private $marge_bas = 0;private $marge_gauche = 0;private $marge_droite = 0;
	private $couleur_fond = null;private $motif_fond = null;private $papierpeint_fond = null;
	private $bordure = null;private $type_bordure = null;
	private $titre_bandeau = null;private $style_titre_bandeau = null;

	public function __construct($nom) {$this->nom = $nom;}

	// Manipulateurs
	public function set_marge_haut($param) {$this->marge_haut = (int) $param;}
	public function set_marge_bas($param) {$this->marge_bas = (int) $param;}
	public function set_marge_gauche($param) {$this->marge_gauche = (int) $param;}
	public function set_marge_droite($param) {$this->marge_droite = (int) $param;}
	public function set_couleur_fond($param) {$this->couleur_fond = $param;}
	public function set_motif_fond($param) {$this->motif_fond = $param;}
	public function set_papierpeint_fond($param) {$this->papierpeint_fond = $param;}
	public function set_bordure($param) {$this->bordure = $param;}
	public function set_type_bordure($param) {$this->type_bordure = $param;}
	public function set_titre_bandeau($param) {$this->titre_bandeau = $param;}
	public function set_style_titre_bandeau($param) {$this->style_titre_bandeau = $param;}

	// Accesseurs
	public function get_nom() {return $this->nom;}
	public function get_marge_haut() {return $this->marge_haut;}
	public function get_marge_bas() {return $this->marge_bas;}
	public function get_marge_gauche() {return $this->marge_gauche;}
	public function get_marge_droite() {return $this->marge_droite;}
	public function get_couleur_fond() {return $this->couleur_fond;}
	public function get_motif_fond() {return $this->motif_fond;}
	public function get_papierpeint_fond() {return $this->papierpeint_fond;}
	public function get_bordure() {return $this->bordure;}
	public function get_type_bordure() {return $this->type_bordure;}
	public function get_titre_bandeau() {return $this->titre_bandeau;}
	public function get_style_titre_bandeau() {return $this->style_titre_bandeau;}
}

class style_texte {
	// Propriétés
	private $police = null;
	private $couleur = null;private $couleur_lien = null;private $couleur_survol = null;
	private $taille = 0;private $alignement = null;private $decoration = null;

	// Manipulateurs
	public function set_police($param) {$this->police = $param;}
	public function set_couleur($param) {$this->couleur = $param;}
	public function set_couleur_lien($param) {$this->couleur_lien = $param;}
	public function set_couleur_survol($param) {$this->couleur_survol = $param;}
	public function set_taille($param) {$this->taille = (float) $param;}
	public function set_alignement($param) {$this->alignement = $this->normaliser_alignement($param);}
	public function set_decoration($param) {$this->decoration = $this->normaliser_decoration($param);}

	// Accesseurs
	public function get_police() {return $this->police;}
	public function get_couleur() {return $this->couleur;}
	public function get_couleur_lien() {return $this->couleur_lien;}
	public function get_couleur_survol() {return $this->couleur_survol;}
	public function get_taille() {return $this->taille;}
	public function get_alignement() {return $this->alignement;}
	public function get_decoration() {return $this->decoration;}
	
	private function normaliser_alignement($param) {
		$ret = $param;
		if (strlen($ret) > 0) {
			$ret = trim(strtolower($ret));
			if ((strcmp($ret, _STYLE_ATTR_ALIGNEMENT_GAUCHE)) && (strcmp($ret, _STYLE_ATTR_ALIGNEMENT_DROITE))  && (strcmp($ret, _STYLE_ATTR_ALIGNEMENT_JUSTIFIE))) {
				$ret = _STYLE_ATTR_ALIGNEMENT_CENTRE;
			}
		}
		return $ret;
	}
	private function normaliser_decoration($param) {
		$param = trim(strtolower($param));
		$ret = ((strcmp($param, _STYLE_ATTR_DECORATION_GRAS)) && (strcmp($param, _STYLE_ATTR_DECORATION_ITALIQUE)))?null:$param;
		return $ret;
	}
}

class style_formulaire {
	// Propriétés
	private $couleur_texte_champ = null;
	private $couleur_fond_champ = null;
	private $couleur_fond_saisie = null;
	private $couleur_texte_bouton = null;
	private $couleur_fond_bouton = null;
	private $couleur_texte_statut = null;

	// Manipulateurs
	public function set_couleur_texte_champ($param) {$this->couleur_texte_champ = $param;}
	public function set_couleur_fond_champ($param) {$this->couleur_fond_champ = $param;}
	public function set_couleur_fond_saisie($param) {$this->couleur_fond_saisie = $param;}
	public function set_couleur_texte_bouton($param) {$this->couleur_texte_bouton = $param;}
	public function set_couleur_fond_bouton($param) {$this->couleur_fond_bouton = $param;}
	public function set_couleur_texte_statut($param) {$this->couleur_texte_statut = $param;}
	// Accesseurs
	public function get_couleur_texte_champ() {return $this->couleur_texte_champ;}
	public function get_couleur_fond_champ() {return $this->couleur_fond_champ;}
	public function get_couleur_fond_saisie() {return $this->couleur_fond_saisie;}
	public function get_couleur_texte_bouton() {return $this->couleur_texte_bouton;}
	public function get_couleur_fond_bouton() {return $this->couleur_fond_bouton;}
	public function get_couleur_texte_statut() {return $this->couleur_texte_statut;}
}

class style_actu {
	// Propriétés
	private $marge_gauche_titre = null;private $marge_haut_titre = null;
	private $couleur_titre = null;private $fond_titre = null;
	private $marge_gauche_sous_titre = null;private $marge_haut_sous_titre = null;
	private $couleur_sous_titre = null;private $fond_sous_titre = null;
	private $marge_gauche_resume = null;private $marge_haut_resume = null;
	private $couleur_resume = null;private $fond_resume = null;
	
	// Manipulateurs
	public function set_marge_gauche_titre($param) {$this->marge_gauche_titre = $param;}
	public function set_marge_haut_titre($param) {$this->marge_haut_titre = $param;}
	public function set_couleur_titre($param) {$this->couleur_titre = $param;}
	public function set_fond_titre($param) {$this->fond_titre = $param;}
	public function set_marge_gauche_sous_titre($param) {$this->marge_gauche_sous_titre = $param;}
	public function set_marge_haut_sous_titre($param) {$this->marge_haut_sous_titre = $param;}
	public function set_couleur_sous_titre($param) {$this->couleur_sous_titre = $param;}
	public function set_fond_sous_titre($param) {$this->fond_sous_titre = $param;}
	public function set_marge_gauche_resume($param) {$this->marge_gauche_resume = $param;}
	public function set_marge_haut_resume($param) {$this->marge_haut_resume = $param;}
	public function set_couleur_resume($param) {$this->couleur_resume = $param;}
	public function set_fond_resume($param) {$this->fond_resume = $param;}

	// Accesseurs
	public function get_marge_gauche_titre() {return $this->marge_gauche_titre;}
	public function get_marge_haut_titre() {return $this->marge_haut_titre;}
	public function get_couleur_titre() {return $this->couleur_titre;}
	public function get_fond_titre() {return $this->fond_titre;}
	public function get_marge_gauche_sous_titre() {return $this->marge_gauche_sous_titre;}
	public function get_marge_haut_sous_titre() {return $this->marge_haut_sous_titre;}
	public function get_couleur_sous_titre() {return $this->couleur_sous_titre;}
	public function get_fond_sous_titre() {return $this->fond_sous_titre;}
	public function get_marge_gauche_resume() {return $this->marge_gauche_resume;}
	public function get_marge_haut_resume() {return $this->marge_haut_resume;}
	public function get_couleur_resume() {return $this->couleur_resume;}
	public function get_fond_resume() {return $this->fond_resume;}
}

class xml_style {
	// Propriétés
	private $styles_contenus = array();
	private $styles_blocs = array();
	private $styles_textes = array();
	private $styles_formulaires = array();
	private $styles_actus = array();

	// Méthodes publiques
	public function ouvrir($nom) {
		$xml_style = new xml_struct();
		$ret = $xml_style->ouvrir($nom);
		if ($ret) {
			// Traitement des styles de bloc
			$nb_styles = $xml_style->compter_elements(_STYLE_CONTENU);
			$xml_style->pointer_sur_balise(_STYLE_CONTENU);
			for ($cpt = 0;$cpt < $nb_styles; $cpt++) {
				$nom = $xml_style->lire_n_attribut(_STYLE_CONTENU_ATTR_NOM, $cpt);
				if (strlen($nom) > 0) {
					$marge_haut = $xml_style->lire_n_valeur(_STYLE_CONTENU_MARGE_HAUT, $cpt);
					$marge_bas = $xml_style->lire_n_valeur(_STYLE_CONTENU_MARGE_BAS, $cpt);
					$couleur_fond = $xml_style->lire_n_valeur(_STYLE_CONTENU_COULEUR_FOND, $cpt);
					$motif_fond = $xml_style->lire_n_valeur(_STYLE_CONTENU_MOTIF_FOND, $cpt);
					$papierpeint_fond = $xml_style->lire_n_valeur(_STYLE_CONTENU_PAPIERPEINT_FOND, $cpt);
					$type_special = $xml_style->lire_n_valeur(_STYLE_CONTENU_TYPE_SPECIAL, $cpt);
					// Création du style de bloc
					$style = new style_contenu($nom);
					$style->set_marge_haut($marge_haut);
					$style->set_marge_bas($marge_bas);
					$style->set_couleur_fond($couleur_fond);
					$style->set_motif_fond($motif_fond);
					$style->set_papierpeint_fond($papierpeint_fond);
					$style->set_type_special($type_special);
					$this->styles_contenus[$nom] = $style;
				}
			}
			// Traitement des styles de bloc
			$xml_style->pointer_sur_origine();
			$nb_styles = $xml_style->compter_elements(_STYLE_BLOC);
			$xml_style->pointer_sur_balise(_STYLE_BLOC);
			for ($cpt = 0;$cpt < $nb_styles; $cpt++) {
				$nom = $xml_style->lire_n_attribut(_STYLE_BLOC_ATTR_NOM, $cpt);
				if (strlen($nom) > 0) {
					$marge_haut = $xml_style->lire_n_valeur(_STYLE_BLOC_MARGE_HAUT, $cpt);
					$marge_bas = $xml_style->lire_n_valeur(_STYLE_BLOC_MARGE_BAS, $cpt);
					$marge_gauche = $xml_style->lire_n_valeur(_STYLE_BLOC_MARGE_GAUCHE, $cpt);
					$marge_droite = $xml_style->lire_n_valeur(_STYLE_BLOC_MARGE_DROITE, $cpt);
					$couleur_fond = $xml_style->lire_n_valeur(_STYLE_BLOC_COULEUR_FOND, $cpt);
					$motif_fond = $xml_style->lire_n_valeur(_STYLE_BLOC_MOTIF_FOND, $cpt);
					$papierpeint_fond = $xml_style->lire_n_valeur(_STYLE_BLOC_PAPIERPEINT_FOND, $cpt);
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
					// Création du style de bloc
					$style = new style_bloc($nom);
					$style->set_marge_haut($marge_haut);
					$style->set_marge_bas($marge_bas);
					$style->set_marge_gauche($marge_gauche);
					$style->set_marge_droite($marge_droite);
					$style->set_couleur_fond($couleur_fond);
					$style->set_motif_fond($motif_fond);
					$style->set_papierpeint_fond($papierpeint_fond);
					$style->set_bordure($bordure);
					$style->set_type_bordure($type_bordure);
					$this->styles_blocs[$nom] = $style;
				}
			}
			// Traitement des styles de texte
			$xml_style->pointer_sur_origine();
			$nb_styles = $xml_style->compter_elements(_STYLE_TEXTE);
			$xml_style->pointer_sur_balise(_STYLE_TEXTE);
			for ($cpt = 0;$cpt < $nb_styles; $cpt++) {
				$nom = $xml_style->lire_n_attribut(_STYLE_TEXTE_ATTR_NOM, $cpt);
				if (strlen($nom) > 0) {
					$police = $xml_style->lire_n_valeur(_STYLE_TEXTE_POLICE, $cpt);
					$couleur = $xml_style->lire_n_valeur(_STYLE_TEXTE_COULEUR, $cpt);
					$couleur_lien = $xml_style->lire_n_valeur(_STYLE_TEXTE_COULEUR_LIEN, $cpt);
					$couleur_survol = $xml_style->lire_n_valeur(_STYLE_TEXTE_COULEUR_SURVOL, $cpt);
					$taille = $xml_style->lire_n_valeur(_STYLE_TEXTE_TAILLE, $cpt);
					$alignement = $xml_style->lire_n_valeur(_STYLE_TEXTE_ALIGNEMENT, $cpt);
					$decoration = $xml_style->lire_n_valeur(_STYLE_TEXTE_DECORATION, $cpt);
					// Création du style de texte
					$style = new style_texte();
					$style->set_police($police);
					$style->set_couleur($couleur);
					$style->set_couleur_lien($couleur_lien);
					$style->set_couleur_survol($couleur_survol);
					$style->set_taille($taille);
					$style->set_alignement($alignement);
					$style->set_decoration($decoration);
					$this->styles_textes[$nom] = $style;
				}
			}
			// Traitement des styles de formulaire
			$xml_style->pointer_sur_origine();
			$nb_styles = $xml_style->compter_elements(_STYLE_FORMULAIRE);
			$xml_style->pointer_sur_balise(_STYLE_FORMULAIRE);
			for ($cpt = 0;$cpt < $nb_styles; $cpt++) {
				$nom = $xml_style->lire_n_attribut(_STYLE_FORMULAIRE_ATTR_NOM, $cpt);
				if (strlen($nom) > 0) {
					$couleur_texte_champ = $xml_style->lire_n_valeur(_STYLE_FORMULAIRE_TEXTE_CHAMP, $cpt);
					$couleur_fond_champ = $xml_style->lire_n_valeur(_STYLE_FORMULAIRE_FOND_CHAMP, $cpt);
					$couleur_fond_saisie = $xml_style->lire_n_valeur(_STYLE_FORMULAIRE_FOND_SAISIE, $cpt);
					$couleur_texte_bouton = $xml_style->lire_n_valeur(_STYLE_FORMULAIRE_TEXTE_BOUTON, $cpt);
					$couleur_fond_bouton = $xml_style->lire_n_valeur(_STYLE_FORMULAIRE_FOND_BOUTON, $cpt);
					$couleur_texte_statut = $xml_style->lire_n_valeur(_STYLE_FORMULAIRE_TEXTE_STATUT, $cpt);
					// Création du style de formulaire
					$style = new style_formulaire();
					$style->set_couleur_texte_champ($couleur_texte_champ);
					$style->set_couleur_fond_champ($couleur_fond_champ);
					$style->set_couleur_fond_saisie($couleur_fond_saisie);
					$style->set_couleur_texte_bouton($couleur_texte_bouton);
					$style->set_couleur_fond_bouton($couleur_fond_bouton);
					$style->set_couleur_texte_statut($couleur_texte_statut);
					$this->styles_formulaires[$nom] = $style;
				}
			}
			// Traitement des styles d'actualité
			$xml_style->pointer_sur_origine();
			$nb_styles = $xml_style->compter_elements(_STYLE_ACTUALITE);
			$xml_style->pointer_sur_balise(_STYLE_ACTUALITE);
			for ($cpt = 0;$cpt < $nb_styles; $cpt++) {
				$nom = $xml_style->lire_n_attribut(_STYLE_ACTUALITE_ATTR_NOM, $cpt);
				if (strlen($nom) > 0) {
					$marge_gauche_titre = $xml_style->lire_n_valeur(_STYLE_ACTUALITE_GAUCHE_TITRE, $cpt);
					$marge_haut_titre = $xml_style->lire_n_valeur(_STYLE_ACTUALITE_HAUT_TITRE, $cpt);
					$couleur_titre = $xml_style->lire_n_valeur(_STYLE_ACTUALITE_COULEUR_TITRE, $cpt);
					$fond_titre = $xml_style->lire_n_valeur(_STYLE_ACTUALITE_FOND_TITRE, $cpt);
					$marge_gauche_sous_titre = $xml_style->lire_n_valeur(_STYLE_ACTUALITE_GAUCHE_STITRE, $cpt);
					$marge_haut_sous_titre = $xml_style->lire_n_valeur(_STYLE_ACTUALITE_HAUT_STITRE, $cpt);
					$couleur_sous_titre = $xml_style->lire_n_valeur(_STYLE_ACTUALITE_COULEUR_STITRE, $cpt);
					$fond_sous_titre = $xml_style->lire_n_valeur(_STYLE_ACTUALITE_FOND_STITRE, $cpt);
					$marge_gauche_resume = $xml_style->lire_n_valeur(_STYLE_ACTUALITE_GAUCHE_RESUME, $cpt);
					$marge_haut_resume = $xml_style->lire_n_valeur(_STYLE_ACTUALITE_HAUT_RESUME, $cpt);
					$couleur_resume = $xml_style->lire_n_valeur(_STYLE_ACTUALITE_COULEUR_RESUME, $cpt);
					$fond_resume = $xml_style->lire_n_valeur(_STYLE_ACTUALITE_FOND_RESUME, $cpt);
					// Création du style d'actualité
					$style = new style_actu();
					$style->set_marge_gauche_titre($marge_gauche_titre);
					$style->set_marge_haut_titre($marge_haut_titre);
					$style->set_couleur_titre($couleur_titre);
					$style->set_fond_titre($fond_titre);
					$style->set_marge_gauche_sous_titre($marge_gauche_sous_titre);
					$style->set_marge_haut_sous_titre($marge_haut_sous_titre);
					$style->set_couleur_sous_titre($couleur_sous_titre);
					$style->set_fond_sous_titre($fond_sous_titre);
					$style->set_marge_gauche_resume($marge_gauche_resume);
					$style->set_marge_haut_resume($marge_haut_resume);
					$style->set_couleur_resume($couleur_resume);
					$style->set_fond_resume($fond_resume);
					$this->styles_actus[$nom] = $style;
				}
			}
		}

		return $ret;
	}

	public function get_style_contenu($nom) {return (isset($this->styles_contenus[$nom])?$this->styles_contenus[$nom]:null);}
	public function get_style_bloc($nom) {return $this->styles_blocs[$nom];}
	public function get_style_texte($nom) {return $this->styles_textes[$nom];}
	public function get_style_formulaire($nom) {return $this->styles_formulaires[$nom];}
	public function get_style_actu($nom) {return $this->styles_actus[$nom];}

	public function extraire_css() {
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
					case _STYLE_ATTR_TYPE_BORDURE_COULEUR :
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
			if (!(strcmp($param, _STYLE_ATTR_ALIGNEMENT_GAUCHE))) {
				$ret = "text-align:left;";
			}
			elseif (!(strcmp($param, _STYLE_ATTR_ALIGNEMENT_DROITE))) {
				$ret = "text-align:right;";
			}
			elseif (!(strcmp($param, _STYLE_ATTR_ALIGNEMENT_JUSTIFIE))) {
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