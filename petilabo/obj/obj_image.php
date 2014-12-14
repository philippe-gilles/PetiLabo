<?php

class obj_legende_image extends obj_editable {
	private $obj_texte = null;
	private $id_texte = null;
	private $est_exterieur = false;
	private $style = "rscaption";
	private $niveau = 0;
	private $url_lien = null;
	private $touche_lien = null;
	
	public function __construct(&$obj_texte, $id_texte, $url_lien, $touche_lien) {
		$this->obj_texte = $obj_texte;
		$this->id_texte = $id_texte;
		$this->url_lien = $url_lien;
		$this->touche_lien = $touche_lien;
	}

	public function afficher($mode, $langue) {
		if (strcmp($mode, _PETILABO_MODE_EDIT)) {
			$legende = $this->obj_texte->get_texte($this->id_texte, $langue);
			if (strlen($legende) > 0) {
				echo "<div class=\"cadre_legende "._CSS_PREFIXE_INTERIEUR.$this->style." transparence_80pc\">"._HTML_FIN_LIGNE;
				echo "<table class=\"tableau_legende\"><tr><td>";
				if (strlen($this->url_lien) > 0) {
					$a_html = ($this->fabriquer_html_lien($mode, $this->url_lien, $this->touche_lien))._HTML_FIN_LIGNE;
					printf($a_html, $legende);
				}
				else {
					$balise = ($this->niveau < 1)?"p":"h".$this->niveau;
					echo "<".$balise.">".$legende."</".$balise.">"._HTML_FIN_LIGNE;
				}
				echo "</td></tr></table>"._HTML_FIN_LIGNE;
				echo "</div>"._HTML_FIN_LIGNE;
			}
		}
		else {
			$legende = $this->check_texte($this->obj_texte, $this->id_texte, $langue);
			$this->ouvrir_ligne();
			$this->ecrire_cellule_symbole_texte($this->id_texte, _EDIT_SYMBOLE_LEGENDE, "Modifier la légende de l'image");
			$this->ecrire_cellule_texte($this->id_texte, $legende);
			$this->fermer_ligne();
		}
	}

	public function set_est_exterieur($param) {$this->est_exterieur = $param;}
	public function set_style($param) {$this->style = $param;}
	public function set_niveau($param) {$this->niveau = $param;}
	public function get_est_exterieur() {return $this->est_exterieur;}
	public function get_style() {return $this->style;}
	public function get_niveau() {return $this->niveau;}
}

class obj_image extends obj_editable {
	private $obj_media = null;
	private $obj_style = null;
	private $obj_texte = null;
	private $obj_legende = null;
	private $id_alt = null;
	private $id_info = null;
	private $id_copyright = null;
	private $url_lien = null;
	private $touche_lien = null;

	public function __construct(&$obj_media, &$obj_style, &$obj_texte, $url_lien, $touche_lien) {
		$this->obj_media = $obj_media;
		$this->obj_style = $obj_style;
		$this->obj_texte = $obj_texte;
		
		$this->id_alt = $obj_media->get_alt();
		$this->id_copyright = $obj_media->get_copyright();
		$this->url_lien = $url_lien;
		$this->touche_lien = $touche_lien;

		$this->id_info = $this->obj_media->get_legende();
		if (strlen($this->id_info) > 0) {
			$this->obj_legende = new obj_legende_image($this->obj_texte, $this->id_info, $url_lien, $touche_lien);
			if (!($this->obj_style)) {return;}
			$this->obj_legende->set_niveau($this->obj_style->get_niveau_titre());
			$this->obj_legende->set_est_exterieur($this->obj_style->get_est_exterieur());
			$nom_style = $this->obj_style->get_nom();
			$survol = $this->obj_style->get_survol();
			if ($survol) {$nom_style .= " "._CSS_CLASSE_SURVOL;}
			$style_texte = $this->obj_style->get_style_texte();
			if (strlen($style_texte) > 0) {$nom_style .= " "._CSS_PREFIXE_TEXTE.$style_texte;}
			$this->obj_legende->set_style($nom_style);
		}
	}
	
	public function get_est_vide() {
		return ($this->obj_media->get_est_vide());
	}

