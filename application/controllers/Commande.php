<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commande extends CI_Controller {

	public function index( $index_commande = null)
	{
		if(  $this->session->logged_in ){

			if(check_privilege('commande', $this->session->user['id_role'], 'voir')){

				$crud 					= new grocery_CRUD();
				$devise 				= "CDF";
				$commande_link_active 	= "link_menu_active";
				$clients 				= $this->Client_model->get_all_clients();
				$avoided_paramaters_array = [
					'success',
					'add',
					'edit',
					'delete',
				];
				
				if($index_commande <> null AND !in_array($index_commande,$avoided_paramaters_array)){
					$crud->where(['id_commande'=>$index_commande]);
				}

				if(  isset( $_POST['start_date'] )  AND isset($_POST['end_date'])  AND !empty( $_POST['start_date'] ) AND !empty( $_POST['end_date'] ) ){
					$sql = "date_commande BETWEEN'".$_POST['start_date']."' AND '".$_POST['end_date']."'";
					$crud->where($sql);
				}

				if(  isset( $_POST['id_foreign_client'] )  AND $_POST['id_foreign_client'] <>'null' ){
					$crud->where(['id_foreign_client'=>$_POST['id_foreign_client']]);
				}

				if ( !isset( $_POST['start_date'] )  AND !isset($_POST['end_date'])  AND empty( $_POST['start_date'] ) AND empty( $_POST['end_date'] ) ) {					
					if ( !isset( $_POST['id_foreign_client'] )  AND empty($_POST['id_foreign_client'])  ) {
						$crud->where(['date_commande'=>date('Y-m-d')]);
					}
				}

				$this->Commande_model->update_commande_by_status_read_commande( 0, ['status_read_commande'=>1] );

				$crud->set_table('commande');
				$crud->columns('id_commande','id_foreign_client','id_foreign_couple','text_adress_livraison','note_commande','montant_total_commande','date_commande','heure_commande','id_status');
				
				$crud->display_as('id_commande','#');
				$crud->display_as('quantite_commande','Quantité');
				$crud->display_as('quantite_restant','Quantité Total en Stok');
				$crud->display_as('prix_commande','Prix en '.$devise);
				$crud->display_as('id_foreign_client','Nom du client ');
				$crud->display_as('id_foreign_couple','Nom du Couple ');
				$crud->display_as('text_adress_livraison','Adresse de Livraison ');
				$crud->display_as('note_commande','Note du client ');
				$crud->display_as('montant_total_commande','Montant Total CDF');
				$crud->display_as('date_commande','Date');
				$crud->display_as('heure_commande','Heure');
				$crud->display_as('id_status','Status');
				
				$crud->set_subject('Une Commande');

				$crud->add_fields(array('text_adress_livraison','note_commande','date_commande','montant_total_commande','heure_commande','id_status'));
				$crud->edit_fields(array('id_status'));
				

				$crud->callback_before_insert(array($this,'_onRowBeforeInserted'));
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_before_update(array($this,'_onRowBeforeUpdated'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));

				$crud->add_action('Détails', '', '','list-alt',array($this,'show_article_commande_details'));
				$crud->add_action('', '', '','copy',array($this,'transfert_commande_url'));

				

				$crud->callback_column('id_status',array($this,'_callback_status'));

				$crud->set_relation('id_foreign_client','client','- {nom_client} <br>- {tel_whatsapp_client}');
				$crud->set_relation('id_foreign_couple','couple','- {nom_couple} <br>- {code_couple}');
				$crud->field_type('status_read_commande','hidden');
				$crud->unset_clone();
				$crud->unset_add();
				$crud->unset_delete();
				$this->stateDisplay($crud);

				if( ! check_privilege('commande', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( ! check_privilege('commande', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}
				
				$crud->order_by('id_commande','desc');

				$output = $crud->render();

				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['commande_link_active'=>$commande_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("commande/commande", [
					'header' => $header, 
					'navbar'=>$navbar, 
					'sidebar' => $sidebar, 
					'footer' => $footer,
					'devise'=>$devise,
					'clients'=>$clients,
					'output'=>$output->output,
				]);
		

			}else{
				redirect($_SERVER['HTTP_REFERER']);
			}
		}else {
			redirect("Starter/login");
		}
	
	}


	public function dashboard()
	{
		if(  $this->session->logged_in ){
			if(check_privilege('commande', $this->session->user['id_role'], 'voir')){

				$crud 		= new grocery_CRUD();
				$crud->set_table('sexe');

				$commande_link_active 	= "link_menu_active";
				
				$output 	= $crud->render();
				$header 	= $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar 	= $this->load->view("layouts/sidebar", [], true);
				$navbar 	= $this->load->view("layouts/menu_nav_bar", ['commande_link_active'=>$commande_link_active], true);
				$footer 	= $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("commande/commande_dashboard", [
					'header' => $header, 
					'navbar'=>$navbar, 
					'sidebar' => $sidebar, 
					'footer' => $footer,
					'output'=>$output->output,
				]);


			}else{
				redirect($_SERVER['HTTP_REFERER']);
			}
		}else{
			redirect("Starter/login");
		}
	}

	public function stateDisplay($crud){
		$state = $crud->getState();

		switch ($state) {
			case 'read':
				$crud->set_subject("D'une Commande ");
				break;
			case 'list':
				$crud->set_subject("D'une Commande ");				
				break;
			case 'add':
				break;			
			case 'edit':
				$crud->set_relation('id_status','status_commande','designation');
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
			"nom_table"=> 'commande ',
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout d'une commande avec comme articles :".$post_array['id_articles'],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}


	public function _onRowBeforeUpdated($post_array,$primary_key)
	{	
		// Change or set sortie de stock status is livré	
		return $post_array;
	}


	public function _onRowUpdated($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'commande',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour de la commande avec comme articles :".$post_array["id_article"],
			"date_heure" => date("Y-m-d H:m:i")
		];

    
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data 		= ['id_commande'=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'commande',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "suppression de la commande avec comme articles : ".$this->ModelGetTableRow->getObjectFieldValue($data,'commande')->id_articles,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}
	

	public function show_article_commande_details($primary_key , $row)
	{
		return site_url('Article_commande/index/').$primary_key.'/';
	}


	public function transfert_commande($primary_key)
	{
		$url 					= "http://192.168.1.142/fromatter_express/index.php/Course/insert_course_from_fromatter_market/";
		$tabArticleCommandes 	= $this->Article_commande_model-> get_article_commande_by_id_commande_group_by_id_fournisseur( $primary_key);

		foreach ($tabArticleCommandes as $key => $articleCommande) {
			$commande	= $this->Commande_model->get_commandes($articleCommande['id_foreign_commande']);
			if(!empty($commande)){
				$client 			= $this->Client_model->get_client($commande['id_foreign_client']);
				$fournisseur 		= $this->Fournisseur_model->get_fournisseur($articleCommande['id_foreign_fournisseur_commande']); 
				$articleCommande['commande']	= $commande;
				$articleCommande['client']		= $client;
				$articleCommande['fournisseur']	= $fournisseur;
				$tabArticleCommandes[$key]  	= $articleCommande;
			}
		}
				
		$data_json = json_encode($tabArticleCommandes);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response  = curl_exec($ch);
		curl_close($ch); 

		$this->Commande_model->update_commande($primary_key, ['id_status' => 5 ]);

		if($response == "ready"){
			$_SESSION['transfert_message'] = "Commande bien transferée au service de Livraison !!!";
		}else{
			$_SESSION['transfert_message'] = "Une erreur est survenue lors du transfert !!!";
		}
		
		redirect('Commande/index/');

	}

	public function transfert_commande_url($primary_key , $row)
	{
		return site_url('Commande/transfert_commande/').$primary_key.'/';
	}


	public function get_status_read_commande()
	{
		$commande_not_read_row = $this->Commande_model->get_commandes_not_read();
		if(!empty($commande_not_read_row)){
			echo 'alert';
		}else{
			echo 'silence';
		}
	}


	public function get_status_read_commande_fournisseur()
	{
		if(isset($_POST['id_entr_user']) AND $_POST['id_entr_user']<> 1){

			$commande_not_read_row = $this->Commande_model->get_commandes_not_read_by_id_fournisseur( $_POST['id_entr_user'] );
			if(!empty($commande_not_read_row)){
				echo 'alert';
			}else{
				echo 'silence';
			}
		}
	}


	public function _callback_status($value, $row)
	{
		$commande = $this->Commande_model->get_commandes($row->id_commande);
		if(!empty($commande)){
			$status_commande = $this->Status_commande_model->get_status_commande($commande['id_status']);
			if(!empty($status_commande)){
				if($status_commande['id_status_commande'] == 1){
					$status_commande['html'] = '<span class="badge red text-white"  style="font-weight: 300; font-size: 0.8rem; border-radius: 2px;"> '.$status_commande['designation'].' </span>';
				}elseif ($status_commande['id_status_commande'] == 2) {
					$status_commande['html'] = '<span class="badge teal text-white"  style="font-weight: 300; font-size: 0.8rem; border-radius: 2px;"> '.$status_commande['designation'].' </span>';
				}elseif ($status_commande['id_status_commande'] == 3) {
					$status_commande['html'] = '<span class="badge green text-white"  style="font-weight: 300; font-size: 0.8rem; border-radius: 2px;"> '.$status_commande['designation'].' </span>';
				}elseif ($status_commande['id_status_commande'] == 4){
					$status_commande['html'] = '<span class="badge orange text-white"  style="font-weight: 300; font-size: 0.8rem; border-radius: 2px;"> '.$status_commande['designation'].' </span>';
				}else{
					$status_commande['html'] = '<span class="badge blue text-white"  style="font-weight: 300; font-size: 0.8rem; border-radius: 2px;"> '.$status_commande['designation'].' </span>';
				}

				return $status_commande['html'];
			}
		}
	}


}
