<?php
	require_once "inc/path.php";
	
	// Correction pour les ancres non validées
	function jqte_safe_string($texte) {
		$ancre_jqte = "<a jqte-setlink=\"\"";$ancre_href = "<a href=\"#\"";
		$ret = str_replace($ancre_jqte, $ancre_href, $texte);
		return trim($ret);
	}

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
	elseif (!(strncmp($src_texte, _XML_SOURCE_LIBRAIRIE, strlen(_XML_SOURCE_LIBRAIRIE)))) {
		$nom_librairie = substr($src_texte, strlen(_XML_SOURCE_LIBRAIRIE)+1);
		$fichier_xml = _XML_PATH_LIBRAIRIE.$nom_librairie."/"._XML_TEXTE._XML_EXT;
	}
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
		$tab_langues = $xml_texte->get_tab_langues();
		foreach ($tab_langues as $code_langue) {
			$trad = $param->post($code_langue, false);
			if (strlen($trad) > 0) {
				$sec_trad = $xml_texte->strip_tags_attributes($trad);
				$sec_safe = jqte_safe_string($sec_trad);
				$texte .= "{".$code_langue."}".$sec_safe;
			}
		}
		$xml_texte->set_texte($id_texte, $texte);
		$xml_texte->enregistrer($fichier_xml);
	}
	// Redirection finale
	$id_tab = $param->post(_PARAM_FRAGMENT);
	$ret_page = preparer_redirection($session, $id_tab);
	header("Location: ".$ret_page);