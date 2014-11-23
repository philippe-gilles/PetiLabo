<?php

class obj_banniere_actu extends obj_editable {
	private $obj_texte = null;
	private $obj_image = null;
	private $no_actu = 0;
	private $style = null;

	public function __construct(&$obj_texte, &$obj_image, $no_actu, $style) {
		$this->obj_texte = $obj_texte;
		$this->obj_image = $obj_image;
		$this->no_actu = (int) $no_actu;
		$this->style = $style;
	}
	
	public function afficher($mode, $langue) {
		if (!($this->obj_image)) {return;}
		if (strcmp($mode, _PETILABO_MODE_EDIT)) {
			$alt = $this->obj_texte->get_texte($this->obj_image->get_alt(), $langue);
			$titre = $this->obj_texte->get_titre_actu($this->no_actu, $langue);
			$sous_titre = $this->obj_texte->get_sous_titre_actu($this->no_actu, $langue);
			$resume = $this->obj_texte->get_resume_actu($this->no_actu, $langue);
			$texte = $this->obj_texte->get_texte_actu($this->no_actu, $langue);
			$num_clic = (strlen($texte) > 0)?$this->no_actu:0;
			$src = $this->obj_image->get_src();
			if (strcmp($mode, _PETILABO_MODE_SITE)) {$src .= "?v=".uniqid();}
			$this->ecrire_banniere($src, $num_clic, $alt, $titre, $sous_titre, $resume);
		}
		else {
			$this->ouvrir_tableau_simple();
			$this->ouvrir_ligne();
			$titre = _EDIT_LABEL_ACTUALITE."&nbsp;n°".$this->no_actu;
			$this->ecrire_cellule_categorie($titre, _EDIT_COULEUR, 5);
			$this->ecrire_cellule_symbole_image($this->obj_image->get_nom(), _EDIT_SYMBOLE_IMAGE);
			$this->ecrire_cellule_image($this->obj_image->get_src());
			$this->fermer_ligne();
			$id_titre = $this->obj_texte->get_id_titre_actu($this->no_actu);
			$trad_titre = $this->check_texte($this->obj_texte, $id_titre, $langue);
			$this->ouvrir_ligne();
			$this->ecrire_cellule_symbole_texte($id_titre, _EDIT_SYMBOLE_LABEL, "Modifier le titre de l'actualité");
			$this->ecrire_cellule_texte($id_titre, $trad_titre);
			$this->fermer_ligne();
			$id_sous_titre = $this->obj_texte->get_id_sous_titre_actu($this->no_actu);
			$trad_sous_titre = $this->check_texte($this->obj_texte, $id_sous_titre, $langue);
			$this->ouvrir_ligne();
			$this->ecrire_cellule_symbole_texte($id_sous_titre, _EDIT_SYMBOLE_LABEL, "Modifier le sous-titre de l'actualité");
			$this->ecrire_cellule_texte($id_sous_titre, $trad_sous_titre);
			$this->fermer_ligne();
			$id_resume = $this->obj_texte->get_id_resume_actu($this->no_actu);
			$trad_resume = $this->check_texte($this->obj_texte, $id_resume, $langue);
			$this->ouvrir_ligne();
			$this->ecrire_cellule_symbole_texte($id_resume, _EDIT_SYMBOLE_LABEL, "Modifier le résumé de l'actualité");
			$this->ecrire_cellule_texte($id_resume, $trad_resume);
			$this->fermer_ligne();
			$id_texte = $this->obj_texte->get_id_texte_actu($this->no_actu);
			$trad_texte = $this->check_texte($this->obj_texte, $id_texte, $langue);
			$this->ouvrir_ligne();
			$this->ecrire_cellule_symbole_texte($id_texte, _EDIT_SYMBOLE_LABEL, "Modifier le texte d'accompagnement de l'actualité");
			$this->ecrire_cellule_texte($id_texte, $trad_texte);
			$this->fermer_ligne();
			$this->fermer_tableau();
		}
	}
	
