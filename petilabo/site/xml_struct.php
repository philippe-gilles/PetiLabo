<?php
class xml_struct {
	// Propriétés
	private $xml = false;
	private $pointeur = null;
	private $reperes = array();

	// Méthodes publiques
	public function ouvrir($fichier) {
		if (@file_exists($fichier)) {
			// Sécurité : lisible uniquement par le serveur
			@chmod($fichier, 0600);
			// Ouverture avec gestion des éventuels xi:include
			$this->xml = new SimpleXMLElement($fichier, 0, true);
			$dom = dom_import_simplexml($this->xml);
			$dom->ownerDocument->xinclude();
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
	public function add_balise($balise) {
		if ($this->pointeur) {
			$this->pointeur->addChild($balise);
		}
	}
	public function add_balise_valeur($balise, $valeur) {
		if ($this->pointeur) {
			$this->pointeur->addChild($balise, $valeur);
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
	public function lire_html_n($balise, $index) {
		$ret = null;
		if ($this->pointeur) {
			$tab = &$this->pointeur->$balise;
			$elt = $tab[$index];
			foreach ($elt as $elt_html) {
				$ret .= $elt_html->asXML();
			}
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
	public function charger_sur_index($fichier, $index) {
		$ret = false;
		if ($this->pointeur) {
			$xmlReplace = new SimpleXMLElement($fichier, 0, true);
			if ($xmlReplace) {
				$domToChange = dom_import_simplexml($this->pointeur[$index]);
				$domReplace = dom_import_simplexml($xmlReplace);
				$nodeImport = $domToChange->ownerDocument->importNode($domReplace, TRUE);
				$domToChange->parentNode->replaceChild($nodeImport, $domToChange);
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
		// Méthode pour un enregistrement formatté
		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($this->xml->asXML());
		$dom->save($fichier);
	}
	public function afficher() {
		echo "<pre>\n";
		print_r($this->pointeur);
		echo "</pre>\n";
	}
}  