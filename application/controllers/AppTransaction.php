<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT");

defined('BASEPATH') OR exit('No direct script access allowed');

class AppTransaction extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}


	public function showcommission(){
		$response = "";
		if (isset($_POST['commission'])) {
			$commission = base64_decode($_POST['commission']);
			if ($commission == 'give_commission') {
				$response = iconv('UTF-8','windows-1252','les frais sont estimée de 2.5% à 3.5% sur les transactions Mobile Money et de 3.5% à 4.5% sur les cartes bancaires.');
				$response = base64_encode($response);
			}
		}
		echo $response;
	}

	public function getTodayUserTransactionHistory(){

		$trx_home_result ="";
		$nom_type_operateur ='';
		$date_transaction = date('Y-m-d');
		//$date_transaction = '2024-04-20';
		$num_mobile_money_user = '+243';
		$trx_empty_string = '<div class="col s12 text-center  empty-history">
								<i class="fa fa-list-alt fa-4x"></i> <br>
								<span>Liste des transactions vide</span>
							</div>';

		if(isset($_POST['getHomeTransaction']) AND !empty($_POST['getHomeTransaction']) ){

			$nom_type_operateur    = formatOperatorMobileMoneyName( getTypeOperator(base64_decode($_POST['numMobileMoney'])) );
			$num_mobile_money_user .= base64_decode($_POST['numMobileMoney']);
			$transactions = $this->Transaction_model->get_transaction_by_from_num_date_id_foreign_statut_transaction($num_mobile_money_user,$date_transaction,$date_transaction,1);
			
			if (!empty($transactions)) {
				foreach ($transactions as $key => $transaction) {

					$entreprise = $this->Entreprise_model->get_entreprise( $transaction['id_foreign_entreprise'] );
					$short_code_ent = (!empty($entreprise)) ? $entreprise['nom_marchand'] : 'N/A';
					$numero_ent = (!empty($entreprise)) ? $entreprise['telephone_contact'] : 'N/A';
					$devise 		= $this->Devise_model->get_devise($transaction['id_devise']);
					$devise['abreviation'] 	= (!empty($devise)) ? $devise['abreviation'] : 'CDF';
					$montant_paiement = number_format($transaction['montant'],2,',',' ');
					$cout_transaction = number_format($transaction['network_commission'],4,","," ");
					$montant_total    = number_format($transaction['montant']+$transaction['network_commission'],2,',',' ');
					$deepay_transaction_ref  = $transaction['deepay_transaction_ref'];
					$network_transaction_ref = $transaction['network_transaction_ref'];
					$type_transaction =  ($transaction['transaction_type'] == 1) ? 'Paiement de' : 'Réception de '; 
					$labe_destination =  ($transaction['transaction_type'] == 1) ? ' Au ' : 'De '; 

					$trx_home_result.=' <a class="white-text trx-item-container">
											<div class="col s12  trx-item">
												<div class="col s12 theme-color border-white-alpha trx-detail waves-effect waves-light">
													<div class="col s7 text-left  trx-detail-head trx-detail-head-left"> '.$type_transaction.' '.$montant_paiement.' '.$devise['abreviation'].'</div>
													<div class="col s5 text-right trx-detail-head trx-detail-head-right"> <i class="fa fa-check-circle green-text"></i>'.$nom_type_operateur.' </div>
													<div class="col s8 text-left no-padding no-margin trx-detail-content"> 
													<span class="num">'.$labe_destination.' '.$short_code_ent.'</span> <br>
													<span class="transid font-arial">Trans.ID : '.$network_transaction_ref.'</span> <br>
													<span class="cout font-arial">Cout : '.$cout_transaction.' '.$devise['abreviation'].'</span><br>
													<span class="total font-arial">Total : '.$montant_total.' '.$devise['abreviation'].'</span><br>
													<span class="ref font-arial">Réf: '.$deepay_transaction_ref.'</span>
													</div>
													<div class="col s4 text-right no-padding no-margin trx-detail-content"> 
													<span class="temp"><i class="fa fa-calendar"></i> '.$transaction['date_transaction'].' '.$transaction['time_transaction'].'</span>
													<br><br>
													<span class="trx-icon-footer"><i class="fa fa-dollar fa-2x"> </i></span>
													<span class="trx-icon-description" style="display:none">'.$transaction['description'].'</span>
													</div>
												</div>
											</div>
										</a>';
				}
			}else {
				$trx_home_result = $trx_empty_string;
			}
			
			echo $trx_home_result;
		}
				
	}

	public function getUserTransactionHistory(){

		$trx_home_result ="";
		$nom_type_operateur ='';
		$num_mobile_money_user = '+243';
		$trx_empty_string = '<div class="col s12 text-center  empty-history">
								<i class="fa fa-list-alt fa-4x"></i> <br>
								<span>Liste des transactions vide</span>
							</div>';

		if(isset($_POST['getTransactions']) AND !empty($_POST['getTransactions']) ){

			if (strlen(base64_decode($_POST['numMobileMoney'])) > 4) {

				$nom_type_operateur    = formatOperatorMobileMoneyName( getTypeOperator(base64_decode($_POST['numMobileMoney'])) );
				$num_mobile_money_user .= base64_decode($_POST['numMobileMoney']);
				$trx_filtered_by = base64_decode($_POST['trxFilteredBy']); 
				$limit_offset = base64_decode($_POST['limitOffset']);
				$start_date = (isset($_POST['startDate'])) ? base64_decode($_POST['startDate']) : null;
				$end_date = (isset($_POST['endDate'])) ? base64_decode($_POST['endDate']) : null;
				$sql_query ='';
								

				if($start_date <> null AND $end_date<> null){
					$sql_query.= "date_transaction BETWEEN '".$start_date."' AND '".$end_date."'";
				}else{
					$start_date = date('Y-m-d');
					$end_date 	= date('Y-m-d');
					$sql_query.= "date_transaction BETWEEN '".$start_date."' AND '".$end_date."'";
				}


				if($trx_filtered_by <> '0'){
					if( strlen($sql_query) > 3){
						$sql_query.=" AND transaction_type=".$trx_filtered_by;
					}else{
						$sql_query.=" transaction_type=".$trx_filtered_by;
					}
				}

				if($num_mobile_money_user){
					if( strlen($sql_query) > 3){
						$sql_query.=" AND from_num='".$num_mobile_money_user."'";
					}else{
						$sql_query.=" from_num='".$num_mobile_money_user."'";
					}
				}

				$sql_query.=" AND id_foreign_statut_transaction=1";
				

				
				$transactions = $this->Transaction_model->get_all_transaction_by_sql_limit_offset($sql_query,10,$limit_offset);
				$total_transcation = $this->Transaction_model->get_total_all_transaction_by_sql($sql_query);
				$total_transcation_cdf = $this->Transaction_model->get_sum_montant_all_transaction_by_sql_id_devise($sql_query,1);
				$total_transcation_usd = $this->Transaction_model->get_sum_montant_all_transaction_by_sql_id_devise($sql_query,2);
				$total_transcation_cdf = (!empty($total_transcation_cdf)) ? $total_transcation_cdf : 0;
				$total_transcation_usd = (!empty($total_transcation_usd)) ? $total_transcation_usd : 0;
				$index_limit_offset  = (int) $limit_offset +1;

				if (!empty($transactions)) {
					foreach ($transactions as $key => $transaction) {
	
						$entreprise = $this->Entreprise_model->get_entreprise( $transaction['id_foreign_entreprise'] );
						$short_code_ent = (!empty($entreprise)) ? $entreprise['nom_marchand'] : 'N/A';
						$numero_ent = (!empty($entreprise)) ? $entreprise['telephone_contact'] : 'N/A';
						$devise 		= $this->Devise_model->get_devise($transaction['id_devise']);
						$devise['abreviation'] 	= (!empty($devise)) ? $devise['abreviation'] : 'CDF';
						$montant_paiement = number_format($transaction['montant'],2,',',' ');
						$cout_transaction = number_format($transaction['network_commission'],4,","," ");
						$montant_total    = number_format($transaction['montant']+$transaction['network_commission'],2,',',' ');
						$deepay_transaction_ref  = $transaction['deepay_transaction_ref'];
						$network_transaction_ref = $transaction['network_transaction_ref'];
						$type_transaction =  ($transaction['transaction_type'] == 1) ? 'Paiement de' : 'Réception de '; 
						$labe_destination =  ($transaction['transaction_type'] == 1) ? ' Au ' : 'De '; 
						
	
						$trx_home_result.=' <a class="white-text trx-item-container" idDatabase="'.$index_limit_offset.'">
												<div class="col s12  trx-item">
													<div class="col s12 theme-color border-white-alpha trx-detail waves-effect waves-light">
														<div class="col s7 text-left  trx-detail-head trx-detail-head-left"> '.$type_transaction.' '.$montant_paiement.' '.$devise['abreviation'].'</div>
														<div class="col s5 text-right trx-detail-head trx-detail-head-right"> <i class="fa fa-check-circle green-text"></i>'.$nom_type_operateur.' </div>
														<div class="col s8 text-left no-padding no-margin trx-detail-content"> 
														<span class="num">'.$labe_destination.' '.$short_code_ent.'</span> <br>
														<span class="transid font-arial">Trans.ID : '.$network_transaction_ref.'</span> <br>
														<span class="cout font-arial">Cout : '.$cout_transaction.' '.$devise['abreviation'].'</span><br>
														<span class="total font-arial">Total : '.$montant_total.' '.$devise['abreviation'].'</span><br>
														<span class="ref font-arial">Réf: '.$deepay_transaction_ref.'</span>
														</div>
														<div class="col s4 text-right no-padding no-margin trx-detail-content"> 
														<span class="temp"><i class="fa fa-calendar"></i> '.$transaction['date_transaction'].' '.$transaction['time_transaction'].'</span>
														<br><br>
														<span class="trx-icon-footer"><i class="fa fa-dollar fa-2x"> </i></span>
														<span class="trx-icon-description" style="display:none">'.$transaction['description'].'</span>
														</div>
													</div>
												</div>
											</a>';
						$index_limit_offset = ( $index_limit_offset + 1 );
					}
					$trx_home_result =  $trx_home_result."deepay".round($total_transcation_cdf,2)."deepay".round($total_transcation_usd,2)."deepay".$index_limit_offset."deepay".$total_transcation;
				}else {
					$trx_home_result = $trx_empty_string;
				}
				
				echo $trx_home_result;
			}

		}
			
	}

	public function calculate_commission(){
		if(isset($_POST['numaccount'])){

			$numaccount  	= htmlspecialchars( base64_decode($_POST['numaccount'] ) );
			$amount      	= htmlspecialchars( base64_decode($_POST['amount'] ) );
			$device      	= htmlspecialchars( base64_decode($_POST['device'] ) );
			$entreprise  	= [];
			$compte_money 	= 0;
			$short_code_entreprise = '';

			
			if(strlen($numaccount) === 16 OR strlen($numaccount) === 6 ){
				$compte_money = $this->Compte_money_model->get_compte_money_by_num_mobile_money($numaccount);
			}else {
				$compte_money = $this->Compte_money_model->get_compte_money_by_num_mobile_money('+243'.$numaccount);
			}

			if(!empty($compte_money)){
				$entreprise = $this->Entreprise_model->get_entreprise($compte_money['id_entreprise_cliente']);
				$short_code_entreprise = (!empty($entreprise)) ? $entreprise['nom_marchand'] : '';
			}

			$commission_mobile_money = $this->Commission_model->get_last_commission_by_id_foreign_type_commission(1);
			$commission_carte_bancaire = $this->Commission_model->get_last_commission_by_id_foreign_type_commission(2);
	
			$commission_mobile_money['commission_network'] = (!empty($commission_mobile_money)) ? $commission_mobile_money['commission_network'] : 0;
			$commission_carte_bancaire['commission_network'] = (!empty($commission_carte_bancaire)) ? $commission_carte_bancaire['commission_network'] : 0;

			if(strlen($numaccount) === 16 ){
				//$amount = ( ($amount * (float) $commission_carte_bancaire['commission_network'] )/100);
				$amount =0;
				echo 'ok___'.base64_encode( $amount).'___'.base64_encode($short_code_entreprise);
			}else{
				//$amount = ( ($amount * (float) $commission_mobile_money['commission_network'] )/100);
				$amount =0;
				echo 'ok___'.base64_encode( $amount).'___'.base64_encode($short_code_entreprise);
			}

		}
	}

	public function makeTransaction (){
		if (isset($_POST['t']) AND !empty( $_POST['t'] ) ) {

			$trx_maker = base64_decode($_POST['n']);
			$trx_owner = base64_decode($_POST['a']);
			$trxdescription = htmlspecialchars($_POST['d']);
			$transactionDetail =  base64_decode($_POST['t']);
			$response = '';
		
			try {
				$transactionDetail = json_decode($transactionDetail);
				$montant =  $transactionDetail->montant;
				$num_marchant = $transactionDetail->numaccount;
				$num_marchant = (strlen($num_marchant) == 16 OR $num_marchant == 6) ? $num_marchant : '+243'.$num_marchant; 
				$devise = $this->Devise_model->get_devise_by_abreviation($transactionDetail->device);
				$id_devise = (!empty($devise)) ? $devise['id_devise'] : 1;
				$deepay_transaction_ref = $transactionDetail->numref;
				$num_utilisateur = (strlen($trx_maker) == 16 OR $trx_maker == 6) ? $trx_maker : '+243'.$trx_maker; 
				$transaction_type = ((int)$transactionDetail->typetransaction == 3) ? 1 : 1;
				
				$commission_mobile_money = $this->Commission_model->get_last_commission_by_id_foreign_type_commission(1);
				$commission_carte_bancaire = $this->Commission_model->get_last_commission_by_id_foreign_type_commission(2);
				$commission_mobile_money['commission_dee_pay'] = (!empty($commission_mobile_money)) ? $commission_mobile_money['commission_dee_pay'] : 0;
				$commission_carte_bancaire['commission_dee_pay'] = (!empty($commission_carte_bancaire)) ? $commission_carte_bancaire['commission_dee_pay'] : 0;
				$commission_mobile_money['commission_network'] = (!empty($commission_mobile_money)) ? $commission_mobile_money['commission_network'] : 0;
				$commission_carte_bancaire['commission_network'] = (!empty($commission_carte_bancaire)) ? $commission_carte_bancaire['commission_network'] : 0;
				$dee_pay_commission = ($num_utilisateur == 16) ? ($montant * ( $commission_carte_bancaire['commission_dee_pay'] /100) ) : ($montant * ( $commission_mobile_money['commission_dee_pay'] /100) );
				$network_commission = ($num_utilisateur == 16) ? ($montant * ( $commission_carte_bancaire['commission_network'] /100) ) : ($montant * ( $commission_mobile_money['commission_network'] /100) );
				$network_commission = $network_commission - $dee_pay_commission ;

				$date_transaction = date('Y-m-d');
				$time_transaction = date('H:i:s');
				$id_foreign_statut_transaction  = 0;
				$id_foreign_statut_execution 	= 1;
				$compte_entreprise = $this->Compte_money_model->get_compte_money_by_num_mobile_money($num_marchant);
				$id_foreign_entreprise = (!empty($compte_entreprise)) ? $compte_entreprise['id_entreprise_cliente'] : 0;
				$entreprise = $this->Entreprise_model->get_entreprise($id_foreign_entreprise);
				$id_foreign_entreprise = (!empty($entreprise)) ? $id_foreign_entreprise : 0 ;
				

				$array_trans =[
					'montant'=> $montant,
					'id_devise'=> $id_devise,
					'deepay_transaction_ref'=> $deepay_transaction_ref,
					'network_transaction_ref'=> null,
					'transaction_type'=> $transaction_type,
					'from_num'=> $num_utilisateur,
					'id_foreign_entreprise'=> $id_foreign_entreprise,
					'dee_pay_commission'=> $dee_pay_commission,
					'network_commission'=> $network_commission,
					'description'=> $trxdescription,
					'date_transaction'=> $date_transaction,
					'time_transaction'=> $time_transaction,
					'id_foreign_statut_transaction'=> $id_foreign_statut_transaction,
					'id_foreign_statut_execution'=> $id_foreign_statut_execution,
				];

				if(!empty($entreprise)){

					$transaction =  $this->Transaction_model->get_transaction_by_deepay_transaction_ref_id_foreign_statut_transaction($deepay_transaction_ref,1);
	
					if(empty($transaction)){
	
						$this->Transaction_model->add($array_trans);
						$response = $this->process_transaction($array_trans,$entreprise);
						if (preg_match('/Veuillez valider le push message/', $response)) {
							$response = 'ok___';
						}else {
							$response = "error";
						}
	
					}else {
						$response = 'nope___';
					}

				}else {
					$response = "error";
				}


			} catch (\Throwable $th) {
				$response = "error";
			}
			echo base64_encode($response);

			
		}
	}

	private function process_transaction($arr_trans_in = null,$entreprise_in = null ){
				
		$message_result = '';
		$devise = $this->Devise_model->get_devise($arr_trans_in['id_devise']);
		$devise['abreviation'] = (!empty($devise)) ? $devise['abreviation'] : 'CDF';
		$entreprise_in['token'] = str_replace('<p>','',$entreprise_in['token']);
		$entreprise_in['token'] = str_replace('</p>','',$entreprise_in['token']);
		$entreprise_in['token'] = trim($entreprise_in['token']);

		

		$data = array(
			"merchant" => "".trim($entreprise_in['nom_marchand'])."",
			"type" => "1",
			"phone"=>str_replace('+','',$arr_trans_in['from_num']),
			"reference" => time(),
			"amount" => $arr_trans_in['montant'],
			"currency" => $devise['abreviation'],
			"callbackUrl" => "https://www.deeservices.tech/web/",
		);
		$data = json_encode($data);


		$gateway = "https://backend.flexpay.cd/api/rest/v1/paymentService";
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
			$message_result = 'Une erreur lors du traitement de votre requête'; 
		}else{
			curl_close($ch);
			$jsonRes = json_decode($response);
			$code = $jsonRes->code;
			if ($code != "0") {
				$message_result = 'Impossible de traiter la demande, veuillez réessayer';
			 }else{
				$message_result = $jsonRes->message;
				$orderNumber = $jsonRes->orderNumber;
				$this->Transaction_model->update_by_deepay_transaction_ref($arr_trans_in['deepay_transaction_ref'], ['network_transaction_ref'=>$orderNumber] );
		   }
		}
		return $message_result;
	}


	public function on_process_transaction(){

		$data = file_get_contents('php://input'); 
		$json = json_decode($data, true); 
		$deepay_commission_percent = 1;
		if (isset($json['orderNumber'])) {
			$orderNumber = $json['orderNumber'];
			$user_phone = '+'.$json['phone'];
			$code = $json['code'];
			if( (int)$code == 0){
				$network_commission = ((float) $json['amountCustomer'] - (float) $json['amount']);
				$network_commission_percent = ($network_commission * 100) / (float) $json['amount'] ;
				$flexpay_commission_percent = $network_commission_percent - $deepay_commission_percent;
				$flexpay_commission = ($flexpay_commission_percent/100) * (float) $json['amount'];
				$dee_pay_commission = $network_commission - $flexpay_commission;
				$dee_pay_commission = round($dee_pay_commission,2);
				$last_user_phone_transaction = $this->Transaction_model->get_last_transaction_by_from_num($user_phone);
				if(!empty($last_user_phone_transaction)){
					$this->Transaction_model->update($last_user_phone_transaction['id_transaction'], ['id_foreign_statut_transaction'=>1,'dee_pay_commission'=>$dee_pay_commission,'network_commission'=>$network_commission] );
				}
			}
		}
		return true;
	}

 

	// $gateway = "https://beta-backend.flexpay.cd/api/rest/v1/paymentService";


}
