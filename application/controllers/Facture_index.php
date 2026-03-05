<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facture_index extends CI_Controller {

	public function index($show_article=null,$id_article=null)
	{
		if(  $this->session->logged_in ){

			if(check_privilege('facture_index', $this->session->user['id_role'], 'voir')){

				$crud = new grocery_CRUD();
				$settings_link_active	= 'link_menu_active';

				if( (int) $_SESSION['role']['id_role']<>1 AND (int) $_SESSION['role']['id_role']<>14  ){
					$crud->where( ['id_foreign_entreprise'=> (int) $_SESSION['user']['id_entreprise_utilisateur'] ] );
				}

				$crud->set_table('facture_index');
				$crud->columns('id_facture_index','num_start_facture','num_facture_index','id_foreign_entreprise');
				
				$crud->display_as('id_facture_index','#');
				$crud->display_as('num_start_facture','Index de début');
				$crud->display_as('num_facture_index','Dernier numéro de facture');
				$crud->display_as('id_foreign_entreprise','Marchand');
				
				$crud->set_subject('Une index de facture');

				$crud->add_fields(array('num_start_facture','num_facture_index','id_foreign_entreprise'));
				$crud->edit_fields(array('num_start_facture','num_facture_index','id_foreign_entreprise'));
				

				$crud->callback_before_insert(array($this,'_onRowBeforeInserted'));
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_before_update(array($this,'_onRowBeforeUpdated'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));


				$crud->set_relation('id_foreign_entreprise','entreprise','nom');
				$crud->required_fields('num_start_facture','num_start_facture','id_foreign_entreprise');
				$crud->unset_clone();
				$this->stateDisplay($crud);

				if( ! check_privilege('facture_index', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('facture_index', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( ! check_privilege('facture_index', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}

				if( ! check_privilege('facture_index', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}


				
				$crud->order_by('id_facture_index','desc');

				$output = $crud->render();

				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['settings_link_active'=>$settings_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("facture_index/facture_index", ['header' => $header, 'navbar'=>$navbar, 'sidebar' => $sidebar, 'footer' => $footer,'output'=>$output->output]);
		

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
				$crud->set_subject("Un index de facture ");
				break;
			case 'list':
				$crud->set_subject("Un index de facture");				
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
			"nom_table"=> 'facture_index ',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout d'Un index de facture ".$post_array["num_start_facture"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}


	public function _onRowBeforeUpdated($post_array,$primary_key)
	{
		if( (int)$post_array['num_start_facture']  >= (int)$post_array['num_facture_index']  ){
			$post_array['num_facture_index']  =  $post_array['num_start_facture'];
		}
		return $post_array;
	}


	public function _onRowUpdated($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'facture_index',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour de l' index de facture  ".$post_array["num_start_facture"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data = ['id_facture_index'=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'facture_index',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "suppression",
			"text_descriptif" => "suppression de l' index de facture ".$this->ModelGetTableRow->getObjectFieldValue($data,'facture_index')->num_start_facture,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}
	
}
