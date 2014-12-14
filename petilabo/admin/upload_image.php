<?php

	require_once "inc/path.php";

	function get_extension($fichier) {
		$ext = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
		$ret = ($ext == _UPLOAD_EXTENSION_JPEG)?_UPLOAD_EXTENSION_JPG:$ext;
		return $ret;
	}

	function verif_upload($tabFile) {
		$file_name = $tabFile["name"];
		$file_tmp = $tabFile["tmp_name"];
		$file_type = $tabFile["type"];
		$file_error = $tabFile["error"];
		$file_size = (int) $tabFile["size"];

		// Vérification du code d'erreur en retour
		if ($file_error > 0) {
			switch ($file_error) {
				case UPLOAD_ERR_NO_FILE :
					$ret = _UPLOAD_NOFILE_ERROR;
					break;
				case UPLOAD_ERR_INI_SIZE :
				case UPLOAD_ERR_FORM_SIZE :
					$ret = _UPLOAD_MAXSIZE_ERROR;
					break;
				default :
					$ret = _UPLOAD_UPLOAD_ERROR ;
					
			}
			return $ret;
		}
		
		// Fichier temporaire non issu de l'upload (malveillance)
		if (!is_uploaded_file($file_tmp)) {
			return _UPLOAD_UPLOAD_ERROR;
		}
		// Validité du nom
		if (empty($file_name)) {
			return _UPLOAD_NAME_ERROR;
		}
		elseif (preg_match('#[\x00-\x1F\x7F-\x9F/\\\\]#', $file_name)) {
			return _UPLOAD_NAME_ERROR;
		}
		// Taille non nulle (double vérif)
		if (($file_size == 0) || (filesize($file_tmp) == 0)) {
			return _UPLOAD_MINSIZE_ERROR;
		}
		return _UPLOAD_NO_ERROR;
	}
	
	function verif_image($tabFile) {
		$ret = _UPLOAD_NO_ERROR;

		$file_name = $tabFile["name"];
		$extension = get_extension($file_name);
		if (!(in_array($extension, array(_UPLOAD_EXTENSION_JPG, _UPLOAD_EXTENSION_PNG, _UPLOAD_EXTENSION_GIF)))) {
			$ret = _UPLOAD_TYPE_ERROR;
		}
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
	
	// Quoi qu'il en soit on vide le dossier d'upload
	$upload_path = getcwd()."/"._UPLOAD_DOSSIER;
	@unlink($upload_path._UPLOAD_FICHIER."."._UPLOAD_EXTENSION_JPG);
	@unlink($upload_path._UPLOAD_FICHIER."."._UPLOAD_EXTENSION_PNG);
	@unlink($upload_path._UPLOAD_FICHIER."."._UPLOAD_EXTENSION_GIF);
	
	$result = _UPLOAD_UNKNOWN_ERROR;
	if (isset($_FILES["upload_image"])) {
		$result = verif_upload($_FILES["upload_image"]);
		if ($result == _UPLOAD_NO_ERROR) {
			$result = verif_image($_FILES["upload_image"]);
		}
		if ($result == _UPLOAD_NO_ERROR) {
			$file_name = $_FILES["upload_image"]["name"];
			$tmp_name = $_FILES["upload_image"]["tmp_name"];
			$extension = get_extension($file_name);
			$new_name = _UPLOAD_FICHIER.".".$extension;
			$ret = @move_uploaded_file($tmp_name, $upload_path.$new_name);
			if ($ret) {
				$result = $new_name;
			}
		}
	}
   
	echo $result;