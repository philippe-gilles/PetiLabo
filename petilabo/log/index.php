<?php
	require_once "inc/path.php";
	inclure_inc("const", "param", "html");

	$param = new param();
	$page = $param->get(_PARAM_PAGE);
	if (strlen($page) == 0) {
		header("HTTP/1.0 404 Not Found");
		exit;
	}
	// Cas particulier de la page actu
	if (!(strcmp($page,_HTML_PREFIXE_ACTU))) {
		header("HTTP/1.0 404 Not Found");
		exit;
	}
	$est_actu = preg_match("/^"._HTML_PREFIXE_ACTU."-[1-5]$/", $page);
	if ($est_actu == 1) {
		$no_actu = (int) substr($page, 1+strlen(_HTML_PREFIXE_ACTU));
		$dossier = _XML_PATH_PAGES._HTML_PREFIXE_ACTU;
		$page_retour = _PHP_PATH_ROOT._HTML_PATH_ACTU."?"._PARAM_ID."=".$no_actu;
	}
	else {
		$dossier = _XML_PATH_PAGES.$page;
		$page_retour = _PHP_PATH_ROOT.$page._PXP_EXT;
	}
	
	// Vérification de l'existence de la page
	if (!(file_exists($dossier))) {
		header("HTTP/1.0 404 Not Found");
		exit;
	}

	$html = new html();
	$html->ouvrir();
	$html->ouvrir_head();
	$html->ecrire_meta_noindex();
	$html->ecrire_meta_titre("Accès privé");
	echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"css/log.css\" />\n";
	// Chargement JQuery depuis CDN
	$html->charger_js_cdn("//code.jquery.com/jquery-1.12.0.min.js");
	$html->charger_js_cdn("//code.jquery.com/jquery-migrate-1.2.1.min.js");
	// Chargement plugins
	$html->charger_js(_HTTP_LOG_PREFIXE."/js/hash.js");
	$html->charger_js(_HTTP_LOG_PREFIXE."/js/log.js");
	$html->fermer_head();
	echo "<body>\n";
?>
	<div class="container">
	<h1>Accès privé</h1>
	<p class="status_msg">Identifiant ou mot de passe erroné&nbsp;!</p>
	<div class="panneau">
	<form id="id_log" method="post" action="connect.php">
	<p class="wrap_champ"><label for="id_id">Identifiant</label><input class="champ" type="text" id="id_id" name="id" /></p>
	<p class="wrap_autre_champ"><label for="id_cs">Code secret</label><input class="champ" type="text" id="id_cs" name="cs" /></p>
	<p class="wrap_champ"><label for="id_mdp">Mot de passe</label><input class="champ" type="password" id="id_mdp" name="mdp" /></p>
	<p class="wrap_champ"><input type="hidden" id="id_page" name="page" value="<?php echo $page;?>" /><input type="hidden" id="id_sha1mdp" name="sha1mdp" value="" /></p>
	<p class="wrap_champ"><input class="bouton" type="submit" name="submit" value="Connexion" /></p>
	<div style="clear:both;"></div>
	<p class="lien_retour"><a href="../<?php echo $page_retour; ?>" title="Quitter l'accès privé">Quitter l'accès privé</a></p>
	</form>
	</div>
	</div>
<?php
	$html->fermer_body();
	$html->fermer();