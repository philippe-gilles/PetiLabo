<?php
	require_once "inc/path.php";

	$session = new session();
	if (is_null($session)) {
		header("Location: "._SESSION_URL_FERMETURE);
		exit;
	}
	$id = $session->ouvrir_session();
	if (strlen($id) > 0) {
		$page = $session->get_session_param(_SESSION_PARAM_PAGE);
		$est_actu = preg_match("/^"._HTML_PREFIXE_ACTU."-[1-5]$/", $page);
		if ($est_actu == 1) {
			$no_actu = (int) substr($page, 1+strlen(_HTML_PREFIXE_ACTU));
			$page_fermeture = _PHP_PATH_ROOT."../"._HTML_PATH_ACTU."?"._PARAM_ID."=".$no_actu;;
		}
		else {
			$page_fermeture = _PHP_PATH_ROOT."../".$page._PXP_EXT;
		}
		$session->fermer_session($page_fermeture);
	}