<?php

class html {
	// Types de pied de page
	private $interne = false;
	private $reduit = false;

	// Méthodes publiques
	public function __construct($interne=false, $reduit=false) {
		$this->interne = (int) $interne;
		$this->reduit = (int) $reduit;
	}
	public function ouvrir($langue="fr") {
		echo "<!doctype html>\n";
		// echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:og=\"http://ogp.me/ns#\" xml:lang=\"".$langue."\" lang=\"".$langue."\" dir=\"ltr\">\n";
		echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"".$langue."\" lang=\"".$langue."\" dir=\"ltr\">\n";
	}
	public function fermer() {
		echo "</html>";
	}
	public function ouvrir_head() {
		echo "<head>\n";
		echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\" />\n";
		echo "<meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\n";
		echo "<meta name=\"generator\" content=\"PetiLabo "._VERSION_PETILABO."\" />\n";
	}
	public function fermer_head() {
		echo "</head>\n";
	}
	public function ouvrir_body($police) {
		if (strlen($police) > 0) {
			$police = strcmp(trim(strtolower($police)), "serif")?"'".$police."'":$police;
			echo "<body class=\"exterieur\" style=\"font-family:".$police.",sans-serif;\">\n";
		}
		else {
			echo "<body class=\"exterieur\" style=\"font-family:sans-serif;\">\n";
		}
	}
	public function afficher_papierpeint_ie($image) {
		if (strlen($image) > 0) {
			echo "<!--[if lte IE 8]>\n";
			echo "<img class=\"papierpeint\" src=\""._XML_PATH_IMAGES_SITE.$image."\" alt=\"Papier peint\" />\n";
			echo "<![endif]-->\n";
		}
	}
	public function fermer_body() {
		echo "</body>\n";
	}
	public function ouvrir_balise_html5($balise) {
		if (strlen($balise) > 0) {
			echo "<!--[if !IE]> -->";
			echo "<".$balise.">";
			echo "<!-- <![endif]-->";
			echo "<!--[if gt IE 9]>";
			echo "<".$balise.">";
			echo "<![endif]-->\n";
		}
	}
	public function fermer_balise_html5($balise) {
		if (strlen($balise) > 0) {
			echo "<!--[if !IE]> -->";
			echo "</".$balise.">";
			echo "<!-- <![endif]-->";
			echo "<!--[if gt IE 9]>";
			echo "</".$balise.">";
			echo "<![endif]-->\n";
		}
	}
	public function ouvrir_page($largeur, $largeur_max, $largeur_min) {
		echo "<div class=\"global\">\n";
		echo "<div class=\"ligne_initiale\"></div>\n";
		$style = "";
		if (strlen($largeur) > 0) {
			$style .=  "width:".$largeur.";";
		}
		if (strlen($largeur_max) > 0) {
			$style .=  "max-width:".$largeur_max.";";
		}
		if (strlen($largeur_min) > 0) {
			$style .=  "min-width:".$largeur_min.";";
		}
		if (strlen($style) > 0) {
			echo "<div class=\"page interieur\" style=\"".$style."\">\n";
		}
		else {
			echo "<div class=\"page interieur\">\n";
		}
	}
	public function fermer_page($admin, $page, $proprietaire, $mentions, $credits, $plan, $webmaster, $social, $tab_social) {
		echo "</div>\n";
		$this->ouvrir_pp($admin);
		$this->ecrire_copy_admin_pp($admin, $page, $proprietaire);
		$this->ouvrir_balise_html5("nav");
		$this->ecrire_liens_pp($admin, $proprietaire, $mentions, $credits, $plan, $webmaster, $tab_social, $page);
		$this->ecrire_social_pp($admin, $social, $tab_social);
		$this->fermer_balise_html5("nav");
		$this->fermer_pp($admin);
		echo "</div>\n";
	}
	public function inserer_panneau_ga($le_site, $nom_site, $installer_ga, $accepter, $refuser) {
		$cookie_set = isset($_COOKIE["petilabo_ga"]);
		if ($cookie_set) {
			$cookie_val = $_COOKIE["petilabo_ga"];
			if ((strcmp($cookie_val, "ok")) && (strcmp($cookie_val, "nok"))) {$cookie_set = false;}
		}
		if ($cookie_set) {return;}
		$incipit = $le_site;
		$incipit .= (strlen($nom_site) > 0)?(" <strong>".trim($nom_site)."</strong>"):"";
		$incipit .= " ".$installer_ga;
		echo "<div class=\"wrap_panneau_ga\">\n";
		echo "<div class=\"panneau_ga\">";
		echo "<p class=\"incipit_ga\">".$incipit." :</p>";
		echo "<form class=\"form_ga\" method=\"post\" action=\"petilabo/inc/cookie_ok.php\"><input type=\"submit\" value=\"".$accepter."\"/></form>";
		echo "<form class=\"form_ga\" method=\"post\" action=\"petilabo/inc/cookie_nok.php\"><input type=\"submit\" value=\"".$refuser."\"/></form>";
		echo "</div>\n</div>\n";
	}
	public function inserer_ga($code_ga) {
		$cookie_set = isset($_COOKIE["petilabo_ga"]);
		$cookie_ok = ($cookie_set)?(!(strcmp($_COOKIE["petilabo_ga"], "ok"))):false;
		if ($cookie_ok) {
			$src_js = _PHP_PATH_ROOT."js/ga-template.js";
			$file_js = fopen($src_js, "r");
			if (!($file_js)) {return;}
			echo "<script type=\"text/javascript\">\n";
			while ($ligne_js = fgets($file_js)) {
				$sortie_js = str_replace("_IDENTIFIANT_GOOGLE_ANALYTICS", $code_ga, $ligne_js);
				echo $sortie_js;
			}
			fclose($file_js);
			echo "</script>\n";
		}
	}
	private function ouvrir_pp($admin) {
		echo "<div class=\"ligne_finale\"></div>\n";
		$this->ouvrir_balise_html5("footer");
		echo "<div class=\"pied_de_page pied_de_page_fixe\">\n";
	}
	private function ecrire_liens_pp($admin, $proprietaire, $mentions, $credits, $plan, $webmaster, $tab_social, $page) {
		if (!($this->reduit)) {
			$html = "<span class=\"icone_pp\">&#xf05a;</span>&nbsp;&nbsp;";
			$html .= ($admin)?$mentions:"<a href=\""._HTML_PATH_MENTIONS_LEGALES."\" rel=\"nofollow\">".$mentions."</a>";
			$html .= "&nbsp; &nbsp; <span class=\"icone_pp\">&#xf12e;</span>&nbsp;&nbsp;";
			$html .= ($admin)?$credits:"<a href=\""._HTML_PATH_CREDITS."\" rel=\"nofollow\">".$credits."</a>";
			$html .= "&nbsp; &nbsp; <span class=\"icone_pp\">&#xf0e8;</span>&nbsp;&nbsp;";
			$html .= ($admin)?$plan:"<a href=\""._HTML_PATH_PLAN_DU_SITE."\" rel=\"nofollow\" accesskey=\"0\">".$plan."</a>";
			if ($this->interne) {
				$html .= "&nbsp; &nbsp; <span class=\"icone_pp\">&#xf0cb;</span>&nbsp;&nbsp;";
				$html .= ($admin)?"Versions":"<a href=\""._HTML_PATH_VERSIONS."\">Versions</a>";
			}
			else {
				$html .= "&nbsp; &nbsp; <span class=\"icone_pp\">&#xf0c3;</span>&nbsp;&nbsp;";
				// Attribut nofollow en dehors de la page d'accueil
				$rel = (strcmp($page, "index"))?" rel=\"nofollow\"":"";
				$html .= ($admin)?$webmaster:"<a target=\"_blank\" href=\""._HTML_PATH_WEBMASTER."\"".$rel.">".$webmaster."</a>";
			}
			$classe = (count($tab_social) > 0)?"":" liens_pp_sans_social";
			echo "<p class=\"liens_pp".$classe."\">".$html."</p>\n";
		}
	}
	private function ecrire_social_pp($admin, $social, $tab_social) {
		$tab_icones = array(_SITE_SOCIAL_FACEBOOK => "082",	_SITE_SOCIAL_TWITTER => "081", _SITE_SOCIAL_GOOGLE_PLUS => "0d4", _SITE_SOCIAL_PINTEREST => "0d3", _SITE_SOCIAL_TUMBLR => "174", _SITE_SOCIAL_INSTAGRAM => "16d", _SITE_SOCIAL_LINKEDIN => "08c", _SITE_SOCIAL_YOUTUBE => "166", _SITE_SOCIAL_FLICKR => "16e");
		$tab_noms = array(_SITE_SOCIAL_FACEBOOK => "Facebook",	_SITE_SOCIAL_TWITTER => "Twitter", _SITE_SOCIAL_GOOGLE_PLUS => "Google+", _SITE_SOCIAL_PINTEREST => "Pinterest", _SITE_SOCIAL_TUMBLR => "Tumblr", _SITE_SOCIAL_INSTAGRAM => "Instagram", _SITE_SOCIAL_LINKEDIN => "LinkedIn", _SITE_SOCIAL_YOUTUBE => "YouTube", _SITE_SOCIAL_FLICKR => "Flickr");
		if (count($tab_social) > 0) {
			$html = "";
			foreach ($tab_social as $reseau => $lien) {
				$html .= "&nbsp;<span class=\"icone_pp\">";
				$html .= ($admin)?"&#xf".$tab_icones[$reseau].";":"<a target=\"_blank\" href=\"".$lien."\" title=\"".$tab_noms[$reseau]."\">&#xf".$tab_icones[$reseau].";</a>";
				$html .= "</span>&nbsp;";
			}
			echo "<p class=\"social_pp\"><span class=\"legende_social_pp\">".$social."&nbsp;:</span>".$html."</p>\n";
		}
	}
	private function ecrire_copy_admin_pp($admin, $page, $proprietaire) {
		echo "<div class=\"droite_pp droite_pp_fixe\">";
		$annee = date("Y");
		echo "<p class=\"copy_pp\">&copy;&nbsp;".$annee." - ".$proprietaire."</p>\n";
		echo "<p class=\"icone_pp admin_pp\">";
		if ($admin) {
			echo "<a href=\""._PHP_PATH_ROOT._HTTP_LOG_ADMIN."/".$page."\" title=\"Quitter la page d'administration\" rel=\"nofollow\">&#xf08b;</a>";
		}
		else {
			echo "<a href=\""._PHP_PATH_ROOT._HTTP_LOG_PREFIXE."/?"._PARAM_PAGE."=".$page."\" title=\"Accès privé\" rel=\"nofollow\">&#xf013;</a>";
		}
		echo "</p>\n";
		echo "</div>";
	}
	private function fermer_pp($admin) {
		echo "<div style=\"clear:both;\"></div>\n";
		echo "</div>\n";
		$this->fermer_balise_html5("footer");
	}
	public function ouvrir_contenu($no_cont, $nb_blocs, $style) {
		$pluriel = ($nb_blocs < 2)?"":"s";
		echo "<!-- Contenu n°".(((int) $no_cont)+1)." : ".$nb_blocs." bloc".$pluriel." -->\n";
		$classe_style = (strlen($style)>0)?" "._CSS_PREFIXE_CONTENU.$style:"";
		echo "<div id=\"contenu_".((int) $no_cont)."\" class=\"contenu".$classe_style."\">"._HTML_FIN_LIGNE;
	}
	public function fermer_contenu() {
		echo "</div>\n";
	}
	public function ouvrir_div($id, $class) {
		echo "<div id=\"".$id."\" class=\"".$class."\">";
	}
	public function fermer_div() {
		echo "</div>\n\n";
	}
	public function ecrire_signet($signet) {
		echo "<div id=\"".$signet."\"></div>"._HTML_FIN_LIGNE;
	}
	public function ouvrir_bloc(&$bloc, $taille_totale, $admin=false) {
		$taille = $bloc->get_taille();
		$position = $bloc->get_position();
		$classe = $this->extraire_classe_bloc($position);
		$style_css = $this->extraire_style_bloc($taille, $taille_totale);
		$repere = $bloc->get_repere();
		if ($admin) {
			$nb_elems_admin = $bloc->get_nb_elems_admin();
			if ((strlen($repere) > 0) && ($nb_elems_admin > 0)) {
				echo "<div id=\"".$repere."\" class=\"bloc ".$classe."\" style=\"".$style_css."\">"._HTML_FIN_LIGNE;
			}
			else {
				echo "<div class=\"bloc ".$classe."\" style=\"".$style_css."\">"._HTML_FIN_LIGNE;
			}
		}
		else {
			echo "<div id=\"".$repere."\" class=\"bloc ".$classe."\" style=\"".$style_css."\">"._HTML_FIN_LIGNE;
		}
	}
	public function ouvrir_style_bloc(&$style) {
		echo "<div class=\""._CSS_PREFIXE_EXTERIEUR.$style->get_nom()."\">"._HTML_FIN_LIGNE;
		if ($style) {
			$type = $style->get_type_bordure();
			if (!(strcmp($type, _STYLE_ATTR_TYPE_BORDURE_SCOTCH))) {
				echo "<img class=\"bloc_scotch_hg\" src=\""._PHP_PATH_ROOT."images/scotchmg.png\" alt=\"Scotch coin gauche\">"._HTML_FIN_LIGNE;
				echo "<img class=\"bloc_scotch_hd\" src=\""._PHP_PATH_ROOT."images/scotchmd.png\" alt=\"Scotch coin droit\">"._HTML_FIN_LIGNE;
			}
		}
		echo "<div class=\""._CSS_PREFIXE_INTERIEUR.$style->get_nom()."\">"._HTML_FIN_LIGNE;
	}
	public function fermer_style_bloc(&$style) {
		echo "</div></div>"._HTML_FIN_LIGNE;
	}
	public function fermer_bloc(&$bloc) {
		echo "</div>"._HTML_FIN_LIGNE;
	}
	public function ouvrir_tab($no_cont, $no_bloc, $fragment) {
		$id_tab = "tab_".((int) $no_cont)."_".((int) $no_bloc);
		if ((strlen($fragment) > 0) && (!(strcmp($fragment, $id_tab)))) {$classe = "tab_edit_actif";}
		else {$classe = "tab_edit_inactif";}
		echo "<div id=\"".$id_tab."\" name=\"".$id_tab."\" class=\"tab_edit ".$classe."\">";
		return $id_tab;
	}
	public function fermer_tab() {
		echo "</div>"._HTML_FIN_LIGNE;
	}
	public function charger_police($police) {
		if (strlen($police) > 0) {
			echo "<link href=\"http://fonts.googleapis.com/css?family=".strtr($police, " ", "+")."\" rel=\"stylesheet\" type=\"text/css\" />\n";
		}
	}
	public function charger_css($fichier) {
		$nom_fichier = basename($fichier);
		$nom_dossier = dirname($fichier);
		echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\""._PHP_PATH_ROOT.$nom_dossier."/".$nom_fichier."\" />\n";
		echo "<!--[if lte IE 7]>\n";
		echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\""._PHP_PATH_ROOT.$nom_dossier."/ie7_".$nom_fichier."\" />\n";
		echo "<![endif]-->\n";
		echo "<!--[if IE 8]>\n";
		echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\""._PHP_PATH_ROOT.$nom_dossier."/ie8_".$nom_fichier."\" />\n";
		echo "<![endif]-->\n";
	}
	public function charger_xml_css() {
		$fichier_css = _XML_PATH_CSS."style.css";
		if (file_exists($fichier_css)) {
			echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"".$fichier_css."\" />\n";
		}
	}
	public function ecrire_css_ie($css) {
		if (strlen($css) > 0) {
			echo "<!--[if lte IE 8]>\n";
			echo "<style type=\"text/css\" media=\"all\">\n".$css."\n</style>\n";
			echo "<![endif]-->\n";
		}
	}
	public function ecrire_css($css) {
		if (strlen($css) > 0) {
			echo "<style type=\"text/css\" media=\"screen\">\n".$css."\n</style>\n";
		}
	}
	public function charger_js($fichier) {
		echo "<script type=\"text/javascript\" src=\""._PHP_PATH_ROOT.$fichier."\"></script>\n";
	}
	public function charger_xml_js() {
		$fichier_js = _XML_PATH_JS."script.js";
		if (file_exists($fichier_js)) {
			echo "<script type=\"text/javascript\" src=\"".$fichier_js."\"></script>\n";
		}
	}
	public function ecrire_js($script) {
		echo "<script type=\"text/javascript\">".$script."</script>\n";
	}
	public function ecrire_meta_titre($titre) {
		echo "<title>".$titre."</title>\n";
	}
	public function ecrire_meta_descr($descr) {
		echo "<meta name=\"description\" content=\"".$descr."\" />\n";
	}
	public function ecrire_meta_noindex() {
		echo "<meta name=\"robots\" content=\"noindex\" />\n";
	}
	private function extraire_style_bloc($taille, $taille_totale) {
		$style = "";
		// La marge d'erreur peut servir à cause du white-space dans les inline-blocks
		$marge_erreur = (float) 0;
		if ($taille_totale > 0) {
			$pourmille = (float) 1000*((float) ($taille / $taille_totale));
			$pm_arrondi = floor($pourmille);
			$style = "width:".(((float) ($pm_arrondi/10))-$marge_erreur)."%;";
		}
		return $style;
	}
	private function extraire_classe_bloc($position) {
		if (!(strcmp($position, _PAGE_ATTR_ALIGNEMENT_HAUT))) {$classe = "bloc_h";}
		elseif (!(strcmp($position, _PAGE_ATTR_ALIGNEMENT_BAS))) {$classe = "bloc_b";}
		else {$classe = "bloc_m";}
		
		return $classe;
	}
}