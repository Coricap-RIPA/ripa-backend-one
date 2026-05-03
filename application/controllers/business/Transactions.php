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
    }

    public function index() {
        $this->require_business_roles(array('administrateur', 'gestionnaire', 'lecteur_seul'));
        $id_m = (int) $this->session->userdata('business_marchand_id');
        $list = $this->business_transaction_model->get_by_marchand($id_m, 300);
        $this->load->view('business/transactions/index', array(
            'list' => $list,
            'can_write' => $this->_can_write(),
        ));
    }

    public function add() {
        $this->_require_write();
        $id_m = (int) $this->session->userdata('business_marchand_id');
        $id_ub = (int) $this->session->userdata('business_ub_id');
        if ($this->input->method(TRUE) === 'POST') {
            $this->verify_portal_csrf_post();
            $this->form_validation->set_rules('montant', 'Montant', 'required|numeric');
            $this->form_validation->set_rules('type_operation', 'Type', 'required');
            $this->form_validation->set_rules('sens', 'Sens', 'required|regex_match[/^(debit|credit)$/]');
            if ($this->form_validation->run()) {
                $id_service = $this->input->post('id_service');
                $id_service = ($id_service !== '' && $id_service !== null) ? (int) $id_service : null;
                if ($id_service) {
                    $s = $this->service_model->get_by_id($id_service, $id_m);
                    if (!$s) {
                        $id_service = null;
                    }
                }
                $id_emp = $this->input->post('id_employe');
                $id_emp = ($id_emp !== '' && $id_emp !== null) ? (int) $id_emp : null;
                if ($id_emp) {
                    $e = $this->employe_model->get_by_id($id_emp, $id_m);
                    if (!$e) {
                        $id_emp = null;
                    }
                }
                $this->business_transaction_model->insert_row($id_m, array(
                    'id_service' => $id_service,
                    'id_utilisateur_business' => $id_ub,
                    'id_employe' => $id_emp,
                    'type_operation' => $this->security->xss_clean($this->input->post('type_operation')),
                    'sens' => $this->input->post('sens'),
                    'montant' => abs((float) $this->input->post('montant')),
                    'devise' => $this->security->xss_clean($this->input->post('devise')) ?: 'USD',
                    'libelle' => $this->security->xss_clean($this->input->post('libelle')) ?: null,
                    'statut' => 'valide',
                    'id_paiement_ripa' => null,
                    'meta_json' => null,
                ));
                $this->session->set_flashdata('message', 'Transaction enregistrée.');
                redirect('business/transactions');
                return;
            }
        }
        $this->load->view('business/transactions/form', array(
            'error' => $this->input->method(TRUE) === 'POST' ? validation_errors() : '',
            'services' => $this->service_model->get_by_marchand($id_m),
            'employes' => $this->employe_model->get_by_marchand($id_m),
        ));
    }

    private function _can_write() {
        return in_array((string) $this->session->userdata('business_role'), self::$roles_write, true);
    }

    private function _require_write() {
        $this->require_business_roles(self::$roles_write);
    }
}
