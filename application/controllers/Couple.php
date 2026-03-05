<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Couple extends CI_Controller {

	public function index()
	{
		if(  $this->session->logged_in ){
			if(check_privilege('couple', $this->session->user['id_role'], 'voir')){

				$crud 					= new grocery_CRUD();
				$settings_link_active	= 'link_menu_active';

				$crud->set_table('couple');
				$crud->columns('id_couple','nom_couple','tel_whatsapp_couple','code_couple','photo_couple','date_mariage');
				$crud->display_as('id_couple','#');
				$crud->display_as('nom_couple','Nom couple');
				$crud->display_as('adresse_livraison_couple','Adresse');
				$crud->display_as('tel_whatsapp_couple','Tél/Whatsap');
				$crud->display_as('code_couple','Code');
				$crud->display_as('date_mariage','Date de Mariage');
				$crud->display_as('photo_couple','Photo du Couple');
				$crud->display_as('date_enregistrement_couple','Date enregistrement');
				$crud->set_subject('Un couple');
	
				$crud->callback_before_insert(array($this, '_onRowBeforeInserted'));
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));

				
	
				$crud->required_fields('nom_couple','tel_whatsapp_couple','date_mariage');
				$crud->unique_fields(array('tel_whatsapp_couple'));
				$crud->field_type('date_enregistrement_couple', 'hidden');
				$crud->field_type('code_couple', 'hidden');
				$crud->set_field_upload('photo_couple','assets/uploads/files');
				$crud->unset_clone();
				
				$this->stateDisplay($crud);

				if( ! check_privilege('couple', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('couple', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( ! check_privilege('couple', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}

				if( ! check_privilege('couple', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}

	
				$crud->order_by('id_couple','desc');

				$output = $crud->render();
	
				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['settings_link_active'=>$settings_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("couple/couple", ['header' => $header, 'navbar'=>$navbar, 'sidebar' => $sidebar, 'footer' => $footer,'output'=>$output->output]);	

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
				$crud->set_subject("Un couple ");
				break;
			case 'list':
				$crud->set_subject("Un couple ");
				break;
			case 'add':
				$crud->set_subject("D'Un couple ");
				$crud->field_type('date_enregistrement_couple', 'hidden',date('Y-m-d'));
				break;
			
			case 'edit':
				$crud->set_subject("D'Un couple ");
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
		$code_couple = mt_rand(1000,9999);
		$couple = $this->Couple_model->get_couple_by_code_by_date($code_couple,date('Y-m-d'));
		
		if(!empty($couple)){
			$code_couple =  mt_rand(1000,9999);
		}

		$post_array['code_couple'] = $code_couple;

		return $post_array;
	}

	public function _onRowInserted($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'couple',
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout du couple ".$post_array["nom_couple"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowUpdated($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'couple',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour du couple ".$post_array["nom_couple"],
			"date_heure" => date("Y-m-d H:m:i")
		];

    
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data = ['id_couple'=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'couple',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "suppression du couple ".$this->ModelGetTableRow->getObjectFieldValue($data,'couple')->nom_couple,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}


	

	
}
