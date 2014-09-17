<?php
inclure_inc("const", "param", "carte", "video");
inclure_site("xml_media");
	
class html {
	// Propriétés privées
	private $mobile = false;
	// Types de pied de page
	private $interne = false;
	private $reduit = false;

	// Méthodes publiques
	public function __construct($mobile=false, $interne=false, $reduit=false) {
		$this->mobile = (int) $mobile;
		$this->interne = (int) $interne;
		$this->reduit = (int) $reduit;
	}
	public function ouvrir($langue="fr") {
		// echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
		echo "<!doctype html>\n";
		// echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:og=\"http://ogp.me/ns#\" xml:lang=\"".$langue."\" lang=\"".$langue."\" dir=\"ltr\">\n";
		echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"".$langue."\" lang=\"".$langue."\" dir=\"ltr\">\n";
	}
	public function fermer() {
		echo "</html>";
	}
	public function ouvrir_head($root="") {
		echo "<head>\n";
		echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=UTF-8\" />\n";
		echo "<meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />\n";
		if (strlen($root)>0) {
			echo "<link rel=\"canonical\" href=\"http://".$root."\" />\n";
		}
		// Version mobile : meta no_index pour les moteurs de recherche
		if ($this->mobile) {$this->ecrire_meta_noindex();}
		// Version courante de PetiLabo
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
		if (!($this->mobile)) {
			if (strlen($image) > 0) {
				echo "<!--[if lte IE 8]>\n";
				echo "<img class=\"papierpeint\" src=\""._XML_PATH_IMAGES_SITE.$image."\" alt=\"Papier peint\" />\n";
				echo "<![endif]-->\n";
			}
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
		if ($this->mobile) {
			$style .= "width:90%;";
		}
		else {
			if (strlen($largeur) > 0) {
				$style .=  "width:".$largeur.";";
			}
			if (strlen($largeur_max) > 0) {
				$style .=  "max-width:".$largeur_max.";";
			}
			if (strlen($largeur_min) > 0) {
				$style .=  "min-width:".$largeur_min.";";
			}
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
		if ($this->mobile) {
			$this->ouvrir_balise_html5("nav");
			$this->ecrire_liens_pp($admin, $proprietaire, $mentions, $credits, $plan, $webmaster, $tab_social, $page);
			$this->ecrire_social_pp($admin, $social, $tab_social);
			$this->fermer_balise_html5("nav");
			$this->ecrire_copy_admin_pp($admin, $page, $proprietaire);
			$this->ecrire_switch_pp($admin, $page);
		}
		else {
			$this->ecrire_switch_pp($admin, $page);
			$this->ecrire_copy_admin_pp($admin, $page, $proprietaire);
			$this->ouvrir_balise_html5("nav");
			$this->ecrire_liens_pp($admin, $proprietaire, $mentions, $credits, $plan, $webmaster, $tab_social, $page);
			$this->ecrire_social_pp($admin, $social, $tab_social);
			$this->fermer_balise_html5("nav");
		}
		$this->fermer_pp($admin);
		echo "</div>\n";
	}
	private function ouvrir_pp($admin) {
		echo "<div class=\"ligne_finale\"></div>\n";
		$classe = ($this->mobile)?"":" pied_de_page_fixe";
		$this->ouvrir_balise_html5("footer");
		echo "<div class=\"pied_de_page".$classe."\">\n";
	}
	private function ecrire_switch_pp($admin, $page) {
		$classe = ($this->mobile)?"":" gauche_pp_fixe";
		echo "<div class=\"gauche_pp".$classe."\">";
		if ($admin) {
			echo "<p class=\"icone_pp switch_pp\">&#xf10b;</p>";
		}
		else {
			if ($this->mobile) {
				echo "<p class=\"icone_pp switch_pp\"><a href=\"../".$page._PXP_EXT."\" title=\"Standard\">&#xf109;</a></p>";
			}
			else {
				echo "<p class=\"icone_pp switch_pp\"><a href=\"./mobile/".$page._PXP_EXT."\" title=\"Mobile\">&#xf10b;</a></p>";
			}
		}
		echo "</div>";
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
		$classe = ($this->mobile)?"":" droite_pp_fixe";
		echo "<div class=\"droite_pp".$classe."\">";
		$annee = date("Y");
		echo "<p class=\"copy_pp\">&copy;&nbsp;".$annee." - ".$proprietaire."</p>\n";
		// L'administration est désactivée dans la version mobile
		if (!($this->mobile)) {
			echo "<p class=\"icone_pp admin_pp\">";
			if ($admin) {
				echo "<a href=\""._PHP_PATH_ROOT._HTTP_LOG_ADMIN."/".$page."\" title=\"Quitter la page d'administration\" rel=\"nofollow\">&#xf08b;</a>";
			}
			else {
				echo "<a href=\""._PHP_PATH_ROOT._HTTP_LOG_PREFIXE."/?"._PARAM_PAGE."=".$page."\" title=\"Accès privé\" rel=\"nofollow\">&#xf013;</a>";
			}
			echo "</p>\n";
		}
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
		if ($this->mobile) {
			echo "<div class=\"bloc_mobile\">"._HTML_FIN_LIGNE;
		}
		else {
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
	}
	public function ouvrir_style_bloc(&$style) {
		echo "<div class=\""._CSS_PREFIXE_EXTERIEUR.$style->get_nom()."\">"._HTML_FIN_LIGNE;
		if ($style) {
			$type = $style->get_type_bordure();
			switch ($type) {
				case _STYLE_ATTR_TYPE_BORDURE_SCOTCH :
					// Pour le moment on insère d'office en haut à gauche et à droite
					echo "<img class=\"bloc_scotch_hg\" src=\""._PHP_PATH_ROOT."images/scotchmg.png\" alt=\"Scotch coin gauche\">"._HTML_FIN_LIGNE;
					echo "<img class=\"bloc_scotch_hd\" src=\""._PHP_PATH_ROOT."images/scotchmd.png\" alt=\"Scotch coin droit\">"._HTML_FIN_LIGNE;
					break;
				case _STYLE_ATTR_TYPE_BORDURE_BANDEAU :
					$couleur = $style->get_bordure();
					if (strlen($couleur) == 0) {
						$couleur = "#333";
					}
					$marge_gauche = (int) $style->get_marge_gauche();
					echo "<div class=\"bloc_bandeau\" style=\"background:".$couleur.";\">"._HTML_FIN_LIGNE;
					$this->ecrire_titre(3, $style->get_style_titre_bandeau(), $style->get_titre_bandeau());
					echo "</div>"._HTML_FIN_LIGNE;
					echo "<div class=\"bloc_bandeau_gauche\" style=\"width:".$marge_gauche."px;height:".((int) (4+$marge_gauche))."px;\"></div>"._HTML_FIN_LIGNE;
					echo "<div class=\"bloc_bandeau_droite\" style=\"width:".$marge_gauche."px;height:".((int) (4+$marge_gauche))."px;\"></div>"._HTML_FIN_LIGNE;
					break;
				default :
					break;
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
	public function charger_police($police) {
		if (strlen($police) > 0) {
			echo "<link href=\"http://fonts.googleapis.com/css?family=".strtr($police, " ", "+")."\" rel=\"stylesheet\" type=\"text/css\" />\n";
		}
	}
	public function charger_css($fichier) {
		$nom_fichier = basename($fichier);
		$nom_dossier = dirname($fichier);
		echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\""._PHP_PATH_ROOT.$nom_dossier."/".$nom_fichier."\" />\n";
		// Si non mobile on charge le CSS spécifique IE7/IE8
		if (!($this->mobile)) {
			echo "<!--[if lte IE 7]>\n";
			echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\""._PHP_PATH_ROOT.$nom_dossier."/ie7_".$nom_fichier."\" />\n";
			echo "<![endif]-->\n";
			echo "<!--[if IE 8]>\n";
			echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\""._PHP_PATH_ROOT.$nom_dossier."/ie8_".$nom_fichier."\" />\n";
			echo "<![endif]-->\n";
		}
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
	public function ecrire_meta_titre($titre) {
		echo "<title>".$titre."</title>\n";
	}
	public function ecrire_meta_descr($descr) {
		echo "<meta name=\"description\" content=\"".$descr."\" />\n";
	}
	public function ecrire_meta_noindex() {
		echo "<meta name=\"robots\" content=\"noindex\" />\n";
	}
	public function ecrire_titre($niveau, $style_titre, $texte) {
		// Normalisation du niveau de titre
		$niv_titre = (int) $niveau;
		if (strlen($style_titre) > 0) {
			$style_titre = _CSS_PREFIXE_TEXTE.$style_titre;
		}
		// Affichage du titre
		// EXPERIMENTAL 1.1.5 : Affichage du paragraphe uniquement s'il n'est pas vide
		if (strlen($texte) > 0) {echo "<h".$niv_titre." class=\"titre ".$style_titre."\">".$texte."</h".$niveau.">"._HTML_FIN_LIGNE;}
	}
	public function ecrire_paragraphe($admin, $style_p, $texte, $lien_telephonique=null, $trad_tel=null) {
		if (strlen($style_p) > 0) {$style_p = _CSS_PREFIXE_TEXTE.$style_p;}
		$classe = "paragraphe ".$style_p;
		if (strlen($lien_telephonique) > 0) {
			$classe .= " paragraphe_tel";
			$device = ($this->mobile)?"tel:":"callto:";
			$title = ($this->mobile)?$trad_tel." ".$lien_telephonique:$trad_tel;
			$texte ="<a class=\"lien_tel\" href=\"".$device.$lien_telephonique."\" title=\"".$title."\">".$texte."</a>";
		}
		if (strlen($texte) > 0) {echo "<p class=\"".$classe."\">".$texte."</p>"._HTML_FIN_LIGNE;}
		else {if ($admin) {echo "<p class=\"".$classe."\"></p>"._HTML_FIN_LIGNE;} }
	}
	public function ecrire_titre_legal($style_p, $texte) {
		if (strlen($style_p) > 0) {$style_p = _CSS_PREFIXE_TEXTE.$style_p;}
		echo "<p class=\"titre_legal ".$style_p."\"><span class=\"titre_legal\">".$texte."</span></p>"._HTML_FIN_LIGNE;
	}
	public function ecrire_saut($hauteur) {
		echo "<p style=\"font-size:".$hauteur."em;line-height:".$hauteur."em;\"><br /></p>"._HTML_FIN_LIGNE;
	}
	public function ecrire_image_sans_legende(&$image, $alt, $lien = null, $access_key = null) {
		if ($image) {
			$style="max-width:".$image->get_width()."px;max-height:".$image->get_height()."px";
			echo "<div>"._HTML_FIN_LIGNE;
			if (strlen($lien) > 0) {
				$attr_access = (strlen($access_key)>0)?" accesskey=\"".$access_key."\"":"";
				$attr_target = $this->url_target($lien);
				echo "<a".$attr_target." href=\"".$lien."\"".$attr_access.">";
			}
			echo "<img class=\"image_cadre\" style=\"".$style."\" src=\"".$image->get_src()."\" alt=\"".$alt."\" />";
			if (strlen($lien) > 0) {echo "</a>";}
			echo _HTML_FIN_LIGNE."</div>"._HTML_FIN_LIGNE;
		}
	}
	public function ecrire_image_avec_legende(&$image, $alt, $legende, $niveau_legende, $style_legende, $est_exterieur, $lien = null, $access_key = null) {
		if ($image) {
			$style="max-width:".$image->get_width()."px;max-height:".$image->get_height()."px";
			if ($est_exterieur) {
				// Le style extérieur ne doit pas posséder la classe dédiée au survol
				$style_exterieur = str_replace(_CSS_CLASSE_SURVOL, "", $style_legende);
				echo "<div class=\"image_cadre\">"._HTML_FIN_LIGNE;
				echo "<div class=\""._CSS_PREFIXE_EXTERIEUR.$style_exterieur."\" style=\"".$style."\">"._HTML_FIN_LIGNE;
				if (strlen($lien)>0) {
					$attr_target = $this->url_target($lien);
					echo "<a class=\"legende_avec_lien\"".$attr_target." href=\"".$lien."\">";
				}
				echo "<img class=\"image_dans_cadre\" src=\"".$image->get_src()."\" alt=\"".$alt."\" />"._HTML_FIN_LIGNE;
				if (strlen($lien)>0) {echo "</a>";}
				$this->ecrire_legende($legende, $niveau_legende, $style_legende, $lien, $access_key);
				echo "</div>"._HTML_FIN_LIGNE;
			}
			else {
				echo "<div class=\"image_cadre\" style=\"".$style."\">"._HTML_FIN_LIGNE;
				if (strlen($lien)>0) {
					$attr_target = $this->url_target($lien);
					echo "<a class=\"legende_avec_lien\"".$attr_target." href=\"".$lien."\">";
				}
				echo "<img class=\"image_dans_cadre\" src=\"".$image->get_src()."\" alt=\"".$alt."\" />"._HTML_FIN_LIGNE;
				if (strlen($lien)>0) {echo "</a>";}
				$this->ecrire_legende($legende, $niveau_legende, $style_legende, $lien, $access_key);
			}
			echo "</div>"._HTML_FIN_LIGNE;
		}
	}
	public function ouvrir_drapeaux($alignement) {
		$classe = "wrap_drapeau";
		$classe .= " ".$this->extraire_classe_alignement($alignement);
		echo "<div class=\"".$classe."\">"._HTML_FIN_LIGNE;
	}
	public function ajouter_drapeau($langue, $href, $titre, $position) {
		if (strlen($href) > 0) {
			echo "<a class=\"drapeau\" href=\"".$href."\" title=\"".$titre."\" style=\"background-position:".$position."\">".$langue."</a>"._HTML_FIN_LIGNE;
		}
		else {
			echo "<p class=\"drapeau\" style=\"background-position:".$position."\">&nbsp;</p>"._HTML_FIN_LIGNE;
		}
	}
	public function fermer_drapeaux() {
		echo "</div>"._HTML_FIN_LIGNE;
	}
	public function ouvrir_diaporama($id_gal, $maxwidth=0) {
		$style = ($maxwidth > 0)?" style=\"max-width:".$maxwidth."px;\"":"";
		echo "<div class=\"diaporama\"><ul id=\"".$id_gal."\" class=\"rslides\"".$style.">"._HTML_FIN_LIGNE;
	}
	public function ajouter_diaporama_sans_legende(&$image, $alt) {
		if ($image) {
			echo "<li>"._HTML_FIN_LIGNE;
			echo "<img src=\"".$image->get_src()."\" alt=\"".$alt."\" />"._HTML_FIN_LIGNE;
			echo "</li>"._HTML_FIN_LIGNE;
		}
	}
	public function ajouter_diaporama_avec_legende(&$image, $alt, $legende, $style_legende, $est_exterieur, $lien = null, $access_key = null) {
		if ($image) {
			echo "<li>"._HTML_FIN_LIGNE;
			if ($est_exterieur) {
				echo "<div class=\""._CSS_PREFIXE_EXTERIEUR.$style_legende."\">"._HTML_FIN_LIGNE;
				echo "<img src=\"".$image->get_src()."\" alt=\"".$alt."\" />"._HTML_FIN_LIGNE;
				$this->ecrire_legende($legende, 0, $style_legende, $lien, $access_key);
				echo "</div>"._HTML_FIN_LIGNE;
			}
			else {
				echo "<img src=\"".$image->get_src()."\" alt=\"".$alt."\" />"._HTML_FIN_LIGNE;
				$this->ecrire_legende($legende, 0, $style_legende, $lien, $access_key);
			}
			echo "</li>"._HTML_FIN_LIGNE;
		}
	}
	public function fermer_diaporama($id_gal, $has_navigation, $has_boutons, $largeur_max) {
		echo "</ul></div>"._HTML_FIN_LIGNE;
		// Si l'id de la galerie est nul on ne produit pas le javascript
		if (strlen($id_gal) > 0) {
			$param = "{namespace:'boutons_diapo'";
			// PATCH POUR ASTRID
			$param .= ",timeout:5000,pause:true";
			$param .= ",pager:".(($has_boutons)?"true":"false");
			$param .= ",nav:".(($has_navigation)?"true":"false");
			$param .= ($largeur_max > 0)?",maxwidth:'".$largeur_max."px'}":"}";
			echo "<script type=\"text/javascript\">"._HTML_FIN_LIGNE;
			echo "$(function() {"._HTML_FIN_LIGNE;
			echo "$(\"#".$id_gal."\").responsiveSlides(".$param.");"._HTML_FIN_LIGNE;
			echo "});"._HTML_FIN_LIGNE;
			echo "</script>"._HTML_FIN_LIGNE;
		}
	}
	public function ouvrir_vignettes($id_gal) {
		echo "<div class=\"lb_".$id_gal."\" style=\"text-align:center;\">"._HTML_FIN_LIGNE;
	}
	public function ajouter_vignette($image, $src, $lien, $info, $nb_cols) {
		$width = floor(100/((int) $nb_cols));
		$width -= 2;
		if (strlen($lien) > 0) {
			echo "<a href=\"".$lien."\" title=\"".$info."\">"._HTML_FIN_LIGNE;
		}
		echo "<img src=\"".$src."\" alt=\"".$info."\" style=\"display:inline;width:".$width."%;margin:0 1%;\" />"._HTML_FIN_LIGNE;
		if (strlen($lien) > 0) {
			echo "</a>"._HTML_FIN_LIGNE;
		}
	}
	public function fermer_vignettes($id_gal, $label_prec, $label_suiv, $label_fermer) {
		echo "</div>"._HTML_FIN_LIGNE;
		// Si l'id de la galerie est nul on ne produit pas le javascript
		if (strlen($id_gal) > 0) {
			echo "<script type=\"text/javascript\">"._HTML_FIN_LIGNE;
			echo "$(document).ready(function() {"._HTML_FIN_LIGNE;
			echo "$('.lb_".$id_gal."').magnificPopup({delegate:'a',type:'image',closeOnContentClick:'true',tClose:'".$label_fermer."',gallery:{enabled:'true',tPrev:'".$label_prec."',tNext:'".$label_suiv."'}});"._HTML_FIN_LIGNE;
			echo "});"._HTML_FIN_LIGNE;
			echo "</script>"._HTML_FIN_LIGNE;
		}
	}
	public function ouvrir_vue_galerie($id_gal, &$image_init, $vertical) {
		$classe = ($vertical)?"vue_galerie_verticale":"vue_galerie_horizontale";
		if ($image_init) {$style="style=\"max-width:".$image_init->get_width()."px;max-height:".$image_init->get_height()."px;\"";}
		else {$style="";}
		echo "<div id=\"gal_".$id_gal."\" class=\"".$classe."\" ".$style.">"._HTML_FIN_LIGNE;
		if ($image_init) {echo "<img class=\"image_galerie\" src=\"".$image_init->get_src()."\" alt=\"Galerie ".$id_gal."\" />";}
	}
	public function ajouter_legende_galerie($nom_gal, $legende, $nom_style, $index) {
		$style_display = ($index == 0)?"visible":"hidden";
		echo "<div id=\"leg_".$nom_gal."_".$index."\" class=\"legende_galerie "._CSS_PREFIXE_INTERIEUR.$nom_style." transparence_80pc\" style=\"visibility:".$style_display.";\" >"._HTML_FIN_LIGNE;
		echo "<table class=\"tableau_legende\"><tr><td>";
		echo "<p>".$legende."</p>"._HTML_FIN_LIGNE;
		echo "</td></tr></table>"._HTML_FIN_LIGNE;
		echo "</div>"._HTML_FIN_LIGNE;
	}
	public function fermer_vue_galerie($id_gal) {
		echo "</div>"._HTML_FIN_LIGNE;
	}
	public function ouvrir_onglet_galerie($id_gal, $vertical) {
		$classe = ($vertical)?"onglet_galerie_verticale":"onglet_galerie_horizontale";
		echo "<div id=\"onglet_".$id_gal."\" class=\"".$classe."\">"._HTML_FIN_LIGNE;
	}
	public function ajouter_onglet_galerie($nom_gal, &$image, $id_alt, $index, $nb_cols) {
		$width = floor(100/((int) $nb_cols));
		$width -= 2;
		echo "<img id=\"min_".$nom_gal."_".$index."\" class=\"miniature_galerie transparence_80pc\" src=\"".$image->get_src_reduite()."\" alt=\"".$id_alt."\"  style=\"width:".$width."%;\" />"._HTML_FIN_LIGNE;
	}
	public function fermer_onglet_galerie($id_gal) {
		echo "</div>"._HTML_FIN_LIGNE;
	}
	public function fermer_galerie($nom_gal, $vertical) {
		if ($vertical) {
			echo "<div style=\"clear:both;\"></div>"._HTML_FIN_LIGNE;
		}
	}
	public function ouvrir_menu($alignement) {
		$classe = "wrap_menu";
		$classe .= " ".$this->extraire_classe_alignement($alignement);
		$this->ouvrir_balise_html5("nav");
		echo "<div class=\"".$classe."\">"._HTML_FIN_LIGNE;
	}
	public function ajouter_menu($lien_actif, $classe, $texte, $lien=null, $access_key=null, $info=null) {
		if ($lien_actif) {
			echo "<a class=\"item_menu ".$classe._MENU_STYLE_EXT_ACTIF."\">".$texte."</a>"._HTML_FIN_LIGNE;
		}
		else {
			if (strlen($lien) > 0) {
				$attr_access = (strlen($access_key)>0)?" accesskey=\"".$access_key."\"":"";
				$attr_target = $this->url_target($lien);
				echo "<a href=\"".$lien."\" class=\"item_menu ".$classe."\" title=\"".$info."\"".$attr_access."".$attr_target.">".$texte."</a>"._HTML_FIN_LIGNE;
			}
			else {
				echo "<a class=\"item_menu ".$classe."\">".$texte."</a>"._HTML_FIN_LIGNE;
			}
		}
	}
	public function fermer_menu() {
		echo "</div>"._HTML_FIN_LIGNE;
		$this->fermer_balise_html5("nav");
	}
	public function ecrire_plan($code, $trad_code, $langue, $actif = true) {
		$carte = new carte($code, $trad_code, $langue);
		$lien_carte = $carte->get_ref_carte();
		// Si admin on prend directement la carte de l'API Google Maps
		$src_carte = ($actif)?$carte->get_src_carte():$carte->get_src_carte_distante();
		// Si admin on supprime la carte locale pour provoquer la copie de la nouvelle carte
		if (!($actif)) {$carte->reinit();}
		$debut_lien = ($actif)?"<a href=\"".$lien_carte."\" title=\"Google Maps\" target=\"_blank\">":"";
		$fin_lien = ($actif)?"</a>":"";
		echo "<div>".$debut_lien;
		echo "<img class=\"image_cadre image_plan\" src=\"".$src_carte."\" alt=\"".$code."\" />";
		echo $fin_lien."</div>"._HTML_FIN_LIGNE;
	}
	public function ecrire_video($source, $code, $actif = true) {
		$video = new video($source, $code);
		if ($actif) {
			$html = $video->get_iframe();
			if (strlen($html) > 0) {
				echo "<div class=\"wrap_video\"><div class=\"cadre_video\">"._HTML_FIN_LIGNE;
				echo $html._HTML_FIN_LIGNE;
				echo "</div></div>"._HTML_FIN_LIGNE;
			}
		}
		else {
			$src = $video->get_src();
			if (strlen($src) > 0) {
				echo "<div class=\"wrap_video\"><img class=\"image_cadre\" src=\"".$src."\" alt=\"".$source."\" /></div>"._HTML_FIN_LIGNE;
			}
			else {
				echo "<p class=\"paragraphe\">".$code."</p>"._HTML_FIN_LIGNE;
			}
		}
	}
	public function ecrire_pj($id_pj, $lien, $style, $fichier, $info, $legende) {
		$classe = "paragraphe";
		if (strlen($style) > 0) {
			$classe .= " "._CSS_PREFIXE_TEXTE.$style;
		}
		switch ($lien) {
			case _PAGE_ATTR_LIEN_IMAGE :
				$base = basename($fichier);
				if (strlen($base) > 0) {
					$extension = $this->extraire_extension($base);
					echo "<div class=\"file\">"._HTML_FIN_LIGNE;
					if (strlen($id_pj) > 0) {
						echo "<p class=\"filetype\"><a href=\"".$fichier."\" title=\"".$info."\" target=\"_blank\">".$extension."</a></p>"._HTML_FIN_LIGNE;
					}
					else {
						echo "<p class=\"filetype\">".$extension."</p>"._HTML_FIN_LIGNE;
					}
					echo "</div>"._HTML_FIN_LIGNE;
					if (strlen($id_pj) > 0) {
						echo "<p class=\"".$classe."\"><a href=\"".$fichier."\" title=\"".$info."\" target=\"_blank\">".$legende."</a></p>"._HTML_FIN_LIGNE;
					}
					else {
						echo "<p class=\"".$classe."\"><span style=\"text-decoration:underline;\">".$legende."</span></p>"._HTML_FIN_LIGNE;
					}
				}
				break;
			case _PAGE_ATTR_LIEN_FICHIER :
				$base = basename($fichier);
				if (strlen($base) > 0) {
					if (strlen($id_pj) > 0) {
						echo "<p class=\"lien_pj_fichier ".$classe."\">".$legende."&nbsp;: <a href=\"".$fichier."\" title=\"".$info."\" target=\"_blank\">".$base."</a></p>"._HTML_FIN_LIGNE;
					}
					else {
						echo "<p class=\"lien_pj_fichier ".$classe."\">".$legende."&nbsp;: <span style=\"text-decoration:underline;\">".$base."</span></p>"._HTML_FIN_LIGNE;
					}
				}
				break;
			default :
				$base = basename($fichier);
				if (strlen($base) > 0) {
					if (strlen($id_pj) > 0) {
						echo "<p class=\"lien_pj_legende ".$classe."\"><a href=\"".$fichier."\" title=\"".$info."\" target=\"_blank\">".$legende."</a></p>"._HTML_FIN_LIGNE;
					}
					else {
						echo "<p class=\"lien_pj_legende ".$classe."\"><span style=\"text-decoration:underline;\">".$legende."</span></p>"._HTML_FIN_LIGNE;
					}
				}
				break;
		}
	}
	public function ecrire_form_contact($style_p, $style, $nom, $prenom, $tel, $email, $message, $captcha, $envoyer, $actif = true) {
		if (strlen($style_p) > 0) {$style_p = " "._CSS_PREFIXE_TEXTE.$style_p;}
		echo "<div class=\"formulaire_cadre\">"._HTML_FIN_LIGNE;
		echo "<form id=\"id_form_contact\" method=\"post\" action=\""._PHP_PATH_INCLUDE."mail.php\">"._HTML_FIN_LIGNE;
		// Champs du formulaire
		$this->ecrire_contact_texte($style_p, $style, true, $nom, "nom", false, false, $actif);
		$this->ecrire_contact_texte($style_p, $style, true, $prenom, "prenom", false, false, $actif);
		$this->ecrire_contact_texte($style_p, $style, false, $tel, "tel", true, false, $actif);
		$this->ecrire_contact_texte($style_p, $style, true, $email, "email", false, true, $actif);
		$this->ecrire_contact_message($style_p, $style, true, $message, "message", $actif);
		// Captcha
		echo "<p class=\"paragraphe".$style_p."\">"._HTML_FIN_LIGNE;
		echo "<label class=\"champ_label\" for=\"id_captcha\">".$captcha."<span class=\"champ_obligatoire\">&nbsp;(*)</span></label>"._HTML_FIN_LIGNE;
		$classe = "champ_tres_court";
		$disabled = ($actif)?"":" disabled=\"disabled\"";
		if (strlen($style) > 0) {
			$classe .= " "._CSS_PREFIXE_FORMULAIRE_CHAMP.$style;
		}
		echo "<span id=\"q_captcha\">&nbsp;</span><input class=\"champ_saisie ".$classe."\" type=\"text\" id=\"id_captcha\" name=\"captcha\" size=\"5\"".$disabled."/>"._HTML_FIN_LIGNE;
		echo "</p>"._HTML_FIN_LIGNE;
		echo "<p class=\"champ_erreur\" id=\"err_captcha\">&nbsp;</p>"._HTML_FIN_LIGNE;
		echo "<p class=\"paragraphe\">"._HTML_FIN_LIGNE;
		echo "<input id=\"id_action\" type=\"hidden\" name=\"action\" value=\"send\" />"._HTML_FIN_LIGNE;
		echo "<input type=\"submit\" name=\"send\" class=\"bouton_envoyer\" value=\"".$envoyer."\"".$disabled."/>"._HTML_FIN_LIGNE;
		echo "</p>"._HTML_FIN_LIGNE;
		echo "<div class=\"formulaire_separation\"></div>"._HTML_FIN_LIGNE;
		echo "<p id=\"status_msg\" class=\"paragraphe\" style=\"text-align:center;\">&nbsp;</p>"._HTML_FIN_LIGNE;
		echo "</form>"._HTML_FIN_LIGNE;
		echo "</div>"._HTML_FIN_LIGNE;
	}
	public function ecrire_plan_du_site($niveau, $style, $nom, $ref, $touche) {
		if (strlen($nom) > 0) {
			$niveau = ($niveau>5)?6:($niveau+1);
			$classe = "paragraphe";
			$span_lien = (strlen($style) > 0)?" class=\""._CSS_PREFIXE_TEXTE.$style."\"":"";
			$class_touche = " class=\"plan_touche\"";
			$access_key = (strlen($touche)>0)?" accesskey=\"".$touche."\"":"";
			echo "<div class=\"plan_du_site "._CSS_PREFIXE_PLAN_NIVEAU.$niveau."\">";
			$ref_touche = (strlen($ref) > 0)?"<a href=\"".$ref."\" title=\"".$nom."\"".$access_key.">".$touche."</a>":$touche;
			$html_touche = (strlen($touche) > 0)?$ref_touche:"&nbsp;";
			echo "<p ".$class_touche.">".$html_touche."</p>";
			$ref_lien = (strlen($ref) > 0)?"<a ".$span_lien." href=\"".$ref."\" title=\"".$nom."\"".$access_key.">".$nom."</a>":$nom;
			$html_lien = (strlen($style) > 0)?"<span class=\""._CSS_PREFIXE_TEXTE.$style."\">".$ref_lien."</span>":$ref_lien;
			echo "<p class=\"plan_titre ".$classe."\">".$html_lien."</p></div>"._HTML_FIN_LIGNE;
		}
	}
	public function fermer_plan_du_site($legende) {
		echo "<div class=\"plan_legende "._CSS_PREFIXE_PLAN_NIVEAU."1\">";
		echo "<p class=\"plan_fleche\">&#xf062;</p></div>"._HTML_FIN_LIGNE;
		echo "<div class=\"plan_legende "._CSS_PREFIXE_PLAN_NIVEAU."1\">";
		echo "<p class=\"plan_fleche_legende\">".$legende."</p><br /></div>"._HTML_FIN_LIGNE;
	}
	public function ecrire_credit_technique($titre, $style, $lien, $id_credit, $visite) {
		$classe_lien  = (strlen($style) > 0)?_CSS_PREFIXE_TEXTE.$style:"";
		echo "<div class=\"credit_cadre_technique\">"._HTML_FIN_LIGNE;
		echo "<img src=\""._PHP_PATH_ROOT."images/".$id_credit.".jpg\" />"._HTML_FIN_LIGNE;
		$html_lien = (strlen($lien) > 0)?"<a href=\"".$lien."\" title=\"".$titre."\" target=\"_blank\">".$visite." ".$titre."</a>":$titre;
		echo "<p class=\"paragraphe credit_lien ".$classe_lien."\" style=\"text-align:center;\">".$html_lien."</p>"._HTML_FIN_LIGNE;
		echo "</div>"._HTML_FIN_LIGNE;
	}
	public function ecrire_credit_photo($src, $copyright, $largeur, $hauteur, $taille = 0) {
		if ($taille == 0) {$taille = 185;} elseif ($taille < 50) {$taille = 50;}
		// echo "<div class=\"credit_cadre_photo\" style=\"width:".$taille."px;height:".(((int) $taille)+30)."px;\">"._HTML_FIN_LIGNE;
		echo "<div class=\"credit_cadre_photo\" style=\"width:".$taille."px;\">"._HTML_FIN_LIGNE;
		echo "<div class=\"credit_cadre_img\" style=\"width:".$taille."px;height:".$taille."px;\">"._HTML_FIN_LIGNE;
		if ($largeur > $hauteur) {
			$nouvelle_largeur = ($largeur * $taille)/$hauteur;
			$position = (int) (-(($nouvelle_largeur - $taille)/2));
			echo "<img src=\"".$src."\" style=\"height:".$taille."px;left:".$position."px;\" />"._HTML_FIN_LIGNE;
		}
		else {
			$nouvelle_hauteur = ($hauteur * $taille)/$largeur;
			$position = (int) (-(($nouvelle_hauteur - $taille)/2));
			echo "<img src=\"".$src."\" style=\"width:".$taille."px;top:".$position."px;\" />"._HTML_FIN_LIGNE;
		}
		echo "</div>"._HTML_FIN_LIGNE;
		echo "<p class=\"paragraphe credit_copyright\">&copy;&nbsp;".$copyright."</p>"._HTML_FIN_LIGNE;
		echo "</div>"._HTML_FIN_LIGNE;
	}
	public function fermer_credit_section() {
		echo "<div style=\"clear:both;\"></div>"._HTML_FIN_LIGNE;
	}
	public function ecrire_legal_site_editeur($style, $site, $url, $edite) {
		$classe  = (strlen($style) > 0)?_CSS_PREFIXE_TEXTE.$style:"";
		echo "<br />"._HTML_FIN_LIGNE;
		if (strlen($url) > 0) {
			echo "<p class=\"paragraphe credit_texte ".$classe."\">".$site." <strong>".$url."</strong> ".$edite."&nbsp;:</p><br />"._HTML_FIN_LIGNE;
		}
	}
	public function ecrire_legal_coord_editeur($style, $nom, $adr, $tel, $rcs, $siret) {
		$classe  = (strlen($style) > 0)?_CSS_PREFIXE_TEXTE.$style:"";
		if (strlen($nom) > 0) {
			echo "<p class=\"paragraphe credit_texte ".$classe."\"><strong>".$nom."</strong></p>"._HTML_FIN_LIGNE;
		}
		if (strlen($adr) > 0) {
			echo "<p class=\"paragraphe credit_texte ".$classe."\">".$adr."</p>"._HTML_FIN_LIGNE;
		}
		if (strlen($tel) > 0) {
			echo "<p class=\"paragraphe credit_texte ".$classe."\">Tel&nbsp;:&nbsp;".$tel."</p>"._HTML_FIN_LIGNE;
		}
		if (strlen($siret) > 0) {
			echo "<p class=\"paragraphe credit_texte ".$classe."\">N° SIRET&nbsp;:&nbsp;".$siret."</p>"._HTML_FIN_LIGNE;
		}
		if (strlen($rcs) > 0) {
			echo "<p class=\"paragraphe credit_texte ".$classe."\">RCS&nbsp;:&nbsp;".$rcs."</p>"._HTML_FIN_LIGNE;
		}
		echo "<br />"._HTML_FIN_LIGNE;
	}
	public function ecrire_legal_resp_publication($style, $label_resp, $resp) {
		$classe  = (strlen($style) > 0)?_CSS_PREFIXE_TEXTE.$style:"";
		if (strlen($resp) > 0) {
			echo "<p class=\"paragraphe credit_texte ".$classe."\"><strong>".$label_resp."</strong>&nbsp;:&nbsp;".$resp."</p>"._HTML_FIN_LIGNE;
			echo "<br />"._HTML_FIN_LIGNE;
		}
	}
	public function ecrire_legal_hebergement($style, $label_hebergeur, $hebergeur) {
		$classe  = (strlen($style) > 0)?_CSS_PREFIXE_TEXTE.$style:"";
		if (strlen($hebergeur) > 0) {
			echo "<p class=\"paragraphe credit_texte ".$classe."\"><strong>".$label_hebergeur."</strong>&nbsp;:&nbsp;".$hebergeur."</p>"._HTML_FIN_LIGNE;
			echo "<br />"._HTML_FIN_LIGNE;
		}
	}
	public function ecrire_legal_protection($style, $site, $url, $protection, $cnil, $no_cnil) {
		$classe  = (strlen($style) > 0)?_CSS_PREFIXE_TEXTE.$style:"";
		echo "<br />"._HTML_FIN_LIGNE;
		if (strlen($url) > 0) {
			echo "<p class=\"paragraphe credit_texte ".$classe."\">".$protection."</p>"._HTML_FIN_LIGNE;
			if (strlen($no_cnil) > 0) {
				echo "<br /><p class=\"paragraphe credit_texte ".$classe."\">".$site." <strong>".$url."</strong> ".$cnil.$no_cnil.".</p>"._HTML_FIN_LIGNE;
			}
			echo "<br />"._HTML_FIN_LIGNE;
		}
	}
	public function ecrire_legal_cookies($style, $site, $url, $cookies) {
		$classe  = (strlen($style) > 0)?_CSS_PREFIXE_TEXTE.$style:"";
		echo "<br />"._HTML_FIN_LIGNE;
		if (strlen($url) > 0) {
			echo "<p class=\"paragraphe credit_texte ".$classe."\">".$site." <strong>".$url."</strong> ".$cookies."</p>"._HTML_FIN_LIGNE;
		}
		echo "<br />"._HTML_FIN_LIGNE;
	}
	public function ecrire_legal_copyright($style, $proprietaire, $propriete, $reproduction, $infraction) {
		$classe  = (strlen($style) > 0)?_CSS_PREFIXE_TEXTE.$style:"";
		echo "<br />"._HTML_FIN_LIGNE;
		if (strlen($proprietaire) > 0) {
			echo "<p class=\"paragraphe credit_texte ".$classe."\"><strong>".$proprietaire."</strong> ".$propriete."</p><br />"._HTML_FIN_LIGNE;
			echo "<p class=\"paragraphe credit_texte ".$classe."\">".$reproduction." <strong>".$proprietaire."</strong>.</p><br />"._HTML_FIN_LIGNE;
			echo "<p class=\"paragraphe credit_texte ".$classe."\">".$infraction."</p><br />"._HTML_FIN_LIGNE;
		}
	}
	public function ecrire_addthis($titre, $forme, $taille) {
		$url = urlencode("http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]);
		$suffixe_image = "-".$forme."-".$taille.".png";
		echo "<p class=\"partage_social\">";
		if ($titre) {echo "<a href=\"http://www.facebook.com/sharer.php?u=".$url."&amp;t=".$titre."\" title=\"Facebook\" target=\"_blank\">";}
		echo "<img src=\""._PHP_PATH_ROOT."images/facebook".$suffixe_image."\" alt=\"Partager sur Facebook\"/>"._HTML_FIN_LIGNE;
		if ($titre) {echo "</a>";}
		echo "&nbsp;";
		if ($titre) {echo "<a href=\"http://twitter.com/home?status=".$url."\" title=\"Twitter\" target=\"_blank\">";}
		echo "<img src=\""._PHP_PATH_ROOT."images/twitter".$suffixe_image."\" alt=\"Partager sur Twitter\"/>"._HTML_FIN_LIGNE;
		if ($titre) {echo "</a>";}
		echo "&nbsp;";
		if ($titre) {echo "<a href=\"https://plus.google.com/share?url=".$url."\" title=\"Google+\" target=\"_blank\">";}
		echo "<img src=\""._PHP_PATH_ROOT."images/google-plus".$suffixe_image."\" alt=\"Partager sur Google+\"/>"._HTML_FIN_LIGNE;
		if ($titre) {echo "</a>";}
		echo "</p>"._HTML_FIN_LIGNE;
	}
	public function ouvrir_resa($id_cal, &$tab_mois, $mois, $an, $actif = true) {
		$disabled = ($actif)?"":" disabled=\"disabled\"";
		echo "<select id=\"select_".$id_cal."\" class=\"select_resa\" ".$disabled.">"._HTML_FIN_LIGNE;
		for ($cpt = 0;$cpt < 12;$cpt++) {
			echo "<option value=\"".$cpt."\">".$tab_mois[$mois]." ".$an."</option>"._HTML_FIN_LIGNE;
			$mois += 1;if ($mois > 12) {$mois = 1;$an += 1;}
		}
		echo "</select>"._HTML_FIN_LIGNE;
		echo "<div id=\"resa_".$id_cal."\" class=\"wrap_resa\">"._HTML_FIN_LIGNE;
	}
	public function ecrire_mois_resa($idx, &$tab_jour_sem, $jour_deb, $mois_deb, $an_deb, $mois, $an, &$tab_am, &$tab_pm) {
		echo "<div id=\"mois_".$idx."\" class=\"mois_resa\">"._HTML_FIN_LIGNE;
		echo "<table class=\"tab_resa\"><tr>"._HTML_FIN_LIGNE;
		foreach($tab_jour_sem as $jour_sem) {echo "<td colspan=\"2\">".$jour_sem."</td>";}
		echo "</tr>"._HTML_FIN_LIGNE;
		$index_tab = 0;
		for ($cpt_ligne = 0;$cpt_ligne < 6;$cpt_ligne++) {
			if (($cpt_ligne < 5) || (($cpt_ligne == 5) && ($no_mois == $mois))) {
				echo "<tr>";
				for ($cpt_col = 0;$cpt_col < 7; $cpt_col++) {
					$date_jour = mktime(0, 0, 0, $mois_deb, $jour_deb + $index_tab, $an_deb);
					$no_jour = (int) date("j", $date_jour);
					$unite_jour = (int) ($no_jour % 10);
					$dizaine_jour = (int) (($no_jour - $unite_jour) / 10);
					$no_mois = (int) date("n", $date_jour);
					$code_am = ($no_mois != $mois)?"autre":$tab_am[$index_tab];
					$code_pm = ($no_mois != $mois)?"autre":$tab_pm[$index_tab];
					echo "<td class=\"am tab_resa_".$code_am."\">".(($dizaine_jour>0)?$dizaine_jour:"&nbsp;")."</td>";
					echo "<td class=\"pm tab_resa_".$code_pm."\">".$unite_jour."</td>";
					$index_tab += 1;
				}
				echo "</tr>"._HTML_FIN_LIGNE;
			}
		}
		echo "</table></div>"._HTML_FIN_LIGNE;
	}
	public function fermer_resa(&$tab_statut_resa) {
		echo "</div><div class=\"legende_resa\"><p>"._HTML_FIN_LIGNE;
		echo "<span class=\"tab_resa_libre\">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;:&nbsp;".$tab_statut_resa[0]."&nbsp;&nbsp;&nbsp;&nbsp;"._HTML_FIN_LIGNE;
		echo "<span class=\"tab_resa_reserve\">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;:&nbsp;".$tab_statut_resa[1]." &nbsp;&nbsp; "._HTML_FIN_LIGNE;
		echo "<span class=\"tab_resa_occupe\">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;:&nbsp;".$tab_statut_resa[2]."&nbsp;&nbsp;&nbsp;&nbsp;"._HTML_FIN_LIGNE;
		echo "<span class=\"tab_resa_ferme\">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;:&nbsp;".$tab_statut_resa[3].""._HTML_FIN_LIGNE;
		echo "</p></div><br/>"._HTML_FIN_LIGNE;
	}
	public function ouvrir_actu($largeur_max) {
		$style_largeur = ($largeur_max > 0)?" style=\"max-width:".$largeur_max."px;\"":"";
		echo "<div class=\"actu\"".$style_largeur.">"._HTML_FIN_LIGNE;
		echo "<ul id=\"actu\" class=\"rslides boutons_actu\">"._HTML_FIN_LIGNE;
	}
	public function ecrire_actu($num_clic, &$image, $alt, $titre, $sous_titre, $resume, $style) {
		echo "<li>"._HTML_FIN_LIGNE;
		$div_id = ($num_clic > 0)?" id=\"actu_".$num_clic."\"":"";
		echo "<div".$div_id." class=\"div_actu\">"._HTML_FIN_LIGNE;
		if ($image) {
			echo "<img class=\"image_actu\" src=\"".$image->get_src()."\" alt=\"".$alt."\" />"._HTML_FIN_LIGNE;
			if (strlen($titre) > 0) {
				$style_titre = (strlen($style) > 0)?" "._CSS_PREFIXE_ACTU."titre_".$style:"";
				echo "<p class=\"cadre_actu titre_actu".$style_titre."\">".$titre."</p>"._HTML_FIN_LIGNE;
			}
			if (strlen($sous_titre) > 0) {
				$style_sous_titre = (strlen($style) > 0)?" "._CSS_PREFIXE_ACTU."sous_titre_".$style:"";
				echo "<p class=\"cadre_actu sous_titre_actu".$style_sous_titre."\">".$sous_titre."</p>"._HTML_FIN_LIGNE;
			}
			if (strlen($resume) > 0) {
				$style_resume = (strlen($style) > 0)?" "._CSS_PREFIXE_ACTU."resume_".$style:"";
				echo "<p class=\"cadre_actu resume_actu".$style_resume."\">".$resume."</p>"._HTML_FIN_LIGNE;
			}
		}
		echo "</div>"._HTML_FIN_LIGNE;
		echo "</li>"._HTML_FIN_LIGNE;
	}
	public function fermer_actu($actif = true) {
		echo "</ul>"._HTML_FIN_LIGNE;
		echo "</div>"._HTML_FIN_LIGNE;
		if ($actif) {
			echo "<script type=\"text/javascript\">"._HTML_FIN_LIGNE;
			echo "$(function() {"._HTML_FIN_LIGNE;
			echo "$(\"#actu\").responsiveSlides({speed:200,timeout:5000,pager:true,nav:true,namespace:'boutons_actu'});"._HTML_FIN_LIGNE;
			echo "});"._HTML_FIN_LIGNE;
			echo "</script>"._HTML_FIN_LIGNE;
		}
	}
	public function ecrire_prev_next_actu($prev_label, $prev_titre, $prev_actu, $next_label, $next_titre, $next_actu, $style, $langue=null) {
		$classe = "paragraphe";
		if (strlen($style) > 0) {$classe .= " "._CSS_PREFIXE_TEXTE.$style;}
		$href_prev = _HTML_PATH_ACTU."?id=".$prev_actu.((strlen($langue)>0)?"&l=".$langue:"");
		$href_next = _HTML_PATH_ACTU."?id=".$next_actu.((strlen($langue)>0)?"&l=".$langue:"");
		echo "<div class=\"prev_actu\"><p class=\"".$classe."\">"._HTML_FIN_LIGNE;
		echo "<a class=\"bouton_actu\" href=\"".$href_prev."\" title=\"".$prev_label."\">&#xf048;</a>&nbsp;"._HTML_FIN_LIGNE;
		echo "&nbsp;&nbsp;<a href=\"".$href_prev."\" title=\"".$prev_label."\">".$prev_titre."</a></p>"._HTML_FIN_LIGNE;
		echo "</div>"._HTML_FIN_LIGNE;
		echo "<div class=\"next_actu\"><p class=\"".$classe."\">"._HTML_FIN_LIGNE;
		echo "<a href=\"".$href_next."\" title=\"".$next_label."\">".$next_titre."</a>&nbsp;&nbsp;"._HTML_FIN_LIGNE;
		echo "&nbsp;<a class=\"bouton_actu\" href=\"".$href_next."\" title=\"".$next_label."\">&#xf051;</a></p>"._HTML_FIN_LIGNE;
		echo "</div><div style=\"clear:both;\"></div>"._HTML_FIN_LIGNE;
	}
	private function ecrire_legende($legende, $niveau_legende, $style_legende, $lien = null, $access_key = null) {
		if (strlen($legende) > 0) {
			echo "<div class=\"cadre_legende "._CSS_PREFIXE_INTERIEUR.$style_legende." transparence_80pc\">"._HTML_FIN_LIGNE;
			echo "<table class=\"tableau_legende\"><tr><td>";
			if (strlen($lien) > 0) {
				$attr_access = (strlen($access_key)>0)?" accesskey=\"".$access_key."\"":"";
				$attr_target = $this->url_target($lien);
				echo "<a href=\"".$lien."\"".$attr_access.$attr_target.">".$legende."</a>"._HTML_FIN_LIGNE;
			}
			else {
				$balise = ($niveau_legende < 1)?"p":"h".$niveau_legende;
				echo "<".$balise.">".$legende."</".$balise.">"._HTML_FIN_LIGNE;
			}
			echo "</td></tr></table>"._HTML_FIN_LIGNE;
			echo "</div>"._HTML_FIN_LIGNE;
		}
	}
	private function ecrire_contact_texte($style_p, $nom_style, $obligatoire, $label, $name, $court, $email, $actif) {
		$span = ($obligatoire)?"<span class=\"champ_obligatoire\">&nbsp;(*)</span>":"";
		$classe = ($court)?"champ_court":"champ_long";
		if (strlen($nom_style) > 0) {
			$classe .= " "._CSS_PREFIXE_FORMULAIRE_CHAMP.$nom_style;
		}
		$type = ($email)?"email":"text";
		$maxlength = ($court)?"20":"50";
		$disabled = ($actif)?"":" disabled=\"disabled\"";
		echo "<p class=\"paragraphe".$style_p."\">"._HTML_FIN_LIGNE;
		echo "<label class=\"champ_label\" for=\"id_".$name."\">".$label.$span."</label>"._HTML_FIN_LIGNE;
		echo "<input class=\"champ_saisie ".$classe."\" type=\"".$type."\" id=\"id_".$name."\" name=\"".$name."\" maxlength=\"".$maxlength."\"".$disabled."/>"._HTML_FIN_LIGNE;
		echo "</p>"._HTML_FIN_LIGNE;
		echo "<p class=\"champ_erreur\" id=\"err_".$name."\">&nbsp;</p>"._HTML_FIN_LIGNE;
	}
	private function ecrire_contact_message($style_p, $nom_style, $obligatoire, $label, $name, $actif) {
		$classe = "champ_long";
		if (strlen($nom_style) > 0) {
			$classe .= " "._CSS_PREFIXE_FORMULAIRE_CHAMP.$nom_style;
		}
		$span = ($obligatoire)?"<span class=\"champ_obligatoire\">&nbsp;(*)</span>":"";
		$disabled = ($actif)?"":" disabled=\"disabled\"";
		echo "<p class=\"paragraphe".$style_p."\">"._HTML_FIN_LIGNE;
		echo "<label class=\"champ_label\" for=\"id_".$name."\">".$label.$span."</label>"._HTML_FIN_LIGNE;
		echo "<textarea class=\"champ_saisie ".$classe."\" id=\"id_".$name."\" name=\"".$name."\" cols=\"80\" rows=\"10\" maxlength=\"1500\"".$disabled."></textarea>"._HTML_FIN_LIGNE;
		echo "</p>"._HTML_FIN_LIGNE;
		echo "<p class=\"champ_erreur\" id=\"err_".$name."\">&nbsp;</p>"._HTML_FIN_LIGNE;
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
	private function extraire_classe_alignement($alignement) {
		$classe = "";
		switch ($alignement) {
			case _STYLE_ATTR_ALIGNEMENT_GAUCHE :
				$classe .= "texte_g";break;
			case _STYLE_ATTR_ALIGNEMENT_DROITE :
				$classe .= "texte_d";break;
			default :
				$classe .= "texte_c";break;
		}
		return $classe;
	}
	private function extraire_extension($fichier) {
		$extension = strtoupper(substr(strrchr($fichier, ".")  ,1));
		if (!(strcmp($extension, "jpeg"))) {$extension = _UPLOAD_EXTENSION_JPG;}
		return $extension;
	}
	private function url_target($lien) {
		$ret = (strncmp($lien, "http", 4))?"":" target=\"_blank\"";
		return $ret;
	}
}