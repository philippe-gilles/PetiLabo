<?php

class document {
	// Propriétés
	private $fichier = null;
	private $info = null;
	private $legende = null;

	public function set_fichier($param) {
		$this->fichier = $param;
	}
	public function set_info($param) {
		$this->info = $param;
	}
	public function set_legende($param) {
		$this->legende = $param;
	}
	
	// Accesseurs
	public function get_fichier() {
		return $this->fichier;
	}
	public function get_info() {
		return $this->info;
	}
	public function get_legende() {
		return $this->legende;
	}
}

class xml_document {
	// Propriétés
	private $docs = array();

	public function ouvrir($nom) {
		$xml_doc = new xml_struct();
		$ret = $xml_doc->ouvrir($nom);
		if ($ret) {
			// Traitement des documents
			$nb_docs = $xml_doc->compter_elements(_DOCUMENT_DOC);
			$xml_doc->pointer_sur_balise(_DOCUMENT_DOC);
			for ($cpt = 0;$cpt < $nb_docs; $cpt++) {
				$nom = $xml_doc->lire_n_attribut(_DOCUMENT_ATTR_NOM, $cpt);
				if (strlen($nom) > 0) {
					$fichier = $xml_doc->lire_n_valeur(_DOCUMENT_DOC_FICHIER, $cpt);
					$info = $xml_doc->lire_n_valeur(_DOCUMENT_DOC_INFO, $cpt);
					$legende = $xml_doc->lire_n_valeur(_DOCUMENT_DOC_LEGENDE, $cpt);

					// Création de l'objet document si le nom de fichier n'est pas vide
					if (strlen($fichier) > 0) {
						$doc = new document();
						$doc->set_fichier(_XML_PATH_FICHIERS.$fichier);
						if (strlen($info) > 0) {
							$doc->set_info($info);
						}
						if (strlen($legende) > 0) {
							$doc->set_legende($legende);
						}
						$this->docs[$nom] = $doc;
					}
				}
			}
		}

		return $ret;
	}
	
	function get_document($nom) {
		$ret = null;
		if (array_key_exists($nom, $this->docs)) {
			$ret = $this->docs[$nom];
		}
		
		return $ret;
	}
}