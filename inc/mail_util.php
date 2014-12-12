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

// Constantes sur le retour SMTP
define("_MAIL_NOERR", "0");
define("_MAIL_ERR_CAPTCHA", "1");
define("_MAIL_ERR_AUTRE", "2");

// Constantes pour le contrôle des formats
define("_CHAMP_FORMAT_TEXTE", "TXT");
define("_CHAMP_FORMAT_TEL", "TEL");
define("_CHAMP_FORMAT_EMAIL", "MEL");

// Valeur du champ "action"
define("_MAIL_ACTION_SENDMAIL", "send");

class champ_mail_util {
	private $obligatoire;
	private $nom;
	private $format;
	
	public function __construct($obligatoire, $nom, $format) {
		$this->obligatoire = $obligatoire;
		$this->nom = $nom;
		$this->format = $format;
	}
	
	public function check($param) {
		$ret = _CHAMP_NOERR;
		if (strlen($param) == 0) {
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
					if (!(preg_match($syntaxe,$param))) {
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
	private $param = null;
	private $info = array();
	private $tab_champs = array();
	
	public function __construct() {
		$this->param = 	new param();
		$xml_site = new xml_struct();
		$ret = $xml_site->ouvrir(_XML_PATH._XML_GENERAL._XML_EXT);
		if ($ret) {
			$dest = $xml_site->lire_valeur(_SITE_DESTINATAIRE);
			$racine = $xml_site->lire_valeur(_SITE_RACINE);
			$this->info[_SITE_DESTINATAIRE] = $dest;
			$this->info[_SITE_RACINE] = $racine;
		}
	}

	// Accesseur
	public function get_info() {return $this->info;}

	// TODO : Gestion automatisée des paramètres
	public function ajouter_param($obligatoire, $nom, $format) {
		$this->tab_champs[$nom] = new champ_mail_util($obligatoire, $nom, $format);
	}
	
	public function lire_params() {
		$ret = array();
		foreach ($this->tab_champs as $nom => $champ) {
			$ret[] = $this->param->post_stripquotes($nom);
		}
		return $ret;
	}

	// Contrôle le champ action du formulaire 
	public function check_action() {
		$action = $this->post_stripquotes_param("action");
		$ret = (strcmp($action,_MAIL_ACTION_SENDMAIL))?false:true;
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

	// Récupère un paramètre post pour l'envoi de message
	public function post_stripquotes_param($name) {
		$ret = $this->param->post_stripquotes($name);
		return $ret;
	}

	// Envoi d'un mail
	public function send_mail($nom, $emetteur, $destinataire, $titre, $message) {
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
		return $ret;
	}
}