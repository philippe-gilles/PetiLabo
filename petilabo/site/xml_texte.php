<?php

define("_MODULE_ACTU_TITRE", "titre_actu_");
define("_MODULE_ACTU_SOUS_TITRE", "sous_titre_actu_");
define("_MODULE_ACTU_RESUME", "resume_actu_");
define("_MODULE_ACTU_TEXTE", "texte_actu_");

class xml_texte {
	// Constantes
	const langue_par_defaut = _LANGUE_FR;

	// Propriétés statiques
	private static $tab_icones = array("glass"=>"000","music"=>"001","search"=>"002","envelope-o"=>"003","heart"=>"004","star"=>"005","star-o"=>"006","user"=>"007","film"=>"008","th-large"=>"009","th"=>"00a","th-list"=>"00b","check"=>"00c","remove"=>"00d","close"=>"00d","times"=>"00d","search-plus"=>"00e","search-minus"=>"010","power-off"=>"011","signal"=>"012","gear"=>"013","cog"=>"013","trash-o"=>"014","home"=>"015","file-o"=>"016","clock-o"=>"017","road"=>"018","download"=>"019","arrow-circle-o-down"=>"01a","arrow-circle-o-up"=>"01b","inbox"=>"01c","play-circle-o"=>"01d","rotate-right"=>"01e","repeat"=>"01e","refresh"=>"021","list-alt"=>"022","lock"=>"023","flag"=>"024","headphones"=>"025","volume-off"=>"026","volume-down"=>"027","volume-up"=>"028","qrcode"=>"029","barcode"=>"02a","tag"=>"02b","tags"=>"02c","book"=>"02d","bookmark"=>"02e","print"=>"02f","camera"=>"030","font"=>"031","bold"=>"032","italic"=>"033","text-height"=>"034","text-width"=>"035","align-left"=>"036","align-center"=>"037","align-right"=>"038","align-justify"=>"039","list"=>"03a","dedent"=>"03b","outdent"=>"03b","indent"=>"03c","video-camera"=>"03d","photo"=>"03e","image"=>"03e","picture-o"=>"03e","pencil"=>"040","map-marker"=>"041","adjust"=>"042","tint"=>"043","edit"=>"044","pencil-square-o"=>"044","share-square-o"=>"045","check-square-o"=>"046","arrows"=>"047","step-backward"=>"048","fast-backward"=>"049","backward"=>"04a","play"=>"04b","pause"=>"04c","stop"=>"04d","forward"=>"04e","fast-forward"=>"050","step-forward"=>"051","eject"=>"052","chevron-left"=>"053","chevron-right"=>"054","plus-circle"=>"055","minus-circle"=>"056","times-circle"=>"057","check-circle"=>"058","question-circle"=>"059","info-circle"=>"05a","crosshairs"=>"05b","times-circle-o"=>"05c","check-circle-o"=>"05d","ban"=>"05e","arrow-left"=>"060","arrow-right"=>"061","arrow-up"=>"062","arrow-down"=>"063","mail-forward"=>"064","share"=>"064","expand"=>"065","compress"=>"066","plus"=>"067","minus"=>"068","asterisk"=>"069","exclamation-circle"=>"06a","gift"=>"06b","leaf"=>"06c","fire"=>"06d","eye"=>"06e","eye-slash"=>"070","warning"=>"071","exclamation-triangle"=>"071","plane"=>"072","calendar"=>"073","random"=>"074","comment"=>"075","magnet"=>"076","chevron-up"=>"077","chevron-down"=>"078","retweet"=>"079","shopping-cart"=>"07a","folder"=>"07b","folder-open"=>"07c","arrows-v"=>"07d","arrows-h"=>"07e","bar-chart-o"=>"080","bar-chart"=>"080","twitter-square"=>"081","facebook-square"=>"082","camera-retro"=>"083","key"=>"084","gears"=>"085","cogs"=>"085","comments"=>"086","thumbs-o-up"=>"087","thumbs-o-down"=>"088","star-half"=>"089","heart-o"=>"08a","sign-out"=>"08b","linkedin-square"=>"08c","thumb-tack"=>"08d","external-link"=>"08e","sign-in"=>"090","trophy"=>"091","github-square"=>"092","upload"=>"093","lemon-o"=>"094","phone"=>"095","square-o"=>"096","bookmark-o"=>"097","phone-square"=>"098","twitter"=>"099","facebook-f"=>"09a","facebook"=>"09a","github"=>"09b","unlock"=>"09c","credit-card"=>"09d","feed"=>"09e","rss"=>"09e","hdd-o"=>"0a0","bullhorn"=>"0a1","bell"=>"0f3","certificate"=>"0a3","hand-o-right"=>"0a4","hand-o-left"=>"0a5","hand-o-up"=>"0a6","hand-o-down"=>"0a7","arrow-circle-left"=>"0a8","arrow-circle-right"=>"0a9","arrow-circle-up"=>"0aa","arrow-circle-down"=>"0ab","globe"=>"0ac","wrench"=>"0ad","tasks"=>"0ae","filter"=>"0b0","briefcase"=>"0b1","arrows-alt"=>"0b2","group"=>"0c0","users"=>"0c0","chain"=>"0c1","link"=>"0c1","cloud"=>"0c2","flask"=>"0c3","cut"=>"0c4","scissors"=>"0c4","copy"=>"0c5","files-o"=>"0c5","paperclip"=>"0c6","save"=>"0c7","floppy-o"=>"0c7","square"=>"0c8","navicon"=>"0c9","reorder"=>"0c9","bars"=>"0c9","list-ul"=>"0ca","list-ol"=>"0cb","strikethrough"=>"0cc","underline"=>"0cd","table"=>"0ce","magic"=>"0d0","truck"=>"0d1","pinterest"=>"0d2","pinterest-square"=>"0d3","google-plus-square"=>"0d4","google-plus"=>"0d5","money"=>"0d6","caret-down"=>"0d7","caret-up"=>"0d8","caret-left"=>"0d9","caret-right"=>"0da","columns"=>"0db","unsorted"=>"0dc","sort"=>"0dc","sort-down"=>"0dd","sort-desc"=>"0dd","sort-up"=>"0de","sort-asc"=>"0de","envelope"=>"0e0","linkedin"=>"0e1","rotate-left"=>"0e2","undo"=>"0e2","legal"=>"0e3","gavel"=>"0e3","dashboard"=>"0e4","tachometer"=>"0e4","comment-o"=>"0e5","comments-o"=>"0e6","flash"=>"0e7","bolt"=>"0e7","sitemap"=>"0e8","umbrella"=>"0e9","paste"=>"0ea","clipboard"=>"0ea","lightbulb-o"=>"0eb","exchange"=>"0ec","cloud-download"=>"0ed","cloud-upload"=>"0ee","user-md"=>"0f0","stethoscope"=>"0f1","suitcase"=>"0f2","bell-o"=>"0a2","coffee"=>"0f4","cutlery"=>"0f5","file-text-o"=>"0f6","building-o"=>"0f7","hospital-o"=>"0f8","ambulance"=>"0f9","medkit"=>"0fa","fighter-jet"=>"0fb","beer"=>"0fc","h-square"=>"0fd","plus-square"=>"0fe","angle-double-left"=>"100","angle-double-right"=>"101","angle-double-up"=>"102","angle-double-down"=>"103","angle-left"=>"104","angle-right"=>"105","angle-up"=>"106","angle-down"=>"107","desktop"=>"108","laptop"=>"109","tablet"=>"10a","mobile-phone"=>"10b","mobile"=>"10b","circle-o"=>"10c","quote-left"=>"10d","quote-right"=>"10e","spinner"=>"110","circle"=>"111","mail-reply"=>"112","reply"=>"112","github-alt"=>"113","folder-o"=>"114","folder-open-o"=>"115","smile-o"=>"118","frown-o"=>"119","meh-o"=>"11a","gamepad"=>"11b","keyboard-o"=>"11c","flag-o"=>"11d","flag-checkered"=>"11e","terminal"=>"120","code"=>"121","mail-reply-all"=>"122","reply-all"=>"122","star-half-empty"=>"123","star-half-full"=>"123","star-half-o"=>"123","location-arrow"=>"124","crop"=>"125","code-fork"=>"126","unlink"=>"127","chain-broken"=>"127","question"=>"128","info"=>"129","exclamation"=>"12a","superscript"=>"12b","subscript"=>"12c","eraser"=>"12d","puzzle-piece"=>"12e","microphone"=>"130","microphone-slash"=>"131","shield"=>"132","calendar-o"=>"133","fire-extinguisher"=>"134","rocket"=>"135","maxcdn"=>"136","chevron-circle-left"=>"137","chevron-circle-right"=>"138","chevron-circle-up"=>"139","chevron-circle-down"=>"13a","html5"=>"13b","css3"=>"13c","anchor"=>"13d","unlock-alt"=>"13e","bullseye"=>"140","ellipsis-h"=>"141","ellipsis-v"=>"142","rss-square"=>"143","play-circle"=>"144","ticket"=>"145","minus-square"=>"146","minus-square-o"=>"147","level-up"=>"148","level-down"=>"149","check-square"=>"14a","pencil-square"=>"14b","external-link-square"=>"14c","share-square"=>"14d","compass"=>"14e","toggle-down"=>"150","caret-square-o-down"=>"150","toggle-up"=>"151","caret-square-o-up"=>"151","toggle-right"=>"152","caret-square-o-right"=>"152","euro"=>"153","eur"=>"153","gbp"=>"154","dollar"=>"155","usd"=>"155","rupee"=>"156","inr"=>"156","cny"=>"157","rmb"=>"157","yen"=>"157","jpy"=>"157","ruble"=>"158","rouble"=>"158","rub"=>"158","won"=>"159","krw"=>"159","bitcoin"=>"15a","btc"=>"15a","file"=>"15b","file-text"=>"15c","sort-alpha-asc"=>"15d","sort-alpha-desc"=>"15e","sort-amount-asc"=>"160","sort-amount-desc"=>"161","sort-numeric-asc"=>"162","sort-numeric-desc"=>"163","thumbs-up"=>"164","thumbs-down"=>"165","youtube-square"=>"166","youtube"=>"167","xing"=>"168","xing-square"=>"169","youtube-play"=>"16a","dropbox"=>"16b","stack-overflow"=>"16c","instagram"=>"16d","flickr"=>"16e","adn"=>"170","bitbucket"=>"171","bitbucket-square"=>"172","tumblr"=>"173","tumblr-square"=>"174","long-arrow-down"=>"175","long-arrow-up"=>"176","long-arrow-left"=>"177","long-arrow-right"=>"178","apple"=>"179","windows"=>"17a","android"=>"17b","linux"=>"17c","dribbble"=>"17d","skype"=>"17e","foursquare"=>"180","trello"=>"181","female"=>"182","male"=>"183","gittip"=>"184","gratipay"=>"184","sun-o"=>"185","moon-o"=>"186","archive"=>"187","bug"=>"188","vk"=>"189","weibo"=>"18a","renren"=>"18b","pagelines"=>"18c","stack-exchange"=>"18d","arrow-circle-o-right"=>"18e","arrow-circle-o-left"=>"190","toggle-left"=>"191","caret-square-o-left"=>"191","dot-circle-o"=>"192","wheelchair"=>"193","vimeo-square"=>"194","turkish-lira"=>"195","try"=>"195","plus-square-o"=>"196","space-shuttle"=>"197","slack"=>"198","envelope-square"=>"199","wordpress"=>"19a","openid"=>"19b","institution"=>"19c","bank"=>"19c","university"=>"19c","mortar-board"=>"19d","graduation-cap"=>"19d","yahoo"=>"19e","google"=>"1a0","reddit"=>"1a1","reddit-square"=>"1a2","stumbleupon-circle"=>"1a3","stumbleupon"=>"1a4","delicious"=>"1a5","digg"=>"1a6","pied-piper"=>"1a7","pied-piper-alt"=>"1a8","drupal"=>"1a9","joomla"=>"1aa","language"=>"1ab","fax"=>"1ac","building"=>"1ad","child"=>"1ae","paw"=>"1b0","spoon"=>"1b1","cube"=>"1b2","cubes"=>"1b3","behance"=>"1b4","behance-square"=>"1b5","steam"=>"1b6","steam-square"=>"1b7","recycle"=>"1b8","automobile"=>"1b9","car"=>"1b9","cab"=>"1ba","taxi"=>"1ba","tree"=>"1bb","spotify"=>"1bc","deviantart"=>"1bd","soundcloud"=>"1be","database"=>"1c0","file-pdf-o"=>"1c1","file-word-o"=>"1c2","file-excel-o"=>"1c3","file-powerpoint-o"=>"1c4","file-photo-o"=>"1c5","file-picture-o"=>"1c5","file-image-o"=>"1c5","file-zip-o"=>"1c6","file-archive-o"=>"1c6","file-sound-o"=>"1c7","file-audio-o"=>"1c7","file-movie-o"=>"1c8","file-video-o"=>"1c8","file-code-o"=>"1c9","vine"=>"1ca","codepen"=>"1cb","jsfiddle"=>"1cc","life-bouy"=>"1cd","life-buoy"=>"1cd","life-saver"=>"1cd","support"=>"1cd","life-ring"=>"1cd","circle-o-notch"=>"1ce","ra"=>"1d0","rebel"=>"1d0","ge"=>"1d1","empire"=>"1d1","git-square"=>"1d2","git"=>"1d3","y-combinator-square"=>"1d4","yc-square"=>"1d4","hacker-news"=>"1d4","tencent-weibo"=>"1d5","qq"=>"1d6","wechat"=>"1d7","weixin"=>"1d7","send"=>"1d8","paper-plane"=>"1d8","send-o"=>"1d9","paper-plane-o"=>"1d9","history"=>"1da","circle-thin"=>"1db","header"=>"1dc","paragraph"=>"1dd","sliders"=>"1de","share-alt"=>"1e0","share-alt-square"=>"1e1","bomb"=>"1e2","soccer-ball-o"=>"1e3","futbol-o"=>"1e3","tty"=>"1e4","binoculars"=>"1e5","plug"=>"1e6","slideshare"=>"1e7","twitch"=>"1e8","yelp"=>"1e9","newspaper-o"=>"1ea","wifi"=>"1eb","calculator"=>"1ec","paypal"=>"1ed","google-wallet"=>"1ee","cc-visa"=>"1f0","cc-mastercard"=>"1f1","cc-discover"=>"1f2","cc-amex"=>"1f3","cc-paypal"=>"1f4","cc-stripe"=>"1f5","bell-slash"=>"1f6","bell-slash-o"=>"1f7","trash"=>"1f8","copyright"=>"1f9","at"=>"1fa","eyedropper"=>"1fb","paint-brush"=>"1fc","birthday-cake"=>"1fd","area-chart"=>"1fe","pie-chart"=>"200","line-chart"=>"201","lastfm"=>"202","lastfm-square"=>"203","toggle-off"=>"204","toggle-on"=>"205","bicycle"=>"206","bus"=>"207","ioxhost"=>"208","angellist"=>"209","cc"=>"20a","shekel"=>"20b","sheqel"=>"20b","ils"=>"20b","meanpath"=>"20c","buysellads"=>"20d","connectdevelop"=>"20e","dashcube"=>"210","forumbee"=>"211","leanpub"=>"212","sellsy"=>"213","shirtsinbulk"=>"214","simplybuilt"=>"215","skyatlas"=>"216","cart-plus"=>"217","cart-arrow-down"=>"218","diamond"=>"219","ship"=>"21a","user-secret"=>"21b","motorcycle"=>"21c","street-view"=>"21d","heartbeat"=>"21e","venus"=>"221","mars"=>"222","mercury"=>"223","intersex"=>"224","transgender"=>"224","transgender-alt"=>"225","venus-double"=>"226","mars-double"=>"227","venus-mars"=>"228","mars-stroke"=>"229","mars-stroke-v"=>"22a","mars-stroke-h"=>"22b","neuter"=>"22c","genderless"=>"22d","facebook-official"=>"230","pinterest-p"=>"231","whatsapp"=>"232","server"=>"233","user-plus"=>"234","user-times"=>"235","hotel"=>"236","bed"=>"236","viacoin"=>"237","train"=>"238","subway"=>"239","medium"=>"23a","yc"=>"23b","y-combinator"=>"23b","optin-monster"=>"23c","opencart"=>"23d","expeditedssl"=>"23e","battery-4"=>"240","battery-full"=>"240","battery-3"=>"241","battery-three-quarters"=>"241","battery-2"=>"242","battery-half"=>"242","battery-1"=>"243","battery-quarter"=>"243","battery-0"=>"244","battery-empty"=>"244","mouse-pointer"=>"245","i-cursor"=>"246","object-group"=>"247","object-ungroup"=>"248","sticky-note"=>"249","sticky-note-o"=>"24a","cc-jcb"=>"24b","cc-diners-club"=>"24c","clone"=>"24d","balance-scale"=>"24e","hourglass-o"=>"250","hourglass-1"=>"251","hourglass-start"=>"251","hourglass-2"=>"252","hourglass-half"=>"252","hourglass-3"=>"253","hourglass-end"=>"253","hourglass"=>"254","hand-grab-o"=>"255","hand-rock-o"=>"255","hand-stop-o"=>"256","hand-paper-o"=>"256","hand-scissors-o"=>"257","hand-lizard-o"=>"258","hand-spock-o"=>"259","hand-pointer-o"=>"25a","hand-peace-o"=>"25b","trademark"=>"25c","registered"=>"25d","creative-commons"=>"25e","gg"=>"260","gg-circle"=>"261","tripadvisor"=>"262","odnoklassniki"=>"263","odnoklassniki-square"=>"264","get-pocket"=>"265","wikipedia-w"=>"266","safari"=>"267","chrome"=>"268","firefox"=>"269","opera"=>"26a","internet-explorer"=>"26b","tv"=>"26c","television"=>"26c","contao"=>"26d","500px"=>"26e","amazon"=>"270","calendar-plus-o"=>"271","calendar-minus-o"=>"272","calendar-times-o"=>"273","calendar-check-o"=>"274","industry"=>"275","map-pin"=>"276","map-signs"=>"277","map-o"=>"278","map"=>"279","commenting"=>"27a","commenting-o"=>"27b","houzz"=>"27c","vimeo"=>"27d","black-tie"=>"27e","fonticons"=>"280","reddit-alien"=>"281","edge"=>"282","credit-card-alt"=>"283","codiepie"=>"284","modx"=>"285","fort-awesome"=>"286","usb"=>"287","product-hunt"=>"288","mixcloud"=>"289","scribd"=>"28a","pause-circle"=>"28b","pause-circle-o"=>"28c","stop-circle"=>"28d","stop-circle-o"=>"28e","shopping-bag"=>"290","shopping-basket"=>"291","hashtag"=>"292","bluetooth"=>"293","bluetooth-b"=>"294","percent"=>"295");
	private static $tab_mois = array(
		_LANGUE_DE=>array("", "Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"),
		_LANGUE_EN=>array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"), 
		_LANGUE_ES=>array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"),
		_LANGUE_FR=>array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"),
		_LANGUE_IT=>array("", "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre "),
		_LANGUE_NL=>array("", "Januari", "Februari", "Maart", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "December"),
		_LANGUE_PT=>array("", "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"));
	private static $tab_semaine = array(
		_LANGUE_DE=>array("Mo", "Di", "Mi", "Do", "Fr", "Sa", "So"),
		_LANGUE_EN=>array("Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"), 
		_LANGUE_ES=>array("Lu", "Ma", "Mi", "Ju", "Vi", "Sá", "Do"),
		_LANGUE_FR=>array("Lu", "Ma", "Me", "Je", "Ve", "Sa", "Di"),
		_LANGUE_IT=>array("Lu", "Ma", "Me", "Gi", "Ve", "Sa", "Do"),
		_LANGUE_NL=>array("Ma", "Di", "Wo", "Do", "Vr", "Za", "Zo"),
		_LANGUE_PT=>array("Seg", "Te", "Qua", "Qui", "Sex", "Sá", "Do"));
	private static $tab_statut_resa = array(
		_LANGUE_DE=>array("Vorhanden", "Reserviet", "Besetzt", "Geschlossen"),
		_LANGUE_EN=>array("Available", "Booked", "Occupied", "Closed"), 
		_LANGUE_ES=>array("Disponible", "Reservado", "Ocupado", "Cerrado"),
		_LANGUE_FR=>array("Libre", "Réservé", "Occupé", "Fermé"),
		_LANGUE_IT=>array("Disponibile", "Prenotato", "Occupato", "Chiuso"),
		_LANGUE_NL=>array("Beschikbaar", "Geboekt", "Bezet", "Gesloten"),
		_LANGUE_PT=>array("Disponível", "Reservado", "Ocupado", "Fechado"));

