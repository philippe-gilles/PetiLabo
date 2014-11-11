<?php

class obj_item extends obj_editable {
	private $obj_texte = null;
	private $obj_item = null;
	private $obj_style_item = null;
	private $id_label = null;
	private $id_icone = null;
	private $id_info = null;
	private $style = null;
	private $lien = null;
	private $cible = null;
	private $is_editable = false;
	private $id_liste = null;
	private $is_active = false;
	private $access_key = null;

	public function __construct(&$obj_texte, &$item, &$style_item, $id_label) {
		$this->obj_texte = $obj_texte;
		$this->obj_item = $item;
		$this->obj_style_item = $style_item;
		$this->id_label = $id_label;
		if ($item) {
			$this->style = $item->get_style();
			if ($style_item) {
				$style_texte = $style_item->get_style_texte();
				if (strlen($style_texte) > 0) {
					$pref_style = (strlen($id_label) > 0)?_CSS_PREFIXE_TEXTE:_CSS_PREFIXE_ICONE;
					$this->style = $pref_style.$style_texte." "._CSS_PREFIXE_MENU.$this->style;
				}
			}
		}
		$this->id_icone = $item->get_icone();
		$this->id_info = $item->get_info();
	}
	
	public function ajouter_lien($cible, $is_editable, $id_liste, $is_active, $access_key) {
		$this->cible = $cible;
		$this->is_editable = $is_editable;
		$this->id_liste = $id_liste;
		$this->is_active = $is_active;
		$this->access_key = $access_key;
		$this->lien = ($is_editable)?$this->obj_item->get_lien_editable():$this->obj_item->get_lien();
	}
	
	public function afficher($mode, $langue) {
		if (!(strcmp($mode, _PETILABO_MODE_SITE))) {
			$this->afficher_site($langue);
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_ADMIN))) {
			$this->afficher_admin($this->obj_texte->get_langue_par_defaut());
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_EDIT))) {
			$this->afficher_edit($this->obj_texte->get_langue_par_defaut());
		}
	}

	protected function afficher_site($langue) {
		$label = $this->obj_texte->get_texte($this->id_label, $langue);
		$icone = $this->obj_texte->get_texte($this->id_icone, $langue);
		if (strlen($this->cible) > 0) {
			if (strlen($icone) > 0) {
				$code_icone = _MENU_PREFIXE_ICONE.$icone._MENU_SUFFIXE_ICONE;
				if (strlen($label) > 0) {$label = "<span class=\"menu_icone_sur_label\">".$code_icone."</span><br/>".$label;}
				else {$label = $code_icone;}
			}
			$info = $this->obj_texte->get_texte($this->id_info, $langue);
			if ($this->is_active) {
				echo "<a class=\"item_menu ".$this->style._MENU_STYLE_EXT_ACTIF."\">".$label."</a>"._HTML_FIN_LIGNE;
			}
			else {
				$attr_access = (strlen($this->access_key)>0)?" accesskey=\"".$this->access_key."\"":"";
				$attr_target = $this->url_target($this->cible);
				echo "<a href=\"".$this->cible."\" class=\"item_menu ".$this->style."\" title=\"".$info."\"".$attr_access."".$attr_target.">".$label."</a>"._HTML_FIN_LIGNE;
			}
		}
	}
	
	protected function afficher_admin($langue) {
		$label = $this->obj_texte->get_texte($this->id_label, $langue);
		$icone = $this->obj_texte->get_texte($this->id_icone, $langue);
		if (strlen($icone) > 0) {
			$code_icone = _MENU_PREFIXE_ICONE.$icone._MENU_SUFFIXE_ICONE;
			if (strlen($label) > 0) {$label = "<span class=\"menu_icone_sur_label\">".$code_icone."</span><br/>".$label;}
			else {$label = $code_icone;}
		}
		echo "<a class=\"item_menu ".$this->style."\">".$label."</a>"._HTML_FIN_LIGNE;
	}
	protected function afficher_edit($langue) {
		$nb_lignes = (strlen($this->id_icone)>0)?2:0;
		$nb_lignes += (strlen($this->id_label)>0)?2:0;
		$nb_lignes += ($this->is_editable)?1:0;
		$this->ouvrir_tableau_simple();
		$this->ouvrir_ligne();
		$this->ecrire_cellule_categorie(_EDIT_LABEL_ITEM, _EDIT_COULEUR, $nb_lignes);
		if (strlen($this->id_icone)>0) {
			$trad_icone = $this->check_texte($this->obj_texte, $this->id_icone, $langue);
			$icone = _MENU_PREFIXE_ICONE.$trad_icone._MENU_SUFFIXE_ICONE;
			$this->ecrire_cellule_symbole_texte_simple(_EDIT_TYPE_ICONE, $this->id_icone, _EDIT_SYMBOLE_ICONE, "Modifier le code de l'icône");
			$this->ecrire_cellule_icone($icone);
			$this->fermer_ligne();
			$trad_info = $this->check_texte($this->obj_texte, $this->id_info, $langue);
			$this->ouvrir_ligne();
			$this->ecrire_cellule_symbole_texte_brut($this->id_info, _EDIT_SYMBOLE_INFO, "Modifier le texte de l'infobulle");
			$this->ecrire_cellule_texte($this->id_info, $trad_info);
			$this->fermer_ligne();
		}
		if (strlen($this->id_label)>0) {
			$trad_label = $this->check_texte($this->obj_texte, $this->id_label, $langue);
			$this->ouvrir_ligne();
			$this->ecrire_cellule_symbole_texte($this->id_label, _EDIT_SYMBOLE_LABEL, "Modifier le texte de l'item de menu");
			$this->ecrire_cellule_texte($this->id_label, $trad_label);
			$this->fermer_ligne();
		}
		if ($this->is_editable) {
			$trad_label = $this->check_texte($this->obj_texte, $this->lien, $langue);
			$this->ouvrir_ligne();
			$this->ecrire_cellule_symbole_lien_editable($this->lien, _EDIT_SYMBOLE_LIEN, "Modifier le lien de l'item de menu", $this->id_liste);
			$this->ecrire_cellule_texte($this->lien, $trad_label);
			$this->fermer_ligne();
		}
		$this->fermer_tableau();
	}
}

