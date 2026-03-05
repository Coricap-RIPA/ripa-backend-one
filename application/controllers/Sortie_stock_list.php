<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT");

defined('BASEPATH') OR exit('No direct script access allowed');

class Sortie_stock_list extends CI_Controller {


	function index(){
		if(  $this->session->logged_in ){

			if(check_privilege('sortie_stock', $this->session->user['id_role'], 'voir')){

				$crud = new grocery_CRUD();
				$devise 		= "USD";
				$sortie_stock_link_active	= 'link_menu_active';


				if( isset( $_POST['start_date'] )  AND isset($_POST['end_date'])){
					$sql = "date_enregistrement_article_sortie_vente BETWEEN'".$_POST['start_date']."' AND '".$_POST['end_date']."'";
					$crud->where($sql);
				}else {
					$crud->where(['date_enregistrement_article_sortie_vente'=>date('Y-m-d')]);
				}

			
				$crud->set_table('article_sortie_vente');
				$crud->set_subject('Une sortie de stock');
				$crud->columns('id_article_sortie_vente','id_article','quantite','sortie_stock','date_enregistrement_article_sortie_vente');

				$crud->display_as('id_article_sortie_vente','#');
				$crud->display_as('sortie_stock','Sortie Stock / Client');
				$crud->display_as('id_article','Produit');
				$crud->display_as('quantite','Quantité');
				$crud->display_as('date_enregistrement_article_sortie_vente','Date enregistrement');

				$crud->callback_before_insert(array($this,'_onRowBeforeInserted'));
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_before_update(array($this,'_onRowBeforeUpdated'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));

				$crud->callback_column('id_article',array($this,'show_article'));
				$crud->callback_column('sortie_stock',array($this,'show_sortie_stock'));
	
				$crud->unset_clone();
				$crud->unset_read();
				$crud->unset_add();
				$crud->unset_edit();
			
				$this->stateDisplay($crud);

				if( check_privilege('sortie_stock', $this->session->user['id_role'], 'ajouter') ){
					$crud->add_action('FACTURE', '', '', 'list-alt',array($this,'return_link_to_facturation'),'target_blank');
				}

				if(  check_privilege('sortie_stock', $this->session->user['id_role'], 'editer') ){					
					$crud->add_action('MODIFIER', '', '', 'pencil',array($this,'return_link_to_sortie_stock'),'target_blank');
				}

				
				if( !check_privilege('sortie_stock', $this->session->user['id_role'], 'supprimer') ){					
					$crud->unset_delete();
				}

				
				$crud->order_by('id_article_sortie_vente','desc');

				$output = $crud->render();

				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ["sortie_stock_link_active"=>$sortie_stock_link_active], true);				
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("sortie_stock/sortie_stock_list", ['header' => $header, 'navbar'=>$navbar,'footer' => $footer,'devise'=>$devise,'output'=>$output->output]);
		

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
				$crud->set_subject("Une sortie de stock ");
				$crud->field_type('photo_article', 'hidden');
				break;
			case 'list':
				$crud->set_subject("Une sortie de stock");	
				$crud->field_type('photo_article', 'hidden');			
				break;
			case 'add':
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
	
		$tabQte = explode('-',$post_array['quantite_sortie']);
		$tabArticle = $post_array['Articles'];
		
		if( count($tabArticle) == count($tabQte)  ){


			foreach($tabArticle as $key=>$val){
				
				$article = $this->Article_model->get_article( $val );
		
				$article['quantite'] 	= $article['quantite'] - $tabQte[$key];
				$article['revenue']		= ($article['quantite'] * $article['prix_vente']) - ($article['quantite'] * $article['prix_achat']);
	
				$action_article = [
					'quantite'=> $article['quantite'],
					'revenue'=>$article['revenue']	
				];
	
				$this->Article_model->update_article($val,$action_article);			
			}
		}
		
		$action = [
			"id_utilisateur_sys"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'sortie_stock',
			"id_champ"=> $primary_key,
			'id_client_sys'=> $post_array['id_client'],
			"action" => "insertion", 
			"text_descriptif" => "ajout d'Une sortie de stock : ",
			"date_heure" => date("Y-m-d H:m:i"),
			"type_action" => 1
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
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$article_sortie =  $this->Article_sortie_model->get_article_sortie_vente($primary_key);
		$sortie_sotck = $this->Sortie_stock_model->get_sortie_stock($article_sortie['id_sortie_stock']);
		$article = $this->Article_model->get_article($article_sortie['id_article']);
		$nom_article = $article['nom_article'];
		$article['quantite'] = 	$article['quantite'] + $article_sortie['quantite'];
		$this->Article_model->update_article($article["id_article"],$article);
		$this->Article_sortie_model->delete_article_sortie($article_sortie["id_article_sortie_vente"]);

		$articles_sorties= $this->Article_sortie_model->get_sorti_article_by_sortieId($sortie_sotck['id_sortie_stock']);

		if(empty($articles_sorties)){
			$this->Sortie_stock_model->delete_sortie_stock( $sortie_sotck['id_sortie_stock'] );
		}
		
		$action = [
			"id_utilisateur_sys"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'article_sortie_vente',
			"id_champ"=> $primary_key,
			"action" => "supression",
			"text_descriptif" => "suppression de l'article sortie en stock  article : ".$nom_article,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );

		return true;
	}



	
	function return_link_to_sortie_stock($primary_key , $row){

		$sortie_stock  	= $this->Sortie_stock_model->get_sortie_stock((int) $row->id_sortie_stock);
		if (!empty($sortie_stock)) {
			if((int) $sortie_stock['is_editable'] == 1){
				return site_url('Sortie_stock/index/').$row->id_sortie_stock;
			}
		}

		return "";

	}

	function show_article($value, $row){
		$article = $this->Article_model->get_article( $row->id_article );
		
		if(!empty($article)){
			if($article['quantite'] <= $article['seuil_critique']){
				return "<a target='__blank' href='".site_url("Article/index/9194/").$row->id_article."' style='color:red;'><b>".$article['nom_article']." - ( ".$article['code']." ) </b></a>";
			}else{
				return "<a target='__blank' href='".site_url("Article/index/9194/").$row->id_article."'><b>".$article['nom_article']." - ( ".$article['code']." ) </b></a>";
			}
		}
		return '';
	}

	function show_sortie_stock($value, $row){

		$sortie_stock  	= $this->Sortie_stock_model->get_sortie_stock((int) $row->id_sortie_stock);
		$nom_client 	= "";

		if (isset($sortie_stock['id_client']) and !empty($sortie_stock['id_client'])) {
			$client = $this->Client_model->get_client((int) $sortie_stock['id_client']);
			if (!empty($client)) {
				$nom_client = $client['nom_client'];
			}
		} else {
			$nom_client = $sortie_stock['nom_client'];
		}

		return $nom_client;
	}

	function return_link_to_facturation($primary_key , $row){
		return site_url('Facture/index/').$row->id_sortie_stock;
	}



	public function show_status_cuisine($value, $row)
	{
		$status_cuisine = $this->Status_cuisine_model->get_status_cuisine((int) $value);

		if(!empty($status_cuisine)){
			if((int) $status_cuisine['id_status_cuisine'] == 1){
				return '<span class="badge red text-white" style="font-weight: 300; font-size: 0.8rem; border-radius: 2px;"> <b>'.$status_cuisine['designation'].'</b> </span>';
			}else{
				return '<span class="badge green text-white" style="font-weight: 300; font-size: 0.8rem; border-radius: 2px;"> <b>'.$status_cuisine['designation'].'</b> </span>';
			}
		}
	}

}
