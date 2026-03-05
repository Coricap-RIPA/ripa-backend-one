<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT");

defined('BASEPATH') OR exit('No direct script access allowed');


class AppSearchOld extends CI_Controller {

	public function index($id_tel_user=null,$id_fournisseur=null,$id_categorie=null,$nom_article=null)
	{
		
		if(  isMobile() ){
			$sql 					= '';
			$articles 				= [];
			$categories 			= $this->getAppCategoriesAndSousCategorie();
			$restaurants 			= $this->getRestaurateurs();
			$supermarchets 			= $this->getSuperMachets();
			$pharmacies 			= $this->getPharmacies(); 
			$userAppData 			= $this->getGlobalUser($id_tel_user);
			$fournisseur_search		= [];
			$categorie 				= [];
			$fournisseurs 			= $this->Fournisseur_model->get_all_fournisseurs();
			$categories_articles	= $this->Categorie_model->get_all_categories();
	
			$footer 		= $this->load->view("app/footer", [], true);
			$header 		= $this->load->view("app/header", [], true);
			$search_bar 	= $this->load->view("app/search_bar", ['fournisseurs'=>$fournisseurs,'categories_articles'=>$categories_articles], true);
	
	
	
			if(isset($_SESSION['id_fournisseur']) AND $_SESSION['id_fournisseur'] <> null){
				$_POST['id_fournisseur'] 	= $_SESSION['id_fournisseur'];
				$_SESSION['id_fournisseur']	= null;
			}
			if(isset( $_SESSION['id_categorie'] ) AND $_SESSION['id_categorie'] <> null){
				$_POST['id_categorie'] 		= $_SESSION['id_categorie'];
				$_SESSION['id_categorie']	= null;
			}
			if(isset($_SESSION['nom_article']) AND $_SESSION['nom_article'] <> null){
				$_POST['nom_article'] 		= $_SESSION['nom_article'];
				$_SESSION['nom_article']	= null;
			}
	
	
			if(isset($id_fournisseur) AND $id_fournisseur <> null AND $id_fournisseur <> 'null'){
				$_POST['id_fournisseur'] 	= $id_fournisseur;
			}
			if(isset($id_categorie) AND $id_categorie <> null AND $id_categorie <> 'null'){
				$_POST['id_categorie'] 	= $id_categorie;
			}
			if(isset($nom_article) AND $nom_article <> null AND $nom_article <> 'null'){
				$_POST['nom_article'] 	= $nom_article;
			}
	
			
			if(isset($_POST['id_fournisseur']) AND $_POST['id_fournisseur']<>'null' ){
	
				if( strlen($sql) > 3){
					$sql.= " AND id_foreign_fournisseur=".$_POST['id_fournisseur'];
				}else{
					$sql = " id_foreign_fournisseur=".$_POST['id_fournisseur'];
				}
	
				$fournisseur_search = $this->Fournisseur_model->get_fournisseur( $_POST['id_fournisseur'] );
			}
	
			if(isset($_POST['id_categorie']) AND $_POST['id_categorie']<>'null' ){
				if( strlen($sql) > 3){
					$sql.= " AND id_foreign_categorie=".$_POST['id_categorie'];
				}else{
					$sql = " id_foreign_categorie=".$_POST['id_categorie'];
				}
				$categorie = $this->Categorie_model->get_categorie($_POST['id_categorie']); 
			}
	
			if(isset($_POST['nom_article']) AND strlen($_POST['nom_article'])> 3 ){
				if( strlen($sql) > 3){
					$sql.= " AND nom_article LIKE '".$_POST['nom_article']."%'";
				}else{
					$sql = " nom_article LIKE '".$_POST['nom_article']."%'";
				}
			}
	
			if( strlen( $sql) > 5){
				$articles = $this->Article_model->get_article_by_sql($sql);
			}
			
	
			if( !empty($articles) ){ 
				$sous_categories 		= $this->getSousCategories($articles);
				
				$this->load->view("app/search", 
				[
					'articles'=>$articles,
					'categories'=>$categories,
					'categorie' =>$categorie,
					'sous_categories' =>$sous_categories,
					'restaurants'=>$restaurants,
					'supermarchets'=>$supermarchets,
					'pharmacies' =>$pharmacies,
					'userAppData' =>$userAppData,
					'fournisseur_search' =>$fournisseur_search,
					'fournisseurs' =>$fournisseurs,
					'categories_articles' =>$categories_articles,
					'footer' =>$footer,
					'header' =>$header,
					'search_bar' =>$search_bar,
				]);
	
			}else{
				$this->load->view("app/empty",[ 
					'categories'=>$categories,
					'restaurants'=>$restaurants,
					'supermarchets'=>$supermarchets,
					'pharmacies'=>$pharmacies,
					'userAppData'=>$userAppData,
					'fournisseurs'=>$fournisseurs,
					'categories_articles'=>$categories_articles,
					'footer'=>$footer,
					'header'=>$header,
					'search_bar'=>$search_bar,
					]);
			}
		}else {
			$this->siteindex($id_tel_user, $id_fournisseur, $id_categorie, $nom_article);
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

	
	public function getUserApp(){
		if(strlen( get_cookie('usertel') ) >2){
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
		set_cookie('username', 'fromatter_default_user',10512000);
		set_cookie('prenom', 'fromatter_default_user',10512000);
		set_cookie('usertel', "97".str_shuffle('1430057'),10512000);
		
		$user['usertel'] 	= get_cookie('username');
		$user['prenom'] 	= get_cookie('prenom');
		$user['username'] 	= get_cookie('usertel');

		if( strlen($user['usertel']) <3 ){
			$user['usertel'] 	= "97".str_shuffle('1430057');
			$user['prenom'] 	= 'fromatter_default_user';
			$user['username'] 	= 'fromatter_default_user';
		}

		$this->session->set_userdata( $user );

		return $user;
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
			$this->session->set_userdata($userAppData);
		}else{
			$userAppData = $this->session->userdata();
		}

		if($id_tel_user <> null){
			$userFromBdd = $this->Client_model->get_client_by_tel($id_tel_user);
			if(!empty($userFromBdd)){
				$userFromBddTab 			= explode(" ", $userFromBdd['nom_client']);
				$userAppData['username'] 	= $userFromBddTab[0];
				$userAppData['prenom'] 		= $userFromBddTab[1];
				$userAppData['usertel'] 	= $userFromBdd['tel_whatsapp_client'];
				$userAppData['adresse'] 	= $userFromBdd['adresse_livraison_client'];
				$commandes_client 			= [];
				$commande_client 			=  $this->Article_commande_client_model->get_article_commande_client_by_tel($userAppData['usertel']);
				if(!empty($commande_client)){
					foreach ($commande_client as $key => $commande) {
						$article  = $this->Article_model->get_article($commande['id_foreign_article']);
						if(!empty($article)){
							$article['quantite'] 	=  $commande['quantite'];
							$commandes_client[]		= $article;
						}
					}
				}
				$userAppData['commandes'] = $commandes_client;
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

	public function setSessionAppSearch()
	{
		$_SESSION['id_fournisseur']  	= (isset( $_POST['id_fournisseur'] ) AND $_POST['id_fournisseur'] <> 'null') ? $_POST['id_fournisseur'] : null;
		$_SESSION['id_categorie']  		= (isset( $_POST['id_categorie'] ) AND $_POST['id_categorie']) ? $_POST['id_categorie'] : null;
		$_SESSION['nom_article']  		= (isset( $_POST['nom_article'] ) AND $_POST['nom_article']) ? $_POST['nom_article'] : null;

		echoTab($_POST);

	}

	
}
