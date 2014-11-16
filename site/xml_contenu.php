<?php

	class xml_contenu {
		// Propriétés
		private $semantique = null;
		private $signet = null;
		private $style = null;
		private $blocs = array();
		private $taille_totale = 0;

		// Méthodes publiques
		public function __construct($signet, $style, $semantique = null) {
			if (strlen($semantique) > 0) {$this->semantique = $semantique;}
			if (strlen($signet) > 0) {$this->signet = $signet;}
			if (strlen($style) > 0) {$this->style = $style;}
		}
		public function ajouter_bloc($repere, $taille, $style, $position) {
			if ($taille < 1) {$taille = 1;}
			$this->blocs[] = new xml_bloc($repere, $taille, $style, $position);
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
		public function get_semantique() {return $this->semantique;}
		public function get_signet() {return $this->signet;}
		public function get_style() {return $this->style;}
		public function get_nb_blocs() {return count($this->blocs);}
		public function get_bloc($index) {return $this->blocs[$index];}
		public function get_taille_totale() {return $this->taille_totale;}	
	}