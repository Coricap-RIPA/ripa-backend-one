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

    public function __construct() {
        parent::__construct();

        // Librairies (JWT pour l'auth, format des réponses)
        $this->load->library('JWT_Library');
        $this->load->library('Response_format');

        // Modèles
        $this->load->model('User_model');
        $this->load->model('Kyc_model');
        $this->load->model('Notification_model');

        // Helper personnalisé (chiffrement, sanitize log, détection type carte / mobile money)
        $this->load->helper('custom_helper');

        // CORS : autoriser les requêtes depuis l'app React Native / Expo
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }
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
            $this->response_format->send_error('Erreur lors de la création du compte', 500);
        }

        $otp_code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $this->User_model->save_otp($user_id, $otp_code);
        $user = $this->User_model->get_user_by_id($user_id);

        $nom_complet = $nom . ' ' . $post_nom . ' ' . $prenom;
        $response = array(
            'user' => array(
                'id' => (int) $user['id_utilisateur_application'],
                'nom_complet' => $nom_complet,
                'nom' => $nom,
                'post_nom' => $post_nom,
                'prenom' => $prenom,
                'phone' => $user['phone'],
            ),
            'otp_code' => $otp_code,
            'message' => 'Inscription réussie. Un code OTP a été envoyé à votre numéro.',
        );
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
        $nom_complet = '';
        if (!empty($user['nom_c']) || !empty($user['post_nom_c']) || !empty($user['prenom_c'])) {
            $nom_complet = trim(
                decrypt_ripa(isset($user['nom_c']) ? $user['nom_c'] : '') . ' ' .
                decrypt_ripa(isset($user['post_nom_c']) ? $user['post_nom_c'] : '') . ' ' .
                decrypt_ripa(isset($user['prenom_c']) ? $user['prenom_c'] : '')
            );
        }
        if ($nom_complet === '' && !empty($user['nom_complet'])) {
            $nom_complet = $user['nom_complet'];
        }
        return array(
            'id' => (int) $user['id_utilisateur_application'],
            'nom_complet' => $nom_complet,
            'phone' => isset($user['phone']) ? $user['phone'] : '',
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

        $last4 = substr($pan, -4);
        $brand = (substr($pan, 0, 1) === '4') ? 'Visa' : 'Mastercard';
        $token_vault = 'mock_token_' . bin2hex(random_bytes(16));

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

        $account = $this->db->get_where('compte_mobile_money_utilisateur_application', array(
            'id_compte_mobile_money' => $account_id,
            'id_utilisateur_application' => $user_id,
            'is_active' => 1,
        ))->row_array();
        if (empty($account)) {
            $this->response_format->send_error('Compte Mobile Money introuvable', 404);
        }

        $current_balance = 0;
        if (!empty($card['solde_c'])) {
            $dec = decrypt_ripa($card['solde_c']);
            $current_balance = (is_numeric($dec) || $dec === '') ? (float) $dec : 0;
        }
        $new_balance = $current_balance + $amount;
        $solde_c = encrypt_ripa((string) $new_balance);

        $this->db->where('id_carte', $card_id);
        $this->db->update('carte_utilisateur_application', array('solde_c' => $solde_c));

        $ref_onafriq = 'ONAFRIQ-R-' . strtoupper(bin2hex(random_bytes(8)));
        $montant_c = encrypt_ripa((string) $amount);
        $reference_c = encrypt_ripa($ref_onafriq);

        $this->db->insert('transaction_carte_mobile_money_utilisateur', array(
            'id_utilisateur_application' => $user_id,
            'id_carte' => $card_id,
            'id_compte_mobile_money' => $account_id,
            'type_operation' => 'recharge',
            'montant_c' => $montant_c,
            'reference_onafriq_c' => $reference_c,
        ));
        $tx_id = $this->db->insert_id();

        $this->response_format->send_success(array(
            'transaction_id' => (int) $tx_id,
            'amount' => $amount,
            'balance' => $new_balance,
            'reference' => $ref_onafriq,
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

        $account = $this->db->get_where('compte_mobile_money_utilisateur_application', array(
            'id_compte_mobile_money' => $account_id,
            'id_utilisateur_application' => $user_id,
            'is_active' => 1,
        ))->row_array();
        if (empty($account)) {
            $this->response_format->send_error('Compte Mobile Money introuvable', 404);
        }

        $current_balance = 0;
        if (!empty($card['solde_c'])) {
            $dec = decrypt_ripa($card['solde_c']);
            $current_balance = (is_numeric($dec) || $dec === '') ? (float) $dec : 0;
        }
        if ($current_balance < $amount) {
            $this->response_format->send_error('Solde insuffisant sur la carte virtuelle. Solde actuel : ' . number_format($current_balance, 2, ',', ' ') . ' $', 400);
        }
        $new_balance = $current_balance - $amount;
        $solde_c = encrypt_ripa((string) $new_balance);

        $this->db->where('id_carte', $card_id);
        $this->db->update('carte_utilisateur_application', array('solde_c' => $solde_c));

        $ref_onafriq = 'ONAFRIQ-W-' . strtoupper(bin2hex(random_bytes(8)));
        $montant_c = encrypt_ripa((string) $amount);
        $reference_c = encrypt_ripa($ref_onafriq);

        $this->db->insert('transaction_carte_mobile_money_utilisateur', array(
            'id_utilisateur_application' => $user_id,
            'id_carte' => $card_id,
            'id_compte_mobile_money' => $account_id,
            'type_operation' => 'retrait',
            'montant_c' => $montant_c,
            'reference_onafriq_c' => $reference_c,
        ));
        $tx_id = $this->db->insert_id();

        $this->response_format->send_success(array(
            'transaction_id' => (int) $tx_id,
            'amount' => $amount,
            'balance' => $new_balance,
            'reference' => $ref_onafriq,
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
            $is_credit = ($type_op === 'recharge'); // recharge = MM -> carte = débit MM, crédit carte (affichage: entrée pour la carte)
            $list[] = array(
                'id' => 'card_mm_' . (int) $r['id_transaction_carte_mm'],
                'type' => $type_op === 'recharge' ? 'recharge' : 'retrait',
                'label' => $type_op === 'recharge' ? 'Recharge carte' : 'Décharge carte',
                'amount' => $amount,
                'date' => isset($r['date_creation']) ? date('d/m/Y H:i', strtotime($r['date_creation'])) : '',
                'source' => 'card_mm',
                'is_credit' => $is_credit,
            );
        }

        // TODO: fusionner avec paiements, réceptions, transferts (tables à créer)
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
}
