<?php

define("_CARTE_GOOGLE_PORTRAIT_SRC", "http://maps.googleapis.com/maps/api/staticmap?center=%s&markers=%s&language=%s&zoom=%d&size=400x600&sensor=false");
define("_CARTE_GOOGLE_CARRE_SRC", "http://maps.googleapis.com/maps/api/staticmap?center=%s&markers=%s&language=%s&zoom=%d&size=400x400&sensor=false");
define("_CARTE_GOOGLE_PAYSAGE_SRC", "http://maps.googleapis.com/maps/api/staticmap?center=%s&markers=%s&language=%s&zoom=%d&size=600x400&sensor=false");
define("_CARTE_GOOGLE_REF", "http://maps.google.com/?q=%s");

define("_CARTE_OSM_REF", "http://www.openstreetmap.org/#map=%d/%f/%f");

define("_CARTE_PREFIXE", "carte_");
define("_CARTE_SUFFIXE_GOOGLE", ".png");
define("_CARTE_SUFFIXE_OSM", ".jpg");
	
class obj_carte extends obj_editable {
	private $obj_texte = null;
	private $id_texte = null;
	private $source = null;
	private $niveau_zoom = 14;
	private $orientation = null;

	public function __construct(&$obj_texte, $id_texte, $source, $zoom, $orientation) {
		$this->id_texte = $id_texte;
		$this->obj_texte = $obj_texte;
		if ($zoom < 2) {$this->niveau_zoom = 10;}
		elseif ($zoom > 2) {$this->niveau_zoom = 16;}
		$this->orientation = $orientation;
		$this->source = $source;
	}

	public function afficher($mode, $langue) {
		if (!(strcmp($mode, _PETILABO_MODE_SITE))) {
			$texte = $this->obj_texte->get_texte($this->id_texte, $langue);
			$infobulle = $this->obj_texte->get_texte("trad_ouvrir_carte_dans", $langue);
			$infobulle .= " ".((strcmp($this->source, _PAGE_ATTR_SOURCE_OSM))?"Google Maps":"Open Street Map");
			$lien_carte = $this->get_ref_carte($texte);
			$src_carte = $this->get_src_carte($texte, $langue);
			echo "<div class=\"wrap_plan\"><a class=\"ancre_plan\" href=\"".$lien_carte."\" title=\"".$infobulle."\" target=\"_blank\">"._HTML_FIN_LIGNE;
			echo "<img class=\"image_cadre image_plan_".$this->orientation."\" src=\"".$src_carte."\" alt=\"".$texte."\" />";
			echo "</a></div>"._HTML_FIN_LIGNE;
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_ADMIN))) {
			$texte = $this->obj_texte->get_texte($this->id_texte, $langue);
			$this->reinit_carte($langue);
			$src_carte = $this->get_src_carte($texte, $langue);
			echo "<div class=\"wrap_plan\">"._HTML_FIN_LIGNE;
			echo "<img class=\"image_cadre image_plan_".$this->orientation."\" src=\"".$src_carte."\" alt=\"".$texte."\" />";
			echo "</div>"._HTML_FIN_LIGNE;
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_EDIT))) {
			$texte = $this->check_texte($this->obj_texte, $this->id_texte, $langue);
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
		$carte_locale = $this->get_src_carte_locale($langue);
		// Si la carte n'a pas été copiée en local on effectue cette copie
		if (!(@file_exists($carte_locale))) {
			$this->get_src_carte_distante($carte_locale, $texte, $langue);
		}
		return $carte_locale;
	}
	private function get_src_carte_locale($langue) {
		if (!(strcmp($this->orientation, _PAGE_ATTR_CARTE_PORTRAIT))) {$orientation = "v";}
		elseif (!(strcmp($this->orientation, _PAGE_ATTR_CARTE_CARRE))) {$orientation = "c";}
		else {$orientation = "h";}
		$extension = (strcmp($this->source, _PAGE_ATTR_SOURCE_OSM))?_CARTE_SUFFIXE_GOOGLE:_CARTE_SUFFIXE_OSM;
		$src = _XML_PATH_IMAGES_SITE._CARTE_PREFIXE.$this->id_texte."_".$orientation."_".$this->niveau_zoom."_".$langue.$extension;
		return $src;
	}
	private function get_src_carte_distante($carte_locale, $texte, $langue) {
		if (strcmp($this->source, _PAGE_ATTR_SOURCE_OSM)) {
			$carte_distante = $this->get_src_carte_google($texte, $langue);
			$this->copier_carte($carte_distante, $carte_locale);
		}
		else {
			$this->get_src_carte_osm($carte_locale, $texte);
		}
	}
	private function get_src_carte_google($texte, $langue) {
		if (!(strcmp($this->orientation, _PAGE_ATTR_CARTE_PORTRAIT))) {$template = _CARTE_GOOGLE_PORTRAIT_SRC;}
		elseif (!(strcmp($this->orientation, _PAGE_ATTR_CARTE_CARRE))) {$template = _CARTE_GOOGLE_CARRE_SRC;}
		else {$template = _CARTE_GOOGLE_PAYSAGE_SRC;}
		$src = sprintf($template, urlencode($texte), urlencode($texte), $langue, $this->niveau_zoom);
		return $src;
	}
	private function get_src_carte_osm($carte_locale, $texte) {
		list($lat, $lon) = explode(",",trim($texte));
		if (!(strcmp($this->orientation, _PAGE_ATTR_CARTE_PORTRAIT))) {$width = 400;$height = 600;}
		elseif (!(strcmp($this->orientation, _PAGE_ATTR_CARTE_CARRE))) {$width = 400;$height = 400;}
		else {$width = 600;$height = 400;}
		$osm = new openstreetmap(_PHP_PATH_ROOT, (float) $lat, (float) $lon, $width, $height, $this->niveau_zoom);
		$osm->makeMap($carte_locale);
	}
	private function get_ref_carte($texte) {
		if (strcmp($this->source, _PAGE_ATTR_SOURCE_OSM)) {
			$ref_carte = sprintf(_CARTE_GOOGLE_REF, urlencode($texte));
		}
		else {
			list($lat, $lon) = explode(",",trim($texte));
			$ref_carte = sprintf(_CARTE_OSM_REF, $this->niveau_zoom, (float) $lat, (float) $lon);
		}
		return $ref_carte;
	}
	private function reinit_carte($langue) {
		$carte_locale = $this->get_src_carte_locale($langue);
		@unlink($carte_locale);
	}
	private function copier_carte($carte_distante, $carte_locale) {
		$in = @fopen($carte_distante, "rb");$out = @fopen($carte_locale, "wb");
		while ($chunk = @fread($in,8192)) {@fwrite($out, $chunk, 8192);}
		@fclose($in);@fclose($out);
	}
}