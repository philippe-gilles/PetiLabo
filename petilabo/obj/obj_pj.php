<?php
class obj_pj extends obj_editable {
	private $obj_pj = null;
	private $obj_texte = null;
	private $nom = null;
	private $lien = null;
	private $fichier = null;
	private $base = null;
	private $extension = null;
	private $id_info = null;
	private $id_legende = null;

	public function __construct(&$pj, &$obj_texte, $nom, $lien) {
		$this->obj_pj = $pj;
		$this->obj_texte = $obj_texte;
		$this->nom = $nom;
		$this->lien = $lien;
		$this->fichier = $this->obj_pj->get_fichier();
		$this->base = basename($this->fichier);
		$this->extension = $this->extraire_extension($this->base);
		$this->id_info = $this->obj_pj->get_info();
		$this->id_legende = $this->obj_pj->get_legende();
	}

	public function afficher($mode, $langue, $style = null) {
		$classe = "paragraphe";
		if (strlen($style) > 0) {$classe .= " "._CSS_PREFIXE_TEXTE.$style;}

		if (!(strcmp($mode, _PETILABO_MODE_SITE))) {
			$this->afficher_site($langue, $classe);
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_ADMIN))) {
			$this->afficher_admin($langue, $classe);
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_EDIT))) {
			$this->afficher_edit($langue);
		}
	}
	
	protected function afficher_site($langue, $classe) {
		if (strlen($this->base) == 0) return;
		$info = $this->obj_texte->get_texte($this->id_info, $langue);
		$legende = $this->obj_texte->get_texte($this->id_legende, $langue);
		switch ($this->lien) {
			case _PAGE_ATTR_LIEN_IMAGE :
				echo "<div class=\"file\">"._HTML_FIN_LIGNE;
				echo "<p class=\"filetype\"><a href=\"".$this->fichier."\" title=\"".$info."\" target=\"_blank\">".$this->extension."</a></p>"._HTML_FIN_LIGNE;
				echo "</div>"._HTML_FIN_LIGNE;
				echo "<p class=\"".$classe."\"><a href=\"".$this->fichier."\" title=\"".$info."\" target=\"_blank\">".$legende."</a></p>"._HTML_FIN_LIGNE;
				break;
			case _PAGE_ATTR_LIEN_FICHIER :
				echo "<p class=\"lien_pj_fichier ".$classe."\">".$legende."&nbsp;: <a href=\"".$this->fichier."\" title=\"".$info."\" target=\"_blank\">".$this->base."</a></p>"._HTML_FIN_LIGNE;
				break;
			default :
				echo "<p class=\"lien_pj_legende ".$classe."\"><a href=\"".$this->fichier."\" title=\"".$info."\" target=\"_blank\">".$legende."</a></p>"._HTML_FIN_LIGNE;
				break;
		}
	}

	protected function afficher_admin($langue, $classe) {
		if (strlen($this->base) == 0) return;
		$legende = $this->obj_texte->get_texte($this->id_legende, $langue);
		switch ($this->lien) {
			case _PAGE_ATTR_LIEN_IMAGE :
				echo "<div class=\"file\">"._HTML_FIN_LIGNE;
				echo "<p class=\"filetype\">".$this->extension."</p>"._HTML_FIN_LIGNE;
				echo "</div>"._HTML_FIN_LIGNE;
				echo "<p class=\"".$classe."\"><span style=\"text-decoration:underline;\">".$legende."</span></p>"._HTML_FIN_LIGNE;
				break;
			case _PAGE_ATTR_LIEN_FICHIER :
				echo "<p class=\"lien_pj_fichier ".$classe."\">".$legende."&nbsp;: <span style=\"text-decoration:underline;\">".$this->base."</span></p>"._HTML_FIN_LIGNE;
				break;
			default :
				echo "<p class=\"lien_pj_legende ".$classe."\"><span style=\"text-decoration:underline;\">".$legende."</span></p>"._HTML_FIN_LIGNE;
				break;
		}
	}
	
	protected function afficher_edit($langue) {
		list($trad_info, $src_info) = $this->check_src_texte($this->obj_texte, $this->id_info, $langue);
		list($trad_legende, $src_legende) = $this->check_src_texte($this->obj_texte, $this->id_legende, $langue);
		$this->ouvrir_tableau_simple();
		$this->ouvrir_ligne();
		$this->ecrire_cellule_categorie(_EDIT_LABEL_PJ, _EDIT_COULEUR, 3);
		$this->ecrire_cellule_symbole_pj($this->nom, _EDIT_SYMBOLE_LIEN);
		$this->ecrire_cellule_texte($this->nom, strtolower($this->base));
		$this->fermer_ligne("fichier");
		$this->ouvrir_ligne();
		$this->ecrire_cellule_symbole_texte_brut($this->id_info, _EDIT_SYMBOLE_INFO,"Modifier le texte de l'infobulle");
		$this->ecrire_cellule_texte($this->id_info, $trad_info);
		$this->fermer_ligne($src_info);
		$this->ouvrir_ligne();
		$this->ecrire_cellule_symbole_texte($this->id_legende, _EDIT_SYMBOLE_LEGENDE, "Modifier la description du fichier");
		$this->ecrire_cellule_texte($this->id_legende, $trad_legende);
		$this->fermer_ligne($src_legende);
		$this->fermer_tableau();
	}
	
}