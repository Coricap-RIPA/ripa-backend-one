<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Action_utilisateur extends CI_Controller {

	public function index()
	{
		if(  $this->session->logged_in ){
			if(check_privilege('activite_user', $this->session->user['id_role'], 'voir')){

				$crud 					= new grocery_CRUD();
				$periode 				= '';
				$settings_link_active	= 'link_menu_active';
				$liste_utilisateurs 	= $this->Utilisateur_model->get_all_utilisateurs();
				$liste_action_tables	= [
											'insertion',
											'mise à jour',
											'suppression',
											'loggin',
											'loggout',
										];

				if( isset( $_POST['start_date'] )  AND isset($_POST['end_date'])){

					$sql =" DATE(date_heure) BETWEEN '".$_POST['start_date']."' AND '".$_POST['end_date']."'";
					$periode = 'Du '.$_POST['start_date'].' AU '.$_POST['end_date'];

					if(  isset( $_POST['id_utilisateur'] ) AND $_POST['id_utilisateur'] <> 'null'){
						$sql .=" AND id_utilisateur = ".$_POST['id_utilisateur']."";
					}


					if( isset( $_POST['action_table'] ) AND $_POST['action_table'] <> 'null' ){
						$sql .=" AND `action` = '".$liste_action_tables[ $_POST['action_table'] ]."'";
					}

					$crud->where($sql);

				}

				

				$crud->set_table('action_utilisateur');
				$crud->columns('id_action_utilisateur','id_utilisateur','id_entreprise_cliente','nom_table','id_champ','action','text_descriptif','date_heure');
				$crud->display_as('id_action_utilisateur','ID');
				$crud->display_as('id_utilisateur','Nom de l\'utilisateur ');
				$crud->display_as('nom_table','nom de la table ');
				$crud->display_as('id_champ','Numéro de la ligne ');
				$crud->display_as('action','Action');
				$crud->display_as('text_descriptif','Détail sur l\'action ');
				$crud->display_as('date_heure','Date & Heure ');
				$crud->display_as('id_entreprise_cliente','Marchand');
				$crud->set_subject('Action utilisateur ');
	
				$crud->set_relation('id_utilisateur','utilisateur','{nom} - {post_nom} - {prenom}');
				$crud->set_relation('id_entreprise_cliente','entreprise','nom');
				
				$crud->unset_add();
				$crud->unset_edit();
				$crud->unset_delete();
				$crud->unset_clone();

				$crud->order_by('id_action_utilisateur','desc');
				
				$this->stateDisplay($crud);
				
	
				$output = $crud->render();
	
				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['settings_link_active'=>$settings_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("action_utilisateur/action_utilisateur", [
					'header' => $header, 
					'navbar'=>$navbar, 
					'sidebar' => $sidebar, 
					'footer' => $footer,
					'periode' => $periode,
					'liste_utilisateurs' =>  $liste_utilisateurs,
					'liste_action_tables' => $liste_action_tables,
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
				$crud->set_subject("Une action Utilisateur ");
				break;
			case 'list':
				$crud->set_subject("Une action Utilisateur ");
				break;
			case 'add':
				$crud->set_subject("D'une action  Utilisateur ");
				break;
			
			case 'edit':
				$crud->set_subject("D'une action Utilisateur ");
				break;
			
			default:
				break;
		}

	}
	
}
