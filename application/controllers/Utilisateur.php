<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utilisateur extends CI_Controller {

	private $pass ;

	public function index()
	{
		if(  $this->session->logged_in ){

			if(check_privilege('compte', $this->session->user['id_role'], 'voir')){

				$crud 					= new grocery_CRUD();
				$settings_link_active	= 'link_menu_active';


				if( (int) $_SESSION['role']['id_role']<>1 AND (int) $_SESSION['role']['id_role']<>14  ){
					$crud->where( ['id_utilisateur'=> (int) $_SESSION['user']['id_utilisateur'] ] );
				}
				

				$crud->where( ['id_utilisateur!='=>9] );

				
				$crud->set_table('utilisateur');
				$crud->columns('id_utilisateur','nom','email','photo','id_role','id_entreprise_utilisateur');
				
				$crud->display_as('id_utilisateur','#');
				$crud->display_as('nom','Nom');
				$crud->display_as('post_nom','Post Nom');
				$crud->display_as('prenom','Prénom');
				$crud->display_as('email','Adresse mail de l\'Utilisateur ');
				$crud->display_as('photo','photo de l\'Utilisateur ');
				$crud->display_as('id_sexe','Sexe');
				$crud->display_as('id_role','role');
				$crud->display_as('id_etat_civil','Etat civil');
				$crud->display_as('id_entreprise_utilisateur','Entreprise Marchande');

				$crud->set_subject('D\'un utilisateur ');


				$crud->callback_before_insert(array($this,'_onRowBeforeInserted'));
				$crud->callback_before_update(array($this,'_onRowBeforeInserted'));
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));
				$crud->callback_column('nom',array($this,'_callback_nom'));

				$crud->add_fields(array('nom','post_nom','prenom','id_sexe','id_etat_civil','email','phone1','photo','id_entreprise_utilisateur','id_role','password'));
				$crud->edit_fields(array('nom','post_nom','prenom','id_sexe','id_etat_civil','email','phone1','photo','id_entreprise_utilisateur','id_role','password'));
				
				if( (int) $_SESSION['role']['id_role'] == 1){
					$crud->set_relation('id_entreprise_utilisateur','entreprise','nom');
				}else {
					$crud->field_type('id_entreprise_utilisateur', 'hidden', $_SESSION['user']['id_entreprise_utilisateur'] );
				}

				$crud->set_relation('id_role','role','designation');
				$crud->set_relation('id_sexe','sexe','designation');
				$crud->set_relation('id_etat_civil','etat_civil','designation');
				

				$crud->required_fields('email','nom','password');
				$crud->unique_fields(array('email'));
				$crud->unset_clone();
				$crud->set_field_upload('photo','assets/uploads/files');

				if( ! check_privilege('compte', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('compte', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( ! check_privilege('compte', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}

				if( ! check_privilege('compte', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}

				$this->stateDisplay($crud);

				$output = $crud->render();

				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['settings_link_active'=>$settings_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("utilisateur/utilisateur", ['header' => $header, 'navbar'=>$navbar, 'sidebar' => $sidebar, 'footer' => $footer,'output'=>$output->output,'password'=>$this->pass]);
		

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
				$crud->callback_field('photo',array($this,'_showImage'));
				$crud->set_subject("Un Utilisateur ");
				break;
			case 'list':
				$crud->set_subject("Un Utilisateur ");
				$crud->callback_field('photo',array($this,'_showImage'));
				break;
			case 'add':
				$crud->set_subject("D'un Utilisateur ");
				break;
			
			case 'edit':
				$crud->set_subject("D'un Utilisateur ");
				$crud->callback_field('password',array($this,'_mpd_edit'));
				break;
			
			default:
				break;
		}

	}

	public function _showImage($value,$row)
	{
		return "<img src='".base_url('assets/uploads/files/'.$value)."' width='100px' height='100px' style='object-fit: content;'/>";
	}

	public function _mpd_edit($value,$row)
	{
		return "<input type='text' name='password' class='form-control' />";
	}

	public function _onRowInserted($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'Utilisateur',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout de l'Utilisateur ".$post_array["nom"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowBeforeInserted($post_array)
	{
		$mdp = $post_array['password'];
		$post_array['password'] = password_hash($post_array['password'], PASSWORD_DEFAULT);
		$post_array['pass'] = $this->pass;


		$to = $post_array['email'];
		$subject = "Creation de compte";
		$txt = "votre compte a été crée sur ".base_url()." votre mot de passe est ".$mdp;
		$headers = "From: webmaster@fromatter.com" . "\r\n";

		//mail($to,$subject,$txt,$headers);

		return $post_array;
	}

	public function _onRowUpdated($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'Utilisateur',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour de l'utilisateur ".$post_array["nom"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		

		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data = ['id_utilisateur'=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'Utilisateur',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "suppression",
			"text_descriptif" => "suppression de l'utilisateur ".$this->ModelGetTableRow->getObjectFieldValue($data,'utilisateur')->nom,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _callback_nom($value, $row){
		return  $value." ".$row->post_nom." ".$row->prenom;
	}
	
}
