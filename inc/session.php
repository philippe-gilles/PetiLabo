<?php
	// Paramètres pour ini_set php
	define("_PHP_DISPLAY_ERRORS", "display_errors");
	define("_SESSION_USE_TRANS_SID", "session.use_trans_sid");
	define("_SESSION_COOKIE_ONLY", "session.use_only_cookies");
	define("_SESSION_GC_PROBABILITY", "session.gc_probability");
	define("_SESSION_GC_DIVISOR", "session.gc_divisor");
	define("_SESSION_GC_LIFETIME", "session.gc_maxlifetime");
	// define("_URL_SESSION_SAVE_PATH", "sessions");

	// Paramètres de gestion de la session
	define("_SESSION_PARAM_ID", "log");
	define("_SESSION_PARAM_TIME", "time");
	define("_SESSION_PARAM_PAGE", "page");
	define("_SESSION_TIMEOUT", "600");
	define("_SESSION_COOKIE_LIFETIME", "3600");
	define("_SESSION_URL_FERMETURE", _PHP_PATH_ROOT."../index.php");
	
	class session {
		public function ouvrir_session() {
			ini_set(_PHP_DISPLAY_ERRORS, "1");
			ini_set(_SESSION_USE_TRANS_SID, "0");
			ini_set(_SESSION_COOKIE_ONLY, "1");
			ini_set(_SESSION_GC_PROBABILITY, "1");
			ini_set(_SESSION_GC_DIVISOR, "25");
			ini_set(_SESSION_GC_LIFETIME, _SESSION_COOKIE_LIFETIME);
			// session_save_path(getcwd()."/"._URL_SESSION_SAVE_PATH);
			session_start();
			
			return session_id();
		}
		public function fermer_session($fermeture = _SESSION_URL_FERMETURE) {
			$id = session_id();
			if ($id != "") {
				$_SESSION = array();
				session_destroy();
			}
			header("Location: ".$fermeture);
		}
		function check_session() {
			$ret = false;
			// Vérification de l'identifiant
			$id = $this->ouvrir_session();
			if ($id != "") {
				$sess_id = (int) $this->get_session_param(_SESSION_PARAM_ID, false);
				if ($this->checksum_sessid($id) === $sess_id) {
					// Vérification du timeout
					$sess_time = $this->get_session_param(_SESSION_PARAM_TIME, false);
					$sess_lifetime = time() - $sess_time;
					if ($sess_lifetime <= _SESSION_TIMEOUT) {
						// On réarme le timeout
						$this->set_session_param(_SESSION_PARAM_TIME, time());

						// Par sécurité on regénère l'identifiant de session
						$ret = session_regenerate_id();
						if ($ret) {
							$this->set_session_param(_SESSION_PARAM_ID, $this->checksum_sessid(session_id()));
						}
					}
				}
			}
			if (!($ret)) {
				$this->fermer_session();
			}
		}
		public function get_session_param($name) {
			if (is_null($name)) {$ret = null;}
			else if (strlen($name) === 0) {$ret = null;}
			else {
				if (isset($_SESSION[$name])) {
					$ret = $_SESSION[$name];
					if (strlen($ret) === 0) {$ret = null;}
					else {$ret = str_replace("\0", '', $ret);}
				}
				else {$ret = null;}
			}
			return $ret;
		}
		public function set_session_param($name, $value) {
			if ((is_null($name)) || (is_null($value))) {$ret = null;}
			else if ((strlen($name) == 0) || (strlen($value) == 0)) {$ret = null;}
			else {
				$_SESSION[$name] = $value;
				$ret = $value;
			}
			return $ret;
		}
		public function checksum_sessid($id) {
			$ret = (int) 0;
			if (!(is_null($id))) {
				for ($cpt = 0; $cpt < strlen($id); $cpt++) {
					$ret += (ord(substr($id, $cpt, 1))-32);
				}
			}
			return $ret;
		}
	}