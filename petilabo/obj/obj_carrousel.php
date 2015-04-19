<?php

class obj_carrousel extends obj_collection_images {
	private $obj_texte = null;
	private $nom = null;
	private $has_navigation = false;
	private $has_boutons = false;
	private $has_auto = false;
	private $nb_cols = 0;

	public function __construct(&$obj_texte, $nom, $has_navigation, $has_boutons, $has_auto, $largeur_max, $nb_cols) {
		$this->obj_texte = $obj_texte;
		$this->nom = $nom;
		$this->has_navigation = $has_navigation;
		$this->has_boutons = $has_boutons;
		$this->has_auto = $has_auto;
		$this->nb_cols = $nb_cols;
	}

	public function afficher($mode, $langue) {
		if (!(strcmp($mode, _PETILABO_MODE_SITE))) {
			$this->afficher_site($langue);
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_ADMIN))) {
			$this->afficher_admin($langue);
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_EDIT))) {
			$this->afficher_edit($langue);
		}
	}
	
	protected function afficher_site($langue) {
		echo "<div class=\"carrousel\"><ul id=\"carrousel_".$this->nom."\" class=\"bxslider\">"._HTML_FIN_LIGNE;
		foreach ($this->tab_images as $obj_image) {
			if ($obj_image->get_est_vide()) {continue;}
			echo "<li>"._HTML_FIN_LIGNE;
			$obj_image->afficher(_PETILABO_MODE_SITE, $langue, true);
			echo "</li>"._HTML_FIN_LIGNE;
		}
		echo "</ul></div>"._HTML_FIN_LIGNE;
		$param = "{";
		if ($this->has_auto) {
			$param .= "auto:true";
		}
		if ($this->largeur_max > 1) {
			$param .= ($this->has_auto)?",":"";
			$param .= "slideWidth:".$this->largeur_max;
			$cols = ($this->nb_cols > 0)?$this->nb_cols:3;
			$param .= ",minSlides:2,maxSlides:".$cols;
			$param .= ",moveSlides:1,slideMargin:10";
		}
		$param .= "}";
		echo "<script type=\"text/javascript\">"._HTML_FIN_LIGNE;
		echo "$('div.carrousel ul#carrousel_".$this->nom."').bxSlider(".$param.");"._HTML_FIN_LIGNE;
		echo "</script>"._HTML_FIN_LIGNE;
	}

	protected function afficher_admin($langue) {
		echo "<div class=\"carrousel carrousel_admin\">"._HTML_FIN_LIGNE;
		$style_largeur = ($this->largeur_max > 0)?"max-width:".$this->largeur_max."px;":"max-width:100%;";
		$no_img = 0;
		foreach ($this->tab_images as $obj_image) {
			if ($obj_image->get_est_vide()) {continue;}
			if ($no_img == 0) {$obj_image->afficher_brut(_PETILABO_MODE_ADMIN, $langue, null, $style_largeur);}
			elseif ($this->largeur_max > 0) {
				$pos_x = ($this->largeur_max+10) * $no_img;
				$obj_image->afficher_brut(_PETILABO_MODE_ADMIN, $langue, "carrousel_image_admin", $style_largeur."left:".$pos_x."px;");
			}
			$no_img += 1;
		}
		echo "</div>"._HTML_FIN_LIGNE;
	}

	protected function afficher_edit($langue) {
		$titre = $this->construire_etiquette(_EDIT_LABEL_CARROUSEL, $this->nom);
		$this->ouvrir_tableau_multiple($titre, _EDIT_COULEUR, $this->nom);
		foreach ($this->tab_images as $obj_image) {
			if ($obj_image) {
				$obj_image->set_id_tab($this->id_tab);
				$obj_image->afficher(_PETILABO_MODE_EDIT, $langue, true);
			}
		}
		$this->fermer_tableau();
	}
}