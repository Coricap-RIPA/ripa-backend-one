<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Authentification portail marchand (session dédiée, distincte du backoffice RIPA)
 * Modèles : autoload (business_marchand_model, ub_model)
 */
class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->config('business', true);
        $this->load->library('JWT_Library');
    }

    public function login($error = '') {
        if ($this->_session_business_ok()) {
            redirect('business');
            return;
        }
        $this->load->view('business/auth/login', array('error' => $error));
    }

    public function login_submit() {
        if (!ripa_portal_csrf_verify()) {
            $this->login('Jeton de sécurité invalide ou session expirée. Rechargez la page.');
            return;
        }
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Mot de passe', 'required');
        if (!$this->form_validation->run()) {
            $this->login('Veuillez remplir tous les champs.');
            return;
        }
        $email = strtolower(trim($this->security->xss_clean($this->input->post('email'))));
        $password = $this->input->post('password');
        $row = $this->ub_model->get_by_email($email);
        if (empty($row) || empty($row['actif'])) {
            $this->login('Email ou mot de passe incorrect.');
            return;
        }
        if (!password_verify($password, $row['mot_de_passe'])) {
            $this->login('Email ou mot de passe incorrect.');
            return;
        }
        $marchand = $this->business_marchand_model->get_by_id($row['id_marchand']);
        if (empty($marchand) || $marchand['statut'] !== 'actif') {
            $this->login('Votre compte entreprise n’est pas actif. Contactez RIPA.');
            return;
        }
        $this->ub_model->update_row($row['id'], array('derniere_connexion' => date('Y-m-d H:i:s')));
        $token = $this->jwt_library->encode(array(
            'ub_id' => (int) $row['id'],
            'id_marchand' => (int) $row['id_marchand'],
            'role' => (string) $row['role'],
            'exp' => time() + (7 * 24 * 60 * 60),
            'iat' => time(),
        ));
        $this->session->set_userdata(array(
            'business_ub_id' => (int) $row['id'],
            'business_marchand_id' => (int) $row['id_marchand'],
            'business_email' => $row['email'],
            'business_role' => $row['role'],
            'business_must_change_password' => !empty($row['doit_changer_mot_de_passe']),
            'business_logged_in' => true,
            'business_api_jwt' => $token,
        ));
        if (!empty($row['doit_changer_mot_de_passe'])) {
            redirect('business/premier-mot-de-passe');
            return;
        }
        redirect('business');
    }

    public function first_password() {
        if (!$this->_session_business_ok()) {
            redirect('business/connexion');
            return;
        }
        if (!$this->session->userdata('business_must_change_password')) {
            redirect('business');
            return;
        }
        $this->load->view('business/auth/first_password', array('error' => ''));
    }

    public function first_password_submit() {
        if (!$this->_session_business_ok()) {
            redirect('business/connexion');
            return;
        }
        if (!$this->session->userdata('business_must_change_password')) {
            redirect('business');
            return;
        }
        if (!ripa_portal_csrf_verify()) {
            $this->load->view('business/auth/first_password', array('error' => 'Jeton de sécurité invalide. Rechargez la page.'));
            return;
        }
        $this->form_validation->set_rules('password', 'Nouveau mot de passe', 'required|min_length[8]');
        $this->form_validation->set_rules('password_confirm', 'Confirmation', 'required|matches[password]');
        if (!$this->form_validation->run()) {
            $this->load->view('business/auth/first_password', array('error' => validation_errors()));
            return;
        }
        $new = $this->input->post('password');
        $id = (int) $this->session->userdata('business_ub_id');
        $hash = password_hash($new, PASSWORD_BCRYPT);
        $this->ub_model->update_row($id, array(
            'mot_de_passe' => $hash,
            'doit_changer_mot_de_passe' => 0,
        ));
        $this->session->set_userdata('business_must_change_password', false);
        $this->session->set_flashdata('message', 'Mot de passe mis à jour. Bienvenue sur le portail marchand.');
        redirect('business');
    }

    public function logout() {
        $this->session->unset_userdata(array(
            'business_ub_id', 'business_marchand_id', 'business_email', 'business_role',
            'business_must_change_password', 'business_logged_in', 'business_api_jwt',
        ));
        redirect('business/connexion');
    }

    private function _session_business_ok() {
        return $this->session->userdata('business_logged_in') && $this->session->userdata('business_ub_id');
    }
}
