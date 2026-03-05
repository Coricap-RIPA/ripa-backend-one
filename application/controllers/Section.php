<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Section extends CI_Controller {

	public function index()
	{
		if(  $this->session->logged_in ){
			if(check_privilege('section_article', $this->session->user['id_role'], 'voir')){


				$crud 					= new grocery_CRUD();
				$settings_link_active	= 'link_menu_active';


				$crud->set_table('section_article');
				$crud->columns('id_section_article','nom_section_article');
				
				$crud->display_as('id_section_article','#');
				$crud->display_as('nom_section_article','Designation');
				$crud->set_subject('Une  section_article');
	
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));
	
				$crud->required_fields('nom_section_article');
				$crud->unique_fields(array('nom_section_article'));
				$crud->unset_clone();
				$this->stateDisplay($crud);

				if( ! check_privilege('section_article', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('section_article', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( ! check_privilege('section_article', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}

				if( ! check_privilege('section_article', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}

	
				$crud->order_by('id_section_article','desc');

				$output = $crud->render();
	
				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['settings_link_active'=>$settings_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("section_article/section_article", ['header' => $header, 'navbar'=>$navbar, 'sidebar' => $sidebar, 'footer' => $footer,'output'=>$output->output]);	

			}else{
				redirect($_SERVER['HTTP_REFERER']);
			}	
		}else {
			redirect("Starter/login");
		}
	}

	public function stateDisplay($crud){
		$state = $crud->getState();

		switch ($state) {
			case 'read':
				$crud->set_subject("Une section article ");
				break;
			case 'list':
				$crud->set_subject("Une section article ");
				break;
			case 'add':
				$crud->set_subject("D'Une section article ");
				break;
			
			case 'edit':
				$crud->set_subject("D'Une section article ");
				break;
			
			default:
				break;
		}

	}

	public function _showImage($value,$row)
	{
		return "<img src='".base_url('assets/uploads/files/'.$value)."' width='100px' height='100px' style='object-fit: content;'/>";
	}

	public function _onRowInserted($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'section_article',
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout de la section article ".$post_array["nom_section_article"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowUpdated($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'section_article',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour de la section article ".$post_array["nom_section_article"],
			"date_heure" => date("Y-m-d H:m:i")
		];

    
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data = ['id_section_article'=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'section_article',
			"id_champ"=> $primary_key,
			"action" => "suppression",
			"text_descriptif" => "suppression de la section article ".$this->ModelGetTableRow->getObjectFieldValue($data,'section_article')->nom_section_article,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}


	

	
}
