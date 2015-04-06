<?php

define("_TEMPLATE_VID_DAILYMOTION", "<iframe frameborder=\"0\" width=\"480\" height=\"270\" src=\"http://www.dailymotion.com/embed/video/%s\"></iframe>");
define("_TEMPLATE_VID_YOUTUBE", "<iframe width=\"560\" height=\"315\" src=\"//www.youtube.com/embed/%s\" frameborder=\"0\" allowfullscreen></iframe>");
define("_TEMPLATE_VID_VIMEO", "<iframe src=\"//player.vimeo.com/video/%s\" width=\"500\" height=\"281\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>");
define("_TEMPLATE_IMG_DAILYMOTION", "http://www.dailymotion.com/thumbnail/video/%s");
define("_TEMPLATE_IMG_YOUTUBE", "http://img.youtube.com/vi/%s/0.jpg");
define("_TEMPLATE_API_VIMEO", "http://vimeo.com/api/v2/video/%s.php");
define("_TEMPLATE_IMG_VIMEO", "%s");
	
class obj_video extends obj_editable {
	private $obj_texte = null;
	private $id_texte = null;
	private $source = null;

	public function __construct(&$obj_texte, $id_texte, $source) {
		$this->id_texte = $id_texte;
		$this->source = $source;
		$this->obj_texte = $obj_texte;
	}

	public function afficher($mode, $langue) {
		if (!(strcmp($mode, _PETILABO_MODE_SITE))) {
			$texte = $this->obj_texte->get_texte($this->id_texte, $langue);
			$html = $this->get_iframe($texte);
			if (strlen($html) > 0) {
				echo "<div class=\"wrap_video\"><div class=\"cadre_video\">"._HTML_FIN_LIGNE;
				echo $html._HTML_FIN_LIGNE;
				echo "</div></div>"._HTML_FIN_LIGNE;
			}
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_ADMIN))) {
			$texte = $this->obj_texte->get_texte($this->id_texte, $langue);
			$src = $this->get_src($texte);
			if (strlen($src) > 0) {
				echo "<div class=\"wrap_video\"><img class=\"image_cadre\" src=\"".$src."\" alt=\"".$this->source."\" /></div>"._HTML_FIN_LIGNE;
			}
			else {
				echo "<p class=\"paragraphe\">".$texte."</p>"._HTML_FIN_LIGNE;
			}
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_EDIT))) {
			list($texte, $src_texte) = $this->check_src_texte($this->obj_texte, $this->id_texte, $langue);
			$src = (strlen($texte) > 0)?$this->get_src($texte):null;
			$this->ouvrir_tableau_simple();
			$this->ouvrir_ligne();
			$cat = _EDIT_LABEL_VIDEO."<br/>".ucwords($this->source);
			$this->ecrire_cellule_categorie($cat, _EDIT_COULEUR, 1);
			$this->ecrire_cellule_symbole_texte_simple(_EDIT_TYPE_VIDEO, $this->id_texte, _EDIT_SYMBOLE_VIDEO, "Modifier l'identifiant de la vidéo");
			$this->ecrire_cellule_video($this->id_texte, $texte, $src);
			$this->fermer_ligne($src_texte);
			$this->fermer_tableau();
		}
	}

	private function get_iframe($texte) {
		$html = null;
		if (!(strcmp($this->source, _PAGE_ATTR_SOURCE_YOUTUBE))) {$html = sprintf(_TEMPLATE_VID_YOUTUBE, $texte);}
		elseif (!(strcmp($this->source, _PAGE_ATTR_SOURCE_DAILYMOTION))) {$html = sprintf(_TEMPLATE_VID_DAILYMOTION, $texte);}
		elseif (!(strcmp($this->source, _PAGE_ATTR_SOURCE_VIMEO))) {$html = sprintf(_TEMPLATE_VID_VIMEO, $texte);}
		return $html;
	}

	private function get_src($texte) {
		$src = null;
		if (!(strcmp($this->source, _PAGE_ATTR_SOURCE_YOUTUBE))) {$src = sprintf(_TEMPLATE_IMG_YOUTUBE, $texte);}
		elseif (!(strcmp($this->source, _PAGE_ATTR_SOURCE_DAILYMOTION))) {$src = sprintf(_TEMPLATE_IMG_DAILYMOTION, $texte);}
		elseif (!(strcmp($this->source, _PAGE_ATTR_SOURCE_VIMEO))) {
			$src_api_vi = sprintf(_TEMPLATE_API_VIMEO, $texte);
			$api_vi = unserialize(file_get_contents($src_api_vi));
			$src_vi = $api_vi[0]['thumbnail_large'];  
			$src = sprintf(_TEMPLATE_IMG_VIMEO, $src_vi);
		}
		return $src;
	}
}