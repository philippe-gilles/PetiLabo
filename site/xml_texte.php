<?php
inclure_inc("const");
inclure_site("xml_const", "xml_struct");

define("_MODULE_ACTU_TITRE", "titre_actu_");
define("_MODULE_ACTU_SOUS_TITRE", "sous_titre_actu_");
define("_MODULE_ACTU_RESUME", "resume_actu_");
define("_MODULE_ACTU_TEXTE", "texte_actu_");

class xml_texte {
	// Constantes
	const langue_par_defaut = _LANGUE_FR;

	// Propriétés
	private $tab_langues = array(_LANGUE_DE, _LANGUE_EN, _LANGUE_ES, _LANGUE_FR, _LANGUE_IT, _LANGUE_NL, _LANGUE_PT);
	private $nom_langues = array(
		_LANGUE_DE=>"Deutsch",
		_LANGUE_EN=>"English", 
		_LANGUE_ES=>"Español",
		_LANGUE_FR=>"Français",
		_LANGUE_IT=>"Italiano",
		_LANGUE_NL=>"Nederlands",
		_LANGUE_PT=>"Português");
	private $css_pos_langues = array(
		_LANGUE_DE=>"0 0",
		_LANGUE_EN=>"-40px 0", 
		_LANGUE_ES=>"-80px 0",
		_LANGUE_FR=>"-120px 0",
		_LANGUE_IT=>"-160px 0",
		_LANGUE_NL=>"-200px 0",
		_LANGUE_PT=>"-240px 0");
	private $tab_precedent = array(
		_LANGUE_DE=>"Bisherige",
		_LANGUE_EN=>"Previous", 
		_LANGUE_ES=>"Anterior",
		_LANGUE_FR=>"Précédent",
		_LANGUE_IT=>"Precedente",
		_LANGUE_NL=>"Vorige",
		_LANGUE_PT=>"Anterior");
	private $tab_suivant = array(
		_LANGUE_DE=>"Nächste",
		_LANGUE_EN=>"Next", 
		_LANGUE_ES=>"Siguiente",
		_LANGUE_FR=>"Suivant",
		_LANGUE_IT=>"Successivo",
		_LANGUE_NL=>"Volgende",
		_LANGUE_PT=>"Seguinte");
	private $tab_fermer = array(
		_LANGUE_DE=>"Schließen",
		_LANGUE_EN=>"Close", 
		_LANGUE_ES=>"Cerrar",
		_LANGUE_FR=>"Fermer",
		_LANGUE_IT=>"Chiudere",
		_LANGUE_NL=>"Sluiten",
		_LANGUE_PT=>"Fechar");
	private $tab_nom = array(
		_LANGUE_DE=>"Nachnamen",
		_LANGUE_EN=>"Name", 
		_LANGUE_ES=>"Apellidos",
		_LANGUE_FR=>"Nom",
		_LANGUE_IT=>"Cognome",
		_LANGUE_NL=>"Achternaam",
		_LANGUE_PT=>"Apelido");
	private $tab_prenom = array(
		_LANGUE_DE=>"Vorname",
		_LANGUE_EN=>"First name", 
		_LANGUE_ES=>"Nombre",
		_LANGUE_FR=>"Prénom",
		_LANGUE_IT=>"Nome",
		_LANGUE_NL=>"Voornaam",
		_LANGUE_PT=>"Nome");
	private $tab_tel = array(
		_LANGUE_DE=>"Telefonnummer",
		_LANGUE_EN=>"Phone number", 
		_LANGUE_ES=>"Teléfono",
		_LANGUE_FR=>"Téléphone",
		_LANGUE_IT=>"Telefono",
		_LANGUE_NL=>"Telefoonnummer",
		_LANGUE_PT=>"Telefone");
	private $tab_email = array(
		_LANGUE_DE=>"E-mail",
		_LANGUE_EN=>"E-mail", 
		_LANGUE_ES=>"E-mail",
		_LANGUE_FR=>"E-mail",
		_LANGUE_IT=>"E-mail",
		_LANGUE_NL=>"E-mail",
		_LANGUE_PT=>"E-mail");
	private $tab_message = array(
		_LANGUE_DE=>"Nachricht",
		_LANGUE_EN=>"Message", 
		_LANGUE_ES=>"Mensaje",
		_LANGUE_FR=>"Message",
		_LANGUE_IT=>"Messaggio",
		_LANGUE_NL=>"Bericht",
		_LANGUE_PT=>"Mensagem");
	private $tab_captcha = array(
		_LANGUE_DE=>"Sicherheitscode",
		_LANGUE_EN=>"Security code", 
		_LANGUE_ES=>"Código de seguridad",
		_LANGUE_FR=>"Code de sécurité",
		_LANGUE_IT=>"Codice di sicurezza",
		_LANGUE_NL=>"Veiligheidscode",
		_LANGUE_PT=>"Código de segurança");
	private $tab_envoyer = array(
		_LANGUE_DE=>"Senden",
		_LANGUE_EN=>"Send", 
		_LANGUE_ES=>"Enviar",
		_LANGUE_FR=>"Envoyer",
		_LANGUE_IT=>"Invia",
		_LANGUE_NL=>"Verzenden",
		_LANGUE_PT=>"Enviar");
	private $tab_mentions = array(
		_LANGUE_DE=>"Impressum",
		_LANGUE_EN=>"Legal notice", 
		_LANGUE_ES=>"Aviso legal",
		_LANGUE_FR=>"Mentions légales",
		_LANGUE_IT=>"Colophon",
		_LANGUE_NL=>"Afdruk",
		_LANGUE_PT=>"Aviso legal");
	private $tab_credits = array(
		_LANGUE_DE=>"Credits",
		_LANGUE_EN=>"Credits", 
		_LANGUE_ES=>"Credits",
		_LANGUE_FR=>"Crédits",
		_LANGUE_IT=>"Credits",
		_LANGUE_NL=>"Credits",
		_LANGUE_PT=>"Credits");
	private $tab_plan = array(
		_LANGUE_DE=>"Web-Site Karte",
		_LANGUE_EN=>"Site map", 
		_LANGUE_ES=>"Mapa del sitio",
		_LANGUE_FR=>"Plan du site",
		_LANGUE_IT=>"Mappa del sito",
		_LANGUE_NL=>"Site map",
		_LANGUE_PT=>"Plano do sítio");
	private $tab_webmaster = array(
		_LANGUE_DE=>"PetiLabo",
		_LANGUE_EN=>"PetiLabo", 
		_LANGUE_ES=>"PetiLabo",
		_LANGUE_FR=>"PetiLabo",
		_LANGUE_IT=>"PetiLabo",
		_LANGUE_NL=>"PetiLabo",
		_LANGUE_PT=>"PetiLabo");
	private $tab_pied_de_page = array(
		_LANGUE_DE=>"Fuß Seite",
		_LANGUE_EN=>"Footer", 
		_LANGUE_ES=>"Pie de página",
		_LANGUE_FR=>"Pied de page",
		_LANGUE_IT=>"Piede di pagina",
		_LANGUE_NL=>"Voet van bladzijde",
		_LANGUE_PT=>"Pé de página");
	private $tab_social = array(
		_LANGUE_DE=>"Und über soziale Netzwerke",
		_LANGUE_EN=>"And on social networks", 
		_LANGUE_ES=>"Y en las redes sociales",
		_LANGUE_FR=>"Et aussi sur les réseaux sociaux",
		_LANGUE_IT=>"Ed anche sulle reti sociali",
		_LANGUE_NL=>"En op sociale netwerken",
		_LANGUE_PT=>"E também sobre as redes sociais");
	private $tab_accesskey = array(
		_LANGUE_DE=>"Tastenkombination",
		_LANGUE_EN=>"Keyboard shortcut", 
		_LANGUE_ES=>"Tecla aceleradora",
		_LANGUE_FR=>"Raccourci clavier",
		_LANGUE_IT=>"Scorciatoia da tastiera",
		_LANGUE_NL=>"Functietoets",
		_LANGUE_PT=>"Atalho de teclado");
	private $tab_mois = array(
		_LANGUE_DE=>array("", "Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"),
		_LANGUE_EN=>array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"), 
		_LANGUE_ES=>array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"),
		_LANGUE_FR=>array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"),
		_LANGUE_IT=>array("", "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre "),
		_LANGUE_NL=>array("", "Januari", "Februari", "Maart", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "December"),
		_LANGUE_PT=>array("", "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"));
	private $tab_semaine = array(
		_LANGUE_DE=>array("Mo", "Di", "Mi", "Do", "Fr", "Sa", "So"),
		_LANGUE_EN=>array("Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"), 
		_LANGUE_ES=>array("Lu", "Ma", "Mi", "Ju", "Vi", "Sá", "Do"),
		_LANGUE_FR=>array("Lu", "Ma", "Me", "Je", "Ve", "Sa", "Di"),
		_LANGUE_IT=>array("Lu", "Ma", "Me", "Gi", "Ve", "Sa", "Do"),
		_LANGUE_NL=>array("Ma", "Di", "Wo", "Do", "Vr", "Za", "Zo"),
		_LANGUE_PT=>array("Seg", "Te", "Qua", "Qui", "Sex", "Sá", "Do"));
	private $tab_statut_resa = array(
		_LANGUE_DE=>array("Vorhanden", "Reserviet", "Besetzt", "Geschlossen"),
		_LANGUE_EN=>array("Available", "Booked", "Occupied", "Closed"), 
		_LANGUE_ES=>array("Disponible", "Reservado", "Ocupado", "Cerrado"),
		_LANGUE_FR=>array("Libre", "Réservé", "Occupé", "Fermé"),
		_LANGUE_IT=>array("Disponibile", "Prenotato", "Occupato", "Chiuso"),
		_LANGUE_NL=>array("Beschikbaar", "Geboekt", "Bezet", "Gesloten"),
		_LANGUE_PT=>array("Disponível", "Reservado", "Ocupado", "Fechado"));
	private $tab_appeler_tel = array(
		_LANGUE_DE=>"Wahltelefon",
		_LANGUE_EN=>"Dial", 
		_LANGUE_ES=>"Marque el",
		_LANGUE_FR=>"Composer le",
		_LANGUE_IT=>"Comporre il",
		_LANGUE_NL=>"Kies het",
		_LANGUE_PT=>"Disque o");
	private $tab_appeler_skype = array(
		_LANGUE_DE=>"Rufen mit Skype",
		_LANGUE_EN=>"Call with Skype", 
		_LANGUE_ES=>"Llamar con Skype",
		_LANGUE_FR=>"Appeler avec Skype",
		_LANGUE_IT=>"Chiama con Skype",
		_LANGUE_NL=>"Bellen met Skype",
		_LANGUE_PT=>"Chamada com Skype");
	private $xml_struct = null;
	private $liste_langues = array();
	private $textes = array();
	private $sources = array();
	private $index_xml = array();

