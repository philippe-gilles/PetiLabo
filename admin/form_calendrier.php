<?php
	require_once "inc/path.php";
	inclure_inc("const", "param", "session");
	inclure_site("xml_const", "xml_site", "xml_page", "xml_module_resa");
	
	class form_calendrier {
		private $nom_page = null;
		private $id_cal = null;
		private $module_resa = null;
		private $mois_en_cours = 0;private $an_en_cours = 0;
		private $jour_deb = 0;private $mois_deb = 0;private $an_deb = 0;

		public function __construct($nom_page, $id_cal) {
			$this->nom_page = $nom_page;
			$this->id_cal = $id_cal;
			$this->module_resa = new xml_module_resa();
			$this->module_resa->ouvrir($id_cal, _XML_PATH_MODULES.$id_cal."/"._XML_MODULE_RESA._XML_EXT);
			$this->mois_en_cours = (int) date("n");
			$this->an_en_cours = (int) date("Y");
			$jour_en_cours = (int) date("j");
			$jour_sem = (int) date("N");
			$date_deb = mktime(0, 0, 0, $this->mois_en_cours, $jour_en_cours + (1 - $jour_sem), $this->an_en_cours);
			$this->jour_deb = (int) date("j", $date_deb);
			$this->mois_deb = (int) date("n", $date_deb);
			$this->an_deb = (int) date("Y", $date_deb);
		}
		public function afficher() {
			$tab_am = array();$tab_pm = array();
			$this->module_resa->get_info_resa($this->id_cal, $this->jour_deb, $this->mois_deb, $this->an_deb, 371, $tab_am, $tab_pm);
			$this->afficher_entete();
			$this->afficher_dates(371, $tab_am, $tab_pm);
		}
		private function afficher_entete() {
			$tab_jour_sem = array("  ", "Lu", "Ma", "Me", "Je", "Ve", "Sa", "Di");
			echo "<table class=\"entetes_calendrier\"><tr>";
			foreach($tab_jour_sem as $jour_sem) {echo "<td style=\"width:12%;text-align:center;\">".$jour_sem."</td>";}
			echo "<td style=\"text-align:center;\">&nbsp;</td>";
			echo "</tr></table>\n";
		}
		private function afficher_dates($nb_dates, &$tab_am, &$tab_pm) {
			$tab_mois = array("", "Janv", "Fév", "Mars", "Avr", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc");
			echo "<div class=\"wrap_calendrier\"><table class=\"calendrier\">";
			$index_tab = 0;
			$nb_lignes = (int) ($nb_dates / 7);
			for ($cpt_ligne = 0;$cpt_ligne < $nb_lignes;$cpt_ligne++) {
				echo "<tr>";
				for ($cpt_col = 0;$cpt_col < 7; $cpt_col++) {
					$date_jour = mktime(0, 0, 0, $this->mois_deb, $this->jour_deb + $index_tab, $this->an_deb);
					$no_jour = (int) date("j", $date_jour);
					$unite_jour = (int) ($no_jour % 10);
					$dizaine_jour = (int) (($no_jour - $unite_jour) / 10);
					$mois_jour = (int) date("n", $date_jour);
					$an_jour = (int) date("Y", $date_jour);
					if ($cpt_col == 0) {echo "<td class=\"nom_mois\">".$tab_mois[$mois_jour]."</td>";}
					$id = sprintf("%d_%02d_%02d", $an_jour, $mois_jour, $no_jour);
					echo "<td class=\"am ".$tab_am[$index_tab]."\" id=\"am_".$id."\">".(($dizaine_jour>0)?$dizaine_jour:"&nbsp;")."</td>";
					echo "<td class=\"pm ".$tab_pm[$index_tab]." active\"  id=\"pm_".$id."\">".$unite_jour."</td>";
					$index_tab += 1;
				}
				echo "</tr>";
			}
			echo "</table></div>\n";
		}
	}

	$session = new session();
	if (is_null($session)) {
		header("Location: "._SESSION_URL_FERMETURE);
		exit;
	}

	$session->check_session();
	
	$param = new param();
	$id_calendrier = $param->get(_PARAM_ID);
	if (strlen($id_calendrier) == 0) {
		$session->fermer_session();
		exit;
	}

	$page = $session->get_session_param(_SESSION_PARAM_PAGE);
	if (strlen($page) == 0) {
		$session->fermer_session();
		exit;
	}

	$form = new form_calendrier($page, $id_calendrier);
	echo "<div class=\"form_lb\">\n";
	echo "<p class=\"titre_zone\">Calendrier de réservation</p>\n";
	$form->afficher();
	echo "<form id=\"id_form_calendrier\" name=\"form_calendrier\" accept-charset=\"UTF-8\" method=\"post\" action=\"submit_calendrier.php\">\n";
	echo "<div style=\"margin:10px 10px 10px 0;padding:0;float:left;\">\n";
	echo "<p class=\"champ\"><label for=\"id_debut\">&Agrave; partir du</label></p>";
	echo "<p class=\"champ\"><input type=\"texte\" id=\"id_debut\" name=\"date_debut\" value=\"\" disabled=\"disabled\" size=\"12\"/></p>\n";
	echo "</div>\n";
	echo "<div style=\"margin:10px 10px 10px 0;padding:0;float:left;\">\n";
	echo "<p class=\"champ\"><label for=\"id_fin\">Jusqu'au</label></p>";
	echo "<p class=\"champ\"><input type=\"texte\" id=\"id_fin\" name=\"date_fin\" value=\"\" disabled=\"disabled\" size=\"12\"/></p>\n";
	echo "</div><div style=\"clear:both;\"></div>\n";
	echo "<div style=\"margin:10px 10px 10px 0;padding:0;float:left;\">\n";
	echo "<p class=\"champ\"><label for=\"id_statut\">Statut&nbsp;: </label>";
	echo "<select id=\"id_statut\" name=\"statut\">";
	echo "<option value=\""._MODULE_RESA_ATTR_STATUT_LIBRE."\">Libre</option>";
	echo "<option value=\""._MODULE_RESA_ATTR_STATUT_RESERVE."\">Réservé</option>";
	echo "<option value=\""._MODULE_RESA_ATTR_STATUT_OCCUPE."\">Occupé</option>";
	echo "<option value=\""._MODULE_RESA_ATTR_STATUT_FERME."\">Fermé</option>";
	echo "</select></p>\n";
	echo "</div>\n";
	echo "<div style=\"margin:10px 10px 10px 0;padding:0;float:left;\">\n";
	echo "<p class=\"champ\" id=\"id_nb_nuits\"></p>\n";
	echo "</div><div style=\"clear:both;\"></div>\n";
	echo "<p class=\"champ\"><input type=\"hidden\" name=\"id_calendrier\" value=\"".$id_calendrier."\" /></p>\n";
	echo "<p class=\"champ\"><input class=\"bouton\" type=\"submit\" name=\"valider\" value=\"Enregistrer\"></p>\n";
	echo "</form>\n";
	echo "</div>\n";