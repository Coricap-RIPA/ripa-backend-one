<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * API Business (Portail marchand) — API-first
 * JWT obligatoire pour toutes les routes sauf auth/login.
 *
 * Principes:
 * - Logs API via Api_log_model (sans données sensibles, sanitize_for_log())
 * - Aucune donnée carte (PAN/CVV/token complet) dans les logs (PCI DSS)
 */
class Apibusiness extends CI_Controller {

    /** Durée de validité du JWT en secondes (7 jours) */
    const JWT_EXPIRY_SECONDS = 7 * 24 * 60 * 60;

    /** Upload KYB: 3 Mo */
    const KYB_UPLOAD_MAX_BYTES = 3145728;

    /** Timestamp début requête (ms) */
    public $start_time;

    public function __construct() {
        parent::__construct();
        $this->start_time = microtime(true);

        $this->load->library('JWT_Library');
        $this->load->library('Response_format');
        $this->load->library('Rate_limit_library');
        $this->load->helper('custom_helper');

        // Logs standardisés (sans payload sensible)
        $this->load->model('Api_log_model');

        // Modèles portail business
        $this->load->model('business/utilisateur_business_model', 'ub_model');
        $this->load->model('business/business_marchand_model');
        $this->load->model('business/business_kyb_model');
        $this->load->model('business/employe_model');
        $this->load->model('business/service_model');
        $this->load->model('business/business_transaction_model');

        // CORS: activable si un jour le portail web est séparé
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Request-Id');
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }

