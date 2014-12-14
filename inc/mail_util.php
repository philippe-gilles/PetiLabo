<?php

define("_XML_PATH_ROOT", "../../xml/");
define("_PHP_PATH_ROOT", "../");
define("_PHP_PATH_INCLUDE", "./");
define("_PHP_PATH_SITE", _PHP_PATH_ROOT."site/");
define("_PXP_EXT", ".php");

require_once "const.php";
require_once "param.php";
require_once _PHP_PATH_SITE."xml_const.php";
require_once _PHP_PATH_SITE."xml_struct.php";

// Constantes sur le contrôle des champs
define("_CHAMP_NOERR", "0");	
define("_CHAMP_ERR_VIDE", "1");
define("_CHAMP_ERR_INCORRECT", "2");
define("_CHAMP_ERR_AUTRE", "3");

// Nom du champ "envoi" dans JSON
define("_MAIL_CHAMP_ENVOI", "send");

// Constantes sur le retour SMTP
define("_MAIL_NOERR", "0");
define("_MAIL_ERR_CAPTCHA", "1");
define("_MAIL_ERR_AUTRE", "2");
define("_MAIL_ERR_FLOODING", "3");

// Constantes pour le contrôle des formats
define("_CHAMP_FORMAT_TEXTE", "TXT");
define("_CHAMP_FORMAT_TEL", "TEL");
define("_CHAMP_FORMAT_EMAIL", "MEL");
define("_CHAMP_FORMAT_ACTION", "ACT");

// Valeur du champ "action" dans le formulaire
define("_MAIL_ACTION_SENDMAIL", "send");

class ip_flood_info {
	private $ip = null;
	private $horodatage_succes = -1;
	private $horodatage_echec = -1;
	private $comptage_envois = 0;
	
	public function __construct($ip) {
		$this->ip = $ip;
	}
	
	// Accesseurs et manipulateurs
	public function get_ip() {return $this->ip;}
	public function get_horodatage_succes() {return $this->horodatage_succes;}
	public function get_horodatage_echec() {return $this->horodatage_echec;}
	public function get_comptage_envois() {return $this->comptage_envois;}
	public function set_horodatage_succes($param) {$this->horodatage_succes = (int) $param;}
	public function set_horodatage_echec($param) {$this->horodatage_echec = (int) $param;}
	public function set_comptage_envois($param) {$this->comptage_envois = (int) $param;}
	public function maj_horodatage_succes() {$this->horodatage_succes = time();}
	public function maj_horodatage_echec() {$this->horodatage_echec = time();}
	public function maj_comptage_envois() {$this->comptage_envois += 1;}
}

class champ_mail_util {
	private $param = null;
	private $obligatoire = false;
	private $nom = null;
	private $format = null;
	private $valeur = null;
	
	public function __construct($obligatoire, $nom, $format) {
		$this->param = 	new param();
		$this->obligatoire = $obligatoire;
		$this->nom = $nom;
		$this->format = $format;
		$this->valeur = $this->param->post_stripquotes($this->nom);
	}
	public function lire() {
		return $this->valeur;
	}
	public function check() {
		$ret = _CHAMP_NOERR;
		if (strlen($this->valeur) == 0) {
			if ($this->obligatoire) {
				$ret = _CHAMP_ERR_VIDE;
			}
		}
		else {
			switch ($this->format) {
				case _CHAMP_FORMAT_TEXTE :
					break;
				case _CHAMP_FORMAT_TEL :
					break;
				case _CHAMP_FORMAT_EMAIL :
					$syntaxe="#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#";  
					if (!(preg_match($syntaxe,$this->valeur))) {
						$ret = _CHAMP_ERR_INCORRECT;
					}
					break;
				case _CHAMP_FORMAT_ACTION :
					if (strcmp($this->valeur,_MAIL_ACTION_SENDMAIL)) {
						$ret = _CHAMP_ERR_INCORRECT;
					}
					break;
				default :
					break;
			}
		}
		return $ret;
	}
}

class mail_util {
	private $balise_ip_courante = null;
	private $anti_flooding = true;
	private $info = array();
	private $tab_champs = array();
	private $delai_apres_succes = 6;
	private $delai_apres_echec = 2;
	private $max_envois_par_jour = 8;
	private $tab_journal = array();
	
	public function __construct() {
		// Récup de l'IP client (pas d'antiflooding si echec)
		$this->balise_ip_courante = $this->get_balise_ip();
		if (strlen($this->balise_ip_courante) == 0) {
			$this->anti_flooding = false;
		}
		// Récupération du mail destinataire et de l'URL racine
		$xml_site = new xml_struct();
		$ret = $xml_site->ouvrir(_XML_PATH._XML_GENERAL._XML_EXT);
		if ($ret) {
			$dest = $xml_site->lire_valeur(_SITE_DESTINATAIRE);
			$racine = $xml_site->lire_valeur(_SITE_RACINE);
			$this->info[_SITE_DESTINATAIRE] = $dest;
			$this->info[_SITE_RACINE] = $racine;
		}
		if ($this->anti_flooding) {
			// Récupération de la configuration anti-flooding
			$this->lire_config();
			// Initialisation du journal anti-flooding
			$this->lire_journal();
		}
	}

