<?php

class style_menu extends xml_abstract {
	public function __construct() {
		$this->enregistrer_chaine("style_texte", null, _MENU_STYLE_TEXTE);
		$this->enregistrer_chaine("fond", null, _MENU_STYLE_FOND);
		$this->enregistrer_chaine("couleur_survol", null, _MENU_STYLE_COULEUR_SURVOL);
		$this->enregistrer_chaine("fond_survol", null, _MENU_STYLE_FOND_SURVOL);
		$this->enregistrer_flottant("espace_vertical", 0, _MENU_STYLE_ESPACE_VERTICAL);
		$this->enregistrer_flottant("espace_horizontal", 0, _MENU_STYLE_ESPACE_HORIZONTAL);
	}
}					
					
class item_menu extends xml_abstract {
	private $lien = null;
	
	public function __construct() {
		$this->enregistrer_chaine("label", null, _MENU_ITEM_LABEL);
		$this->enregistrer_chaine("icone", null, _MENU_ITEM_ICONE);
		$this->enregistrer_chaine("info", null, _MENU_ITEM_INFO);
		$this->enregistrer_chaine("lien_editable");
		$this->enregistrer_chaine("liste_cibles");
		$this->enregistrer_chaine("style");
	}

	public function set_lien($param) {
		$param_w3c = str_replace("&", "&amp;", $param);
		$this->lien = $param_w3c;
	}
	public function get_lien() {return $this->lien;}
	public function is_lien_editable() {
		$ret = (strlen($this->get_lien_editable()) > 0)?true:false;
		return $ret;
	}
}

class menu {
	private $liste_items = array();
	private $has_editable = false;
	
	function ajouter_item($nom_item, $is_editable) {
		$this->liste_items[] = $nom_item;
		if ($is_editable) {$this->has_editable = true;}
	}
	function get_nb_items() {return count($this->liste_items);}
	function get_has_editable() {return $this->has_editable;}
	function get_item($index) {return $this->liste_items[$index];}
}

class liste_cibles {
	private $liste_cibles = array();
	
	function ajouter_cible($lien, $nom_cible) {$this->liste_cibles[] = array($lien, $nom_cible);}
	function get_nb_cibles() {return count($this->liste_cibles);}
	function get_cible($index) {return $this->liste_cibles[$index];}

	function get_lien_cible($index) {
		$lien = null;
		$elt = $this->liste_cibles[$index];
		if (is_array($elt)) {
			$lien = $elt[0];
		}
		return $lien;
	}
	function get_valeur_cible($index) {
		$valeur = null;
		$elt = $this->liste_cibles[$index];
		if (is_array($elt)) {
			$valeur = $elt[1];
		}
		return $valeur;
	}
}

class xml_menu {
	// Propriétés
	private $styles = array();
	private $items = array();
	private $menus = array();
	private $listes_cibles = array();

