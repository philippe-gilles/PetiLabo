<?php
	
class obj_mentions_legales extends obj_html {
	private $obj_texte = null;
	private $chapitre_mentions = false;
	private $chapitre_protection = false;
	private $chapitre_cookies = false;
	private $chapitre_copyright = false;
	private $sections_chapitre = false;
	private $nom_editeur = null;
	private $adr_editeur = null;
	private $tel_editeur = null;
	private $rcs_editeur = null;
	private $siret_editeur = null;
	private $nom_redacteur = null;
	private $nom_hebergeur = null;
	private $nom_site = null;
	private $no_cnil = null;

	public function __construct(&$obj_texte, $chapitre_mentions, $chapitre_protection, $chapitre_cookies, $chapitre_copyright, $sections_chapitre) {
		$this->obj_texte = $obj_texte;
		$this->chapitre_mentions = $chapitre_mentions;
		$this->chapitre_protection = $chapitre_protection;
		$this->chapitre_cookies = $chapitre_cookies;
		$this->chapitre_copyright = $chapitre_copyright;
		$this->sections_chapitre = $sections_chapitre;
	}
	
	public function ajouter_editeur($nom_editeur, $adr_editeur, $tel_editeur, $rcs_editeur, $siret_editeur) {
		$this->nom_editeur = $nom_editeur;
		$this->adr_editeur = $adr_editeur;
		$this->tel_editeur = $tel_editeur;
		$this->rcs_editeur = $rcs_editeur;
		$this->siret_editeur = $siret_editeur;
	}
	
	public function ajouter_redacteur($nom_redacteur) {
		$this->nom_redacteur = $nom_redacteur;
	}
	
	public function ajouter_hebergeur($nom_hebergeur) {
		$this->nom_hebergeur = $nom_hebergeur;
	}

	public function ajouter_cnil($no_cnil) {
		$this->no_cnil = $no_cnil;
	}
	
	public function ajouter_site($nom_site) {
		$this->nom_site = $nom_site;
	}

	public function afficher($mode, $langue, $style_p = null) {
		$langue_affichee = (strcmp($mode, _PETILABO_MODE_SITE))?$this->obj_texte->get_langue_par_defaut():$langue;
		$classe  = (strlen($style_p) > 0)?_CSS_PREFIXE_TEXTE.$style_p:"";
		// Section mentions légales
		if ($this->chapitre_mentions) {
			if ($this->sections_chapitre) {$this->ecrire_section("legal_section_legale", $langue_affichee, $classe);}
			$this->ecrire_legal_mentions($mode, $langue, $classe);
		}
		// Section protection
		if ($this->chapitre_protection) {
			if ($this->sections_chapitre) {$this->ecrire_section("legal_section_protection", $langue_affichee, $classe);}
			$this->ecrire_legal_protection($mode, $langue, $classe);
		}
		// Section cookies
		if ($this->chapitre_cookies) {
			if ($this->sections_chapitre) {$this->ecrire_section("legal_section_cookies", $langue_affichee, $classe);}
			$this->ecrire_legal_cookies($mode, $langue, $classe);
		}
		// Section copyright
		if ($this->chapitre_copyright) {
			if ($this->sections_chapitre) {$this->ecrire_section("legal_section_copyright", $langue_affichee, $classe);}
			$this->ecrire_legal_copyright("legal_propriete", "legal_reproduction", "legal_infraction");
		}
	}
	