	// Accesseur
	public function get_info() {return $this->info;}

	// Ajout d'un parametre post
	public function ajouter_param($obligatoire, $nom, $format) {
		$this->tab_champs[$nom] = new champ_mail_util($obligatoire, $nom, $format);
	}
	
	// Lecture de l'ensemble des paramètres
	public function lire_params() {
		$ret = array();
		foreach ($this->tab_champs as $champ) {
			$ret[] = $champ->lire();
		}
		return $ret;
	}

	// Contrôle un élément de message en fonction du format
	public function check_param($nom, $param) {
		if (array_key_exists((string) $nom, $this->tab_champs)) {
			$champ = $this->tab_champs[$nom];
			$ret = $champ->check($param);
		}
		else {
			$ret = _CHAMP_ERR_AUTRE;
		}
		return $ret;
	}
	
	public function creer_err_json($envoi_err, &$objet_json) {
		$ret = (strcmp($envoi_err, _MAIL_NOERR))?true:false;
		$tab_err = array();
		foreach ($this->tab_champs as $nom => $champ) {
			$check = $champ->check();
			$ret = $ret || ($check != _CHAMP_NOERR);
			$tab_err[$nom] = $check;
		}
		$tab_err[_MAIL_CHAMP_ENVOI] = $envoi_err;
		$objet_json = json_encode($tab_err);
		return $ret;
	}

	// Envoi d'un mail
	public function envoi_mail($nom, $emetteur, $destinataire, $titre, $message) {
		$autorisation = $this->check_flooding();
		if ($autorisation) {
			$headers = "From: ".$nom." <".$emetteur.">\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=utf-8\r\n";
			$returnpath = "-f".$emetteur;
			if (mail($destinataire, $titre, $message, $headers, $returnpath)) {
				$ret = _MAIL_NOERR;
			}
			else {
				$ret = _MAIL_ERR_AUTRE;
			}
			if ($this->anti_flooding) {
				$this->rafraichir_journal($ret);
			}
		}
		else {
			$ret = _MAIL_ERR_FLOODING;
		}

		return $ret;
	}
	
	// Gestion du flooding
	public function check_flooding() {
		$ret = true;
		if ($this->anti_flooding) {
			if (array_key_exists($this->balise_ip_courante, $this->tab_journal)) {
				$obj_ip = $this->tab_journal[$this->balise_ip_courante];
				if ($obj_ip) {
					$horodatage_reference = time();
					$horodatage_echec = $obj_ip->get_horodatage_echec();
					$horodatage_succes = $obj_ip->get_horodatage_succes();
					$comptage_envois = $obj_ip->get_comptage_envois();
					$delai_echec = $horodatage_reference - $horodatage_echec;
					$delai_succes = $horodatage_reference - $horodatage_succes;
					$ret = (($delai_echec > $this->delai_apres_echec) && ($delai_succes > $this->delai_apres_succes) && ($comptage_envois < $this->max_envois_par_jour));
				}
			}
		}
		return $ret;
	}
	
	private function get_balise_ip() {
		$balise_ip = null;
		$adresse_ip = trim(strtolower($this->get_adresse_ip()));
		if (strlen($adresse_ip) > 0) {
			$balise_ip = "ip_".strtr($adresse_ip, ":.", "__");
		}
		return $balise_ip;
	}

	private function lire_config() {
		$nom_config = _XML_PATH_MAIL._XML_MAIL_CONFIG._XML_EXT;
		if (@file_exists($nom_config)) {@chmod($nom_config, 0600);}
		$xml_config_mail = new xml_struct();
		$ret = $xml_config_mail->ouvrir($nom_config);
		if ($ret) {
			$delai = $xml_config_mail->lire_valeur(_MAIL_CONFIG_DELAI_SUCCES);
			if (strlen($delai) > 0) {
				$val_delai = (int) $delai;
				if ($val_delai > 0) {$this->delai_apres_succes = $val_delai;}
			}
			$delai = $xml_config_mail->lire_valeur(_MAIL_CONFIG_DELAI_ECHEC);
			if (strlen($delai) > 0) {
				$val_delai = (int) $delai;
				if ($val_delai > 0) {$this->delai_apres_echec = $val_delai;}
			}
			$max = $xml_config_mail->lire_valeur(_MAIL_CONFIG_MAX_JOUR);
			if (strlen($max) > 0) {
				$val_max = (int) $max;
				if ($val_max > 0) {$this->max_envois_par_jour = $val_max;}
			}
		}
	}
	private function lire_journal() {
		$nom_journal = _XML_PATH_MAIL._XML_MAIL_JOURNAL._XML_EXT;
		if (!(@file_exists($nom_journal))) {
			$nom_modele_journal = _XML_PATH_INTERNE._XML_MAIL_MODELE_JOURNAL._XML_EXT;
			$this->anti_flooding = @copy($nom_modele_journal, $nom_journal);
		}
		if ($this->anti_flooding) {
			$xml_journal = new xml_struct();
			$this->anti_flooding = $xml_journal->ouvrir($nom_journal);
		}
		if  ($this->anti_flooding) {
			@chmod($nom_journal, 0600);
			$nb_ip = $xml_journal->compter_enfants();
			for ($cpt_ip = 0; $cpt_ip < $nb_ip; $cpt_ip++) {
				$ip = $xml_journal->lire_balise_enfant($cpt_ip);
				if (strlen($ip) > 0) {
					$this->tab_journal[$ip] = new ip_flood_info($ip);
				}
			}
			foreach ($this->tab_journal as $adr_ip => $obj_ip) {
				$xml_journal->pointer_sur_origine();
				$xml_journal->pointer_sur_balise($adr_ip);
				$horodatage_succes = $xml_journal->lire_valeur(_MAIL_FLOOD_HORODATAGE_SUCCES);
				if (strlen($horodatage_succes) > 0) {$obj_ip->set_horodatage_succes($horodatage_succes);}
				$horodatage_echec = $xml_journal->lire_valeur(_MAIL_FLOOD_HORODATAGE_ECHEC);
				if (strlen($horodatage_echec) > 0) {$obj_ip->set_horodatage_echec($horodatage_echec);}
				$comptage_envois = $xml_journal->lire_valeur(_MAIL_FLOOD_COMPTAGE_ENVOIS);
				if (strlen($comptage_envois) > 0) {$obj_ip->set_comptage_envois($comptage_envois);}
			}
		}
	}
	
