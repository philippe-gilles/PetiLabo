<?php
class obj_paragraphe extends obj_editable {
	private $obj_texte = null;
	private $style = null;
	private $id_texte = null;
	private $lien_telephonique = null;

	public function __construct(&$obj_texte, $style, $id_texte, $lien_telephonique) {
		if (strlen($style) > 0) {$this->style = _CSS_PREFIXE_TEXTE.$style;}
		$this->id_texte = $id_texte;
		$this->lien_telephonique = $lien_telephonique;
		$this->obj_texte = $obj_texte;
	}

	public function afficher($mode, $langue) {
		if (!(strcmp($mode, _PETILABO_MODE_SITE))) {
			$texte = $this->obj_texte->get_texte($this->id_texte, $langue);
			$classe = "paragraphe ".$this->style;
			if (strlen($this->lien_telephonique) > 0) {
				$classe .= " paragraphe_tel";
				$title = $this->obj_texte->get_label_appeler_tel($langue);
				$texte ="<a class=\"lien_tel\" href=\"tel:".$this->lien_telephonique."\" title=\"".$title."\">".$texte."</a>";
			}
			if (strlen($texte) > 0) {echo "<p class=\"".$classe."\">".$texte."</p>"._HTML_FIN_LIGNE;}
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_ADMIN))) {
			$texte = $this->obj_texte->get_texte($this->id_texte, $langue);
			$classe = "paragraphe ".$this->style;
			if (strlen($texte) > 0) {echo "<p class=\"".$classe."\">".$texte."</p>"._HTML_FIN_LIGNE;}
			else {echo "<p class=\"".$classe."\">...</p>"._HTML_FIN_LIGNE;}
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_EDIT))) {
			$texte = $this->check_texte($this->obj_texte, $this->id_texte, $langue);
			$this->ouvrir_tableau_simple();
			$this->ouvrir_ligne();
			$this->ecrire_cellule_categorie(_EDIT_LABEL_TEXTE, _EDIT_COULEUR, 1);
			$this->ecrire_cellule_symbole_texte($this->id_texte, _EDIT_SYMBOLE_LABEL, "Modifier le texte du paragraphe");
			$this->ecrire_cellule_texte($this->id_texte, $texte);
			$this->fermer_ligne();
			$this->fermer_tableau();
		}
	}
}