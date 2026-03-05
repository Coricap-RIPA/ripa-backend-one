<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function index()
	{
		if(  $this->session->logged_in ){
			$crud = new grocery_CRUD();
			$crud->set_table('sexe');
			$output = $crud->render();	
			$dashboard_link_active = "link_menu_active";
			$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
			$navbar = $this->load->view("layouts/menu_nav_bar", ['dashboard_link_active'=>$dashboard_link_active], true);
			$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
			$this->load->view("dashboard/dashboard", ['header' => $header, 'navbar'=>$navbar, 'footer' => $footer,'output'=>$output->output]);
		}else{
			redirect("Starter/login");
		}
	}

}
