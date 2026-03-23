<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Back-office RIPA : validation des comptes marchands (portail B2B)
 */
class Business_marchand_backoffice extends CI_Controller {

    const FONCTIONNALITE = 'business_marchand';

    public function __construct() {
        parent::__construct();
        $this->load->config('business', true);
    }

    private function _check_privilege_or_redirect($operation) {
        if (!$this->session->logged_in) {
            redirect('Starter/login');
            return false;
        }
        $user = $this->session->userdata('user');
        if (empty($user) || !isset($user['id_role'])) {
            redirect('Starter/login');
            return false;
        }
        if (!check_privilege(self::FONCTIONNALITE, (int) $user['id_role'], $operation)) {
            if (!empty($_SERVER['HTTP_REFERER'])) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect('Dashboard/index');
            }
            return false;
        }
        return true;
    }

    public function index() {
        if ($this->_check_privilege_or_redirect('voir') === false) {
            return;
        }
        $statut = $this->input->get('statut');
        $allowed = array('', 'en_attente_validation', 'actif', 'refuse', 'suspendu', 'brouillon');
        if (!in_array((string) $statut, $allowed, true)) {
            $statut = '';
        }
        $list = $this->business_marchand_model->get_all_by_statut($statut === '' ? null : $statut);
        $crud = new grocery_CRUD();
        $crud->set_table('sexe');
        $output = $crud->render();
        $header = $this->load->view('layouts/header', array('css_files' => $output->css_files), true);
        $navbar = $this->load->view('layouts/menu_nav_bar', array('business_marchand_link_active' => 'link_menu_active'), true);
        $footer = $this->load->view('layouts/footer', array('js_files' => $output->js_files), true);
        $this->load->view('business_backoffice/list', array(
            'header' => $header,
            'navbar' => $navbar,
            'footer' => $footer,
            'list' => $list,
            'filter_statut' => $statut,
            'output' => $output->output,
        ));
    }

    /**
     * Valider une demande : statut actif + création utilisateur_business administrateur
     */
    public function valider($id) {
        if ($this->_check_privilege_or_redirect('editer') === false) {
            return;
        }
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
            return;
        }
        if (!ripa_bo_csrf_verify()) {
            $this->session->set_flashdata('message', 'Jeton de sécurité invalide. Rechargez la page.');
            redirect('Business_marchand_backoffice/index');
            return;
        }
        $id = (int) $id;
        $m = $this->business_marchand_model->get_by_id($id);
        if (!$m) {
            $this->session->set_flashdata('message', 'Marchand introuvable.');
            redirect('Business_marchand_backoffice/index');
            return;
        }
        if ($m['statut'] !== 'en_attente_validation') {
            $this->session->set_flashdata('message', 'Cette demande n’est pas en attente de validation.');
            redirect('Business_marchand_backoffice/index');
            return;
        }
        $user_bo = $this->session->userdata('user');
        $id_validateur = isset($user_bo['id_utilisateur']) ? (int) $user_bo['id_utilisateur'] : null;
        $default_pw = $this->config->item('ripa_business_default_password', 'business');
        if ($default_pw === null || $default_pw === '') {
            $default_pw = '123456';
        }
        $this->db->trans_begin();
        $ok_ub = $this->ub_model->create_administrateur($id, $m['email_contact'], $default_pw);
        if (!$ok_ub['ok']) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', $ok_ub['message']);
            redirect('Business_marchand_backoffice/index');
            return;
        }
        $this->business_marchand_model->update_row($id, array(
            'statut' => 'actif',
            'date_decision' => date('Y-m-d H:i:s'),
            'id_utilisateur_validateur' => $id_validateur,
            'motif_refus' => null,
        ));
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', 'Erreur lors de la validation.');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('message', 'Compte marchand activé. Compte portail créé pour l’email professionnel enregistré. Communiquez le mot de passe provisoire selon la procédure interne RIPA ; le marchand devra le changer à la première connexion.');
        }
        redirect('Business_marchand_backoffice/index?statut=actif');
    }

    public function refuser() {
        if ($this->_check_privilege_or_redirect('editer') === false) {
            return;
        }
        if (!ripa_bo_csrf_verify()) {
            $this->session->set_flashdata('message', 'Jeton de sécurité invalide. Rechargez la page.');
            redirect('Business_marchand_backoffice/index');
            return;
        }
        $id = (int) $this->input->post('id_marchand');
        $motif = $this->security->xss_clean($this->input->post('motif_refus'));
        if ($id < 1 || trim((string) $motif) === '') {
            $this->session->set_flashdata('message', 'Motif de refus requis.');
            redirect('Business_marchand_backoffice/index');
            return;
        }
        $m = $this->business_marchand_model->get_by_id($id);
        if (!$m || $m['statut'] !== 'en_attente_validation') {
            $this->session->set_flashdata('message', 'Demande invalide.');
            redirect('Business_marchand_backoffice/index');
            return;
        }
        $user_bo = $this->session->userdata('user');
        $id_validateur = isset($user_bo['id_utilisateur']) ? (int) $user_bo['id_utilisateur'] : null;
        $this->business_marchand_model->update_row($id, array(
            'statut' => 'refuse',
            'date_decision' => date('Y-m-d H:i:s'),
            'id_utilisateur_validateur' => $id_validateur,
            'motif_refus' => $motif,
        ));
        $this->session->set_flashdata('message', 'Demande refusée.');
        redirect('Business_marchand_backoffice/index?statut=refuse');
    }
}
