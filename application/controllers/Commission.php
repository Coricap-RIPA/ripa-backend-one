<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commission extends CI_Controller {

	public function index()
	{
		if(  $this->session->logged_in ){
			if(check_privilege('commission', $this->session->user['id_role'], 'voir')){


				$crud 					= new grocery_CRUD();
				$settings_link_active	= 'link_menu_active';


				$crud->set_table('commission');
				$crud->columns('id_commission','nom_commission','commission_dee_pay','commission_network','id_foreign_type_commission','date_enregistrement');
				
				$crud->display_as('id_commission','#');
				$crud->display_as('nom_commission','Désignation');
				$crud->display_as('commission_dee_pay','Commission RIPA');
				$crud->display_as('commission_network','Commission du réseau');
				$crud->display_as('id_foreign_type_commission','Type');
				$crud->display_as('date_enregistrement','Date enregistrement');
				$crud->set_subject('Une commission');
	
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));

				$crud->set_relation('id_foreign_type_commission','type_commission','nom_type_commission');
				$crud->required_fields('nom_commission','commission_dee_pay','commission_network','id_foreign_type_commission');
				$crud->unique_fields(array('nom_commission'));
				$crud->field_type('date_enregistrement', 'hidden');
				$crud->unset_clone();
				$crud->unset_delete();
				$this->stateDisplay($crud);

				if( ! check_privilege('commission', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('commission', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( ! check_privilege('commission', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}

	
				$crud->order_by('id_commission','desc');

				$output = $crud->render();
	
				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['settings_link_active'=>$settings_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("commission/commission", ['header' => $header, 'navbar'=>$navbar, 'sidebar' => $sidebar, 'footer' => $footer,'output'=>$output->output]);	

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
				$crud->set_subject("Une commission ");
				break;
			case 'list':
				$crud->set_subject("Une commission ");
				break;
			case 'add':
				$crud->set_subject("D'Une commission ");
				$crud->field_type('date_enregistrement', 'hidden',date('Y-m-d'));
				break;
			
			case 'edit':
				$crud->set_subject("D'Une commission ");
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
			"nom_table"=> 'commission',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout de la commission ".$post_array["nom_commission"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowUpdated($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'commission',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour de la commission ".$post_array["nom_commission"],
			"date_heure" => date("Y-m-d H:m:i")
		];

    
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data = ['id_commission'=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'commission',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "suppression",
			"text_descriptif" => "suppression de la commission ".$this->ModelGetTableRow->getObjectFieldValue($data,'commission')->nom_commission,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}
	
}
