<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article_commande extends CI_Controller {

	public function index($id_commande=null)
	{
		if(  $this->session->logged_in ){
			if(check_privilege('article_commande', $this->session->user['id_role'], 'voir')){

				$crud 					= new grocery_CRUD();
				$commande_link_active 	= "link_menu_active";
				$commande 				= [];
				$client 				= [];
				$commande_fournisseur 	= [];
				$article_commandes 		= [];
				$article_fournisseur 	= [];
				$status_commandes 		= [
					['id_status_commande'=>1,'designation'=>'Annulée'],
					['id_status_commande'=>2,'designation'=>'Commande arrivée'],
					['id_status_commande'=>4,'designation'=>'En cours de Livraison'],
				];

				
				if($id_commande <> null){
					$crud->where(['id_foreign_commande'=>$id_commande]);
					$commande 			= $this->Commande_model->get_commandes( $id_commande );
					$client 			= $this->Client_model->get_client( $commande['id_foreign_client']);
					$article_commandes 	= $this->Article_commande_model->get_article_commande_by_id_commande($id_commande); 

					foreach ($article_commandes as $key => $article_commande) {

						$article = $this->Article_model->get_article($article_commande['id_foreign_article']);
						$fourniseur = $this->Fournisseur_model->get_fournisseur($article_commande['id_foreign_fournisseur_commande']);

						$article_commande['id_foreign_article'] = $article;
						$article_commande['id_foreign_fournisseur_commande'] = $fourniseur;

						$article_commandes[$key]  = $article_commande;
 
					}

				}
				
				if($this->session->userdata['user']['id_foreign_fournisseur'] <> 1){

					$this->Commande_model->update_commande_by_status_read_commande_by_id_fournisseur( $this->session->userdata['user']['id_foreign_fournisseur'] );
					$commande_fournisseur 	=  $this->Article_commande_model->get_article_commande_by_id_fournisseur_group_by_id_commande( $this->session->userdata['user']['id_foreign_fournisseur'] );
					$article_fournisseur 	=  $this->Article_model->get_fournisseur_article( $this->session->userdata['user']['id_foreign_fournisseur'] ); 
					//$crud->where(['id_foreign_fournisseur_commande'=> $this->session->userdata['user']['id_foreign_fournisseur'] ]);
					
					if(isset( $_POST['id_foreign_article'] ) AND $_POST['id_foreign_article'] <>'null' ){

						//$crud->where(['id_foreign_article'=>$_POST['id_foreign_article'] ]);
					}

					if(isset( $_POST['id_foreign_commande'] ) AND $_POST['id_foreign_commande'] <>'null' ){

						//$crud->where(['id_foreign_commande'=>$_POST['id_foreign_commande'] ]);
					}
				}

				if( isset($_POST['id_foreign_commande_status'])  AND isset($_POST['id_status_commande'])  ){
					$commande_updater_status = [
						'id_status'=>$_POST['id_status_commande']
					];
					$this->Commande_model->update_commande($_POST['id_foreign_commande_status'], $commande_updater_status);
				}


				$crud->set_table('article_commande');
				$crud->columns('id_article_commande','id_foreign_commande','Fournisseur','id_foreign_article','article_commande_quantite','montant_total','Status','Date_heure');
				$crud->display_as('id_article_commande','#');
				$crud->display_as('id_foreign_commande','Numéro Commande');
				$crud->display_as('id_foreign_article','Article');
				$crud->display_as('article_commande_quantite','Quantité');
				$crud->display_as('montant_total','Montant en USD');
				$crud->display_as('Date_heure','Date & heure');
				$crud->set_subject('Un article commandé');
	
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));

	
				$crud->callback_column('Fournisseur',array($this,'_callback_fournisseur'));
				$crud->callback_column('Status',array($this,'_callback_status'));
				$crud->callback_column('Date_heure',array($this,'_callback_date_heure'));
				$crud->callback_column('id_foreign_article',array($this,'_callback_article'));


				$crud->unset_clone();
				$crud->unset_add();
				$crud->unset_edit();
				$crud->unset_delete();
				$crud->unset_read();
				$this->stateDisplay($crud);


				//echoDie($article_commandes);

	
				$crud->order_by('id_article_commande','desc');

				$output = $crud->render();
	
				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['commande_link_active'=>$commande_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("article_commande/article_commande", [
					'header' => $header, 
					'navbar'=>$navbar, 
					'sidebar' => $sidebar, 
					'footer' => $footer,
					'commande' => $commande,
					'client' => $client,
					'output'=>$output->output,
					'commande_fournisseur'=>$commande_fournisseur,
					'article_fournisseur'=>$article_fournisseur,
					'status_commandes'=>$status_commandes,
					'article_commandes'=>$article_commandes,
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
				$crud->set_subject("Un article commandé ");
				break;
			case 'list':
				$crud->set_subject("Un article commandé ");
				break;
			case 'add':
				$crud->set_subject("D'Un article commandé ");
				break;
			
			case 'edit':
				$crud->set_subject("D'Un article commandé ");
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
			"nom_table"=> 'article_commande',
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout de l'article commandé ",
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowUpdated($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'article_commande',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour de l'article commandé ",
			"date_heure" => date("Y-m-d H:m:i")
		];

    
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'article_commande',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "suppression de l'article commandé ",
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}


	public function show_article_details($primary_key , $row)
	{
		return site_url('Article/index/9194/').$row->id_foreign_article.'/';
	}

	public function _callback_fournisseur($value, $row)
	{
		$article = $this->Article_model->get_article($row->id_foreign_article);
		if(!empty($article)){
			$fournisseur = $this->Fournisseur_model->get_fournisseur($article['id_foreign_fournisseur']);
			if(!empty($fournisseur)){
				return $fournisseur['nom_fournisseur'].'<br>'.$fournisseur['tel_fournisseur'];
			}
		}
	}


	public function _callback_status($value, $row)
	{
		$commande = $this->Commande_model->get_commandes($row->id_foreign_commande);
		if(!empty($commande)){
			$status_commande = $this->Status_commande_model->get_status_commande($commande['id_status']);
			if(!empty($status_commande)){
				if($status_commande['id_status_commande'] == 1){
					$status_commande['html'] = '<span class="badge red text-white"  style="font-weight: 300; font-size: 0.8rem; border-radius: 2px;"> '.$status_commande['designation'].' </span>';
				}elseif ($status_commande['id_status_commande'] == 2) {
					$status_commande['html'] = '<span class="badge teal text-white"  style="font-weight: 300; font-size: 0.8rem; border-radius: 2px;"> '.$status_commande['designation'].' </span>';
				}elseif ($status_commande['id_status_commande'] == 3) {
					$status_commande['html'] = '<span class="badge green text-white"  style="font-weight: 300; font-size: 0.8rem; border-radius: 2px;"> '.$status_commande['designation'].' </span>';
				}else{
					$status_commande['html'] = '<span class="badge orange text-white"  style="font-weight: 300; font-size: 0.8rem; border-radius: 2px;"> '.$status_commande['designation'].' </span>';
				}

				return $status_commande['html'];
			}
		}
	}


	public function _callback_date_heure($value, $row)
	{
		$commande = $this->Commande_model->get_commandes($row->id_foreign_commande);
		if(!empty($commande)){
			return $commande['date_commande'] .' à '.$commande['heure_commande'];
		}
	}

	public function _callback_article($value, $row)
	{
		$article= $this->Article_model->get_article($row->id_foreign_article);
		if(!empty($article)){
			$article_img = '<img src="'.base_url('assets/uploads/files/').$article['photo_article'].'" style="width: 50px; height:50px; object-fit:cover; border-radius:10px;" />';
			return $article['nom_article'] .' CODE: '.$article['code'].'<br>'.$article_img;
		}
	}
	
}
