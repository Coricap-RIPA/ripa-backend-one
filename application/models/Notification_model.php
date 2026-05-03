<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modèle Notifications – app mobile (KYC validé/rejeté/supprimé, etc.)
 */
class Notification_model extends CI_Model {

    private $table = 'notification_utilisateur_application';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Ajoute une notification pour un utilisateur
     * @param int $user_id
     * @param string $type ex: kyc_valide, kyc_rejete, kyc_supprime
     * @param string $titre
     * @param string $message
     * @return int id_notification
     */
    public function add($user_id, $type, $titre, $message) {
        $this->db->insert($this->table, array(
            'id_utilisateur_application' => (int) $user_id,
            'type' => $type,
            'titre' => $titre,
            'message' => $message,
            'lu' => 0,
        ));
        return (int) $this->db->insert_id();
    }

    /**
     * Liste des notifications pour un utilisateur (plus récentes en premier)
     * @param int $user_id
     * @param bool $unread_only
     * @param int $limit
     * @return array
     */
    public function get_by_user($user_id, $unread_only = false, $limit = 50) {
        $this->db->where('id_utilisateur_application', (int) $user_id);
        if ($unread_only) {
            $this->db->where('lu', 0);
        }
        $this->db->order_by('date_creation', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    /**
     * Marquer une ou plusieurs notifications comme lues
     * @param int $user_id
     * @param array|int $id_notifications id ou liste d'ids
     */
    public function mark_read($user_id, $id_notifications) {
        $ids = is_array($id_notifications) ? $id_notifications : array($id_notifications);
        if (empty($ids)) return;
        $this->db->where('id_utilisateur_application', (int) $user_id);
        $this->db->where_in('id_notification', array_map('intval', $ids));
        $this->db->update($this->table, array('lu' => 1));
    }

    /**
     * Marquer toutes les notifications d'un utilisateur comme lues
     */
    public function mark_all_read($user_id) {
        $this->db->where('id_utilisateur_application', (int) $user_id);
        $this->db->update($this->table, array('lu' => 1));
    }

    /**
     * Nombre de notifications non lues pour un utilisateur
     */
    public function count_unread($user_id) {
        $this->db->where('id_utilisateur_application', (int) $user_id);
        $this->db->where('lu', 0);
        return (int) $this->db->count_all_results($this->table);
    }
}
