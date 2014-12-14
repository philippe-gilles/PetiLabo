<?php
	
class obj_partage_social extends obj_html {
	private $obj_texte = null;
	private $url = null;
	private $titre = null;
	private $forme_carree = false;
	private $grande_taille = false;

	public function __construct(&$obj_texte, $url, $titre, $forme_carree, $grande_taille) {
		$this->obj_texte = $obj_texte;
		$this->url = $url;
		$this->titre = $titre;
		$this->forme_carree = $forme_carree;
		$this->grande_taille = $grande_taille;

	}

	public function afficher($mode) {
		$suffixe_image = "-".(($this->forme_carree)?"carre":"rond")."-".(($this->grande_taille)?"48":"32").".png";
		if (!(strcmp($mode, _PETILABO_MODE_SITE))) {
			echo "<p class=\"partage_social\">";
			echo "<a href=\"http://www.facebook.com/sharer.php?u=".$this->url."&amp;t=".$this->titre."\" title=\"Facebook\" target=\"_blank\">";
			echo "<img src=\""._PHP_PATH_ROOT."images/facebook".$suffixe_image."\" alt=\"Partager sur Facebook\"/>";
			echo "</a>&nbsp;";
			echo "<a href=\"http://twitter.com/home?status=".$this->url."\" title=\"Twitter\" target=\"_blank\">";
			echo "<img src=\""._PHP_PATH_ROOT."images/twitter".$suffixe_image."\" alt=\"Partager sur Twitter\"/>";
			echo "</a>&nbsp;";
			echo "<a href=\"https://plus.google.com/share?url=".$this->url."\" title=\"Google+\" target=\"_blank\">";
			echo "<img src=\""._PHP_PATH_ROOT."images/google-plus".$suffixe_image."\" alt=\"Partager sur Google+\"/>";
			echo "</a></p>"._HTML_FIN_LIGNE;
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_ADMIN))) {
			echo "<p class=\"partage_social\">";
			echo "<img src=\""._PHP_PATH_ROOT."images/facebook".$suffixe_image."\" alt=\"Partager sur Facebook\"/>&nbsp;";
			echo "<img src=\""._PHP_PATH_ROOT."images/twitter".$suffixe_image."\" alt=\"Partager sur Twitter\"/>&nbsp;";
			echo "<img src=\""._PHP_PATH_ROOT."images/google-plus".$suffixe_image."\" alt=\"Partager sur Google+\"/>";
			echo "</p>"._HTML_FIN_LIGNE;
		}
	}
}