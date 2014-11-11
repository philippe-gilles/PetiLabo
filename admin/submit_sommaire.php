<?php
	require_once "inc/path.php";

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
	$id_sommaire = (int) $param->post("id_sommaire");
	if (($id_sommaire < 1) || ($id_sommaire > 5)) {
		$session->fermer_session();
		header("HTTP/1.0 404 Not Found");
		exit;
	}
	$no_actu = (int) $param->post("no_actu");
	if (($no_actu < 0) || ($no_actu > 5)) {
		$session->fermer_session();
		header("HTTP/1.0 404 Not Found");
		exit;
	}

	$fichier_xml = _XML_PATH_MODULES._XML_MODULE_ACTU._XML_EXT;
	$xml_actu = new xml_module_actu();
	$ret = $xml_actu->ouvrir($fichier_xml);
	if ($ret) {
		$xml_actu->set_sommaire($id_sommaire, $no_actu);
		$xml_actu->enregistrer($fichier_xml);
	}
	
	// Redirection finale
	$id_tab = $param->post(_PARAM_FRAGMENT);
	$ret_page = preparer_redirection($session, $id_tab);
	header("Location: ".$ret_page);