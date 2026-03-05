<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT");

defined('BASEPATH') OR exit('No direct script access allowed');


class AppCouple extends CI_Controller {

	public function index($id_tel_user=null)
	{
		$couple 			= $this->Couple_model->get_couple_by_tel($id_tel_user);
		$articles_couples 	= (!empty($couple)) ? $this->Couple_model->get_couple_article_by_id_foreign_couple($couple['id_couple']) : [];
		$articles_couples 	= $this->formart_couples_articles($articles_couples);
		$userAppData 		= $this->getGlobalUser($id_tel_user);
		$categories 		= $this->getAppCategoriesAndSousCategorie(); 
		$foursLastArticles	= $this->getFourLastArticles();
		$sixLastArticles	= $this->getSixLastArticles();
		$nineLastArticles	= $this->getNineLastArticles();
		$header 			= $this->load->view("site_app_views/header", [], true);
		$footersection 		= $this->load->view("site_app_views/footersection", ['foursLastArticles'=>$foursLastArticles], true);
		$footer 			= $this->load->view("site_app_views/footer", [], true);

		//echoDie($articles_couples);

		$this->load->view("site_app_views/couple", 
		[
			'categories'=>$categories,
			'userAppData'=>$userAppData,
			'header'=>$header,
			'footersection'=>$footersection,
			'footer'=>$footer,
			'foursLastArticles'=>$foursLastArticles,
			'sixLastArticles'=>$sixLastArticles,
			'nineLastArticles'=>$nineLastArticles,
			'couple'=>$couple,
			'articles_couples'=>$articles_couples,
		]);
	}


	public function couple($id_tel_user=null)
	{
		
		$code_couple 		= htmlspecialchars($_POST['code_couple']);
		$couple 			= $this->Couple_model->get_couple_by_code($code_couple);
		$articles_couples 	= (!empty($couple)) ? $this->Couple_model->get_couple_article_by_id_foreign_couple($couple['id_couple']) : [];		
		$articles_couples 	= (!empty($articles_couples)) ? $this->formart_couples_articles($articles_couples) : [];
		$userAppData 		= $this->getGlobalUser($id_tel_user);
		$categories 		= $this->getAppCategoriesAndSousCategorie(); 
		$foursLastArticles	= $this->getFourLastArticles();
		$sixLastArticles	= $this->getSixLastArticles();
		$nineLastArticles	= $this->getNineLastArticles();
		$header 			= $this->load->view("site_app_views/header", [], true);
		$footersection 		= $this->load->view("site_app_views/footersection", ['foursLastArticles'=>$foursLastArticles], true);
		$footer 			= $this->load->view("site_app_views/footer", [], true);

		//echoDie($articles_couples);

		if(!empty($couple)){
	
			$this->load->view("site_app_views/listecouple", 
			[
				'categories'=>$categories,
				'userAppData'=>$userAppData,
				'header'=>$header,
				'footersection'=>$footersection,
				'footer'=>$footer,
				'foursLastArticles'=>$foursLastArticles,
				'sixLastArticles'=>$sixLastArticles,
				'nineLastArticles'=>$nineLastArticles,
				'couple'=>$couple,
				'articles_couples'=>$articles_couples,
			]);

		}else {
			$this->load->view("site_app_views/siteempty",[ 
				'categories'=>$categories,
				'userAppData'=>$userAppData,
				'header'=>$header,
				'footersection'=>$footersection,
				'footer'=>$footer,
				'nineLastArticles'=>$nineLastArticles,
			]);
		}
		
	}


	public function siteindex($id_tel_user=null)
	{
	}

	public function login($message=null)
	{
		$userAppData 		= $this->getGlobalUser();
		$categories 		= $this->getAppCategoriesAndSousCategorie(); 
		$foursLastArticles	= $this->getFourLastArticles();
		$nineLastArticles	= $this->getNineLastArticles();
		$header 			= $this->load->view("site_app_views/header", [], true);
		$footersection 		= $this->load->view("site_app_views/footersection", ['foursLastArticles'=>$foursLastArticles], true);
		$footer 			= $this->load->view("site_app_views/footer", [], true);

		if ($message == '12022023') {
			$message = "Votre compte a été bien crée cher couple Cadeau Mart prendra Contact avec vous très rapidement pour la suite. Merci " ;
		}elseif ($message == '12022024') {
			$message = "Numéro de téléphone ou mot de passe incorrect !";
		} 

		$this->load->view("site_app_views/appcouplelogin", 
		[
			'categories'=>$categories,
			'userAppData'=>$userAppData,
			'header'=>$header,
			'footersection'=>$footersection,
			'footer'=>$footer,
			'foursLastArticles'=>$foursLastArticles,
			'nineLastArticles'=>$nineLastArticles,
			'message'=>$message,
		]);
		
	}


