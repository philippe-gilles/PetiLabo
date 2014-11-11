<?php
	require_once "inc/path.php";

	define("_EDIT_SIMPLE_TITRE_COPY", "Copyright de l'image");
	define("_EDIT_SIMPLE_TITRE_ICONE", "Code de l'icone");
	define("_EDIT_SIMPLE_TITRE_PLAN", "Adresse du plan");
	define("_EDIT_SIMPLE_TITRE_VIDEO", "Identifiant de la video");
	define("_EDIT_SIMPLE_TITRE_LIEN", "Destination du lien");

	class form_texte {
		private $nom_page = null;
		private $id_edit = null;
		private $site = null;
		private $texte = null;
		private $tab_titre = array(
			_EDIT_TYPE_COPY=>_EDIT_SIMPLE_TITRE_COPY,
			_EDIT_TYPE_ICONE=>_EDIT_SIMPLE_TITRE_ICONE,
			_EDIT_TYPE_PLAN=>_EDIT_SIMPLE_TITRE_PLAN,
			_EDIT_TYPE_VIDEO=>_EDIT_SIMPLE_TITRE_VIDEO,
			_EDIT_TYPE_LIEN=>_EDIT_SIMPLE_TITRE_LIEN);
		
		public function __construct($nom_page, $id_edit) {
			$this->nom_page = $nom_page;
			$this->id_edit = $id_edit;
			
			// Chargement XML Site
			$this->site = new xml_site();
			$ret = $this->site->ouvrir(_XML_PATH._XML_GENERAL._XML_EXT);
			if (!$ret) {
				echo "<p>Erreur lors de l'ouverture du fichier XML site</p>\n";
				return null;
			}
			
			// Gestion des textes
			$this->texte = new xml_texte();

			// Lecture des textes pour le site et la page
			$this->texte->ouvrir(_XML_SOURCE_SITE, _XML_PATH._XML_TEXTE._XML_EXT);
			$this->texte->ouvrir(_XML_SOURCE_PAGE, _XML_PATH_PAGES.$this->nom_page."/"._XML_TEXTE._XML_EXT);
			if ($this->site->has_module(_SITE_MODULE_ACTU)) {
				$ret = $this->texte->ouvrir(_XML_SOURCE_MODULE, _XML_PATH_MODULES._XML_TEXTE._XML_EXT);
			}
			
			// Récupération de la source de l'id
			$this->source_edit = $this->texte->get_source($id_edit);
			if (!($this->source_edit)) {
				echo "<p>Erreur lors de la lecture de l'identifiant texte</p>\n";
				return null;
			}
		}
		public function get_source() {return $this->source_edit;}
		public function get_texte() {return $this->texte->get_texte($this->id_edit, $this->get_langue_par_defaut());}
		public function get_langue_par_defaut() {return $this->texte->get_langue_par_defaut();}
		public function get_titre_type($type) {return $this->tab_titre[$type];}
	}

	$session = new session();
	if (is_null($session)) {
		header("Location: "._SESSION_URL_FERMETURE);
		exit;
	}

	$session->check_session();
	
	$param = new param();
	$id_texte = $param->get(_PARAM_ID);
	if (strlen($id_texte) == 0) {
		$session->fermer_session();
		exit;
	}
	$type = $param->get(_PARAM_TYPE);
	if (strlen($type) == 0) {
		$session->fermer_session();
		exit;
	}
	$id_tab = $param->get(_PARAM_POINT_RETOUR);

	$page = $session->get_session_param(_SESSION_PARAM_PAGE);
	if (strlen($page) == 0) {
		$session->fermer_session();
		exit;
	}
	
	$form = new form_texte($page, $id_texte);
	$titre = $form->get_titre_type($type);
	if (strlen($titre) == 0) {
		$session->fermer_session();
		exit;
	}

	$texte = $form->get_texte();
	echo "<div class=\"form_lb\">\n";
	echo "<p class=\"titre_zone\" style=\"margin-top:0;\">".$titre." :</p>\n";
	echo "<form id=\"id_form_texte\" name=\"form_texte\" accept-charset=\"UTF-8\" method=\"post\" action=\"submit_texte_simple.php\">\n";
	echo "<p class=\"champ\">";
	if ((strcmp($type, _EDIT_TYPE_LIEN)) && (strcmp($type, _EDIT_TYPE_ICONE)) && (strcmp($type, _EDIT_TYPE_VIDEO))) {
		echo "<textarea class=\"texte_champ texte_champ_simple\" name=\"".$form->get_langue_par_defaut()."\" rows=\"3\">".$texte."</textarea>";
	}
	else {
		echo "<input class=\"texte_champ texte_champ_simple_lien\" name=\"".$form->get_langue_par_defaut()."\" value=\"".$texte."\" />";
	}
	echo "</p>\n";
	echo "<p class=\"champ\"><input type=\"hidden\" name=\"id_texte\" value=\"".$id_texte."\" /></p>\n";
	echo "<p class=\"champ\"><input type=\"hidden\" name=\"src_texte\" value=\"".$form->get_source()."\" /></p>\n";
	echo "<p class=\"champ\"><input type=\"hidden\" name=\""._PARAM_FRAGMENT."\" value=\"".$id_tab."\" /></p>\n";
	echo "<p class=\"champ\"><input class=\"bouton\" type=\"submit\" name=\"valider\" value=\"Enregistrer\"></p>\n";
	echo "</form>\n";
	echo "</div>\n";