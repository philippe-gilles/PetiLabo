<?php

// Valeurs possibles des attributs
define("_MODULE_RESA_ATTR_PARTIE_AM", "am");
define("_MODULE_RESA_ATTR_PARTIE_PM", "pm");
define("_MODULE_RESA_ATTR_STATUT_LIBRE", "libre");
define("_MODULE_RESA_ATTR_STATUT_RESERVE", "reserve");
define("_MODULE_RESA_ATTR_STATUT_OCCUPE", "occupe");
define("_MODULE_RESA_ATTR_STATUT_FERME", "ferme");

class xml_module_calendrier {
	// Propriétés
	private $statuts_am = array();
	private $statuts_pm = array();
	private $fichier = null;

	// Méthodes publiques
	public function __construct($fichier) {
		$xml_struct = new xml_struct();
		$ret = $xml_struct->ouvrir($fichier);
		if ($ret) {
			$this->fichier = $fichier;
			$nb_dates = $xml_struct->compter_elements(_MODULE_RESA_DATE);
			for ($cpt = 0;$cpt < $nb_dates; $cpt++) {
				$xml_struct->pointer_sur_balise(_MODULE_RESA_DATE);
				$date = $xml_struct->lire_n($cpt);
				if (strlen($date) > 0) {
					list($jour, $mois, $an) = explode("/",$date);
					$date_tag = (int) mktime(0, 0, 0, (int) $mois, (int) $jour, (int) $an);
					$statut = $xml_struct->lire_n_attribut(_MODULE_RESA_ATTR_STATUT, $cpt);
					if (strlen($statut) > 0) {
						$partie = $xml_struct->lire_n_attribut(_MODULE_RESA_ATTR_PARTIE, $cpt);
						if (!(strcmp($partie, _MODULE_RESA_ATTR_PARTIE_AM))) {
							$this->statuts_am[$date_tag] = $statut;
						}
						elseif (!(strcmp($partie, _MODULE_RESA_ATTR_PARTIE_PM))) {
							$this->statuts_pm[$date_tag] = $statut;
						}
						else {
							$this->statuts_am[$date_tag] = $statut;
							$this->statuts_pm[$date_tag] = $statut;
						}
					}
				}
				$xml_struct->pointer_sur_origine();
			}
		}
	}
	public function get_statut_am($date) {
		$ret = isset($this->statuts_am[$date])?$this->statuts_am[$date]:_MODULE_RESA_ATTR_STATUT_LIBRE;
		return $ret;
	}
	public function get_statut_pm($date) {
		$ret = isset($this->statuts_pm[$date])?$this->statuts_pm[$date]:_MODULE_RESA_ATTR_STATUT_LIBRE;
		return $ret;
	}
	public function set_statut_am($date, $statut) {
		$this->statuts_am[$date] = $statut;
	}
	public function set_statut_pm($date, $statut) {
		$this->statuts_pm[$date] = $statut;
	}
	public function enregistrer() {
		// TODO : Eliminer les dates trop anciennes (> 40 jours)
		$xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><petilabo></petilabo>");
		foreach ($this->statuts_am as $date_am => $statut_am) {
			if (strcmp($statut_am, _MODULE_RESA_ATTR_STATUT_LIBRE)) {
				$date_format = sprintf("%02d/%02d/%d", date("j", $date_am), date("n", $date_am), date("Y", $date_am));
				$xml_elt = $xml->addChild("date", $date_format);
				$xml_elt->addAttribute("partie", "am");
				$xml_elt->addAttribute("statut", $statut_am);
			}
		}
		foreach ($this->statuts_pm as $date_pm => $statut_pm) {
			if (strcmp($statut_pm, _MODULE_RESA_ATTR_STATUT_LIBRE)) {
				$date_format = sprintf("%02d/%02d/%d", date("j", $date_pm), date("n", $date_pm), date("Y", $date_pm));
				$xml_elt = $xml->addChild("date", $date_format);
				$xml_elt->addAttribute("partie", "pm");
				$xml_elt->addAttribute("statut", $statut_pm);
			}
		}
		$xml->asXML($this->fichier);
	}
}

class xml_module_resa {
	// Propriétés
	private $xml_struct = null;
	private $liste_calendriers = array();

	// Méthodes publiques
	public function ouvrir($nom, $fichier) {
		$ret = false;
		if (!(isset($this->liste_calendriers[$nom]))) {
			if (file_exists($fichier)) {
				$this->liste_calendriers[$nom] = new xml_module_calendrier($fichier);
				$ret = true;
			}
		}
		return $ret;
	}
	public function get_info_resa($nom, $jour, $mois, $an, $nb_jours, &$tab_am, &$tab_pm) {
		$calendrier = $this->liste_calendriers[$nom];
		if ($calendrier) {
			for ($cpt = 0;$cpt < $nb_jours;$cpt++) {
				$date = mktime(0, 0, 0, $mois, (int) $jour + (int) $cpt, $an);
				$tab_am[] = $calendrier->get_statut_am($date);
				$tab_pm[] = $calendrier->get_statut_pm($date);
			}
		}	
	}
	public function set_info_resa($nom, $jour_deb, $mois_deb, $an_deb, $jour_fin, $mois_fin, $an_fin, $statut) {
		$calendrier = $this->liste_calendriers[$nom];
		if ($calendrier) {
			if ((strcmp($statut, _MODULE_RESA_ATTR_STATUT_RESERVE)) && (strcmp($statut, _MODULE_RESA_ATTR_STATUT_OCCUPE)) && (strcmp($statut, _MODULE_RESA_ATTR_STATUT_FERME))) {$statut = _MODULE_RESA_ATTR_STATUT_LIBRE;}
			$date_pm = mktime(0, 0, 0, $mois_deb, $jour_deb, $an_deb);
			$calendrier->set_statut_pm($date_pm, $statut);
			$date_am_pm = mktime(0, 0, 0, $mois_deb, (int) $jour_deb + 1, $an_deb);
			$date_am = mktime(0, 0, 0, $mois_fin, $jour_fin, $an_fin);
			$cpt = 1;
			while ($date_am_pm < $date_am) {
				$calendrier->set_statut_am($date_am_pm, $statut);
				$calendrier->set_statut_pm($date_am_pm, $statut);
				$cpt += 1;
				$date_am_pm = mktime(0, 0, 0, $mois_deb, (int) $jour_deb + (int) $cpt, $an_deb);
			}
			$calendrier->set_statut_am($date_am, $statut);
		}
	}
	public function enregistrer($nom) {
		$calendrier = $this->liste_calendriers[$nom];
		if ($calendrier) {
			$calendrier->enregistrer();
		}
	}
}