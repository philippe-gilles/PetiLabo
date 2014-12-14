<?php
	require_once "mail_util.php";

	// Initialisation des paramètres
	$mail_util = new mail_util();
	$mail_util->ajouter_param(true, "action", _CHAMP_FORMAT_ACTION);
	$mail_util->ajouter_param(true, "nom", _CHAMP_FORMAT_TEXTE);
	$mail_util->ajouter_param(true, "email", _CHAMP_FORMAT_EMAIL);
	$mail_util->ajouter_param(true, "message", _CHAMP_FORMAT_TEXTE);

	// Contrôle des champs du formulaire
	$err = $mail_util->creer_err_json(_MAIL_NOERR, $json);
	if ($err) {
		echo $json;
		return false;
	}
	
	// Récupération des valeurs
	list($action, $nom, $email, $msg) = $mail_util->lire_params();
	
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
		$ret = $mail_util->envoi_mail($nom, $email, $destinataire, $titre, $message);
		$err = $mail_util->creer_err_json($ret, $json);
		echo $json;
		return $err;
	}
	else {
		$mail_util->creer_err_json(_MAIL_ERR_AUTRE, $json);
		echo $json;
		return false;
	}