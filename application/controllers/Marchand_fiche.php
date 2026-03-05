<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require( APPPATH.'libraries/makefont/makefont.php');

class Marchand_fiche extends CI_Controller {

	public function index( $reset = null)
	{
		if(  $this->session->logged_in ){
			if(check_privilege('marchand_fiche', $this->session->user['id_role'], 'voir')){

				$crud = new grocery_CRUD();
				$marchand_fiche_link_active	= 'link_menu_active';
				

				if( isset( $_POST['start_date'] )  AND isset($_POST['end_date'])){
					$sql = "date_signature BETWEEN'".$_POST['start_date']."' AND '".$_POST['end_date']."'";
					$crud->where($sql);
				}

				$crud->set_table('marchand_fiche');
				$crud->columns('id_marchand_fiche','nom_activite','domaine_activite','adresse_activite','tel_proprietaire','num_mobile_money_activite','is_pris_en_charge','date_signature');

				$crud->display_as('id_marchand_fiche','#');
				$crud->display_as('nom_activite','Nom Activité');
				$crud->display_as('domaine_activite','Domaine');
				$crud->display_as('tel_proprietaire','Tél Propriétaire ');
				$crud->display_as('num_mobile_money_activite','Num mobile money ');
				$crud->display_as('email','Adresse mail du marchand');
				$crud->display_as('adresse_activite','Adresse');
				$crud->display_as('rccm_activite','RCCM ');
				$crud->display_as('nif_activite','NIF');
				$crud->display_as('id_foregin_sexe','Sexe');
				$crud->display_as('nom_proprietaire','Nom Propriétaire');
				$crud->display_as('adresse_proprietaire','Adresse propriétaire');
				$crud->display_as('date_naissance_proprietaire','Date naissance propriétaire');
				$crud->display_as('nom_gestionnaire','Nom du gestionnaire');
				$crud->display_as('tel_gestionnaire','Tel du gestionnaire');
				$crud->display_as('tel_gestionnaire','Tel du gestionnaire');
				$crud->display_as('is_pris_en_charge','Est pris en charge ?');
				$crud->display_as('date_signature',' Date d\'enregistrement ');

				$crud->set_subject('Une fiche de marchand');

				$crud->edit_fields( array(
					'nom_activite',
					'domaine_activite',
					'rccm_activite',
					'nif_activite',
					'adresse_activite',
					'id_foregin_sexe',
					'nom_proprietaire',
					'tel_proprietaire',
					'adresse_proprietaire',
					'date_naissance_proprietaire',
					'nom_gestionnaire',
					'tel_gestionnaire',
					'date_signature'
				) );
	
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));

				$crud->set_relation('id_foregin_sexe','sexe','designation');
				$crud->callback_column('is_pris_en_charge',array($this,'_is_pris_en_charge'));

	
				//$crud->required_fields('tel_proprietaire','nom_activite');
				//$crud->unique_fields(array('email','domaine_activite'));
				//$crud->field_type('is_systeme', 'hidden',2);
				//$crud->field_type('date_signature', 'hidden', date('Y-m-d'));
				$crud->unset_clone();
				$crud->unset_add();
				$crud->unset_read();
				$crud->set_field_upload('logo','assets/uploads/files');
			
				$this->stateDisplay($crud);


				if( ! check_privilege('marchand_fiche', $this->session->user['id_role'], 'editer') ){
					$crud->unset_edit();
				}

				if( check_privilege('marchand_fiche', $this->session->user['id_role'], 'voir') ){
					$crud->add_action('FICHE RIPA', '', '','book',array($this,'link_print_fiche'));
				}

				if( check_privilege('marchand_fiche', $this->session->user['id_role'], 'voir') ){
					$crud->add_action('FICHE FLEXPAY', '', '','book',array($this,'link_print_fiche_flexpay'));
				}

				if( ! check_privilege('marchand_fiche', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}
	
				$crud->order_by('id_marchand_fiche','desc');

				$output = $crud->render();
	
				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['marchand_fiche_link_active'=>$marchand_fiche_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("marchand_fiche/marchand_fiche", ['header' => $header, 'navbar'=>$navbar, 'sidebar' => $sidebar, 'footer' => $footer,'output'=>$output->output]);	

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
				$crud->set_subject("Une fiche de marchand ");
				break;
			case 'list':
				$crud->set_subject("Une fiche de marchand ");
				break;
			case 'add':
				$crud->set_subject("D'Une fiche de marchand ");
				break;
			
			case 'edit':
				$crud->set_subject("D'Une fiche de marchand ");
				break;
			
			default:
				break;
		}

	}

	public function _showImage($value,$row)
	{
		return "<img src='".base_url('assets/uploads/files/'.$value)."'  height='100px' style='width: auto; object-fit: content;'/>";
	}


	public function _is_pris_en_charge($value,$row)
	{
		if ($row->is_pris_en_charge == 0) {
			return '<a href="'.site_url('Marchand_fiche/update_is_pris_en_charge/'.$row->id_marchand_fiche.'/').'"><span class="badge red text-white"  style="font-weight: 300; font-size: 0.8rem; border-radius: 2px;"> NON</span></a>';
		}else {
			return '<a href="'.site_url('Marchand_fiche/update_is_pris_en_charge/'.$row->id_marchand_fiche.'/').'"><span class="badge green text-white"  style="font-weight: 300; font-size: 0.8rem; border-radius: 2px;"> OUI </span><a/>';
		}	
	}


	public function link_print_fiche($primary_key , $row)
	{
		return site_url('Marchand_fiche/print_fiche/'.$primary_key.'/');
	}

	public function link_print_fiche_flexpay($primary_key , $row)
	{
		return site_url('Marchand_fiche/print_fiche_flexpay/'.$primary_key.'/');
	}


	function update_is_pris_en_charge($id_marchand_fiche){
		$marchand_fiche = $this->Marchand_model->get_marchand_fiche($id_marchand_fiche);
		if(!empty($marchand_fiche)){
			if ($marchand_fiche['is_pris_en_charge'] == 0) {
				$this->Marchand_model->update($marchand_fiche['id_marchand_fiche'],['is_pris_en_charge'=>1]);
			}else {
				$this->Marchand_model->update($marchand_fiche['id_marchand_fiche'],['is_pris_en_charge'=>0]);
			}
			redirect("Marchand_fiche/index/");
		}else {
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	public function _onRowInserted($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"nom_table"=> 'marchand_fiche',
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout de la fiche  Marchand ".$post_array["nom_activite"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowUpdated($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"nom_table"=> 'marchand_fiche',
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour de la fiche  Marchand ".$post_array["nom_activite"],
			"date_heure" => date("Y-m-d H:m:i")
		];

    
		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data = ['id_marchand_fiche'=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"nom_table"=> 'marchand_fiche',
			"id_champ"=> $primary_key,
			"action" => "suppression",
			"text_descriptif" => "suppression de la fiche du Marchand ".$this->ModelGetTableRow->getObjectFieldValue($data,'marchand_fiche')->nom_activite,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}


	public function print_fiche($id_marchand_fiche){

		if(check_privilege('marchand_fiche', $this->session->user['id_role'], 'voir')){
			if(!empty($id_marchand_fiche)){
				$marchand_fiche =  $this->Marchand_model->get_marchand_fiche($id_marchand_fiche);
				if(!empty($marchand_fiche)){
					$sexe = $this->Sexe_model->get_sexes($marchand_fiche['id_foregin_sexe']);
					$designation_sexe = ($sexe['id_sexe'] == 1) ? "Mr" : "Mme";
					$file_export_name = "Formulaire RIPA pour le marchand ".$marchand_fiche['nom_activite']." ".$marchand_fiche['num_mobile_money_activite'].".pdf";
					$font_signature = str_replace('class_font_','',$marchand_fiche['nom_class_police']);
					$font_signature_size = ($font_signature =='rockybilly') ? 13 : 45;
					//$font_files = str_replace('application','assets',APPPATH);
					//$font_files .= 'dore_assets/css/fonts/'.$font_signature.'/'.$font_signature.'.ttf';
					//MakeFont($font_files,'cp1252');
					

					$pdf = new Fpdfroundedrecalpha('P','mm','A4');
					$pdf->AddFont($font_signature,'',$font_signature.'.php');
					$pdf->AddPage();
					$pdf->SetAutoPageBreak(true,1);

					$pdf->Cell(193,275,'', 1,0);

					$pdf->SetXY(23,17);

					$pdf->SetFont('Arial','B','12');
					$pdf->Cell(170,6,'FORMULAIRE D\'ENREGISTREMENT MARCHAND ', 0,1,'C');
					$pdf->Image(base_url('assets/dore_assets/img/logoapp.png'), 160, 15, 40,13);


					$pdf->SetAlpha(0.4);
					$pdf->Image(base_url('assets/dore_assets/img/logoapp.png'), 23, 120, 170,55);
					$pdf->SetAlpha(1);

					$pdf->Ln(7);
					$pdf->SetX(23);
					$pdf->SetFillColor(16,50,193);
					$pdf->SetDrawColor(255,255,255);
					$pdf->SetTextColor(255,255,255);
					$pdf->Cell(170,6,'INFORMATIONS SUR L\'ACTIVITE ', 1,1,'C',true);

					$pdf->SetDrawColor(0,0,0);
					$pdf->SetTextColor(0,0,0);

					$pdf->Ln(6);
					$pdf->SetX(23);
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','Nom de l\'activité : '), 0,0,'L');
					$pdf->Cell(100,6,iconv('UTF-8','windows-1252',' '.$marchand_fiche['nom_activite']), 1,1,'L');

					$pdf->Ln(6);
					$pdf->SetX(23);
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','Domaine de l\'activité : '), 0,0,'L');
					$pdf->Cell(100,6,iconv('UTF-8','windows-1252',' '.$marchand_fiche['domaine_activite']), 1,1,'L');

					$pdf->Ln(6);
					$pdf->SetX(23);
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','RCCM : '), 0,0,'L');
					$pdf->Cell(100,6,iconv('UTF-8','windows-1252',' '.$marchand_fiche['rccm_activite']), 1,1,'L');

					$pdf->Ln(6);
					$pdf->SetX(23);
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','NIF : '), 0,0,'L');
					$pdf->Cell(100,6,iconv('UTF-8','windows-1252',' '.$marchand_fiche['nif_activite']), 1,1,'L');

					$pdf->Ln(6);
					$pdf->SetX(23);
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','ID NAT : '), 0,0,'L');
					$pdf->Cell(100,6,iconv('UTF-8','windows-1252',' '.$marchand_fiche['id_nat_activite']), 1,1,'L');

					$pdf->Ln(6);
					$pdf->SetX(23);
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','Adresse physique : '), 0,0,'L');
					$pdf->Cell(100,6,iconv('UTF-8','windows-1252',' '.$marchand_fiche['adresse_activite']), 1,1,'L');

					$pdf->Ln(6);
					$pdf->SetX(23);
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','Numéro Mobile money : '), 0,0,'L');
					$pdf->Cell(100,6,iconv('UTF-8','windows-1252',' '.$marchand_fiche['num_mobile_money_activite']), 1,1,'L');


					$pdf->Ln(7);
					$pdf->SetX(23);
					$pdf->SetFillColor(16,50,193);
					$pdf->SetDrawColor(255,255,255);
					$pdf->SetTextColor(255,255,255);
					$pdf->Cell(170,6,'INFORMATIONS DU PROPRIETAIRE ', 1,1,'C',true);


					$pdf->SetDrawColor(0,0,0);
					$pdf->SetTextColor(0,0,0);

					$pdf->Ln(6);
					$pdf->SetX(23);
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','Nom complet '.$designation_sexe.' : '), 0,0,'L');
					$pdf->Cell(100,6,iconv('UTF-8','windows-1252',' '.$marchand_fiche['nom_proprietaire']), 1,1,'L');

					$pdf->Ln(6);
					$pdf->SetX(23);
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','Numéro de téléphone : '), 0,0,'L');
					$pdf->Cell(100,6,iconv('UTF-8','windows-1252',' '.$marchand_fiche['tel_proprietaire']), 1,1,'L');

					$pdf->Ln(6);
					$pdf->SetX(23);
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','Adresse physique : '), 0,0,'L');
					$pdf->Cell(100,6,iconv('UTF-8','windows-1252',' '.$marchand_fiche['adresse_proprietaire']), 1,1,'L');

					$pdf->Ln(6);
					$pdf->SetX(23);
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','Nationalité : '), 0,0,'L');
					$pdf->Cell(100,6,iconv('UTF-8','windows-1252',' '.$marchand_fiche['nationalite_proprietaire']), 1,1,'L');

					$pdf->Ln(6);
					$pdf->SetX(23);
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','Date et lieu de naissance : '), 0,0,'L');
					$pdf->Cell(100,6,iconv('UTF-8','windows-1252',' '.$marchand_fiche['date_naissance_proprietaire'].', '.$marchand_fiche['lieu_naissance_proprietaire']), 1,1,'L');

					$pdf->Ln(6);
					$pdf->SetX(23);
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','Nom du gestionnaire : '), 0,0,'L');
					$pdf->Cell(100,6,iconv('UTF-8','windows-1252',' '.$marchand_fiche['nom_gestionnaire']), 1,1,'L');

					$pdf->Ln(6);
					$pdf->SetX(23);
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','Numéro du gestionnaire : '), 0,0,'L');
					$pdf->Cell(100,6,iconv('UTF-8','windows-1252',' '.$marchand_fiche['tel_gestionnaire']), 1,1,'L');

					$pdf->SetFont('Arial','B','10');
					$pdf->Ln(6);
					$pdf->SetX(23);
					$pdf->MultiCell(170,4,iconv('UTF-8','windows-1252','Par la signature avec mon nom du présent formulaire, Je reconnais avoir pris connaissance et accepter le contenu de ce formulaire RIPA ainsi que les termes & conditions générales d\'utilisation. Je garantie en outre la sincérité des déclarations faites sur ce formulaire et m\'engage à informer RIPA de toute modification utérieure.'), 0,'J');

					$pdf->SetFont('Arial','','12');
					$pdf->Ln(6);
					$pdf->SetX(23);
					$pdf->Cell(50,24,iconv('UTF-8','windows-1252','Signature du marchand : '), 0,0,'C');
					
					$pdf->SetFont($font_signature,'',$font_signature_size); 
					$pdf->Cell(70,24,strtolower($marchand_fiche['nom_proprietaire']), 1,0,'C');

					$pdf->SetFont('Arial','','12');
					$pdf->Cell(20,24,'Date :', 0,0,'C');
					$pdf->Cell(30,24,$marchand_fiche['date_signature'], 0,1,'C');

					$pdf->Output();
					//$pdf->Output('D',$file_export_name);

				}else{
					redirect($_SERVER['HTTP_REFERER']);
				}
			}else{
				redirect($_SERVER['HTTP_REFERER']);
			}
		}else{
			redirect($_SERVER['HTTP_REFERER']);
		}

	}
	
	public function print_fiche_flexpay($id_marchand_fiche){

		if(check_privilege('marchand_fiche', $this->session->user['id_role'], 'voir')){
			if(!empty($id_marchand_fiche)){
				$marchand_fiche =  $this->Marchand_model->get_marchand_fiche($id_marchand_fiche);
				if(!empty($marchand_fiche)){
					$sexe = $this->Sexe_model->get_sexes($marchand_fiche['id_foregin_sexe']);
					$designation_sexe = ($sexe['id_sexe'] == 1) ? "Homme" : "Femme";
					$file_export_name = "Formulaire RIPA pour le marchand ".$marchand_fiche['nom_activite']." ".$marchand_fiche['num_mobile_money_activite'].".pdf";
					$font_signature = str_replace('class_font_','',$marchand_fiche['nom_class_police']);
					$font_signature_size = ($font_signature =='rockybilly') ? 13 : 60;
					//$font_files = str_replace('application','assets',APPPATH);
					//$font_files .= 'dore_assets/css/fonts/'.$font_signature.'/'.$font_signature.'.ttf';
					//MakeFont($font_files,'cp1252');
					

					$pdf = new Fpdfroundedrecalpha('P','mm','A4');
					$pdf->AddFont($font_signature,'',$font_signature.'.php');
					$pdf->AddPage();
					$pdf->SetAutoPageBreak(true,1);

					$pdf->Image(base_url('assets/dore_assets/img/flexpay.png'), 13, 8, 18,10);
					
					$pdf->SetAlpha(0.4);
					$pdf->Image(base_url('assets/dore_assets/img/flexpay.png'), 15, 90, 180,100);

					$pdf->SetAlpha(1);
					$pdf->SetXY(60,16);
					$pdf->SetFont('Arial','B','18');
					$pdf->MultiCell(90,7,iconv('UTF-8','windows-1252','Formulaire d\'enregistrement Marchand'), 0,'C');
					
					// Information sur l'activité
					$pdf->SetDrawColor(44,130,205);
					$pdf->RoundedRect(15, 32, 180, 85, 10, '1234', 'D');
					$pdf->SetXY(20,37);
					$pdf->SetDrawColor(0,0,0);

					$pdf->SetFont('Arial','B','14');
					$pdf->Cell(90,6,iconv('UTF-8','windows-1252','Information sur l\'activité'), 0,0,'L');

					$pdf->SetFont('Arial','','14');
					$pdf->Cell(80,6,iconv('UTF-8','windows-1252','Code marchand choisi .................'), 0,1,'L');

					$pdf->Ln(5);
					$pdf->SetX(20);
					$pdf->Cell(100,6,iconv('UTF-8','windows-1252','Nom de l\'activité '), 0,0,'L');
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','Domaine de l\'activité '), 0,1,'L');

					$pdf->SetX(20);
					$pdf->SetDrawColor(230,230,230);
					$pdf->SetFont('Arial','','12');
					$pdf->Cell(90,8,iconv('UTF-8','windows-1252',$marchand_fiche['nom_activite']), 1,0,'L');
					$pdf->Cell(10,8,iconv('UTF-8','windows-1252',''), 0,0,'L');
					$pdf->Cell(70,8,iconv('UTF-8','windows-1252',$marchand_fiche['domaine_activite']), 1,1,'L');

					$pdf->Ln(3);
					$pdf->SetX(20);
					$pdf->SetFont('Arial','','14');
					$pdf->Cell(50,6,iconv('UTF-8','windows-1252','RCCM '), 0,0,'L');
					$pdf->Cell(50,6,iconv('UTF-8','windows-1252','NIF '), 0,0);
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','ID NAT '), 0,1,'L');

					$pdf->SetX(20);
					$pdf->SetDrawColor(230,230,230);
					$pdf->SetFont('Arial','','12');
					$pdf->Cell(46,8,iconv('UTF-8','windows-1252',$marchand_fiche['rccm_activite']), 1,0,'L');
					$pdf->Cell(5,8,iconv('UTF-8','windows-1252',''), 0,0);
					$pdf->Cell(46,8,iconv('UTF-8','windows-1252',$marchand_fiche['nif_activite']), 1,0,'L');
					$pdf->Cell(4,8,iconv('UTF-8','windows-1252',''), 0,0);
					$pdf->Cell(69,8,iconv('UTF-8','windows-1252',$marchand_fiche['id_nat_activite']), 1,1,'L');

					$pdf->Ln(3);
					$pdf->SetX(20);
					$pdf->SetFont('Arial','','14');
					$pdf->Cell(170,6,iconv('UTF-8','windows-1252','Adresse '), 0,1,'L');

					$pdf->SetX(20);
					$pdf->SetDrawColor(230,230,230);
					$pdf->SetFont('Arial','','12');
					$pdf->Cell(170,8,iconv('UTF-8','windows-1252',$marchand_fiche['adresse_activite']), 1,1,'L');

					$pdf->Ln(3);
					$pdf->SetX(20);
					$pdf->SetFont('Arial','','14');
					$pdf->Cell(100,6,iconv('UTF-8','windows-1252','Numéro de compte mobile money '), 0,0,'L');
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','Adresse e-mail '), 0,1,'L');

					$pdf->SetX(20);
					$pdf->SetDrawColor(230,230,230);
					$pdf->SetFont('Arial','','12');
					$pdf->Cell(90,8,iconv('UTF-8','windows-1252',$marchand_fiche['num_mobile_money_activite']), 1,0,'L');
					$pdf->Cell(10,8,iconv('UTF-8','windows-1252',''), 0,0,'L');
					$pdf->Cell(70,8,iconv('UTF-8','windows-1252',''), 1,1,'L');

					// Information du propriétaire 
					$pdf->SetDrawColor(44,130,205);
					$pdf->RoundedRect(15, 120, 180, 89, 10, '1234', 'D');
					$pdf->SetDrawColor(0,0,0);
					$pdf->SetXY(20,124);

					$pdf->SetFont('Arial','B','14');
					$pdf->Cell(90,6,iconv('UTF-8','windows-1252','Information du propriétaire '), 0,0,'L');

					$pdf->SetFont('Arial','','14');
					$pdf->Cell(80,6,iconv('UTF-8','windows-1252',$designation_sexe), 0,1,'L');

					$pdf->Ln(5);
					$pdf->SetX(20);
					$pdf->Cell(100,6,iconv('UTF-8','windows-1252','Nom du propriétaire'), 0,0,'L');
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','Téléphone '), 0,1,'L');

					$pdf->SetX(20);
					$pdf->SetDrawColor(230,230,230);
					$pdf->SetFont('Arial','','12');
					$pdf->Cell(90,8,iconv('UTF-8','windows-1252',$marchand_fiche['nom_proprietaire']), 1,0,'L');
					$pdf->Cell(10,8,iconv('UTF-8','windows-1252',''), 0,0,'L');
					$pdf->Cell(70,8,iconv('UTF-8','windows-1252',$marchand_fiche['tel_proprietaire']), 1,1,'L');

					$pdf->Ln(3);
					$pdf->SetX(20);
					$pdf->SetFont('Arial','','14');
					$pdf->Cell(170,6,iconv('UTF-8','windows-1252','Adresse '), 0,1,'L');

					$pdf->SetX(20);
					$pdf->SetDrawColor(230,230,230);
					$pdf->SetFont('Arial','','12');
					$pdf->Cell(170,8,iconv('UTF-8','windows-1252',$marchand_fiche['adresse_proprietaire']), 1,1,'L');

					$pdf->Ln(5);
					$pdf->SetX(20);
					$pdf->Cell(100,6,iconv('UTF-8','windows-1252','Date et lieu de naissance'), 0,0,'L');
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','Nationalité '), 0,1,'L');

					$pdf->SetX(20);
					$pdf->SetDrawColor(230,230,230);
					$pdf->SetFont('Arial','','12');
					$pdf->Cell(90,8,iconv('UTF-8','windows-1252',$marchand_fiche['date_naissance_proprietaire'].', '.$marchand_fiche['lieu_naissance_proprietaire']), 1,0,'L');
					$pdf->Cell(10,8,iconv('UTF-8','windows-1252',''), 0,0,'L');
					$pdf->Cell(70,8,iconv('UTF-8','windows-1252',$marchand_fiche['nationalite_proprietaire']), 1,1,'L');


					$pdf->Ln(5);
					$pdf->SetX(20);
					$pdf->Cell(100,6,iconv('UTF-8','windows-1252','Nom du gestionnaire'), 0,0,'L');
					$pdf->Cell(70,6,iconv('UTF-8','windows-1252','Téléphone '), 0,1,'L');

					$pdf->SetX(20);
					$pdf->SetDrawColor(230,230,230);
					$pdf->SetFont('Arial','','12');
					$pdf->Cell(90,8,iconv('UTF-8','windows-1252',$marchand_fiche['nom_gestionnaire']), 1,0,'L');
					$pdf->Cell(10,8,iconv('UTF-8','windows-1252',''), 0,0,'L');
					$pdf->Cell(70,8,iconv('UTF-8','windows-1252',$marchand_fiche['tel_gestionnaire']), 1,1,'L');

					$pdf->Ln(6);
					$pdf->SetX(20);
					$pdf->SetFont('Arial','B','12');
					$pdf->SetDrawColor(44,130,205);
					$pdf->Cell(56,18,iconv('UTF-8','windows-1252',''), 'RB',0,'L');
					$pdf->Cell(56,18,iconv('UTF-8','windows-1252',''), 'RB',0,'L');
					$pdf->Cell(56,18,iconv('UTF-8','windows-1252',''), 'B',1,'L');

					$pdf->SetDrawColor(0,0,0);
					$pdf->SetXY(25,213);
					$pdf->SetFont('Arial','BU','11');
					$pdf->Cell(60,4,iconv('UTF-8','windows-1252','APIs sollicité'), 0,0,'L');
					$pdf->Cell(5,4,'', 1,0,'L');
					$pdf->Cell(56,4,'Portail VPOS', 0,0,'L');
					$pdf->Cell(56,4,'Dotation TPE (POS)', 0,1,'L');

					$pdf->Ln(5);
					$pdf->SetX(17);
					$pdf->SetFont('Arial','','8');

					$pdf->Cell(5,4,'', 1,0,'L');
					$pdf->Cell(28,4,'Carte Bancaire', 0,0,'L');

					$pdf->Cell(5,4,'', 1,0,'L');
					$pdf->Cell(22,4,'Mobile Money', 0,0,'L');

					$pdf->Cell(5,4,'', 1,0,'L');
					$pdf->Cell(33,4,'Simple', 0,0,'L');

					$pdf->Cell(5,4,'', 1,0,'L');
					$pdf->Cell(13,4,iconv('UTF-8','windows-1252','Avancé'), 0,0,'L');


					$pdf->Cell(5,4,'', 1,0,'L');
					$pdf->Cell(38,4,'TPE+Internet mensuel', 0,0,'L');

					$pdf->Cell(5,4,'', 1,0,'L');
					$pdf->Cell(13,4,'TPE simple', 0,0,'L');



					$pdf->SetXY(20,230);
					$pdf->SetFont('Arial','','10');
					$pdf->MultiCell(170,4,iconv('UTF-8','windows-1252','Par la signature avec mon nom du présent formulaire, Je reconnais avoir pris connaissance et accepter le contenu de l\'offre RIPA ainsi que les conditions générales d\'utilisation. Je garantie en outre la sincérité des déclarations faites sur ce formulaire et m\'engage à informer RIPA de toute modification utérieure.'), 0,'J');

					$pdf->Ln(6);
					$pdf->SetX(25);
					$pdf->Cell(50,5,iconv('UTF-8','windows-1252','Signature du marchand : '), 0,0,'C');
					
					$pdf->SetFont($font_signature,'',$font_signature_size); 
					$pdf->Cell(60,2,strtolower($marchand_fiche['nom_proprietaire']), 0,0,'C');

					$pdf->SetFont('Arial','','10');
					$pdf->Cell(30,5,'Date :', 0,0,'C');
					$pdf->Cell(30,5,$marchand_fiche['date_signature'], 0,1,'C');

					$pdf->SetDrawColor(44,130,205);
					$pdf->RoundedRect(30, 260, 160, 30, 0, '1234', 'D');
					$pdf->SetFont('Arial','IU','8');
					$pdf->SetXY(33,263);
					$pdf->Cell(100,5,iconv('UTF-8','windows-1252','Réservé à l\'administration'), 0,1,'L');

					$pdf->Ln(2);
					$pdf->SetFont('Arial','I','10');
					$pdf->SetX(33);
					$pdf->Cell(100,5,iconv('UTF-8','windows-1252','Délégué Commercial ................................................. signature'), 0,1,'L');

					$pdf->SetX(33);
					$pdf->Cell(100,5,iconv('UTF-8','windows-1252','Equipe commercial .................................................. Aproval'), 0,1,'L');

					$pdf->Ln(2);
					$pdf->SetX(33);
					$pdf->SetFont('Arial','BI','8');
					$pdf->SetDrawColor(0,0,0);
					$pdf->Cell(40,5,iconv('UTF-8','windows-1252','Catégorie du marchand'), 0,0,'L');
					$pdf->Cell(14,5,iconv('UTF-8','windows-1252','Platinum'), 0,0,'L');
					$pdf->Cell(5,5,'', 1,0,'L');

					$pdf->Cell(5,5,'', 0,0,'L');
					$pdf->Cell(8,5,iconv('UTF-8','windows-1252','Gold'), 0,0,'L');
					$pdf->Cell(5,5,'', 1,0,'L');

					$pdf->Cell(5,5,'', 0,0,'L');
					$pdf->Cell(10,5,iconv('UTF-8','windows-1252','Silver'), 0,0,'L');
					$pdf->Cell(5,5,'', 1,0,'L');

					$pdf->Cell(15,5,'', 0,0,'L');
					$pdf->Cell(12,5,iconv('UTF-8','windows-1252','Bronze'), 0,0,'L');
					$pdf->Cell(5,5,'', 1,0,'L');


					$pdf->Output();

				}else{
					redirect($_SERVER['HTTP_REFERER']);
				}
			}else{
				redirect($_SERVER['HTTP_REFERER']);
			}
		}else{
			redirect($_SERVER['HTTP_REFERER']);
		}

	}
	



}
