<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation extends CI_Controller {

	public function index($id_pour_entreprise=null)
	{
		if(  $this->session->logged_in ){

			if(check_privilege('quotation', $this->session->user['id_role'], 'voir')){

				$crud = new grocery_CRUD();

				$crud->where(['id_quot_pour_entreprise'=>$id_pour_entreprise]);

				$nom_entreprise = $this->ModelGetTableRow->getObjectFieldValue(['id_entreprise'=>$id_pour_entreprise],'entreprise')->nom;
				$devise = '$';

				if( isset( $_POST['start_date'] )  AND isset($_POST['end_date'])){
					$sql = "date_enregistrement BETWEEN'".$_POST['start_date']."' AND '".$_POST['end_date']."' AND id_quot_pour_entreprise = ".$_POST['id_pour_entreprise']."";
					$crud->where($sql);
				}

				if($id_pour_entreprise == 4){
					$devise = 'Rand';
				}
				
				$crud->set_table('quotation');
				$crud->columns('id_quotation','id_entreprise','prix_quotation','marchandise','fichier_quotation','num_quotation');
				$crud->display_as('id_entreprise','quotation pour l\'entreprise');
				$crud->display_as('prix_quotation','Prix '.$devise);
				$crud->display_as('marchandise','Marchandises');
				$crud->display_as('fichier_quotation','Fichier preuve de quotation');
				$crud->display_as('date_enregistrement',' Date d\'enregistrement');
				$crud->display_as('num_quotation','numéro de la quotation');
				
				$crud->set_subject('Une quotation');

				$crud->add_fields(array('id_entreprise','prix_quotation','marchandise','fichier_quotation','num_quotation','date_enregistrement','id_quot_pour_entreprise'));
				$crud->edit_fields(array('id_entreprise','prix_quotation','marchandise','fichier_quotation','num_quotation','date_enregistrement','id_quot_pour_entreprise'));
				

				$crud->callback_before_insert(array($this,'_onRowBeforeInserted'));
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));
				

				$crud->set_relation('id_entreprise','entreprise','nom','is_systeme <>1');

				$crud->set_field_upload('fichier_quotation','assets/uploads/files');
				$crud->field_type('id_quot_pour_entreprise', 'hidden',$id_pour_entreprise);

				$crud->required_fields('id_entreprise','prix_quotation','marchandise','fichier_quotation');			
				$crud->unset_clone();
				$this->stateDisplay($crud);

				if( ! check_privilege('quotation', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('quotation', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( ! check_privilege('quotation', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}

				if( ! check_privilege('quotation', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}

				
				$crud->order_by('id_quotation','desc');

				$output = $crud->render();

				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/navbar", [], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("quotation/quotation", ['header' => $header, 'navbar'=>$navbar, 'sidebar' => $sidebar, 'footer' => $footer,'devise'=>$devise,'id_pour_entreprise'=>$id_pour_entreprise,'nom_entreprise'=>$nom_entreprise,'output'=>$output->output]);
		

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
				$crud->set_subject("Une quotation ");
				break;
			case 'list':
				$crud->set_subject("Une quotation");				
				break;
			case 'add':
				$crud->field_type('num_quotation', 'hidden');
				$crud->field_type('date_enregistrement', 'hidden');
				break;
			
			case 'edit':
				$crud->field_type('num_quotation', 'hidden');
				$crud->field_type('date_enregistrement', 'hidden');
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
		$post_array['num_quotation'] = rand(100000, 999999);
		$post_array['date_enregistrement'] = date("Y-m-d H:m:s");
		return $post_array;
	}


	public function _onRowInserted($post_array, $primary_key)
	{	
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'quotation ',
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout d'Une quotation ".$post_array["num_quotation"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}


	public function _onRowUpdated($post_array, $primary_key)
	{		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'quotation',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour de la quotation  ".$post_array["num_quotation"],
			"date_heure" => date("Y-m-d H:m:i")
		];
    
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data = ['id_quotation'=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'quotation',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "suppression de la quotation ".$this->ModelGetTableRow->getObjectFieldValue($data,'commmande')->num_quotation,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

}
