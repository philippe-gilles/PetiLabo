<?php
	// Chemins pour les inclusions
	define("_DB_PATH_ROOT", "analitix/");
	define("_XML_PATH_ROOT", "xml/");
	define("_PHP_PATH_ROOT", "petilabo/");
	define("_PHP_PATH_INCLUDE", _PHP_PATH_ROOT."inc/");
	define("_PHP_PATH_OBJETS", _PHP_PATH_ROOT."obj/");
	define("_PHP_PATH_SITE", _PHP_PATH_ROOT."site/");
	define("_PHP_PREFIXE_OBJETS", "obj_");
	define("_PHP_PREFIXE_SITE", "xml_");
	define("_DB_EXT", ".db");
	define("_PXP_EXT", ".php");

	// Compatibilité ascendante < V2.0
	function inclure_site($fichier) {@require_once _PHP_PATH_SITE.$fichier._PXP_EXT;}
	
	// Autoload >= V2.0
    class chargeur {
        public function __construct() {
            spl_autoload_register(array($this, 'autoload'));
        }
	
		public function inclure_inc() {
			$tab_args = func_get_args();
			foreach ($tab_args as $fichier) {
				@require_once _PHP_PATH_INCLUDE.$fichier._PXP_EXT;
			}
		}
		
		public function inclure_obj() {
			$tab_args = func_get_args();
			foreach ($tab_args as $fichier) {
				@require_once _PHP_PATH_OBJETS.$fichier._PXP_EXT;
			}
		}
		
		public function inclure_site() {
			$tab_args = func_get_args();
			foreach ($tab_args as $fichier) {
				@require_once _PHP_PATH_SITE.$fichier._PXP_EXT;
			}
		}

        private function autoload($classe) {
			$prefixe = substr($classe, 0, 4);
			if (!(strcmp($prefixe, _PHP_PREFIXE_SITE))) {$this->inclure_site($classe);}
			elseif (!(strcmp($prefixe, _PHP_PREFIXE_OBJETS))) {$this->inclure_obj($classe);}
			else {$this->inclure_inc($classe);}
        }
    }
	
	$chargeur = new chargeur();
	// Chargement des constantes (pas de classes)
	$chargeur->inclure_inc("const");
	$chargeur->inclure_site("xml_const");