<?php

class xml_page extends xml_abstract {
	private $page = null;
	private $meta_multilingue = null;private $meta_noindex = null;
	private $has_rs = false;private $has_lb = false;
	private $has_bx = false;private $has_form = false;
	private $contenu = array();
	private $librairie = array();

	public function __construct() {
		$this->enregistrer_chaine("meta_titre", null);$this->enregistrer_chaine("meta_descr", null);
		$this->enregistrer_chaine("meta_titre_editable", null);$this->enregistrer_chaine("meta_descr_editable", null);
		$this->enregistrer_chaine("meta_ga", null);$this->enregistrer_chaine("meta_pa", null);
		$this->enregistrer_entier("nb_actus", 0);
	}
	public function ouvrir($nom, $lib_only = false) {
		$this->page = new xml_struct();
		$ret = $this->page->ouvrir($nom);
		if ($ret) {
			// Lecture des meta titre et description
			$this->lire_balises_meta();

			// Positionnement sur le contenu de la page
			$nb_contenus = $this->page->compter_elements(_PAGE_CONTENU);
			$this->page->pointer_sur_balise(_PAGE_CONTENU);
			$this->page->creer_repere(_PAGE_CONTENU);

			// Parcours des contenus dans la page
			for ($cpt_cont = 0;$cpt_cont < $nb_contenus; $cpt_cont++) {
				$this->page->pointer_sur_repere(_PAGE_CONTENU);
				$src = trim($this->page->lire_n_attribut(_PAGE_ATTR_CONTENU_SRC, $cpt_cont));
				if (strlen($src) > 0) {
					$path = _XML_PATH_LIBRAIRIE.$src."/"._XML_CONTENU._XML_EXT;
					if (file_exists($path)) {
						if (!($lib_only)) {$this->page->charger_sur_index($path, $cpt_cont);}
						$this->librairie[] = $src;
					}
				}
				if ($lib_only) {continue;}
				$signet_contenu = $this->page->lire_n_attribut(_PAGE_ATTR_CONTENU_SIGNET, $cpt_cont);
				$style_contenu = $this->page->lire_n_attribut(_PAGE_ATTR_CONTENU_STYLE, $cpt_cont);
				$semantique_contenu = $this->page->lire_n_attribut(_PAGE_ATTR_CONTENU_SEMANTIQUE, $cpt_cont);
				$obj_contenu = new xml_contenu($signet_contenu, $style_contenu, $semantique_contenu);
				$this->page->pointer_sur_index($cpt_cont);
				$nb_blocs = $this->page->compter_elements(_PAGE_BLOC);
				$this->page->pointer_sur_balise(_PAGE_BLOC);
				$this->page->creer_repere(_PAGE_BLOC);

				// Parcours des blocs dans le contenu
				for ($cpt_bloc = 0;$cpt_bloc < $nb_blocs; $cpt_bloc++) {
					$repere_bloc = _PAGE_BLOC."_".$cpt_cont."_".$cpt_bloc;
					$this->page->pointer_sur_repere(_PAGE_BLOC);
					$this->page->pointer_sur_index($cpt_bloc);
					$this->page->creer_repere($repere_bloc);
					$taille_bloc = $this->page->lire_attribut(_PAGE_ATTR_BLOC_TAILLE);
					$style_bloc = $this->page->lire_attribut(_PAGE_ATTR_BLOC_STYLE);
					$position_bloc = $this->page->lire_attribut(_PAGE_ATTR_BLOC_POSITION);
					$obj_contenu->ajouter_bloc($repere_bloc, $taille_bloc, $style_bloc, $position_bloc);
					
					// On contrôle l'existence d'un carrousel
					$carrousel = $this->page->lire_valeur(_PAGE_CARROUSEL);
					if (strlen($carrousel) > 0) {$this->has_bx = true;}
					// On contrôle l'existence d'une galerie
					$galerie = $this->page->lire_valeur(_PAGE_GALERIE);
					if (strlen($galerie) > 0) {$this->has_bx = true;}
					// On contrôle l'existence d'un diaporama
					$diaporama = $this->page->lire_valeur(_PAGE_DIAPORAMA);
					if (strlen($diaporama) > 0) {$this->has_rs = true;}
					// On contrôle l'existence d'un module d'actualité
					$nb_actus = (int) $this->page->lire_valeur(_PAGE_BANNIERE_ACTU);
					if ($nb_actus > 0) {
						$this->set_nb_actus($nb_actus);
						$this->has_rs = true;
					}
					// On contrôle l'existence d'une lightbox
					$vignettes = $this->page->lire_valeur(_PAGE_VIGNETTES);
					if (strlen($vignettes) > 0) {$this->has_lb = true;}
					// On contrôle l'existence d'un formulaire de contact
					$form_contact = $this->page->lire_valeur(_PAGE_FORM_CONTACT);
					if (strlen($form_contact) > 0) {$this->has_form = true;}

					// Parcours des éléments dans le bloc
					$nb_elems = $this->page->compter_enfants();
					for ($cpt_elem = 0; $cpt_elem < $nb_elems; $cpt_elem++) {
						$balise = $this->page->lire_balise_enfant($cpt_elem);
						$obj_contenu->ajouter_elem_bloc($balise);
					}
				}
				$this->contenu[] = $obj_contenu;
			}
		}
		return $ret;
	}

