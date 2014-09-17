<?php
	define("_CARTE_TEMPLATE_SRC", "http://maps.googleapis.com/maps/api/staticmap?center=%s&markers=%s&language=%s&zoom=14&size=600x400&sensor=false");
	define("_CARTE_TEMPLATE_REF", "http://maps.google.com/?q=%s");
	define("_CARTE_PREFIXE", "carte_");
	define("_CARTE_SUFFIXE", ".png");

	class carte {
		// Propriétés
		private $code = null;
		private $adresse = null;
		private $langue = null;
		
		public function __construct($code, $trad_code, $langue) {
			$this->code = trim(strtolower($code));
			$this->adresse = urlencode($trad_code);
			$this->langue = $langue;
		}
		public function get_src_carte() {
			// TODO : Gérer les différentes langues (pour le moment : la carte est créée dans la langue du première accès)
			$carte_locale = $this->get_src_carte_locale();
			// Si la carte n'a pas été copiée en local on effectue cette copie (économise le compteur Google)
			if (!(@file_exists($carte_locale))) {
				$carte_distante = $this->get_src_carte_distante();
				$this->copier_carte($carte_distante, $carte_locale);
			}
			return $carte_locale;
		}
		public function get_src_carte_locale() {
			$src = _XML_PATH_IMAGES_SITE._CARTE_PREFIXE.$this->code._CARTE_SUFFIXE;
			return $src;
		}
		public function get_src_carte_distante() {
			$src = sprintf(_CARTE_TEMPLATE_SRC, $this->adresse, $this->adresse, $this->langue);
			return $src;
		}
		public function get_ref_carte() {
			$ref_carte = sprintf(_CARTE_TEMPLATE_REF, $this->adresse);
			return $ref_carte;
		}
		public function reinit() {
			$carte_locale = $this->get_src_carte_locale();
			@unlink($carte_locale);
		}
		private function copier_carte($carte_distante, $carte_locale) {
			$in = @fopen($carte_distante, "rb");$out = @fopen($carte_locale, "wb");
			while ($chunk = @fread($in,8192)) {@fwrite($out, $chunk, 8192);}
			@fclose($in);@fclose($out);
		}
	}