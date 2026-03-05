<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT");

defined('BASEPATH') OR exit('No direct script access allowed');


class AppFournisseur extends CI_Controller {

	public function index($id_categorie,$id_tel_user=null)
	{
		
		if(  isMobile() ){
			if ($id_categorie == null) {
				redirect('AppIndex');
			}else{
				$fournisseurs 			= $this->getFournisseursByIdCategorie($id_categorie);
				$categories 			= $this->getAppCategoriesAndSousCategorie(); 
				$restaurants 			= $this->getRestaurateurs();
				$supermarchets 			= $this->getSuperMachets();
				$pharmacies 			= $this->getPharmacies();
				$userAppData 			= $this->getGlobalUser($id_tel_user);
				$fournisseurs_articles 	= $this->Fournisseur_model->get_all_fournisseurs();
				$categories_articles	= $this->Categorie_model->get_all_categories();
				$footer 				= $this->load->view("app/footer", [], true);
				$header 				= $this->load->view("app/header", [], true);
				$search_bar 			= $this->load->view("app/search_bar", ['fournisseurs'=>$fournisseurs_articles,'categories_articles'=>$categories_articles], true);
		
	
				if( !empty($fournisseurs) ){
					$this->load->view("app/fournisseur", 
					[
						'categories'=>$categories,
						'fournisseurs'=>$fournisseurs,
						'restaurants'=>$restaurants,
						'supermarchets'=>$supermarchets,
						'pharmacies'=>$pharmacies,
						'userAppData'=>$userAppData,
						'footer'=>$footer,
						'header'=>$header,
						'search_bar'=>$search_bar,
					]);
				}else{
					$this->load->view("app/empty",[ 
						'categories'=>$categories,
						'restaurants'=>$restaurants,
						'supermarchets'=>$supermarchets,
						'pharmacies'=>$pharmacies,
						'userAppData'=>$userAppData,
						'footer'=>$footer,
						'header'=>$header,
						'search_bar'=>$search_bar,
					 ]);
				}
				
			}	
		}else {
			$this->siteindex($id_categorie,$id_tel_user);
		}

	}

	public function siteindex($id_categorie,$id_tel_user=null)
	{
		if(  !isMobile() ){
			if ($id_categorie == null) {
				redirect('AppIndex/siteindex/');
			}else{
				$fournisseurs 			= $this->getFournisseursByIdCategorie($id_categorie);
				$categories 			= $this->getAppCategoriesAndSousCategorie(); 
				$restaurants 			= $this->getRestaurateurs();
				$supermarchets 			= $this->getSuperMachets();
				$pharmacies 			= $this->getPharmacies();
				$userAppData 			= $this->getGlobalUser($id_tel_user);
				$fournisseurs_articles 	= $this->Fournisseur_model->get_all_fournisseurs();
				$categories_articles	= $this->Categorie_model->get_all_categories();
				$footer 				= $this->load->view("app/footer", [], true);
				$header 				= $this->load->view("app/header", [], true);
				$search_bar 			= $this->load->view("app/search_bar", ['fournisseurs'=>$fournisseurs_articles,'categories_articles'=>$categories_articles], true);
		
				//echoDie($fournisseurs[0]);
	
				if( !empty($fournisseurs) ){
					$this->load->view("app/sitefournisseur", 
					[
						'categories'=>$categories,
						'fournisseurs'=>$fournisseurs,
						'restaurants'=>$restaurants,
						'supermarchets'=>$supermarchets,
						'pharmacies'=>$pharmacies,
						'userAppData'=>$userAppData,
						'footer'=>$footer,
						'header'=>$header,
						'search_bar'=>$search_bar,
					]);
				}else{
					$this->load->view("app/siteempty",[ 
						'categories'=>$categories,
						'restaurants'=>$restaurants,
						'supermarchets'=>$supermarchets,
						'pharmacies'=>$pharmacies,
						'userAppData'=>$userAppData,
						'footer'=>$footer,
						'header'=>$header,
						'search_bar'=>$search_bar,
					 ]);
				}
				
			}	
		}else {
			$this->index($id_categorie,$id_tel_user);
		}
		
	}

