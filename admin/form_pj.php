<?php
	require_once "inc/path.php";
	inclure_inc("const", "param", "session");
	inclure_site("xml_const", "xml_document");

	class form_pj {
		private $nom_page = null;
		private $id_edit = null;
		private $document = null;
		private $pj = null;
		
		public function __construct($nom_page, $id_edit) {
			$this->nom_page = $nom_page;
			$this->id_edit = $id_edit;

			$this->document = new xml_document();
			$ret = $this->document->ouvrir(_XML_PATH._XML_DOCUMENT._XML_EXT);
			$ret = $this->document->ouvrir(_XML_PATH_PAGES.$this->nom_page."/"._XML_DOCUMENT._XML_EXT);
			
			// Récupération de la source de l'original
			$this->pj = $this->document->get_document($id_edit);
			if (!($this->pj)) {
				echo "<p>Erreur lors de la lecture de l'identifiant document</p>\n";
				return null;
			}
		}
		public function get_fichier() {
			$ret = ($this->pj)?$this->pj->get_fichier():null;

			return $ret;
		}
		public function get_taille_fichier() {
			$taille = 0;
			if ($this->pj) {
				$fichier = $this->pj->get_fichier();
				$taille = (int) (@filesize($fichier) / 1024);
			}
			return $taille;
		}
		public function get_nom_fichier() {
			$ret = null;
			if ($this->pj) {
				$fichier = $this->pj->get_fichier();
				$base = pathinfo($fichier, PATHINFO_BASENAME);
				$extension = ".".pathinfo($fichier, PATHINFO_EXTENSION);
				$ret = str_replace($extension, "", $base);
			}
			return $ret;
		}
		public function get_extension_fichier() {
			$ret = null;
			if ($this->pj) {
				$fichier = $this->pj->get_fichier();
				$ext = pathinfo($fichier, PATHINFO_EXTENSION);
				$ret = ($ext == _UPLOAD_EXTENSION_JPEG)?_UPLOAD_EXTENSION_JPG:$ext;
			}
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
	$id_pj = $param->get(_PARAM_ID);
	if (strlen($id_pj) == 0) {
		$session->fermer_session();
		exit;
	}

	$page = $session->get_session_param(_SESSION_PARAM_PAGE);
	if (strlen($page) == 0) {
		$session->fermer_session();
		exit;
	}
	
	// On vide préalablement le dossier d'upload
	$upload_path = getcwd()."/"._UPLOAD_DOSSIER;
	@unlink($upload_path._UPLOAD_FICHIER."."._UPLOAD_EXTENSION_PJ);
	
	$form = new form_pj($page, $id_pj);
	$fichier = $form->get_fichier();

	if ($fichier) {
		echo "<div class=\"form_lb\">\n";
		echo "<p class=\"titre_zone\" style=\"margin-top:0;\">Document en ligne :</p>\n";
		$base = basename($fichier);
		$ext = $form->get_extension_fichier();
		echo "<p class=\"texte_zone\"><span style=\"font-weight:bold;\">Nom</span> : ".ucfirst($base)."</p>\n";
		echo "<p id=\"id_ext_".$ext."\" class=\"texte_zone texte_extension\"><span style=\"font-weight:bold;\">Extension</span> : ".ucfirst($ext)."</p>\n";
		echo "<p class=\"texte_zone\"><span style=\"font-weight:bold;\">Taille</span> : ".$form->get_taille_fichier()." ko</p>\n";
		echo "<p class=\"titre_zone\" style=\"margin-top:1em;\">Nouveau document :</p>\n";
		echo "<form id=\"id_form_pj\" name=\"form_pj\" enctype=\"multipart/form-data\" method=\"post\" action=\"submit_pj.php\">\n";
		echo "<p class=\"champ\"><input type=\"hidden\" name=\"id_pj\" value=\"".$id_pj."\" /></p>\n";
		echo "<p class=\"champ\"><input type=\"hidden\" name=\"upload_name_pj\" value=\"\" /></p>\n";
		echo "<p class=\"champ\">\n";
		echo "<input type=\"file\" id=\"id_upload_pj\" name=\"upload_pj\" onchange=\"upload('pj');\" />\n";
		echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"5242880\" /></p>\n";
		echo "<p id=\"id_extension_pj\" class=\"texte_zone\"></p>\n";
		echo "<p id=\"id_taille_pj\" class=\"texte_zone\"></p>\n";
		echo "<p id=\"id_dif_ext\" class=\"texte_zone erreur_zone\" style=\"display:none;margin-top:5px;\"><strong>Attention : </strong>Les extensions sont différentes, ce document risque d'être illisible.</p>\n";
		echo "<p class=\"champ\"><input class=\"bouton\" type=\"submit\" name=\"valider\" value=\"Enregistrer\"></p>\n";
		echo "</form>\n";
		echo "</div>\n";
	}