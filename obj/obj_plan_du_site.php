<?php

class obj_entree {
	private $obj_texte = null;
	private $niveau = 0;
	private $nom = null;
	private $touche = null;
	private $href = null;
	
	public function __construct(&$obj_texte, $niveau, $nom, $href, $touche) {
		$this->obj_texte = $obj_texte;
		$this->niveau = $niveau;
		$this->nom = $nom;
		$this->touche = $touche;
		$this->href = $href;
	}

	public function afficher($mode, $langue, $style_p = null) {
		$nom_page = $this->obj_texte->get_texte($this->nom, $langue);
		if (strlen($nom_page) > 0) {
			$this->niveau = ($this->niveau>5)?6:($this->niveau+1);
			$classe = "paragraphe";
			$span_lien = (strlen($style_p) > 0)?" class=\""._CSS_PREFIXE_TEXTE.$style_p."\"":"";
			$class_touche = " class=\"plan_touche\"";
			$access_key = (strlen($this->touche)>0)?" accesskey=\"".$this->touche."\"":"";
			echo "<div class=\"plan_du_site "._CSS_PREFIXE_PLAN_NIVEAU.$this->niveau."\">";
			$ref_touche = (!(strcmp($mode, _PETILABO_MODE_SITE)))?"<a href=\"".$this->href."\" title=\"".$nom_page."\"".$access_key.">".$this->touche."</a>":"<a>".$this->touche."</a>";
			$html_touche = (strlen($this->touche) > 0)?$ref_touche:"&nbsp;";
			echo "<p ".$class_touche.">".$html_touche."</p>";
			$ref_lien = (!(strcmp($mode, _PETILABO_MODE_SITE)))?"<a ".$span_lien." href=\"".$this->href."\" title=\"".$nom_page."\"".$access_key.">".$nom_page."</a>":$nom_page;
			$html_lien = (strlen($style_p) > 0)?"<span class=\""._CSS_PREFIXE_TEXTE.$style_p."\">".$ref_lien."</span>":$ref_lien;
			echo "<p class=\"plan_titre ".$classe."\">".$html_lien."</p></div>"._HTML_FIN_LIGNE;
		}
	}
}

class obj_plan_du_site extends obj_html {
	private $obj_texte = null;
	private $tab_entrees = array();

	public function __construct(&$obj_texte) {
		$this->obj_texte = $obj_texte;
	}

	public function ajouter_entree($niveau, $nom, $href, $touche) {
		$this->tab_entrees[] = new obj_entree($this->obj_texte, $niveau, $nom, $href, $touche);
	}

	public function afficher($mode, $langue, $style_p) {
		if ((!(strcmp($mode, _PETILABO_MODE_SITE))) || (!(strcmp($mode, _PETILABO_MODE_ADMIN)))){
			foreach ($this->tab_entrees as $obj_entree) {
				if ($obj_entree) {$obj_entree->afficher($mode, $langue, $style_p);}
			}
			$this->afficher_pied($langue, $style_p);
		}
	}
	
	private function afficher_pied($langue, $style_p) {
		$legende_accesskey = $this->obj_texte->get_label_accesskey($langue);
		echo "<div class=\"plan_legende "._CSS_PREFIXE_PLAN_NIVEAU."1\">";
		echo "<p class=\"plan_fleche\">&#xf062;</p></div>"._HTML_FIN_LIGNE;
		echo "<div class=\"plan_legende "._CSS_PREFIXE_PLAN_NIVEAU."1\">";
		echo "<p class=\"plan_fleche_legende\">".$legende_accesskey."</p><br /></div>"._HTML_FIN_LIGNE;
	}
}