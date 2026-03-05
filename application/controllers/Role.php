<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends CI_Controller {

	public function index()
	{
		if(  $this->session->logged_in ){
			
			if(check_privilege('role', $this->session->user['id_role'], 'voir')){

				$crud 					= new grocery_CRUD();
				$settings_link_active	= 'link_menu_active';

				//$crud->where( ['id_role!='=>1] );

				$crud->set_table('role');
				$crud->columns('id_role','designation');
				$crud->display_as('id_role','ID');
				$crud->display_as('designation','Désignation');
				$crud->display_as('id_pays','Pays ');
				$crud->display_as('system','System');
				$crud->display_as('elligible_type_entreprise','elligible type entreprise');
				$crud->display_as('token_role','Token Role');
				$crud->display_as('is_deleted','is deleted');
				$crud->set_subject('Role');

				$crud->add_fields(array('designation'));
				$crud->edit_fields(array('designation'));
				

				
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));
					
				$crud->required_fields('designation');
				$crud->unset_clone();
				$crud->order_by('id_role','desc');
				$this->stateDisplay($crud);

				if( ! check_privilege('role', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('role', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( ! check_privilege('role', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}

				if( ! check_privilege('role', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}

				if( check_privilege('role', $this->session->user['id_role'], 'ajouter') ){
					$crud->add_action('Permissions', '', '', 'fa-cog',array($this,'return_link_to_permission'));
				}

				$output = $crud->render();

				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['settings_link_active'=>$settings_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("role/role", ['header' => $header, 'navbar'=>$navbar, 'sidebar' => $sidebar, 'footer' => $footer,'output'=>$output->output]);
	
				
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
				$crud->set_subject("Un Role ");
				break;
			case 'list':
				$crud->set_subject("Un Role ");
				break;
			case 'add':
				$crud->set_subject("D'Un Role ");
				break;
			
			case 'edit':
				$crud->set_subject("D'Un Role ");
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
			"nom_table"=> 'role ',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout d'un Role  ".$post_array["designation"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowUpdated($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'role',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour du role  ".$post_array["designation"],
			"date_heure" => date("Y-m-d H:m:i")
		];

    
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data = ['id_role'=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'role',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "suppression",
			"text_descriptif" => "suppression du role ".$this->ModelGetTableRow->getObjectFieldValue($data,'role')->designation,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	
	function permissions($id_role=null){
		if($this->session->logged_in){
			//check_privilege('role_permission', $this->session->user['id_role'], 'voir')
			if(true){
				$role = $this->Role_model->get_role( $id_role );
				//var_dump($role); die;
				if(!empty($role)){
					// Tous les groupes de fonctionnalites
					$group_fonctionnalites = [];
					$groupes = $this->Group_fonctionnalite_model->get_all_group_fonctionnalites();
					if(!empty($groupes)){
						foreach($groupes as $group){
							// Afficher les fonctionnalites du groupe
							$fonctionnalites = $this->Fonctionnalite_model->get_all_fonctionnalites_by_idgroup($group['id_group_fonctionnalite']);
							$all_fonctionnalites = [];
							if(!empty($fonctionnalites)){
								foreach($fonctionnalites as $fonct){
									// Afficher les permissions selon le role
									$permission = $this->Role_permission_model->get_permission_by_idfonctionnalite_and_idrole($role['id_role'], $fonct['id_fonctionnalite']);
									$fonct['permission'] = $permission;
									array_push($all_fonctionnalites, $fonct);
								}
							}
							$group['fonctionnalites'] = $all_fonctionnalites;
							array_push($group_fonctionnalites, $group);
						}
					}
		
					$header 	= $this->load->view("role/role_header", [], true);
					$footer 	= $this->load->view("role/role_footer", [], true);
					$this->load->view("role/permissions", ['header' => $header,'footer' => $footer, 'group_fonctionnalites' => $group_fonctionnalites, 'role' => $role]);

				} else {
					redirect($_SERVER['HTTP_REFERER']);
				}
			} else {
				redirect($_SERVER['HTTP_REFERER']);
			}
		} else {
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	function save_permissions($id_role=null){
		$role = $this->Role_model->get_role( $id_role );
		//var_dump($role); die;
		if(!empty($role)){
			if(isset($_POST) && count($_POST) > 0){
				$fonct_post = $this->input->post("fonctionnalites");
				 //echoTab($_POST); 

				foreach($fonct_post as $fp){
					$check_post_view = $this->input->post("can_view-".$fp);
					$check_post_add = $this->input->post("can_add-".$fp);
					$check_post_edit = $this->input->post("can_edit-".$fp);
					$check_post_delete = $this->input->post("can_delete-".$fp);

				   

					$exist_permission = $this->Role_permission_model->get_permission_by_idfonctionnalite_and_idrole($role['id_role'], $fp);
					if(empty($exist_permission)){
						// Mise a jour des permissions
						$params = array(
							'id_role' => $role['id_role'],
							'id_fonctionnalite' => $fp,
							'peux_voir' => isset($check_post_view)  == 'on' ? 1 : 0,
							'peux_ajouter' => isset($check_post_add)  == 'on' ? 1 : 0,
							'peux_editer' => isset($check_post_edit)  == 'on' ? 1 : 0,
							'peux_supprimer' => isset($check_post_delete)  == 'on' ? 1 : 0,
							'date_heure' => date("Y-m-d H:i:s")
						);
						$insert = $this->Role_permission_model->add($params);
					} else {
						// Insertion des permissions
						$params = array(
							'peux_voir' => isset($check_post_view)  == 'on' ? 1 : 0,
							'peux_ajouter' => isset($check_post_add)  == 'on' ? 1 : 0,
							'peux_editer' => isset($check_post_edit)  == 'on' ? 1 : 0,
							'peux_supprimer' => isset($check_post_delete)  == 'on' ? 1 : 0,
							'date_heure' => date("Y-m-d H:i:s")
						);
						$update = $this->Role_permission_model->update($params, $role['id_role'], $fp);
					}
				}
				$action_data = array(
					"id_utilisateur" => $_SESSION['user']['id_utilisateur'],
					"nom_table" => "role_permission",
					"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
					"id_champ" => 0,
					"action" => "Insertion ou mise à jour",
					"text_descriptif" => "Mise à jour des permissions du rôle ".$role['designation'],
					"date_heure" => date("Y-m-d H:i:s")
				);

				$this->Action_utilisateur_model->add($action_data);
				redirect("Role/permissions/".$role['id_role']);
			} else {
				redirect($_SERVER['HTTP_REFERER']);
			}
			
		} else {
			redirect($_SERVER['HTTP_REFERER']);
		}
		
	}


	function return_link_to_permission($primary_key , $row){
		return site_url('Role/permissions/').$row->id_role;
	}
	

}