	public function ouvrir($source, $nom, $suffixe = null) {
		// Ouverture du fichier XML
		$this->xml_struct = new xml_struct();
		$ret = $this->xml_struct->ouvrir($nom);
		if ($ret) {
			$nb_textes = $this->xml_struct->compter_elements(_TEXTE_TEXTE);
			for ($cpt = 0;$cpt < $nb_textes; $cpt++) {
				$this->xml_struct->pointer_sur_balise(_TEXTE_TEXTE);
				$nom = $this->xml_struct->lire_n_attribut(_TEXTE_ATTR_NOM, $cpt);
				if (strlen($nom) > 0) {
					$texte = $this->xml_struct->lire_n($cpt);
					$key = (strlen($suffixe) > 0)?$nom."_".$suffixe:$nom;
					$this->textes[$key] = $texte;
					$this->sources[$key] = $source;
					$this->index_xml[$key] = $cpt;
				}
				$this->xml_struct->pointer_sur_origine();
			}
		}
		return $ret;
	}
	public function enregistrer($fichier) {
		$this->xml_struct->enregistrer($fichier);
	}
	public function set_texte($nom, $texte) {
		// Mise à jour dans le tableau de la classe
		$this->textes[$nom] = $texte;
		
		// Mise à jour dans la structure XML associée
		$index = (int) $this->index_xml[$nom];
		$this->xml_struct->pointer_sur_balise_n(_TEXTE_TEXTE, $index);
		$this->xml_struct->set_valeur($texte);
		$this->xml_struct->pointer_sur_origine();
	}
	public function existe_texte($nom) {
		$existe = array_key_exists($nom, $this->textes);
		return $existe;
	}
	public function get_texte($nom, $langue) {
		$ret = isset($this->textes[$nom])?$this->textes[$nom]:null;
		// Traduction dans la langue en cours
		if (strlen($ret) > 0) {
			$ret = $this->parser_texte($ret, $langue);
			$ret = $this->parser_ancres($ret);
		}
		return $ret;
	}
	public function get_source($nom) {return $this->sources[$nom];}
	
