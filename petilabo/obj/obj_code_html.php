<?php
class obj_code_html extends obj_html {
	private $code = null;
	public function __construct($code) {$this->code = $code;}
	public function afficher($mode, $langue) {if (strcmp($mode, _PETILABO_MODE_EDIT)) { echo $this->code;} }
}