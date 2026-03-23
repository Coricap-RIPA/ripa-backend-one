<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Demande de compte marchand (formulaire public)
 * Modèle : business_marchand_model (autoload)
 */
class Inscription extends CI_Controller {

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
        if (!$this->form_validation->run()) {
            $this->index(validation_errors(), false);
            return;
        }
        $rs = $this->security->xss_clean($this->input->post('raison_sociale'));
        $em = $this->security->xss_clean($this->input->post('email_contact'));
        $tel = $this->security->xss_clean($this->input->post('telephone_contact'));
        $id = $this->business_marchand_model->create_demande($rs, $em, $tel, null);
        if ($id) {
            $this->index('Votre demande a été enregistrée. L’équipe RIPA la traitera sous peu.', true);
        } else {
            $this->index('Erreur lors de l’enregistrement. Réessayez plus tard.', false);
        }
    }
}
