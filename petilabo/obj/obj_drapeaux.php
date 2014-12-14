<?php

class obj_drapeau {
	private $langue = null;
	private $nom = null;
	private $position = 0;
	private $href = 0;
	
	public function __construct($langue, $nom, $position, $href) {
		$this->langue = $langue;
		$this->nom = $nom;
		$this->position = $position;
		$this->href = $href;
	}

	public function afficher($mode, $actif) {
		if (!(strcmp($mode, _PETILABO_MODE_SITE))) {
			if ($actif) {
				echo "<a class=\"drapeau\" href=\"".$this->href."\" title=\"".$this->nom."\" style=\"background-position:".$this->position."\">".$this->langue."</a>"._HTML_FIN_LIGNE;
			}
			else {
				echo "<a class=\"drapeau_inactif\" style=\"background-position:".$this->position."\">&nbsp;</a>"._HTML_FIN_LIGNE;
			}
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_ADMIN))) {
			$classe = ($actif)?"drapeau":"drapeau_inactif";
			echo "<p class=\"".$classe."\" style=\"margin:0 3px 0 0!important;background-position:".$this->position."\">&nbsp;</p>"._HTML_FIN_LIGNE;
		}
	}
	
	public function get_langue() {return $this->langue;}
}

class obj_drapeaux extends obj_html {
	private $obj_texte = null;
	private $alignement = null;
	private $is_multilingue = true;
	private $tab_drapeaux = array();

	public function __construct(&$obj_texte, $alignement, $is_multilingue) {
		$this->obj_texte = $obj_texte;
		$this->alignement = $alignement;
		$this->is_multilingue = $is_multilingue;			
	}
	
	public function ajouter_drapeau($langue, $nom, $pos, $href) {
		$this->tab_drapeaux[] = new obj_drapeau($langue, $nom, $pos, $href);
	}

	public function afficher($mode, $langue) {
		if ((!(strcmp($mode, _PETILABO_MODE_SITE))) || (!(strcmp($mode, _PETILABO_MODE_ADMIN)))){
			$classe = "wrap_drapeau";
			$classe .= " ".$this->extraire_classe_alignement($this->alignement);
			echo "<div class=\"".$classe."\">"._HTML_FIN_LIGNE;
			foreach ($this->tab_drapeaux as $obj_drapeau) {
				if ($obj_drapeau) {
					if (!($this->is_multilingue)) {
						$langue = $obj_drapeau->get_langue();
						$actif = (strcmp($langue, $this->obj_texte->get_langue_par_defaut()))?false:true;
					} else {$actif = true;}
					$obj_drapeau->afficher($mode, $actif);
				}
			}
			echo "</div>"._HTML_FIN_LIGNE;
		}
	}

}