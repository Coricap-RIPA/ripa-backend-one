<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Depense extends CI_Controller {

	public function index($nom_table=null, $id_entree=null,$champ=null,$id_pour_entreprise=null)
	{
		if(  $this->session->logged_in ){

			if(check_privilege('finance', $this->session->user['id_role'], 'voir')){

				$crud = new grocery_CRUD();
				$crud->where(['nom_table'=>$nom_table,'id_entree'=>$id_entree]);

				$montant_entre 			   	= $this->ModelGetTableRow->getObjectFieldValue( ['id_'.$nom_table =>$id_entree] ,$nom_table)->$champ;
				$montant_achat				= $this->ModelGetTableRow->getObjectFieldValue( ['id_'.$nom_table  =>$id_entree] , $nom_table)->prix_achat;
				$marchandise 			   	= $this->ModelGetTableRow->getObjectFieldValue( ['id_'.$nom_table =>$id_entree] ,$nom_table)->marchandise;
				$id_entreprise 			   	= $this->ModelGetTableRow->getObjectFieldValue( ['id_'.$nom_table =>$id_entree] ,$nom_table)->id_entreprise;
				$client_entreprise			= $this->ModelGetTableRow->getObjectFieldValue( ['id_entreprise' =>$id_entreprise] ,'entreprise')->nom;
				$pourcentage_bureau 	   	= $this->ModelGetTableRow->getObjectFieldValue( ['nom_table' => $nom_table] ,'conf_pourcentage_bureau')->taux_pourcentage;
				$nom_entreprise 			= $this->ModelGetTableRow->getObjectFieldValue(['id_entreprise'=>$id_pour_entreprise],'entreprise')->nom;
				$valeur_pourcentage_bureau 	= ($montant_entre*$pourcentage_bureau)/100;  
				$devise = '$';


				if( isset( $_POST['start_date'] )  AND isset($_POST['end_date'])){
					$sql = "date_enregistrement BETWEEN'".$_POST['start_date']."' AND '".$_POST['end_date']."' AND nom_table ='".$_POST['nom_table']."'";
				
					$crud->where($sql);
				}

				if($id_pour_entreprise == 4){
					$devise = 'Rand';
				}

				$crud->set_table('depense');
				$crud->columns('id_depense','nom_table','id_entree','montant_depense','fichier_joint','designation');
				$crud->display_as('nom_table','Nom de la table');
				$crud->display_as('id_entree','Id enregistrement');
				$crud->display_as('montant_depense','Montant de la pense '.$devise);
				$crud->display_as('solde_restant','Solde restant ');
				$crud->display_as('designation','Raison de la dépense');
				$crud->display_as('fichier_joint','Fichier de justification');
				$crud->display_as('date_enregistrement',' Date d\'enregistrement');
				$crud->display_as('id_depense','ID');
				
				$crud->set_subject('Une dépense');

				$crud->add_fields(array('nom_table','id_entree','montant_depense','designation','fichier_joint','date_enregistrement'));
				$crud->edit_fields(array('nom_table','id_entree','montant_depense','designation','fichier_joint','date_enregistrement'));
				
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));


				$crud->required_fields('montant_depense','designation');
				$crud->unset_clone();
				$crud->set_field_upload('fichier_joint','assets/uploads/files');
				$this->stateDisplay($crud,$nom_table,$id_entree);

				if( ! check_privilege('finance', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('finance', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( ! check_privilege('finance', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}

				if( ! check_privilege('finance', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}

				$crud->order_by('id_depense','desc');

				$output = $crud->render();

				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/navbar", [], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("depense/depense", ['header' => $header, 'navbar'=>$navbar, 'sidebar' => $sidebar, 'footer' => $footer,'output'=>$output->output,"montant_entre"=>$montant_entre,'pourcentage_bureau'=>$pourcentage_bureau,'nom_table'=>$nom_table,'valeur_pourcentage_bureau'=>$valeur_pourcentage_bureau,'devise'=>$devise,'id_entree'=>$id_entree,'champ'=>$champ,'nom_entreprise'=>$nom_entreprise,'id_pour_entreprise'=>$id_pour_entreprise,'marchandise'=>$marchandise,'client_entreprise'=>$client_entreprise,'montant_achat'=>$montant_achat]);

			}else{
				redirect($_SERVER['HTTP_REFERER']);
			}
			
		}else {
			redirect("Starter/login");
		}
	
	}

	public function stateDisplay($crud,$nom_table=null,$id_entree=null){
		$state = $crud->getState();

		switch ($state) {
			case 'read':
				$crud->set_subject("Une depense ");
				$crud->field_type('solde_restant', 'hidden');
				break;
			case 'list':
				$crud->set_subject("Une depense ");
				break;
			case 'add':
				$crud->field_type('date_enregistrement', 'hidden',date("Y-m-d") );
				$crud->field_type('nom_table', 'hidden', $nom_table );
				$crud->field_type('id_entree', 'hidden', $id_entree );
				$crud->set_subject("D'une depense ");
				break;
			
			case 'edit':
				$crud->set_subject("D'une depense ");
				break;
			
			default:
				break;
		}

	}



	public function _onRowInserted($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'depenpse',
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout d'une depense ".$post_array["nom_table"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowUpdated($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'deoense',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour du depense ".$post_array["nom_table"],
			"date_heure" => date("Y-m-d H:m:i")
		];

    
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data = ['id_depense'=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'depense',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "suppression d'une depense ".$this->ModelGetTableRow->getObjectFieldValue($data,'depense')->nom_table,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	
}
