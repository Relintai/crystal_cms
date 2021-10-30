<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends V_Model
{
  function __construct()
  {
      parent::__construct();
  }
  
  function is_registration_enabled() {
	  $sql = "SELECT * FROM settings";
	  
	  $res = $this->db->query($sql);
	  
	  $res = $res->result_array();
	  
	  foreach ($res as $r) {
		  if ($r["name"] == "reg_enabled") {
			  if ($r["value"] == "true") {
				  return true;
			  } else {
				  return false;
			  }
		  }
	  }
	  
	  return false;
  }
  
  function check_user($username, $password) {
		$sql = "SELECT password FROM avxg_users WHERE name = ?";
		$res = $this->db->query($sql, array($username));
		
		if ($res->num_rows() <= 0) {
			return false;
		}
		
		$res = $res->row_array();
		
		
		if ($res['password'] == $password)
			return true;
		
		return false;
  }
  
	function create_cookie_session_id() {
	  	$sql = "SELECT * FROM session_links";
		$res = $this->db->query($sql);
		
		if ($res->num_rows() <= 0)
			return $this->getcsid();
		
		$res->result_array();
		
		$sess_id = "";
		$found = false;
		while(!found) {
			$sess_id = $this->getcsid();
			
			$f = false;
			foreach ($res as $r) {
				if ($r["cookie_session_id"] == $sess_id) {
					$f = true;
					break;
				}
			}
			
			if (!$f) {
				$found = true;
			}
		}
		
		return $sess_id;
  }
  
  function getcsid() {
	  return md5("+41)d" . rand() . "ikZtzdDJU");
  }
  
  function get_or_create_session_id($username) {
	  	$sql = "SELECT * FROM avxg_users WHERE name = ?";
		$res = $this->db->query($sql, array($username));
		
		if ($res->num_rows() <= 0)
			return -1;
		
		$res = $res->row_array();
		
		$sess_id = "";
		if ($res['session_id'] == "") {
			$sql = "SELECT * FROM avxg_users";
			$res2 = $this->db->query($sql);
			
			$res2 = $res2->result_array();
			
			$found = false;
			while (!$found) {
				$sess_id = md5("77=89$@" + rand() + "99)(!4%)");
				
				$f = false;
				foreach ($res2 as $u) {
					if ($u["session_id"] == $sess_id) {
						$f = true;
						break;
					}
				}
				
				if (!$f) {
					$found = true;
				}
				
				$sql = "UPDATE avxg_users SET session_id = ? WHERE name = ?";
				$this->db->query($sql, array($sess_id, $username));
			}
		} else {
			$sess_id = $res['session_id'];
		}
		
		return $sess_id;
  }
  
	function register_session($sessionid, $cookie_session_id) {
		$sql = "INSERT INTO session_links VALUES(default, ?, ?)";
		$this->db->query($sql, array($sessionid, $cookie_session_id));
		
		return true;
	}
  
  function login_user($username, $sessionid) {
		
  }
  
  function register_user($username, $password, $email) {
	  $sql = "SELECT * FROM avxg_users";
	  $res = $this->db->query($sql);
	  $res = $res->result_array();
	  
	  $nametaken = false;
	  $emailtaken = false;
	  foreach ($res as $r) {
		  if ($r["name"] == $username) {
			  $nametaken = true;
		  }
		  
		  if ($r["email"] == $email) {
			  $emailtaken = true;
		  }
	  }
	  
	  //TODO tell error?
	  
	  if ($emailtaken || $nametaken)
		  return;
	  
	  $sql = "INSERT INTO avxg_users VALUES(default, ?, ?, ?, NULL, NULL, NULL)";
	  
	  $this->db->query($sql, array($username, $password, $email));
	  
	  //turn off registration
	  $sql = "UPDATE settings SET value = 'false' WHERE name LIKE 'reg_enabled'";
	  $this->db->query($sql);
  }
  
	function is_admin($sid) {
		$sql = "SELECT * FROM session_links WHERE cookie_session_id = ?";
		$res = $this->db->query($sql, array($sid));
		
		if (!$res->num_rows())
			return false;
		
		$res = $res->row_array();
		
		$sql = "SELECT * FROM avxg_users WHERE session_id = ?";
		$res2 = $this->db->query($sql, array($res["session_id"]));
		
		if (!$res2->num_rows())
			return false;
		
		return true;
	}
  
	function get_username_sid($sid) {
		$sql = "SELECT * FROM session_links WHERE cookie_session_id = ?";
		$res = $this->db->query($sql, array($sid));
		
		if (!$res->num_rows())
			return "E";
		
		$res = $res->row_array();
		
		$sql = "SELECT * FROM avxg_users WHERE session_id = ?";
		$res2 = $this->db->query($sql, array($res["session_id"]));
		
		if (!$res2->num_rows())
			return "E2";
		
		$res2 = $res2->row_array();
		
		return $res2["name"];
	}
	
	function logout($sid) {
		$sql = "DELETE FROM session_links WHERE cookie_session_id = ?";
		$this->db->query($sql, array($sid));
	}
	
	function logout_all($sid) {
		$sql = "SELECT * FROM session_links WHERE cookie_session_id = ?";
		$res = $this->db->query($sql, array($sid));
		
		if (!$res->num_rows())
			return;
		
		$res = $res->row_array();
		
		$sql = "DELETE FROM session_links WHERE session_id = ?";
		$this->db->query($sql, array($res["session_id"]));
	}
}