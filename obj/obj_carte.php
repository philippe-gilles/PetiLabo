<?php

define("_CARTE_TEMPLATE_SRC", "http://maps.googleapis.com/maps/api/staticmap?center=%s&markers=%s&language=%s&zoom=14&size=600x400&sensor=false");
define("_CARTE_TEMPLATE_REF", "http://maps.google.com/?q=%s");
define("_CARTE_PREFIXE", "carte_");
define("_CARTE_SUFFIXE", ".png");
	
class obj_carte extends obj_editable {
	private $obj_texte = null;
	private $id_texte = null;

	public function __construct(&$obj_texte, $id_texte) {
		$this->id_texte = $id_texte;
		$this->obj_texte = $obj_texte;
	}

	public function afficher($mode, $langue) {
		if (!(strcmp($mode, _PETILABO_MODE_SITE))) {
			$texte = $this->obj_texte->get_texte($this->id_texte, $langue);
			$lien_carte = $this->get_ref_carte($texte);
			$src_carte = $this->get_src_carte($texte, $langue);
			echo "<div><a href=\"".$lien_carte."\" title=\"Google Maps\" target=\"_blank\">"._HTML_FIN_LIGNE;
			echo "<img class=\"image_cadre image_plan\" src=\"".$src_carte."\" alt=\"".$texte."\" />";
			echo "</a></div>"._HTML_FIN_LIGNE;
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_ADMIN))) {
			$texte = $this->obj_texte->get_texte($this->id_texte, $this->obj_texte->get_langue_par_defaut());
			$src_carte = $this->get_src_carte_distante($texte, $langue);
			$this->reinit_carte();
			echo "<div>"._HTML_FIN_LIGNE;
			echo "<img class=\"image_cadre image_plan\" src=\"".$src_carte."\" alt=\"".$texte."\" />";
			echo "</div>"._HTML_FIN_LIGNE;
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_EDIT))) {
			$texte = $this->check_texte($this->obj_texte, $this->id_texte, $this->obj_texte->get_langue_par_defaut());
			$this->ouvrir_tableau_simple();
			$this->ouvrir_ligne();
			$this->ecrire_cellule_categorie(_EDIT_LABEL_PLAN, _EDIT_COULEUR, 1);
			$this->ecrire_cellule_symbole_texte_simple(_EDIT_TYPE_PLAN, $this->id_texte, _EDIT_SYMBOLE_PLAN, "Modifier l'adresse du plan");
			$this->ecrire_cellule_texte($this->id_texte, $texte);
			$this->fermer_ligne();
			$this->fermer_tableau();
		}
	}

	private function get_src_carte($texte, $langue) {
		// TODO : Gérer les différentes langues (pour le moment : la carte est créée dans la langue du première accès)
		$carte_locale = $this->get_src_carte_locale();
		// Si la carte n'a pas été copiée en local on effectue cette copie (économise le compteur Google)
		if (!(@file_exists($carte_locale))) {
			$carte_distante = $this->get_src_carte_distante($texte, $langue);
			$this->copier_carte($carte_distante, $carte_locale);
		}
		return $carte_locale;
	}
	private function get_src_carte_locale() {
		$src = _XML_PATH_IMAGES_SITE._CARTE_PREFIXE.$this->id_texte._CARTE_SUFFIXE;
		return $src;
	}
	private function get_src_carte_distante($texte, $langue) {
		$src = sprintf(_CARTE_TEMPLATE_SRC, urlencode($texte), urlencode($texte), $langue);
		return $src;
	}
	private function get_ref_carte($texte) {
		$ref_carte = sprintf(_CARTE_TEMPLATE_REF, urlencode($texte));
		return $ref_carte;
	}
	private function reinit_carte() {
		$carte_locale = $this->get_src_carte_locale();
		@unlink($carte_locale);
	}
	private function copier_carte($carte_distante, $carte_locale) {
		$in = @fopen($carte_distante, "rb");$out = @fopen($carte_locale, "wb");
		while ($chunk = @fread($in,8192)) {@fwrite($out, $chunk, 8192);}
		@fclose($in);@fclose($out);
	}
}