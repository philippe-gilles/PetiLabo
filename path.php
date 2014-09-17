<?php
	// Chemins pour les inclusions
	define("_XML_PATH_ROOT", "xml/");
	define("_PHP_PATH_ROOT", "petilabo/");
	define("_PHP_PATH_INCLUDE", _PHP_PATH_ROOT."inc/");
	define("_PHP_PATH_SITE", _PHP_PATH_ROOT."site/");
	define("_PXP_EXT", ".php");
	
	function inclure_inc() {
		$tab_args = func_get_args();
		foreach ($tab_args as $fichier) {
			require_once _PHP_PATH_INCLUDE.$fichier._PXP_EXT;
		}
	}
	
	function inclure_site() {
		$tab_args = func_get_args();
		foreach ($tab_args as $fichier) {
			require_once _PHP_PATH_SITE.$fichier._PXP_EXT;
		}
	}