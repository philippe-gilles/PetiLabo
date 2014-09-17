<?php
	inclure_site("moteur_bloc");

	class moteur_contenu {
		// Propriétés
		private $semantique = null;
		private $signet = null;
		private $style = null;
		private $blocs = array();
		private $taille_totale = 0;
		private $has_rs = false;
		private $has_lb = false;
		private $has_gal = false;
		private $has_form = false;

		// Méthodes publiques
		public function __construct($signet, $style, $semantique = null) {
			if (strlen($semantique) > 0) {$this->semantique = $semantique;}
			if (strlen($signet) > 0) {$this->signet = $signet;}
			if (strlen($style) > 0) {$this->style = $style;}
		}
		public function ajouter_bloc($repere, $taille, $style, $position) {
			if ($taille < 1) {
				$taille = 1;
			}
			$this->blocs[] = new moteur_bloc($repere, $taille, $style, $position);
			$this->taille_totale += (int) $taille;
		}

		public function ajouter_elem_bloc($balise) {
			$nb_blocs = count($this->blocs);
			if ($nb_blocs > 0) {
				$index = ((int) $nb_blocs) - 1;
				$bloc = $this->blocs[$index];
				if ($bloc) {
					$bloc->ajouter_elem($balise);
				}
			}
		}
		public function set_has_rs($param) {$this->has_rs = ($param)?true:false;}
		public function set_has_lb($param) {$this->has_lb = ($param)?true:false;}
		public function set_has_gal($param) {$this->has_gal = ($param)?true:false;}
		public function set_has_form($param) {$this->has_form = ($param)?true:false;}
		public function get_has_rs() {return $this->has_rs;}
		public function get_has_lb() {return $this->has_lb;}
		public function get_has_gal() {return $this->has_gal;}
		public function get_has_form() {return $this->has_form;}
		public function get_semantique() {return $this->semantique;}
		public function get_signet() {return $this->signet;}
		public function get_style() {return $this->style;}
		public function get_nb_blocs() {return count($this->blocs);}
		public function get_bloc($index) {return $this->blocs[$index];}
		public function get_taille_totale() {return $this->taille_totale;}	
	}