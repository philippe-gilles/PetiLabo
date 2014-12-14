<?php
	// Le refus est acté pour un mois
	$duree = 3600*24*30;
	setcookie("petilabo_google_analytics", "nok", time() + $duree, "/");

	// Retour à la page appelante
	$referer = (isset($_SERVER['HTTP_REFERER']))?$_SERVER['HTTP_REFERER']:null;
	if (strlen($referer) == 0) {
		$referer = str_replace(basename($_SERVER['SCRIPT_NAME']), "../../index.php", $_SERVER['REQUEST_URI']);
	}
	header('Location: '.$referer);