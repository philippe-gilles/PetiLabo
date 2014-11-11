<?php
	require_once "inc/path.php";

	define("_TYPE_AJUSTEMENT_SANS", "0");
	define("_TYPE_AJUSTEMENT_ACTUEL", "1");
	define("_TYPE_AJUSTEMENT_ORIGINE", "2");

	class fichier_image {
		// Constantes
		const qualite_jpg = 80;
		const qualite_jpg_reduite = 60;
		const qualite_png = 7;
		const qualite_png_reduite = 7;
		const ratio_reduction = 0.7;

		// Propriétés
		private $src = null;private $ext = null;
		private $largeur = 0;private $hauteur = 0;
		private $largeur_standard = 0;private $hauteur_standard = 0;
		
		public function __construct($src, $ext) {
			$f_exists = @file_exists($src);
			$this->src = ($f_exists)?$src:null;
			$this->ext = $ext;

			if (strlen($this->src) > 0) {
				list($this->largeur, $this->hauteur) = @getimagesize($this->src);
			}
		}
		public function is_null() {
			$ret = false;
			if (strlen($this->src) == 0) {
				$ret = true;
			}
			elseif ((strcmp($this->ext, _UPLOAD_EXTENSION_JPG)) && (strcmp($this->ext, _UPLOAD_EXTENSION_GIF)) && (strcmp($this->ext, _UPLOAD_EXTENSION_JPEG)) && (strcmp($this->ext, _UPLOAD_EXTENSION_PNG))) {
				$ret = true;
			}
			elseif (($this->largeur < 1) || ($this->hauteur < 1)) {
				$ret = true;
			}
			return $ret;
		}
		public function remplacer(&$image, $type_ajustement) {
			$rapport_1 = (float) (((float) $image->get_largeur()) / ((float) $image->get_hauteur()));
			switch ($type_ajustement) {
				case _TYPE_AJUSTEMENT_ACTUEL :
					$rapport_0 = (float) (((float) $this->get_largeur()) / ((float) $this->get_hauteur()));
					if ($rapport_0 > $rapport_1) {
						$delta_l = 0;
						$delta_h = (int) (($image->get_hauteur() * $this->get_largeur() - $image->get_largeur() * $this->get_hauteur()) / (2 * $this->get_largeur()));
					}
					elseif ($rapport_0 < $rapport_1) {
						$delta_l = (int) (($image->get_largeur() * $this->get_hauteur() - $image->get_hauteur() * $this->get_largeur()) / (2 * $this->get_hauteur()));
						$delta_h = 0;
					}
					else {
						$delta_l = 0;
						$delta_h = 0;
					}
					$image->retailler($this->get_largeur(), $this->get_hauteur(), $delta_l, $delta_h);
					@copy($image->get_src(), $this->get_src());
					$largeur_reduite = (int) ($this->get_largeur() * self::ratio_reduction);
					$hauteur_reduite = (int) ($this->get_hauteur() * self::ratio_reduction);
					$image->retailler($largeur_reduite, $hauteur_reduite, 0, 0);
					@rename($image->get_src(), $this->get_src_reduite());
					break;
				case _TYPE_AJUSTEMENT_ORIGINE :
					if (($this->get_largeur_standard() > 0) && ($this->get_hauteur_standard() > 0)) {
						$rapport_std = (float) (((float) $this->get_largeur_standard()) / ((float) $this->get_hauteur_standard()));
						if ($rapport_std > $rapport_1) {
							$delta_l = 0;
							$delta_h = (int) (($image->get_hauteur() * $this->get_largeur_standard() - $image->get_largeur() * $this->get_hauteur_standard()) / (2 * $this->get_largeur_standard()));
						}
						elseif ($rapport_std < $rapport_1) {
							$delta_l = (int) (($image->get_largeur() * $this->get_hauteur_standard() - $image->get_hauteur() * $this->get_largeur_standard()) / (2 * $this->get_hauteur_standard()));
							$delta_h = 0;
						}
						else {
							$delta_l = 0;
							$delta_h = 0;
						}
						$largeur = $this->get_largeur_standard();
						$hauteur = $this->get_hauteur_standard();
					}
					elseif (($this->get_largeur_standard() > 0) && ($this->get_hauteur_standard() <= 0)) {
						$delta_l = 0;$delta_h = 0;
						$largeur = $this->get_largeur_standard();
						$hauteur = $image->get_hauteur() * (float) (((float) $this->get_largeur_standard()) / ((float) $image->get_largeur()));
					}
					elseif (($this->get_largeur_standard() <= 0) && ($this->get_hauteur_standard() > 0)) {
						$delta_l = 0;$delta_h = 0;
						$hauteur = $this->get_hauteur_standard();
						$largeur = $image->get_largeur() * (float) (((float) $this->get_hauteur_standard()) / ((float) $image->get_hauteur()));
					}
					if (($this->get_largeur_standard() > 0) || ($this->get_hauteur_standard() > 0)) {
						$image->retailler($largeur, $hauteur, $delta_l, $delta_h);
					}
					@copy($image->get_src(), $this->get_src());
					if (($this->get_largeur_standard() > 0) || ($this->get_hauteur_standard() > 0)) {
						$largeur_reduite = (int) ($largeur * self::ratio_reduction);
						$hauteur_reduite = (int) ($hauteur * self::ratio_reduction);
						$image->retailler($largeur_reduite, $hauteur_reduite, 0, 0);
					}
					@rename($image->get_src(), $this->get_src_reduite());
					break;
				case _TYPE_AJUSTEMENT_SANS :
				default :
					// Pas de retaillage : on effectue une simple copie
					@copy($image->get_src(), $this->get_src());
					$largeur_reduite = (int) ($image->get_largeur() * self::ratio_reduction);
					$hauteur_reduite = (int) ($image->get_hauteur() * self::ratio_reduction);
					$image->retailler($largeur_reduite, $hauteur_reduite, 0, 0);
					@rename($image->get_src(), $this->get_src_reduite());
					break;
			}
		}
		
		public function set_largeur_standard($param) {$this->largeur_standard = (int) $param;}
		public function set_hauteur_standard($param) {$this->hauteur_standard = (int) $param;}

		public function get_src_reduite() {
			$ret = _XML_PATH_IMAGES_REDUITES_SITE.basename($this->src);
			return $ret;
		}
		public function get_src() {return $this->src;}
		public function get_ext() {return $this->ext;}
		public function get_largeur() {return $this->largeur;}
		public function get_hauteur() {return $this->hauteur;}
		public function get_largeur_standard() {return $this->largeur_standard;}
		public function get_hauteur_standard() {return $this->hauteur_standard;}

		private function retailler($nouvelle_largeur, $nouvelle_hauteur, $delta_largeur, $delta_hauteur, $reduite = false) {
			$ret = false;
			if (!($this->is_null())) {
				$src_r = null;
				if ((!(strcmp($this->get_ext(), _UPLOAD_EXTENSION_JPG))) || (!(strcmp($this->get_ext(), _UPLOAD_EXTENSION_JPEG)))) {
					$src_r = imagecreatefromjpeg($this->get_src());
				}
				elseif (!(strcmp($this->get_ext(), _UPLOAD_EXTENSION_PNG))) {
					$src_r = imagecreatefrompng($this->get_src());
				}
				elseif (!(strcmp($this->get_ext(), _UPLOAD_EXTENSION_GIF))) {
					$src_r = imagecreatefromgif($this->get_src());
				}
				if ($src_r) {
					if (($this->get_ext() == _UPLOAD_EXTENSION_JPG) || ($this->get_ext() == _UPLOAD_EXTENSION_JPEG)) {
						$dst_r = ImageCreateTrueColor($nouvelle_largeur, $nouvelle_hauteur);
						if ($dst_r) {
							imagecopyresampled($dst_r, $src_r,
												0, 0, 
												$delta_largeur, $delta_hauteur, 
												$nouvelle_largeur, $nouvelle_hauteur, 
												$this->get_largeur() - (2*$delta_largeur), $this->get_hauteur() - (2*$delta_hauteur));
							$qualite = ($reduite)?(self::qualite_jpg_reduite):(self::qualite_jpg);
							$ret = imagejpeg($dst_r, $this->get_src(), $qualite);
							// Mise à jour des nouvelles dimensions
							$this->largeur = $nouvelle_largeur;
							$this->hauteur = $nouvelle_hauteur;
							imagedestroy($dst_r);
						}
					}
					elseif ($this->get_ext() == _UPLOAD_EXTENSION_PNG) {
						$src_alpha = $this->png_has_transparency($this->get_src());
						$dst_r = ImageCreateTrueColor($nouvelle_largeur, $nouvelle_hauteur);
						if ($dst_r) {
							if ($src_alpha) {
								imagealphablending( $dst_r, false );
								imagesavealpha( $dst_r, true );
							}
							imagecopyresampled($dst_r, $src_r,
												0, 0, 
												$delta_largeur, $delta_hauteur, 
												$nouvelle_largeur, $nouvelle_hauteur, 
												$this->get_largeur() - (2*$delta_largeur), $this->get_hauteur() - (2*$delta_hauteur));
							/* En cas d'image non transparente on reduit à une image avec palette (pb de taille) */
							if (!$src_alpha) {
								$tmp = ImageCreateTrueColor($nouvelle_largeur, $nouvelle_hauteur);
								ImageCopyMerge($tmp, $dst_r, 0, 0, 0, 0, $nouvelle_largeur, $nouvelle_hauteur, 100);
								ImageTrueColorToPalette($dst_r, false, 8192);
								ImageColorMatch($tmp, $dst_r);
								ImageDestroy($tmp );
							}
							$qualite = ($reduite)?(self::qualite_png_reduite):(self::qualite_png);
							$ret = imagepng($dst_r, $this->get_src(), $qualite);
							// Mise à jour des nouvelles dimensions
							$this->largeur = $nouvelle_largeur;
							$this->hauteur = $nouvelle_hauteur;
							imagedestroy($dst_r);
						}
					}
					elseif ($this->get_ext() == _UPLOAD_EXTENSION_GIF) {
						$dst_r = ImageCreateTrueColor($nouvelle_largeur, $nouvelle_hauteur);
						if ($dst_r) {
							imagecopyresampled($dst_r, $src_r,
												0, 0, 
												$delta_largeur, $delta_hauteur, 
												$nouvelle_largeur, $nouvelle_hauteur, 
												$this->get_largeur() - (2*$delta_largeur), $this->get_hauteur() - (2*$delta_hauteur));
							$ret = imagegif($dst_r, $this->get_src());
							// Mise à jour des nouvelles dimensions
							$this->largeur = $nouvelle_largeur;
							$this->hauteur = $nouvelle_hauteur;
							imagedestroy($dst_r);
						}
					}
					imagedestroy($src_r);
				}
			}
			return $ret;
		}
		// Grand merci à http://www.jonefox.com/ !!!
		private function png_has_transparency($filename) {
			if (strlen($filename) == 0 || !file_exists($filename)) return false;
			if (ord(file_get_contents($filename, false, null, 25, 1)) & 4) return true;
			$contents = file_get_contents($filename);
			if (stripos($contents, 'PLTE') !== false && stripos($contents, 'tRNS') !== false) return true;
			return false;
		}
	}
	function get_extension($fichier) {
		$ext = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
		$ret = ($ext == _UPLOAD_EXTENSION_JPEG)?_UPLOAD_EXTENSION_JPG:$ext;
		return $ret;
	}

	$session = new session();
	if (is_null($session)) {
		header("Location: "._SESSION_URL_FERMETURE);
		exit;
	}

	$session->check_session();

	$page = $session->get_session_param(_SESSION_PARAM_PAGE);
	if (strlen($page) == 0) {
		$session->fermer_session();
		header("HTTP/1.0 404 Not Found");
		exit;
	}

	$param = new param();
	$id_image = $param->post("id_image");
	if (strlen($id_image) == 0) {
		$session->fermer_session();
		header("HTTP/1.0 404 Not Found");
		exit;
	}

	$src_image = $param->post("src_image");
	if (strlen($src_image) == 0) {
		$session->fermer_session();
		header("HTTP/1.0 404 Not Found");
		exit;
	}
	
	if (!(strcmp($src_image, _XML_SOURCE_SITE))) {
		$fichier_xml = _XML_PATH._XML_MEDIA._XML_EXT;
	}
	elseif (!(strcmp($src_image, _XML_SOURCE_PAGE))) {
		$fichier_xml = _XML_PATH_PAGES.$page."/"._XML_MEDIA._XML_EXT;
	}
	elseif (!(strcmp($src_image, _XML_SOURCE_MODULE))) {
		$fichier_xml = _XML_PATH_MODULES._XML_MEDIA._XML_EXT;
	}
	else {
		$session->fermer_session();
		header("HTTP/1.0 404 Not Found");
		exit;
	}

	$type_ajustement = (int) $param->post("ajustement");

	// On teste la présence d'un fichier dans le dossier d'upload
	$src_1 = null;
	$ext_1 = null;
	$upload_path = getcwd()."/"._UPLOAD_DOSSIER;
	$fichier_jpg = $upload_path._UPLOAD_FICHIER."."._UPLOAD_EXTENSION_JPG;
	$fichier_png = $upload_path._UPLOAD_FICHIER."."._UPLOAD_EXTENSION_PNG;
	$fichier_gif = $upload_path._UPLOAD_FICHIER."."._UPLOAD_EXTENSION_GIF;
	if (file_exists($fichier_jpg)) {
		$src_1 = $fichier_jpg;
		$ext_1 = _UPLOAD_EXTENSION_JPG;
	}
	elseif (file_exists($fichier_png)) {
		$src_1 = $fichier_png;
		$ext_1 = _UPLOAD_EXTENSION_PNG;
	}
	elseif (file_exists($fichier_gif)) {
		$src_1 = $fichier_gif;
		$ext_1 = _UPLOAD_EXTENSION_GIF;
	}

	$img_1 = new fichier_image($src_1, $ext_1);
	if (!($img_1->is_null())) {
		$xml_media = new xml_media();
		$xml_media->ouvrir($src_image, $fichier_xml);
		$img_media = $xml_media->get_image($id_image);
		if ($img_media) {
			$src_0 = $img_media->get_src();
			$ext_0 = get_extension($src_0);
			$img_0 = new fichier_image($src_0, $ext_0);
			if (!($img_0->is_null())) {
				$img_0->set_largeur_standard($img_media->get_width_standard());
				$img_0->set_hauteur_standard($img_media->get_height_standard());
				$img_0->remplacer($img_1, $type_ajustement);
			}
		}
	}

	// Redirection finale
	$id_tab = $param->post(_PARAM_FRAGMENT);
	$ret_page = preparer_redirection($session, $id_tab);
	header("Location: ".$ret_page);