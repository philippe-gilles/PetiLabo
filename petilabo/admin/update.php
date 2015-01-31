<?php
	require_once "inc/path.php";
	
	$session = new session();
	if (is_null($session)) {header("Location: "._SESSION_URL_FERMETURE);exit;}
	$session->check_session();	
	$page = $session->get_session_param(_SESSION_PARAM_PAGE);
	if (strlen($page) == 0) {
		$session->fermer_session();
		header("HTTP/1.0 404 Not Found");
		exit;
	}

	$page_php = $page._PXP_EXT;
	$referer = basename($_SERVER["HTTP_REFERER"]);
	if (strcmp($referer, "index.php")) {
		$session->fermer_session();
		header("HTTP/1.0 404 Not Found");
		exit;
	}
	$param = new param();
	$no_version = $param->get("v");
	if (preg_match("/\d+(?:\.\d+)+/", $no_version) == 0) {
		$session->fermer_session();
		header("HTTP/1.0 404 Not Found");
		exit;
	}

	$html = new html();
	$html->ouvrir();
	$html->ouvrir_head();
	$html->ecrire_meta_noindex();
	$html->ecrire_meta_titre("Mise à jour de PetiLabo");
	echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"css/update.css\" />\n";
	$html->fermer_head();
	echo "<body>\n";

	echo "<p style=\"text-align:center;\"><img src=\"images/logo.png\" alt=\"Logo PetiLabo\"></p>\n";
	echo "<h1>Installation de la version ".$no_version." depuis la page ".$page."</h1>\n";

	echo "<div class=\"container\">\n";

	$url_source_zip = _PETIXML_CHEMIN_VERSION_TXT._PETIXML_PREFIXE_FICHIER_ZIP.str_replace(".", "-", $no_version)._PETIXML_SUFFIXE_FICHIER_ZIP;
	$destination_zip = "petilabo.zip";
	$dossier_tmp = "./tmp/";
	if (!(@is_dir($dossier_tmp))) {@mkdir("tmp");}
	if (@is_dir($dossier_tmp)) {
		$path_destination_zip = $dossier_tmp.$destination_zip;
		echo "<p>Téléchargement du fichier ".basename($url_source_zip)." dans un dossier temporaire ...</p>\n";
		@unlink($path_destination_zip);
		$ret = @copy($url_source_zip, $path_destination_zip);
		if (($ret) && (is_readable($path_destination_zip))) {
			echo "<p>Téléchargement terminé.</p>\n";
			$zip = new ZipArchive;
			if ($zip->open($path_destination_zip) === true) {
				echo "<p>Décompression de l'archive ZIP...</p>\n";
				$zip->extractTo(_PHP_PATH_ROOT);
				$zip->close();
				echo "<p>Décompression terminée...</p>\n";
			} else {
			echo "<p style=\"color:#800;\">Impossible de décompresser l'archive ZIP.</p>\n";
			}
			echo "<p>Suppression du dossier temporaire.</p>\n";
			@unlink($path_destination_zip);
			@rmdir($dossier_tmp);
		}
		else {
			echo "<p style=\"color:#800;\">Impossible de télécharger l'archive ZIP.</p>\n";
		}
	}
	else {
		echo "<p style=\"color:#800;\">Impossible de créer le dossier temporaire de téléchargement.</p>\n";
	}
	echo "<p class=\"update_bouton_retour\"><a href=\"index.php\" title=\"Retourner à la page ".$page."\">Retour en mode administration</a></p>\n";
	echo "</div>\n";
	$html->fermer_body();
	$html->fermer();

	