        $this->rate_limit_library->enforce();
    }

    // =========================================================================
    // AUTH
    // =========================================================================

    /**
     * POST /api/business/auth/login
     * Body JSON: { email, password }
     */
    public function auth_login() {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $json = json_decode(file_get_contents('php://input'), true);
        $email = isset($json['email']) ? strtolower(trim((string) $json['email'])) : '';
        $password = isset($json['password']) ? (string) $json['password'] : '';
        if ($email === '' || $password === '') {
            $this->response_format->send_error('Email et mot de passe requis', 400);
        }

        $ub = $this->ub_model->get_by_email($email);
        if (empty($ub) || empty($ub['actif']) || !password_verify($password, $ub['mot_de_passe'])) {
            $this->_log_business_api('auth_login', 401, 'Invalid credentials');
            $this->response_format->send_error('Email ou mot de passe incorrect', 401);
        }

        $token_payload = array(
            'ub_id' => (int) $ub['id'],
            'id_marchand' => (int) $ub['id_marchand'],
            'role' => (string) $ub['role'],
            'exp' => time() + self::JWT_EXPIRY_SECONDS,
            'iat' => time(),
        );
        $jwt = $this->jwt_library->encode($token_payload);
        $this->_log_business_api('auth_login', 200, 'OK');
        $this->response_format->send_success(array(
            'token' => $jwt,
            'user' => array(
                'id' => (int) $ub['id'],
                'email' => (string) $ub['email'],
                'role' => (string) $ub['role'],
                'id_marchand' => (int) $ub['id_marchand'],
            ),
        ), 'Connexion réussie');
    }

    /** GET /api/business/auth/me */
    public function auth_me() {
        $auth = $this->require_business_auth();
        $this->_log_business_api('auth_me', 200, 'OK');
        $this->response_format->send_success(array(
            'ub_id' => (int) $auth['ub_id'],
            'id_marchand' => (int) $auth['id_marchand'],
            'role' => (string) $auth['role'],
        ));
    }

    // =========================================================================
    // INSCRIPTION / MARCHAND
    // =========================================================================

    /** POST /api/business/register — crée une demande marchand */
    public function register() {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $json = json_decode(file_get_contents('php://input'), true);
        $rs = isset($json['raison_sociale']) ? trim((string) $json['raison_sociale']) : '';
        $em = isset($json['email_contact']) ? trim((string) $json['email_contact']) : '';
        $tel = isset($json['telephone_contact']) ? trim((string) $json['telephone_contact']) : '';
        $identifiant_legal = isset($json['identifiant_legal']) ? trim((string) $json['identifiant_legal']) : null;
        if (mb_strlen($rs) < 2 || $em === '' || $tel === '') {
            $this->response_format->send_error('raison_sociale, email_contact, telephone_contact requis', 400);
        }
        if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            $this->response_format->send_error('email_contact invalide', 400);
        }
        if ($this->business_marchand_model->exists_by_email($em)) {
            $this->response_format->send_error('Cet email est déjà utilisé pour un autre marchand.', 409);
        }
        if ($this->business_marchand_model->exists_by_phone($tel)) {
            $this->response_format->send_error('Ce numéro de téléphone est déjà utilisé pour un autre marchand.', 409);
        }
        $id = $this->business_marchand_model->create_demande($rs, $em, $tel, null, $identifiant_legal);
        if (!$id) {
            $this->_log_business_api('register', 500, 'DB error');
            $this->response_format->send_error('Erreur lors de l’enregistrement', 500);
        }
        $this->_log_business_api('register', 200, 'OK');
        $this->response_format->send_success(array('id_marchand' => (int) $id), 'Demande enregistrée');
    }

    /** GET /api/business/marchand — profil entreprise (business_marchand) */
    public function marchand_get() {
        $auth = $this->require_business_auth();
        $id_m = (int) $auth['id_marchand'];
        $m = $this->business_marchand_model->get_by_id($id_m);
        if (!$m) {
            $this->_log_business_api('marchand_get', 404, 'Not found');
            $this->response_format->send_error('Entreprise introuvable', 404);
        }
        $this->_log_business_api('marchand_get', 200, 'OK');
        $this->response_format->send_success(array('item' => $m));
    }

    // =========================================================================
    // EMPLOYÉS
    // =========================================================================

    /** GET /api/business/employes */
    public function employes_index() {
        $auth = $this->require_business_auth();
        $id_m = (int) $auth['id_marchand'];
        $list = $this->employe_model->get_by_marchand($id_m);
        $this->_log_business_api('employes_index', 200, 'OK');
        $this->response_format->send_success(array('items' => $list));
    }

    /** GET /api/business/employes/{id} */
    public function employes_show($id) {
        $auth = $this->require_business_auth();
        $id_m = (int) $auth['id_marchand'];
        $row = $this->employe_model->get_by_id((int) $id, $id_m);
        if (!$row) {
            $this->_log_business_api('employes_show', 404, 'Not found');
            $this->response_format->send_error('Employé introuvable', 404);
        }
        $this->_log_business_api('employes_show', 200, 'OK');
        $this->response_format->send_success(array('item' => $row));
    }

    /** POST /api/business/employes */
    public function employes_create() {
        $auth = $this->require_business_auth(array('administrateur', 'gestionnaire'));
        $id_m = (int) $auth['id_marchand'];
        $json = json_decode(file_get_contents('php://input'), true);
        if (!is_array($json)) {
            $this->response_format->send_error('Body JSON invalide', 400);
        }
        $payload = $this->_coerce_employe_payload($json, $id_m);
        $id = $this->employe_model->insert_row($id_m, $payload);
        $this->_log_business_api('employes_create', 200, 'OK');
        $this->response_format->send_success(array('id' => (int) $id), 'Employé enregistré');
    }

    /** PUT /api/business/employes/{id} */
    public function employes_update($id) {
        $auth = $this->require_business_auth(array('administrateur', 'gestionnaire'));
        $id_m = (int) $auth['id_marchand'];
        $cur = $this->employe_model->get_by_id((int) $id, $id_m);
        if (!$cur) {
            $this->_log_business_api('employes_update', 404, 'Not found');
            $this->response_format->send_error('Employé introuvable', 404);
        }
        $json = json_decode(file_get_contents('php://input'), true);
        if (!is_array($json)) {
            $this->response_format->send_error('Body JSON invalide', 400);
        }
        $payload = $this->_coerce_employe_payload($json, $id_m, $cur);
        $this->employe_model->update_row((int) $id, $id_m, $payload);
        $this->_log_business_api('employes_update', 200, 'OK');
        $this->response_format->send_success(null, 'Employé mis à jour');
    }

    /** DELETE /api/business/employes/{id} */
    public function employes_delete($id) {
        $auth = $this->require_business_auth(array('administrateur', 'gestionnaire'));
        $id_m = (int) $auth['id_marchand'];
        $this->employe_model->delete_row((int) $id, $id_m);
        $this->_log_business_api('employes_delete', 200, 'OK');
        $this->response_format->send_success(null, 'Employé supprimé');
    }

    // =========================================================================
    // SERVICES
    // =========================================================================

    /** GET /api/business/services */
    public function services_index() {
        $auth = $this->require_business_auth();
        $id_m = (int) $auth['id_marchand'];
        $list = $this->service_model->get_by_marchand($id_m);
        $this->_log_business_api('services_index', 200, 'OK');
        $this->response_format->send_success(array('items' => $list));
    }

    /** GET /api/business/services/{id} */
    public function services_show($id) {
        $auth = $this->require_business_auth();
        $id_m = (int) $auth['id_marchand'];
        $row = $this->service_model->get_by_id((int) $id, $id_m);
        if (!$row) {
            $this->_log_business_api('services_show', 404, 'Not found');
            $this->response_format->send_error('Service introuvable', 404);
        }
        $this->_log_business_api('services_show', 200, 'OK');
        $this->response_format->send_success(array('item' => $row));
    }

    /** POST /api/business/services */
    public function services_create() {
        $auth = $this->require_business_auth(array('administrateur', 'gestionnaire'));
        $id_m = (int) $auth['id_marchand'];
        $json = json_decode(file_get_contents('php://input'), true);
        if (!is_array($json)) {
            $this->response_format->send_error('Body JSON invalide', 400);
        }
        $libelle = isset($json['libelle']) ? trim((string) $json['libelle']) : '';
        if (mb_strlen($libelle) < 2) {
            $this->response_format->send_error('libelle requis', 400);
        }
        $id = $this->service_model->insert_row($id_m, array(
            'libelle' => $libelle,
            'description' => isset($json['description']) && trim((string) $json['description']) !== '' ? trim((string) $json['description']) : null,
            'code' => isset($json['code']) && trim((string) $json['code']) !== '' ? trim((string) $json['code']) : null,
            'actif' => !empty($json['actif']) ? 1 : 0,
            'ordre' => isset($json['ordre']) ? (int) $json['ordre'] : 0,
        ));
        $this->_log_business_api('services_create', 200, 'OK');
        $this->response_format->send_success(array('id' => (int) $id), 'Service créé');
    }

    /** PUT /api/business/services/{id} */
    public function services_update($id) {
        $auth = $this->require_business_auth(array('administrateur', 'gestionnaire'));
        $id_m = (int) $auth['id_marchand'];
        $cur = $this->service_model->get_by_id((int) $id, $id_m);
        if (!$cur) {
            $this->_log_business_api('services_update', 404, 'Not found');
            $this->response_format->send_error('Service introuvable', 404);
        }
        $json = json_decode(file_get_contents('php://input'), true);
        if (!is_array($json)) {
            $this->response_format->send_error('Body JSON invalide', 400);
        }
        $libelle = isset($json['libelle']) ? trim((string) $json['libelle']) : '';
        if (mb_strlen($libelle) < 2) {
            $this->response_format->send_error('libelle requis', 400);
        }
        $this->service_model->update_row((int) $id, $id_m, array(
            'libelle' => $libelle,
            'description' => isset($json['description']) && trim((string) $json['description']) !== '' ? trim((string) $json['description']) : null,
            'code' => isset($json['code']) && trim((string) $json['code']) !== '' ? trim((string) $json['code']) : null,
            'actif' => !empty($json['actif']) ? 1 : 0,
            'ordre' => isset($json['ordre']) ? (int) $json['ordre'] : 0,
        ));
        $this->_log_business_api('services_update', 200, 'OK');
        $this->response_format->send_success(null, 'Service mis à jour');
    }

    /** DELETE /api/business/services/{id} */
    public function services_delete($id) {
        $auth = $this->require_business_auth(array('administrateur', 'gestionnaire'));
        $id_m = (int) $auth['id_marchand'];
        $this->service_model->delete_row((int) $id, $id_m);
        $this->_log_business_api('services_delete', 200, 'OK');
        $this->response_format->send_success(null, 'Service supprimé');
    }

    // =========================================================================
    // TRANSACTIONS
    // =========================================================================

    /** GET /api/business/transactions?limit=300 */
    public function transactions_index() {
        $auth = $this->require_business_auth();
        $id_m = (int) $auth['id_marchand'];
        $limit = (int) $this->input->get('limit');
        if ($limit < 1 || $limit > 500) {
            $limit = 300;
        }
        $list = $this->business_transaction_model->get_by_marchand($id_m, $limit);
        $this->_log_business_api('transactions_index', 200, 'OK');
        $this->response_format->send_success(array('items' => $list));
    }

    /** GET /api/business/transactions/{id} */
    public function transactions_show($id) {
        $auth = $this->require_business_auth();
        $id_m = (int) $auth['id_marchand'];
        $row = $this->db->get_where('business_transaction', array('id' => (int) $id, 'id_marchand' => $id_m))->row_array();
        if (!$row) {
            $this->_log_business_api('transactions_show', 404, 'Not found');
            $this->response_format->send_error('Transaction introuvable', 404);
        }
        $this->_log_business_api('transactions_show', 200, 'OK');
        $this->response_format->send_success(array('item' => $row));
    }

    /** POST /api/business/transactions */
    public function transactions_create() {
        $auth = $this->require_business_auth(array('administrateur', 'gestionnaire'));
        $id_m = (int) $auth['id_marchand'];
        $id_ub = (int) $auth['ub_id'];
        $dossier_kyb = $this->business_kyb_model->get_by_marchand($id_m);
        if (!$this->business_kyb_model->is_approved_for_operations($dossier_kyb)) {
            $this->_log_business_api('transactions_create', 403, 'KYB blocked');
            $this->response_format->send_error('Dossier KYB non approuvé ou expiré : impossible d’enregistrer une transaction.', 403);
        }
        $json = json_decode(file_get_contents('php://input'), true);
        if (!is_array($json)) {
            $this->response_format->send_error('Body JSON invalide', 400);
        }
        $montant = isset($json['montant']) ? (float) $json['montant'] : 0.0;
        $type_operation = isset($json['type_operation']) ? trim((string) $json['type_operation']) : '';
        $sens = isset($json['sens']) ? trim((string) $json['sens']) : '';
        if ($montant <= 0 || $type_operation === '' || !in_array($sens, array('debit', 'credit'), true)) {
            $this->response_format->send_error('Champs transaction invalides', 400);
        }
        $id_service = isset($json['id_service']) ? (int) $json['id_service'] : null;
        $id_employe = isset($json['id_employe']) ? (int) $json['id_employe'] : null;
        if ($id_service) {
            $s = $this->service_model->get_by_id($id_service, $id_m);
            if (!$s) {
                $id_service = null;
            }
        }
        if ($id_employe) {
            $e = $this->employe_model->get_by_id($id_employe, $id_m);
            if (!$e) {
                $id_employe = null;
            }
        }
        $this->business_transaction_model->insert_row($id_m, array(
            'id_service' => $id_service,
            'id_utilisateur_business' => $id_ub,
            'id_employe' => $id_employe,
            'type_operation' => $type_operation,
            'sens' => $sens,
            'montant' => abs($montant),
            'devise' => isset($json['devise']) && trim((string) $json['devise']) !== '' ? trim((string) $json['devise']) : 'USD',
            'libelle' => isset($json['libelle']) ? trim((string) $json['libelle']) : null,
            'statut' => 'valide',
            'id_paiement_ripa' => null,
            'meta_json' => null,
        ));
        $this->_log_business_api('transactions_create', 200, 'OK');
        $this->response_format->send_success(null, 'Transaction enregistrée');
    }

    /** DELETE /api/business/transactions/{id} */
    public function transactions_delete($id) {
        $auth = $this->require_business_auth(array('administrateur', 'gestionnaire'));
        $id_m = (int) $auth['id_marchand'];
        $dossier_kyb = $this->business_kyb_model->get_by_marchand($id_m);
        if (!$this->business_kyb_model->is_approved_for_operations($dossier_kyb)) {
            $this->_log_business_api('transactions_delete', 403, 'KYB blocked');
            $this->response_format->send_error('Dossier KYB non approuvé ou expiré : impossible de supprimer une transaction.', 403);
        }
        $this->db->where('id', (int) $id);
        $this->db->where('id_marchand', (int) $id_m);
        $this->db->delete('business_transaction');
        $this->_log_business_api('transactions_delete', 200, 'OK');
        $this->response_format->send_success(null, 'Transaction supprimée');
    }

    // =========================================================================
    // KYB
    // =========================================================================

    /** GET /api/business/kyb */
    public function kyb_get() {
        $auth = $this->require_business_auth();
        $id_m = (int) $auth['id_marchand'];
        $dossier = $this->business_kyb_model->get_by_marchand($id_m);
        $meta = $this->_kyb_build_meta($dossier);
        $this->_log_business_api('kyb_get', 200, 'OK');
        $this->response_format->send_success(array(
            'item' => $dossier,
            'meta' => $meta,
        ));
    }

    /**
     * DELETE /api/business/kyb/dossier — supprime le dossier rejeté (pièces + ligne) pour permettre une nouvelle soumission.
     */
    public function kyb_delete() {
        $auth = $this->require_business_auth(array('administrateur', 'gestionnaire'));
        $m = strtoupper((string) $this->input->method(TRUE));
        if ($m !== 'DELETE' && $m !== 'POST') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $id_m = (int) $auth['id_marchand'];
        $ok = $this->business_kyb_model->delete_for_marchand_if_rejected($id_m);
        if (!$ok) {
            $this->_log_business_api('kyb_delete', 409, 'Not rejected or no row');
            $this->response_format->send_error('Suppression impossible : aucun dossier rejeté à effacer.', 409);
        }
        $this->_log_business_api('kyb_delete', 200, 'OK');
        $this->response_format->send_success(null, 'Dossier KYB supprimé. Vous pouvez soumettre un nouveau dossier.');
    }

    /**
     * POST /api/business/kyb/submit
     * Body JSON: champs dossier (sans upload dans cette version API).
     */
    public function kyb_submit() {
        $auth = $this->require_business_auth(array('administrateur', 'gestionnaire'));
        $id_m = (int) $auth['id_marchand'];
        $existing = $this->business_kyb_model->get_by_marchand($id_m);
        if ($existing && $existing['statut'] === 'en_attente') {
            $this->response_format->send_error('Dossier déjà en cours de vérification', 409);
        }
        if ($existing && $existing['statut'] === 'valide' && !$this->business_kyb_model->is_expired($existing)) {
            $this->response_format->send_error('Dossier validé : modifications et renouvellement via le back-office RIPA jusqu’à expiration.', 409);
        }
        $json = json_decode(file_get_contents('php://input'), true);
        if (!is_array($json)) {
            $this->response_format->send_error('Body JSON invalide', 400);
        }
        $required = array('denomination_sociale', 'numero_identification_legal', 'adresse_siege', 'telephone', 'email_contact');
        foreach ($required as $k) {
            if (empty($json[$k]) || trim((string) $json[$k]) === '') {
                $this->response_format->send_error('Champ requis: ' . $k, 400);
            }
        }
        if (!filter_var((string) $json['email_contact'], FILTER_VALIDATE_EMAIL)) {
            $this->response_format->send_error('email_contact invalide', 400);
        }
        $now = date('Y-m-d H:i:s');
        $payload = array(
            'statut' => 'en_attente',
            'denomination_sociale' => trim((string) $json['denomination_sociale']),
            'numero_identification_legal' => trim((string) $json['numero_identification_legal']),
            'adresse_siege' => trim((string) $json['adresse_siege']),
            'ville' => isset($json['ville']) && trim((string) $json['ville']) !== '' ? trim((string) $json['ville']) : null,
            'pays' => isset($json['pays']) && trim((string) $json['pays']) !== '' ? trim((string) $json['pays']) : null,
            'site_web' => isset($json['site_web']) && trim((string) $json['site_web']) !== '' ? trim((string) $json['site_web']) : null,
            'activite_principale' => isset($json['activite_principale']) && trim((string) $json['activite_principale']) !== '' ? trim((string) $json['activite_principale']) : null,
            'effectif_tranche' => isset($json['effectif_tranche']) && trim((string) $json['effectif_tranche']) !== '' ? trim((string) $json['effectif_tranche']) : null,
            'telephone' => trim((string) $json['telephone']),
            'email_contact' => strtolower(trim((string) $json['email_contact'])),
            'commentaire_marchand' => isset($json['commentaire_marchand']) && trim((string) $json['commentaire_marchand']) !== '' ? trim((string) $json['commentaire_marchand']) : null,
            'date_soumission' => $now,
            'date_decision' => null,
            'date_fin_validite' => null,
            'id_utilisateur_validateur' => null,
            'motif_refus' => null,
        );
        if ($existing) {
            $db_ok = $this->business_kyb_model->update_by_marchand($id_m, $payload);
        } else {
            $payload['id_marchand'] = $id_m;
            $new_id = $this->business_kyb_model->insert_row($payload);
            $db_ok = $new_id > 0;
        }
        $row_after = $this->business_kyb_model->get_by_marchand($id_m);
        if (!$db_ok || empty($row_after) || ($row_after['statut'] ?? '') !== 'en_attente') {
            $this->_log_business_api('kyb_submit', 500, 'DB insert/update or verify failed');
            $this->response_format->send_error('Enregistrement du dossier KYB impossible (vérifiez la base, la clé étrangère id_marchand, les migrations, ou contactez le support).', 500);
        }
        $this->_log_business_api('kyb_submit', 200, 'OK');
        $this->response_format->send_success(null, 'Dossier KYB soumis');
    }

    /**
     * POST /api/business/kyb/submit-upload (multipart/form-data)
     * Champs: denomination_sociale, numero_identification_legal, adresse_siege, telephone, email_contact, ...
     * Files: piece_legal (optionnel), piece_complement (optionnel)
     */
    public function kyb_submit_upload() {
        $auth = $this->require_business_auth(array('administrateur', 'gestionnaire'));
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $id_m = (int) $auth['id_marchand'];
        $existing = $this->business_kyb_model->get_by_marchand($id_m);
        if ($existing && $existing['statut'] === 'en_attente') {
            $this->response_format->send_error('Dossier déjà en cours de vérification', 409);
        }
        if ($existing && $existing['statut'] === 'valide' && !$this->business_kyb_model->is_expired($existing)) {
            $this->response_format->send_error('Dossier validé : modifications et renouvellement via le back-office RIPA jusqu’à expiration.', 409);
        }
        $denom = trim((string) $this->input->post('denomination_sociale'));
        $nlegal = trim((string) $this->input->post('numero_identification_legal'));
        $addr = trim((string) $this->input->post('adresse_siege'));
        $tel = trim((string) $this->input->post('telephone'));
        $email = strtolower(trim((string) $this->input->post('email_contact')));
        if ($denom === '' || $nlegal === '' || $addr === '' || $tel === '' || $email === '') {
            $this->response_format->send_error('Champs requis manquants', 400);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->response_format->send_error('email_contact invalide', 400);
        }

        // Diagnostic : savoir si PHP a bien peuplé $_FILES (sans logger les chemins tmp).
        $this->_kyb_log_incoming_files_summary();

        $why1 = null;
        $why2 = null;
        $f1 = $this->_kyb_handle_upload('piece_legal', $id_m, $existing ? $existing['fichier_piece_legal'] : null, $why1);
        $f2 = $this->_kyb_handle_upload('piece_complement', $id_m, $existing ? $existing['fichier_piece_complement'] : null, $why2);

        if ($f1 === false) {
            $this->response_format->send_error($this->_kyb_upload_reject_message('piece_legal', $why1), 400);
        }
        if ($f2 === false) {
            $this->response_format->send_error($this->_kyb_upload_reject_message('piece_complement', $why2), 400);
        }

        $now = date('Y-m-d H:i:s');
        $payload = array(
            'statut' => 'en_attente',
            'denomination_sociale' => $denom,
            'numero_identification_legal' => $nlegal,
            'adresse_siege' => $addr,
            'ville' => trim((string) $this->input->post('ville')) ?: null,
            'pays' => trim((string) $this->input->post('pays')) ?: null,
            'site_web' => trim((string) $this->input->post('site_web')) ?: null,
            'activite_principale' => trim((string) $this->input->post('activite_principale')) ?: null,
            'effectif_tranche' => trim((string) $this->input->post('effectif_tranche')) ?: null,
            'telephone' => $tel,
            'email_contact' => $email,
            'commentaire_marchand' => trim((string) $this->input->post('commentaire_marchand')) ?: null,
            'date_soumission' => $now,
            'date_decision' => null,
            'date_fin_validite' => null,
            'id_utilisateur_validateur' => null,
            'motif_refus' => null,
        );
        if ($f1 !== null) {
            $payload['fichier_piece_legal'] = $f1;
        } elseif ($existing && !empty($existing['fichier_piece_legal'])) {
            $payload['fichier_piece_legal'] = $existing['fichier_piece_legal'];
        }
        if ($f2 !== null) {
            $payload['fichier_piece_complement'] = $f2;
        } elseif ($existing && !empty($existing['fichier_piece_complement'])) {
            $payload['fichier_piece_complement'] = $existing['fichier_piece_complement'];
        }

        if ($existing) {
            $db_ok = $this->business_kyb_model->update_by_marchand($id_m, $payload);
        } else {
            $payload['id_marchand'] = $id_m;
            $new_id = $this->business_kyb_model->insert_row($payload);
            $db_ok = $new_id > 0;
        }
        $row_after = $this->business_kyb_model->get_by_marchand($id_m);
        if (!$db_ok || empty($row_after) || ($row_after['statut'] ?? '') !== 'en_attente') {
            $this->_log_business_api('kyb_submit_upload', 500, 'DB insert/update or verify failed');
            $this->response_format->send_error('Enregistrement du dossier KYB impossible (vérifiez la base, la clé étrangère id_marchand, les migrations, ou contactez le support).', 500);
        }
        $this->_log_business_api('kyb_submit_upload', 200, 'OK');
        $this->response_format->send_success(null, 'Dossier KYB soumis');
    }

    // =========================================================================
    // UTILISATEURS PORTAIL (utilisateur_business)
    // =========================================================================

    /** GET /api/business/portal-users */
    public function portal_users_index() {
        $auth = $this->require_business_auth(array('administrateur', 'gestionnaire', 'lecteur_seul'));
        $id_m = (int) $auth['id_marchand'];
        $list = $this->ub_model->get_by_marchand($id_m);
        $this->_log_business_api('portal_users_index', 200, 'OK');
        $this->response_format->send_success(array('items' => $list));
    }

    /** POST /api/business/portal-users/create (admin only) */
    public function portal_users_create() {
        $auth = $this->require_business_auth(array('administrateur'));
        $id_m = (int) $auth['id_marchand'];
        $json = json_decode(file_get_contents('php://input'), true);
        if (!is_array($json)) {
            $this->response_format->send_error('Body JSON invalide', 400);
        }
        $email = isset($json['email']) ? strtolower(trim((string) $json['email'])) : '';
        $password = isset($json['password']) ? (string) $json['password'] : '';
        $role = isset($json['role']) ? trim((string) $json['role']) : 'lecteur_seul';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->response_format->send_error('Email invalide', 400);
        }
        if (strlen($password) < 8) {
            $this->response_format->send_error('Mot de passe trop court (min 8)', 400);
        }
        if (!in_array($role, array('administrateur', 'gestionnaire', 'lecteur_seul'), true)) {
            $this->response_format->send_error('Role invalide', 400);
        }
        if ($this->ub_model->get_by_email($email)) {
            $this->response_format->send_error('Email déjà utilisé', 409);
        }
        $id = $this->ub_model->insert_row(array(
            'id_marchand' => $id_m,
            'email' => $email,
            'mot_de_passe' => password_hash($password, PASSWORD_BCRYPT),
            'role' => $role,
            'doit_changer_mot_de_passe' => 1,
            'actif' => 1,
        ));
        $this->_log_business_api('portal_users_create', 200, 'OK');
        $this->response_format->send_success(array('id' => (int) $id), 'Compte portail créé');
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    /**
     * @param array<string,mixed> $allowed_roles
     * @return array{ub_id:int,id_marchand:int,role:string}
     */
    private function require_business_auth(array $allowed_roles = array('administrateur', 'gestionnaire', 'lecteur_seul')) {
        $headers = $this->input->request_headers();
        $auth = null;
        foreach ($headers as $k => $v) {
            if (strtolower($k) === 'authorization') {
                $auth = $v;
                break;
            }
        }
        if (!$auth || stripos($auth, 'Bearer ') !== 0) {
            $this->_log_business_api('auth', 401, 'Missing token');
            $this->response_format->send_error('Token manquant', 401);
        }
        $token = trim(substr($auth, strlen('Bearer ')));
        try {
            $decoded = $this->jwt_library->decode($token);
        } catch (Throwable $e) {
            $this->_log_business_api('auth', 401, 'Invalid token');
            $this->response_format->send_error('Token invalide', 401);
        }
        $ub_id = isset($decoded->ub_id) ? (int) $decoded->ub_id : 0;
        $id_m = isset($decoded->id_marchand) ? (int) $decoded->id_marchand : 0;
        $role = isset($decoded->role) ? (string) $decoded->role : '';
        if ($ub_id < 1 || $id_m < 1 || $role === '') {
            $this->_log_business_api('auth', 401, 'Invalid token payload');
            $this->response_format->send_error('Token invalide', 401);
        }
        if (!in_array($role, $allowed_roles, true)) {
            $this->_log_business_api('auth', 403, 'Forbidden');
            $this->response_format->send_error('Accès refusé', 403);
        }
        return array('ub_id' => $ub_id, 'id_marchand' => $id_m, 'role' => $role);
    }

    /**
     * Coerce + whitelist champs employés; conserve departement texte si besoin.
     */
    private function _coerce_employe_payload(array $in, $id_marchand, $previous_row = null) {
        $nom = isset($in['nom']) ? trim((string) $in['nom']) : '';
        $prenom = isset($in['prenom']) ? trim((string) $in['prenom']) : '';
        if (mb_strlen($nom) < 2 || mb_strlen($prenom) < 2) {
            $this->response_format->send_error('Nom et prénom requis', 400);
        }
        $id_service = isset($in['id_service']) && $in['id_service'] !== '' ? (int) $in['id_service'] : null;
        $departement = null;
        if ($id_service) {
            $sv = $this->service_model->get_by_id($id_service, (int) $id_marchand);
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
        $out = array(
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => isset($in['email']) && trim((string) $in['email']) !== '' ? trim((string) $in['email']) : null,
            'telephone' => isset($in['telephone']) && trim((string) $in['telephone']) !== '' ? trim((string) $in['telephone']) : null,
            'poste' => isset($in['poste']) && trim((string) $in['poste']) !== '' ? trim((string) $in['poste']) : null,
            'id_ref_poste_fonction' => isset($in['id_ref_poste_fonction']) && (int) $in['id_ref_poste_fonction'] > 0 ? (int) $in['id_ref_poste_fonction'] : null,
            'matricule' => isset($in['matricule']) && trim((string) $in['matricule']) !== '' ? trim((string) $in['matricule']) : null,
            'id_service' => $id_service,
            'departement' => $departement,
            'date_entree' => isset($in['date_entree']) && trim((string) $in['date_entree']) !== '' ? trim((string) $in['date_entree']) : null,
            'ville' => isset($in['ville']) && trim((string) $in['ville']) !== '' ? trim((string) $in['ville']) : null,
            'pays' => isset($in['pays']) && trim((string) $in['pays']) !== '' ? strtoupper(substr(trim((string) $in['pays']), 0, 3)) : null,
            'actif' => !empty($in['actif']) ? 1 : 0,
            'id_utilisateur_business' => isset($in['id_utilisateur_business']) && (int) $in['id_utilisateur_business'] > 0 ? (int) $in['id_utilisateur_business'] : null,
            'id_utilisateur_application' => isset($in['id_utilisateur_application']) && (int) $in['id_utilisateur_application'] > 0 ? (int) $in['id_utilisateur_application'] : null,
        );
        return sanitize_for_log($out) ? $out : $out;
    }

    private function _request_id() {
        $headers = $this->input->request_headers();
        foreach ($headers as $k => $v) {
            if (strtolower($k) === 'x-request-id' && trim((string) $v) !== '') {
                return trim((string) $v);
            }
        }
        try {
            return bin2hex(random_bytes(10));
        } catch (Throwable $e) {
            return (string) time();
        }
    }

    private function _log_business_api($action, $status_code = null, $message = null) {
        $duration_ms = (int) round((microtime(true) - $this->start_time) * 1000);
        $this->Api_log_model->insert_log(array(
            'service' => 'business_api',
            'action' => (string) $action,
            'method' => $this->input->method(TRUE),
            'request_id' => $this->_request_id(),
            'status_code' => $status_code !== null ? (int) $status_code : null,
            'duration_ms' => $duration_ms,
            'message' => $message !== null ? (string) $message : null,
        ));
    }

    /**
     * Méta KYB pour le portail (transactions, soumission, expiration 1 an).
     *
     * @param array|null $dossier
     * @return array<string,mixed>
     */
    private function _kyb_build_meta($dossier) {
        $approved = $this->business_kyb_model->is_approved_for_operations($dossier);
        $portal = $this->business_kyb_model->portal_may_submit($dossier);
        $expired = !empty($dossier) && is_array($dossier) && ($dossier['statut'] ?? '') === 'valide'
            && $this->business_kyb_model->is_expired($dossier);
        $end = (empty($dossier) || !is_array($dossier)) ? null : $this->business_kyb_model->get_date_fin_validite($dossier);
        return array(
            'kyb_approved_for_transactions' => $approved,
            'portal_may_submit_kyb' => $portal,
            'kyb_expired' => $expired,
            'date_fin_validite' => $end,
        );
    }

    /**
     * Fichier d’upload PHP présent sous un répertoire temporaire (chemins réels différents selon OS / php.ini).
     */
    private function _kyb_upload_tmp_is_allowed($tmp_path) {
        if (!is_string($tmp_path) || $tmp_path === '') {
            return false;
        }
        $tmp_real = @realpath($tmp_path);
        if ($tmp_real === false || !is_file($tmp_path)) {
            return false;
        }
        $bases = array();
        foreach (array(ini_get('upload_tmp_dir'), sys_get_temp_dir()) as $d) {
            if ($d !== '' && $d !== false && @is_dir($d)) {
                $r = @realpath($d);
                if ($r !== false) {
                    $bases[] = $r;
                }
            }
        }
        foreach (array('/tmp', '/private/tmp', '/var/tmp') as $d) {
            if (@is_dir($d)) {
                $r = @realpath($d);
                if ($r !== false) {
                    $bases[] = $r;
                }
            }
        }
        $bases = array_values(array_unique($bases));
        $ds = DIRECTORY_SEPARATOR;
        foreach ($bases as $base) {
            if ($tmp_real === $base || strpos($tmp_real, $base . $ds) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Vérifie les premiers octets (finfo renvoie souvent application/octet-stream sur les PDF).
     * Certains PDF exportés ont un BOM UTF-8 ou des blancs avant %PDF.
     */
    private function _kyb_magic_matches_extension($tmp_path, $ext) {
        $h = @fopen($tmp_path, 'rb');
        if (!$h) {
            return false;
        }
        $head = fread($h, 64);
        fclose($h);
        if ($head === false || $head === '') {
            return false;
        }
        $ext = strtolower((string) $ext);
        if ($ext === 'pdf') {
            $p = $head;
            $p = preg_replace('/^[\x00-\x20\x7F]+/', '', $p);
            if (strncmp($p, "\xEF\xBB\xBF", 3) === 0) {
                $p = substr($p, 3);
            }
            if (strncmp($p, '%PDF', 4) === 0) {
                return true;
            }
            // Même logique sur les 1024 premiers octets (préfixe rare, sans chercher %PDF au milieu du fichier).
            $more = @file_get_contents($tmp_path, false, null, 0, 1024);
            if (is_string($more) && $more !== '') {
                $p2 = preg_replace('/^[\x00-\x20\x7F]+/', '', $more);
                if (strncmp($p2, "\xEF\xBB\xBF", 3) === 0) {
                    $p2 = substr($p2, 3);
                }
                if (strncmp($p2, '%PDF', 4) === 0) {
                    return true;
                }
            }
            return false;
        }
        if ($ext === 'png') {
            return strlen($head) >= 8 && substr($head, 0, 8) === "\x89PNG\r\n\x1a\n";
        }
        if ($ext === 'jpg' || $ext === 'jpeg') {
            return strlen($head) >= 3 && ord($head[0]) === 0xFF && ord($head[1]) === 0xD8 && ord($head[2]) === 0xFF;
        }
        return false;
    }

    /**
     * Log synthétique des entrées fichier (codes d’erreur PHP, tailles) pour diagnostiquer les soumissions KYB.
     */
    private function _kyb_log_incoming_files_summary() {
        $one = function ($key) {
            if (!isset($_FILES[$key]) || !is_array($_FILES[$key])) {
                return $key . '=absent';
            }
            $e = isset($_FILES[$key]['error']) ? (int) $_FILES[$key]['error'] : -1;
            $s = isset($_FILES[$key]['size']) ? (int) $_FILES[$key]['size'] : -1;
            $has_tmp = !empty($_FILES[$key]['tmp_name']);
            return $key . '=(err:' . $e . ',size:' . $s . ',tmp:' . ($has_tmp ? '1' : '0') . ')';
        };
        log_message('info', 'kyb_submit_upload FILES ' . $one('piece_legal') . ' ' . $one('piece_complement'));
    }

    /**
     * Message utilisateur pour un refus d’upload (codes internes depuis _kyb_handle_upload).
     *
     * @param string|null $reason_code
     */
    private function _kyb_upload_reject_message($field_key, $reason_code) {
        $label = ($field_key === 'piece_complement')
            ? 'Document complémentaire'
            : 'Pièce d’identité légale';
        $suffix = '';
        if (is_string($reason_code) && strncmp($reason_code, 'php_err_', 8) === 0) {
            $c = (int) substr($reason_code, 8);
            switch ($c) {
                case UPLOAD_ERR_INI_SIZE:
                    $suffix = ' : le fichier dépasse la limite « upload_max_filesize » du serveur (PHP). Réduisez le fichier ou augmentez cette limite côté hébergement.';
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $suffix = ' : le fichier dépasse la limite du formulaire (MAX_FILE_SIZE).';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $suffix = ' : transfert incomplet — réessayez.';
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $suffix = ' : erreur serveur (dossier temporaire manquant).';
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $suffix = ' : erreur serveur (impossible d’écrire le fichier temporaire).';
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $suffix = ' : une extension PHP a bloqué l’envoi du fichier.';
                    break;
                default:
                    $suffix = ' : erreur de transfert (code ' . (string) $c . ').';
            }
        } elseif ($reason_code === 'too_large_kyb') {
            $suffix = ' : taille maximale 3 Mo.';
        } elseif ($reason_code === 'bad_extension') {
            $suffix = ' : extension non autorisée (PDF, JPG ou PNG uniquement).';
        } elseif ($reason_code === 'magic_mismatch') {
            $suffix = ' : le contenu ne correspond pas à un PDF ou une image valide (fichier corrompu ou type incorrect).';
        } elseif ($reason_code === 'copy_blocked') {
            $suffix = ' : fichier temporaire refusé par sécurité — contactez le support.';
        } elseif ($reason_code === 'move_failed') {
            $suffix = ' : enregistrement impossible (vérifiez les droits sur assets/uploads/business_kyb/).';
        }
        if ($suffix === '') {
            $suffix = ' : format PDF/JPG/PNG attendu, taille max. 3 Mo.';
        }
        return $label . $suffix;
    }

    /**
     * @param string|null $fail_reason code interne (ex. php_err_1, magic_mismatch)
     * @return string|null nouveau chemin, null si pas de fichier, false si erreur
     */
    private function _kyb_handle_upload($field_name, $id_marchand, $previous_path, &$fail_reason = null) {
        $fail_reason = null;
        if (!isset($_FILES[$field_name]) || !is_array($_FILES[$field_name])) {
            return null;
        }
        $fi = $_FILES[$field_name];
        if (!isset($fi['tmp_name']) || $fi['tmp_name'] === '') {
            return null;
        }
        $tmp = (string) $fi['tmp_name'];
        if (!is_uploaded_file($tmp) && !is_file($tmp)) {
            return null;
        }
        $err = isset($fi['error']) ? (int) $fi['error'] : UPLOAD_ERR_OK;
        if ($err !== UPLOAD_ERR_OK) {
            $fail_reason = 'php_err_' . (string) $err;
            log_message('error', 'KYB upload PHP error field=' . $field_name . ' code=' . (string) $err);
            return false;
        }
        $size = isset($fi['size']) ? (int) $fi['size'] : (is_file($tmp) ? (int) @filesize($tmp) : 0);
        if ($size > self::KYB_UPLOAD_MAX_BYTES) {
            $fail_reason = 'too_large_kyb';
            return false;
        }
        $orig_name = isset($fi['name']) ? (string) $fi['name'] : '';
        $ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
        if (!in_array($ext, array('pdf', 'jpg', 'jpeg', 'png'), true)) {
            $fail_reason = 'bad_extension';
            return false;
        }
        if (!$this->_kyb_magic_matches_extension($tmp, $ext)) {
            $fail_reason = 'magic_mismatch';
            log_message('error', 'KYB upload magic mismatch field=' . $field_name . ' ext=' . $ext);
            return false;
        }
        // Après magic bytes + extension : ne pas rejeter sur finfo (PDF souvent application/octet-stream, CDFV2, etc.).
        if (class_exists('finfo', false)) {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($tmp);
            $usual = array(
                'application/pdf',
                'application/x-pdf',
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/x-png',
                'application/octet-stream',
            );
            if (!in_array($mime, $usual, true)) {
                log_message('info', 'KYB upload mime non standard (magic OK) field=' . $field_name . ' mime=' . $mime);
            }
        }
        $dir = FCPATH . 'assets/uploads/business_kyb/' . (int) $id_marchand . '/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $new = bin2hex(random_bytes(16)) . '.' . $ext;
        if (is_uploaded_file($tmp)) {
            $moved = move_uploaded_file($tmp, $dir . $new);
        } else {
            if (!$this->_kyb_upload_tmp_is_allowed($tmp)) {
                $fail_reason = 'copy_blocked';
                log_message('error', 'KYB upload copy blocked: tmp not under known temp dirs field=' . $field_name);
                return false;
            }
            $moved = @copy($tmp, $dir . $new);
            if ($moved) {
                @unlink($tmp);
            }
        }
        if (!$moved) {
            $fail_reason = 'move_failed';
            log_message('error', 'KYB upload move/copy failed field=' . $field_name . ' dir_writable=' . (is_writable($dir) ? '1' : '0'));
            return false;
        }
        $rel = 'assets/uploads/business_kyb/' . (int) $id_marchand . '/' . $new;
        if ($previous_path !== null && $previous_path !== '' && is_file(FCPATH . $previous_path)) {
            @unlink(FCPATH . $previous_path);
        }
        return $rel;
    }
}

