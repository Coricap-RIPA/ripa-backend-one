<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Back-office RIPA : validation des dossiers KYB (portail B2B)
 */
class Kyb_backoffice extends CI_Controller {

    const FONCTIONNALITE = 'business_kyb';

    public function __construct() {
        parent::__construct();
        $this->load->helper('ripa_mail');
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
        $allowed = array('', 'en_attente', 'valide', 'rejete');
        if (!in_array((string) $statut, $allowed, true)) {
            $statut = '';
        }
        $list = $this->business_kyb_model->list_with_marchand($statut === '' ? null : $statut);
        $crud = new grocery_CRUD();
        $crud->set_table('sexe');
        $output = $crud->render();
        $header = $this->load->view('layouts/header', array('css_files' => $output->css_files), true);
        $navbar = $this->load->view('layouts/menu_nav_bar', array('business_kyb_link_active' => 'link_menu_active'), true);
        $footer = $this->load->view('layouts/footer', array('js_files' => $output->js_files), true);
        $this->load->view('business_backoffice/kyb_list', array(
            'header' => $header,
            'navbar' => $navbar,
            'footer' => $footer,
            'list' => $list,
            'filter_statut' => $statut,
            'output' => $output->output,
        ));
    }

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
            redirect('Kyb_backoffice/index');
            return;
        }
        $id = (int) $id;
        $row = $this->business_kyb_model->get_by_id($id);
        if (!$row || $row['statut'] !== 'en_attente') {
            $this->session->set_flashdata('message', 'Dossier introuvable ou déjà traité.');
            redirect('Kyb_backoffice/index');
            return;
        }
        $user_bo = $this->session->userdata('user');
        $id_validateur = isset($user_bo['id_utilisateur']) ? (int) $user_bo['id_utilisateur'] : null;
        $this->business_kyb_model->update_by_id($id, array(
            'statut' => 'valide',
            'date_decision' => date('Y-m-d H:i:s'),
            'date_fin_validite' => date('Y-m-d H:i:s', strtotime('+1 year')),
            'id_utilisateur_validateur' => $id_validateur,
            'motif_refus' => null,
        ));
        $this->_notify_marchand_kyb_validated($row);
        $this->session->set_flashdata('message', 'Dossier KYB validé. Un e-mail a été envoyé au contact du dossier.');
        redirect('Kyb_backoffice/index?statut=valide');
    }

    public function refuser() {
        if ($this->_check_privilege_or_redirect('editer') === false) {
            return;
        }
        if (!ripa_bo_csrf_verify()) {
            $this->session->set_flashdata('message', 'Jeton de sécurité invalide. Rechargez la page.');
            redirect('Kyb_backoffice/index');
            return;
        }
        $id = (int) $this->input->post('id_kyb');
        $motif = $this->security->xss_clean($this->input->post('motif_refus'));
        if ($id < 1 || trim((string) $motif) === '') {
            $this->session->set_flashdata('message', 'Motif de refus requis.');
            redirect('Kyb_backoffice/index');
            return;
        }
        $row = $this->business_kyb_model->get_by_id($id);
        if (!$row || $row['statut'] !== 'en_attente') {
            $this->session->set_flashdata('message', 'Dossier invalide.');
            redirect('Kyb_backoffice/index');
            return;
        }
        $user_bo = $this->session->userdata('user');
        $id_validateur = isset($user_bo['id_utilisateur']) ? (int) $user_bo['id_utilisateur'] : null;
        $this->business_kyb_model->update_by_id($id, array(
            'statut' => 'rejete',
            'date_decision' => date('Y-m-d H:i:s'),
            'id_utilisateur_validateur' => $id_validateur,
            'motif_refus' => $motif,
        ));
        $this->_notify_marchand_kyb_rejected($row, $motif);
        $this->session->set_flashdata('message', 'Dossier KYB refusé. Un e-mail a été envoyé au contact du dossier.');
        redirect('Kyb_backoffice/index?statut=rejete');
    }

    /**
     * Fiche dossier KYB : consultation ; modification réservée au back-office (dossiers validés ou en attente).
     */
    public function fiche($id) {
        if ($this->_check_privilege_or_redirect('voir') === false) {
            return;
        }
        $id = (int) $id;
        $row = $this->business_kyb_model->get_by_id($id);
        if (!$row) {
            $this->session->set_flashdata('message', 'Dossier introuvable.');
            redirect('Kyb_backoffice/index');
            return;
        }
        $user = $this->session->userdata('user');
        $id_role = isset($user['id_role']) ? (int) $user['id_role'] : 0;
        $can_edit = check_privilege(self::FONCTIONNALITE, $id_role, 'editer')
            && in_array($row['statut'], array('valide', 'en_attente'), true);
        $crud = new grocery_CRUD();
        $crud->set_table('sexe');
        $output = $crud->render();
        $header = $this->load->view('layouts/header', array('css_files' => $output->css_files), true);
        $navbar = $this->load->view('layouts/menu_nav_bar', array('business_kyb_link_active' => 'link_menu_active'), true);
        $footer = $this->load->view('layouts/footer', array('js_files' => $output->js_files), true);
        $this->load->view('business_backoffice/kyb_fiche', array(
            'header' => $header,
            'navbar' => $navbar,
            'footer' => $footer,
            'dossier' => $row,
            'can_edit' => $can_edit,
            'date_fin_validite' => $this->business_kyb_model->get_date_fin_validite($row),
            'output' => $output->output,
            'kyb_preview_legal_url' => $this->_kyb_bo_piece_url($row, 'legal'),
            'kyb_preview_complement_url' => $this->_kyb_bo_piece_url($row, 'complement'),
            'kyb_preview_legal_kind' => ripa_kyb_storage_file_kind(isset($row['fichier_piece_legal']) ? (string) $row['fichier_piece_legal'] : ''),
            'kyb_preview_complement_kind' => ripa_kyb_storage_file_kind(isset($row['fichier_piece_complement']) ? (string) $row['fichier_piece_complement'] : ''),
        ));
    }

    /**
     * Sert une pièce jointe KYB (PDF / image) après contrôle d’accès back-office.
     * GET Kyb_backoffice/fichier/{id}/{legal|complement}
     */
    public function fichier($id, $slot = 'legal') {
        if ($this->_check_privilege_or_redirect('voir') === false) {
            return;
        }
        $id = (int) $id;
        $slot = strtolower(trim((string) $slot));
        if (!in_array($slot, array('legal', 'complement'), true)) {
            show_404();
            return;
        }
        $row = $this->business_kyb_model->get_by_id($id);
        if (!$row) {
            show_404();
            return;
        }
        $col = ($slot === 'complement') ? 'fichier_piece_complement' : 'fichier_piece_legal';
        $rel = isset($row[$col]) ? trim((string) $row[$col]) : '';
        if ($rel === '') {
            show_404();
            return;
        }
        $full = ripa_kyb_storage_resolve_full_path($rel, (int) $row['id_marchand']);
        if ($full === null || !is_file($full)) {
            show_404();
            return;
        }
        $ext = strtolower(pathinfo($full, PATHINFO_EXTENSION));
        $mime = 'application/octet-stream';
        if ($ext === 'pdf') {
            $mime = 'application/pdf';
        } elseif (in_array($ext, array('jpg', 'jpeg'), true)) {
            $mime = 'image/jpeg';
        } elseif ($ext === 'png') {
            $mime = 'image/png';
        }
        $this->output->set_content_type($mime);
        $this->output->set_header('X-Content-Type-Options: nosniff');
        $this->output->set_header('Content-Disposition: inline; filename="' . basename($full) . '"');
        $this->output->set_output((string) file_get_contents($full));
    }

    /**
     * @param array<string,mixed> $row
     * @return string URL vide si pas de fichier
     */
    private function _kyb_bo_piece_url(array $row, $slot) {
        $col = ($slot === 'complement') ? 'fichier_piece_complement' : 'fichier_piece_legal';
        $rel = isset($row[$col]) ? trim((string) $row[$col]) : '';
        if ($rel === '') {
            return '';
        }
        if (ripa_kyb_storage_resolve_full_path($rel, (int) $row['id_marchand']) === null) {
            return '';
        }
        $seg = ($slot === 'complement') ? 'complement' : 'legal';
        return site_url('Kyb_backoffice/fichier/' . (int) $row['id'] . '/' . $seg);
    }

    /**
     * @param array<string,mixed> $row dossier avant mise à jour
     */
    private function _notify_marchand_kyb_validated(array $row) {
        $to = isset($row['email_contact']) ? trim((string) $row['email_contact']) : '';
        if ($to === '') {
            return;
        }
        $nom = isset($row['denomination_sociale']) ? htmlspecialchars((string) $row['denomination_sociale'], ENT_QUOTES, 'UTF-8') : '';
        $portal = site_url('business/kyb');
        $html = '<p>Bonjour,</p>'
            . '<p>Votre dossier <strong>Know Your Business (KYB)</strong> pour <strong>' . $nom . '</strong> a été <strong>validé</strong> par l’équipe RIPA.</p>'
            . '<p>Vous pouvez consulter le statut sur le portail marchand : <a href="' . htmlspecialchars($portal, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($portal, ENT_QUOTES, 'UTF-8') . '</a></p>'
            . '<p>Cordialement,<br/>RIPA</p>';
        ripa_send_html_mail($to, 'RIPA — Votre dossier KYB a été validé', $html);
    }

    /**
     * @param array<string,mixed> $row dossier avant mise à jour
     */
    private function _notify_marchand_kyb_rejected(array $row, $motif) {
        $to = isset($row['email_contact']) ? trim((string) $row['email_contact']) : '';
        if ($to === '') {
            return;
        }
        $nom = isset($row['denomination_sociale']) ? htmlspecialchars((string) $row['denomination_sociale'], ENT_QUOTES, 'UTF-8') : '';
        $motif_h = nl2br(htmlspecialchars((string) $motif, ENT_QUOTES, 'UTF-8'));
        $portal = site_url('business/kyb');
        $html = '<p>Bonjour,</p>'
            . '<p>Votre dossier <strong>KYB</strong> pour <strong>' . $nom . '</strong> nécessite une <strong>correction</strong>.</p>'
            . '<p><strong>Motif indiqué par l’équipe RIPA :</strong></p><blockquote style="border-left:3px solid #7b1fa2;padding-left:12px;margin:12px 0;">' . $motif_h . '</blockquote>'
            . '<p>Vous pouvez mettre à jour votre dossier depuis le portail : <a href="' . htmlspecialchars($portal, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($portal, ENT_QUOTES, 'UTF-8') . '</a></p>'
            . '<p>Cordialement,<br/>RIPA</p>';
        ripa_send_html_mail($to, 'RIPA — Votre dossier KYB nécessite une correction', $html);
    }

    public function update($id) {
        if ($this->_check_privilege_or_redirect('editer') === false) {
            return;
        }
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
            return;
        }
        if (!ripa_bo_csrf_verify()) {
            $this->session->set_flashdata('message', 'Jeton de sécurité invalide. Rechargez la page.');
            redirect('Kyb_backoffice/fiche/' . (int) $id);
            return;
        }
        $id = (int) $id;
        $row = $this->business_kyb_model->get_by_id($id);
        if (!$row || !in_array($row['statut'], array('valide', 'en_attente'), true)) {
            $this->session->set_flashdata('message', 'Mise à jour impossible pour ce dossier.');
            redirect('Kyb_backoffice/index');
            return;
        }
        $this->form_validation->set_rules('denomination_sociale', 'Dénomination sociale', 'required|min_length[2]|max_length[255]');
        $this->form_validation->set_rules('numero_identification_legal', 'N° identification légale', 'required|min_length[2]|max_length[120]');
        $this->form_validation->set_rules('adresse_siege', 'Adresse du siège', 'required|min_length[5]');
        $this->form_validation->set_rules('telephone', 'Téléphone', 'required|min_length[6]|max_length[50]');
        $this->form_validation->set_rules('email_contact', 'Email', 'required|valid_email|max_length[255]');
        $this->form_validation->set_rules('ville', 'Ville', 'trim|max_length[120]');
        $this->form_validation->set_rules('pays', 'Pays', 'trim|max_length[3]');
        $this->form_validation->set_rules('site_web', 'Site web', 'trim|max_length[255]');
        $this->form_validation->set_rules('activite_principale', 'Activité', 'trim|max_length[255]');
        $this->form_validation->set_rules('effectif_tranche', 'Effectif', 'trim|max_length[50]');
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('message', validation_errors());
            redirect('Kyb_backoffice/fiche/' . $id);
            return;
        }
        $data = array(
            'denomination_sociale' => $this->security->xss_clean($this->input->post('denomination_sociale')),
            'numero_identification_legal' => $this->security->xss_clean($this->input->post('numero_identification_legal')),
            'adresse_siege' => $this->security->xss_clean($this->input->post('adresse_siege')),
            'ville' => $this->security->xss_clean($this->input->post('ville')) ?: null,
            'pays' => $this->security->xss_clean($this->input->post('pays')) ?: null,
            'site_web' => $this->security->xss_clean($this->input->post('site_web')) ?: null,
            'activite_principale' => $this->security->xss_clean($this->input->post('activite_principale')) ?: null,
            'effectif_tranche' => $this->security->xss_clean($this->input->post('effectif_tranche')) ?: null,
            'telephone' => $this->security->xss_clean($this->input->post('telephone')),
            'email_contact' => strtolower(trim($this->security->xss_clean($this->input->post('email_contact')))),
            'commentaire_marchand' => $this->security->xss_clean($this->input->post('commentaire_marchand')) ?: null,
        );
        $this->business_kyb_model->update_by_id($id, $data);
        $this->session->set_flashdata('message', 'Dossier KYB mis à jour.');
        redirect('Kyb_backoffice/fiche/' . $id);
    }
}
