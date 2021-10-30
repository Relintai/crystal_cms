<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends V_Controller {
  
	function __construct()
	{
		parent::__construct();
	  
		$this->_manage_admin_session();
		$this->load->model("admin_model");
	}
  
	public function index() 
	{
		$this->load->helper("url");
		redirect("admin/login");
	}
  
	public function login() 
	{
		$this->load->helper("url");
	
		$data["reg_enabled"] = $this->admin_model->is_registration_enabled();
	
		$this->load->view("admin/login", $data);
	}
  
	public function dologin() 
	{
		$username = $this->input->post("user");
		$password = $this->input->post("pass");
	
		$password = $this->salt_md5($password);
	
		if ($this->admin_model->check_user($username, $password))
		{
			//let's generate the session data
			$sessionid = $this->admin_model->get_or_create_session_id($username);
		
			if ($sessionid == -1) {
				//echo "sessid -1";
				$this->load->helper('url');
				redirect("admin/login");
			}
		
			$cookie_session_id = $this->admin_model->create_cookie_session_id($username);
		
			if ($cookie_session_id == -1) {
				$this->load->helper('url');
				redirect("admin/login");
			}
		
			if (!$this->admin_model->register_session($sessionid, $cookie_session_id)) {
				$this->load->helper('url');
				redirect("admin/login");
			}
		
			$this->session->set_userdata('sid', $cookie_session_id);
		
			$this->load->helper('url');
			redirect("main/index");
		} else {
			$this->load->helper('url');
			redirect("admin/login");
		}
	}
  
	public function register() 
	{
		$this->load->helper("url");
	
		$data["reg_enabled"] = $this->admin_model->is_registration_enabled();
	
		if ($data["reg_enabled"] == false) {
			show_404();
		}
	
		$this->load->view("admin/register", $data);
  }
  
	public function doregister() 
	{
		$username = $this->input->post('user');
		$password = $this->input->post('pass');
		$password2 = $this->input->post('pass2');
		$email = $this->input->post('email');
	  
		if (!$username || !$password || !$password2 || !$email || !($password == $password2)) {
			$this->load->helper('url');
			redirect("admin/register");
		}
	  
		$password = $this->salt_md5($password);
	  
		$this->admin_model->register_user($username, $password, $email);
	  
		$this->load->helper('url');
		redirect("admin/login");
	}
  
	public function addcontent($page = -1, $elementid = -1) 
	{
		if (!$this->is_admin)
			show_404();
		
		$this->load->helper("url");
		if ($page == -1) {
			redirect("main/index");
		}
		
		$this->load->model("content_model");
		
		if ($elementid == -1) {
			redirect("main/index");
		}
		
		$data["pageid"] = $page;
		$data["mode"] = "add";
		
		switch ($elementid) {
			case 1:
				$this->load->view("admin/text", $data);
			break;
			case 3:
				$this->load->view("admin/add_gallery", $data);
			break;
			case 4:
				$this->load->view("admin/add_multi_gallery", $data);
			break;
			default:
				redirect("main/index");
		}
	}
	
	public function editcontent($page = -1, $elementid = -1, $contentid = -1) 
	{
		if (!$this->is_admin)
			show_404();
		
		$this->load->helper("url");
		if ($page == -1 || $elementid == -1 || $contentid == -1) {
			redirect("main/index");
		}
		
		$this->load->model("content_model");
		
		$data["pageid"] = $page;
		$data["contentid"] = $contentid;
		$data["mode"] = "edit";
		$s = $this->content_model->get_content_text($contentid);
		$data["text"] = $s["text_noformat"];
		
		switch ($elementid) {
			case 1:
				$this->load->view("admin/text", $data);
			break;
			default:
				redirect("main/index");
		}
	}
	
	public function addtext($pageid = -1) 
	{
		if (!$this->is_admin)
			show_404();
		
		$this->load->helper("url");
		
		$message = $this->input->post("message");
		
		$this->load->model("content_model");
		$this->content_model->add_page_text($pageid, $message);
		
		redirect("main/index");
	}
	
	public function edittext($textid = -1) 
	{
		if (!$this->is_admin)
			show_404();
		
		$this->load->helper("url");
		
		$message = $this->input->post("message");
		
		$this->load->model("content_model");
		$this->content_model->edit_page_text($textid, $message);
		
		redirect("main/index");
	}
	
	public function contentdown($pageid = -1, $contentid = -1) {
		if (!$this->is_admin)
			show_404();
		
		$this->load->helper("url");
		
		if ($pageid == -1 && $contentid == -1)
			redirect("main/index");
		
		$this->load->model("content_model");
		$this->content_model->content_down($pageid, $contentid);
		
		redirect("main/index");
	}
	
	public function contentup($pageid = -1, $contentid = -1) {
		if (!$this->is_admin)
			show_404();
		
		$this->load->helper("url");
		
		if ($pageid == -1 && $contentid == -1)
			redirect("main/index");
		
		$this->load->model("content_model");
		$this->content_model->content_up($pageid, $contentid);
		
		redirect("main/index");
	}
	
	public function deletecontent($pageid = -1, $id = -1) {
		if (!$this->is_admin)
			show_404();
		
		$this->load->helper("url");
		
		if ($pageid == -1 && $id == -1)
			redirect("main/index");
		
		$this->load->model("content_model");
		$this->content_model->content_delete($pageid, $id);
		
		redirect("main/index");
	}
	
	public function logout() {
		if (!$this->is_admin)
			show_404();
		
		$this->load->helper("url");
		
		$this->admin_model->logout($_SESSION["sid"]);
		unset($_SESSION['sid']);
		
		redirect("main/index");
	}
	
	public function logoutall() {
		if (!$this->is_admin)
			show_404();
		
		$this->load->helper("url");
		
		$this->admin_model->logout_all($_SESSION["sid"]);
		unset($_SESSION['sid']);
		
		redirect("main/index");
	}
	
	public function addgallery($page = -1) {
		if (!$this->is_admin)
			show_404();
		
		$this->load->helper("url");

		if ($page == -1)
			redirect("main/index");
		
		$name = $this->input->post("name");
		$description = $this->input->post("description");
		
		$this->load->model("content_model");
		$this->content_model->add_gallery($page, $name, $description);
		
		redirect("main/index");
	}
	
	public function addgalleryimage($galleryid = -1) {
		if (!$this->is_admin)
			show_404();
		
		$this->load->helper("url");

		if ($galleryid == -1)
			redirect("main/index");
		
		$this->load->helper('form');
		$data["id"] = $galleryid;
		$data["mode"] = 'add';
		$this->load->view("admin/add_gallery_img", $data);
		
		//redirect("main/index");
	}
	
	public function doaddgalleryimage($galleryid = -1) {
		if (!$this->is_admin)
			show_404();
		
		$this->load->helper("url");

		if ($galleryid == -1)
			redirect("main/index");
		
		$name = $this->input->post("name");
		$description = $this->input->post("description");
		//$img = $this->input->post("img");
		
		//$this->load->model("content_model");
		//$this->content_model->add_gallery_image($galleryid, $name, $description);
		
		$config['upload_path']          = './img/upload/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['max_size']             = 80000;
		$config['max_width']            = 90000;
		$config['max_height']           = 90000;

		$this->load->library('upload', $config);

		if ($this->upload->do_upload("img"))
		{
			//$data = array('upload_data' => $this->upload->data());
			//$this->load->view('upload_success', $data);
			
			$data = $this->upload->data();

			/*
				$data = array(
				'file_name'		=> $this->file_name,
				'file_type'		=> $this->file_type,
				'file_path'		=> $this->upload_path,
				'full_path'		=> $this->upload_path.$this->file_name,
				'raw_name'		=> str_replace($this->file_ext, '', $this->file_name),
				'orig_name'		=> $this->orig_name,
				'client_name'		=> $this->client_name,
				'file_ext'		=> $this->file_ext,
				'file_size'		=> $this->file_size,
				'is_image'		=> $this->is_image(),
				'image_width'		=> $this->image_width,
				'image_height'		=> $this->image_height,
				'image_type'		=> $this->image_type,
				'image_size_str'	=> $this->image_size_str,
			);
			
			*/
			//var_dump($data);
			//var_dump(gd_info());
			//var_dump(getimagesize($data["full_path"]));
			
			//first let's make a thumbnail
			$res = null;
			if ($data["file_type"] == "image/jpeg") {
				$res = imagecreatefromjpeg($data["full_path"]);
			} elseif ($data["file_type"] == "image/gif") {
				$res = imagecreatefromgif($data["full_path"]);
			} elseif ($data["file_type"] == "image/png") {
				$res = imagecreatefrompng($data["full_path"]);
			} else {
				die("Nem támogatott kép formátum!");
			}
			
			//first let's crop out a rectangle from the middle
			$size = getimagesize($data["full_path"]);
			$width = $size[0];
			$height = $size[1];
			
			$rect = null;
			$cropped = imagecreatetruecolor(155, 155);
			
			if ($width > $height) {
				$x = intval(($width - $height) / 2);
				$y = 0;
				$w = $height;
				$h = $height;
				
				imagecopyresampled($cropped, $res, 0, 0, $x, $y, 155, 155, $w, $h);
				//imagecopyresized($cropped, $res, 0, 0, $rect["x"], $rect["y"], 155, 155, $rect["width"], $rect["height"]);
			} elseif($height > $width) {
				$x = 0;
				$y = intval(($height - $width) / 2);
				$w = $width;
				$h = $width;
				
				imagecopyresampled($cropped, $res, 0, 0, $x, $y, 155, 155, $w, $h);
				//imagecopyresized($cropped, $res, 0, 0, $rect["x"], $rect["y"], 155, 155, $rect["width"], $rect["height"]);
				//$cropped = imagecrop ($res, $rect);
			} else {
				imagecopyresampled($cropped, $res, 0, 0, 0, 0, 155, 155, $width, $height);
			}
			
			//bool
			imagejpeg($cropped, $data["file_path"] . "thumb.jpg", 100);
			
			//Now let's make a big version
			//470 width
			$imgsize = getimagesize($data["full_path"]);
			$widthrat = 470 / $imgsize[0];
			$h = $imgsize[1] * $widthrat;
			$h = intval($h);
			$big = imagecreatetruecolor(470, $h);
			imagecopyresampled($big, $res, 0, 0, 0, 0, 470, $h, $imgsize[0], $imgsize[1]);
			//var_dump($imgsize);
			//imagecopyresized($big, $res, 0, 0, 0, 0, 470, $h, $imgsize[0], $imgsize[1]);
			imagejpeg($big, $data["file_path"] . "mid.jpg", 100);
			
			//Now let's make a big version
			//1200 width
			$imgsize = getimagesize($data["full_path"]);
			$widthrat = 1200 / $imgsize[0];
			$h = $imgsize[1] * $widthrat;
			$h = intval($h);
			$big = imagecreatetruecolor(1200, $h);
			imagecopyresampled($big, $res, 0, 0, 0, 0, 1200, $h, $imgsize[0], $imgsize[1]);
			imagejpeg($big, $data["file_path"] . "big.jpg", 100);
			
			//now let's put hem into the img directory
			$this->load->helper('file');
			
			$finalfilename = $this->_get_unique_file_name(str_replace('\\', '/', realpath('') . '/img/gallery/orig/'), $name);
			
			$f = read_file($data["file_path"] . "big.jpg");
			$werfull = str_replace('\\', '/', realpath('') . '/img/gallery/orig/');
			$werfull = $werfull . $finalfilename . ".jpg";
			write_file($werfull, $f);
			
			$f = read_file($data["file_path"] . "thumb.jpg");
			$werthumb = str_replace('\\', '/', realpath('') . '/img/gallery/thumb/');
			$werthumb = $werthumb . $finalfilename . ".jpg";
			write_file($werthumb, $f);
			
			$f = read_file($data["file_path"] . "mid.jpg");
			$wermid = str_replace('\\', '/', realpath('') . '/img/gallery/mid/');
			$wermid = $wermid . $finalfilename . ".jpg";
			write_file($wermid, $f);
			
			$this->load->model("content_model");
			$this->content_model->add_gallery_image($galleryid, $name, $description, $finalfilename . ".jpg", $finalfilename . ".jpg", $finalfilename . ".jpg");
			
			//$d = str_replace('\\', '/', realpath('') . '/img/upload/');
			delete_files('./img/upload/');
		}
		else
		{
			//echo $this->upload->display_errors();
			//$error = array('error' => $this->upload->display_errors());
			//var_dump($error);
			//$this->load->view('upload_form', $error);
		}
		
		$this->load->model("gallery_model");
		$url = $this->gallery_model->get_gallery_link($galleryid);
		redirect("gallery/view/" . $url);
	}
	
	function delgalleryimage($galleryid = -1, $imageid = -1) {
		if (!$this->is_admin)
			show_404();
		
		$this->load->helper("url");
		
		if ($galleryid == -1) 
			redirect("main/index");
		
		if ($imageid == -1)
			redirect("gallery/view/" . $galleryid);
		
		
		$this->load->model("gallery_model");
		
		$data = $this->gallery_model->del_gallery_image_from_db($imageid);
		
		if ($data) {
			$this->load->helper('file');
			
			$werfull = str_replace('\\', '/', realpath('') . '/img/gallery/orig/');
			$werfull = $werfull . $data["orig_img"];
			
			if (get_file_info($werfull)) {
				unlink($werfull);
			}
			
			$werthumb = str_replace('\\', '/', realpath('') . '/img/gallery/thumb/');
			$werthumb = $werthumb . $data["thumb"];
			
			if (get_file_info($werthumb)) {
				unlink($werthumb);
			}
			
			$wermid = str_replace('\\', '/', realpath('') . '/img/gallery/mid/');
			$wermid = $wermid . $data["img"];
			
			if (get_file_info($wermid)) {
				unlink($wermid);
			}
		}
		
		//$url = $this->gallery_model->get_gallery_name($galleryid);
		redirect("gallery/view/" . $galleryid);
	}
	
	public function addmultigallery($page = -1) {
		if (!$this->is_admin)
			show_404();
		
		$this->load->helper("url");

		if ($page == -1)
			redirect("main/index");
		
		$name = $this->input->post("name");
		$description = $this->input->post("description");
		
		$this->load->model("content_model");
		$this->content_model->add_multi_gallery($page, $name, $description);
		
		$this->redirect_to_pageid($page);
		//redirect("main/index");
	}
	
	public function addmultigalleryfolder($galleryid = -1) {
		if (!$this->is_admin)
			show_404();
		
		$this->load->helper("url");

		if ($galleryid == -1)
			redirect("main/index");
		
		if (!$this->input->post()) {
			$this->load->helper('form');
			$data["id"] = $galleryid;
			$data["mode"] = 'add';
			$this->load->view("admin/add_multi_gallery_folder", $data);
			return;
		}

		$name = $this->input->post("name");
		$description = $this->input->post("description");
		
		$config['upload_path']          = './img/upload/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['max_size']             = 80000;
		$config['max_width']            = 90000;
		$config['max_height']           = 90000;

		$this->load->library('upload', $config);

		if ($this->upload->do_upload("img"))
		{
			$data = $this->upload->data();

			//let's make a thumbnail
			$res = null;
			if ($data["file_type"] == "image/jpeg") {
				$res = imagecreatefromjpeg($data["full_path"]);
			} elseif ($data["file_type"] == "image/gif") {
				$res = imagecreatefromgif($data["full_path"]);
			} elseif ($data["file_type"] == "image/png") {
				$res = imagecreatefrompng($data["full_path"]);
			} else {
				die("Nem támogatott kép formátum!");
			}
			
			//first let's crop out a rectangle from the middle
			$size = getimagesize($data["full_path"]);
			$width = $size[0];
			$height = $size[1];
			
			$rect = null;
			$cropped = imagecreatetruecolor(202, 202);
			
			if ($width > $height) {
				$x = intval(($width - $height) / 2);
				$y = 0;
				$w = $height;
				$h = $height;
				
				imagecopyresampled($cropped, $res, 0, 0, $x, $y, 202, 202, $w, $h);
			} elseif($height > $width) {
				$x = 0;
				$y = intval(($height - $width) / 2);
				$w = $width;
				$h = $width;
				
				imagecopyresampled($cropped, $res, 0, 0, $x, $y, 202, 202, $w, $h);
			} else {
				imagecopyresampled($cropped, $res, 0, 0, 0, 0, 202, 202, $width, $height);
			}
			
			//bool
			imagejpeg($cropped, $data["file_path"] . "thumb.jpg", 100);
			
			//now let's put hem into the img directory
			$this->load->helper('file');
			
			$finalfilename = $this->_get_unique_file_name(str_replace('\\', '/', realpath('') . '/img/mgallery/folder/'), $name);
			
			$f = read_file($data["file_path"] . "thumb.jpg");
			$werthumb = str_replace('\\', '/', realpath('') . '/img/mgallery/folder/');
			$werthumb = $werthumb . $finalfilename . ".jpg";
			write_file($werthumb, $f);
			
			$this->load->model("multi_gallery_model");
			$this->multi_gallery_model->add_multi_gallery_folder($galleryid, $name, $description, $finalfilename . ".jpg");
			
			delete_files('./img/upload/');
		}
		else
		{
			//echo $this->upload->display_errors();
			//$error = array('error' => $this->upload->display_errors());
			//var_dump($error);
			//$this->load->view('upload_form', $error);
		}
		
		$this->load->model("multi_gallery_model");
		$url = $this->multi_gallery_model->get_page_name_from_gallery_id($galleryid);
		redirect("main/index/" . $url);
	}
	
	public function addmultigalleryimage($galleryid = -1, $folderid = -1) {
		if (!$this->is_admin)
			show_404();
		
		$this->load->helper("url");

		if ($galleryid == -1 || $folderid == -1)
			redirect("main/index");
		
		if (!$this->input->post()) {
			$this->load->helper('form');
			$data["id"] = $galleryid;
			$data["folderid"] = $folderid;
			$data["mode"] = 'add';
			$this->load->view("admin/add_multi_gallery_img", $data);
			return;
		}
		
		$name = $this->input->post("name");
		$description = $this->input->post("description");

		$config['upload_path']          = './img/upload/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['max_size']             = 80000;
		$config['max_width']            = 90000;
		$config['max_height']           = 90000;

		$this->load->library('upload', $config);

		if ($this->upload->do_upload("img"))
		{
			$data = $this->upload->data();

			$res = null;
			if ($data["file_type"] == "image/jpeg") {
				$res = imagecreatefromjpeg($data["full_path"]);
			} elseif ($data["file_type"] == "image/gif") {
				$res = imagecreatefromgif($data["full_path"]);
			} elseif ($data["file_type"] == "image/png") {
				$res = imagecreatefrompng($data["full_path"]);
			} else {
				die("Nem támogatott kép formátum!");
			}
			
			//first let's make a thumbnail
			//let's crop out a rectangle from the middle
			$size = getimagesize($data["full_path"]);
			$width = $size[0];
			$height = $size[1];
			
			$rect = null;
			$cropped = imagecreatetruecolor(155, 155);
			
			if ($width > $height) {
				$x = intval(($width - $height) / 2);
				$y = 0;
				$w = $height;
				$h = $height;
				
				imagecopyresampled($cropped, $res, 0, 0, $x, $y, 155, 155, $w, $h);
			} elseif($height > $width) {
				$x = 0;
				$y = intval(($height - $width) / 2);
				$w = $width;
				$h = $width;
				
				imagecopyresampled($cropped, $res, 0, 0, $x, $y, 155, 155, $w, $h);
			} else {
				imagecopyresampled($cropped, $res, 0, 0, 0, 0, 155, 155, $width, $height);
			}
			
			//bool
			imagejpeg($cropped, $data["file_path"] . "thumb.jpg", 100);
			
			//Now let's make a mid version
			//470 width
			$imgsize = getimagesize($data["full_path"]);
			$widthrat = 470 / $imgsize[0];
			$h = $imgsize[1] * $widthrat;
			$h = intval($h);
			$mid = imagecreatetruecolor(470, $h);
			imagecopyresampled($mid, $res, 0, 0, 0, 0, 470, $h, $imgsize[0], $imgsize[1]);
			imagejpeg($mid, $data["file_path"] . "mid.jpg", 100);
			
			//Now let's make a big version
			//1200 width
			$imgsize = getimagesize($data["full_path"]);
			$widthrat = 1200 / $imgsize[0];
			$h = $imgsize[1] * $widthrat;
			$h = intval($h);
			$big = imagecreatetruecolor(1200, $h);
			imagecopyresampled($big, $res, 0, 0, 0, 0, 1200, $h, $imgsize[0], $imgsize[1]);
			imagejpeg($big, $data["file_path"] . "big.jpg", 100);
			
			//now let's put hem into the img directory
			$this->load->helper('file');
			
			$finalfilename = $this->_get_unique_file_name(str_replace('\\', '/', realpath('') . '/img/mgallery/big/'), $name);
			
			$f = read_file($data["file_path"] . "big.jpg");
			$werfull = str_replace('\\', '/', realpath('') . '/img/mgallery/big/');
			$werfull = $werfull . $finalfilename . ".jpg";
			write_file($werfull, $f);
			
			$f = read_file($data["file_path"] . "thumb.jpg");
			$werthumb = str_replace('\\', '/', realpath('') . '/img/mgallery/thumb/');
			$werthumb = $werthumb . $finalfilename . ".jpg";
			write_file($werthumb, $f);
			
			$f = read_file($data["file_path"] . "mid.jpg");
			$wermid = str_replace('\\', '/', realpath('') . '/img/mgallery/mid/');
			$wermid = $wermid . $finalfilename . ".jpg";
			write_file($wermid, $f);
			
			$this->load->model("multi_gallery_model");
			$this->multi_gallery_model->add_gallery_image($folderid, $name, $description, $finalfilename . ".jpg", $finalfilename . ".jpg", $finalfilename . ".jpg", $finalfilename . ".jpg");
			
			delete_files('./img/upload/');
		}
		else
		{
			//echo $this->upload->display_errors();
			//$error = array('error' => $this->upload->display_errors());
			//var_dump($error);
			//$this->load->view('upload_form', $error);
		}
		
		$this->load->model("multi_gallery_model");
		$gn = $this->multi_gallery_model->get_gallery_name($galleryid);
		$fn = $this->multi_gallery_model->get_folder_name($folderid);
		redirect("mgallery/view/" . $gn . "/" . $fn);
	}
	
	function delmultigalleryimage($galleryname = "", $foldername = "", $imageid = -1) {
		if (!$this->is_admin)
			show_404();
		
		$this->load->helper("url");
		
		if ($imageid == -1)
			redirect("mgallery/view/" . $galleryname . "/" . $foldername);
		
		$this->load->model("multi_gallery_model");
		
		$data = $this->multi_gallery_model->del_image_from_db($imageid);
		
		if ($data) {
			$this->load->helper('file');
			
			$werfull = str_replace('\\', '/', realpath('') . '/img/mgallery/big/');
			$werfull = $werfull . $data["big"];
			
			if (get_file_info($werfull)) {
				unlink($werfull);
			}
			
			$werthumb = str_replace('\\', '/', realpath('') . '/img/mgallery/thumb/');
			$werthumb = $werthumb . $data["thumb"];
			
			if (get_file_info($werthumb)) {
				unlink($werthumb);
			}
			
			$wermid = str_replace('\\', '/', realpath('') . '/img/mgallery/mid/');
			$wermid = $wermid . $data["mid"];
			
			if (get_file_info($wermid)) {
				unlink($wermid);
			}
		}
		
		redirect("mgallery/view/" . $galleryname . "/" . $foldername);
	}
}