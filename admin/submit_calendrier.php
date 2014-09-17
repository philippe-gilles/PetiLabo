<?php
	require_once "inc/path.php";
	inclure_inc("const", "param", "session");
	inclure_site("xml_const", "xml_module_resa");

	$session = new session();
	if (is_null($session)) {
		header("Location: "._SESSION_URL_FERMETURE);
		exit;
	}

	$session->check_session();

	$page = $session->get_session_param(_SESSION_PARAM_PAGE);
	if (strlen($page) == 0) {
		$session->fermer_session();
		header("HTTP/1.0 404 Not Found");
		exit;
	}

	$param = new param();
	$id_cal = $param->post("id_calendrier");
	if (strlen($id_cal) == 0) {
		$session->fermer_session();
		header("HTTP/1.0 404 Not Found");
		exit;
	}
	$date_debut = $param->post("date_debut");
	$date_fin = $param->post("date_fin");
	$statut = $param->post("statut");
	if ((strlen($date_debut) > 0) && (strlen($date_fin) > 0) && (strlen($statut) > 0)) {
		$module_resa = new xml_module_resa();
		$ret = $module_resa->ouvrir($id_cal, _XML_PATH_MODULES.$id_cal."/"._XML_MODULE_RESA._XML_EXT);
		if ($ret) {
			list($jour_deb, $mois_deb, $an_deb) = explode("/",$date_debut);
			list($jour_fin, $mois_fin, $an_fin) = explode("/",$date_fin);
			$module_resa->set_info_resa($id_cal, (int) $jour_deb, (int) $mois_deb, (int) $an_deb, (int) $jour_fin, (int) $mois_fin, (int) $an_fin, $statut);
			$module_resa->enregistrer($id_cal);
		}
	}
	
	// Redirection finale
	header("Location: index.php");