	public function filter($id_fournisseur,$id_categorie =null,$id_sous_categorie=null,$id_tel_user=null)
	{
		if ($id_fournisseur == null AND $id_categorie == null) {
			redirect('AppIndex');
		}else{
			
			if($id_sous_categorie <>null AND strlen($id_sous_categorie) >0 ){
				$articles = $this->Article_model->get_article_by_id_fournisseur_id_categorie_id_sous_categorie($id_fournisseur,$id_categorie,$id_sous_categorie);
			}

			if($id_categorie <>null AND strlen($id_categorie) >0 AND strlen($id_categorie) <5 ) {
				$articles = $this->Article_model->get_article_by_id_fournisseur_id_categorie($id_fournisseur,$id_categorie);
			}else{
				$id_categorie = null;
			}

			if($id_categorie == null AND $id_sous_categorie  == null ) {
				$articles = $this->Article_model->get_fournisseur_article($id_fournisseur);
			}

			$id_tel_user 			= ($id_sous_categorie >= 12) ? $id_sous_categorie : $id_tel_user;
			$categories 			= $this->getAppCategoriesAndSousCategorie(); 
			$restaurants 			= $this->getRestaurateurs();
			$supermarchets 			= $this->getSuperMachets();
			$pharmacies 			= $this->getPharmacies();
			$userAppData 			= $this->getGlobalUser( $id_tel_user);
			$fournisseurs_articles 	= $this->Fournisseur_model->get_all_fournisseurs();
			$categories_articles	= $this->Categorie_model->get_all_categories();
			$footer 				= $this->load->view("app/footer", [], true);
			$header 				= $this->load->view("app/header", [], true);
			$search_bar 			= $this->load->view("app/search_bar", ['fournisseurs'=>$fournisseurs_articles,'categories_articles'=>$categories_articles], true);


			if( !empty($articles) ){ 
				$categorie					= $this->Categorie_model->get_categorie($id_categorie); 
				$fournisseur				= $this->Fournisseur_model->get_fournisseur($id_fournisseur); 
				$categories_fournisseurs 	= $this->getCategories($articles);
				$sous_categories 		    = $this->getSousCategories($articles);
				
				$this->load->view("app/article_fournisseur", 
				[
					'articles'	=>$articles,
					'categories'=>$categories,
					'categorie'=>$categorie,
					'categories_fournisseurs'=>$categories_fournisseurs,
					'sous_categories'=>$sous_categories,
					'fournisseur'	=>$fournisseur,
					'restaurants'=>$restaurants,
					'supermarchets'=>$supermarchets,
					'pharmacies'=>$pharmacies,
					'userAppData'=>$userAppData,
					'footer'=>$footer,
					'header'=>$header,
					'search_bar'=>$search_bar,
				]);

			}else{
				$this->load->view("app/empty",[ 
					'categories'=>$categories,
					'restaurants'=>$restaurants,
					'supermarchets'=>$supermarchets,
					'pharmacies'=>$pharmacies,
					'userAppData'=>$userAppData,
					'footer'=>$footer,
					'header'=>$header,
					'search_bar'=>$search_bar,
				 ]);
			}
		}
	}