	public function ajouter_langue($param) {
		$existe = array_key_exists($param, $this->nom_langues);
		if ($existe) {
			$this->liste_langues[] = $param;
		}
		return $existe;
	}
	public function verifier_langue($param) {
		$ret = self::langue_par_defaut;
		if (strlen($param) == 2) {
			if (in_array($param, $this->liste_langues)) {
				$ret = $param;
			}
		}
		return $ret;
	}
	public function get_label_precedent($langue) {return $this->tab_precedent[$langue];}
	public function get_label_suivant($langue) {return $this->tab_suivant[$langue];}
	public function get_label_fermer($langue) {return $this->tab_fermer[$langue];}
	public function get_label_nom($langue) {return $this->tab_nom[$langue];}
	public function get_label_prenom($langue) {return $this->tab_prenom[$langue];}
	public function get_label_tel($langue) {return $this->tab_tel[$langue];}
	public function get_label_email($langue) {return $this->tab_email[$langue];}
	public function get_label_message($langue) {return $this->tab_message[$langue];}
	public function get_label_captcha($langue) {return $this->tab_captcha[$langue];}
	public function get_label_envoyer($langue) {return $this->tab_envoyer[$langue];}
	public function get_label_mentions($langue) {return $this->tab_mentions[$langue];}
	public function get_label_credits($langue) {return $this->tab_credits[$langue];}
	public function get_label_plan($langue) {return $this->tab_plan[$langue];}
	public function get_label_webmaster($langue) {return $this->tab_webmaster[$langue];}
	public function get_label_pied_de_page($langue) {return $this->tab_pied_de_page[$langue];}
	public function get_label_social($langue) {return $this->tab_social[$langue];}
	public function get_label_accesskey($langue) {return $this->tab_accesskey[$langue];}
	public function get_tab_mois($langue) {return $this->tab_mois[$langue];}
	public function get_tab_semaine($langue) {return $this->tab_semaine[$langue];}
	public function get_tab_statut_resa($langue) {return $this->tab_statut_resa[$langue];}
	public function get_label_appeler_tel($langue) {return $this->tab_appeler_tel[$langue];}
	public function get_label_appeler_skype($langue) {return $this->tab_appeler_skype[$langue];}
	
