<?php
	require_once "inc/path.php";

	class form_lien_editable {
		private $nom_page = null;
		private $id_edit = null;
		private $id_liste = null;
		private $texte = null;
		private $page = null;
		private $menu = null;
		private $liste_cibles = null;
		
		public function __construct($nom_page, $id_edit, $id_liste) {
			$this->nom_page = $nom_page;
			$this->id_edit = $id_edit;
			
			// Chargement des menus
			$this->menu = new xml_menu();
			$this->menu->ouvrir(_XML_PATH._XML_MENU._XML_EXT);
			$this->menu->ouvrir(_XML_PATH_PAGES.$this->nom_page."/"._XML_MENU._XML_EXT);
			
			// Chargement des textes
			$this->texte = new xml_texte();
			$this->texte->ouvrir(_XML_SOURCE_SITE, _XML_PATH._XML_TEXTE._XML_EXT);
			$this->texte->ouvrir(_XML_SOURCE_PAGE, _XML_PATH_PAGES.$this->nom_page."/"._XML_TEXTE._XML_EXT);

			// Traitement des librairies
			$this->page = new xml_page();
			$ret = $this->page->ouvrir(_XML_PATH_PAGES.$this->nom_page."/"._XML_PAGE._XML_EXT, true);
			if ($ret) {
				$nb_librairies = $this->page->get_nb_librairies();
				for ($cpt = 0;$cpt < $nb_librairies; $cpt++) {
					$nom_librairie = $this->page->get_librairie($cpt);
					$source = _XML_SOURCE_LIBRAIRIE."_".$nom_librairie;
					$this->texte->ouvrir($source, _XML_PATH_LIBRAIRIE.$nom_librairie."/"._XML_TEXTE._XML_EXT);
					$this->menu->ouvrir(_XML_PATH_LIBRAIRIE.$nom_librairie."/"._XML_MENU._XML_EXT);
				}
			}

			// Récupération de la source de l'id
			$this->source_edit = $this->texte->get_source($id_edit);
			if (!($this->source_edit)) {
				echo "<p>Erreur lors de la lecture de l'identifiant texte</p>\n";
				return null;
			}
			
			// Récupération de l'éventuelle liste
			$this->liste_cibles = $this->menu->get_liste_cibles($id_liste);
		}
		public function get_source() {return $this->source_edit;}
		public function get_texte() {return $this->texte->get_texte($this->id_edit, $this->get_langue_par_defaut());}
		public function get_langue_par_defaut() {return $this->texte->get_langue_par_defaut();}
		public function get_nb_cibles() {
			$ret = 0;
			if ($this->liste_cibles) {$ret = (int) $this->liste_cibles->get_nb_cibles();}
			return $ret;
		}
		public function get_lien_cible($index) {
			$ret = null;
			if ($this->liste_cibles) {$ret = $this->liste_cibles->get_lien_cible($index);}
			return $ret;
		}
		public function get_valeur_cible($index) {
			$ret = null;
			if ($this->liste_cibles) {$ret = $this->liste_cibles->get_valeur_cible($index);}
			return $ret;
		}
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
	$id_tab = $param->get(_PARAM_POINT_RETOUR);
	$id_liste = $param->get(_PARAM_ID_LISTE);

	$page = $session->get_session_param(_SESSION_PARAM_PAGE);
	if (strlen($page) == 0) {
		$session->fermer_session();
		exit;
	}
	
	$form = new form_lien_editable($page, $id_texte, $id_liste);

	$texte = $form->get_texte();
	echo "<div class=\"form_lb\">\n";
	echo "<p class=\"titre_zone\" style=\"margin-top:0;\">Cible du lien :</p>\n";
	echo "<form id=\"id_form_texte\" name=\"form_texte\" accept-charset=\"UTF-8\" method=\"post\" action=\"submit_texte_simple.php\">\n";
	$nb_cibles = $form->get_nb_cibles();
	if ($nb_cibles > 0) {
		echo "<p class=\"champ\">";
		echo "<select class=\"select_lien\" onchange=\"var u=getElementById('id_cible_lien_perso');if (u) {u.value = this[this.selectedIndex].value;}\">";
		echo "<option value=\"\">Personnalisé</option>";
		for ($cpt = 0;$cpt < $nb_cibles;$cpt++) {
			$lien = $form->get_lien_cible($cpt);
			$selected = (strcmp($lien, $texte))?"":" selected=\"selected\"";
			$valeur = $form->get_valeur_cible($cpt);
			echo "<option value=\"".$lien."\"".$selected.">".$valeur."</option>";
		}
		echo "</select></p>\n";
		echo "<p class=\"titre_zone\"><br/>Lien personnalisé :</p>\n";
	}
	echo "<p class=\"champ\">";
	echo "<input id=\"id_cible_lien_perso\" class=\"texte_champ texte_champ_simple_lien\" name=\"".$form->get_langue_par_defaut()."\" value=\"".$texte."\" />";
	echo "</p>\n";
	echo "<p class=\"champ\"><input type=\"hidden\" name=\"id_texte\" value=\"".$id_texte."\" /></p>\n";
	echo "<p class=\"champ\"><input type=\"hidden\" name=\"src_texte\" value=\"".$form->get_source()."\" /></p>\n";
	echo "<p class=\"champ\"><input type=\"hidden\" name=\""._PARAM_FRAGMENT."\" value=\"".$id_tab."\" /></p>\n";
	echo "<p class=\"champ\"><input class=\"bouton\" type=\"submit\" name=\"valider\" value=\"Enregistrer\"></p>\n";
	echo "</form>\n";
	echo "</div>\n";