	public function afficher($mode, $langue, $diaporama = false) {
		if (!(strcmp($mode, _PETILABO_MODE_SITE))) {
			$this->afficher_site($langue, $diaporama);
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_ADMIN))) {
			$this->afficher_admin($langue, $diaporama);
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_EDIT))) {
			$this->afficher_edit($langue);
		}
	}

	public function afficher_brut($mode, $langue, $classe = null, $style_inline = null) {
		$alt = " alt=\"".$this->obj_texte->get_texte($this->id_alt, $langue)."\"";
		$src = " src=\"".$this->obj_media->get_src()."\"";
		$class = (strlen($classe) > 0)?" class=\"".$classe."\"":"";
		$style = (strlen($style_inline) > 0)?" style=\"".$style_inline."\"":"";
		echo "<img ".$class.$src.$alt.$style."/>"._HTML_FIN_LIGNE;
	}
	
	public function afficher_reduit($mode, $langue, $lien = true, $classe = null, $style_inline = null) {
		$href = " href=\"".$this->obj_media->get_src()."\"";
		$info = (strlen($this->id_info) > 0)?" title=\"".$this->obj_texte->get_texte($this->id_info, $langue)."\"":"";		
		$alt = " alt=\"".$this->obj_texte->get_texte($this->id_alt, $langue)."\"";
		$src = " src=\"".$this->obj_media->get_src_reduite()."\"";
		$class = (strlen($classe) > 0)?" class=\"".$classe."\"":"";
		$style = (strlen($style_inline) > 0)?" style=\"".$style_inline."\"":"";
		if ($lien) {echo "<a ".$href.$info."/>"._HTML_FIN_LIGNE;}
		echo "<img ".$class.$src.$alt.$info.$style."/>"._HTML_FIN_LIGNE;
		if ($lien) {echo "</a>"._HTML_FIN_LIGNE;}
	}

	public function afficher_vide($langue, $classe = null, $style_inline = null) {
		$alt = " alt=\"".$this->obj_texte->get_texte($this->id_alt, $langue)."\"";
		$src = " src=\"./images/"._ADMIN_IMAGE_VIDE."\"";
		$class = (strlen($classe) > 0)?" class=\"".$classe."\"":"";
		$style = (strlen($style_inline) > 0)?" style=\"".$style_inline."\"":"";
		echo "<img ".$class.$src.$alt.$style."/>"._HTML_FIN_LIGNE;
	}
	
	public function afficher_complet($mode, $langue, $version, $class = null, $diaporama = false) {
		$src = (strlen($version) > 0)?($this->obj_media->get_src())."?v=".$version:($this->obj_media->get_src());
		$alt = $this->obj_texte->get_texte($this->id_alt, $langue);
		$style = "max-width:".$this->obj_media->get_width()."px;max-height:".$this->obj_media->get_height()."px";
		if ($this->obj_legende) {
			$a_html = ($this->fabriquer_html_lien($mode, $this->url_lien, $this->touche_lien, "legende_avec_lien"))._HTML_FIN_LIGNE;
			if ($this->obj_legende->get_est_exterieur()) {
				$style_exterieur = str_replace(_CSS_CLASSE_SURVOL, "", $this->obj_legende->get_style());
				echo "<div class=\"image_cadre\">"._HTML_FIN_LIGNE;
				echo "<div class=\""._CSS_PREFIXE_EXTERIEUR.$style_exterieur."\" style=\"".$style."\">"._HTML_FIN_LIGNE;
				printf($a_html, "<img class=\"image_dans_cadre\" src=\"".$src."\" alt=\"".$alt."\" />");
				$this->obj_legende->afficher($mode, $langue);
				echo "</div></div>"._HTML_FIN_LIGNE;
			}
			else {
				if ($diaporama) {
					printf($a_html, "<img src=\"".$src."\" alt=\"".$alt."\" />");
					$this->obj_legende->afficher($mode, $langue);
				}
				else {
					echo "<div class=\"image_cadre\" style=\"".$style."\">"._HTML_FIN_LIGNE;
					printf($a_html, "<img class=\"image_dans_cadre\" src=\"".$src."\" alt=\"".$alt."\" />");
					$this->obj_legende->afficher($mode, $langue);
					echo "</div>"._HTML_FIN_LIGNE;
				}
			}
		}
		else {
			$a_html = ($this->fabriquer_html_lien($mode, $this->url_lien, $this->touche_lien))._HTML_FIN_LIGNE;
			printf($a_html, "<img class=\"image_cadre\" style=\"".$style."\" src=\"".$src."\" alt=\"".$alt."\" />");
		}
	}
	
	protected function afficher_site($langue, $diaporama) {
		if (!($this->get_est_vide())) {$this->afficher_complet(_PETILABO_MODE_SITE, $langue, null, null, $diaporama);}
	}
	
	protected function afficher_admin($langue, $diaporama) {
		if ($this->get_est_vide()) {$this->afficher_vide($langue);}
		else {$this->afficher_complet(_PETILABO_MODE_ADMIN, $langue, uniqid(), null, $diaporama);}
	}
	
	protected function afficher_edit($langue) {
		$src = $this->obj_media->get_src();
		if (strlen($src) == 0) {return;}
		$this->ouvrir_tableau_simple();
		if ($this->get_est_vide()) {
			$this->ouvrir_ligne();
			$this->ecrire_cellule_categorie(_EDIT_LABEL_IMAGE, _EDIT_COULEUR, 1);
			$this->ecrire_cellule_symbole_image($this->obj_media->get_nom(), _EDIT_SYMBOLE_IMAGE);
			$this->ecrire_cellule_image();
			$this->fermer_ligne();
		}
		else {
			$nb_lignes = ($this->obj_legende)?4:3;
			$this->ouvrir_ligne();
			$this->ecrire_cellule_categorie(_EDIT_LABEL_IMAGE, _EDIT_COULEUR, $nb_lignes);
			$this->ecrire_cellule_symbole_image($this->obj_media->get_nom(), _EDIT_SYMBOLE_IMAGE);
			$this->ecrire_cellule_image($src);
			$this->fermer_ligne();
			$alt = $this->check_texte($this->obj_texte, $this->id_alt, $langue);
			$this->ouvrir_ligne();
			$this->ecrire_cellule_symbole_texte_brut($this->id_alt, _EDIT_SYMBOLE_ALT, "Modifier le texte alternatif de l'image");
			$this->ecrire_cellule_texte($this->id_alt, $alt);
			$this->fermer_ligne();
			$copyright = $this->check_texte($this->obj_texte, $this->id_copyright, $langue);
			$this->ouvrir_ligne();
			$this->ecrire_cellule_symbole_texte_simple(_EDIT_TYPE_COPY, $this->id_copyright, _EDIT_SYMBOLE_COPY, "Modifier le copyright de l'image");
			$this->ecrire_cellule_texte($this->id_copyright, $copyright);
			$this->fermer_ligne();
			if ($this->obj_legende) {
				$this->obj_legende->set_id_tab($this->id_tab);
				$this->obj_legende->afficher(_PETILABO_MODE_EDIT, $langue);
			}
		}
		$this->fermer_tableau();
	}
}