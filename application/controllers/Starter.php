<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Starter extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function index($error = '')
	{
		$this->load->view('login/index',['error'=>$error]);
	}


	function login($error = ''){
		$this->load->view('login/index',['error'=>$error]);
	}

	function check_connexion(){

		$this->form_validation->set_rules("email", "Adresse email", "required", array(
			"required" => "Please introduce your email"
		));
		$this->form_validation->set_rules("password", "Mot de passe", "required", array(
			"required" => "Please introduce your password"
		));
		
		if($this->form_validation->run()){
			
			$user = $this->Utilisateur_model->check_connexion($this->security->xss_clean($this->input->post("email")));
			
			if(!empty($user)){
				if(password_verify($this->security->xss_clean($this->input->post("password")), $user['password'])){
					$role = $this->Role_model->get_role($user['id_role']);
					$_SESSION['user'] = $user;
					$_SESSION['role'] = $role;
					$_SESSION['logged_in'] = true;

					$action = [
						"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
						"nom_table"=> 'null',
						"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
						"id_champ"=> null,
						"action" => "loggin",
						"text_descriptif" => "loggin to system",
						"date_heure" => date("Y-m-d H:m:i")
					];
					$this->Action_utilisateur_model->add( $action );
					redirect("Dashboard");
					
				} else {
					$this->login("Email ou mot de passe incorrect");
				}
			} else {
				$this->login("Email ou mot de passe incorrect");
			}
		} else {
			$this->login();
		}
	}

	public function disconnect(){

		if (!empty($_SESSION['user'])) {
			$action = [
				"id_utilisateur"=> $_SESSION['user']['id_utilisateur'],
				"nom_table"=> 'null',
				"id_entreprise_cliente"=>$_SESSION['user']['id_entreprise_utilisateur'],
				"id_champ"=> null,
				"action" => "loggout",
				"text_descriptif" => "loggout from system",
				"date_heure" => date("Y-m-d H:m:i")
			];
			$this->Action_utilisateur_model->add( $action );
	
			session_destroy();
		}
		
		redirect('Starter/index');
	}

}
