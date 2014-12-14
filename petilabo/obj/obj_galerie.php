<?php

class obj_galerie extends obj_editable {
	private $obj_texte = null;
	private $nom = null;
	private $has_navigation = false;
	private $has_boutons = false;
	private $nb_cols = 0;
	private $largeur = 0;
	private $tab_images = array();

	public function __construct(&$obj_texte, $nom, $has_navigation, $has_boutons, $nb_cols) {
		$this->obj_texte = $obj_texte;
		$this->nom = $nom;
		$this->has_navigation = $has_navigation;
		$this->has_boutons = $has_boutons;
		$this->nb_cols = $nb_cols;
		$this->largeur = floor(100/($this->nb_cols));
		$this->largeur -= 2;
	}
	
	public function ajouter_image($obj_image) {
		$this->tab_images[] = $obj_image;
	}

	public function afficher($mode, $langue, $vertical = false, $vue_d_abord = false) {
		if (!(strcmp($mode, _PETILABO_MODE_SITE))) {
			$this->afficher_site($langue, $vertical, $vue_d_abord);
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_ADMIN))) {
			$this->afficher_admin($langue, $vertical, $vue_d_abord);
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_EDIT))) {
			$this->afficher_edit($langue);
		}
	}
	
	protected function afficher_site($langue, $vertical, $vue_d_abord) {
		if ($vertical) {
			if ($vue_d_abord) {
				$this->afficher_vue_site($langue, true);
				$this->afficher_onglets_site($langue, true);
			}
			else {
				$this->afficher_onglets_site($langue, true);
				$this->afficher_vue_site($langue, true);
			}
			echo "<div style=\"clear:both;\"></div>"._HTML_FIN_LIGNE;
			$classe = "vue_galerie_verticale";
		}
		else {
			if ($vue_d_abord) {
				$this->afficher_vue_site($langue, false);
				$this->afficher_onglets_site($langue, false);
			}
			else {
				$this->afficher_onglets_site($langue, false);
				$this->afficher_vue_site($langue, false);
			}
			$classe = "vue_galerie_horizontale";
		}
		echo "<script type=\"text/javascript\">"._HTML_FIN_LIGNE;
		$param = "{mode:'fade',pages:false";
		$param .= ",pagerCustom:'#onglets_".$this->nom."'}";
		echo "$('div.".$classe." ul#vue_".$this->nom."').bxSlider(".$param.");"._HTML_FIN_LIGNE;
		echo "</script>"._HTML_FIN_LIGNE;
	}

	protected function afficher_admin($langue, $vertical, $vue_d_abord) {
		if ($vertical) {
			if ($vue_d_abord) {
				$this->afficher_vue_admin($langue, true);
				$this->afficher_onglets_admin($langue, true);
			}
			else {
				$this->afficher_onglets_admin($langue, true);
				$this->afficher_vue_admin($langue, true);
			}
		}
		else {
			if ($vue_d_abord) {
				$this->afficher_vue_admin($langue, false);
				$this->afficher_onglets_admin($langue, false);
			}
			else {
				$this->afficher_onglets_admin($langue, false);
				$this->afficher_vue_admin($langue, false);
			}
		}
	}

	protected function afficher_edit($langue) {
		$titre = $this->construire_etiquette(_EDIT_LABEL_GALERIE, $this->nom);
		$this->ouvrir_tableau_multiple($titre, _EDIT_COULEUR, $this->nom);
		foreach ($this->tab_images as $obj_image) {
			if ($obj_image) {
				$obj_image->set_id_tab($this->id_tab);
				$obj_image->afficher(_PETILABO_MODE_EDIT, $langue, true);
			}
		}
		$this->fermer_tableau();
	}
	
	private function afficher_vue_site($langue, $vertical) {
		$classe = ($vertical)?"vue_galerie_verticale":"vue_galerie_horizontale";
		echo "<div class=\"".$classe."\">"._HTML_FIN_LIGNE;
		echo "<ul id=\"vue_".$this->nom."\" class=\"bxslider\">"._HTML_FIN_LIGNE;
		foreach ($this->tab_images as $obj_image) {
			if ($obj_image->get_est_vide()) {continue;}
			echo "<li>"._HTML_FIN_LIGNE;
			$obj_image->afficher_complet(_PETILABO_MODE_SITE, $langue, null, "image_galerie", true);
			echo "</li>"._HTML_FIN_LIGNE;
		}
		echo "</ul>"._HTML_FIN_LIGNE;
		echo "</div>"._HTML_FIN_LIGNE;
	}

	private function afficher_vue_admin($langue, $vertical) {
		$classe = ($vertical)?"vue_galerie_verticale":"vue_galerie_horizontale";
		echo "<div class=\"".$classe."\">"._HTML_FIN_LIGNE;
		foreach ($this->tab_images as $obj_image) {
			if ($obj_image->get_est_vide()) {continue;}
			$obj_image->afficher_brut(_PETILABO_MODE_SITE, $langue, "image_galerie");
			break;
		}
		echo "</div>"._HTML_FIN_LIGNE;
	}
	
	private function afficher_onglets_site($langue, $vertical) {
		$classe = ($vertical)?"onglet_galerie_verticale":"onglet_galerie_horizontale";
		echo "<div id=\"onglets_".$this->nom."\" class=\"".$classe."\">"._HTML_FIN_LIGNE;
		$index = 0;
		foreach ($this->tab_images as $obj_image) {
			if ($obj_image->get_est_vide()) {continue;}
			echo "<a data-slide-index=\"".$index."\" href=\"\">";
			$obj_image->afficher_reduit(_PETILABO_MODE_SITE, $langue, false, "miniature_galerie transparence_80pc", "width:".$this->largeur."%;");
			echo "</a>"._HTML_FIN_LIGNE;
			$index += 1;
		}
		echo "</div>"._HTML_FIN_LIGNE;
	}

	private function afficher_onglets_admin($langue, $vertical) {
		$classe = ($vertical)?"onglet_galerie_verticale":"onglet_galerie_horizontale";
		echo "<div id=\"onglets_".$this->nom."\" class=\"".$classe."\">"._HTML_FIN_LIGNE;
		foreach ($this->tab_images as $obj_image) {
			if ($obj_image->get_est_vide()) {continue;}
			$obj_image->afficher_reduit(_PETILABO_MODE_SITE, $langue, false, "miniature_galerie transparence_80pc", "width:".$this->largeur."%;");
		}
		echo "</div>"._HTML_FIN_LIGNE;
	}
}