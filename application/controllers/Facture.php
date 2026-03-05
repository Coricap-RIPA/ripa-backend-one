<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facture extends CI_Controller {


	function __construct()
	{
		parent::__construct();
		$this->load->library('ci_qr_code');
		$this->config->load('qr_code');
	} 
	
	public function index($show_article=null,$id_article=null)
	{
		if(  $this->session->logged_in ){

			if(check_privilege('facture', $this->session->user['id_role'], 'voir')){

				$crud = new grocery_CRUD();
				$facture_link_active	= 'link_menu_active';

				
				if( isset( $_POST['start_date'] )  AND isset($_POST['end_date'])){
					$sql = "date_facture BETWEEN'".$_POST['start_date']."' AND '".$_POST['end_date']."'";
					$crud->where($sql);
				}


				if( (int) $_SESSION['role']['id_role']<>1   ){
					$crud->where( ['id_foreign_entreprise'=> (int) $_SESSION['user']['id_entreprise_utilisateur'] ] );
				}



				$crud->set_table('facture');
				$crud->columns('id_facture','num_facture','num_reference','montant','id_foreign_devise','qr_code_facture','id_foreign_entreprise','id_foreign_status_facture','date_facture');
				
				$crud->display_as('id_facture','#');
				$crud->display_as('num_facture','No Facture');
				$crud->display_as('num_reference','Réf');
				$crud->display_as('montant','Montant');
				$crud->display_as('id_foreign_devise','Devise');
				$crud->display_as('qr_code_facture','Qr Facture');
				$crud->display_as('id_foreign_entreprise','Marchand');
				$crud->display_as('id_foreign_status_facture','Status');
				$crud->display_as('id_foreign_compte_money_entreprise','Compte');
				$crud->display_as('date_facture','Date');
				
				$crud->set_subject('Une Facture');

				$crud->add_fields(array('num_facture','num_reference','montant','id_foreign_compte_money_entreprise','id_foreign_devise','qr_code_facture','id_foreign_entreprise','id_foreign_status_facture','date_facture'));
				$crud->edit_fields(array('montant','id_foreign_compte_money_entreprise','id_foreign_devise','qr_code_facture','id_foreign_entreprise','id_foreign_status_facture','date_facture'));
				

				$crud->callback_before_insert(array($this,'_onRowBeforeInserted'));
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_before_update(array($this,'_onRowBeforeUpdated'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));

				$crud->callback_column('id_foreign_status_facture',array($this,'_callback_status'));
				$crud->callback_column('id_foreign_entreprise',array($this,'_callback_entreprise'));
				$crud->callback_column('Qr Code',array($this,'_callback_qr_code'));

				
				$crud->set_relation('id_foreign_devise','devise','abreviation');

				$crud->set_field_upload('qr_code_facture','/global/tmp/qr_codes/');
				$crud->required_fields('montant','id_foreign_devise','date_facture');
				$crud->field_type('id_foreign_status_facture','hidden');
				$crud->field_type('num_facture','hidden');
				$crud->field_type('num_reference','hidden');
				$crud->field_type('id_foreign_compte_money_entreprise','hidden');
				$crud->unset_clone();
				$this->stateDisplay($crud);

				if( (int) $_SESSION['role']['id_role']<>1  ){
					$crud->field_type('id_foreign_entreprise','hidden',(int) $_SESSION['user']['id_entreprise_utilisateur']);
				}else{
					$crud->set_relation('id_foreign_entreprise','entreprise','nom');
				}

				if( ! check_privilege('facture', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('facture', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( ! check_privilege('facture', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}

				if( ! check_privilege('facture', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}

				if( check_privilege('facture', $this->session->user['id_role'], 'editer') ){
					$crud->add_action('Payée | Non Payée', '', '', 'record',array($this,'_status_facture'));
				}


				
				$crud->order_by('id_facture','desc');

				$output = $crud->render();

				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['facture_link_active'=>$facture_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("facture/facture", ['header' => $header, 'navbar'=>$navbar, 'sidebar' => $sidebar, 'footer' => $footer,'output'=>$output->output]);
		

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
				$crud->set_subject("Une Facture ");
				break;
			case 'list':
				$crud->set_subject("Une Facture");				
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
		$id_entreprise  = 0;
		$entreprise 	= [];
		$num_facture    = 0;
		$num_reference  = 0;
		$short_code_ent = '';
		$numero_ent 	= '';
		$devise 		= $this->Devise_model->get_devise($post_array['id_foreign_devise']);
		$devise['abreviation'] 	= (!empty($devise)) ? $devise['abreviation'] : 'CDF';

		if( (int) $_SESSION['role']['id_role']<>1  ){
			$entreprise = $this->Entreprise_model->get_entreprise( (int) $_SESSION['user']['id_entreprise_utilisateur'] );
		}else{
			$entreprise = $this->Entreprise_model->get_entreprise( $post_array['id_foreign_entreprise'] );
		}

		if(!empty($entreprise)){
			$id_entreprise =  (int) $entreprise['id_entreprise'];
			$short_code_ent = $entreprise['nom_marchand'];
			$numero_ent = $entreprise['telephone_contact'];
		}

		$facture_index   = $this->Facture_index_model->get_last_facture_index_by_id_foreign_entreprise($id_entreprise);
		$reference_index = $this->Reference_index_model->get_last_reference_index_by_id_foreign_entreprise($id_entreprise);

		if(!empty($facture_index)){
			$num_facture = ( (int)$facture_index['num_start_facture'] > (int) $facture_index['num_facture_index']) ? (int)$facture_index['num_start_facture'] : (int) $facture_index['num_facture_index'];
		}

		if(!empty($reference_index)){
			$num_reference = ( (int)$reference_index['num_start_reference'] > (int) $reference_index['num_reference_index']) ? (int)$reference_index['num_start_reference'] : (int) $reference_index['num_reference_index'];
		}

		$num_facture 	=  $num_facture + 1;
		$num_reference  =  $num_reference + 1 ;

		$array_up_ins_fature_index = ['num_facture_index'=>$num_facture];
		$array_up_ins_reference_index = ['num_reference_index'=>$num_reference];


		if(empty($facture_index)){
			$array_up_ins_fature_index['num_start_facture'] = '0';
			$array_up_ins_fature_index['id_foreign_entreprise'] = $id_entreprise;
			$this->Facture_index_model->add($array_up_ins_fature_index);
		}else {
			$this->Facture_index_model->update($facture_index['id_facture_index'],$array_up_ins_fature_index);
		}

		if(empty($reference_index)){
			$array_up_ins_reference_index['num_start_reference'] = '0';
			$array_up_ins_reference_index['id_foreign_entreprise'] = $id_entreprise;
			$this->Reference_index_model->add($array_up_ins_reference_index);
		}else {
			$this->Reference_index_model->update($reference_index['id_reference_index'],$array_up_ins_reference_index);
		}

		$post_array['num_facture']     = $this->format_numero_facture($num_facture,$short_code_ent);
		$post_array['num_reference']   = $this->format_numero_reference($num_reference,$short_code_ent);
		$post_array['qr_code_facture'] = $this->generate_code_qr($numero_ent.'deepay'.$post_array['montant'].'deepay'.$devise['abreviation'].'deepay'.$post_array['num_reference']);
		$post_array['id_foreign_status_facture'] = 0;

		return $post_array;
	}


	public function _onRowInserted($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'facture ',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout d'Un Facture ".$post_array["num_facture"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}


	public function _onRowBeforeUpdated($post_array,$primary_key)
	{

		$id_entreprise  = 0;
		$entreprise 	= [];
		$short_code_ent = '';
		$numero_ent 	= '';
		$facture 		= $this->Facture_model->get_facture($primary_key);
		$devise 		= $this->Devise_model->get_devise($post_array['id_foreign_devise']);
		$devise['abreviation'] 	= (!empty($devise)) ? $devise['abreviation'] : 'CDF';
		
		if( (int) $_SESSION['role']['id_role']<>1  ){
			$entreprise = $this->Entreprise_model->get_entreprise( (int) $_SESSION['user']['id_entreprise_utilisateur'] );
		}else{
			$entreprise = $this->Entreprise_model->get_entreprise( $post_array['id_foreign_entreprise'] );
		}

		if(!empty($entreprise)){
			$id_entreprise =  (int) $entreprise['id_entreprise'];
			$short_code_ent = $entreprise['nom_marchand'];
			$numero_ent = $entreprise['telephone_contact'];
		}

		$post_array['qr_code_facture'] = $this->generate_code_qr($numero_ent.'deepay'.$post_array['montant'].'deepay'.$devise['abreviation'].'deepay'.$facture['num_reference']);
		$post_array['id_foreign_status_facture'] = 0;

		return $post_array;
	}


	public function _onRowUpdated($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'facture',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour de la Facture  ".$post_array["num_facture"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data = ['id_facture'=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'facture',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "suppression",
			"text_descriptif" => "suppression de la Facture ".$this->ModelGetTableRow->getObjectFieldValue($data,'facture')->num_facture,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}
	
	
	public function _callback_qr_code($value, $row){
		// $entreprise = $this->Entreprise_model->get_entreprise($row->id_entreprise_cliente);
		// if(!empty($entreprise)){
		// 	return  "<img src='".base_url('assets/uploads/files/').$entreprise['logo']."'  style='witdh auto; height:40px; object-fit: scale-down;'/> <br/>".$entreprise['nom'];
		// }
	}


	public function _callback_entreprise($value, $row){
		$entreprise = $this->Entreprise_model->get_entreprise($row->id_foreign_entreprise);
		if(!empty($entreprise)){
			return  "<img src='".base_url('assets/uploads/files/').$entreprise['logo']."'  style='witdh auto; height:40px; object-fit: scale-down;'/> <br/>".$entreprise['nom'];
		}
	}
	

	public function _callback_status($value, $row){
		if((int) $value == 0)
			return  '<span class="badge badge-danger" style="background-color:red; color:white; font-size: 12px;">Non Payé</span>';
		else
			return '<span class="badge badge-success" style="background-color:green; color:white; font-size: 12px;">Payée</span>';	
	}

	function _status_facture($primary_key , $row){		
		return site_url('Facture/activefacture/'.str_shuffle('abdcuxz2345').'/'.str_shuffle('abdcuxz2345').'/'.$row->id_facture.'/');
	}


	function generate_code_qr($datas){
		// generate code qr
		$qr_code_config = array();
		$qr_code_config['cacheable'] = $this->config->item('cacheable');
		$qr_code_config['cachedir'] = $this->config->item('cachedir');
		$qr_code_config['imagedir'] = $this->config->item('imagedir');
		$qr_code_config['errorlog'] = $this->config->item('errorlog');
		$qr_code_config['ciqrcodelib'] = $this->config->item('ciqrcodelib');
		$qr_code_config['quality'] = $this->config->item('quality');
		$qr_code_config['size'] = $this->config->item('size');
		$qr_code_config['black'] = $this->config->item('black');
		$qr_code_config['white'] = $this->config->item('white');
		$this->ci_qr_code->initialize($qr_code_config);

		$image_name = $datas.".png";

		$codeContents = "";
		$codeContents .= "$datas";

		$params['data'] = $codeContents;
		$params['level'] = 'H';
		$params['size'] = 10;

		$params['savename'] = FCPATH . $qr_code_config['imagedir'] . $image_name;
		$this->ci_qr_code->generate($params);
		return $image_name;

	}


	public function format_numero_facture($num_facture,$short_code)
	{
		$length_num_facture = strlen($num_facture);
		$num_facture_final  = str_repeat('0',10-$length_num_facture).$num_facture;
		return 'INV-'.strtoupper($short_code).'-'.$num_facture_final;
	}


	public function format_numero_reference($num_reference,$short_code)
	{
		$length_num_reference = strlen($num_reference);
		$num_reference_final  = str_repeat('0',10-$length_num_reference).$num_reference;
		return 'REF-'.strtoupper($short_code).'-'.$num_reference_final;
	}



	public function activefacture ($sheme_1=null, $sheme_2=null, $id_facture=null){

		

		if( ($id_facture <> null AND $id_facture<>0 AND is_int( (int) $id_facture))  ){

			$id_facture = (int) $id_facture;
			$facture = $this->Facture_model->get_facture($id_facture);

			

			if(!empty($facture)){

				if ((int) $facture['id_foreign_status_facture'] == 0 ) {
					$facture['id_foreign_status_facture'] = 1;
				}else {
					$facture['id_foreign_status_facture'] = 0;
				}

				$this->Facture_model->update(['id_foreign_status_facture'=>$facture['id_foreign_status_facture']],$facture['id_facture']);

			}
		}

		 redirect('Facture/index/') ;
	}
	
	
	// OLD SECTION 
	
	public function indexold($id_sortie=null)
	{
		
		if(  $this->session->logged_in ){

			if(check_privilege('sortie_stock', $this->session->user['id_role'], 'voir')){
				
				$articles_sorties 	=  $this->Article_sortie_vente_model->get_article_sortie_vente_by_stock_sortie_id($id_sortie);
				$sortie_sotck 		=  $this->Sortie_stock_model->get_sortie_stock($id_sortie);
				$articles 			= [];
				$montant_total 		= 0;
				$reduction 			= 0;

				if( !empty( $sortie_sotck ) ){
					$num_facture 		= $sortie_sotck['id_sortie_stock'];
					$nom_client 		= "";

					if(!empty($sortie_sotck['nom_client'])){
						$nom_client = $sortie_sotck['nom_client'] ;
					}else{
						if(isset( $sortie_sotck['id_client'] ) AND (int) $sortie_sotck['id_client'] >0 ){
							$nom_client =	$this->ModelGetTableRow->getObjectFieldValue(['id_client'=>$sortie_sotck['id_client']],'client')->nom_client;
						}else{
							$nom_client = "CLIENT";
						}
					}
	
					foreach ($articles_sorties as $key => $value) {
						$article = $this->Article_model->get_article($value['id_article']);
						$article['quantite']	=  $value['quantite'];
						$article['prix_vente']	=  $value['prix_vente'];
						$article['montant']  	= $article['quantite'] * $article['prix_vente'];
						$montant_total = $montant_total + $article['montant'];
						array_push($articles,$article);
					}
	
					$reduction = (($montant_total * (int) $sortie_sotck['taux_reduction']) /100);
					$tva = ( $montant_total * 0) / 100;
					$sous_total = $montant_total - $tva;
					$montant_total = $montant_total- $reduction;
					$num_facture =  (strlen($num_facture) < 4) ? str_repeat('0',5-strlen($num_facture)).$num_facture : $num_facture;
					$header = $this->load->view("layouts/header", [], true);
					$sidebar = $this->load->view("layouts/sidebar", [], true);
					$navbar = $this->load->view("layouts/navbar", [], true);
					$footer = $this->load->view("layouts/footer", [], true);
					$this->load->view("facture/facture", ['header' => $header, 'navbar'=>$navbar, 'sidebar' => $sidebar,'footer' => $footer,'sortie_stock'=>$sortie_sotck,'articles'=>$articles,'montant_total'=>$montant_total,'reduction'=>$reduction,'nom_client'=>$nom_client,'num_facture'=>$num_facture,'tva'=>$tva,'sous_total'=>$sous_total]);
				}else{
					redirect($_SERVER['HTTP_REFERER']);
				}
			
			}else{
				redirect($_SERVER['HTTP_REFERER']);
			}
			
		}else {
			redirect("Starter/login");
		}
	
	}


	public function process_add(){
		
		if(  $this->session->logged_in ){

			if(check_privilege('sortie_stock', $this->session->user['id_role'], 'voir')){

				$id_sortie_stock 	= $_POST['id_sortie_stock'];
				$articles_sorties 	=  $this->Article_sortie_vente_model->get_article_sortie_vente_by_stock_sortie_id($id_sortie_stock);
				$sortie_sotck 		=  $this->Sortie_stock_model->get_sortie_stock($id_sortie_stock);
				$articles 			= [];
				$montant_total 		= 0;
				$reduction 			= 0;


				if( !empty( $sortie_sotck ) ){
					$num_facture 		= $sortie_sotck['id_sortie_stock'];
					$nom_client 		= "";

					if(!empty($sortie_sotck['nom_client'])){
						$nom_client = $sortie_sotck['nom_client'] ;
					}else{
						if(isset( $sortie_sotck['id_client'] ) AND (int) $sortie_sotck['id_client'] >0 ){
							$nom_client =	$this->ModelGetTableRow->getObjectFieldValue(['id_client'=>$sortie_sotck['id_client']],'client')->nom_client;
						}else{
							$nom_client = "CLIENT";
						}
					}
	
					foreach ($articles_sorties as $key => $value) {
						$article = $this->Article_model->get_article($value['id_article']);
						$article['quantite']	=  $value['quantite'];
						$article['prix_vente']	=  $value['prix_vente'];
						$article['montant']  	= $article['quantite'] * $article['prix_vente'];
						$montant_total = $montant_total + $article['montant'];
						array_push($articles,$article);
					}

					$reduction = (($montant_total * (int) $sortie_sotck['taux_reduction']) /100);
					$tva = ( $montant_total * 0) / 100;
					$sous_total = $montant_total - $tva;
					$montant_total = $montant_total- $reduction;
					$num_facture =  (strlen($num_facture) < 4) ? str_repeat('0',5-strlen($num_facture)).$num_facture : $num_facture;
					$num_facture = $this->format_num_facture( $num_facture);
					$file_export_name = 'Facture '.$num_facture.' Pour '.($nom_client . ' ' . format_date_fr( $sortie_sotck['date_enregistrement_sortie_stock'] )).'.pdf';
					$tel_client = (!empty( $this->ModelGetTableRow->getObjectFieldValue(['id_client'=>$sortie_sotck['id_client']],'client')->tel_client ))
					? $this->ModelGetTableRow->getObjectFieldValue(['id_client'=>$sortie_sotck['id_client']],'client')->tel_client : 'N/A';
					
					$email_client = (!empty( $this->ModelGetTableRow->getObjectFieldValue(['id_client'=>$sortie_sotck['id_client']],'client')->email_client ))
					? $this->ModelGetTableRow->getObjectFieldValue(['id_client'=>$sortie_sotck['id_client']],'client')->email_client : 'N/A';


					$pdf = new FPDF('P','cm','A4');

					$pdf->AddPage();
					$pdf->SetAutoPageBreak(true,0.5);

					$pdf->Cell(19.3,27.5,'', 1,0);

					$pdf->SetXY(1.5,1.5);

					$pdf->SetFont('Arial','B',18);
					$pdf->Cell(19.3,0.8,'Facture ', 0,1);

					$pdf->SetX(1.5);
					$pdf->Cell(19.3,0.8, '# '.$num_facture , 0,1);

					$pdf->Image(base_url('assets/dore_assets').'/img/logo.jpeg',17.5,1.5,2,2);

					$pdf->SetXY(1.5,4);
					$pdf->SetFont('Arial','',8);

					$pdf->Cell(9,0.5, iconv('UTF-8','windows-1252','Facturé à : '.$nom_client), 0,0,'L');
					$pdf->Cell(9,0.5, 'Date : ' .$sortie_sotck['date_enregistrement_sortie_stock'] , 0,1,'R');

					$pdf->SetX(1.5);
					$pdf->Cell(9,0.5, iconv('UTF-8','windows-1252','Tél : '.$tel_client ), 0,0,'L');
					$pdf->Cell(9,0.5, iconv('UTF-8','windows-1252','Tél : +243 835 669 290') , 0,1,'R');


					$pdf->SetX(1.5);
					$pdf->Cell(9,0.5, 'Email : '.$email_client  , 0,0,'L');
					$pdf->Cell(9,0.5, 'Email : admin@cadeaumart.com' , 0,1,'R');

					$pdf->SetX(1.5);
					$pdf->Cell(9,0.5, iconv('UTF-8','windows-1252','Code Client  : '.$this->format_code_client(  $this->ModelGetTableRow->getObjectFieldValue(['id_client'=>$sortie_sotck['id_client']],'client')->id_client )  ) , 0,0,'L');
					$pdf->Cell(9,0.5, iconv('UTF-8','windows-1252','Adresse : 04, Avenue des musées, Lubumbashi') , 0,1,'R');

					$pdf->SetX(1.5);
					$pdf->Cell(9,0.5, '', 0,0,'L');
					$pdf->Cell(9,0.5, 'Compte EQUITY : 00017-25000-00373600001-21 USD' , 0,0,'R');

					$pdf->Ln(2.5);

					$pdf->SetFont('Arial','B',8);
					$pdf->SetTextColor(28,86,226);
					$pdf->SetLineWidth(0.04);

					$pdf->SetX(1);
					$pdf->Cell(1.3,0.6, 'No' , 'TB',0,'C');
					$pdf->Cell(7,0.6, 'PRODUIT' , 'TB',0,'C');
					$pdf->Cell(2,0.6, 'QTE' , 'TB',0,'C');
					$pdf->Cell(4.5,0.6, 'PRIX UNITAIRE' , 'TB',0,'C');
					$pdf->Cell(4.5,0.6, 'TOTAL' , 'TB',1,'C');

					$pdf->SetFont('Arial','',8);
					$pdf->SetTextColor(0,0,0);
					$pdf->SetLineWidth(0.01);

					$counter =0;
					foreach ($articles as $key => $value) {
						$counter ++;
						$pdf->SetX(1);
						$pdf->Cell(1.3,0.6, $counter , 'B',0,'C');
						$pdf->Cell(7,0.6, $value['nom_article'] , 'B',0,'C');
						$pdf->Cell(2,0.6, $value['quantite'] , 'B',0,'C');
						$pdf->Cell(4.5,0.6, number_format($value['prix_vente'],0,',',' ').' USD' , 'B',0,'C');
						$pdf->Cell(4.5,0.6, number_format($value['montant'],0,',',' ').' USD' , 'B',1,'C');
					}

					
					
					$pdf->Ln(1);


					$pdf->SetFont('Arial','',10);
					$pdf->SetX(1);
					$pdf->Cell(9,0.8, '' , '',0,'C');
					$pdf->Cell(5,0.8, 'Sous Total : ' , '',0,'R');
					$pdf->Cell(5,0.8,  number_format($sous_total,0,',',' ').' USD' , '',1,'R');

					$pdf->SetX(1);
					$pdf->Cell(9,0.8, '' , '',0,'C');
					$pdf->Cell(5,0.8, 'Tva (0%): ' , '',0,'R');
					$pdf->Cell(5,0.8,  number_format(0,0,',',' ').' USD' , '',1,'R');

					$pdf->SetFont('Arial','B',14);

					$pdf->SetX(1);
					$pdf->Cell(9,0.8, '' , '',0,'C');
					$pdf->Cell(5,0.8, 'Total : ' , '',0,'R');
					$pdf->Cell(5,0.8,  number_format($montant_total,0,',',' ').' USD' , '',1,'R');

					$pdf->Output('D',$file_export_name);

				}else{
					redirect($_SERVER['HTTP_REFERER']);
				}

			}else {
				redirect($_SERVER['HTTP_REFERER']);
			}

		}else {
			redirect("Starter/login");
		}

	}

	public function format_num_facture($num_facture)
	{
		$length_num_facture = strlen($num_facture);
		$num_facture_final  = str_repeat('0',6-$length_num_facture).$num_facture;
		return 'INV'.$num_facture_final;
	}


	public function format_code_client($num_facture)
	{
		$length_num_facture = strlen($num_facture);
		$num_facture_final  = str_repeat('0',6-$length_num_facture).$num_facture;
		return $num_facture_final;
	}

	// END OLD SECTION

}
