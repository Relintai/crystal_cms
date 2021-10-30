<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends V_Model
{
	function __construct()
	{
		parent::__construct();
	}
  
	function is_page_valid($page) {
		$sql = "SELECT * FROM menu";
		$res = $this->db->query($sql);
		
		if (!$res->num_rows())
			return false;
		
		$res = $res->result_array();
		
		foreach ($res as $m) {
			if ($m["link"] == $page) {
				return true;
			}
		}
		
		return false;
	}
  
	function getFirstMenuItem() {
		$sql = "SELECT * FROM menu WHERE id = 1";
	
		$q = $this->db->query($sql);
	
		return $q->row_array(); 
	}

	function getBaseMenuData() {
		$sql = "SELECT * FROM menu ORDER BY menu.order ASC";
	
		$q = $this->db->query($sql);
	
		return $q->result_array();
	}
  
	function getPageId($page) {
		$sql = "SELECT * FROM menu WHERE name = ?";
	
		$q = $this->db->query($sql, array($page));
		
		if ($q->num_rows())
			$q = $q->row_array();
		else
			return -1;
		
		return $q["id"];
	}
}