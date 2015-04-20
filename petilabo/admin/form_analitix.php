<?php
	require_once "inc/path.php";
	require_once _PHP_PATH_INCLUDE."visites.php";

	$tab_pays = array(
		"Serveur local" => array(46.7,2.5,6),"Afghanistan"  => array(33,65,6),"Albania"  => array(41,20,6),"Algeria"  => array(28,3,5),"American Samoa"  => array(-14.33,-170,6),"Andorra"  => array(42.50,1.50,6),"Angola"  => array(-12.50,18.50,7),"Anguilla"  => array(18.25,-63.17,6),	"Antarctica"  => array(-90,0,6),"Antigua and Barbuda"  => array(17.05,-61.80,6),"Argentina"  => array(-34,-64,5),"Armenia"  => array(40,45,6),"Aruba"  => array(12.50,-69.97,6),"Australia"  => array(-27,133,5),"Austria"  => array(47.33,13.33,6),"Azerbaijan"  => array(40.50,47.50,6),"Bahamas"  => array(24.25,-76,6),"Bahrain"  => array(26,50.55,6),"Bangladesh"  => array(24,90,6),"Barbados"  => array(13.17,-59.53,6),"Belarus"  => array(53,28,6),"Belgium"  => array(50.83,4,6),"Belize"  => array(17.25,-88.75,6),"Benin"  => array(9.50,2.25,6),"Bermuda"  => array(32.33,-64.75,6),"Bhutan"  => array(27.50,90.50,6),"Bolivia"  => array(-17,-65,7),"Bosnia and Herzegovina"  => array(44,18,6),"Botswana"  => array(-22,24,6),"Brazil"  => array(-10,-55,5),"Brunei"  => array(4.50,114.67,6),"Bulgaria"  => array(43,25,6),"Burkina Faso"  => array(13,-2,6),"Burundi"  => array(-3.50,30,6),"Cambodia"  => array(13,105,6),"Cameroon"  => array(6,12,7),"Canada"  => array(60,-95,5),"Cape Verde"  => array(16,-24,6),"Cayman Islands"  => array(19.50,-80.50,6),"Central African Republic"  => array(7,21,6),"Chad"  => array(15,19,5),"Chile"  => array(-30,-71,5),"China"  => array(35,105,5),"Colombia"  => array(4,-72,5),"Comoros"  => array(-12.17,44.25,6),"Congo"  => array(-1,15,6),"Congo, The Democratic Republic of the"  => array(0.02,25,5),"Costa Rica"  => array(10,-84,6),"Cote d'Ivoire"  => array(8,-5,6),"Croatia"  => array(45.17,15.50,6),"Cuba"  => array(21.50,-80,6),"Cyprus"  => array(35,33,6),"Czech Republic"  => array(49.75,15.50,6),"Denmark"  => array(56,10,6),"Djibouti"  => array(11.50,43,6),"Dominica"  => array(15.42,-61.33,6),"Dominican Republic"  => array(19,-70.67,6),"Ecuador"  => array(-2,-77.50,6),"Egypt"  => array(27,30,6),"El Salvador"  => array(13.83,-88.92,6),"Equatorial Guinea"  => array(2,10,6),"Eritrea"  => array(15,39,6),"Estonia"  => array(59,26,6),"Ethiopia"  => array(8,38,7),"Faroe Islands"  => array(62,-7,6),"Fiji"  => array(-18,175,6),"Finland"  => array(64,26,7),"France"  => array(46.7,2.5,6),"French Guiana"  => array(4,-53,6),"French Polynesia"  => array(-15,-140,6),"Gabon"  => array(-1,11.75,6),"Gambia"  => array(13.47,-16.57,6),	"Georgia"  => array(42,43.50,6),"Germany"  => array(50.3,9,6),"Ghana"  => array(8,-2,6),"Gibraltar"  => array(36.13,-5.35,6),"Greece"  => array(39,22,6),"Greenland"  => array(72,-40,5),"Grenada"  => array(12.12,-61.67,6),"Guadeloupe"  => array(16.25,-61.58,9),"Guatemala"  => array(15.50,-90.25,6),"Guernsey"  => array(49.47,-2.58,6),"Guinea"  => array(11,-10,6),"Guinea-Bissau"  => array(12,-15,6),"Guyana"  => array(5,-59,6),"Haiti"  => array(19,-72.42,6),"Honduras"  => array(15,-86.50,6),"Hong Kong"  => array(22.25,114.17,6),"Hungary"  => array(47,20,6),"Iceland"  => array(65,-18,6),"India"  => array(20,77,5),"Indonesia"  => array(-5,120,5),"Iran"  => array(32,53,7),"Iraq"  => array(33,44,6),"Ireland"  => array(53,-8,6),"Israel"  => array(31.50,34.75,6),"Italy"  => array(42.83,12.83,7),"Jamaica"  => array(18.25,-77.50,6),"Japan"  => array(36,138,5),"Jersey"  => array(49.25,-2.17,6),"Jordan"  => array(31,36,6),"Kazakhstan"  => array(48,68,7),"Kenya"  => array(1,38,6),"Kiribati"  => array(1.42,173,5),"Korea, Democratic People's Republic of"  => array(40,127,6),"Korea, Republic of"  => array(37,127.50,6),"Kuwait"  => array(29.37,47.97,6),"Kyrgyzstan"  => array(41,75,6),"Laos"  => array(18,105,6),"Latvia"  => array(57,25,6),"Lebanon"  => array(33.83,35.83,6),"Lesotho"  => array(-29.50,28.50,6),"Liberia"  => array(6.50,-9.50,6),"Libya"  => array(25,17,7),"Liechtenstein"  => array(47.27,9.53,6),"Lithuania"  => array(56,24,6),"Luxembourg"  => array(49.75,6.17,6),"Macao"  => array(22.17,113.55,6),"Macedonia"  => array(41.83,22,6),"Madagascar"  => array(-20,47,7),"Malawi"  => array(-13.50,34,6),"Malaysia"  => array(2.50,112.50,6),"Maldives"  => array(3.25,73,6),"Mali"  => array(17,-4,7),"Malta"  => array(35.83,14.58,6),"Martinique"  => array(14.60,-61.08,6),"Mauritania"  => array(20,-12,7),"Mauritius"  => array(-20.28,57.55,7),"Mayotte"  => array(-12.83,45.17,6),"Mexico"  => array(23,-102,5),"Micronesia"  => array(6.92,158.25,6),"Moldova"  => array(47,29,6),"Monaco"  => array(43.73,7.42,6),"Mongolia"  => array(46,105,7),"Montenegro"  => array(42.50,19.30,6),"Montserrat"  => array(16.75,-62.20,6),"Morocco"  => array(32,-5,6),"Mozambique"  => array(-18.25,35,5),"Myanmar"  => array(22,98,5),"Namibia"  => array(-22,17,7),"Nepal"  => array(28,84,6),"Netherlands"  => array(52,5,7),"New Caledonia"  => array(-21.50,165.50,6),"New Zealand"  => array(-41,174,5),"Nicaragua"  => array(13,-85,6),"Niger"  => array(16,8,7),"Nigeria"  => array(10,8,6),"Norfolk Island"  => array(-29.03,167.95,6),"Norway"  => array(62,10,5),"Occupied Palestinian Territory"  => array(32,35.25,6),"Oman"  => array(21,57,6),"Pakistan"  => array(30,70,7),"Palau"  => array(7.50,134.50,6),"Panama"  => array(9,-80,6),"Papua New Guinea"  => array(-6,147,7),"Paraguay"  => array(-23,-58,6),"Peru"  => array(-10,-76,5),"Philippines"  => array(13,122,5),"Poland"  => array(52,20,6),"Portugal"  => array(39.50,-8,7),"Puerto Rico"  => array(18.25,-66.50,6),"Qatar"  => array(25.50,51.25,6),"Reunion"  => array(-21.10,55.60,6),"Romania"  => array(46,25,6),"Russia"  => array(60,100,5),"Rwanda"  => array(-2,30,6),"Saint Barthelemy"  => array(17.90,-62.83,6),"Saint Helena, Ascension and Tristan da Cunha"  => array(-15.95,-5.70,6),"Saint Kitts and Nevis"  => array(17.33,-62.75,6),"Saint Lucia"  => array(13.88,-60.97,6),"Saint Martin"  => array(18.08,-63.06,6),"Saint Pierre and Miquelon"  => array(46.83,-56.33,6),	"Saint Vincent and The Grenadines"  => array(13.25,-61.20,6),"Samoa"  => array(-13.58,-172.33,6),"San Marino"  => array(43.93,12.42,6),"Sao Tome and Principe"  => array(1,7,6),"Saudi Arabia"  => array(25,45,5),"Senegal"  => array(14,-14,6),"Serbia"  => array(44,21,6),"Seychelles"  => array(-4.58,55.67,6),"Sierra Leone"  => array(8.50,-11.50,6),"Singapore"  => array(1.37,103.80,6),"Slovakia"  => array(48.67,19.50,6),"Slovenia"  => array(46.12,14.82,6),"Solomon Islands"  => array(-8,159,6),"Somalia"  => array(10,49,7),"South Africa"  => array(-29,24,5),"South Sudan"  => array(8,30,6),"Spain"  => array(40,-4,5),"Sri Lanka"  => array(7,81,6),"Sudan"  => array(15,30,5),"Suriname"  => array(4,-56,6),"Swaziland"  => array(-26.50,31.50,6),"Sweden"  => array(62,15,7),"Switzerland"  => array(47,8,6),"Syrian Arab Republic"  => array(35,38,6),"Taiwan"  => array(23.50,121,6),"Tajikistan"  => array(39,71,6),"Tanzania"  => array(-6,35,7),"Thailand"  => array(15,100,7),"Timor-Leste"  => array(-8.83,125.92,6),"Togo"  => array(8,1.17,6),"Tonga"  => array(-20,-175,6),"Trinidad and Tobago"  => array(11,-61,6),"Tunisia"  => array(34,9,6),"Turkey"  => array(39,35,6),"Turkmenistan"  => array(40,60,6),"Tuvalu"  => array(-8,178,6),"Uganda"  => array(1,32,6),"Ukraine"  => array(49,32,6),"United Arab Emirates"  => array(24,54,6),"United Kingdom"  => array(54,-2,7),"United States"  => array(41,-101,4),"Uruguay"  => array(-33,-56,6),	"Uzbekistan"  => array(41,64,6),"Vanuatu"  => array(-16,167,6),"Venezuela"  => array(8,-66,7),"Viet Nam"  => array(16.17,107.83,7),"Wallis and Futuna"  => array(-13.30,-176.20,6),"Yemen"  => array(15,48,6),"Zambia"  => array(-15,30,6),"Zimbabwe"  => array(-20,30,6));
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