	private function rafraichir_journal($retour) {
		if (array_key_exists($this->balise_ip_courante, $this->tab_journal)) {
			$obj_ip = $this->tab_journal[$this->balise_ip_courante];
			if ($obj_ip) {
				if ($retour == _MAIL_NOERR) {
					$obj_ip->maj_horodatage_succes();
					$obj_ip->maj_comptage_envois();
				}
				else {
					$obj_ip->maj_horodatage_echec();
				}
			}
		}
		else {
			$obj_ip = new ip_flood_info($this->balise_ip_courante);
			if ($obj_ip) {
				if ($retour == _MAIL_NOERR) {
					$obj_ip->maj_horodatage_succes();
					$obj_ip->maj_comptage_envois();
				}
				else {
					$obj_ip->maj_horodatage_echec();
				}
				$this->tab_journal[$this->balise_ip_courante] = $obj_ip;
			}
		}
		$this->ecrire_journal();
	}
	
	private function ecrire_journal() {
		$nom_journal = _XML_PATH_MAIL._XML_MAIL_JOURNAL._XML_EXT;
		$nom_modele_journal = _XML_PATH_INTERNE._XML_MAIL_MODELE_JOURNAL._XML_EXT;
		$this->anti_flooding = @copy($nom_modele_journal, $nom_journal);
		if ($this->anti_flooding) {
			$xml_journal = new xml_struct();
			$this->anti_flooding = $xml_journal->ouvrir($nom_journal);
		}
		if  ($this->anti_flooding) {
			@chmod($nom_journal, 0600);
			$horodatage_reference = time();
			foreach ($this->tab_journal as $adr_ip => $obj_ip) {
				$horodatage_echec = $obj_ip->get_horodatage_echec();
				$horodatage_succes = $obj_ip->get_horodatage_succes();
				$comptage_envois = $obj_ip->get_comptage_envois();
				$delai_echec = $horodatage_reference - $horodatage_echec;
				$delai_succes = $horodatage_reference - $horodatage_succes;
				// Les infos datant de plus de 24 heures sont purgées
				if (($delai_echec <= 86400) || ($delai_succes <=  86400)) {
					$xml_journal->pointer_sur_origine();
					$xml_journal->add_balise($adr_ip);
					$xml_journal->pointer_sur_balise($adr_ip);
					$xml_journal->add_balise_valeur(_MAIL_FLOOD_HORODATAGE_ECHEC, $horodatage_echec);
					$xml_journal->add_balise_valeur(_MAIL_FLOOD_HORODATAGE_SUCCES, $horodatage_succes);
					$xml_journal->add_balise_valeur(_MAIL_FLOOD_COMPTAGE_ENVOIS, $comptage_envois);
				}
			}
			$xml_journal->pointer_sur_origine();
			$xml_journal->enregistrer($nom_journal);
		}
	}

	private function get_adresse_ip() {
		$adresse_ip = null;
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {$adresse_ip = $_SERVER['HTTP_CLIENT_IP'];}
		else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {$adresse_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];}
		else if(isset($_SERVER['HTTP_X_FORWARDED'])) {$adresse_ip = $_SERVER['HTTP_X_FORWARDED'];}
		else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {$adresse_ip = $_SERVER['HTTP_FORWARDED_FOR'];}
		else if(isset($_SERVER['HTTP_FORWARDED'])) {$adresse_ip = $_SERVER['HTTP_FORWARDED'];}
		else if(isset($_SERVER['REMOTE_ADDR'])) {$adresse_ip = $_SERVER['REMOTE_ADDR'];}
		return $adresse_ip;
	}
}