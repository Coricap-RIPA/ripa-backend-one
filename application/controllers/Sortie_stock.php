<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT");

defined('BASEPATH') OR exit('No direct script access allowed');

class Sortie_stock extends CI_Controller {

	public function index($id_sortie_stock_list =null)
	{
		if(  $this->session->logged_in ){

			if(check_privilege('sortie_stock', $this->session->user['id_role'], 'voir')){

				$crud 			= new grocery_CRUD();
				$sortie_stocks 	= $this->Sortie_stock_model->get_all_sortie_stock();
				$clients 		= $this->Client_model->get_all_clients();
				$total_vente 	= 0;
				$total_achat 	= 0;
				$recette	 	= 0;
				$devise 		= "USD";
				$sortie_stock_link_active	= 'link_menu_active';
				$article_sortie_ventes 		= [];
				$sortie_stock 				= [];
				$total_sortie_stock 		= 0;
				$articles_ventes 			= $this->Article_model->get_all_article();


				if( isset( $_POST['start_date'] )  AND isset($_POST['end_date'])){
					$sql = "date_enregistrement_sortie_stock BETWEEN'".$_POST['start_date']."' AND '".$_POST['end_date']."'";
					$sortie_stocks = $this->Sortie_stock_model->get_sortie_stock_by_date($_POST['start_date'],$_POST['end_date']);
				}

				if( $id_sortie_stock_list <> null AND isset($id_sortie_stock_list) ){
					$sortie_stock 			= $this->Sortie_stock_model->get_sortie_stock($id_sortie_stock_list);
					$article_sortie_ventes  = $this->Article_sortie_vente_model->get_article_sortie_vente_by_stock_sortie_id($id_sortie_stock_list);
					if(!empty($sortie_stock)){
						if(!empty($article_sortie_ventes)){
							foreach ($article_sortie_ventes as $key => $article_sortie_vente) {
								$article = $this->Article_model->get_article($article_sortie_vente['id_article']);
								if(!empty($article)){
									$article_sortie_vente['nom_article'] = $article['nom_article'];
									$article_sortie_ventes[$key] = $article_sortie_vente;
									$total_sortie_stock = $total_sortie_stock + (int) $article_sortie_vente['total'];
								}
							}
						}
					}
				}

				if(isset($_POST['id_foreign_categorie'] ) ){
					$articles_ventes 			= $this->Article_model->get_article_by_id_categorie( (int) $_POST['id_foreign_categorie']);
					if(isset($_POST['id_foreign_sous_categorie'] )){
						$articles_ventes 			= $this->Article_model->get_article_by_id_categorie_id_sous_categorie( (int) $_POST['id_foreign_categorie'], (int) $_POST['id_foreign_sous_categorie'] );
					}
				}

				$articles_ventes = $this->format_articles_array($articles_ventes);

				$crud->set_table('article');

				$crud->set_subject('Une sortie de stock');
				$crud->columns('nom_article','prix','code');

				$crud->display_as('nom_article','Produit');
				$crud->display_as('prix','Prix');
				$crud->display_as('code','Code');


				$crud->set_field_upload('photo_article','assets/uploads/files');
				$crud->callback_column('nom_article',array($this,'show_article'));
				$crud->callback_column('prix',array($this,'show_prix'));
				
				$crud->unset_operations();
				$this->stateDisplay($crud);

				if( check_privilege('sortie_stock', $this->session->user['id_role'], 'ajouter') ){
					$crud->add_action('AJOUTER A LA COMMANDE', '', '', 'list-alt add_to_commande',array($this,'return_link_to_commande'),'target_blank');
				}

				$crud->order_by('id_article','desc');

				$output = $crud->render();

				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ["sortie_stock_link_active"=>$sortie_stock_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("sortie_stock/sortie_stock",[
					'header' => $header, 
					'navbar'=>$navbar,
					'footer' => $footer,
					'devise'=>$devise,
					'total_vente'=>$total_vente,
					'total_achat'=>$total_achat,
					'recette'=>$recette,
					'output'=>$output->output,
					'sortie_stocks'=>$sortie_stocks,
					'clients'=>$clients,
					'article_sortie_ventes'=>$article_sortie_ventes,
					'sortie_stock'=>$sortie_stock,
					'articles_ventes'=>$articles_ventes
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
		$articles_sorties =  $this->Article_sortie_model->get_sorti_article_by_sortieId($primary_key);
		$sortie_sotck = $this->Sortie_stock_model->get_sortie_stock($articles_sorties[0]['id_sortie_stock']);
		$artQte =  explode("-",$sortie_sotck['quantite_sortie']); 
		$nom_article ="";

		if(count($artQte) == count($articles_sorties)){

			foreach ($articles_sorties as $key => $value) {
				$article = $this->Article_model->get_article($value['id_article']);
				$nom_article.=$article['nom_article'].", ";
				$article['quantite'] = 	$article['quantite'] + $artQte[$key];
				$this->Article_model->update_article($article["id_article"],$article);
				$this->Article_sortie_model->delete_article_sortie($value["id_article_sortie_vente"]);
			}

		}

		$nom_article = substr($nom_article,0,-2);

		$action = [
			"id_utilisateur_sys"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'sortie_stock',
			"id_champ"=> $primary_key,
			"action" => "supression",
			"text_descriptif" => "suppression de la sortie en stock  article(s) : ".$nom_article,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}
	

	public function show_article($value, $row)
	{
		return "<center> 
					<a href='".base_url('assets/uploads/files/'.$row->photo_article)."' class='image-thumbnail'>
						<img src='".base_url('assets/uploads/files/'.$row->photo_article)."' width='150px' height='150px' style='object-fit: scale-down; border-radius: 5px;'/>
					</a><br>
					<span class='nom_article'>
						<b>".$row->nom_article."</b>
					<span>
				</center>";
	}

	public function show_prix($value, $row)
	{
		$entree_stock = $this->Entree_stock_model->get_last_entree_stock_by_id_article($row->id_article);
		if(!empty($entree_stock)){
			return "<b>".$entree_stock['prix_vente']." $</b>";
		}
		return  '';
	}

	function return_link_to_commande($primary_key , $row){
		$entree_stock = $this->Entree_stock_model->get_last_entree_stock_by_id_article($row->id_article);
		$string_href  = $primary_key;
		if(!empty($entree_stock)){
			$string_href.="-".$entree_stock['prix_vente'];
		}
		return $string_href;
	}


	function format_articles_array($array_artciles){
		foreach ($array_artciles as $key => $array_artcile) {
			$array_artcile['conditionnement'] = 'pièces';
			$entree_stock = $this->Entree_stock_model->get_last_entree_stock_by_id_article((int) $array_artcile['id_article']);
			if(!empty($entree_stock)){
				$array_artcile['prix_vente'] = $entree_stock['prix_vente'];
				$array_artcile['prix_achat'] = $entree_stock['prix_achat'];
				$categorie = $this->Categorie_model->get_categorie( $array_artcile['id_foreign_categorie'] );
				$array_artcile['categorie'] = ( !empty( $categorie ) ) ? $categorie['nom_categorie'] : '';
				//$conditionnement = $this->Conditionnement_model->get_conditionnement($entree_stock['id_foreign_conditionnement']);
				//$array_artcile['conditionnement'] = (!empty($conditionnement)) ? $conditionnement['nom_conditionnement'] : $array_artcile['conditionnement'];
				$array_artciles[$key] = $array_artcile;
			}
		}
		return $array_artciles;
	}


	function process_commande(){
		
		if(isset($_POST['prosess_commande'])){

			$id_client 						= (isset($_POST['id_client'])  AND strlen($_POST['id_client']) > 0 AND $_POST['id_client'] <> 'null') ? $_POST['id_client'] : null;
			$nom_client 					= (isset($_POST['nom_client'])  AND strlen($_POST['nom_client']) > 2) ? $_POST['nom_client'] : null;
			$id_sortie_stock 				= (isset($_POST['id_sortie_stock'])  AND strlen($_POST['id_sortie_stock']) > 0 ) ? $_POST['id_sortie_stock'] : null;;
			$total_commande 				= (int) $_POST['total_commande'];
			$taux_reduction 				= (int) $_POST['taux_reduction'];
			$date_sortie 					= $_POST['date_sortie'];
			$total_sortie_stock				= ( $total_commande - ( $total_commande * $taux_reduction ) / 100 );
			
			
			if( ($nom_client == null) AND ($id_client == null) ){
				$nom_client = "CLIENT";
			}
			
			$inser_sortie_stock = [
				'id_client' => $id_client,
				'nom_client' => $nom_client,
				'taux_reduction' => $taux_reduction,
				'total_sortie_stock' => $total_sortie_stock,
				'is_editable'=>1,
				'id_foreign_status_cuisine'=>1,
				'date_enregistrement_sortie_stock' => $date_sortie,
			];


			if( (int) $id_sortie_stock <> 0 ){
				$article_sortie_ventes = $this->Article_sortie_vente_model->get_article_sortie_vente_by_stock_sortie_id($id_sortie_stock);
				if(!empty( $article_sortie_ventes ) ){
					foreach ($article_sortie_ventes as $key => $article_sortie_vente) {
						$article 				= $this->Article_model->get_article( $article_sortie_vente['id_article'] );
						$article['quantite'] 	= $article['quantite'] + $article_sortie_vente['quantite'];
						
						$action_article = [
							'quantite'=> $article['quantite'],
						];
						$this->Article_model->update_article($article['id_article'],$action_article);	
					}
				}
				$this->Sortie_stock_model->update_sortie_stock($id_sortie_stock,$inser_sortie_stock);
				$this->Article_sortie_vente_model->delete_article_sortie_vente_by_id_sortie_stock($id_sortie_stock);
			}else{
				$id_sortie_stock = $this->Sortie_stock_model->add_sortie_stock($inser_sortie_stock);
			}


			$tabQte 	= explode('-',$_POST['total_qte_article']);
			$tabArticle = explode('-',$_POST['total_ids_article']);
			$tabPrixArt = explode('-',$_POST['total_prix_article']);

			

			if( count($tabArticle) == count($tabQte)  ){

				foreach($tabArticle as $key=>$val){
					
					$article = $this->Article_model->get_article( $val );
			
					$article['quantite'] 	= $article['quantite'] - $tabQte[$key];
		
					$action_article = [
						'quantite'=> $article['quantite'],
					];
					$this->Article_model->update_article($val,$action_article);		
					
					$article_sortie_vente =[
						"id_sortie_stock"=>$id_sortie_stock,
						"id_article"=>$val,
						"prix_vente"=>$tabPrixArt[$key],
						"quantite"=>$tabQte[$key],
						"total"=>$tabQte[$key] * $tabPrixArt[$key],
						"date_enregistrement_article_sortie_vente"=>$date_sortie,
					];
					$this->Article_sortie_vente_model->add_article_sortie_vente($article_sortie_vente);
				}
			}

			echo  (int) $id_sortie_stock;

		}else{
			redirect('Sortie_stock/index/');
		}

	}

	function show_total_sortie_stock($value, $row){
		$value = (int) $value;
		$taux_reduction = (int) $row->taux_reduction;
		return number_format( ($value -( $value * $taux_reduction ) /100 ),2 );
	}

	function return_link_to_facturation($primary_key , $row){
		return site_url('Facture/index/').$row->id_sortie_stock;
	}

	public function udpateSortieStockIsEditable()
	{
		if(isset($_GET['id_sortie_stock'])){
			$id_sortie_stock = (int) $_GET['id_sortie_stock'];
			$update_sortie_stock_iseditable = [
				'is_editable' => 0,
			];
			$this->Sortie_stock_model->update_sortie_stock($id_sortie_stock,$update_sortie_stock_iseditable);
			echo 'updated';
		}
	}


}
