<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Business_Controller.php';

/**
 * CRUD employés (fiches RH)
 */
class Employes extends MY_Business_Controller {

    private static $roles_write = array('administrateur', 'gestionnaire');

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->require_business_roles(array('administrateur', 'gestionnaire', 'lecteur_seul'));
        $id_m = (int) $this->session->userdata('business_marchand_id');
        $list = $this->employe_model->get_by_marchand($id_m);
        $this->load->view('business/employes/index', array(
            'list' => $list,
            'can_write' => $this->_can_write(),
        ));
    }

    public function add() {
        $this->_require_write();
        $id_m = (int) $this->session->userdata('business_marchand_id');
        if ($this->input->method(TRUE) === 'POST') {
            $this->verify_portal_csrf_post();
            $this->form_validation->set_rules('nom', 'Nom', 'required|min_length[2]');
            $this->form_validation->set_rules('prenom', 'Prénom', 'required|min_length[2]');
            if ($this->form_validation->run()) {
                $id_ub = $this->input->post('id_utilisateur_business');
                $id_ub = ($id_ub !== '' && $id_ub !== null) ? (int) $id_ub : null;
                if ($id_ub) {
                    $ub = $this->ub_model->get_by_id($id_ub);
                    if (!$ub || (int) $ub['id_marchand'] !== $id_m) {
                        $id_ub = null;
                    }
                }
                $this->employe_model->insert_row($id_m, array(
                    'nom' => $this->security->xss_clean($this->input->post('nom')),
                    'prenom' => $this->security->xss_clean($this->input->post('prenom')),
                    'email' => $this->security->xss_clean($this->input->post('email')) ?: null,
                    'telephone' => $this->security->xss_clean($this->input->post('telephone')) ?: null,
                    'poste' => $this->security->xss_clean($this->input->post('poste')) ?: null,
                    'actif' => $this->input->post('actif') ? 1 : 0,
                    'id_utilisateur_business' => $id_ub,
                ));
                $this->session->set_flashdata('message', 'Employé enregistré.');
                redirect('business/employes');
                return;
            }
        }
        $this->load->view('business/employes/form', array(
            'row' => null,
            'title' => 'Nouvel employé',
            'error' => $this->input->method(TRUE) === 'POST' ? validation_errors() : '',
            'users_portail' => $this->ub_model->get_by_marchand($id_m),
        ));
    }

    public function edit($id) {
        $this->_require_write();
        $id_m = (int) $this->session->userdata('business_marchand_id');
        $row = $this->employe_model->get_by_id((int) $id, $id_m);
        if (!$row) {
            show_404();
            return;
        }
        if ($this->input->method(TRUE) === 'POST') {
            $this->verify_portal_csrf_post();
            $this->form_validation->set_rules('nom', 'Nom', 'required|min_length[2]');
            $this->form_validation->set_rules('prenom', 'Prénom', 'required|min_length[2]');
            if ($this->form_validation->run()) {
                $id_ub = $this->input->post('id_utilisateur_business');
                $id_ub = ($id_ub !== '' && $id_ub !== null) ? (int) $id_ub : null;
                if ($id_ub) {
                    $ub = $this->ub_model->get_by_id($id_ub);
                    if (!$ub || (int) $ub['id_marchand'] !== $id_m) {
                        $id_ub = null;
                    }
                }
                $this->employe_model->update_row((int) $id, $id_m, array(
                    'nom' => $this->security->xss_clean($this->input->post('nom')),
                    'prenom' => $this->security->xss_clean($this->input->post('prenom')),
                    'email' => $this->security->xss_clean($this->input->post('email')) ?: null,
                    'telephone' => $this->security->xss_clean($this->input->post('telephone')) ?: null,
                    'poste' => $this->security->xss_clean($this->input->post('poste')) ?: null,
                    'actif' => $this->input->post('actif') ? 1 : 0,
                    'id_utilisateur_business' => $id_ub,
                ));
                $this->session->set_flashdata('message', 'Employé mis à jour.');
                redirect('business/employes');
                return;
            }
        }
        $this->load->view('business/employes/form', array(
            'row' => $row,
            'title' => 'Modifier l’employé',
            'error' => $this->input->method(TRUE) === 'POST' ? validation_errors() : '',
            'users_portail' => $this->ub_model->get_by_marchand($id_m),
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
        $this->employe_model->delete_row((int) $id, $id_m);
        $this->session->set_flashdata('message', 'Employé supprimé.');
        redirect('business/employes');
    }

    private function _can_write() {
        return in_array((string) $this->session->userdata('business_role'), self::$roles_write, true);
    }

    private function _require_write() {
        $this->require_business_roles(self::$roles_write);
    }
}
