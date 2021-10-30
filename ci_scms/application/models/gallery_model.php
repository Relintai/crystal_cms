<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery_model extends V_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function get_gallery_info($galleryname) {
		$sql = "SELECT * FROM content_gallery WHERE link LIKE ?";
		$res = $this->db->query($sql, array($galleryname));
		
		if ($res->num_rows())
			return $res->row_array();
		
		$sql = "SELECT * FROM content_gallery LIMIT 1";
		$res = $this->db->query($sql);
		
		if ($res->num_rows())
			return $res->row_array();
	}
	
	function get_gallery_data($galleryid) {
		$sql = "SELECT * FROM gallery_data WHERE gallery_id = ?";
		$res = $this->db->query($sql, array($galleryid));
		
		if ($res->num_rows())
			return $res->result_array();
		
		return null;
	}
	
	function get_gallery_image_data($galleryid, $imagename) {
		
	}
	
	function get_first_gallery_name() {
		
	}
	
	function get_fake_page_and_id_from_gallery_name() {
		//let's just return false for now
		return false;
		
		/*
		$sql = "SELECT * FROM content_gallery LIMIT 1";
		$res = $this->db->query($sql);
		
		$res = $res->row_array();
		
		$id = $res["id"];
		
		$sql = "SELECT * FROM page_contents WHERE content_id = ? AND content_type = 3";
		$res = $this->db->query($sql, array($id));
		
		$res = $res->row_array();
		$pageid = $res["pageid"];
		
		$sql = "SELECT * FROM menu WHERE id = ?";
		$res = $this->db->query($sql, array($pageid));
		
		$res = $res->row_array();
		$link = $res["link"];
		
		$d["page"] = $link;
		$d["pageid"] = $pageid;
		
		return $d;*/
	}
	
	function get_page_and_id_from_gallery_link($galleryname) {
		$sql = "SELECT * FROM content_gallery WHERE link = ?";
		$res = $this->db->query($sql, array($galleryname));
		
		if (!$res->num_rows())
			return $this->get_fake_page_and_id_from_gallery_name();
		
		$res = $res->row_array();
		
		$id = $res["id"];
		
		$sql = "SELECT * FROM page_contents WHERE content_id = ? AND content_type = 3";
		$res = $this->db->query($sql, array($id));
		
		if (!$res->num_rows())
			return $this->get_fake_page_and_id_from_gallery_name();
		
		$res = $res->row_array();
		
		$pageid = $res["pageid"];
		
		$sql = "SELECT * FROM menu WHERE id = ?";
		$res = $this->db->query($sql, array($pageid));
		
		if (!$res->num_rows())
			return $this->get_fake_page_and_id_from_gallery_name();
		
		$res = $res->row_array();
		$link = $res["link"];
		
		
		$d["page"] = $link;
		$d["pageid"] = $pageid;
		
		return $d;
	}
	
	function get_gallery_name($id) {
		$sql = "SELECT * FROM content_gallery WHERE id = ?";
		$res = $this->db->query($sql, array($id));
		
		if (!$res->num_rows())
			return $this->get_first_gallery_name();
		
		$res = $res->row_array();
		
		return $res["name"];
	}
	
	function get_gallery_link($id) {
		$sql = "SELECT * FROM content_gallery WHERE id = ?";
		$res = $this->db->query($sql, array($id));
		
		if (!$res->num_rows())
			return $this->get_first_gallery_name();
		
		$res = $res->row_array();
		
		return $res["link"];
	}
	
	function del_gallery_image_from_db($id) {
		$id = intval($id);
		$sql = "SELECT * FROM gallery_data WHERE id = ?";
		$res = $this->db->query($sql, array($id));
		
		if (!$res->num_rows())
			return false;
		
		$res = $res->row_array();
		
		$sql = "DELETE FROM gallery_data WHERE id = ?";
		$this->db->query($sql, array($id));
		
		return $res;
	}
}