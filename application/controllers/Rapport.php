<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT");

defined('BASEPATH') OR exit('No direct script access allowed');

class Rapport extends CI_Controller {

	public function index()
	{
		if(  $this->session->logged_in ){

			if(check_privilege('rapport', $this->session->user['id_role'], 'voir') ){

				$crud 					= new grocery_CRUD();
				$articles 				= $this->Article_model->get_all_article();
				$commandes 				= [];
				$fournisseurs 			= $this->Fournisseur_model->get_all_fournisseurs();
				$devise 				= "USD";
				$rapport_link_active	= 'link_menu_active';




				if( $this->session->userdata['user']['id_foreign_fournisseur'] <> 1 ){
					$articles 	= $this->Article_model->get_fournisseur_article( (int)  $this->session->userdata['user']['id_foreign_fournisseur'] );
				}
				
				if(isset($_POST['start_date']) AND isset($_POST['end_date'])  AND !empty($_POST['start_date']) AND !empty($_POST['end_date'])){
					$commandes = $this->Commande_model->get_commande_by_periode($_POST['start_date'], $_POST['end_date']);
					$commandes 	= $this->format_global_commandes_array($commandes);

					if( $this->session->userdata['user']['id_foreign_fournisseur'] <> 1 ){
						$articles 	= $this->Article_model->get_fournisseur_article( (int)  $this->session->userdata['user']['id_foreign_fournisseur'] );
						$commandes 	= $this->format_all_commandes_array( $commandes, (int) $this->session->userdata['user']['id_foreign_fournisseur'] );
					}

					if( isset($_POST['id_fournisseur'])  AND $_POST['id_fournisseur'] <> 'null' ){
						$articles 	= $this->Article_model->get_fournisseur_article(   $_POST['id_fournisseur'] );
						$commandes 	= $this->format_all_commandes_array( $commandes, $_POST['id_fournisseur'] );
					}
				}
				




				
				$crud->set_table('sexe');
				$crud->unset_operations();
				$output = $crud->render();

				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ["rapport_link_active"=>$rapport_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);

				$this->load->view("rapport/rapport",[

					'header' => $header, 
					'navbar'=>$navbar,
					'footer' => $footer,
					'devise'=>$devise,
					'output'=>$output->output,
					'commandes'=>$commandes,
					'articles'=>$articles,
					'fournisseurs'=>$fournisseurs,

				]);
		
			}else{
				redirect($_SERVER['HTTP_REFERER']);
			}
		}else {
			redirect("Starter/login");
		}
	
	}

	public function format_all_commandes_array(array $commandes, int $id_fournisseur )
	{
		$final_commandes = [];
		$total_commandes = 0;

		

		foreach ($commandes['commandes'] as $key_in => $commande) {
			if(!empty( $commande['ARTICLES'] )){
				$articles 		= [];
				$commande_total = 0;
				foreach ($commande['ARTICLES'] as $key => $article) {
					if($article['id_foreign_fournisseur']['id_fournisseur'] == $id_fournisseur){
						$articles [] 	= $article;
						$commande_total	= $commande_total + $article['montant_total'];
					}
				}

				if( !empty($articles) ){
					$commande['ARTICLES'] = $articles;
					$commande['total'] = $commande_total;
					$final_commandes['commandes'][] = $commande;
					$total_commandes = $total_commandes + $commande_total; 
				}
			}
		}
		
		$final_commandes['total_commandes'] = $total_commandes;
		return $final_commandes;
	}


	public function format_global_commandes_array(array $commandes )
	{
		$final_commandes = [];
		$total_commandes = 0;

		foreach ($commandes as $key_in => $commande) {

			$client = $this->Client_model->get_client( (int) $commande['id_foreign_client']);
			if(!empty($client) ){
				$commande['id_foreign_client'] = $client;
			}

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

				$commande['id_status'] =$status_commande;
			}


			$articles_commandes = $this->Article_commande_model->get_article_commande_by_id_commande((int) $commande['id_commande']);
			
			if(!empty($articles_commandes)){

				$total_commande = 0;
				foreach ($articles_commandes as $key => $article_commande) {
					$article = $this->Article_model->get_article( (int) $article_commande['id_foreign_article']);
					
					if( !empty($article) ){
						$fournisseur = $this->Fournisseur_model->get_fournisseur( (int) $article['id_foreign_fournisseur']);
						if(!empty($fournisseur)){
							$article['id_foreign_fournisseur'] 		= $fournisseur;
							$article['article_commande_quantite'] 	= $article_commande['article_commande_quantite'];
							$article['montant_total'] 				= $article_commande['montant_total'];
							$total_commande 						= $total_commande + $article_commande['montant_total'];
						}
					}
					$commande['total'] = $total_commande;
					$commande['ARTICLES'][] = $article;
				}

				$final_commandes['commandes'][] = $commande;
				$total_commandes = $total_commandes + $commande['total']; 
			}

		}

		$final_commandes['total_commandes'] = $total_commandes;
		return $final_commandes;
	}
	

}
