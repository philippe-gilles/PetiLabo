<?php

define("_CREDIT_ID_TITRE_SECTION_TECHNIQUE", "credit_section_technique");
define("_CREDIT_ID_TITRE_SECTION_PHOTOGRAPHIQUE", "credit_section_photo");

class obj_photo {
	private $src = null;
	private $copyright = null;
	private $largeur = null;
	private $hauteur = null;

	public function __construct($src, $copyright, $largeur, $hauteur) {
		$this->src = $src;
		$this->copyright = $copyright;
		$this->largeur = $largeur;
		$this->hauteur = $hauteur;
	}

	public function afficher($taille, $style_p) {
		if ($taille == 0) {$taille = 185;} elseif ($taille < 50) {$taille = 50;}
		$classe_copy  = (strlen($style_p) > 0)?_CSS_PREFIXE_TEXTE.$style_p:"";
		echo "<div class=\"credit_cadre_photo\" style=\"width:".$taille."px;\">"._HTML_FIN_LIGNE;
		echo "<div class=\"credit_cadre_img\" style=\"width:".$taille."px;height:".$taille."px;\">"._HTML_FIN_LIGNE;
		if ($this->largeur > $this->hauteur) {
			$nouvelle_largeur = ($this->largeur * $taille)/$this->hauteur;
			$position = (int) (-(($nouvelle_largeur - $taille)/2));
			echo "<img src=\"".$this->src."\" style=\"height:".$taille."px;left:".$position."px;\" />"._HTML_FIN_LIGNE;
		}
		else {
			$nouvelle_hauteur = ($this->hauteur * $taille)/$this->largeur;
			$position = (int) (-(($nouvelle_hauteur - $taille)/2));
			echo "<img src=\"".$this->src."\" style=\"width:".$taille."px;top:".$position."px;\" />"._HTML_FIN_LIGNE;
		}
		echo "</div>"._HTML_FIN_LIGNE;
		echo "<p class=\"paragraphe credit_copyright ".$classe_copy."\">&copy;&nbsp;".$this->copyright."</p>"._HTML_FIN_LIGNE;
		echo "</div>"._HTML_FIN_LIGNE;
	}
}

class obj_credits extends obj_html {
	private $obj_texte = null;
	private $chapitre_technique = false;
	private $chapitre_photographique = false;
	private $sections_chapitre = false;
	private $taille_vignette = 0;
	private $tab_technique = array();
	private $tab_photos = array();

	public function __construct(&$obj_texte, $chapitre_technique, $chapitre_photographique, $sections_chapitre, $taille_vignette) {
		$this->obj_texte = $obj_texte;
		$this->chapitre_technique = $chapitre_technique;
		$this->chapitre_photographique = $chapitre_photographique;
		$this->sections_chapitre = $sections_chapitre;
		$this->taille_vignette = $taille_vignette;
		$this->tab_technique = array("fa", "rs", "bx", "mp", "id", "ju", "jc", "te");
	}
	
	public function ajouter_credit_photo($src, $copyright, $largeur, $hauteur) {
		$this->tab_photos[] = new obj_photo($src, $copyright, $largeur, $hauteur);
	}

	public function afficher($mode, $langue, $style_p = null) {
		$langue_affichee = (strcmp($mode, _PETILABO_MODE_SITE))?$this->obj_texte->get_langue_par_defaut():$langue;
		if ($this->chapitre_technique) {$this->afficher_technique($mode, $langue_affichee, $style_p);}
		if ($this->chapitre_photographique) {$this->afficher_photographique($mode, $langue_affichee, $style_p);}
	}

	private function afficher_technique($mode, $langue, $style_p) {
		if ($this->sections_chapitre) {$this->ecrire_section(_CREDIT_ID_TITRE_SECTION_TECHNIQUE, $langue, $style_p);}
		foreach ($this->tab_technique as $credit_technique) {
			$id_titre = "credit_titre_".$credit_technique;
			$titre = $this->obj_texte->get_texte($id_titre, $langue);
			$id_lien = "credit_lien_".$credit_technique;
			$lien = $this->obj_texte->get_texte($id_lien, $langue);
			$id_visite = "credit_prefixe_lien";
			$visite = $this->obj_texte->get_texte($id_visite, $langue);		
			$this->ecrire_credit_technique($mode, $titre, $lien, $credit_technique, $visite, $style_p);
		}
		echo "<div style=\"clear:both;\"></div>"._HTML_FIN_LIGNE;
	}
	

	private function afficher_photographique($mode, $langue, $style_p) {
		$nb_photos = count($this->tab_photos);
		if ($nb_photos == 0) {return;}
		if ($this->sections_chapitre) {$this->ecrire_section(_CREDIT_ID_TITRE_SECTION_PHOTOGRAPHIQUE, $langue, $style_p);}
		foreach ($this->tab_photos as $credit_photo) {
			if ($credit_photo) {$credit_photo->afficher($this->taille_vignette, $style_p);}
		}
		echo "<div style=\"clear:both;\"></div>"._HTML_FIN_LIGNE;
	}
	
	private function ecrire_section($id_texte, $langue, $style_p) {
		$texte = $this->obj_texte->get_texte($id_texte, $langue);
		if (strlen($style_p) > 0) {$style_p = _CSS_PREFIXE_TEXTE.$style_p;}
		echo "<p class=\"titre_legal ".$style_p."\"><span class=\"titre_legal\">".$texte."</span></p>"._HTML_FIN_LIGNE;
	}
	
	public function ecrire_credit_technique($mode, $titre, $lien, $id_credit, $visite, $style_p) {
		$classe_lien  = (strlen($style_p) > 0)?_CSS_PREFIXE_TEXTE.$style_p:"";
		echo "<div class=\"credit_cadre_technique\">"._HTML_FIN_LIGNE;
		echo "<img src=\""._PHP_PATH_ROOT."images/".$id_credit.".jpg\" />"._HTML_FIN_LIGNE;
		if (strcmp($mode, _PETILABO_MODE_SITE)) {$html_lien = $titre;}
		else {$html_lien = (strlen($lien) > 0)?"<a href=\"".$lien."\" title=\"".$titre."\" target=\"_blank\">".$visite." ".$titre."</a>":$titre;}
		echo "<p class=\"paragraphe credit_lien ".$classe_lien."\" style=\"text-align:center;\">".$html_lien."</p>"._HTML_FIN_LIGNE;
		echo "</div>"._HTML_FIN_LIGNE;
	}
}