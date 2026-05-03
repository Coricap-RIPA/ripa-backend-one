<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Contrôleurs portail marchand (sous-dossier business/) — session + CSRF POST + rôles
 */
class MY_Business_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    /** Session portail marchand valide */
    protected function require_business_login() {
        if (!$this->session->userdata('business_logged_in') || !$this->session->userdata('business_ub_id')) {
            redirect('business/connexion');
            exit;
        }
        if ($this->session->userdata('business_must_change_password')) {
            redirect('business/premier-mot-de-passe');
            exit;
        }
    }

    /** Vérifie jeton CSRF pour les POST (helper custom_helper) */
    protected function verify_portal_csrf_post() {
        if ($this->input->method(TRUE) !== 'POST') {
            return true;
        }
        if (!ripa_portal_csrf_verify()) {
            show_error('Session expirée ou jeton de sécurité invalide. Rechargez la page.', 403);
            exit;
        }
        return true;
    }

    /** Rôles autorisés : administrateur, gestionnaire, lecteur_seul */
    protected function require_business_roles(array $allowed_roles) {
        $this->require_business_login();
        $role = (string) $this->session->userdata('business_role');
        if (!in_array($role, $allowed_roles, true)) {
            show_error('Accès refusé pour votre profil.', 403);
            exit;
        }
    }
}
