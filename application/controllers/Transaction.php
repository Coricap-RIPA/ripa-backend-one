<?php


defined('BASEPATH') OR exit('No direct script access allowed');


require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;



class Transaction extends CI_Controller {
	
	public function recu($id_trx=null,$code_message_trx=null,$transID=null,$transRef=null)
	{
		if(  $this->session->logged_in ){
			if(check_privilege('transaction', $this->session->user['id_role'], 'voir')){


				$crud = new grocery_CRUD();
				$transaction_recu_link_active	= 'link_menu_active';
				$entreprises = $this->Entreprise_model->get_all_entreprises();
				$arr_status_executions = [
					['id_foreign_statut_execution'=>0,'designation'=>'Non Exécutée'],
					['id_foreign_statut_execution'=>1,'designation'=>'Exécutée']
				]; 
				$arr_status_transactions = [
					['id_foreign_statut_transaction'=>0,'designation'=>'En cours'],
					['id_foreign_statut_transaction'=>1,'designation'=>'Réussie']
				]; 
				$sql_string = '';

				if( (int) $_SESSION['role']['id_role']<>1){
					$sql_string.= 'id_foreign_entreprise='. $_SESSION['user']['id_entreprise_utilisateur'];
				}

				if( isset( $_POST['start_date'] )  AND isset($_POST['end_date'])){
					$sql = "date_transaction BETWEEN'".$_POST['start_date']."' AND '".$_POST['end_date']."'";
					$crud->where($sql);

					if( strlen($sql_string) > 3){
						$sql_string.= " AND date_transaction BETWEEN'".$_POST['start_date']."' AND '".$_POST['end_date']."'";
					}else{
						$sql_string = "date_transaction BETWEEN'".$_POST['start_date']."' AND '".$_POST['end_date']."'";;
					}
				}else {
					if ($id_trx==null) {
						$sql = "date_transaction BETWEEN'".date('Y-m-d')."' AND '".date('Y-m-d')."'";
						$crud->where($sql);
	
						if( strlen($sql_string) > 3){
							$sql_string.= " AND date_transaction BETWEEN'".date('Y-m-d')."' AND '".date('Y-m-d')."'";
						}else{
							$sql_string = "date_transaction BETWEEN'".date('Y-m-d')."' AND '".date('Y-m-d')."'";;
						}
					}
				}

				if( isset( $_POST['id_foreign_entreprise'] )  AND ($_POST['id_foreign_entreprise']) <> 'null'){
					if( strlen($sql_string) > 3){
						$sql_string.= " AND id_foreign_entreprise=".$_POST['id_foreign_entreprise'] ;
					}else{
						$sql_string = "id_foreign_entreprise=".$_POST['id_foreign_entreprise'];
					}
				}

				if( isset( $_POST['id_foreign_statut_execution'] )  AND $_POST['id_foreign_statut_execution'] <> 'null'){
					if( strlen($sql_string) > 3){
						$sql_string.= " AND id_foreign_statut_execution=".$_POST['id_foreign_statut_execution'] ;
					}else{
						$sql_string = "id_foreign_statut_execution=".$_POST['id_foreign_statut_execution'];
					}
				}

				if( isset( $_POST['id_foreign_statut_transaction'] )  AND ($_POST['id_foreign_statut_transaction']) <> 'null'){
					if( strlen($sql_string) > 3){
						$sql_string.= " AND id_foreign_statut_transaction=".$_POST['id_foreign_statut_transaction'] ;
					}else{
						$sql_string = "id_foreign_statut_transaction=".$_POST['id_foreign_statut_transaction'];
					}
				}

				if($id_trx<>null){
					$transaction = $this->Transaction_model->get_transaction($id_trx);
					if (!empty($transaction)) {
						$crud->where(['id_transaction'=>$id_trx]);
					}
				}


				if( strlen($sql_string) > 3){
					$sql_string.= " AND transaction_type=1" ;
				}else{
					$sql_string = "transaction_type=1";
				}

				$total_transcation_cdf = $this->Transaction_model->get_sum_montant_all_transaction_by_sql_id_devise($sql_string,1);
				$total_transcation_usd = $this->Transaction_model->get_sum_montant_all_transaction_by_sql_id_devise($sql_string,2);
				$total_commission_ripa_cdf = $this->Transaction_model->get_sum_montant_all_ripa_commission_transaction_by_sql_id_devise($sql_string,1);
				$total_commission_ripa_usd = $this->Transaction_model->get_sum_montant_all_ripa_commission_transaction_by_sql_id_devise($sql_string,2);
				$total_commission_network_cdf = $this->Transaction_model->get_sum_montant_all_network_commission_transaction_by_sql_id_devise($sql_string,1);
				$total_commission_network_usd = $this->Transaction_model->get_sum_montant_all_network_commission_transaction_by_sql_id_devise($sql_string,2);
				
				$total_transcation_cdf = (!empty($total_transcation_cdf)) ? $total_transcation_cdf : 0;
				$total_transcation_usd = (!empty($total_transcation_usd)) ? $total_transcation_usd : 0;
				$total_commission_ripa_cdf = (!empty($total_commission_ripa_cdf)) ? $total_commission_ripa_cdf : 0;
				$total_commission_ripa_usd = (!empty($total_commission_ripa_usd)) ? $total_commission_ripa_usd : 0;
				$total_commission_network_cdf = (!empty($total_commission_network_cdf)) ? $total_commission_network_cdf : 0;
				$total_commission_network_usd = (!empty($total_commission_network_usd)) ? $total_commission_network_usd : 0;
				
			
				
				$transactions_recus = $this->Transaction_model->get_all_transaction_by_sql($sql_string);
				
				$tab_entete =['#','MONTANT','COMMISSION TOTAL','COMMISSION DEEAY', 'DEVISE','TYPE','DU COMPTE','DESCRPITION','TRANS ID','MARCHAND','DATE','HEURE','ETAT','STATUS'];
				$spreadsheet = new Spreadsheet();
				$myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'DEEPAY EXPORTATION');
				$spreadsheet->addSheet($myWorkSheet,0);
				$spreadsheet->setActiveSheetIndex(0);
				$worksheet = $spreadsheet->getActiveSheet();
				$worksheet->fromArray($tab_entete, null, 'A1');


				foreach ($transactions_recus as $key => $transaction_recu) {

					$devise = $this->Devise_model->get_devise($transaction_recu['id_devise']);
					$devise['abreviation'] = (!empty($devise)) ? $devise['abreviation'] : '';
					$type = ((int)$transaction_recu['transaction_type'] ==1 ) ? 'ENTREE' : 'SORTIE';
					$marchand = $this->Entreprise_model->get_entreprise( $transaction_recu['id_foreign_entreprise']);
					$marchand['nom'] = (!empty($marchand)) ? $marchand['nom'] :  '';
					$etat = ((int)$transaction_recu['id_foreign_statut_execution'] ==1 ) ? 'EXECUTEE' : 'NON EXECUTEE';
					$status = ((int)$transaction_recu['id_foreign_statut_transaction'] ==1 ) ? 'REUSSI / CONFIRMEE PAR L\'UTILISATEUR' : 'ECHOUEE / NON CONFIRMEE PAR L\'UTILISATEUR ';

					$data = [
						($key+1),
						$transaction_recu['montant'],
						$transaction_recu['network_commission'],
						$transaction_recu['dee_pay_commission'],
						$devise['abreviation'],
						$type,
						$transaction_recu['from_num'],
						$transaction_recu['description'],
						$transaction_recu['network_transaction_ref'],
						$marchand['nom'],
						$transaction_recu['date_transaction'],
						$transaction_recu['time_transaction'],
						$etat,
						$status
					];
					
					$worksheet->fromArray($data, null, 'A' . ($key + 2));
				}

				$writer = new Xlsx($spreadsheet);
				$writer->save('assets/uploads/files/data_excel_output_dee_pay_export_'.date('d-m-Y').'.xlsx');

				//echoTab($sql_string." ==> ");
				//echoTab($transactions_recus);

				$crud->where($sql_string);

				$crud->set_table('transaction');

				if( (int) $_SESSION['role']['id_role']<>1){
					$crud->columns('id_transaction','id_foreign_statut_transaction','montant','id_devise','transaction_type','from_num','description','deepay_transaction_ref','id_foreign_entreprise','date_transaction','time_transaction','id_foreign_statut_execution');
				}else {
					$crud->columns('id_transaction','id_foreign_statut_transaction','montant','dee_pay_commission','id_devise','transaction_type','from_num','description','deepay_transaction_ref','network_transaction_ref','id_foreign_entreprise','date_transaction','time_transaction','id_foreign_statut_execution');
				}

				$crud->display_as('id_transaction','#');
				$crud->display_as('montant','Montant');
				$crud->display_as('id_devise','Device');
				$crud->display_as('deepay_transaction_ref','REF. DEEPAY');
				$crud->display_as('network_transaction_ref','Trans.ID');
				$crud->display_as('transaction_type','Type');
				$crud->display_as('from_num','Du compte');
				$crud->display_as('id_foreign_entreprise','Marchand');
				$crud->display_as('dee_pay_commission','Commission Deepay');
				$crud->display_as('network_commission','Commission Du Réseau');
				$crud->display_as('description','Description');
				$crud->display_as('date_transaction','Date');
				$crud->display_as('time_transaction','Heure');
				$crud->set_subject('Une Transaction');
				$crud->display_as('id_foreign_statut_execution','Etat');
				$crud->display_as('id_foreign_statut_transaction','Status');
				
				
				$this->stateDisplay($crud);

				$crud->callback_column('id_foreign_entreprise',array($this,'_callback_entreprise'));
				$crud->callback_column('id_foreign_statut_execution',array($this,'_callback_status_execution'));
				$crud->callback_column('id_foreign_statut_transaction',array($this,'_callback_status_transaction'));

				$crud->set_relation('id_devise','devise','abreviation');
				$crud->set_relation('transaction_type','type_transaction','nom_type_transaction');


				

				$crud->unset_add();
				$crud->unset_edit();
				$crud->unset_delete();
				$crud->unset_clone();

				if( ! check_privilege('transaction', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}
				
				if( check_privilege('transaction', $this->session->user['id_role'], 'voir') ){
					$crud->add_action('Vérifier', '', '','record',array($this,'_verifier_status_transaction'));
				}

				$crud->order_by('id_transaction','desc');
	
				$output = $crud->render();
	
				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['transaction_recu_link_active'=>$transaction_recu_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("transaction/recu", [
					'header' => $header, 
					'navbar'=>$navbar, 
					'sidebar' => $sidebar, 
					'footer' => $footer,
					'output'=>$output->output,
					'total_transcation_cdf'=>$total_transcation_cdf,
					'total_transcation_usd'=>$total_transcation_usd,
					'entreprises'=>$entreprises,
					'arr_status_executions'=>$arr_status_executions,
					'arr_status_transactions'=>$arr_status_transactions,
					'total_commission_ripa_cdf'=>$total_commission_ripa_cdf,
					'total_commission_ripa_usd'=>$total_commission_ripa_usd,
					'total_commission_network_cdf'=>$total_commission_network_cdf,
					'total_commission_network_usd'=>$total_commission_network_usd,
				]);	

			}else{
				redirect($_SERVER['HTTP_REFERER']);
			}	
		}else {
			redirect("Starter/login");
		}
	}


	public function sorti($code_message_in=null,$id_trx=null,$code_message_trx=null,$transID=null,$transRef=null)
	{
		if(  $this->session->logged_in ){
			if(check_privilege('transaction', $this->session->user['id_role'], 'voir')){


				$crud = new grocery_CRUD();
				$transaction_sorti_link_active	= 'link_menu_active';
				$entreprises = $this->Entreprise_model->get_all_entreprises();
				$arr_status_executions = [
					['id_foreign_statut_execution'=>0,'designation'=>'Non Exécutée'],
					['id_foreign_statut_execution'=>1,'designation'=>'Exécutée']
				]; 
				$arr_status_transactions = [
					['id_foreign_statut_transaction'=>0,'designation'=>'En cours'],
					['id_foreign_statut_transaction'=>1,'designation'=>'Réussie']
				]; 
				$sql_string = '';
				$final_message = '';

				if( (int) $_SESSION['role']['id_role']<>1){
					$sql_string.= 'id_foreign_entreprise='. $_SESSION['user']['id_entreprise_utilisateur'];
				}

				if( isset( $_POST['start_date'] )  AND isset($_POST['end_date'])){
					$sql = "date_transaction BETWEEN'".$_POST['start_date']."' AND '".$_POST['end_date']."'";
					$crud->where($sql);

					if( strlen($sql_string) > 3){
						$sql_string.= " AND date_transaction BETWEEN'".$_POST['start_date']."' AND '".$_POST['end_date']."'";
					}else{
						$sql_string = "date_transaction BETWEEN'".$_POST['start_date']."' AND '".$_POST['end_date']."'";;
					}
				}else {
					if($id_trx == null){
						$sql = "date_transaction BETWEEN'".date('Y-m-d')."' AND '".date('Y-m-d')."'";
						$crud->where($sql);
	
						if( strlen($sql_string) > 3){
							$sql_string.= " AND date_transaction BETWEEN'".date('Y-m-d')."' AND '".date('Y-m-d')."'";
						}else{
							$sql_string = "date_transaction BETWEEN'".date('Y-m-d')."' AND '".date('Y-m-d')."'";;
						}
					}
				}

				if( isset( $_POST['id_foreign_entreprise'] )  AND ($_POST['id_foreign_entreprise']) <> 'null' AND strlen($_POST['id_foreign_entreprise'])>0 ){
					if( strlen($sql_string) > 3){
						$sql_string.= " AND id_foreign_entreprise=".$_POST['id_foreign_entreprise'] ;
					}else{
						$sql_string = "id_foreign_entreprise=".$_POST['id_foreign_entreprise'];
					}
				}

				if( isset( $_POST['id_foreign_statut_execution'] )  AND $_POST['id_foreign_statut_execution'] <> 'null' AND strlen($_POST['id_foreign_statut_execution'])>0){
					if( strlen($sql_string) > 3){
						$sql_string.= " AND id_foreign_statut_execution=".$_POST['id_foreign_statut_execution'] ;
					}else{
						$sql_string = "id_foreign_statut_execution=".$_POST['id_foreign_statut_execution'];
					}
				}

				if( isset( $_POST['id_foreign_statut_transaction'] )  AND ($_POST['id_foreign_statut_transaction']) <> 'null' AND strlen($_POST['id_foreign_statut_transaction'])>0){
					if( strlen($sql_string) > 3){
						$sql_string.= " AND id_foreign_statut_transaction=".$_POST['id_foreign_statut_transaction'] ;
					}else{
						$sql_string = "id_foreign_statut_transaction=".$_POST['id_foreign_statut_transaction'];
					}
				}


				if($id_trx<>null){
					$transaction = $this->Transaction_model->get_transaction($id_trx);
					if (!empty($transaction)) {
						$crud->where(['id_transaction'=>$id_trx]);
					}
				}


				if( strlen($sql_string) > 3){
					$sql_string.= " AND transaction_type=2" ;
				}else{
					$sql_string = "transaction_type=2";
				}

				$total_transcation_cdf = $this->Transaction_model->get_sum_montant_all_transaction_by_sql_id_devise($sql_string,1);
				$total_transcation_usd = $this->Transaction_model->get_sum_montant_all_transaction_by_sql_id_devise($sql_string,2);
				$total_commission_ripa_cdf = $this->Transaction_model->get_sum_montant_all_ripa_commission_transaction_by_sql_id_devise($sql_string,1);
				$total_commission_ripa_usd = $this->Transaction_model->get_sum_montant_all_ripa_commission_transaction_by_sql_id_devise($sql_string,2);
				$total_commission_network_cdf = $this->Transaction_model->get_sum_montant_all_network_commission_transaction_by_sql_id_devise($sql_string,1);
				$total_commission_network_usd = $this->Transaction_model->get_sum_montant_all_network_commission_transaction_by_sql_id_devise($sql_string,2);
				
				$total_transcation_cdf = (!empty($total_transcation_cdf)) ? $total_transcation_cdf : 0;
				$total_transcation_usd = (!empty($total_transcation_usd)) ? $total_transcation_usd : 0;
				$total_commission_ripa_cdf = (!empty($total_commission_ripa_cdf)) ? $total_commission_ripa_cdf : 0;
				$total_commission_ripa_usd = (!empty($total_commission_ripa_usd)) ? $total_commission_ripa_usd : 0;
				$total_commission_network_cdf = (!empty($total_commission_network_cdf)) ? $total_commission_network_cdf : 0;
				$total_commission_network_usd = (!empty($total_commission_network_usd)) ? $total_commission_network_usd : 0;


				$transactions_sorties = $this->Transaction_model->get_all_transaction_by_sql($sql_string);
				$tab_entete =['#','MONTANT','COMMISSION TOTAL','COMMISSION DEEAY', 'DEVISE','TYPE','DU COMPTE','DESCRPITION','TRANS ID','MARCHAND','DATE','HEURE','ETAT','STATUS'];
				$spreadsheet = new Spreadsheet();
				$myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'DEEPAY EXPORTATION');
				$spreadsheet->addSheet($myWorkSheet,0);
				$spreadsheet->setActiveSheetIndex(0);
				$worksheet = $spreadsheet->getActiveSheet();
				$worksheet->fromArray($tab_entete, null, 'A1');


				foreach ($transactions_sorties as $key => $transaction_sortie) {

					$devise = $this->Devise_model->get_devise($transaction_sortie['id_devise']);
					$devise['abreviation'] = (!empty($devise)) ? $devise['abreviation'] : '';
					$type = ((int)$transaction_sortie['transaction_type'] ==1 ) ? 'ENTREE' : 'SORTIE';
					$marchand = $this->Entreprise_model->get_entreprise( $transaction_sortie['id_foreign_entreprise']);
					$marchand['nom'] = (!empty($marchand)) ? $marchand['nom'] :  '';
					$etat = ((int)$transaction_sortie['id_foreign_statut_execution'] ==1 ) ? 'EXECUTEE' : 'NON EXECUTEE';
					$status = ((int)$transaction_sortie['id_foreign_statut_transaction'] ==1 ) ? 'REUSSI / CONFIRMEE PAR L\'UTILISATEUR' : 'ECHOUEE / NON CONFIRMEE PAR L\'UTILISATEUR ';

					$data = [
						($key+1),
						$transaction_sortie['montant'],
						$transaction_recu['network_commission'],
						$transaction_sortie['dee_pay_commission'],
						$devise['abreviation'],
						$type,
						$transaction_sortie['from_num'],
						$transaction_sortie['description'],
						$transaction_sortie['network_transaction_ref'],
						$marchand['nom'],
						$transaction_sortie['date_transaction'],
						$transaction_sortie['time_transaction'],
						$etat,
						$status
					];
					
					$worksheet->fromArray($data, null, 'A' . ($key + 2));
				}

				$writer = new Xlsx($spreadsheet);
					$writer->save('assets/uploads/files/data_excel_output_dee_pay_export_'.date('d-m-Y').'.xlsx');

				//echoTab($sql_string." ==> ");
				//echoTab($entreprises);

				$crud->where($sql_string);
				$crud->set_table('transaction');

				if( (int) $_SESSION['role']['id_role']<>1){
					$crud->columns('id_transaction','id_foreign_statut_transaction','montant','id_devise','transaction_type','from_num','description','deepay_transaction_ref','Marchand','date_transaction','time_transaction','id_foreign_statut_execution');
				}else{
					$crud->columns('id_transaction','id_foreign_statut_transaction','montant','dee_pay_commission','id_devise','transaction_type','from_num','description','deepay_transaction_ref','Marchand','date_transaction','time_transaction','id_foreign_statut_execution');
				}

				$crud->display_as('id_transaction','#');
				$crud->display_as('montant','Montant');
				$crud->display_as('id_devise','Device');
				$crud->display_as('deepay_transaction_ref','REF. DEEPAY');
				$crud->display_as('network_transaction_ref','Trans.ID');
				$crud->display_as('transaction_type','Type');
				$crud->display_as('from_num','Au compte');
				$crud->display_as('id_foreign_entreprise','Marchand');
				$crud->display_as('dee_pay_commission','Commission Deepay');
				$crud->display_as('network_commission','Commission Du Réseau');
				$crud->display_as('description','Description');
				$crud->display_as('date_transaction','Date');
				$crud->display_as('time_transaction','Heure');
				$crud->display_as('id_foreign_statut_execution','Etat');
				$crud->display_as('id_foreign_statut_transaction','Status');
				$crud->set_subject('Une Transaction');	
				
				$crud->add_fields(array(
					'montant',
					'id_devise',
					'deepay_transaction_ref',
					'network_transaction_ref',
					'transaction_type',
					'from_num',
					'id_foreign_entreprise',
					'dee_pay_commission',
					'network_commission',
					'description',
					'date_transaction',
					'time_transaction',
					'id_foreign_statut_execution',
					'id_foreign_statut_transaction'
				));


				$crud->edit_fields(array(
					'montant',
					'id_devise',
					'transaction_type',
					'from_num',
					'id_foreign_entreprise',
					'description',
					'date_transaction',
					'time_transaction'
				));		
				
				
				$crud->callback_before_insert(array($this,'_onRowBeforeInserted'));
				$crud->callback_after_insert(array($this, '_onRowInserted'));
				$crud->callback_before_update(array($this,'_onRowBeforeUpdated'));
				$crud->callback_after_update(array($this, '_onRowUpdated'));
				$crud->callback_before_delete(array($this, '_onRowDeleted'));


				$this->stateDisplay($crud);
				$crud->set_relation('id_devise','devise','abreviation');
				$crud->required_fields('montant','id_devise','from_num','description');
				$crud->field_type('deepay_transaction_ref','hidden',time());
				$crud->field_type('network_transaction_ref','hidden',time());
				$crud->field_type('transaction_type','hidden',2);
				$crud->field_type('dee_pay_commission','hidden',2);
				$crud->field_type('network_commission','hidden',2);
				$crud->field_type('date_transaction','hidden',date('Y-m-d'));
				$crud->field_type('time_transaction','hidden',date('H:i:s'));
				$crud->field_type('id_foreign_statut_execution','hidden',0);
				$crud->field_type('id_foreign_statut_transaction','hidden',0);

				
				$crud->callback_column('Marchand',array($this,'_callback_entreprise'));
				$crud->callback_column('id_foreign_statut_execution',array($this,'_callback_status_execution'));
				$crud->callback_column('id_foreign_statut_transaction',array($this,'_callback_status_transaction'));
				$crud->callback_column('transaction_type',array($this,'_callback_transaction_type'));

				$crud->unset_edit();
				$crud->unset_clone();
				

				if( (int) $_SESSION['role']['id_role']<>1  ){
					$crud->field_type('id_foreign_entreprise','hidden',(int) $_SESSION['user']['id_entreprise_utilisateur']);
				}else{
					$crud->set_relation('id_foreign_entreprise','entreprise','nom');
				}


				if( ! check_privilege('transaction', $this->session->user['id_role'], 'voir') ){
					$crud->unset_read();
				}
				
				if( ! check_privilege('transaction', $this->session->user['id_role'], 'ajouter') ){
					$crud->unset_add();
				}

				if( ! check_privilege('transaction', $this->session->user['id_role'], 'supprimer') ){
					$crud->unset_delete();
				}

				if( check_privilege('transaction', $this->session->user['id_role'], 'editer') ){
					$crud->add_action('Exécuter', '', '', 'record',array($this,'_executé_transaction'));
				}


				if( check_privilege('transaction', $this->session->user['id_role'], 'voir') ){
					$crud->add_action('Vérifier', '', '','record',array($this,'_verifier_status_transaction'));
				}

				switch ($code_message_in) {

					case '12022024':
						$final_message = "Cette transaction à déjà été bien efféctuée !";
					break;

					case '12022023':
						$final_message = "Transaction bien envoyée, Veuillez introduire le pin sur votre téléphone pour valider la transaction";
					break;


					case '12022022':
						$final_message = "Le marchand que vous payez n'est pas trouvé dans notre système, Veillez introduite un bon numéro marchand !";
					break;

					case '12022021':
						$final_message = "Une erreur lors du traitement de votre requête !";
					break;

					case '12022020':
						$final_message = "Impossible de traiter la demande, veuillez réessayer !";
					break;

					default:
						$final_message = "";
					break;

				}


				$crud->order_by('id_transaction','desc');
	
				$output = $crud->render();
	
				$header = $this->load->view("layouts/header", ["css_files"=>$output->css_files], true);
				$sidebar = $this->load->view("layouts/sidebar", [], true);
				$navbar = $this->load->view("layouts/menu_nav_bar", ['transaction_sorti_link_active'=>$transaction_sorti_link_active], true);
				$footer = $this->load->view("layouts/footer", ["js_files"=>$output->js_files], true);
				$this->load->view("transaction/sorti", [
					'header' => $header, 
					'navbar'=>$navbar, 
					'sidebar' => $sidebar, 
					'footer' => $footer,
					'output'=>$output->output,
					'total_transcation_cdf'=>$total_transcation_cdf,
					'total_transcation_usd'=>$total_transcation_usd,
					'entreprises'=>$entreprises,
					'arr_status_executions'=>$arr_status_executions,
					'arr_status_transactions'=>$arr_status_transactions,
					'total_commission_ripa_cdf'=>$total_commission_ripa_cdf,
					'total_commission_ripa_usd'=>$total_commission_ripa_usd,
					'total_commission_network_cdf'=>$total_commission_network_cdf,
					'total_commission_network_usd'=>$total_commission_network_usd,
					'final_message'=>$final_message
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
				$crud->set_subject("Une Transaction");
				break;
			case 'list':
				$crud->set_subject("Une Transaction");
				break;
			case 'add':
				$crud->set_subject("D'Une Transaction");
				break;
			
			case 'edit':
				$crud->set_subject("D'Une Transaction");
				break;
			
			default:
				break;
		}

	}


	public function _callback_entreprise($value, $row){
		$entreprise = $this->Entreprise_model->get_entreprise($row->id_foreign_entreprise);
		if(!empty($entreprise)){
			return  "<img src='".base_url('assets/uploads/files/').$entreprise['logo']."'  style='witdh auto; height:40px; object-fit: scale-down;'/> <br/>".$entreprise['nom_marchand'];
		}
	}


	function _executé_transaction($primary_key , $row){		
		return site_url('Transaction/execute/'.str_shuffle('abdcuxz2345').'/'.str_shuffle('abdcuxz2345').'/'.$row->id_transaction.'/');
	}


	public function execute ($sheme_1=null, $sheme_2=null, $id_transaction=null){

		if( ($id_transaction <> null AND $id_transaction<>0 AND is_int( (int) $id_transaction))  ){

			$id_transaction = (int) $id_transaction;
			$transaction = $this->Transaction_model->get_transaction($id_transaction);
			$message_result = "12022023";
			$transaction_execution = 0;

			if(!empty($transaction)){

				// if ((int) $transaction['id_foreign_statut_execution'] == 0 ) {
				// 	$transaction_execution = 1;
				// }else {
				// 	$transaction_execution = 0;
				// }			
				//$this->Transaction_model->update( $transaction['id_transaction'],['id_foreign_statut_execution'=>$transaction_execution] );

				$compte_entreprise = $this->Compte_money_model->get_compte_money_by_num_mobile_money($transaction['from_num']);
				$id_foreign_entreprise = (!empty($compte_entreprise)) ? $compte_entreprise['id_entreprise_cliente'] : 0;
				$entreprise = $this->Entreprise_model->get_entreprise($id_foreign_entreprise);
				
				if(!empty($entreprise)){
					$transaction_set =  $this->Transaction_model->get_transaction_by_ripa_transaction_ref_id_foreign_statut_transaction($transaction['deepay_transaction_ref'],1);
					if (empty($transaction_set)) {
						$this->process_transaction($transaction, $entreprise);
					}else {
						$this->Transaction_model->update( $transaction['id_transaction'],['id_foreign_statut_execution'=>1] );
						$message_result = "12022024";
						redirect('Transaction/sorti/'.$message_result.'/') ;
					}
				}else{
					$this->Transaction_model->update( $transaction['id_transaction'],['id_foreign_statut_execution'=>1] );
					$message_result = "12022022";
					redirect('Transaction/sorti/'.$message_result.'/') ;
				}
				
			}

		}

	}


	public function _callback_status_execution($value, $row){
		if((int) $value == 0)
			return  '<span class="badge badge-danger" style="background-color:red; color:white; font-size: 12px;">Non Exécutée</span>';
		else
			return '<span class="badge badge-success" style="background-color:green; color:white; font-size: 12px;">Exécutée</span>';	
	}
	
	public function _callback_status_transaction($value, $row){
		if((int) $value == 0)
			return  '<a title="Cliquez ici pour vérifier" href="'.site_url('Transaction/check/'.$row->id_transaction.'/').'"><span class="badge badge-danger" style="background-color:red; color:white; font-size: 12px;">En cours</span></a>';
		else
			return '<a title="Cliquez ici pour vérifier" href="'.site_url('Transaction/check/'.$row->id_transaction.'/').'"><span class="badge badge-success" style="background-color:green; color:white; font-size: 12px;">Réussie</span></a>';	
	}

	public function _callback_transaction_type($value, $row){
		if((int) $value == 1)
			return  'Entrée';
		else
			return 'Sortie';	
	}


	private function process_transaction($arr_trans_in = null,$entreprise_in = null ){
				
		$message_result = '';
		$devise = $this->Devise_model->get_devise($arr_trans_in['id_devise']);
		$devise['abreviation'] = (!empty($devise)) ? $devise['abreviation'] : 'CDF';
		$entreprise_in['token'] = str_replace('<p>','',$entreprise_in['token']);
		$entreprise_in['token'] = str_replace('</p>','',$entreprise_in['token']);
		$entreprise_in['token'] = trim($entreprise_in['token']);

		

		$data = array(
			"merchant" => $entreprise_in['nom_marchand'],
			"type" => "1",
			"phone"=>str_replace('+','',$arr_trans_in['from_num']),
			"reference" => time(),
			"amount" => $arr_trans_in['montant'],
			"currency" => $devise['abreviation'],
			"callbackUrl" => "https://www.deeservices.tech/web/",
		);
		$data = json_encode($data);


		$gateway = "https://beta-backend.flexpay.cd/api/rest/v1/paymentService";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $gateway);
			curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/json","Authorization: ".$entreprise_in['token']));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
		$response = curl_exec($ch);

		if(curl_errno($ch)) {
			$message_result = '12022021'; 
		}else{
			curl_close($ch);
			$jsonRes = json_decode($response);
			$code = $jsonRes->code;
			if ($code != "0") {
				$message_result = '12022020';
			 }else{
				$message_result = '12022023';
				$orderNumber = $jsonRes->orderNumber;
				$this->Transaction_model->update_by_ripa_transaction_ref($arr_trans_in['deepay_transaction_ref'], ['id_foreign_statut_execution'=>1,'network_transaction_ref'=>$orderNumber] );
		   }
		}

		redirect('Transaction/sorti/'.$message_result.'/') ;
	}


	public function on_process_transaction(){

		$data = file_get_contents('php://input'); 
		$json = json_decode($data, true); 
		if (isset($json['orderNumber'])) {
			$orderNumber = $json['orderNumber'];
			$user_phone = '+'.$json['phone'];
			$code = $json['code'];
			if( (int)$code == 0){
				$network_commission = ((float) $json['amountCustomer'] - (float) $json['amount']);
				$dee_pay_commission = ($network_commission * (29/100));
				$dee_pay_commission = round($dee_pay_commission,2);
				$network_commission = $network_commission - $dee_pay_commission;
				$last_user_phone_transaction = $this->Transaction_model->get_last_transaction_by_from_num($user_phone);
				if(!empty($last_user_phone_transaction)){
					$this->Transaction_model->update($last_user_phone_transaction['id_transaction'], ['id_foreign_statut_transaction'=>1,'dee_pay_commission'=>$dee_pay_commission,'network_commission'=>$network_commission] );
				}
			}
		}
		return true;
	}


	public function _onRowBeforeInserted($post_array)
	{
		return $post_array;
	}


	public function _onRowInserted($post_array, $primary_key)
	{
		
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'transaction ',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "insertion", 
			"text_descriptif" => "ajout d'Une transaction de sorti ref ".$post_array["deepay_transaction_ref"],
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
			"nom_table"=> 'transaction',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "mise à jour",
			"text_descriptif" => "mise à jour de la transaction de sorti ref  ".$post_array["deepay_transaction_ref"],
			"date_heure" => date("Y-m-d H:m:i")
		];

		$this->Action_utilisateur_model->add( $action );
		return true;
	}

	public function _onRowDeleted($primary_key)
	{
		$data = ['id_transaction '=>$primary_key];
		$action = [
			"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
			"nom_table"=> 'transaction',
			"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
			"id_champ"=> $primary_key,
			"action" => "suppression",
			"text_descriptif" => "suppression de la transaction de sorti ref ".$this->ModelGetTableRow->getObjectFieldValue($data,'transaction')->deepay_transaction_ref,
			"date_heure" => date("Y-m-d H:m:i")		
		];
		$this->Action_utilisateur_model->add( $action );
		return true;
	}
	

	public  function _verifier_status_transaction ($primary_key , $row){
		return site_url('Transaction/check/'.$primary_key.'/');
	}

	public function check($id_transaction){
		$transaction = $this->Transaction_model->get_transaction($id_transaction);
		$deepay_commission_percent = 1;
		if (!empty($transaction)) {
			$entreprise = $this->Entreprise_model->get_entreprise($transaction['id_foreign_entreprise']);
			if (!empty($entreprise)) {
				$token = str_replace('<p>','',$entreprise['token']);
				$token = str_replace('</p>','',$token);
				$token = trim($token);
				$network_transaction_ref = $transaction['network_transaction_ref'];
				$gateway = "https://backend.flexpay.cd/api/rest/v1/check/".$network_transaction_ref;

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $gateway);
				curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/json","Authorization: ".$token ));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
				$response = curl_exec($ch);

				if(curl_errno($ch)) {
					$message_result = 'Une erreur lors du traitement de votre requête'; 
				}else{
					curl_close($ch);
					$jsonRes = json_decode($response);
					$code = $jsonRes->code;
					$message_result = $jsonRes->message;
					$trx_result = $jsonRes->transaction;
					if($code == "0"){
						if( (int) $trx_result->status == 0){
							$network_commission = (float) $trx_result->amountCustomer - (float) $trx_result->amount;
							$network_commission_percent = ($network_commission * 100) / (float) $trx_result->amount ;
							$flexpay_commission_percent = $network_commission_percent - $deepay_commission_percent;
							$flexpay_commission = ($flexpay_commission_percent/100) * (float) $trx_result->amount;
							$dee_pay_commission = $network_commission - $flexpay_commission;
							$dee_pay_commission = round($dee_pay_commission,2);
							$this->Transaction_model->update($transaction['id_transaction'], ['id_foreign_statut_transaction'=>1,'dee_pay_commission'=>$dee_pay_commission,'network_commission'=>$flexpay_commission] );
						}
					}
				}
			}

			if ((int)$transaction['transaction_type'] ==1) {
				redirect('Transaction/recu/'.$transaction['id_transaction'].'/');
			}else{
				redirect('Transaction/sorti/'.$transaction['id_transaction'].'/');
			}
		}else{
			redirect($_SERVER['HTTP_REFERER']);
		}
		
	}


}
