<?php
class xml_abstract {
	private $var_chaine = array();protected $var_entier = array();protected $var_flottant = array();
	protected function enregistrer_entier($nom_propriete, $defaut = 0, $xml_equiv = null) {
		$this->var_entier[$nom_propriete] = array("valeur" => (int) $defaut, "equiv" => $xml_equiv);
	}
	protected function enregistrer_flottant($nom_propriete, $defaut = 0.0, $xml_equiv = null) {
		$this->var_flottant[$nom_propriete] = array("valeur" => (float) $defaut, "equiv" => $xml_equiv);
	}
	protected function enregistrer_chaine($nom_propriete, $defaut = null, $xml_equiv = null) {
		$this->var_chaine[$nom_propriete] = array("valeur" => (string) $defaut, "equiv" => $xml_equiv);
	}
	protected function get($nom_propriete) {
		if (isset($this->var_chaine[$nom_propriete])) {return ((string) $this->var_chaine[$nom_propriete]["valeur"]);}
		elseif (isset($this->var_entier[$nom_propriete])) {return ((int) $this->var_entier[$nom_propriete]["valeur"]);}
		elseif (isset($this->var_flottant[$nom_propriete])) {return ((float) $this->var_flottant[$nom_propriete]["valeur"]);}
		else {return null;}
	}
	protected function set($nom_propriete, $valeur) {
		if (isset($this->var_chaine[$nom_propriete])) {$this->var_chaine[$nom_propriete]["valeur"] = (string) $valeur;}
		elseif (isset($this->var_entier[$nom_propriete])) {$this->var_chaine[$nom_propriete]["valeur"] = (int) $valeur;}
		elseif (isset($this->var_flottant[$nom_propriete])) {$this->var_chaine[$nom_propriete]["valeur"] = (float) $valeur;}
	}
	protected function ins($nom_propriete, $valeur) {
		if (isset($this->var_chaine[$nom_propriete])) {
			if (strlen((string) $valeur) > 0) {$this->var_chaine[$nom_propriete]["valeur"] = (string) $valeur;}
		}
		elseif (isset($this->var_entier[$nom_propriete])) {
			if (((int) $valeur) <> 0) {$this->var_chaine[$nom_propriete]["valeur"] = (int) $valeur;}
		}
		elseif (isset($this->var_flottant[$nom_propriete])) {
			if (((float) $valeur) <> 0) {$this->var_chaine[$nom_propriete]["valeur"] = (float) $valeur;}
		}
	}
	public function load(&$xml_struct, $index) {
		foreach($this->var_chaine as $propriete => $tab) {
			if ((isset($tab["equiv"])) && (strlen($tab["equiv"]) > 0)) {
				$this->var_chaine[$propriete]["valeur"] = (string) $xml_struct->lire_n_valeur($tab["equiv"], $index);
			}
		}
		foreach($this->var_entier as $propriete => $tab) {
			if ((isset($tab["equiv"])) && (strlen($tab["equiv"]) > 0)) {
				$this->var_entier[$propriete]["valeur"] = (int) $xml_struct->lire_n_valeur($tab["equiv"], $index);
			}
		}
		foreach($this->var_flottant as $propriete => $tab) {
			if ((isset($tab["equiv"])) && (strlen($tab["equiv"]) > 0)) {
				$this->var_flottant[$propriete]["valeur"] = (float) $xml_struct->lire_n_valeur($tab["equiv"], $index);
			}
		}
	}
	public function __call($methode, $arguments) {
		if (!(strncmp($methode, "get_", 4))) {
			$propriete = substr($methode, 4);
			return $this->get($propriete);
		}
		elseif (!(strncmp($methode, "set_", 4))) {
			$propriete = substr($methode, 4);
			$this->set($propriete, $arguments[0]);
		}
		elseif (!(strncmp($methode, "ins_", 4))) {
			$propriete = substr($methode, 4);
			$this->ins($propriete, $arguments[0]);
		}
		else {var_dump("ERREUR", $methode, $arguments);echo "<br/>\n";}
	}
}