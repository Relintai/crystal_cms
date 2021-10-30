<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_model extends V_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function get_page_name_from_id($id) {
		$sql = "SELECT * FROM menu WHERE id = ?";
		$res = $this->db->query($sql, array($id));
		
		if (!$res->num_rows())
			return false;
		
		$res = $res->row_array();
		
		return $res["link"];
	}
}