<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Demande de compte marchand (formulaire public)
 * Modèle : business_marchand_model (autoload)
 */
class Inscription extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('Business_api_client');
    }

    public function index($message = '', $success = false) {
        $this->load->view('business/inscription/form', array(
            'message' => $message,
            'success' => $success,
        ));
    }

    public function submit() {
        if (!ripa_portal_csrf_verify()) {
            $this->index('Jeton de sécurité invalide ou session expirée. Rechargez la page.', false);
            return;
        }
        $this->form_validation->set_rules('raison_sociale', 'Raison sociale', 'required|min_length[2]');
        $this->form_validation->set_rules('email_contact', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('telephone_contact', 'Téléphone', 'required');
        $this->form_validation->set_rules('identifiant_legal', 'RCCM / identifiant légal', 'trim|max_length[100]');
        if (!$this->form_validation->run()) {
            $this->index(validation_errors(), false);
            return;
        }
        $rs = $this->security->xss_clean($this->input->post('raison_sociale'));
        $em = $this->security->xss_clean($this->input->post('email_contact'));
        $tel = $this->security->xss_clean($this->input->post('telephone_contact'));
        $rccm = $this->security->xss_clean($this->input->post('identifiant_legal')) ?: null;
        $api = $this->business_api_client->post('register', array(
            'raison_sociale' => $rs,
            'email_contact' => $em,
            'telephone_contact' => $tel,
            'identifiant_legal' => $rccm,
        ));
        if (!empty($api['ok'])) {
            $this->index('Votre demande a été enregistrée. L’équipe RIPA la traitera sous peu.', true);
        } else {
            $msg = !empty($api['error']) ? (string) $api['error'] : 'Erreur lors de l’enregistrement. Réessayez plus tard.';
            $this->index($msg, false);
        }
    }
}
