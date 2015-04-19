<?php

class obj_diaporama extends obj_collection_images {
	private $obj_texte = null;
	private $nom = null;
	private $has_navigation = false;
	private $has_boutons = false;

	public function __construct(&$obj_texte, $nom, $has_navigation, $has_boutons) {
		$this->obj_texte = $obj_texte;
		$this->nom = $nom;
		$this->has_navigation = $has_navigation;
		$this->has_boutons = $has_boutons;
	}

	public function afficher($mode, $langue) {
		if (!(strcmp($mode, _PETILABO_MODE_SITE))) {
			$this->afficher_site($langue, $this->largeur_max);
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_ADMIN))) {
			$this->afficher_admin($langue, $this->largeur_max);
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_EDIT))) {
			$this->afficher_edit($langue);
		}
	}
	
	protected function afficher_site($langue, $largeur) {
		echo "<div class=\"diaporama\"><ul id=\"diaporama_".$this->nom."\" class=\"rslides\">"._HTML_FIN_LIGNE;
		foreach ($this->tab_images as $obj_image) {
			if ($obj_image->get_est_vide()) {continue;}
			echo "<li>"._HTML_FIN_LIGNE;
			$obj_image->afficher(_PETILABO_MODE_SITE, $langue, true);
			echo "</li>"._HTML_FIN_LIGNE;
		}
		echo "</ul></div>"._HTML_FIN_LIGNE;
		$param = "{namespace:'boutons_diapo'";
		// TODO : Ajouter les paramètres durée (timeout) et pause (pause)
		$param .= ",pager:".(($this->has_boutons)?"true":"false");
		$param .= ",nav:".(($this->has_navigation)?"true":"false");
		$param .= ($largeur > 0)?",maxwidth:'".$largeur."px'}":"}";
		echo "<script type=\"text/javascript\">"._HTML_FIN_LIGNE;
		echo "$(function() {"._HTML_FIN_LIGNE;
		echo "$(\"#diaporama_".$this->nom."\").responsiveSlides(".$param.");"._HTML_FIN_LIGNE;
		echo "});"._HTML_FIN_LIGNE;
		echo "</script>"._HTML_FIN_LIGNE;
	}

	protected function afficher_admin($langue, $largeur) {
		echo "<div class=\"diaporama\">";
		echo "<ul id=\"diaporama_".$this->nom."\" class=\"rslides\" style=\"max-width:".$largeur."px\">"._HTML_FIN_LIGNE;
		foreach ($this->tab_images as $obj_image) {
			if ($obj_image->get_est_vide()) {continue;}
			echo "<li>"._HTML_FIN_LIGNE;
			$obj_image->afficher(_PETILABO_MODE_ADMIN, $langue, true);
			echo "</li>"._HTML_FIN_LIGNE;
			break;
		}
		echo "</ul></div>"._HTML_FIN_LIGNE;
	}
	protected function afficher_edit($langue) {
		$titre = $this->construire_etiquette(_EDIT_LABEL_DIAPORAMA, $this->nom);
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