<?php

class xml_module_actu {
	// Propriétés
	private $xml_struct = null;
	private $style = null;
	private $sommaire = array();

	// Méthodes publiques
	public function ouvrir($nom) {
		$this->xml_struct = new xml_struct();
		$ret = $this->xml_struct->ouvrir($nom);
		if ($ret) {
			$this->style = $this->xml_struct->lire_valeur(_MODULE_ACTU_STYLE);
			$this->sommaire[0] = $this->xml_struct->lire_valeur(_MODULE_ACTU_SOMMAIRE_1);
			$this->sommaire[1] = $this->xml_struct->lire_valeur(_MODULE_ACTU_SOMMAIRE_2);
			$this->sommaire[2] = $this->xml_struct->lire_valeur(_MODULE_ACTU_SOMMAIRE_3);
			$this->sommaire[3] = $this->xml_struct->lire_valeur(_MODULE_ACTU_SOMMAIRE_4);
			$this->sommaire[4] = $this->xml_struct->lire_valeur(_MODULE_ACTU_SOMMAIRE_5);
		}

		return $ret;
	}
	
	public function get_style() {
		return $this->style;
	}
	
	public function get_sommaire($index) {
		return $this->sommaire[$index];
	}
	public function get_prev_actu($no_actu) {
		$ret = 0;$index = $this->get_index_sommaire($no_actu);
		if ($index >= 0) {
			$prev = ($index == 0)?4:$index-1;
			while ($this->sommaire[$prev] == 0) {$prev = ($prev == 0)?4:$prev-1;}
			$ret = $this->sommaire[$prev];
		}
		return $ret;
	}
	public function get_next_actu($no_actu) {
		$ret = 0;$index = $this->get_index_sommaire($no_actu);
		if ($index >= 0) {
			$next = ($index+1) % 5;
			while ($this->sommaire[$next] == 0) {$next = ($next + 1) % 5;}
			$ret = $this->sommaire[$next];
		}
		return $ret;
	}
	public function set_sommaire($index, $no_actu) {
		if ($index > 0) {
			$this->sommaire[($index-1)] = $no_actu;
			// Mise à jour dans la structure XML associée
			$balise = _MODULE_ACTU_SOMMAIRE_.$index;
			$this->xml_struct->pointer_sur_balise($balise);
			$this->xml_struct->set_valeur($no_actu);
			$this->xml_struct->pointer_sur_origine();
		}
	}
	public function enregistrer($fichier) {
		$this->xml_struct->enregistrer($fichier);
	}
	private function get_index_sommaire($no_actu) {
		$ret = -1;
		for ($cpt = 0;(($cpt<5) && ($ret < 0));$cpt++) {$ret=($this->sommaire[$cpt] == $no_actu)?$cpt:$ret;}
		return $ret;
	}
}