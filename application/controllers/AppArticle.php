<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT");

defined('BASEPATH') OR exit('No direct script access allowed');

class AppArticle extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index($id_article=null,$id_tel_user=null)
	{
		$this->siteindex($id_article,$id_tel_user);
	}


	
	public function siteindex($id_article=null,$id_tel_user=null)
	{
		if ($id_article == null) {
			redirect('AppIndex/siteindex/');
		}else{

			$article 				= $this->Article_model->get_article($id_article);
			$userAppData 			= $this->getGlobalUser($id_tel_user);
			$categories 			= $this->getAppCategoriesAndSousCategorie(); 
			$foursLastArticles		= $this->getFourLastArticles();
			$nineLastArticles		= $this->getNineLastArticles();
			$categories_articles	= $this->getAppCategoriesAndArticlesQuantity();
			$header 				= $this->load->view("site_app_views/header", [], true);
			$footersection 			= $this->load->view("site_app_views/footersection", ['foursLastArticles'=>$foursLastArticles], true);
			$footer 				= $this->load->view("site_app_views/footer", [], true);
			
			if( !empty($article) ){

				$article 				= $this->format_array_article($article);
				$arcticles_similaires	= $this->Article_model->get_article_by_id_categorie_limit_exclude_artilce($article['id_foreign_categorie'],$article['id_article'],6);
				$arcticles_similaires	= $this->format_array_articles_similaires($arcticles_similaires);

				$this->load->view("site_app_views/sitearticle", 
				[
					'article'=>$article,
					'arcticles_similaires'=>$arcticles_similaires,
					'categories'=>$categories,
					'userAppData'=>$userAppData,
					'header'=>$header,
					'footersection'=>$footersection,
					'footer'=>$footer,
					'nineLastArticles'=>$nineLastArticles,
					'categories_articles'=>$categories_articles,
				]);

			}else{

				$this->load->view("site_app_views/siteempty",[ 
					'categories'=>$categories,
					'userAppData'=>$userAppData,
					'header'=>$header,
					'footersection'=>$footersection,
					'footer'=>$footer,
					'nineLastArticles'=>$nineLastArticles,
					'categories_articles'=>$categories_articles,
				]);
			}
		}	
	}


	public function all($id_article=null,$id_tel_user=null)
	{

		$userAppData 			= $this->getGlobalUser($id_tel_user);
		$categories 			= $this->getAppCategoriesAndSousCategorie(); 
		$foursLastArticles		= $this->getFourLastArticles();
		$nineLastArticles		= $this->getNineLastArticles();
		$categories_articles	= $this->getAppCategoriesAndArticlesQuantity();
		$twelves_last_articles 	= $this->getTewlveLastArticles();
		$total_article_number 	= $this->get_total_article_number();
		$header 				= $this->load->view("site_app_views/header", [], true);
		$footersection 			= $this->load->view("site_app_views/footersection", ['foursLastArticles'=>$foursLastArticles], true);
		$footer 				= $this->load->view("site_app_views/footer", [], true);


		$this->load->view("site_app_views/allarticles", 
		[
			'categories'=>$categories,
			'userAppData'=>$userAppData,
			'header'=>$header,
			'footersection'=>$footersection,
			'footer'=>$footer,
			'nineLastArticles'=>$nineLastArticles,
			'categories_articles'=>$categories_articles,
			'twelves_last_articles'=>$twelves_last_articles,
			'total_article_number'=>$total_article_number,
		]);

	}


	public function get_total_article_number(){
		return count($this->Article_model->get_all_article() );
	}

	public function getFourLastArticles()
	{
		return $this->Article_model->get_four_last_article();
	}


	public function getNineLastArticles()
	{
		$array_nine_last_articles = $this->Article_model->get_nine_last_article();
		
		foreach ($array_nine_last_articles as $key => $nine_article) {
			$nine_article['prix_vente'] = 0;
			$last_entre_stock = $this->Entree_stock_model->get_last_entree_stock_by_id_article($nine_article['id_article']);
			if (!empty($last_entre_stock)) {
				$nine_article['prix_vente'] = $last_entre_stock['prix_vente'];
			}
			$array_nine_last_articles[$key] = $nine_article;
		}
		return $array_nine_last_articles;
	}


	public function getTewlveLastArticles()
	{
		$array_twelve_last_articles = $this->Article_model->get_article_by_limit(12);
		
		foreach ($array_twelve_last_articles as $key => $twelve_article) {
			$twelve_article['prix_vente'] = 0;
			$article_section = $this->Section_article_model->get_article_section_article_by_id_article($twelve_article['id_article']);
			$section_article = $this->Section_article_model->get_section_article($article_section['id_section_article']);
			$last_entre_stock = $this->Entree_stock_model->get_last_entree_stock_by_id_article($twelve_article['id_article']);
			if (!empty($last_entre_stock)) {
				$twelve_article['prix_vente'] = $last_entre_stock['prix_vente'];
			}
			if (!empty($section_article)) {
				$twelve_article['id_section_article'] = $section_article['nom_section_article'];
			}
			$array_twelve_last_articles[$key] = $twelve_article;
		}
		return $array_twelve_last_articles;
	}


	public function format_array_article($array_article)  {

		$last_entre_stock 	= $this->Entree_stock_model->get_last_entree_stock_by_id_article($array_article['id_article']);
		$article_section 	= $this->Section_article_model->get_article_section_article_by_id_article($array_article['id_article']);
		$section_article 	= $this->Section_article_model->get_section_article($article_section['id_section_article']);
		$categorie 			= $this->Categorie_model->get_categorie($array_article['id_foreign_categorie']); 

		if (!empty($section_article)) {
			$array_article['id_section_article'] = $section_article['id_section_article'];
			$array_article['nom_section_article'] = $section_article['nom_section_article'];
		}

		if (!empty($categorie)) {
			$array_article['id_categorie'] = $categorie['id_categorie'];
			$array_article['nom_categorie'] = $categorie['nom_categorie'];
		}

		if (!empty($last_entre_stock)) {
			$array_article['prix_vente'] = $last_entre_stock['prix_vente'];
		}

		return $array_article;
	}


	public function format_array_articles_similaires($array_articles)  {

		if( !empty($array_articles)){

			foreach ($array_articles as $key => $array_article) {
				$last_entre_stock 	= $this->Entree_stock_model->get_last_entree_stock_by_id_article($array_article['id_article']);
				$article_section 	= $this->Section_article_model->get_article_section_article_by_id_article($array_article['id_article']);
				$section_article 	= $this->Section_article_model->get_section_article($article_section['id_section_article']);
				$categorie 			= $this->Categorie_model->get_categorie($array_article['id_foreign_categorie']); 
		
				if (!empty($section_article)) {
					$array_article['id_section_article'] = $section_article['id_section_article'];
					$array_article['nom_section_article'] = $section_article['nom_section_article'];
				}
		
				if (!empty($categorie)) {
					$array_article['id_categorie'] = $categorie['id_categorie'];
					$array_article['nom_categorie'] = $categorie['nom_categorie'];
				}
		
				if (!empty($last_entre_stock)) {
					$array_article['prix_vente'] = $last_entre_stock['prix_vente'];
				}
				$array_articles[$key] = $array_article;
			}

		}


		return $array_articles;
	}



	public function getAppCategoriesAndSousCategorie(){
		$categories = $this->Categorie_model->get_all_categories();
		
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



	public function getAppCategoriesAndArticlesQuantity(){
		$categories = $this->Categorie_model->get_all_categories();
		$categories_articles_array = [];
		foreach ($categories as $key => $categorie) {
			$categorie['qte'] = $this->Article_model->get_sum_article_by_id_foreign_categorie($categorie['id_categorie']);
			$categories_articles_array[]= $categorie;
		}
		return $categories_articles_array;
	}

	public function getPagePublicites()
	{
		return $this->Publicite_model->get_publicite_by_type(3);
	}


	public function panier( $id_article_remove = null, $refer_in = null ){

		$id_user_tel 			= (strlen( $this->input->post('usertel') ) >2 ) ? $this->input->post('usertel') : null;
		$userApp				= $this->getGlobalUser( $id_user_tel);
		$string_final_commande  = '';
		$compter_panier 		= 0;
		$sous_total 			= 0;

		if( $id_article_remove == null OR $id_article_remove == 'null' ){

			$id_article 			=  $this->input->post('id_article');
			$quantite 				=  $this->input->post('quantite');
			$article  				=  $this->Article_model->get_article($id_article);
			$article['quantite'] 	=  $quantite;
			$index 					=   $this->isArticleInCommande($userApp['commandes'],$article);
			

			$article_commande_client = [
				'id_foreign_client_tel' => $userApp['usertel'],
				'id_foreign_article' => $article['id_article'],
				'quantite' =>$article['quantite']
			];

			if(  $index <> -1){
				$userApp['commandes'][$index] = $article;
				if($id_user_tel <> null){
					$this->Article_commande_client_model->update_article_commande_client_by_tel_id_article($userApp['usertel'],$article['id_article'], $article_commande_client);
				}
			}else{
				array_push($userApp['commandes'],$article);
				if($id_user_tel <> null){
					$this->Article_commande_client_model->add_article_commande_client($article_commande_client);
				}

			}
			
		}else{

			if( $id_article_remove <> 'null' AND $id_article_remove <> null ){

				$id_user_tel 			= $_POST['id_user_tel'];
				$userApp['commandes'] 	= $this->removeArticleInCommande($userApp['commandes'], $id_article_remove);

				if($id_user_tel<>null){
					$this->Article_commande_client_model->delete_article_commande_client_by_tel_id_article($id_user_tel , $id_article_remove);
				}
			}

		}

		$refer 					= (strlen( $this->input->post('refer') )>3 ) ? $this->input->post('refer') : $this->agent->referrer();
		$refer 					= (strlen( $this->input->post('refer_add_card') )>3 ) ? $this->input->post('refer_add_card') : $refer;
		if($refer_in <> null) 	$refer = str_replace('_','/',$refer_in);
		$refer 					= str_replace( site_url(),"", $refer); 
		$refer 					= str_replace( base_url(),"", $refer); 
		$userApp 				= $this->getGlobalUser($id_user_tel);		
		$this->setGlobalUser($userApp);
		//echoDie($userApp['commandes']);

		foreach ($userApp['commandes'] as $key => $commande) {
			$compter_panier++;
			$sous_total  = $sous_total + ($commande['prix_vente'] * $commande['quantite']);

			$string_final_commande.='
				<li>
					<div class="cart-widget">
						<div class="dz-media me-3">
							<img src="'.base_url('assets/uploads/files/'.$commande['photo_article']).'" alt="">
						</div>
						<div class="cart-content">
							<h6 class="title"><a href="'.site_url('AppArticle/index/'.$commande['id_article']).'">'.$commande['nom_article'].'</a></h6>
							<div class="d-flex align-items-center">
								<div class="btn-quantity light quantity-sm me-3">
									<input type="number" value="'.$commande['quantite'].'" name="quantite_article_'.$compter_panier.'">
									<input type="number" value="'.$commande['id_article'].'" name="id_article_'.$compter_panier.'" style="display:none;">
								</div>
								<h6 class="dz-price text-primary mb-0">'.number_format($commande['prix_vente'], 0, ',',' ').'$</h6>
							</div>
						</div>
						<a href="'.site_url('AppArticle/panier/').$commande['id_article'].'/'.str_replace('/','_',$refer).'" class="dz-close">
							<i class="ti-close delete_article"  id_article="'.$commande['id_article'].'"></i>
						</a>
					</div>
				</li>';
		}

		if( $id_article_remove == null OR $id_article_remove == 'null' ){
			echo '__OK__'.count($userApp['commandes']).'__'.$compter_panier.'__'.$sous_total.'__'.$string_final_commande ;
		}else {
			echo '__OK__'.count($userApp['commandes']).'__'.$sous_total;
		}

	}


	public function isArticleInCommande($commandes, $articleIn)
	{
		foreach ($commandes as $key => $com) {

			if( isset($com['id_article']) AND isset( $articleIn['id_article'] ) ){
				if($com['id_article'] == $articleIn['id_article']){
					return $key;
				}
			}
		}
		return -1;
	}

	public function removeArticleInCommande($commandes, $articleIn)
	{
		foreach ($commandes as $key => $com) {
			if($com['id_article'] == $articleIn ){
				unset($commandes[$key]) ;
			}
		}
		return array_values($commandes);
	}

	public function updatePanier($id_user_tel=null, $refer_in=null){

		$total_panier 	= $this->input->post('total_article_panier');
		$id_user_tel 	= (strlen( $this->input->post('usertel') ) >2 ) ? $this->input->post('usertel') : null;
		$userApp		= $this->getGlobalUser($id_user_tel);
		$sous_total 	= 0;
		

		for ($i=1; $i <=$total_panier ; $i++) { 
			$id_article 			=  $this->input->post('id_article_'.$i);
			$quantite 				=  $this->input->post('quantite_article_'.$i);
			$article  				=  $this->Article_model->get_article($id_article);
			$last_entre_stock 		= $this->Entree_stock_model->get_last_entree_stock_by_id_article($article['id_article']);
			$article['prix_vente'] 	= (!empty($last_entre_stock)) ? $last_entre_stock['prix_vente'] : 0;
			$article['quantite'] 	=  $quantite;
			$index 					=   $this->isArticleInCommande($userApp['commandes'],$article);
			$sous_total  			= $sous_total + ($article['prix_vente'] * $article['quantite']);


			$article_commande_client = [
				'id_foreign_client_tel' => $userApp['usertel'],
				'id_foreign_article' => $article['id_article'],
				'quantite' =>$article['quantite']
			];

			if(  $index <> -1){
				$userApp['commandes'][$index] = $article;
				if($id_user_tel <> null){
					$this->Article_commande_client_model->update_article_commande_client_by_tel_id_article($userApp['usertel'],$article['id_article'], $article_commande_client);
				}
			}else{
				array_push($userApp['commandes'],$article);
				if($id_user_tel <> null){
					$this->Article_commande_client_model->add_article_commande_client($article_commande_client);
				}
			}
		}
		
		$this->setGlobalUser($userApp);
		echo '__OK__'.$sous_total;

	}
	

	public function loadMoreArticles(){

		$index_to_load_article  = (int) htmlspecialchars($_GET['index_limit_bdd']);
		$cadeaux  				= $this->Article_model->get_all_articles_by_limit_offset(12,$index_to_load_article);
		$userAppData 			= $this->getGlobalUser();

		if(!empty($cadeaux)){
			foreach ($cadeaux as $key => $cadeau) {
				$index_to_load_article = $index_to_load_article +1;
				$cadeau['prix_vente'] = 0;
				$article_section = $this->Section_article_model->get_article_section_article_by_id_article($cadeau['id_article']);
				$section_article = $this->Section_article_model->get_section_article($article_section['id_section_article']);
				$last_entre_stock = $this->Entree_stock_model->get_last_entree_stock_by_id_article($cadeau['id_article']);
				if (!empty($last_entre_stock)) {
					$cadeau['prix_vente'] = $last_entre_stock['prix_vente'];
				}
				if (!empty($section_article)) {
					$cadeau['id_section_article'] = $section_article['nom_section_article'];
				}
				echo '  <div class="article_container col-6 col-xl-3 col-lg-4 col-md-4 col-sm-6 m-md-b15 m-b30" idDatabase="'.$index_to_load_article.'">
							<div class="shop-card">
								<div class="dz-media">
									<img src="'.base_url('assets/uploads/files/'.$cadeau['photo_article']).'" alt="image">										
									<div class="shop-meta">

										<a href="'.site_url('AppArticle/index/'.$cadeau['id_article'].'/').'" class="btn btn-secondary btn-icon" style="background-color: #f31173; border-color:#f31173;">
											<i class="fa-solid fa-eye"></i>
											<span class="d-md-block d-none">Voir</span>
										</a>
										<a href="https://wa.me/243810274370?text=salut" class="btn btn-secondary btn-icon" style="background-color:#0d775e !important; border-color:#0d775e !important; ">
											<i class="fab fa-whatsapp"></i>
											<span class="d-md-block d-none">Whatsapp</span>
										</a>
									</div>
								</div>
								<div class="dz-content">
									<h5 class="title"><a href="'.site_url('AppArticle/index/'.$cadeau['id_article'].'/').'">'.$cadeau['nom_article'].'</a></h5>
									<ul class="star-rating">
										<li>
											<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M7.24805 0.734375L9.22301 5.01608L13.9054 5.57126L10.4436 8.77267L11.3625 13.3975L7.24805 11.0944L3.13355 13.3975L4.0525 8.77267L0.590651 5.57126L5.27309 5.01608L7.24805 0.734375Z" fill="#FF8A00"></path>
											</svg>
										</li>
										<li>
											<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M7.24805 0.734375L9.22301 5.01608L13.9054 5.57126L10.4436 8.77267L11.3625 13.3975L7.24805 11.0944L3.13355 13.3975L4.0525 8.77267L0.590651 5.57126L5.27309 5.01608L7.24805 0.734375Z" fill="#FF8A00"></path>
											</svg>
										</li>
										<li>
											<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M7.24805 0.734375L9.22301 5.01608L13.9054 5.57126L10.4436 8.77267L11.3625 13.3975L7.24805 11.0944L3.13355 13.3975L4.0525 8.77267L0.590651 5.57126L5.27309 5.01608L7.24805 0.734375Z" fill="#FF8A00"></path>
											</svg>
										</li>
										<li>
											<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M7.24805 0.734375L9.22301 5.01608L13.9054 5.57126L10.4436 8.77267L11.3625 13.3975L7.24805 11.0944L3.13355 13.3975L4.0525 8.77267L0.590651 5.57126L5.27309 5.01608L7.24805 0.734375Z" fill="#FF8A00"></path>
											</svg>
										</li>
										<li>
											<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M7.24805 0.734375L9.22301 5.01608L13.9054 5.57126L10.4436 8.77267L11.3625 13.3975L7.24805 11.0944L3.13355 13.3975L4.0525 8.77267L0.590651 5.57126L5.27309 5.01608L7.24805 0.734375Z" fill="#E4E5E8"></path>
											</svg>
										</li>
									</ul>
									<h6 class="price">
										<del>'.number_format($cadeau['prix_vente']+50,0,',',' ').'$</del>
										'.number_format($cadeau['prix_vente'],0,',',' ').'$
									</h6>
									<form class="form_ajout_panier" method="POST"  name="form_add_article_' . $cadeau['id_article'] . '" style="width: 100%; margin-top:4%;"> 
										<input type="number" value="1" name="quantite" hidden>
										<input type="number" value="' . $cadeau['id_article'] . '" name="id_article" hidden>
										<input type="text"   value="" name="refer" hidden>
										<input type="text"   value="" name="refer_add_card" hidden>
										<input type="text"   value="' . $userAppData['usertel'] . '" name="usertel" hidden>                                                                                               
										<button class="btn btn-secondary"  style="width: 100%; height:30px; padding-left: 2%; padding-right: 2%;"  id="ajout-panier" type="submit">Ajouter au Panier</button>
									</form>
								</div>
								<div class="product-tag">
									<span class="badge badge-secondary">EN VENTE</span>
									<span class="badge badge-primary" style="background-color: #f31173;">'.$cadeau['id_section_article'].'</span>
								</div>
							</div>	
						</div>';
			}
		}

		

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
