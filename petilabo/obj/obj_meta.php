<?php
class obj_meta extends obj_editable {
	private $obj_texte = null;
	private $id_meta_titre = null;
	private $id_meta_descr = null;
	private $is_meta = false;

	public function __construct(&$obj_texte, $id_meta_titre, $id_meta_descr) {
		$this->obj_texte = $obj_texte;
		$this->id_meta_titre = $id_meta_titre;
		if (strlen($this->id_meta_titre) > 0) {$this->is_meta = true;}
		$this->id_meta_descr = $id_meta_descr;
		if (strlen($this->id_meta_descr) > 0) {$this->is_meta = true;}
	}

	public function afficher($mode, $langue) {
		if (!($this->is_meta)) {return;}
		if (strcmp($mode, _PETILABO_MODE_EDIT)) {return;}
		$this->ouvrir_tableau_multiple(_EDIT_LABEL_META, _EDIT_COULEUR, null);
		$this->ouvrir_tableau_simple();
		list($meta_titre, $src_meta_titre) = $this->check_src_texte($this->obj_texte, $this->id_meta_titre, $langue);
		$this->ouvrir_ligne();
		$this->ecrire_cellule_categorie(_EDIT_LABEL_META_TITRE, _EDIT_COULEUR, 1);
		$this->ecrire_cellule_symbole_texte_brut($this->id_meta_titre, _EDIT_SYMBOLE_META, "Modifier le méta titre de la page");
		$this->ecrire_cellule_texte($this->id_meta_titre, $meta_titre);
		$this->fermer_ligne($src_meta_titre);
		list($meta_descr, $src_meta_descr) = $this->check_src_texte($this->obj_texte, $this->id_meta_descr, $langue);
		$this->ouvrir_ligne();
		$this->ecrire_cellule_categorie(_EDIT_LABEL_META_DESCR, _EDIT_COULEUR, 1);
		$this->ecrire_cellule_symbole_texte_brut($this->id_meta_descr, _EDIT_SYMBOLE_META, "Modifier la méta description de la page");
		$this->ecrire_cellule_texte($this->id_meta_descr, $meta_descr);
		$this->fermer_ligne($src_meta_descr);
		$this->fermer_tableau();
		$this->fermer_tableau();
	}
}