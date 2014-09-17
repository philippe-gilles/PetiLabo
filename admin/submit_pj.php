<?php
	require_once "inc/path.php";
	inclure_inc("const", "param", "session");
	inclure_site("xml_const", "xml_document");

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
	$id_pj = $param->post("id_pj");
	if (strlen($id_pj) == 0) {
		$session->fermer_session();
		header("HTTP/1.0 404 Not Found");
		exit;
	}

	$nom_pj = $param->post("upload_name_pj");
	if (strlen($nom_pj) == 0) {
		$session->fermer_session();
		header("HTTP/1.0 404 Not Found");
		exit;
	}

	$source = getcwd()."/"._UPLOAD_DOSSIER.$nom_pj;
	if (file_exists($source)) {
		$xml_doc = new xml_document();
		$xml_doc->ouvrir(_XML_PATH._XML_DOCUMENT._XML_EXT);
		$xml_doc->ouvrir(_XML_PATH_PAGES.$page."/"._XML_DOCUMENT._XML_EXT);
		$doc = $xml_doc->get_document($id_pj);
		if ($doc) {
			$destination = getcwd()."/".$doc->get_fichier();
			@rename($source, $destination);
		}
	}

	// Redirection finale
	$self = $_SERVER["PHP_SELF"];
	$url = str_replace("submit_pj.php", "index.php", $self);
	header("Location: ".$url);