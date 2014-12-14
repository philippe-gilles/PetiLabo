<?php
	require_once "inc/path.php";
	require_once "user/user.php";
	inclure_inc("const", "param", "session");
	
	define("_MSG_ERREUR_CONNEXION", "0");
	define("_MSG_SUCCES_CONNEXION", "1");

	$param = new param();
	
	// Contrôle du code secret
	$code_secret = $param->post(_PARAM_CODE_SECRET);
	if (strlen($code_secret) > 0) {
		echo _MSG_ERREUR_CONNEXION;
		exit;
	}

	// Contrôle de la page à administrer
	$page = $param->post(_PARAM_PAGE);
	if (strlen($page) == 0) {
		echo _MSG_ERREUR_CONNEXION;
		exit;
	}
	// Cas particulier de la page actu
	if (!(strcmp($page,_HTML_PREFIXE_ACTU))) {
		echo _MSG_ERREUR_CONNEXION;
		exit;
	}
	$est_actu = preg_match("/^"._HTML_PREFIXE_ACTU."-[1-5]$/", $page);
	if ($est_actu == 1) {$dossier = _XML_PATH_PAGES._HTML_PREFIXE_ACTU;}
	else {$dossier = _XML_PATH_PAGES.$page;}
	if (!(file_exists($dossier))) {
		echo _MSG_ERREUR_CONNEXION;
		exit;
	}

	// Contrôle de l'identifiant
	$id = $param->post(_PARAM_ID);
	if (strcmp($id, _ID_CONNEXION)) {
		echo _MSG_ERREUR_CONNEXION;
		exit;
	}
	
	// Contrôle du mot de passe
	$hash_mdp = $param->post(_PARAM_HASH_MDP);
	if (strlen($hash_mdp) == 0) {
		$hash_mdp = sha1($param->post(_PARAM_MDP));
	}

	if (strcmp($hash_mdp, _MDP_CONNEXION)) {
		echo _MSG_ERREUR_CONNEXION;
		exit;
	}
	
	// Réussite de la connexion
	$session = new session();
	if (is_null($session)) {
		echo _MSG_ERREUR_CONNEXION;
		exit;
	}
	
	$session_id = $session->ouvrir_session();
	if (strlen($session_id) == 0) {
		echo _MSG_ERREUR_CONNEXION;
		exit;
	}

	$session->set_session_param(_SESSION_PARAM_ID, $session->checksum_sessid($session_id));
	$session->set_session_param(_SESSION_PARAM_TIME, time());
	$session->set_session_param(_SESSION_PARAM_PAGE, $page);
	
	echo _MSG_SUCCES_CONNEXION;