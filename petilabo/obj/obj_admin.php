<?php

class obj_admin extends obj_editable {
	private $nom_page = null;
	private $version_txt = null;
	private $version_php = null;
	private $taille_php = 0;
	private $taille_xml = 0;

	public function __construct($nom_page, $is_multilingue, $nb_langues, $is_noindex) {
		$this->nom_page = $nom_page;
		if ($nb_langues > 1) {$this->is_multilingue = $is_multilingue;}
		else {$this->is_multilingue = false;}
		$this->is_noindex = $is_noindex;
		$lecture = file_get_contents(_PETIXML_CHEMIN_VERSION_TXT._PETIXML_FICHIER_VERSION_TXT);
		$this->version_txt = preg_replace("~[[:cntrl:][:space:]]~", "", $lecture);
		$this->version_php = _VERSION_PETILABO;
		$this->version_maj = strcmp($this->version_txt, $this->version_php);
		$this->taille_php = $this->taille_repertoire(_PHP_PATH_ROOT);
		$this->taille_xml = $this->taille_repertoire(_XML_PATH_ROOT);
	}

	public function afficher($mode, $langue) {
		echo "<div class=\"panneau_admin\">\n";
		echo "<h1>Administration de la page <strong>".$this->nom_page."</strong></h1>\n";
		echo "<table><tr>\n";
		echo "<td>\n";
		echo "<p>Page multilingue : ".($this->is_multilingue?"Oui":"Non")."</p>\n";
		echo "<p>Langue affichée : ".strtoupper($langue)."</p>\n";
		echo "<p>Indexation autorisée : ".($this->is_noindex?"Non":"Oui")."</p>\n";
		echo "</td>\n";
		echo "<td>\n";
		echo "<p class=\"admin_info_site\"><span class=\"icone_prefixe\">&#xf07b;</span> petilabo/<span class=\"taille_suffixe\">".$this->conversion_taille($this->taille_php)."</span></p>\n";
		echo "<p class=\"admin_info_site\"><span class=\"icone_prefixe\">&#xf07b;</span> xml/<span class=\"taille_suffixe\">".$this->conversion_taille($this->taille_xml)."</span></p>\n";
		$taille_totale = $this->taille_php + $this->taille_xml;
		echo "<p class=\"admin_info_site\"><span class=\"icone_prefixe\">&#xf07c;</span> Total <span class=\"taille_suffixe\">".$this->conversion_taille($taille_totale)."</span></p>\n";
		echo "</td>\n";
		echo "<td>\n";
		echo "<p class=\"admin_info_version\"><span class=\"icone_prefixe\">&#xf0c3;</span> ".$this->version_php."</p>\n";
		if ($this->version_maj) {
			echo "<p class=\"admin_info_version\"><span class=\"icone_prefixe\">&#xf021;</span> ".$this->version_txt."</p>\n";
			echo "<p class=\"admin_bouton_version\"><a href=\"update.php?v=".urlencode($this->version_txt)."\" title=\"Installation automatique de la version ".$this->version_txt."\">Mettre&nbsp;à&nbsp;jour</a></p>";		
		}
		else {
			echo "<p class=\"admin_annotation\">&Agrave; jour</p>\n";
		}
		echo "</td>\n";
		echo "</tr></table>\n";
		echo "</div>\n";
	}
	
	private function taille_repertoire($path){
		$bytestotal = 0;
		$path = realpath($path);
		if ($path !== false){
			foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
				try {
					$bytestotal += $object->getSize();
				}
				catch(Exception $e) {}
			}
		}
		return $bytestotal;
	}
	
	private function conversion_taille($taille, $decimales = 2) {
		$taille_mo = (float) ($taille / (1024*1024));
		$conversion = sprintf("%.2f Mo",$taille_mo);
		return $conversion;
	}
}