	private function ecrire_legal_mentions($mode, $langue, $classe) {
		$le_site = $this->obj_texte->get_texte("legal_le_site", $langue);
		$est_edite = $this->obj_texte->get_texte("legal_est_edite", $langue);
		echo "<br />"._HTML_FIN_LIGNE;
		$this->ecrire_legal($le_site." <strong>".$this->nom_site."</strong> ".$est_edite."&nbsp;:", $classe);
		echo "<br />"._HTML_FIN_LIGNE;
		if (strlen($this->nom_editeur) > 0) {$this->ecrire_legal("<strong>".$this->nom_editeur."</strong>", $classe);}
		if (strlen($this->adr_editeur) > 0) {$this->ecrire_legal($this->adr_editeur, $classe);}
		if (strlen($this->tel_editeur) > 0) {$this->ecrire_legal("Tel&nbsp;:&nbsp;".$this->tel_editeur, $classe);}
		if (strlen($this->siret_editeur) > 0) {$this->ecrire_legal("N° SIRET&nbsp;:&nbsp;".$this->siret_editeur, $classe);}
		if (strlen($this->rcs_editeur) > 0) {$this->ecrire_legal("RCS&nbsp;:&nbsp;".$this->rcs_editeur, $classe);}
		echo "<br />"._HTML_FIN_LIGNE;
		if ($this->nom_redacteur) {
			$responsable = $this->obj_texte->get_texte("legal_responsable", $langue);
			$this->ecrire_legal("<strong>".$responsable."</strong>&nbsp;:&nbsp;".$this->nom_redacteur, $classe);
			echo "<br />"._HTML_FIN_LIGNE;
		}
		if ($this->nom_hebergeur) {
			$hebergement = $this->obj_texte->get_texte("legal_hebergement", $langue);
			$this->ecrire_legal("<strong>".$hebergement."</strong>&nbsp;:&nbsp;".$this->nom_hebergeur, $classe);
			echo "<br />"._HTML_FIN_LIGNE;
		}
	}
	
	private function ecrire_legal_protection($mode, $langue, $classe) {
		$protection = $this->obj_texte->get_texte("legal_protection", $langue);
		echo "<br />"._HTML_FIN_LIGNE;
		$this->ecrire_legal($protection, $classe);
		if (strlen($this->no_cnil) > 0) {
			echo "<br />"._HTML_FIN_LIGNE;
			$le_site = $this->obj_texte->get_texte("legal_le_site", $langue);
			$cnil = $this->obj_texte->get_texte("legal_cnil", $langue);
			$this->ecrire_legal($le_site." <strong>".$this->nom_site."</strong> ".$cnil.($this->no_cnil).".", $classe);
		}
		echo "<br />"._HTML_FIN_LIGNE;
	}
	
	private function ecrire_legal_cookies($mode, $langue, $classe) {
		$cookies_petilabo = $this->obj_texte->get_texte("legal_cookies_petilabo", $langue);
		echo "<br />"._HTML_FIN_LIGNE;
		$this->ecrire_legal($cookies_petilabo, $classe);
		$le_site = $this->obj_texte->get_texte("legal_le_site", $langue);
		$cookies_site = $this->obj_texte->get_texte("legal_cookies_site", $langue);
		echo "<br />"._HTML_FIN_LIGNE;
		$this->ecrire_legal($le_site." <strong>".$this->nom_site."</strong> ".$cookies_site, $classe);
		echo "<br />"._HTML_FIN_LIGNE;
	}
	
	private function ecrire_legal_copyright($mode, $langue, $classe) {
		echo "<br />"._HTML_FIN_LIGNE;
		if (strlen($this->nom_editeur) > 0) {
			$propriete = $this->obj_texte->get_texte("legal_propriete", $langue);
			$this->ecrire_legal("<strong>".$this->nom_editeur."</strong> ".$propriete, $classe);
			echo "<br />"._HTML_FIN_LIGNE;
			$reproduction = $this->obj_texte->get_texte("legal_reproduction", $langue);
			$this->ecrire_legal($reproduction." <strong>".$this->nom_editeur."</strong>.", $classe);
			echo "<br />"._HTML_FIN_LIGNE;
			$infraction = $this->obj_texte->get_texte("legal_infraction", $langue);
			$this->ecrire_legal($infraction, $classe);
			echo "<br />"._HTML_FIN_LIGNE;
		}
	}
	
	private function ecrire_legal($texte, $classe) {
		echo "<p class=\"paragraphe credit_texte ".$classe."\">".$texte."</p>"._HTML_FIN_LIGNE;
	}

	private function ecrire_section($id_texte, $langue, $classe) {
		$texte = $this->obj_texte->get_texte($id_texte, $langue);
		echo "<p class=\"titre_legal ".$classe."\"><span class=\"titre_legal\">".$texte."</span></p>"._HTML_FIN_LIGNE;
	}
}