	// Propriétés
	private $tab_langues = array(_LANGUE_DE, _LANGUE_EN, _LANGUE_ES, _LANGUE_FR, _LANGUE_IT, _LANGUE_NL, _LANGUE_PT);
	private $nom_langues = array(_LANGUE_DE=>"Deutsch",_LANGUE_EN=>"English",_LANGUE_ES=>"Español",_LANGUE_FR=>"Français",_LANGUE_IT=>"Italiano",_LANGUE_NL=>"Nederlands",_LANGUE_PT=>"Português");
	private $css_pos_langues = array(_LANGUE_DE=>"0 0",_LANGUE_EN=>"-40px 0",_LANGUE_ES=>"-80px 0",_LANGUE_FR=>"-120px 0",_LANGUE_IT=>"-160px 0",_LANGUE_NL=>"-200px 0",_LANGUE_PT=>"-240px 0");
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
				$nom = $this->xml_struct->lire_n_attribut(_XML_NOM, $cpt);
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
	public function get_icone($nom, $langue) {
		$val = $this->get_texte($nom, $langue);
		$ret = $this->get_val_icone($val, false);
		return $ret;
	}
	public function get_val_icone($val, $css) {
		$ret = null;
		if (strlen($val) > 0) {
			$val = str_replace("fa-", "", $val);
			if (array_key_exists($val, self::$tab_icones)) {$val = self::$tab_icones[$val];}
			$ret = ($css)?"f".$val:"&#xf".$val.";";
		}
		return $ret;
	}
	public function get_source($nom) {return $this->sources[$nom];}
	
	public function ajouter_langue($param) {
		$existe = array_key_exists($param, $this->nom_langues);
		if ($existe) {$this->liste_langues[] = $param;}
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
	// Fonction générique pour les appels à la traduction
	public function __call($methode, $arguments) {
		if (!(strncmp($methode, "get_label_", 10))) {
			$identifiant = "trad_petilabo_".substr($methode, 10);
			$langue = $arguments[0];
			return $this->get_texte($identifiant, $langue);
		}
		else {var_dump("ERREUR", $methode, $arguments);echo "<br/>\n";}
	}	
	public function get_tab_mois($langue) {return self::$tab_mois[$langue];}
	public function get_tab_semaine($langue) {return self::$tab_semaine[$langue];}
	public function get_tab_statut_resa($langue) {return self::$tab_statut_resa[$langue];}

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
	public function get_nom($langue) {return $this->nom_langues[strtolower($langue)];}
	public function get_position($langue) {return $this->css_pos_langues[strtolower($langue)];}
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