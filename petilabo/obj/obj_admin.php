<?php
require_once(_PHP_PATH_INCLUDE."visites.php");

define("_DB_VISITES_COURBES_LARGEUR", "800");
define("_DB_VISITES_COURBES_HAUTEUR", "200");
define("_DB_VISITES_TABLEAU_MAX_LIGNES", "25");

class obj_admin extends obj_editable {
	private $nom_page = null;
	private $version_txt = null;private $version_php = null;
	private $taille_php = 0;private $taille_xml = 0;
	private $stat_total_visites = 0;private $stat_total_visites_mobiles = 0;
	private $stat_total_visiteurs = 0;private $stat_total_visiteurs_recurrents = 0;
	private $db_ip = array();private $db_ip_geolocalisation = array();
	private $db_visites = array();private $db_visites_uniques = array();
	private $db_visites_mobiles = array();private $db_visites_referers = array();
	private $db_visites_pays = array();private $db_visites_langues = array();
	private $db_tab_mois = array("", "janv", "fév", "mars", "avr", "mai", "juin", "juil", "août", "sept", "oct", "nov", "déc");
	private $config_stat = false;

	public function __construct($nom_page, $is_multilingue, $nb_langues, $is_noindex) {
		$this->nom_page = $nom_page;
		if ($nb_langues > 1) {$this->is_multilingue = $is_multilingue;}
		else {$this->is_multilingue = false;}
		$this->is_noindex = $is_noindex;
		$lecture = file_get_contents(_PETIXML_CHEMIN_VERSION_TXT._PETIXML_FICHIER_VERSION_TXT);
		$this->version_txt = preg_replace("~[[:cntrl:][:space:]]~", "", $lecture);
		$this->version_php = _VERSION_PETILABO;
		$this->version_maj = strcmp($this->version_txt, $this->version_php);
		$this->taille_php = $this->taille_repertoire(_PHP_PATH_ROOT);
		$this->taille_xml = $this->taille_repertoire(_XML_PATH_ROOT);
		$annee = date("Y");$mois = date("m");$jour = date("j");
		for ($cpt = (((int) _DB_VISITES_DUREE_ARCHIVAGE) - 2);$cpt >= 0;$cpt--) {
			$date = date("ymd", mktime(0, 0, 0, $mois, $jour-$cpt, $annee));
			$this->db_visites[$date] = 0;
			$this->db_visites_uniques[$date] = array();
			$this->db_visites_mobiles[$date] = 0;
		}
	}
	public function ajouter_statistiques() {
		$this->config_stat = true;
	}
	public function afficher($mode, $langue) {
		echo "<div class=\"panneau_admin\">\n";
		echo "<h1>Administration de la page <strong>".$this->nom_page."</strong></h1>\n";
		echo "<table><tr>\n";
		echo "<td>\n";
		echo "<p>Page multilingue : ".($this->is_multilingue?"Oui":"Non")."</p>\n";
		echo "<p>Langue affichée : ".strtoupper($langue)."</p>\n";
		echo "<p>Indexation autorisée : ".($this->is_noindex?"Non":"Oui")."</p>\n";
		echo "</td>\n";
		echo "<td>\n";
		echo "<p class=\"admin_info_site\"><span class=\"icone_prefixe\">&#xf07b;</span> petilabo/<span class=\"taille_suffixe\">".$this->conversion_taille($this->taille_php)."</span></p>\n";
		echo "<p class=\"admin_info_site\"><span class=\"icone_prefixe\">&#xf07b;</span> xml/<span class=\"taille_suffixe\">".$this->conversion_taille($this->taille_xml)."</span></p>\n";
		$taille_totale = $this->taille_php + $this->taille_xml;
		echo "<p class=\"admin_info_site\"><span class=\"icone_prefixe\">&#xf07c;</span> Total <span class=\"taille_suffixe\">".$this->conversion_taille($taille_totale)."</span></p>\n";
		echo "</td>\n";
		echo "<td>\n";
		echo "<p class=\"admin_info_version\"><span class=\"icone_prefixe\">&#xf0c3;</span> ".$this->version_php."</p>\n";
		if ($this->version_maj) {
			echo "<p class=\"admin_info_version\"><span class=\"icone_prefixe\">&#xf021;</span> ".$this->version_txt."</p>\n";
			echo "<p class=\"admin_bouton_version\"><a href=\"update.php?v=".urlencode($this->version_txt)."\" title=\"Installation automatique de la version ".$this->version_txt."\">Mettre&nbsp;à&nbsp;jour</a></p>";		
		}
		else {
			echo "<p class=\"admin_annotation\">&Agrave; jour</p>\n";
		}
		echo "</td>\n";
		echo "</tr></table>\n";
		if ($this->config_stat) {$this->afficher_statistiques();}
		echo "</div>\n";
	}
	private function taille_repertoire($path){
		$bytestotal = 0;
		$path = realpath($path);
		if ($path !== false){
			foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
				try {$bytestotal += $object->getSize();}
				catch(Exception $e) {}
			}
		}
		return $bytestotal;
	}
	private function conversion_taille($taille, $decimales = 2) {
		$taille_mo = (float) ($taille / (1024*1024));
		$conversion = sprintf("%.2f Mo",$taille_mo);
		return $conversion;
	}
	private function afficher_statistiques() {
		// Remplissage des tableaux db_
		$nom_db = _DB_PATH_ROOT.$this->nom_page._DB_EXT;
		$fichier = @gzopen($nom_db, "r");
		if ($fichier) {
			while (!(@gzeof($fichier))) { 
				$ligne = @gzgets($fichier);
				$champs = explode("|", $ligne);
				if (count($champs) != 3) {continue;}
				list($date_db, $ip_db, $nb_db) = $champs;
				if (!(isset($this->db_visites[$date_db]))) {continue;}
				$nb_visites = (int) $nb_db;
				if (($nb_visites < 1) || ($nb_visites > ((int) _DB_VISITES_LIMITE_FLOODING))) {continue;}
				list($langue, $visite_mobile, $visite_referer, $ip_stricte, $pays) = $this->parser_ip_db($ip_db);
				if (array_key_exists($ip_stricte, $this->db_ip)) {$this->db_ip[$ip_stricte] += $nb_db;}
				else {$this->db_ip[$ip_stricte] = $nb_db;}
				$this->db_visites[$date_db] += $nb_db;$this->stat_total_visites += $nb_db;
				if (!(@in_array($ip_stricte, $this->db_visites_uniques[$date_db]))) {$this->db_visites_uniques[$date_db][] = $ip_stricte;}
				if ($visite_mobile) {$this->db_visites_mobiles[$date_db] += $nb_db;$this->stat_total_visites_mobiles += $nb_db;}
				if (array_key_exists($visite_referer, $this->db_visites_referers)) {$this->db_visites_referers[$visite_referer] += $nb_db;}
				else {$this->db_visites_referers[$visite_referer] = $nb_db;}
				if (strlen($pays) > 0) {
					if (array_key_exists($pays, $this->db_visites_pays)) {$this->db_visites_pays[$pays] += $nb_db;}
					else {$this->db_visites_pays[$pays] = $nb_db;}
				}
				if (strlen($langue) > 0) {
					if (array_key_exists($langue, $this->db_visites_langues)) {$this->db_visites_langues[$langue] += $nb_db;}
					else {$this->db_visites_langues[$langue] = $nb_db;}
				}
			}
			@gzclose($fichier);
		}
		foreach ($this->db_visites_uniques as $date => $tab_visiteurs) {$this->stat_total_visiteurs += count($tab_visiteurs);}
		foreach ($this->db_ip as $ip => $nb_visites) {if ($nb_visites > 1) {$this->stat_total_visiteurs_recurrents += 1;}}
		arsort($this->db_visites_referers);arsort($this->db_visites_pays);arsort($this->db_visites_langues);
		// Ouverture des onglets
		echo "<div class=\"admin_stat_container\">\n";
		echo "<p class=\"admin_onglets_stats\"><a id=\"onglet_stats\" style=\"background:#666\">Statistiques</a>&nbsp;&nbsp;<a id=\"onglet_visites_uniques\" href=\"#\">Visiteurs</a>&nbsp;&nbsp;<a id=\"onglet_visites\" href=\"#\">Visites</a>&nbsp;&nbsp;<a id=\"onglet_geoloc\" href=\"#\">International</a></p>\n";
		echo "<div id=\"onglets_wrapper\" class=\"admin_courbe_wrapper\">\n";
		// Tracé des courbes
		$this->afficher_stats();
		$this->afficher_visiteurs();
		$this->afficher_visites();
		$this->afficher_geoloc();
		// Fermeture des onglets
		echo "</div></div>\n";
	}
	private function afficher_stats() {
		echo "<div id=\"stats\" class=\"admin_courbe_stats\">\n";
		echo "<table class=\"admin_tab_stats_wrapper\"><tr><td>";
		$this->afficher_section_stat("Totaux mensuels");
		echo "<table class=\"admin_tab_stats_content\" style=\"margin-bottom:8px;\">";
		$this->afficher_stat("Nombre total de visiteurs", $this->stat_total_visiteurs);
		$this->afficher_stat("Dont visiteurs récurrents", $this->stat_total_visiteurs_recurrents);
		$this->afficher_stat("Nombre total de visites", $this->stat_total_visites);
		$moyenne_quotidienne = sprintf("%.1f", ($this->stat_total_visites)/30);
		$this->afficher_stat("Dont visites mobiles", $this->stat_total_visites_mobiles);
		$this->afficher_stat("Nombre moyen de visites par jour", $moyenne_quotidienne);
		echo "</table>";
		$this->afficher_section_stat("Accès autres que référents");
		echo "<table class=\"admin_tab_stats_content\">";
		foreach ($this->db_visites_referers as $referer => $nb) {
			if (!(strcmp($referer, _DB_VISITES_REFERER_DIRECT))) {$this->afficher_stat_pc("Accès directs (favoris...)", $nb, $this->stat_total_visites);}
			elseif (!(strcmp($referer, _DB_VISITES_REFERER_SELF))) {$this->afficher_stat_pc("Accès par navigation interne", $nb, $this->stat_total_visites);}
		}
		echo "</table></td><td>";
		$this->afficher_section_stat("Principaux référents");
		echo "<div class=\"admin_stat_scrollable\"><table class=\"admin_tab_stats_content\">";
		$nb_lignes = 0;
		foreach ($this->db_visites_referers as $referer => $nb) {
			if ($nb_lignes >= ((int) _DB_VISITES_TABLEAU_MAX_LIGNES)) {continue;}
			if ((strcmp($referer, _DB_VISITES_REFERER_DIRECT)) && (strcmp($referer, _DB_VISITES_REFERER_SELF))) {
				$this->afficher_stat_pc($referer, $nb, $this->stat_total_visites);
				$nb_lignes += 1;
			}
		}
		echo "</table></div></td></tr></table>\n";
		echo "</div>\n";
	}
	private function afficher_visiteurs() {
		$script_tab = "";
		foreach ($this->db_visites_uniques as $date_visite => $tab_visites_uniques) {
			$nb_visites_uniques = count($tab_visites_uniques);
			if (strlen($script_tab) > 0) {$script_tab .= ",";}
			$jour = (int) substr($date_visite, 4, 2);
			$mois = (int) substr($date_visite, 2, 2);
			$val_x = $jour." ".$this->db_tab_mois[$mois];
			$script_tab .=  "{ X: \"".$val_x."\", Y:".(int) $nb_visites_uniques."}";
		}
		$this->afficher_script_chart("#000088", "Visiteurs", "visites_uniques", $script_tab);
	}
	private function afficher_visites() {
		$script_tab = "";
		foreach ($this->db_visites as $date_visite => $nb_visites) {
			if (strlen($script_tab) > 0) {$script_tab .= ",";}
			$jour = (int) substr($date_visite, 4, 2);
			$mois = (int) substr($date_visite, 2, 2);
			$val_x = $jour." ".$this->db_tab_mois[$mois];
			$script_tab .=  "{ X: \"".$val_x."\", Y:".(int) $nb_visites."}";
		}
		$this->afficher_script_chart("#008800", "Visites", "visites", $script_tab);
	}
	private function afficher_geoloc() {
		echo "<div id=\"geoloc\" class=\"admin_courbe_stats\">\n";
		echo "<table class=\"admin_tab_stats_wrapper\"><tr><td>";
		$this->afficher_section_stat("Pays");
		echo "<div class=\"admin_stat_scrollable\"><table class=\"admin_tab_stats_content\">";
		$nb_lignes = 0;
		foreach ($this->db_visites_pays as $pays => $nb) {
			if ($nb_lignes >= ((int) _DB_VISITES_TABLEAU_MAX_LIGNES)) {continue;}
			if (!(strcmp($pays, _DB_VISITES_LABEL_GEOLOC_INCONNUE))) {continue;}
			$this->afficher_stat_pc_pays($pays, $nb, $this->stat_total_visites);
			$nb_lignes += 1;
		}
		echo "</table></div></td><td>";
		$this->afficher_section_stat("Langues");
		echo "<table class=\"admin_tab_stats_content\">";
		foreach ($this->db_visites_langues as $langue => $nb) {$this->afficher_stat_pc($langue, $nb, $this->stat_total_visites);}
		echo "</table></td></tr></table>\n";
		echo "</div>\n";
	}
	private function afficher_script_chart($couleur, $titre, $identifiant, &$tab_valeurs) {
		$script = "<script type=\"text/javascript\">\n";
		$script .= "var graph_".$identifiant." = {linecolor: \"".$couleur."\",title: \"".$titre."\", values: [".$tab_valeurs."]};\n";
		$script .= "$(function () {";
		$script .= "$(\"#".$identifiant."\").SimpleChart({ChartType: \"Line\", toolwidth: \"60\", toolheight: \"40\", axiscolor: \"#555555\", textcolor: \"#444444\", showlegends: false, data: [graph_".$identifiant."], xaxislabel: null, title: '".$titre."', yaxislabel: null});";
		$script .= "});\n";
		$script .=  "</script>\n";
		$script .=  "<div id=\"".$identifiant."\" class=\"admin_courbe_stats\"></div>\n";
		echo "<!--[if !IE]> -->\n";
		echo $script;
		echo "<!-- <![endif]-->\n<!--[if gte IE 9]>\n";
		echo $script;
		echo "<![endif]-->\n<!--[if lte IE 8]>\n";
		echo "<div id=\"".$identifiant."\" class=\"admin_courbe_stats\"><p class=\"admin_msg_erreur\">Désolé, votre navigateur est trop ancien pour afficher la courbe des ".strtolower($titre)."...</div>\n";
		echo "<![endif]-->\n";
	}
	private function afficher_section_stat($section) {
		echo "<p class=\"stats_section\">".$section."</p>";
	}
	private function afficher_stat($label, $valeur) {
		echo "<tr><td><p class=\"stats_label\">".$label."</p></td><td class=\"admin_cellule_pc\"><p class=\"stats_valeur\">".$valeur."</p></td></tr>";
	}
	private function afficher_stat_pc($label, $valeur, $total) {
		echo "<tr><td><p class=\"stats_label\">".$label."</p></td><td class=\"admin_cellule_pc\"><p class=\"stats_valeur\">".$valeur."</p></td><td class=\"admin_cellule_pc\"><p class=\"stats_valeur\">".round((100.0 * ((float) $valeur)/((float) $total)),1)."%</p></td></tr>";
	}
	private function afficher_stat_pc_pays($label, $valeur, $total) {
		echo "<tr><td><p class=\"stats_label\">".$label."<a class=\"symbole symbole_geo\" href=\"form_analitix.php?"._PARAM_ID."=".urlencode($label)."\" title=\"".$label." : voir les détails sur une carte du pays\">&#xf14c;</a></p></td><td class=\"admin_cellule_pc\"><p class=\"stats_valeur\">".$valeur."</p></td><td class=\"admin_cellule_pc\"><p class=\"stats_valeur\">".round((100.0 * ((float) $valeur)/((float) $total)),1)."%</p></td></tr>";
	}
	private function parser_ip_db($ip_db) {
		$langue = substr($ip_db, 0, 2);$ip_db = substr($ip_db, 2);
		$visite_mobile = (!(strcmp(substr($ip_db, 0, 1), _DB_VISITES_INDICATEUR_MOBILE)));
		if ($visite_mobile) {$ip_db = substr($ip_db, 1);}
		$champs = explode(_DB_VISITES_INDICATEUR_REFERER, $ip_db, 2);
		if (count($champs) > 1) {list($ip_augmentee, $referer) = $champs;}
		else {$referer = _DB_VISITES_REFERER_DIRECT;$ip_augmentee = $champs[0];}
		$elements_ip = explode(_DB_VISITES_SEPARATEUR_IP, $ip_augmentee, 5);
		$ip_stricte = $elements_ip[0];$pays = $elements_ip[1];
		return array($langue, $visite_mobile, $referer, $ip_stricte, $pays);
	}
}