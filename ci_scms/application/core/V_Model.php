<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class V_Model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function convert_string_all($text) {
		$text = $this->escape($text);
		$text = $this->convert_le_to_br($text);
		$text = $this->convert_bb_to_html($text);
		
		return $text;
	}
	
	public function escape($text) {
		$text = strip_tags($text);
		$text = htmlentities($text, ENT_COMPAT | ENT_HTML5, "UTF-8", true);
		$test = htmlspecialchars($text, ENT_COMPAT | ENT_HTML5, "UTF-8", true);

		return $text;
	}
	
	public function convert_le_to_br($text) {
		$text = nl2br($text, false);
		return $text;
	}
	
	public function convert_bb_to_html($text) {
		return $text;
	}
	
	protected function _url_sanitize_name($name) {
		//allowed a-z, A-Z, 0-9, _
		
		$name = str_replace(" ","_", $name);
		
		//var_dump($name);
		
		$regex = '/[^A-Za-z0-9_]/';
		
		$name = preg_replace($regex, "", $name);
		
		//var_dump($name);
		
		return $name;
	}
}