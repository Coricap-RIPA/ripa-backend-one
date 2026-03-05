<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publicite extends CI_Controller {

	public function index()
	{
		if(  $this->session->logged_in ){

			if(check_privilege('pub', $this->session->user['id_role'], 'voir')){

				$crud 				= new grocery_CRUD();
				$pub_link_active 	= "link_menu_active";

				if( isset( $_POST['start_date'] )  AND isset($_POST['end_date'])){
					$sql = "date_enregistrement_publicite BETWEEN'".$_POST['start_date']."' AND '".$_POST['end_date']."'";
					$crud->where($sql);
				}

				$crud->set_table('publicite');
				$crud->columns('id_publicite','image_publicite','video_publicite','nom_publicite','nom_client_pub','lien_publicite','date_enregistrement_publicite');

				$crud->display_as('id_publicite','#');
				$crud->display_as('image_publicite','Affiche');
				$crud->display_as('video_publicite','Vidéo');
				$crud->display_as('nom_publicite','Nom de la pub');
				$crud->display_as('nom_client_pub','Nom du client');
				$crud->display_as('lien_publicite','Lien');
				$crud->display_as('date_enregistrement_publicite',' Date d\'enregistrement');
				
				$crud->set_subject('Une publicité');

				$crud->add_fields(array('image_publicite','video_publicite','nom_publicite','nom_client_pub','lien_publicite','date_enregistrement_publicite'));
				$crud->edit_fields(array('image_publicite','video_publicite','nom_publicite','nom_client_pub','lien_publicite','date_enregistrement_publicite'));
				

				$crud->callback_before_insert(array($this,'_onRowBeforeInserted'));
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_before_update(array($this,'_onRowBeforeUpdated'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));

				$crud->set_field_upload('image_publicite','assets/uploads/files');
				$crud->set_field_upload('video_publicite','assets/uploads/files');
				$crud->required_fields('image_publicite','nom_publicite','nom_client_pub');			
				$crud->field_type('date_enregistrement_publicite', 'hidden');
				$crud->unset_clone();
				$this->stateDisplay($crud);

				if( ! check_privilege('pub', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('pub', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}

				if( ! check_privilege('pub', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( ! check_privilege('pub', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}

				
				$crud->order_by('id_publicite','desc');

				$output = $crud->render();

				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['pub_link_active'=>$pub_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("publicite/publicite", [
					'header' => $header, 
					'navbar'=>$navbar, 
					'sidebar' => $sidebar,
					'footer' => $footer,
					'output'=>$output->output
				]);
		

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
				$crud->set_subject("Une publicité ");
				$crud->callback_field('photo_article',array($this,'_showImage'));
				break;
			case 'list':
				$crud->set_subject("Une publicité");				
				break;
			case 'add':
				$crud->field_type('date_enregistrement_publicite', 'hidden',date('Y-m-d'));
				break;
			case 'edit':
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
		return $post_array;
	}


	public function _onRowInserted($post_array, $primary_key)
	{

		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'Publicite ',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout d'une publicité : ".$post_array['nom_publicite'],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}


	public function _onRowBeforeUpdated($post_array,$primary_key)
	{		
		return $post_array;
	}


	public function _onRowUpdated($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'Publicite',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour de la publicité  ".$post_array["nom_publicite"],
			"date_heure" => date("Y-m-d H:m:i")
		];

    
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data 		= ['id_publicite'=>$primary_key];

		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'Publicite',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "suppression",
			"text_descriptif" => "suppression de l' publicité : ".$this->ModelGetTableRow->getObjectFieldValue($data,'publicite')->nom_publicite,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}


	public function getMainSliderPubImage(){
		$publiciteSilderMain = $this->Publicite_model->get_publicite_by_type(1);
		echo json_encode($publiciteSilderMain);
	}

	public function getSousSliderPubImage(){
		$publiciteSilderMain = $this->Publicite_model->get_publicite_by_type(2);
		echo json_encode($publiciteSilderMain);
	}


}
