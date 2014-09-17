<?php
class xml_struct {
	// Propriétés
	private $xml = false;
	private $pointeur = null;
	private $reperes = array();

	// Méthodes publiques
	public function ouvrir($fichier) {
		if (file_exists($fichier)) {
			$this->xml = simplexml_load_file($fichier);
			$this->pointeur = &$this->xml;
		}
		else {
			$this->xml = false;
		}

		return ($this->xml !== false);
	}

	public function lire_attribut($attribut) {
		$ret = null;

		if ($this->pointeur) {
			$attr = $this->pointeur->attributes();
			$ret = (string) $attr[$attribut];
		}
		
		return $ret;
	}

	public function lire_n_attribut($attribut, $index=0) {
		$ret = null;

		if ($this->pointeur) {
			$tab = &$this->pointeur;
			$elem = $tab[$index];
			$attr = $elem->attributes();
			$ret = (string) $attr[$attribut];
		}
		
		return $ret;
	}
	
	public function lire_n($index) {
		$ret = null;

		if ($this->pointeur) {
			$tab = &$this->pointeur;
			$ret = (string) $tab[$index];
		}
		
		return $ret;
	}

	public function lire_valeur($balise) {
		$ret = null;

		if ($this->pointeur) {
			$ret = (string) $this->pointeur->$balise;
		}
		
		return $ret;
	}
	
	public function set_valeur($valeur) {
		if ($this->pointeur) {
			$this->pointeur[0] = $valeur;
		}
	}

	public function lire_n_valeur($balise, $index) {
		$ret = null;

		if ($this->pointeur) {
			$tab = &$this->pointeur;
			$elem = $tab[$index];
			$ret = (string) $elem->$balise;
		}
		
		return $ret;
	}

	public function lire_valeur_n($balise, $index) {
		$ret = null;

		if ($this->pointeur) {
			$tab = &$this->pointeur->$balise;
			$ret = (string) $tab[$index];
		}
		
		return $ret;
	}

	public function compter_elements($balise) {
		$ret = 0;

		if ($this->pointeur) {
			$ret = count($this->pointeur->$balise);
		}
		
		return $ret;
	}
	
	public function compter_enfants() {
		$ret = 0;
		
		if ($this->pointeur) {
			$ret = count($this->pointeur->children());
		}
		
		return $ret;
	}

	public function pointer_sur_balise($balise) {
		$ret = false;

		if ($this->pointeur) {
			$this->pointeur = &$this->pointeur->$balise;
			if ($this->pointeur) {
				$ret = true;
			}
		}
		
		return $ret;
	}

	public function pointer_sur_balise_n($balise, $index) {
		$ret = false;

		if ($this->pointeur) {
			$this->pointeur = &$this->pointeur->$balise;
			if ($this->pointeur) {
				$this->pointeur = &$this->pointeur[$index];
				if ($this->pointeur) {
					$ret = true;
				}
			}
		}
		
		return $ret;
	}

	public function pointer_sur_index($index) {
		$ret = false;

		if ($this->pointeur) {
			$this->pointeur = &$this->pointeur[$index];
			if ($this->pointeur) {
				$ret = true;
			}
		}
		
		return $ret;
	}

	public function pointer_sur_origine() {
		$this->pointeur = &$this->xml;
		
		return true;
	}
	public function lire_balise_enfant($rang) {
		$ret = null;

		if ($this->pointeur) {
			$tab = $this->pointeur->children();
			$ret = (string) $tab[$rang]->getName();
		}
		
		return $ret;
	}
	public function lire_valeur_enfant($rang) {
		$ret = null;

		if ($this->pointeur) {
			$tab = $this->pointeur->children();
			$ret = (string) $tab[$rang]->asXML();

		}
		
		return $ret;
	}

	public function creer_repere($nom) {
		$this->reperes[$nom] = &$this->pointeur;
	}

	public function pointer_sur_repere($nom) {
		$this->pointeur = &$this->reperes[$nom];
	}

	public function enregistrer($fichier) {
		// $xml = $this->xml->asXML();
		// var_dump($xml);
		// $xml = html_entity_decode($xml, ENT_QUOTES, 'UTF-8');
		// var_dump($xml);
		// $xml_utf8 = mb_convert_encoding($xml, 'UTF-8');
		// $xml = str_replace("&amp;", "&", $xml);
		// $file = fopen($fichier, "w");
		// fwrite($file, $xml);
		// fclose($file);
		$this->xml->asXML($fichier);
	}

	public function afficher() {
		echo "<pre>\n";
		print_r($this->pointeur);
		echo "</pre>\n";
	}
}  