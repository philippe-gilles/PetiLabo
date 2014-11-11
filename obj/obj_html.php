<?php
	
class obj_html {
	protected $id_tab = null;

	public function set_id_tab($id) {
		$this->id_tab = $id;
	}

	protected function ouvrir_balise_html5($balise) {
		if (strlen($balise) > 0) {
			echo "<!--[if !IE]> -->";
			echo "<".$balise.">";
			echo "<!-- <![endif]-->";
			echo "<!--[if gt IE 9]>";
			echo "<".$balise.">";
			echo "<![endif]-->\n";
		}
	}
	protected function fermer_balise_html5($balise) {
		if (strlen($balise) > 0) {
			echo "<!--[if !IE]> -->";
			echo "</".$balise.">";
			echo "<!-- <![endif]-->";
			echo "<!--[if gt IE 9]>";
			echo "</".$balise.">";
			echo "<![endif]-->\n";
		}
	}
	protected function fabriquer_html_lien($mode, $url_lien, $touche_lien, $classe = null) {
		if ((strlen($url_lien) > 0) && (!(strcmp($mode, _PETILABO_MODE_SITE)))) {
			$attr_access = (strlen($touche_lien)>0)?" accesskey=\"".$touche_lien."\"":"";
			$attr_target = $this->url_target($url_lien);
			$attr_classe = (strlen($classe)>0)?" class=\"".$classe."\"":"";
			$ret = "<a".$attr_classe.$attr_target." href=\"".$url_lien."\"".$attr_access.">%s</a>";
		}
		else {$ret = "%s";}
		return $ret;
	}
	protected function extraire_classe_alignement($alignement) {
		switch ($alignement) {
			case _STYLE_ATTR_ALIGNEMENT_GAUCHE :
				$classe = "texte_g";break;
			case _STYLE_ATTR_ALIGNEMENT_DROITE :
				$classe = "texte_d";break;
			default :
				$classe = "texte_c";break;
		}
		return $classe;
	}
	protected function extraire_extension($fichier) {
		$extension = null;
		if (strlen($fichier) > 0) {
			$extension = strtoupper(substr(strrchr($fichier, ".")  ,1));
			if (!(strcmp($extension, "jpeg"))) {$extension = "jpg";}
		}
		return $extension;
	}
	protected function url_target($lien) {
		$ret = (strncmp($lien, "http", 4))?"":" target=\"_blank\"";
		return $ret;
	}
}