	public function sitefilter($id_fournisseur,$id_categorie =null,$id_sous_categorie=null,$id_tel_user=null)
	{
		if ($id_fournisseur == null AND $id_categorie == null) {
			redirect('AppIndex/siteindex/');
		}else{
			
			if($id_sous_categorie <>null AND strlen($id_sous_categorie) >0 ){
				$articles = $this->Article_model->get_article_by_id_fournisseur_id_categorie_id_sous_categorie($id_fournisseur,$id_categorie,$id_sous_categorie);
			}

			if($id_categorie <>null AND strlen($id_categorie) >0 AND strlen($id_categorie) <5 ) {
				$articles = $this->Article_model->get_article_by_id_fournisseur_id_categorie($id_fournisseur,$id_categorie);
			}else{
				$id_categorie = null;
			}

			if($id_categorie == null AND $id_sous_categorie  == null ) {
				$articles = $this->Article_model->get_fournisseur_article($id_fournisseur);
			}

			$id_tel_user 			= ($id_sous_categorie >= 12) ? $id_sous_categorie : $id_tel_user;
			$categories 			= $this->getAppCategoriesAndSousCategorie(); 
			$restaurants 			= $this->getRestaurateurs();
			$supermarchets 			= $this->getSuperMachets();
			$pharmacies 			= $this->getPharmacies();
			$userAppData 			= $this->getGlobalUser( $id_tel_user);
			$fournisseurs_articles 	= $this->Fournisseur_model->get_all_fournisseurs();
			$categories_articles	= $this->Categorie_model->get_all_categories();
			$footer 				= $this->load->view("app/footer", [], true);
			$header 				= $this->load->view("app/header", [], true);
			$search_bar 			= $this->load->view("app/search_bar", ['fournisseurs'=>$fournisseurs_articles,'categories_articles'=>$categories_articles], true);


			if( !empty($articles) ){ 
				$categorie					= $this->Categorie_model->get_categorie($id_categorie); 
				$fournisseur				= $this->Fournisseur_model->get_fournisseur($id_fournisseur); 
				$categories_fournisseurs 	= $this->getCategories($articles);
				$sous_categories 		    = $this->getSousCategories($articles);
				
				$this->load->view("app/sitefournisseurfilter", 
				[
					'articles'	=>$articles,
					'categories'=>$categories,
					'categorie'=>$categorie,
					'categories_fournisseurs'=>$categories_fournisseurs,
					'sous_categories'=>$sous_categories,
					'fournisseur'	=>$fournisseur,
					'restaurants'=>$restaurants,
					'supermarchets'=>$supermarchets,
					'pharmacies'=>$pharmacies,
					'userAppData'=>$userAppData,
					'footer'=>$footer,
					'header'=>$header,
					'search_bar'=>$search_bar,
				]);

			}else{
				$this->load->view("app/siteempty",[ 
					'categories'=>$categories,
					'restaurants'=>$restaurants,
					'supermarchets'=>$supermarchets,
					'pharmacies'=>$pharmacies,
					'userAppData'=>$userAppData,
					'footer'=>$footer,
					'header'=>$header,
					'search_bar'=>$search_bar,
				 ]);
			}
		}
	}



	public function getAppCategoriesAndSousCategorie(){
		$categories = $this->Categorie_model->get_fromatter_categories();
		
		foreach ($categories as $key => $categorie) {
			$sous_categorie_array = [];
			$categorie_sous_categories = $this->Categorie_sous_categorie_model->get_all_categorie_from_categorie_sous_categories($categorie['id_categorie']);
			if($categorie_sous_categories){
				foreach ($categorie_sous_categories as $index => $categorie_sous_categorie) {
					$sous_categories = $this->Sous_categorie_model->get_sous_categorie( $categorie_sous_categorie['id_sous_categorie'] );
					if($sous_categories){
						array_push($sous_categorie_array, $sous_categories);
					}
				}
			}
			$categories[$key]['sous_categories'] = $sous_categorie_array;
		}
		return $categories;
	}

	public function getPagePublicites()
	{
		return $this->Publicite_model->get_publicite_by_type(3);
	}

	public function getFournisseursByIdCategorie($id_categorie)
	{
		$fournisseurs 	= $this->Fournisseur_model->get_all_fournisseurs();
		$categorie		= $this->Categorie_model->get_categorie($id_categorie);
		$maisons_ventes = ['id_categorie'=>$id_categorie,'nom_categorie'=>$categorie['nom_categorie'], 'icon_categorie'=>$categorie['icon_categorie']];
		foreach ($fournisseurs as $key => $maison) {
			if(!empty( $this->Article_model->get_article_by_id_fournisseur_id_categorie($maison['id_fournisseur'], $id_categorie) ) ){
				array_push( $maisons_ventes,$maison);
			}
		}
		return $maisons_ventes;
	}

	public function getSousCategories($articles){
		$sous_categories =[];
		if (!empty( $articles) ) {
			
			foreach ($articles as $key => $article) {
				$sous_categorie			= $this->Sous_categorie_model->get_sous_categorie($article['id_foreign_sous_categorie']); 
				if(!empty( $article) ){
					if( ! in_array($sous_categorie, $sous_categories)){
						array_push($sous_categories, $sous_categorie);
					}
					 
				}
			}
		}
		return $sous_categories;
	}

