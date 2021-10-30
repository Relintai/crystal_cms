<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Multi_gallery_model extends V_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function add_multi_gallery_folder($galleryid, $name, $description, $filename) {
		$link = "";
		
		if ($name) {
			$link = $this->_url_sanitize_name($name);
			
			$sql = "SELECT * FROM multi_gallery_folders WHERE galleryid = ? AND link LIKE ?";
			$res = $this->db->query($sql, array($galleryid, $link));
			
			if ($res->num_rows())
				$link = "";
		}
		
		$sql = "INSERT INTO multi_gallery_folders VALUES(default, ?, ?, ?, ?, ?, ?, default)";
		$this->db->query($sql, array($galleryid, $name, $link, $description, $filename, 0));
		
		if (!$link) {
			$sql = "UPDATE multi_gallery_folders SET link = id ORDER BY id DESC LIMIT 1";
			$this->db->query($sql);
		}
	}
	
	function get_content_multi_gallery($id) {
		$sql = "SELECT * FROM content_multi_gallery WHERE id = ?";
		$res = $this->db->query($sql, array($id));
		
		if (!$res->num_rows())
			return false;
		
		return $res->row_array();
	}
	
	function get_content_multi_gallery_folders($id) {
		$sql = "SELECT * FROM multi_gallery_folders WHERE galleryid = ?";
		$res = $this->db->query($sql, array($id));
		
		if (!$res->num_rows())
			return false;
		
		return $res->result_array();
	}

	function get_multi_gallery_folder($gallery, $folder) {
		//is folder is a number, it's the id, no need for $gallery
		if (is_numeric($folder)) {
			$sql = "SELECT * FROM multi_gallery_folders WHERE id = ?";
			$res = $this->db->query($sql, array($folder));
			
			if (!$res->num_rows())
				return false;
			
			return $res->row_array();
		} else {
			$sql = "";
			$res = null;
			
			if (is_numeric($gallery)) {
				$sql = "SELECT * FROM multi_gallery_folders WHERE link LIKE ? AND galleryid = ?";
				$res = $this->db->query($sql, array($folder, $gallery));
			} else {
				$sql = "SELECT * FROM content_multi_gallery WHERE link LIKE ?";
				$res = $this->db->query($sql, array($gallery));
				
				if (!$res->num_rows())
					return false;
				
				$res = $res->row_array();
				
				$sql = "SELECT * FROM multi_gallery_folders WHERE link LIKE ? AND galleryid = ?";
				$res = $this->db->query($sql, array($folder, $res["id"]));
			}

			if (!$res->num_rows())
				return false;
			
			return $res->row_array();
		}
	}
	
	function get_multi_gallery_folder_images($folderid) {
		$sql = "SELECT * FROM multi_gallery_data WHERE folderid = ?";
		$res = $this->db->query($sql, array($folderid));
		
		if (!$res->num_rows())
			return false;
		
		return $res->result_array();
	}
	
	function add_gallery_image($folderid, $name, $description, $thumb, $mid, $big, $orig) {
		$link = "";
		
		if ($name) {
			$link = $this->_url_sanitize_name($name);
			
			$sql = "SELECT * FROM multi_gallery_data WHERE folderid = ? AND link LIKE ?";
			$res = $this->db->query($sql, array($folderid, $name));
			
			if ($res->num_rows())
				$link = "";
		}
		
		$sql = "INSERT INTO multi_gallery_data VALUES(default, ?, ?, ?, ?, ?, ?, ?, ?)";
		$this->db->query($sql, array($folderid, $name, $link, $description, $thumb, $mid, $big, $orig));
		
		if (!$name) {
			$sql = "UPDATE multi_gallery_data SET name = id ORDER BY id DESC LIMIT 1";
			$this->db->query($sql);
		}
		
		if (!$link) {
			$sql = "UPDATE multi_gallery_data SET link = id ORDER BY id DESC LIMIT 1";
			$this->db->query($sql);
		}
	}
	
	function get_gallery_name($id) {
		$sql = "SELECT * FROM content_multi_gallery WHERE id = ?";
		$res = $this->db->query($sql, array($id));
		
		if (!$res->num_rows())
			return "";
		
		$res = $res->row_array();
		
		return $res["link"];
	}
	
	function get_folder_name($id) {
		$sql = "SELECT * FROM multi_gallery_folders WHERE id = ?";
		$res = $this->db->query($sql, array($id));
		
		if (!$res->num_rows())
			return "";
		
		$res = $res->row_array();
		
		return $res["link"];
	}
	
	function del_image_from_db($imageid) {
		$imageid = intval($imageid);
		$sql = "SELECT * FROM multi_gallery_data WHERE id = ?";
		$res = $this->db->query($sql, array($imageid));
		
		if (!$res->num_rows())
			return false;
		
		$res = $res->row_array();
		
		$sql = "DELETE FROM multi_gallery_data WHERE id = ?";
		$this->db->query($sql, array($imageid));
		
		return $res;
	}
	
	//TODO are any of this needed?:
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
	
	function get_page_and_id_from_gallery_name($galleryname) {
		$id = 0;
		if (!is_numeric($galleryname)) {
			$sql = "SELECT * FROM content_multi_gallery WHERE link = ?";
			$res = $this->db->query($sql, array($galleryname));
			
			if (!$res->num_rows())
				return $this->get_fake_page_and_id_from_gallery_name();
			
			$res = $res->row_array();
			
			$id = $res["id"];
		} else {
			$id = $galleryname;
		}
		
		$sql = "SELECT * FROM page_contents WHERE content_id = ? AND content_type = 4";
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
	
	function get_page_name_from_gallery_id($galleryid) {
		$sql = "SELECT * FROM page_contents WHERE content_id = ? AND content_type = 4";
		$res = $this->db->query($sql, array($galleryid));
		
		if (!$res->num_rows())
			return "";
		
		$res = $res->row_array();
		
		$pageid = $res["pageid"];
		
		$sql = "SELECT * FROM menu WHERE id = ?";
		$res = $this->db->query($sql, array($pageid));
		
		if (!$res->num_rows())
			return "";
		
		$res = $res->row_array();

		return $res["link"];
	}
}