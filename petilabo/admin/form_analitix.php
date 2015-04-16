<?php
	require_once "inc/path.php";
	require_once _PHP_PATH_INCLUDE."visites.php";
	require_once _PHP_PATH_INCLUDE."openstreetmap.php";
	
	define("_ANALITIX_GEO_INDEX_FICHIER", "0");
	define("_ANALITIX_GEO_INDEX_ZOOM", "1");
	define("_ANALITIX_GEO_INDEX_COORD", "2");

	$tab_pays = array(
		"Serveur local" => array("fr", 6, array(31, 21, 33, 23)),
		"Canada" => array("ca", 4, array(2, 3, 5, 5)),
		"France" => array("fr", 6, array(31, 21, 33, 23)),
		"Germany" => array("de", 6, array(32, 20, 34, 22)),
		"Italy" => array("it", 6, array(33, 22, 35, 24)),
		"Japan" => array("jp", 6, array(55, 23, 57, 25)),
		"Netherlands" => array("nl", 8, array(130, 83, 132, 85)),
		"Romania" => array("ro", 7, array(71, 44, 74, 46)),
		"Switzerland" => array("ch", 8, array(132, 89, 134, 91)),
		"United Kingdom" => array("uk", 6, array(30, 19, 32, 21)),
		"United States" => array("us", 4, array(2, 5, 4, 6)),
	);
	$tab_stat = array();$tab_ville_stat = array();

	function conversion_lon_lat($n, $x, $y) {
		$lon = $x / $n * 360.0 - 180.0;
		$lat = rad2deg(atan(sinh(pi() * (1 - 2 * $y / $n))));
		return array($lon, $lat);
	}
	
	function parser_geo_ip_db($ip_db) {
		$langue = substr($ip_db, 0, 2);$ip_db = substr($ip_db, 2);
		$visite_mobile = (!(strcmp(substr($ip_db, 0, 1), _DB_VISITES_INDICATEUR_MOBILE)));
		if ($visite_mobile) {$ip_db = substr($ip_db, 1);}
		$champs = explode(_DB_VISITES_INDICATEUR_REFERER, $ip_db, 2);
		if (count($champs) > 1) {list($ip_augmentee, $referer) = $champs;}
		else {$referer = _DB_VISITES_REFERER_DIRECT;$ip_augmentee = $champs[0];}
		$elements_ip = explode(_DB_VISITES_SEPARATEUR_IP, $ip_augmentee, 5);
		$pays = $elements_ip[1];$ville = $elements_ip[2];
		$lon = (float) $elements_ip[3];$lat = (float) $elements_ip[4];
		return array($pays, $ville, $lon, $lat);
	}
	
	$session = new session();
	if (is_null($session)) {
		header("Location: "._SESSION_URL_FERMETURE);
		exit;
	}
	$session->check_session();
	$param = new param();
	$nom_pays = $param->get(_PARAM_ID);
	if (strlen($nom_pays) == 0) {
		$session->fermer_session();
		exit;
	}

	$page = $session->get_session_param(_SESSION_PARAM_PAGE);
	if (strlen($page) == 0) {
		$session->fermer_session();
		exit;
	}

	echo "<div class=\"analitix_lb\">\n";
	if (!(array_key_exists($nom_pays, $tab_pays))) {
		echo "<p style=\"padding:25px 10px;\">Désolé, le pays <b>".$nom_pays."</b> n'est pas disponible actuellement...<br/><br/>Veuillez contacter <b><a href=\"mailto:petilabo@gmail.com\" title=\"Ecrire à petilabo@gmail.com\" style=\"color:#333\">petilabo@gmail.com</a></b> pour une demande d'ajout à PetiLabo Analitix.</p>\n";
	}
	else {
		// Création de la carte
		$base_fichier = $tab_pays[$nom_pays][(int) _ANALITIX_GEO_INDEX_FICHIER];
		$fichier_jpg = _DB_PATH_ROOT.$base_fichier.".jpg";
		$zoom = (int) $tab_pays[$nom_pays][(int) _ANALITIX_GEO_INDEX_ZOOM];
		list($from_x, $from_y, $to_x, $to_y) = $tab_pays[$nom_pays][(int) _ANALITIX_GEO_INDEX_COORD];
		if (!(@file_exists($fichier_jpg))) {
			// Todo : supprimer l'ancienne carte (paramétrable ?)
			$osm = new openstreetmap();
			$osm->prepareMapAdmin(_PHP_PATH_ROOT, $zoom, $from_x, $from_y, $to_x, $to_y);
			$osm->makeMap($fichier_jpg);
		}
		
		// Calcul des coordonnées
		$n = pow(2, $zoom);
		list($from_lon, $from_lat) = conversion_lon_lat($n, $from_x, $from_y);
		list($to_lon, $to_lat) = conversion_lon_lat($n, 1 + $to_x, 1 + $to_y);
		$width_lon = $to_lon - $from_lon;$height_lat = $to_lat - $from_lat;
		$width_pixel = 8 * (1 + $to_x - $from_x);$height_pixel = 8 * (1 + $to_y - $from_y);
		$pas_lon = (float) ($width_pixel / $width_lon);$pas_lat = (float) ($height_pixel / $height_lat);

		// Lecture de la base de données
		$total_visites = 0;$total_visites_non_identifie = 0;
		$nom_db = _DB_PATH_ROOT.$page._DB_EXT;
		$fichier_db = @gzopen($nom_db, "r");
		if ($fichier_db) {
			while (!(@gzeof($fichier_db))) { 
				$ligne = @gzgets($fichier_db);
				$champs = explode("|", $ligne);
				if (count($champs) != 3) {continue;}
				list($date_db, $ip_db, $nb_db) = $champs;
				$nb_visites = (int) $nb_db;
				if (($nb_visites < 1) || ($nb_visites > ((int) _DB_VISITES_LIMITE_FLOODING))) {continue;}
				list($pays, $ville, $lon, $lat) = parser_geo_ip_db($ip_db);
				if ($pays != $nom_pays) {continue;}
				$total_visites += $nb_visites;
				if (($lon == _DB_VISITES_LABEL_COORD_INCONNUE) || ($lat == _DB_VISITES_LABEL_COORD_INCONNUE)) {
					$total_visites_non_identifie += $nb_visites;continue;
				}
				$x_stat = (int) ($pas_lon * ((float) ($lon - $from_lon)));
				$y_stat = (int) ($pas_lat * ((float) ($lat - $from_lat)));
				$n = ($width_pixel * $y_stat) + $x_stat;
				if (isset($tab_stat[$n])) {$tab_stat[$n] += $nb_visites;}
				else {$tab_stat[$n] = $nb_visites;}
				if (!(strcmp($ville, _DB_VISITES_LABEL_GEOLOC_INCONNUE))) {continue;}
				if (isset($tab_ville_stat[$n])) {
					if (array_key_exists($ville, $tab_ville_stat[$n])) {
						$tab_ville_stat[$n][$ville] += $nb_visites;
					}
					else {
						$tab_ville_stat[$n][$ville] = $nb_visites;
					}
				}
				else {
					$tab_ville_stat[$n] = array($ville => $nb_visites);
				}
			}
			@gzclose($fichier_db);
		}
		$max_stat = (count($tab_stat) > 0)?max($tab_stat):1;

		// Dessin de la carte
		echo "<div class=\"analitix_lb\">\n";
		$label_visites = " visite".(($total_visites > 1)?"s":"");
		$non_id = ($total_visites_non_identifie > 0)?" (dont ".$total_visites_non_identifie." non géolocalisées)":"";
		echo "<p>".$nom_pays." : ".$total_visites.$label_visites.$non_id."</p>\n";
		echo "<div class=\"analitix_img\">\n";
		echo "<img src=\"".$fichier_jpg."\" />";
		foreach($tab_stat as $n => $stat) {
			$label_visites = " visite".(($stat > 1)?"s":"");
			$x = $n % $width_pixel;$y = ($n - $x)/$width_pixel;
			if (isset($tab_ville_stat[$n])) {
				arsort($tab_ville_stat[$n]);
				$spot = $stat.$label_visites." (";
				$nb_villes = 0;
				foreach ($tab_ville_stat[$n] as $ville => $nb) {
					if ($nb_villes > 0) {$spot .= ", ";}
					$spot .= $ville;$nb_villes += 1;
					if ($nb_villes > 3) {$spot .= ",...";break;}
				}
				$spot .= ")";
			}
			else {
				$spot = $stat.$label_visites;
			}
			$rayon = max(6,(int) (32 * ((float) ($stat / $max_stat))));
			$delta = (int) ((32 - $rayon) / 2);
			$pos_x = 4 + $delta + $x * 32;$pos_y = 12 + $delta + $y * 32;
			echo "<p style=\"position:absolute;top:".$pos_y."px;left:".$pos_x."px;\"><a href title=\"".$spot."\"><img src=\"images/spot.png\" width=\"".$rayon."\" height=\"".$rayon."\" /></a></p>\n";
		}
		echo "</div>\n";
	}
	echo "</div>\n";