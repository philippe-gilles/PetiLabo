<?php
	inclure_site("xml_const");

	class moteur_bloc {
		// Propriétés
		private $repere = null;
		private $taille = 0;
		private $style = null;
		private $position = null;
		private $elems = array();
		private $idx_elems = array();
		private $nb_elems_admin = 0;
		private $premier_titre = -1;

		// Méthodes publiques
		public function __construct($repere, $taille, $style, $position) {
			$this->repere = $repere;
			$this->taille = $taille;
			$this->position = $this->normaliser_position($position);
			if (strlen($style) > 0) {
				$this->style = $style;
			}
		}
		public function ajouter_elem($balise) {
			$tab_non_admin = array(_PAGE_DRAPEAUX, _PAGE_SAUT, _PAGE_FORM_CONTACT, _PAGE_PLAN_DU_SITE, _PAGE_CREDITS, _PAGE_MENTIONS_LEGALES);
			$tab_occ = array_count_values($this->elems);
			$nb_occ = isset($tab_occ[$balise])?(int) $tab_occ[$balise]:0;
			$this->elems[] = $balise;
			$this->idx_elems[] = $nb_occ;
			// On incrémente le nombre d'éléments administrables
			if (!(in_array($balise, $tab_non_admin))) {
				$this->nb_elems_admin += 1;
			}
			/* On stocke l'index du premier titre */
			if (($this->premier_titre < 0) && (!(strcmp($balise, _PAGE_TITRE)))) {
				$this->premier_titre = count($this->elems)-1;
			}
		}
		public function set_balise_elem($index, $balise) {
			if ($index >= 0) {
				$this->elems[$index] = $balise;
				$this->idx_elems[$index] = 0;
			}
			return true;
		}

		public function get_nb_elems() {return count($this->elems);}
		public function get_elem($index) {return $this->elems[$index];}
		public function get_idx_elem($index) {return $this->idx_elems[$index];}
		public function get_repere() {return $this->repere;}
		public function get_taille() {return $this->taille;}
		public function get_style() {return $this->style;}
		public function get_position() {return $this->position;}
		public function get_nb_elems_admin() {return $this->nb_elems_admin;}
		public function get_premier_titre() {return $this->premier_titre;}
		
		private function normaliser_position($param) {
			$ret = $param;
			if (strlen($ret) > 0) {
				$ret = trim(strtolower($ret));
				if ((strcmp($ret, _PAGE_ATTR_ALIGNEMENT_HAUT)) && (strcmp($ret, _PAGE_ATTR_ALIGNEMENT_BAS))) {
					$ret = _PAGE_ATTR_ALIGNEMENT_MILIEU;
				}
			}
			return $ret;
		}
	}