	public function get_id_titre_actu($no) {return _MODULE_ACTU_TITRE.$no;}
	public function get_id_sous_titre_actu($no) {return _MODULE_ACTU_SOUS_TITRE.$no;}
	public function get_id_resume_actu($no) {return _MODULE_ACTU_RESUME.$no;}
	public function get_id_texte_actu($no) {return _MODULE_ACTU_TEXTE.$no;}

	public function get_titre_actu($no, $langue) {
		$nom = $this->get_id_titre_actu($no);
		$texte = $this->get_texte($nom, $langue);
		return $texte;
	}
	public function get_sous_titre_actu($no, $langue) {
		$nom = $this->get_id_sous_titre_actu($no);
		$texte = $this->get_texte($nom, $langue);
		return $texte;
	}
	public function get_resume_actu($no, $langue) {
		$nom = $this->get_id_resume_actu($no);
		$texte = $this->get_texte($nom, $langue);
		return $texte;
	}
	public function get_texte_actu($no, $langue) {
		$nom = $this->get_id_texte_actu($no);
		$texte = $this->get_texte($nom, $langue);
		return $texte;
	}

	public function parser_texte($texte, $langue) {
		$ret = "";$trim = trim($texte);
		// On recherche le prefixe de la langue dans le texte
		$prefixe = "{".$langue."}";
		$pos = stripos($trim, $prefixe);
		if ($pos === false) {
			// La langue est absente : on utilise la langue par défaut
			$ret = $this->parser_texte_absent($trim);
		}
		else {
			// La langue est présente : on récupère la portion de texte qui convient
			$pos_debut = $pos + strlen($prefixe);
			$pos_suite = stripos($trim, "{", $pos_debut);
			if ($pos_suite === false) {
				// C'est la dernière portion de texte qui est concernée
				$ret = substr($trim, $pos_debut);
			}
			else {
				// C'est une portion de texte qui précède une autre langue
				$ret = substr($trim, $pos_debut, $pos_suite-$pos_debut);
			}
		}
		return trim($ret);
	}
	public function parser_ancres($texte) {
		$ancre_http = "<a href=\"http";$target_http = "<a target=\"_blank\" href=\"http";
		$ret = str_replace($ancre_http, $target_http, $texte);
		return $ret;
	}
	public function get_tab_langues() {return $this->tab_langues;}
	public function get_langue_par_defaut() {return self::langue_par_defaut;}
	public function get_nb_langues() {return count($this->liste_langues);}
	public function get_langue($index) {return $this->liste_langues[$index];}
	public function get_nom($langue) {return $this->nom_langues[$langue];}
	public function get_position($langue) {return $this->css_pos_langues[$langue];}
	public function strip_tags_attributes($texte) {
		// Traitement des balises
		$aAllowedTags = array("<strike>", "<u>", "<b>", "<i>", "<a>", "<br>", "<br/>", "<span>", "<font>", "<sup>");
		$aDisabledAttributes = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavaible', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragdrop', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterupdate', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmoveout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
		$ret = preg_replace('/<(.*?)>/ie', "'<' . preg_replace(array('/javascript:[^\"\']*/i', '/(" . implode('|', $aDisabledAttributes) . ")[ \\t\\n]*=[ \\t\\n]*[\"\'][^\"\']*[\"\']/i', '/\s+/'), array('', '', ' '), stripslashes('\\1')) . '>'", strip_tags($texte, implode('', $aAllowedTags)));
		// Traitement des guillemets en dehors des balises
		$tag = false;$cpt = 0;$long = strlen($ret);$ret_noquotes = "";
		for ($cpt = 0;$cpt < $long;$cpt++) {
			$car = substr($ret, $cpt, 1);
			if (!(strcmp($car, "<"))) {$tag = true;}
			elseif (!(strcmp($car, ">"))) {$tag = false;}
			elseif ((!(strcmp($car, "\""))) && (!($tag))) {$car = "&#34;";}
			$ret_noquotes .= $car;
		}
		return $ret_noquotes;
	}
	public function secure_xml($texte) {
		$ret = htmlentities($texte, ENT_COMPAT | ENT_XHTML, "UTF-8");
		return $ret;
	}
	private function parser_texte_absent($texte) {
		// On recherche le prefixe de la langue par défaut
		$prefixe = "{".self::langue_par_defaut."}";
		$pos = stripos($texte, $prefixe);
		if ($pos === false) {
			// Le préfixe est absent donc on le rajoute au début
			$texte = $prefixe.$texte;
		}
		// Il suffit de rappeler récursivement la méthode parser_texte
		// en utilisant la langue par défaut qui sera forcément trouvée
		$ret = $this->parser_texte($texte, self::langue_par_defaut);
		
		return $ret;
	}
}