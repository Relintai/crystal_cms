<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class V_Controller extends CI_Controller {
	public $is_admin;
	
	function __construct()
	{
		parent::__construct();
	  
		$this->isadmin = false;
		$this->_manage_session();
	}
  
	function _manage_session() {
		if (isset($_SESSION['sid'])) {
			$this->load->model("admin_model");
			$this->is_admin = $this->admin_model->is_admin($_SESSION['sid']);
		}
	}
  
	function _manage_admin_session() {
	}
  
	function _send_admin_headers() {
	}
  
	function _send_headers($page, $type = "") {
		$this->load->helper('url');
	
		$data["title"] = "title";
		$data["pageid"] = $page;
		$data["is_admin"] = $this->is_admin;
		$data["type"] = $type;
		
		$this->load->helper("url");
	
		$this->_send_header($data);
		$this->_send_menu($data);
	}
  
	function _send_header($data) {
		$this->load->view("header", $data);
	}
  
	function _send_menu($data) {
		$this->load->model('menu_model');
	  
		$data["menu"] = $this->menu_model->getBaseMenuData();
	  
		$this->load->view("menu", $data);
	}
  
	function _send_footer() {
		$data["is_admin"] = $this->is_admin;
		$this->load->view("footer", $data);
	}
  
	function salt_md5($text) {
		return hash("sha512", "dla=/aasdf42)%/sf14" . $text . "$)/fasdfh297452sdikfahzsbgdfa|");
	}
	
	function redirect_to_pageid($id) {
		$this->load->helper('url');
		
		if (!is_numeric($id))
			redirect("main/index");
		
		if ($id < 0)
			redirect("main/index");
		
		$this->load->model("common_model");
		$pname = $this->common_model->get_page_name_from_id($id);

		if ($pname)
			redirect("main/index/" . $pname);
		else
			redirect("main/index");
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
	
	protected function _get_unique_file_name($path, $hint) {
		$filename = "";
		
		$this->load->helper('file');
		$dir = get_filenames($path);
		
		if ($hint) {
			//$hint = str_replace(" ","_", $hint);
			$hint = $this->_url_sanitize_name($hint);
			$h = $hint . ".jpg";
			$mid = "";
			
			if ($dir) {
				$i = 0;
				while (true) {
					$ffound = false;
					foreach ($dir as $d) {
						if ($d == $h) {
							$ffound = true;
							break;
						}
					}
					
					if ($ffound) {
						$mid = "_" . $i;
						$h = $hint . $mid . ".jpg";
					} else {
						return $hint . $mid;
					}
					
					$i++;
				}
			} else {
				return $hint;
			}
		} else {
			if ($dir) {
				$max = 0;
				foreach ($dir as $d) {
					$n = explode(".", $d);
					if ($n[0]) {
						$name = $n[0];
						
						if (is_numeric($name)) {
							$num = intval($name);
							
							if ($num > $max) {
								$max = $num;
							}
						}
					}
				}
				
				$max++;
				return $max;
			} else {
				return "1";
			}
		}
		
		return $filename;
	}
}
