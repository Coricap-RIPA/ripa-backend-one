<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entreprise extends CI_Controller {

	public function index( $reset = null)
	{
		if(  $this->session->logged_in ){
			if(check_privilege('entreprise', $this->session->user['id_role'], 'voir')){

				$crud = new grocery_CRUD();
				$crud->where("is_systeme <> 1");
				

				if( isset( $_POST['start_date'] )  AND isset($_POST['end_date'])){
					$sql = "date_enregistrement BETWEEN'".$_POST['start_date']."' AND '".$_POST['end_date']."'";
					$crud->where($sql);
				}

				$crud->set_table('entreprise');
				$crud->columns('id_entreprise','nom','nom_marchand','telephone_contact','logo','date_enregistrement');

				$crud->display_as('id_entreprise','#');
				$crud->display_as('nom','Nom');
				$crud->display_as('nom_marchand','Short Code');
				$crud->display_as('telephone_contact','Tél de Paiement / contact ');
				$crud->display_as('email','Adresse mail du marchand');
				$crud->display_as('logo','logo du marchand ');
				$crud->display_as('date_enregistrement',' Date d\'enregistrement ');
				$crud->set_subject('Un Marchand');

				$crud->add_fields( array('nom','nom_marchand','telephone_contact','email','logo','date_enregistrement','is_systeme','token',) );
				$crud->edit_fields( array('nom','nom_marchand','telephone_contact','email','logo','token') );
	
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));
	
				$crud->required_fields('telephone_contact','nom');
				$crud->unique_fields(array('email','nom_marchand'));
				$crud->field_type('is_systeme', 'hidden',2);
				$crud->field_type('date_enregistrement', 'hidden', date('Y-m-d'));
				$crud->unset_clone();
				$crud->set_field_upload('logo','assets/uploads/files');
			
				$this->stateDisplay($crud);

				if( ! check_privilege('entreprise', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('entreprise', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( ! check_privilege('entreprise', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}

				if( ! check_privilege('entreprise', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}
	
				$crud->order_by('id_entreprise','desc');

				$output = $crud->render();
	
				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", [], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("entreprise/entreprise", ['header' => $header, 'navbar'=>$navbar, 'sidebar' => $sidebar, 'footer' => $footer,'output'=>$output->output]);	

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
				$crud->callback_field('logo',array($this,'_showImage'));
				$crud->set_subject("Un Marchand ");
				break;
			case 'list':
				$crud->set_subject("Un Marchand ");
				$crud->callback_field('logo',array($this,'_showImage'));
				break;
			case 'add':
				$crud->set_subject("D'Un Marchand ");
				break;
			
			case 'edit':
				$crud->set_subject("D'Un Marchand ");
				break;
			
			default:
				break;
		}

	}

	public function _showImage($value,$row)
	{
		return "<img src='".base_url('assets/uploads/files/'.$value)."'  height='100px' style='width: auto; object-fit: content;'/>";
	}

	public function _onRowInserted($post_array, $primary_key)
	{
		//$message = "<b> Bonjour ".$post_array['nom'].". Votre compte entreprise a bien été crée. </b><br> Dee-Pay Service.";
		//sendMail(ADMIN_EMAIL, $post_array['email'],null,'Création du compte entreprise',$message);
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"nom_table"=> 'Entreprise',
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout du Marchand ".$post_array["nom"],
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
			"nom_table"=> 'Entreprise',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour du Marchand ".$post_array["nom"],
			"date_heure" => date("Y-m-d H:m:i")
		];

    
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data = ['id_entreprise'=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"nom_table"=> 'Entreprise',
			"id_champ"=> $primary_key,
			"action" => "suppression",
			"text_descriptif" => "suppression du Marchand ".$this->ModelGetTableRow->getObjectFieldValue($data,'entreprise')->nom,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}
	
}
