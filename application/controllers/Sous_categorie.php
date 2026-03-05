<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sous_categorie extends CI_Controller {

	public function index()
	{
		if(  $this->session->logged_in ){
			if(check_privilege('sous_categorie', $this->session->user['id_role'], 'voir')){


				$crud 					= new grocery_CRUD();
				$settings_link_active	= 'link_menu_active';


				$crud->set_table('sous_categorie');
				$crud->columns('id_sous_categorie','nom_sous_categorie','icon_sous_categorie','date_enregistrement_sous_categorie');
				
				$crud->display_as('id_sous_categorie','#');
				$crud->display_as('nom_sous_categorie','Designation');
				$crud->display_as('icon_sous_categorie','Icon');
				$crud->display_as('date_enregistrement_sous_categorie','Date enregistrement');

				$crud->set_subject('Une  Sous Categorie');
	
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));
	
				$crud->required_fields('nom_sous_categorie','cigle_sous_categorie');
				$crud->field_type('date_enregistrement_sous_categorie', 'hidden');
				$crud->unset_clone();
				$this->stateDisplay($crud);

				if( ! check_privilege('sous_categorie', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('sous_categorie', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( ! check_privilege('sous_categorie', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}

				if( ! check_privilege('sous_categorie', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}
	
				$crud->order_by('id_sous_categorie','desc');

				$output = $crud->render();
	
				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['settings_link_active'=>$settings_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("sous_categorie/sous_categorie", ['header' => $header, 'navbar'=>$navbar, 'sidebar' => $sidebar, 'footer' => $footer,'output'=>$output->output]);	

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
				$crud->set_subject("Une Sous Categorie ");
				break;
			case 'list':
				$crud->set_subject("Une Sous Categorie ");
				break;
			case 'add':
				$crud->set_subject("D'Une Sous Categorie ");
				$crud->field_type('date_enregistrement_sous_categorie', 'hidden',date('Y-m-d'));
				break;
			
			case 'edit':
				$crud->set_subject("D'Une Sous Categorie ");
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
			"nom_table"=> 'sous_categorie',
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout de la sous_categorie ".$post_array["nom_sous_categorie"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowUpdated($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'sous_categorie',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour de la sous_categorie ".$post_array["nom_sous_categorie"],
			"date_heure" => date("Y-m-d H:m:i")
		];

    
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data = ['id_sous_categorie'=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'sous_categorie',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "suppression de la sous_categorie ".$this->ModelGetTableRow->getObjectFieldValue($data,'sous_categorie')->nom_sous_categorie,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	
}
