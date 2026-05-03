<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Backoffice RIPA : gestion des dossiers KYC (liste, détail, valider, rejeter, supprimer)
 * Sécurisé par permissions : voir / editer / supprimer (check_privilege, short_code kyc_application).
 */
class Kyc_backoffice extends CI_Controller {

    /** Short-code de la fonctionnalité (table fonctionnalite) */
    const FONCTIONNALITE_KYC = 'kyc_application';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Vérifie que l'utilisateur est connecté et a le droit demandé. Sinon redirige.
     * @param string $operation voir | editer | supprimer
     * @return bool true si autorisé (et ne retourne pas)
     */
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
        if (!check_privilege(self::FONCTIONNALITE_KYC, (int) $user['id_role'], $operation)) {
            if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect('Dashboard/index');
            }
            return false;
        }
        return true;
    }

    /**
     * Liste des dossiers KYC (filtre optionnel par statut)
     */
    public function index() {
        if ($this->_check_privilege_or_redirect('voir') === false) {
            return;
        }
        $statut = $this->input->get('statut');
        $list = $this->Kyc_model->get_all_for_backoffice($statut);
        $kyc_link_active = 'link_menu_active';
        $crud = new grocery_CRUD();
        $crud->set_table('sexe');
        $output = $crud->render();
        $header = $this->load->view('layouts/header', array('css_files'=>$output->css_files), true);
        $navbar = $this->load->view('layouts/menu_nav_bar', array('kyc_link_active' => $kyc_link_active), true);
        $footer = $this->load->view('layouts/footer', array('js_files'=>$output->js_files), true);
        $this->load->view('kyc_backoffice/list', array(
            'header' => $header,
            'navbar' => $navbar,
            'footer' => $footer,
            'list' => $list,
            'filter_statut' => $statut,
            'output' => $output->output,
        ));
    }

    /**
     * Détail d'un dossier KYC (données déchiffrées pour vérification)
     */
    public function detail($id_kyc) {
        if ($this->_check_privilege_or_redirect('voir') === false) {
            return;
        }
        $id_kyc = (int) $id_kyc;
        $kyc = $this->Kyc_model->get_by_id_for_backoffice($id_kyc);
        if (!$kyc) {
            $this->session->set_flashdata('message', 'Dossier KYC introuvable.');
            redirect('Kyc_backoffice/index');
            return;
        }
        $user = $this->User_model->get_user_by_id($kyc['id_utilisateur_application']);
        $user_session = $this->session->userdata('user');
        $id_role = !empty($user_session['id_role']) ? (int) $user_session['id_role'] : 0;
        $data = array(
            'id_kyc' => $kyc['id_kyc'],
            'id_utilisateur_application' => $kyc['id_utilisateur_application'],
            'statut' => $kyc['statut'],
            'can_editer' => check_privilege(self::FONCTIONNALITE_KYC, $id_role, 'editer'),
            'can_supprimer' => check_privilege(self::FONCTIONNALITE_KYC, $id_role, 'supprimer'),
            'date_enregistrement' => $kyc['date_enregistrement'],
            'date_validation_kyc' => $kyc['date_validation_kyc'],
            'date_prochaine_kyc' => $kyc['date_prochaine_kyc'],
            'phone' => isset($user['phone']) ? $user['phone'] : '',
            'nom' => $this->_decrypt_safe($kyc, 'nom_c'),
            'post_nom' => $this->_decrypt_safe($kyc, 'post_nom_c'),
            'prenom' => $this->_decrypt_safe($kyc, 'prenom_c'),
            'date_naissance' => $this->_decrypt_safe($kyc, 'date_naissance_c'),
            'adresse' => $this->_decrypt_safe($kyc, 'adresse_c'),
            'photo_piece_base64' => $this->_decrypt_safe($kyc, 'photo_piece_identite_c'),
            'photo_utilisateur_base64' => $this->_decrypt_safe($kyc, 'photo_utilisateur_c'),
        );
        $kyc_link_active = 'link_menu_active';
        $crud = new grocery_CRUD();
        $crud->set_table('sexe');
        $output = $crud->render();
        $header = $this->load->view('layouts/header', array('css_files'=>$output->css_files), true);
        $navbar = $this->load->view('layouts/menu_nav_bar', array('kyc_link_active' => $kyc_link_active), true);
        $footer = $this->load->view('layouts/footer', array('js_files'=>$output->js_files), true);
        $this->load->view('kyc_backoffice/detail', array(
            'header' => $header,
            'navbar' => $navbar,
            'footer' => $footer,
            'data' => $data,
            'output' => $output->output,
        ));
    }

    /**
     * Valider un dossier KYC (POST)
     */
    public function valider($id_kyc) {
        if ($this->_check_privilege_or_redirect('editer') === false) {
            return;
        }
        if ($this->input->method() !== 'post') {
            redirect('Kyc_backoffice/index');
            return;
        }
        $id_kyc = (int) $id_kyc;
        $kyc = $this->Kyc_model->get_by_id_for_backoffice($id_kyc);
        $this->Kyc_model->set_statut($id_kyc, 'valide');
        if ($kyc && !empty($kyc['id_utilisateur_application'])) {
            $this->Notification_model->add(
                $kyc['id_utilisateur_application'],
                'kyc_valide',
                'KYC validé',
                'Votre dossier de vérification d\'identité a été validé par RIPA. Vous pouvez enregistrer une carte.'
            );
        }
        $this->session->set_flashdata('message', 'Dossier KYC validé.');
        redirect('Kyc_backoffice/detail/' . $id_kyc);
    }

    /**
     * Rejeter un dossier KYC (POST)
     */
    public function rejeter($id_kyc) {
        if ($this->_check_privilege_or_redirect('editer') === false) {
            return;
        }
        if ($this->input->method() !== 'post') {
            redirect('Kyc_backoffice/index');
            return;
        }
        $id_kyc = (int) $id_kyc;
        $kyc = $this->Kyc_model->get_by_id_for_backoffice($id_kyc);
        $this->Kyc_model->set_statut($id_kyc, 'rejete');
        if ($kyc && !empty($kyc['id_utilisateur_application'])) {
            $this->Notification_model->add(
                $kyc['id_utilisateur_application'],
                'kyc_rejete',
                'KYC rejeté',
                'Votre dossier de vérification d\'identité a été rejeté. Contactez le service client RIPA pour plus d\'informations.'
            );
        }
        $this->session->set_flashdata('message', 'Dossier KYC rejeté.');
        redirect('Kyc_backoffice/detail/' . $id_kyc);
    }

    /**
     * Supprimer un dossier KYC (POST)
     */
    public function supprimer($id_kyc) {
        if ($this->_check_privilege_or_redirect('supprimer') === false) {
            return;
        }
        if ($this->input->method() !== 'post') {
            redirect('Kyc_backoffice/index');
            return;
        }
        $id_kyc = (int) $id_kyc;
        $kyc = $this->Kyc_model->get_by_id_for_backoffice($id_kyc);
        $user_id = $kyc ? (int) $kyc['id_utilisateur_application'] : 0;
        $this->Kyc_model->delete_kyc($id_kyc);
        if ($user_id) {
            $this->Notification_model->add(
                $user_id,
                'kyc_supprime',
                'Dossier KYC supprimé',
                'Votre dossier de vérification d\'identité a été supprimé. Vous pouvez soumettre un nouveau dossier depuis l\'application.'
            );
        }
        $this->session->set_flashdata('message', 'Dossier KYC supprimé.');
        redirect('Kyc_backoffice/index');
    }

    /**
     * Déchiffre un champ KYC de manière sûre (retourne chaîne vide si erreur)
     */
    private function _decrypt_safe($row, $key) {
        if (empty($row[$key])) {
            return '';
        }
        if (!function_exists('decrypt_ripa')) {
            return '';
        }
        $out = decrypt_ripa($row[$key]);
        return $out !== false ? $out : '';
    }
}
