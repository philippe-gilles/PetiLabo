<?php

class xml_page {
	// Propriétés
	private $page = null;
	private $meta_titre = null;
	private $meta_descr = null;
	private $meta_titre_editable = null;
	private $meta_descr_editable = null;
	private $meta_multilingue = null;
	private $contenu = array();
	private $nb_actus = 0;

	public function ouvrir($nom) {
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
					if (strlen($carrousel) > 0) {$obj_contenu->set_has_bx(true);}
					// On contrôle l'existence d'une galerie
					$galerie = $this->page->lire_valeur(_PAGE_GALERIE);
					if (strlen($galerie) > 0) {$obj_contenu->set_has_bx(true);}
					// On contrôle l'existence d'un diaporama
					$diaporama = $this->page->lire_valeur(_PAGE_DIAPORAMA);
					if (strlen($diaporama) > 0) {$obj_contenu->set_has_rs(true);}
					// On contrôle l'existence d'un module d'actualité
					$nb_actus = (int) $this->page->lire_valeur(_PAGE_BANNIERE_ACTU);
					if ($nb_actus > 0) {$this->nb_actus = $nb_actus;$obj_contenu->set_has_rs(true);}
					// On contrôle l'existence d'une lightbox
					$vignettes = $this->page->lire_valeur(_PAGE_VIGNETTES);
					if (strlen($vignettes) > 0) {$obj_contenu->set_has_lb(true);}
					// On contrôle l'existence d'un formulaure de contact
					$form_contact = $this->page->lire_valeur(_PAGE_FORM_CONTACT);
					if (strlen($form_contact) > 0) {$obj_contenu->set_has_form(true);}

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
		$ret = null;
		if ($this->page) {$ret = $this->page->lire_valeur_n($balise, $index);}
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
	public function get_meta_titre() {return $this->meta_titre;}
	public function get_meta_descr() {return $this->meta_descr;}
	public function get_meta_titre_editable() {return $this->meta_titre_editable;}
	public function get_meta_descr_editable() {return $this->meta_descr_editable;}
	public function get_meta_multilingue() {
		$ret = (strcmp($this->meta_multilingue, _XML_FALSE))?true:false;
		return $ret;
	}
	public function get_nb_actus() {return $this->nb_actus;}
	
	private function lire_balises_meta() {
		// Lecture des meta titre et description
		$this->meta_titre = $this->page->lire_valeur(_PAGE_META_TITRE);
		$this->meta_descr = $this->page->lire_valeur(_PAGE_META_DESCR);
		$this->meta_titre_editable = $this->page->lire_valeur(_PAGE_META_TITRE_EDITABLE);
		$this->meta_descr_editable = $this->page->lire_valeur(_PAGE_META_DESCR_EDITABLE);
		$multilingue = trim(strtolower($this->page->lire_valeur(_PAGE_META_MULTILINGUE)));
		$this->meta_multilingue = (strlen($multilingue) > 0)?$multilingue:_XML_TRUE;
	}
}