<?php

// Constantes pour les couleurs de catégorie
define("_EDIT_COULEUR", "#444");

// Constantes pour les noms de catégorie
define("_EDIT_LABEL_TITRE", "Titre");
define("_EDIT_LABEL_SOUS_TITRE", "Sous-titre");
define("_EDIT_LABEL_TEXTE", "Paragraphe");
define("_EDIT_LABEL_SYMBOLE", "Symbole");
define("_EDIT_LABEL_DIAPORAMA", "Diaporama");
define("_EDIT_LABEL_CARROUSEL", "Carrousel");
define("_EDIT_LABEL_VIGNETTES", "Vignettes");
define("_EDIT_LABEL_GALERIE", "Galerie");
define("_EDIT_LABEL_IMAGE", "Image");
define("_EDIT_LABEL_MENU", "Menu");
define("_EDIT_LABEL_ITEM", "Choix");
define("_EDIT_LABEL_PLAN", "Plan");
define("_EDIT_LABEL_VIDEO", "Video");
define("_EDIT_LABEL_PJ", "Pièce jointe");
define("_EDIT_LABEL_CALENDRIER", "Calendrier");
define("_EDIT_LABEL_BANNIERE_ACTUALITE", "Bannière d'actualités");
define("_EDIT_LABEL_ACTUALITE", "Actualité");
define("_EDIT_LABEL_SOMMAIRE", "Sommaire");
define("_EDIT_LABEL_META", "Balises méta");
define("_EDIT_LABEL_META_TITRE", "Méta titre");
define("_EDIT_LABEL_META_DESCR", "Méta description");

// Constantes pour les symboles
define("_EDIT_SYMBOLE_LABEL", "15c");
define("_EDIT_SYMBOLE_IMAGE", "03e");
define("_EDIT_SYMBOLE_ALT", "02b");
define("_EDIT_SYMBOLE_COPY", "1f9");
define("_EDIT_SYMBOLE_INFO", "075");
define("_EDIT_SYMBOLE_ICONE", "005");
define("_EDIT_SYMBOLE_LEGENDE", "02d");
define("_EDIT_SYMBOLE_LIEN", "0c1");
define("_EDIT_SYMBOLE_PLAN", "041");
define("_EDIT_SYMBOLE_VIDEO", "03d");
define("_EDIT_SYMBOLE_SOMMAIRE", "03a");
define("_EDIT_SYMBOLE_CALENDRIER", "073");
define("_EDIT_SYMBOLE_META", "024");
	
class obj_editable extends obj_html {

