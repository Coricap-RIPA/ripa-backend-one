<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Contrôleur d'authentification pour l'API RIPA
 * Gère l'inscription, la connexion et la vérification OTP
 */
class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Charger les modèles nécessaires
        $this->load->model('User_model');
        
        // Charger les librairies
        $this->load->library('JWT_Library');
        $this->load->library('Response_format');
        
        // Charger les helpers
        $this->load->helper('string');
        
        // Configuration CORS (pour permettre les requêtes depuis React Native)
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        
        // Gérer les requêtes OPTIONS (preflight)
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }
    }

    /**
     * Inscription d'un nouvel utilisateur
     * POST /api/auth/register
     * 
     * Body (JSON):
     * {
     *   "nom_complet": "John Doe",
     *   "phone": "+243970000000",
     *   "pin": "1234"
     * }
     */
    public function register() {
        // Vérifier que c'est une requête POST
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }

        // Récupérer les données JSON
        $json = json_decode(file_get_contents('php://input'), true);

        // Validation des données
        if (empty($json['nom_complet']) || empty($json['phone']) || empty($json['pin'])) {
            $this->response_format->send_error('Tous les champs sont obligatoires', 400);
        }

        $nom_complet = trim($json['nom_complet']);
        $phone = trim($json['phone']);
        $pin = trim($json['pin']);

        // Validation du nom complet
        if (strlen($nom_complet) < 2) {
            $this->response_format->send_error('Le nom complet doit contenir au moins 2 caractères', 400);
        }

        // Validation du téléphone (format international)
        if (!preg_match('/^\+?[0-9]{10,15}$/', str_replace(' ', '', $phone))) {
            $this->response_format->send_error('Numéro de téléphone invalide', 400);
        }

        // Validation du PIN (4 caractères alphanumériques)
        if (strlen($pin) !== 4 || !preg_match('/^[a-zA-Z0-9]{4}$/', $pin)) {
            $this->response_format->send_error('Le PIN doit contenir exactement 4 caractères alphanumériques', 400);
        }

        // Nettoyer le numéro de téléphone (enlever espaces et caractères spéciaux sauf +)
        $phone_clean = preg_replace('/[^0-9+]/', '', $phone);

        // Vérifier si l'utilisateur existe déjà
        $existing_user = $this->User_model->get_user_by_phone($phone_clean);
        if ($existing_user) {
            $this->response_format->send_error('Ce numéro de téléphone est déjà enregistré', 409);
        }

        // Hasher le PIN
        $pin_hashed = password_hash($pin, PASSWORD_BCRYPT);

        // Créer l'utilisateur
        $user_data = array(
            'nom_complet' => $nom_complet,
            'phone' => $phone_clean,
            'mot_passe_pin' => $pin_hashed,
            'date_enregistrement' => date('Y-m-d')
        );

        $user_id = $this->User_model->create_user($user_data);

        if (!$user_id) {
            $this->response_format->send_error('Erreur lors de la création du compte', 500);
        }

        // Générer un code OTP (4 chiffres)
        $otp_code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        // Sauvegarder le code OTP (valide 10 minutes)
        $this->User_model->save_otp($user_id, $otp_code);

        // TODO: Envoyer le SMS avec le code OTP
        // Pour le développement, on retourne le code OTP dans la réponse
        // En production, il faudra utiliser une API SMS

        // Récupérer l'utilisateur créé
        $user = $this->User_model->get_user_by_id($user_id);

        // Préparer la réponse
        $response = array(
            'user' => array(
                'id' => $user['id_utilisateur_application'],
                'nom_complet' => $user['nom_complet'],
                'phone' => $user['phone']
            ),
            'otp_code' => $otp_code, // À RETIRER EN PRODUCTION !
            'message' => 'Inscription réussie. Un code OTP a été envoyé à votre numéro.'
        );

        $this->response_format->send_success($response, 'Inscription réussie', 201);
    }

    /**
     * Vérification du code OTP
     * POST /api/auth/verify-otp
     * 
     * Body (JSON):
     * {
     *   "user_id": 1,
     *   "otp_code": "1234"
     * }
     */
    public function verify_otp() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }

        $json = json_decode(file_get_contents('php://input'), true);

        if (empty($json['user_id']) || empty($json['otp_code'])) {
            $this->response_format->send_error('ID utilisateur et code OTP requis', 400);
        }

        $user_id = intval($json['user_id']);
        $otp_code = trim($json['otp_code']);

        // Vérifier le code OTP
        $is_valid = $this->User_model->verify_otp($user_id, $otp_code);

        if (!$is_valid) {
            $this->response_format->send_error('Code OTP invalide ou expiré', 400);
        }

        // Marquer le code OTP comme utilisé
        $this->User_model->mark_otp_used($user_id);

        // Récupérer l'utilisateur
        $user = $this->User_model->get_user_by_id($user_id);

        // ============================
        // CREATION DU COMPTE FINANCIER
        // ============================
        // Normaliser le numéro
        $user_phone = isset($user['phone']) ? preg_replace('/[^0-9+]/', '', $user['phone']) : '';

        if (!empty($user_phone)) {
            // Déterminer le type mobile money par préfixe
            $type_id = $this->map_mobile_money_type_id($user_phone);

            // Vérifier si un compte existe déjà pour ce user et ce numéro
            $this->db->where('id_foreign_utilisateur_application', $user_id);
            $this->db->where('num_compte_financier', $user_phone);
            $exists = $this->db->get('compte_financier_utilisateur_application')->row_array();

            if (!$exists) {
                // Passer tout autre compte en non-principal
                $this->db->where('id_foreign_utilisateur_application', $user_id);
                $this->db->update('compte_financier_utilisateur_application', array('is_default' => 0));

                // Créer le compte principal
                $this->db->insert('compte_financier_utilisateur_application', array(
                    'num_compte_financier' => $user_phone,
                    'id_foreign_type_mobile_money' => $type_id,
                    'id_foreign_utilisateur_application' => $user_id,
                    'date_compte_financier_utilisateur_application' => date('Y-m-d'),
                    'is_default' => 1,
                    'is_active' => 1,
                ));
            }
        }

        // Générer le token JWT (valide 2 ans)
        $token_data = array(
            'user_id' => $user['id_utilisateur_application'],
            'phone' => $user['phone'],
            'exp' => time() + (2 * 365 * 24 * 60 * 60) // 2 ans
        );

        $jwt_token = $this->jwt_library->encode($token_data);

        // Préparer la réponse
        $response = array(
            'token' => $jwt_token,
            'user' => array(
                'id' => $user['id_utilisateur_application'],
                'nom_complet' => $user['nom_complet'],
                'phone' => $user['phone']
            )
        );

        $this->response_format->send_success($response, 'Vérification OTP réussie');
    }

    /**
     * Déterminer l'ID type_mobile_money à partir du préfixe du numéro (+243...)
     * Règles:
     *  - +24397/+24398/+24399  => 1 (Airtel)
     *  - +24381/+24382/+24383  => 3 (M-Pesa)
     *  - +24384/+24385/+24389  => 2 (Orange)
     *  - +24390/+24391         => 4 (Africel)
     *  - Autres                => 5 (Compte Bancaire / autre)
     */
    private function map_mobile_money_type_id($phone) {
        // Assurer un format uniforme
        $p = preg_replace('/\s+/', '', $phone);

        // Extraire les 5 premiers caractères après +243
        // Exemple: +24397..., +24381...
        $prefix5 = substr($p, 0, 6); // +24397 -> 6 avec le plus
        $prefix = substr($p, 0, 5);  // +2439

        // Match par groupes
        if (preg_match('/^\+2439(7|8|9)/', $p)) {
            return 1; // Airtel
        }
        if (preg_match('/^\+2438(1|2|3)/', $p)) {
            return 3; // M-Pesa
        }
        if (preg_match('/^\+2438(4|5|9)/', $p)) {
            return 2; // Orange
        }
        if (preg_match('/^\+2439(0|1)/', $p)) {
            return 4; // Africel
        }
        return 5; // Autres (Compte Bancaire)
    }

    /**
     * Renvoyer un nouveau code OTP
     * POST /api/auth/resend-otp
     * Body:
     * {
     *   "user_id": 1
     * }
     */
    public function resend_otp() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }

        $json = json_decode(file_get_contents('php://input'), true);

        if (empty($json['user_id'])) {
            $this->response_format->send_error('ID utilisateur requis', 400);
        }

        $user_id = intval($json['user_id']);

        // Vérifier si l'utilisateur existe
        $user = $this->User_model->get_user_by_id($user_id);
        if (!$user) {
            $this->response_format->send_error('Utilisateur introuvable', 404);
        }

        // Supprimer les OTP non utilisés existants
        $this->db->where('user_id', $user_id);
        $this->db->where('is_used', 0);
        $this->db->delete('otp_codes');

        // Générer un nouveau code OTP
        $otp_code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        // Enregistrer le nouveau code (valide 10 minutes)
        $this->User_model->save_otp($user_id, $otp_code);

        // TODO: Envoyer le SMS avec le code OTP

        $response = array(
            'message' => 'Nouveau code OTP généré et envoyé.',
            'otp_code' => $otp_code // À RETIRER EN PRODUCTION
        );

        $this->response_format->send_success($response, 'OTP renvoyé');
    }

    /**
     * Connexion d'un utilisateur existant
     * POST /api/auth/login
     * 
     * Body (JSON):
     * {
     *   "phone": "+243970000000",
     *   "pin": "1234"
     * }
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
        $pin = trim($json['pin']);

        // Récupérer l'utilisateur par téléphone
        $user = $this->User_model->get_user_by_phone($phone);

        if (!$user) {
            $this->response_format->send_error('Numéro de téléphone ou PIN incorrect', 401);
        }

        // Vérifier le PIN
        if (!password_verify($pin, $user['mot_passe_pin'])) {
            $this->response_format->send_error('Numéro de téléphone ou PIN incorrect', 401);
        }

        // Générer le token JWT (valide 2 ans)
        $token_data = array(
            'user_id' => $user['id_utilisateur_application'],
            'phone' => $user['phone'],
            'exp' => time() + (2 * 365 * 24 * 60 * 60) // 2 ans
        );

        $jwt_token = $this->jwt_library->encode($token_data);

        // Préparer la réponse
        $response = array(
            'token' => $jwt_token,
            'user' => array(
                'id' => $user['id_utilisateur_application'],
                'nom_complet' => $user['nom_complet'],
                'phone' => $user['phone']
            )
        );

        $this->response_format->send_success($response, 'Connexion réussie');
    }

    /**
     * Vérifier si un token est valide
     * GET /api/auth/verify-token
     * 
     * Headers:
     * Authorization: Bearer {token}
     */
    public function verify_token() {
        if ($this->input->method() !== 'get') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }

        // Récupérer le token depuis les headers
        $headers = $this->input->request_headers();
        $token = null;

        if (isset($headers['Authorization'])) {
            $auth_header = $headers['Authorization'];
            if (preg_match('/Bearer\s+(.*)$/i', $auth_header, $matches)) {
                $token = $matches[1];
            }
        }

        if (!$token) {
            $this->response_format->send_error('Token manquant', 401);
        }

        // Vérifier le token
        $decoded = $this->jwt_library->decode($token);

        if (!$decoded) {
            $this->response_format->send_error('Token invalide ou expiré', 401);
        }

        // Récupérer l'utilisateur
        $user = $this->User_model->get_user_by_id($decoded->user_id);

        if (!$user) {
            $this->response_format->send_error('Utilisateur introuvable', 404);
        }

        // Préparer la réponse
        $response = array(
            'valid' => true,
            'user' => array(
                'id' => $user['id_utilisateur_application'],
                'nom_complet' => $user['nom_complet'],
                'phone' => $user['phone']
            )
        );

        $this->response_format->send_success($response, 'Token valide');
    }

    /**
     * Récupération de mot de passe - Étape 1 : Envoi OTP
     * POST /api/auth/forgot-password
     * 
     * Body (JSON):
     * {
     *   "phone": "+243970000000"
     * }
     */
    public function forgot_password() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }

        $json = json_decode(file_get_contents('php://input'), true);

        if (empty($json['phone'])) {
            $this->response_format->send_error('Numéro de téléphone requis', 400);
        }

        $phone = preg_replace('/[^0-9+]/', '', trim($json['phone']));

        // Vérifier si l'utilisateur existe
        $user = $this->User_model->get_user_by_phone($phone);

        if (!$user) {
            $this->response_format->send_error('Aucun compte associé à ce numéro', 404);
        }

        // Générer un code OTP
        $otp_code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        // Sauvegarder le code OTP
        $this->User_model->save_otp($user['id_utilisateur_application'], $otp_code);

        // TODO: Envoyer le SMS
        
        $response = array(
            'user_id' => $user['id_utilisateur_application'],
            'otp_code' => $otp_code, // À RETIRER EN PRODUCTION
            'message' => 'Un code OTP a été envoyé à votre numéro'
        );

        $this->response_format->send_success($response, 'Code OTP envoyé');
    }

    /**
     * Récupération de mot de passe - Étape 2 : Réinitialisation
     * POST /api/auth/reset-password
     * 
     * Body (JSON):
     * {
     *   "user_id": 1,
     *   "otp_code": "1234",
     *   "new_pin": "5678"
     * }
     */
    public function reset_password() {
        if ($this->input->method() !== 'post') {
            $this->response_format->send_error('Méthode non autorisée', 405);
        }

        $json = json_decode(file_get_contents('php://input'), true);

        if (empty($json['user_id']) || empty($json['otp_code']) || empty($json['new_pin'])) {
            $this->response_format->send_error('Tous les champs sont requis', 400);
        }

        $user_id = intval($json['user_id']);
        $otp_code = trim($json['otp_code']);
        $new_pin = trim($json['new_pin']);

        // Validation du nouveau PIN
        if (strlen($new_pin) !== 4 || !preg_match('/^[a-zA-Z0-9]{4}$/', $new_pin)) {
            $this->response_format->send_error('Le PIN doit contenir exactement 4 caractères alphanumériques', 400);
        }

        // Vérifier le code OTP
        $is_valid = $this->User_model->verify_otp($user_id, $otp_code);

        if (!$is_valid) {
            $this->response_format->send_error('Code OTP invalide ou expiré', 400);
        }

        // Hasher le nouveau PIN
        $pin_hashed = password_hash($new_pin, PASSWORD_BCRYPT);

        // Mettre à jour le PIN
        $updated = $this->User_model->update_pin($user_id, $pin_hashed);

        if (!$updated) {
            $this->response_format->send_error('Erreur lors de la mise à jour du PIN', 500);
        }

        // Marquer le code OTP comme utilisé
        $this->User_model->mark_otp_used($user_id);

        $this->response_format->send_success(null, 'Mot de passe réinitialisé avec succès');
    }
}

