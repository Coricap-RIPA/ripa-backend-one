<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modèle pour les logs des appels API (Vault, Onafriq, etc.).
 * Table : api_logs.
 * Ne jamais y enregistrer PAN, CVV, token complet, ni corps de requête/réponse sensibles.
 */
class Api_log_model extends CI_Model {

    private $table = 'api_logs';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Enregistre un appel API dans api_logs.
     * @param array $data [ service, action, method?, request_id?, status_code?, duration_ms?, message? ]
     * @return int|bool ID du log ou false
     */
    public function insert_log(array $data) {
        $row = array(
            'service' => isset($data['service']) ? (string) $data['service'] : '',
            'action' => isset($data['action']) ? (string) $data['action'] : '',
            'method' => isset($data['method']) ? (string) $data['method'] : null,
            'request_id' => isset($data['request_id']) ? (string) $data['request_id'] : null,
            'status_code' => isset($data['status_code']) ? (int) $data['status_code'] : null,
            'duration_ms' => isset($data['duration_ms']) ? (int) $data['duration_ms'] : null,
            'message' => isset($data['message']) ? (string) $data['message'] : null,
        );
        $this->db->insert($this->table, $row);
        return $this->db->insert_id();
    }
}
