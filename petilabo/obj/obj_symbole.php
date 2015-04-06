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
			$icone = $this->obj_texte->get_icone($this->id_texte, $langue);
			$classe = "paragraphe ".$this->style;
			if (strlen($icone) > 0) {echo "<p class=\"".$classe."\">".$icone."</p>"._HTML_FIN_LIGNE;}
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_ADMIN))) {
			$icone = $this->obj_texte->get_icone($this->id_texte, $langue);
			$classe = "paragraphe ".$this->style;
			if (strlen($icone) > 0) {echo "<p class=\"".$classe."\">".$icone."</p>"._HTML_FIN_LIGNE;}
			else {echo "<p class=\"".$classe."\">.</p>"._HTML_FIN_LIGNE;}
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_EDIT))) {
			list($icone, $src_icone) = $this->check_src_icone($this->obj_texte, $this->id_texte, $langue);
			$this->ouvrir_tableau_simple();
			$this->ouvrir_ligne();
			$this->ecrire_cellule_categorie(_EDIT_LABEL_SYMBOLE, _EDIT_COULEUR, 1);
			$this->ecrire_cellule_symbole_texte_simple(_EDIT_TYPE_ICONE, $this->id_texte, _EDIT_SYMBOLE_ICONE, "Modifier le code du symbole");
			$this->ecrire_cellule_icone($icone);
			$this->fermer_ligne($src_icone);
			$this->fermer_tableau();
		}
	}
}