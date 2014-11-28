<?php
class obj_symbole extends obj_editable {
	private $obj_texte = null;
	private $style = null;
	private $id_texte = null;

	public function __construct(&$obj_texte, $style, $id_texte) {
		if (strlen($style) > 0) {$this->style = _CSS_PREFIXE_ICONE.$style;}
		$this->id_texte = $id_texte;
		$this->obj_texte = $obj_texte;
	}

	public function afficher($mode, $langue) {
		if (!(strcmp($mode, _PETILABO_MODE_SITE))) {
			$texte = $this->obj_texte->get_texte($this->id_texte, $langue);
			$classe = "paragraphe ".$this->style;
			if (strlen($texte) > 0) {echo "<p class=\"".$classe."\">"._ICONE_PREFIXE.$texte._ICONE_SUFFIXE."</p>"._HTML_FIN_LIGNE;}
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_ADMIN))) {
			$texte = $this->obj_texte->get_texte($this->id_texte, $langue);
			$classe = "paragraphe ".$this->style;
			if (strlen($texte) > 0) {echo "<p class=\"".$classe."\">"._ICONE_PREFIXE.$texte._ICONE_SUFFIXE."</p>"._HTML_FIN_LIGNE;}
			else {echo "<p class=\"".$classe."\">.</p>"._HTML_FIN_LIGNE;}
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_EDIT))) {
			$texte = $this->check_texte($this->obj_texte, $this->id_texte, $langue);
			$this->ouvrir_tableau_simple();
			$this->ouvrir_ligne();
			$icone = _ICONE_PREFIXE.$texte._ICONE_SUFFIXE;
			$this->ecrire_cellule_categorie(_EDIT_LABEL_SYMBOLE, _EDIT_COULEUR, 1);
			$this->ecrire_cellule_symbole_texte_simple(_EDIT_TYPE_ICONE, $this->id_texte, _EDIT_SYMBOLE_ICONE, "Modifier le code du symbole");
			$this->ecrire_cellule_icone($icone);
			$this->fermer_ligne();
			$this->fermer_tableau();
		}
	}
}