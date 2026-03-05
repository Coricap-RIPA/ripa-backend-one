<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {

	public function index()
	{
		if(  $this->session->logged_in ){
			if(check_privilege('client', $this->session->user['id_role'], 'voir')){

				$crud 					= new grocery_CRUD();
				$settings_link_active	= 'link_menu_active';

				$crud->set_table('client');
				$crud->columns('id_client','nom_client','tel_whatsapp_client','adresse_livraison_client','date_enregistrement_client');
				$crud->display_as('nom_client','Nom Client');
				$crud->display_as('adresse_livraison_client','Adresse');
				$crud->display_as('tel_whatsapp_client','Tél/Whatsap');
				$crud->display_as('date_enregistrement_client','Date enregistrement');
				$crud->set_subject('Un client');
	
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));

				
	
				$crud->required_fields('nom_client','tel_whatsapp_client');
				$crud->unique_fields(array('tel_whatsapp_client'));
				$crud->field_type('date_enregistrement_client', 'hidden');
				$crud->unset_clone();
				
				$this->stateDisplay($crud);

				if( ! check_privilege('client', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('client', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( ! check_privilege('client', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}

				if( ! check_privilege('client', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}

	
				$crud->order_by('id_client','desc');

				$output = $crud->render();
	
				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['settings_link_active'=>$settings_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("client/client", ['header' => $header, 'navbar'=>$navbar, 'sidebar' => $sidebar, 'footer' => $footer,'output'=>$output->output]);	

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
				$crud->set_subject("Un client ");
				break;
			case 'list':
				$crud->set_subject("Un client ");
				break;
			case 'add':
				$crud->set_subject("D'Un client ");
				$crud->field_type('date_enregistrement_client', 'hidden',date('Y-m-d'));
				break;
			
			case 'edit':
				$crud->set_subject("D'Un client ");
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
			"nom_table"=> 'client',
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout du client ".$post_array["nom_client"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowUpdated($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'client',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour du client ".$post_array["nom_client"],
			"date_heure" => date("Y-m-d H:m:i")
		];

    
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data = ['id_client'=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'client',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "suppression du client ".$this->ModelGetTableRow->getObjectFieldValue($data,'client')->nom_client,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}


	

	
}