	public function ouvrir($nom) {
		$xml_menu = new xml_struct();
		$ret = $xml_menu->ouvrir($nom);
		if ($ret) {
			// Traitement des styles
			$nb_styles = $xml_menu->compter_elements(_MENU_STYLE);
			$xml_menu->pointer_sur_balise(_MENU_STYLE);
			for ($cpt = 0;$cpt < $nb_styles; $cpt++) {
				$nom = $xml_menu->lire_n_attribut(_MENU_ATTR_STYLE_NOM, $cpt);
				if (strlen($nom) > 0) {
					$style = new style_menu();
					$style->load($xml_menu, $cpt);
					$this->styles[$nom] = $style;
				}
			}
			// Traitement des items
			$xml_menu->pointer_sur_origine();
			$nb_items = $xml_menu->compter_elements(_MENU_ITEM);
			$xml_menu->pointer_sur_balise(_MENU_ITEM);
			for ($cpt = 0;$cpt < $nb_items; $cpt++) {
				$nom = $xml_menu->lire_n_attribut(_MENU_ATTR_ITEM_NOM, $cpt);
				if (strlen($nom) > 0) {
					$item = new item_menu();
					$item->load($xml_menu, $cpt);
					$lien = $xml_menu->lire_n_valeur(_MENU_ITEM_LIEN, $cpt);
					$lien_editable = $xml_menu->lire_n_valeur(_MENU_ITEM_LIEN_EDITABLE, $cpt);
					// En cas de lien éditable on va lire l'éventuelle liste de cibles
					if (strlen($lien_editable) > 0) {
						$xml_menu->creer_repere($nom);
						$xml_menu->pointer_sur_index($cpt);
						$xml_menu->pointer_sur_balise(_MENU_ITEM_LIEN_EDITABLE);
						$nom_liste_cibles = $xml_menu->lire_attribut(_MENU_ATTR_ITEM_LIEN_EDITABLE_LISTE);
						$xml_menu->pointer_sur_repere($nom);
					}
					else {
						$nom_liste_cibles = null;
					}
					if (strlen($lien) > 0) {$item->set_lien($lien);}
					if (strlen($lien_editable) > 0) {
						$item->set_lien_editable($lien_editable);
						$item->set_liste_cibles($nom_liste_cibles);
					}
					$style = $xml_menu->lire_n_valeur(_MENU_ITEM_STYLE, $cpt);
					if (strlen($style) > 0) {
						if (array_key_exists($style, $this->styles)) {
							$item->set_style($style);
						}
					}
					$this->items[$nom] = $item;
				}
			}
			// Traitement des menus
			$xml_menu->pointer_sur_origine();
			$nb_menus = $xml_menu->compter_elements(_MENU_MENU);
			$xml_menu->pointer_sur_balise(_MENU_MENU);
			$xml_menu->creer_repere(_MENU_MENU);
			for ($cpt = 0;$cpt < $nb_menus; $cpt++) {
				$xml_menu->pointer_sur_repere(_MENU_MENU);
				$xml_menu->pointer_sur_index($cpt);
				$nom = (string) $xml_menu->lire_attribut(_MENU_ATTR_NOM);
				if (strlen($nom) > 0) {
					$nb_items = $xml_menu->compter_elements(_MENU_MENU_CHOIX);
					if ($nb_items > 0) {
						$menu = new menu();
						for ($cpt_item = 0; $cpt_item < $nb_items; $cpt_item++) {
							$valeur = $xml_menu->lire_valeur_n(_MENU_MENU_CHOIX, $cpt_item);
							$item = $this->get_item((string) $valeur);
							if ($item) {
								$menu->ajouter_item((string) $valeur, $item->is_lien_editable());
							}
						}
						$this->menus[$nom] = $menu;
					}
				}
			}
			// Traitement des listes de cibles pour liens éditables
			$xml_menu->pointer_sur_origine();
			$nb_liste_cibles = $xml_menu->compter_elements(_MENU_LISTE_CIBLES);
			$xml_menu->pointer_sur_balise(_MENU_LISTE_CIBLES);
			$xml_menu->creer_repere(_MENU_LISTE_CIBLES);
			for ($cpt = 0;$cpt < $nb_liste_cibles; $cpt++) {
				$xml_menu->pointer_sur_repere(_MENU_LISTE_CIBLES);
				$xml_menu->pointer_sur_index($cpt);
				$nom = (string) $xml_menu->lire_attribut(_MENU_ATTR_LISTE_CIBLES_NOM);
				if (strlen($nom) > 0) {
					$nb_cibles = $xml_menu->compter_elements(_MENU_LISTE_CIBLES_CIBLE);
					if ($nb_cibles > 0) {
						$liste_cibles = new liste_cibles();
						$repere = _MENU_LISTE_CIBLES."_".$nom;
						$xml_menu->creer_repere($repere);
						for ($cpt_cible = 0; $cpt_cible < $nb_cibles; $cpt_cible++) {
							$xml_menu->pointer_sur_repere($repere);
							$valeur = (string) $xml_menu->lire_valeur_n(_MENU_LISTE_CIBLES_CIBLE, $cpt_cible);
							$xml_menu->pointer_sur_balise_n(_MENU_LISTE_CIBLES_CIBLE, $cpt_cible);
							$lien = (string) $xml_menu->lire_attribut(_MENU_ATTR_LISTE_CIBLES_LIEN);
							$liste_cibles->ajouter_cible($lien, $valeur);
						}
						$this->listes_cibles[$nom] = $liste_cibles;
					}
				}
			}
		}
		return $ret;
	}
	function get_liste_cibles($nom) {
		$ret = null;
		if (array_key_exists($nom, $this->listes_cibles)) {
			$ret = $this->listes_cibles[$nom];
		}
		return $ret;
	}
	function get_menu($nom) {
		$ret = null;
		if (array_key_exists($nom, $this->menus)) {
			$ret = $this->menus[$nom];
		}
		return $ret;
	}
	function get_style($nom) {
		$ret = null;
		if (array_key_exists($nom, $this->styles)) {
			$ret = $this->styles[$nom];
		}
		return $ret;
	}
	function get_item($nom) {
		$ret = null;
		if (array_key_exists($nom, $this->items)) {
			$ret = $this->items[$nom];
		}
		return $ret;
	}
	public function extraire_css($hover=true) {
		$css = "";
		foreach ($this->styles as $nom_style => $style) {
			$couleur = $style->get_couleur();
			$fond = $style->get_fond();
			$couleur_survol = $style->get_couleur_survol();
			$fond_survol = $style->get_fond_survol();
			$espace_v = (float) $style->get_espace_vertical();
			$espace_h = (float) $style->get_espace_horizontal();
			$css .= "."._CSS_PREFIXE_MENU.$nom_style." {";
			if (strlen($couleur) > 0) {$css .= "color:".$couleur.";";}
			if (strlen($fond) > 0) {$css .= "background:".$fond.";";}
			$css .= "line-height:".(1+(float) $espace_v).";";
			$css .= "padding:0 ".((float) $espace_h)."em;";
			$css .= "}"._CSS_FIN_LIGNE;
			if (($hover) && ((strlen($couleur_survol) > 0) || (strlen($fond_survol) > 0))) {
				// Style pour hover
				$css .= "."._CSS_PREFIXE_MENU.$nom_style.":hover {";
				if (strlen($couleur_survol) > 0) {$css .= "color:".$couleur_survol.";";}
				if (strlen($fond_survol) > 0) {$css .= "background:".$fond_survol.";";}
				$css .= "}"._CSS_FIN_LIGNE;
				// Style pour lien actif
				$css .= "."._CSS_PREFIXE_MENU.$nom_style._MENU_STYLE_EXT_ACTIF." {";
				if (strlen($couleur_survol) > 0) {$css .= "color:".$couleur_survol.";";}
				if (strlen($fond_survol) > 0) {$css .= "background:".$fond_survol.";";}
				$css .= "line-height:".(1+(float) $espace_v).";";
				$css .= "padding:0 ".((float) $espace_h)."em;";
				$css .= "}"._CSS_FIN_LIGNE;
			}
		}
		return $css;
	}
}