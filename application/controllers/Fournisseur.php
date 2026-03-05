<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fournisseur extends CI_Controller {

	public function index()
	{
		if(  $this->session->logged_in ){
			if(check_privilege('fournisseur', $this->session->user['id_role'], 'voir')){

				$crud 					= new grocery_CRUD();
				$settings_link_active	= 'link_menu_active';

				$crud->where(['is_hiden'=>2]);

				$crud->set_table('fournisseur');
				$crud->columns('id_fournisseur','nom_fournisseur','tel_fournisseur','logo_fournisseur','date_enregistrement');
				
				$crud->display_as('id_fournisseur','#');
				$crud->display_as('nom_fournisseur','Fournisseur');
				$crud->display_as('tel_fournisseur','Téléphone de contact');
				$crud->display_as('logo_fournisseur','logo');
				$crud->display_as('date_enregistrement','Date enregistrement');
				$crud->set_subject('Un  fournisseur');
	
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));
	
				$crud->set_field_upload('logo_fournisseur','assets/uploads/files');
				$crud->required_fields('tel_fournisseur','nom_fournisseur');
				$crud->unique_fields(array('nom_fournisseur'));
				$crud->field_type('date_enregistrement', 'hidden');
				$crud->field_type('is_hiden', 'hidden',2);
				$crud->unset_clone();
				$this->stateDisplay($crud);

				if( ! check_privilege('fournisseur', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('fournisseur', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( ! check_privilege('fournisseur', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}

				if( ! check_privilege('fournisseur', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}
	
				$crud->order_by('id_fournisseur','desc');

				$output = $crud->render();
	
				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['settings_link_active'=>$settings_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("fournisseur/fournisseur", ['header' => $header, 'navbar'=>$navbar, 'sidebar' => $sidebar, 'footer' => $footer,'output'=>$output->output]);	

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
				$crud->set_subject("Un fournisseur ");
				break;
			case 'list':
				$crud->set_subject("Un fournisseur ");
				break;
			case 'add':
				$crud->set_subject("D'Un fournisseur ");
				$crud->field_type('date_enregistrement', 'hidden',date('Y-m-d'));
				break;
			
			case 'edit':
				$crud->set_subject("D'Un fournisseur ");
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
			"nom_table"=> 'fournisseur',
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout du fournisseur ".$post_array["nom_fournisseur"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowUpdated($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'fournisseur',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour du fournisseur ".$post_array["nom_fournisseur"],
			"date_heure" => date("Y-m-d H:m:i")
		];
    
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data = ['id_fournisseur'=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'fournisseur',
			"id_champ"=> $primary_key,
			"action" => "suppression",
			"text_descriptif" => "suppression du fournisseur ".$this->ModelGetTableRow->getObjectFieldValue($data,'fournisseur')->nom_fournisseur,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	
}