	public function get_no_actu() {return $this->no_actu;}

	private function ecrire_banniere($src, $num_clic, $alt, $titre, $sous_titre, $resume) {
		echo "<li>"._HTML_FIN_LIGNE;
		$div_id = ($num_clic > 0)?" id=\"actu_".$num_clic."\"":"";
		echo "<div".$div_id." class=\"div_actu\">"._HTML_FIN_LIGNE;
		echo "<img class=\"image_actu\" src=\"".$src."\" alt=\"".$alt."\" />"._HTML_FIN_LIGNE;
		if (strlen($titre) > 0) {
			$style_titre = (strlen($this->style) > 0)?" "._CSS_PREFIXE_ACTU."titre_".$this->style:"";
			echo "<p class=\"cadre_actu titre_actu".$style_titre."\">".$titre."</p>"._HTML_FIN_LIGNE;
		}
		if (strlen($sous_titre) > 0) {
			$style_sous_titre = (strlen($this->style) > 0)?" "._CSS_PREFIXE_ACTU."sous_titre_".$this->style:"";
			echo "<p class=\"cadre_actu sous_titre_actu".$style_sous_titre."\">".$sous_titre."</p>"._HTML_FIN_LIGNE;
		}
		if (strlen($resume) > 0) {
			$style_resume = (strlen($this->style) > 0)?" "._CSS_PREFIXE_ACTU."resume_".$this->style:"";
			echo "<p class=\"cadre_actu resume_actu".$style_resume."\">".$resume."</p>"._HTML_FIN_LIGNE;
		}
		echo "</div>"._HTML_FIN_LIGNE;
		echo "</li>"._HTML_FIN_LIGNE;
	}
}

class obj_actu extends obj_editable {
	private $obj_texte = null;
	private $style = null;
	private $largeur_max = 0;
	private $tab_actu = array();
	private $tab_sommaire = array();

	public function __construct(&$obj_texte, $style) {
		$this->obj_texte = $obj_texte;
		$this->style = $style;
	}

	public function ajouter_actu(&$image, $no_actu) {
		$this->tab_actu[$no_actu] = new obj_banniere_actu($this->obj_texte, $image, $no_actu, $this->style);
		if ($image) {
			if ($image->get_width() > $this->largeur_max) {$this->largeur_max = $image->get_width();}
		}
	}

	public function ajouter_sommaire($no_actu) {
		$this->tab_sommaire[] = (int) $no_actu;
	}

