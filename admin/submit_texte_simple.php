<?php

	require_once "inc/path.php";
	inclure_inc("const", "param", "session");
	inclure_site("xml_const", "xml_texte");

	$session = new session();
	if (is_null($session)) {header("Location: "._SESSION_URL_FERMETURE);exit;}
	$session->check_session();
	$page = $session->get_session_param(_SESSION_PARAM_PAGE);
	if (strlen($page) == 0) {
		$session->fermer_session();
		header("HTTP/1.0 404 Not Found");
		exit;
	}
	$param = new param();
	$id_texte = $param->post("id_texte");
	if (strlen($id_texte) == 0) {
		$session->fermer_session();
		header("HTTP/1.0 404 Not Found");
		exit;
	}
	$src_texte = $param->post("src_texte");
	if (strlen($src_texte) == 0) {
		$session->fermer_session();
		header("HTTP/1.0 404 Not Found");
		exit;
	}
	if (!(strcmp($src_texte, _XML_SOURCE_SITE))) {$fichier_xml = _XML_PATH._XML_TEXTE._XML_EXT;}
	elseif (!(strcmp($src_texte, _XML_SOURCE_PAGE))) {$fichier_xml = _XML_PATH_PAGES.$page."/"._XML_TEXTE._XML_EXT;}
	elseif (!(strcmp($src_texte, _XML_SOURCE_MODULE))) {$fichier_xml = _XML_PATH_MODULES._XML_TEXTE._XML_EXT;}
	else {
		$session->fermer_session();
		header("HTTP/1.0 404 Not Found");
		exit;
	}
	$xml_texte = new xml_texte();
	$xml_texte->ouvrir($src_texte, $fichier_xml);
	$existe = $xml_texte->existe_texte($id_texte);
	if ($existe) {
		$texte = "";
		$langue = $xml_texte->get_langue_par_defaut();
		$trad = $param->post($langue, false);
		if (strlen($trad) > 0) {
			$sec_trad = $xml_texte->secure_xml($trad);
			$texte = "{".$langue."}".trim($sec_trad);
		}
		$xml_texte->set_texte($id_texte, $texte);
		$xml_texte->enregistrer($fichier_xml);
	}
	// Redirection finale
	header("Location: index.php");