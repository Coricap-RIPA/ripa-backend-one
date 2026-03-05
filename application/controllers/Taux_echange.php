<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Taux_echange extends CI_Controller {

	public function index($reset=null)
	{
		if(  $this->session->logged_in ){

			if(check_privilege('taux', $this->session->user['id_role'], 'voir')){

				$crud = new grocery_CRUD();

				if( $reset <> null AND (int) $reset <> 0){
					if( isset( $_POST['start_date'] )  AND isset($_POST['end_date'])){
						$sql = "date_mis_a_jour BETWEEN'".$_POST['start_date']."' AND '".$_POST['end_date']."'";
						$crud->where($sql);
					}
				}

				$crud->set_table('taux_echange');
				$crud->columns('id_taux_echange','usd_cdf','cdf_usd','date_mis_a_jour');
				$crud->display_as('id_taux_echange','ID');
				$crud->display_as('usd_cdf','USD - CDF');
				$crud->display_as('cdf_usd','CDF - USD');
				$crud->display_as('date_mis_a_jour','Date de mise à jour');

				$crud->set_subject('Taux D\'échage ');

				$crud->add_fields(array('usd_cdf','cdf_usd','date_mis_a_jour'));
				
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));
					
				$crud->required_fields('usd_cdf','cdf_usd');
				$crud->unset_clone();
				$crud->order_by('id_taux_echange','desc');
				$crud->field_type('date_mis_a_jour', 'hidden',getCurrentDate());

				$this->stateDisplay($crud);

				$crud->unset_edit();
				$crud->unset_clone();

				if( ! check_privilege('taux', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('taux', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}

				if( ! check_privilege('taux', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}

				
				$output = $crud->render();

				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", [], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("taux/taux", ['header' => $header, 'navbar'=>$navbar, 'sidebar' => $sidebar, 'footer' => $footer,'output'=>$output->output]);
	
				
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
				$crud->set_subject("Un taux d'échange ");
				break;
			case 'list':
				$crud->set_subject("Un taux d'échange  ");
				break;
			case 'add':
				$crud->set_subject("D'un taux d'échange ");
				break;
			
			case 'edit':
				$crud->set_subject("D'Un taux d'échange  ");
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
			"nom_table"=> 'taux_echange',
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout d'un taux d'échange  ".$post_array["usd_cdf"]." usd-cdf",
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
			"nom_table"=> 'taux_echange',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour du taux d'échange  ".$post_array["usd_cdf"]." usd-cdf",
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data = ['id_taux_echange'=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"nom_table"=> 'taux_echange',
			"id_champ"=> $primary_key,
			"action" => "suppression",
			"text_descriptif" => "suppression du taux d'échange ".$this->ModelGetTableRow->getObjectFieldValue($data,'taux_echange')->usd_cdf." usd-cdf",
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

}
