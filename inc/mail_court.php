<?php
	require_once "mail_util.php";

	// Construit si nécessaire l'encodage JSON du tableau des erreurs pour le retour AJAX
	function build_err_array(	$check_send = _MAIL_NOERR,
								$check_action = _CHAMP_NOERR,
								$check_nom = _CHAMP_NOERR, 
								$check_email = _CHAMP_NOERR, 
								$check_msg = _CHAMP_NOERR) {
		$ret = ($check_action != _CHAMP_NOERR);
		$ret = $ret || ($check_nom != _CHAMP_NOERR);
		$ret = $ret || ($check_email != _CHAMP_NOERR);
		$ret = $ret || ($check_msg != _CHAMP_NOERR);
		$ret = $ret || ($check_send != _MAIL_NOERR);

		$err = NULL;
		if ($ret) {
			$tab_err = array(	"action" => $check_action,
								"nom" => $check_nom,
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
							"email" => _CHAMP_NOERR,
							"message" => _CHAMP_NOERR,
							"send" => _MAIL_NOERR);
		$noerr = json_encode($tab_noerr);
		
		return $noerr;
	}

	// Récupération des paramètres
	$mail_util = new mail_util();
	$mail_util->ajouter_param(true, "nom", _CHAMP_FORMAT_TEXTE);
	$mail_util->ajouter_param(true, "email", _CHAMP_FORMAT_EMAIL);
	$mail_util->ajouter_param(true, "message", _CHAMP_FORMAT_TEXTE);

	list($nom, $email, $msg) = $mail_util->lire_params();

	// Contrôle des champs du formulaire
	$check_action = $mail_util->check_action();
	if (!($check_action)) {
		$err = build_err_array(_MAIL_NOERR, _CHAMP_ERR_INCORRECT);
		if (!(is_null($err))) {
			echo $err;
			return false;
		}
	}
	$check_nom = $mail_util->check_param("nom", $nom);
	$check_email = $mail_util->check_param("email", $email);
	$check_msg = $mail_util->check_param("message", $msg);
	$err = build_err_array(_MAIL_NOERR, _CHAMP_NOERR, $check_nom, $check_email, $check_msg);
	if (!(is_null($err))) {
		echo $err;
		return false;
	}
	
	// Fabrication du message
	$tab_info = $mail_util->get_info();
	$racine = $tab_info[_SITE_RACINE];
	$destinataire = $tab_info[_SITE_DESTINATAIRE];
	if ($destinataire) {
		$titre = "Demande de contact depuis le site ".$racine;
		$message = "De la part de : ".$nom."<br>";
		$message .= "Adresse email : ".$email."<br>";
		$message .= "Message : <br>";
		$message .= nl2br($msg);

		// Envoi du message
		$ret = $mail_util->send_mail($nom, $email, $destinataire, $titre, $message);
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