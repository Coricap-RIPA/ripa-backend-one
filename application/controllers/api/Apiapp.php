<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Contrôleur unique API Application RIPA
 * Toutes les routes de l'application mobile passent par ce contrôleur.
 * Utilise JWT_Library pour l'authentification (token 1 an).
 */
class Apiapp extends CI_Controller {

    /** Durée de validité du JWT en secondes (1 an) */
    const JWT_EXPIRY_SECONDS = 365 * 24 * 60 * 60;

    /** Timestamp de début de requête (pour log api_logs) */
    public $start_time;

    public function __construct() {
        parent::__construct();
        $this->start_time = microtime(true);

        // Librairies (JWT pour l'auth, format des réponses)
        $this->load->library('JWT_Library');
        $this->load->library('Response_format');

        // Modèles
        $this->load->model('User_model');
        $this->load->model('Kyc_model');
        $this->load->model('Notification_model');
        $this->load->model('Log_utilisateur_application_model');
        $this->load->model('Api_log_model');

        // Services (segmentation B2C / Vault / Onafriq — même projet, traduction Laravel facile)
        $this->load->library('Vault_service');
        $this->load->library('Onafriq_service');

        // Helper personnalisé (chiffrement, sanitize log, détection type carte / mobile money)
        $this->load->helper('custom_helper');

        // CORS : autoriser les requêtes depuis l'app React Native / Expo
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }

