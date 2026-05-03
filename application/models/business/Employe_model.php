<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employe_model extends CI_Model {

    protected $table = 'business_employe';

    public function get_by_marchand($id_marchand) {
        $this->db->select('e.*, s.libelle AS service_libelle, r.libelle AS ref_poste_libelle, r.is_autre AS ref_poste_is_autre');
        $this->db->from($this->table . ' e');
        $this->db->join('business_service s', 's.id = e.id_service', 'left');
        $this->db->join('ref_poste_fonction r', 'r.id = e.id_ref_poste_fonction', 'left');
        $this->db->where('e.id_marchand', (int) $id_marchand);
        $this->db->order_by('e.nom', 'ASC');
        return $this->db->get()->result_array();
    }

    /** Derniers employés créés (tableau de bord). */
    public function get_recent_by_marchand($id_marchand, $limit = 5) {
        $this->db->select('e.*, s.libelle AS service_libelle, r.libelle AS ref_poste_libelle, r.is_autre AS ref_poste_is_autre');
        $this->db->from($this->table . ' e');
        $this->db->join('business_service s', 's.id = e.id_service', 'left');
        $this->db->join('ref_poste_fonction r', 'r.id = e.id_ref_poste_fonction', 'left');
        $this->db->where('e.id_marchand', (int) $id_marchand);
        $this->db->order_by('e.created_at', 'DESC');
        $this->db->limit(max(1, min(20, (int) $limit)));
        return $this->db->get()->result_array();
    }

    public function get_by_id($id, $id_marchand) {
        $this->db->select('e.*, s.libelle AS service_libelle, r.libelle AS ref_poste_libelle, r.is_autre AS ref_poste_is_autre');
        $this->db->from($this->table . ' e');
        $this->db->join('business_service s', 's.id = e.id_service', 'left');
        $this->db->join('ref_poste_fonction r', 'r.id = e.id_ref_poste_fonction', 'left');
        $this->db->where('e.id', (int) $id);
        $this->db->where('e.id_marchand', (int) $id_marchand);
        return $this->db->get()->row_array();
    }

    public function insert_row($id_marchand, array $data) {
        $data['id_marchand'] = (int) $id_marchand;
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Insertion groupée (import Excel). Chaque ligne doit contenir les colonnes attendues + cohérent avec la table.
     */
    public function insert_batch_for_marchand($id_marchand, array $rows) {
        if (empty($rows)) {
            return 0;
        }
        $id_m = (int) $id_marchand;
        foreach ($rows as &$r) {
            $r['id_marchand'] = $id_m;
        }
        unset($r);
        $this->db->insert_batch($this->table, $rows);
        return count($rows);
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
