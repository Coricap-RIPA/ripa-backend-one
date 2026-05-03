<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Business_Controller.php';

/**
 * Liste des transactions business + saisie manuelle (admin / gestionnaire)
 */
class Transactions extends MY_Business_Controller {

    private static $roles_write = array('administrateur', 'gestionnaire');

    public function __construct() {
        parent::__construct();
        $this->load->library('Business_api_client');
    }

    public function index() {
        $this->require_business_roles(array('administrateur', 'gestionnaire', 'lecteur_seul'));
        $resp = $this->business_api_client->get('transactions?limit=300');
        if (empty($resp['ok'])) {
            show_error('Erreur API Business (transactions).', 502);
            return;
        }
        $list = isset($resp['body']['data']['items']) ? $resp['body']['data']['items'] : array();
        $kyb = $this->business_api_client->get('kyb');
        $kyb_ok = false;
        if (!empty($kyb['ok']) && !empty($kyb['body']['data']['meta']['kyb_approved_for_transactions'])) {
            $kyb_ok = true;
        }
        $this->load->view('business/transactions/index', array(
            'list' => $list,
            'can_write' => $this->_can_write(),
            'kyb_transactions_ok' => $kyb_ok,
        ));
    }

    public function add() {
        $this->_require_write();
        $kyb_gate = $this->business_api_client->get('kyb');
        $kyb_ok = !empty($kyb_gate['ok']) && !empty($kyb_gate['body']['data']['meta']['kyb_approved_for_transactions']);
        if ($this->input->method(TRUE) === 'POST') {
            $this->verify_portal_csrf_post();
            if (!$kyb_ok) {
                $this->session->set_flashdata('warning_message', 'Transaction refusée : dossier KYB non approuvé ou expiré.');
                redirect('business/transactions');
                return;
            }
            $this->form_validation->set_rules('montant', 'Montant', 'required|numeric');
            $this->form_validation->set_rules('type_operation', 'Type', 'required');
            $this->form_validation->set_rules('sens', 'Sens', 'required|regex_match[/^(debit|credit)$/]');
            if ($this->form_validation->run()) {
                $id_service = $this->input->post('id_service');
                $id_service = ($id_service !== '' && $id_service !== null) ? (int) $id_service : null;
                $id_emp = $this->input->post('id_employe');
                $id_emp = ($id_emp !== '' && $id_emp !== null) ? (int) $id_emp : null;
                $payload = array(
                    'id_service' => $id_service,
                    'id_employe' => $id_emp,
                    'type_operation' => $this->security->xss_clean($this->input->post('type_operation')),
                    'sens' => $this->input->post('sens'),
                    'montant' => abs((float) $this->input->post('montant')),
                    'devise' => $this->security->xss_clean($this->input->post('devise')) ?: 'USD',
                    'libelle' => $this->security->xss_clean($this->input->post('libelle')) ?: null,
                );
                $api = $this->business_api_client->post('transactions/create', $payload);
                if (empty($api['ok'])) {
                    $svc = $this->business_api_client->get('services');
                    $emp = $this->business_api_client->get('employes');
                    $kyb = $this->business_api_client->get('kyb');
                    $kyb_ok = !empty($kyb['ok']) && !empty($kyb['body']['data']['meta']['kyb_approved_for_transactions']);
                    $services = !empty($svc['ok']) && isset($svc['body']['data']['items']) ? $svc['body']['data']['items'] : array();
                    $employes = !empty($emp['ok']) && isset($emp['body']['data']['items']) ? $emp['body']['data']['items'] : array();
                    $this->load->view('business/transactions/form', array(
                        'error' => '<p>Erreur API Business: ' . htmlspecialchars((string) ($api['error'] ?? '')) . '</p>' . validation_errors(),
                        'services' => $services,
                        'employes' => $employes,
                        'kyb_transactions_ok' => $kyb_ok,
                    ));
                    return;
                }
                $this->session->set_flashdata('message', 'Transaction enregistrée.');
                redirect('business/transactions');
                return;
            }
        }
        $svc = $this->business_api_client->get('services');
        $emp = $this->business_api_client->get('employes');
        $services = !empty($svc['ok']) && isset($svc['body']['data']['items']) ? $svc['body']['data']['items'] : array();
        $employes = !empty($emp['ok']) && isset($emp['body']['data']['items']) ? $emp['body']['data']['items'] : array();
        $this->load->view('business/transactions/form', array(
            'error' => $this->input->method(TRUE) === 'POST' ? validation_errors() : '',
            'services' => $services,
            'employes' => $employes,
            'kyb_transactions_ok' => $kyb_ok,
        ));
    }

    private function _can_write() {
        return in_array((string) $this->session->userdata('business_role'), self::$roles_write, true);
    }

    private function _require_write() {
        $this->require_business_roles(self::$roles_write);
    }
}
