<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends V_Controller {
  
  
	public function index($page = "")
	{
		$this->load->model('menu_model');
		$this->load->model("content_model");
		$data["is_admin"] = $this->is_admin;
		
		if ($page != "") {
			if (!$this->menu_model->is_page_valid($page)) {
				show_404();
			}
		}
		
		if ($page == "") {
			$data = $this->menu_model->getFirstMenuItem();
			$page = $data["link"];
		}
		
		$pageid = $this->menu_model->getPageId($page);
		$data["pageid"] = $pageid;
		$content_data;
		$contents;
		$i = 0;
		
		if ($pageid != -1) {
			$contentlinks = $this->content_model->get_page_contents($pageid);
			
			if (sizeof($contentlinks) > 0)
			{
				//first let's prep a class like array
				foreach($contentlinks as $c) {
					$contents[$i]["link"] = $c;
					
					$d = null;
					if ($c["content_type"] == "1") {
						$d = $this->content_model->get_content_text($c["content_id"]);
					} elseif ($c["content_type"] == "3") {
						$d["main"] = $this->content_model->get_content_gallery($c["content_id"]);
						$d["data"] = $this->content_model->get_gallery_data($d["main"]["id"]);
					} elseif ($c["content_type"] == "4") {
						$d["main"] = $this->content_model->get_content_multi_gallery($c["content_id"]);
						$d["folders"] = $this->content_model->get_content_multi_gallery_folders($d["main"]["id"]);
					}
					
					$contents[$i]["data"] = $d;
					
					$i++;
				}
			}
		}
		
		
		$this->_send_headers($page);
		
		$htmld;
		$j = 0;
		
		if (isset($contents)) {
			if (sizeof($contents) > 0) {
				$i = 0;
				$data["contsize"] = sizeof($contents);
				foreach ($contents as $c) {
					$data["data"] = $c["data"];
					$data["link"] = $c["link"];
					$data["i"] = $i;
					//var_dump($c["data"]);
					//var_dump($c["data"]);
					if ($c["link"]["content_type"] == 1) {
						$data["htmld"][$j] = $this->load->view("textcontent", $data, true);
					} else if ($c["link"]["content_type"] == 3) {
						$data["htmld"][$j] = $this->load->view("gallerycontent", $data, true);
					} else if ($c["link"]["content_type"] == 4) {
						$data["htmld"][$j] = $this->load->view("multigallerycontent", $data, true);
					}
					
					$i++;
					$j++;
				}
			}
		}
		
		$this->load->view("content", $data);
		$this->_send_footer();
  }
}