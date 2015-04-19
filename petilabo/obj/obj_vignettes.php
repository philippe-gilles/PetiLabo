<?php

class obj_vignettes extends obj_collection_images {
	private $obj_texte = null;
	private $nom = null;
	private $nb_cols = 0;
	private $largeur = 0;

	public function __construct(&$obj_texte, $nom, $nb_cols) {
		$this->obj_texte = $obj_texte;
		$this->nom = $nom;
		$this->nb_cols = (int) $nb_cols;
		$this->largeur = floor(100/($this->nb_cols));
		$this->largeur -= 2;
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
		echo "<div class=\"vignettes\" id=\"vignettes_".$this->nom."\">"._HTML_FIN_LIGNE;
		foreach ($this->tab_images as $obj_image) {
			if ($obj_image->get_est_vide()) {continue;}
			$obj_image->afficher_reduit(_PETILABO_MODE_SITE, $langue, true, null, "display:inline;width:".$this->largeur."%;margin:0 1%;");
		}
		echo "</div>"._HTML_FIN_LIGNE;
		$param = "{delegate:'a',type:'image',closeOnContentClick:'true'";
		$param .= ",tClose:'".$this->obj_texte->get_label_fermer($langue)."'";
		$param .= ",gallery:{enabled:'true'";
		$param .= ",tPrev:'".$this->obj_texte->get_label_precedent($langue)."'";
		$param .= ",tNext:'".$this->obj_texte->get_label_suivant($langue)."'";
		$param .= "}}";
		echo "<script type=\"text/javascript\">"._HTML_FIN_LIGNE;
		echo "$(document).ready(function() {"._HTML_FIN_LIGNE;
		echo "$('#vignettes_".$this->nom."').magnificPopup(".$param.");"._HTML_FIN_LIGNE;
		echo "});"._HTML_FIN_LIGNE;
		echo "</script>"._HTML_FIN_LIGNE;
	}

	protected function afficher_admin($langue) {
		echo "<div class=\"vignettes\" id=\"vignettes_".$this->nom."\">"._HTML_FIN_LIGNE;
		foreach ($this->tab_images as $obj_image) {
			if ($obj_image->get_est_vide()) {continue;}
			$obj_image->afficher_reduit(_PETILABO_MODE_SITE, $langue, false, null, "display:inline;width:".$this->largeur."%;margin:0 1%;");
		}
		echo "</div>"._HTML_FIN_LIGNE;
	}
	protected function afficher_edit($langue) {
		$titre = $this->construire_etiquette(_EDIT_LABEL_VIGNETTES, $this->nom);
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