class obj_menu extends obj_editable {
	private $obj_texte = null;
	private $nom = null;
	private $alignement = null;
	private $tab_items = array();

	public function __construct(&$obj_texte, $nom, $alignement) {
		$this->obj_texte = $obj_texte;
		$this->nom = $nom;
		$this->alignement = $alignement;
	}
	
	public function ajouter_item(&$item, &$style_item, $id_label) {
		$obj = new obj_item($this->obj_texte, $item, $style_item, $id_label);
		if ($obj) {$this->tab_items[] = $obj;}
		return $obj;
	}

	public function afficher($mode, $langue, $nb_items_non_vides = 0) {
		if (!(strcmp($mode, _PETILABO_MODE_SITE))) {
			$this->afficher_site($langue, $nb_items_non_vides);
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_ADMIN))) {
			$this->afficher_admin($this->obj_texte->get_langue_par_defaut(), $nb_items_non_vides);
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_EDIT))) {
			$this->afficher_edit($this->obj_texte->get_langue_par_defaut());
		}
	}
	
	protected function afficher_site($langue, $nb_items_non_vides) {
		if ($nb_items_non_vides == 0) {return;}
		$classe = "wrap_menu";
		$classe .= " ".$this->extraire_classe_alignement($this->alignement);
		$this->ouvrir_balise_html5("nav");
		echo "<div class=\"".$classe."\">"._HTML_FIN_LIGNE;
		foreach ($this->tab_items as $obj_item) {
			$obj_item->afficher(_PETILABO_MODE_SITE, $langue);
		}
		echo "</div>"._HTML_FIN_LIGNE;
		$this->fermer_balise_html5("nav");
	}

	protected function afficher_admin($langue, $nb_items_non_vides) {
		$classe = "wrap_menu";
		$classe .= " ".$this->extraire_classe_alignement($this->alignement);
		echo "<div class=\"".$classe."\">"._HTML_FIN_LIGNE;
		foreach ($this->tab_items as $obj_item) {
			if ($obj_item) {$obj_item->afficher(_PETILABO_MODE_ADMIN, $langue);}
		}
		echo "</div>"._HTML_FIN_LIGNE;
	}

	protected function afficher_edit($langue) {
		$titre = $this->construire_etiquette(_EDIT_LABEL_MENU, $this->nom);
		$this->ouvrir_tableau_multiple($titre, _EDIT_COULEUR, $this->nom);
		foreach ($this->tab_items as $obj_item) {
			if ($obj_item) {
				$obj_item->set_id_tab($this->id_tab);
				$obj_item->afficher(_PETILABO_MODE_EDIT, $langue);
			}
		}
		$this->fermer_tableau();
	}
}