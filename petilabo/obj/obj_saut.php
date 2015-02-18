<?php
	
class obj_saut extends obj_html {
	private $hauteur = 0.0;

	public function __construct($hauteur) {
		$this->hauteur = (float) $hauteur;
	}

	public function afficher($mode, $langue) {
		if (!(strcmp($mode, _PETILABO_MODE_SITE))) {
			echo "<p class=\"saut_de_ligne\" style=\"font-size:".$this->hauteur."em;\"><br /></p>"._HTML_FIN_LIGNE;
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_ADMIN))) {
			echo "<p class=\"saut_de_ligne\" style=\"font-size:".$this->hauteur."em;\"><br /></p>"._HTML_FIN_LIGNE;
		}
	}
}