	public function afficher($mode, $langue) {
		$style_largeur = ($this->largeur_max > 0)?" style=\"max-width:".$this->largeur_max."px;\"":"";
		if (strcmp($mode, _PETILABO_MODE_EDIT)) {
			echo "<div class=\"actu\"".$style_largeur.">"._HTML_FIN_LIGNE;
			echo "<ul id=\"actu\" class=\"rslides boutons_actu\">"._HTML_FIN_LIGNE;
			foreach ($this->tab_sommaire as $entree) {
				$index = (int) $entree;
				$actu = ($index > 0)?$this->tab_actu[$index]:null;
				if ($actu) {$actu->afficher($mode, $langue);}
			}
			echo "</ul>"._HTML_FIN_LIGNE;
			echo "</div>"._HTML_FIN_LIGNE;
			if (strcmp($mode, _PETILABO_MODE_ADMIN)) {
				echo "<script type=\"text/javascript\">"._HTML_FIN_LIGNE;
				echo "$(function() {"._HTML_FIN_LIGNE;
				echo "$(\"#actu\").responsiveSlides({speed:200,timeout:5000,pager:true,nav:true,namespace:'boutons_actu'});"._HTML_FIN_LIGNE;
				echo "});"._HTML_FIN_LIGNE;
				echo "</script>"._HTML_FIN_LIGNE;
			}
		}
		else {
			$this->ouvrir_tableau_multiple(_EDIT_LABEL_BANNIERE_ACTUALITE, _EDIT_COULEUR, null);
			// Sommaire
			$nb_sommaire = count($this->tab_sommaire);
			$this->ouvrir_tableau_simple();
			for ($cpt = 0;$cpt < $nb_sommaire;$cpt++) {
				$no_actu = $this->tab_sommaire[((int) $cpt)];
				$this->ouvrir_ligne();
				if ($cpt == 0) {$this->ecrire_cellule_categorie(_EDIT_LABEL_SOMMAIRE, _EDIT_COULEUR, $nb_sommaire);}
				$this->ecrire_cellule_symbole_sommaire($cpt+1, _EDIT_SYMBOLE_SOMMAIRE);
				$label_actu = ($no_actu > 0)?_EDIT_LABEL_ACTUALITE."&nbsp;n°".$no_actu:"Pas d'actualité";
				$this->ecrire_cellule_texte(strval($cpt), $label_actu);
				$this->fermer_ligne();
			}
			$this->fermer_tableau();
			// Actualités
			for ($cpt = 1;$cpt <= 5;$cpt++) {
				$actu = $this->tab_actu[$cpt];
				if ($actu) {$actu->afficher($mode, $langue);}
			}
			$this->fermer_tableau();
		}
	}
	
	// TODO : A migrer dans la V2.0
	private function edit_actu($no_actu) {
		$this->ouvrir_tableau_simple();
		$this->ouvrir_ligne();
		$titre = _EDIT_LABEL_ACTUALITE."&nbsp;n°".$no_actu;
		$this->ecrire_cellule_categorie($titre, _EDIT_COULEUR, 5);
		$this->ecrire_cellule_symbole_image($image->get_nom(), _EDIT_SYMBOLE_IMAGE);
		$this->ecrire_cellule_image($image->get_src());
		$this->fermer_ligne();
		$id_titre = $this->obj_texte->get_id_titre_actu($no_actu);
		$trad_titre = $this->check_texte($id_titre);
		$this->ouvrir_ligne();
		$this->ecrire_cellule_symbole_texte($id_titre, _EDIT_SYMBOLE_LABEL, "Modifier le titre de l'actualité");
		$this->ecrire_cellule_texte($id_titre, $this->relook_texte($trad_titre));
		$this->fermer_ligne();
		$id_sous_titre = $this->obj_texte->get_id_sous_titre_actu($no_actu);
		$trad_sous_titre = $this->check_texte($id_sous_titre);
		$this->ouvrir_ligne();
		$this->ecrire_cellule_symbole_texte($id_sous_titre, _EDIT_SYMBOLE_LABEL, "Modifier le sous-titre de l'actualité");
		$this->ecrire_cellule_texte($id_sous_titre, $this->relook_texte($trad_sous_titre));
		$this->fermer_ligne();
		$id_resume = $this->obj_texte->get_id_resume_actu($no_actu);
		$trad_resume = $this->check_texte($id_resume);
		$this->ouvrir_ligne();
		$this->ecrire_cellule_symbole_texte($id_resume, _EDIT_SYMBOLE_LABEL, "Modifier le résumé de l'actualité");
		$this->ecrire_cellule_texte($id_resume, $this->relook_texte($trad_resume));
		$this->fermer_ligne();
		$id_texte = $this->obj_texte->get_id_texte_actu($no_actu);
		$trad_texte = $this->check_texte($id_texte);
		$this->ouvrir_ligne();
		$this->ecrire_cellule_symbole_texte($id_texte, _EDIT_SYMBOLE_LABEL, "Modifier le texte d'accompagnement de l'actualité");
		$this->ecrire_cellule_texte($id_texte, $this->relook_texte($trad_texte));
		$this->fermer_ligne();
		$this->fermer_tableau();
	}
}