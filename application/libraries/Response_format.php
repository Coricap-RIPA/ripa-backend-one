<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Librairie pour formater les réponses API de manière uniforme
 */
class Response_format {

    private $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    /**
     * Envoyer une réponse de succès
     * @param mixed $data Données à retourner
     * @param string $message Message de succès
     * @param int $status_code Code HTTP (200 par défaut)
     */
    public function send_success($data = null, $message = 'Succès', $status_code = 200) {
        $response = array(
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        );

        $this->send_response($response, $status_code);
    }

    /**
     * Envoyer une réponse d'erreur
     * @param string $message Message d'erreur
     * @param int $status_code Code HTTP (400 par défaut)
     * @param array $errors Détails des erreurs (optionnel)
     */
    public function send_error($message = 'Erreur', $status_code = 400, $errors = null) {
        $response = array(
            'success' => false,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        );

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        $this->send_response($response, $status_code);
    }

    /**
     * Envoyer une réponse paginée
     * @param array $data Données paginées
     * @param int $total Total d'éléments
     * @param int $page Page actuelle
     * @param int $per_page Éléments par page
     * @param string $message Message
     */
    public function send_paginated($data, $total, $page, $per_page, $message = 'Succès') {
        $total_pages = ceil($total / $per_page);

        $response = array(
            'success' => true,
            'message' => $message,
            'data' => $data,
            'pagination' => array(
                'total' => $total,
                'page' => $page,
                'per_page' => $per_page,
                'total_pages' => $total_pages,
                'has_more' => $page < $total_pages
            ),
            'timestamp' => date('Y-m-d H:i:s')
        );

        $this->send_response($response, 200);
    }

    /**
     * Envoyer la réponse HTTP
     * @param array $response Réponse à envoyer
     * @param int $status_code Code HTTP
     */
    private function send_response($response, $status_code) {
        // Définir le code de statut HTTP
        $this->CI->output->set_status_header($status_code);

        // Définir le type de contenu
        $this->CI->output->set_content_type('application/json');

        // Envoyer la réponse JSON
        $this->CI->output->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));

        // Arrêter l'exécution
        $this->CI->output->_display();
        exit;
    }

    /**
     * Envoyer une réponse 404
     * @param string $message
     */
    public function send_not_found($message = 'Ressource non trouvée') {
        $this->send_error($message, 404);
    }

    /**
     * Envoyer une réponse 401 (Non autorisé)
     * @param string $message
     */
    public function send_unauthorized($message = 'Non autorisé') {
        $this->send_error($message, 401);
    }

    /**
     * Envoyer une réponse 403 (Interdit)
     * @param string $message
     */
    public function send_forbidden($message = 'Accès interdit') {
        $this->send_error($message, 403);
    }

    /**
     * Envoyer une réponse 500 (Erreur serveur)
     * @param string $message
     */
    public function send_server_error($message = 'Erreur serveur interne') {
        $this->send_error($message, 500);
    }
}

