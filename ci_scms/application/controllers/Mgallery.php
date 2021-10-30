<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mgallery extends V_Controller {

	public function index($page = "")
	{
		$this->load->helper("url");
		redirect('gallery/view/');
	}
	
	public function view($gallery = "", $folder = "", $picture = "")
	{
		if (!$gallery || !$folder) {
			$this->load->helper("url");
			redirect("main/index");
		}
		
		$this->load->model("multi_gallery_model");
		
		$data["is_admin"] = $this->is_admin;
		
		$p = $this->multi_gallery_model->get_page_and_id_from_gallery_name($gallery);
		
		if (!$p) {
			$this->load->helper("url");
			redirect("main/index");
		}
		
		$page = $p["page"];
		$pageid = $p["pageid"];
		
		$data["pageid"] = $pageid;
		$data["page"] = $page;
		
		$data["gallery_info"] = $this->multi_gallery_model->get_multi_gallery_folder($gallery, $folder);
		$data["gallery_data"] = $this->multi_gallery_model->get_multi_gallery_folder_images($data["gallery_info"]["id"]);
		
		$picdata = null;
		
		if ($data["gallery_data"]) {
			$found = false;
			foreach ($data["gallery_data"] as $d) {
				if ($d["link"] == strval($picture)) {
					$picdata = $d;
					$found = true;
					break;
				}		
				/*
				if (is_numeric($picture)) {
					if ($d["id"] == intval($picture)) {
						$picdata = $d;
						$found = true;
						break;
					}			
				} else {
					if ($d["name"] == $picture) {
						$picdata = $d;
						$found = true;
						break;
					}
				}*/
			}
			
			if (!$found) {
				if ($data["gallery_data"]) {
					$picdata = $data["gallery_data"][0];
				}
			}
		}
		
		$data["curr_pic"] = $picdata;
		
		$data["galleryname"] = $gallery;
		$data["currpic"] = $picture;
		
		$this->_send_headers($page, "gallery");
		
		$this->load->view("multigallery", $data);
		
		$this->_send_footer();
	}
}