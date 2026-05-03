<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Business_Controller.php';

/**
 * CRUD employés (fiches RH) + import Excel
 */
class Employes extends MY_Business_Controller {

    private static $roles_write = array('administrateur', 'gestionnaire');

    /** Limite de lignes données (hors en-tête) par fichier. */
    const IMPORT_MAX_ROWS = 500;

    /** Taille max fichier (octets). */
    const IMPORT_MAX_BYTES = 2097152;

    public function __construct() {
        parent::__construct();
        $this->load->library('Business_api_client');
    }

    public function index() {
        $this->require_business_roles(array('administrateur', 'gestionnaire', 'lecteur_seul'));
        $resp = $this->business_api_client->get('employes');
        if (empty($resp['ok'])) {
            show_error('Erreur API Business (employés).', 502);
            return;
        }
        $list = isset($resp['body']['data']['items']) ? $resp['body']['data']['items'] : array();
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
                $poste_err = $this->_validate_employe_poste_selection();
                if ($poste_err !== '') {
                    $this->load->view('business/employes/form', array(
                        'row' => null,
                        'title' => 'Nouvel employé',
                        'error' => $poste_err . validation_errors(),
                        'users_portail' => $this->ub_model->get_by_marchand($id_m),
                        'services' => $this->service_model->get_by_marchand($id_m),
                        'postes_ref' => $this->ref_poste_fonction_model->list_ordered(),
                    ));
                    return;
                }
                $id_ub = $this->input->post('id_utilisateur_business');
                $id_ub = ($id_ub !== '' && $id_ub !== null) ? (int) $id_ub : null;
                if ($id_ub) {
                    $ub = $this->ub_model->get_by_id($id_ub);
                    if (!$ub || (int) $ub['id_marchand'] !== $id_m) {
                        $id_ub = null;
                    }
                }
                $payload = $this->_employe_payload_from_post($id_ub, $id_m, null);
                $api = $this->business_api_client->post('employes/create', $payload);
                if (empty($api['ok'])) {
                    $this->load->view('business/employes/form', array(
                        'row' => null,
                        'title' => 'Nouvel employé',
                        'error' => '<p>Erreur API Business: ' . htmlspecialchars((string) ($api['error'] ?? '')) . '</p>' . validation_errors(),
                        'users_portail' => $this->ub_model->get_by_marchand($id_m),
                        'services' => $this->service_model->get_by_marchand($id_m),
                        'postes_ref' => $this->ref_poste_fonction_model->list_ordered(),
                    ));
                    return;
                }
                $this->session->set_flashdata('message', $this->_flash_after_employe_save($payload));
                redirect('business/employes');
                return;
            }
        }
        $this->load->view('business/employes/form', array(
            'row' => null,
            'title' => 'Nouvel employé',
            'error' => $this->input->method(TRUE) === 'POST' ? validation_errors() : '',
            'users_portail' => $this->ub_model->get_by_marchand($id_m),
            'services' => $this->service_model->get_by_marchand($id_m),
            'postes_ref' => $this->ref_poste_fonction_model->list_ordered(),
        ));
    }

    public function edit($id) {
        $this->_require_write();
        $id_m = (int) $this->session->userdata('business_marchand_id');
        $show = $this->business_api_client->get('employes/' . (int) $id);
        $row = !empty($show['ok']) && isset($show['body']['data']['item']) ? $show['body']['data']['item'] : null;
        if (!$row) {
            show_404();
            return;
        }
        if ($this->input->method(TRUE) === 'POST') {
            $this->verify_portal_csrf_post();
            $this->form_validation->set_rules('nom', 'Nom', 'required|min_length[2]');
            $this->form_validation->set_rules('prenom', 'Prénom', 'required|min_length[2]');
            if ($this->form_validation->run()) {
                $poste_err = $this->_validate_employe_poste_selection();
                if ($poste_err !== '') {
                    $this->load->view('business/employes/form', array(
                        'row' => $row,
                        'title' => 'Modifier l’employé',
                        'error' => $poste_err . validation_errors(),
                        'users_portail' => $this->ub_model->get_by_marchand($id_m),
                        'services' => $this->service_model->get_by_marchand($id_m),
                        'postes_ref' => $this->ref_poste_fonction_model->list_ordered(),
                    ));
                    return;
                }
                $id_ub = $this->input->post('id_utilisateur_business');
                $id_ub = ($id_ub !== '' && $id_ub !== null) ? (int) $id_ub : null;
                if ($id_ub) {
                    $ub = $this->ub_model->get_by_id($id_ub);
                    if (!$ub || (int) $ub['id_marchand'] !== $id_m) {
                        $id_ub = null;
                    }
                }
                $payload = $this->_employe_payload_from_post($id_ub, $id_m, $row);
                $api = $this->business_api_client->put('employes/' . (int) $id . '/update', $payload);
                if (empty($api['ok'])) {
                    $this->load->view('business/employes/form', array(
                        'row' => $row,
                        'title' => 'Modifier l’employé',
                        'error' => '<p>Erreur API Business: ' . htmlspecialchars((string) ($api['error'] ?? '')) . '</p>' . validation_errors(),
                        'users_portail' => $this->ub_model->get_by_marchand($id_m),
                        'services' => $this->service_model->get_by_marchand($id_m),
                        'postes_ref' => $this->ref_poste_fonction_model->list_ordered(),
                    ));
                    return;
                }
                $this->session->set_flashdata('message', $this->_flash_after_employe_save($payload, false));
                redirect('business/employes');
                return;
            }
        }
        $this->load->view('business/employes/form', array(
            'row' => $row,
            'title' => 'Modifier l’employé',
            'error' => $this->input->method(TRUE) === 'POST' ? validation_errors() : '',
            'users_portail' => $this->ub_model->get_by_marchand($id_m),
            'services' => $this->service_model->get_by_marchand($id_m),
            'postes_ref' => $this->ref_poste_fonction_model->list_ordered(),
        ));
    }

    public function delete($id) {
        $this->_require_write();
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
            return;
        }
        $this->verify_portal_csrf_post();
        $api = $this->business_api_client->delete('employes/' . (int) $id . '/delete');
        if (empty($api['ok'])) {
            show_error('Erreur API Business (suppression employé).', 502);
            return;
        }
        $this->session->set_flashdata('message', 'Employé supprimé.');
        redirect('business/employes');
    }

    /**
     * Indique si le téléphone saisi correspond à un (ou plusieurs) utilisateur(s) de l’app RIPA (JSON).
     */
    public function phone_app_lookup() {
        $this->require_business_roles(array('administrateur', 'gestionnaire', 'lecteur_seul'));
        $q = $this->input->get('q');
        $q = is_string($q) ? trim($q) : '';
        $n = 0;
        if ($q !== '') {
            $n = count($this->User_model->find_users_by_phone_match($q));
        }
        $this->output->set_content_type('application/json', 'utf-8')->set_output(json_encode(array(
            'match' => $n > 0,
            'count' => $n,
        )));
    }

    /**
     * Guide + formulaire d’import Excel (GET) ou traitement (POST).
     */
    public function import() {
        $this->_require_write();
        $this->load->helper('business_employe_import');
        if ($this->input->method(TRUE) === 'POST') {
            $this->_import_process();
            return;
        }
        $this->load->view('business/employes/import', array(
            'column_guide' => business_employe_import_column_guide(),
            'import_report' => $this->session->flashdata('employe_import_report'),
        ));
    }

    /**
     * Téléchargement du modèle .xlsx
     */
    public function import_template() {
        $this->_require_write();
        if (!$this->_load_phpspreadsheet()) {
            show_error('Bibliothèque PhpSpreadsheet indisponible. Exécutez composer install à la racine du projet.', 500);
            return;
        }
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $headers = array('nom', 'prenom', 'email', 'telephone', 'poste', 'matricule', 'departement', 'date_entree', 'ville', 'pays', 'actif');
        $sheet->fromArray(array($headers), null, 'A1');
        $sheet->fromArray(array(array(
            'Kabila', 'Marie', 'marie.kabila@exemple.cd', '+243 850 000 000', 'Comptable', 'EMP-001', 'Finance', '2024-01-15', 'Kinshasa', 'CD', '1',
        )), null, 'A2');
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="RIPA_modele_import_employes.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    private function _import_process() {
        $this->verify_portal_csrf_post();
        $this->load->helper('business_employe_import');
        if (!$this->_load_phpspreadsheet()) {
            $this->session->set_flashdata('message', 'Import impossible : exécutez composer install (PhpSpreadsheet).');
            redirect('business/employes/import');
            return;
        }
        if (empty($_FILES['fichier_import']['tmp_name']) || !is_uploaded_file($_FILES['fichier_import']['tmp_name'])) {
            $this->session->set_flashdata('message', 'Veuillez sélectionner un fichier Excel (.xlsx ou .xls).');
            redirect('business/employes/import');
            return;
        }
        if (!empty($_FILES['fichier_import']['error']) && (int) $_FILES['fichier_import']['error'] !== UPLOAD_ERR_OK) {
            $this->session->set_flashdata('message', 'Erreur lors de l’envoi du fichier.');
            redirect('business/employes/import');
            return;
        }
        if ((int) $_FILES['fichier_import']['size'] > self::IMPORT_MAX_BYTES) {
            $this->session->set_flashdata('message', 'Fichier trop volumineux (max. 2 Mo).');
            redirect('business/employes/import');
            return;
        }
        $ext = strtolower(pathinfo($_FILES['fichier_import']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, array('xlsx', 'xls'), true)) {
            $this->session->set_flashdata('message', 'Format non supporté. Utilisez .xlsx ou .xls.');
            redirect('business/employes/import');
            return;
        }
        $tmp = $_FILES['fichier_import']['tmp_name'];
        try {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($tmp);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($tmp);
            $sheet = $spreadsheet->getActiveSheet();
            $matrix = $sheet->toArray(null, true, true, false);
        } catch (Throwable $e) {
            $this->session->set_flashdata('message', 'Impossible de lire le fichier Excel. Vérifiez le format.');
            redirect('business/employes/import');
            return;
        }
        if (empty($matrix) || empty($matrix[0])) {
            $this->session->set_flashdata('message', 'Fichier vide ou sans en-tête.');
            redirect('business/employes/import');
            return;
        }
        $header_row = array_shift($matrix);
        $col_map = business_employe_import_map_header_row($header_row);
        if (!isset($col_map['nom'], $col_map['prenom'])) {
            $this->session->set_flashdata('message', 'En-tête invalide : les colonnes « nom » et « prenom » (ou équivalent) sont obligatoires dans la première ligne.');
            redirect('business/employes/import');
            return;
        }
        $id_m = (int) $this->session->userdata('business_marchand_id');
        $valid_rows = array();
        $error_rows = array();
        $line_no = 1;
        $data_lines = 0;
        foreach ($matrix as $row_cells) {
            $line_no++;
            if (!is_array($row_cells)) {
                continue;
            }
            $non_empty = false;
            foreach ($row_cells as $c) {
                if ($c !== null && trim((string) $c) !== '') {
                    $non_empty = true;
                    break;
                }
            }
            if (!$non_empty) {
                continue;
            }
            $data_lines++;
            if ($data_lines > self::IMPORT_MAX_ROWS) {
                $error_rows[] = array('line' => $line_no, 'msg' => 'Limite de ' . self::IMPORT_MAX_ROWS . ' lignes dépassée. Le reste a été ignoré.');
                break;
            }
            $assoc = array();
            foreach ($col_map as $field => $idx) {
                $assoc[$field] = isset($row_cells[$idx]) ? $row_cells[$idx] : null;
            }
            if (isset($assoc['date_entree'])) {
                $coerced = $this->_coerce_import_date($assoc['date_entree']);
                if ($coerced === false) {
                    $error_rows[] = array('line' => $line_no, 'msg' => 'Date d’entrée invalide.');
                    continue;
                }
                $assoc['date_entree'] = $coerced;
            }
            $assoc['actif'] = business_employe_import_parse_actif(isset($assoc['actif']) ? $assoc['actif'] : null);
            $check = business_employe_import_validate_db_row($assoc);
            if (!$check['ok']) {
                $error_rows[] = array('line' => $line_no, 'msg' => implode(' ', $check['errors']));
                continue;
            }
            $valid_rows[] = $check['row'];
        }
        if ($data_lines === 0) {
            $this->session->set_flashdata('message', 'Aucune ligne de données à importer (ajoutez au moins une ligne sous l’en-tête).');
            redirect('business/employes/import');
            return;
        }
        $imported = 0;
        if (!empty($valid_rows)) {
            $this->db->trans_begin();
            try {
                $this->employe_model->insert_batch_for_marchand($id_m, $valid_rows);
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('message', 'Erreur base de données à l’import. Vérifiez que la migration sql/17_business_employe_champs_generiques.sql est appliquée.');
                    redirect('business/employes/import');
                    return;
                }
                $this->db->trans_commit();
                $imported = count($valid_rows);
            } catch (Throwable $e) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('message', 'Erreur lors de l’enregistrement des employés.');
                redirect('business/employes/import');
                return;
            }
        }
        $this->session->set_flashdata('employe_import_report', array(
            'imported' => $imported,
            'errors' => array_slice($error_rows, 0, 80),
            'error_count' => count($error_rows),
        ));
        if ($imported > 0 && empty($error_rows)) {
            $this->session->set_flashdata('message', $imported . ' employé(s) importé(s).');
            redirect('business/employes');
            return;
        }
        if ($imported > 0) {
            $this->session->set_flashdata('message', $imported . ' employé(s) importé(s), avec des lignes en erreur (voir le détail ci-dessous).');
        } else {
            $this->session->set_flashdata('message', 'Aucune ligne importée. Corrigez le fichier ou le guide des colonnes.');
        }
        redirect('business/employes/import');
    }

    /**
     * @param mixed $raw
     * @return string|null date Y-m-d ou null si vide
     * @return false si invalide
     */
    private function _coerce_import_date($raw) {
        if ($raw === null || $raw === '') {
            return null;
        }
        if (is_numeric($raw) && (float) $raw > 20000) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $raw)->format('Y-m-d');
            } catch (Throwable $e) {
                return false;
            }
        }
        $s = trim((string) $raw);
        foreach (array('Y-m-d', 'd/m/Y', 'd-m-Y') as $fmt) {
            $d = DateTime::createFromFormat('!' . $fmt, $s);
            if ($d instanceof DateTime) {
                return $d->format('Y-m-d');
            }
        }
        return false;
    }

    /**
     * @param array|null $previous_row ligne actuelle en édition (pour conserver un département texte importé si pas de service choisi)
     */
    private function _employe_payload_from_post($id_ub, $id_marchand, $previous_row = null) {
        $de = $this->input->post('date_entree');
        $de = ($de !== null && trim((string) $de) !== '') ? trim((string) $de) : null;
        $id_service = $this->input->post('id_service');
        $id_service = ($id_service !== '' && $id_service !== null) ? (int) $id_service : null;
        $departement = null;
        if ($id_service) {
            $sv = $this->service_model->get_by_id($id_service, $id_marchand);
            if (!$sv) {
                $id_service = null;
            } else {
                $departement = $sv['libelle'];
            }
        } elseif ($previous_row !== null
            && (empty($previous_row['id_service']) || (int) $previous_row['id_service'] < 1)
            && !empty($previous_row['departement'])) {
            $departement = $previous_row['departement'];
        }
        $tel = $this->security->xss_clean($this->input->post('telephone'));
        $tel = ($tel !== null && trim((string) $tel) !== '') ? trim((string) $tel) : null;
        $id_ref = null;
        $poste_val = null;
        $raw_ref = $this->input->post('id_ref_poste_fonction');
        if ($raw_ref === '-1') {
            $pl = $this->security->xss_clean($this->input->post('poste_libre'));
            $poste_val = ($pl !== null && trim((string) $pl) !== '') ? trim((string) $pl) : null;
        } elseif ($raw_ref !== '' && $raw_ref !== null) {
            $rid = (int) $raw_ref;
            if ($rid > 0) {
                $ref = $this->ref_poste_fonction_model->get_by_id($rid);
                if ($ref) {
                    $id_ref = $rid;
                    if (!empty($ref['is_autre'])) {
                        $pa = $this->security->xss_clean($this->input->post('poste_autre'));
                        $poste_val = ($pa !== null && trim((string) $pa) !== '') ? trim((string) $pa) : null;
                    } else {
                        $poste_val = $ref['libelle'];
                    }
                }
            }
        }
        $matches_ua = $tel ? $this->User_model->find_users_by_phone_match($tel) : array();
        $id_ua = null;
        if (count($matches_ua) === 1) {
            $id_ua = (int) $matches_ua[0]['id_utilisateur_application'];
        }
        return array(
            'nom' => $this->security->xss_clean($this->input->post('nom')),
            'prenom' => $this->security->xss_clean($this->input->post('prenom')),
            'email' => $this->security->xss_clean($this->input->post('email')) ?: null,
            'telephone' => $tel,
            'poste' => $poste_val,
            'id_ref_poste_fonction' => $id_ref,
            'matricule' => $this->security->xss_clean($this->input->post('matricule')) ?: null,
            'id_service' => $id_service,
            'departement' => $departement,
            'date_entree' => $de,
            'ville' => $this->security->xss_clean($this->input->post('ville')) ?: null,
            'pays' => $this->security->xss_clean($this->input->post('pays')) ?: null,
            'actif' => $this->input->post('actif') ? 1 : 0,
            'id_utilisateur_business' => $id_ub,
            'id_utilisateur_application' => $id_ua,
        );
    }

    /**
     * @return string message d’erreur ou chaîne vide
     */
    private function _validate_employe_poste_selection() {
        $v = $this->input->post('id_ref_poste_fonction');
        if ($v === null || $v === '') {
            return '<p>Veuillez choisir un poste / une fonction, ou « Libellé libre » pour une saisie manuelle (ex. import).</p>';
        }
        if ($v === '-1') {
            $p = trim((string) $this->input->post('poste_libre'));
            if (mb_strlen($p) < 2) {
                return '<p>Indiquez le poste en saisie libre (au moins 2 caractères).</p>';
            }
            return '';
        }
        $id = (int) $v;
        if ($id < 1) {
            return '<p>Poste / fonction invalide.</p>';
        }
        $ref = $this->ref_poste_fonction_model->get_by_id($id);
        if (!$ref) {
            return '<p>Poste / fonction invalide.</p>';
        }
        if (!empty($ref['is_autre'])) {
            $p = trim((string) $this->input->post('poste_autre'));
            if (mb_strlen($p) < 2) {
                return '<p>Précisez la fonction pour « Autre » (au moins 2 caractères).</p>';
            }
        }
        return '';
    }

    /**
     * @param array $payload résultat de _employe_payload_from_post
     */
    private function _flash_after_employe_save(array $payload, $is_create = true) {
        $base = $is_create ? 'Employé enregistré.' : 'Employé mis à jour.';
        $tel = isset($payload['telephone']) ? trim((string) $payload['telephone']) : '';
        if ($tel !== '') {
            $raw = $this->User_model->find_users_by_phone_match($tel);
            if (count($raw) > 1) {
                $base .= ' Plusieurs comptes utilisateur RIPA correspondent à ce numéro : la liaison avec l’application n’a pas été enregistrée automatiquement.';
            }
        }
        return $base;
    }

    private function _load_phpspreadsheet() {
        $p = FCPATH . 'vendor/autoload.php';
        if (!is_file($p)) {
            return false;
        }
        require_once $p;
        return class_exists('\PhpOffice\PhpSpreadsheet\IOFactory');
    }

    private function _can_write() {
        return in_array((string) $this->session->userdata('business_role'), self::$roles_write, true);
    }

    private function _require_write() {
        $this->require_business_roles(self::$roles_write);
    }
}
