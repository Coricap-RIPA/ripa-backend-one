<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modèle pour les logs des actions utilisateur application (B2C).
 * Table : log_utilisateur_application.
 * PCI DSS : les détails doivent être passés via sanitize_for_log() avant appel.
 */
class Log_utilisateur_application_model extends CI_Model {

    private $table = 'log_utilisateur_application';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Enregistre une action dans log_utilisateur_application.
     * @param array $data [ action, ressource?, id_ressource?, details? (sanitized), id_utilisateur_application?, ip_address?, user_agent? ]
     * @return int|bool ID du log ou false
     */
    public function insert_log(array $data) {
        $row = array(
            'id_utilisateur_application' => isset($data['id_utilisateur_application']) ? (int) $data['id_utilisateur_application'] : null,
            'action' => isset($data['action']) ? (string) $data['action'] : '',
            'ressource' => isset($data['ressource']) ? (string) $data['ressource'] : null,
            'id_ressource' => isset($data['id_ressource']) ? (int) $data['id_ressource'] : null,
            'details' => isset($data['details']) ? (is_array($data['details']) ? json_encode($data['details']) : (string) $data['details']) : null,
            'ip_address' => isset($data['ip_address']) ? (string) $data['ip_address'] : null,
            'user_agent' => isset($data['user_agent']) ? (string) $data['user_agent'] : null,
        );
        $this->db->insert($this->table, $row);
        return $this->db->insert_id();
    }
}
