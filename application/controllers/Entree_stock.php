<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entree_stock extends CI_Controller {

	public function index()
	{
		if(  $this->session->logged_in ){

			if(check_privilege('entre_stock', $this->session->user['id_role'], 'voir')){

				$crud = new grocery_CRUD();
				$devise = "USD";
				$entre_stocks = $this->Entree_stock_model->get_all_entre_stock();
				$total_vente 	= 0;
				$total_achat 	= 0;
				$recette	 	= 0;
				$entree_stock_link_active	= 'link_menu_active';

				if( isset( $_POST['start_date'] )  AND isset($_POST['end_date'])){
					$sql = "date_enregistrement_entre_stock BETWEEN'".$_POST['start_date']."' AND '".$_POST['end_date']."'";
					$crud->where($sql);
					$entre_stocks = $this->Entree_stock_model->get_entre_stock_by_date($_POST['start_date'],$_POST['end_date']);
				}

				foreach ($entre_stocks as $key => $entre_stock) {
					$total_vente = $total_vente + ($entre_stock['quantite_entre'] * $entre_stock['prix_vente']);
					$total_achat = $total_achat + ($entre_stock['quantite_entre'] * $entre_stock['prix_achat']);
				}

				$recette = $total_vente - $total_achat;

				$crud->set_table('entre_stock');
				$crud->columns('id_entre_stock','Produit','quantite_entre','quantite_restant','prix_vente','prix_achat','id_fournisseur','date_enregistrement_entre_stock');
				
				$crud->display_as('id_entre_stock','#');
				$crud->display_as('id_article','Produit');
				$crud->display_as('quantite_entre','Quantité Entrée');
				$crud->display_as('quantite_restant','Total en stock');
				$crud->display_as('id_fournisseur','Fournisseur');
				$crud->display_as('id_foreign_conditionnement','Conditionnement');
				$crud->display_as('id_foreign_emplacement','Emplacement');
				$crud->display_as('prix_vente','Prix de vente '.$devise);
				$crud->display_as('prix_achat','Prix d\'achat '.$devise);
				$crud->display_as('revenue','Benefices '.$devise);
				$crud->display_as('date_expiration','Date d\'expiration');
				$crud->display_as('date_enregistrement_entre_stock','Date');
				
				$crud->set_subject('Une entrée de stock');

				$crud->add_fields(array('id_article','quantite_entre','prix_achat','prix_vente','quantite_restant','id_fournisseur','revenue','date_enregistrement_entre_stock'));
				$crud->edit_fields(array('id_article','prix_achat','prix_vente','id_fournisseur'));
				

				$crud->callback_before_insert(array($this,'_onRowBeforeInserted'));
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_before_update(array($this,'_onRowBeforeUpdated'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));
				$crud->callback_column('Produit',array($this,'show_article'));


				$crud->required_fields('id_article','quantite_entre','prix_achat','prix_vente');	
				$crud->set_relation('id_article','article','nom_article');
				$crud->set_relation('id_fournisseur','fournisseur','nom_fournisseur');

				$crud->unset_clone();
				
				$this->stateDisplay($crud);

				if( ! check_privilege('entre_stock', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('entre_stock', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( ! check_privilege('entre_stock', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}

				if( ! check_privilege('entre_stock', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}

				
				$crud->order_by('id_entre_stock','desc');

				$output = $crud->render();

				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ["entree_stock_link_active"=>$entree_stock_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("entre_stock/entre_stock", ['header' => $header, 'navbar'=>$navbar, 'footer' => $footer,'devise'=>$devise,'total_vente'=>$total_vente,'total_achat'=>$total_achat,'recette'=>$recette,'output'=>$output->output,'entre_stocks'=>$entre_stocks]);
		

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
				$crud->set_subject("Une entrée de stock ");
				break;
			case 'list':
				$crud->set_subject("Une entrée de stock");				
				break;
			case 'add':
				$crud->field_type('date_enregistrement_entre_stock', 'hidden',date('Y-m-d'));
				$crud->field_type('quantite_restant', 'hidden');
				$crud->field_type('revenue', 'hidden');
				break;
			
			case 'edit':
				$crud->field_type('date_enregistrement_entre_stock', 'hidden',date('Y-m-d'));
				$crud->field_type('quantite_restant', 'hidden');
				$crud->field_type('revenue', 'hidden');
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
		$article = $this->Article_model->get_article( $post_array["id_article"] );
		$post_array['quantite_restant'] 	= $article['quantite'] + $post_array["quantite_entre"];
		$post_array['revenue']	= (int) ($post_array['quantite_entre'] * $post_array['prix_vente']) - ($post_array['quantite_entre'] * $post_array['prix_achat']);

		return $post_array;
	}


	public function _onRowInserted($post_array, $primary_key)
	{
		$article = $this->Article_model->get_article( $post_array["id_article"] );
		$article['quantite'] 	= $article['quantite'] + $post_array["quantite_entre"];

		$action_article = [
			'quantite'=> $article['quantite'],
		];

		$this->Article_model->update_article($post_array["id_article"],$action_article);

		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'entre_stock ',
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout d'une entrée en stock : ".$article['nom_article'],
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
			"nom_table"=> 'entre_stock',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour de l'entrée en stock  ".$post_array["id_article"],
			"date_heure" => date("Y-m-d H:m:i")
		];

    
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data 		= ['id_entre_stock'=>$primary_key];
		$id_article = $this->ModelGetTableRow->getObjectFieldValue($data,'entre_stock')->id_article;

		$article 	= $this->Article_model->get_article( $id_article );
		
		$article['quantite'] = $article['quantite'] - $this->ModelGetTableRow->getObjectFieldValue($data,'entre_stock')->quantite_entre;

		$action_article = [
			'quantite'=> $article['quantite']
		];

		$this->Article_model->update_article($article["id_article"],$action_article);


		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'entre_stock',
			"id_champ"=> $primary_key,
			"action" => "suppression",
			"text_descriptif" => "suppression de l' entrée en stock de l'article : ".$article['nom_article'],
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}
	
	
	public function show_article($value, $row)
	{
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

	
}
