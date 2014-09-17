<?php
	class html_edit {
		private $nom_page = null;
		private $no_contenu = null;
		private $no_bloc = null;
		private $id = null;
		
		public function __construct($nom_page, $no_contenu, $no_bloc) {
			$this->nom_page = $nom_page;
			$this->no_contenu = $no_contenu;
			$this->no_bloc = $no_bloc;
			$this->id = "tab_".$no_contenu."_".$no_bloc;
		}
		public function ouvrir_tableau_multiple($label, $couleur, $nom) {
			echo "<table class=\"tableau\" cellspacing=\"2\"><tr>";
			echo "<td class=\"cellule cellule_categorie\" style=\"background:".$couleur."\">";
			echo "<p class=\"nom_categorie\">".$label."</p>";
			echo "</td></tr>";
		}
		public function ouvrir_tableau_simple() {
			echo "<table class=\"tableau\" cellspacing=\"2\">";
		}
		public function fermer_tableau() {
			echo "</table>\n";
		}
		public function ouvrir_ligne() {
			echo "<tr>";
		}
		public function fermer_ligne() {
			echo "</tr>";
		}
		public function ecrire_cellule_categorie($label, $couleur, $nb_lignes) {
			$rowspan = "";
			if ($nb_lignes > 1) {
				$rowspan = " rowspan=\"".$nb_lignes."\"";
			}
			echo "<td class=\"cellule cellule_categorie\" style=\"background:".$couleur."\"".$rowspan.">";
			echo "<p class=\"nom_categorie\">".$label."</p>";
			echo "</td>";
		}
		public function ecrire_cellule_symbole_texte_simple($type, $id, $symbole, $info="") {
			echo "<td class=\"cellule cellule_symbole\">";
			if (strlen($id) > 0) {
				$title = (strlen($info) > 0)?" title=\"".$info."\"":"";
				echo "<a class=\"symbole symbole_actif\" href=\"form_texte_simple.php?"._PARAM_ID."=".$id."&"._PARAM_TYPE."=".$type."\"".$title.">&#xf".$symbole.";</a>";
			}
			else {
				echo "<p class=\"symbole symbole_inactif\">&#xf".$symbole.";</p>";
			}
			echo "</td>";
		}
		public function ecrire_cellule_symbole_texte_brut($id, $symbole, $info="") {
			echo "<td class=\"cellule cellule_symbole\">";
			if (strlen($id) > 0) {
				$title = (strlen($info) > 0)?" title=\"".$info."\"":"";
				echo "<a class=\"symbole symbole_actif\" href=\"form_texte_brut.php?"._PARAM_ID."=".$id."\"".$title.">&#xf".$symbole.";</a>";
			}
			else {
				echo "<p class=\"symbole symbole_inactif\">&#xf".$symbole.";</p>";
			}
			echo "</td>";
		}
		public function ecrire_cellule_symbole_texte($id, $symbole, $info="") {
			echo "<td class=\"cellule cellule_symbole\">";
			if (strlen($id) > 0) {
				$title = (strlen($info) > 0)?" title=\"".$info."\"":"";
				echo "<a class=\"symbole symbole_actif\" href=\"form_texte.php?"._PARAM_ID."=".$id."\"".$title.">&#xf".$symbole.";</a>";
			}
			else {
				echo "<p class=\"symbole symbole_inactif\">&#xf".$symbole.";</p>";
			}
			echo "</td>";
		}
		public function ecrire_cellule_symbole_lien_editable($id, $symbole, $info="", $id_liste="") {
			echo "<td class=\"cellule cellule_symbole\">";
			if (strlen($id) > 0) {
				$title = (strlen($info) > 0)?" title=\"".$info."\"":"";
				$param_liste = (strlen($id_liste) > 0)?("&"._PARAM_ID_LISTE."=".$id_liste):"";
				echo "<a class=\"symbole symbole_actif\" href=\"form_lien_editable.php?"._PARAM_ID."=".$id.$param_liste."\"".$title.">&#xf".$symbole.";</a>";
			}
			else {
				echo "<p class=\"symbole symbole_inactif\">&#xf".$symbole.";</p>";
			}
			echo "</td>";
		}
		public function ecrire_cellule_symbole_image($id, $symbole) {
			echo "<td class=\"cellule cellule_symbole\">";
			echo "<a class=\"symbole symbole_actif\" href=\"form_image.php?"._PARAM_ID."=".$id."\" title=\"Modifier le fichier de l'image\">&#xf".$symbole.";</a>";
			echo "</td>";
		}
		public function ecrire_cellule_symbole_pj($id, $symbole) {
			echo "<td class=\"cellule cellule_symbole\">";
			echo "<a class=\"symbole symbole_actif\" href=\"form_pj.php?"._PARAM_ID."=".$id."\" title=\"Modifier le fichier de la pièce jointe\">&#xf".$symbole.";</a>";
			echo "</td>";
		}
		public function ecrire_cellule_symbole_calendrier($id, $symbole) {
			echo "<td class=\"cellule cellule_symbole\">";
			echo "<a class=\"symbole symbole_actif\" href=\"form_calendrier.php?"._PARAM_ID."=".$id."\" title=\"Modifier le calendrier de réservation\">&#xf".$symbole.";</a>";
			echo "</td>";
		}
		public function ecrire_cellule_symbole_sommaire($id, $symbole) {
			echo "<td class=\"cellule cellule_symbole\">";
			$title = "Modifier la ".(($id == 1)?"1ère":$id."ème")." entrée du sommaire";
			echo "<a class=\"symbole symbole_actif\" href=\"form_sommaire.php?"._PARAM_ID."=".$id."\" title=\"".$title."\">&#xf".$symbole.";</a>";
			echo "</td>";
		}
		public function ecrire_cellule_texte($id, $texte) {
			echo "<td class=\"cellule cellule_texte\">";
			$label = (strlen($id) > 0)?$texte:"Non défini";
			$classe = (strlen($id) > 0)?"_actif":"_inactif";
			echo "<p class=\"texte_edit texte".$classe."\">".$label."</p>";
			echo "</td>";
		}
		public function ecrire_cellule_video($id, $code, $source) {
			echo "<td class=\"cellule cellule_texte\">";
			if (strlen($id) > 0) {
				$video = new video($source, $code);
				$src = $video->get_src();
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
		public function ecrire_cellule_icone($texte) {
			echo "<td class=\"cellule cellule_texte\">";
			echo "<p class=\"icone_edit\">".$texte."</p>";
			echo "</td>";
		}
		public function ecrire_cellule_image($source = null) {
			echo "<td class=\"cellule cellule_texte\">";
			if ($source) {
				echo "<img class=\"image_edit\" src=\"".$source."?id=".uniqid()."\" alt=\"Image\" />";
			}
			else {
				echo "<p class=\"texte_edit texte_actif\" style=\"font-style:italic;\">Vide</p>";
			}
			echo "</td>";
		}
	}