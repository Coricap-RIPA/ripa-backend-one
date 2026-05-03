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
        $this->load->library('Business_api_client');
    }

    public function index() {
        $this->require_business_roles(array('administrateur', 'gestionnaire', 'lecteur_seul'));
        $resp = $this->business_api_client->get('services');
        if (empty($resp['ok'])) {
            show_error('Erreur API Business (services).', 502);
            return;
        }
        $list = isset($resp['body']['data']['items']) ? $resp['body']['data']['items'] : array();
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
                $payload = array(
                    'libelle' => $this->security->xss_clean($this->input->post('libelle')),
                    'description' => $this->security->xss_clean($this->input->post('description')) ?: null,
                    'code' => $this->security->xss_clean($this->input->post('code')) ?: null,
                    'actif' => $this->input->post('actif') ? 1 : 0,
                    'ordre' => (int) $this->input->post('ordre'),
                );
                $api = $this->business_api_client->post('services/create', $payload);
                if (empty($api['ok'])) {
                    $this->load->view('business/services/form', array(
                        'row' => null,
                        'title' => 'Nouveau service',
                        'error' => '<p>Erreur API Business: ' . htmlspecialchars((string) ($api['error'] ?? '')) . '</p>' . validation_errors(),
                    ));
                    return;
                }
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
        $show = $this->business_api_client->get('services/' . (int) $id);
        $row = !empty($show['ok']) && isset($show['body']['data']['item']) ? $show['body']['data']['item'] : null;
        if (!$row) {
            show_404();
            return;
        }
        if ($this->input->method(TRUE) === 'POST') {
            $this->verify_portal_csrf_post();
            $this->form_validation->set_rules('libelle', 'Libellé', 'required|min_length[2]');
            if ($this->form_validation->run()) {
                $payload = array(
                    'libelle' => $this->security->xss_clean($this->input->post('libelle')),
                    'description' => $this->security->xss_clean($this->input->post('description')) ?: null,
                    'code' => $this->security->xss_clean($this->input->post('code')) ?: null,
                    'actif' => $this->input->post('actif') ? 1 : 0,
                    'ordre' => (int) $this->input->post('ordre'),
                );
                $api = $this->business_api_client->put('services/' . (int) $id . '/update', $payload);
                if (empty($api['ok'])) {
                    $this->load->view('business/services/form', array(
                        'row' => $row,
                        'title' => 'Modifier le service',
                        'error' => '<p>Erreur API Business: ' . htmlspecialchars((string) ($api['error'] ?? '')) . '</p>' . validation_errors(),
                    ));
                    return;
                }
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
        $api = $this->business_api_client->delete('services/' . (int) $id . '/delete');
        if (empty($api['ok'])) {
            show_error('Erreur API Business (suppression service).', 502);
            return;
        }
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
