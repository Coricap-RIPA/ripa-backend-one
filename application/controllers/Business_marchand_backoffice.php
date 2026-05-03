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
        $user = $this->session->userdata('user');
        $id_role = isset($user['id_role']) ? (int) $user['id_role'] : 0;
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
            'can_editer' => check_privilege(self::FONCTIONNALITE, $id_role, 'editer'),
            'can_supprimer' => check_privilege(self::FONCTIONNALITE, $id_role, 'supprimer'),
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
        if (!in_array($m['statut'], array('en_attente_validation', 'refuse'), true)) {
            $this->session->set_flashdata('message', 'Cette demande ne peut pas être validée dans son état actuel.');
            redirect('Business_marchand_backoffice/index');
            return;
        }
        $user_bo = $this->session->userdata('user');
        $id_validateur = isset($user_bo['id_utilisateur']) ? (int) $user_bo['id_utilisateur'] : null;
        $default_pw = trim((string) $this->input->post('default_password'));
        if ($default_pw !== '' && strlen($default_pw) < 8) {
            $this->session->set_flashdata('message', 'Le mot de passe provisoire doit contenir au moins 8 caractères.');
            redirect('Business_marchand_backoffice/index');
            return;
        }
        if ($default_pw === '') {
            $default_pw = $this->config->item('ripa_business_default_password', 'business');
            if ($default_pw === null || $default_pw === '') {
                $default_pw = $this->_generate_temp_password();
            }
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
            $this->_notify_business_email(
                $m['email_contact'],
                'Compte marchand activé',
                "Bonjour,\n\nVotre compte marchand RIPA a été activé.\n\nIdentifiant: " . $m['email_contact'] . "\nMot de passe provisoire: " . $default_pw . "\n\nPour des raisons de sécurité, vous devez changer ce mot de passe à la première connexion.\n\nPortail: " . site_url('business/connexion') . "\n"
            );
            $this->session->set_flashdata('message', 'Compte marchand activé. Compte portail créé pour l’email professionnel enregistré. Communiquez le mot de passe provisoire selon la procédure interne RIPA ; le marchand devra le changer à la première connexion.');
        }
        redirect('Business_marchand_backoffice/index?statut=actif');
    }

    /**
     * Modifier une fiche marchand (refusé, actif, suspendu).
     */
    public function edit($id) {
        if ($this->_check_privilege_or_redirect('editer') === false) {
            return;
        }
        $id = (int) $id;
        $m = $this->business_marchand_model->get_by_id($id);
        if (!$m || !in_array($m['statut'], array('refuse', 'actif', 'suspendu'), true)) {
            $this->session->set_flashdata('message', 'Fiche introuvable ou non modifiable pour ce statut.');
            redirect('Business_marchand_backoffice/index');
            return;
        }
        $crud = new grocery_CRUD();
        $crud->set_table('sexe');
        $output = $crud->render();
        $header = $this->load->view('layouts/header', array('css_files' => $output->css_files), true);
        $navbar = $this->load->view('layouts/menu_nav_bar', array('business_marchand_link_active' => 'link_menu_active'), true);
        $footer = $this->load->view('layouts/footer', array('js_files' => $output->js_files), true);
        $this->load->view('business_backoffice/marchand_refuse_edit', array(
            'header' => $header,
            'navbar' => $navbar,
            'footer' => $footer,
            'marchand' => $m,
            'output' => $output->output,
        ));
    }

    public function update_refuse($id) {
        if ($this->_check_privilege_or_redirect('editer') === false) {
            return;
        }
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
            return;
        }
        if (!ripa_bo_csrf_verify()) {
            $this->session->set_flashdata('message', 'Jeton de sécurité invalide. Rechargez la page.');
            redirect('Business_marchand_backoffice/index?statut=refuse');
            return;
        }
        $id = (int) $id;
        $m = $this->business_marchand_model->get_by_id($id);
        if (!$m || !in_array($m['statut'], array('refuse', 'actif', 'suspendu'), true)) {
            $this->session->set_flashdata('message', 'Fiche introuvable ou non modifiable.');
            redirect('Business_marchand_backoffice/index');
            return;
        }
        $this->form_validation->set_rules('raison_sociale', 'Raison sociale', 'required|min_length[2]|max_length[255]');
        $this->form_validation->set_rules('email_contact', 'Email', 'required|valid_email|max_length[255]');
        $this->form_validation->set_rules('telephone_contact', 'Téléphone', 'trim|max_length[50]');
        $this->form_validation->set_rules('identifiant_legal', 'Identifiant légal', 'trim|max_length[100]');
        $new_password = trim((string) $this->input->post('new_password'));
        if ($new_password !== '' && strlen($new_password) < 8) {
            $this->session->set_flashdata('message', 'Le nouveau mot de passe doit contenir au moins 8 caractères.');
            redirect('Business_marchand_backoffice/edit/' . $id);
            return;
        }
        if (!$this->form_validation->run()) {
            $crud = new grocery_CRUD();
            $crud->set_table('sexe');
            $output = $crud->render();
            $header = $this->load->view('layouts/header', array('css_files' => $output->css_files), true);
            $navbar = $this->load->view('layouts/menu_nav_bar', array('business_marchand_link_active' => 'link_menu_active'), true);
            $footer = $this->load->view('layouts/footer', array('js_files' => $output->js_files), true);
            $this->load->view('business_backoffice/marchand_refuse_edit', array(
                'header' => $header,
                'navbar' => $navbar,
                'footer' => $footer,
                'marchand' => $m,
                'output' => $output->output,
                'validation_errors' => validation_errors(),
            ));
            return;
        }
        $email_upd = strtolower(trim((string) $this->security->xss_clean($this->input->post('email_contact'))));
        $tel_upd = trim((string) $this->security->xss_clean($this->input->post('telephone_contact')));
        if ($this->business_marchand_model->exists_by_email($email_upd, $id)) {
            $this->session->set_flashdata('message', 'Cet email est déjà utilisé pour un autre marchand.');
            redirect('Business_marchand_backoffice/edit/' . $id);
            return;
        }
        if ($tel_upd !== '' && $this->business_marchand_model->exists_by_phone($tel_upd, $id)) {
            $this->session->set_flashdata('message', 'Ce téléphone est déjà utilisé pour un autre marchand.');
            redirect('Business_marchand_backoffice/edit/' . $id);
            return;
        }
        $notes = $this->security->xss_clean($this->input->post('notes_internes'));
        $this->business_marchand_model->update_row($id, array(
            'raison_sociale' => $this->security->xss_clean($this->input->post('raison_sociale')),
            'email_contact' => $email_upd,
            'telephone_contact' => $tel_upd !== '' ? $tel_upd : null,
            'identifiant_legal' => $this->security->xss_clean($this->input->post('identifiant_legal')) ?: null,
            'notes_internes' => $notes !== '' ? $notes : null,
        ));
        // Si un compte administrateur portail existe, on aligne l'email et/ou le mot de passe.
        $admin = $this->ub_model->get_admin_by_marchand($id);
        if (!empty($admin)) {
            $ub_update = array();
            if (isset($admin['email']) && strtolower((string) $admin['email']) !== $email_upd) {
                $ub_update['email'] = $email_upd;
            }
            if ($new_password !== '') {
                $ub_update['mot_de_passe'] = password_hash($new_password, PASSWORD_BCRYPT);
                $ub_update['doit_changer_mot_de_passe'] = 1;
            }
            if (!empty($ub_update)) {
                $this->ub_model->update_row((int) $admin['id'], $ub_update);
            }
        }
        $this->session->set_flashdata('message', 'Fiche marchand mise à jour.');
        redirect('Business_marchand_backoffice/edit/' . $id);
    }

    /**
     * Supprimer définitivement une demande refusée (pas de compte actif).
     */
    public function supprimer($id) {
        if ($this->_check_privilege_or_redirect('supprimer') === false) {
            return;
        }
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
            return;
        }
        if (!ripa_bo_csrf_verify()) {
            $this->session->set_flashdata('message', 'Jeton de sécurité invalide. Rechargez la page.');
            redirect('Business_marchand_backoffice/index?statut=refuse');
            return;
        }
        $id = (int) $id;
        $m = $this->business_marchand_model->get_by_id($id);
        if (!$m || $m['statut'] !== 'refuse') {
            $this->session->set_flashdata('message', 'Suppression impossible : demande introuvable ou statut non « refusé ».');
            redirect('Business_marchand_backoffice/index?statut=refuse');
            return;
        }
        $cnt_ub = (int) $this->db->where('id_marchand', $id)->count_all_results('utilisateur_business');
        if ($cnt_ub > 0) {
            $this->session->set_flashdata('message', 'Suppression refusée : des comptes portail sont déjà liés à ce marchand. Contactez l’équipe technique.');
            redirect('Business_marchand_backoffice/index?statut=refuse');
            return;
        }
        $this->business_marchand_model->delete_row($id);
        $this->session->set_flashdata('message', 'Demande refusée supprimée.');
        redirect('Business_marchand_backoffice/index?statut=refuse');
    }

    /**
     * Bloquer un compte marchand actif (statut suspendu).
     */
    public function bloquer($id) {
        if ($this->_check_privilege_or_redirect('editer') === false) {
            return;
        }
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
            return;
        }
        if (!ripa_bo_csrf_verify()) {
            $this->session->set_flashdata('message', 'Jeton de sécurité invalide. Rechargez la page.');
            redirect('Business_marchand_backoffice/index?statut=actif');
            return;
        }
        $id = (int) $id;
        $m = $this->business_marchand_model->get_by_id($id);
        if (!$m || $m['statut'] !== 'actif') {
            $this->session->set_flashdata('message', 'Blocage impossible : marchand introuvable ou non actif.');
            redirect('Business_marchand_backoffice/index?statut=actif');
            return;
        }
        $user_bo = $this->session->userdata('user');
        $id_validateur = isset($user_bo['id_utilisateur']) ? (int) $user_bo['id_utilisateur'] : null;
        $this->business_marchand_model->update_row($id, array(
            'statut' => 'suspendu',
            'date_decision' => date('Y-m-d H:i:s'),
            'id_utilisateur_validateur' => $id_validateur,
        ));
        $this->_notify_business_email(
            $m['email_contact'],
            'Compte marchand bloqué',
            "Bonjour,\n\nVotre compte marchand RIPA a été bloqué (statut suspendu).\n\nSi vous pensez qu’il s’agit d’une erreur, contactez le support RIPA.\n"
        );
        $this->session->set_flashdata('message', 'Compte marchand bloqué (statut suspendu).');
        redirect('Business_marchand_backoffice/index?statut=suspendu');
    }

    /**
     * Réactiver un compte marchand suspendu (retour au statut actif).
     */
    public function debloquer($id) {
        if ($this->_check_privilege_or_redirect('editer') === false) {
            return;
        }
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
            return;
        }
        if (!ripa_bo_csrf_verify()) {
            $this->session->set_flashdata('message', 'Jeton de sécurité invalide. Rechargez la page.');
            redirect('Business_marchand_backoffice/index?statut=suspendu');
            return;
        }
        $id = (int) $id;
        $m = $this->business_marchand_model->get_by_id($id);
        if (!$m || $m['statut'] !== 'suspendu') {
            $this->session->set_flashdata('message', 'Réactivation impossible : marchand introuvable ou non suspendu.');
            redirect('Business_marchand_backoffice/index?statut=suspendu');
            return;
        }
        $user_bo = $this->session->userdata('user');
        $id_validateur = isset($user_bo['id_utilisateur']) ? (int) $user_bo['id_utilisateur'] : null;
        $this->business_marchand_model->update_row($id, array(
            'statut' => 'actif',
            'date_decision' => date('Y-m-d H:i:s'),
            'id_utilisateur_validateur' => $id_validateur,
        ));
        $this->_notify_business_email(
            $m['email_contact'],
            'Compte marchand réactivé',
            "Bonjour,\n\nVotre compte marchand RIPA a été réactivé.\n\nVous pouvez à nouveau accéder au portail.\n"
        );
        $this->session->set_flashdata('message', 'Compte marchand réactivé (statut actif).');
        redirect('Business_marchand_backoffice/index?statut=actif');
    }

    /**
     * Supprimer un compte marchand actif (si aucune dépendance critique).
     */
    public function supprimer_actif($id) {
        if ($this->_check_privilege_or_redirect('supprimer') === false) {
            return;
        }
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
            return;
        }
        if (!ripa_bo_csrf_verify()) {
            $this->session->set_flashdata('message', 'Jeton de sécurité invalide. Rechargez la page.');
            redirect('Business_marchand_backoffice/index?statut=actif');
            return;
        }
        $id = (int) $id;
        $m = $this->business_marchand_model->get_by_id($id);
        if (!$m || $m['statut'] !== 'suspendu') {
            $this->session->set_flashdata('message', 'Suppression impossible : seul un compte suspendu peut être supprimé.');
            redirect('Business_marchand_backoffice/index?statut=actif');
            return;
        }
        // On supprime d'abord les comptes portail liés pour éviter le blocage systématique.
        $this->db->where('id_marchand', $id)->delete('utilisateur_business');
        $deps = array(
            'business_service' => (int) $this->db->where('id_marchand', $id)->count_all_results('business_service'),
            'business_employe' => (int) $this->db->where('id_marchand', $id)->count_all_results('business_employe'),
            'business_transaction' => (int) $this->db->where('id_marchand', $id)->count_all_results('business_transaction'),
            'business_kyb_dossier' => (int) $this->db->where('id_marchand', $id)->count_all_results('business_kyb_dossier'),
        );
        foreach ($deps as $k => $v) {
            if ($v > 0) {
                $this->session->set_flashdata('message', 'Suppression refusée : dépendances trouvées dans ' . $k . '.');
                redirect('Business_marchand_backoffice/index?statut=' . $m['statut']);
                return;
            }
        }
        $this->business_marchand_model->delete_row($id);
        $this->_notify_business_email(
            $m['email_contact'],
            'Compte marchand supprimé',
            "Bonjour,\n\nVotre compte marchand RIPA a été supprimé par l’administration.\n\nPour toute contestation, contactez le support RIPA.\n"
        );
        $this->session->set_flashdata('message', 'Compte marchand supprimé.');
        redirect('Business_marchand_backoffice/index?statut=' . $m['statut']);
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
        $this->_notify_business_email(
            $m['email_contact'],
            'Demande marchand refusée',
            "Bonjour,\n\nVotre demande de compte marchand a été refusée.\n\nMotif: " . trim((string) $motif) . "\n\nVous pouvez soumettre une nouvelle demande en corrigeant ces éléments.\n"
        );
        $this->session->set_flashdata('message', 'Demande refusée.');
        redirect('Business_marchand_backoffice/index?statut=refuse');
    }

    private function _generate_temp_password() {
        try {
            return 'Ripa#' . substr(bin2hex(random_bytes(6)), 0, 8) . '9';
        } catch (Throwable $e) {
            return 'Ripa#2026Tmp9';
        }
    }

    /**
     * Notification simple par email (best-effort).
     */
    private function _notify_business_email($to, $subject, $body) {
        $to = trim((string) $to);
        if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/plain; charset=UTF-8\r\n";
        $headers .= "From: RIPA <no-reply@ripa.local>\r\n";
        @mail($to, $subject, $body, $headers);
        return true;
    }
}