	public function register($id_tel_user=null)
	{
		$userAppData 		= $this->getGlobalUser($id_tel_user);
		$categories 		= $this->getAppCategoriesAndSousCategorie(); 
		$foursLastArticles	= $this->getFourLastArticles();
		$nineLastArticles	= $this->getNineLastArticles();
		$header 			= $this->load->view("site_app_views/header", [], true);
		$footersection 		= $this->load->view("site_app_views/footersection", ['foursLastArticles'=>$foursLastArticles], true);
		$footer 			= $this->load->view("site_app_views/footer", [], true);

		//echoTab($userAppData);

		$this->load->view("site_app_views/appcoupleregister", 
		[
			'categories'=>$categories,
			'userAppData'=>$userAppData,
			'header'=>$header,
			'footersection'=>$footersection,
			'footer'=>$footer,
			'foursLastArticles'=>$foursLastArticles,
			'nineLastArticles'=>$nineLastArticles,
		]);
		
	}


	public function getFourLastArticles()
	{
		return $this->Article_model->get_four_last_article();
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

	public function getSixLastArticles()
	{
		return $this->Article_model->get_six_last_article();
	}


	public function process_register(){

		$nom_couple = htmlspecialchars($_POST['nom_couple']);
		$tel_whatsapp_couple = htmlspecialchars($_POST['tel_couple']);
		$email_couple = htmlspecialchars($_POST['eamil_couple']);
		$mot_de_passe = htmlspecialchars($_POST['mot_de_passe_couple']);
		$mot_de_passe = password_hash($mot_de_passe, PASSWORD_DEFAULT);
		$date_mariage = htmlspecialchars($_POST['date_mariage']);
		$code_couple = time();
		
		$array_insert_couple =[
			'nom_couple'=>$nom_couple,
			'tel_whatsapp_couple'=>$tel_whatsapp_couple,
			'email_couple'=>$email_couple,
			'date_mariage'=>$date_mariage,
			'code_couple'=>$code_couple,
			'mot_de_passe'=>$mot_de_passe,
			'date_enregistrement_couple'=>date('Y-m-d'),
		];

		$couple  = $this->Couple_model->get_couple_by_tel($tel_whatsapp_couple);

		if (empty($couple)) {

			$this->Couple_model->add_couple($array_insert_couple);

			$to = "tshike68@gmail.com";
			$subject = "Nouveau Couple sur Cadeau Mart";
			$txt = "Bonjour Admin vous avez un nouveau couple sur Cadeau Mart CODE : ".$code_couple." prière de prende contact avec eux. ";
			$headers = "From: info@cadeaumart.com" . "\r\n" .
			"CC: pascalmmp@gmail.com";

			//mail($to,$subject,$txt,$headers);

		}

		redirect("AppCouple/login/12022023");
	}


	public function process_login(){

		$tel_whatsapp_couple 	= htmlspecialchars($_POST['tel_whatsapp_couple']);
		$mot_de_passe 			= htmlspecialchars($_POST['mot_de_passe_couple']);
		$couple 				= $this->Couple_model->get_couple_by_tel($tel_whatsapp_couple);

		if (!empty($couple)) {			
			if(password_verify($this->security->xss_clean($mot_de_passe), $couple['mot_de_passe'])){
				$this->index($tel_whatsapp_couple);
			}else {
				redirect('AppCouple/login/12022024/');
			}
		}else {
			redirect('AppCouple/login/12022024/');
		}
		
	}


	function formart_couples_articles(array $articles_couples_in)  {

		$tab_id_articles  = explode(',', $articles_couples_in['ids_articles']);
		$tab_quantites 	  = explode(',',$articles_couples_in['quantite']);
		$articles_couples = [];

		foreach ($tab_id_articles as $key => $id_article) {
			$article  = $this->Article_model->get_article($id_article);
			if (!empty($article)) {
				$article['prix_vente'] 	= 0;
				$article['quantite']	= 1;
				$last_entre_stock = $this->Entree_stock_model->get_last_entree_stock_by_id_article($article['id_article']);
				if (!empty($last_entre_stock)) {
					$article['prix_vente'] = $last_entre_stock['prix_vente'];
				}
				$article['quantite'] 	=  (isset($tab_quantites[$key]) AND !empty($tab_quantites[$key])) ? $tab_quantites[$key] : 1;
				$articles_couples[]		= $article;
			}
		}

		return $articles_couples;
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
