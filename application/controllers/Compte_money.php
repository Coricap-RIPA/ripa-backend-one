<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compte_money extends CI_Controller {

	public function index()
	{
		if(  $this->session->logged_in ){

			if(check_privilege('compte_money', $this->session->user['id_role'], 'voir')){
				
				
				$crud = new grocery_CRUD();

				if( (int) $_SESSION['role']['id_role']<>1 AND (int) $_SESSION['role']['id_role']<>14  ){
					$crud->where( ['id_entreprise_cliente'=> (int) $_SESSION['user']['id_entreprise_utilisateur'] ] );
				}
				
				$crud->set_table('compte_money_entreprise');
				$crud->columns('id_compte_money_entreprise','num_mobile_money','Type','id_foreign_devise','Entreprise');
				$crud->display_as('id_compte_money_entreprise','ID');
				$crud->display_as('id_entreprise_cliente','Marchand');
				$crud->display_as('num_mobile_money','Numéro de compte '); 
				$crud->display_as('id_foreign_devise','Device'); 
				$crud->display_as('date_enregistrement','Enregistré le : '); 
				$crud->display_as('is_active','Actif');
				$crud->display_as('id_type_money','Type de compte');

				$crud->set_subject('Compte de paiement');

				$crud->add_fields(array('num_mobile_money','id_foreign_devise','id_entreprise_cliente','date_enregistrement','is_active','id_type_money'));
				$crud->edit_fields(array('num_mobile_money','id_foreign_devise','id_entreprise_cliente','is_active','id_type_money'));
				
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));

				$crud->callback_column('is_active',array($this,'_callback_status'));
				$crud->callback_column('Entreprise',array($this,'_callback_entreprise'));
				$crud->callback_column('Type',array($this,'_callback_type_mobile_money'));
				
				$crud->unique_fields('num_mobile_money');
				$crud->set_rules('num_mobile_money','Numéro de Compte money ','numeric');
				$crud->required_fields('num_mobile_money');
				$crud->unset_clone();

				$crud->field_type('is_active','hidden', 1);
				$crud->field_type('date_enregistrement','hidden',getCurrentDate());
				//$crud->field_type('id_type_money','hidden');
				//$crud->field_type('id_entreprise_cliente','hidden', 1 );

				$crud->set_relation('id_foreign_devise','devise','designation');
				$crud->set_relation('id_entreprise_cliente','entreprise','nom');
				$crud->set_relation('id_type_money','type_mobile_money','designation');

				$crud->order_by('id_compte_money_entreprise','desc');
				$this->stateDisplay($crud);

				
				$crud->unset_clone();

				if( ! check_privilege('compte_money', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('compte_money', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}

				if( ! check_privilege('compte_money', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}

				if( ! check_privilege('compte_money', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				// if( check_privilege('compte_money', $this->session->user['id_role'], 'editer') ){
				// 	$crud->add_action('Actif | Non Actif', '', '', 'record',array($this,'_status_compte_money'));
				// }

				
				$output = $crud->render();

				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", [], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("compte_money/compte_money", ['header' => $header, 'navbar'=>$navbar, 'sidebar' => $sidebar, 'footer' => $footer,'output'=>$output->output]);
	
				
			}else{
				redirect($_SERVER['HTTP_REFERER']);
			}
			
		}else {
			redirect("Starter/login");
		}
	
	}

	public function activecompte ($sheme_1=null, $sheme_2=null, $id_compte=null){

		if(isset($_POST['id_cpt_money'])) $id_compte = (int) $_POST['id_cpt_money'];

		if( ($id_compte <> null AND $id_compte<>0 AND is_int( (int) $id_compte))  ){

			$is_active = $this->Compte_money_model->get_compte_money_entreprise($id_compte)['is_active'];

			$update_status_compte =[
				'is_active'=> 0
			];

			$this->Compte_money_model->update_status_compte_money_entreprises($update_status_compte);
			
			if((int) $is_active == 1){
				$update_status_compte =[
					'is_active'=> 0
				];
			}else{
				$update_status_compte =[
					'is_active'=> 1
				];
			}

			$this->Compte_money_model->update_compte_money_entreprises($id_compte,$update_status_compte);
		}

		(isset($_POST['id_cpt_money'])) ? redirect('Dashboard/') : redirect('Compte_money/');
	}

	public function stateDisplay($crud){
		$state = $crud->getState();

		switch ($state) {
			case 'read':
				$crud->set_subject("Un Compte de paiement ");
				break;
			case 'list':
				$crud->set_subject("Un Compte de paiement ");
				break;
			case 'add':
				$crud->set_subject("D'un Compte de paiement ");
				break;
			
			case 'edit':
				$crud->set_subject("D'Un Compte de paiement ");
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
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"nom_table"=> 'compte_money_entreprise',
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout d'un Compte de paiement  ".$post_array["num_mobile_money"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}


	public function _onRowUpdated($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"nom_table"=> 'compte_money_entreprise',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour du Compte de paiement  ".$post_array["num_mobile_money"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data = ['id_compte_money_entreprise'=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"nom_table"=> 'compte_money_entreprise',
			"id_champ"=> $primary_key,
			"action" => "suppression",
			"text_descriptif" => "suppression du Compte de paiement ".$this->ModelGetTableRow->getObjectFieldValue($data,'compte_money_entreprise')->num_mobile_money,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}


	public function _callback_entreprise($value, $row){
		$entreprise = $this->Entreprise_model->get_entreprise($row->id_entreprise_cliente);
		if(!empty($entreprise)){
			return  "<img src='".base_url('assets/uploads/files/').$entreprise['logo']."'  style='witdh auto; height:40px; object-fit: scale-down;'/> <br/>".$entreprise['nom'];
		}
	}

	public function _callback_type_mobile_money($value, $row){
		$type_mobile_money = $this->Type_mobile_money_model->get_type_mobile_money($row->id_type_money);
		if(!empty($type_mobile_money)){
			return  "<img src='".base_url('assets/uploads/files/').$type_mobile_money['logo']."'  style='witdh auto; height:50px; object-fit: scale-down !important;'/> <br/>".$type_mobile_money['designation'];
		}
	}
	

	public function _callback_status($value, $row){
		if((int) $value == 0)
			return  '<span class="badge badge-danger">non actif</span>';
		else
			return '<span class="badge badge-success">Actif</span>';	
	}

	function _status_compte_money($primary_key , $row){		
		return site_url('Compte_money/activecompte/'.str_shuffle('abdcuxz2345').'/'.str_shuffle('abdcuxz2345').'/'.$row->id_compte_money_entreprise.'/');
	}

}
