<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employe_model extends CI_Model {

    protected $table = 'business_employe';

    public function get_by_marchand($id_marchand) {
        $this->db->where('id_marchand', (int) $id_marchand);
        $this->db->order_by('nom', 'ASC');
        return $this->db->get($this->table)->result_array();
    }

    /** Derniers employés créés (tableau de bord). */
    public function get_recent_by_marchand($id_marchand, $limit = 5) {
        $this->db->where('id_marchand', (int) $id_marchand);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(max(1, min(20, (int) $limit)));
        return $this->db->get($this->table)->result_array();
    }

    public function get_by_id($id, $id_marchand) {
        return $this->db->get_where($this->table, array(
            'id' => (int) $id,
            'id_marchand' => (int) $id_marchand,
        ))->row_array();
    }

    public function insert_row($id_marchand, array $data) {
        $data['id_marchand'] = (int) $id_marchand;
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_row($id, $id_marchand, array $data) {
        $this->db->where('id', (int) $id);
        $this->db->where('id_marchand', (int) $id_marchand);
        return $this->db->update($this->table, $data);
    }

    public function delete_row($id, $id_marchand) {
        $this->db->where('id', (int) $id);
        $this->db->where('id_marchand', (int) $id_marchand);
        return $this->db->delete($this->table);
    }

    /** Nombre d’employés (option : uniquement actifs). */
    public function count_by_marchand($id_marchand, $actifs_only = false) {
        $this->db->where('id_marchand', (int) $id_marchand);
        if ($actifs_only) {
            $this->db->where('actif', 1);
        }
        return (int) $this->db->count_all_results($this->table);
    }
}
