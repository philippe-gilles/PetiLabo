<?php
	require_once "inc/path.php";
	require_once _PHP_PATH_INCLUDE."visites.php";

	$tab_pays = array(
		"Serveur local" => array(46.7,2.5,6),
		"Canada" => null,
		"France" => array(46.7,2.5,6),
		"Germany" => array(50.3,9,6),
		"Italy" => null,
		"Japan" => null,
		"Netherlands" => array(52,5,7),
		"Romania" => null,
		"Switzerland" => null,
		"United Kingdom" => null,
		"United States" => array(41,-101,4),
	);
	$tab_stat = array();$tab_stat_villes = array();
	
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
	if (!(isset($tab_pays[$nom_pays]))) {
		echo "<p style=\"padding:25px 10px;\">Désolé, le pays <b>".$nom_pays."</b> n'est pas disponible actuellement...<br/><br/>Veuillez contacter <b><a href=\"mailto:petilabo@gmail.com\" title=\"Ecrire à petilabo@gmail.com\" style=\"color:#333\">petilabo@gmail.com</a></b> pour une demande d'ajout à PetiLabo Analitix.</p>\n";
	}
	else {
		// Création de la carte
		list($olat, $olon, $ozoom) = $tab_pays[$nom_pays];
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
				if (($lon == _DB_VISITES_LABEL_COORD_INCONNUE) || ($lat == _DB_VISITES_LABEL_COORD_INCONNUE)) {continue;}
				$rlon = (int) (100 * $lon);$rlat = (int) (100 * $lat);
				if (isset($tab_stat[$rlon])) {
					if (isset($tab_stat[$rlon][$rlat])) {
						$tab_stat[$rlon][$rlat] += $nb_visites;
						if (!(in_array($ville, $tab_stat_villes[$rlon][$rlat]))) {$tab_stat_villes[$rlon][$rlat][] = $ville;}
					}
					else {
						$tab_stat[$rlon][$rlat] = $nb_visites;
						$tab_stat_villes[$rlon][$rlat][] = $ville;
					}
				}
				else {
					$tab_stat[$rlon] = array();$tab_stat_villes[$rlon] = array();
					$tab_stat[$rlon][$rlat] = $nb_visites;
					$tab_stat_villes[$rlon][$rlat] = array();
					$tab_stat_villes[$rlon][$rlat][] = $ville;
				}
			}
			@gzclose($fichier_db);
		}

		// Dessin de la carte
		echo "<div class=\"analitix_lb\">\n";
		echo "<p>".$nom_pays."</p>\n";
		echo "<div id=\"analitix_leaflet\" class=\"analitix_img\"></div>\n";
		echo "</div>\n";
		echo "<script type=\"text/javascript\">\n";
		echo "$(document).ready(function(){\n";
		echo "map = new L.Map('analitix_leaflet');\n";
		echo "var osmUrl='http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';\n";
		echo "var osmAttrib='Map data © <a href=\"http://openstreetmap.org\">OpenStreetMap</a> contributors';\n";
		echo "var osm = new L.TileLayer(osmUrl, {minZoom: 8, maxZoom: 12, attribution: osmAttrib,minZoom: 4, maxZoom: 18});\n";
		echo "map.setView(new L.LatLng(".$olat.", ".$olon."),".$ozoom.");\n";
		echo "map.addLayer(osm);\n";
		foreach ($tab_stat as $rlon => $tab) {
			foreach ($tab as $rlat => $nb_visites) {
				echo "var marker = L.marker([".(float) ($rlat / 100).", ".(float) ($rlon / 100)."]).addTo(map);\n";
				echo "marker.bindPopup(\"<b>".$nb_visites." visite".(($nb_visites > 1)?"s":"")."</b><br/><i>".implode(", ",$tab_stat_villes[$rlon][$rlat])."</i>\");\n";
			}
		}
		echo "});\n";
		//echo "var popup = L.popup();function onMapClick(e) {popup.setLatLng(e.latlng).setContent(\"Coordonnées = \" + e.latlng.toString()).openOn(map);}map.on('click', onMapClick);\n";
		echo "</script>\n";
	}