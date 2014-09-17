<?php
	// Paramètres
	define("_PARAM_LANGUE", "l");
	define("_PARAM_MOBILE", "m");
	define("_PARAM_PAGE", "page");
	define("_PARAM_ID", "id");
	define("_PARAM_TYPE", "t");
	define("_PARAM_ID_LISTE", "il");
	define("_PARAM_MDP", "mdp");
	define("_PARAM_HASH_MDP", "sha1mdp");
	define("_PARAM_CODE_SECRET", "cs");

	class param {
		public function get($name, $htmlentities = true) {
			if (is_null($name)) {$ret = null;}
			else if (strlen($name) == 0) {$ret = null;}
			else {
				if (isset($_GET[$name])) {
					$ret = $_GET[$name];
					if (strlen($ret) == 0) {$ret = null;}
					else {$ret = $this->clean_param($ret, $htmlentities);}
				}
				else {$ret = null;}
			}
			return $ret;
		}
		public function post($name, $htmlentities = true) {
			if (is_null($name)) {$ret = null;}
			else if (strlen($name) == 0) {$ret = null;}
			else {
				if (isset($_POST[$name])) {
					$ret = $_POST[$name];
					if (strlen($ret) == 0) {$ret = null;}
					else {$ret = $this->clean_param($ret, $htmlentities);}
				}
				else {$ret = null;}
			}
			return $ret;
		}
		public function post_stripquotes($name) {
			$ret = $this->post($name, false);
			if (get_magic_quotes_gpc()) {
				if (!(is_null($ret))) {
					$ret = stripslashes($ret);
				}
			}
			return $ret;
		}
		private function clean_param($str, $htmlentities = true) {
			if (!is_null($str)) {
				// Protection contre le null byte poisonning
				$str = str_replace("\0", '', $str);
				// Traitement des magic quotes
				if (get_magic_quotes_gpc()) {
					$str = stripslashes($str);
				}
				// Suppression des espaces à gauche et à droite
				$str = trim($str);
				// Element de protection contre les attaques XSS
				if ($htmlentities) {
					$str = htmlentities($str, ENT_COMPAT | ENT_XHTML, "UTF-8");
				}
			}
			return $str;
		}
	}