	public function ouvrir_meta($nom) {
		$this->page = new xml_struct();
		$ret = $this->page->ouvrir($nom);
		if ($ret) {$this->lire_balises_meta();}
		return $ret;
	}

	public function pointer_sur_bloc($repere) {
		if ($this->page) {$this->page->pointer_sur_repere($repere);}
	}
	
	public function lire_valeur_n($balise, $index) {
		$ret = ($this->page)?$this->page->lire_valeur_n($balise, $index):null;
		return $ret;
	}
	
	public function lire_html_n($balise, $index) {
		$ret = ($this->page)?$this->page->lire_html_n($balise, $index):null;
		return $ret;
	}
	
	public function lire_attribut_n($balise, $index, $attribut) {
		$ret = null;
		$repere = $balise."_".$attribut;
		if ($this->page) {
			$this->page->creer_repere($repere);
			$this->page->pointer_sur_balise_n($balise, $index);
			$ret = $this->page->lire_attribut($attribut);
			$this->page->pointer_sur_repere($repere);
		}
		return $ret;
	}

	public function get_nb_contenus() {return count($this->contenu);}
	public function get_contenu($index) {return $this->contenu[$index];}
	public function get_nb_librairies() {return count($this->librairie);}
	public function get_librairie($index) {return $this->librairie[$index];}
	public function get_meta_multilingue() {
		$ret = (strcmp($this->meta_multilingue, _XML_FALSE))?true:false;
		return $ret;
	}
	public function get_meta_noindex() {
		$ret = (strcmp($this->meta_noindex, _XML_TRUE))?false:true;
		return $ret;
	}
	public function has_pa() {return ((strlen($this->meta_pa) > 0)?true:false);}
	public function has_ga() {return ((strlen($this->meta_ga) > 0)?true:false);}
	public function has_bx() {return $this->has_bx;}
	public function has_rs() {return $this->has_rs;}
	public function has_lb() {return $this->has_lb;}
	public function has_form() {return $this->has_form;}

	private function lire_balises_meta() {
		// Lecture des meta titre et description
		$this->set_meta_titre($this->page->lire_valeur(_PAGE_META_TITRE));
		$this->set_meta_descr($this->page->lire_valeur(_PAGE_META_DESCR));
		$this->set_meta_titre_editable($this->page->lire_valeur(_PAGE_META_TITRE_EDITABLE));
		$this->set_meta_descr_editable($this->page->lire_valeur(_PAGE_META_DESCR_EDITABLE));
		$this->set_meta_ga($this->page->lire_valeur(_PAGE_META_GOOGLE_ANALYTICS));
		$this->set_meta_pa($this->page->lire_valeur(_PAGE_META_PETILABO_ANALITIX));
		$multilingue = trim(strtolower($this->page->lire_valeur(_PAGE_META_MULTILINGUE)));
		$this->meta_multilingue = (strlen($multilingue) > 0)?$multilingue:_XML_TRUE;
		$noindex = trim(strtolower($this->page->lire_valeur(_PAGE_META_NOINDEX)));
		$this->meta_noindex = (strlen($noindex) > 0)?$noindex:_XML_FALSE;
	}
}