	public function getCategories($articles){
		$categories =[];
		if (!empty( $articles) ) {
			
			foreach ($articles as $key => $article) {
				$categorie			= $this->Categorie_model->get_categorie($article['id_foreign_categorie']); 
				if(!empty( $categorie) ){
					if( ! in_array($categorie, $categories)){
						array_push($categories, $categorie);
					}
					 
				}
			}
		}
		return $categories;
	}
	

	public function getPharmacies()
	{
		$pharmacies = $this->Fournisseur_model->get_all_fournisseurs();
		$shoppharmacies = ['id_categorie'=> 3];
		foreach ($pharmacies as $key => $pharmacie) {
			if(!empty( $this->Article_model->get_article_by_id_fournisseur_id_categorie($pharmacie['id_fournisseur'], 3) ) ){
				array_push( $shoppharmacies,$pharmacie);
			}
		}

		return $shoppharmacies;
	}

	public function getRestaurateurs()
	{
		$restaurateurs = $this->Fournisseur_model->get_all_fournisseurs();
		$restaurants = ['id_categorie'=> 9];
		foreach ($restaurateurs as $key => $resto) {
			if(!empty( $this->Article_model->get_article_by_id_fournisseur_id_categorie($resto['id_fournisseur'], 9) ) ){
				array_push( $restaurants,$resto);
			}
		}

		return $restaurants;
	}


	public function getSuperMachets()
	{
		$supermarchets = $this->Fournisseur_model->get_all_fournisseurs();
		$shopsupermarches = ['id_categorie'=> 10];
		foreach ($supermarchets as $key => $supermarchet) {
			if(!empty( $this->Article_model->get_article_by_id_fournisseur_id_categorie($supermarchet['id_fournisseur'], 10) ) ){
				array_push( $shopsupermarches,$supermarchet);
			}
		}

		return $shopsupermarches;
	}



	// Section Info On User App //

	public function getUserApp(){
		$user =[];

		if( !empty( get_cookie('usertel') ) ){
			$user['usertel'] 	= get_cookie('usertel');
			$user['username'] 	= get_cookie('username');
			$user['prenom'] 	= get_cookie('prenom');
			return $user;
		}


		if(isset( $this->session->get_userdata( )['usertel'] ) AND strlen( $this->session->get_userdata( )['usertel']) >2){
			$user['usertel'] 	= $this->session->get_userdata( )['usertel'];
			$user['username'] 	= $this->session->get_userdata( )['username'];
			$user['prenom'] 	= $this->session->get_userdata( )['prenom'];
			return $user;
		}

		return null;
	}


	public function setUserApp(){
		$user =[];
		set_cookie('username', 'cadeaumart_default_user',10512000);
		set_cookie('prenom', 'cadeaumart_default_user',10512000);
		set_cookie('usertel', "97".mt_rand(100000,999999),10512000);
		
		$user['usertel'] 	= get_cookie('username');
		$user['prenom'] 	= get_cookie('prenom');
		$user['username'] 	= get_cookie('usertel');

		if( empty($user['usertel']) AND strlen($user['usertel']) <3 ){
			$user['usertel'] 	= "97".mt_rand(100000,999999);
			$user['prenom'] 	= 'cadeaumart_default_user';
			$user['username'] 	= 'cadeaumart_default_user';
		}

		$this->session->set_userdata( $user );

		return $commandes;
	}


	public function initUser(){
		$userApp = [];
		if($this->getUserApp() == null){
			$userApp = $this->setUserApp();
		}else{
			$userApp = $this->getUserApp();
		}
		return $userApp;
	}

