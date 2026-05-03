<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Business_Controller.php';

/**
 * CRUD services internes (Finance, Compta, …)
 */
class Services extends MY_Business_Controller {

    private static $roles_write = array('administrateur', 'gestionnaire');

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->require_business_roles(array('administrateur', 'gestionnaire', 'lecteur_seul'));
        $id_m = (int) $this->session->userdata('business_marchand_id');
        $list = $this->service_model->get_by_marchand($id_m);
        $this->load->view('business/services/index', array(
            'list' => $list,
            'can_write' => $this->_can_write(),
        ));
    }

    public function add() {
        $this->_require_write();
        if ($this->input->method(TRUE) === 'POST') {
            $this->verify_portal_csrf_post();
            $this->form_validation->set_rules('libelle', 'Libellé', 'required|min_length[2]');
            if ($this->form_validation->run()) {
                $id_m = (int) $this->session->userdata('business_marchand_id');
                $this->service_model->insert_row($id_m, array(
                    'libelle' => $this->security->xss_clean($this->input->post('libelle')),
                    'code' => $this->security->xss_clean($this->input->post('code')) ?: null,
                    'actif' => $this->input->post('actif') ? 1 : 0,
                    'ordre' => (int) $this->input->post('ordre'),
                ));
                $this->session->set_flashdata('message', 'Service créé.');
                redirect('business/services');
                return;
            }
        }
        $this->load->view('business/services/form', array(
            'row' => null,
            'title' => 'Nouveau service',
            'error' => $this->input->method(TRUE) === 'POST' ? validation_errors() : '',
        ));
    }

    public function edit($id) {
        $this->_require_write();
        $id_m = (int) $this->session->userdata('business_marchand_id');
        $row = $this->service_model->get_by_id((int) $id, $id_m);
        if (!$row) {
            show_404();
            return;
        }
        if ($this->input->method(TRUE) === 'POST') {
            $this->verify_portal_csrf_post();
            $this->form_validation->set_rules('libelle', 'Libellé', 'required|min_length[2]');
            if ($this->form_validation->run()) {
                $this->service_model->update_row((int) $id, $id_m, array(
                    'libelle' => $this->security->xss_clean($this->input->post('libelle')),
                    'code' => $this->security->xss_clean($this->input->post('code')) ?: null,
                    'actif' => $this->input->post('actif') ? 1 : 0,
                    'ordre' => (int) $this->input->post('ordre'),
                ));
                $this->session->set_flashdata('message', 'Service mis à jour.');
                redirect('business/services');
                return;
            }
        }
        $this->load->view('business/services/form', array(
            'row' => $row,
            'title' => 'Modifier le service',
            'error' => $this->input->method(TRUE) === 'POST' ? validation_errors() : '',
        ));
    }

    public function delete($id) {
        $this->_require_write();
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
            return;
        }
        $this->verify_portal_csrf_post();
        $id_m = (int) $this->session->userdata('business_marchand_id');
        $this->service_model->delete_row((int) $id, $id_m);
        $this->session->set_flashdata('message', 'Service supprimé.');
        redirect('business/services');
    }

    private function _can_write() {
        return in_array((string) $this->session->userdata('business_role'), self::$roles_write, true);
    }

    private function _require_write() {
        $this->require_business_roles(self::$roles_write);
    }
}
