<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Couple_article extends CI_Controller {

	public function index($id_commande=null)
	{
		if(  $this->session->logged_in ){
			if(check_privilege('couple_article', $this->session->user['id_role'], 'voir')){

				$crud 					= new grocery_CRUD();
				$settings_link_active 	= "link_menu_active";
				$commande 				= [];
				$client 				= [];
				$articles 				= $this->Article_model->get_all_article_id_name_filed();
				$articles 				= $this->format_array_articles($articles);
				$commande_fournisseur 	= [];
				$couple_articles 		= [];
				$article_fournisseur 	= [];


				$crud->set_table('couple_article');
				$crud->columns('id_couple_article','id_foreign_couple','ids_articles','quantite','date_mariage');
				$crud->display_as('id_couple_article','#');
				$crud->display_as('ids_articles','Articles');
				$crud->display_as('id_foreign_couple','Couple');
				$crud->display_as('quantite','Quantité');
				$crud->display_as('date_mariage','Date du Mariage');

				$crud->set_subject('Un article par couple');
	
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));

	
				// $crud->callback_column('Fournisseur',array($this,'_callback_fournisseur'));
				// $crud->callback_column('Status',array($this,'_callback_status'));
				// $crud->callback_column('Date_heure',array($this,'_callback_date_heure'));
				//$crud->callback_column('id_foreign_article',array($this,'_callback_article'));
				
				$crud->callback_column('date_mariage',array($this,'_callback_date_mariage'));
				$crud->set_relation('id_foreign_couple','couple','nom_couple');
				$crud->field_type('ids_articles','multiselect', $articles);


				$crud->unset_clone();
				$this->stateDisplay($crud);


				if( ! check_privilege('couple_article', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('couple_article', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( ! check_privilege('couple_article', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}

				if( ! check_privilege('couple_article', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}
	
				$crud->order_by('id_couple_article','desc');

				$output = $crud->render();
	
				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['settings_link_active'=>$settings_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("couple_article/couple_article", [
					'header' => $header, 
					'navbar'=>$navbar, 
					'sidebar' => $sidebar, 
					'footer' => $footer,
					'output'=>$output->output,
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
				$crud->set_subject("Un article par couple ");
				break;
			case 'list':
				$crud->set_subject("Un article par couple ");
				break;
			case 'add':
				$crud->set_subject("D'Un article par couple ");
				break;
			
			case 'edit':
				$crud->set_subject("D'Un article par couple ");
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
			"nom_table"=> 'couple_article',
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
			"nom_table"=> 'couple_article',
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
			"nom_table"=> 'couple_article',
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
		$commande = $this->Commande_model->get_commandes($row->id_foreign_couple);
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
		$commande = $this->Commande_model->get_commandes($row->id_foreign_couple);
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


	public function _callback_date_mariage($value, $row)
	{
		$couple = $this->Couple_model->get_couple($row->id_foreign_couple);
		$status_mariage_couple = [];

		if(!empty($couple)){

			$to_day_date = date('Y-m-d');
			$mariage_date = $couple['date_mariage'];
			//$mariage_date = '2023-12-20';

			$date1 = new DateTime($to_day_date);
			$date2 = new DateTime($mariage_date);
			$interval = $date1->diff($date2);

			if ($to_day_date < $mariage_date) {
				$status_mariage_couple['html'] = '<span class="badge green text-white"  style="font-weight: 300; font-size: 0.8rem; border-radius: 2px;"> Mariage dans '.$interval->days.' Jours</span>';
			}else {
				$status_mariage_couple['html'] = '<span class="badge red text-white"  style="font-weight: 300; font-size: 0.8rem; border-radius: 2px;"> Mariage passé depuis  '.$interval->days.' Jours</span>';
			}

			return  $status_mariage_couple['html'];			
		}
	}

	public function format_array_articles ($array_articles)  {

		$final_array_articles = [];

		if (!empty($array_articles)) {
			foreach ($array_articles as $key => $array_article) {
				$final_array_articles[$array_article['id_article']] = $array_article['nom_article'];
			}
			return $final_array_articles;
		}



	}


	
}