	public function getGlobalUser($id_tel_user=null)
	{
		$userAppData = [];

		if(! ($this->session->usertel) ){
			$userAppData 				=  $this->initUser();
			$userAppData['commandes'] 	= [];
			$userAppData['wishs'] 		= [];
			$this->session->set_userdata($userAppData);
		}else{
			$userAppData = $this->session->userdata();
		}

		$userAppData['usertel'] = (empty($userAppData['usertel'])) ? $id_tel_user : $userAppData['usertel'];

		if(!empty($userAppData['usertel'])){

			$commandes_client 			= [];
			$wishs_client 				= [];
			$commande_client 			=  $this->Article_commande_client_model->get_article_commande_client_by_tel($userAppData['usertel']);
			$wish_client 				=  $this->Article_wish_client_model->get_article_wish_client_by_tel($userAppData['usertel']);
			
			if(!empty($commande_client)){
				foreach ($commande_client as $key => $commande) {
					$article  = $this->Article_model->get_article($commande['id_foreign_article']);
					if(!empty($article)){
						$article['prix_vente'] = 0;
						$last_entre_stock = $this->Entree_stock_model->get_last_entree_stock_by_id_article($article['id_article']);
						if (!empty($last_entre_stock)) {
							$article['prix_vente'] = $last_entre_stock['prix_vente'];
						}
						$article['quantite'] 	=  $commande['quantite'];
						$commandes_client[]		= $article;
					}
				}
			}

			if(!empty($wish_client)){
				foreach ($wish_client as $key => $wish) {
					$article  = $this->Article_model->get_article($wish['id_foreign_article']);
					if(!empty($article)){
						$article['prix_vente'] = 0;
						$last_entre_stock = $this->Entree_stock_model->get_last_entree_stock_by_id_article($article['id_article']);
						if (!empty($last_entre_stock)) {
							$article['prix_vente'] = $last_entre_stock['prix_vente'];
						}
						$article['quantite'] 	=  $wish['quantite'];
						$wishs_client[]		= $article;
					}
				}
			}

			$userAppData['commandes'] 	= $commandes_client;
			$userAppData['wishs'] 		= $wishs_client;
			$userFromBdd = $this->Client_model->get_client_by_tel($userAppData['usertel']);

			if(!empty($userFromBdd)){
				$userFromBddTab 			= explode(" ", $userFromBdd['nom_client']);
				$userAppData['username'] 	= $userFromBddTab[0];
				$userAppData['prenom'] 		= $userFromBddTab[1];
				$userAppData['usertel'] 	= $userFromBdd['tel_whatsapp_client'];
				$userAppData['adresse'] 	= $userFromBdd['adresse_livraison_client'];
			}
		}

		return $userAppData;
	}

	public function setGlobalUser($userAppDataIn)
	{
		$this->session->set_userdata($userAppDataIn);
		set_cookie('username', $userAppDataIn['username'],10512000);
		set_cookie('prenom', $userAppDataIn['prenom'],10512000);
		set_cookie('usertel', $userAppDataIn['usertel'],10512000);

		return $userAppDataIn;
	}

	public function setGlobalUserFromApp(){
		$userset = "";
		if ($this->input->post('userapp')) {
			$userApp = $this->input->post('userapp');

			if( !empty( $this->Client_model->get_client_by_tel($userApp['usertel']) ) ){
				$userFromBdd 			= $this->Client_model->get_client_by_tel($userApp['usertel']);
				$userFromBddTab 		= explode(" ", $userFromBdd['nom_client']);
				$userApp['username']	= $userFromBddTab[0];
				$userApp['prenom']		= ($userFromBddTab[1] == " " OR strlen($userFromBddTab[1]) <2 ) ? $userFromBddTab[2] : $userFromBddTab[1];
				$userApp['usertel']		= $userFromBdd['tel_whatsapp_client'];
				$userApp['adresse']		= $userFromBdd['adresse_livraison_client'];
				$userset 				= "set from bdd ".$userApp['prenom'];

			}else{
				$user_insert = [
					'nom_client'=> $userApp['username'].' '.$userApp['prenom'],
					'tel_whatsapp_client'=>$userApp['usertel'],
					'adresse_livraison_client'=>$userApp['adresse'],
					'num_referal_client'=>'',
				];
				$this->Client_model->add_client($user_insert);
				$userset = "set after insert";
			}
		}
		echo $userset;
	}

	public function setScrollTop(){
		if(isset($_POST['scrolltop'])){
			$_SESSION['scrolltop'] = $_POST['scrolltop'];
			echo $_SESSION['scrolltop'];
		}
	}
	
	// End Section On User App //

}
