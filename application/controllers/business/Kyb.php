<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Business_Controller.php';

/**
 * KYB — soumission / mise à jour dossier depuis le portail marchand
 */
class Kyb extends MY_Business_Controller {

    private static $roles_write = array('administrateur', 'gestionnaire');

    const UPLOAD_MAX_BYTES = 3145728;

    public function __construct() {
        parent::__construct();
        $this->load->library('Business_api_client');
    }

    public function index() {
        $this->require_business_roles(array('administrateur', 'gestionnaire', 'lecteur_seul'));
        $m = $this->business_api_client->get('marchand');
        if (empty($m['ok'])) {
            show_error('Erreur API Business (marchand).', 502);
            return;
        }
        $k = $this->business_api_client->get('kyb');
        if (empty($k['ok'])) {
            show_error('Erreur API Business (kyb).', 502);
            return;
        }
        $marchand = isset($m['body']['data']['item']) ? $m['body']['data']['item'] : null;
        $dossier = isset($k['body']['data']['item']) ? $k['body']['data']['item'] : null;
        $meta = isset($k['body']['data']['meta']) && is_array($k['body']['data']['meta']) ? $k['body']['data']['meta'] : array();
        $this->load->view('business/kyb/index', array(
            'marchand' => $marchand,
            'dossier' => $dossier,
            'kyb_meta' => $meta,
            'can_write' => $this->_can_write(),
            'nav_active' => 'kyb',
        ));
    }

    public function submit() {
        $this->_require_write();
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
            return;
        }
        //$this->verify_portal_csrf_post();
        $this->form_validation->set_rules('denomination_sociale', 'Dénomination sociale', 'required|min_length[2]|max_length[255]');
        $this->form_validation->set_rules('numero_identification_legal', 'Numéro d’identification légale', 'required|min_length[2]|max_length[120]');
        $this->form_validation->set_rules('adresse_siege', 'Adresse du siège', 'required|min_length[5]');
        $this->form_validation->set_rules('telephone', 'Téléphone', 'required|min_length[6]|max_length[50]');
        $this->form_validation->set_rules('email_contact', 'Email de contact', 'required|valid_email|max_length[255]');
        $this->form_validation->set_rules('ville', 'Ville', 'trim|max_length[120]');
        $this->form_validation->set_rules('pays', 'Pays', 'trim|max_length[3]');
        $this->form_validation->set_rules('site_web', 'Site web', 'trim|max_length[255]');
        $this->form_validation->set_rules('activite_principale', 'Activité principale', 'trim|max_length[255]');
        $this->form_validation->set_rules('effectif_tranche', 'Effectif', 'trim|max_length[50]');
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('kyb_errors', validation_errors());
            redirect('business/kyb');
            return;
        }
        $payload = array(
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
        $files = array();
        if ($this->_kyb_portal_upload_ok('piece_legal')) {
            $files['piece_legal'] = array(
                'tmp_name' => $_FILES['piece_legal']['tmp_name'],
                'name' => $_FILES['piece_legal']['name'],
                'type' => isset($_FILES['piece_legal']['type']) ? $_FILES['piece_legal']['type'] : '',
            );
        }
        if ($this->_kyb_portal_upload_ok('piece_complement')) {
            $files['piece_complement'] = array(
                'tmp_name' => $_FILES['piece_complement']['tmp_name'],
                'name' => $_FILES['piece_complement']['name'],
                'type' => isset($_FILES['piece_complement']['type']) ? $_FILES['piece_complement']['type'] : '',
            );
        }
        $api = $this->business_api_client->post_multipart('kyb/submit-upload', $payload, $files);
        if (empty($api['ok'])) {
            $this->session->set_flashdata('kyb_errors', 'Erreur API Business: ' . htmlspecialchars((string) ($api['error'] ?? '')));
            redirect('business/kyb');
            return;
        }
        $this->session->set_flashdata('kyb_message', 'Dossier KYB soumis. Notre équipe le traitera sous peu.');
        redirect('business/kyb');
    }

    /**
     * Fichier réellement reçu par PHP (évite d’envoyer à l’API un tmp_name avec error ≠ OK).
     */
    private function _kyb_portal_upload_ok($field) {
        if (!isset($_FILES[$field]) || !is_array($_FILES[$field])) {
            return false;
        }
        $fi = $_FILES[$field];
        if (empty($fi['tmp_name']) || !is_string($fi['tmp_name']) || $fi['tmp_name'] === '') {
            return false;
        }
        $err = isset($fi['error']) ? (int) $fi['error'] : UPLOAD_ERR_OK;
        return $err === UPLOAD_ERR_OK && is_uploaded_file($fi['tmp_name']);
    }

    private function _can_write() {
        return in_array((string) $this->session->userdata('business_role'), self::$roles_write, true);
    }

    private function _require_write() {
        $this->require_business_roles(self::$roles_write);
    }

    /**
     * GET business/kyb/piece/legal|complement — pièce jointe (session marchand).
     */
    public function piece($slot = 'legal') {
        $this->require_business_roles(array('administrateur', 'gestionnaire', 'lecteur_seul'));
        $slot = strtolower(trim((string) $slot));
        if (!in_array($slot, array('legal', 'complement'), true)) {
            show_404();
            return;
        }
        $id_m = (int) $this->session->userdata('business_marchand_id');
        if ($id_m < 1) {
            show_404();
            return;
        }
        $row = $this->business_kyb_model->get_by_marchand($id_m);
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
        $full = ripa_kyb_storage_resolve_full_path($rel, $id_m);
        if ($full === null) {
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
     * POST business/kyb/supprimer — efface un dossier « rejeté » (API DELETE kyb/dossier).
     */
    public function supprimer() {
        $this->_require_write();
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
            return;
        }
        if (!ripa_portal_csrf_verify()) {
            $this->session->set_flashdata('kyb_errors', 'Session expirée ou jeton de sécurité invalide. Rechargez la page.');
            redirect('business/kyb');
            return;
        }
        $api = $this->business_api_client->delete('kyb/dossier');
        if (empty($api['ok'])) {
            $this->session->set_flashdata('kyb_errors', 'Erreur API Business: ' . htmlspecialchars((string) ($api['error'] ?? '')));
            redirect('business/kyb');
            return;
        }
        $this->session->set_flashdata('kyb_message', 'Dossier KYB supprimé. Vous pouvez soumettre un nouveau dossier.');
        redirect('business/kyb');
    }
}
