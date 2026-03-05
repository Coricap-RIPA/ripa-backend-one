<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends CI_Controller {

	public function index($show_article=null,$id_article=null)
	{
		if(  $this->session->logged_in ){

			if(check_privilege('article', $this->session->user['id_role'], 'voir')){

				$crud = new grocery_CRUD();
				$devise = "USD";
				$articles_link_active 	= "link_menu_active";
				$categories_articles 	= $this->Categorie_model->get_all_categories();
				$fournisseurs_articles 	= $this->Fournisseur_model->get_all_fournisseurs();

				if($id_article<>null AND (int) $show_article == 9194){
					$crud->where(['id_article'=>$id_article]);
				}

				

				if( isset( $_POST['start_date'] )  AND isset($_POST['end_date'])  AND !empty( $_POST['start_date'] ) AND !empty( $_POST['end_date'] ) ){
					$sql = "date_enregistrement_article BETWEEN'".$_POST['start_date']."' AND '".$_POST['end_date']."'";
					$crud->where($sql);
				}
				// else {
				// 	$crud->where(['date_enregistrement_article'=>date('Y-m-d')]);
				// }


				if( isset( $_POST['id_foreign_categorie'] )  AND $_POST['id_foreign_categorie'] <> 'null'  ){
					$crud->where(['id_foreign_categorie'=>$_POST['id_foreign_categorie']]);
				}

				if( isset( $_POST['id_foreign_fournisseur'] )  AND $_POST['id_foreign_fournisseur'] <> 'null' ){
					$crud->where(['id_foreign_fournisseur'=>$_POST['id_foreign_fournisseur']]);
				}


				$crud->set_table('article');
				$crud->columns('id_article','nom_article','photo_article','id_foreign_fournisseur','Section','id_foreign_categorie','quantite');
				
				$crud->display_as('id_article','#');
				$crud->display_as('nom_article','Article');
				$crud->display_as('quantite','Quantité');
				$crud->display_as('photo_article','Photo');
				$crud->display_as('photo_article_2','Photo');
				$crud->display_as('photo_article_3','Photo');
				$crud->display_as('photo_article_4','Photo');
				$crud->display_as('video_article','Vidéo');
				$crud->display_as('prix_vente','Prix de vente / Unité '.$devise);
				$crud->display_as('id_foreign_fournisseur','Fournisseur');
				$crud->display_as('code','Code de l\'article ');
				$crud->display_as('id_foreign_categorie','Categorie du Produit');
				$crud->display_as('id_foreign_sous_categorie','Sous Categorie');
				$crud->display_as('id_foreign_is_acceuille','Afficher à l\'accueil');
				$crud->display_as('text_article','Description');
				$crud->display_as('date_enregistrement_article',' Date d\'enregistrement');
				
				$crud->set_subject('Un article');

				$crud->add_fields(array('nom_article','photo_article','photo_article_2','photo_article_3','photo_article_4','video_article','text_article','code','id_foreign_fournisseur','Section','id_foreign_categorie','id_foreign_sous_categorie','id_foreign_is_acceuille','date_enregistrement_article'));
				$crud->edit_fields(array('nom_article','photo_article','photo_article_2','photo_article_3','photo_article_4','video_article','text_article','code','id_foreign_fournisseur','Section','id_foreign_categorie','id_foreign_sous_categorie','id_foreign_is_acceuille','date_enregistrement_article'));
				

				$crud->callback_before_insert(array($this,'_onRowBeforeInserted'));
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_before_update(array($this,'_onRowBeforeUpdated'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));

				$crud->callback_column('quantite',array($this,'show_seuil_critique'));
				$crud->callback_column('revenue',array($this,'show_revenue'));
				
				$crud->set_relation('id_foreign_fournisseur','fournisseur','nom_fournisseur');
				$crud->set_relation('id_foreign_categorie','categorie','nom_categorie');
				$crud->set_relation('id_foreign_sous_categorie','sous_categorie','nom_sous_categorie');
				$crud->set_relation('id_foreign_is_acceuille','is_acceuille','designation');
				$crud->set_relation_n_n('Section', 'article_section', 'section_article', 'id_article', 'id_section_article', 'nom_section_article');

				$crud->set_field_upload('photo_article','assets/uploads/files');
				$crud->set_field_upload('photo_article_2','assets/uploads/files');
				$crud->set_field_upload('photo_article_3','assets/uploads/files');
				$crud->set_field_upload('photo_article_4','assets/uploads/files');
				$crud->set_field_upload('video_article','assets/uploads/files');
				$crud->unique_fields(array('code'));
				$crud->field_type('revenue', 'hidden');
				$crud->required_fields('nom_article','photo_article','Section','id_foreign_categorie','id_foreign_fournisseur');	
				$crud->unset_clone();
				$this->stateDisplay($crud);

				if( ! check_privilege('article', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('article', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( ! check_privilege('article', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}

				if( ! check_privilege('article', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}

				
				$crud->order_by('id_article','desc');

				$output = $crud->render();


				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['articles_link_active'=>$articles_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("article/article", [
					'header' => $header, 
					'navbar'=>$navbar, 
					'sidebar' => $sidebar, 
					'footer' => $footer,
					'devise'=>$devise,
					'output'=>$output->output,
					'categories_articles' => $categories_articles,
					'fournisseurs_articles' => $fournisseurs_articles,
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
				$crud->set_subject("Un article ");
				$crud->callback_field('photo_article',array($this,'_showImage'));
				$crud->callback_field('photo_article_2',array($this,'_showImage'));
				$crud->callback_field('photo_article_3',array($this,'_showImage'));
				$crud->callback_field('photo_article_4',array($this,'_showImage'));
				break;
			case 'list':
				$crud->set_subject("Un article");				
				break;
			case 'add':
				$crud->field_type('date_enregistrement_article', 'hidden');
				break;
			
			case 'edit':
				$crud->field_type('date_enregistrement_article', 'hidden');
				break;
			
			default:
				break;
		}

	}

	public function _showImage($value,$row)
	{
		return "<img src='".base_url('assets/uploads/files/'.$value)."' width='100px' height='100px' style='object-fit: content;'/>";
	}


	public function _showvideo($value,$row)
	{
		return "<video  width='300' height='250' style='object-fit: content;'> 
					<source src='".base_url('assets/uploads/files/'.$value)."'>
				</video>";
	}

	public function _onRowBeforeInserted($post_array)
	{
		$post_array['date_enregistrement_article'] = date("Y-m-d H:m:s");
		return $post_array;
	}

	public function _onRowInserted($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'article ',
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout d'un article ".$post_array["nom_article"],
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
			"nom_table"=> 'article',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour de l'article  ".$post_array["nom_article"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data = ['id_article'=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'article',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "suppression de la article ".$this->ModelGetTableRow->getObjectFieldValue($data,'article')->nom_article,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}


	
	public function show_prix_vente($value, $row)
	{
		return "<span><b>".number_format($value,0,'.',' ')." </b></span>";
	}



	public function show_seuil_critique($value, $row)
	{
		if($row->quantite <=  $row->seuil_critique){
			return "<span style='color:red;'><b>".$value."</b></span>";
		}else{
			return $value;
		}
	}

	public function show_revenue($value, $row)
	{
		return "<span><b>".number_format($value,0,'.',' ')." </b></span>";
	}

}
