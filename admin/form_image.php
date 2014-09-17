<?php
	require_once "inc/path.php";
	inclure_inc("const", "param", "session");
	inclure_site("xml_const", "xml_media");

	class form_image {
		private $nom_page = null;
		private $id_edit = null;
		private $media = null;
		private $image = null;
		
		public function __construct($nom_page, $id_edit) {
			$this->nom_page = $nom_page;
			$this->id_edit = $id_edit;

			$this->media = new xml_media();
			$this->media->ouvrir(_XML_SOURCE_SITE, _XML_PATH._XML_MEDIA._XML_EXT);
			$this->media->ouvrir(_XML_SOURCE_PAGE, _XML_PATH_PAGES.$this->nom_page."/"._XML_MEDIA._XML_EXT);
			$this->media->ouvrir(_XML_SOURCE_MODULE, _XML_PATH_MODULES._XML_MEDIA._XML_EXT);

			// Récupération de la source de l'original
			$this->image = $this->media->get_image($id_edit);
			if (!($this->image)) {
				echo "<p>Erreur lors de la lecture de l'identifiant image</p>\n";
				return null;
			}
		}
		public function get_source() {
			$ret = null;
			if ($this->image) {$ret = $this->image->get_source();}
			return $ret;
		}
		public function get_taille() {
			$ret = null;
			if ($this->image) {
				$largeur = $this->image->get_width();
				$hauteur = $this->image->get_height();
				$ret = "Largeur : ".$largeur."px, hauteur : ".$hauteur."px";
			}
			return $ret;
		}
		public function get_src() {
			$ret = null;
			if ($this->image) {$ret = $this->image->get_src();}
			return $ret;
		}
		public function get_est_vide() {
			$ret = true;
			if ($this->image) {$ret = $this->image->get_est_vide();}
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
	$id_image = $param->get(_PARAM_ID);
	if (strlen($id_image) == 0) {
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
	@unlink($upload_path._UPLOAD_FICHIER."."._UPLOAD_EXTENSION_JPG);
	@unlink($upload_path._UPLOAD_FICHIER."."._UPLOAD_EXTENSION_PNG);
	@unlink($upload_path._UPLOAD_FICHIER."."._UPLOAD_EXTENSION_GIF);
	
	$form = new form_image($page, $id_image);
	$src = $form->get_src();
	$taille = $form->get_taille();
	$source = $form->get_source();
	$est_vide = $form->get_est_vide();

	if ($src) {
		echo "<div class=\"form_lb\">\n";
		echo "<p class=\"titre_zone\" style=\"margin-top:0;\">Image en ligne :</p>\n";
		if ($est_vide) {
			echo "<p class=\"texte_zone\" style=\"font-style:italic;\">Vide</p>\n";
		}
		else {
			echo "<img id=\"id_ancienne_image\" class=\"image_edit\" src=\"".$src."?id=".uniqid()."\" />\n";
			echo "<p class=\"texte_zone\">".$taille."</p>\n";
			echo "<p class=\"champ\"><input type=\"button\" class=\"bouton_suppr\" name=\"supprimer\" title=\"Supprimer cette image\" value=\"&#xf014;\"></p>";
			echo "<form id=\"id_suppr_image\" name=\"suppr_image\" method=\"post\" action=\"submit_image_suppr.php\">\n";
			echo "<p class=\"champ\"><input type=\"hidden\" name=\"id_image\" value=\"".$id_image."\" /></p>\n";
			echo "<p class=\"champ\"><input type=\"hidden\" name=\"src_image\" value=\"".$source."\" /></p>\n";
			echo "<p class=\"texte_zone\">Voulez-vous vraiment supprimer cette image ?</p>";
			echo "<p class=\"champ\">";
			echo "<input class=\"bouton_annul\" type=\"button\" name=\"annuler\" title=\"Annuler la suppression\" value=\"Annuler\">";
			echo "<input class=\"bouton_confirm\" type=\"submit\" name=\"confirmer\" title=\"Confirmer la suppression\" value=\"Confirmer\">";
			echo "</p>\n";
			echo "</form>\n";
		}
		echo "<p class=\"titre_zone\" style=\"margin-top:1em;\">Nouvelle image :</p>\n";
		echo "<form id=\"id_form_image\" name=\"form_image\" enctype=\"multipart/form-data\" method=\"post\" action=\"submit_image.php\">\n";
		echo "<p class=\"champ\"><input type=\"hidden\" name=\"id_image\" value=\"".$id_image."\" /></p>\n";
		echo "<p class=\"champ\"><input type=\"hidden\" name=\"src_image\" value=\"".$source."\" /></p>\n";
		echo "<p class=\"champ\">\n";
		echo "<input type=\"file\" id=\"id_upload_image\" name=\"upload_image\" onchange=\"upload('img');\" />\n";
		echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"5242880\" /></p>\n";
		echo "<img id=\"id_nouvelle_image\" class=\"image_edit\" style=\"display:none;\" />\n";
		echo "<p id=\"id_taille_image\" class=\"texte_zone\"></p>\n";
		echo "<p id=\"id_taille_exces\" class=\"texte_zone erreur_zone\" style=\"display:none;margin-top:5px;\"><strong>Remarque : </strong>Cette image est de grande dimension pour un site web.</p>\n";
		echo "<div id=\"id_ajustements\" style=\"display:none;\">\n";
		echo "<p class=\"titre_zone\">Ajustements :</p>\n";
		echo "<p class=\"champ\"><input id=\"id_ajuster_2\" name=\"ajustement\" type=\"radio\" value=\"2\" checked=\"checked\" style=\"margin:0 10px 0 0;\"/>";
		echo "<label for=\"id_ajuster_2\">Ajuster aux dimensions par défaut dans le site</label></p>\n";
		if (!($est_vide)) {
			echo "<p class=\"champ\"><input id=\"id_ajuster_1\" name=\"ajustement\" type=\"radio\" value=\"1\" style=\"margin:0 10px 0 0;\"/>";
			echo "<label for=\"id_ajuster_1\">Ajuster aux dimensions de l'image en ligne</label></p>\n";
		}
		echo "<p class=\"champ\"><input id=\"id_ajuster_0\" name=\"ajustement\" type=\"radio\" value=\"0\" style=\"margin:0 10px 0 0;\"/>";
		echo "<label for=\"id_ajuster_0\">Conserver les dimensions de la nouvelle image</label></p>\n";
		echo "</div>\n";
		echo "<p class=\"champ\"><input class=\"bouton\" type=\"submit\" name=\"valider\" value=\"Enregistrer\"></p>\n";
		echo "</form>\n";
		echo "</div>\n";
	}