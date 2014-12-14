<?php
	define("_COOKIES_NOM_VISITE", "petilabo_landing_page");
	define("_COOKIES_NOM_GOOGLE_ANALYTICS", "petilabo_google_analytics");
	define("_COOKIES_VALEUR_DEJA_VALIDE", "petilabo_deja_valide");

	class cookies {
		private $nom_page = null;

		public function __construct($nom_page) {
			$this->nom_page = $nom_page;
		}
	
		public function init() {
			$cookie_flag = isset($_COOKIE[_COOKIES_NOM_VISITE]);
			if ($cookie_flag) {
				if ((strcmp($_COOKIE[_COOKIES_NOM_VISITE], $this->nom_page)) && (strcmp($_COOKIE[_COOKIES_NOM_VISITE], _COOKIES_VALEUR_DEJA_VALIDE))) {
					$_COOKIE[_COOKIES_NOM_GOOGLE_ANALYTICS] = "ok";
					setcookie(_COOKIES_NOM_GOOGLE_ANALYTICS, "ok", time() + 3600*24*30*13, "/");
					setcookie(_COOKIES_NOM_VISITE, _COOKIES_VALEUR_DEJA_VALIDE, 0, "/");
				}
			}
			else {
				setcookie(_COOKIES_NOM_VISITE, $this->nom_page, 0, "/");
			}
		}
		
		public function is_set() {
			$cookie_set = isset($_COOKIE[_COOKIES_NOM_GOOGLE_ANALYTICS]);
			if ($cookie_set) {
				$cookie_val = $_COOKIE[_COOKIES_NOM_GOOGLE_ANALYTICS];
				if ((strcmp($cookie_val, "ok")) && (strcmp($cookie_val, "nok"))) {$cookie_set = false;}
			}
			return $cookie_set;
		}
		
		public function is_ok() {
			$cookie_set = isset($_COOKIE[_COOKIES_NOM_GOOGLE_ANALYTICS]);
			$cookie_ok = ($cookie_set)?(!(strcmp($_COOKIE[_COOKIES_NOM_GOOGLE_ANALYTICS], "ok"))):false;
			return $cookie_ok;
		}
	}