        // Rate limiting (IP + palier par type d’action) — voir config/ripa_rate_limit.php
        $this->load->library('Rate_limit_library');
        $this->rate_limit_library->enforce();
    }

    // =========================================================================
    // AUTHENTIFICATION
    // =========================================================================

    /**
     * Inscription
     * POST /api/app/register
     * Body: nom, post_nom, prenom, tel (avec + et indicatif pays), pin (5 chiffres)
     */
    public function register() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $json = json_decode(file_get_contents('php://input'), true);
        $required = array('nom', 'post_nom', 'prenom', 'tel', 'pin');
        foreach ($required as $k) {
            if (!isset($json[$k]) || trim((string) $json[$k]) === '') {
                $this->response_format->send_error('Tous les champs sont obligatoires (nom, post_nom, prenom, tel, pin)', 400);
            }
        }

        $nom = trim((string) $json['nom']);
        $post_nom = trim((string) $json['post_nom']);
        $prenom = trim((string) $json['prenom']);
        $tel = normalise_phone_ripa((string) $json['tel']);
        $pin = trim((string) $json['pin']);

        if (strlen($nom) < 2) {
            $this->response_format->send_error('Le nom doit contenir au moins 2 caractères', 400);
        }
        if (strlen($post_nom) < 2) {
            $this->response_format->send_error('Le post-nom doit contenir au moins 2 caractères', 400);
        }
        if (strlen($prenom) < 2) {
            $this->response_format->send_error('Le prénom doit contenir au moins 2 caractères', 400);
        }
        if (!preg_match('/^\+[0-9]{10,15}$/', $tel)) {
            $this->response_format->send_error('Le numéro doit commencer par + et l\'indicatif pays (ex: +243...)', 400);
        }
        if (strlen($pin) !== 5 || !preg_match('/^[0-9]{5}$/', $pin)) {
            $this->response_format->send_error('Le PIN doit contenir exactement 5 chiffres', 400);
        }

        $existing = $this->User_model->get_user_by_phone($tel);
        if ($existing) {
            $this->response_format->send_error('Ce numéro de téléphone est déjà enregistré', 409);
        }

        $phone_hash = hash('sha256', $tel);
        $nom_c = encrypt_ripa($nom);
        $post_nom_c = encrypt_ripa($post_nom);
        $prenom_c = encrypt_ripa($prenom);
        $tel_c = encrypt_ripa($tel);
        $pin_hashed = password_hash($pin, PASSWORD_BCRYPT);

        $user_data = array(
            'phone' => $tel,
            'phone_hash' => $phone_hash,
            'nom_c' => $nom_c,
            'post_nom_c' => $post_nom_c,
            'prenom_c' => $prenom_c,
            'tel_c' => $tel_c,
            'mot_passe_pin' => $pin_hashed,
            'date_enregistrement' => date('Y-m-d'),
        );
        $user_id = $this->User_model->create_user($user_data);
        if (!$user_id) {
            $this->response_format->send_error('Impossible d\'enregistrer l\'utilisateur en base. Réessayez ou contactez le support.', 500);
        }

        $otp_code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $otp_saved = $this->User_model->save_otp($user_id, $otp_code);
        $user = $this->User_model->get_user_by_id($user_id);

        $nom_complet = $nom . ' ' . $post_nom . ' ' . $prenom;
        $phone_display = isset($user['phone']) ? $user['phone'] : $tel;
        $response = array(
            'user' => array(
                'id' => (int) $user_id,
                'nom_complet' => $nom_complet,
                'nom' => $nom,
                'post_nom' => $post_nom,
                'prenom' => $prenom,
                'phone' => $phone_display,
            ),
            'otp_code' => $otp_code,
            'message' => 'Inscription réussie. Un code OTP a été envoyé à votre numéro.',
        );
        if (!$otp_saved) {
            $response['message'] = 'Compte créé. Le code OTP n\'a pas pu être enregistré ; utilisez « Renvoyer le code » sur l\'écran suivant.';
        }
        try {
            $this->_log_app('inscription', 'utilisateur_application', $user_id, array('user_id' => $user_id));
        } catch (Exception $e) {
            // Ne pas bloquer la réponse : l'utilisateur est créé, le log est optionnel
        }
        $this->response_format->send_success($response, 'Inscription réussie', 201);
    }

    /**
     * Vérification OTP puis connexion automatique (token 1 an)
     * POST /api/app/verify-otp
     */
    public function verify_otp() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $json = json_decode(file_get_contents('php://input'), true);
        if (empty($json['user_id']) || empty($json['otp_code'])) {
            $this->response_format->send_error('ID utilisateur et code OTP requis', 400);
        }
        $user_id = (int) $json['user_id'];
        $otp_code = trim($json['otp_code']);

        if (!$this->User_model->verify_otp($user_id, $otp_code)) {
            $this->response_format->send_error('Code OTP invalide ou expiré', 400);
        }
        $this->User_model->mark_otp_used($user_id);
        $user = $this->User_model->get_user_by_id($user_id);
        if (!$user) {
            $this->response_format->send_error('Utilisateur introuvable', 404);
        }

        $token_data = array(
            'user_id' => (int) $user['id_utilisateur_application'],
            'phone' => $user['phone'],
            'exp' => time() + self::JWT_EXPIRY_SECONDS,
        );
        $jwt_token = $this->jwt_library->encode($token_data);

        $response = array(
            'token' => $jwt_token,
            'user' => $this->_build_user_response($user),
        );
        $this->_log_app('verification_otp', 'utilisateur_application', (int) $user['id_utilisateur_application'], array('user_id' => (int) $user['id_utilisateur_application']), (int) $user['id_utilisateur_application']);
        $this->response_format->send_success($response, 'Vérification OTP réussie');
    }

    /**
     * Renvoyer un code OTP
     * POST /api/app/resend-otp
     */
    public function resend_otp() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $json = json_decode(file_get_contents('php://input'), true);
        if (empty($json['user_id'])) {
            $this->response_format->send_error('ID utilisateur requis', 400);
        }
        $user_id = (int) $json['user_id'];
        $user = $this->User_model->get_user_by_id($user_id);
        if (!$user) {
            $this->response_format->send_error('Utilisateur introuvable', 404);
        }

        $this->db->where('user_id', $user_id);
        $this->db->where('is_used', 0);
        $this->db->delete('otp_codes');

        $otp_code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $this->User_model->save_otp($user_id, $otp_code);

        $response = array(
            'message' => 'Nouveau code OTP généré et envoyé.',
            'otp_code' => $otp_code,
        );
        $this->response_format->send_success($response, 'OTP renvoyé');
    }

    /**
     * Connexion (téléphone + PIN 5 chiffres). Token 1 an.
     * POST /api/app/login
     */
    public function login() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $json = json_decode(file_get_contents('php://input'), true);
        if (empty($json['phone']) || empty($json['pin'])) {
            $this->response_format->send_error('Téléphone et PIN requis', 400);
        }
        $phone = preg_replace('/[^0-9+]/', '', trim($json['phone']));
        if (strpos($json['phone'], '+') !== 0) {
            $phone = '+' . $phone;
        }
        $pin = trim($json['pin']);

        $user = $this->User_model->get_user_by_phone($phone);
        if (!$user) {
            $this->response_format->send_error('Numéro de téléphone ou PIN incorrect', 401);
        }
        if (!password_verify($pin, $user['mot_passe_pin'])) {
            $this->response_format->send_error('Numéro de téléphone ou PIN incorrect', 401);
        }

        $token_data = array(
            'user_id' => (int) $user['id_utilisateur_application'],
            'phone' => $user['phone'],
            'exp' => time() + self::JWT_EXPIRY_SECONDS,
        );
        $jwt_token = $this->jwt_library->encode($token_data);

        $response = array(
            'token' => $jwt_token,
            'user' => $this->_build_user_response($user),
        );
        $this->_log_app('connexion', 'utilisateur_application', (int) $user['id_utilisateur_application'], array(), (int) $user['id_utilisateur_application']);
        $this->response_format->send_success($response, 'Connexion réussie');
    }

    /**
     * Vérifier si le token JWT est valide (au démarrage de l'app)
     * GET /api/app/verify-token
     * Header: Authorization: Bearer {token}
     */
    public function verify_token() {
        if ($this->input->method() !== 'get') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $token = $this->get_bearer_token();
        if (!$token) {
            $this->response_format->send_error('Token manquant', 401);
        }
        $decoded = $this->jwt_library->decode($token);
        if (!$decoded) {
            $this->response_format->send_error('Token invalide ou expiré', 401);
        }
        $user = $this->User_model->get_user_by_id($decoded->user_id);
        if (!$user) {
            $this->response_format->send_error('Utilisateur introuvable', 404);
        }
        $response = array(
            'valid' => true,
            'user' => $this->_build_user_response($user),
        );
        $this->response_format->send_success($response, 'Token valide');
    }

    /**
     * Déverrouillage par PIN (utilisateur qui a déjà un token stocké)
     * POST /api/app/verify-pin
     * Header: Authorization: Bearer {token}
     * Body: { "pin": "12345" }
     * Décode le token → user_id → charge l'utilisateur en BDD → vérifie que le PIN correspond.
     */
    public function verify_pin() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user = $auth['user'];

        $json = json_decode(file_get_contents('php://input'), true);
        if (empty($json['pin']) || !is_string($json['pin'])) {
            $this->response_format->send_error('PIN requis', 400);
        }
        $pin = trim($json['pin']);

        if (!password_verify($pin, $user['mot_passe_pin'])) {
            $this->response_format->send_error('PIN incorrect', 401);
        }

        $this->response_format->send_success(
            array('unlocked' => true, 'user' => $this->_build_user_response($user)),
            'Déverrouillage réussi'
        );
    }

    /**
     * Construit le tableau user pour la réponse API (déchiffrement nom, post_nom, prenom si présents).
     * @param array $user Ligne BDD utilisateur_application
     * @return array [ id, nom_complet, phone ]
     */
    private function _build_user_response($user) {
        $nom_plain = '';
        $post_nom_plain = '';
        $prenom_plain = '';
        if (!empty($user['nom_c'])) {
            $d = decrypt_ripa($user['nom_c']);
            $nom_plain = ($d !== false && $d !== null) ? (string) $d : '';
        }
        if (!empty($user['post_nom_c'])) {
            $d = decrypt_ripa($user['post_nom_c']);
            $post_nom_plain = ($d !== false && $d !== null) ? (string) $d : '';
        }
        if (!empty($user['prenom_c'])) {
            $d = decrypt_ripa($user['prenom_c']);
            $prenom_plain = ($d !== false && $d !== null) ? (string) $d : '';
        }
        $nom_complet = trim($nom_plain . ' ' . $post_nom_plain . ' ' . $prenom_plain);
        if ($nom_complet === '' && !empty($user['nom_complet'])) {
            $nom_complet = $user['nom_complet'];
        }
        return array(
            'id' => (int) $user['id_utilisateur_application'],
            'nom_complet' => $nom_complet,
            'phone' => isset($user['phone']) ? $user['phone'] : '',
            'nom' => $nom_plain,
            'post_nom' => $post_nom_plain,
            'prenom' => $prenom_plain,
        );
    }

    /**
     * Récupère le token Bearer depuis les headers (pour les routes protégées).
     * Apache/XAMPP ne transmet pas toujours Authorization à PHP → fallback sur $_SERVER['HTTP_AUTHORIZATION'].
     * @return string|null
     */
    private function get_bearer_token() {
        $auth = null;
        $headers = $this->input->request_headers();
        if (!empty($headers['Authorization'])) {
            $auth = $headers['Authorization'];
        } else {
            foreach (array_keys($headers) as $key) {
                if (strtolower($key) === 'authorization') {
                    $auth = $headers[$key];
                    break;
                }
            }
        }
        if ($auth && preg_match('/Bearer\s+(.*)$/i', $auth, $m)) {
            return trim($m[1]);
        }
        if (!empty($_SERVER['HTTP_AUTHORIZATION']) && preg_match('/Bearer\s+(.*)$/i', $_SERVER['HTTP_AUTHORIZATION'], $m)) {
            return trim($m[1]);
        }
        return null;
    }

    /**
     * Log action utilisateur app → table log_utilisateur_application (PCI DSS : détails sanitized).
     * @param string $action ex: inscription, connexion, ajout_compte_mobile_money, enregistrement_carte
     * @param string|null $ressource ex: utilisateur_application, carte_utilisateur_application
     * @param int|null $id_ressource
     * @param array|string $details Données à logger (seront passées à sanitize_for_log)
     * @param int|null $user_id NULL pour actions avant login (ex. inscription)
     */
    private function _log_app($action, $ressource = null, $id_ressource = null, $details = array(), $user_id = null) {
        $details = sanitize_for_log($details);
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
        if ($ip === null && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
        }
        $this->Log_utilisateur_application_model->insert_log(array(
            'id_utilisateur_application' => $user_id,
            'action' => $action,
            'ressource' => $ressource,
            'id_ressource' => $id_ressource,
            'details' => $details,
            'ip_address' => $ip,
            'user_agent' => $this->input->user_agent() ? substr($this->input->user_agent(), 0, 255) : null,
        ));
    }

    /**
     * Log appel API (Vault, Onafriq, etc.) → table api_logs (jamais de PAN/CVV/corps).
     * @param string $service ex: vault, onafriq
     * @param string $action ex: tokenize, recharge, withdraw
     * @param int|null $status_code 200, 400, 500
     * @param int|null $duration_ms
     * @param string|null $message Message court optionnel
     * @param string|null $request_id Corrélation optionnelle
     */
    private function _log_api($service, $action, $status_code = null, $duration_ms = null, $message = null, $request_id = null) {
        $this->Api_log_model->insert_log(array(
            'service' => $service,
            'action' => $action,
            'method' => $this->input->method(true),
            'request_id' => $request_id,
            'status_code' => $status_code,
            'duration_ms' => $duration_ms,
            'message' => $message !== null ? substr((string) $message, 0, 255) : null,
        ));
    }

    /**
     * Vérifie le token et retourne le user_id ou null (pour usage interne)
     * @return array|null [ 'user_id' => int, 'user' => array ] ou null
     */
    private function require_auth() {
        $token = $this->get_bearer_token();
        if (!$token) {
            $this->response_format->send_error('Token manquant', 401);
        }
        $decoded = $this->jwt_library->decode($token);
        if (!$decoded) {
            $this->response_format->send_error('Token invalide ou expiré', 401);
        }
        $user = $this->User_model->get_user_by_id($decoded->user_id);
        if (!$user) {
            $this->response_format->send_error('Utilisateur introuvable', 404);
        }
        return array('user_id' => (int) $user['id_utilisateur_application'], 'user' => $user);
    }

    // =========================================================================
    // PROFIL (à compléter avec chiffrement / déchiffrement)
    // =========================================================================

    /**
     * GET /api/app/profile
     */
    public function profile() {
        if ($this->input->method() !== 'get') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $this->response_format->send_success($this->_build_user_response($auth['user']), 'Profil');
    }

    // =========================================================================
    // COMPTES MOBILE MONEY (nouvelle table – à brancher après SQL 02)
    // =========================================================================

    /**
     * Liste des comptes mobile money de l'utilisateur
     * GET /api/app/accounts
     */
    public function accounts() {
        if ($this->input->method() !== 'get') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $this->db->where('id_utilisateur_application', $user_id);
        $this->db->where('is_active', 1);
        $rows = $this->db->get('compte_mobile_money_utilisateur_application')->result_array();
        $accounts = array();
        foreach ($rows as $r) {
            $num = decrypt_ripa($r['num_compte_c']);
            $accounts[] = array(
                'id' => (int) $r['id_compte_mobile_money'],
                'number_masked' => $this->_mask_phone($num),
                'type_id' => (int) $r['id_type_mobile_money'],
                'is_default' => (bool) $r['is_default'],
            );
        }
        $this->response_format->send_success(array('accounts' => $accounts), 'Comptes mobile money');
    }

    /**
     * Ajouter un compte mobile money (règle : un ou plusieurs autorisés)
     * POST /api/app/accounts/add — Body: num_compte (avec +), pin (5 chiffres)
     */
    public function accounts_add() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $user = $auth['user'];

        $json = json_decode(file_get_contents('php://input'), true);
        if (empty($json['num_compte']) || empty($json['pin'])) {
            $this->response_format->send_error('Numéro de compte et PIN requis', 400);
        }
        $num = normalise_phone_ripa((string) $json['num_compte']);
        $pin = trim((string) $json['pin']);

        if (!preg_match('/^\+[0-9]{10,15}$/', $num)) {
            $this->response_format->send_error('Le numéro doit commencer par + et l\'indicatif pays (ex: +243...)', 400);
        }
        if (strlen($pin) !== 5 || !preg_match('/^[0-9]{5}$/', $pin)) {
            $this->response_format->send_error('PIN invalide (5 chiffres)', 400);
        }
        if (!password_verify($pin, $user['mot_passe_pin'])) {
            $this->response_format->send_error('PIN incorrect', 401);
        }

        $type_id = $this->_map_mobile_money_type_id($num);
        $num_c = encrypt_ripa($num);

        $this->db->where('id_utilisateur_application', $user_id);
        $this->db->where('is_active', 1);
        $existing_rows = $this->db->get('compte_mobile_money_utilisateur_application')->result_array();
        foreach ($existing_rows as $r) {
            $existing_num = decrypt_ripa($r['num_compte_c']);
            if ($existing_num !== false && $existing_num === $num) {
                $this->response_format->send_error('Ce numéro Mobile Money est déjà enregistré sur votre compte.', 409);
            }
        }

        $this->db->insert('compte_mobile_money_utilisateur_application', array(
            'id_utilisateur_application' => $user_id,
            'num_compte_c' => $num_c,
            'id_type_mobile_money' => $type_id,
            'is_default' => 0,
            'is_active' => 1,
        ));
        $id = $this->db->insert_id();
        $this->_log_app('ajout_compte_mobile_money', 'compte_mobile_money_utilisateur_application', $id, array('id' => $id, 'type_id' => $type_id), $user_id);
        $this->response_format->send_success(array(
            'id' => (int) $id,
            'number_masked' => $this->_mask_phone($num),
            'type_id' => (int) $type_id,
        ), 'Compte mobile money enregistré');
    }

    /**
     * Types de mobile money (liste pour formulaire)
     * GET /api/app/accounts/types
     */
    public function accounts_types() {
        if ($this->input->method() !== 'get') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $rows = $this->db->get('type_mobile_money')->result_array();
        $this->response_format->send_success(array('types' => $rows), 'Types mobile money');
    }

    /**
     * Supprimer un compte mobile money — POST /api/app/accounts/delete — Body: id, pin
     */
    public function accounts_delete() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $user = $auth['user'];
        $json = json_decode(file_get_contents('php://input'), true);
        $id = isset($json['id']) ? (int) $json['id'] : 0;
        $pin = isset($json['pin']) ? trim((string) $json['pin']) : '';
        if ($id <= 0) {
            $this->response_format->send_error('Identifiant compte invalide', 400);
        }
        if (strlen($pin) !== 5 || !preg_match('/^[0-9]{5}$/', $pin)) {
            $this->response_format->send_error('PIN invalide (5 chiffres)', 400);
        }
        if (!password_verify($pin, $user['mot_passe_pin'])) {
            $this->response_format->send_error('PIN incorrect', 401);
        }
        $row = $this->db->get_where('compte_mobile_money_utilisateur_application', array(
            'id_compte_mobile_money' => $id,
            'id_utilisateur_application' => $user_id,
        ))->row_array();
        if (empty($row)) {
            $this->response_format->send_error('Compte introuvable', 404);
        }
        $this->db->where('id_compte_mobile_money', $id);
        $this->db->update('compte_mobile_money_utilisateur_application', array('is_active' => 0));
        $this->_log_app('suppression_compte_mobile_money', 'compte_mobile_money_utilisateur_application', $id, array('id' => $id), $user_id);
        $this->response_format->send_success(array('id' => $id), 'Compte supprimé');
    }

    /**
     * Mettre à jour un compte mobile money (défaut uniquement) — POST /api/app/accounts/update — Body: id, is_default, pin
     */
    public function accounts_update() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $user = $auth['user'];
        $json = json_decode(file_get_contents('php://input'), true);
        $id = isset($json['id']) ? (int) $json['id'] : 0;
        $is_default = isset($json['is_default']) ? (bool) $json['is_default'] : false;
        $pin = isset($json['pin']) ? trim((string) $json['pin']) : '';
        if ($id <= 0) {
            $this->response_format->send_error('Identifiant compte invalide', 400);
        }
        if (strlen($pin) !== 5 || !preg_match('/^[0-9]{5}$/', $pin)) {
            $this->response_format->send_error('PIN invalide (5 chiffres)', 400);
        }
        if (!password_verify($pin, $user['mot_passe_pin'])) {
            $this->response_format->send_error('PIN incorrect', 401);
        }
        $row = $this->db->get_where('compte_mobile_money_utilisateur_application', array(
            'id_compte_mobile_money' => $id,
            'id_utilisateur_application' => $user_id,
            'is_active' => 1,
        ))->row_array();
        if (empty($row)) {
            $this->response_format->send_error('Compte introuvable', 404);
        }
        if ($is_default) {
            $this->db->where('id_utilisateur_application', $user_id);
            $this->db->update('compte_mobile_money_utilisateur_application', array('is_default' => 0));
        }
        $this->db->where('id_compte_mobile_money', $id);
        $this->db->update('compte_mobile_money_utilisateur_application', array('is_default' => $is_default ? 1 : 0));
        $this->response_format->send_success(array('id' => $id, 'is_default' => $is_default), 'Compte mis à jour');
    }

    /**
     * Liste des comptes bancaires — GET /api/app/accounts/bank
     */
    public function accounts_bank() {
        if ($this->input->method() !== 'get') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $this->db->where('id_utilisateur_application', $user_id);
        $this->db->where('is_active', 1);
        $rows = $this->db->get('compte_bancaire_utilisateur_application')->result_array();
        $accounts = array();
        foreach ($rows as $r) {
            $num = decrypt_ripa($r['num_compte_c']);
            $accounts[] = array(
                'id' => (int) $r['id_compte_bancaire'],
                'nom_banque' => $r['nom_banque'],
                'number_masked' => $this->_mask_bank_number($num),
                'is_default' => (bool) $r['is_default'],
            );
        }
        $this->response_format->send_success(array('accounts' => $accounts), 'Comptes bancaires');
    }

    /**
     * Ajouter un compte bancaire — POST /api/app/accounts/bank/add — Body: nom_banque, num_compte, pin
     */
    public function accounts_bank_add() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $user = $auth['user'];
        $json = json_decode(file_get_contents('php://input'), true);
        $nom_banque = isset($json['nom_banque']) ? trim((string) $json['nom_banque']) : '';
        $num_compte = isset($json['num_compte']) ? trim((string) $json['num_compte']) : '';
        $pin = isset($json['pin']) ? trim((string) $json['pin']) : '';
        if ($nom_banque === '' || strlen($nom_banque) < 2) {
            $this->response_format->send_error('Nom de la banque requis (2 caractères min)', 400);
        }
        if ($num_compte === '' || strlen($num_compte) < 4) {
            $this->response_format->send_error('Numéro de compte ou IBAN requis', 400);
        }
        if (strlen($pin) !== 5 || !preg_match('/^[0-9]{5}$/', $pin)) {
            $this->response_format->send_error('PIN invalide (5 chiffres)', 400);
        }
        if (!password_verify($pin, $user['mot_passe_pin'])) {
            $this->response_format->send_error('PIN incorrect', 401);
        }

        $this->db->where('id_utilisateur_application', $user_id);
        $this->db->where('is_active', 1);
        $existing_bank = $this->db->get('compte_bancaire_utilisateur_application')->result_array();
        $num_compte_clean = preg_replace('/\s+/', '', $num_compte);
        foreach ($existing_bank as $r) {
            $existing_num = decrypt_ripa($r['num_compte_c']);
            $existing_clean = $existing_num !== false ? preg_replace('/\s+/', '', (string) $existing_num) : '';
            if ($existing_clean !== '' && $existing_clean === $num_compte_clean) {
                $this->response_format->send_error('Ce numéro de compte bancaire est déjà enregistré sur votre compte.', 409);
            }
        }

        $num_c = encrypt_ripa($num_compte);
        $this->db->insert('compte_bancaire_utilisateur_application', array(
            'id_utilisateur_application' => $user_id,
            'nom_banque' => $nom_banque,
            'num_compte_c' => $num_c,
            'is_default' => 0,
            'is_active' => 1,
        ));
        $id = $this->db->insert_id();
        $this->response_format->send_success(array(
            'id' => (int) $id,
            'nom_banque' => $nom_banque,
            'number_masked' => $this->_mask_bank_number($num_compte),
        ), 'Compte bancaire enregistré');
    }

    /**
     * Modifier un compte bancaire — POST /api/app/accounts/bank/update — Body: id, nom_banque?, num_compte?, pin
     */
    public function accounts_bank_update() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $user = $auth['user'];
        $json = json_decode(file_get_contents('php://input'), true);
        $id = isset($json['id']) ? (int) $json['id'] : 0;
        $pin = isset($json['pin']) ? trim((string) $json['pin']) : '';
        if ($id <= 0) {
            $this->response_format->send_error('Identifiant compte invalide', 400);
        }
        if (strlen($pin) !== 5 || !preg_match('/^[0-9]{5}$/', $pin)) {
            $this->response_format->send_error('PIN invalide (5 chiffres)', 400);
        }
        if (!password_verify($pin, $user['mot_passe_pin'])) {
            $this->response_format->send_error('PIN incorrect', 401);
        }
        $row = $this->db->get_where('compte_bancaire_utilisateur_application', array(
            'id_compte_bancaire' => $id,
            'id_utilisateur_application' => $user_id,
            'is_active' => 1,
        ))->row_array();
        if (empty($row)) {
            $this->response_format->send_error('Compte introuvable', 404);
        }
        $upd = array();
        if (isset($json['nom_banque'])) {
            $v = trim((string) $json['nom_banque']);
            if (strlen($v) >= 2) $upd['nom_banque'] = $v;
        }
        if (isset($json['num_compte'])) {
            $v = trim((string) $json['num_compte']);
            if (strlen($v) >= 4) $upd['num_compte_c'] = encrypt_ripa($v);
        }
        if (!empty($upd)) {
            $this->db->where('id_compte_bancaire', $id);
            $this->db->update('compte_bancaire_utilisateur_application', $upd);
        }
        $this->response_format->send_success(array('id' => $id), 'Compte mis à jour');
    }

    /**
     * Supprimer un compte bancaire — POST /api/app/accounts/bank/delete — Body: id, pin
     */
    public function accounts_bank_delete() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $user = $auth['user'];
        $json = json_decode(file_get_contents('php://input'), true);
        $id = isset($json['id']) ? (int) $json['id'] : 0;
        $pin = isset($json['pin']) ? trim((string) $json['pin']) : '';
        if ($id <= 0) {
            $this->response_format->send_error('Identifiant compte invalide', 400);
        }
        if (strlen($pin) !== 5 || !preg_match('/^[0-9]{5}$/', $pin)) {
            $this->response_format->send_error('PIN invalide (5 chiffres)', 400);
        }
        if (!password_verify($pin, $user['mot_passe_pin'])) {
            $this->response_format->send_error('PIN incorrect', 401);
        }
        $row = $this->db->get_where('compte_bancaire_utilisateur_application', array(
            'id_compte_bancaire' => $id,
            'id_utilisateur_application' => $user_id,
        ))->row_array();
        if (empty($row)) {
            $this->response_format->send_error('Compte introuvable', 404);
        }
        $this->db->where('id_compte_bancaire', $id);
        $this->db->update('compte_bancaire_utilisateur_application', array('is_active' => 0));
        $this->response_format->send_success(array('id' => $id), 'Compte supprimé');
    }

    /**
     * Masque un numéro de compte bancaire pour affichage
     */
    private function _mask_bank_number($num) {
        $num = preg_replace('/\s+/', '', $num);
        $len = strlen($num);
        if ($len <= 4) return '****';
        return '****' . substr($num, -4);
    }

    // =========================================================================
    // CARTES (token + last4 uniquement, Vault mocké)
    // =========================================================================

    /**
     * Enregistrer une carte physique (règle : une seule). Vérification KYC obligatoire.
     * POST /api/app/cards/register — Body: pan, expiry (MM/YY), cvv, pin
     */
    public function cards_register() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $user = $auth['user'];

        if (!$this->Kyc_model->has_valid_kyc($user_id)) {
            $this->response_format->send_error('Complétez votre KYC avant d\'enregistrer une carte. Menu Paramètres puis KYC.', 403);
        }

        $json = json_decode(file_get_contents('php://input'), true);
        $pan = isset($json['pan']) ? preg_replace('/\s+/', '', $json['pan']) : '';
        $expiry = isset($json['expiry']) ? trim($json['expiry']) : '';
        $cvv = isset($json['cvv']) ? trim($json['cvv']) : '';
        $pin = isset($json['pin']) ? trim((string) $json['pin']) : '';

        if (strlen($pan) < 15 || !ctype_digit($pan)) {
            $this->response_format->send_error('Numéro de carte invalide', 400);
        }
        if (!preg_match('/^\d{2}\/\d{2}$/', $expiry)) {
            $this->response_format->send_error('Date d\'expiration invalide (MM/YY)', 400);
        }
        if (strlen($cvv) < 3 || !ctype_digit($cvv)) {
            $this->response_format->send_error('CVV invalide', 400);
        }
        if (strlen($pin) !== 5 || !preg_match('/^[0-9]{5}$/', $pin)) {
            $this->response_format->send_error('PIN invalide', 400);
        }
        if (!password_verify($pin, $user['mot_passe_pin'])) {
            $this->response_format->send_error('PIN incorrect', 401);
        }

        $existing = $this->db->get_where('carte_utilisateur_application', array(
            'id_utilisateur_application' => $user_id,
            'type_carte' => 'physique'
        ))->row_array();
        if ($existing) {
            $this->response_format->send_error('Vous ne pouvez enregistrer qu\'une seule carte physique', 409);
        }

        $t0 = (int) (microtime(true) * 1000);
        $vault_result = $this->vault_service->tokenize($pan, $cvv, $expiry);
        $duration_ms = (int) (microtime(true) * 1000) - $t0;
        $this->_log_api('vault', 'tokenize', 200, $duration_ms, 'OK', null);
        $token_vault = $vault_result['token'];
        $last4 = $vault_result['last4'];
        $brand = $vault_result['brand'];

        $this->db->insert('carte_utilisateur_application', array(
            'id_utilisateur_application' => $user_id,
            'token_vault' => $token_vault,
            'last_four' => $last4,
            'date_expiration' => $expiry,
            'type_carte' => 'physique',
            'brand' => $brand,
            'is_active' => 1,
        ));
        $id = $this->db->insert_id();
        $this->_log_app('enregistrement_carte', 'carte_utilisateur_application', $id, array('last4' => $last4, 'brand' => $brand, 'type_carte' => 'physique'), $user_id);
        $this->response_format->send_success(array(
            'id' => (int) $id,
            'last4' => $last4,
            'brand' => $brand,
            'expiry' => $expiry,
        ), 'Carte enregistrée');
    }

    /**
     * Commander une carte virtuelle (1 $, prélevé sur Mobile Money). KYC + au moins un compte Mobile Money requis.
     * POST /api/app/cards/order-virtual — Body: brand (visa|mastercard), pin
     */
    public function cards_order_virtual() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $user = $auth['user'];

        if (!$this->Kyc_model->has_valid_kyc($user_id)) {
            $this->response_format->send_error('Complétez votre KYC avant de commander une carte virtuelle. Menu Paramètres puis KYC.', 403);
        }

        $this->db->where('id_utilisateur_application', $user_id);
        $this->db->where('is_active', 1);
        $accounts = $this->db->get('compte_mobile_money_utilisateur_application')->result_array();
        if (empty($accounts)) {
            $this->response_format->send_error('Enregistrez au moins un compte Mobile Money pour le prélèvement de 1 $.', 400);
        }

        $json = json_decode(file_get_contents('php://input'), true);
        $brand = isset($json['brand']) ? strtolower(trim($json['brand'])) : '';
        $pin = isset($json['pin']) ? trim((string) $json['pin']) : '';

        if (!in_array($brand, array('visa', 'mastercard'), true)) {
            $this->response_format->send_error('Choisissez Visa ou Mastercard.', 400);
        }
        if (strlen($pin) !== 5 || !preg_match('/^[0-9]{5}$/', $pin)) {
            $this->response_format->send_error('PIN invalide (5 chiffres).', 400);
        }
        if (!password_verify($pin, $user['mot_passe_pin'])) {
            $this->response_format->send_error('PIN incorrect.', 401);
        }

        $existing = $this->db->get_where('carte_utilisateur_application', array(
            'id_utilisateur_application' => $user_id,
            'type_carte' => 'virtuelle',
            'is_active' => 1
        ))->row_array();
        if ($existing) {
            $this->response_format->send_error('Vous avez déjà une carte virtuelle.', 409);
        }

        // Simulation prélèvement 1 $ (log ou table dédiée en prod)
        $brand_display = $brand === 'visa' ? 'Visa' : 'Mastercard';
        $last4 = str_pad((string) rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $expiry = date('m/y', strtotime('+3 years'));
        $token_vault = 'mock_vault_' . $brand . '_' . bin2hex(random_bytes(12));

        $this->db->insert('carte_utilisateur_application', array(
            'id_utilisateur_application' => $user_id,
            'token_vault' => $token_vault,
            'last_four' => $last4,
            'date_expiration' => $expiry,
            'type_carte' => 'virtuelle',
            'brand' => $brand_display,
            'is_active' => 1,
            'solde_c' => encrypt_ripa('0'),
        ));
        $id = $this->db->insert_id();
        $this->_log_app('commande_carte_virtuelle', 'carte_utilisateur_application', $id, array('last4' => $last4, 'brand' => $brand_display), $user_id);
        $this->response_format->send_success(array(
            'id' => (int) $id,
            'last4' => $last4,
            'brand' => $brand_display,
            'expiry' => $expiry,
            'balance' => 0,
        ), 'Carte virtuelle commandée. 1 $ a été prélevé sur votre Mobile Money.');
    }

    /**
     * Liste des cartes de l'utilisateur (token + last4 uniquement)
     * GET /api/app/cards
     */
    public function cards() {
        if ($this->input->method() !== 'get') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $rows = $this->db->get_where('carte_utilisateur_application', array(
            'id_utilisateur_application' => $auth['user_id'],
            'is_active' => 1
        ))->result_array();
        $cards = array();
        foreach ($rows as $r) {
            $is_virtual = (strtolower($r['type_carte']) === 'virtuelle' || strtolower($r['type_carte']) === 'virtual');
            $balance = null;
            if ($is_virtual) {
                $balance = 0;
                if (!empty($r['solde_c'])) {
                    $dec = decrypt_ripa($r['solde_c']);
                    $balance = (is_numeric($dec) || $dec === '') ? (float) $dec : 0;
                }
            }
            $cards[] = array(
                'id' => (int) $r['id_carte'],
                'last4' => $r['last_four'],
                'brand' => $r['brand'],
                'expiry' => $r['date_expiration'],
                'type' => $r['type_carte'],
                'balance' => $balance,
            );
        }
        $this->response_format->send_success(array('cards' => $cards), 'Cartes');
    }

    /**
     * Supprimer une carte (soft delete) — POST /api/app/cards/delete — Body: id, pin
     */
    public function cards_delete() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $user = $auth['user'];
        $json = json_decode(file_get_contents('php://input'), true);
        $id = isset($json['id']) ? (int) $json['id'] : 0;
        $pin = isset($json['pin']) ? trim((string) $json['pin']) : '';
        if ($id <= 0) {
            $this->response_format->send_error('Identifiant carte invalide', 400);
        }
        if (strlen($pin) !== 5 || !preg_match('/^[0-9]{5}$/', $pin)) {
            $this->response_format->send_error('PIN invalide (5 chiffres)', 400);
        }
        if (!password_verify($pin, $user['mot_passe_pin'])) {
            $this->response_format->send_error('PIN incorrect', 401);
        }
        $row = $this->db->get_where('carte_utilisateur_application', array(
            'id_carte' => $id,
            'id_utilisateur_application' => $user_id,
        ))->row_array();
        if (empty($row)) {
            $this->response_format->send_error('Carte introuvable', 404);
        }
        $this->db->where('id_carte', $id);
        $this->db->update('carte_utilisateur_application', array('is_active' => 0));
        $this->response_format->send_success(array('id' => $id), 'Carte supprimée');
    }

    /**
     * Recharger la carte virtuelle depuis un compte Mobile Money (simulation Onafriq).
     * POST /api/app/cards/recharge — Body: card_id, account_id, amount, pin
     */
    public function cards_recharge() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $user = $auth['user'];
        $json = json_decode(file_get_contents('php://input'), true);
        $card_id = isset($json['card_id']) ? (int) $json['card_id'] : 0;
        $account_id = isset($json['account_id']) ? (int) $json['account_id'] : 0;
        $amount = isset($json['amount']) ? (float) $json['amount'] : 0;
        $pin = isset($json['pin']) ? trim((string) $json['pin']) : '';

        if ($card_id <= 0 || $account_id <= 0) {
            $this->response_format->send_error('Carte et compte Mobile Money requis', 400);
        }
        if ($amount <= 0 || $amount > 999999) {
            $this->response_format->send_error('Montant invalide (entre 0.01 et 999999)', 400);
        }
        if (strlen($pin) !== 5 || !preg_match('/^[0-9]{5}$/', $pin)) {
            $this->response_format->send_error('PIN invalide (5 chiffres)', 400);
        }
        if (!password_verify($pin, $user['mot_passe_pin'])) {
            $this->response_format->send_error('PIN incorrect', 401);
        }

        $card = $this->db->get_where('carte_utilisateur_application', array(
            'id_carte' => $card_id,
            'id_utilisateur_application' => $user_id,
            'is_active' => 1,
        ))->row_array();
        if (empty($card)) {
            $this->response_format->send_error('Carte introuvable', 404);
        }
        if ((strtolower($card['type_carte']) !== 'virtuelle' && $card['type_carte'] !== 'virtual')) {
            $this->response_format->send_error('Seules les cartes virtuelles peuvent être rechargées depuis Mobile Money', 400);
        }

        $t0 = (int) (microtime(true) * 1000);
        $result = $this->onafriq_service->recharge($user_id, $card_id, $account_id, $amount);
        $duration_ms = (int) (microtime(true) * 1000) - $t0;

        if (!empty($result['error'])) {
            $this->_log_api('onafriq', 'recharge', 400, $duration_ms, $result['error'], null);
            $this->response_format->send_error(isset($result['error']) ? $result['error'] : 'Erreur recharge', 400);
        }
        $this->_log_api('onafriq', 'recharge', 200, $duration_ms, 'OK', $result['reference']);
        $this->_log_app('recharge_carte', 'transaction_carte_mobile_money_utilisateur', $result['transaction_id'], array('card_id' => $card_id, 'amount' => $amount, 'reference' => $result['reference']), $user_id);

        $this->response_format->send_success(array(
            'transaction_id' => $result['transaction_id'],
            'amount' => $amount,
            'balance' => $result['new_balance'],
            'reference' => $result['reference'],
            'message' => 'Recharge effectuée (simulation Onafriq). Montant débité du Mobile Money et crédité sur la carte.',
        ), 'Carte rechargée');
    }

    /**
     * Retirer de la carte virtuelle vers un compte Mobile Money (simulation Onafriq).
     * POST /api/app/cards/withdraw — Body: card_id, account_id, amount, pin
     */
    public function cards_withdraw() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $user = $auth['user'];
        $json = json_decode(file_get_contents('php://input'), true);
        $card_id = isset($json['card_id']) ? (int) $json['card_id'] : 0;
        $account_id = isset($json['account_id']) ? (int) $json['account_id'] : 0;
        $amount = isset($json['amount']) ? (float) $json['amount'] : 0;
        $pin = isset($json['pin']) ? trim((string) $json['pin']) : '';

        if ($card_id <= 0 || $account_id <= 0) {
            $this->response_format->send_error('Carte et compte Mobile Money requis', 400);
        }
        if ($amount <= 0 || $amount > 999999) {
            $this->response_format->send_error('Montant invalide (entre 0.01 et 999999)', 400);
        }
        if (strlen($pin) !== 5 || !preg_match('/^[0-9]{5}$/', $pin)) {
            $this->response_format->send_error('PIN invalide (5 chiffres)', 400);
        }
        if (!password_verify($pin, $user['mot_passe_pin'])) {
            $this->response_format->send_error('PIN incorrect', 401);
        }

        $card = $this->db->get_where('carte_utilisateur_application', array(
            'id_carte' => $card_id,
            'id_utilisateur_application' => $user_id,
            'is_active' => 1,
        ))->row_array();
        if (empty($card)) {
            $this->response_format->send_error('Carte introuvable', 404);
        }
        if ((strtolower($card['type_carte']) !== 'virtuelle' && $card['type_carte'] !== 'virtual')) {
            $this->response_format->send_error('Seules les cartes virtuelles permettent un retrait vers Mobile Money', 400);
        }

        $t0 = (int) (microtime(true) * 1000);
        $result = $this->onafriq_service->withdraw($user_id, $card_id, $account_id, $amount);
        $duration_ms = (int) (microtime(true) * 1000) - $t0;

        if (!empty($result['error'])) {
            $this->_log_api('onafriq', 'withdraw', 400, $duration_ms, $result['error'], null);
            $msg = $result['error'];
            if (isset($result['current_balance'])) {
                $msg .= ' Solde actuel : ' . number_format($result['current_balance'], 2, ',', ' ') . ' $';
            }
            $this->response_format->send_error($msg, 400);
        }
        $this->_log_api('onafriq', 'withdraw', 200, $duration_ms, 'OK', $result['reference']);
        $this->_log_app('retrait_carte', 'transaction_carte_mobile_money_utilisateur', $result['transaction_id'], array('card_id' => $card_id, 'amount' => $amount, 'reference' => $result['reference']), $user_id);

        $this->response_format->send_success(array(
            'transaction_id' => $result['transaction_id'],
            'amount' => $amount,
            'balance' => $result['new_balance'],
            'reference' => $result['reference'],
            'message' => 'Retrait effectué (simulation Onafriq). Montant débité de la carte et crédité sur le Mobile Money.',
        ), 'Retrait vers Mobile Money effectué');
    }

    // =========================================================================
    // KYC
    // =========================================================================

    /**
     * GET /api/app/kyc — Statut KYC + données en lecture si validé
     */
    public function kyc_get() {
        if ($this->input->method() !== 'get') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        try {
            $kyc = $this->Kyc_model->get_by_user($user_id);
        } catch (\Throwable $e) {
            log_message('error', 'KYC get_by_user: ' . $e->getMessage());
            $this->response_format->send_success(array(
                'has_kyc' => false,
                'statut' => null,
                'data' => null,
                'date_prochaine_kyc' => null,
            ), 'Aucun KYC');
            return;
        }

        if (!$kyc) {
            $this->response_format->send_success(array(
                'has_kyc' => false,
                'statut' => null,
                'data' => null,
                'date_prochaine_kyc' => null,
            ), 'Aucun KYC');
            return;
        }

        $statut = isset($kyc['statut']) ? $kyc['statut'] : 'en_attente';
        $data = null;
        if ($statut === 'valide' || $statut === 'rejete') {
            try {
                $nom_d = decrypt_ripa(isset($kyc['nom_c']) ? $kyc['nom_c'] : '');
                $post_nom_d = decrypt_ripa(isset($kyc['post_nom_c']) ? $kyc['post_nom_c'] : '');
                $prenom_d = decrypt_ripa(isset($kyc['prenom_c']) ? $kyc['prenom_c'] : '');
                $date_naissance_d = decrypt_ripa(isset($kyc['date_naissance_c']) ? $kyc['date_naissance_c'] : '');
                $adresse_d = decrypt_ripa(isset($kyc['adresse_c']) ? $kyc['adresse_c'] : '');
                $data = array(
                    'nom' => $nom_d !== false ? $nom_d : '',
                    'post_nom' => $post_nom_d !== false ? $post_nom_d : '',
                    'prenom' => $prenom_d !== false ? $prenom_d : '',
                    'date_naissance' => $date_naissance_d !== false ? $date_naissance_d : '',
                    'adresse' => $adresse_d !== false ? $adresse_d : '',
                );
                if ($statut === 'rejete' || $statut === 'valide') {
                    $photo_piece = decrypt_ripa(isset($kyc['photo_piece_identite_c']) ? $kyc['photo_piece_identite_c'] : '');
                    $photo_selfie = decrypt_ripa(isset($kyc['photo_utilisateur_c']) ? $kyc['photo_utilisateur_c'] : '');
                    $data['photo_piece_base64'] = ($photo_piece !== false && $photo_piece !== '') ? $photo_piece : null;
                    $data['photo_utilisateur_base64'] = ($photo_selfie !== false && $photo_selfie !== '') ? $photo_selfie : null;
                }
            } catch (\Throwable $e) {
                log_message('error', 'KYC decrypt_ripa: ' . $e->getMessage());
                $data = array('nom' => '', 'post_nom' => '', 'prenom' => '', 'date_naissance' => '', 'adresse' => '');
                if ($statut === 'rejete' || $statut === 'valide') {
                    $data['photo_piece_base64'] = null;
                    $data['photo_utilisateur_base64'] = null;
                }
            }
        }
        $this->response_format->send_success(array(
            'has_kyc' => true,
            'statut' => $statut,
            'date_enregistrement' => isset($kyc['date_enregistrement']) ? $kyc['date_enregistrement'] : null,
            'date_validation_kyc' => isset($kyc['date_validation_kyc']) ? $kyc['date_validation_kyc'] : null,
            'date_prochaine_kyc' => isset($kyc['date_prochaine_kyc']) ? $kyc['date_prochaine_kyc'] : null,
            'data' => $data,
        ), 'KYC');
    }

    /**
     * POST /api/app/kyc/submit — Soumission KYC (données chiffrées)
     * Body: nom, post_nom, prenom, date_naissance, adresse, photo_piece_identite (base64), photo_utilisateur (base64)
     */
    public function kyc_submit() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];

        $json = json_decode(file_get_contents('php://input'), true);
        if (!is_array($json)) {
            $this->response_format->send_error('Corps de requête invalide', 400);
            return;
        }
        $required = array('nom', 'post_nom', 'prenom', 'date_naissance', 'adresse', 'photo_piece_identite', 'photo_utilisateur');
        foreach ($required as $k) {
            if (empty($json[$k])) {
                $this->response_format->send_error('Tous les champs sont obligatoires', 400);
                return;
            }
        }

        try {
            $data = array(
                'nom_c' => encrypt_ripa(trim($json['nom'])),
                'post_nom_c' => encrypt_ripa(trim($json['post_nom'])),
                'prenom_c' => encrypt_ripa(trim($json['prenom'])),
                'date_naissance_c' => encrypt_ripa(trim($json['date_naissance'])),
                'adresse_c' => encrypt_ripa(trim($json['adresse'])),
                'photo_piece_identite_c' => encrypt_ripa($json['photo_piece_identite']),
                'photo_utilisateur_c' => encrypt_ripa($json['photo_utilisateur']),
            );
            $this->Kyc_model->upsert($user_id, $data);
        } catch (\Throwable $e) {
            log_message('error', 'KYC submit: ' . $e->getMessage());
            $this->response_format->send_error('Erreur lors de l\'enregistrement du dossier KYC. Vérifiez que les photos ne sont pas trop volumineuses.', 500);
            return;
        }
        $this->_log_app('soumission_kyc', 'kyc_utilisateur_application', null, array('statut' => 'en_attente'), $user_id);
        $this->response_format->send_success(array('statut' => 'en_attente'), 'KYC soumis. En attente de validation RIPA.');
    }

    // =========================================================================
    // TRANSACTIONS RÉCENTES (paiements, réceptions, transferts, recharge/décharge carte)
    // Pour l'instant : recharge et retrait carte <-> Mobile Money. Paiement/réception/transfert à brancher plus tard.
    // =========================================================================

    /**
     * GET /api/app/transactions/recent — Dernières transactions (recharge/décharge carte, puis paiement, réception, transfert)
     * Retourne un tableau unifié avec type, label, amount, date pour affichage tableau de bord.
     */
    public function transactions_recent() {
        if ($this->input->method() !== 'get') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $limit = min(50, max(10, (int) $this->input->get('limit')) ?: 20);

        $list = array();

        $this->db->where('id_utilisateur_application', $user_id);
        $this->db->order_by('date_creation', 'DESC');
        $this->db->limit($limit);
        $rows = $this->db->get('transaction_carte_mobile_money_utilisateur')->result_array();
        foreach ($rows as $r) {
            $amount = 0;
            if (!empty($r['montant_c'])) {
                $dec = decrypt_ripa($r['montant_c']);
                $amount = (is_numeric($dec) || $dec === '') ? (float) $dec : 0;
            }
            $type_op = isset($r['type_operation']) ? $r['type_operation'] : '';
            $is_credit = ($type_op === 'recharge');
            $list[] = array(
                'id' => 'card_mm_' . (int) $r['id_transaction_carte_mm'],
                'type' => $type_op === 'recharge' ? 'recharge' : 'retrait',
                'label' => $type_op === 'recharge' ? 'Recharge carte' : 'Décharge carte',
                'amount' => $amount,
                'date' => isset($r['date_creation']) ? date('d/m/Y H:i', strtotime($r['date_creation'])) : '',
                'date_creation' => isset($r['date_creation']) ? $r['date_creation'] : '',
                'source' => 'card_mm',
                'is_credit' => $is_credit,
            );
        }

        $this->db->group_start();
        $this->db->where('id_emetteur', $user_id);
        $this->db->or_where('id_destinataire', $user_id);
        $this->db->group_end();
        $this->db->where('statut', 'traite');
        $this->db->order_by('date_creation', 'DESC');
        $this->db->limit($limit);
        $rows_p = $this->db->get('transaction_paiement_ripa')->result_array();
        foreach ($rows_p as $r) {
            $amount = 0;
            if (!empty($r['montant_c'])) {
                $dec = decrypt_ripa($r['montant_c']);
                $amount = (is_numeric($dec) || $dec === '') ? (float) $dec : 0;
            }
            $is_sent = (int) $r['id_emetteur'] === (int) $user_id;
            $other_id = $is_sent ? (int) $r['id_destinataire'] : (int) $r['id_emetteur'];
            $other = $this->User_model->get_user_by_id($other_id);
            $other_name = $other ? trim(decrypt_ripa($other['prenom_c']) . ' ' . decrypt_ripa($other['nom_c'])) : 'RIPA';
            $list[] = array(
                'id' => 'p2c_' . (int) $r['id_paiement'],
                'type' => 'payment',
                'label' => $is_sent ? 'Envoyé à ' . $other_name : 'Reçu de ' . $other_name,
                'amount' => $amount,
                'date' => date('d/m/Y H:i', strtotime($r['date_creation'])),
                'date_creation' => $r['date_creation'],
                'source' => 'paiement_ripa',
                'is_credit' => !$is_sent,
            );
        }
        usort($list, function ($a, $b) {
            return strcmp($b['date_creation'], $a['date_creation']);
        });
        $list = array_slice($list, 0, $limit);
        foreach ($list as &$item) {
            unset($item['date_creation']);
        }

        $this->response_format->send_success(array('transactions' => $list), 'Transactions récentes');
    }

    // =========================================================================
    // NOTIFICATIONS (KYC validé/rejeté/supprimé, etc.)
    // =========================================================================

    /**
     * GET /api/app/notifications — Liste des notifications (optionnel: ?unread_only=1)
     */
    public function notifications_get() {
        if ($this->input->method() !== 'get') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $unread_only = $this->input->get('unread_only') === '1' || $this->input->get('unread_only') === 'true';
        $list = $this->Notification_model->get_by_user($user_id, $unread_only, 50);
        $count_unread = $this->Notification_model->count_unread($user_id);
        $this->response_format->send_success(array(
            'notifications' => $list,
            'count_unread' => $count_unread,
        ), 'Notifications');
    }

    /**
     * POST /api/app/notifications/read — Marquer comme lues (body: { "ids": [1,2] } ou { "all": true })
     */
    public function notifications_mark_read() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $json = json_decode(file_get_contents('php://input'), true);
        if (!empty($json['all'])) {
            $this->Notification_model->mark_all_read($user_id);
        } elseif (!empty($json['ids']) && is_array($json['ids'])) {
            $this->Notification_model->mark_read($user_id, $json['ids']);
        }
        $this->response_format->send_success(array('ok' => true), 'Marqué(s) comme lu(s).');
    }

    /**
     * Masque un numéro pour affichage (ex: +243 *** ** 456)
     */
    private function _mask_phone($num) {
        $len = strlen($num);
        if ($len <= 6) {
            return $num;
        }
        return substr($num, 0, 4) . ' *** ** ' . substr($num, -3);
    }

    /**
     * ID type_mobile_money à partir du préfixe (+243...)
     */
    private function _map_mobile_money_type_id($phone) {
        $p = preg_replace('/\s+/', '', $phone);
        if (preg_match('/^\+2439(7|8|9)/', $p)) return 1;
        if (preg_match('/^\+2438(1|2|3)/', $p)) return 3;
        if (preg_match('/^\+2438(4|5|9)/', $p)) return 2;
        if (preg_match('/^\+2439(0|1)/', $p)) return 4;
        return 5;
    }

    // =========================================================================
    // PAIEMENT B2C — Scan & Pay, C2C (sources unifiées, payee lookup, submit)
    // =========================================================================

    /**
     * Liste unifiée des comptes source pour "Payer avec" (cartes + MM + bancaires).
     * GET /api/app/payment/sources
     */
    public function payment_sources() {
        if ($this->input->method() !== 'get') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $sources = array();

        $rows = $this->db->get_where('carte_utilisateur_application', array(
            'id_utilisateur_application' => $user_id,
            'is_active' => 1,
        ))->result_array();
        foreach ($rows as $r) {
            $balance = null;
            if (strtolower($r['type_carte']) === 'virtuelle' && !empty($r['solde_c'])) {
                $dec = decrypt_ripa($r['solde_c']);
                $balance = (is_numeric($dec) || $dec === '') ? (float) $dec : 0;
            }
            $sources[] = array(
                'source_type' => $r['type_carte'] === 'physique' ? 'carte_physique' : 'carte_virtuelle',
                'id' => (int) $r['id_carte'],
                'label' => ($r['brand'] ? $r['brand'] . ' ' : '') . '****' . $r['last_four'],
                'balance' => $balance,
            );
        }

        $mm = $this->db->get_where('compte_mobile_money_utilisateur_application', array(
            'id_utilisateur_application' => $user_id,
            'is_active' => 1,
        ))->result_array();
        foreach ($mm as $r) {
            $num = decrypt_ripa($r['num_compte_c']);
            $sources[] = array(
                'source_type' => 'mobile_money',
                'id' => (int) $r['id_compte_mobile_money'],
                'label' => 'Mobile Money ' . $this->_mask_phone($num),
                'balance' => null,
            );
        }

        $bank = $this->db->get_where('compte_bancaire_utilisateur_application', array(
            'id_utilisateur_application' => $user_id,
            'is_active' => 1,
        ))->result_array();
        foreach ($bank as $r) {
            $num = decrypt_ripa($r['num_compte_c']);
            $sources[] = array(
                'source_type' => 'compte_bancaire',
                'id' => (int) $r['id_compte_bancaire'],
                'label' => $r['nom_banque'] . ' ****' . substr(preg_replace('/\s+/', '', $num), -4),
                'balance' => null,
            );
        }

        $this->response_format->send_success(array('sources' => $sources), 'Comptes source');
    }

    /**
     * Récupérer ou créer le token payee (ripa://p/{token}).
     * @param int $user_id
     * @return string token court
     */
    private function _ensure_payee_token($user_id) {
        $row = $this->db->get_where('ripa_payee_token', array('id_utilisateur_application' => $user_id))->row_array();
        if ($row) {
            return (string) $row['token'];
        }
        $token = bin2hex(random_bytes(16));
        $this->db->insert('ripa_payee_token', array(
            'id_utilisateur_application' => $user_id,
            'token' => $token,
        ));
        $this->_log_app('creation_token_payee', 'ripa_payee_token', $this->db->insert_id(), array(), $user_id);
        return $token;
    }

    /**
     * Récupérer ou créer le token payee pour afficher le QR (ripa://p/{token}).
     * GET /api/app/payee/token
     */
    public function payee_token() {
        if ($this->input->method() !== 'get') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];

        try {
            $row = $this->db->get_where('ripa_payee_token', array('id_utilisateur_application' => $user_id))->row_array();
            if ($row) {
                $this->response_format->send_success(array(
                    'token' => $row['token'],
                    'qr_uri' => 'ripa://p/' . $row['token'],
                    'date_expiration' => $row['date_expiration'],
                ), 'Token payee');
                return;
            }

            $token = $this->_ensure_payee_token($user_id);
            $this->response_format->send_success(array(
                'token' => $token,
                'qr_uri' => 'ripa://p/' . $token,
                'date_expiration' => null,
            ), 'Token payee créé');
        } catch (\Exception $e) {
            log_message('error', 'payee_token: ' . $e->getMessage());
            $this->response_format->send_error('Configuration requise : exécutez sql/11_ripa_payee_token.sql sur la base de données.', 503);
        }
    }

    /**
     * Contexte pour générer le QR « Recevoir » : token, moyens disponibles, destination implicite ou préférence chiffrée.
     * GET /api/app/payee/qr-context
     */
    public function payee_qr_context() {
        if ($this->input->method() !== 'get') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        try {
            $token = $this->_ensure_payee_token($user_id);
        } catch (\Exception $e) {
            log_message('error', 'payee_qr_context: ' . $e->getMessage());
            $this->response_format->send_error('Configuration requise : sql/11_ripa_payee_token.sql', 503);
            return;
        }
        $available = $this->_get_payee_available_destination_types($user_id);
        $implicit = count($available) === 1 ? $available[0] : null;
        $saved = null;
        if ($this->db->field_exists('payee_qr_destination_c', 'utilisateur_application')) {
            $u = $this->db->get_where('utilisateur_application', array('id_utilisateur_application' => $user_id))->row_array();
            if (!empty($u['payee_qr_destination_c'])) {
                $dec = decrypt_ripa($u['payee_qr_destination_c']);
                if ($dec !== false && $dec !== '' && in_array((string) $dec, $available, true)) {
                    $saved = (string) $dec;
                }
            }
        }
        $this->response_format->send_success(array(
            'token' => $token,
            'available_destination_types' => $available,
            'implicit_destination' => $implicit,
            'saved_destination_type' => $saved,
        ), 'Contexte QR payee');
    }

    /**
     * Enregistrer la préférence de moyen de réception pour le QR (chiffré en base).
     * POST /api/app/payee/qr-preference — Body: { "destination_type": "carte_virtuelle"|"mobile_money"|"compte_bancaire" }
     */
    public function payee_qr_preference() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        if (!$this->db->field_exists('payee_qr_destination_c', 'utilisateur_application')) {
            $this->response_format->send_error('Migration requise : exécutez sql/13_payee_qr_destination_preference.sql', 503);
            return;
        }
        $json = json_decode(file_get_contents('php://input'), true);
        $dest = isset($json['destination_type']) ? trim((string) $json['destination_type']) : '';
        $valid = array('carte_virtuelle', 'mobile_money', 'compte_bancaire');
        if (!in_array($dest, $valid, true)) {
            $this->response_format->send_error('destination_type invalide', 400);
            return;
        }
        $available = $this->_get_payee_available_destination_types($user_id);
        if (!in_array($dest, $available, true)) {
            $this->response_format->send_error('Ce moyen de réception n\'est pas disponible sur votre compte.', 400);
            return;
        }
        $this->db->where('id_utilisateur_application', $user_id);
        $this->db->update('utilisateur_application', array(
            'payee_qr_destination_c' => encrypt_ripa($dest),
        ));
        $this->_log_app('payee_qr_preference', 'utilisateur_application', $user_id, array('destination_type' => $dest), $user_id);
        $this->response_format->send_success(array('destination_type' => $dest), 'Préférence enregistrée');
    }

    /**
     * Retourne les types de réception disponibles pour un utilisateur (carte_virtuelle, mobile_money, compte_bancaire).
     */
    private function _get_payee_available_destination_types($user_id) {
        $types = array();
        $c = $this->db->get_where('carte_utilisateur_application', array(
            'id_utilisateur_application' => $user_id,
            'type_carte' => 'virtuelle',
            'is_active' => 1,
        ))->num_rows();
        if ($c > 0) {
            $types[] = 'carte_virtuelle';
        }
        $c = $this->db->get_where('compte_mobile_money_utilisateur_application', array(
            'id_utilisateur_application' => $user_id,
            'is_active' => 1,
        ))->num_rows();
        if ($c > 0) {
            $types[] = 'mobile_money';
        }
        $c = $this->db->get_where('compte_bancaire_utilisateur_application', array(
            'id_utilisateur_application' => $user_id,
            'is_active' => 1,
        ))->num_rows();
        if ($c > 0) {
            $types[] = 'compte_bancaire';
        }
        return $types;
    }

    /**
     * Lookup destinataire par token (QR) ou par téléphone (contact) + type de réception.
     * POST /api/app/payee/lookup — Body: token OU phone, destination_type (carte_virtuelle|mobile_money|compte_bancaire)
     * Réponse: found, has_requested_destination, available_destination_types, display_name, destination_label
     */
    public function payee_lookup() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $this->require_auth();
        $json = json_decode(file_get_contents('php://input'), true);
        $token = isset($json['token']) ? trim((string) $json['token']) : '';
        $phone = isset($json['phone']) ? normalise_phone_ripa(trim((string) $json['phone'])) : '';
        $destination_type = isset($json['destination_type']) ? trim((string) $json['destination_type']) : '';

        $valid_dest = array('carte_virtuelle', 'mobile_money', 'compte_bancaire');
        if (!in_array($destination_type, $valid_dest, true)) {
            $this->response_format->send_error('destination_type invalide (carte_virtuelle, mobile_money, compte_bancaire)', 400);
        }
        if ($token !== '') {
            $token = trim($token);
            if (strpos($token, 'ripa://p/') === 0) {
                $token = substr($token, strlen('ripa://p/'));
            }
            $token = preg_replace('/[#?].*$/', '', $token);
            $token = trim($token);
        }
        if ($token === '' && $phone === '') {
            $this->response_format->send_error('Indiquez token (QR) ou phone (contact)', 400);
        }
        if ($token !== '' && $phone !== '') {
            $this->response_format->send_error('Indiquez soit token soit phone, pas les deux', 400);
        }

        $payee_user_id = null;
        if ($token !== '') {
            $row = $this->db->get_where('ripa_payee_token', array('token' => $token))->row_array();
            if ($row && (empty($row['date_expiration']) || strtotime($row['date_expiration']) > time())) {
                $payee_user_id = (int) $row['id_utilisateur_application'];
            }
        } else {
            $phone_hash = hash('sha256', $phone);
            $user = $this->User_model->get_user_by_phone_hash($phone_hash);
            if ($user) {
                $payee_user_id = (int) $user['id_utilisateur_application'];
            }
        }

        if ($payee_user_id === null) {
            $this->response_format->send_success(array(
                'found' => false,
                'message' => 'Destinataire non inscrit sur RIPA ou token expiré.',
            ), 'Lookup payee');
            return;
        }

        $available = $this->_get_payee_available_destination_types($payee_user_id);
        $has_requested = in_array($destination_type, $available, true);

        $display_name = '';
        $payee = $this->User_model->get_user_by_id($payee_user_id);
        if ($payee) {
            $nom = decrypt_ripa($payee['nom_c']);
            $post = decrypt_ripa($payee['post_nom_c']);
            $prenom = decrypt_ripa($payee['prenom_c']);
            $display_name = trim($prenom . ' ' . $nom . ' ' . $post);
            if ($display_name === '') {
                $display_name = 'RIPA ****' . substr($payee['phone_hash'] ?? '', -4);
            }
        }

        $destination_label = '';
        if ($has_requested) {
            if ($destination_type === 'carte_virtuelle') {
                $destination_label = 'Carte virtuelle RIPA';
            } elseif ($destination_type === 'mobile_money') {
                $destination_label = 'Mobile Money';
            } else {
                $destination_label = 'Compte bancaire';
            }
        }

        $this->response_format->send_success(array(
            'found' => true,
            'has_requested_destination' => $has_requested,
            'available_destination_types' => $available,
            'display_name' => $display_name,
            'destination_label' => $destination_label,
        ), 'Lookup payee');
    }

    /**
     * Soumettre un paiement C2C / Scan & Pay.
     * POST /api/app/payment/submit — Body: source_type, source_id, payee_token OU payee_phone, destination_type, amount, pin [, libelle]
     */
    public function payment_submit() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $user = $auth['user'];

        $json = json_decode(file_get_contents('php://input'), true);
        $source_type = isset($json['source_type']) ? trim((string) $json['source_type']) : '';
        $source_id = isset($json['source_id']) ? (int) $json['source_id'] : 0;
        $payee_token = isset($json['payee_token']) ? trim((string) $json['payee_token']) : '';
        $payee_phone = isset($json['payee_phone']) ? normalise_phone_ripa(trim((string) $json['payee_phone'])) : '';
        $destination_type = isset($json['destination_type']) ? trim((string) $json['destination_type']) : '';
        $amount = isset($json['amount']) ? (float) $json['amount'] : 0;
        $pin = isset($json['pin']) ? trim((string) $json['pin']) : '';
        $libelle = isset($json['libelle']) ? trim((string) $json['libelle']) : '';

        if ($payee_token !== '') {
            if (strpos($payee_token, 'ripa://p/') === 0) {
                $payee_token = substr($payee_token, strlen('ripa://p/'));
            }
            $payee_token = preg_replace('/[#?].*$/', '', $payee_token);
            $payee_token = trim($payee_token);
        }

        $valid_source = array('carte_virtuelle', 'carte_physique', 'mobile_money', 'compte_bancaire');
        $valid_dest = array('carte_virtuelle', 'mobile_money', 'compte_bancaire');
        if (!in_array($source_type, $valid_source, true) || !in_array($destination_type, $valid_dest, true)) {
            $this->response_format->send_error('source_type ou destination_type invalide', 400);
        }
        if ($source_id <= 0) {
            $this->response_format->send_error('source_id requis', 400);
        }
        if ($payee_token === '' && $payee_phone === '') {
            $this->response_format->send_error('Indiquez payee_token (QR) ou payee_phone', 400);
        }
        if ($amount <= 0 || $amount > 999999) {
            $this->response_format->send_error('Montant invalide (0.01 à 999999)', 400);
        }
        if (strlen($pin) !== 5 || !preg_match('/^[0-9]{5}$/', $pin)) {
            $this->response_format->send_error('PIN invalide (5 chiffres)', 400);
        }
        if (!password_verify($pin, $user['mot_passe_pin'])) {
            $this->response_format->send_error('PIN incorrect', 401);
        }

        $payee_user_id = null;
        if ($payee_token !== '') {
            $row = $this->db->get_where('ripa_payee_token', array('token' => $payee_token))->row_array();
            if ($row && (empty($row['date_expiration']) || strtotime($row['date_expiration']) > time())) {
                $payee_user_id = (int) $row['id_utilisateur_application'];
            }
        } else {
            $phone_hash = hash('sha256', $payee_phone);
            $u = $this->User_model->get_user_by_phone_hash($phone_hash);
            if ($u) {
                $payee_user_id = (int) $u['id_utilisateur_application'];
            }
        }
        if ($payee_user_id === null) {
            $this->response_format->send_error('Destinataire non trouvé ou token expiré', 404);
        }
        if ($payee_user_id === $user_id) {
            $this->response_format->send_error('Vous ne pouvez pas vous envoyer un paiement à vous-même', 400);
        }

        $available_dest = $this->_get_payee_available_destination_types($payee_user_id);
        if (!in_array($destination_type, $available_dest, true)) {
            $this->response_format->send_error('Ce destinataire ne peut pas recevoir sur ' . $destination_type . '. Moyens disponibles : ' . implode(', ', $available_dest), 400);
        }

        $id_destination = $this->_resolve_destination_id($payee_user_id, $destination_type);
        if ($id_destination === null) {
            $this->response_format->send_error('Compte destination introuvable', 500);
        }

        $source_ok = false;
        $id_source = $source_id;
        if ($source_type === 'carte_virtuelle' || $source_type === 'carte_physique') {
            $row = $this->db->get_where('carte_utilisateur_application', array(
                'id_carte' => $source_id,
                'id_utilisateur_application' => $user_id,
                'is_active' => 1,
            ))->row_array();
            if ($row) {
                $source_ok = true;
                if ($source_type === 'carte_virtuelle') {
                    $balance = 0;
                    if (!empty($row['solde_c'])) {
                        $dec = decrypt_ripa($row['solde_c']);
                        $balance = (is_numeric($dec) || $dec === '') ? (float) $dec : 0;
                    }
                    if ($balance < $amount) {
                        $this->response_format->send_error('Solde carte virtuelle insuffisant', 400);
                    }
                }
            }
        } elseif ($source_type === 'mobile_money') {
            $row = $this->db->get_where('compte_mobile_money_utilisateur_application', array(
                'id_compte_mobile_money' => $source_id,
                'id_utilisateur_application' => $user_id,
                'is_active' => 1,
            ))->row_array();
            if ($row) {
                $source_ok = true;
            }
        } else {
            $row = $this->db->get_where('compte_bancaire_utilisateur_application', array(
                'id_compte_bancaire' => $source_id,
                'id_utilisateur_application' => $user_id,
                'is_active' => 1,
            ))->row_array();
            if ($row) {
                $source_ok = true;
            }
        }
        if (!$source_ok) {
            $this->response_format->send_error('Compte source introuvable ou inactif', 404);
        }

        $reference = 'RIPA' . date('YmdHis') . str_pad((string) rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $montant_c = encrypt_ripa((string) $amount);
        $reference_c = encrypt_ripa($reference);
        $libelle_c = $libelle !== '' ? encrypt_ripa($libelle) : null;

        $this->db->insert('transaction_paiement_ripa', array(
            'id_emetteur' => $user_id,
            'type_source' => $source_type,
            'id_source' => $id_source,
            'id_destinataire' => $payee_user_id,
            'type_destination' => $destination_type,
            'id_destination' => $id_destination,
            'montant_c' => $montant_c,
            'reference_c' => $reference_c,
            'libelle_c' => $libelle_c,
            'statut' => 'traite',
        ));
        $id_paiement = $this->db->insert_id();

        if ($source_type === 'carte_virtuelle') {
            $current = $this->db->get_where('carte_utilisateur_application', array('id_carte' => $source_id))->row_array();
            $bal = 0;
            if (!empty($current['solde_c'])) {
                $dec = decrypt_ripa($current['solde_c']);
                $bal = (is_numeric($dec) || $dec === '') ? (float) $dec : 0;
            }
            $new_bal = max(0, $bal - $amount);
            $this->db->where('id_carte', $source_id);
            $this->db->update('carte_utilisateur_application', array('solde_c' => encrypt_ripa((string) $new_bal)));
        }

        if ($destination_type === 'carte_virtuelle' && $id_destination > 0) {
            $dest_card = $this->db->get_where('carte_utilisateur_application', array('id_carte' => $id_destination))->row_array();
            if (!empty($dest_card) && (int) $dest_card['id_utilisateur_application'] === $payee_user_id && !empty($dest_card['is_active'])) {
                $bal = 0;
                if (!empty($dest_card['solde_c'])) {
                    $dec = decrypt_ripa($dest_card['solde_c']);
                    $bal = (is_numeric($dec) || $dec === '') ? (float) $dec : 0;
                }
                $new_bal = $bal + $amount;
                $this->db->where('id_carte', $id_destination);
                $this->db->update('carte_utilisateur_application', array('solde_c' => encrypt_ripa((string) $new_bal)));
            }
        }

        $this->_log_app('paiement_c2c', 'transaction_paiement_ripa', $id_paiement, array(
            'id_paiement' => $id_paiement,
            'amount' => $amount,
            'destination_type' => $destination_type,
        ), $user_id);
        $this->_log_api('ripa_app', 'payment_submit', 200, null, 'OK', $reference);

        $emetteur_name = trim(
            (decrypt_ripa($user['prenom_c']) ?: '') . ' ' .
            (decrypt_ripa($user['nom_c']) ?: '') . ' ' .
            (decrypt_ripa($user['post_nom_c']) ?: '')
        );
        $this->Notification_model->add(
            $payee_user_id,
            'paiement_recu',
            'Paiement reçu',
            'Vous avez reçu ' . number_format($amount, 0, ',', ' ') . ' $ de ' . ($emetteur_name ?: 'RIPA') . '.'
        );

        $this->response_format->send_success(array(
            'id_paiement' => (int) $id_paiement,
            'reference' => $reference,
            'amount' => $amount,
            'statut' => 'traite',
        ), 'Paiement effectué');
    }

    /**
     * Retourne l'id du compte destination (id_carte, id_compte_mobile_money ou id_compte_bancaire) pour le destinataire.
     */
    private function _resolve_destination_id($payee_user_id, $destination_type) {
        if ($destination_type === 'carte_virtuelle') {
            $row = $this->db->get_where('carte_utilisateur_application', array(
                'id_utilisateur_application' => $payee_user_id,
                'type_carte' => 'virtuelle',
                'is_active' => 1,
            ))->row_array();
            return $row ? (int) $row['id_carte'] : null;
        }
        if ($destination_type === 'mobile_money') {
            $row = $this->db->get_where('compte_mobile_money_utilisateur_application', array(
                'id_utilisateur_application' => $payee_user_id,
                'is_active' => 1,
            ))->row_array();
            return $row ? (int) $row['id_compte_mobile_money'] : null;
        }
        if ($destination_type === 'compte_bancaire') {
            $row = $this->db->get_where('compte_bancaire_utilisateur_application', array(
                'id_utilisateur_application' => $payee_user_id,
                'is_active' => 1,
            ))->row_array();
            return $row ? (int) $row['id_compte_bancaire'] : null;
        }
        return null;
    }

    /**
     * GET /api/app/transactions/recent-contacts — 20 contacts récents (C2C) pour la section Contacts récents.
     * Retourne pour chaque contact : contact_id, display_name, last_date, last_amount, direction (sent|received).
     */
    public function transactions_recent_contacts() {
        if ($this->input->method() !== 'get') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $limit = min(20, max(5, (int) $this->input->get('limit') ?: 20));

        $this->db->select('id_paiement, id_emetteur, id_destinataire, date_creation, montant_c');
        $this->db->group_start();
        $this->db->where('id_emetteur', $user_id);
        $this->db->or_where('id_destinataire', $user_id);
        $this->db->group_end();
        $this->db->where('statut', 'traite');
        $this->db->order_by('date_creation', 'DESC');
        $this->db->limit(200);
        $rows = $this->db->get('transaction_paiement_ripa')->result_array();

        $contacts = array();
        $seen = array();
        foreach ($rows as $r) {
            $other_id = (int) $r['id_emetteur'] === (int) $user_id ? (int) $r['id_destinataire'] : (int) $r['id_emetteur'];
            if ($other_id === (int) $user_id || isset($seen[$other_id])) {
                continue;
            }
            $seen[$other_id] = true;
            if (count($contacts) >= $limit) {
                break;
            }
            $amount = 0;
            if (!empty($r['montant_c'])) {
                $dec = decrypt_ripa($r['montant_c']);
                $amount = (is_numeric($dec) || $dec === '') ? (float) $dec : 0;
            }
            $direction = (int) $r['id_emetteur'] === (int) $user_id ? 'sent' : 'received';
            $other = $this->User_model->get_user_by_id($other_id);
            $display_name = 'RIPA';
            if ($other) {
                $n = trim(decrypt_ripa($other['nom_c']) . ' ' . decrypt_ripa($other['post_nom_c']));
                $p = trim(decrypt_ripa($other['prenom_c']));
                $display_name = $p ? $p . ' ' . $n : ($n ?: 'RIPA');
            }
            $contacts[] = array(
                'contact_id' => $other_id,
                'display_name' => $display_name,
                'last_date' => $r['date_creation'],
                'last_amount' => $amount,
                'direction' => $direction,
            );
        }
        $this->response_format->send_success(array('contacts' => $contacts), 'Contacts récents');
    }

    /**
     * GET /api/app/transactions/with-contact?contact_id= — Transactions C2C avec un contact (entrantes + sortantes).
     * Montants déchiffrés pour affichage.
     */
    public function transactions_with_contact() {
        if ($this->input->method() !== 'get') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $contact_id = (int) $this->input->get('contact_id');
        if ($contact_id <= 0) {
            $this->response_format->send_error('contact_id requis', 400);
        }

        $this->db->group_start();
        $this->db->where('id_emetteur', $user_id);
        $this->db->where('id_destinataire', $contact_id);
        $this->db->or_group_start();
        $this->db->where('id_emetteur', $contact_id);
        $this->db->where('id_destinataire', $user_id);
        $this->db->group_end();
        $this->db->group_end();
        $this->db->where('statut', 'traite');
        $this->db->order_by('date_creation', 'DESC');
        $rows = $this->db->get('transaction_paiement_ripa')->result_array();

        $list = array();
        $contact = $this->User_model->get_user_by_id($contact_id);
        $contact_name = $contact ? trim(decrypt_ripa($contact['prenom_c']) . ' ' . decrypt_ripa($contact['nom_c']) . ' ' . decrypt_ripa($contact['post_nom_c'])) : 'Contact';
        foreach ($rows as $r) {
            $amount = 0;
            if (!empty($r['montant_c'])) {
                $dec = decrypt_ripa($r['montant_c']);
                $amount = (is_numeric($dec) || $dec === '') ? (float) $dec : 0;
            }
            $ref = !empty($r['reference_c']) ? decrypt_ripa($r['reference_c']) : '';
            $libelle = !empty($r['libelle_c']) ? decrypt_ripa($r['libelle_c']) : '';
            $is_sent = (int) $r['id_emetteur'] === (int) $user_id;
            $list[] = array(
                'id' => (int) $r['id_paiement'],
                'type' => 'paiement_c2c',
                'direction' => $is_sent ? 'sent' : 'received',
                'amount' => $amount,
                'reference' => $ref,
                'libelle' => $libelle,
                'date' => $r['date_creation'],
                'contact_id' => $contact_id,
                'contact_name' => $contact_name,
            );
        }
        $this->response_format->send_success(array('transactions' => $list, 'contact_name' => $contact_name), 'Transactions avec contact');
    }

    /**
     * GET /api/app/transactions/history — Historique unifié avec filtres (type, date_from, date_to, limit, offset).
     * type = all|sent|received, date_from/date_to = Y-m-d. Inclut C2C + recharge/retrait carte.
     */
    public function transactions_history() {
        if ($this->input->method() !== 'get') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }
        $auth = $this->require_auth();
        $user_id = $auth['user_id'];
        $type = $this->input->get('type') ?: 'all';
        if (!in_array($type, array('all', 'sent', 'received'), true)) {
            $type = 'all';
        }
        $date_from = ripa_validate_date_ymd($this->input->get('date_from'));
        $date_to = ripa_validate_date_ymd($this->input->get('date_to'));
        $limit = min(100, max(10, (int) $this->input->get('limit') ?: 30));
        $offset = max(0, (int) $this->input->get('offset'));

        $list = array();

        if ($type === 'all' || $type === 'received' || $type === 'sent') {
            $this->db->where('statut', 'traite');
            $this->db->group_start();
            $this->db->where('id_emetteur', $user_id);
            $this->db->or_where('id_destinataire', $user_id);
            $this->db->group_end();
            if ($date_from) {
                $this->db->where('date_creation >=', $date_from . ' 00:00:00');
            }
            if ($date_to) {
                $this->db->where('date_creation <=', $date_to . ' 23:59:59');
            }
            $this->db->order_by('date_creation', 'DESC');
            $this->db->limit($limit);
            $this->db->offset($offset);
            $rows = $this->db->get('transaction_paiement_ripa')->result_array();
            foreach ($rows as $r) {
                $amount = 0;
                if (!empty($r['montant_c'])) {
                    $dec = decrypt_ripa($r['montant_c']);
                    $amount = (is_numeric($dec) || $dec === '') ? (float) $dec : 0;
                }
                $is_sent = (int) $r['id_emetteur'] === (int) $user_id;
                if ($type === 'sent' && !$is_sent) continue;
                if ($type === 'received' && $is_sent) continue;
                $other_id = $is_sent ? (int) $r['id_destinataire'] : (int) $r['id_emetteur'];
                $other = $this->User_model->get_user_by_id($other_id);
                $other_name = $other ? trim(decrypt_ripa($other['prenom_c']) . ' ' . decrypt_ripa($other['nom_c'])) : 'RIPA';
                $list[] = array(
                    'id' => 'p2c_' . (int) $r['id_paiement'],
                    'type' => 'paiement_c2c',
                    'direction' => $is_sent ? 'sent' : 'received',
                    'label' => $is_sent ? 'Envoyé à ' . $other_name : 'Reçu de ' . $other_name,
                    'amount' => $amount,
                    'date' => $r['date_creation'],
                    'contact_id' => $other_id,
                );
            }
        }

        if ($type === 'all') {
            $this->db->where('id_utilisateur_application', $user_id);
            if ($date_from) {
                $this->db->where('date_creation >=', $date_from . ' 00:00:00');
            }
            if ($date_to) {
                $this->db->where('date_creation <=', $date_to . ' 23:59:59');
            }
            $this->db->order_by('date_creation', 'DESC');
            $this->db->limit($limit);
            $this->db->offset($offset);
            $rows_mm = $this->db->get('transaction_carte_mobile_money_utilisateur')->result_array();
            foreach ($rows_mm as $r) {
                $amount = 0;
                if (!empty($r['montant_c'])) {
                    $dec = decrypt_ripa($r['montant_c']);
                    $amount = (is_numeric($dec) || $dec === '') ? (float) $dec : 0;
                }
                $is_credit = ($r['type_operation'] === 'recharge');
                $list[] = array(
                    'id' => 'card_mm_' . (int) $r['id_transaction_carte_mm'],
                    'type' => $r['type_operation'] === 'recharge' ? 'recharge' : 'retrait',
                    'direction' => $is_credit ? 'received' : 'sent',
                    'label' => $is_credit ? 'Recharge carte' : 'Décharge carte',
                    'amount' => $amount,
                    'date' => $r['date_creation'],
                );
            }
            usort($list, function ($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
            $list = array_slice($list, 0, $limit);
        }

        $this->response_format->send_success(array('transactions' => $list), 'Historique');
    }
}
