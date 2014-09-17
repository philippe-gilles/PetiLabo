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

	// Contrôle un élément de message en fonction du format
	function check_param($obligatoire, $param, $format = _CHAMP_FORMAT_TEXTE) {
		$ret = _CHAMP_NOERR;
		if (strlen($param) == 0) {
			if ($obligatoire) {
				$ret = _CHAMP_ERR_VIDE;
			}
		}
		else {
			switch ($format) {
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

	// Construit si nécessaire l'encodage JSON du tableau des erreurs pour le retour AJAX
	function build_err_array(	$check_send = _MAIL_NOERR,
								$check_action = _CHAMP_NOERR,
								$check_nom = _CHAMP_NOERR, 
								$check_prenom = _CHAMP_NOERR, 
								$check_tel = _CHAMP_NOERR, 
								$check_email = _CHAMP_NOERR, 
								$check_msg = _CHAMP_NOERR) {
		$ret = ($check_action != _CHAMP_NOERR);
		$ret = $ret || ($check_nom != _CHAMP_NOERR);
		$ret = $ret || ($check_prenom != _CHAMP_NOERR);
		$ret = $ret || ($check_tel != _CHAMP_NOERR);
		$ret = $ret || ($check_email != _CHAMP_NOERR);
		$ret = $ret || ($check_msg != _CHAMP_NOERR);
		$ret = $ret || ($check_send != _MAIL_NOERR);

		$err = NULL;
		if ($ret) {
			$tab_err = array(	"action" => $check_action,
								"nom" => $check_nom,
								"prenom" => $check_prenom,
								"tel" => $check_tel,
								"email" => $check_email,
								"message" => $check_msg,
								"send" => $check_send);
			$err = json_encode($tab_err);
		}
		return $err;
	}
	
	// Construit l'encodage JSON d'un tableau sans erreurs pour AJAX
	function build_noerr_array() {
		$tab_noerr = array(	"action" => _CHAMP_NOERR,
							"nom" => _CHAMP_NOERR,
							"prenom" => _CHAMP_NOERR,
							"tel" => _CHAMP_NOERR,
							"email" => _CHAMP_NOERR,
							"message" => _CHAMP_NOERR,
							"send" => _MAIL_NOERR);
		$noerr = json_encode($tab_noerr);
		
		return $noerr;
	}

	// Récupère un paramètre post pour l'envoi de message
	function post_stripquotes_param($name) {
		$ret = post_string_param($name, false);
		if (get_magic_quotes_gpc()) {
			if (!(is_null($ret))) {
				$ret = stripslashes($ret);
			}
		}
		
		return $ret;
	}
	
	// Envoi d'un mail
	function send_mail($nom, $prenom, $emetteur, $destinataire, $titre, $message) {
		$headers = "From: ".$prenom." ".$nom." <".$emetteur.">\r\n";
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
	
	
	// Récupération du destinataire
	function get_info() {
		$info = array();

		$xml_site = new xml_struct();
		$ret = $xml_site->ouvrir(_XML_PATH._XML_GENERAL._XML_EXT);
		if ($ret) {
			$dest = $xml_site->lire_valeur(_SITE_DESTINATAIRE);
			$racine = $xml_site->lire_valeur(_SITE_RACINE);
			$info[_SITE_DESTINATAIRE] = $dest;
			$info[_SITE_RACINE] = $racine;
		}
		
		return $info;
	}

	// Récupération des paramètres
	$param = new param();
	$nom = $param->post_stripquotes("nom");
	$prenom = $param->post_stripquotes("prenom");
	$tel = $param->post_stripquotes("tel");
	$email = $param->post_stripquotes("email");
	$msg = $param->post_stripquotes("message");
	$action = $param->post_stripquotes("action");
	
	// Contrôle des champs du formulaire
	if ($action != _MAIL_ACTION_SENDMAIL)	{
		$err = build_err_array(_MAIL_NOERR, _CHAMP_ERR_INCORRECT);
		if (!(is_null($err))) {
			echo $err;
			return false;
		}
	}

	$check_nom = check_param(true, $nom);
	$check_prenom = check_param(true, $prenom);
	$check_tel = check_param(false, $tel, _CHAMP_FORMAT_TEL);
	$check_email = check_param(true, $email, _CHAMP_FORMAT_EMAIL);
	$check_msg = check_param(true, $msg);
	$err = build_err_array(_MAIL_NOERR, _CHAMP_NOERR, $check_nom, $check_prenom, $check_tel, $check_email, $check_msg);
	if (!(is_null($err))) {
		echo $err;
		return false;
	}
	
	// Fabrication du message
	$tab_info = get_info();
	$racine = $tab_info[_SITE_RACINE];
	$destinataire = $tab_info[_SITE_DESTINATAIRE];
	if ($destinataire) {
		$titre = "Demande de contact depuis le site ".$racine;
		$message = "De la part de : ".$prenom." ".$nom."<br>";
		$message .= "Adresse email : ".$email."<br>";
		$message .= "Téléphone : ".((strlen($tel) > 0)?$tel:"Non renseigné")."<br>";
		$message .= "Message : <br>";
		$message .= nl2br($msg);

		// Envoi du message
		$ret = send_mail($nom, $prenom, $email, $destinataire, $titre, $message);
		if ($ret <> _MAIL_NOERR) {
			$err = build_err_array($ret);
			if (!(is_null($err))) {
				echo $err;
				return false;
			}
		}
		else {
			$no_err = build_noerr_array();
			echo $no_err;
			return true;
		}
	}
	else {
		$err = build_err_array(_MAIL_ERR_AUTRE);
		if (!(is_null($err))) {
			echo $err;
			return false;
		}
	}