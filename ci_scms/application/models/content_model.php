<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content_model extends V_Model
{
	public $content_types = array(
		1 => "TEXT"
	);
	
	function __construct()
	{
		parent::__construct();
	}
	
	function get_page_contents($pageid) {
		$sql = "SELECT * FROM page_contents WHERE pageid = ? ORDER BY `order` ASC";
		$res = $this->db->query($sql, array($pageid));
		return $res->result_array();
	}
	
	function get_content_text($id) {
		$sql = "SELECT * FROM content_text WHERE id = ?";
		$res = $this->db->query($sql, array($id));
		
		
		if (!$res->num_rows())
			return false;
		
		return $res->row_array();
	}
	
	function get_content_gallery($id) {
		$sql = "SELECT * FROM content_gallery WHERE id = ?";
		$res = $this->db->query($sql, array($id));
		
		
		if (!$res->num_rows())
			return false;
		
		return $res->row_array();
	}
	
	function get_gallery_data($id) {
		$sql = "SELECT * FROM gallery_data WHERE gallery_id = ?";
		$res = $this->db->query($sql, array($id));
		
		
		if (!$res->num_rows())
			return false;
		
		return $res->result_array();
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
	
	function get_content_text_noformat($id) {
		$sql = "SELECT * FROM content_text WHERE id = ?";
		$res = $this->db->query($sql, array($id));
		
		
		//if ($res->num_rows() > 0)
			//$res = $res->row_array();
		
		return $res->row_array();
	}
	
	function add_page_text($pageid, $message) {
		$sql = "SELECT * FROM page_contents WHERE pageid = ? ORDER BY `order` ASC";
		$res = $this->db->query($sql, array($pageid));
		
		$nextid = 1;
		
		if ($res->num_rows()) {
			$res = $res->result_array();
			
			foreach($res as $e) {
				if ($e["order"] > $nextid) {
					$nextid = $e["order"];
				}
			}
			
			$nextid++;
		}
		
		$m = $message;
		$messagehtmld = $this->convert_string_all($m);
		
		$sql = "INSERT INTO content_text VALUES(DEFAULT, ?, ?)";
		$this->db->query($sql, array($messagehtmld, $message));
		
		$sql = "SELECT MAX(id) AS id FROM content_text";
		$res = $this->db->query($sql);
		
		if ($res->num_rows()) {
			$res = $res->row_array();
			
			$sql = "INSERT INTO page_contents VALUES(DEFAULT, ?, ?, 1, ?)";
			$this->db->query($sql, array($pageid, $nextid, $res["id"]));
		}
	}
	
	function edit_page_text($textid, $message) {
		$m = $message;
		$messagehtmld = $this->convert_string_all($m);
		
		$sql = "UPDATE content_text SET `text` = ?,`text_noformat` = ? WHERE id = ?";
		$this->db->query($sql, array($messagehtmld, $message, $textid));
	}
	
	function get_content_types() {
		return $content_types;
	}
	
	function content_down($pageid, $id) {
		$sql = "SELECT * from page_contents WHERE pageid = ?";
		$res = $this->db->query($sql, array($pageid));
		
		if (!$res->num_rows())
			return;
		
		$res = $res->result_array();
		
		$selected;
		foreach ($res as $r) {
			if ($r["id"] == $id) {
				$selected = $r;
				break;
			}
		}
		
		if (!$selected)
			return;
		
		$lower;
		foreach ($res as $r) {
			if ($r["order"] == $selected["order"] + 1) {
				$lower = $r;
				break;
			}
		}
		
		if (!$lower)
			return;
		
		$sql = "UPDATE page_contents SET `order` = ? WHERE id = ?";
		$this->db->query($sql, array($lower["order"], $selected["id"]));
		$this->db->query($sql, array($selected["order"], $lower["id"]));
	}
	
	function content_up($pageid, $id) {
		$sql = "SELECT * from page_contents WHERE pageid = ?";
		$res = $this->db->query($sql, array($pageid));
		
		if (!$res->num_rows())
			return;
		
		$res = $res->result_array();
		
		$selected;
		foreach ($res as $r) {
			if ($r["id"] == $id) {
				$selected = $r;
				break;
			}
		}
		
		if (!$selected)
			return;
		
		$higher;
		foreach ($res as $r) {
			if ($r["order"] == $selected["order"] - 1) {
				$higher = $r;
				break;
			}
		}
		
		if (!$higher)
			return;
		
		$sql = "UPDATE page_contents SET `order` = ? WHERE id = ?";
		$this->db->query($sql, array($higher["order"], $selected["id"]));
		$this->db->query($sql, array($selected["order"], $higher["id"]));
	}
	
	function content_delete($pageid, $id) {
		$sql = "SELECT * FROM page_contents WHERE id = ?";
		$res = $this->db->query($sql, array($id));
		
		if (!$res->num_rows())
			return;
		
		$res = $res->row_array();
		
		$sql = "DELETE FROM page_contents WHERE id = ?";
		$this->db->query($sql, array($id));
		
		$sql = "SELECT * FROM page_contents WHERE pageid = ?";
		$ares = $this->db->query($sql, array($id));
		
		if (!$ares->num_rows())
			return;
		
		$ares = $ares->result_array();
		
		//TODO make a concatenated query
		foreach ($ares as $r) {
			if ($r["order"] > $res["order"]) {
				$sql = "UPDATE page_contents SET `order` = ? WHERE id = ?";
				$this->db->query($sql, array($r["order"] - 1, $r["id"]));
			}
		}
		
		switch ($res["content_type"]) {
			case 1: //text
				$sql = "DELETE FROM content_text WHERE id = ?";
				$this->db->query($sql, array($res["content_id"]));
			break;
		}
	}
	
	function add_gallery($pageid, $name, $description) {
		$link = "";
		
		if ($name) {
			$link = _url_sanitize_name($name);
			
			$sql = "SELECT * FROM content_gallery WHERE link LIKE ?";
			$res = $this->db->query($sql, array($link));
			
			if ($res->num_rows())
				$link = "";
		}
		
		$sql = "INSERT INTO content_gallery VALUES(default, ?, ?, ?, default)";
		$this->db->query($sql, array($name, $link, $description));
		
		$sql = "SELECT MAX(id) AS id FROM content_gallery";
		$res = $this->db->query($sql);
		$res = $res->row_array();
		
		$id = $res["id"];
		
		if (!$name) {
			$sql = "UPDATE content_gallery SET name = ? WHERE id = ?";
			$this->db->query($sql, array($id, $id));
		}
		
		if (!$link) {
			$sql = "UPDATE content_gallery SET link = ? WHERE id = ?";
			$this->db->query($sql, array($id, $id));
		}
		
		$order = 1;
		
		$sql = "SELECT MAX(`order`) AS `order` FROM page_contents WHERE pageid = ?";
		$res = $this->db->query($sql, array($pageid));
		
		if ($res->num_rows()) {
			$res = $res->row_array();
			$order = $res["order"];
			$order++;
		}
		
		$sql = "INSERT INTO page_contents VALUES(default, ?, ?, 3, ?)";
		$this->db->query($sql, array($pageid, $order, $id));
	}
	
	function add_gallery_image($galleryid, $name, $description, $img, $thumb, $orig_img) {
		$link = "";
		
		if ($name) {
			$link = $this->_url_sanitize_name($name);
			
			$sql = "SELECT * FROM gallery_data WHERE gallery_id = ? AND link LIKE ?";
			$res = $this->db->query($sql, array($galleryid, $link));
			
			if ($res->num_rows())
				$link = "";		
		}
		
		$sql = "INSERT INTO gallery_data VALUES(default, ?, ?, ?, ?, ?, ?, ?)";
		$this->db->query($sql, array($galleryid, $name, $link, $description, $img, $thumb, $orig_img));
		
		if (!$link) {
			$sql = "UPDATE gallery_data SET link = id ORDER BY id DESC LIMIT 1";
			$this->db->query($sql);
		}
	}
	
	function add_multi_gallery($pageid, $name, $description) {
		$link = "";
		
		if ($name) {
			$link = $this->_url_sanitize_name($name);
			
			$sql = "SELECT * FROM content_multi_gallery WHERE id = ? AND link LIKE ?";
			$res = $this->db->query($sql, array($pageid, $link));
			
			if ($res->num_rows())
				$link = "";
		}
		
		$sql = "INSERT INTO content_multi_gallery VALUES(default, ?, ?, ?, default)";
		$this->db->query($sql, array($name, $link, $description));
		
		$sql = "SELECT MAX(id) AS id FROM content_multi_gallery";
		$res = $this->db->query($sql);
		$res = $res->row_array();
		
		$id = $res["id"];
		
		if (!$name) {
			$sql = "UPDATE content_multi_gallery SET name = ? WHERE id = ?";
			$this->db->query($sql, array($id, $id));
		}
		
		if (!$link) {
			$sql = "UPDATE content_multi_gallery SET link = ? WHERE id = ?";
			$this->db->query($sql, array($id, $id));
		}
		
		$order = 1;
		
		$sql = "SELECT MAX(`order`) AS `order` FROM page_contents WHERE pageid = ?";
		$res = $this->db->query($sql, array($pageid));
		
		if ($res->num_rows()) {
			$res = $res->row_array();
			$order = $res["order"];
			$order++;
		}
		
		$sql = "INSERT INTO page_contents VALUES(default, ?, ?, 4, ?)";
		$this->db->query($sql, array($pageid, $order, $id));
	}
}