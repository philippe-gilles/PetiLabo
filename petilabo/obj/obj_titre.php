<?php
	
class obj_titre extends obj_editable {
	private $obj_texte = null;
	private $niveau = 0;
	private $style = null;
	private $id_texte = null;

	public function __construct(&$obj_texte, $niveau, $style, $id_texte) {
		$this->niveau = (int) $niveau;
		if (strlen($style) > 0) {$this->style = _CSS_PREFIXE_TEXTE.$style;}
		$this->id_texte = $id_texte;
		$this->obj_texte = $obj_texte;
	}

	public function afficher($mode, $langue) {
		if (!(strcmp($mode, _PETILABO_MODE_SITE))) {
			$texte = $this->obj_texte->get_texte($this->id_texte, $langue);
			if (strlen($texte) > 0) {echo "<h".$this->niveau." class=\"titre ".$this->style."\">".$texte."</h".$this->niveau.">"._HTML_FIN_LIGNE;}
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_ADMIN))) {
			$texte = $this->obj_texte->get_texte($this->id_texte, $langue);
			if (strlen($texte) > 0) {echo "<h".$this->niveau." class=\"titre ".$this->style."\">".$texte."</h".$this->niveau.">"._HTML_FIN_LIGNE;}
			else {echo "<h".$this->niveau." class=\"titre ".$this->style."\">...</h".$this->niveau.">"._HTML_FIN_LIGNE;}
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_EDIT))) {
			list($texte, $src) = $this->check_src_texte($this->obj_texte, $this->id_texte, $langue);
			$this->ouvrir_tableau_simple();
			$this->ouvrir_ligne();
			$titre = ($this->niveau > 1)?_EDIT_LABEL_SOUS_TITRE:_EDIT_LABEL_TITRE;
			$this->ecrire_cellule_categorie($titre, _EDIT_COULEUR, 1);
			$this->ecrire_cellule_symbole_texte($this->id_texte, _EDIT_SYMBOLE_LABEL, "Modifier le texte du titre");
			$this->ecrire_cellule_texte($this->id_texte, $texte);
			$this->fermer_ligne($src);
			$this->fermer_tableau();
		}
	}
}