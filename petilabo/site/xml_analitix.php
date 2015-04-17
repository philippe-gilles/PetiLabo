<?php

class xml_analitix {
	// Propriétés
	private $nom_fitre_ip = null;
	private $liste_ip = array();
	private $nom_fitre_pays = null;
	private $liste_pays = array();
	private $nom_fitre_ref = null;
	private $liste_ref = array();
	private $nom_config = null;
	private $anonymisation_ip = true;
	private $respect_dnt = true;

	public function ouvrir($nom_config, $en_ligne) {
		$xml_analitix = new xml_struct();
		$ret = $xml_analitix->ouvrir(_XML_PATH._XML_ANALITIX._XML_EXT);
		if ($ret) {
			// Traitement de la configuration
			$nb_config = $xml_analitix->compter_elements(_ANALITIX_CONFIG);
			$xml_analitix->pointer_sur_balise(_ANALITIX_CONFIG);
			$xml_analitix->creer_repere(_ANALITIX_CONFIG);
			for ($cpt = 0;$cpt < $nb_config; $cpt++) {
				$xml_analitix->pointer_sur_repere(_ANALITIX_CONFIG);
				$xml_analitix->pointer_sur_index($cpt);
				$nom_attr = (string) $xml_analitix->lire_attribut(_XML_NOM);
				if (!(strcmp($nom_config, $nom_attr))) {
					$this->nom_config = $nom_config;
					$this->nom_fitre_ip = $xml_analitix->lire_n_valeur(_ANALITIX_CONFIG_FILTRE_IP, $cpt);
					$this->nom_fitre_pays = $xml_analitix->lire_n_valeur(_ANALITIX_CONFIG_FILTRE_PAYS, $cpt);
					$this->nom_fitre_ref = $xml_analitix->lire_n_valeur(_ANALITIX_CONFIG_FILTRE_REFERENTS, $cpt);
					$param_anonymisation = strtolower(trim($xml_analitix->lire_n_valeur(_ANALITIX_CONFIG_ANONYMISATION_IP, $cpt)));
					$this->anonymisation_ip = (strcmp($param_anonymisation, _XML_FALSE))?true:false;
					$param_dnt = strtolower(trim($xml_analitix->lire_n_valeur(_ANALITIX_CONFIG_RESPECT_DNT, $cpt)));
					$this->respect_dnt = (strcmp($param_dnt, _XML_FALSE))?true:false;
					break;
				}
			}
			// Traitement des listes d'IP
			if (strlen($this->nom_fitre_ip) > 0) {
				$xml_analitix->pointer_sur_origine();
				$nb_liste_ip = $xml_analitix->compter_elements(_ANALITIX_LISTE_IP);
				$xml_analitix->pointer_sur_balise(_ANALITIX_LISTE_IP);
				$xml_analitix->creer_repere(_ANALITIX_LISTE_IP);
				for ($cpt = 0;$cpt < $nb_liste_ip; $cpt++) {
					$xml_analitix->pointer_sur_repere(_ANALITIX_LISTE_IP);
					$xml_analitix->pointer_sur_index($cpt);
					$nom_attr = (string) $xml_analitix->lire_attribut(_XML_NOM);
					if (!(strcmp($this->nom_fitre_ip, $nom_attr))) {
						$nb_ip = $xml_analitix->compter_elements(_ANALITIX_IP);
						for ($cpt_ip = 0; $cpt_ip < $nb_ip; $cpt_ip++) {
							$valeur = $xml_analitix->lire_valeur_n(_ANALITIX_IP, $cpt_ip);
							$this->liste_ip[] = $valeur;
						}
						break;
					}
				}
			}

			// Traitement des listes de pays
			if (strlen($this->nom_fitre_pays) > 0) {
				$xml_analitix->pointer_sur_origine();
				$nb_liste_pays = $xml_analitix->compter_elements(_ANALITIX_LISTE_PAYS);
				$xml_analitix->pointer_sur_balise(_ANALITIX_LISTE_PAYS);
				$xml_analitix->creer_repere(_ANALITIX_LISTE_PAYS);
				for ($cpt = 0;$cpt < $nb_liste_pays; $cpt++) {
					$xml_analitix->pointer_sur_repere(_ANALITIX_LISTE_PAYS);
					$xml_analitix->pointer_sur_index($cpt);
					$nom_attr = (string) $xml_analitix->lire_attribut(_XML_NOM);
					if (!(strcmp($this->nom_fitre_pays, $nom_attr))) {
						$nb_pays = $xml_analitix->compter_elements(_ANALITIX_PAYS);
						for ($cpt_pays = 0; $cpt_pays < $nb_pays; $cpt_pays++) {
							$valeur = $xml_analitix->lire_valeur_n(_ANALITIX_PAYS, $cpt_pays);
							$this->liste_pays[] = $valeur;
						}
						break;
					}
				}
			}
			// Traitement des listes de référents
			if (strlen($this->nom_fitre_ref) > 0) {
				$xml_analitix->pointer_sur_origine();
				$nb_liste_ref = $xml_analitix->compter_elements(_ANALITIX_LISTE_REFERENTS);
				$xml_analitix->pointer_sur_balise(_ANALITIX_LISTE_REFERENTS);
				$xml_analitix->creer_repere(_ANALITIX_LISTE_REFERENTS);
				for ($cpt = 0;$cpt < $nb_liste_ref; $cpt++) {
					$xml_analitix->pointer_sur_repere(_ANALITIX_LISTE_REFERENTS);
					$xml_analitix->pointer_sur_index($cpt);
					$nom_attr = (string) $xml_analitix->lire_attribut(_XML_NOM);
					if (!(strcmp($this->nom_fitre_ref, $nom_attr))) {
						$nb_ref = $xml_analitix->compter_elements(_ANALITIX_REFERENT);
						for ($cpt_ref = 0; $cpt_ref < $nb_ref; $cpt_ref++) {
							$valeur = $xml_analitix->lire_valeur_n(_ANALITIX_REFERENT, $cpt_ref);
							$this->liste_ref[] = $valeur;
						}
						break;
					}
				}
			}
		}
		return $ret;
	}
	public function get_filtre_ip() {return $this->liste_ip;}
	public function get_filtre_pays() {return $this->liste_pays;}
	public function get_filtre_referents() {return $this->liste_ref;}
	public function get_anonymisation_ip() {return $this->anonymisation_ip;}
	public function get_respect_dnt() {return $this->respect_dnt;}
}