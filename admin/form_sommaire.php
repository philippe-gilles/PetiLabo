<?php
	require_once "inc/path.php";
	
	class form_sommaire {
		private $nom_page = null;
		private $id_edit = null;
		private $site = null;
		private $page = null;
		private $module_actu = null;
		private $nb_actus = 0;
		private $no_actu = 0;

		public function __construct($nom_page, $id_edit) {
			$this->nom_page = $nom_page;
			$this->id_edit = $id_edit;

			// Chargement XML Page
			$this->page = new xml_page();
			$ret = $this->page->ouvrir(_XML_PATH_PAGES.$this->nom_page."/"._XML_PAGE._XML_EXT);
			if (!$ret) {
				echo "<p>Erreur lors de l'ouverture du fichier XML page</p>\n";
				return null;
			}
			$this->nb_actus = $this->page->get_nb_actus();
			// Chargement XML Actu
			$this->module_actu = new xml_module_actu();
			$ret = $this->module_actu->ouvrir(_XML_PATH_MODULES._XML_MODULE_ACTU._XML_EXT);
			if ($ret) {
				$this->no_actu = $this->module_actu->get_sommaire($id_edit-1);
			}
		}

		public function get_nb_actus() {
			return $this->nb_actus;
		}
		
		public function get_no_actu() {
			return $this->no_actu;
		}
	}

	$session = new session();
	if (is_null($session)) {
		header("Location: "._SESSION_URL_FERMETURE);
		exit;
	}

	$session->check_session();
	
	$param = new param();
	$id_sommaire = $param->get(_PARAM_ID);
	if (strlen($id_sommaire) == 0) {
		$session->fermer_session();
		exit;
	}
	$id_tab = $param->get(_PARAM_POINT_RETOUR);

	$page = $session->get_session_param(_SESSION_PARAM_PAGE);
	if (strlen($page) == 0) {
		$session->fermer_session();
		exit;
	}

	$form = new form_sommaire($page, $id_sommaire);

	echo "<div class=\"form_lb\">\n";
	$position = ($id_sommaire == 1)?"1<sup>ère</sup>":$id_sommaire."<sup>ème</sup>";
	$no_actu = $form->get_no_actu();
	echo "<p class=\"titre_zone\" style=\"margin-top:0;\">".$position." position dans le sommaire :</p>\n";
	echo "<form id=\"id_form_sommaire\" name=\"form_sommaire\" accept-charset=\"UTF-8\" method=\"post\" action=\"submit_sommaire.php\">\n";
	echo "<p class=\"champ\"><select class=\"select_champ\" name=\"no_actu\">";
	// for ($cpt = 0;$cpt <= $form->get_nb_actus();$cpt++) {
	for ($cpt = 0;$cpt <= 5;$cpt++) {
		$selected = ($cpt == $no_actu)?" selected=\"selected\"":"";
		$label = ($cpt > 0)?"Actualité n°".$cpt:"Pas d'actualité";
		echo "<option value=\"".$cpt."\"".$selected.">".$label."</option>";
	}
	echo "</select></p>\n";
	echo "<p class=\"champ\"><input type=\"hidden\" name=\"id_sommaire\" value=\"".$id_sommaire."\" /></p>\n";
	echo "<p class=\"champ\"><input type=\"hidden\" name=\""._PARAM_FRAGMENT."\" value=\"".$id_tab."\" /></p>\n";
	echo "<p class=\"champ\"><input class=\"bouton\" type=\"submit\" name=\"valider\" value=\"Enregistrer\"></p>\n";
	echo "</form>\n";
	echo "</div>\n";