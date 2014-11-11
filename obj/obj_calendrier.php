<?php
	
class obj_calendrier extends obj_editable {
	private $obj_module_resa = null;
	private $obj_texte = null;
	private $id_cal = null;
	private $mois = 0;
	private $an = 0;

	public function __construct(&$obj_module_resa, &$obj_texte, $id_cal) {
		$this->obj_module_resa = $obj_module_resa;
		$this->obj_texte = $obj_texte;
		$this->id_cal = $id_cal;
		$this->mois = (int) date("n");
		$this->an = (int) date("Y");
	}

	public function afficher($mode, $langue) {
		$langue_affichee = (strcmp($mode, _PETILABO_MODE_SITE))?$this->obj_texte->get_langue_par_defaut():$langue;
		if (strcmp($mode, _PETILABO_MODE_EDIT)) {
			$tab_mois = $this->obj_texte->get_tab_mois($langue_affichee);
			$tab_statut_resa = $this->obj_texte->get_tab_statut_resa($langue_affichee);
			$disabled = (strcmp($mode, _PETILABO_MODE_SITE))?" disabled=\"disabled\"":"";
			$mois = $this->mois;$an = $this->an;
			echo "<select id=\"select_".$this->id_cal."\" class=\"select_resa\" ".$disabled.">"._HTML_FIN_LIGNE;
			for ($cpt = 0;$cpt < 12;$cpt++) {
				echo "<option value=\"".$cpt."\">".$tab_mois[$mois]." ".$an."</option>"._HTML_FIN_LIGNE;
				$mois += 1;if ($mois > 12) {$mois = 1;$an += 1;}
			}
			echo "</select>"._HTML_FIN_LIGNE;
			echo "<div id=\"resa_".$this->id_cal."\" class=\"wrap_resa\">"._HTML_FIN_LIGNE;
			$mois = $this->mois;$an = $this->an;
			$cpt_max = (strcmp($mode, _PETILABO_MODE_SITE))?1:12;
			for ($cpt = 0;$cpt < 12;$cpt++) {
				$jour_sem = ((int) date("N", mktime(0, 0, 0, $mois, 1, $an)) - 1);
				$date_deb = mktime(0, 0, 0, $mois, (1 - $jour_sem), $an);
				$jour_deb = (int) date("j", $date_deb);
				$mois_deb = (int) date("n", $date_deb);
				$an_deb = (int) date("Y", $date_deb);
				$this->ecrire_calendrier_resa($mode, $cpt, $jour_deb, $mois_deb, $an_deb, $mois, $an, $langue_affichee);
				$mois += 1;if ($mois == 13) {$mois = 1;$an += 1;}
			}
			echo "</div><div class=\"legende_resa\"><p>"._HTML_FIN_LIGNE;
			echo "<span class=\"tab_resa_libre\">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;:&nbsp;".$tab_statut_resa[0]."&nbsp;&nbsp;&nbsp;&nbsp;"._HTML_FIN_LIGNE;
			echo "<span class=\"tab_resa_reserve\">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;:&nbsp;".$tab_statut_resa[1]." &nbsp;&nbsp; "._HTML_FIN_LIGNE;
			echo "<span class=\"tab_resa_occupe\">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;:&nbsp;".$tab_statut_resa[2]."&nbsp;&nbsp;&nbsp;&nbsp;"._HTML_FIN_LIGNE;
			echo "<span class=\"tab_resa_ferme\">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;:&nbsp;".$tab_statut_resa[3].""._HTML_FIN_LIGNE;
			echo "</p></div><br/>"._HTML_FIN_LIGNE;
		}
		else {
			$this->ouvrir_tableau_simple();
			$this->ouvrir_ligne();
			$this->ecrire_cellule_categorie(_EDIT_LABEL_CALENDRIER, _EDIT_COULEUR, 1);
			$this->ecrire_cellule_symbole_calendrier($this->id_cal, _EDIT_SYMBOLE_CALENDRIER);
			$this->ecrire_cellule_texte($this->id_cal, $this->id_cal);
			$this->fermer_ligne();
			$this->fermer_tableau();
		}
	}
	
	private function ecrire_calendrier_resa($mode, $cpt_mois_resa, $jour_deb, $mois_deb, $an_deb, $mois, $an, $langue) {
		$tab_am = array();$tab_pm = array();
		$tab_jour_sem = $this->obj_texte->get_tab_semaine($langue);
		// 42 = 6 semaines de 7 jours
		$this->obj_module_resa->get_info_resa($this->id_cal, $jour_deb, $mois_deb, $an_deb, 42, $tab_am, $tab_pm);
		echo "<div id=\"mois_".$cpt_mois_resa."\" class=\"mois_resa\">"._HTML_FIN_LIGNE;
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
}