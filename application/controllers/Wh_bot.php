<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wh_bot extends CI_Controller {

	public function index()
	{
		if(  $this->session->logged_in ){

			$chatApiToken = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE2MTczMTczMjcsInVzZXIiOiIyNDM5NzE0MDMwNzUifQ.2Kfd0hMxmdo4qMHuO9vL6ckiavmzm5vrG4yEAWi7CoM";
			$number = "27727476976"; 
			$message = "*Daily Report System From  Epiphnaie Management*\n\n";

			$daily_date ='2021-02-24';

			$message.="*Date of report : ".$daily_date."* \n\n";
			$message.="*For Aem Co.za :*\n";
			$message.="Total number of orders   : ".count( $this->Commande_model->get_daily_commande($daily_date,4) )."\n";
			$message.="For a sale amount of     : ".(int) $this->Commande_model->get_amout_daily_commande($daily_date,4)." RAND\n";
			$message.="For a purchase amount of : ".(int) $this->Commande_model->get_amout_achat_daily_commande($daily_date,4)." RAND \n\n\n\n";


			$message.="*For Aem S.A.R.L :*\n";
			$message.="Total number of orders   : ".count($this->Commande_model->get_daily_commande($daily_date,5) )."\n";
			$message.="For a sale amount of     : ".(int) $this->Commande_model->get_amout_daily_commande($daily_date,5)."$\n";
			$message.="For a purchase amount of : ".(int) $this->Commande_model->get_amout_achat_daily_commande($daily_date,5)."$ \n\n\n\n";


			$message.="*For Voltat Projet :*\n";
			$message.="Total number of orders   : ".count($this->Commande_model->get_daily_commande($daily_date,3) )."\n";
			$message.="For a sale amount of     : ".(int) $this->Commande_model->get_amout_daily_commande($daily_date,3)."$ \n";
			$message.="For a purchase amount of : ".(int) $this->Commande_model->get_amout_achat_daily_commande($daily_date,3)."$ \n\n\n\n";

			$message.="*For Amazon :*\n";
			$message.="Total number of orders   : ".count($this->Commande_model->get_daily_commande($daily_date,6) )."\n";
			$message.="For a sale amount of     : ".(int) $this->Commande_model->get_amout_daily_commande($daily_date,6)."$\n";
			$message.="For a purchase amount of : ".(int) $this->Commande_model->get_amout_achat_daily_commande($daily_date,6)."$ \n\n\n\n";

			$message.="*For Wem trading ltd :*\n";
			$message.="Total number of orders   : ".count($this->Commande_model->get_daily_commande($daily_date,10) )."\n";
			$message.="For a sale amount of     : ".(int) $this->Commande_model->get_amout_daily_commande($daily_date,10)."$\n";
			$message.="For a purchase amount of : ".(int) $this->Commande_model->get_amout_achat_daily_commande($daily_date,10)."$ \n\n\n\n";

			$message.="*For Wem R.D.C :*\n";
			$message.="Total number of orders   : ".count($this->Commande_model->get_daily_commande($daily_date,11) )."\n";
			$message.="For a sale amount of     : ".(int) $this->Commande_model->get_amout_daily_commande($daily_date,11)."$\n";
			$message.="For a purchase amount of : ".(int) $this->Commande_model->get_amout_achat_daily_commande($daily_date,11)."$ \n\n\n\n";

			$message.="*For more details login to system here : http://epiphaniemanagement.hstn.me/ep_man/*";
			
			
			$curl = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://chat-api.phphive.info/message/send/text',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS =>json_encode(array("jid"=> $number."@s.whatsapp.net", "message" => $message)),
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer '.$chatApiToken,
				'Content-Type: application/json'
			),
			));
			
			$response = curl_exec($curl);
			curl_close($curl);
			echoTab($response);
			
		}else {
			redirect("Starter/login");
		}
	
	}


	
}
