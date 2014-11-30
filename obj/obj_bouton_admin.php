<?php
class obj_bouton_admin extends obj_html {
	private $page = null;
	private $alignement = null;
	private $style = null;

	public function __construct($page, $alignement, $style) {
		$this->page = $page;
		$this->alignement = $alignement;
		$this->style = $style;
	}

	public function afficher($mode, $langue) {
		if (!(strcmp($mode, _PETILABO_MODE_SITE))) {
			echo "<p class=\"icone_pp manuel_site_pp\">";
			echo "<a href=\""._PHP_PATH_ROOT._HTTP_LOG_PREFIXE."/?"._PARAM_PAGE."=".$this->page."\" title=\"Accès privé\" rel=\"nofollow\">&#xf013;</a>";
			echo "</p>\n";
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_ADMIN))) {
			echo "<p class=\"icone_pp manuel_admin_pp\">";
			echo "<a href=\""._PHP_PATH_ROOT._HTTP_LOG_ADMIN."/deconnect.php\" title=\"Quitter la page d'administration\" rel=\"nofollow\">&#xf08b;</a>";
			echo "</p>\n";
		}
	}
}