<?php

class obj_collection_images extends obj_editable {
	protected $largeur_max = 0;
	protected $tab_images = array();
	
	public function set_largeur_max($largeur_max) {
		$this->largeur_max = $largeur_max;
	}
	public function ajouter_image($obj_image) {
		$this->tab_images[] = $obj_image;
	}
}