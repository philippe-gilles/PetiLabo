<?php
	
class obj_formulaire extends obj_html {
	private $obj_texte = null;
	private $style = null;

	public function __construct(&$obj_texte, $style) {
		$this->obj_texte = $obj_texte;
		$this->style = $style;
	}

	public function afficher($mode, $langue, $style_p = null) {
		if (!(strcmp($mode, _PETILABO_MODE_SITE))) {
			$this->afficher_champs(true, $langue, $style_p);
		}
		elseif (!(strcmp($mode, _PETILABO_MODE_ADMIN))) {
			$this->afficher_champs(false, $langue, $style_p);
		}
	}

	private function afficher_champs($actif, $langue, $style_p) {
		$nom = $this->obj_texte->get_label_nom($langue);
		$prenom = $this->obj_texte->get_label_prenom($langue);
		$tel = $this->obj_texte->get_label_tel($langue);
		$email = $this->obj_texte->get_label_email($langue);
		$message = $this->obj_texte->get_label_message($langue);
		$captcha = $this->obj_texte->get_label_captcha($langue);
		$envoyer = $this->obj_texte->get_label_envoyer($langue);
		
		if (strlen($style_p) > 0) {$style_p = " "._CSS_PREFIXE_TEXTE.$style_p;}
		echo "<div class=\"formulaire_cadre\">"._HTML_FIN_LIGNE;
		echo "<form id=\"id_form_contact\" method=\"post\" action=\""._PHP_PATH_INCLUDE."mail.php\">"._HTML_FIN_LIGNE;
		// Champs du formulaire
		$this->ecrire_contact_texte($style_p, $this->style, true, $nom, "nom", false, false, $actif);
		$this->ecrire_contact_texte($style_p, $this->style, true, $prenom, "prenom", false, false, $actif);
		$this->ecrire_contact_texte($style_p, $this->style, false, $tel, "tel", true, false, $actif);
		$this->ecrire_contact_texte($style_p, $this->style, true, $email, "email", false, true, $actif);
		$this->ecrire_contact_message($style_p, $this->style, true, $message, "message", $actif);
		// Captcha
		echo "<p class=\"paragraphe".$style_p."\">"._HTML_FIN_LIGNE;
		echo "<label class=\"champ_label\" for=\"id_captcha\">".$captcha."<span class=\"champ_obligatoire\">&nbsp;(*)</span></label>"._HTML_FIN_LIGNE;
		$classe = "champ_tres_court";
		$disabled = ($actif)?"":" disabled=\"disabled\"";
		if (strlen($this->style) > 0) {$classe .= " "._CSS_PREFIXE_FORMULAIRE_CHAMP.$this->style;}
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
}