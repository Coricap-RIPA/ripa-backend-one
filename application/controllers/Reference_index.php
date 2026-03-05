<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reference_index extends CI_Controller {

	public function index($show_article=null,$id_article=null)
	{
		if(  $this->session->logged_in ){

			if(check_privilege('reference_index', $this->session->user['id_role'], 'voir')){

				$crud = new grocery_CRUD();
				$settings_link_active	= 'link_menu_active';

				if( (int) $_SESSION['role']['id_role']<>1 AND (int) $_SESSION['role']['id_role']<>14  ){
					$crud->where( ['id_foreign_entreprise'=> (int) $_SESSION['user']['id_entreprise_utilisateur'] ] );
				}

				$crud->set_table('reference_index');
				$crud->columns('id_reference_index','num_start_reference','num_reference_index','id_foreign_entreprise');
				
				$crud->display_as('id_reference_index','#');
				$crud->display_as('num_start_reference','Index de début');
				$crud->display_as('num_reference_index','Dernier numéro de reference');
				$crud->display_as('id_foreign_entreprise','Marchand');
				
				$crud->set_subject('Une index de reference');

				$crud->add_fields(array('num_start_reference','num_reference_index','id_foreign_entreprise'));
				$crud->edit_fields(array('num_start_reference','num_reference_index','id_foreign_entreprise'));
				

				$crud->callback_before_insert(array($this,'_onRowBeforeInserted'));
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_before_update(array($this,'_onRowBeforeUpdated'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));


				$crud->set_relation('id_foreign_entreprise','entreprise','nom');
				$crud->required_fields('num_start_reference','num_start_reference','id_foreign_entreprise');
				$crud->unset_clone();
				$this->stateDisplay($crud);

				if( ! check_privilege('reference_index', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('reference_index', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( ! check_privilege('reference_index', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}

				if( ! check_privilege('reference_index', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}


				
				$crud->order_by('id_reference_index','desc');

				$output = $crud->render();

				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['settings_link_active'=>$settings_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("reference_index/reference_index", ['header' => $header, 'navbar'=>$navbar, 'sidebar' => $sidebar, 'footer' => $footer,'output'=>$output->output]);
		

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
				$crud->set_subject("Un index de reference ");
				break;
			case 'list':
				$crud->set_subject("Un index de reference");				
				break;
			case 'add':
				break;
			
			case 'edit':
				break;
			
			default:
				break;
		}

	}

	public function _showImage($value,$row)
	{
		return "<img src='".base_url('assets/uploads/files/'.$value)."' width='100px' height='100px' style='object-fit: content;'/>";
	}


	public function _onRowBeforeInserted($post_array)
	{
		return $post_array;
	}


	public function _onRowInserted($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'reference_index ',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout d'Un index de reference ".$post_array["num_start_reference"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}


	public function _onRowBeforeUpdated($post_array,$primary_key)
	{
		if( (int)$post_array['num_start_reference']  >= (int)$post_array['num_reference_index']  ){
			$post_array['num_reference_index']  =  $post_array['num_start_reference'];
		}
		return $post_array;
	}


	public function _onRowUpdated($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'reference_index',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour de l' index de reference  ".$post_array["num_start_reference"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data = ['id_reference_index'=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'reference_index',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "suppression",
			"text_descriptif" => "suppression de l' index de reference ".$this->ModelGetTableRow->getObjectFieldValue($data,'reference_index')->num_start_reference,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}
	
}
