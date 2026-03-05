<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Librairie JWT pour l'authentification
 * Gère l'encodage et le décodage des tokens JWT
 */
class JWT_Library {

    private $secret_key;
    private $algorithm = 'HS256';

    public function __construct() {
        // Clé lue depuis .env (RIPA_JWT_SECRET_KEY) ou config, jamais en dur en prod
        $this->secret_key = getenv('RIPA_JWT_SECRET_KEY');
        if ($this->secret_key === false || $this->secret_key === '') {
            $CI = &get_instance();
            $this->secret_key = $CI->config->item('ripa_jwt_secret_key');
        }
        if (empty($this->secret_key)) {
            $this->secret_key = '22-08-2025'; // fallback dev uniquement
        }
        // Vérifier que l'extension OpenSSL est chargée
        if (!extension_loaded('openssl')) {
            show_error('L\'extension OpenSSL est requise pour JWT');
        }
    }

    /**
     * Encoder un payload en token JWT
     * @param array $payload
     * @return string
     */
    public function encode($payload) {
        // Header
        $header = array(
            'typ' => 'JWT',
            'alg' => $this->algorithm
        );

        // Encoder en base64
        $header_encoded = $this->base64url_encode(json_encode($header));
        $payload_encoded = $this->base64url_encode(json_encode($payload));

        // Créer la signature
        $signature = $this->sign($header_encoded . '.' . $payload_encoded);
        $signature_encoded = $this->base64url_encode($signature);

        // Assembler le token
        return $header_encoded . '.' . $payload_encoded . '.' . $signature_encoded;
    }

    /**
     * Décoder et vérifier un token JWT
     * @param string $token
     * @return object|bool Payload décodé ou false si invalide
     */
    public function decode($token) {
        // Séparer les parties du token
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return false;
        }

        list($header_encoded, $payload_encoded, $signature_encoded) = $parts;

        // Vérifier la signature
        $signature = $this->base64url_decode($signature_encoded);
        $expected_signature = $this->sign($header_encoded . '.' . $payload_encoded);

        if (!hash_equals($expected_signature, $signature)) {
            return false;
        }

        // Décoder le payload
        $payload = json_decode($this->base64url_decode($payload_encoded));

        if (!$payload) {
            return false;
        }

        // Vérifier l'expiration
        if (isset($payload->exp) && $payload->exp < time()) {
            return false;
        }

        return $payload;
    }

    /**
     * Créer une signature HMAC
     * @param string $data
     * @return string
     */
    private function sign($data) {
        return hash_hmac('sha256', $data, $this->secret_key, true);
    }

    /**
     * Encoder en base64 URL-safe
     * @param string $data
     * @return string
     */
    private function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Décoder depuis base64 URL-safe
     * @param string $data
     * @return string
     */
    private function base64url_decode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     * Extraire le token depuis le header Authorization
     * @param CI_Controller $CI Instance de CodeIgniter
     * @return string|null
     */
    public function get_token_from_header($CI) {
        $headers = $CI->input->request_headers();
        
        if (isset($headers['Authorization'])) {
            $auth_header = $headers['Authorization'];
            if (preg_match('/Bearer\s+(.*)$/i', $auth_header, $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }

    /**
     * Vérifier et décoder le token depuis le header
     * @param CI_Controller $CI Instance de CodeIgniter
     * @return object|bool
     */
    public function verify_from_header($CI) {
        $token = $this->get_token_from_header($CI);
        
        if (!$token) {
            return false;
        }
        
        return $this->decode($token);
    }
}