	protected function ouvrir_tableau_multiple($label, $couleur, $nom) {
		echo "<table class=\"tableau\" cellspacing=\"2\"><tr>";
		echo "<td class=\"cellule cellule_categorie\" style=\"background:".$couleur."\">";
		echo "<p class=\"nom_categorie\">".$label."</p>";
		echo "</td></tr>";
	}
	protected function ouvrir_tableau_simple() {
		echo "<table class=\"tableau\" cellspacing=\"2\">";
	}
	protected function fermer_tableau() {
		echo "</table>\n";
	}
	protected function ouvrir_ligne() {
		echo "<tr>";
	}
	protected function fermer_ligne($src = null) {
		if (strlen($src) > 0) {
			if (!(strncmp($src, "librairie", 9))) {$src = "librairie";}
			echo "<td class=\"admin_source_element admin_source_element_".$src."\">".ucwords($src)."</td>";
		}
		else {
			echo "<td class=\"admin_source_element admin_source_element_vide\">-</td>";
		}
		echo "</tr>";
	}
	protected function ecrire_cellule_categorie($label, $couleur, $nb_lignes) {
		$rowspan = "";
		if ($nb_lignes > 1) {
			$rowspan = " rowspan=\"".$nb_lignes."\"";
		}
		echo "<td class=\"cellule cellule_categorie\" style=\"background:".$couleur."\"".$rowspan.">";
		echo "<p class=\"nom_categorie\">".$label."</p>";
		echo "</td>";
	}
	protected function ecrire_cellule_symbole_texte_simple($type, $id, $symbole, $info="") {
		echo "<td class=\"cellule cellule_symbole\">";
		if (strlen($id) > 0) {
			$title = (strlen($info) > 0)?" title=\"".$info."\"":"";
			$param = $this->build_param($id);
			echo "<a class=\"symbole symbole_actif\" href=\"form_texte_simple.php?".$param."&"._PARAM_TYPE."=".$type."\"".$title.">&#xf".$symbole.";</a>";
		}
		else {
			echo "<p class=\"symbole symbole_inactif\">&#xf".$symbole.";</p>";
		}
		echo "</td>";
	}
	protected function ecrire_cellule_symbole_texte_brut($id, $symbole, $info="") {
		echo "<td class=\"cellule cellule_symbole\">";
		if (strlen($id) > 0) {
			$title = (strlen($info) > 0)?" title=\"".$info."\"":"";
			$param = $this->build_param($id);
			echo "<a class=\"symbole symbole_actif\" href=\"form_texte_brut.php?".$param."\"".$title.">&#xf".$symbole.";</a>";
		}
		else {
			echo "<p class=\"symbole symbole_inactif\">&#xf".$symbole.";</p>";
		}
		echo "</td>";
	}
	protected function ecrire_cellule_symbole_texte($id, $symbole, $info="") {
		echo "<td class=\"cellule cellule_symbole\">";
		if (strlen($id) > 0) {
			$title = (strlen($info) > 0)?" title=\"".$info."\"":"";
			$param = $this->build_param($id);
			echo "<a class=\"symbole symbole_actif\" href=\"form_texte.php?".$param."\"".$title.">&#xf".$symbole.";</a>";
		}
		else {
			echo "<p class=\"symbole symbole_inactif\">&#xf".$symbole.";</p>";
		}
		echo "</td>";
	}
	protected function ecrire_cellule_symbole_lien_editable($id, $symbole, $info="", $id_liste="") {
		echo "<td class=\"cellule cellule_symbole\">";
		if (strlen($id) > 0) {
			$title = (strlen($info) > 0)?" title=\"".$info."\"":"";
			$param = $this->build_param($id);
			$param .= (strlen($id_liste) > 0)?("&"._PARAM_ID_LISTE."=".$id_liste):"";
			echo "<a class=\"symbole symbole_actif\" href=\"form_lien_editable.php?".$param."\"".$title.">&#xf".$symbole.";</a>";
		}
		else {
			echo "<p class=\"symbole symbole_inactif\">&#xf".$symbole.";</p>";
		}
		echo "</td>";
	}
	protected function ecrire_cellule_symbole_image($id, $symbole) {
		echo "<td class=\"cellule cellule_symbole\">";
		$param = $this->build_param($id);
		echo "<a class=\"symbole symbole_actif\" href=\"form_image.php?".$param."\" title=\"Modifier le fichier de l'image\">&#xf".$symbole.";</a>";
		echo "</td>";
	}
	protected function ecrire_cellule_symbole_pj($id, $symbole) {
		echo "<td class=\"cellule cellule_symbole\">";
		$param = $this->build_param($id);
		echo "<a class=\"symbole symbole_actif\" href=\"form_pj.php?".$param."\" title=\"Modifier le fichier de la pièce jointe\">&#xf".$symbole.";</a>";
		echo "</td>";
	}
	protected function ecrire_cellule_symbole_calendrier($id, $symbole) {
		echo "<td class=\"cellule cellule_symbole\">";
		$param = $this->build_param($id);
		echo "<a class=\"symbole symbole_actif\" href=\"form_calendrier.php?".$param."\" title=\"Modifier le calendrier de réservation\">&#xf".$symbole.";</a>";
		echo "</td>";
	}
	protected function ecrire_cellule_symbole_sommaire($id, $symbole) {
		echo "<td class=\"cellule cellule_symbole\">";
		$title = "Modifier la ".(($id == 1)?"1ère":$id."ème")." entrée du sommaire";
		$param = $this->build_param($id);
		echo "<a class=\"symbole symbole_actif\" href=\"form_sommaire.php?".$param."\" title=\"".$title."\">&#xf".$symbole.";</a>";
		echo "</td>";
	}
	protected function ecrire_cellule_texte($id, $texte) {
		echo "<td class=\"cellule cellule_texte\">";
		$label = (strlen($id) > 0)?$texte:"Non défini";
		$classe = (strlen($id) > 0)?"_actif":"_inactif";
		echo "<p class=\"texte_edit texte".$classe."\">".$label."</p>";
		echo "</td>";
	}
	protected function ecrire_cellule_video($id, $code, $src) {
		echo "<td class=\"cellule cellule_texte\">";
		if (strlen($id) > 0) {
			if (strlen($src) > 0) {
				echo "<img class=\"image_edit\" src=\"".$src."?id=".uniqid()."\" alt=\"Video\" />";
			}
			else {
				echo "<p class=\"texte_edit texte_actif\">".$code."</p>";
			}
		}
		else {
			echo "<p class=\"texte_edit texte_inactif\">Non défini</p>";
		}
		echo "</td>";
	}
	protected function ecrire_cellule_icone($texte) {
		echo "<td class=\"cellule cellule_texte\">";
		echo "<p class=\"icone_edit\">".$texte."</p>";
		echo "</td>";
	}
	protected function ecrire_cellule_image($source = null) {
		echo "<td class=\"cellule cellule_texte\">";
		if ($source) {
			echo "<img class=\"image_edit\" src=\"".$source."?v=".uniqid()."\" alt=\"Image\" />";
		}
		else {
			echo "<p class=\"texte_edit texte_actif\" style=\"font-style:italic;\">Vide</p>";
		}
		echo "</td>";
	}
	protected function construire_etiquette($titre, $nom, $separateur="&nbsp;") {
		$etiquette = $titre.$separateur."<span style=\"font-size:0.8em;font-style:italic;\">(".$nom.")</span>";
		return $etiquette;
	}
	protected function check_src_texte(&$obj_texte, &$id, $langue) {
		if ($obj_texte->existe_texte($id)) {
			$trad = $obj_texte->get_texte($id, $langue);
			$src = $obj_texte->get_source($id);
		}
		else {$id = null;$trad = null;$src = null;}
		return array($trad, $src);
	}
	protected function check_src_icone(&$obj_texte, &$id, $langue) {
		if ($obj_texte->existe_texte($id)) {
			$trad = $obj_texte->get_icone($id, $langue);
			$src = $obj_texte->get_source($id);
		}
		else {$id = null;$trad = null;$src = null;}
		return array($trad, $src);
	}
	protected function build_param($id) {
		$ret = "";
		if (strlen($id) > 0) {$ret .= _PARAM_ID."=".$id;}
		if (strlen($this->id_tab) > 0) { $ret .= "&"._PARAM_POINT_RETOUR."=".$this->id_tab;}
		return $ret;
	}
}